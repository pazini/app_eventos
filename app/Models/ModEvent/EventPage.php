<?php

namespace App\Models\ModEvent;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class EventPage extends Model
{
    use Uuid;

    protected $keyType = 'uuid';

    public $incrementing = false;

    protected $table = 'tev_events_page';

    protected $fillable = [
        'event_id',
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
