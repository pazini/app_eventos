<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
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
        // Configura domínio e prefix dinamicamente baseado no ambiente
        $this->configureFortifyRoutes();

        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->email;

            return Limit::perMinute(5)->by($email.$request->ip());
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });
    }

    /**
     * Configure Fortify routes based on environment
     *
     * @return void
     */
    protected function configureFortifyRoutes()
    {
        $isLocalDev = in_array(request()->getHost(), ['127.0.0.1', 'localhost']);

        if ($isLocalDev) {
            // Desenvolvimento local: /painel/*
            config(['fortify.prefix' => 'painel']);
            config(['fortify.domain' => null]);
        } else {
            // Produção: subdomínio painel.*
            config(['fortify.prefix' => '']);
            config(['fortify.domain' => parse_url(config('domains.painel'), PHP_URL_HOST)]);
        }
    }
}
