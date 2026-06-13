<div>

    

    <?php
if (! isset($_instance)) {
    $html = \Livewire\Livewire::mount('compras.exibir-compra',[
        'localizador' => $order_json['localizador'],
    ])->html();
} elseif ($_instance->childHasBeenRendered('l3075687318-0')) {
    $componentId = $_instance->getRenderedChildComponentId('l3075687318-0');
    $componentTag = $_instance->getRenderedChildComponentTagName('l3075687318-0');
    $html = \Livewire\Livewire::dummyMount($componentId, $componentTag);
    $_instance->preserveRenderedChild('l3075687318-0');
} else {
    $response = \Livewire\Livewire::mount('compras.exibir-compra',[
        'localizador' => $order_json['localizador'],
    ]);
    $html = $response->html();
    $_instance->logRenderedChild('l3075687318-0', $response->id(), \Livewire\Livewire::getRootElementTagName($html));
}
echo $html;
?>

</div>
<?php /**PATH /home/igrnc24/public_html_sistemas/campanhas-eventos/resources/views/livewire/evento/evento-pagamento.blade.php ENDPATH**/ ?>