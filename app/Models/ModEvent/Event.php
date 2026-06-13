<?php

namespace App\Models\ModEvent;

use App\Models\AppEvent\AppEventOrder;
use App\Models\AppEvent\AppEventOrderSponsorship;
use App\Models\AppEvent\AppEventOrderTicket;
use App\Models\Customer;
use App\Models\CustomerOrganizationPlace;
use App\Models\CustomerOrganizer;
use App\Models\CustomerPayGateway;
use App\Models\Faturamento;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use Uuid;

    protected $keyType = 'uuid';

    public $incrementing = false;

    protected $table = 'tev_events';

    protected $fillable = [
        'customer_id',
        'organizer_id',
        'event_slug',
        'referer_url',
        'event_name',
        'event_name_short',
        'event_description',
        'event_about',
        'event_text_header',
        'event_text_footer',
        'notification_text_1',
        'notification_text_2',
        'notification_text_pos_btn',
        'active',
        'status',
        'type',
        'category',
        'event_visibility_public',
        'event_datetime_label',
        'event_datetime_start',
        'event_datetime_finish',
        'event_tickets_nomenclature',
        'event_online',
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
        'color_default_inverse',
        'url_image_logo',
        'url_image_thumbnail',
        'url_image',
        'url_image_bg',
        'questions_buyer_pre_purchase',
        'questions_buyer_json',
        'questions_user_pre_purchase',
        'questions_user_json',
        'pay_gateway_id',
        'pay_sandbox',
        'pay_boleto',
        'pay_boleto_date_end',
        'pay_card_debit',
        'pay_card_credit',
        'pay_card_credit_installment_max',
        'pay_card_credit_installment_amount_min',
        'pay_pix',
        'pay_slip_pix',
        'pay_slip_pix_installment_max_auto',
        'pay_slip_pix_installment_max',
        'pay_slip_pix_installment_max_days_before',
        'pay_slip_pix_installment_max_event_date_finish',
        'pay_slip_pix_installment_amount_min',
        'sales_items_per_purchase',
        'sales_label',
        'sales_btn',
        'sales_theme',
        'sales_amount_max',
        'preview_summary',
        'preview_summary_json',
        'preview_summary_update',
        'preview_budget_management_entries',
        'preview_budget_management_entries_json',
        'preview_budget_management_outputs',
        'preview_budget_management_json',
        'preview_budget_management_update',
        'sales_label_item',
        'sales_label_item_multiple',
        'pay_limit_installment_date_event',
    ];

    protected $casts = [
        'event_datetime_start' => 'datetime',
        'event_datetime_finish' => 'datetime',
        'preview_summary_update' => 'datetime',
        'preview_budget_management_update' => 'datetime',
        'pay_boleto_date_end' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function customer()
    {
        return $this->HasOne(Customer::class,'id','customer_id');
    }

    public function organizer()
    {
        return $this->HasOne(CustomerOrganizer::class,'id','organizer_id');
    }

    public function place()
    {
        return $this->HasOne(CustomerOrganizationPlace::class,'id','place_id');
    }

    public function page()
    {
        return $this->HasMany(EventPage::class,'event_id','id');
    }

    public function tickets()
    {
        return $this->HasMany(AppEventOrderTicket::class);
    }

    public function sponsorship()
    {
        return $this->HasOne(EventSponsorship::class);
    }

    public function sponsorshipPlans()
    {
        return $this->HasMany(EventSponsorshipPlan::class);
    }

    public function sponsorshipOrders()
    {
        return $this->HasMany(AppEventOrderSponsorship::class);
    }

    public function ticketsTypes()
    {
        return $this->HasMany(EventTicketType::class);
    }

    public function orderTickets()
    {
        return $this->HasMany(AppEventOrderTicket::class);
    }

    public function codesPromo()
    {
        // 1:N
        return $this->hasMany(EventTicketCodePromo::class, 'event_id', 'id');
    }

    public function gatewayPay()
    {
        return $this->HasOne(CustomerPayGateway::class,'id','pay_gateway_id');
    }

    public function faturamento()
    {
        return $this->HasOne(Faturamento::class,'event_id','id');
    }

    public function orders()
    {
        return $this->HasMany(AppEventOrder::class,'event_id','id')->orderBy('created_at');
    }

    public function budgetsReceita()
    {
        return $this->HasMany(EventBudget::class)->whereIn('tev_events_budgets.budget_operation', ['receita']);
    }

    public function budgetsDespesa()
    {
        return $this->HasMany(EventBudget::class)->whereIn('tev_events_budgets.budget_operation', ['despesa']);
    }
}
