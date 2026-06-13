<?php

namespace App\Models\ModCampaign;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignSubscriptionCycle extends Model
{
    use HasFactory, Uuid;

    protected $table = 'tbc_campaign_subscription_cycle';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'subscription_id',
        'cycle_number',
        'billing_date',
        'status',
        'campaign_order_id',
        'paid_at',
        'last_attempt_at',
        'next_attempt_at',
        'attempts_count',
        'error_message',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'billing_date' => 'date',
        'paid_at' => 'datetime',
        'last_attempt_at' => 'datetime',
        'next_attempt_at' => 'datetime',
    ];

    public function subscription()
    {
        return $this->belongsTo(CampaignSubscription::class, 'subscription_id');
    }

    public function order()
    {
        return $this->belongsTo(CampaignOrder::class, 'campaign_order_id');
    }

    public function attempts()
    {
        return $this->hasMany(CampaignPaymentAttempt::class, 'subscription_cycle_id')
            ->orderBy('attempt_number')
            ->orderBy('attempted_at');
    }
}
