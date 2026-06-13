<?php

namespace App\Http\Controllers\Notificacao;

use App\Http\Controllers\Controller;
use App\Jobs\Notificacao\NotificacaoJob;
use App\Jobs\Testes\DemoMailJob;
use App\Jobs\Pagamentos\PagamentoJob;
use App\Mail\Notificacao\NotificacaoMail;
use App\Mail\Testes\DemoMail;
use App\Mail\Pagamentos\PagamentoMail;
use App\Models\Instituicoes\Instituicao;
use App\Models\Notificaoes\Notificacao;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public $tipo;
    public $to;
    public $cc;
    public $bcc;
    public $subject;
    public $urlLogo;
    public $colorBg;
    public $header;
    public $body;
    public $footer;
    public $dispatch;
    //
    public $mailData;

    public function enviarEmail($tipo='envioTeste', $to=[], $cc=[], $bcc=[], $subject=false, $urlLogo=false, $colorBg=false, $header=false, $body=false, $footer=false, $dispatch=true)
    {
        try
        {
            //
            $this->mailData = [
                'tipo'     => $this->tipo     = $tipo,
                'to'       => $this->to       = implode(';', is_array($to)  ? $to  : [$to] ),
                'cc'       => $this->cc       = implode(';', is_array($cc)  ? $cc  : [$cc] ),
                'bcc'      => $this->bcc      = implode(';', is_array($bcc) ? $bcc : [$bcc] ),
                'subject'  => $this->subject  = $subject,
                'urlLogo'  => $this->urlLogo  = $urlLogo ?? asset('images/app/proeventpay-logo.png'),
                'colorBg'  => $this->colorBg  = $colorBg,
                'header'   => $this->header   = $header,
                'body'     => $this->body     = $body,
                'footer'   => $this->footer   = $footer,
                'dispatch' => $this->dispatch = ($dispatch) ? true : false,
            ];

            // TIPO DE EMAIL ENVIO
            switch ($this->tipo)
            {
                case 'envioTeste':
                    $emailReturn = $this->envioTeste($this->mailData);
                    break;

                case 'enviar':
                    $emailReturn = $this->enviar($this->mailData,$dispatch);
                    break;

                default:
                    return response()->json('Tipo não definido - ' . ($this->tipo ?? 'ND'), 404);
            }

            // dd($emailReturn);

            // dd(asset('images/app/proeventpay-logo-color.png'));

            return response()->json('Email processado com sucesso', 200);
        }
        catch (\Throwable $th)
        {
            // dd('Throwable',$th);

            return response()->json($th->getMessage(), $th->getCode());
        }
    }

    private function envioTeste($mailData)
    {
        $mailData['to']      = ($mailData['to'] ?? false) ? $mailData['to'] : 'proeventpay@gmail.com';
        $mailData['cc']      = ($mailData['cc'] ?? false) ? $mailData['cc'] : 'proeventpay@gmail.com';
        //
        $mailData['subject'] = ($mailData['subject'] ?? false) ? $mailData['subject'] : ("ENVIO #" . now()->format('Hi'));
        $mailData['header']  = ($mailData['header']  ?? false) ? $mailData['header']  : 'header';
        $mailData['body']    = ($mailData['body']    ?? false) ? $mailData['body']    : 'body';
        $mailData['footer']  = ($mailData['footer']  ?? false) ? $mailData['footer']  : 'footer';
        //
        $mailData['urlLogo'] = ($mailData['urlLogo'] ?? false) ? $mailData['urlLogo'] : asset('images/app/proeventpay-logo.png');
        $mailData['colorBg'] = ($mailData['colorBg'] ?? false) ? $mailData['colorBg'] : '#9b2767';

        // dd($mailData);

        return $this->enviar($mailData,dispatch:false);
    }

    private function enviar($mailData, $dispatch=true)
    {
        //
        $mailData['bcc'] = $mailData['bcc'] ? $mailData['bcc'] : env('MAIL_FROM_ADDRESS');

        //
        if($dispatch ?? false)
            return NotificacaoJob::dispatch($mailData);
        else
            return Mail::to($mailData['to'])->cc($mailData['cc'])->bcc($mailData['bcc'])->send(new NotificacaoMail($mailData));
    }

    // private function registraEnvio($response, $status='OK')
    // {
    //     $notificacao = Notificacao::create([
    //         'formato'      => 'email',
    //         'tipo'         => $this->tipo,
    //         'ref'          => $this->ref,
    //         'ref_id'       => $this->ref_id,
    //         'envio_tipo'   => ($this->dispatch ?? false) ? 'ENVIO_FILA' : 'ENVIO_DIRETO',
    //         'envio_status' => $status,
    //         'data'         => json_encode($this->mailData),
    //         'response'     => $response,
    //     ]);

    //     return $notificacao;
    // }
}

