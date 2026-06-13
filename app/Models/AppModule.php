<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class AppModule extends Model
{
    use Uuid;

    protected $keyType = 'uuid';

    public $incrementing = false;

    protected $table = 'tb_app_modules';

    protected $fillable = [
        'app_id',
        'slug',
        'module_name',
        'module_description',
        'module_active',
        'singular_name',
    ];

    protected $dates = [];
}
