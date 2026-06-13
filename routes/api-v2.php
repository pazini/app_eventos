<?php

use App\Models\AppPayment\AppPayment;
use App\Models\AppPayment\AppPaymentCallback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

// SAFE 2 PAY
Route::match(['get', 'post'], '/payment-callback-safe2pay/{callbackType}/{orderId?}/{paymentId?}', function (Request $request, $callbackType=null, $orderId=null, $paymentId=null)
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
        $modelCallback->postback_processed_status = 'em-analise';
        $modelCallback->save();

        //
        if($modelCallback->nsu ?? false)
        {
            //
            if($payment = AppPayment::with(['gateway'])->find($modelCallback->payment_id))
            {
                $modelCallback->postback_processed_status = $modelCallback->status;
                $modelCallback->postback_processed_json   = json_encode([
                    'status'        => $modelCallback->status ?? 'sem-status',
                    'ref'           => __LINE__ . $modelCallback->postback_processed_status ?? null,
                    'payment'       => $payment->toArray(),
                ]);
                $modelCallback->save();

                //
                if(in_array($modelCallback->status,listPaymentStatusPaidCanceled()))
                {
                    $modelCallback->postback_processed_status = 'processar';
                    $modelCallback->postback_processed_json = json_encode([
                        'status'        => $modelCallback->status ?? 'sem-status',
                        'ref'           => __LINE__ . '-' . $modelCallback->postback_processed_status ?? null,
                        'payment'       => $payment->toArray(),
                    ]);
                    $modelCallback->save();

                    return response()->json([
                        'error'        => false,
                        'msg'          => 'success',
                        'reference_id' => $modelCallback->id ?? null,
                    ], 200);
                }

                // SE PENDENTE
                if(in_array($modelCallback->status,['pendente']))
                {
                    //
                    if($payment->order_slip_id ?? false)
                    {
                        $modelCallback->postback_processed_status = 'carne-pendente';
                        $modelCallback->postback_processed_json   = json_encode([
                            'status'        => $modelCallback->status,
                            'ref'           => __LINE__ . $modelCallback->postback_processed_status ?? null,
                            'order_slip_id' => $payment->order_slip_id,
                            'payment'       => $payment->toArray(),
                        ]);
                        $modelCallback->save();
                    }
                }
            }
            else
            {
                $modelCallback->postback_processed_status = $modelCallback->status;
                $modelCallback->postback_processed_json   = json_encode([
                    'status'        => $modelCallback->status,
                    'ref'           => __LINE__ . $modelCallback->postback_processed_status ?? null,
                    'payment'       => 'paymento-404',
                ]);

                // PROCESSA
                $modelCallback->postback_processed = now()->format('Y-m-d H:i:s');
                $modelCallback->save();
            }
        }
        else
        {
            $modelCallback->postback_processed_status = 'sem-nsu';
            $modelCallback->postback_processed_json   = json_encode([
                'status'        => $modelCallback->status . '-sem-nsu',
                'ref'           => __LINE__ . $modelCallback->postback_processed_status ?? null,
                'payment'       => 'paymento-404',
            ]);
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

})->name('payment-callback-safe2pay');


// SAFE 2 PAY - PROCESSAR v2
Route::match(['get'], '/payment-callback-safe2pay-processar/{callbackId?}', function (Request $request,$callbackId=false )
{
    try
    {
        return response()->json([
            'msg'        => 'success',
            'processado' => consolidaCallbacksPayments($callbackId ?? false),
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
})->name('payment-callback-safe2pay-processar');

// LEMBRETE CARNÊ
Route::match(['get'], '/lembrete-notifica-carne/{qtdDias?}', function (Request $request,$qtdDias=false)
{
    try
    {
        return response()->json([
            'msg'        => 'success',
            'processado' => notificaPagamentoLembreteCarne($qtdDias ?? false),
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
})->name('payment-callback-safe2pay-processar');
