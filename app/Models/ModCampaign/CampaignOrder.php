<?php

namespace App\Models\ModCampaign;

use App\Traits\HasTenantScope;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignOrder extends Model
{
    use HasFactory, Uuid, HasTenantScope;

    protected $table = 'tbc_campaign_order';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'campaign_id',
        'buyer_id',
        'order_control',
        'buyer_name',
        'buyer_email',
        'buyer_doc_num',
        'buyer_contact_country',
        'buyer_contact_ddd',
        'buyer_contact_num',
        'amount_total',
        'amount_paid',
        'amount_discount',
        'status',
        'paid_at',
        'cancelled_at',
        'metadata',
        'is_anonymous',
        'ip_address',
        'user_agent',
        'referer',
        'current_payment_slip_id',
        'slip_group_id',
        'subscription_id',
        'subscription_cycle_id',
        'is_recurring',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'paid_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'metadata' => 'array',
        'is_anonymous' => 'boolean',
        'is_recurring' => 'boolean',
    ];

    /**
     * Accessor para garantir que amount_total seja sempre tratado como inteiro (centavos)
     */
    public function getAmountTotalAttribute($value)
    {
        // Se o valor vier como string decimal (ex: "1122.00"), converte para inteiro
        if (is_string($value) && strpos($value, '.') !== false) {
            return (int) round((float) $value);
        }
        // Se já for numérico, retorna como inteiro
        return (int) $value;
    }

    /**
     * Accessor para garantir que amount_paid seja sempre tratado como inteiro (centavos)
     */
    public function getAmountPaidAttribute($value)
    {
        // Se o valor vier como string decimal (ex: "0.00"), converte para inteiro
        if (is_string($value) && strpos($value, '.') !== false) {
            return (int) round((float) $value);
        }
        // Se já for numérico, retorna como inteiro
        return (int) $value;
    }

    /**
     * Accessor para garantir que amount_discount seja sempre tratado como inteiro (centavos)
     */
    public function getAmountDiscountAttribute($value)
    {
        // Se o valor vier como string decimal (ex: "0.00"), converte para inteiro
        if (is_string($value) && strpos($value, '.') !== false) {
            return (int) round((float) $value);
        }
        // Se já for numérico, retorna como inteiro
        return (int) ($value ?? 0);
    }

    /**
     * Relacionamento com Campaign
     */
    public function campaign()
    {
        return $this->belongsTo(Campaign::class, 'campaign_id');
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
     * Relacionamento com respostas do quiz
     */
    public function answers()
    {
        return $this->hasMany(CampaignOrderAnswer::class, 'campaign_order_id', 'id');
    }

    /**
     * Relacionamento com tentativas de pagamento
     */
    public function paymentAttempts()
    {
        return $this->hasMany(CampaignPaymentAttempt::class, 'campaign_order_id', 'id')
            ->orderBy('attempted_at', 'desc');
    }

    /**
     * Relacionamento com CampaignPaymentSlip atual
     */
    public function currentPaymentSlip()
    {
        return $this->belongsTo(CampaignPaymentSlip::class, 'current_payment_slip_id', 'id');
    }

    /**
     * Relacionamento com todos os PaymentSlips do pedido
     * Ordenado por installment_control para garantir ordem correta mesmo quando criados no mesmo segundo
     */
    public function paymentSlips()
    {
        return $this->hasMany(CampaignPaymentSlip::class, 'slip_group_id', 'slip_group_id')
            ->orderBy('installment_control', 'asc')
            ->orderBy('created_at', 'desc'); // Mais recente primeiro quando mesmo controle
    }

    /**
     * Relacionamento com todos os CampaignPayments do pedido
     */
    public function campaignPayments()
    {
        return $this->hasMany(CampaignPayment::class, 'campaign_order_id', 'id')
            ->orderBy('installment_number', 'asc')
            ->orderBy('created_at', 'asc');
    }

    /**
     * Relacionamento com webhooks
     */
    public function webhooks()
    {
        return $this->hasMany(CampaignPaymentWebhook::class, 'campaign_order_id', 'id')
            ->orderBy('created_at', 'desc');
    }

    /**
     * Accessor para obter o pagamento mais recente de forma confiável.
     */
    public function getCurrentPaymentAttribute()
    {
        // Se já tiver carregado os campaignPayments, retorna o primeiro
        if ($this->relationLoaded('campaignPayments')) {
            return $this->campaignPayments->first();
        }

        // Senão, faz uma query direta
        return $this->campaignPayments()->orderBy('created_at', 'desc')->first();
    }

    /**
     * Alias para compatibilidade com código legado que referencia ->payments.
     */
    public function payments()
    {
        return $this->campaignPayments();
    }
}
