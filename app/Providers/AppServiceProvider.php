<?php

namespace App\Providers;

use App\Models\Customer;
use App\Models\AppEvent\AppEventOrder;
use App\Models\ModCampaign\CampaignOrganizer;
use App\Observers\CustomerObserver;
use App\Observers\AppEventOrderObserver;
use App\Observers\CampaignOrganizerObserver;
use App\Http\Middleware\TrimStrings as AppTrimStrings;
use Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Force HTTPS only in production
        if ($this->app->environment('production')) {
            $request = app('request');
            $forwardedProto = strtolower((string) $request->header('X-Forwarded-Proto'));
            $isHttps = $request->isSecure() || str_contains($forwardedProto, 'https');

            if ($isHttps) {
                URL::forceScheme('https');
            }
        }

        $skipLivewire = function ($request) {
            return $request->hasHeader('X-Livewire');
        };

        AppTrimStrings::skipWhen($skipLivewire);
        ConvertEmptyStringsToNull::skipWhen($skipLivewire);

        // Observers
        Customer::observe(CustomerObserver::class);
        AppEventOrder::observe(AppEventOrderObserver::class);
        CampaignOrganizer::observe(CampaignOrganizerObserver::class);
    }
}
