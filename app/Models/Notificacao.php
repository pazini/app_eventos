<?php

namespace App\Models;

use App\Models\ModEvent\Event;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class Notificacao extends Model
{
    use Uuid;

    protected $keyType = 'uuid';

    public $incrementing = false;

    protected $table = 'tb_notificacoes';

    protected $fillable = [
        'target_ref',
        'target_id',
        'status',
        'envio_tipo',
        'envio_nome',
        'envio_descricao',
        'envio_assunto',
        'envio_assunto_nome',
        'envio_header',
        'envio_header_nome',
        'envio_body',
        'envio_footer',
        'envio_url_logo',
        'envio_color_bg',
        'programado',
        'programado_datahora',
        'data_envio_ini',
        'data_envio_fim',
    ];

    protected $casts = [
        'programado_datahora' => 'datetime',
        'data_envio_ini' => 'datetime',
        'data_envio_fim' => 'datetime',
    ];

    public function notificacaoEnvio()
    {
        return $this->HasMany(NotificacaoEnvio::class,'notificacao_id','id');
    }
}
