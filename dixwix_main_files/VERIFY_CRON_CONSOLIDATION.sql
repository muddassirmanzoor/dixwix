-- ============================================
-- VERIFY CRON RUNS & CONSOLIDATION LOGIC
-- ============================================
-- This query verifies that multiple entries per user = ONE invoice

-- 1. All schedules and their status
SELECT 
    id,
    recurring_days,
    status,
    is_active,
    last_run_at,
    next_run_at,
    JSON_PRETTY(result_summary) AS summary,
    error
FROM stripe_invoice_schedules
ORDER BY id DESC;

-- 2. For each schedule that has run, show consolidation verification
SELECT 
    s.id AS schedule_id,
    s.recurring_days,
    s.last_run_at,
    COUNT(DISTINCT si.user_id) AS unique_users_invoiced,
    COUNT(si.id) AS total_invoice_items,
    -- Verify: invoice_items should equal unique_users (one invoice per user)
    CASE 
        WHEN COUNT(DISTINCT si.user_id) = COUNT(si.id) THEN '✓ CORRECT: One invoice per user'
        ELSE '✗ ERROR: Multiple invoices for same user!'
    END AS consolidation_check,
    SUM(si.subtotal_amount) AS total_rental_sent,
    SUM(si.commission_amount) AS total_commission_sent,
    SUM(si.total_amount) AS total_invoices_sent
FROM stripe_invoice_schedules s
LEFT JOIN stripe_invoice_schedule_items si ON si.schedule_id = s.id
WHERE s.last_run_at IS NOT NULL
GROUP BY s.id, s.recurring_days, s.last_run_at
ORDER BY s.last_run_at DESC;

-- 3. DETAILED: Show users with multiple point entries → ONE invoice (consolidation proof)
SELECT 
    si.schedule_id,
    si.user_id,
    u.name AS user_name,
    u.email,
    -- Invoice totals (ONE invoice per user)
    si.subtotal_amount AS invoice_rental_total,
    si.commission_amount AS invoice_commission_total,
    si.total_amount AS invoice_total,
    si.stripe_invoice_id,
    si.status AS invoice_status,
    -- Count of point entries that were consolidated
    (SELECT COUNT(*) 
     FROM points p 
     WHERE p.stripe_invoice_schedule_id = si.schedule_id 
       AND p.user_id = si.user_id
       AND p.type = 'debit'
       AND p.description LIKE 'Charges paid for rental%') AS point_entries_count,
    -- Sum of those point entries (should match invoice totals)
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
       AND p.description LIKE 'Charges paid for rental%') AS points_commission_sum,
    -- Verification: Do they match?
    CASE 
        WHEN ABS((SELECT SUM(p.amount) FROM points p WHERE p.stripe_invoice_schedule_id = si.schedule_id AND p.user_id = si.user_id AND p.type = 'debit' AND p.description LIKE 'Charges paid for rental%') - si.subtotal_amount) < 0.01 
         AND ABS((SELECT SUM(p.system_fee) FROM points p WHERE p.stripe_invoice_schedule_id = si.schedule_id AND p.user_id = si.user_id AND p.type = 'debit' AND p.description LIKE 'Charges paid for rental%') - si.commission_amount) < 0.01
        THEN '✓ MATCHES'
        ELSE '✗ MISMATCH'
    END AS verification
FROM stripe_invoice_schedule_items si
JOIN users u ON u.id = si.user_id
WHERE si.schedule_id IN (
    SELECT id FROM stripe_invoice_schedules WHERE last_run_at IS NOT NULL
)
ORDER BY si.schedule_id DESC, point_entries_count DESC;

-- 4. Find orphaned points (points with schedule_id but no invoice item)
SELECT 
    p.stripe_invoice_schedule_id AS schedule_id,
    p.user_id,
    COUNT(*) AS point_count,
    SUM(p.amount) AS total_rental,
    SUM(p.system_fee) AS total_commission,
    (SELECT COUNT(*) FROM stripe_invoice_schedule_items si 
     WHERE si.schedule_id = p.stripe_invoice_schedule_id 
       AND si.user_id = p.user_id) AS invoice_items_count,
    CASE 
        WHEN (SELECT COUNT(*) FROM stripe_invoice_schedule_items si 
              WHERE si.schedule_id = p.stripe_invoice_schedule_id 
                AND si.user_id = p.user_id) = 0 
        THEN '⚠️ ORPHANED: Points marked but no invoice created!'
        ELSE '✓ OK'
    END AS status
FROM points p
WHERE p.stripe_invoice_schedule_id IS NOT NULL
  AND p.type = 'debit'
  AND p.description LIKE 'Charges paid for rental%'
GROUP BY p.stripe_invoice_schedule_id, p.user_id
HAVING invoice_items_count = 0
ORDER BY p.stripe_invoice_schedule_id DESC;

-- 5. Quick summary: Last 5 runs
SELECT 
    s.id,
    s.recurring_days,
    s.last_run_at,
    JSON_EXTRACT(s.result_summary, '$.processed_users') AS processed,
    JSON_EXTRACT(s.result_summary, '$.sent') AS sent,
    JSON_EXTRACT(s.result_summary, '$.skipped') AS skipped,
    JSON_EXTRACT(s.result_summary, '$.failed') AS failed,
    (SELECT COUNT(DISTINCT user_id) FROM stripe_invoice_schedule_items WHERE schedule_id = s.id) AS users_invoiced,
    (SELECT COUNT(*) FROM stripe_invoice_schedule_items WHERE schedule_id = s.id) AS invoice_items_created,
    CASE 
        WHEN (SELECT COUNT(DISTINCT user_id) FROM stripe_invoice_schedule_items WHERE schedule_id = s.id) = 
             (SELECT COUNT(*) FROM stripe_invoice_schedule_items WHERE schedule_id = s.id)
        THEN '✓ One invoice per user'
        ELSE '✗ Multiple invoices per user detected!'
    END AS consolidation_status
FROM stripe_invoice_schedules s
WHERE s.last_run_at IS NOT NULL
ORDER BY s.last_run_at DESC
LIMIT 5;

