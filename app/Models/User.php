<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;
    use Uuid;

    protected $keyType = 'uuid';

    public $incrementing = false;

    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'remember_token',
        'profile_photo_path',
        'password',
        'birth_date',
        'doc_type',
        'doc_num',
        'contact_country',
        'contact_ddd',
        'contact_num',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'birth_date' => 'datetime',
    ];

    // protected $appends = [
    //     'profile_photo_url',
    // ];

    // protected function defaultProfilePhotoUrl()
    // {
    //     return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=FFFFFF&background=000000';
    // }

    // SET NOME
    public function setNameAttribute($value)
    {
        $this->attributes['name']       = ucwords(strtolower(trim($value)));
        // $this->attributes['name_first'] = ucwords(strtolower(trim(explode(' ',$value)[0])));
        // $this->attributes['name_last']  = ucwords(strtolower(trim(explode(' ',$value)[1] ?? null)));
    }

    // SET EMAIL
    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = strtolower(trim($value));
    }

    public function app()
    {
        return $this->belongsToMany(App::class,'users_app')->withPivot(['user_active','user_role'])->orderBy('users_app.created_at');

        // return $this->hasOneThrough(App::class,UserApp::class,'user_id','id','id','app_id')->withPivot(['user_active','user_role'])->orderBy('created_at');
    }

    public function customers()
    {
        return $this->belongsToMany(Customer::class, 'users_customer')
            ->withPivot(['user_active', 'user_role', 'can_events', 'can_campaigns', 'can_subscriptions'])
            ->orderBy('users_customer.created_at');
    }

    public function customerOrganization()
    {
        return $this->belongsToMany(CustomerOrganization::class,'users_customer_organization','user_id','organization_id')->withPivot(['user_active','user_role'])->orderBy('users_customer_organization.created_at');
    }

    public function customerOrganizationSub()
    {
        return $this->belongsToMany(CustomerOrganizationSub::class,'users_customer_organization_sub','user_id','organization_sub_id')->withPivot(['user_active','user_role'])->orderBy('users_customer_organization_sub.created_at');
    }

    public function organizers()
    {
        return $this->belongsToMany(
                CustomerOrganizer::class,
                'users_customer_organizer',
                'user_id',
                'organizer_id'
            )
            ->withPivot(['user_active','user_role'])
            ->orderBy('users_customer_organizer.created_at');
    }

    public function campaignOrganizers()
    {
        return $this->belongsToMany(
                \App\Models\ModCampaign\CampaignOrganizer::class,
                'users_campaign_organizer',
                'user_id',
                'organizer_id'
            )
            ->withPivot(['user_active', 'user_role', 'campaign_id'])
            ->orderBy('users_campaign_organizer.created_at');
    }
}
