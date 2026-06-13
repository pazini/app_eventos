<?php

namespace App\Models\ModEvent;

use App\Models\AppEvent\AppEventOrder;
use App\Models\AppEvent\AppEventOrderTicket;
use App\Models\User;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventTicketType extends Model
{
    use Uuid;

    protected $keyType = 'uuid';

    public $incrementing = false;

    protected $table = 'tev_events_tickets_types';

    protected $fillable = [
        'event_id',
        'ticket_slug',
        'ticket_name',
        'ticket_description',
        'ticket_free',
        'ticket_free_qtd',
        'price',
        'amount',
        'amount_sales',
        'visible',
        'sale_period_type',
        'sale_start_ticket_id_finish',
        'sale_start_datetime',
        'sale_finish_datetime',
        'sale_amount_min',
        'sale_amount_max',
        'sale_ticket_availability',
        'sale_label_title',
        'sale_label_btn',
        'sale_view_grid_pre',
        'sale_view_grid_pos',
        'url_image_thumbnail',
        'url_image',
        'url_image_bg',
        'questions_buyer_pre_purchase',
        'questions_buyer_json',
        'questions_item_pre_purchase',
        'questions_item_json',
        'lote_publico',
        'view_order',
    ];

    protected $casts = [
        'sale_start_datetime' => 'datetime',
        'sale_finish_datetime' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (EventTicketType $ticketType) {
            if ($userId = auth()->id()) {
                $ticketType->created_by = $userId;
                $ticketType->updated_by = $userId;
            }
        });

        static::updating(function (EventTicketType $ticketType) {
            if ($userId = auth()->id()) {
                $ticketType->updated_by = $userId;
            }
        });
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function orders()
    {
        return $this->HasMany(AppEventOrder::class,'order_items_ticket_type_id','id')->orderBy('created_at');
    }

    public function tickets()
    {
        return $this->HasMany(AppEventOrderTicket::class,'event_ticket_id','id')->orderBy('created_at');
    }

    public function codesPromo()
    {
        return $this->HasMany(EventTicketCodePromo::class,'event_ticket_id','id')->orderBy('created_at');
    }
}
