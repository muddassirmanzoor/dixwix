-- ============================================
-- CHECK LAST CRON RUNS & USER INVOICE SUMMARY
-- ============================================

-- 1. Last 5 cron runs with summary
SELECT 
    id,
    recurring_days,
    status,
    last_run_at,
    next_run_at,
    JSON_PRETTY(result_summary) AS summary,
    error
FROM stripe_invoice_schedules
WHERE last_run_at IS NOT NULL
ORDER BY last_run_at DESC
LIMIT 5;

-- 2. For each schedule, show how many users got invoices
SELECT 
    s.id AS schedule_id,
    s.recurring_days,
    s.last_run_at,
    COUNT(DISTINCT si.user_id) AS total_users_invoiced,
    COUNT(si.id) AS total_invoice_items,
    SUM(si.subtotal_amount) AS total_rental_sent,
    SUM(si.commission_amount) AS total_commission_sent,
    SUM(si.total_amount) AS total_invoices_sent,
    SUM(CASE WHEN si.status = 'completed' THEN 1 ELSE 0 END) AS successfully_sent,
    SUM(CASE WHEN si.status = 'failed' THEN 1 ELSE 0 END) AS failed,
    SUM(CASE WHEN si.status = 'skipped' THEN 1 ELSE 0 END) AS skipped
FROM stripe_invoice_schedules s
LEFT JOIN stripe_invoice_schedule_items si ON si.schedule_id = s.id
WHERE s.last_run_at IS NOT NULL
GROUP BY s.id, s.recurring_days, s.last_run_at
ORDER BY s.last_run_at DESC
LIMIT 5;

-- 3. Verify: ONE user with MULTIPLE entries = ONE invoice (consolidated)
-- This shows users who had multiple point entries but got ONE invoice
SELECT 
    si.schedule_id,
    si.user_id,
    u.name,
    u.email,
    si.subtotal_amount AS invoice_rental_total,
    si.commission_amount AS invoice_commission_total,
    si.total_amount AS invoice_total,
    si.stripe_invoice_id,
    si.status,
    -- Count how many point entries were included in this invoice
    (SELECT COUNT(*) 
     FROM points p 
     WHERE p.stripe_invoice_schedule_id = si.schedule_id 
       AND p.user_id = si.user_id) AS point_entries_count,
    -- Show the sum of those point entries
    (SELECT SUM(p.amount) 
     FROM points p 
     WHERE p.stripe_invoice_schedule_id = si.schedule_id 
       AND p.user_id = si.user_id) AS points_rental_sum,
    (SELECT SUM(p.system_fee) 
     FROM points p 
     WHERE p.stripe_invoice_schedule_id = si.schedule_id 
       AND p.user_id = si.user_id) AS points_commission_sum
FROM stripe_invoice_schedule_items si
JOIN users u ON u.id = si.user_id
WHERE si.schedule_id IN (
    SELECT id FROM stripe_invoice_schedules 
    WHERE last_run_at IS NOT NULL 
    ORDER BY last_run_at DESC LIMIT 5
)
ORDER BY si.schedule_id DESC, point_entries_count DESC;

-- 4. Example: Show a specific user's multiple entries that were consolidated
-- Replace USER_ID and SCHEDULE_ID with actual values
SELECT 
    p.id AS point_id,
    p.user_id,
    p.amount AS rental_amount,
    p.system_fee AS commission_amount,
    p.created_at,
    p.stripe_invoice_schedule_id,
    p.stripe_invoiced_at,
    si.subtotal_amount AS invoice_total_rental,
    si.commission_amount AS invoice_total_commission,
    si.total_amount AS invoice_total,
    si.stripe_invoice_id
FROM points p
LEFT JOIN stripe_invoice_schedule_items si 
    ON si.schedule_id = p.stripe_invoice_schedule_id 
    AND si.user_id = p.user_id
WHERE p.stripe_invoice_schedule_id IS NOT NULL
  AND p.user_id = 617  -- Replace with user_id you want to check
ORDER BY p.stripe_invoice_schedule_id DESC, p.created_at DESC
LIMIT 10;

-- 5. Quick summary: Last run stats
SELECT 
    s.id,
    s.last_run_at,
    s.recurring_days,
    JSON_EXTRACT(s.result_summary, '$.processed_users') AS processed,
    JSON_EXTRACT(s.result_summary, '$.sent') AS sent,
    JSON_EXTRACT(s.result_summary, '$.skipped') AS skipped,
    JSON_EXTRACT(s.result_summary, '$.failed') AS failed,
    (SELECT COUNT(DISTINCT user_id) FROM stripe_invoice_schedule_items WHERE schedule_id = s.id) AS users_invoiced,
    (SELECT COUNT(*) FROM stripe_invoice_schedule_items WHERE schedule_id = s.id) AS invoice_items_created
FROM stripe_invoice_schedules s
WHERE s.last_run_at IS NOT NULL
ORDER BY s.last_run_at DESC
LIMIT 5;

