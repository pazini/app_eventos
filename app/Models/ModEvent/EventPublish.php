<?php

namespace App\Models\ModEvent;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class EventPublish extends Model
{
    use Uuid;

    protected $keyType = 'uuid';

    public $incrementing = false;

    protected $table = 'tev_events_publishs';

    protected $fillable = [
        'event_id',
        'user_id',
        'publish_status',
        'publish_control',
        'publish_json_event',
        'publish_datetime_start',
        'publish_datetime_finish',
    ];

    protected $casts = [
        'publish_datetime_start' => 'datetime',
        'publish_datetime_finish' => 'datetime',
    ];
}
