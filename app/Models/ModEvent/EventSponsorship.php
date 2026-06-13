<?php

namespace App\Models\ModEvent;

use App\Models\AppEvent\AppEventOrder;
use App\Models\AppEvent\AppEventOrderTicket;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class EventSponsorship extends Model
{
    use Uuid;

    protected $keyType = 'uuid';

    public $incrementing = false;

    protected $table = 'tev_events_sponsorship';

    protected $fillable = [
        'event_id',
        'slug',
        'name',
        'description',
        'about',
        'url_document_plan',
        'buyer_json_questions',
        'visible',
    ];

    protected $dates = [];

    public function event()
    {
        return $this->HasOne(Event::class,'event_id','id');
    }

    public function orders()
    {
        return $this->HasMany(AppEventOrderTicket::class,'event_ticket_id','id')->orderBy('created_at');
    }
}
