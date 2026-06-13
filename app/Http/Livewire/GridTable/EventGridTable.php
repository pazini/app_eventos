<?php

namespace App\Http\Livewire\GridTable;

use App\Models\ModEvent\Event;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Rules\{Rule, RuleActions};
use PowerComponents\LivewirePowerGrid\Traits\ActionButton;
use PowerComponents\LivewirePowerGrid\{Button, Column, Exportable, Footer, Header, PowerGrid, PowerGridComponent, PowerGridEloquent};

final class EventGridTable extends PowerGridComponent
{
    use ActionButton;

    public $organizerId;
    public $targetList;
    public bool $showUpdateMessages = false;


    public function setUp(): array
    {
        // $this->showCheckBox();

        return [
            Exportable::make('export')
                ->striped()
                ->type(Exportable::TYPE_XLS),
            Header::make()
                ->showToggleColumns()
                ->showSearchInput(),
            Footer::make()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Event::query()->where('organizer_id', $this->organizerId);
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function addColumns(): PowerGridEloquent
    {
        return PowerGrid::eloquent()
            ->addColumn('id')
            ->addColumn('customer_id')
            ->addColumn('organizer_id')
            ->addColumn('event_slug')

           /** Example of custom column using a closure **/
            ->addColumn('event_slug_lower', function (Event $model) {
                return strtolower(e($model->event_slug));
            })

            ->addColumn('event_name', function (Event $model) {
                return '<a href=' . route('dashboard-evento', ['target_id' => $model->id]) . ' class=" hover:text-indigo-600 cursor-pointer">' . strtoupper(e($model->event_name)) . '</a>';
            })

            ->addColumn('event_name_short')
            ->addColumn('event_description', function (Event $model) {
                return $model->event_description ? strtolower(e($model->event_description)) : '--';
            })
            ->addColumn('event_text_header')
            ->addColumn('event_text_footer')
            ->addColumn('notification_text_1')
            ->addColumn('notification_text_2')
            ->addColumn('notification_text_pos_btn')
            ->addColumn('active')
            ->addColumn('status')
            ->addColumn('type', function (Event $model) {
                return $model->type ? strtoupper(e($model->type)) : '--';
            })
            ->addColumn('category', function (Event $model) {
                return $model->category ? strtoupper(e($model->category)) : '--';
            })
            ->addColumn('event_visibility_public')
            ->addColumn('event_datetime_label')
            ->addColumn('event_datetime_start_formatted', fn (Event $model) => Carbon::parse($model->event_datetime_start)->format('d/m/Y H:i'))
            ->addColumn('event_datetime_finish_formatted', fn (Event $model) => Carbon::parse($model->event_datetime_finish)->format('d/m/Y H:i'))
            ->addColumn('event_tickets_nomenclature')
            ->addColumn('event_online')
            ->addColumn('place_id')
            ->addColumn('address')
            ->addColumn('address_number')
            ->addColumn('address_complement')
            ->addColumn('address_reference')
            ->addColumn('city_neighborhood')
            ->addColumn('city')
            ->addColumn('state')
            ->addColumn('zip_code')
            ->addColumn('google_maps_iframe')
            ->addColumn('cod_latitude')
            ->addColumn('cod_longitude')
            ->addColumn('color_primary')
            ->addColumn('color_secondary')
            ->addColumn('color_default')
            ->addColumn('url_image_logo')
            ->addColumn('url_image_thumbnail')
            ->addColumn('url_image')
            ->addColumn('url_image_bg')
            ->addColumn('questions_buyer_pre_purchase')
            ->addColumn('questions_buyer_json')
            ->addColumn('questions_user_pre_purchase')
            ->addColumn('questions_user_json')
            ->addColumn('pay_gateway_id')
            ->addColumn('pay_sandbox')
            ->addColumn('pay_boleto')
            ->addColumn('pay_pix')
            ->addColumn('pay_card_debit')
            ->addColumn('pay_card_credit')
            ->addColumn('pay_card_credit_installment_max')
            ->addColumn('pay_card_credit_installment_amount_min')
            ->addColumn('created_at_formatted', fn (Event $model) => Carbon::parse($model->created_at)->format('d/m/Y H:i:s'))
            ->addColumn('updated_at_formatted', fn (Event $model) => Carbon::parse($model->updated_at)->format('d/m/Y H:i:s'));
    }

    public function columns(): array
    {
        return [
            // Column::make('ID', 'id')
            //     ->sortable()
            //     ->searchable(),

            // Column::make('CUSTOMER ID', 'customer_id')
            //     ->sortable()
            //     ->searchable(),

            // Column::make('ORGANIZER ID', 'organizer_id')
            //     ->sortable()
            //     ->searchable(),

            // Column::make('EVENT SLUG', 'event_slug')
            //     ->sortable()
            //     ->searchable()
            //     ->makeInputText(),

            Column::make('NOME', 'event_name')
                ->sortable()
                ->searchable(),

            // Column::make('EVENT NAME SHORT', 'event_name_short')
            //     ->sortable()
            //     ->searchable()
            //     ->makeInputText(),

            Column::make('DESCRIÇÃO', 'event_description')
                ->sortable()
                ->searchable(),

            // Column::make('EVENT TEXT HEADER', 'event_text_header')
            //     ->sortable()
            //     ->searchable()
            //     ->makeInputText(),

            // Column::make('EVENT TEXT FOOTER', 'event_text_footer')
            //     ->sortable()
            //     ->searchable()
            //     ->makeInputText(),

            // Column::make('NOTIFICATION TEXT 1', 'notification_text_1')
            //     ->sortable()
            //     ->searchable()
            //     ->makeInputText(),

            // Column::make('NOTIFICATION TEXT 2', 'notification_text_2')
            //     ->sortable()
            //     ->searchable()
            //     ->makeInputText(),

            // Column::make('NOTIFICATION TEXT POS BTN', 'notification_text_pos_btn')
            //     ->sortable()
            //     ->searchable()
            //     ->makeInputText(),

            // Column::make('ACTIVE', 'active')
            //     ->toggleable(),

            // Column::make('STATUS', 'status')
            //     ->sortable()
            //     ->searchable()
            //     ->makeInputText(),

            // Column::make('TIPO', 'type')
            //     ->sortable()
            //     ->searchable()
            //     ->makeInputText(),

            Column::make('CATEGORIA', 'category')
                ->sortable()
                ->searchable(),

            // Column::make('EVENT VISIBILITY PUBLIC', 'event_visibility_public')
            //     ->sortable()
            //     ->searchable()
            //     ->makeInputText(),

            // Column::make('LEGENDA', 'event_datetime_label')
            //     ->sortable()
            //     ->searchable(),

            Column::make('DATA INÍCIO', 'event_datetime_start_formatted', 'event_datetime_start')
                ->searchable()
                ->sortable(),

            Column::make('DATA FIM', 'event_datetime_finish_formatted', 'event_datetime_finish')
                ->searchable()
                ->sortable(),

            Column::make('VENDA', 'event_tickets_nomenclature')
                ->sortable()
                ->searchable(),

            // Column::make('EVENT ONLINE', 'event_online')
            //     ->toggleable(),

            // Column::make('PLACE ID', 'place_id')
            //     ->sortable()
            //     ->searchable(),

            // Column::make('ADDRESS', 'address')
            //     ->sortable()
            //     ->searchable()
            //     ->makeInputText(),

            // Column::make('ADDRESS NUMBER', 'address_number')
            //     ->sortable()
            //     ->searchable()
            //     ->makeInputText(),

            // Column::make('ADDRESS COMPLEMENT', 'address_complement')
            //     ->sortable()
            //     ->searchable()
            //     ->makeInputText(),

            // Column::make('ADDRESS REFERENCE', 'address_reference')
            //     ->sortable()
            //     ->searchable()
            //     ->makeInputText(),

            // Column::make('CITY NEIGHBORHOOD', 'city_neighborhood')
            //     ->sortable()
            //     ->searchable()
            //     ->makeInputText(),

            // Column::make('CITY', 'city')
            //     ->sortable()
            //     ->searchable()
            //     ->makeInputText(),

            // Column::make('STATE', 'state')
            //     ->sortable()
            //     ->searchable()
            //     ->makeInputText(),

            // Column::make('ZIP CODE', 'zip_code')
            //     ->sortable()
            //     ->searchable()
            //     ->makeInputText(),

            // Column::make('GOOGLE MAPS IFRAME', 'google_maps_iframe')
            //     ->sortable()
            //     ->searchable()
            //     ->makeInputText(),

            // Column::make('COD LATITUDE', 'cod_latitude')
            //     ->sortable()
            //     ->searchable()
            //     ->makeInputText(),

            // Column::make('COD LONGITUDE', 'cod_longitude')
            //     ->sortable()
            //     ->searchable()
            //     ->makeInputText(),

            // Column::make('COLOR PRIMARY', 'color_primary')
            //     ->sortable()
            //     ->searchable()
            //     ->makeInputText(),

            // Column::make('COLOR SECONDARY', 'color_secondary')
            //     ->sortable()
            //     ->searchable()
            //     ->makeInputText(),

            // Column::make('COLOR DEFAULT', 'color_default')
            //     ->sortable()
            //     ->searchable()
            //     ->makeInputText(),

            // Column::make('URL IMAGE LOGO', 'url_image_logo')
            //     ->sortable()
            //     ->searchable()
            //     ->makeInputText(),

            // Column::make('URL IMAGE THUMBNAIL', 'url_image_thumbnail')
            //     ->sortable()
            //     ->searchable()
            //     ->makeInputText(),

            // Column::make('URL IMAGE', 'url_image')
            //     ->sortable()
            //     ->searchable()
            //     ->makeInputText(),

            // Column::make('URL IMAGE BG', 'url_image_bg')
            //     ->sortable()
            //     ->searchable()
            //     ->makeInputText(),

            // Column::make('QUESTIONS BUYER PRE PURCHASE', 'questions_buyer_pre_purchase')
            //     ->toggleable(),

            // Column::make('QUESTIONS BUYER JSON', 'questions_buyer_json')
            //     ->sortable()
            //     ->searchable(),

            // Column::make('QUESTIONS USER PRE PURCHASE', 'questions_user_pre_purchase')
            //     ->toggleable(),

            // Column::make('QUESTIONS USER JSON', 'questions_user_json')
            //     ->sortable()
            //     ->searchable(),

            // Column::make('PAY GATEWAY ID', 'pay_gateway_id')
            //     ->sortable()
            //     ->searchable(),

            // Column::make('PAY SANDBOX', 'pay_sandbox')
            //     ->toggleable(),

            // Column::make('PAY BOLETO', 'pay_boleto')
            //     ->toggleable(),

            // Column::make('PAY PIX', 'pay_pix')
            //     ->toggleable(),

            // Column::make('PAY CARD DEBIT', 'pay_card_debit')
            //     ->toggleable(),

            // Column::make('PAY CARD CREDIT', 'pay_card_credit')
            //     ->toggleable(),

            // Column::make('PAY CARD CREDIT INSTALLMENT MAX', 'pay_card_credit_installment_max')
            //     ->makeInputRange(),

            // Column::make('PAY CARD CREDIT INSTALLMENT AMOUNT MIN', 'pay_card_credit_installment_amount_min')
            //     ->makeInputRange(),

            // Column::make('CREATED AT', 'created_at_formatted', 'created_at')
            //     ->searchable()
            //     ->sortable()
            //     ->makeInputDatePicker(),

            // Column::make('UPDATED AT', 'updated_at_formatted', 'updated_at')
            //     ->searchable()
            //     ->sortable()
            //     ->makeInputDatePicker(),
        ];
    }

    public function actions(): array
    {
       return [
            Button::make('info', 'VER')
                ->class('text-indigo-500 hover:text-indigo-600 cursor-pointer text-sm rounded-md')
                ->route('dashboard-evento', ['target_id' => 'id'])
                ->target('_self'),
       ];
    }
}
