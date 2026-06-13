<?php

namespace App\Models\ModCampaign;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignPaymentAttempt extends Model
{
    use HasFactory, Uuid;

    protected $table = 'tbc_campaign_payment_attempt';
    
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'campaign_id',
        'campaign_order_id',
        'campaign_payment_id',
        'pay_type',
        'gateway_slug',
        'status',
        'error_message',
        'request_data',
        'response_data',
        'attempted_at',
        'subscription_id',
        'subscription_cycle_id',
        'attempt_number',
        'scheduled_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'attempted_at' => 'datetime',
        'scheduled_at' => 'datetime',
        'request_data' => 'array',
        'response_data' => 'array',
    ];

    /**
     * Relacionamento com Campaign
     */
    public function campaign()
    {
        return $this->belongsTo(Campaign::class, 'campaign_id', 'id');
    }

    /**
     * Relacionamento com CampaignOrder
     */
    public function order()
    {
        return $this->belongsTo(CampaignOrder::class, 'campaign_order_id', 'id');
    }

    /**
     * Relacionamento com CampaignPayment
     */
    public function payment()
    {
        return $this->belongsTo(CampaignPayment::class, 'campaign_payment_id', 'id');
    }

    public function subscription()
    {
        return $this->belongsTo(CampaignSubscription::class, 'subscription_id');
    }

    public function subscriptionCycle()
    {
        return $this->belongsTo(CampaignSubscriptionCycle::class, 'subscription_cycle_id');
    }

    /**
     * Scope: apenas tentativas bem-sucedidas
     */
    public function scopeSuccess($query)
    {
        return $query->where('status', 'success');
    }

    /**
     * Scope: apenas tentativas com erro
     */
    public function scopeError($query)
    {
        return $query->where('status', 'error');
    }
}
