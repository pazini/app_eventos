<?php

namespace App\Models;

use App\Models\ModEvent\Event;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class NotificacaoEnvio extends Model
{
    use Uuid;

    protected $keyType = 'uuid';

    public $incrementing = false;

    protected $table = 'tb_notificacoes_envios';

    protected $fillable = [
        'notificacao_id',
        'status',
        'tipo',
        'datahora',
        'destino',
        'destino_nome',
        'assunto',
        'header',
        'body',
        'footer',
        'url_logo',
        'color_bg',
    ];

    protected $casts = [
        'datahora' => 'datetime',
    ];

    public function faturamento()
    {
        return $this->HasOne(Faturamento::class,'id','faturamento_id');
    }
}
