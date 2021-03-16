<?php
session_start();
//questo file è richiamato quando l'admin clicca su bottone per rimuovere user
include("root_connection.php");

//recupera id utente da url
$id_user=$_GET['idu'];


if(!$conn_isernia) {
    die('Connessione fallita !<br />');
} else {
	// l'utente non viene rimosso dal DB ma viene messa la colonna nascosto a true
	$query_remove = "UPDATE utenti.utenti SET nascosto = true where id=$1";
	$result_rem = pg_prepare($conn_isernia, "myquery0", $query_remove);
	$result_rem = pg_execute($conn_isernia, "myquery0", array($id_user));


	/* $query_usrname = "UPDATE utenti.utenti SET usr_login = (SELECT concat(usr_login, '_hide', to_char(now(), 'YYYYMMDDHH24MI')) from utenti.utenti where id = $1) where id=$1";
	$result_usr = pg_prepare($conn_isernia, "myquery1", $query_usrname);
	$result_usr = pg_execute($conn_isernia, "myquery1", array($id_user)); */

	//redirect alla dashboard
	header("Location: dashboard.php#about");
}

?>