<?php

namespace App\Models\ModSubscription;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class SubscriptionCycle extends Model
{
    use Uuid;

    protected $keyType = 'uuid';

    public $incrementing = false;

    protected $table = 'tbs_subscription_cycle';

    protected $fillable = [
        'subscription_id',
        'cycle_number',
        'billing_date',
        'status',
        'subscription_order_id',
        'paid_at',
        'last_attempt_at',
        'next_attempt_at',
        'attempts_count',
        'error_message',
    ];

    protected $casts = [
        'billing_date' => 'date',
        'paid_at' => 'datetime',
        'last_attempt_at' => 'datetime',
        'next_attempt_at' => 'datetime',
    ];

    public function subscription()
    {
        return $this->belongsTo(Subscription::class, 'subscription_id', 'id');
    }
}
