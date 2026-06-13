<?php

namespace App\Models\AppWorkshop;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class AppWorkshop extends Model
{
    use Uuid;

    protected $keyType = 'uuid';

    public $incrementing = false;

    protected $table = 'app_workshop';

    protected $dates = [];

    protected $fillable = [
        'organizer_id',
        'organizer_slug',
        'workshop_slug',
        'type_id',
        'category_id',
        'active',
        'status',
        'workshop_visibility_public',
        'json_workshop',
    ];
}
