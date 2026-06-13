<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        Commands\AppEventClearReservaTemp::class,
        Commands\AppEventReservaTempBoletoUpdate::class,
        Commands\TestEmailConnection::class,
        Commands\ProcessCampaignRecurring::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        // CLEAR EVENTS - RESERVA TEMP
        $schedule->command('appEvent:clearReservaTemp')->everyMinute();

        // CONSOLIDA CALLBACKS PAGAMENTOS
        $schedule->command('consolida:callbacksPayments')->everyMinute();

        // NOTIFICA PAGAMENTOS CARNE
        $schedule->command('notificacao:lembreteCarne')->dailyAt('00:25');

        // VERIFICA PIX EXPIRADOS
        $schedule->command('payments:check-expired-pix')->everyFiveMinutes();

        // RECORRÊNCIA CAMPANHAS
        $schedule->command('campaigns:process-recurring')->hourly();
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
