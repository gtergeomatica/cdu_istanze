<?php
session_start();
include("root_connection.php");

$user_id=$_GET['u'];


if(!$conn_isernia) {
    die('Connessione fallita !<br />');
} else {

	// Query per polare la tabella istanza nella dashboard amministratore

	/* $query_istanza = "SELECT id_istanza, string_agg(concat('F',foglio,' M',mappale), ', ') as terreni, data_istanza, file_s, file_bi, file_bc
		FROM istanze.dettagli_istanze d, istanze.istanze i, istanze.pagamento_segreteria ps, istanze.pagamento_bollo_ist pbi, istanze.pagamento_bollo_cdu pbc 
		where d.id_istanza = i.id and i.id_utente = $1 and d.id_istanza = ps.id_istanza_s and d.id_istanza = pbi.id_istanza_bi and d.id_istanza = pbc.id_istanza_bc
		group by d.id_istanza, i.data_istanza, ps.file_s, pbi.file_bi, pbc.file_bc
		;"; */
	$query_istanza = "SELECT id_istanza, string_agg(concat('F',foglio,' M',mappale), ', ') as terreni, usr_login, concat(firstname,' ',lastname) as nome, usr_email, nascosto, data_istanza, motivo, inviato, file_txt, n_bolli, file_s, estremi_s, file_bi, estremi_bi, file_bc, estremi_bc, file_bc_integr, estremi_bc_integr, file_cdu, terminato, data_invio, dt.descr as tipo
			FROM istanze.dettagli_istanze d
			left join istanze.istanze i
			on d.id_istanza = i.id
			left join istanze.pagamento_segreteria ps
			on d.id_istanza = ps.id_istanza_s
			left join istanze.pagamento_bollo_ist pbi
			on d.id_istanza = pbi.id_istanza_bi
			left join istanze.pagamento_bollo_cdu pbc 
			on d.id_istanza = pbc.id_istanza_bc
			left join utenti.utenti u
			on u.id = i.id_utente
			left join istanze.deco_tipo dt
			on dt.cod = i.tipo
			where i.inviato = true and u.nascosto is not true
			group by d.id_istanza, u.usr_login, u.firstname, u.lastname, u.usr_email, u.nascosto, i.data_istanza, i.motivo, i.inviato, i.file_txt, i.n_bolli, ps.file_s, ps.estremi_s, pbi.file_bi, pbi.estremi_bi, pbc.file_bc, pbc.estremi_bc, pbc.file_bc_integr, pbc.estremi_bc_integr, i.file_cdu, i.terminato, i.data_invio, dt.descr
			order by i.data_invio desc;";
	//echo $query."<br>";
	$result = pg_prepare($conn_isernia, "myquery0", $query_istanza);
    $result = pg_execute($conn_isernia, "myquery0", array());
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