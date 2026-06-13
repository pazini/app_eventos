<?php

use App\Jobs\AppEvent\NotificationAppEventCompra;
use App\Mail\Compra\CompraConfirmada;
use App\Mail\Compra\PagamentoConfirmado;
use App\Mail\Compra\PagamentoLembreteCarne;
use App\Mail\EmailTeste;
use App\Mail\Pagamento\PagamentoSucesso;
use App\Models\AppEvent\AppEventOrder;
use App\Models\AppEvent\AppEventOrderSponsorship;
use App\Models\AppEvent\AppEventOrderTicket;
use App\Models\AppNotifica;
use App\Models\AppPayment\AppPayment;
use App\Models\AppPayment\AppPaymentCallback;
use App\Models\AppPayment\AppPaymentSlip;
use App\Models\Customer;
use App\Models\CustomerOrganizer;
use App\Models\UserCustomer;
use App\Services\safe2pay\Safe2PayService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

if (!function_exists('buscarCep')) {

    function buscarCep($cep=false)
    {
        $buscarCep = new stdClass;
        $buscarCep->error    = false;
        $buscarCep->msg      = '';
        $buscarCep->cep      = putMask($cep,'cep');
        $buscarCep->endereco = '';
        $buscarCep->bairro   = '';
        $buscarCep->cidade   = '';
        $buscarCep->estado   = '';

        //
        if ($cep) {

            $buscarCep->msg = "Endereço localizado com sucesso";

            $response = Http::get("https://viacep.com.br/ws/{$cep}/json/");
            //
            $buscarCep->endereco = $response->json('logradouro') ?? '';
            $buscarCep->bairro   = $response->json('bairro') ?? '';
            $buscarCep->cidade   = $response->json('localidade') ?? '';
            $buscarCep->estado   = $response->json('uf') ?? '';

            //
            if (!$buscarCep->endereco) {
                $buscarCep->error = true;
                $buscarCep->msg   = "Endereço não localizado para o CEP ". putMask($cep,'cep');
            }

        } else {
            $buscarCep->error = true;
            $buscarCep->msg   = "É preciso informar um CEP";
        }

        return $buscarCep;
    }
}

if (!function_exists('toMoney')) {

    function toMoney($amount=0, $prefix=null, $coinName='blr')
    {
        $amount = str_replace([',','.'],'', (string) $amount);

        // $amount = str_replace(['.'],',', (string) $amount);
        // $amount = str_replace([','],'.', (string) $amount);

        switch (strtolower($coinName)) {
            case 'blr':
                $amount = number_format((int) $amount / 100 , 2 , ',' , '.');
                break;

            default:
                return $amount;
        }

        //
        if($prefix ?? false)
            $amount = $prefix . $amount;

        return $amount;
    }
}

if (!function_exists('toMoneyDot')) {

    function toMoneyDot($amount)
    {
        $amount = toMoney($amount);
        $amount = str_replace('.','', (string) $amount);
        $amount = str_replace([','],'.', (string) $amount);

        return $amount;
    }
}

if (!function_exists('toMoneyInt')) {

    function toMoneyInt($amount=0)
    {
        $value = (int) number_format($amount , 2,'','');
        return $value;
    }
}

if (!function_exists('boolSimNao'))
{
    function boolSimNao($value) :string
    {
        if($value ?? false)
            return 'SIM';
        else
            return 'NÃO';
    }
}

if (!function_exists('viewByGrid'))
{
    function viewByGrid($data=[], $translate=true, $json=false) :string
    {
        //
        if($json)
        {
            $data = json_decode($data ?? '{}',true);
        }

        // SE OBJECT >> TO ARRAY
        if(gettype($data) == 'object')
            $data = json_decode(json_encode($data), true);

        // START
        $div = "";

        //
        foreach ($data as $dataKey => $dataValue)
        {
            if(gettype($dataValue) == 'array')
            {
                if($translate ?? false)
                {
                    $dataKey = (gettype($dataKey) == 'integer') ? 'Key ' . $dataKey : __($dataKey ?? '--');
                }

                $dataValue = viewByGrid($dataValue,$translate);

                $div  .= <<<EOT
                <div class="pb-1"><hr></div>
                <div class='w-full bg-gray-500 text-white py-1 px-1'>{$dataKey}</div>
                <div class='flex w-full'>
                    <div class='w-2 bg-gray-500 text-white'></div>
                    <div class='w-full bg-gray-200 text-black pl-1'>{$dataValue}</div>
                </div>
                EOT;
            }
            else
            {
                // DATA KEY
                if(gettype($dataKey) == 'integer')
                {
                    $dataKey = (string) $dataKey;
                }

                // DATA VALUE
                if(gettype($dataValue) == 'boolean')
                {
                    if((string) $dataValue)
                        $dataValue = 'true';
                    else
                        $dataValue = 'false';
                }
                elseif(gettype($dataValue) == 'integer')
                {
                    $dataValue = (string) $dataValue;
                }
                elseif(gettype($dataValue) == 'string')
                {
                    // SE JSON >> ARRAY
                    $decode = json_decode($dataValue, true);
                    //
                    if(gettype($decode) == 'array')
                        $dataValue = $decode;
                }

                // FORMAT DATE
                if(in_array($dataKey,['created_at','updated_at','date_created','pay_datetime','date_created','date_updated']) && $dataValue ?? false)
                {
                    $dataValue = Carbon::create($dataValue)->timezone('America/Sao_Paulo')->format('d/m/Y H:i:s');
                }

                if(gettype($dataValue) == 'array')
                {
                    if($translate ?? false)
                        $dataKey = (gettype($dataKey) == 'integer') ? 'Key ' . $dataKey : __($dataKey ?? '--');

                    if($bgLoop ?? false && $bgLoop == 'bg-green-600')
                    {
                        $bgLoop = 'bg-blue-500';
                    }
                    else
                    {
                        $bgLoop = 'bg-green-600';
                    }

                    $dataValue = viewByGrid($dataValue,$translate);

                    $div  .= <<<EOT
                    <div class="pb-1"><hr></div>
                    <div class='w-full {$bgLoop} text-white py-1 px-1'>{$dataKey}</div>
                    <div class='flex w-full shadow'>
                        <div class='w-2 {$bgLoop} text-white'></div>
                        <div class='w-full bg-gray-200 text-black pl-1'>{$dataValue}</div>
                    </div>
                    EOT;
                }
                else
                {
                    if($translate ?? false)
                    {
                        $dataKey = (gettype($dataKey) == 'integer') ? 'Key ' . $dataKey : __($dataKey ?? '--');
                        $dataValue = __($dataValue ?? '--');
                    }

                    $div      .= <<<EOT
                    <div class='flex gap-1 my-1'>
                        <div class='w-5/12 bg-gray-100 text-black p-1 text-right'>{$dataKey}</div>
                        <div class='w-full bg-gray-100 text-black p-1'>{$dataValue}</div>
                    </div>
                    EOT;
                }
            }
        }

        return $div;
    }
}

if (!function_exists('setLabel'))
{
    function setLabel($title='title', $body='body',$titleU=true,$bodyU=true,$bold=true,$translate=true,$onlyTitle=false,$mb=4,$hint=false,$truncate=true) :string
    {
        $titleU = $titleU ? 'uppercase' : 'capitalize';
        $bodyU  = $bodyU ? 'uppercase' : '';

        //
        $bold = $bold ? 'font-semibold' : 'font-normal';

        $title = $title ? $title : '---';
        $body = $body ? $body : '---';

        //
        if($translate ?? false)
        {
            $title = __($title);
            $body  = __($body);
        }

        //
        if($onlyTitle ?? false)
        {
            $body = null;
        }

        //
        if($truncate ?? false)
            $truncate = 'truncate';
        else
            $truncate = null;

        $div = "";
        $div  .= <<<EOT
        <div class="w-full mb-{$mb}">
            <dl class="w-full">
                <dt class="{$titleU} text-lg md:text-sm font-light">{$title}</dt>
        EOT;
        //
        if(!$onlyTitle)
        {
            $div  .= <<<EOT
                    <dd class="{$bodyU} text-xl md:text-lg {$bold} -mt-1 {$truncate}" title="{$body}">{$body}</dd>
            EOT;
        }

        //
        if($hint ?? false)
        {
            $div  .= <<<EOT
                    <dd class="text-xl md:text-sm -mt-1 truncate" title="{$hint}">{$hint}</dd>
            EOT;
        }

        $div  .= <<<EOT
            </dl>
        </div>
        EOT;
        return $div;
    }
}

if (!function_exists('setLabelHeader'))
{
    function setLabelHeader($title='title', $body='body', $titleSub=null) :string
    {
        $titleU = true;
        $bodyU  = true;
        $bold   = true;

        $titleU = $titleU ? 'uppercase' : 'capitalize';
        $bodyU  = $bodyU ? 'uppercase' : 'capitalize';
        $bold   = $bold ? 'font-semibold' : 'font-normal';

        $div = "";
        $div  .= <<<EOT
        <div class="w-full">
            <dl class="w-full m-0">
        EOT;

        if($title ?? false)
        {
            $title = $title ? __($title) : '---';
            $div  .= <<<EOT
                    <dt class="{$titleU} text-xs md:text-xs font-light pb-1">{$title}</dt>
            EOT;
        }

        if($body ?? false)
        {

            $body = $body ? __($body) : '---';
            $div .= <<<EOT
                    <dd class="{$bodyU} text-xl md:text-2xl {$bold} -mt-2">{$body}</dd>
            EOT;
        }


        $div  .= <<<EOT
            </dl>
        EOT;

        if ($titleSub ?? false)
        {

            $titleSub = $titleSub ? __($titleSub) : null;
            $div     .= <<<EOT
                <div class="-mt-1 text-text-lg md:text-base font-light truncate">{$titleSub}</div>
            EOT;
        }


        $div  .= <<<EOT
        </div>
        EOT;

        return $div;
    }
}

if (!function_exists('setClass'))
{
    function setClass($class=false,$classAdd=null)
    {
        switch ($class)
        {
            case 'btnFormaPagamento':
                $return = "w-full flex flex-row justify-center items-center gap-2 text-center font-medium hover:font-semibold text-green-700 bg-white hover:bg-green-100 border hover:border-green-400 p-2 md:p-4 rounded shadow hover:shadow-lg";
                break;

            case 'btnFormaPagamentoSelecionado':
                $return = "w-full flex flex-row justify-center items-center gap-2 text-center font-bold text-white bg-green-600 border hover:border-green-700 p-2 md:p-4 rounded shadow-sm";
                break;

            case 'divContentM':
                $return = "w-full max-w-4xl mx-auto py-4 px-2 md:px-16 flex flex-col md:flex-row justify-between items-center gap-0 md:gap-8";
                break;

            case 'divContentMax':
                $return = "w-full max-w-7xl mx-auto shadow border";
                break;

            case 'divContentMaxClean':
                $return = "w-full max-w-7xl mx-auto p-2";
                break;

            case 'divContentTitleDiv':
                $return = "w-full max-w-7xl mx-auto py-6 px-2 md:px-16 flex flex-col md:flex-row justify-between items-center gap-0 md:gap-8 bg-white shadow border";
                break;

            case 'divContent':
                $return = "w-full max-w-7xl mx-auto py-4 px-2 md:px-16 flex flex-col md:flex-row justify-between items-center gap-0 md:gap-8 bg-stone-100 shadow border";
                break;

            case 'divContentFull':
                $return = "w-full mx-auto py-4 px-2 md:px-16 flex flex-col md:flex-row justify-between items-center gap-0 md:gap-8 shadow border";
                break;

            case 'divContentHeaderFront':
                $return = "w-full max-w-7xl mx-auto py-4 px-2 md:px-16 flex flex-col md:flex-row justify-between items-center gap-0 md:gap-8";
                break;

            case 'divContentHeaderFull':
                $return = "w-full mx-auto py-4 px-2 md:px-16 flex flex-col md:flex-row justify-between items-center gap-0 md:gap-8";
                break;

            case 'divContentLabel':
                $return = "w-full block text-base font-light uppercase text-black dark:text-gray-400 mb-1";
                break;

            case 'divContentLabelSmall':
                $return = "text-gray-500 text-xs";
                break;

            case 'divContentInput':
                $return = "placeholder-secondary-400 dark:bg-secondary-800 dark:text-secondary-400 dark:placeholder-secondary-500 border border-secondary-300 focus:ring-primary-500 focus:border-primary-500 dark:border-secondary-600 form-input block w-full sm:text-sm rounded-md transition ease-in-out duration-100 focus:outline-none shadow-sm rounded-none";
                break;

            case 'divContentTitle':
                $return = "text-3xl font-light text-gray-500";
                break;

            case 'divContentBtn':
                $return = "w-full max-w-7xl mx-auto py-4 flex flex-col md:flex-row justify-end items-center gap-x-8";
                $return = "w-full max-w-7xl mx-auto py-4 flex flex-col md:flex-row justify-end items-center gap-x-4";
                break;

            case 'divContentErros':
                $return = "w-full max-w-7xl mx-auto my-2";
                break;

            case 'divContentErrosFull':
                $return = "w-full mx-auto my-2";
                break;

            case 'divContentHeader':
                $return = "w-full max-w-7xl mx-auto py-6 px-2 md:px-16 flex flex-col md:flex-row justify-between items-center bg-gradient-to-r from-blue-600 to-indigo-700 text-white shadow-lg rounded-xl";
                break;

            case 'divForItem':
                $return = "gap-4 p-4 border-l-8 border-gray-500 shadow-md rounded-r bg-gray-50";
                break;

            case 'labelTitle':
                $return = "text-xl md:text-lg font-semibold uppercase my-2";
                break;

            case 'uiInput':
                $return = "placeholder-secondary-400 dark:bg-secondary-800 dark:text-secondary-400 dark:placeholder-secondary-500 border border-secondary-300 focus:ring-primary-500 focus:border-primary-500 dark:border-secondary-600 form-input block w-full sm:text-sm rounded-md transition ease-in-out duration-100 focus:outline-none shadow-sm pl-8 rounded-none";
                break;

            case 'btnMenu':
                $return = "w-full inline-block px-6 py-2.5 bg-gray-200 text-gray-700 font-medium text-xs leading-tight uppercase rounded shadow-md hover:bg-gray-300 hover:shadow-lg focus:bg-gray-300 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-gray-400 active:shadow-lg transition duration-150 ease-in-out";
                break;
            case 'menuDropdownItem':
                $return = "dropdown-item text-left text-sm py-2 px-4 font-normal block w-full whitespace-nowrap bg-transparent text-gray-700 hover:bg-gray-100 uppercase";
                break;
            case 'menuDropdownItemActive':
                $return = "dropdown-item text-left text-sm py-2 px-4 font-semibold block w-full whitespace-nowrap text-gray-700 bg-gray-100 uppercase";
                break;
            case 'badgePositive':
                $return = "outline-none inline-flex justify-center items-center group rounded-full gap-x-1 text-xs font-semibold px-2.5 py-0.5 text-white bg-positive-500 dark:bg-positive-700";
                break;
            case 'badgeNegative':
                $return = "outline-none inline-flex justify-center items-center group rounded-full gap-x-1 text-xs font-semibold px-2.5 py-0.5 text-white bg-negative-500 dark:bg-negative-700";
                break;
            case 'badgeInfo':
                $return = "outline-none inline-flex justify-center items-center group rounded-full gap-x-1 text-xs font-semibold px-2.5 py-0.5 text-white bg-info-500 dark:bg-info-700";
                break;
            case 'cardPositiive':
                $return = "w-full outline-none inline-flex justify-center items-center group rounded-sm shadow gap-x-1 text-xs md:text-sm font-semibold px-2.5 py-1 text-white text-center border border-positive-500 bg-positive-500 dark:bg-positive-700";
                break;
            case 'cardNegative':
                $return = "w-full outline-none inline-flex justify-center items-center group rounded-sm shadow gap-x-1 text-xs md:text-sm font-semibold px-2.5 py-1 text-white text-center border border-negative-500 bg-negative-500 dark:bg-negative-700";
                break;
            case 'cardInfo':
                $return = "w-full outline-none inline-flex justify-center items-center group rounded-sm shadow gap-x-1 text-xs md:text-sm font-semibold px-2.5 py-1 text-white text-center border border-info-500 bg-info-500 dark:bg-info-700";
                break;
            case 'cardFlat':
                $return = "w-full outline-none inline-flex justify-center items-center group rounded-sm shadow gap-x-1 text-xs md:text-sm font-semibold px-2.5 py-1 text-gray-600 text-center border bg-gray-50 dark:bg-gray-100";
                break;


            case 'payment_paid':
            case 'payment_pago':
            case 'payment_autorizado':
                $return = "text-white bg-green-700 font-semibold rounded shadow pt-0.5 pb-0.5 px-2 uppercase";
                break;
            case 'payment_pago_parcial':
                $return = "text-white bg-yellow-600 font-semibold rounded shadow pt-0.5 pb-0.5 px-2 uppercase";
                break;
            case 'payment_pendente':
            case 'payment_aguardando_pagamento':
                $return = "text-white bg-blue-500 font-semibold rounded shadow pt-0.5 pb-0.5 px-2 uppercase";
                break;
            case 'payment_em_atraso':
            case 'payment_canceled':
                $return = "text-white bg-red-500 font-semibold rounded shadow pt-0.5 pb-0.5 px-2 uppercase";
                break;
            case 'payment_utilizado':
                $return = "text-white bg-green-700 font-semibold rounded shadow pt-0.5 pb-0.5 px-2 uppercase";
                break;
            case 'payment_agendado':
            case 'payment_disponivel':
                $return = "text-white bg-gray-400 font-light rounded shadow pt-0.5 pb-0.5 px-2 uppercase";
                break;

            case 'statusPago':
                $return = "text-white bg-green-700 font-semibold rounded shadow pt-0.5 pb-0.5 px-2 uppercase";
                break;
            case 'statusPendente':
                $return = "text-white bg-blue-500 font-semibold rounded shadow pt-0.5 pb-0.5 px-2 uppercase";
                break;
            case 'statusCanceled':
                $return = "text-white bg-red-500 font-semibold rounded shadow pt-0.5 pb-0.5 px-2 uppercase";
                break;
            case 'statusUtilizado':
                $return = "text-white bg-green-700 font-semibold rounded shadow pt-0.5 pb-0.5 px-2 uppercase";
                break;
            case 'statusDisponivel':
                $return = "text-white bg-gray-500 font-semibold rounded shadow pt-0.5 pb-0.5 px-2 uppercase";
                break;

            case 'tkt_adicionado':
                $return = "text-blue-700 font-semibold rounded uppercase";
                break;
            case 'tkt_disponivel':
                $return = "text-green-700 font-semibold rounded uppercase";
                break;
            case 'tkt_utilizado':
                $return = "text-black font-semibold rounded uppercase";
                break;
            case 'tkt_pendente':
                $return = "text-gray-500 font-semibold rounded uppercase";
                break;
            case 'tkt_cancelado':
                $return = "text-red-500 font-semibold rounded uppercase";
                break;

            case 'statusEnviadoV2':
                $return = "text-blue-700 font-semibold rounded uppercase";
                break;
            case 'statusPagoV2':
                $return = "text-green-700 font-semibold rounded uppercase";
                break;
            case 'statusPendenteV2':
                $return = "text-gray-500 font-semibold rounded uppercase";
                break;
            case 'statusCanceledV2':
                $return = "text-red-500 font-semibold rounded uppercase";
                break;
            case 'statusEstornadoV2':
                $return = "text-red-500 font-semibold rounded uppercase";
                break;
            case 'statusUtilizadoV2':
                $return = "text-green-700 font-semibold rounded uppercase";
                break;
            case 'statusDisponivelV2':
                $return = "text-gray-500 font-semibold rounded uppercase";
                break;

            case 'divEnviadoV2':
                $return = "border-l-8 border-blue-700 rounded rounded-l-none";
                break;
            case 'divPagoV2':
                $return = "border-l-8 border-green-700 rounded rounded-l-none";
                break;
            case 'divPendenteV2':
                $return = "border-l-8 border-gray-500 rounded rounded-l-none";
                break;
            case 'divCanceledV2':
                $return = "border-l-8 border-red-500 rounded rounded-l-none";
                break;
            case 'divEstornadoV2':
                $return = "border-l-8 border-red-500 rounded rounded-l-none";
                break;
            case 'divUtilizadoV2':
                $return = "border-l-8 border-green-700 rounded rounded-l-none";
                break;
            case 'divDisponivelV2':
                $return = "border-l-8 border-gray-500 rounded rounded-l-none";
                break;
            case 'table_status_utilizado':
                $return = "text-green-700 font-semibold uppercase";
                break;
            case 'table_status_disponivel':
                $return = "text-white bg-gray-500 font-semibold pb-0.5 px-1 uppercase";
                break;
            case 'table_status_utilizado_bg':
                $return = "bg-green-100 text-black font-normal shadow hover:shadow-md uppercase text-sm truncate";
                break;
            case 'table_status_disponivel_bg':
                $return = "bg-gray-100 text-black font-semibold shadow hover:shadow-md uppercase text-sm truncate";
                break;
            case 'table_status_cancelado':
                $return = "text-white bg-red-500 font-semibold pb-0.5 px-1 uppercase";
                break;
            case 'table_status_cancelado_bg':
                $return = "bg-red-100 text-black font-semibold shadow hover:shadow-md uppercase text-sm truncate";
                break;
            default:
                $return = null;
                break;
        }

        return $return . ' ' . $classAdd;
    }
}

