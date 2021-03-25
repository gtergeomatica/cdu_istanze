<?php
session_start();
//questo file è richiamato quando l'utente clicca su bottone per rimuovere istanze
include("root_connection.php");

$id_istanza=$_GET['idi'];


if(!$conn_isernia) {
    die('Connessione fallita !<br />');
} else {
	$query_select = "SELECT file_s, file_bc, file_bi from istanze.pagamento_segreteria s
			left join istanze.pagamento_bollo_cdu bc
			on s.id_istanza_s = bc.id_istanza_bc 
			left join istanze.pagamento_bollo_ist bi 
			on s.id_istanza_s = bi.id_istanza_bi 
			where s.id_istanza_s = $1";
	$result_sel = pg_prepare($conn_isernia, "myquery5", $query_select);
	$result_sel = pg_execute($conn_isernia, "myquery5", array($id_istanza));
	while($r = pg_fetch_assoc($result_sel)) {
		//$rows[] = $r;
		$file_s=$r["file_s"];
		$file_bi=$r["file_bi"];
		$file_bc=$r["file_bc"];
	}
	if ($file_s != null){
		if (file_exists($file_s)) {  
			unlink($file_s);  
		}

	}
	if ($file_bi != null){
		if (file_exists($file_bi)) {  
			unlink($file_bi);  
		}

	}
	if ($file_bc != null){
		if (file_exists($file_bc)) {  
			unlink($file_bc);  
		}

	}
	//query per rimuovere sia l'istanza che i relativi dettagli dal DB
	$query_remove = "DELETE from istanze.dettagli_istanze where id_istanza=$1";
	$result_rem = pg_prepare($conn_isernia, "myquery0", $query_remove);
	$result_rem = pg_execute($conn_isernia, "myquery0", array($id_istanza));
	
	$query_remove1 = "DELETE from istanze.istanze where id=$1";
	$result_rem1 = pg_prepare($conn_isernia, "myquery1", $query_remove1);
	$result_rem1 = pg_execute($conn_isernia, "myquery1", array($id_istanza));

	$query_remove2 = "DELETE from istanze.pagamento_bollo_ist where id_istanza_bi=$1";
	$result_rem2 = pg_prepare($conn_isernia, "myquery2", $query_remove2);
	$result_rem2 = pg_execute($conn_isernia, "myquery2", array($id_istanza));

	$query_remove3 = "DELETE from istanze.pagamento_bollo_cdu where id_istanza_bc=$1";
	$result_rem3 = pg_prepare($conn_isernia, "myquery3", $query_remove3);
	$result_rem3 = pg_execute($conn_isernia, "myquery3", array($id_istanza));

	$query_remove4 = "DELETE from istanze.pagamento_segreteria where id_istanza_s=$1";
	$result_rem4 = pg_prepare($conn_isernia, "myquery4", $query_remove4);
	$result_rem4 = pg_execute($conn_isernia, "myquery4", array($id_istanza));
	//redirect alla dashboard
	header("Location: dashboard.php#about");
}

?>