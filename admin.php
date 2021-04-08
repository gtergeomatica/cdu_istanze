<?php
session_start();
/* echo $_SESSION['user'] ."<br>";
echo $_POST['user']."<br>"; */
// Questo file viene richiamato quando l'admin clicca sul bottone nella tabella per rendere un altro utente admin
//$_SESSION['user'] = pg_escape_string($_POST['userAd']);

//Richiama connessioni al DB
include("root_connection.php");

//Salva user id in una variabile prendendolo dalla url
$id_user=$_GET['idu'];

if(!$conn_isernia) {
    die('Connessione fallita !<br />');
} else {

	if ( isset( $_POST['submitadmin'] ) ) {


		$query = "UPDATE utenti.utenti SET admin = true where id = $1;";
		$result2 = pg_prepare($conn_isernia, "myquery2", $query);
		$result2 = pg_execute($conn_isernia, "myquery2", array($id_user));

		header ("Location: dashboard.php#about");
	}

}
?>