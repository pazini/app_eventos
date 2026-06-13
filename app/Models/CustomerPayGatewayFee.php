<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class CustomerPayGatewayFee extends Model
{
    use Uuid;

    protected $keyType = 'uuid';

    public $incrementing = false;

    protected $table = 'tb_customers_pay_gateways_fees';

    protected $fillable = [
        'pay_gateway_id',
        'pay_type',
        'pay_installment',
        'percentage_fee',
        'percentage_adjust',
        'value_additional',
        'value_additional_apply',
    ];

    protected $dates = [];

    public function gateway()
    {
        return $this->HasOne(CustomerPayGateway::class,'id','pay_gateway_id');
    }
}