// SET SESSIONS
if (!function_exists('sessionReferer'))
{
    function sessionReferer($url=false)
    {
        //
        $referer    = Session::get('referer');
        $requestUrl = request()->url();
        $refererUrl = ($url) ? $url : request()->headers->get('referer');

        //
        if($requestUrl == $refererUrl || in_array($refererUrl,['get']))
            return $referer;

        //
        if(($refererUrl ?? false) && $refererUrl != $referer)
        {
            Session::put('referer', $refererUrl);
            $referer = $refererUrl;
        }

        // echo "<pre>";
        // echo "REQUEST URL.: {$requestUrl}";
        // echo "</br>";
        // echo "REFERER.....: {$referer}";
        // echo "</br>";
        // echo "REFERER URL.: {$refererUrl}";
        // echo "</pre>";
        // echo "<hr>";

        return $referer;
    }
}

if (!function_exists('sessionApp'))
{
    function sessionApp()
    {
        // CUSTOMERS
        if($app = Session::get('app'))
        {
            return $app;
        }

        $app = null;

        //
        if($app = Auth::user()->app->first())
        {
            $app->user_active = $app->pivot->user_active ?? null;
            $app->user_role   = $app->pivot->user_role ?? null;
            //
            unset($app->pivot);
            //
            Session::put('appModules', $app->modules);
        }

        Session::put('app', $app);
        Session::put('userRole', $app->user_role ?? 'user');


        return $app;
    }
}

if (!function_exists('sessionUserRole')) {

    function sessionUserRole()
    {
        return Session::get('userRole') ?? 'user';
    }
}

if (!function_exists('sessionCustomers')) {

    function sessionCustomers($update=false)
    {
        // CUSTOMERS / NAO UPDATE
        if(Session::get('customers') && !$update)
        {
            return Session::get('customers');
        }

        //
        $customers = Auth::user()->customers ?? [];

        // Verificar se é admin ou super-admin
        if(Auth::user() && Auth::user()->app && Auth::user()->app->first()) {
            $userRole = Auth::user()->app->first()->pivot->user_role ?? null;

            if(in_array($userRole, ['admin', 'super-admin'])) {
                // Super-admin vê customers de TODOS os apps (sem filtro de tenant)
                if($userRole === 'super-admin') {
                    $customers = Customer::withoutGlobalScopes()->get();
                }
                // Admin vê apenas customers do app atual (com filtro de tenant)
                else {
                    $customers = Customer::all();
                }
            }
        }

        foreach ($customers ?? [] as $customerKey => $customerValues)
        {
            //
            $customers[$customerKey]->user_active = $customerValues->pivot->user_active ?? null;
            $customers[$customerKey]->user_role   = $customerValues->pivot->user_role ?? null;
            //
            unset($customers[$customerKey]->pivot);
        }

        Session::put('customers', $customers);

        //
        if(count($customers) == 1)
            sessionCustomer($customers->first()->id);

        return $customers;
    }
}

if (!function_exists('sessionCustomer')) {

    function sessionCustomer($id=false)
    {
        if($id ?? false)
        {
            $customers = sessionCustomers();
            $customer  = $customers->find($id);
            Session::put('customer', $customer);
        }

        return Session::get('customer');
    }
}

if (!function_exists('sessionOrganizations')) {

    function sessionOrganizations()
    {
        //  ORGANIZATION
        if(Session::get('organizations'))
            return Session::get('organizations');

        //
        $organizations = [];

        //
        if($customer = sessionCustomer())
        {
            //
            if(Auth::user()->customerOrganization->count())
            {
                $customerOrganizationIds = array_keys(Auth::user()->customerOrganization->groupBy('id')->toArray());

                $organizations = $customer->organizations->whereIn('id',$customerOrganizationIds) ?? [];
            }
            else
            {
                $organizations = $customer->organizations ?? [];
            }
        }

        Session::put('organizations', $organizations);

        //
        if(count($organizations) == 1)
            sessionOrganization($organizations->first()->id);

        return $organizations;
    }
}

if (!function_exists('sessionOrganization')) {

    function sessionOrganization($id=false)
    {
        if($id ?? false)
        {
            $organizations = sessionOrganizations();
            $organization  = $organizations->find($id);
            Session::put('organization', $organization);
        }

        return Session::get('organization');
    }
}

if (!function_exists('sessionOrganizationSubs')) {

    function sessionOrganizationSubs()
    {
        //  ORGANIZATION
        if(Session::get('organizationSubs'))
            return Session::get('organizationSubs');

        //
        $organizationSubs = [];

        //
        if ($organization = sessionOrganization())
        {
            // dd(
            //     $organization->toArray(),
            //     $organization->organizationSubs,
            //     Auth::user()->customerOrganizationSub->count(),
            // );

            //
            if(Auth::user()->customerOrganizationSub->count())
            {
                $customerOrganizationSubIds = array_keys(Auth::user()->customerOrganizationSub->groupBy('id')->toArray());

                $organizationSubs = $organization->organizationSubs->whereIn('id',$customerOrganizationSubIds) ?? [];
            }
            else
            {
                $organizationSubs = $organization->organizationSubs ?? [];
            }
        }

        Session::put('organizationSubs', $organizationSubs);

        //
        if(count($organizationSubs) == 1)
            sessionOrganizationSub($organizationSubs->first()->id);

        return $organizationSubs;
    }
}

if (!function_exists('sessionOrganizationSub')) {

    function sessionOrganizationSub($id=false)
    {
        if($id ?? false)
        {
            $organizationSubs = sessionOrganizationSubs();
            $organizationSub  = $organizationSubs->find($id);
            Session::put('organizationSub', $organizationSub);
        }

        return Session::get('organizationSub');
    }
}

if (!function_exists('sessionOrganizers'))
{
    function sessionOrganizers($organization_id=false)
    {
        $organizers = Auth::user()->organizers ?? false;

        // SE ADMIN DA APLICAÇÃO
        if(Auth::user() && Auth::user()->app && Auth::user()->app->first()) {
            $userRole = Auth::user()->app->first()->pivot->user_role ?? null;

            if(in_array($userRole, ['admin', 'super-admin'])) {
                $organizers = CustomerOrganizer::all();
            }
        }
        elseif($customer = sessionCustomer() ?? false)
        {
            // SE OWNER
            if($userCustomer = UserCustomer::where('user_id',Auth::user()->id)->where('customer_id',$customer->id)->first())
            {
                if($userCustomer->user_role == "owner")
                {
                    $organizers = CustomerOrganizer::where('customer_id',$userCustomer->customer_id)->get();
                }
            }
        }

        //
        if($organization_id ?? false)
        {
            $organizers = sessionOrganizers(); // GARANTE RETORNO SOMENTE DE APENAS OS SEUS ORGANIZADORES
            $organizers = $organizers->where('organization_id',$organization_id);
        }

        Session::put('organizers', $organizers);

        return $organizers;
    }
}

if (!function_exists('sessionOrganizer'))
{
    function sessionOrganizer($id=false)
    {
        if($id && $organizers = Session::get('organizers')) // PARA NAO ENTRAR EM LOOP INFINITO
        {
            if($organizer = $organizers->find($id))
            {
                $organizer->user_active = $organizer->pivot->user_active ?? null;
                $organizer->user_role   = $organizer->pivot->user_role ?? null;
                unset($organizer->pivot);

                //
                if($userOrganizer = Auth::user()->organizers->find($organizer->id))
                {
                    $organizer->user_active = $userOrganizer->user_active ?? null;
                    $organizer->user_role   = $userOrganizer->user_role ?? null;
                }

                //
                if(!$organizer->user_role && $app = sessionApp())
                {
                    $organizer->user_active = $app->user_active ?? null;
                    $organizer->user_role   = $app->user_role ?? null;
                }

                //
                sessionUserRole($organizer->user_role);
                Session::put('organizer', $organizer);
                Session::put('organizerId', $organizer->id);
            }
        }

        return Session::get('organizer');
    }
}

if (!function_exists('sessionUserRole'))
{
    function sessionUserRole($value=false)
    {
        if($value)
        {
            Session::put('userRole', $value);
        }

        return Session::get('userRole');
    }
}

if (!function_exists('sessionTargetRef'))
{
    function sessionTargetRef($value=false)
    {
        if($value)
        {
            Session::put('targetRef', $value);
        }

        return Session::get('targetRef');
    }
}

if (!function_exists('sessionTargetId'))
{
    function sessionTargetId($id=false)
    {
        if($id ?? false)
        {
            Session::put('targetId', $id);
        }

        return Session::get('targetId');
    }
}

if (!function_exists('sessionClear'))
{

    function sessionClear($clear='all')
    {
        switch ($clear) {
            case 'all':
                Session::forget('app');
                Session::forget('target_ref');
                Session::forget('target_id');
            case 'customer':
                Session::forget('customers');
                Session::forget('customer');
            case 'organization':
                Session::forget('organizations');
                Session::forget('organization');
            case 'organizationSub':
                Session::forget('organizationSubs');
                Session::forget('organizationSub');
            case 'organizers':
                Session::forget('organizers');
            case 'organizer':
                Session::forget('organizer');
                break;
            case 'target':
                Session::forget('targetRef');
                Session::forget('targetId');
                break;
            case 'orderId':
                Session::forget('orderId');
                break;
            case 'pedido':
                Session::forget('pedido');
            case 'slipPaymentSelectId':
                Session::forget('slipPaymentSelectId');
                break;
        }
        return true;
    }
}

if (!function_exists('sessionOrderId')) {

    function sessionOrderId($id=false)
    {
        if($id ?? false)
        {
            Session::put('orderId', $id);
        }

        return Session::get('orderId');
    }
}

if (!function_exists('sessionOrderIdClear')) {

    function sessionOrderIdClear()
    {
        Session::forget('orderId');

        return Session::get('orderId');
    }
}

if (!function_exists('sessionOrdersEvent')) {

    function sessionOrdersEvent($order=false)
    {
        $ordersEvent = Session::get('ordersEvent');

        if($order ?? false)
        {
            $ordersEvent[$order['event_id']] = $order;

            Session::put('ordersEvent', $ordersEvent);
        }

        return Session::get('ordersEvent');
    }
}

if (!function_exists('sessionPedido')) {

    function sessionPedido($dadosPedido=false)
    {
        if($dadosPedido ?? false)
        {
            Session::put('pedido', $dadosPedido);
        }

        return Session::get('pedido');
    }
}

if (!function_exists('myClass')) {

    function myClass($class=false)
    {
        switch ($class) {
            case 'card':
                return "block rounded-lg shadow-lg bg-white max-w-sm";
            case 'card_header':
                return "py-1 px-3 border-b border-gray-300 text-gray-500 font-light";
            case 'card_body':
                return "py-1 px-3 text-gray-700 text-base mb-4";
            default:
                return null;
        }
    }
}

if (!function_exists('formatAddress'))
{
    function formatAddress($address=false,$address_number=false,$address_complement=false,$address_reference=false,$city_neighborhood=false,$city=false,$state=false,$zip_code=false) :string
    {
        $endereco = $address . ' ' . $address_number;
        //
        if($address_complement ?? false)
            $endereco .= ' - ' . $address_complement;
        //
        if($address_reference ?? false)
            $endereco .= ' (' . $address_reference . ')';
        //
        if($city_neighborhood ?? false)
            $endereco .= ' - ' . $city_neighborhood;
        //
        if($city ?? false)
            $endereco .= ' - ' . $city;
        //
        if($state ?? false)
            $endereco .= '/' . $state;
        //
        if($zip_code ?? false)
            $endereco .= ' CEP:' . $zip_code;

        $endereco = trim($endereco);

        return empty($endereco) ? '--' : $endereco;
    }
}

if (!function_exists('zeroEsquerda')) {

    function zeroEsquerda($numero, $tamanho_desejado=2,$preencher_com='0')
    {
        return str_pad($numero, $tamanho_desejado, $preencher_com, STR_PAD_LEFT);
    }
}

if (!function_exists('compCard'))
{
    function compCard($header=null,$body=null,$spanNum=1,$translate=true,$href=false) :string
    {
        $header = empty($header) ? '--' : $header;
        $body   = empty($body)   ? '--' : $body;

        if($href ?? false)
        {
            $body = "<a href='{$body}' class='text-blue-600' target='_blank'>{$body}</a>";
        }

        if($translate ?? false)
        {
            $header = __($header);
            $body   = __($body);
        }

        $div   = "";
        $div  .= <<<EOT
        <div class="col-span-{$spanNum} w-full block shadow bg-white border text-sm">
        EOT;

        if ($header ?? false)
        {
            $div  .= <<<EOT
                <div class="py-1 px-3 border-b bg-gray-50 border-gray-300 text-gray-700 text-xs font-normal uppercase" title="{$header}">{$header}</div>
            EOT;
        }

        if ($body ?? false)
        {
            $div  .= <<<EOT
                <div class="py-1 px-3 text-gray-700">
                    <div class="truncate" title="{$body}">{$body}</div>
                </div>
            EOT;
        }

        $div  .= <<<EOT
        </div>
        EOT;

        return $div;
    }
}

if (!function_exists('convertTruncate')) {

    function convertTruncate(string $value = null, $len = 50, $sufix='...')
    {
        if (strlen($value) > $len)
        {
            return substr($value, 0, $len) . $sufix;
        }

        return $value;
    }
}

if (!function_exists('convertToDate')) {

    function convertToDate(string $value = null, $mask = 'd/m/Y')
    {
        return date($mask,strtotime($value));
    }
}

if (!function_exists('dateAge')) {

    function dateAge(string $value = null)
    {
        if($value ?? false)
        return Carbon::create($value)->age;
        else
        return '--';
    }
}

