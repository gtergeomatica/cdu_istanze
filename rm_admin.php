<?php
session_start();
//questo file viene richiamato quando l'admin clicca sul pulsante nella tabella utenti per rimuovere un utente da amministratore
//$_SESSION['user'] = pg_escape_string($_POST['userAd']);
//echo $_SESSION['user'] ."<br>";

include("root_connection.php");

//recupera l'id utente dalla url
$id_user=$_GET['idu'];

if(!$conn_isernia) {
    die('Connessione fallita !<br />');
} else {

	if ( isset( $_POST['submitadmin'] ) ) {

		$query = "UPDATE utenti.utenti SET admin = null where id = $1;";
		$result2 = pg_prepare($conn_isernia, "myquery2", $query);
		$result2 = pg_execute($conn_isernia, "myquery2", array($id_user));
		//redirect alla dashboard
		header ("Location: dashboard.php#about");
	}

}
?>