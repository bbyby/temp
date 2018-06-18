<?php


$ru_id = ( !empty($_POST['ru_id']) ) ? $_POST['ru_id'] : 0;
$en_id = ( !empty($_POST['en_id']) ) ? $_POST['en_id'] : 0;

//$ru_group = 8;  //новости
$ru_group = 9;  //Ascour в СМИ
//$ru_group = 10; //Legal Digest
//$ru_file = '/var/www/demo/data/www/ascour.dev.fixp.ru/ru/ru-news.txt';
//$ru_file = '/var/www/demo/data/www/ascour.dev.fixp.ru/ru/ru-press.txt';
$ru_file  = '/var/www/demo/data/www/ascour.dev.fixp.ru/ru/ru-press-2.txt';
$ru_file1 = '/var/www/demo/data/www/ascour.dev.fixp.ru/ru/ru-press-2-orig.txt';
//$ru_file = '/var/www/demo/data/www/ascour.dev.fixp.ru/ru/ru-digest.txt';

//$en_group = 11; //news
$en_group = 12; //Acsour in Mass Media
//$en_group = 13; //Legal Digest
//$en_file = '/var/www/demo/data/www/ascour.dev.fixp.ru/ru/en-news.txt';
//$en_file = '/var/www/demo/data/www/ascour.dev.fixp.ru/ru/en-press.txt';
$en_file  = '/var/www/demo/data/www/ascour.dev.fixp.ru/ru/en-press-2.txt';
$en_file1 = '/var/www/demo/data/www/ascour.dev.fixp.ru/ru/en-press-2-orig.txt';
//$en_file = '/var/www/demo/data/www/ascour.dev.fixp.ru/ru/en-digest.txt';

$old_db = new mysqli('localhost', 'DB_USER', 'DB_PASS', 'DB_NAME');
$new_db = new mysqli('localhost', 'DB_USER', 'DB_PASS', 'DB_NAME');
$old_db->set_charset('utf8');
$new_db->set_charset('utf8');

if ($old_db->connect_error) {
    die('Ошибка подключения (' . $old_db->connect_errno . ') '. $old_db->connect_error);
}
if ($new_db->connect_error) {
    die('Ошибка подключения (' . $new_db->connect_errno . ') '. $new_db->connect_error);
}

if (!empty($en_id)) {
    $res = $old_db->query("SELECT id, alias from acsour_k2_items WHERE id='". $en_id ."'");
    $row = $res->fetch_array(MYSQLI_ASSOC);
    $code = $row['alias'] . "-ru";
    $res->close();
} else {
    $code = '';
}

if (!empty($ru_id) AND $ru_id != 0) {
    $res = $old_db->query("SELECT * from acsour_k2_items WHERE id='". $ru_id ."'");
    $row = $res->fetch_array(MYSQLI_ASSOC);
    $full_text = ( !empty($row["fulltext"]) ) ? $row["fulltext"] : $row["introtext"];
    $search_cont = $row['title'] ."\n". $row["extra_fields_search"];
    if( empty($code) ){ $code = $row['alias'] . "-ru"; }

    $sql_ru = "INSERT INTO `b_iblock_element` (
            TIMESTAMP_X,
            MODIFIED_BY,
            DATE_CREATE,
            CREATED_BY,
            IBLOCK_ID,
            IBLOCK_SECTION_ID,
            ACTIVE,
            ACTIVE_FROM,
            ACTIVE_TO,
            SORT,
            NAME,
            PREVIEW_PICTURE,
            PREVIEW_TEXT,
            PREVIEW_TEXT_TYPE,
            DETAIL_PICTURE,
            DETAIL_TEXT,
            DETAIL_TEXT_TYPE,
            SEARCHABLE_CONTENT,
            WF_STATUS_ID,
            WF_PARENT_ELEMENT_ID,
            WF_NEW,
            WF_LOCKED_BY,
            WF_DATE_LOCK,
            WF_COMMENTS,
            IN_SECTIONS,
            XML_ID,
            CODE,
            TAGS,
            TMP_ID,
            WF_LAST_HISTORY_ID,
            SHOW_COUNTER,
            SHOW_COUNTER_START) 
        VALUES(
            '". $row['modified'] ."', 
            1,
            '". $row['created'] ."', 
            1,
            8,
            '". $ru_group ."',
            'Y',
            NULL,
            NULL,
            500,
            '". $new_db->real_escape_string($row['title']) ."', 
            NULL,
            '". $new_db->real_escape_string($row['introtext']) ."', 
            'html',
            NULL,
            '". $new_db->real_escape_string($full_text) ."',
            'html',
            '". $new_db->real_escape_string($search_cont) ."',
            1,
            NULL,
            NULL,
            NULL,
            NULL,
            NULL,
            'Y',
            NULL,
            '". $new_db->real_escape_string($code) ."',
            NULL,
            '". $ru_id ."',
            NULL,
            NULL,
            NULL
        )";
    $res->close();

    $new_db->query($sql_ru);
	if( $new_db->insert_id > 0 ){
		file_put_contents($ru_file, "http://ascour.dev.fixp.ru/press/detail.php?ID=". $new_db->insert_id . "&URL=". $code .""  . "\n", FILE_APPEND);
		file_put_contents($ru_file1, "http://acsour.com/ru/press/publications/item/". $ru_id . "-". $code ."\n", FILE_APPEND);
		$sql_ru = "INSERT INTO `b_iblock_section_element` (IBLOCK_SECTION_ID, IBLOCK_ELEMENT_ID, ADDITIONAL_PROPERTY_ID) 
					VALUES ('". $ru_group ."', '". $new_db->insert_id ."', NULL)";

		$new_db->query($sql_ru);	
	}else{
		file_put_contents($ru_file, "ERROR ID=". $ru_id ." MORE: " . $new_db->error . "\n", FILE_APPEND);
	}
    
} else {
    file_put_contents($ru_file, "NULL"  . "\n", FILE_APPEND);
}
//вставили в РУ

