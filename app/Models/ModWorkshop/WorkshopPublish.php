<?php

namespace App\Models\ModWorkshop;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class WorkshopPublish extends Model
{
    use Uuid;

    protected $keyType = 'uuid';

    public $incrementing = false;

    protected $table = 'two_workshop_publishs';

    protected $fillable = [
        'workshop_id',
        'user_id',
        'publish_status',
        'publish_control',
        'publish_json_workshop',
        'publish_datetime_start',
        'publish_datetime_finish',
    ];

    protected $casts = [
        'publish_datetime_start' => 'datetime',
        'publish_datetime_finish' => 'datetime',
    ];
}
