<?php

namespace App\Models;

use App\Models\ModEvent\Event;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class FaturamentoPagamento extends Model
{
    use Uuid;

    protected $keyType = 'uuid';

    public $incrementing = false;

    protected $table = 'tb_app_faturamento_pagamentos';

    protected $fillable = [
        'faturamento_id',
        'pay_ref',
        'pay_status',
        'pay_descricao',
        'pay_tipo',
        'pay_valor',
        'pay_data_vencimento',
        'pay_boleto_url',
        'pay_data',
    ];

    protected $dates = [];

    public function faturamento()
    {
        return $this->HasOne(Faturamento::class,'id','faturamento_id');
    }
}