if (!function_exists('dateAgo')) {

    function dateAgo(string $value = null)
    {
        if($value ?? false)
            return Carbon::create($value)->ago();
        else
            return '--';
    }
}

if (!function_exists('convertToTime')) {

    function convertToTime(string $value = null, $mask = 'H:i')
    {
        return date($mask,strtotime($value));
    }
}

if (!function_exists('convertToDateTime')) {

    function convertToDateTime(string $value = null, $mask = 'd/m/Y H:i')
    {
        return date($mask,strtotime($value));
    }
}

if (!function_exists('convertToTimestamp')) {

    function convertToTimestamp(string $value = null)
    {
        if($value ?? false)
            return Carbon::create($value)->timestamp;
        else
            return now()->timestamp;
    }
}

if (!function_exists('convertInt2Float'))
{
    function convertInt2Float($valor,$decimal=2,$separador='.')
    {
        $decimal = $decimal * (-1);

        $valor = substr_replace($valor, $separador, $decimal, 0);

        return $valor;
    }
}

if (!function_exists('convertDecimalInt'))
{
    function convertDecimalInt($stringNumero)
    {
        if(in_array($stringNumero,['0.0','0,0']))
        {
            return 0;
        }

        if($stringNumero ?? false)
        {
            $stringNumero = preg_replace('/[^0-9.,]/', '', $stringNumero);
            $stringNumero = str_replace(',', '.', $stringNumero);
            $stringNumero = round($stringNumero,2);

            $partes = explode('.', $stringNumero);

            if(count($partes) > 1)
            {
                $centavos = str_pad(end($partes), 2, '0', STR_PAD_RIGHT);
                unset($partes[array_key_last($partes)]);
                $valorInteiro = implode('',$partes);
                //
                $valorInteiro = ($valorInteiro . $centavos);
            }
            else
            {
                $valorInteiro = number_format($stringNumero,2,'','');
            }

            return intval($valorInteiro);
        }

        return $stringNumero;
    }
}

if (!function_exists('convertMoney'))
{
    function convertMoney($valor=0,$prefix=false,$milhar='.',$decimal=',')
    {
        $number = round($valor / 100, 2);

        $number =  number_format($number, 2, $decimal, $milhar);

        if($prefix ?? false)
            return $prefix .' ' . $number;
        else
            return $number;
    }
}

if (!function_exists('toSlug')) {

    function toSlug($text,$separador='_')
    {
        return Str::slug($text,$separador);
    }
}

if (!function_exists('dataCarbon')) {

    function dataCarbon($data=false,$format=false,$ago=false,$age=false,$returnNull=false)
    {
        if($data)
        {
            $dateCreate = Carbon::create($data);
            $date       = $dateCreate;

            if($format ?? false)
            {
                $date = $dateCreate->format($format);
            }

            if($ago ?? false)
            {
                $date .= ' ' . $dateCreate->ago();
            }

            if($age ?? false)
            {
                $date .= ' ' . $dateCreate->age . ' anos';
            }

            return $date;
        }

        return $returnNull ? null : '--';
    }
}

if (!function_exists('dataData')) {

    function dataData($data,$age=false,$ago=false)
    {
        return dataCarbon($data,'d/m/Y',age:($age??false),ago:($ago??false));
    }
}

if (!function_exists('dataDataHora')) {

    function dataDataHora($data,$age=false,$ago=false)
    {
        return dataCarbon($data,'d/m/Y H:i',age:($age??false),ago:($ago??false));
    }
}

if (!function_exists('dataHora')) {

    function dataHora($data)
    {
        return dataCarbon($data,'H:i');
    }
}

if (!function_exists('dataAge')) {

    function dataAge($data,$sufixo=false)
    {
        $age = dataCarbon($data)->age;

        if($sufixo ?? false)
        {
            return $age . ' ' . $sufixo;
        }

        return $age;
    }
}

if (!function_exists('dataAgo')) {

    function dataAgo($data,$sufixo=false)
    {
        $age = dataCarbon($data)->ago();

        if($sufixo ?? false)
        {
            return $age . ' ' . $sufixo;
        }

        return $age;
    }
}

if (!function_exists('dataDuracao')) {

    function dataDuracao($data1,$data2)
    {
        $data1 = Carbon::create($data1);
        $data2 = Carbon::create($data2);
        //
        return $data1->diffInHours($data2) . ' horas';
    }
}

if (!function_exists('dataDiferenca')) {

    function dataDiferenca($data1,$data2=false)
    {
        if(!$data2 ?? false)
        {
            $data2 = now()->format('Y-m-d 00:00:00');
        }

        $data1 = Carbon::create($data1);
        $data2 = Carbon::create($data2);
        //
        return $data2->diffInSeconds($data1);
    }
}

if (!function_exists('dataDiferencaDias')) {

    function dataDiferencaDias($data1,$data2=false)
    {
        if(!$data2 ?? false)
        {
            $data2 = now()->format('Y-m-d 00:00:00');
        }

        $data1 = Carbon::create($data1);
        $data2 = Carbon::create($data2);
        //
        return $data1->format('Ymd') - $data2->format('Ymd');
    }
}

if (!function_exists('calculaSegundosDif')) {

    function calculaSegundosDif($date1=false,$date2=false)
    {
        if(!$date2)
            $date2 = now();

        if($date1)
        {
            return strtotime($date1) - strtotime($date2);
        }

        return 0;
    }
}

if (!function_exists('calculaPorcentagem')) {

    function calculaPorcentagem($vTotal=false,$vCalculo=false,$sufixo=null,$prefix=null)
    {
        $porcentagem = 0;

        if($vTotal ?? false)
        {
            $porcentagem = ((int) $vCalculo * 100) / (int) $vTotal;
            $porcentagem = number_format($porcentagem,2,',');
        }

        //
        if ($sufixo ?? false)
            $porcentagem = $porcentagem . $sufixo;

        //
        if ($prefix ?? false)
            $porcentagem = $prefix . $porcentagem;

        return $porcentagem;
    }
}

if (!function_exists('formatDateStartFinish')) {

    function formatDateStartFinish($start=null, $finish=null, bool $ago=true)
    {
        $date = '';

        if ($start)
        {
            $date = $start->format('d/m/Y - H:i');

            if ($finish ?? false)
            {
                if ($start->format('d/m/Y') == $finish->format('d/m/Y'))
                    $date .= ' às ' . $finish->format('H:i');
                else
                    $date .= ' até ' . $finish->format('d/m/Y H:i');
            }

            if($ago ?? false)
            {
                if ($finish ?? false)
                {
                    $carbon = Carbon::create($finish);
                }
                else
                {
                    $carbon = Carbon::create($start);
                }

                $date .= ' ' . $carbon->ago();
            }
        }

        return $date;
    }
}

if (!function_exists('statusByDate')) {

    function statusByDate($start=null, $finish=null, bool $ago=true)
    {
        $status = 'EM ANDAMENTO';

        $now  = now()->format('YmdHis');

        if ($finish ?? false)
        {
            $finish = $finish->format('YmdHis');

            if($finish < $now)
            {
                $status = 'REALIZADO';
            }
        }
        else
        {
            $start = $start->format('YmdHis');

            if($start < $now)
            {
                $status = 'REALIZADO';
            }
        }

        return $status;
    }
}

// SAFE 2 PAY
if (!function_exists('safe2payValidarPagamento'))
{
    function safe2payValidarPagamento($orderId,$nsu,$paymentId,$token=false,$targetType=null)
    {
        //
        if (in_array($targetType, ['evento_patrocinador'])) {
            $eventOrder = AppEventOrderSponsorship::with(['payments','event','event.gatewayPay'])->find($orderId);
        } else {
            $eventOrder = AppEventOrder::with(['payments','itens','event','event.customer','event.gatewayPay'])->find($orderId);
        }
        $eventOrderPayment = $eventOrder->payments->find($paymentId);
        $eventOrderPayment->pay_nsu = $nsu;
        $eventOrderPayment->save();

        // SO PRODUÇÃO
        $token   = $token ?? $eventOrder->event->gatewayPay->token_live;
        $sandbox = false;

        //
        $gatewayService    = new Safe2PayService($token,$sandbox ?? false); // PIX SOMENTE PRODUÇÃO (SEM SANDBOX)
        $consultaTransacao = $gatewayService->consultaTransacao($nsu);
        $processaResponse  = $gatewayService->processaResponse($consultaTransacao);

        // if($nsu == '104154544')
        // {
        //     dd($processaResponse);
        // }

        // SE ERRO
        if($processaResponse->error ?? false)
        {
            if(in_array($processaResponse->code,[6]) && ($eventOrderPayment ?? false))
            {
                // SE STATUS ANTERIOR DIFERENTE
                if(mb_strtolower($processaResponse->msg) != $eventOrderPayment->status)
                {
                    // SUCESSO - PAGAMENTO
                    $eventOrderPayment->update([
                        'status'              => mb_strtolower($processaResponse->msg),
                        'status_old'          => $eventOrderPayment->status,
                        'status_old_datetime' => now()->format('Y-m-d H:i:s'),
                        "description"         => $processaResponse->msg,
                        "paid_description"    => $processaResponse->msg_sub,
                        "pay_datetime"        => $processaResponse->datahora,
                        "value_paid"          => $processaResponse->valor,
                        "value_liquid"        => $processaResponse->valor,
                        "value_fees"          => 0,
                    ]);
                }
            }

            session()->flash('modal_error',$processaResponse->msg);
            session()->flash('modal_error_sub',$processaResponse->msg_sub ?? ('TRANSAÇÃO ' . $processaResponse->nsu));
            return $processaResponse;
        }

        // SE NAO OK = PENDENTE
        if(!$processaResponse->pagamento_ok ?? false)
        {
            session()->flash('modal_info',$processaResponse->msg);
            session()->flash('modal_info_sub',$processaResponse->msg_sub);
            return $processaResponse;
        }

        //
        if(!$eventOrderPayment ?? false)
        {
            return $processaResponse;
        }

        // ORDER JA PAID
        if(in_array($eventOrder->status,['paid']))
        {
            $statusOldPaid       = true;
            $status_old          = $eventOrder->status_old;
            $status_old_datetime = $eventOrder->status_old_datetime;
        }
        else
        {
            $statusOldPaid       = false;
            $status_old          = $eventOrder->status;
            $status_old_datetime = now()->format('Y-m-d H:i:s');
        }

        // SUCESSO - ORDER
        $eventOrder->update([
            'status'                       => 'paid',
            'status_old'                   => $status_old,
            'status_old_datetime'          => $status_old_datetime,
            'order_amount_received'        => $processaResponse->pagamento_valor ?? 0,
            'order_amount_received_liquid' => $processaResponse->pagamento_liquido ?? 0,
            'reservation_expiration_date'  => null,
        ]);

        // SUCESSO - PAGAMENTO
        $eventOrderPayment->update([
            'status'              => 'paid',
            'status_old'          => $eventOrderPayment->status,
            'status_old_datetime' => now()->format('Y-m-d H:i:s'),
            "description"         => $processaResponse->msg,
            "paid_description"    => $processaResponse->msg_sub,
            "pay_datetime"        => $processaResponse->datahora,
            "value_paid"          => $processaResponse->pagamento_valor,
            "value_liquid"        => $processaResponse->pagamento_liquido,
            "value_fees"          => $processaResponse->pagamento_taxa,
        ]);

        // TRATA TICKETS (apenas para pedidos de evento)
        if (!in_array($targetType, ['evento_patrocinador'])) {
            trataTicketsEvento($eventOrder->id,'disponivel');
        }

        // SE STATUS OLD NAO PAID = NOTIFICAÇÃO - EMAIL
        if(!$statusOldPaid ?? false)
        {
            // Notificação somente para pedidos de evento (PagamentoSucesso mail não suporta AppEventOrderSponsorship)
            if (!in_array($targetType, ['evento_patrocinador'])) {
                notificaEmail(emailTo:$eventOrder->buyer_email ?? config('mail.fallback_buyer'),tipo:'pagamento-sucesso',uuid:$paymentId);
            }
        }

        //
        sessionClear('pedido');

        //
        session()->flash('modal_pagamento_success',$processaResponse->msg ?? 'PAGAMENTO REALIZADO');
        session()->flash('modal_pagamento_success_sub',$processaResponse->msg_sub ?? 'SUCESSO');

        return $processaResponse;
    }
}