if (!empty($en_id) AND $en_id != 0) {
    $res2 = $old_db->query("SELECT * from acsour_k2_items WHERE id='". $en_id ."'");
    $row = $res2->fetch_array(MYSQLI_ASSOC);
    $full_text = ( !empty($row["fulltext"]) ) ? $row["fulltext"] : $row["introtext"];
    $search_cont = $row['title'] ."\n". $row["extra_fields_search"];
    $code = $row['alias'] . "-eng"; 

    $sql_en = "INSERT INTO `b_iblock_element` (
            TIMESTAMP_X,
            MODIFIED_BY,
            DATE_CREATE,
            CREATED_BY,
            IBLOCK_ID,
            IBLOCK_SECTION_ID,
            ACTIVE,
            ACTIVE_FROM,
            ACTIVE_TO,
            SORT,
            NAME,
            PREVIEW_PICTURE,
            PREVIEW_TEXT,
            PREVIEW_TEXT_TYPE,
            DETAIL_PICTURE,
            DETAIL_TEXT,
            DETAIL_TEXT_TYPE,
            SEARCHABLE_CONTENT,
            WF_STATUS_ID,
            WF_PARENT_ELEMENT_ID,
            WF_NEW,
            WF_LOCKED_BY,
            WF_DATE_LOCK,
            WF_COMMENTS,
            IN_SECTIONS,
            XML_ID,
            CODE,
            TAGS,
            TMP_ID,
            WF_LAST_HISTORY_ID,
            SHOW_COUNTER,
            SHOW_COUNTER_START) 
        VALUES(
            '". $row['modified'] ."', 
            1,
            '". $row['created'] ."', 
            1,
            8,
            '". $en_group ."',
            'Y',
            NULL,
            NULL,
            500,
            '". $new_db->real_escape_string($row['title']) ."', 
            NULL,
            '". $new_db->real_escape_string($row['introtext']) ."', 
            'html',
            NULL,
            '". $new_db->real_escape_string($full_text) ."',
            'html',
            '". $new_db->real_escape_string($search_cont) ."',
            1,
            NULL,
            NULL,
            NULL,
            NULL,
            NULL,
            'Y',
            NULL,
            '". $new_db->real_escape_string($code) ."',
            NULL,
            '". $en_id ."',
            NULL,
            NULL,
            NULL
        )";
    $res2->close();

    $new_db->query($sql_en);
	if( $new_db->insert_id >0 ){
		file_put_contents($en_file, "http://en.ascour.dev.fixp.ru/press/detail.php?ID=". $new_db->insert_id . "&URL=". $code .""  . "\n", FILE_APPEND);
		file_put_contents($en_file1, "http://acsour.com/en/en-press/en-publications/item/". $en_id . "-". $code ."\n", FILE_APPEND);
		$sql_en = "INSERT INTO `b_iblock_section_element` (IBLOCK_SECTION_ID, IBLOCK_ELEMENT_ID, ADDITIONAL_PROPERTY_ID) 
					VALUES ('". $en_group ."', '". $new_db->insert_id ."', NULL)";

		$new_db->query($sql_en);
	}else{
		file_put_contents($en_file, "ERROR ID=". $en_id ." MORE: " . $new_db->error . "\n", FILE_APPEND);
	}
} else {
    file_put_contents($en_file, "NULL"  . "\n", FILE_APPEND);
}
//вставили в EN


$res = array(
    'status' => 1
);

$old_db->close();
$new_db->close();
echo json_encode($res);
?>