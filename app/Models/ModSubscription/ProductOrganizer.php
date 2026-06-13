<?php

namespace App\Models\ModSubscription;

use App\Models\Customer;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class ProductOrganizer extends Model
{
    use Uuid;

    protected $keyType = 'uuid';

    public $incrementing = false;

    protected $table = 'tbs_product_organizer';

    protected $fillable = [
        'customer_id',
        'organizer_slug',
        'organizer_name',
        'organizer_name_full',
        'owner_name',
        'owner_email',
        'owner_phone_country',
        'owner_phone_ddd',
        'owner_phone_num',
        'url_image_logo',
        'url_image_thumbnail',
        'url_image',
        'url_image_bg',
        'url_site',
        'url_instagram',
        'url_facebook',
        'customer_pay_gateway_id',
        'customer_pay_gateway_seller_recipient_id',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }
}
