<?php
session_start();
include("root_connection.php");

$user_id=$_GET['idu'];
$user=$_GET['user'];
$foglio=$_GET['f'];
$mappale=$_GET['m'];


if(!$conn_isernia) {
    die('Connessione fallita !<br />');
} else {

	
	$query_remove = "DELETE from istanze.istanze_temp where id_utente=$1 and foglio=$2 and mappale=$3";
	$result_rem = pg_prepare($conn_isernia, "myquery5", $query_remove);
	$result_rem = pg_execute($conn_isernia, "myquery5", array($user_id, $foglio, $mappale));

	header("Location: form_istanza_cdu.php?u=".$user_id."&user=".$user."");
}

?>