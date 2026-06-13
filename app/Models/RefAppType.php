<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class RefAppType extends Model
{
    use Uuid;

    protected $keyType = 'uuid';

    public $incrementing = false;

    protected $table = 'ref_app_event_type';

    protected $fillable = [
        'app_id',
        'customer_id',
        'ref_slug',
        'ref_value',
        'ref_label',
        'ref_description',
        'ref_placeholder',
        'ref_options',
        'to_view',
        'ref_icon',
        'ref_color',
        'ref_color_bg',
    ];

    protected $dates = [];

    // public function modules()
    // {
    //     return $this->hasMany(AppModule::class)->orderBy('module_name');
    // }
}
