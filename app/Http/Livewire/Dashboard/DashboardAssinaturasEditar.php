<?php

namespace App\Http\Livewire\Dashboard;

use App\Http\Middleware\EnsureSuperAdmin;
use App\Models\Customer;
use App\Models\ModSubscription\Product;
use App\Models\ModSubscription\ProductPlan;
use App\Models\ModSubscription\Subscription;
use App\Models\ModSubscription\SubscriptionCycle;
use App\Services\ModuleAccessService;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class DashboardAssinaturasEditar extends Component
{
    use WithFileUploads;

    public $productId;
    public $product;

    public $name;
    public $name_short;
    public $slug;
    public $status = 'draft';
    public $visibility_public = false;
    public $description;
    public $about;
    public $datetime_start;
    public $datetime_finish;

    public $image_banner;
    public $url_image_banner;
    public $preview_banner;

    public $showDeleteModal = false;
    public $deleteConfirmation = '';
    public $deleteSummary = [];

    public function mount(string $product_id)
    {
        $this->productId = $product_id;
        $this->loadProduct();
    }

    public function render()
    {
        return view('livewire.dashboard.assinaturas-editar')
            ->layout('layouts.app-pep-auth');
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'name_short' => ['nullable', 'string', 'max:255'],
            'status' => ['required', Rule::in(['draft', 'active', 'paused', 'cancelled'])],
            'visibility_public' => ['boolean'],
            'datetime_start' => ['nullable', 'date'],
            'datetime_finish' => ['nullable', 'date', 'after_or_equal:datetime_start'],
            'description' => ['nullable', 'string'],
            'about' => ['nullable', 'string'],
            'url_image_banner' => ['nullable', 'string', 'max:500'],
        ];
    }

    protected function loadProduct(): void
    {
        $this->product = Product::with(['customer', 'organizer'])->findOrFail($this->productId);

        if (auth()->check()) {
            $user = auth()->user();

            if (! ModuleAccessService::userIsAppAdmin($user)) {
                $customer = $this->product->customer ?: Customer::find($this->product->customer_id);
                if ($customer && ! ModuleAccessService::userCanAccessSubscriptions($user, $customer)) {
                    abort(403, 'Voce nao tem permissao para editar esta assinatura.');
                }
            }
        }

        if ($this->product->customer_id) {
            sessionCustomer($this->product->customer_id);
        }

        $this->name = $this->product->name;
        $this->name_short = $this->product->name_short;
        $this->slug = $this->product->slug;
        $this->status = $this->product->status ?? 'draft';
        $this->visibility_public = (bool) $this->product->visibility_public;
        $this->description = $this->product->description;
        $this->about = $this->product->about;
        $this->datetime_start = $this->product->datetime_start ? $this->product->datetime_start->format('Y-m-d') : null;
        $this->datetime_finish = $this->product->datetime_finish ? $this->product->datetime_finish->format('Y-m-d') : null;
        $this->url_image_banner = $this->product->url_image_banner;
        $this->preview_banner = $this->product->url_image_banner;
    }

    public function updatedImageBanner(): void
    {
        if (! $this->image_banner) {
            return;
        }

        $this->validate([
            'image_banner' => ['image', 'max:2048'],
        ]);

        $productId = $this->productId ?? 'temp';
        $relativePath = "subscriptions/{$productId}/banner";
        $fullPath = tenantStoragePath($relativePath);

        if (! file_exists($fullPath)) {
            mkdir($fullPath, 0755, true);
        }

        $app = currentApp();
        $appId = $app->id ?? 1;
        $extension = $this->image_banner->getClientOriginalExtension();
        $filename = time() . '_' . Str::random(10) . '.' . $extension;

        $this->image_banner->storeAs("{$appId}/{$relativePath}", $filename, 'local');

        $this->url_image_banner = "{$relativePath}/{$filename}";
        $this->preview_banner = $this->url_image_banner;
    }

    public function removerBanner(): void
    {
        $this->image_banner = null;
        $this->url_image_banner = null;
        $this->preview_banner = null;
    }

    public function save()
    {
        $this->validate();

        $this->product->update([
            'name' => $this->name,
            'name_short' => $this->name_short,
            'description' => $this->description,
            'about' => $this->about,
            'status' => $this->status,
            'visibility_public' => (bool) $this->visibility_public,
            'datetime_start' => $this->datetime_start ? Carbon::parse($this->datetime_start)->startOfDay() : null,
            'datetime_finish' => $this->datetime_finish ? Carbon::parse($this->datetime_finish)->endOfDay() : null,
            'url_image_banner' => $this->url_image_banner,
        ]);

        session()->flash('message', 'Assinatura atualizada com sucesso!');

        return redirect()->route('dashboard-assinaturas-detalhes', ['product_id' => $this->product->id]);
    }

    public function openDeleteModal(): void
    {
        $this->resetErrorBag(['deleteConfirmation', 'deleteConfirmationStatus']);
        $this->deleteConfirmation = '';
        $this->loadDeleteSummary();
        $this->showDeleteModal = true;
    }

    public function closeDeleteModal(): void
    {
        $this->showDeleteModal = false;
        $this->deleteConfirmation = '';
        $this->deleteSummary = [];
        $this->resetErrorBag(['deleteConfirmation', 'deleteConfirmationStatus']);
    }

    public function apagarAssinatura(): void
    {
        if (trim($this->deleteConfirmation) !== 'apagar-assinatura') {
            $this->addError('deleteConfirmationStatus', 'E preciso digitar "apagar-assinatura" para apagar.');
            return;
        }

        $this->loadDeleteSummary();

        $plans = $this->deleteSummary['plans'] ?? 0;
        $subscriptions = $this->deleteSummary['subscriptions'] ?? 0;
        $cycles = $this->deleteSummary['cycles'] ?? 0;

        if ($plans > 0 || $subscriptions > 0 || $cycles > 0) {
            $this->addError('deleteConfirmationStatus', 'Impossivel apagar. Existem dados vinculados a esta assinatura.');
            return;
        }

        $this->product->delete();

        session()->flash('message', 'Assinatura apagada com sucesso!');
        redirect()->route('dashboard-assinaturas')->send();
    }

    public function apagarCiclos(): void
    {
        if (! $this->ensureSuperAdmin()) {
            return;
        }

        $this->loadDeleteSummary();
        $cycles = $this->deleteSummary['cycles'] ?? 0;

        if ($cycles === 0) {
            $this->addError('deleteConfirmationStatus', 'Sem ciclos para apagar.');
            return;
        }

        $subscriptionIds = Subscription::where('product_id', $this->product->id)->pluck('id');
        if ($subscriptionIds->isNotEmpty()) {
            SubscriptionCycle::whereIn('subscription_id', $subscriptionIds)->delete();
        }

        $this->loadDeleteSummary();
    }

    public function apagarAssinaturas(): void
    {
        if (! $this->ensureSuperAdmin()) {
            return;
        }

        $this->loadDeleteSummary();
        $cycles = $this->deleteSummary['cycles'] ?? 0;
        $subscriptions = $this->deleteSummary['subscriptions'] ?? 0;

        if ($subscriptions === 0) {
            $this->addError('deleteConfirmationStatus', 'Sem assinaturas para apagar.');
            return;
        }

        if ($cycles > 0) {
            $this->addError('deleteConfirmationStatus', 'Apague os ciclos antes de apagar as assinaturas.');
            return;
        }

        Subscription::where('product_id', $this->product->id)->delete();
        $this->loadDeleteSummary();
    }

    public function apagarPlanos(): void
    {
        if (! $this->ensureSuperAdmin()) {
            return;
        }

        $this->loadDeleteSummary();
        $subscriptions = $this->deleteSummary['subscriptions'] ?? 0;
        $plans = $this->deleteSummary['plans'] ?? 0;

        if ($plans === 0) {
            $this->addError('deleteConfirmationStatus', 'Sem planos para apagar.');
            return;
        }

        if ($subscriptions > 0) {
            $this->addError('deleteConfirmationStatus', 'Apague as assinaturas antes de apagar os planos.');
            return;
        }

        ProductPlan::where('product_id', $this->product->id)->delete();
        $this->loadDeleteSummary();
    }

    public function apagarProdutoFinal(): void
    {
        if (! $this->ensureSuperAdmin()) {
            return;
        }

        $this->loadDeleteSummary();
        $plans = $this->deleteSummary['plans'] ?? 0;
        $subscriptions = $this->deleteSummary['subscriptions'] ?? 0;
        $cycles = $this->deleteSummary['cycles'] ?? 0;

        if ($plans > 0 || $subscriptions > 0 || $cycles > 0) {
            $this->addError('deleteConfirmationStatus', 'Ainda existem dados vinculados. Finalize as etapas anteriores.');
            return;
        }

        $this->product->delete();
        session()->flash('message', 'Assinatura apagada com sucesso!');
        redirect()->route('dashboard-assinaturas')->send();
    }

    protected function loadDeleteSummary(): void
    {
        $this->deleteSummary = [
            'plans' => 0,
            'subscriptions' => 0,
            'cycles' => 0,
        ];

        if (! $this->product) {
            return;
        }

        $subscriptionIds = Subscription::where('product_id', $this->product->id)->pluck('id');
        $this->deleteSummary['subscriptions'] = $subscriptionIds->count();
        $this->deleteSummary['plans'] = ProductPlan::where('product_id', $this->product->id)->count();

        if ($subscriptionIds->isNotEmpty()) {
            $this->deleteSummary['cycles'] = SubscriptionCycle::whereIn('subscription_id', $subscriptionIds)->count();
        }
    }

    protected function ensureSuperAdmin(): bool
    {
        if (! EnsureSuperAdmin::check()) {
            $this->addError('deleteConfirmationStatus', 'Apenas super-admin pode apagar dados por etapas.');
            return false;
        }

        return true;
    }
}
