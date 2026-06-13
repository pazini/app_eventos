<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class CustomerPayGateway extends Model
{
    use Uuid;

    protected $keyType = 'uuid';

    public $incrementing = false;

    protected $table = 'tb_customers_pay_gateways';

    protected $fillable = [
        'customer_id',
        'pay_gateway_id',
        'pay_gateway_slug',
        'pay_gateway_label',
        'pay_gateway_description',
        'cod_subconta_id',
        'conta_cod',
        'conta_banco',
        'conta_banco_descricao',
        'conta_tipo',
        'conta_agencia',
        'conta_agencia_dv',
        'conta_numero',
        'conta_numero_dv',
        'pay_boleto_fees_json',
        'pay_pix_fees_json',
        'pay_gateway_installment_fees_json',
        'fee_boleto_fixed_amount',
        'fee_pix_fixed_amount',
        'fee_slip_pix_fixed_amount',
        'fee_credit_fixed_amount',
        'pay_gateway_direct_client',
        'pay_boleto',
        'pay_pix',
        'pay_slip_pix',
        'pay_slip_pix_installment_max',
        'pay_slip_pix_installment_amount_min',
        'pay_slip_pix_fees_json',
        'pay_card_debit',
        'pay_card_credit',
        'pay_card_credit_installment_max',
        'pay_card_credit_installment_amount_min',
        'token_live',
        'token_live_pass',
        'token_test',
        'token_test_pass',
        'value_additional',
        'percentage_anticipation',
        'apply_value_additional',
        'apply_percentage_anticipation',
        'apply_installment_fees',
        'split_pay',
        'split_mode',
        'split_customer_amount ',
        'split_live_recipient_id',
        'split_live_recipient_id_client',
        'split_test_recipient_id',
        'split_test_recipient_id_client',
        'pay_active',
        'use_events',
        'use_campaigns',
    ];

    protected $dates = [];

    public function appGateway()
    {
        return $this->HasOne(AppPayGateway::class, 'id', 'pay_gateway_id');
    }

    public function fees()
    {
        return $this->hasMany(CustomerPayGatewayFee::class, 'pay_gateway_id', 'id');
    }
}
