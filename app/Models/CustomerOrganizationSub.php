<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class CustomerOrganizationSub extends Model
{
    use Uuid;

    protected $keyType = 'uuid';

    public $incrementing = false;

    protected $table = 'tb_customers_organizations_subs';

    protected $fillable = [
        'customer_id',
        'organization_id',
        'organization_sub_name',
        'organization_sub_description',
        'organization_sub_url_image_logo',
        'organization_sub_url_image_thumbnail',
        'organization_sub_url_image',
        'organization_sub_url_image_bg',
        'organization_sub_slug',
    ];

    protected $dates = [];

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

    public function setOrganizationSubNameAttribute($value): void
    {
        $this->attributes['organization_sub_name'] = $this->toUpperString($value);
    }

    public function setOrganizationSubDescriptionAttribute($value): void
    {
        $this->attributes['organization_sub_description'] = $this->toTrimString($value);
    }

    public function organizers()
    {
        return $this->hasMany(CustomerOrganizer::class,'organization_sub_id','id')->orderBy('created_at');
    }

    public function organization()
    {
        return $this->belongsTo(CustomerOrganization::class, 'organization_id', 'id');
    }
}
