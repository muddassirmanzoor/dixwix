# Soft Delete & Cron Logging Implementation

## ✅ Completed Features

### 1. Soft Deletes for Schedules
- **Migration**: Added `deleted_at` column to `stripe_invoice_schedules` table
- **Model**: Added `SoftDeletes` trait to `StripeInvoiceSchedule` model
- **Controller**: Updated to use `withTrashed()` to show deleted schedules
- **Views**: 
  - Shows deleted schedules with "Deleted" badge
  - "Show Deleted" / "Show Active" toggle button
  - "Restore" button for deleted schedules
  - "Force Delete" button for permanently deleting soft-deleted schedules

### 2. Cron Run Logging
- **Migration**: Created `stripe_invoice_schedule_logs` table
- **Model**: Created `StripeInvoiceScheduleLog` model
- **Cron Command**: Updated to log every run:
  - Creates log entry at start of processing
  - Updates log with completion status and results
  - Logs failures with error messages
- **Controller**: Added `logs()` method to view all logs for a schedule
- **Views**: 
  - Added "Logs" button in schedule list
  - Shows recent logs in schedule detail page
  - Created dedicated logs page with pagination

## Database Changes

### New Table: `stripe_invoice_schedule_logs`
```sql
- id
- schedule_id (foreign key)
- status (running, completed, failed)
- run_at (datetime)
- completed_at (datetime, nullable)
- recurring_days (integer, nullable)
- range_from (datetime, nullable)
- range_to (datetime, nullable)
- result_summary (json, nullable) - {processed_users, sent, skipped, failed}
- error (text, nullable)
- notes (text, nullable)
- timestamps
```

### Modified Table: `stripe_invoice_schedules`
```sql
- deleted_at (datetime, nullable) - Added for soft deletes
```

## New Routes

```php
GET  /settings/stripe-invoice-scheduler/{id}/logs    - View all logs for a schedule
POST /settings/stripe-invoice-scheduler/{id}/restore   - Restore a soft-deleted schedule
```

## How It Works

### Soft Deletes
1. When admin clicks "Delete", schedule is soft deleted (sets `deleted_at`)
2. Deleted schedules are hidden by default
3. Click "Show Deleted" to see deleted schedules
4. Deleted schedules can be restored or permanently deleted

### Cron Logging
1. Every time cron runs a schedule, it creates a log entry
2. Log entry starts with `status = 'running'`
3. After processing, log is updated with:
   - `status = 'completed'` or `'failed'`
   - `completed_at` timestamp
   - `result_summary` with counts
   - `error` message if failed
4. All logs are stored permanently in database
5. View logs from:
   - Schedule detail page (recent logs)
   - Dedicated logs page (all logs with pagination)

## Usage

### View Deleted Schedules
1. Go to Stripe Invoice Scheduler page
2. Click "Show Deleted" button
3. Deleted schedules appear with gray background and "Deleted" badge

### Restore a Schedule
1. Click "Show Deleted"
2. Find the schedule you want to restore
3. Click "Restore" button
4. Schedule is restored and becomes active again

### View Cron Logs
1. From schedule list: Click "Logs" button next to any schedule
2. From schedule detail: Click "View All Logs" button
3. Logs page shows:
   - All runs for that schedule
   - Status (completed/failed/running)
   - Date range processed
   - Result summary (processed, sent, skipped, failed)
   - Error messages
   - Duration of each run

## Benefits

1. **No Data Loss**: Deleted schedules are preserved and can be restored
2. **Full Audit Trail**: Every cron run is logged with complete details
3. **Debugging**: Easy to see what happened in each run
4. **History**: Track performance and errors over time
5. **Compliance**: Maintain records of all invoice processing

## Testing

To test soft deletes:
```sql
-- Create a test schedule, then delete it
-- It should appear in "Show Deleted" view
-- Restore it to verify restore works
```

To test logging:
```sql
-- Run cron manually or wait for scheduled run
-- Check stripe_invoice_schedule_logs table
-- View logs in admin UI
```

## Notes

- Soft-deleted schedules are excluded from cron processing automatically (Laravel's SoftDeletes trait handles this)
- Logs are never deleted (only schedules can be soft deleted)
- Each log entry represents one complete run of a schedule
- Logs include the exact date range that was processed

