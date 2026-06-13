<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class CustomerOrganizerUser extends Model
{
    use Uuid;

    protected $keyType = 'uuid';

    public $incrementing = false;

    protected $table = 'users_customer_organizer';

    protected $dates = [];

    protected $fillable = [
        'user_id',
        'organizer_id',
        'event_id',
        'user_active',
        'user_role',
    ];
}
