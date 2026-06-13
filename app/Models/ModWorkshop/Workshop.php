<?php

namespace App\Models\ModWorkshop;

use App\Models\AppWorkshop\AppWorkshopOrder;
use App\Models\CustomerPayGateway;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class Workshop extends Model
{
    use Uuid;

    protected $keyType = 'uuid';

    public $incrementing = false;

    protected $table = 'two_workshop';

    protected $dates = [];

    protected $fillable = [
        'customer_id',
        'organizer_id',
        'workshop_slug',
        'workshop_name',
        'workshop_name_short',
        'workshop_description',
        'workshop_text_header',
        'workshop_text_footer',
        'active',
        'status',
        'type',
        'category',
        'workshop_visibility_public',
        'workshop_tickets_nomenclature',
        'place_id',
        'address',
        'address_number',
        'address_complement',
        'address_reference',
        'city_neighborhood',
        'city',
        'state',
        'zip_code',
        'google_maps_iframe',
        'cod_latitude',
        'cod_longitude',
        'color_primary',
        'color_secondary',
        'color_default',
        'url_image_logo',
        'url_image_thumbnail',
        'url_image',
        'url_image_bg',
    ];





    // LEGADO

    public function ticketsTypes()
    {
        return $this->HasMany(WorkshopTicketType::class);
    }

    public function tickets()
    {
        return $this->HasMany(AppWorkshopOrderTicket::class);
    }

    public function gatewayPay()
    {
        return $this->HasOne(CustomerPayGateway::class,'id','pay_gateway_id');
    }

    public function orders()
    {
        return $this->HasMany(AppWorkshopOrder::class,'workshop_id','id')->orderBy('created_at');
    }

    public function budgetsReceita()
    {
        return $this->HasMany(WorkshopBudget::class)->whereIn('tev_workshops_budgets.budget_operation', ['receita']);
    }

    public function budgetsDespesa()
    {
        return $this->HasMany(WorkshopBudget::class)->whereIn('tev_workshops_budgets.budget_operation', ['despesa']);
    }
}
