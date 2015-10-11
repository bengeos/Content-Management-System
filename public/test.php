<?php
require_once ('../private/My_Database.php');
$My_DB = new My_Database();
$res = $My_DB->is_valid_user('bengeos','ben');
print_r($res)
?>