<?php
session_start();
//questo file è richiamato quando l'utente clicca su bottone per rimuovere istanze
include("root_connection.php");

$id_istanza=$_GET['idi'];


if(!$conn_isernia) {
    die('Connessione fallita !<br />');
} else {
	//query per rimuovere sia l'istanza che i relativi dettagli dal DB
	$query_remove = "DELETE from istanze.dettagli_istanze where id_istanza=$1";
	$result_rem = pg_prepare($conn_isernia, "myquery0", $query_remove);
	$result_rem = pg_execute($conn_isernia, "myquery0", array($id_istanza));
	
	$query_remove1 = "DELETE from istanze.istanze where id=$1";
	$result_rem1 = pg_prepare($conn_isernia, "myquery1", $query_remove1);
	$result_rem1 = pg_execute($conn_isernia, "myquery1", array($id_istanza));
	//redirect alla dashboard
	header("Location: dashboard.php#about");
}

?>