<?php

namespace App\Models\ModSubscription;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class ProductPlan extends Model
{
    use Uuid;

    protected $keyType = 'uuid';

    public $incrementing = false;

    protected $table = 'tbs_product_plan';

    protected $fillable = [
        'product_id',
        'plan_name',
        'plan_code',
        'description',
        'status',
        'amount',
        'interval_unit',
        'interval_count',
        'trial_days',
        'setup_fee_amount',
        'is_default',
        'sort_order',
        'url_image_header',
        'monthly_active',
        'monthly_amount',
        'monthly_pay_gateway_id',
        'monthly_pay_sandbox',
        'monthly_pay_pix',
        'monthly_pay_boleto',
        'monthly_pay_card_credit',
        'monthly_pay_card_credit_installment_max',
        'monthly_pay_card_credit_installment_fee_payer',
        'monthly_pay_card_credit_installment_amount_min',
        'annual_active',
        'annual_amount',
        'annual_pay_gateway_id',
        'annual_pay_sandbox',
        'annual_pay_pix',
        'annual_pay_boleto',
        'annual_pay_card_credit',
        'annual_pay_card_credit_installment_max',
        'annual_pay_card_credit_installment_fee_payer',
        'annual_pay_card_credit_installment_amount_min',
        'pay_gateway_id',
        'pay_sandbox',
        'pay_pix',
        'pay_boleto',
        'pay_card_credit',
        'pay_card_credit_installment_max',
        'pay_card_credit_installment_fee_payer',
        'pay_card_credit_installment_amount_min',
        'metadata',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'monthly_active' => 'boolean',
        'monthly_pay_sandbox' => 'boolean',
        'monthly_pay_pix' => 'boolean',
        'monthly_pay_boleto' => 'boolean',
        'monthly_pay_card_credit' => 'boolean',
        'annual_active' => 'boolean',
        'annual_pay_sandbox' => 'boolean',
        'annual_pay_pix' => 'boolean',
        'annual_pay_boleto' => 'boolean',
        'annual_pay_card_credit' => 'boolean',
        'pay_sandbox' => 'boolean',
        'pay_pix' => 'boolean',
        'pay_boleto' => 'boolean',
        'pay_card_credit' => 'boolean',
        'metadata' => 'array',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'product_plan_id', 'id');
    }
}
