<?php

namespace App\Observers;

use App\Models\Customer;
use App\Models\CustomerOrganization;
use App\Models\CustomerOrganizationSub;
use App\Models\CustomerOrganizer;
use App\Models\ModCampaign\CampaignOrganizer;
use App\Models\ModSubscription\ProductOrganizer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class CustomerObserver
{
    /**
        * Cria estrutura padrão (filial, subdivisão e organizador) logo após criar um cliente.
        */
    public function created(Customer $customer): void
    {
        $this->ensureDefaultOrganizers($customer);
        $this->ensureDefaultModules($customer);
    }

    /**
     * Garante a existência do organizador padrão ao atualizar.
     */
    public function updated(Customer $customer): void
    {
        $this->ensureDefaultOrganizers($customer);
    }

    /**
     * Cria ou garante a estrutura padrão (filial, subdivisão e organizadores de eventos/campanhas).
     */
    protected function ensureDefaultOrganizers(Customer $customer): void
    {
        try {
            DB::transaction(function () use ($customer) {
                $orgName = 'Matriz';
                $orgSlugBase = $customer->customer_slug ?: Str::slug($customer->name_fantasy ?? $customer->name_corporate ?? 'cliente');
                $orgSlug = Str::slug($orgSlugBase . '-matriz');

                $organization = CustomerOrganization::firstOrCreate(
                    [
                        'customer_id' => $customer->id,
                        'organization_slug' => $orgSlug,
                    ],
                    [
                        'organization_name' => $orgName,
                        'organization_description' => $orgName,
                    ]
                );

                $subName = 'Principal';
                $subSlug = Str::slug($orgSlug . '-' . $subName);

                $organizationSub = CustomerOrganizationSub::firstOrCreate(
                    [
                        'customer_id' => $customer->id,
                        'organization_id' => $organization->id,
                        'organization_sub_slug' => $subSlug,
                    ],
                    [
                        'organization_sub_name' => $subName,
                        'organization_sub_description' => $subName,
                    ]
                );

                $customerDisplayName = $customer->name_fantasy ?? $customer->name_corporate ?? 'Organizador';

                // Eventos: organizador padrão da filial/subdivisão
                $eventOrganizerNameFull = $customerDisplayName . ' | ' . $organization->organization_name . ' | ' . $organizationSub->organization_sub_name;
                $eventOrganizerSlug = Str::slug($eventOrganizerNameFull);
                CustomerOrganizer::updateOrCreate(
                    [
                        'customer_id' => $customer->id,
                        'organization_id' => $organization->id,
                        'organization_sub_id' => $organizationSub->id,
                    ],
                    [
                        'organizer_slug' => $eventOrganizerSlug,
                        'organizer_name' => $organizationSub->organization_sub_name,
                        'organizer_name_full' => $eventOrganizerNameFull,
                        'owner_name' => $customer->comercial_contact_name ?: ($customer->name_corporate ?? $customer->name_fantasy ?? null),
                        'owner_email' => $customer->comercial_contact_email,
                        'owner_phone_country' => $customer->comercial_contact_country,
                        'owner_phone_ddd' => $customer->comercial_contact_ddd,
                        'owner_phone_num' => $customer->comercial_contact_num,
                    ]
                );

                // Eventos: organizador da própria empresa (sem filial/subdivisão)
                $companyOrganizerSlug = Str::slug($customerDisplayName);
                CustomerOrganizer::updateOrCreate(
                    [
                        'customer_id' => $customer->id,
                        'organization_id' => null,
                        'organization_sub_id' => null,
                    ],
                    [
                        'organizer_slug' => $companyOrganizerSlug,
                        'organizer_name' => $customerDisplayName,
                        'organizer_name_full' => $customerDisplayName,
                        'owner_name' => $customer->comercial_contact_name ?: ($customer->name_corporate ?? $customer->name_fantasy ?? null),
                        'owner_email' => $customer->comercial_contact_email,
                        'owner_phone_country' => $customer->comercial_contact_country,
                        'owner_phone_ddd' => $customer->comercial_contact_ddd,
                        'owner_phone_num' => $customer->comercial_contact_num,
                    ]
                );

                // Campanhas: mesmo padrão, só nome fantasia/razão
                $campaignOrganizerSlug = Str::slug($customerDisplayName);
                CampaignOrganizer::updateOrCreate(
                    [
                        'customer_id' => $customer->id,
                        'organization_id' => $organization->id,
                    ],
                    [
                        'organizer_slug' => $campaignOrganizerSlug,
                        'organizer_name' => $customerDisplayName,
                        'organizer_name_full' => $customerDisplayName,
                        'owner_name' => $customer->comercial_contact_name ?: ($customer->name_corporate ?? $customer->name_fantasy ?? null),
                        'owner_email' => $customer->comercial_contact_email,
                        'owner_phone_country' => $customer->comercial_contact_country,
                        'owner_phone_ddd' => $customer->comercial_contact_ddd,
                        'owner_phone_num' => $customer->comercial_contact_num,
                    ]
                );

                // Assinaturas: organizador da empresa (sem filial/subdivisão)
                if (Schema::hasTable('tbs_product_organizer')) {
                    $subscriptionOrganizerSlug = Str::slug($customerDisplayName);
                    ProductOrganizer::updateOrCreate(
                        [
                            'customer_id' => $customer->id,
                        ],
                        [
                            'organizer_slug' => $subscriptionOrganizerSlug,
                            'organizer_name' => $customerDisplayName,
                            'organizer_name_full' => $customerDisplayName,
                            'owner_name' => $customer->comercial_contact_name ?: ($customer->name_corporate ?? $customer->name_fantasy ?? null),
                            'owner_email' => $customer->comercial_contact_email,
                            'owner_phone_country' => $customer->comercial_contact_country,
                            'owner_phone_ddd' => $customer->comercial_contact_ddd,
                            'owner_phone_num' => $customer->comercial_contact_num,
                        ]
                    );
                }
            });
        } catch (\Throwable $e) {
            Log::warning('Falha ao criar estrutura padrão para o cliente', [
                'customer_id' => $customer->id ?? null,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Garante que o cliente tenha os módulos de eventos e campanhas habilitados.
     */
    protected function ensureDefaultModules(Customer $customer): void
    {
        try {
            DB::transaction(function () use ($customer) {
                // Buscar módulos disponíveis no sistema
                $eventosModule = DB::table('tb_app_modules')
                    ->where('slug', 'eventos')
                    ->where('module_active', 1)
                    ->first();

                $campanhasModule = DB::table('tb_app_modules')
                    ->where('slug', 'campanhas')
                    ->where('module_active', 1)
                    ->first();

                // Habilitar módulo de eventos
                if ($eventosModule) {
                    $exists = DB::table('tb_customers_app_modules')
                        ->where('customer_id', $customer->id)
                        ->where('module_id', $eventosModule->id)
                        ->exists();

                    if (!$exists) {
                        DB::table('tb_customers_app_modules')->insert([
                            'id' => (string) Str::uuid(),
                            'customer_id' => $customer->id,
                            'module_id' => $eventosModule->id,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                    }
                }

                // Habilitar módulo de campanhas
                if ($campanhasModule) {
                    $exists = DB::table('tb_customers_app_modules')
                        ->where('customer_id', $customer->id)
                        ->where('module_id', $campanhasModule->id)
                        ->exists();

                    if (!$exists) {
                        DB::table('tb_customers_app_modules')->insert([
                            'id' => (string) Str::uuid(),
                            'customer_id' => $customer->id,
                            'module_id' => $campanhasModule->id,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                    }
                }
            });
        } catch (\Throwable $e) {
            Log::warning('Falha ao habilitar módulos para o cliente', [
                'customer_id' => $customer->id ?? null,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
