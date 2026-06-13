<?php

namespace App\Models;

use App\Models\ModEvent\Event;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class Faturamento extends Model
{
    use Uuid;

    protected $keyType = 'uuid';

    public $incrementing = false;

    protected $table = 'tb_app_faturamento';

    protected $date = ['pay_date'];

    protected $fillable = [
        'event_id',
        'pay_status',
        'pay_amont',
        'pay_date',
        'pay_nfe_url',
        'pay_nfe_cnpj',
        'tipo_faturamento',
        'vendas_valor_ticket',
        'vendas_qtd_max',
        'vendas_valor_total',
        'descricao',
        'valor',
        'qtd_parcelas',
        'nota_fiscal',
    ];

    protected $dates = [];

    public function event()
    {
        return $this->HasOne(Event::class,'id','event_id');
    }

    public function pagamentos()
    {
        return $this->HasMany(FaturamentoPagamento::class,'faturamento_id','id');
    }
}
