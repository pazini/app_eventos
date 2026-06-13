<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class AppConfig extends Model
{
    use Uuid;

    protected $keyType = 'uuid';

    public $incrementing = false;

    protected $table = 'app_config';

    protected $dates = [];

    protected $fillable = [
        'app_id',
        'app_key',
        'app_value',
        'app_config_active',
        'app_description',
    ];
}
