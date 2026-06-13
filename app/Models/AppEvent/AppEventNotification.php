<?php

namespace App\Models\AppEvent;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class AppEventNotification extends Model
{
    use Uuid;

    protected $keyType = 'uuid';

    public $incrementing = false;

    protected $table = 'app_events_notifications';

    protected $fillable = [
        'event_id',
        'order_id',
        'notification_type',
        'notification_json_info',
        'notification_payload',
        'notification_status',
        'notification_datetime_send',
    ];

    protected $casts = [
        'notification_datetime_send' => 'datetime',
    ];
}
