<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StripeInvoiceScheduleItem extends Model
{
    use HasFactory;

    protected $table = 'stripe_invoice_schedule_items';

    protected $fillable = [
        'schedule_id',
        'log_id',
        'user_id',
        'subtotal_amount',
        'commission_amount',
        'total_amount',
        'stripe_customer_id',
        'stripe_invoice_id',
        'status',
        'error',
    ];

    protected $casts = [
        'subtotal_amount' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public function schedule()
    {
        return $this->belongsTo(StripeInvoiceSchedule::class, 'schedule_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function log()
    {
        return $this->belongsTo(StripeInvoiceScheduleLog::class, 'log_id');
    }
}
