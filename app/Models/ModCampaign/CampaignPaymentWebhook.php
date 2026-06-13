<?php

namespace App\Models\ModCampaign;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignPaymentWebhook extends Model
{
    use HasFactory, Uuid;

    protected $table = 'tbc_campaign_payment_webhook';
    
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'campaign_id',
        'campaign_order_id',
        'campaign_payment_id',
        'gateway_slug',
        'webhook_type',
        'external_transaction_id',
        'reference',
        'status',
        'amount',
        'payload',
        'processing_status',
        'processing_error',
        'processed_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'processed_at' => 'datetime',
        'amount' => 'integer', // Centavos
        'payload' => 'array',
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

    /**
     * Scope: apenas webhooks processados
     */
    public function scopeProcessed($query)
    {
        return $query->where('processing_status', 'processed');
    }

    /**
     * Scope: apenas webhooks pendentes
     */
    public function scopePending($query)
    {
        return $query->where('processing_status', 'pending');
    }

    /**
     * Scope: apenas webhooks com erro
     */
    public function scopeError($query)
    {
        return $query->where('processing_status', 'error');
    }
}

