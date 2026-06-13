<?php

namespace App\Models\AppWorkshop;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class AppWorkshopOrganizer extends Model
{
    use Uuid;

    protected $keyType = 'uuid';

    public $incrementing = false;

    protected $table = 'app_workshop_organizers';

    protected $dates = [];

    protected $fillable = [
        'slug',
        'name',
        'json_organizer',
    ];
}
