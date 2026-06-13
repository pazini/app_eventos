<?php

namespace App\Mail\Transport;

use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mime\MessageConverter;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Resend;
use Illuminate\Support\Facades\Log;

class ResendTransport extends AbstractTransport
{
    /**
     * The Resend API key.
     */
    protected string $key;

    /**
     * The Resend client.
     */
    protected $resend;

    /**
     * Create a new Resend transport instance.
     */
    public function __construct(string $key, EventDispatcherInterface $dispatcher = null, LoggerInterface $logger = null)
    {
        parent::__construct($dispatcher, $logger);

        $this->key = $key;

        // Configuração para Windows (resolve problema SSL)
        if (PHP_OS_FAMILY === 'Windows' || env('RESEND_SSL_VERIFY', true) === false) {
            Log::info('[RESEND] Configurando para Windows - desabilitando verificação SSL');

            // Configurações temporárias para resolver SSL no Windows
            ini_set('curl.cainfo', '');
            ini_set('openssl.cafile', '');
        }

        $this->resend = Resend::client($key);
    }

    /**
     * {@inheritDoc}
     */
    protected function doSend(SentMessage $message): void
    {
        $email = MessageConverter::toEmail($message->getOriginalMessage());

        try {
            $payload = [
                'from' => $this->getFromAddress($email),
                'to' => $this->getToAddresses($email),
                'subject' => $email->getSubject(),
            ];

            // Adiciona HTML se disponível
            if ($email->getHtmlBody()) {
                $payload['html'] = $email->getHtmlBody();
            }

            // Adiciona texto plano se disponível
            if ($email->getTextBody()) {
                $payload['text'] = $email->getTextBody();
            }

            // Adiciona CC se houver
            if ($cc = $this->getCcAddresses($email)) {
                $payload['cc'] = $cc;
            }

            // Adiciona BCC se houver
            if ($bcc = $this->getBccAddresses($email)) {
                $payload['bcc'] = $bcc;
            }

            // Adiciona reply-to se houver
            if ($replyTo = $this->getReplyToAddresses($email)) {
                $payload['reply_to'] = $replyTo;
            }

            // Envia via Resend com tratamento especial para Windows
            if (PHP_OS_FAMILY === 'Windows' || env('RESEND_SSL_VERIFY', true) === false) {
                // Requisição HTTP direta para contornar problema SSL no Windows
                $context = stream_context_create([
                    "http" => [
                        "method" => "POST",
                        "header" => [
                            "Authorization: Bearer " . $this->key,
                            "Content-Type: application/json",
                        ],
                        "content" => json_encode($payload),
                        "timeout" => 30,
                        "ignore_errors" => true,
                    ],
                    "ssl" => [
                        "verify_peer" => false,
                        "verify_peer_name" => false,
                        "allow_self_signed" => true,
                    ]
                ]);

                $result = file_get_contents("https://api.resend.com/emails", false, $context);

                if ($result === false) {
                    throw new \Exception("Falha na requisição HTTP para Resend API");
                }

                $response = json_decode($result, true);

                if (isset($response['message'])) {
                    throw new \Exception("Resend API Error: " . $response['message']);
                }

                Log::info('[RESEND] Email enviado via HTTP direto (Windows SSL bypass)', [
                    'message_id' => $response['id'] ?? 'unknown',
                    'to' => $payload['to'],
                    'subject' => $payload['subject'],
                ]);

            } else {
                // Cliente Resend normal para outros sistemas
                $response = $this->resend->emails->send($payload);

                Log::info('[RESEND] Email enviado com sucesso', [
                    'message_id' => $response->id ?? 'unknown',
                    'to' => $payload['to'],
                    'subject' => $payload['subject'],
                ]);
            }

        } catch (\Exception $e) {
            Log::error('[RESEND] Erro ao enviar email', [
                'error' => $e->getMessage(),
                'to' => $payload['to'] ?? 'unknown',
                'subject' => $payload['subject'] ?? 'unknown',
            ]);

            throw $e;
        }
    }

    /**
     * Get the "from" address from the email.
     */
    protected function getFromAddress($email): string
    {
        $from = $email->getFrom();
        $fromAddress = $from[0] ?? null;

        if (!$fromAddress) {
            return config('mail.from.address');
        }

        $name = $fromAddress->getName();
        $address = $fromAddress->getAddress();

        return $name ? "{$name} <{$address}>" : $address;
    }

    /**
     * Get the "to" addresses from the email.
     */
    protected function getToAddresses($email): array
    {
        $addresses = [];

        foreach ($email->getTo() as $address) {
            $name = $address->getName();
            $email = $address->getAddress();

            $addresses[] = $name ? "{$name} <{$email}>" : $email;
        }

        return $addresses;
    }

    /**
     * Get the "cc" addresses from the email.
     */
    protected function getCcAddresses($email): ?array
    {
        $addresses = [];

        foreach ($email->getCc() as $address) {
            $name = $address->getName();
            $email = $address->getAddress();

            $addresses[] = $name ? "{$name} <{$email}>" : $email;
        }

        return empty($addresses) ? null : $addresses;
    }

    /**
     * Get the "bcc" addresses from the email.
     */
    protected function getBccAddresses($email): ?array
    {
        $addresses = [];

        foreach ($email->getBcc() as $address) {
            $name = $address->getName();
            $email = $address->getAddress();

            $addresses[] = $name ? "{$name} <{$email}>" : $email;
        }

        return empty($addresses) ? null : $addresses;
    }

    /**
     * Get the "reply-to" addresses from the email.
     */
    protected function getReplyToAddresses($email): ?array
    {
        $addresses = [];

        foreach ($email->getReplyTo() as $address) {
            $name = $address->getName();
            $email = $address->getAddress();

            $addresses[] = $name ? "{$name} <{$email}>" : $email;
        }

        return empty($addresses) ? null : $addresses;
    }

    /**
     * Get the string representation of the transport.
     */
    public function __toString(): string
    {
        return 'resend';
    }
}
