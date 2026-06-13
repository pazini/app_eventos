<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    use Uuid;

    protected $keyType = 'uuid';

    public $incrementing = false;

    protected $table = 'tb_providers';

    protected $fillable = [
        'customer_id',
        'organization_id',
        'organization_sub_id',
        'provider_slug',
        'provider_name',
        'provider_name_full',
        'provider_email',
        'provider_contact_country',
        'provider_contact_ddd',
        'provider_contact_num',
        'provider_contact_secondary_country',
        'provider_contact_secondary_ddd',
        'provider_contact_secondary_num',
    ];

    protected $dates = [];
}
