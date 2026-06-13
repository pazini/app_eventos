<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class AppBuyers extends Model
{
    use Uuid;

    protected $keyType = 'uuid';

    public $incrementing = false;

    protected $table = 'app_buyers';

    protected $fillable = [
        'doc_type',
        'doc_num',
        'my_key',
        'name',
        'email',
        'birth_date',
        'contact_country',
        'contact_ddd',
        'contact_num',
        'address',
        'address_number',
        'address_complement',
        'address_reference',
        'city_neighborhood',
        'city',
        'state',
        'country',
        'zip_code',
        'card_description',
        'card_token',
        'card_validate_mm',
        'card_validate_aaaa',
        'app_source',
        'app_user_uuid',
    ];

    protected $dates = ['birth_date'];
}
