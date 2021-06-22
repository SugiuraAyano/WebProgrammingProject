<?php
$SystemName = '網頁程式設計與安全實務';
$dbhost = "sugiuraayano.synology.me:13307";
$dbname = "project";
$dbuser = "ui3a19";
$dbpwd = "qaq890204";
$uDate = date("Y-m-d H:i:s");
$ErrMsg = "";
$UserIP = '';
if (isset($_SERVER['HTTP_VIA']) && isset($_SERVER['HTTP_X_FORWARDED_FOR'])) 
    $UserIP = $_SERVER['HTTP_X_FORWARDED_FOR'];
else if (isset($_SERVER['REMOTE_ADDR'])) $UserIP = $_SERVER['REMOTE_ADDR'];
?>
