<?php
session_start();
include("root_connection.php");

$id_istanza=$_GET['idi'];


if(!$conn_isernia) {
    die('Connessione fallita !<br />');
} else {

	$query_remove = "DELETE from istanze.dettagli_istanze where id_istanza=$1";
	$result_rem = pg_prepare($conn_isernia, "myquery0", $query_remove);
	$result_rem = pg_execute($conn_isernia, "myquery0", array($id_istanza));
	
	$query_remove1 = "DELETE from istanze.istanze where id=$1";
	$result_rem1 = pg_prepare($conn_isernia, "myquery1", $query_remove1);
	$result_rem1 = pg_execute($conn_isernia, "myquery1", array($id_istanza));

	header("Location: dashboard.php#about");
}

?>