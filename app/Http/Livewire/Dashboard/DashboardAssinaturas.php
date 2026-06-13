<?php

namespace App\Http\Livewire\Dashboard;

use App\Models\Customer;
use App\Models\ModSubscription\Product;
use App\Models\ModSubscription\ProductOrganizer;
use App\Services\ModuleAccessService;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;

class DashboardAssinaturas extends Component
{
    public $customers;
    public $customer;
    public $customer_id;

    public $showNewProductModal = false;
    public $newProductName = '';
    public $newProductSlug = '';
    public $newProductStatus = 'draft';
    public $newProductDescription = '';

    public function render()
    {
        $products = collect();
        $stats = [
            'products' => 0,
            'plans' => 0,
            'subscriptions' => 0,
            'mrr' => 0,
        ];

        if ($this->customer_id && Schema::hasTable('tbs_product')) {
            $products = Product::where('customer_id', $this->customer_id)
                ->withCount([
                    'plans',
                    'subscriptions as subscriptions_active_count' => function ($query) {
                        $query->where('status', 'active');
                    },
                ])
                ->orderBy('created_at', 'desc')
                ->get();

            $stats['products'] = $products->count();
            $stats['plans'] = (int) $products->sum('plans_count');
            $stats['subscriptions'] = (int) $products->sum('subscriptions_active_count');

            if (Schema::hasTable('tbs_subscription') && $products->isNotEmpty()) {
                $stats['mrr'] = (int) \App\Models\ModSubscription\Subscription::whereIn('product_id', $products->pluck('id'))
                    ->where('status', 'active')
                    ->sum('amount_total');
            }
        }

        return view('livewire.dashboard.assinaturas', [
            'products' => $products,
            'stats' => $stats,
        ])
            ->layout('layouts.app-pep-auth');
    }

    public function mount()
    {
        $this->customers = sessionCustomers(true);
        $this->customer = sessionCustomer();
        $this->customer_id = $this->customer->id ?? false;

        if (! $this->customer_id && $this->customers && $this->customers->count() == 1) {
            $singleCustomer = $this->customers->first();
            $this->customer_id = $singleCustomer->id;
            sessionCustomer($this->customer_id);
        }

        if (auth()->check()) {
            $user = auth()->user();

            if (ModuleAccessService::userIsAppAdmin($user)) {
                return;
            }

            if ($this->customer_id) {
                $customer = Customer::find($this->customer_id);

                if ($customer && ! ModuleAccessService::userCanAccessSubscriptions($user, $customer)) {
                    abort(403, 'Você não tem permissão para acessar o módulo de assinaturas para este cliente.');
                }
            }
        }
    }

    public function updatedCustomerId()
    {
        sessionClear('customer');
        sessionCustomer($this->customer_id);
    }

    public function updatedNewProductName($value)
    {
        $this->newProductSlug = Str::slug($value);
    }

    public function openNewProductModal()
    {
        if (! $this->customer_id) {
            session()->flash('error', 'Selecione primeiro uma empresa para cadastrar o produto.');
            return;
        }

        $this->resetProductForm();
        $this->showNewProductModal = true;
    }

    public function closeNewProductModal()
    {
        $this->showNewProductModal = false;
        $this->resetProductForm();
    }

    public function createProduct()
    {
        if (! $this->customer_id) {
            session()->flash('error', 'Selecione primeiro uma empresa para cadastrar o produto.');
            return;
        }

        $this->newProductSlug = Str::slug($this->newProductName);

        $this->validate([
            'newProductName' => ['required', 'string', 'max:255'],
            'newProductSlug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('tbs_product', 'slug')->where(function ($query) {
                    return $query->where('customer_id', $this->customer_id);
                }),
            ],
            'newProductStatus' => ['required', Rule::in(['draft', 'active', 'paused', 'cancelled'])],
            'newProductDescription' => ['nullable', 'string'],
        ]);

        $customer = Customer::find($this->customer_id);
        $organizerId = $customer ? $this->resolveProductOrganizerId($customer) : null;

        Product::create([
            'customer_id' => $this->customer_id,
            'organizer_id' => $organizerId,
            'slug' => $this->newProductSlug,
            'name' => $this->newProductName,
            'description' => $this->newProductDescription,
            'status' => $this->newProductStatus,
            'visibility_public' => false,
        ]);

        session()->flash('message', 'Produto criado com sucesso!');
        $this->closeNewProductModal();
    }

    private function resetProductForm(): void
    {
        $this->newProductName = '';
        $this->newProductSlug = '';
        $this->newProductStatus = 'draft';
        $this->newProductDescription = '';
    }

    private function resolveProductOrganizerId(Customer $customer): ?string
    {
        if (! Schema::hasTable('tbs_product_organizer')) {
            return null;
        }

        $organizer = ProductOrganizer::where('customer_id', $customer->id)->first();
        if ($organizer) {
            return $organizer->id;
        }

        $displayName = $customer->name_fantasy ?? $customer->name_corporate ?? 'Organizador';

        $organizer = ProductOrganizer::create([
            'customer_id' => $customer->id,
            'organizer_slug' => Str::slug($displayName),
            'organizer_name' => $displayName,
            'organizer_name_full' => $displayName,
            'owner_name' => $customer->comercial_contact_name ?: ($customer->name_corporate ?? $customer->name_fantasy ?? null),
            'owner_email' => $customer->comercial_contact_email,
            'owner_phone_country' => $customer->comercial_contact_country,
            'owner_phone_ddd' => $customer->comercial_contact_ddd,
            'owner_phone_num' => $customer->comercial_contact_num,
        ]);

        return $organizer->id;
    }
}
