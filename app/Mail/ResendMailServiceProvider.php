<?php

namespace App\Mail;

use Illuminate\Support\ServiceProvider;
use Illuminate\Mail\MailManager;
use App\Mail\Transport\ResendTransport;

class ResendMailServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->app->afterResolving(MailManager::class, function (MailManager $manager) {
            $manager->extend('resend', function (array $config) {
                return new ResendTransport(
                    config('services.resend.key')
                );
            });
        });
    }
}
