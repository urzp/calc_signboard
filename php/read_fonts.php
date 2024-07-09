<?php
header('Access-Control-Allow-Origin: *');
include 'crud.php';

$fonts = crud_read('fonts');
echo json_encode($fonts);

?>