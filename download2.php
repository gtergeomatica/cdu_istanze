<?php
session_start();
$file_path = '/var/www/html/isernia_upload/moduli/Autocertificazione_diritti_istruttori.pdf';
$filename = 'Autocertificazione_diritti_istruttori.pdf';
if(!file_exists($file_path)){ // check se file esiste
    die('file not found');
} else {
    header("Content-type: application/pdf"); 
    header("Content-Disposition: attachment; filename=$filename");
    readfile("$file_path");
}
?>