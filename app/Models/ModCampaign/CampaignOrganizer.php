<?php

namespace App\Models\ModCampaign;

use App\Models\Customer;
use App\Models\CustomerOrganization;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class CampaignOrganizer extends Model
{
    use Uuid;

    protected $keyType = 'uuid';

    public $incrementing = false;

    protected $table = 'tbc_campaign_organizer';

    protected $dates = [];

    protected $fillable = [
        'customer_id',
        'organization_id',
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
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function organization()
    {
        return $this->belongsTo(CustomerOrganization::class, 'organization_id', 'id');
    }

    public function campaigns()
    {
        return $this->hasMany(Campaign::class, 'organizer_id', 'id');
    }

    public function users()
    {
        return $this->belongsToMany(\App\Models\User::class, 'users_campaign_organizer', 'organizer_id', 'user_id')
            ->withPivot(['user_active', 'user_role', 'campaign_id'])
            ->orderBy('created_at');
    }
}
