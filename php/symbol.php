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