// VALIDAR TODOS OS PAGAMENTOS REALIZADOS + CONCLUSÃO DA ORDEM
if (!function_exists('consolidaOrderPayments'))
{
    function consolidaOrderPayments($orderId=false,$paymentId=false,$forceUpdate=false)
    {
        // START
        $orderPaymentsTotalAmount = 0;
        $order = false;

        if($orderId ?? false)
        {
            $order = AppEventOrder::with(['payments'])->find($orderId);
        }
        elseif($paymentId ?? false)
        {
            if($orderPayment = AppPayment::find($paymentId))
            {
                $order = AppEventOrder::with(['payments'])->find($orderPayment->app_ref_order_id);
            }
        }

        // PEGA ORDEM
        if($order ?? FALSE)
        {
            // SE EXISTEM PAGAMENTOS
            if($order->payments->count())
            {
                // SE FORCE ATUALIZAR ou VALIDAR UM PAYMENT ID
                if(($forceUpdate ?? false) || ($paymentId ?? false))
                {
                    // PERCORRE PAGAMENTOS
                    foreach ($order->payments as $paymentKey => $paymentValues)
                    {
                        // SE FOR UM PAGAMENTO ESPECIFICO
                        if(($paymentId ?? false) && ($paymentValues->id != $paymentId))
                        {
                            continue;
                        }

                        // SE NÃO TEM GATEWAY VINCULADO, PULA
                        if(!$paymentValues->gateway)
                        {
                            continue;
                        }

                        // SE SANDBOX
                        if($paymentValues->gateway_sandbox ?? false)
                        {
                            $token = $paymentValues->gateway->token_test;
                        }
                        else
                        {
                            $token = $paymentValues->gateway->token_live;
                        }

                        //
                        $service = new Safe2PayService($token,$paymentValues->gateway_sandbox ?? false); // PIX SOMENTE PRODUÇÃO (SEM SANDBOX)

                        // CONSULTA
                        $consulta = $service->consultaTransacao($paymentValues->pay_nsu,TRUE);

                        // ### APENAS PARA TESTE
                        if(in_array($order->buyer_email,['proeventpay@gmail.com']))
                        {
                            if (($paymentValues->gateway_sandbox ?? false) && in_array($paymentValues->pay_type,["pix","slip_pix"]) && in_array($paymentValues->status,['pending','pending_pix','pendente']))
                            {
                                // $consulta = $service->consultaTransacao('104889285',TRUE);
                                // $consulta = $service->consultaTransacao('100434607',TRUE);
                                $consulta = $service->consultaTransacao('106847347',TRUE);
                            }
                        }

                        // SE ESTORNADO - ENRIQUECE PAYMENT
                        if(in_array($consulta->status,["estornado"]))
                        {
                            // SET LABEL
                            $statusPaid         = mb_strtolower(__($consulta->status));
                            $paidLabel          = toMoney($consulta->valor,'R$ ') . ' ESTORNADO';
                            $paid_description   = $consulta->msg;
                            $pay_value_paid     = $consulta->valor;
                            $pay_value_fees     = 0;
                            $pay_value_liquid   = 0;

                            //
                            session()->flash('forma_pagamento_success','PAGAMENTO ESTORNADO');
                            session()->flash('forma_pagamento_success_sub','DEVOLEVEMOS SEU PAGAMENTO - ' . $consulta->valor);

                            // ATUALIZAR PAYMENT PAID
                            $order->payments[$paymentKey]->status_old         = ($paymentValues->status == $paymentValues->status_old) ? $paymentValues->status_old : $paymentValues->status;
                            $order->payments[$paymentKey]->status             = $statusPaid;
                            $order->payments[$paymentKey]->description        = $consulta->msg;
                            $order->payments[$paymentKey]->paid_label         = $paidLabel;
                            $order->payments[$paymentKey]->paid_description   = $paid_description;
                            $order->payments[$paymentKey]->value_amortization = 0;
                            //
                            $order->payments[$paymentKey]->pay_type           = ($paymentValues->pay_type == 'slip_pix') ? $paymentValues->pay_type : $consulta->pagamento_forma_slug;
                            $order->payments[$paymentKey]->pay_datetime       = $consulta->pagamento_datahora;
                            $order->payments[$paymentKey]->pay_value_paid     = $pay_value_paid;
                            $order->payments[$paymentKey]->pay_value_fees     = $pay_value_fees;
                            $order->payments[$paymentKey]->pay_value_liquid   = $pay_value_liquid;
                        }

                        // SE PAGAMENTO > ENRIQUECE PAYMENT
                        if($consulta->pagamento_ok ?? false)
                        {
                            // SET LABEL
                            $valuePaid          = $paymentValues->value_paid; // VALOR A SER PAGO
                            $paidLabel          = toMoney($valuePaid,'R$ ');
                            $statusPaid         = mb_strtolower(__($consulta->status));
                            $paid_description   = $consulta->msg;
                            $pay_value_paid     = $consulta->pagamento_valor;
                            $pay_value_fees     = $consulta->pagamento_taxa;
                            $pay_value_liquid   = $consulta->pagamento_liquido;
                            $value_amortization = $paymentValues->value_amortization;

                            // SE NÃO VALOR TOTAL
                            if($consulta->pagamento_valor < $paymentValues->value_paid)
                            {
                                $statusPaid         = 'pago_parcial';
                                $paidLabel          = toMoney($consulta->pagamento_valor,'R$ ') . ' de ' . $paidLabel;
                                $paid_description  .= ' - Pagamento Parcial';
                                $value_amortization = $consulta->pagamento_valor; // SE MENOR, AJUSTA AMORTIZACAO
                            }

                            //
                            session()->flash('forma_pagamento_success','PAGAMENTO CONFIRMADO');
                            session()->flash('forma_pagamento_success_sub','RECEBEMOS SEU PAGAMENTO - ' . $paidLabel);
                            //
                            session()->flash('modal_pagamento_success','PAGAMENTO CONFIRMADO');
                            session()->flash('modal_pagamento_success_sub','RECEBEMOS SEU PAGAMENTO - ' . $paidLabel);

                            // ATUALIZAR PAYMENT PAID
                            $order->payments[$paymentKey]->status_old         = ($statusPaid == $paymentValues->status_old) ? $paymentValues->status_old : $paymentValues->status;
                            $order->payments[$paymentKey]->status             = $statusPaid;
                            $order->payments[$paymentKey]->description        = $consulta->msg;
                            $order->payments[$paymentKey]->paid_label         = $paidLabel;
                            $order->payments[$paymentKey]->paid_description   = $paid_description;
                            $order->payments[$paymentKey]->value_amortization = $value_amortization;
                            //
                            $order->payments[$paymentKey]->pay_type           = ($paymentValues->pay_type == 'slip_pix') ? $paymentValues->pay_type : $consulta->pagamento_forma_slug;
                            $order->payments[$paymentKey]->pay_datetime       = $consulta->pagamento_datahora;
                            $order->payments[$paymentKey]->pay_value_paid     = $pay_value_paid;
                            $order->payments[$paymentKey]->pay_value_fees     = $pay_value_fees;
                            $order->payments[$paymentKey]->pay_value_liquid   = $pay_value_liquid;

                            // SE PAGAMENTO OK E SLIP >> REMOVE EXPIRAÇÃO
                            $order->reservation_expiration_date = null;
                            $order->save();
                        }

                        // ATUALIZAR PAYMENT
                        $order->payments[$paymentKey]->pay_card_first    = $consulta->pay_card_first ?? null;
                        $order->payments[$paymentKey]->pay_card_last     = $consulta->pay_card_last ?? null;
                        $order->payments[$paymentKey]->pay_card_name     = $consulta->pay_card_name ?? null;
                        $order->payments[$paymentKey]->pay_card_brand    = $consulta->pay_card_brand ?? null;
                        $order->payments[$paymentKey]->pay_postback_url  = $consulta->callback_url;
                        $order->payments[$paymentKey]->pay_json_response = json_encode($consulta);
                        $order->payments[$paymentKey]->save();

                        // SE PAGO e sendo SLIP
                        if(($consulta->pagamento_ok ?? false) && ($paymentValues->order_slip_id ?? false) && ($paymentValues->slip ?? false))
                        {
                            // ATUALIZA PAGAMENTO
                            $order->payments[$paymentKey]->slip->update([
                                "paid_datetime"                  => $order->payments[$paymentKey]->pay_datetime,
                                "paid_value"                     => $order->payments[$paymentKey]->pay_value_paid,
                                "paid_label"                     => $order->payments[$paymentKey]->paid_label,
                                "installment_value_amortization" => $order->payments[$paymentKey]->value_amortization,
                                "status"                         => $order->payments[$paymentKey]->status,
                            ]);

                            // ABRE O PRÓXIMO PAGAMENTO
                            if($paymentNext = $order->payments[$paymentKey]->slip->paymentNext)
                            {
                                // SE PROXIMA NAO ESTIVER PAGO
                                if(!in_array($paymentNext->status,listPaymentStatusPaid()))
                                {
                                    $paymentNext->update([
                                        "status" => 'aguardando_pagamento',
                                    ]);

                                    //
                                    session()->flash('forma_pagamento_info','PRÓXIMA PARCELA JÁ DISPONÍVEL');
                                }
                            }

                            // GARANTE QUE NAO VAI ABRIR PAGAMENTO SLIP
                            Session::forget('slipPaymentSelectId');
                        }

                        // SE NAO NOTIFICADO >> NOTIFICA PAGAMENTO
                        if(($consulta->pagamento_ok ?? false) && (!$paymentValues->notifica_sucesso_datahora))
                        {
                            // SET REPLY
                            $reply = $order->event->organizer->owner_email ?? ($order->event->customer->comercial_contact_email ?? false);

                            // ENVIA NOTIFICAÇÃO - EMAIL
                            if($r = notificaPagamentoConfirmado(email:$order->buyer_email ?? config('mail.fallback_buyer'),paymentId:$paymentValues->id,orderId:$order->id,reply:$reply))
                            {
                                $order->payments[$paymentKey]->notifica_sucesso = 'pagamento-confirmado';
                                $order->payments[$paymentKey]->notifica_sucesso_datahora = now()->format('Y-m-d H:i:s');
                                $order->payments[$paymentKey]->save();
                            }

                            // ENVIA NOTIFICAÇÃO - WHATSAPP
                            // TODO: CRIAR

                            sleep(2);
                        }
                    }

                    // ATUALIZA ORDER
                    $order = AppEventOrder::with(['payments'])->find($orderId);
                }

                // PEGA PAGAMENTOS REALIZADOS
                if($orderPaymentsPaid = $order->payments->whereIn('status',listPaymentStatusPaid()))
                {
                    // DEFINE VALOR TOTAL A SER PAGO
                    $orderAmount = $order->order_amount;

                    // SE POSSUI DESCONTO
                    if($order->code_promo_id ?? false)
                    {
                        $orderAmount = $order->code_promo_price_new ?? ($order->order_amount - $order->code_promo_discount_amount);
                    }

                    // SE PAGAMENTOS REALIZADOS
                    if($orderPaymentsPaid->count() ?? false)
                    {
                        //
                        $orderPaymentsTotalAmount = 0;
                        $orderPaymentsTeste = [];

                        // PERCORRE PAGAMENTOS OK
                        foreach ($orderPaymentsPaid as $paidKey => $paidValues)
                        {
                            // VALOR CONSIDERADO PARA ABATIMENTO
                            $paid = $paidValues->value_amortization ?? $paidValues->value_liquid;

                            // SE VALOR PAGO MENOR QUE ESPERADO
                            if($paidValues->pay_value_paid < $paid)
                            {
                                $paid = $paidValues->pay_value_paid; // VALOR PAGO REAL
                            }

                            $orderPaymentsTotalAmount += (int) ($paid ?? 0);

                            // GROUP APPEND VALORES PAGOS
                            $orderPaymentsTeste[] = [
                                'id'                => $paidValues->id,
                                'pay_value_paid'    => $paidValues->pay_value_paid,
                                'value_amortization'=> $paidValues->value_amortization,
                                'value_liquid'      => $paidValues->value_liquid,
                                'status'            => $paidValues->status,
                            ];
                        }

                        // SE PAGAMENTO TOTAL ORDEM
                        if($orderPaymentsTotalAmount >= $orderAmount)
                        {
                            // TRATA TICKETS
                            $trataTickets = trataTicketsEvento($order->id,'disponivel');

                            // SE NAO NOTIFICOU
                            if(!$order->notifica_sucesso_datahora ?? false)
                            {
                                // ENVIA NOTIFICAÇÃO - EMAIL
                                if(notificaEmail(emailTo:$order->buyer_email ?? config('mail.fallback_buyer'),tipo:'compra-confirmada',uuid:$order->id))
                                {
                                    $notifica_sucesso          = 'compra-confirmada';
                                    $notifica_sucesso_datahora = now()->format('Y-m-d H:i:s');
                                }

                                // ENVIA NOTIFICAÇÃO - WHATSAPP
                                // TODO: CRIAR
                            }

                            // ATUALIZA ORDER
                            $order->update([
                                'status'                      => 'paid',
                                'status_old'                  => ($order->status_old == 'paid') ? $order->status_old : $order->status,
                                'status_old_datetime'         => ($order->status_old == 'paid') ? $order->status_old_datetime : now()->format('Y-m-d H:i:s'),
                                'notifica_sucesso'            => $notifica_sucesso ?? $order->notifica_sucesso,
                                'notifica_sucesso_datahora'   => $notifica_sucesso_datahora ?? $order->notifica_sucesso_datahora,
                                'payment_id'                  => $paidValues->id,
                                'reservation_expiration_date' => null,
                                'order_amount_pay'            => $orderPaymentsTotalAmount,
                            ]);

                            //
                            sessionClear('pedido');

                            //
                            session()->flash('forma_pagamento_success','SUCESSO');
                            session()->flash('forma_pagamento_success_sub','🎉 PAGAMENTO CONFIRMADO');

                            //
                            session()->flash('success','🎉 COMPRA FINALIZADA!');
                        }
                        else // SE NAO QUITAR O PEDIDO
                        {
                            //
                            session()->flash('forma_pagamento_warning','COMPRA COM QUITAÇÃO PARCIAL');
                            session()->flash('forma_pagamento_warning_sub',' - AINDA EXISTE SALDO PARA PAGAMENTO');

                            // ATUALIZA ORDER
                            $order->update([
                                'status'           => (($order->slip_id ?? false) ? 'pending_slip' : 'pago_parcial'),
                                'order_amount_pay' => $orderPaymentsTotalAmount,
                            ]);

                            // TRATA TICKETS
                            $trataTickets = trataTicketsEvento($order->id,'reserva_temp');
                        }
                    }

                    // if ($order->buyer_email == "proeventpay@gmail.com") {

                    //     dd(
                    //         $orderPaymentsTotalAmount,
                    //         listPaymentStatusPaid(),
                    //         $orderPaymentsTeste,
                    //         $order->payments->whereIn('status',listPaymentStatusPaid())->toArray(),
                    //         $order->payments->toArray(),
                    //         $order->toArray(),
                    //     );
                    // }

                    return $order->status ?? FALSE;
                }
            }
        }

        return false;
    }
}

if (!function_exists('consolidaCallbacksPayments'))
{
    function consolidaCallbacksPayments($callbackId=false)
    {
        if($callbackId ?? false)
        {
            $callbacks = AppPaymentCallback::where('id',$callbackId)->get();
        }
        else
        {
            $callbacks = AppPaymentCallback::whereIn('postback_processed_status',['processar'])
                ->orderBy('updated_at')
                ->get()->take(10);
        }

        //
        $processar = [];

        // SE CALLBACK
        if($callbacks ?? false)
        {
            foreach ($callbacks as $call_key => $call_values)
            {
                $callbacks[$call_key]->postback_processed = now()->format('Y-m-d H:i:s');
                $callbacks[$call_key]->postback_processed_status = 'iniciado';
                $callbacks[$call_key]->save();

                // SE STATUS IN CANCELED ou PAID
                if(in_array($call_values->status,listPaymentStatusPaidCanceled()))
                {
                    // SE NAO NSU
                    if(!$call_values->nsu)
                    {
                        $callbacks[$call_key]->postback_processed_status = 'notfound-nsu';
                        $callbacks[$call_key]->save();
                        //
                        $processar[$call_values->id] = $callbacks[$call_key]->postback_processed_status;
                        continue;
                    }

                    // SE NAO ID TRANSAÇÃO
                    if(!$call_values->payment_id)
                    {
                        $callbacks[$call_key]->postback_processed_status = 'notfound-paymentid';
                        $callbacks[$call_key]->save();
                        //
                        $processar[$call_values->id] = $callbacks[$call_key]->postback_processed_status;
                        continue;
                    }

                    // SE NAO ORDER ID
                    if(!$call_values->order_id)
                    {
                        $callbacks[$call_key]->postback_processed_status = 'notfound-orderid';
                        $callbacks[$call_key]->save();
                        //
                        $processar[$call_values->id] = $callbacks[$call_key]->postback_processed_status;
                        continue;
                    }

                    //
                    $orderStatus = consolidaOrderPayments($call_values->order_id,$call_values->payment_id);
                    $callbacks[$call_key]->postback_processed_status = 'processado-' . (($orderStatus ?? false) ? $orderStatus : null);
                    $callbacks[$call_key]->save();
                }
                else
                {
                    $callbacks[$call_key]->postback_processed_status = 'no-paid-no-cenceled';
                    $callbacks[$call_key]->save();
                }

                $processar[$call_values->id] = $callbacks[$call_key]->postback_processed_status;

                sleep(1);
            }
        }
        else // BUSCA INICIADO E NAO CONCLUSO
        {

        }

        // DD($processar);

        return $processar;
    }
}

if (!function_exists('trataTicketsEvento'))
{
    function trataTicketsEvento($orderId,$ticket_status)
    {
        $tickets = [];

        // CORRE ITENS
        if($order = AppEventOrder::with(['event','itens'])->find($orderId))
        {
            // PERCORRE ITENS
            foreach ($order->itens ?? [] as $itemKey => $item)
            {
                //
                $dataTicket = [
                    'ticket_control'             => $order->order_control . '-' . ($itemKey + 1),
                    'ticket_status'              => $ticket_status,
                    'order_id'                   => $order->id,
                    'order_item_id'              => $item->id,
                    'organizer_id'               => $order->event->organizer_id,
                    'organizer_name'             => $order->event->organizer_slug,
                    'event_id'                   => $order->event->id,
                    'event_name'                 => $order->event->event_name ?? null,
                    'event_description'          => $order->event->event_description ?? null,
                    'event_datetime'             => $order->event->event_datetime_start ? \Carbon\Carbon::parse($order->event->event_datetime_start)->format('Y-m-d H:i:s') : null,
                    'event_ticket_id'            => $item->ticketType->id ?? null,
                    'event_ticket_slug'          => $item->ticketType->ticket_slug ?? null,
                    'event_ticket_name'          => $item->ticketType->ticket_name ?? null,
                    'event_ticket_price'         => $item->item_amount ?? null,
                    'ticket_generation_datetime' => now()->format('Y-m-d H:i:s'),
                    'user_name'                  => strtolower(trim($item->user_name)),
                    'user_email'                 => strtolower(trim($item->user_email)),
                    'user_doc_type'              => $item->user_doc_type,
                    'user_doc_num'               => $item->user_doc_num,
                    'user_contact_country'       => (int) $item->user_contact_country ?? 55,
                    'user_contact_ddd'           => (int) $item->user_contact_ddd,
                    'user_contact_num'           => (int) $item->user_contact_num,
                    'user_json_answers'          => $item->user_json_answers ?? null,
                ];

                //
                if ($ticket = AppEventOrderTicket::where('order_id',$order->id)->where('order_item_id',$item->id)->first())
                {
                    $dataTicket['ticket_generation_datetime'] = $ticket->ticket_generation_datetime ?? $ticket->created_at;
                    $ticket->update($dataTicket);
                }
                elseif ($ticket = AppEventOrderTicket::where('order_id',$order->id)->where('ticket_control',$dataTicket['ticket_control'])->first())
                {
                    $dataTicket['ticket_generation_datetime'] = $ticket->ticket_generation_datetime ?? $ticket->created_at;
                    $ticket->update($dataTicket);
                }
                else
                {
                    $ticket = AppEventOrderTicket::create($dataTicket);
                }

                $tickets[$itemKey] = $ticket->toArray();
            }
        }
        elseif($order = AppEventOrder::with(['itens'])->find($orderId))
        {
            // VER DEPOIS SE ESTA REDUNDANTE
            // // CRIA TICKETS
            // $orderJsonDecode = json_decode($order->order_json, true);

            // //
            // if(isset($orderJsonDecode['order_data']['tickets']))
            // {
            //     // SE EVENTO
            //     if(in_array($orderJsonDecode['order_type'], ['evento','event','app_event']))
            //     {
            //         foreach ($orderJsonDecode['order_data']['tickets'] as $ticketKey => $ticketValues)
            //         {
            //             $ticketValues['ticket_status'] = $ticket_status;

            //             if($ticket = AppEventOrderTicket::where('ticket_control',$ticketValues['ticket_control'])->first())
            //             {
            //                 $ticket->update($ticketValues);
            //             }
            //             else
            //             {
            //                 $ticket = AppEventOrderTicket::create($ticketValues);
            //             }

            //             $tickets[$ticketKey] = $ticket->toArray();
            //         }
            //     }
            // }
        }

        return $tickets;
    }
}

// EMAIL
if (!function_exists(function: 'notificaPagamentoConfirmado'))
{
    function notificaPagamentoConfirmado($email,$paymentId,$orderId=false,$reply=false)
    {
        try
        {
            if($email ?? false)
            {
                $emailBcc = [config('mail.bcc_noreply')];

                $reply = trim(mb_strtolower($reply ?? config('mail.from.address')));
                $queueNew = new PagamentoConfirmado(paymentId:$paymentId,reply:$reply);

                $jobId = Mail::to([mb_strtolower($email)])
                    ->bcc($emailBcc)
                    ->queue($queueNew);

                //
                $notifica = AppNotifica::create([
                    'buyer_id'       => null,
                    'order_id'       => $orderId ?? null,
                    'payment_id'     => $paymentId,
                    'tipo'           => 'pagamento-confirmado',
                    'canal'          => 'email',
                    'envio_destino'  => $email,
                    'envio_datahora' => now()->format('Y-m-d H:i:s'),
                    'subject'        => mb_strtoupper($queueNew->subject ?? 'sem-titulo'),
                    'body'           => $queueNew->render() ?? null,
                    'job_id'         => $jobId,
                    'job_json'       => json_encode($queueNew ?? []),
                ]);

                return $jobId;
            }

            return false;
        }
        catch (\Throwable $th)
        {
            dd(__FUNCTION__, $th);
        }
    }
}

