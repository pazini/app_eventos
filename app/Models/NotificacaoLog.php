<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class NotificacaoLog extends Model
{
    use Uuid;

    protected $table = 'tb_notificacoes_logs';

    public $incrementing = false;
    protected $keyType = 'uuid';

    protected $fillable = [
        'target_ref',
        'target_id',
        'campaign_id',
        'customer_id',
        'channel',
        'notification_type',
        'status',
        'recipient_email',
        'recipient_name',
        'subject',
        'error_message',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];
}
