<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StripeInvoiceSchedule extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'stripe_invoice_schedules';

    protected $fillable = [
        'parent_schedule_id',
        'created_by',
        'run_at',
        'range_from',
        'range_to',
        'recurring_days',
        'next_run_at',
        'last_run_at',
        'status',
        'is_active',
        'stripe_behavior',
        'result_summary',
        'error',
    ];

    protected $casts = [
        'run_at' => 'datetime',
        'range_from' => 'datetime',
        'range_to' => 'datetime',
        'next_run_at' => 'datetime',
        'last_run_at' => 'datetime',
        'result_summary' => 'array',
        'is_active' => 'boolean',
        'deleted_at' => 'datetime',
    ];

    public function items()
    {
        return $this->hasMany(StripeInvoiceScheduleItem::class, 'schedule_id');
    }

    public function logs()
    {
        return $this->hasMany(StripeInvoiceScheduleLog::class, 'schedule_id');
    }

    public function parent()
    {
        return $this->belongsTo(StripeInvoiceSchedule::class, 'parent_schedule_id');
    }

    public function runs()
    {
        return $this->hasMany(StripeInvoiceSchedule::class, 'parent_schedule_id')->orderByDesc('run_at');
    }
}
