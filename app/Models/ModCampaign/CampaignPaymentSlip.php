<?php

namespace App\Models\ModCampaign;

use App\Models\CustomerPayGateway;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CampaignPaymentSlip extends Model
{
    use HasFactory, Uuid;

    protected $table = 'tbc_campaign_payment_slip';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'campaign_id',
        'campaign_order_id',
        'slip_group_id',
        'description',
        'status',
        'paid_at',
        'cancelled_at',
        'expires_at',
        'due_date',
        'total_amount',
        'amount_paid',
        'amount_fees',
        'amount_liquid',
        'installments_total',
        'installments_paid',
        'installment_control',
        'customer_pay_gateway_id',
        'gateway_slug',
        'gateway_sandbox',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'paid_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'expires_at' => 'datetime',
        'due_date' => 'datetime',
        'total_amount' => 'integer',      // Centavos: R$ 1.234,56 = 123456
        'amount_paid' => 'integer',       // Centavos
        'amount_fees' => 'integer',       // Centavos
        'amount_liquid' => 'integer',     // Centavos
        'installments_total' => 'integer',
        'installments_paid' => 'integer',
        'installment_control' => 'integer',
        'gateway_sandbox' => 'boolean',
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
     * Relacionamento com todos os CampaignPayments deste slip
     */
    public function payments()
    {
        return $this->hasMany(CampaignPayment::class, 'campaign_payment_slip_id', 'id')
            ->orderBy('installment_number', 'asc')
            ->orderBy('created_at', 'desc'); // Mais recente primeiro quando installment_number é null
    }

    /**
     * Relacionamento com o primeiro CampaignPayment (para PIX/Boleto à vista)
     * Retorna o mais recente quando há múltiplas tentativas
     */
    public function payment()
    {
        return $this->hasOne(CampaignPayment::class, 'campaign_payment_slip_id', 'id')
            ->orderBy('created_at', 'desc'); // Mais recente primeiro
    }

    /**
     * Relacionamento com Gateway
     */
    public function gateway()
    {
        return $this->belongsTo(CustomerPayGateway::class, 'customer_pay_gateway_id', 'id');
    }

    /**
     * Scope: apenas slips pagos
     */
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    /**
     * Scope: apenas slips pendentes
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
