
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.4/moment.min.js"></script>
<script src="https://cdn.datatables.net/plug-ins/1.10.10/sorting/datetime-moment.js"></script>

<style>
/* ── DataTables Reset ─────────────────────────────────────────── */
#fat-table_wrapper { padding: 0; font-family: inherit; }

/* toolbar: botões + pesquisa */
#fat-table_wrapper > .dataTables_wrapper > div:first-child,
div.dataTables_wrapper div.dataTables_length,
div.dataTables_wrapper div.dataTables_info  { display: none; }

#fat-table_wrapper .dt-buttons {
    display: flex; gap: 6px; padding: 10px 12px 0 12px;
}
.dt-button {
    font-size: 0.7rem !important;
    padding: 4px 10px !important;
    border-radius: 4px !important;
    border: 1px solid #d1d5db !important;
    background: #fff !important;
    color: #374151 !important;
    font-weight: 600 !important;
    letter-spacing: .03em !important;
    text-transform: uppercase !important;
    cursor: pointer;
    transition: background .15s;
}
.dt-button:hover { background: #f3f4f6 !important; border-color: #9ca3af !important; }

div.dataTables_wrapper div.dataTables_filter {
    padding: 8px 12px 0 12px;
    text-align: left;
    float: none;
}
div.dataTables_wrapper div.dataTables_filter label {
    font-size: 0.72rem;
    color: #6b7280;
    font-weight: 500;
    letter-spacing: .03em;
}
div.dataTables_wrapper div.dataTables_filter input {
    font-size: 0.75rem !important;
    border: 1px solid #d1d5db !important;
    border-radius: 6px !important;
    padding: 4px 8px !important;
    outline: none !important;
    margin-left: 6px;
    width: 220px;
}
div.dataTables_wrapper div.dataTables_filter input:focus {
    border-color: #14b8a6 !important;
    box-shadow: 0 0 0 2px rgba(20,184,166,.15) !important;
}

/* cabeçalho da tabela */
table.dataTable thead th,
table.dataTable thead td {
    padding: 8px 10px !important;
    font-size: 0.65rem !important;
    font-weight: 700 !important;
    text-transform: uppercase !important;
    letter-spacing: .05em !important;
    color: #374151 !important;
    background: #f9fafb !important;
    border-bottom: 2px solid #e5e7eb !important;
    white-space: nowrap;
}
table.dataTable thead .sorting:after,
table.dataTable thead .sorting_asc:after,
table.dataTable thead .sorting_desc:after { opacity: .4; }

/* células */
table.dataTable tbody td {
    padding: 5px 10px !important;
    font-size: 0.72rem !important;
    vertical-align: top;
    border-bottom: 1px solid #f3f4f6 !important;
    color: #1f2937;
}
table.dataTable tbody tr:hover td { background: #f9fafb !important; }
table.dataTable { border-collapse: collapse !important; width: 100% !important; }
table.dataTable.no-footer { border-bottom: 1px solid #e5e7eb !important; }

/* info e toolbar inferior */
div.dataTables_wrapper div.dataTables_info {
    padding: 8px 12px;
    font-size: 0.72rem;
    color: #9ca3af;
}
/* wrapper externo — remove o padding lateral do wrapper default */
div.dataTables_wrapper { padding: 0; }
</style>

<div class="w-full">

    
    <div class="bg-teal-600 px-5 py-3 flex items-center justify-between gap-4 flex-wrap">
        <div class="flex items-center gap-2.5">
            <svg class="w-4 h-4 text-white/80 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
            </svg>
            <div>
                <span class="text-white font-semibold text-sm uppercase tracking-wide">Faturamento</span>
                <span class="text-white/60 text-xs ml-2 hidden sm:inline">— Gestão de faturas por evento</span>
            </div>
        </div>
        <div class="flex items-center gap-4 flex-wrap">
            <div class="flex items-center gap-1.5">
                <label class="text-white/75 text-xs whitespace-nowrap">Ano Ref.</label>
                <select wire:model="busca_ano" class="text-xs bg-white/20 border border-white/30 text-white rounded px-2 py-1 focus:outline-none focus:bg-white/30 [&>option]:text-gray-800 [&>option]:bg-white">
                    <option value="">TODOS</option>
                    <?php $__currentLoopData = $busca_ano_lista; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ano_ref): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($ano_ref); ?>"><?php echo e($ano_ref); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="flex items-center gap-1.5">
                <label class="text-white/75 text-xs whitespace-nowrap">Organizador</label>
                <select wire:model="organizador_id" class="text-xs bg-white/20 border border-white/30 text-white rounded px-2 py-1 focus:outline-none focus:bg-white/30 [&>option]:text-gray-800 [&>option]:bg-white uppercase">
                    <option value="">TODOS</option>
                    <?php $__currentLoopData = $organizadores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $organizador_id => $organizador): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($organizador_id); ?>" class="uppercase"><?php echo e($organizador); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </div>
    </div>

    <?php if($events ?? false): ?>

        <div class="bg-white border-x border-b border-gray-200 rounded-b-sm shadow-sm">

            
            <div id="fat-table_wrapper">
                <table id="fat-table" class="w-full">
                    <thead>
                        <tr>
                            <th>Nome / Organizador</th>
                            <th>Data / Previsão</th>
                            <th>Valor Vendas</th>
                            <th>Tipo / Valor</th>
                            <th>Faturado / NF</th>
                            <th>Pagamentos</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $events->sortBy('event_datetime_start') ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event_item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if(in_array($event_item->event_slug,['pink','bloco-experimenta'])): ?>
                            <?php continue; ?>
                            <?php endif; ?>
                            <?php if(in_array(mb_strtoupper($event_item->organizer->organizer_name_full ?? '---'),['FOCUS','MINHA EMPRESA','SAMBA ALELUIA'])): ?>
                            <?php continue; ?>
                            <?php endif; ?>
                            <?php
                                if (in_array($event_item->faturamento->pay_status ?? false,['realizado'])) {
                                    $bg = 'bg-green-50';
                                } elseif (in_array($event_item->faturamento->pay_status ?? false,['em andamento'])) {
                                    $bg = 'bg-blue-50';
                                } else {
                                    $bg = '';
                                }
                            ?>
                            <tr class="<?php echo e($bg); ?>">
                                <td>
                                    <div class="font-semibold uppercase text-gray-800 text-xs leading-tight">
                                        <?php if(isAdmin()): ?>
                                            <a href="<?php echo e(route('plataforma-faturamento-gerar-fatura',['evento_id' => $event_item->id])); ?>" class="text-teal-700 hover:underline"><?php echo e($event_item->event_name ?? '---'); ?></a>
                                        <?php else: ?>
                                            <?php echo e($event_item->event_name ?? '---'); ?>

                                        <?php endif; ?>
                                    </div>
                                    <div class="text-gray-400 uppercase text-2xs leading-tight mt-0.5"><?php echo e(mb_strtoupper($event_item->organizer->organizer_name_full ?? '---')); ?></div>
                                </td>

                                <td>
                                    <span class="sr-only"><?php echo e(\Carbon\Carbon::parse($event_item->event_datetime_start)->format('ymdhi')); ?>:::</span>
                                    <div class="font-medium text-gray-800 text-xs leading-tight"><?php echo e($event_item->event_datetime_start ? \Carbon\Carbon::parse($event_item->event_datetime_start)->format('d/m/Y') : '---'); ?></div>
                                    <div class="text-gray-400 text-2xs leading-tight"><?php echo e($event_item->event_datetime_start ? \Carbon\Carbon::parse($event_item->event_datetime_start)->ago() : '---'); ?></div>
                                    <span class="sr-only">:::</span>
                                    <div class="text-gray-500 text-2xs leading-tight mt-0.5"><?php echo e(toMoney($event_item->faturamento->vendas_valor_total ?? 0, 'R$ ')); ?></div>
                                </td>

                                <td>
                                    <?php
if (! isset($_instance)) {
    $html = \Livewire\Livewire::mount('faturamento.calcula-vendidos', ['eventId' => $event_item->id])->html();
} elseif ($_instance->childHasBeenRendered('l4249166550-0')) {
    $componentId = $_instance->getRenderedChildComponentId('l4249166550-0');
    $componentTag = $_instance->getRenderedChildComponentTagName('l4249166550-0');
    $html = \Livewire\Livewire::dummyMount($componentId, $componentTag);
    $_instance->preserveRenderedChild('l4249166550-0');
} else {
    $response = \Livewire\Livewire::mount('faturamento.calcula-vendidos', ['eventId' => $event_item->id]);
    $html = $response->html();
    $_instance->logRenderedChild('l4249166550-0', $response->id(), \Livewire\Livewire::getRootElementTagName($html));
}
echo $html;
?>
                                    <?php
if (! isset($_instance)) {
    $html = \Livewire\Livewire::mount('faturamento.calcula-vendidos-qtd', ['eventId' => $event_item->id])->html();
} elseif ($_instance->childHasBeenRendered('l4249166550-1')) {
    $componentId = $_instance->getRenderedChildComponentId('l4249166550-1');
    $componentTag = $_instance->getRenderedChildComponentTagName('l4249166550-1');
    $html = \Livewire\Livewire::dummyMount($componentId, $componentTag);
    $_instance->preserveRenderedChild('l4249166550-1');
} else {
    $response = \Livewire\Livewire::mount('faturamento.calcula-vendidos-qtd', ['eventId' => $event_item->id]);
    $html = $response->html();
    $_instance->logRenderedChild('l4249166550-1', $response->id(), \Livewire\Livewire::getRootElementTagName($html));
}
echo $html;
?>
                                </td>

                                <td>
                                    <div class="font-medium text-gray-800 uppercase text-xs leading-tight"><?php echo e($event_item->faturamento->descricao ?? '---'); ?></div>
                                    <?php if($event_item->faturamento->pay_status ?? false): ?>
                                        <span class="sr-only">:::</span>
                                        <div class="text-gray-500 text-2xs leading-tight mt-0.5"><?php echo e(toMoney($event_item->faturamento->valor ?? 0, 'R$ ')); ?></div>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <div class="text-gray-800 text-xs leading-tight">
                                        <?php if($event_item->faturamento->pay_date ?? false): ?>
                                            <?php echo e(convertToDate($event_item->faturamento->pay_date)); ?>

                                        <?php else: ?>
                                            <span class="text-gray-300">—</span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="mt-1">
                                        <?php if($event_item->faturamento->nota_fiscal ?? false): ?>
                                            <span class="inline-flex items-center rounded bg-blue-100 px-1.5 py-0.5 text-2xs font-medium text-blue-700">NF <?php echo e($event_item->faturamento->nota_fiscal); ?></span>
                                        <?php else: ?>
                                            <span class="text-gray-300 text-2xs">—</span>
                                        <?php endif; ?>
                                    </div>
                                </td>

                                <td>
                                    <div class="flex flex-wrap gap-1">
                                        <?php if($event_item->faturamento->pagamentos ?? false): ?>
                                            <?php $__empty_1 = true; $__currentLoopData = $event_item->faturamento->pagamentos ? $event_item->faturamento->pagamentos->sortBy('pay_data_vencimento') : []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pagamento_item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                <?php
                                                    if (in_array($pagamento_item->pay_status,['realizado'])) {
                                                        $badgeClass = 'bg-green-100 text-green-800 border border-green-200';
                                                        $status_label = 'PG';
                                                        $status = 'PAGO';
                                                    } elseif(convertToTimestamp($pagamento_item->pay_data_vencimento) < convertToTimestamp()) {
                                                        $badgeClass = 'bg-red-100 text-red-800 border border-red-200';
                                                        $status_label = 'VENC';
                                                        $status = 'ATRASADO';
                                                    } else {
                                                        $badgeClass = 'bg-yellow-50 text-yellow-800 border border-yellow-200';
                                                        $status_label = '';
                                                        $status = 'PENDENTE';
                                                    }
                                                ?>
                                                <div class="<?php echo e($badgeClass); ?> rounded px-1.5 py-1 text-center leading-tight min-w-[52px]">
                                                    <div class="text-2xs font-normal"><?php echo e($pagamento_item->pay_descricao ?? '---'); ?></div>
                                                    <div class="text-2xs font-semibold"><?php echo e(toMoney($pagamento_item->pay_valor ?? 0, 'R$ ')); ?></div>
                                                    <div class="text-2xs">
                                                        <?php if($pagamento_item->pay_status == 'realizado'): ?>
                                                            <?php echo e($status_label); ?> <?php echo e(convertToDate($pagamento_item->pay_data ? $pagamento_item->pay_data : $pagamento_item->pay_data_vencimento)); ?>

                                                        <?php else: ?>
                                                            <?php echo e($status_label); ?> <?php echo e(convertToDate($pagamento_item->pay_data_vencimento)); ?>

                                                        <?php endif; ?>
                                                        <span class="sr-only">::: <?php echo e($status); ?></span>
                                                    </div>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                <?php if(($event_item->faturamento->pay_status ?? false) && in_array($event_item->faturamento->pay_status,['evento_cancelado','nao_faturar','evento_isento'])): ?>
                                                    <span class="inline-flex items-center rounded bg-red-100 px-1.5 py-0.5 text-2xs font-semibold text-red-700 uppercase"><?php echo e(__(mb_strtoupper($event_item->faturamento->pay_status))); ?></span>
                                                <?php else: ?>
                                                    <span class="text-gray-300 text-2xs">—</span>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <?php if(($event_item->faturamento->pay_status ?? false) && in_array($event_item->faturamento->pay_status,['evento_cancelado','nao_faturar','evento_isento'])): ?>
                                                <span class="inline-flex items-center rounded bg-red-100 px-1.5 py-0.5 text-2xs font-semibold text-red-700 uppercase"><?php echo e(__(mb_strtoupper($event_item->faturamento->pay_status))); ?></span>
                                            <?php else: ?>
                                                <span class="text-gray-300 text-2xs">—</span>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>

    <?php else: ?>
        <div class="bg-white border-x border-b border-gray-200 px-6 py-10 text-center text-gray-400 text-sm">
            Nenhum evento encontrado para os filtros selecionados.
        </div>
    <?php endif; ?>

</div>

<script>
    document.title = 'FATURAMENTO-PLATAFORMA-<?php echo e(now()->format("Ymd-Hi")); ?>';

    $(document).ready(function () {
        $.fn.dataTable.moment('DD/MM/YYYY');

        var table = $('#fat-table').DataTable({
            autoWidth: false,
            dom: '<"flex items-center justify-between px-3 py-2 border-b border-gray-100"<"flex gap-2"B><"flex items-center gap-2"f>>rtip',
            buttons: [
                {
                    extend: 'excelHtml5',
                    text: 'Excel',
                    className: ''
                },
                {
                    extend: 'pdfHtml5',
                    text: 'PDF',
                    className: ''
                }
            ],
            language: { url: "https://cdn.datatables.net/plug-ins/1.11.3/i18n/pt_br.json" },
            lengthMenu: [[-1]],
            pageLength: -1,
            order: [],
            scrollX: true,
        });
    });
</script>
<?php /**PATH /home/igrnc24/public_html_sistemas/campanhas-eventos/resources/views/faturamento/faturamento.blade.php ENDPATH**/ ?>