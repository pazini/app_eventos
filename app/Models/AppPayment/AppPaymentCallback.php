<?php

namespace App\Models\AppPayment;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class AppPaymentCallback extends Model
{
    use Uuid;

    protected $keyType = 'uuid';

    public $incrementing = false;

    protected $table = 'app_payments_callbacks';

    protected $fillable = [
        'order_id',
        'payment_id',
        'callback_type',
        'gateway_slug',
        'gateway_id',
        'gateway_transaction_id',
        'gateway_msg',
        'status',
        'status_old',
        'value_paid',
        'nsu',
        'pay_type',
        'card_first',
        'card_last',
        'card_name',
        'card_brand',
        'boleto_barcode',
        'boleto_expiration_date',
        'boleto_url',
        'postback_id',
        'postback_url',
        'json_response',
        'postback_processed',
        'pix_qr_code',
        'pix_qr_code_url',
        'pix_expires_at',
        'pix_end_to_end_id',
        'pay_datetime',
        'ref_controle',
        'postback_processed_status',
        'postback_processed_json',
    ];

    protected $casts = [
        'pay_datetime' => 'datetime',
    ];
}
