<?php


$ru_id = ( !empty($_POST['ru_id']) ) ? $_POST['ru_id'] : 0;
$en_id = ( !empty($_POST['en_id']) ) ? $_POST['en_id'] : 0;

$twins_log = '/var/www/demo/data/www/ascour.dev.fixp.ru/ru/twins_log.txt';

//$old_db = new mysqli('localhost', 'DB_USER', 'DB_PASS', 'DB_NAME');
$new_db = new mysqli('localhost', 'DB_USER', 'DB_PASS', 'DB_NAME');
//$old_db->set_charset('utf8');
$new_db->set_charset('utf8');

if ($old_db->connect_error) {
    die('Ошибка подключения (' . $old_db->connect_errno . ') '. $old_db->connect_error);
}
if ($new_db->connect_error) {
    die('Ошибка подключения (' . $new_db->connect_errno . ') '. $new_db->connect_error);
}

//var_dump($_POST);
//file_put_contents($twins_log, "http://ascour.dev.fixp.ru/press/detail.php?ID=". $new_db->insert_id . "&URL=". $code .""  . "\n", FILE_APPEND);

//новость с парой
if ( (!empty($ru_id) AND $ru_id != 0) AND (!empty($en_id) AND $en_id != 0) ) {
	$res = $new_db->query("SELECT id, tmp_id from b_iblock_element WHERE tmp_id='". $ru_id ."'");
    $row = $res->fetch_array(MYSQLI_ASSOC);
    $ru_new_id = $row['id'];
	
	$res = $new_db->query("SELECT id, tmp_id from b_iblock_element WHERE tmp_id='". $en_id ."'");
    $row = $res->fetch_array(MYSQLI_ASSOC);
    $en_new_id = $row['id'];
	
	$sql = "INSERT INTO b_iblock_element_property (IBLOCK_PROPERTY_ID, IBLOCK_ELEMENT_ID, VALUE, VALUE_TYPE, VALUE_ENUM, VALUE_NUM, DESCRIPTION) 
								VALUES (15, ". $ru_new_id .", ". $en_new_id .", 'text', NULL, ". $en_new_id .", ''), 
									   (15, ". $en_new_id .", ". $ru_new_id .", 'text', NULL, ". $ru_new_id .", '' ) ";
	$ru_pair = $new_db->query( $sql );
	
	file_put_contents($twins_log, "ID: ". $new_db->insert_id . "\n", FILE_APPEND);
	
	$res = array(
		'status' => 1
	);

	$new_db->close();
	echo json_encode($res);
}
?>