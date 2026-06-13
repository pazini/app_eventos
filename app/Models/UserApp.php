<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class UserApp extends Model
{
    use Uuid;

    protected $keyType = 'uuid';

    public $incrementing = false;

    protected $table = 'users_app';

    protected $fillable = [
        'app_id',
        'user_id',
        'user_active',
        'user_role',
    ];

    protected $dates = [];
}
