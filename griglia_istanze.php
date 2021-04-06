<?php
session_start();
include("root_connection.php");

$user_id=pg_escape_string($_GET['u']);
$user_idn=(int)$user_id;

if(!$conn_isernia) {
    die('Connessione fallita !<br />');
} else {
	// Query per polare la tabella con i dettagli del terreno selezionato in form_istanza_cdu.php
	$query1 = "SELECT data, id_utente, foglio, mappale from istanze.istanze_temp where id_utente=$1 and data > now() - interval '60 minutes' ";
    $result1 = pg_prepare($conn_isernia, "myquery1", $query1);
    $result1 = pg_execute($conn_isernia, "myquery1", array($user_idn));
	//echo $query."<br>";
	//echo $user_id;
	//exit;
	$rows = array();
	//echo $rows;
	//$i=0;
	while($r = pg_fetch_assoc($result1)) {
		//array_push($rows,$r);
    	$rows[] = $r;
	} 

	//pg_close($conn_isernia);
	if (empty($rows)==FALSE){
		//print $rows;
		print json_encode($rows);
		
	} else {
		echo $query1;
		echo "[{\"NOTE\":'Nessun dato presente'}]";
	}
}

?>