// EMAIL
if (!function_exists(function: 'notificaPagamentoLembreteCarne'))
{
    function notificaPagamentoLembreteCarne(int $qtdDias=0)
    {
        try
        {
            if($qtdDias ?? false)
            {
                $listaDias = [$qtdDias];
            }
            else
            {
                //$listaDias = range(1,60); // TESTE
                $listaDias = [-15 , -10 , 5 , 10];
            }

            $targets = [];

            foreach ($listaDias as $dias)
            {
                $now = now();

                $targets[] = $now->addDays($dias)->format('Y-m-d');
            }

            $notifica = [];

            //
            foreach ($targets as $vecimento)
            {
                $paymentsSlips = AppPaymentSlip::with(['order'])->whereDate('installment_date_due',$vecimento)->get();

                if($paymentsSlips->count())
                {
                    foreach($paymentsSlips as $slipKey => $slip)
                    {
                        // SE NAO EXISTIR ORDER
                        if(!$slip->order ?? false)
                        {
                            continue;
                        }

                        // SE JA ESTA PAGO ou CANCELADO
                        if(in_array($slip->status,listPaymentStatusPaidCanceled()))
                        {
                            continue;
                        }

                        $queueNew = new PagamentoLembreteCarne($slip->id);

                        //
                        $emailBcc = [config('mail.bcc_noreply')];

                        //
                        $jobId = Mail::to([mb_strtolower($slip->order->buyer_email)])
                            ->bcc($emailBcc)
                            ->queue($queueNew);

                        //
                        $appNotifica = AppNotifica::create([
                            'buyer_id'       => null,
                            'order_id'       => $slip->order->id ?? null,
                            'payment_id'     => null,
                            'tipo'           => 'lembrete-pagamento-carne-' . $vecimento,
                            'canal'          => 'email',
                            'envio_destino'  => $slip->order->buyer_email,
                            'envio_datahora' => now()->format('Y-m-d H:i:s'),
                            'subject'        => mb_strtoupper($queueNew->subject ?? 'sem-titulo'),
                            'body'           => $queueNew->render() ?? null,
                            'job_id'         => $jobId,
                            'job_json'       => json_encode($queueNew ?? []),
                        ]);

                        $notifica[$slip->id] = [$appNotifica->tipo => $slip->order->buyer_email];
                    }
                }
            }

            // LOG
            $body  = now()->format('Y-m-d H:i:s');
            $body .= '<hr><div>'.json_encode($targets).'</div>';

            if($notifica ?? false)
            {
                foreach ($notifica ?? [] as $key => $notificaValues)
                {
                    foreach ($notificaValues ?? [] as $tipo => $email)
                    {
                        $body .= '<hr><div>[' . $key . '] ' . $tipo . ' - ' . $email . '</div>';
                    }
                }
            }
            else
            {
                $body .= '<hr><div>SEM LEMBRETES PARA ENVIADOS</div>';
            }

            //
            notificaEmail(emailTo:'proeventpay@gmail.com',assunto:mb_strtoupper('ROTINA: lembrete-pagamento-carne'),body:$body);

            return $notifica;
        }
        catch (\Throwable $th)
        {
            dd(__FUNCTION__, $th);
        }
    }
}

// EMAIL
if (!function_exists(function: 'notificaEmail'))
{
    function notificaEmail($emailTo=false,$assunto=false,$body=null,$tipo=false,$uuid=false,$reply=false)
    {
        try
        {
            $reply = $reply ? $reply : config('mail.from.address');

            if($tipo == 'pagamento-sucesso')
            {
                //
                if(!$transacao = AppPayment::with(['order'])->find($uuid))
                {
                    return false;
                }

                //
                $emailTo = $transacao->order->buyer_email ?? config('mail.fallback_buyer');
                $queueNew = new PagamentoSucesso($uuid,reply:$reply);
            }
            elseif($tipo == 'compra-confirmada')
            {
                //
                $emailTo  = $emailTo ?? config('mail.fallback_buyer');
                $queueNew = new CompraConfirmada($uuid,reply:$reply);
            }
            else
            {
                if(!$emailTo ?? false)
                {
                    $emailTo = 'noreply@proeventpay.com.br';
                    $emailTo = 'proeventpay@gmail.com';
                }

                $queueNew = new EmailTeste($assunto,$body,reply:$reply);

                //
                $jobId = Mail::to([mb_strtolower($emailTo)])->queue($queueNew);

                return [
                    'job' => $jobId,
                    'queue' => $queueNew,
                ];
            }

            if($emailTo ?? false)
            {
                //
                $emailBcc = ['noreply@proeventpay.com.br'];

                //
                $jobId = Mail::to([mb_strtolower($emailTo)])
                    ->bcc($emailBcc)
                    ->queue($queueNew);

                //
                $appNotifica = AppNotifica::create([
                    'buyer_id'       => null,
                    'order_id'       => ($transacao ?? false) ? $transacao->app_ref_order_id : ($uuid ?? null),
                    'payment_id'     => ($transacao ?? false) ? $transacao->id : null,
                    'tipo'           => $tipo,
                    'canal'          => 'email',
                    'envio_destino'  => $emailTo,
                    'envio_datahora' => now()->format('Y-m-d H:i:s'),
                    'subject'        => mb_strtoupper($queueNew->subject ?? 'sem-titulo'),
                    'body'           => $queueNew->render() ?? null,
                    'job_id'         => $jobId,
                    'job_json'       => json_encode($queueNew ?? []),
                ]);

                return true;
            }

            return false;
        }
        catch (\Throwable $th)
        {
            \Illuminate\Support\Facades\Log::error('notificaEmail error: ' . $th->getMessage(), ['uuid' => $uuid ?? null, 'tipo' => $tipo ?? null]);
            return false;
        }
    }
}



// ====================================================================================

if (!function_exists('listGenero')) {

    function listGenero($id = false)
    {
        $list = ['m' => 'Masculino', 'f' => 'Feminino'];

        if ($id)
            return $list[strtolower($id)];
        else
            return $list;
    }
}

if (!function_exists('listHorarios')) {

    function listHorarios($id = false)
    {
        $list = [
            '00:00:00' => '00:00',
            '01:00:00' => '01:00',
            '02:00:00' => '02:00',
            '03:00:00' => '03:00',
            '04:00:00' => '04:00',
            '05:00:00' => '05:00',
            '06:00:00' => '06:00',
            '07:00:00' => '07:00',
            '08:00:00' => '08:00',
            '09:00:00' => '09:00',
            '10:00:00' => '10:00',
            '11:00:00' => '11:00',
            '12:00:00' => '12:00',
            '13:00:00' => '13:00',
            '14:00:00' => '14:00',
            '15:00:00' => '15:00',
            '16:00:00' => '16:00',
            '17:00:00' => '17:00',
            '18:00:00' => '18:00',
            '19:00:00' => '19:00',
            '20:00:00' => '20:00',
            '21:00:00' => '21:00',
            '22:00:00' => '22:00',
            '23:00:00' => '23:00',
        ];

        if ($id)
            return $list[strtolower($id)];
        else
            return $list;
    }
}

if (!function_exists('listMes')) {

    function listMes($id = false)
    {
        $list = [
            '01' => 'janeiro',
            '02' => 'fevereiro',
            '03' => 'março',
            '04' => 'abril',
            '05' => 'maio',
            '06' => 'junho',
            '07' => 'julho',
            '08' => 'agosto',
            '09' => 'setembro',
            '10' => 'outubro',
            '11' => 'novembro',
            '12' => 'dezembro',
        ];

        if ($id)
            return $list[strtolower($id)];
        else
            return $list;
    }
}

if (!function_exists('listStates')) {

    function listStates($id = null)
    {
        $fallbackList = [
            ['ref_slug' => 'ac', 'ref_value' => 'AC - Rio Branco', 'ref_label' => null],
            ['ref_slug' => 'al', 'ref_value' => 'AL - Maceió', 'ref_label' => null],
            ['ref_slug' => 'ap', 'ref_value' => 'AP - Macapá', 'ref_label' => null],
            ['ref_slug' => 'am', 'ref_value' => 'AM - Manaus', 'ref_label' => null],
            ['ref_slug' => 'ba', 'ref_value' => 'BA - Salvador', 'ref_label' => null],
            ['ref_slug' => 'ce', 'ref_value' => 'CE - Fortaleza', 'ref_label' => null],
            ['ref_slug' => 'df', 'ref_value' => 'DF - Brasília', 'ref_label' => null],
            ['ref_slug' => 'es', 'ref_value' => 'ES - Vitória', 'ref_label' => null],
            ['ref_slug' => 'go', 'ref_value' => 'GO - Goiânia', 'ref_label' => null],
            ['ref_slug' => 'ma', 'ref_value' => 'MA - São Luís', 'ref_label' => null],
            ['ref_slug' => 'mt', 'ref_value' => 'MT - Cuiabá', 'ref_label' => null],
            ['ref_slug' => 'ms', 'ref_value' => 'MS - Campo Grande', 'ref_label' => null],
            ['ref_slug' => 'mg', 'ref_value' => 'MG - Belo Horizonte', 'ref_label' => null],
            ['ref_slug' => 'pa', 'ref_value' => 'PA - Belém', 'ref_label' => null],
            ['ref_slug' => 'pb', 'ref_value' => 'PB - João Pessoa', 'ref_label' => null],
            ['ref_slug' => 'pr', 'ref_value' => 'PR - Curitiba', 'ref_label' => null],
            ['ref_slug' => 'pe', 'ref_value' => 'PE - Recife', 'ref_label' => null],
            ['ref_slug' => 'pi', 'ref_value' => 'PI - Teresina', 'ref_label' => null],
            ['ref_slug' => 'rj', 'ref_value' => 'RJ - Rio de Janeiro', 'ref_label' => null],
            ['ref_slug' => 'rn', 'ref_value' => 'RN - Natal', 'ref_label' => null],
            ['ref_slug' => 'rs', 'ref_value' => 'RS - Porto Alegre', 'ref_label' => null],
            ['ref_slug' => 'ro', 'ref_value' => 'RO - Porto Velho', 'ref_label' => null],
            ['ref_slug' => 'rr', 'ref_value' => 'RR - Boa Vista', 'ref_label' => null],
            ['ref_slug' => 'sc', 'ref_value' => 'SC - Florianópolis', 'ref_label' => null],
            ['ref_slug' => 'sp', 'ref_value' => 'SP - São Paulo', 'ref_label' => null],
            ['ref_slug' => 'se', 'ref_value' => 'SE - Aracaju', 'ref_label' => null],
            ['ref_slug' => 'to', 'ref_value' => 'TO - Palmas', 'ref_label' => null],
        ];

        $fallbackCollection = collect($fallbackList)->map(function ($item) {
            return (object) $item;
        });

        try {
            if ($id) {
                $state = \Illuminate\Support\Facades\DB::table('ref_app_states')
                    ->select('ref_slug', 'ref_value', 'ref_label')
                    ->where('ref_slug', strtolower($id))
                    ->where('to_view', true)
                    ->first();

                if ($state) {
                    return $state;
                }

                return $fallbackCollection->firstWhere('ref_slug', strtolower($id));
            }

            $states = \Illuminate\Support\Facades\DB::table('ref_app_states')
                ->select('ref_slug', 'ref_value', 'ref_label')
                ->where('to_view', true)
                ->orderBy('ref_slug')
                ->get();

            if ($states->isNotEmpty()) {
                return $states;
            }
        } catch (\Throwable $e) {
            // Fallback para lista fixa caso o banco não esteja disponível.
        }

        return $fallbackCollection;
    }
}

if (!function_exists('listDdd')) {

    function listDdd($id = 'all')
    {
        $list = [
            21 => '21 - Rio de Janeiro / RJ',
            11 => '11 - São Paulo / SP',
            12 => '12 - São José dos Campos / SP',
            13 => '13 - Santos / SP',
            14 => '14 - Bauru / SP',
            15 => '15 - Sorocaba / SP',
            16 => '16 - Ribeirão Preto / SP',
            17 => '17 - São José do Rio Preto / SP',
            18 => '18 - Presidente Prudente / SP',
            19 => '19 - Campinas / SP',
            22 => '22 - Campos dos Goytacazes / RJ',
            24 => '24 - Volta Redonda / RJ',
            27 => '27 - Vila Velha/Vitória / ES',
            28 => '28 - Cachoeiro de Itapemirim / ES',
            31 => '31 - Belo Horizonte / MG',
            32 => '32 - Juiz de Fora / MG',
            33 => '33 - Governador Valadares / MG',
            34 => '34 - Uberlândia / MG',
            35 => '35 - Poços de Caldas / MG',
            37 => '37 - Divinópolis / MG',
            38 => '38 - Montes Claros / MG',
            41 => '41 - Curitiba / PR',
            42 => '42 - Ponta Grossa / PR',
            43 => '43 - Londrina / PR',
            44 => '44 - Maringá / PR',
            45 => '45 - Foz do Iguaçú / PR',
            46 => '46 - Francisco Beltrão/Pato Branco / PR',
            47 => '47 - Joinville / SC',
            48 => '48 - Florianópolis / SC',
            49 => '49 - Chapecó / SC',
            51 => '51 - Porto Alegre / RS',
            53 => '53 - Pelotas / RS',
            54 => '54 - Caxias do Sul / RS',
            55 => '55 - Santa Maria / RS',
            61 => '61 - Brasília / DF',
            62 => '62 - Goiânia / GO',
            63 => '63 - Palmas / TO',
            64 => '64 - Rio Verde / GO',
            65 => '65 - Cuiabá / MT',
            66 => '66 - Rondonópolis / MT',
            67 => '67 - Campo Grande / MS',
            68 => '68 - Rio Branco / AC',
            69 => '69 - Porto Velho / RO',
            71 => '71 - Salvador / BA',
            73 => '73 - Ilhéus / BA',
            74 => '74 - Juazeiro / BA',
            75 => '75 - Feira de Santana / BA',
            77 => '77 - Barreiras / BA',
            79 => '79 - Aracaju / SE',
            81 => '81 - Recife / PE',
            82 => '82 - Maceió / AL',
            83 => '83 - João Pessoa / PB',
            84 => '84 - Natal / RN',
            85 => '85 - Fortaleza / CE',
            86 => '86 - Teresina / PI',
            87 => '87 - Petrolina / PE',
            88 => '88 - Juazeiro do Norte / CE',
            89 => '89 - Picos / PI',
            91 => '91 - Belém / PA',
            92 => '92 - Manaus / AM',
            93 => '93 - Santarém / PA',
            94 => '94 - Marabá / PA',
            95 => '95 - Boa Vista / RR',
            96 => '96 - Macapá / AP',
            97 => '97 - Coari / AM',
            98 => '98 - São Luís / MA',
            99 => '99 - Imperatriz / MA',
        ];

        if ($id == 'all')
            return $list;
        else
            return isset($list[$id]) ? $list[$id] : $id;
    }
}

