# Cron Invoice Consolidation Verification

## How Consolidation Works

The cron job **automatically consolidates multiple entries per user into ONE invoice**. Here's how:

### 1. Query Logic (Line 70-82 in `RunStripeInvoiceSchedules.php`)

```php
$rows = Point::query()
    ->select([
        'user_id',
        DB::raw('SUM(amount) as subtotal_amount'),      // ← Sums ALL entries
        DB::raw('SUM(system_fee) as commission_amount'), // ← Sums ALL commissions
    ])
    ->where('type', 'debit')
    ->whereNotNull('user_id')
    ->whereBetween('created_at', [$rangeFrom, $rangeTo])
    ->where('description', 'like', 'Charges paid for rental%')
    ->whereNull('stripe_invoice_schedule_id')
    ->groupBy('user_id')  // ← Groups by user, so ONE row per user
    ->get();
```

**Key Points:**
- `SUM(amount)` = Adds up ALL rental entries for that user
- `SUM(system_fee)` = Adds up ALL commission entries for that user
- `groupBy('user_id')` = Ensures only ONE row per user (even if they have 10 entries)

### 2. Invoice Item Creation (Line 118-126)

```php
$item = StripeInvoiceScheduleItem::firstOrCreate(
    ['schedule_id' => $schedule->id, 'user_id' => $userId], // ← Unique key
    [
        'subtotal_amount' => $subtotal,      // ← Total of ALL entries
        'commission_amount' => $commission,   // ← Total commission
        'total_amount' => $total,
        'status' => 'pending',
    ]
);
```

**Key Points:**
- `firstOrCreate` with `['schedule_id', 'user_id']` = Ensures only ONE invoice item per user per schedule
- If user has 5 entries, they all get summed and ONE invoice is created

### 3. Stripe Invoice (Line 172-184)

```php
$invoice = $stripeService->createFinalizeAndSendInvoice([
    'customer_id' => $user->stripe_customer_id,
    'rental_amount' => $subtotal,      // ← Total of ALL entries
    'commission_amount' => $commission, // ← Total commission
    // ...
]);
```

**Result:** ONE Stripe invoice per user with the total of all their entries.

---

## Verification Queries

### Check Last Cron Runs

```sql
SELECT 
    s.id,
    s.recurring_days,
    s.last_run_at,
    JSON_EXTRACT(s.result_summary, '$.processed_users') AS users_processed,
    JSON_EXTRACT(s.result_summary, '$.sent') AS invoices_sent,
    (SELECT COUNT(DISTINCT user_id) FROM stripe_invoice_schedule_items WHERE schedule_id = s.id) AS unique_users,
    (SELECT COUNT(*) FROM stripe_invoice_schedule_items WHERE schedule_id = s.id) AS invoice_items,
    CASE 
        WHEN (SELECT COUNT(DISTINCT user_id) FROM stripe_invoice_schedule_items WHERE schedule_id = s.id) = 
             (SELECT COUNT(*) FROM stripe_invoice_schedule_items WHERE schedule_id = s.id)
        THEN '✓ CORRECT: One invoice per user'
        ELSE '✗ ERROR: Multiple invoices per user!'
    END AS consolidation_check
FROM stripe_invoice_schedules s
WHERE s.last_run_at IS NOT NULL
ORDER BY s.last_run_at DESC
LIMIT 5;
```

### Verify: Multiple Entries → ONE Invoice

```sql
SELECT 
    si.user_id,
    u.name,
    si.subtotal_amount AS invoice_rental_total,
    si.commission_amount AS invoice_commission_total,
    si.total_amount AS invoice_total,
    -- Count of point entries that were consolidated
    (SELECT COUNT(*) 
     FROM points p 
     WHERE p.stripe_invoice_schedule_id = si.schedule_id 
       AND p.user_id = si.user_id
       AND p.type = 'debit'
       AND p.description LIKE 'Charges paid for rental%') AS point_entries_count,
    -- Sum of those entries (should match invoice)
    (SELECT SUM(p.amount) 
     FROM points p 
     WHERE p.stripe_invoice_schedule_id = si.schedule_id 
       AND p.user_id = si.user_id
       AND p.type = 'debit'
       AND p.description LIKE 'Charges paid for rental%') AS points_rental_sum,
    (SELECT SUM(p.system_fee) 
     FROM points p 
     WHERE p.stripe_invoice_schedule_id = si.schedule_id 
       AND p.user_id = si.user_id
       AND p.type = 'debit'
       AND p.description LIKE 'Charges paid for rental%') AS points_commission_sum
FROM stripe_invoice_schedule_items si
JOIN users u ON u.id = si.user_id
WHERE si.schedule_id = YOUR_SCHEDULE_ID
ORDER BY point_entries_count DESC;
```

**Expected Result:**
- If `point_entries_count > 1`, that means multiple entries were consolidated
- `points_rental_sum` should equal `invoice_rental_total`
- `points_commission_sum` should equal `invoice_commission_total`

---

## Current Issue: Orphaned Points

Some points have `stripe_invoice_schedule_id` set but their schedules were deleted. To clean up:

```sql
-- Option 1: Reset orphaned points (allow them to be invoiced again)
UPDATE points p
SET stripe_invoice_schedule_id = NULL,
    stripe_invoiced_at = NULL
WHERE p.stripe_invoice_schedule_id IS NOT NULL
  AND p.type = 'debit'
  AND p.description LIKE 'Charges paid for rental%'
  AND NOT EXISTS (
      SELECT 1 FROM stripe_invoice_schedules s 
      WHERE s.id = p.stripe_invoice_schedule_id
  );

-- Option 2: Just check which schedules are missing
SELECT DISTINCT stripe_invoice_schedule_id
FROM points
WHERE stripe_invoice_schedule_id IS NOT NULL
  AND NOT EXISTS (
      SELECT 1 FROM stripe_invoice_schedules s 
      WHERE s.id = points.stripe_invoice_schedule_id
  );
```

---

## Summary

✅ **Consolidation is working correctly:**
- Multiple entries per user = ONE invoice
- All entries are summed using `SUM()` in SQL
- `groupBy('user_id')` ensures one row per user
- `firstOrCreate` ensures one invoice item per user per schedule

✅ **To verify:**
- Run the SQL queries above
- Check that `invoice_items_count = unique_users_count`
- Check that users with multiple entries have `point_entries_count > 1` but only ONE invoice item

