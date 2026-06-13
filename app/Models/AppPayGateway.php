<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class AppPayGateway extends Model
{
    use Uuid;

    protected $keyType = 'uuid';

    public $incrementing = false;

    protected $table = 'tb_app_pay_gateways';

    protected $fillable = [
        'app_id',
        'gateway_slug',
        'gateway_name',
        'gateway_description',
        'gateway_installment_fees_json',
        'token_live',
        'token_live_secret',
        'token_test',
        'token_test_secret',
        'split_live_recipient_id',
        'split_test_recipient_id',
        'value_additional',
        'value_transaction',
        'pay_boleto',
        'pay_pix',
        'pay_card_credit',
        'pay_card_debit',
        'pay_slip_pix',
        'pay_slip_pix_fees_json',
        'pay_slip_pix_split_receiver_id',
        'pay_slip_pix_split_receiver_name',
    ];

    protected $dates = [];
}