if (!function_exists('listDdi')) {

    function listDdi($id = 'all')
    {
        $list = [
            55 => '55 - Brasil',
            93 => '93 - Afeganistão',
            27 => '27 - África do Sul',
            355 => '355 - Albânia',
            49 => '49 - Alemanha',
            213 => '213 - Argélia',
            1 => '1 - Andorra/Anguilla/Antígua/Barbuda/Canadá',
            244 => '244 - Angola',
            966 => '966 - Arábia Saudita',
            297 => '297 - Aruba',
            61 => '61 - Austrália',
            43 => '43 - Áustria',
            994 => '994 - Azerbaijão',
            1242 => '1-242 - Bahamas',
            973 => '973 - Bahrein',
            880 => '880 - Bangladesh',
            1246 => '1-246 - Barbados',
            32 => '32 - Bélgica',
            501 => '501 - Belize',
            229 => '229 - Benin',
            1.441 => '1-441 - Bermudas',
            591 => '591 - Bolívia',
            387 => '387 - Bósnia e Herzegovina',
            267 => '267 - Botswana',
            673 => '673 - Brunei',
            359 => '359 - Bulgária',
            226 => '226 - Burkina Faso',
            257 => '257 - Burundi',
            237 => '237 - Camarões',
            855 => '855 - Camboja',
            974 => '974 - Catar',
            56 => '56 - Chile',
            86 => '86 - China',
            357 => '357 - Chipre',
            57 => '57 - Colômbia',
            269 => '269 - Comores',
            850 => '850 - Coreia do Norte',
            82 => '82 - Coreia do Sul',
            506 => '506 - Costa Rica',
            385 => '385 - Croácia',
            53 => '53 - Cuba',
            45 => '45 - Dinamarca',
            1767 => '1-767 - Dominica',
            20 => '20 - Egito',
            503 => '503 - El Salvador',
            971 => '971 - Emirados Árabes Unidos',
            593 => '593 - Equador',
            291 => '291 - Eritreia',
            34 => '34 - Espanha',
            1809 => '1-809 - Estados Unidos',
            372 => '372 - Estônia',
            251 => '251 - Etiópia',
            63 => '63 - Filipinas',
            358 => '358 - Finlândia',
            33 => '33 - França',
            995 => '995 - Geórgia',
            233 => '233 - Gana',
            350 => '350 - Gibraltar',
            30 => '30 - Grécia',
            299 => '299 - Groenlândia',
            1473 => '1-473 - Granada',
            502 => '502 - Guatemala',
            592 => '592 - Guiana',
            594 => '594 - Guiana Francesa',
            509 => '509 - Haiti',
            504 => '504 - Honduras',
            852 => '852 - Hong Kong',
            36 => '36 - Hungria',
            91 => '91 - Índia',
            62 => '62 - Indonésia',
            98 => '98 - Irã',
            964 => '964 - Iraque',
            353 => '353 - Irlanda',
            354 => '354 - Islândia',
            972 => '972 - Israel',
            39 => '39 - Itália',
            1876 => '1-876 - Jamaica',
            81 => '81 - Japão',
            962 => '962 - Jordânia',
            383 => '383 - Kosovo',
            965 => '965 - Kuwait',
            856 => '856 - Laos',
            371 => '371 - Letônia',
            961 => '961 - Líbano',
            218 => '218 - Líbia',
            423 => '423 - Liechtenstein',
            370 => '370 - Lituânia',
            352 => '352 - Luxemburgo',
            853 => '853 - Macau',
            389 => '389 - Macedônia do Norte',
            261 => '261 - Madagascar',
            60 => '60 - Malásia',
            265 => '265 - Malawi',
            960 => '960 - Maldivas',
            223 => '223 - Mali',
            356 => '356 - Malta',
            212 => '212 - Marrocos',
            230 => '230 - Maurício',
            262 => '262 - Mayotte',
            52 => '52 - México',
            691 => '691 - Micronésia',
            258 => '258 - Moçambique',
            373 => '373 - Moldávia',
            377 => '377 - Mônaco',
            976 => '976 - Mongólia',
            382 => '382 - Montenegro',
            264 => '264 - Namíbia',
            977 => '977 - Nepal',
            505 => '505 - Nicarágua',
            227 => '227 - Níger',
            234 => '234 - Nigéria',
            47 => '47 - Noruega',
            64 => '64 - Nova Zelândia',
            968 => '968 - Omã',
            507 => '507 - Panamá',
            675 => '675 - Papua-Nova Guiné',
            92 => '92 - Paquistão',
            595 => '595 - Paraguai',
            51 => '51 - Peru',
            48 => '48 - Polônia',
            351 => '351 - Portugal',
            254 => '254 - Quênia',
            44 => '44 - Reino Unido',
            236 => '236 - República Centro-Africana',
            420 => '420 - República Tcheca',
            1809 => '1-809 - República Dominicana',
            40 => '40 - Romênia',
            7 => '7 - Rússia',
            250 => '250 - Ruanda',
            221 => '221 - Senegal',
            381 => '381 - Sérvia',
            65 => '65 - Singapura',
            963 => '963 - Síria',
            94 => '94 - Sri Lanka',
            46 => '46 - Suécia',
            41 => '41 - Suíça',
            597 => '597 - Suriname',
            66 => '66 - Tailândia',
            886 => '886 - Taiwan',
            255 => '255 - Tanzânia',
            228 => '228 - Togo',
            1868 => '1-868 - Trinidad e Tobago',
            216 => '216 - Tunísia',
            90 => '90 - Turquia',
            380 => '380 - Ucrânia',
            256 => '256 - Uganda',
            598 => '598 - Uruguai',
            998 => '998 - Uzbequistão',
            39 => '39 - Vaticano',
            58 => '58 - Venezuela',
            84 => '84 - Vietnã',
            260 => '260 - Zâmbia',
            263 => '263 - Zimbábue'
        ];

        if ($id == 'all')
            return $list;
        else
            return isset($list[$id]) ? $list[$id] : $id;
    }
}

if (!function_exists('listDocType')) {

    function listDocType($id = 'all')
    {
        $list = [
            'cpf'  => 'CPF',
            'cnpj' => 'CNPJ',
        ];

        if ($id == 'all')
            return $list;
        else
            return isset($list[$id]) ? $list[$id] : $id;
    }
}

if (!function_exists('listBoleano')) {

    function listBoleano($id = 'all')
    {
        $list = [
            1 => 'Sim',
            0 => 'Não',
        ];

        if ($id == 'all')
            return $list;
        else
            return isset($list[$id]) ? $list[$id] : $id;
    }
}

if (!function_exists('listOrderStatusHidden'))
{
    // STATUS QUE DEVEM SER OCULTADOS
    function listOrderStatusHidden()
    {
        if(in_array(sessionUserRole(), ['admin']))
            return [];
        else
            return ['cancelado_no_pagamento','payment_error','refused','fase_pagamento'];
    }
}

if (!function_exists('listOrderStatusPendente'))
{
    function listOrderStatusPendente()
    {
        return ['fase_pagamento','refused','pending','pending_payment', 'pending_boleto', 'pending_pix','pending_slip_pix','pending_slip','return-error','aguardando_pagamento','pendente','em_atraso'];
    }
}

if (!function_exists('listOrderStatusIrProPagamento'))
{
    function listOrderStatusIrProPagamento()
    {
        return ['fase_pagamento', 'pending', 'pending_boleto', 'pending_pix','pending_slip_pix','pending_slip', 'paid', 'paid_cupom_full','aguardando_pagamento','pendente','em_atraso'];
    }
}

if (!function_exists('listOrderStatusEmPagamento'))
{
    function listOrderStatusEmPagamento()
    {
        return ['pending', 'pending_boleto', 'pending_pix','pending_slip_pix','pending_slip', 'paid', 'paid_cupom_full','aguardando_pagamento','pendente','em_atraso'];
    }
}

if (!function_exists('listOrderStatusNaoAbrePagamento'))
{
    function listOrderStatusNaoAbrePagamento()
    {
        return ['paid', 'cancelado', 'cancelado_no_pagamento', 'expired_order', 'pending_boleto', 'pending_pix','pending_slip_pix'];
    }
}

if (!function_exists('listOrderStatusNaoCancelar'))
{
    // STATUS QUE NÃO PODEM SER CANCELADOS
    function listOrderStatusNaoCancelar()
    {
        return ['paid', 'cancelado', 'cancelado_no_pagamento', 'expired_order'];
    }
}

if (!function_exists('listOrderStatusCancelada'))
{
    function listOrderStatusCancelada()
    {
        return ['canceled', 'cancelado', 'cancelado_no_pagamento', 'expired_order'];
    }
}

if (!function_exists('listOrderStatusPaid'))
{
    function listOrderStatusPaid()
    {
        return ['paid','paid_cupom_full','pago','sucesso','pagamento_ok','pagamento_realizado'];
    }
}

if (!function_exists('listOrderStatusPaidParcial'))
{
    function listOrderStatusPaidParcial()
    {
        return ['pago_parcial'];
    }
}

if (!function_exists('listPaymentStatusPaid'))
{
    function listPaymentStatusPaid()
    {
        return ['paid','paid_cupom_full','pago','pago_parcial','sucesso','pagamento_ok','pagamento_realizado','realizado','autorizado'];
    }
}

if (!function_exists('listPaymentStatusCanceled'))
{
    function listPaymentStatusCanceled()
    {
        return ['pagamento_cancelado','cancelado','canceled','expired','estornado'];
    }
}

if (!function_exists('listPaymentStatusPaidCanceled'))
{
    function listPaymentStatusPaidCanceled()
    {
        return array_merge(listPaymentStatusPaid(),listPaymentStatusCanceled());
    }
}

if (!function_exists('listPaymentStatusEmPagamento'))
{
    function listPaymentStatusEmPagamento()
    {
        return ['pending_boleto', 'pending_pix','pending_slip_pix','pending_slip','aguardando_pagamento','pendente','em_atraso'];
    }
}

if (!function_exists('ticketStatusCapacidade'))
{
    function ticketStatusCapacidade($tipoVisao=false,$visaoAdmin=true)
    {
        // SE ADMIN RETORNA FULL
        if($visaoAdmin && in_array(sessionUserRole(), ['admin']))
            $tipoVisao = false;

        switch ($tipoVisao)
        {
            case 'tickets_validos':
                return ['disponivel','utilizado'];

            case 'participantes':
                return ['disponivel','gerado','utilizado'];

            default:
                return ['disponivel','gerado','reserva_temp','reserva_temp_boleto','utilizado','cancelado'];
                // return ['disponivel','gerado','reserva_temp','reserva_temp_boleto','utilizado'];
        }
    }
}

if (!function_exists('ticketStatusTemp'))
{
    function ticketStatusTemp()
    {
        return ['reserva_temp','reserva_temp_boleto','gerado'];
    }
}

if (!function_exists('getNome')) {

    function getNome($texto = false)
    {
        if ($texto) {
            $texto = explode(' ', $texto)[0];
        }

        return $texto;
    }
}

if (!function_exists('getSobrenome')) {

    function getSobrenome($texto = false)
    {
        if ($texto) {
            $explode = explode(' ', $texto);
            unset($explode[0]);
            $texto = implode(' ', $explode);
        }

        return $texto;
    }
}

if (!function_exists('formatList')) {

    function formatList($list = false, $value = 'id', $label = 'ref_description')
    {
        $return = [];

        if ($list && is_array($list)) {

            foreach ($list as $listValues) {

                if (is_array($label)) {
                    if (!$listValues[$value])
                        continue;

                    $return[$listValues[$value]] = false;

                    foreach ($label as $labelValue) {

                        //
                        if (!$listValues[$labelValue])
                            continue;

                        //
                        if ($return[$listValues[$value]])
                            $return[$listValues[$value]] .= ' - ' . $listValues[$labelValue];
                        else
                            $return[$listValues[$value]] .= $listValues[$labelValue];
                    }
                } else {
                    if (!$listValues[$value] || !$listValues[$label])
                        continue;
                    $return[$listValues[$value]] = $listValues[$label];
                }
            }
        }

        return $return;
    }
}

if (!function_exists('putMask')) {

    function putMask(string $value = null, $mask = false)
    {
        if (empty($value))
            return '---';

        $value = str_replace([" ", ".", "-", "(", ")", "_"], "", $value);

        switch (strtolower($mask)) {

            case 'telefone':
                $maxLen = 11;
                if (strlen($value) == '10')
                    $mask = '(##) ####-####';
                elseif (strlen($value) >= '11')
                    $mask = '(##) #####-####';
                else
                    $mask = false;
                break;

            case 'tel_num':
                $maxLen = 9;
                if (strlen($value) == '8')
                    $mask = '####-####';
                elseif (strlen($value) >= '9')
                    $mask = '#####-####';
                else
                    $mask = false;
                break;

            case 'cpf':
            case 'doc_cpf':
                $maxLen = 11;
                $mask = '###.###.###-##';
                break;

            case 'cnpj':
            case 'doc_cnpj':
                $maxLen = 18;
                $mask = '##.###.###/####-##';
                break;

            case 'cep':
                $maxLen = 8;
                $mask = '#####-###';
                break;

            case 'boleto':
                $maxLen = 47;
                $mask = '#####.##### #####.###### #####.###### # ##############';
                break;

            default:
                return $value;
        }

        if ($mask)
        {
            if ($maxLen ?? false)
                $value = substr($value, 0, $maxLen);

            for ($i = 0; $i < strlen($value); $i++)
                $mask[strpos($mask, "#")] = substr($value, $i, 1);
        }

        return $mask;
    }
}

if (!function_exists('da')) {

    function da($obj)
    {
        dd($obj->toArray());
    }
}

if (!function_exists('setSessionFlash')) {

    function setSessionFlash($msg='Teste',$tipo='status')
    {
        return session()->flash($tipo,$msg);
    }
}

if (!function_exists('returnEventoDashboard')) {

    function returnEventoDashboard($msg=false,$tipo=false)
    {
        if($msg ?? false)
        {
            setSessionFlash($msg,$tipo ?? 'status');
        }

        return redirect()->route('dashboard-evento');
    }
}

if (!function_exists('randomText'))
{
    function getRandom(int $length=5,bool $number=true,bool $string=true)
    {
        $characters = '';

        if($number ?? false) $characters .= '0123456789';
        if($string ?? false) $characters .= 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        if($number || $string)
            return substr(str_shuffle(str_repeat($characters, ceil($length/strlen($characters)) )),1,$length);
        else
            return false;
    }
}

if (!function_exists('sessionDebug'))
{
    function sessionDebug($set=false,$debug=false)
    {
        if($set)
        {
            Session::put('debug', $debug);
        }

        return Session::get('debug');
    }
}

if (!function_exists('teste')) {

    function teste($class = false)
    {
        dd(__LINE__);
    }
}

if (!function_exists('convertArrayToObject')) {

    function convertArrayToObject($dataConvert = [])
    {
        $return = new stdClass;

        if (count($dataConvert)) {

            foreach ($dataConvert as $transactionKey => $transactionValue) {

                if (is_array($transactionValue))
                    $return->$transactionKey = convertArrayToObject($transactionValue);
                else
                    $return->$transactionKey = $transactionValue;
            }
        }
        return $return;
    }
}

if (!function_exists('checkSessionCampusSlug')) {

    function checkSessionCampusSlug()
    {
        if (session('campusSlug') ?? false)
            return true;
        else
            return false;
    }
}

if (!function_exists('campusNotSet')) {

    function campusNotSet()
    {
        if (session('campusItem') ?? false)
            return false;
        else
            return true;
    }
}

if (!function_exists('isAdmin')) {

    function isAdmin()
    {
        if(!Auth::user() || !Auth::user()->app || !Auth::user()->app->first()) {
            return false;
        }

        $userRole = Auth::user()->app->first()->pivot->user_role ?? false;
        return in_array($userRole, ['admin', 'super-admin']);
    }
}

if (!function_exists('isSuperAdmin')) {
    /**
     * Verifica se o usuário é super-admin
     * Super-admin tem acesso total a tudo
     */
    function isSuperAdmin()
    {
        if(!Auth::user() || !Auth::user()->app || !Auth::user()->app->first()) {
            return false;
        }

        $userRole = Auth::user()->app->first()->pivot->user_role ?? false;
        return $userRole === 'super-admin';
    }
}

if (!function_exists('isOwner')) {
    /**
     * Verifica se o usuário é owner do customer atual
     * Super-admin herda permissões de owner
     */
    function isOwner()
    {
        // Super-admin tem todas as permissões de owner
        if (isSuperAdmin()) {
            return true;
        }

        $customer = sessionCustomer();
        if (!$customer || !Auth::user()) {
            return false;
        }

        $userCustomer = \App\Models\UserCustomer::where('user_id', Auth::user()->id)
            ->where('customer_id', $customer->id)
            ->first();

        return $userCustomer && $userCustomer->user_role === 'owner';
    }
}

if (!function_exists('hasRole')) {
    /**
     * Verifica se o usuário tem uma role específica ou superior na hierarquia
     * Hierarquia: user < owner < admin < super-admin
     *
     * @param string|array $role Role(s) a verificar
     * @param bool $inherit Se deve considerar hierarquia (padrão: true)
     * @return bool
     */
    function hasRole($role, $inherit = true)
    {
        if (!Auth::user()) {
            return false;
        }

        $roles = is_array($role) ? $role : [$role];

        // Define hierarquia de roles
        $hierarchy = [
            'user' => 1,
            'owner' => 2,
            'admin' => 3,
            'super-admin' => 4,
        ];

        // Pega role do app
        $appRole = Auth::user()->app->first()->pivot->user_role ?? null;

        // Pega role do customer
        $customerRole = null;
        $customer = sessionCustomer();
        if ($customer) {
            $userCustomer = \App\Models\UserCustomer::where('user_id', Auth::user()->id)
                ->where('customer_id', $customer->id)
                ->first();
            $customerRole = $userCustomer->user_role ?? null;
        }

        $userLevel = max(
            $hierarchy[$appRole] ?? 0,
            $hierarchy[$customerRole] ?? 0
        );

        if (!$inherit) {
            // Verifica se tem exatamente uma das roles
            return in_array($appRole, $roles) || in_array($customerRole, $roles);
        }

        // Verifica hierarquia - se usuário tem nível >= ao menor nível requerido
        $minRequiredLevel = min(array_map(fn($r) => $hierarchy[$r] ?? 0, $roles));
        return $userLevel >= $minRequiredLevel;
    }
}

if (!function_exists('getCampanhasUrl')) {

    /**
     * Retorna a URL base para campanhas configurada no .env
     *
     * @return string
     */
    function getCampanhasUrl()
    {
        return config('domains.campanhas');
    }
}

if (!function_exists('getEventosUrl')) {

    /**
     * Retorna a URL base para eventos configurada no .env
     *
     * @return string
     */
    function getEventosUrl()
    {
        return config('domains.eventos');
    }
}

if (!function_exists('getAssinaturasUrl')) {

    /**
     * Retorna a URL base para assinaturas configurada no .env
     *
     * @return string
     */
    function getAssinaturasUrl()
    {
        return config('domains.assinaturas');
    }
}

if (!function_exists('getHomeUrl')) {

    /**
     * Retorna a URL base para o Home configurada no .env
     *
     * @return string
     */
    function getHomeUrl()
    {
        return config('domains.home');
    }
}

if (!function_exists('getPainelUrl')) {

    /**
     * Retorna a URL base para o painel configurada no .env
     *
     * @return string
     */
    function getPainelUrl()
    {
        return config('domains.painel');
    }
}

if (!function_exists('campanhaUrl')) {

    /**
     * Gera URL completa para uma campanha pública
     *
     * @param string $customerOrganizationSlug
     * @param string $campaignSlug
     * @param int|null $orderId
     * @param string|null $appUserUuid
     * @param string|null $appSource
     * @return string
     */
    function campanhaUrl($customerOrganizationSlug, $campaignSlug, $orderId = null, $appUserUuid = null, $appSource = null)
    {
        $baseUrl = getCampanhasUrl();
        $url = $baseUrl . '/' . $customerOrganizationSlug . '/' . $campaignSlug;

        if ($orderId) {
            $url .= '/' . $orderId;
        }

        $query = [];
        if ($appUserUuid) {
            $query['appUserUuid'] = $appUserUuid;
        }

        if ($appSource) {
            $query['appSource'] = $appSource;
        }

        if (!empty($query)) {
            $url .= '?' . http_build_query($query);
        }

        return $url;
    }
}

