<?php

namespace App\Models\AppEvent;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class AppEvent extends Model
{
    // use Uuid;

    // protected $keyType = 'uuid';

    public $incrementing = false;

    protected $table = 'app_events';

    protected $fillable = [
        'id',
        'organizer_id',
        'organizer_slug',
        'event_slug',
        'type_id',
        'category_id',
        'active',
        'status',
        'event_visibility_public',
        'json_event',
    ];

    protected $dates = [];
}
