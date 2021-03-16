<?php
session_start();
$file = $_GET['f'];
$file_path = '/var/www/html/isernia_upload/mappali_cdu/'.$file;
$filename = $file;
if(!file_exists($file_path)){ // check se file esiste
    die('file not found');
} else {
    header("Content-type: text/plain"); 
    header("Content-Disposition: attachment; filename=$filename");
    readfile("$file_path");
}
?>