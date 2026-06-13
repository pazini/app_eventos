<?php

namespace App\Models\ModCampaign;

use App\Models\CustomerPayGateway;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignPayment extends Model
{
    use HasFactory, Uuid;

    protected $table = 'tbc_campaign_payment';
    
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'campaign_id',
        'campaign_order_id',
        'campaign_payment_slip_id',
        'slip_group_id',
        'installment_number',
        'description',
        'customer_pay_gateway_id',
        'gateway_slug',
        'gateway_sandbox',
        'pay_integration_type',
        'status',
        'status_old',
        'pay_type',
        'value_paid',
        'value_fees',
        'value_liquid',
        'fee_percentage_used',
        'pay_transaction_id',
        'pay_nsu',
        'paid_label',
        'paid_description',
        'pay_pix_key',
        'pay_pix_qr_code',
        'pay_pix_qr_code_url',
        'pay_pix_expires_at',
        'pay_pix_end_to_end_id',
        'pay_boleto_barcode',
        'pay_boleto_expiration_date',
        'pay_boleto_url',
        'pay_installments_number',
        'pay_installment_value',
        'pay_card_first',
        'pay_card_last',
        'pay_card_name',
        'pay_card_brand',
        'pay_datetime',
        'paid_at',
        'expires_at',
        'pay_json_request',
        'pay_json_response',
        'subscription_id',
        'subscription_cycle_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'pay_datetime' => 'datetime',
        'paid_at' => 'datetime',
        'pay_pix_expires_at' => 'datetime',
        'pay_boleto_expiration_date' => 'date',
        'expires_at' => 'datetime',
        'value_paid' => 'integer',        // Centavos: R$ 1.234,56 = 123456
        'value_fees' => 'integer',        // Centavos
        'value_liquid' => 'integer',      // Centavos
        'fee_percentage_used' => 'decimal:2',
        'pay_installment_value' => 'integer', // Centavos
        'pay_installments_number' => 'integer',
        'installment_number' => 'integer',
        'gateway_sandbox' => 'boolean',
        'pay_json_request' => 'array',
        'pay_json_response' => 'array',
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

    public function subscription()
    {
        return $this->belongsTo(CampaignSubscription::class, 'subscription_id');
    }

    public function subscriptionCycle()
    {
        return $this->belongsTo(CampaignSubscriptionCycle::class, 'subscription_cycle_id');
    }

    /**
     * Relacionamento com CampaignPaymentSlip
     */
    public function slip()
    {
        return $this->belongsTo(CampaignPaymentSlip::class, 'campaign_payment_slip_id', 'id');
    }

    /**
     * Relacionamento com Gateway
     */
    public function gateway()
    {
        return $this->belongsTo(CustomerPayGateway::class, 'customer_pay_gateway_id', 'id');
    }

    /**
     * Relacionamento com tentativas de pagamento
     */
    public function attempts()
    {
        return $this->hasMany(CampaignPaymentAttempt::class, 'campaign_payment_id', 'id')
            ->orderBy('attempted_at', 'desc');
    }

    /**
     * Relacionamento com webhooks
     */
    public function webhooks()
    {
        return $this->hasMany(CampaignPaymentWebhook::class, 'campaign_payment_id', 'id')
            ->orderBy('created_at', 'desc');
    }

    /**
     * Scope: apenas payments pagos
     */
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    /**
     * Scope: apenas payments pendentes
     */
    public function scopePending($query)
    {
        return $query->whereIn('status', ['pending', 'processing']);
    }

    /**
     * Accessor: verifica se o payment está pago
     */
    public function getIsPaidAttribute()
    {
        return $this->status === 'paid';
    }

    /**
     * Accessor: verifica se o payment está pendente
     */
    public function getIsPendingAttribute()
    {
        return in_array($this->status, ['pending', 'processing']);
    }
}
