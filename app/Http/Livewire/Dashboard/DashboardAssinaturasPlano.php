<?php

namespace App\Http\Livewire\Dashboard;

use App\Models\Customer;
use App\Models\CustomerPayGateway;
use App\Models\ModSubscription\Product;
use App\Models\ModSubscription\ProductPlan;
use App\Services\ModuleAccessService;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class DashboardAssinaturasPlano extends Component
{
    use WithFileUploads;

    public $productId;
    public $planId;
    public $product;
    public $plan;
    public $availableGateways;

    public $plan_name;
    public $plan_code;
    public $description;
    public $status = 'active';
    public $trial_days = 0;
    public $is_default = false;

    public $monthly_active = true;
    public $monthly_amount;
    public $monthly_amount_input;
    public $monthly_pay_gateway_id;
    public $monthly_pay_sandbox = false;
    public $monthly_pay_pix = false;
    public $monthly_pay_boleto = false;
    public $monthly_pay_card_credit = false;
    public $monthly_pay_card_credit_installment_max = 1;
    public $monthly_pay_card_credit_installment_fee_payer = 'customer';
    public $monthly_pay_card_credit_installment_amount_min;
    public $monthly_pay_card_credit_installment_amount_min_input;

    public $annual_active = false;
    public $annual_amount;
    public $annual_amount_input;
    public $annual_pay_gateway_id;
    public $annual_pay_sandbox = false;
    public $annual_pay_pix = false;
    public $annual_pay_boleto = false;
    public $annual_pay_card_credit = false;
    public $annual_pay_card_credit_installment_max = 1;
    public $annual_pay_card_credit_installment_fee_payer = 'customer';
    public $annual_pay_card_credit_installment_amount_min;
    public $annual_pay_card_credit_installment_amount_min_input;

    public $image_header;
    public $url_image_header;
    public $preview_header;

    public function mount(string $product_id, string $plan_id = null): void
    {
        $this->productId = $product_id;
        $this->planId = $plan_id;

        $this->loadProduct();
        $this->loadPlan();
    }

    public function render()
    {
        return view('livewire.dashboard.assinaturas-plano')
            ->layout('layouts.app-pep-auth');
    }

    protected function rules(): array
    {
        $planId = $this->planId ?: 'NULL';

        return [
            'plan_name' => ['required', 'string', 'max:255'],
            'plan_code' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('tbs_product_plan', 'plan_code')
                    ->ignore($planId, 'id')
                    ->where('product_id', $this->productId),
            ],
            'description' => ['nullable', 'string'],
            'status' => ['required', Rule::in(['active', 'paused', 'cancelled'])],
            'trial_days' => ['nullable', 'integer', 'min:0'],
            'is_default' => ['boolean'],
            'monthly_active' => ['boolean'],
            'monthly_amount' => ['nullable', 'integer', 'min:0'],
            'monthly_pay_gateway_id' => ['nullable', 'uuid', 'exists:tb_customers_pay_gateways,id'],
            'monthly_pay_sandbox' => ['boolean'],
            'monthly_pay_pix' => ['boolean'],
            'monthly_pay_boleto' => ['boolean'],
            'monthly_pay_card_credit' => ['boolean'],
            'monthly_pay_card_credit_installment_max' => ['nullable', 'integer', 'min:1'],
            'monthly_pay_card_credit_installment_fee_payer' => ['nullable', Rule::in(['customer', 'merchant'])],
            'monthly_pay_card_credit_installment_amount_min' => ['nullable', 'integer', 'min:0'],
            'annual_active' => ['boolean'],
            'annual_amount' => ['nullable', 'integer', 'min:0'],
            'annual_pay_gateway_id' => ['nullable', 'uuid', 'exists:tb_customers_pay_gateways,id'],
            'annual_pay_sandbox' => ['boolean'],
            'annual_pay_pix' => ['boolean'],
            'annual_pay_boleto' => ['boolean'],
            'annual_pay_card_credit' => ['boolean'],
            'annual_pay_card_credit_installment_max' => ['nullable', 'integer', 'min:1'],
            'annual_pay_card_credit_installment_fee_payer' => ['nullable', Rule::in(['customer', 'merchant'])],
            'annual_pay_card_credit_installment_amount_min' => ['nullable', 'integer', 'min:0'],
            'url_image_header' => ['nullable', 'string', 'max:500'],
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
                    abort(403, 'Voce nao tem permissao para acessar esta assinatura.');
                }
            }
        }

        if ($this->product->customer_id) {
            sessionCustomer($this->product->customer_id);
        }

        $this->availableGateways = CustomerPayGateway::where('customer_id', $this->product->customer_id)->get();
    }

    protected function loadPlan(): void
    {
        if ($this->planId) {
            $this->plan = ProductPlan::where('product_id', $this->product->id)
                ->where('id', $this->planId)
                ->firstOrFail();

            $this->plan_name = $this->plan->plan_name;
            $this->plan_code = $this->plan->plan_code;
            $this->description = $this->plan->description;
            $this->status = $this->plan->status ?? 'active';
            $this->trial_days = (int) ($this->plan->trial_days ?? 0);
            $this->is_default = (bool) $this->plan->is_default;

            $fallbackAmount = $this->plan->amount;
            $fallbackGateway = $this->plan->pay_gateway_id;
            $fallbackSandbox = $this->plan->pay_sandbox;
            $fallbackPix = $this->plan->pay_pix;
            $fallbackBoleto = $this->plan->pay_boleto;
            $fallbackCard = $this->plan->pay_card_credit;
            $fallbackInstallmentMax = $this->plan->pay_card_credit_installment_max;
            $fallbackFeePayer = $this->plan->pay_card_credit_installment_fee_payer;
            $fallbackInstallmentMin = $this->plan->pay_card_credit_installment_amount_min;

            $this->monthly_active = (bool) ($this->plan->monthly_active ?? ($fallbackAmount ? true : false));
            $this->monthly_amount = $this->plan->monthly_amount ?? $fallbackAmount;
            $this->monthly_amount_input = $this->formatCurrencyValue($this->monthly_amount);
            $this->monthly_pay_gateway_id = $this->plan->monthly_pay_gateway_id ?? $fallbackGateway;
            $this->monthly_pay_sandbox = (bool) ($this->plan->monthly_pay_sandbox ?? $fallbackSandbox);
            $this->monthly_pay_pix = (bool) ($this->plan->monthly_pay_pix ?? $fallbackPix);
            $this->monthly_pay_boleto = (bool) ($this->plan->monthly_pay_boleto ?? $fallbackBoleto);
            $this->monthly_pay_card_credit = (bool) ($this->plan->monthly_pay_card_credit ?? $fallbackCard);
            $this->monthly_pay_card_credit_installment_max = (int) ($this->plan->monthly_pay_card_credit_installment_max ?? $fallbackInstallmentMax ?? 1);
            $this->monthly_pay_card_credit_installment_fee_payer = $this->plan->monthly_pay_card_credit_installment_fee_payer ?? $fallbackFeePayer ?? 'customer';
            $this->monthly_pay_card_credit_installment_amount_min = $this->plan->monthly_pay_card_credit_installment_amount_min ?? $fallbackInstallmentMin;
            $this->monthly_pay_card_credit_installment_amount_min_input = $this->formatCurrencyValue($this->monthly_pay_card_credit_installment_amount_min);

            $this->annual_active = (bool) ($this->plan->annual_active ?? false);
            $this->annual_amount = $this->plan->annual_amount;
            $this->annual_amount_input = $this->formatCurrencyValue($this->annual_amount);
            $this->annual_pay_gateway_id = $this->plan->annual_pay_gateway_id;
            $this->annual_pay_sandbox = (bool) $this->plan->annual_pay_sandbox;
            $this->annual_pay_pix = (bool) $this->plan->annual_pay_pix;
            $this->annual_pay_boleto = (bool) $this->plan->annual_pay_boleto;
            $this->annual_pay_card_credit = (bool) $this->plan->annual_pay_card_credit;
            $this->annual_pay_card_credit_installment_max = (int) ($this->plan->annual_pay_card_credit_installment_max ?? 1);
            $this->annual_pay_card_credit_installment_fee_payer = $this->plan->annual_pay_card_credit_installment_fee_payer ?? 'customer';
            $this->annual_pay_card_credit_installment_amount_min = $this->plan->annual_pay_card_credit_installment_amount_min;
            $this->annual_pay_card_credit_installment_amount_min_input = $this->formatCurrencyValue($this->annual_pay_card_credit_installment_amount_min);

            $this->url_image_header = $this->plan->url_image_header;
            $this->preview_header = $this->plan->url_image_header;

            return;
        }

        $this->status = 'active';
        $this->trial_days = 0;
        $this->is_default = false;

        $this->monthly_active = true;
        $this->monthly_amount = null;
        $this->monthly_amount_input = null;
        $this->monthly_pay_gateway_id = $this->product->pay_gateway_id;
        $this->monthly_pay_sandbox = (bool) $this->product->pay_sandbox;
        $this->monthly_pay_pix = (bool) $this->product->pay_pix;
        $this->monthly_pay_boleto = (bool) $this->product->pay_boleto;
        $this->monthly_pay_card_credit = (bool) $this->product->pay_card_credit;
        $this->monthly_pay_card_credit_installment_max = (int) ($this->product->pay_card_credit_installment_max ?? 1);
        $this->monthly_pay_card_credit_installment_fee_payer = $this->product->pay_card_credit_installment_fee_payer ?? 'customer';
        $this->monthly_pay_card_credit_installment_amount_min = $this->product->pay_card_credit_installment_amount_min;
        $this->monthly_pay_card_credit_installment_amount_min_input = $this->formatCurrencyValue($this->product->pay_card_credit_installment_amount_min);

        $this->annual_active = false;
        $this->annual_amount = null;
        $this->annual_amount_input = null;
        $this->annual_pay_gateway_id = $this->product->pay_gateway_id;
        $this->annual_pay_sandbox = (bool) $this->product->pay_sandbox;
        $this->annual_pay_pix = (bool) $this->product->pay_pix;
        $this->annual_pay_boleto = (bool) $this->product->pay_boleto;
        $this->annual_pay_card_credit = (bool) $this->product->pay_card_credit;
        $this->annual_pay_card_credit_installment_max = (int) ($this->product->pay_card_credit_installment_max ?? 1);
        $this->annual_pay_card_credit_installment_fee_payer = $this->product->pay_card_credit_installment_fee_payer ?? 'customer';
        $this->annual_pay_card_credit_installment_amount_min = $this->product->pay_card_credit_installment_amount_min;
        $this->annual_pay_card_credit_installment_amount_min_input = $this->formatCurrencyValue($this->product->pay_card_credit_installment_amount_min);
    }

    public function updatedMonthlyAmountInput($value): void
    {
        $cents = convertDecimalInt($value);
        if ($cents !== null && $cents !== false) {
            $this->monthly_amount = $cents;
        }
    }

    public function updatedAnnualAmountInput($value): void
    {
        $cents = convertDecimalInt($value);
        if ($cents !== null && $cents !== false) {
            $this->annual_amount = $cents;
        }
    }

    public function updatedMonthlyPayCardCreditInstallmentAmountMinInput($value): void
    {
        $cents = convertDecimalInt($value);
        if ($cents !== null && $cents !== false) {
            $this->monthly_pay_card_credit_installment_amount_min = $cents;
        }
    }

    public function updatedAnnualPayCardCreditInstallmentAmountMinInput($value): void
    {
        $cents = convertDecimalInt($value);
        if ($cents !== null && $cents !== false) {
            $this->annual_pay_card_credit_installment_amount_min = $cents;
        }
    }

    public function updatedMonthlyPayGatewayId(): void
    {
        $this->syncGatewayInstallments('monthly');
    }

    public function updatedAnnualPayGatewayId(): void
    {
        $this->syncGatewayInstallments('annual');
    }

    public function updatedMonthlyPayCardCredit($value): void
    {
        $this->syncCardSettings('monthly', $value);
    }

    public function updatedAnnualPayCardCredit($value): void
    {
        $this->syncCardSettings('annual', $value);
    }

    public function updatedImageHeader(): void
    {
        if (! $this->image_header) {
            return;
        }

        $this->validate([
            'image_header' => ['image', 'max:2048'],
        ]);

        $planFolder = $this->planId ?? 'temp';
        $relativePath = "subscriptions/{$this->productId}/plans/{$planFolder}/header";
        $fullPath = tenantStoragePath($relativePath);

        if (! file_exists($fullPath)) {
            mkdir($fullPath, 0755, true);
        }

        $app = currentApp();
        $appId = $app->id ?? 1;
        $extension = $this->image_header->getClientOriginalExtension();
        $filename = time() . '_' . Str::random(10) . '.' . $extension;

        $this->image_header->storeAs("{$appId}/{$relativePath}", $filename, 'local');

        $this->url_image_header = "{$relativePath}/{$filename}";
        $this->preview_header = $this->url_image_header;
    }

    public function removerHeader(): void
    {
        $this->image_header = null;
        $this->url_image_header = null;
        $this->preview_header = null;
    }

    public function save()
    {
        $isEditing = (bool) $this->plan;

        if ($this->amount_input !== null && $this->amount === null) {
            $cents = convertDecimalInt($this->amount_input);
            if ($cents !== null && $cents !== false) {
                $this->amount = $cents;
            }
        }

        if ($this->pay_card_credit_installment_amount_min_input !== null && $this->pay_card_credit_installment_amount_min === null) {
            $cents = convertDecimalInt($this->pay_card_credit_installment_amount_min_input);
            if ($cents !== null && $cents !== false) {
                $this->pay_card_credit_installment_amount_min = $cents;
            }
        }

        $this->validate();

        $selectedGateway = null;
        if ($this->pay_gateway_id) {
            $selectedGateway = $this->availableGateways->firstWhere('id', $this->pay_gateway_id);
            if (! $selectedGateway) {
                $this->addError('pay_gateway_id', 'Gateway nao encontrado para esta empresa.');
                return;
            }
        }

        $hasPaymentMethod = $this->pay_pix || $this->pay_boleto || $this->pay_card_credit;
        if ($hasPaymentMethod && ! $this->pay_gateway_id) {
            $this->addError('pay_gateway_id', 'Selecione um gateway para liberar pagamentos.');
            return;
        }

        if ($selectedGateway) {
            if ($this->pay_pix && ! $selectedGateway->pay_pix) {
                $this->addError('pay_pix', 'Gateway nao suporta PIX.');
                return;
            }

            if ($this->pay_boleto && ! $selectedGateway->pay_boleto) {
                $this->addError('pay_boleto', 'Gateway nao suporta boleto.');
                return;
            }

            if ($this->pay_card_credit && ! $selectedGateway->pay_card_credit) {
                $this->addError('pay_card_credit', 'Gateway nao suporta cartao de credito.');
                return;
            }
        }

        if ($this->pay_card_credit) {
            $maxInstallments = (int) ($this->pay_card_credit_installment_max ?: 1);
            $gatewayMax = (int) ($selectedGateway->pay_card_credit_installment_max ?? $maxInstallments);

            if ($maxInstallments < 1) {
                $this->addError('pay_card_credit_installment_max', 'Minimo de 1 parcela.');
                return;
            }

            if ($gatewayMax > 0 && $maxInstallments > $gatewayMax) {
                $this->addError('pay_card_credit_installment_max', "Maximo de {$gatewayMax} parcelas.");
                return;
            }

            $minInstallment = (int) ($this->pay_card_credit_installment_amount_min ?? 0);
            $gatewayMin = (int) ($selectedGateway->pay_card_credit_installment_amount_min ?? 0);

            if ($gatewayMin > 0 && $minInstallment < $gatewayMin) {
                $this->addError('pay_card_credit_installment_amount_min', 'Valor minimo abaixo do permitido pelo gateway.');
                return;
            }

            $this->pay_card_credit_installment_max = $maxInstallments;
            $this->pay_card_credit_installment_amount_min = $minInstallment;
        }

        if ($this->pay_card_credit && ! $this->pay_card_credit_installment_fee_payer) {
            $this->pay_card_credit_installment_fee_payer = 'customer';
        }

        $data = [
            'product_id' => $this->product->id,
            'plan_name' => $this->plan_name,
            'plan_code' => $this->plan_code ?: null,
            'description' => $this->description,
            'status' => $this->status,
            'amount' => (int) $this->amount,
            'interval_unit' => $this->interval_unit,
            'interval_count' => (int) $this->interval_count,
            'trial_days' => (int) $this->trial_days,
            'is_default' => (bool) $this->is_default,
            'pay_gateway_id' => $this->pay_gateway_id ?: null,
            'pay_sandbox' => (bool) $this->pay_sandbox,
            'pay_pix' => (bool) $this->pay_pix,
            'pay_boleto' => (bool) $this->pay_boleto,
            'pay_card_credit' => (bool) $this->pay_card_credit,
            'pay_card_credit_installment_max' => $this->pay_card_credit ? (int) $this->pay_card_credit_installment_max : null,
            'pay_card_credit_installment_fee_payer' => $this->pay_card_credit ? $this->pay_card_credit_installment_fee_payer : null,
            'pay_card_credit_installment_amount_min' => $this->pay_card_credit ? (int) $this->pay_card_credit_installment_amount_min : null,
            'url_image_header' => $this->url_image_header,
        ];

        if ($this->plan) {
            $this->plan->update($data);
        } else {
            $this->plan = ProductPlan::create($data);
            $this->planId = $this->plan->id;
        }

        if ($this->is_default) {
            ProductPlan::where('product_id', $this->product->id)
                ->where('id', '!=', $this->plan->id)
                ->update(['is_default' => false]);
        }

        session(['subscription_details_tab' => 'planos']);
        session()->flash('message', $isEditing ? 'Plano atualizado com sucesso!' : 'Plano criado com sucesso!');

        return redirect()->route('dashboard-assinaturas-detalhes', ['product_id' => $this->product->id]);
    }

    protected function formatCurrencyValue($valueInCents): ?string
    {
        if ($valueInCents === null) {
            return null;
        }

        $value = (int) $valueInCents;
        $reais = floor($value / 100);
        $centavos = $value % 100;

        return number_format($reais, 0, '', '.') . ',' . str_pad((string) $centavos, 2, '0', STR_PAD_LEFT);
    }
}
