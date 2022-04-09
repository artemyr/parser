<?php
include_once('core/functions.php');
$app = new Aplication;
$app->get_head();
$file = $app->read_file($_SERVER["DOCUMENT_ROOT"]."/output/stage2stoly.txt");
$separator = ";";

echo '<pre><plaintext>';

// echo 
//     'key'.$separator.
//     'name'.$separator.
//     'price'.$separator.
//     'code'.$separator.
//     'color'.$separator.
//     'main_image'.$separator.
//     'more_photo'.$separator.
//     'shirina'.$separator.
//     'dlina'.$separator.
//     'tip_rascladci'.$separator.
//     'vstavka'.$separator.
//     'matirials'.$separator.
//     'osobennost'.$separator.
//     'diametr_podstavki'.$separator.
//     'ves'.$separator.
//     'obuom_v_upacovke'.$separator.
//     'garantia'.
//     "\n";

foreach ($file as $key => $value) {
    // echo 
    // $key.$separator.
    // $value->name.$separator.
    // $value->price.$separator.
    // $value->code.$separator.
    // $value->color.$separator.
    // $value->main_image.$separator.
    // $value->more_photo.$separator.
    // $value->shirina.$separator.
    // $value->dlina.$separator.
    // $value->tip_rascladci.$separator.
    // $value->vstavka.$separator.
    // $value->matirials.$separator.
    // $value->osobennost.$separator.
    // $value->diametr_podstavki.$separator.
    // $value->ves.$separator.
    // $value->obuom_v_upacovke.$separator.
    // $value->garantia.
    // "\n";
    foreach ($value->more_photo as $key => $photo) {
        echo $photo.',';
    }
    echo "\n";
}

echo '</plaintext></pre>';