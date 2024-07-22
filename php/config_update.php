<?php
header('Access-Control-Allow-Origin: *');
include 'crud.php';

$configs = json_decode($_POST['configs']);

foreach($configs as $item){
    $data = [];
    $id = $item -> id;
    $selector = "`id` = '$id'";
    $data['name'] = $item -> name;
    $data['value']= $item -> value;
    crud_update('config', $data, $selector);
}
    
$data = array(
	'error'   => $error,
	'success' => $test,
);

header('Content-Type: application/json');
echo json_encode($data, JSON_UNESCAPED_UNICODE);
exit();
?>