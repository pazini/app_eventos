<?php

namespace App\Models\ModWorkshop;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class WorkshopPage extends Model
{
    use Uuid;

    protected $keyType = 'uuid';

    public $incrementing = false;

    protected $table = 'two_workshop_page';

    protected $fillable = [
        'workshop_id',
        'page_view',
        'page_view_order',
        'page_title',
        'page_title_view',
        'page_description',
        'page_description_view',
        'page_color_bg',
        'page_color_text',
        'page_color_text_title',
        'page_color_text_description',
        'page_body',
        'page_footer',
    ];

    protected $dates = [];
}
