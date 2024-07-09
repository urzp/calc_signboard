<?php
header('Access-Control-Allow-Origin: *');
include 'crud.php';

$type_function=$_POST['type_function'];

if($type_function=='delete'){
	crud_bd();
	$data = array(
		'success' => 'delete'
	);
	header('Content-Type: application/json');
	echo json_encode($data, JSON_UNESCAPED_UNICODE);
	exit();
}

// Название <input type="file">
$input_name = 'file';
 
// Разрешенные расширения файлов.
$allow = array();
 
// Запрещенные расширения файлов.
$deny = array(
	'phtml', 'php', 'php3', 'php4', 'php5', 'php6', 'php7', 'phps', 'cgi', 'pl', 'asp', 
	'aspx', 'shtml', 'shtm', 'htaccess', 'htpasswd', 'ini', 'log', 'sh', 'js', 'html', 
	'htm', 'css', 'sql', 'spl', 'scgi', 'fcgi', 'exe'
);
 
// Директория куда будут загружаться файлы.
$path = $_SERVER['DOCUMENT_ROOT'].'/fonts/';
 
 
$error = $success = '';
if (!isset($_FILES[$input_name])) {
	$error = 'Файл не загружен.';
} else {
	$file = $_FILES[$input_name];
 
	// Проверим на ошибки загрузки.
	if (!empty($file['error']) || empty($file['tmp_name'])) {
		$error = 'Не удалось загрузить файл.';
	} elseif ($file['tmp_name'] == 'none' || !is_uploaded_file($file['tmp_name'])) {
		$error = 'Не удалось загрузить файл.';
	} else {
		// Оставляем в имени файла только буквы, цифры и некоторые символы.
		$pattern = "[^a-zа-яё0-9,~!@#%^-_\$\?\(\)\{\}\[\]\.]";
		$name = mb_eregi_replace($pattern, '-', $file['name']);
		$name = mb_ereg_replace('[-]+', '-', $name);
		$parts = pathinfo($name);
 
		if (empty($name) || empty($parts['extension'])) {
			$error = 'Недопустимый тип файла';
		} elseif (!empty($allow) && !in_array(strtolower($parts['extension']), $allow)) {
			$error = 'Недопустимый тип файла';
		} elseif (!empty($deny) && in_array(strtolower($parts['extension']), $deny)) {
			$error = 'Недопустимый тип файла';
		} else {
			// Перемещаем файл в директорию.
			if (move_uploaded_file($file['tmp_name'], $path . $name)) {
				// Далее можно сохранить название файла в БД и т.п.
				$success = '<p style="color: green">Файл «' . $name .' '.$type_function. '» успешно загружен.</p>';
				crud_bd();
			} else {
				$error = 'Не удалось загрузить файл.';
			}
		}
	}
}

function crud_bd(){
	global $type_function, $name;
	if($type_function=='new'){
		$data['name']= $name;
		crud_create('fonts', $data);
	}
	if($type_function=='update'){
		$data['name']= $name;
		$id = $_POST['id_font'];
		$selector = "`id` = '$id'";
		crud_update('fonts', $data, $selector);
	}
	if($type_function=='delete'){
		$id = $_POST['id_font'];
		$selector = "`id` = '$id'";
		crud_delete('fonts',$selector);
	}
}
 
// Вывод сообщения о результате загрузки.
if (!empty($error)) {
	$error = '<p style="color: red">' . $error . '</p>';  
}
 
$data = array(
	'error'   => $error,
	'success' => $success,
);
 
header('Content-Type: application/json');
echo json_encode($data, JSON_UNESCAPED_UNICODE);
exit();
?>