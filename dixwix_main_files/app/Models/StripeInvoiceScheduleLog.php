<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StripeInvoiceScheduleLog extends Model
{
    protected $table = 'stripe_invoice_schedule_logs';

    protected $fillable = [
        'schedule_id',
        'status',
        'run_at',
        'completed_at',
        'recurring_days',
        'range_from',
        'range_to',
        'result_summary',
        'error',
        'notes',
    ];

    protected $casts = [
        'run_at' => 'datetime',
        'completed_at' => 'datetime',
        'range_from' => 'datetime',
        'range_to' => 'datetime',
        'result_summary' => 'array',
    ];

    public function schedule()
    {
        return $this->belongsTo(StripeInvoiceSchedule::class, 'schedule_id');
    }

    public function items()
    {
        return $this->hasMany(StripeInvoiceScheduleItem::class, 'log_id');
    }
}
