<?php
session_start();
//questo file è richiamato quando l'utente clicca su bottone per rimuovere istanze
include("root_connection.php");

$id_istanza=pg_escape_string($_GET['idi']);


if(!$conn_isernia) {
    die('Connessione fallita !<br />');
} else {
	//query per rimuovere sia l'istanza che i relativi dettagli dal DB
	$query= "SELECT * from istanze.istanze where id=$1";
	$result= pg_prepare($conn_isernia, "myquery0", $query);
	$result= pg_execute($conn_isernia, "myquery0", array($id_istanza));
	while($r = pg_fetch_assoc($result)) {
		$file_cdu=$r['file_cdu'];
	}
	if ($file_cdu != null){
		if (!unlink($file_cdu)) {  
			echo ("file cannot be deleted due to an error");  
		}else{
			$query= "UPDATE istanze.istanze SET file_cdu = null where id=$1";
			$result= pg_prepare($conn_isernia, "myquery1", $query);
			$result= pg_execute($conn_isernia, "myquery1", array($id_istanza));
		}

	}
	//redirect alla dashboard
	header("Location: dashboard.php#about");
}

?>