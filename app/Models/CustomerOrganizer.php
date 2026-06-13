<?php

namespace App\Models;

use App\Models\ModEvent\Event;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class CustomerOrganizer extends Model
{
    use Uuid;

    protected $keyType = 'uuid';

    public $incrementing = false;

    protected $table = 'tb_customers_organizers';

    protected $dates = [];

    protected $fillable = [
        'customer_id',
        'organization_id',
        'organization_sub_id',
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

    private function toUpperString($value): ?string
    {
        if (is_null($value)) {
            return null;
        }

        return mb_strtoupper(trim((string) $value));
    }

    private function toTrimString($value): ?string
    {
        if (is_null($value)) {
            return null;
        }

        return trim((string) $value);
    }

    private function toLowerEmail($value): ?string
    {
        if (is_null($value)) {
            return null;
        }

        return mb_strtolower(trim((string) $value));
    }

    public function setOrganizerNameAttribute($value): void
    {
        $this->attributes['organizer_name'] = $this->toUpperString($value);
    }

    public function setOrganizerNameFullAttribute($value): void
    {
        $this->attributes['organizer_name_full'] = $this->toUpperString($value);
    }

    public function setOwnerNameAttribute($value): void
    {
        $this->attributes['owner_name'] = $this->toTrimString($value);
    }

    public function setOwnerEmailAttribute($value): void
    {
        $this->attributes['owner_email'] = $this->toLowerEmail($value);
    }

    public function customer()
    {
        return $this->HasOne(Customer::class,'id','customer_id');
    }

    public function organization()
    {
        return $this->HasOne(CustomerOrganization::class,'id','organization_id');
    }

    public function organizationSub()
    {
        return $this->HasOne(CustomerOrganizationSub::class,'id','organization_sub_id');
    }

    public function events()
    {
        return $this->HasMany(Event::class,'organizer_id','id');
    }

    public function places()
    {
        return $this->HasMany(CustomerOrganizationPlace::class,'organization_id','organization_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class,'users_customer_organizer','organizer_id','user_id')->orderBy('created_at');
    }
}
