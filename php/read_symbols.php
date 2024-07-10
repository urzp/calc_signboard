<?php
header('Access-Control-Allow-Origin: *');
include 'crud.php';

function getLengthSymbol($id_font, $id_symbol){
    $selector = "`id_font` = '$id_font' AND `id_symbol` = '$id_symbol'";
    $leng_symbol = crud_read('subol_trace_length', 'length', $selector);
    if(count($leng_symbol)==0){
        $data['id_font'] = $id_font;
        $data['id_symbol'] = $id_symbol;
        crud_create('subol_trace_length', $data);
        return '-';
    }
    return $leng_symbol[0]['length'];
}

$symbols = crud_read('symbols');
$fonts = crud_read('fonts');
$data = [];
foreach ($symbols as $symbol){
    $symbol_fonts = [];
    foreach ($fonts as $font){
        $id_font = $font['id'];
        $id_symbol = $symbol['id'];
        $font['length'] = getLengthSymbol($id_font, $id_symbol);
        array_push($symbol_fonts,$font);
    }
    $symbol['fonts'] = $symbol_fonts;
    array_push($data, $symbol);
}

echo json_encode($data);

?>