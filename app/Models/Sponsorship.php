<?php

namespace App\Models;

use App\Models\AppPayment\AppPayment;
use App\Models\ModEvent\Event;
use App\Models\User;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class Sponsorship extends Model
{
    use Uuid;

    protected $keyType = 'uuid';

    public $incrementing = false;

    protected $table = 'tb_sponsorship';

    protected $dates = [];

    protected $fillable = [
        'customer_id',
        'organizer_id',
        'doc_type',
        'doc_num',
        'name',
        'segment',
        'description',
        'email',
        'contact_name',
        'contact_country',
        'contact_ddd',
        'contact_num',
        'url_logo',
        'url_website',
        'url_instagram',
    ];

}
