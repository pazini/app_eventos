<?php

namespace App\Models\AppPayment;

use App\Models\AppEvent\AppEventOrder;
use App\Models\AppPayGateway;
use App\Models\CustomerPayGateway;
use App\Models\ModEvent\EventTicketCodePromo;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class AppPayment extends Model
{
    use Uuid;

    protected $keyType = 'uuid';

    public $incrementing = false;

    protected $table = 'app_payments';

    protected $fillable = [
        'app_ref',
        'app_ref_order_id',
        'gateway_id',
        'gateway_slug',
        'status',
        'status_old',
        'description',
        'paid_label',
        'paid_description',
        'value_amortization',
        'value_paid',
        'value_liquid',
        'value_fees',
        'fee_percentage_used',
        'pay_transaction_id',
        'pay_nsu',
        'pay_type',
        'pay_datetime',
        'pay_installments_number',
        'pay_installment_value',
        'pay_card_first',
        'pay_card_last',
        'pay_card_name',
        'pay_card_brand',
        'pay_boleto_barcode',
        'pay_boleto_expiration_date',
        'pay_boleto_url',
        'pay_postback_url',
        'pay_gateway_direct_client',
        'pay_json_request',
        'pay_json_response',
        'pay_code_promo_id',
        'pay_pix_key',
        'pay_pix_qr_code',
        'pay_pix_qr_code_url',
        'pay_pix_expires_at',
        'pay_pix_end_to_end_id',
        'pay_integration_type',
        'gateway_sandbox',
        'order_slip_id',
        'pay_code_promo_discount_amount',
        'pay_value_paid',
        'pay_value_fees',
        'pay_value_liquid',
        'notifica_sucesso',
        'notifica_sucesso_datahora',
    ];

    protected $casts = [
        'pay_datetime' => 'datetime',
    ];

    public function gateway()
    {
        return $this->hasOne(CustomerPayGateway::class, 'id', 'gateway_id');
    }

    public function order()
    {
        return $this->hasOne(AppEventOrder::class, 'id', 'app_ref_order_id');
    }

    public function slip()
    {
        return $this->hasOne(AppPaymentSlip::class, 'id', 'order_slip_id');
    }

    public function eventCodePromo()
    {
        return $this->hasOne(EventTicketCodePromo::class, 'id', 'pay_code_promo_id');
    }
}
