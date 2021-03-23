<?php
session_start();
//questo file è richiamato quando l'utente clicca su bottone per rimuovere istanze
include("root_connection.php");

$id_istanza=$_GET['idi'];


if(!$conn_isernia) {
    die('Connessione fallita !<br />');
} else {
	//query per rimuovere sia l'istanza che i relativi dettagli dal DB
	$query= "SELECT * from istanze.pagamento_bollo_ist where id_istanza_bi=$1";
	$result= pg_prepare($conn_isernia, "myquery0", $query);
	$result= pg_execute($conn_isernia, "myquery0", array($id_istanza));
	while($r = pg_fetch_assoc($result)) {
		$file_bi=$r['file_bi'];
	}
	if ($file_bi != null){
		if (!unlink($file_bi)) {  
			echo ("file cannot be deleted due to an error");  
		}else{
			$query= "UPDATE istanze.pagamento_bollo_ist SET file_bi = null, estremi_bi = null where id_istanza_bi=$1";
			$result= pg_prepare($conn_isernia, "myquery1", $query);
			$result= pg_execute($conn_isernia, "myquery1", array($id_istanza));
		}

	}
	//redirect alla dashboard
	header("Location: dashboard.php#about");
}

?>