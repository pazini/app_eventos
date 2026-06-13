<?php

use App\Models\AppEvent\AppEventOrderTicket;
use App\Models\AppPayment\AppPayment;
use App\Models\AppPayment\AppPaymentCallback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

// SAFE 2 PAY - PROCESSAR
Route::match(['get'], '/payment-callback/safe2pay/processar/{callbackId?}', function (Request $request,$callbackId=false )
{
    try
    {
        if($callbackId ?? false)
            $callbacks = AppPaymentCallback::where('id',$callbackId)->get();
        else
            $callbacks = AppPaymentCallback::whereNull('postback_processed')->whereIn('status',['autorizado'])->orderBy('updated_at')->get()->take(5);

        $processar = [];

        foreach ($callbacks as $call_key => $call_values)
        {
            $callbacks[$call_key]->postback_processed = now()->format('Y-m-d H:i:s');
            $callbacks[$call_key]->postback_processed_status = 'iniciado';
            $callbacks[$call_key]->save();

            // SE NAO NSU
            if(!$call_values->nsu)
            {
                $callbacks[$call_key]->postback_processed_status = 'sem-nsu';
                $callbacks[$call_key]->save();
                //
                $processar[$call_values->id] = $callbacks[$call_key]->postback_processed_status;
                continue;
            }

            // SE NAO ID TRANSAÇÃO
            if(!$call_values->payment_id)
            {
                $callbacks[$call_key]->postback_processed_status = 'payment-id';
                $callbacks[$call_key]->save();
                //
                $processar[$call_values->id] = $callbacks[$call_key]->postback_processed_status;
                continue;
            }

            // SE NAO ORDER ID
            if(!$call_values->order_id)
            {
                $callbacks[$call_key]->postback_processed_status = 'order-id';
                $callbacks[$call_key]->save();
                //
                $processar[$call_values->id] = $callbacks[$call_key]->postback_processed_status;
                continue;
            }

            //
            if($payment = AppPayment::with(['gateway'])->find($call_values->payment_id))
            {
                $token   = $payment->gateway->token_live;

                // VALIDA TRANSAÇÃO
                $processar[$call_values->id] = safe2payValidarPagamento($call_values->order_id,$call_values->nsu,$call_values->payment_id,$token);

                // PREOCESSAR
                $callbacks[$call_key]->postback_processed_status = $processar[$call_values->id]->status;
                $callbacks[$call_key]->save();
            }
            else
            {
                // PREOCESSAR
                $callbacks[$call_key]->postback_processed_status = 'Não possui transação associada';
                $callbacks[$call_key]->save();
                //
                $processar[$call_values->id] = $callbacks[$call_key]->postback_processed_status;
            }
        }

        return response()->json([
            'msg'        => 'success',
            'processado' => $processar ?? [],
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
})->name('payment-callback-processar-safe2pay');


// SAFE 2 PAY - SEMPRE DEPOIS DE PROCESSAR PARA NAO ENTRAR NA {callbackType}
Route::match(['get', 'post'], '/payment-callback/safe2pay/{callbackType}/{orderId?}/{paymentId?}', function (Request $request, $callbackType=null, $orderId=null, $paymentId=null)
{
    try
    {
        //
        $gatewaySlug = 'safe2pay';
        $data        = $request->all();

        //
        $modelCallback = AppPaymentCallback::create([
            'callback_type'          => $callbackType,
            'order_id'               => $orderId ?? NULL,
            'payment_id'             => $paymentId ?? NULL,
            'gateway_slug'           => $gatewaySlug,
            'postback_url'           => URL::current() ?? null,
            'json_response'          => json_encode($data ?? []),
            'gateway_id'             => null,
            'gateway_transaction_id' => null,
            'status'                 => 'aguardando',
            'status_old'             => null,
            'value_paid'             => null,
            'nsu'                    => null,
            'pay_type'               => null,
            'pay_datetime'           => null,
            'card_first'             => null,
            'card_last'              => null,
            'card_name'              => null,
            'card_brand'             => null,
            'boleto_barcode'         => null,
            'boleto_expiration_date' => null,
            'boleto_url'             => null,
            'postback_id'            => null,
            'ref_controle'           => null,
        ]);

        // PARSE
        $modelCallback->nsu                    = $data['IdTransaction'] ?? null;
        $modelCallback->gateway_transaction_id = $data['IdTransaction'] ?? null;
        $modelCallback->status_old             = $modelCallback->status;
        $modelCallback->status                 = toSlug($data['TransactionStatus']['Name'] ?? null,'-');
        $modelCallback->value_paid             = convertDecimalInt($data['Amount'] ?? null);
        $modelCallback->pay_type               = toSlug($data['PaymentMethod']['Name'] ?? null,'-');
        $modelCallback->ref_controle           = $data['Reference'] ?? null;
        $modelCallback->save();

        // PROCESSA
        $modelCallback->postback_processed = now()->format('Y-m-d H:i:s');
        $modelCallback->postback_processed_status = 'iniciado';
        $modelCallback->save();

        // SE PAGO
        if(in_array($modelCallback->status,['autorizado']))
        {

            if(!$modelCallback->nsu)
            {
                $modelCallback->postback_processed_status = 'sem-nsu';
                $modelCallback->save();
            }
            else
            {
                //
                $payment = AppPayment::with(['gateway'])->find($modelCallback->payment_id);
                $token   = $payment->gateway->token_live;

                $processar = safe2payValidarPagamento($orderId,$modelCallback->nsu,$modelCallback->payment_id,$token);
                $modelCallback->postback_processed_status = $processar->status;
                $modelCallback->save();
            }
        }
        else
        {
            // PROCESSA
            $modelCallback->postback_processed = now()->format('Y-m-d H:i:s');
            $modelCallback->postback_processed_status = $modelCallback->status;
            $modelCallback->save();
        }

        return response()->json([
            'error'        => false,
            'msg'          => 'success',
            'reference_id' => $modelCallback->id ?? null,
        ], 200);
    }
    catch (\Throwable $th)
    {
        // dd($th);

        $modelCallback->postback_processed_status = 'erro - ' . $th->getMessage() . ' // ' . $th->getFile() . ':' . $th->getLine();
        $modelCallback->save();

        return response()->json([
            'error' => true,
            'msg'   => $th->getMessage(),
            'code'  => $th->getCode(),
            'line'  => $th->getLine(),
            'file'  => $th->getFile(),
        ], 401);
    }

})->name('payment-callback-gateway-slug');


// CHECKIN
Route::match(['get', 'post'], '/checkin/{target}/{control}', function (Request $request, $target, $control) {

    try
    {
        switch ($target)
        {
            case 'evento':
            case 'event':
            case 'app_event':
            default:
                $targetCheckin = AppEventOrderTicket::with('event')
                    ->where('ticket_control',$control)
                    ->first();
                break;
        }

        if(!$targetCheckin)
        {
            throw new Exception('NADA ENCONTRADO PARA ' . substr($control,0,30),'404');
        }

        return response()->json([
            'erro'    => false,
            'msg'     => 'Dados do checkin encontrados com sucesso.',
            'code'    => 200,
            'type'    => 'json',
            'control' => $control,
            'return'  => $targetCheckin->toArray()
        ], 200);

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

        dd(
            $th,
        );
    }

})->name('checkin-buscar');

Route::match(['get', 'post'], '/checkin/{target}/{control}/realizar', function (Request $request, $target, $control) {

    try
    {
        switch ($target)
        {
            case 'evento':
            case 'event':
            case 'app_event':
            default:
                $targetCheckin = AppEventOrderTicket::with('event')
                    ->where('ticket_control',$control)
                    ->first();
                break;
        }

        if(!$targetCheckin)
        {
            throw new Exception('Não localizado!','404');
        }

        //
        $targetCheckin->ticket_status = 'utilizado';
        $targetCheckin->ticket_checkin_datetime = now();
        $targetCheckin->save();

        return response()->json([
            'erro'    => false,
            'msg'     => 'Dados do checkin encontrados com sucesso.',
            'code'    => 200,
            'type'    => 'json',
            'control' => $control,
            'return'  => $targetCheckin->toArray()
        ], 200);

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

        dd(
            $th,
        );
    }

})->name('checkin-realizar');

