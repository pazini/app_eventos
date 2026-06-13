<?php

namespace App\Jobs\Middleware;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class RateLimitEmails
{
    /**
     * Process the queued job.
     *
     * @param  mixed  $job
     * @param  callable  $next
     * @return mixed
     */
    public function handle($job, $next)
    {
        $key = 'email_rate_limit:' . now()->format('Y-m-d-H-i');
        $limit = config('mail.rate_limit', 60); // 60 emails por minuto por padrão

        // Verifica se já atingiu o limite
        $current = Cache::get($key, 0);

        if ($current >= $limit) {
            // Aguarda até o próximo minuto
            $secondsToWait = 60 - now()->second;

            Log::warning('[RATE LIMIT] Limite de emails atingido, aguardando', [
                'current_count' => $current,
                'limit' => $limit,
                'wait_seconds' => $secondsToWait,
                'job_class' => get_class($job),
            ]);

            // Re-agenda o job para o próximo minuto
            $job->release($secondsToWait);
            return;
        }

        // Incrementa contador
        Cache::put($key, $current + 1, 70); // Cache por 70 segundos

        return $next($job);
    }
}
