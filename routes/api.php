<?php

use App\Jobs\AppEvent\NotificationAppEventCompra;
use App\Models\AppCallback;
use App\Models\AppConfig;
use App\Models\AppEvent\AppEventOrder;
use App\Models\AppEvent\AppEventOrderSponsorship;
use App\Models\AppEvent\AppEventOrderTicket;
use App\Models\AppPayment\AppPayment;
use App\Models\AppPayment\AppPaymentCallback;
use App\Models\ModEvent\Event;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

include('api-campanha.php');

include('api-v2.php');

include('api-v1.php');


// GET JSON TO IMPORT - INTEGRAÇÃO WORKSHOPS/SORTEIOS
Route::match(['get', 'post'], '/export/evento/{eventoSlug?}', function ($eventoSlug=false) {

    try
    {
        if ($eventoSlug && $evento = Event::with(['tickets'])->where('event_slug',$eventoSlug)->first())
        {

            $tickets = $evento->tickets->where('ticket_status','disponivel');

            return response()->json([
                'return'  => $tickets->toArray()
            ], 200);
        }

    }
    catch (\Throwable $th)
    {
        // return response($th->getMessage(),$th->getCode() ?? 500);

        return response()->json([
            'erro' => true,
            'msg'  => $th->getMessage(),
            'code' => $th->getCode() ?? 500,
            'type' => 'error',
        ], $th->getCode() ?? 500);
    }

});

// ENVIO EMAIL TESTE
Route::match(['get'], '/envio-email-teste/{email}/{assunto?}/{mensagem?}', function (Request $request,$email,$assunto=null,$mensagem=null)
{
    try
    {
        // TESTANTO LAYOUT
        $n = notificaEmail($email,$assunto ?? 'TESTE DE ENVIO',$mensagem ?? 'SEM CONTEÚDO');

        dd(
            'Email: ' . $email,
            'Assunto: ' . $assunto,
            'Mensagem: ' . $mensagem,
            'Enviado: ' . $n,
        );

        return response()->json([
            'msg'        => 'success',
            'envio'      => $processar ?? [],
            'error'      => false,
        ], 200);
    }
    catch (\Throwable $th)
    {
        return response()->json([
            'error' => true,
            'msg'   => $th->getMessage(),
            'code'  => $th->getCode(),
            'line'  => $th->getLine(),
            'file'  => $th->getFile(),
        ], 401);
    }

})->name('envio-email-teste');

