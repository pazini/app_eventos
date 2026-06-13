<?php

namespace App\Console\Commands;

use App\Models\AppModule;
use Illuminate\Console\Command;

class ListModules extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'modules:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Lista todos os módulos do sistema';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $modules = AppModule::all();

        $this->info('Total de módulos: ' . $modules->count());

        foreach ($modules as $module) {
            $status = $module->module_active ? 'Ativo' : 'Inativo';
            $this->line($module->slug . ' - ' . $module->module_name . ' - Status: ' . $status);
        }

        return Command::SUCCESS;
    }
}
