<?php

namespace App\Http\Livewire\Faturamento;

use App\Models\Faturamento;
use App\Models\FaturamentoPagamento;
use App\Models\ModEvent\Event;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class GerarFatura extends Component
{
    public $faturamento_tipos;
    public $evento_id;

    //
    public $event;
    public $data_faturamento;

    //
    public $pay_data;
    public $nota_fiscal;
    public $pay_amont;
    public $descricao;
    public $qtd_parcelas;

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function mount($evento_id)
    {
        //
        $this->faturamento_tipos = [
            'free' => [
                'tipo_faturamento' => 'FREE',
                'descricao'        => 'ISENTO',
                'faixa_valor_ini'  => 0,
                'faixa_valor_fim'  => 0,
                'valor'            => 0,
                'qtd_parcelas'     => 0,
            ],
            'xp' => [
                'tipo_faturamento' => 'xp',
                'descricao'        => 'TAM XP // até 2,5 mil',
                'faixa_valor_ini'  =>       1, // R$     1,00
                'faixa_valor_fim'  =>  250000, // R$ 2.500,00
                'valor'            =>   20000, // R$   200,00 (1x 200,00)
                'qtd_parcelas'     => 1,
            ],
            'pp' => [
                'tipo_faturamento' => 'pp',
                'descricao'        => 'TAM PP // 2,5 a 5 mil',
                'faixa_valor_ini'  => 250001,  // R$ 2.501,00
                'faixa_valor_fim'  => 500000,  // R$ 5.000,00
                'valor'            =>  40000,  // R$   400,00 (1x 400,00)
                'qtd_parcelas'     => 1,
            ],
            'p' => [
                'tipo_faturamento' => 'p',
                'descricao'        => 'TAM P // 5 a 90 mil',
                'faixa_valor_ini'  =>  500001, // R$  5.001,00
                'faixa_valor_fim'  => 9000000, // R$ 90.000,00
                'valor'            =>   80000, // R$    800,00 (2x 400,00)
                'qtd_parcelas'     => 2,
            ],
            'm' => [
                'tipo_faturamento' => 'm',
                'descricao'        => 'TAM M // 90 e 600 mil',
                'faixa_valor_ini'  =>  9000001, // R$  90.001,00
                'faixa_valor_fim'  => 60000000, // R$ 600.000,00
                'valor'            =>   140000, // R$   1.400,00 (4x 350,00)
                'qtd_parcelas'     => 4,
            ],
            'g' => [
                'tipo_faturamento' => 'g',
                'descricao'        => 'TAM G // mais de 600 mil',
                'faixa_valor_ini'  =>  60000001, // R$   600.000,00
                'faixa_valor_fim'  => 999999999, // R$ 9.999.999,99
                'valor'            =>    200000, // R$     2.000,00 (4x 500,00)
                'qtd_parcelas'     => 4,
            ],
        ];

        $this->evento_id = $evento_id;
    }

    public function render()
    {
        if($this->event = Event::with(['faturamento','faturamento.pagamentos','organizer','ticketsTypes'])->find($this->evento_id))
        {
            //
            if($this->event->faturamento ?? false)
            {
                $this->data_faturamento = $this->event->faturamento->pay_date;
                $this->nota_fiscal      = $this->event->faturamento->nota_fiscal;
                $this->pay_amont        = $this->event->faturamento->pay_amont;
                $this->descricao        = $this->event->faturamento->descricao;
                $this->qtd_parcelas     = $this->event->faturamento->qtd_parcelas;
            }

            //
            if(!$this->data_faturamento)
            {
                $this->data_faturamento = \Carbon\Carbon::parse($this->event->event_datetime_start)->format('Y-m-d');
            }

            return view('livewire.faturamento.gerar-fatura');
        }

        //
        session()->flash('error','Não localizado');

        return redirect()->route('plataforma-faturamento');
    }

    public function removerFatura()
    {
        //
        foreach ($this->event->faturamento->pagamentos ?? [] as $pagamento)
        {
            if(in_array($pagamento->pay_status,['pago','realizado']))
            {
                return session()->flash('error','Existem pagamentos realizados. Fatura não pode ser removida');
            }
            else
            {
                $pagamento->delete();
            }
        }

        //
        if($this->event->faturamento ?? false)
        {
            $this->event->faturamento->delete();
        }

        $this->data_faturamento = '';

        session()->flash('success','Fatura removida');
    }

    public function pagarParcela($parcela_id)
    {
        // pay_status":"realizado
        // pay_data":"realizado

        $pagamento = $this->event->faturamento->pagamentos->find($parcela_id);
        $pagamento->pay_status  = 'realizado';
        $pagamento->pay_data    = $this->pay_data ?? now();
        $pagamento->save();

        $this->pay_data = '';

        //
        if ($this->event->faturamento->pagamentos->whereNull('pay_data')->count())
        {
            $this->event->faturamento->pay_status= 'pagamentos_gerados';
            $this->event->faturamento->save();
        }
        else
        {
            $this->event->faturamento->pay_status= 'quitado';
            $this->event->faturamento->save();
        }

        //
        session()->flash('success','Pagamento Realizado');
    }

    public function removeParcela($parcela_id)
    {
        $pagamento = FaturamentoPagamento::find($parcela_id);
        $pagamento->delete();
        session()->flash('success','Parcela Removida');
    }

    public function resetParcela($parcela_id)
    {
        // pay_status":"realizado
        // pay_data":"realizado

        $pagamento = $this->event->faturamento->pagamentos->find($parcela_id);
        $pagamento->pay_status = 'pendente';
        $pagamento->pay_data   = null;
        $pagamento->save();

        //
        $this->event->faturamento->pay_status= 'pagamentos_gerados';
        $this->event->faturamento->save();

        //
        session()->flash('success','Pagamento Pendente');
    }

    public function eventoCancelado($acao=false)
    {
        //
        if($faturamento = Faturamento::where('event_id',$this->event->id)->first())
        {
            $faturamento->update([
                'pay_status' => $acao ? $acao : 'evento_cancelado',
            ]);

            session()->flash('success','FATURA ATUALIZADA');
        }
        else
        {
            $faturamento = Faturamento::create([
                'event_id'   => $this->event->id,
                'pay_status' => $acao ? $acao : 'evento_cancelado',
            ]);

            session()->flash('success','FATURA GERADA');
        }
    }

    public function gerar($gerarParcelas=false)
    {
        $dataValidate = $this->validate([
            'data_faturamento' => ['required','date'],
        ]);

        $this->data_faturamento = Carbon::create($this->data_faturamento);

        $vendas_valor_ticket = 0;
        $vendas_qtd_max      = $this->event->sales_amount_max;
        $vendas_valor_total  = 0;

        foreach ($this->event->ticketsTypes as $ticketsType)
        {
            if($ticketsType->price > $vendas_valor_ticket)
                $vendas_valor_ticket = (int) $ticketsType->price;
        }

        //
        $vendas_valor_total = $vendas_valor_ticket * $this->event->sales_amount_max;

        $faturamento_tipo = false;

        foreach ($this->faturamento_tipos as $tipo_key => $tipo_values)
        {
            if($vendas_valor_total >= $tipo_values['faixa_valor_ini'] && $vendas_valor_total <= $tipo_values['faixa_valor_fim'])
            {
                $faturamento_tipo = $tipo_values;
            }
        }

        DB::beginTransaction();

        //
        if($faturamento = Faturamento::where('event_id',$this->event->id)->first())
        {
            $faturamento->update([
                'pay_status'          => 'pagamentos_gerados',
                'pay_amont'           => $this->pay_amont ?? ($faturamento_tipo['valor'] ?? 0),
                'pay_date'            => $this->data_faturamento->format('Y-m-d 23:59:59'),
                'vendas_valor_ticket' => $vendas_valor_ticket,
                'vendas_qtd_max'      => $vendas_qtd_max,
                'vendas_valor_total'  => $vendas_valor_total,
                'tipo_faturamento'    => $faturamento_tipo['tipo_faturamento'],
                'descricao'           => $this->descricao ?? $faturamento_tipo['descricao'],
                'valor'               => $this->pay_amont ?? ($faturamento_tipo['valor'] ?? 0),
                'qtd_parcelas'        => $this->qtd_parcelas ?? $faturamento_tipo['qtd_parcelas'],
                'nota_fiscal'         => $this->nota_fiscal ?? null,
            ]);

            session()->flash('success','FATURA ATUALIZADA');
        }
        else
        {
            $faturamento = Faturamento::create([
                'event_id'            => $this->event->id,
                'pay_status'          => 'pagamentos_gerados',
                'pay_amont'           => $this->pay_amont ?? ($faturamento_tipo['valor'] ?? 0),
                'pay_date'            => $this->data_faturamento->format('Y-m-d 23:59:59'),
                'vendas_valor_ticket' => $vendas_valor_ticket,
                'vendas_qtd_max'      => $vendas_qtd_max,
                'vendas_valor_total'  => $vendas_valor_total,
                'tipo_faturamento'    => $faturamento_tipo['tipo_faturamento'],
                'descricao'           => $this->descricao ?? $faturamento_tipo['descricao'],
                'valor'               => $this->pay_amont ?? ($faturamento_tipo['valor'] ?? 0),
                'qtd_parcelas'        => $this->qtd_parcelas ?? $faturamento_tipo['qtd_parcelas'],
                'nota_fiscal'         => $this->nota_fiscal ?? null,
            ]);

            session()->flash('success','FATURA GERADA');
        }

        $faturamentoPagamentoAdd = [];

        //
        if($gerarParcelas)
        {
            //
            if($faturamento->qtd_parcelas ?? false)
            {
                //
                foreach (range(1,$faturamento->qtd_parcelas ?? 1) as $range)
                {
                    //
                    if($range > 1)
                    {
                        // $this->data_faturamento = $this->data_faturamento->addMonths($range);
                        $this->data_faturamento = $this->data_faturamento->addMonths(1);
                    }

                    //
                    if(($faturamento->valor ?? false) && ($faturamento->qtd_parcelas ?? false))
                        $pay_valor = round($faturamento->valor / $faturamento->qtd_parcelas);
                    else
                        $pay_valor = 0;

                    //
                    if($pagamento = FaturamentoPagamento::where('faturamento_id', $faturamento->id)->where('pay_ref', $range)->first())
                    {
                        // NAO ATUALIZA PAGAMENTOS REALIZADOS
                        if(!in_array($pagamento->pay_status,['pago','realizado']))
                        {
                            $pagamento->update([
                                'faturamento_id'      => $faturamento->id,
                                'pay_ref'             => $range,
                                'pay_descricao'       => 'Parcela ' . $range . ' de ' . $faturamento->qtd_parcelas,
                                'pay_data_vencimento' => $this->data_faturamento->format('Y-m-d 23:59:59'),
                                'pay_valor'           => (int) $pay_valor,
                            ]);
                        }
                    }
                    else
                    {
                        $pagamento = FaturamentoPagamento::create([
                            'faturamento_id'      => $faturamento->id,
                            'pay_ref'             => $range,
                            'pay_status'          => 'pendente',
                            'pay_descricao'       => 'Parcela ' . $range . ' de ' . $faturamento->qtd_parcelas,
                            'pay_data_vencimento' => $this->data_faturamento->format('Y-m-d 23:59:59'),
                            'pay_valor'           => (int) $pay_valor,
                        ]);
                    }

                    $faturamentoPagamentoAdd[$range] = $pagamento;
                }
            }
        }

        //
        if ($this->event->faturamento->pagamentos ?? false)
        {
            $pagamentos = $this->event->faturamento->pagamentos;

            if($pagamentos->count() && $pagamentos->whereNull('pay_data')->count() == 0)
            {
                $this->event->faturamento->pay_status= 'quitado';
                $this->event->faturamento->save();
            }
        }

        //
        DB::commit();

        return redirect()->back();
    }
}
