<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class UserCustomerOrganization extends Model
{
    use Uuid;

    protected $keyType = 'uuid';

    public $incrementing = false;

    protected $table = 'users_customer_organization';

    protected $fillable = [
        'user_id',
        'organization_id',
        'user_active',
        'user_role',
    ];

    protected $dates = [];
}