if (!function_exists('eventoUrl')) {

    /**
     * Gera URL completa para um evento
     *
     * @param string $eventSlug
     * @return string
     */
    function eventoUrl($eventSlug)
    {
        $baseUrl = getEventosUrl();
        return $baseUrl . '/' . $eventSlug;
    }
}

if (!function_exists('eventoPatrocinarUrl')) {

    /**
     * Gera URL completa para página de patrocínio de um evento
     *
     * @param string $eventSlug
     * @return string
     */
    function eventoPatrocinarUrl($eventSlug)
    {
        $baseUrl = getEventosUrl();
        return $baseUrl . '/patrocinicar/' . $eventSlug;
    }
}

if (!function_exists('getHomeUrl')) {

    /**
     * Retorna a URL da home baseada no subdomínio atual
     *
     * @return string
     */
    function getHomeUrl()
    {
        $host = request()->getHost();

        // Se estiver no subdomínio do home, retorna a URL do home
        if (str_contains($host, 'home')) {
            return getHomeUrl() . '/';
        }

        // Se estiver no subdomínio do painel, retorna a URL do painel
        if (str_contains($host, 'painel')) {
            return getPainelUrl() . '/';
        }

        // Se estiver no subdomínio de eventos, retorna a URL de eventos
        if (str_contains($host, 'eventos')) {
            return getEventosUrl() . '/';
        }

        // Se estiver no subdomínio de campanhas, retorna a URL de campanhas
        if (str_contains($host, 'campanhas')) {
            return getCampanhasUrl() . '/';
        }

        // Fallback: retorna URL de eventos como padrão
        return getEventosUrl() . '/';
    }
}

// ============================================================================
// WHITE LABEL HELPERS - Adicionados em Sprint 2.3
// ============================================================================

/**
 * Retorna o app atual da sessão (com suporte ao middleware IdentifyTenant)
 *
 * Esta versão atualizada prioriza o app injetado pelo middleware IdentifyTenant
 * e mantém compatibilidade com o código legado.
 *
 * @return \App\Models\App|null
 */
if (!function_exists('currentApp')) {
    function currentApp()
    {
        // Prioridade 1: App da sessão (injetado pelo middleware)
        if ($appId = session('app_id')) {
            // Busca sempre do banco para garantir dados atualizados
            return \App\Models\App::find($appId);
        }

        // Fallback compatibilidade: se tiver app na sessão mas não app_id
        if ($app = session('app')) {
            // Recarrega do banco para garantir dados frescos
            return \App\Models\App::find($app->id);
        }

        // Fallback final: Busca o primeiro app ativo
        return \App\Models\App::where('app_active', true)
            ->orderBy('created_at', 'asc')
            ->first();
    }
}

/**
 * Retorna a URL do logo principal do app
 *
 * @param bool $absolute Se true, retorna URL absoluta
 * @param \App\Models\App|null $app App específico (opcional)
 * @param bool $squared Se true, usa logo squared para páginas de autenticação
 * @return string
 */
if (!function_exists('appLogo')) {
    function appLogo($absolute = false, $app = null, $squared = false)
    {
        $app = $app ?? currentApp();

        if (!$app || !$app->url_image_logo) {
            return asset($squared ? 'images/app/default-logo-squared.png' : 'images/app/default-logo.png');
        }

        $logo = $app->url_image_logo;

        // Para compatibilidade com URLs antigas que começam com /storage/
        if (str_starts_with($logo, '/storage/')) {
            return asset($logo);
        }

        // Usa tenantAsset para storage isolado por UUID
        $url = tenantAsset($logo, $absolute, $app);

        // Cache busting: adiciona timestamp de atualização do branding
        if ($app->branding_updated_at) {
            $separator = str_contains($url, '?') ? '&' : '?';
            $url .= $separator . 'v=' . $app->branding_updated_at->timestamp;
        }

        return $url;
    }
}

/**
 * Retorna a URL do logo escuro (dark mode) do app
 *
 * @param bool $absolute Se true, retorna URL absoluta
 * @param \App\Models\App|null $app App específico (opcional)
 * @param bool $squared Se true, usa logo squared para páginas de autenticação
 * @return string
 */
if (!function_exists('appLogoDark')) {
    function appLogoDark($absolute = false, $app = null, $squared = false)
    {
        $app = $app ?? currentApp();

        if (!$app) {
            return asset($squared ? 'images/app/default-logo-squared.png' : 'images/app/default-logo.png');
        }

        $logo = $app->url_image_logo_dark ?? $app->url_image_logo;

        if (!$logo) {
            return asset($squared ? 'images/app/default-logo-squared.png' : 'images/app/default-logo.png');
        }

        // Para compatibilidade com URLs antigas que começam com /storage/
        if (str_starts_with($logo, '/storage/')) {
            return asset($logo);
        }

        // Usa tenantAsset para storage isolado por UUID
        $url = tenantAsset($logo, $absolute, $app);

        // Cache busting: adiciona timestamp de atualização do branding
        if ($app->branding_updated_at) {
            $separator = str_contains($url, '?') ? '&' : '?';
            $url .= $separator . 'v=' . $app->branding_updated_at->timestamp;
        }

        return $url;
    }
}

/**
 * Busca customer pelo domínio atual
 *
 * @return \App\Models\Customer|null
 */
if (!function_exists('currentCustomerByDomain')) {
    function currentCustomerByDomain()
    {
        $host = request()->getHost();

        // Remove www. se existir
        $host = preg_replace('/^www\./i', '', $host);

        // Tenta buscar customer pelo prefix_url que corresponde ao subdomínio
        // Exemplo: cliente.proeventpay.com -> prefix_url = 'cliente'
        $subdomain = explode('.', $host)[0];

        $customer = \App\Models\Customer::where('prefix_url', $subdomain)
            ->orWhere('customer_slug', $subdomain)
            ->first();

        return $customer;
    }
}

/**
 * Retorna a URL do logo do customer (se houver customer detectado pelo domínio)
 *
 * @param bool $absolute Se true, retorna URL absoluta
 * @param \App\Models\Customer|null $customer Customer específico (opcional)
 * @return string|null Retorna null se não houver customer ou logo
 */
if (!function_exists('customerLogo')) {
    function customerLogo($absolute = false, $customer = null)
    {
        $customer = $customer ?? currentCustomerByDomain();

        if (!$customer || !$customer->url_image_logo) {
            return null;
        }

        $logo = $customer->url_image_logo;

        // Para compatibilidade com URLs antigas que começam com /storage/
        if (str_starts_with($logo, '/storage/')) {
            return asset($logo);
        }

        // Usa tenantAsset para storage isolado
        return tenantAsset($logo, $absolute);
    }
}

/**
 * Retorna favicon do app
 *
 * @param bool $absolute Se true, retorna URL absoluta
 * @param \App\Models\App|null $app App específico (opcional)
 * @return string
 */
if (!function_exists('appFavicon')) {
    function appFavicon($absolute = false, $app = null)
    {
        $app = $app ?? currentApp();

        if (!$app) {
            return asset('images/app/default-favicon.ico');
        }

        $favicon = $app->url_image_favicon;

        if (!$favicon) {
            return asset('images/app/default-favicon.ico');
        }

        // Para compatibilidade com URLs antigas que começam com /storage/
        if (str_starts_with($favicon, '/storage/')) {
            return asset($favicon);
        }

        // Usa tenantAsset para storage isolado por UUID
        $url = tenantAsset($favicon, $absolute, $app);
        // Cache busting: adiciona timestamp de atualização do branding
        if ($app->branding_updated_at) {
            $separator = str_contains($url, '?') ? '&' : '?';
            $url .= $separator . 'v=' . $app->branding_updated_at->timestamp;
        }

        return $url;
    }
}

/**
 * Retorna o nome do app
 *
 * @param string|null $default Valor padrão caso não encontre
 * @return string
 */
if (!function_exists('appName')) {
    function appName($default = 'ProEventPay')
    {
        $app = currentApp();
        return $app->app_name ?? $default;
    }
}

/**
 * Retorna o domínio principal do app
 *
 * @param string|null $default Valor padrão caso não encontre
 * @return string
 */
if (!function_exists('appDomain')) {
    function appDomain($default = 'proeventpay.com')
    {
        $app = currentApp();
        return $app->domain_primary ?? $default;
    }
}

/**
 * Retorna uma cor do tema do app
 *
 * @param string $type Tipo da cor: 'primary', 'secondary', 'accent'
 * @param string|null $default Valor padrão
 * @return string
 */
if (!function_exists('appColor')) {
    function appColor($type = 'primary', $default = null)
    {
        $app = currentApp();

        if (!$app) {
            $defaults = [
                'primary' => '#1a202c',
                'secondary' => '#2d3748',
                'accent' => '#3182ce',
            ];
            return $default ?? ($defaults[$type] ?? '#1a202c');
        }

        $colors = [
            'primary' => $app->color_primary ?? '#1a202c',
            'secondary' => $app->color_secondary ?? '#2d3748',
            'accent' => $app->color_accent ?? '#3182ce',
        ];

        return $colors[$type] ?? ($default ?? '#1a202c');
    }
}

/**
 * Retorna todas as cores do app como array
 *
 * @return array
 */
if (!function_exists('appColors')) {
    function appColors()
    {
        return [
            'primary' => appColor('primary'),
            'secondary' => appColor('secondary'),
            'accent' => appColor('accent'),
        ];
    }
}

/**
 * Retorna um valor de configuração do app
 * Suporta dot notation e cache automático (TTL: 1 hora)
 *
 * @param string $key Chave da configuração (ex: 'timezone', 'features.campaigns')
 * @param mixed $default Valor padrão
 * @return mixed
 */
if (!function_exists('appConfig')) {
    function appConfig($key, $default = null)
    {
        $app = currentApp();

        if (!$app) {
            return $default;
        }

        // Cache key específico por tenant
        $cacheKey = "app_config_{$app->id}";

        // Busca configurações do cache (TTL: 1 hora)
        $settings = cache()->remember($cacheKey, 3600, function () use ($app) {
            $settings = $app->settings;
            // Se ainda for string, decodifica manualmente
            if (is_string($settings)) {
                $settings = json_decode($settings, true);
            }
            return $settings ?? [];
        });

        if (empty($settings)) {
            return $default;
        }

        // Suporta dot notation (ex: 'features.campaigns')
        $keys = explode('.', $key);
        $value = $settings;

        foreach ($keys as $k) {
            if (!is_array($value) || !isset($value[$k])) {
                return $default;
            }
            $value = $value[$k];
        }

        return $value;
    }
}

/**
 * Define configuração do app e salva no banco + cache
 * Suporta dot notation para atualização aninhada
 *
 * @param string $key Chave (ex: 'features.campaigns')
 * @param mixed $value Valor a definir
 * @return bool
 */
if (!function_exists('appConfigSet')) {
    function appConfigSet($key, $value)
    {
        $app = currentApp();

        if (!$app) {
            return false;
        }

        $settings = $app->settings ?? [];

        // Se ainda for string, decodifica manualmente
        if (is_string($settings)) {
            $settings = json_decode($settings, true) ?? [];
        }

        // Suporta dot notation para atualização aninhada
        $keys = explode('.', $key);
        $current = &$settings;

        foreach ($keys as $i => $k) {
            if ($i === count($keys) - 1) {
                $current[$k] = $value;
            } else {
                if (!isset($current[$k]) || !is_array($current[$k])) {
                    $current[$k] = [];
                }
                $current = &$current[$k];
            }
        }

        // Salva no banco
        $app->settings = $settings;
        $app->save();

        // Invalida cache
        appConfigClearCache($app->id);

        return true;
    }
}

/**
 * Limpa cache de configurações de um ou todos os apps
 *
 * @param string|null $appId UUID do app ou null para limpar todos
 * @return void
 */
if (!function_exists('appConfigClearCache')) {
    function appConfigClearCache($appId = null)
    {
        if ($appId) {
            tenantCacheForget("app_config_{$appId}");
        } else {
            // Limpa cache de todos os apps ativos
            $apps = \App\Models\App::active()->get(['id']);
            foreach ($apps as $app) {
                tenantCacheForget("app_config_{$app->id}");
            }
        }
    }
}

/**
 * Retorna todas as configurações do app atual
 *
 * @param bool $fromCache Se true, usa cache; se false, força reload do banco
 * @return array
 */
if (!function_exists('appConfigAll')) {
    function appConfigAll($fromCache = true)
    {
        $app = currentApp();

        if (!$app) {
            return [];
        }

        if (!$fromCache) {
            appConfigClearCache($app->id);
        }

        $cacheKey = "app_config_{$app->id}";

        return tenantCacheRemember($cacheKey, function () use ($app) {
            $settings = $app->settings;

            // Se ainda for string, decodifica manualmente
            if (is_string($settings)) {
                $settings = json_decode($settings, true);
            }

            return $settings ?? [];
        }, 3600);
    }
}

/**
 * Verifica se uma configuração existe (diferente de retornar default)
 *
 * @param string $key Chave (ex: 'features.campaigns')
 * @return bool
 */
if (!function_exists('appConfigHas')) {
    function appConfigHas($key)
    {
        $app = currentApp();

        if (!$app) {
            return false;
        }

        $cacheKey = "app_config_{$app->id}";
        $settings = tenantCacheRemember($cacheKey, function () use ($app) {
            $settings = $app->settings;
            // Se ainda for string, decodifica manualmente
            if (is_string($settings)) {
                $settings = json_decode($settings, true);
            }
            return $settings ?? [];
        });

        if (empty($settings)) {
            return false;
        }

        // Suporta dot notation
        $keys = explode('.', $key);
        $value = $settings;

        foreach ($keys as $k) {
            if (!is_array($value) || !isset($value[$k])) {
                return false;
            }
            $value = $value[$k];
        }

        return true;
    }
}

// ========================================
// HELPERS DE TEXTOS PERSONALIZÁVEIS
// ========================================

/**
 * Obtém texto personalizado do tenant com fallback para tradução padrão
 *
 * Hierarquia de busca:
 * 1. settings.texts.{key} do tenant atual
 * 2. __($key) do sistema de traduções
 * 3. $default fornecido
 *
 * @param string $key Chave do texto (ex: 'ui.welcome', 'email.signature')
 * @param string|null $default Valor padrão se não encontrar
 * @param string|null $locale Locale específico (default: app()->getLocale())
 * @return string
 */
if (!function_exists('appText')) {
    function appText($key, $default = null, $locale = null)
    {
        try {
            $locale = $locale ?: app()->getLocale();
            $cacheKey = "texts.{$key}.{$locale}";

            // 1. Busca no cache/config do tenant
            $tenantText = tenantCacheRemember($cacheKey, function() use ($key, $locale) {
                // Buscar na estrutura de textos do app atual
                $allConfig = appConfigAll();

                if (isset($allConfig['texts'][$key][$locale])) {
                    return $allConfig['texts'][$key][$locale];
                }

                return null;
            }, 3600); // 1 hora

            if (!is_null($tenantText)) {
                return $tenantText;
            }

            // 2. Fallback para sistema de traduções Laravel
            $translation = __($key);
            if ($translation !== $key) {
                return $translation;
            }

            // 3. Fallback para default ou chave
            return $default ?: $key;

        } catch (\Exception $e) {
            \Log::error("Erro ao buscar texto personalizado: {$key}", [
                'error' => $e->getMessage(),
                'key' => $key,
                'default' => $default
            ]);

            return $default ?: $key;
        }
    }
}

/**
 * Define texto personalizado para o tenant atual
 *
 * @param string $key Chave do texto
 * @param string $value Valor do texto
 * @param string|null $locale Locale específico (default: app()->getLocale())
 * @return bool
 */
if (!function_exists('appTextSet')) {
    function appTextSet($key, $value, $locale = null)
    {
        try {
            $locale = $locale ?: app()->getLocale();

            // Obter todas as configurações atuais
            $allConfig = appConfigAll();

            // Inicializar estrutura de textos se não existir
            if (!isset($allConfig['texts'])) {
                $allConfig['texts'] = [];
            }

            if (!isset($allConfig['texts'][$key])) {
                $allConfig['texts'][$key] = [];
            }

            // Definir o valor para a locale
            $allConfig['texts'][$key][$locale] = $value;

            // Salvar na configuração
            $result = appConfigSet('texts', $allConfig['texts']);
            return $result;

        } catch (\Exception $e) {
            \Log::error("Erro ao definir texto personalizado: {$key}", [
                'error' => $e->getMessage(),
                'key' => $key,
                'value' => $value,
                'locale' => $locale
            ]);

            return false;
        }
    }
}

/**
 * Obtém todos os textos personalizados do tenant
 *
 * @param string|null $locale Locale específico (default: app()->getLocale())
 * @param bool $fromCache Se deve usar cache (default: true)
 * @return array
 */
if (!function_exists('appTextAll')) {
    function appTextAll($locale = null, $fromCache = true)
    {
        try {
            $locale = $locale ?: app()->getLocale();
            $cacheKey = "texts_all.{$locale}";

            if (!$fromCache) {
                tenantCacheForget($cacheKey);
            }

            return tenantCacheRemember($cacheKey, function() use ($locale) {
                $allConfig = appConfigAll();
                $texts = [];

                // Extrai apenas textos da locale especificada
                if (isset($allConfig['texts'])) {
                    foreach ($allConfig['texts'] as $key => $localeData) {
                        if (isset($localeData[$locale])) {
                            $texts[$key] = $localeData[$locale];
                        }
                    }
                }

                return $texts;
            }, 3600);

        } catch (\Exception $e) {
            \Log::error("Erro ao buscar todos os textos: {$locale}", [
                'error' => $e->getMessage(),
                'locale' => $locale
            ]);

            return [];
        }
    }
}

