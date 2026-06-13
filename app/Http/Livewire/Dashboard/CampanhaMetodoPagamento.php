<?php

namespace App\Http\Livewire\Dashboard;

use App\Models\CustomerPayGateway;
use App\Models\ModCampaign\Campaign;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CampanhaMetodoPagamento extends Component
{
    public $campaign;
    public $campaign_id;
    public $metodoAlterar;

    public $customerPaymentGateways;
    public $gateway;

    public $pay_gateway_id;
    public $pay_sandbox;
    public $pay_boleto;
    public $pay_pix;
    public $pay_pix_direto;
    public $pay_card_credit;
    public $pay_card_credit_installment_max = 1;
    public $pay_card_credit_installment_amount_min; // Valor em centavos (int)
    public $pay_card_credit_installment_amount_min_input; // Valor formatado para exibição
    public $pay_card_credit_installment_fee_payer;

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function mount($campaign_id)
    {
        $this->campaign_id = $campaign_id;

        // Busca a campanha
        $this->campaign = Campaign::with(['customer', 'customer.paymentGateways', 'gateway'])
            ->where('id', $campaign_id)
            ->firstOrFail();

        // Verifica permissão (mesmo customer da sessão)
        $sessionCustomer = sessionCustomer();
        if (!$sessionCustomer || $this->campaign->customer_id !== $sessionCustomer->id) {
            abort(403, 'Acesso negado');
        }

        // Busca gateways disponíveis do customer (apenas os habilitados para campanhas)
        $this->customerPaymentGateways = CustomerPayGateway::where('customer_id', $this->campaign->customer_id)
            ->where('pay_active', 1)
            ->where('use_campaigns', 1)
            ->orderBy('pay_gateway_label')
            ->orderBy('pay_gateway_description')
            ->get();

        // Carrega gateway atual
        $this->gateway = $this->campaign->gateway;

        // Carrega dados atuais
        $this->pay_gateway_id = $this->campaign->pay_gateway_id;
        $this->pay_sandbox = $this->campaign->pay_sandbox;
        $this->pay_boleto = $this->campaign->pay_boleto;
        $this->pay_pix = $this->campaign->pay_pix;
        $this->pay_pix_direto = $this->campaign->pay_pix_direto ?? false;
        $this->pay_card_credit = $this->campaign->pay_card_credit;
        $this->pay_card_credit_installment_max = $this->campaign->pay_card_credit_installment_max ?? 1;
        // Valor em centavos (int) - padrão 500 centavos = R$ 5,00
        $this->pay_card_credit_installment_amount_min = $this->campaign->pay_card_credit_installment_amount_min ?? 500;
        // Valor formatado para exibição (string formatada como moeda)
        $this->pay_card_credit_installment_amount_min_input = $this->formatCurrencyValue($this->pay_card_credit_installment_amount_min);
        $this->pay_card_credit_installment_fee_payer = $this->campaign->pay_card_credit_installment_fee_payer ?? 'campaign';

        // Se já tem gateway configurado
        if ($this->pay_gateway_id) {
            $this->metodoAlterar = true;
            $this->updatedPayGatewayId();
        }
    }

    public function updatedPayGatewayId()
    {
        $this->gateway = false;

        if ($this->pay_gateway_id ?? false) {
            $this->gateway = CustomerPayGateway::find($this->pay_gateway_id);
        }
    }

    protected function formatCurrencyValue($valueInCents)
    {
        // Converte centavos para formato de moeda (1.234.567,89)
        $value = (int)$valueInCents;
        $reais = floor($value / 100);
        $centavos = $value % 100;

        $formatted = number_format($reais, 0, '', '.') . ',' . str_pad($centavos, 2, '0', STR_PAD_LEFT);
        return $formatted;
    }

    public function updatedPayCardCreditInstallmentAmountMinInput($value)
    {
        // Quando o valor formatado é atualizado, converte para centavos (int)
        // Exemplo: "1.234,56" -> 123456 (centavos)
        $cents = convertDecimalInt($value);
        if ($cents !== null && $cents !== false) {
            $this->pay_card_credit_installment_amount_min = $cents;
        }
    }

    public function metodoPagamentoSubmit()
    {
        $rules = [
            "pay_gateway_id" => ['required', 'string'],
            "pay_sandbox" => ['nullable', 'boolean'],
            "pay_boleto" => ['nullable', 'boolean'],
            "pay_pix" => ['nullable', 'boolean'],
            "pay_pix_direto" => ['nullable', 'boolean'],
            "pay_card_credit" => ['nullable', 'boolean'],
            "pay_card_credit_installment_max" => ['required_if:pay_card_credit,true', 'integer'],
            "pay_card_credit_installment_amount_min" => ['required_if:pay_card_credit,true'],
            "pay_card_credit_installment_fee_payer" => ['nullable', 'in:customer,campaign'],
        ];

        $validateData = $this->validate($rules);

        // Testa provedor
        if (!$gatewaySelecionado = $this->customerPaymentGateways->find($this->pay_gateway_id)) {
            return session()->flash('error', 'Selecione um Provedor de Pagamentos válido');
        }

        // Se cartão
        if ($this->pay_card_credit ?? false) {
            // Crédito - converte o valor formatado para centavos se necessário
            if (!empty($this->pay_card_credit_installment_amount_min_input)) {
                $cents = convertDecimalInt($this->pay_card_credit_installment_amount_min_input);
                if ($cents !== null && $cents !== false) {
                    $this->pay_card_credit_installment_amount_min = $cents;
                }
            }
            // Garante que seja inteiro
            $validateData['pay_card_credit_installment_amount_min'] = (int)$this->pay_card_credit_installment_amount_min;

            // Crédito - qtd mínima parcelas
            if ($validateData['pay_card_credit_installment_max'] < 1) {
                return $this->addError('pay_card_credit_installment_max', "Cartão de crédito // Mínimo 1 parcela");
            }

            // Crédito - qtd máximo parcelas
            if ($validateData['pay_card_credit_installment_max'] > $gatewaySelecionado->pay_card_credit_installment_max) {
                return $this->addError('pay_card_credit_installment_max', "Cartão de crédito // Máximo " . $gatewaySelecionado->pay_card_credit_installment_max . ' parcelas');
            }

            // Crédito - valor mínimo
            if ($validateData['pay_card_credit_installment_amount_min'] < $gatewaySelecionado->pay_card_credit_installment_amount_min) {
                return $this->addError('pay_card_credit_installment_amount_min', "Cartão de crédito // Valor mínimo " . toMoney($gatewaySelecionado->pay_card_credit_installment_amount_min, 'R$ '));
            }
        } else {
            // Se não for cartão de crédito
            unset($validateData['pay_card_credit_installment_max']);
            unset($validateData['pay_card_credit_installment_amount_min']);
            unset($validateData['pay_card_credit_installment_fee_payer']);
        }

        try {
            // Transaction
            DB::beginTransaction();

            $this->campaign->update($validateData);

            // Transaction fim
            DB::commit();

            // Sucesso
            if ($this->metodoAlterar ?? false) {
                session()->flash('success', 'Método de Pagamento Alterado');
            } else {
                session()->flash('success', 'Método de Pagamento Cadastrado');
            }

            return redirect()->route('dashboard-campanhas-detalhes', ['campaign_id' => $this->campaign_id]);
        } catch (\Throwable $th) {
            // Transaction error
            DB::rollBack();

            return session()->flash('error', $th->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.dashboard.campanha-metodo-pagamento')
            ->layout('layouts.app-pep-auth');
    }
}
