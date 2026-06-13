<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class CustomerOrganizationPlace extends Model
{
    use Uuid;

    protected $keyType = 'uuid';

    public $incrementing = false;

    protected $table = 'tb_customers_organizations_places';

    protected $fillable = [
        'organization_id',
        'place_slug',
        'place_name',
        'place_description',
        'address',
        'address_number',
        'address_complement',
        'address_reference',
        'city_neighborhood',
        'city',
        'state',
        'zip_code',
        'iframe_google_maps',
        'cod_latitude',
        'cod_longitude',
    ];

    protected $dates = [];
}