/**
 * Verifica se existe texto personalizado para a chave
 *
 * @param string $key Chave do texto
 * @param string|null $locale Locale específico
 * @return bool
 */
if (!function_exists('appTextHas')) {
    function appTextHas($key, $locale = null)
    {
        try {
            $locale = $locale ?: app()->getLocale();

            $allConfig = appConfigAll();

            return isset($allConfig['texts'][$key][$locale]);

        } catch (\Exception $e) {
            return false;
        }
    }
}

/**
 * Remove texto personalizado
 *
 * @param string $key Chave do texto
 * @param string|null $locale Locale específico (se null, remove todas as locales)
 * @return bool
 */
if (!function_exists('appTextForget')) {
    function appTextForget($key, $locale = null)
    {
        try {
            $allConfig = appConfigAll();

            if (!isset($allConfig['texts'][$key])) {
                return true; // Já não existe
            }

            if ($locale) {
                // Remove apenas a locale específica
                unset($allConfig['texts'][$key][$locale]);

                // Se não sobrou nenhuma locale, remove a chave completamente
                if (empty($allConfig['texts'][$key])) {
                    unset($allConfig['texts'][$key]);
                }

                $result = appConfigSet('texts', $allConfig['texts']);

                // Limpa cache específico
                $cacheKey = "texts.{$key}.{$locale}";
                tenantCacheForget($cacheKey);
            } else {
                // Remove todas as locales da chave
                unset($allConfig['texts'][$key]);
                $result = appConfigSet('texts', $allConfig['texts']);

                // Limpa cache de todas as locales
                foreach (['pt', 'en', 'pt_BR', 'en_US'] as $loc) {
                    $cacheKey = "texts.{$key}.{$loc}";
                    tenantCacheForget($cacheKey);
                }
            }

            return $result ?? true;

        } catch (\Exception $e) {
            \Log::error("Erro ao remover texto personalizado: {$key}", [
                'error' => $e->getMessage(),
                'key' => $key,
                'locale' => $locale
            ]);

            return false;
        }
    }
}

// ========================================
// HELPERS DE CACHE POR TENANT
// ========================================

/**
 * Armazena um valor no cache isolado por tenant
 *
 * @param string $key Chave do cache
 * @param mixed $value Valor a armazenar
 * @param int|null $ttl Tempo de vida em segundos (null = 1 hora)
 * @return bool
 */
if (!function_exists('tenantCache')) {
    function tenantCache(string $key, $value = null, ?int $ttl = null)
    {
        // Se value é null, funciona como get
        if ($value === null && func_num_args() === 1) {
            return \App\Services\TenantCacheService::get($key);
        }

        // Caso contrário, funciona como put
        return \App\Services\TenantCacheService::put($key, $value, $ttl);
    }
}

/**
 * Obtém valor do cache ou executa callback se não existir (cache remember)
 *
 * @param string $key Chave do cache
 * @param \Closure $callback Função que retorna o valor
 * @param int|null $ttl Tempo de vida em segundos
 * @return mixed
 */
if (!function_exists('tenantCacheRemember')) {
    function tenantCacheRemember(string $key, \Closure $callback, ?int $ttl = null)
    {
        return \App\Services\TenantCacheService::remember($key, $callback, $ttl);
    }
}

/**
 * Remove uma chave do cache do tenant
 *
 * @param string $key Chave do cache
 * @return bool
 */
if (!function_exists('tenantCacheForget')) {
    function tenantCacheForget(string $key): bool
    {
        return \App\Services\TenantCacheService::forget($key);
    }
}

/**
 * Verifica se uma chave existe no cache do tenant
 *
 * @param string $key Chave do cache
 * @return bool
 */
if (!function_exists('tenantCacheHas')) {
    function tenantCacheHas(string $key): bool
    {
        return \App\Services\TenantCacheService::has($key);
    }
}

/**
 * Limpa todo o cache do tenant atual
 * ATENÇÃO: Operação pesada! Use com cautela.
 *
 * @param string|null $tenantId UUID do tenant (null = atual)
 * @return int Número de chaves removidas
 */
if (!function_exists('tenantCacheFlush')) {
    function tenantCacheFlush(?string $tenantId = null): int
    {
        return \App\Services\TenantCacheService::flush($tenantId);
    }
}

/**
 * Incrementa um contador no cache do tenant
 *
 * @param string $key Chave
 * @param int $value Valor a incrementar (padrão: 1)
 * @return int|bool
 */
if (!function_exists('tenantCacheIncrement')) {
    function tenantCacheIncrement(string $key, int $value = 1)
    {
        return \App\Services\TenantCacheService::increment($key, $value);
    }
}

/**
 * Decrementa um contador no cache do tenant
 *
 * @param string $key Chave
 * @param int $value Valor a decrementar (padrão: 1)
 * @return int|bool
 */
if (!function_exists('tenantCacheDecrement')) {
    function tenantCacheDecrement(string $key, int $value = 1)
    {
        return \App\Services\TenantCacheService::decrement($key, $value);
    }
}

/**
 * Cria um lock distribuído por tenant
 *
 * @param string $key Nome do lock
 * @param int $seconds Tempo máximo do lock
 * @return \Illuminate\Contracts\Cache\Lock
 */
if (!function_exists('tenantCacheLock')) {
    function tenantCacheLock(string $key, int $seconds = 10)
    {
        return \App\Services\TenantCacheService::lock($key, $seconds);
    }
}

/**
 * Obtém estatísticas de cache do tenant
 *
 * @return array
 */
if (!function_exists('tenantCacheStats')) {
    function tenantCacheStats(): array
    {
        return \App\Services\TenantCacheService::stats();
    }
}

/**
 * Verifica se o app tem uma feature ativa
 *
 * @param string $feature Nome da feature (ex: 'campaigns', 'events', 'analytics')
 * @return bool
 */
if (!function_exists('appHasFeature')) {
    function appHasFeature($feature)
    {
        return (bool) appConfig("features.{$feature}", false);
    }
}

/**
 * Retorna informações de e-mail do app
 *
 * @param string $type Tipo: 'from_name', 'from_address', 'reply_to'
 * @param string|null $default Valor padrão
 * @return string
 */
if (!function_exists('appEmail')) {
    function appEmail($type = 'from_address', $default = null)
    {
        $app = currentApp();

        if (!$app) {
            $defaults = [
                'from_name' => 'ProEventPay',
                'from_address' => 'noreply@proeventpay.com.br',
                'reply_to' => 'contato@proeventpay.com',
            ];
            return $default ?? ($defaults[$type] ?? '');
        }

        $emails = [
            'from_name' => $app->email_from_name ?? 'ProEventPay',
            'from_address' => $app->email_from_address ?? 'noreply@proeventpay.com.br',
            'reply_to' => $app->email_reply_to ?? 'contato@proeventpay.com',
        ];

        return $emails[$type] ?? ($default ?? '');
    }
}

/**
 * Retorna meta tags do app para SEO
 *
 * @param string $type Tipo: 'title', 'description', 'keywords', 'image'
 * @param string|null $default Valor padrão
 * @return string
 */
if (!function_exists('appMeta')) {
    function appMeta($type = 'title', $default = null)
    {
        $app = currentApp();

        if (!$app) {
            $defaults = [
                'title' => 'ProEventPay - Plataforma de Eventos e Campanhas',
                'description' => 'Sistema completo para gestão de eventos e campanhas',
                'keywords' => 'eventos, campanhas, ingressos',
                'image' => asset('images/app/proeventpay-social.jpg'),
            ];
            return $default ?? ($defaults[$type] ?? '');
        }

        $metas = [
            'title' => $app->meta_title ?? 'ProEventPay',
            'description' => $app->meta_description ?? '',
            'keywords' => $app->meta_keywords ?? '',
            'image' => $app->meta_image ? asset($app->meta_image) : '',
        ];

        return $metas[$type] ?? ($default ?? '');
    }
}

/**
 * Gera CSS inline com as variáveis de cores do app
 * Útil para incluir no <head> das páginas
 *
 * @return string
 */
if (!function_exists('appColorsCss')) {
    function appColorsCss()
    {
        $colors = appColors();

        return "<style>
:root {
    --app-color-primary: {$colors['primary']};
    --app-color-secondary: {$colors['secondary']};
    --app-color-accent: {$colors['accent']};
}
</style>";
    }
}

/**
 * Retorna a URL base do app
 *
 * @return string
 */
if (!function_exists('appUrl')) {
    function appUrl()
    {
        $app = currentApp();
        return $app->url_base ?? url('/');
    }
}

/**
 * Retorna o path do storage isolado por tenant
 *
 * @param string $path Path relativo dentro do storage do tenant
 * @return string Path completo isolado por UUID do app
 */
if (!function_exists('tenantStoragePath')) {
    function tenantStoragePath($path = '')
    {
        $app = currentApp();
        $appId = $app->id ?? 1; // Usa o UUID do app

        $basePath = storage_path("app/{$appId}");

        // Remove barra inicial se existir
        $path = ltrim($path, '/\\');

        return $path ? "{$basePath}/{$path}" : $basePath;
    }
}

/**
 * Retorna o path público isolado por tenant
 *
 * @param string $path Path relativo dentro de public
 * @return string Path completo em public/storage/{UUID}
 */
if (!function_exists('tenantPublicPath')) {
    function tenantPublicPath($path = '')
    {
        $app = currentApp();
        $appId = $app->id ?? 1; // Usa o UUID do app

        $basePath = public_path("storage/{$appId}");

        // Remove barra inicial se existir
        $path = ltrim($path, '/\\');

        return $path ? "{$basePath}/{$path}" : $basePath;
    }
}

/**
 * Retorna a URL de um asset público do tenant
 *
 * @param string $path Path relativo dentro do storage público do tenant
 * @param bool $absolute Se deve retornar URL absoluta
 * @return string URL do asset
 */
if (!function_exists('tenantAsset')) {
    function tenantAsset($path, $absolute = false, $app = null)
    {
        if (!is_string($path) || trim($path) === '') {
            return '';
        }

        $path = trim($path);

        if (preg_match('#^/?storage/[0-9a-f-]{36}/(images_eventos/.+|images_patrocinadores/.+|images_customers_logo/.+)$#i', $path, $matches) === 1) {
            return asset($matches[1]);
        }

        // Mantem URLs absolutas intactas.
        if (preg_match('#^https?://#i', $path) === 1) {
            return $path;
        }

        $legacyPrefixes = [
            '/storage/',
            'storage/',
            'images_eventos/',
            'images_patrocinadores/',
            'images_customers_logo/',
            'campanhas/',
        ];

        foreach ($legacyPrefixes as $prefix) {
            if (str_starts_with($path, $prefix)) {
                return asset(ltrim($path, '/'));
            }
        }

        $app = $app ?? currentApp();
        $appId = $app->id ?? 1; // Usa o UUID do app como identificador

        // Remove barra inicial se existir
        $path = ltrim($path, '/\\');

        $url = "storage/{$appId}/{$path}";

        return $absolute ? url($url) : asset($url);
    }
}

/**
 * Cria diretórios do tenant se não existirem
 *
 * @param string $type Tipo de diretório: customers, campaigns, events, exports
 * @return void
 */
if (!function_exists('ensureTenantDirectory')) {
    function ensureTenantDirectory($type = null)
    {
        $app = currentApp();
        $appId = $app->id ?? 1; // Usa o UUID do app

        $directories = [
            "storage/app/{$appId}/customers",
            "storage/app/{$appId}/campaigns",
            "storage/app/{$appId}/events",
            "storage/app/{$appId}/exports",
        ];

        foreach ($directories as $dir) {
            $fullPath = base_path($dir);
            if (!file_exists($fullPath)) {
                mkdir($fullPath, 0755, true);
            }
        }

        // Criar link simbólico público se não existir
        $publicLink = public_path("storage/{$appId}");
        $storageTarget = storage_path("app/{$appId}");

        // Verificar e criar symlink com tratamento de erros
        if (!file_exists($publicLink) && file_exists($storageTarget)) {
            try {
                if (PHP_OS_FAMILY === 'Windows') {
                    // Windows requer privilégios elevados para symlinks
                    // Tentar criar, mas não falhar se não conseguir
                    @symlink($storageTarget, $publicLink);
                } else {
                    // Linux/Unix
                    if (!@symlink($storageTarget, $publicLink)) {
                        // Se falhar, registrar log mas não interromper execução
                        \Log::warning("Não foi possível criar symlink: {$publicLink} -> {$storageTarget}");
                    }
                }
            } catch (\Exception $e) {
                // Registrar erro mas não interromper a execução
                \Log::warning("Erro ao criar symlink para app {$appId}: " . $e->getMessage());
            }
        }
    }
}

// ============================================
// CAMPAIGN ORGANIZERS - Helpers com segurança
// ============================================

if (!function_exists('sessionCampaignOrganizers'))
{
    /**
     * Retorna apenas os organizadores de CAMPANHA aos quais o usuário tem acesso
     * Implementa a mesma lógica de segurança do sessionOrganizers (eventos)
     */
    function sessionCampaignOrganizers($customer_id = false)
    {
        $organizers = Auth::user()->campaignOrganizers ?? false;

        // SE ADMIN DA APLICAÇÃO (admin ou super-admin vê todos)
        if (isAdmin()) {
            if ($customer_id) {
                $organizers = \App\Models\ModCampaign\CampaignOrganizer::where('customer_id', $customer_id)->get();
            } else {
                $organizers = \App\Models\ModCampaign\CampaignOrganizer::all();
            }
        }
        // SE OWNER do customer (vê todos do customer)
        elseif (isOwner() && $customer = sessionCustomer()) {
            $organizers = \App\Models\ModCampaign\CampaignOrganizer::where('customer_id', $customer->id)->get();
        }
        // Usuário comum: apenas organizadores aos quais está associado
        elseif ($customer_id && $organizers) {
            $organizers = $organizers->where('customer_id', $customer_id);
        }

        return $organizers ?: collect();
    }
}

if (!function_exists('sessionCampaignOrganizer'))
{
    /**
     * Retorna um organizador de campanha específico SE o usuário tiver acesso a ele
     */
    function sessionCampaignOrganizer($id = false)
    {
        if (!$id) {
            return session('campaign_organizer');
        }

        // Busca o organizador
        $organizer = \App\Models\ModCampaign\CampaignOrganizer::find($id);

        if (!$organizer) {
            return null;
        }

        // Verifica se o usuário tem acesso a este organizador
        $allowedOrganizers = sessionCampaignOrganizers($organizer->customer_id);

        if (!$allowedOrganizers->contains('id', $id)) {
            // Usuário não tem acesso a este organizador
            return null;
        }

        // Salva na sessão
        session(['campaign_organizer' => $organizer]);

        return $organizer;
    }
}

/**
 * Retorna a URL da thumbnail padrão do app
 *
 * @param bool $absolute Se true, retorna URL absoluta
 * @param \App\Models\App|null $app App específico (opcional)
 * @return string
 */
if (!function_exists('appDefaultThumb')) {
    function appDefaultThumb($absolute = false, $app = null)
    {
        $app = $app ?? currentApp();

        if (!$app || !$app->url_image_default_thumb) {
            return asset('images/default-thumb.png');
        }

        $thumb = $app->url_image_default_thumb;

        // Para compatibilidade com URLs antigas que começam com /storage/
        if (str_starts_with($thumb, '/storage/')) {
            return asset($thumb);
        }

        // Usa tenantAsset para storage isolado por UUID
        $url = tenantAsset($thumb, $absolute, $app);

        // Cache busting: adiciona timestamp de atualização do branding
        if ($app->branding_updated_at) {
            $separator = str_contains($url, '?') ? '&' : '?';
            $url .= $separator . 'v=' . $app->branding_updated_at->timestamp;
        }

        return $url;
    }
}

/**
 * Obtém o UUID do usuário do app quando disponível
 *
 * Esta função pode ser usada em qualquer lugar do sistema para acessar
 * o appUserUuid capturado da query string, útil para rastrear usuários
 * específicos do app em fluxos de campanha.
 *
 * @return string|null
 */
if (!function_exists('getAppUserUuid')) {
    function getAppUserUuid()
    {
        // Tenta obter de várias fontes possíveis

        // 1. Da sessão (se foi armazenado)
        if ($uuid = session('appUserUuid')) {
            return $uuid;
        }

        // 2. Do request atual
        if ($uuid = request()->get('appUserUuid')) {
            // Armazena na sessão para uso futuro
            session(['appUserUuid' => $uuid]);
            return $uuid;
        }

        return null;
    }
}

/**
 * Define o UUID do usuário do app na sessão
 *
 * @param string|null $uuid
 * @return void
 */
if (!function_exists('setAppUserUuid')) {
    function setAppUserUuid($uuid)
    {
        if ($uuid) {
            session(['appUserUuid' => $uuid]);
        } else {
            session()->forget('appUserUuid');
        }
    }
}

/**
 * Obtém o appSource quando disponível
 *
 * @return string|null
 */
if (!function_exists('getAppSource')) {
    function getAppSource()
    {
        if ($source = session('appSource')) {
            return $source;
        }

        if ($source = request()->get('appSource')) {
            session(['appSource' => $source]);
            return $source;
        }

        return null;
    }
}

/**
 * Define o appSource na sessão
 *
 * @param string|null $source
 * @return void
 */
if (!function_exists('setAppSource')) {
    function setAppSource($source)
    {
        if ($source) {
            session(['appSource' => $source]);
        } else {
            session()->forget('appSource');
        }
    }
}

