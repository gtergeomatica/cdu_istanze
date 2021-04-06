<?php
session_start();
//questo file è richiamato quando l'utente clicca su bottone per rimuovere mappale da quelli selezionati in from_istanza_cdu
include("root_connection.php");

$user_id=pg_escape_string($_GET['idu']);
$user=pg_escape_string($_GET['user']);
$foglio=pg_escape_string($_GET['f']);
$mappale=pg_escape_string($_GET['m']);


if(!$conn_isernia) {
    die('Connessione fallita !<br />');
} else {

	//query per rimuovere terreno dal DB
	$query_remove = "DELETE from istanze.istanze_temp where id_utente=$1 and foglio=$2 and mappale=$3";
	$result_rem = pg_prepare($conn_isernia, "myquery5", $query_remove);
	$result_rem = pg_execute($conn_isernia, "myquery5", array($user_id, $foglio, $mappale));
	//redirect a form_istanza_cdu.php
	header("Location: form_istanza_cdu.php?u=".$user_id."&user=".$user."");
}

?>