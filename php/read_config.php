<?php
header('Access-Control-Allow-Origin: *');
include 'crud.php';

$config = crud_read('config');
echo json_encode($config);

?>