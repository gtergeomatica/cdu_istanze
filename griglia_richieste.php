<?php
session_start();
include("root_connection.php");

$user_id=$_GET['u'];


if(!$conn_isernia) {
    die('Connessione fallita !<br />');
} else {

	// Query per polare la tabella istanze nella dashboard utente

	$query_istanza = "SELECT id_istanza, string_agg(concat('F',foglio,' M',mappale), ', ') as terreni, data_istanza, inviato, n_bolli, file_s, estremi_s, file_bi, estremi_bi, file_bc, estremi_bc, file_bc_integr, estremi_bc_integr, file_cdu, terminato, dt.descr as tipo
		FROM istanze.dettagli_istanze d
		left join istanze.istanze i
		on d.id_istanza = i.id
		left join istanze.pagamento_segreteria ps
		on d.id_istanza = ps.id_istanza_s
		left join istanze.pagamento_bollo_ist pbi
		on d.id_istanza = pbi.id_istanza_bi
		left join istanze.pagamento_bollo_cdu pbc 
		on d.id_istanza = pbc.id_istanza_bc
		left join istanze.deco_tipo dt
		on dt.cod = i.tipo
		where i.id_utente = $1
		group by d.id_istanza, i.data_istanza, i.inviato, i.n_bolli, ps.file_s, ps.estremi_s, pbi.file_bi, pbi.estremi_bi, pbc.file_bc, pbc.estremi_bc, pbc.file_bc_integr, pbc.estremi_bc_integr, i.file_cdu, i.terminato, dt.descr
		order by i.data_istanza desc;";
	//echo $query."<br>";
	$result = pg_prepare($conn_isernia, "myquery0", $query_istanza);
    $result = pg_execute($conn_isernia, "myquery0", array($user_id));
	//echo $user_id;
	//exit;
	$rows = array();
	//echo $rows;
	//$i=0;
	while($r = pg_fetch_assoc($result)) {
		//array_push($rows,$r);
    	$rows[] = $r;
	} 

	pg_close($conn_isernia);
	if (empty($rows)==FALSE){
		//print $rows;
		print json_encode($rows);
	} else {
		echo $query_istanza;
		echo "[{\"NOTE\":'Nessun dato presente'}]";
	}
}

?>