<?php
header('Access-Control-Allow-Origin: *');
include 'crud.php';

$type_function=$_POST['type_function'];
$test=[];
function crud_bd(){
	global $type_function, $test;
	if($type_function=='new'){
        $symbols = mb_str_split($_POST['symbol']);
        foreach ($symbols as $char){
            $data['name'] = $char;
            array_push($test, $char);
            crud_create('symbols', $data);
        }
	}
    if($type_function=='update'){
        $id = $_POST['id'];
        $data['name']=$_POST['symbol'];
        $data['sub_panel']=$_POST['sub_panel'];
        $selector = "`id` = '$id'";
        crud_update('symbols', $data, $selector);
        $symbol_fonts = json_decode($_POST['symbol_fonts']);
        $data = [];
        foreach($symbol_fonts as $font){
            $id_font =  $font -> id;
            $length = $font -> length;
            $data['length'] = $length;
            $selector = "`id_font` = '$id_font' AND `id_symbol` = '$id' ";
            crud_update('subol_trace_length', $data, $selector);
        }
    }
    if($type_function=='delete'){
        $id = $_POST['id'];
        $selector = "`id` = '$id'";
        crud_delete('symbols', $selector);
        $symbol_fonts = json_decode($_POST['symbol_fonts']);
        $data = [];
        foreach($symbol_fonts as $font){
            $id_font =  $font -> id;
            $selector = "`id_font` = '$id_font' AND `id_symbol` = '$id' ";
            crud_delete('subol_trace_length', $selector);
        }
    }
}

function mb_str_split($str){
    preg_match_all('#.{1}#uis', $str, $out);
    return $out[0];
}

crud_bd();

$data = array(
	'error'   => $error,
	'success' => $test,
);

header('Content-Type: application/json');
echo json_encode($data, JSON_UNESCAPED_UNICODE);
exit();
?>