<?php
session_start();
include("root_connection.php");

$user_id=$_GET['u'];


if(!$conn_isernia) {
    die('Connessione fallita !<br />');
} else {

	
	/* $query_istanza = "SELECT id_istanza, string_agg(concat('F',foglio,' M',mappale), ', ') as terreni, data_istanza, file_s, file_bi, file_bc
		FROM istanze.dettagli_istanze d, istanze.istanze i, istanze.pagamento_segreteria ps, istanze.pagamento_bollo_ist pbi, istanze.pagamento_bollo_cdu pbc 
		where d.id_istanza = i.id and i.id_utente = $1 and d.id_istanza = ps.id_istanza_s and d.id_istanza = pbi.id_istanza_bi and d.id_istanza = pbc.id_istanza_bc
		group by d.id_istanza, i.data_istanza, ps.file_s, pbi.file_bi, pbc.file_bc
		;"; */
	$query_users = "SELECT id, usr_login, string_agg(concat(firstname,' ',lastname), '') as nome, 
			usr_email, cf, doc_id, 
			string_agg(concat(street ,' - ',postcode, ', ', city), '') as indirizzo,
			phonenumber, organization, admin, nascosto, doc_exp
			from utenti.utenti
			where nascosto is not true
			group by id, usr_login, usr_email, cf, doc_id, phonenumber, organization, admin, lastname, doc_exp
			order by lastname;";
	//echo $query."<br>";
	$result = pg_prepare($conn_isernia, "myquery0", $query_users);
    $result = pg_execute($conn_isernia, "myquery0", array());
	//echo $user_id;
	//exit;
	$rows = array();
	//echo $rows;
	//$i=0;
	while($r = pg_fetch_assoc($result)) {
		//array_push($rows,$r);
    	$rows[] = $r;
		//echo $r[login] .",\n";
        //$query_log = "SELECT log_key, log_user, log_timestamp, log_content, log_repository, log_project 
		//FROM log_detail 
		//WHERE log_user = '" .$r[login]. "'
		/* if ($i==0){
			$query_istanza= $query_istanza . " '" . $r['login']."'";
		} else {
			$query_istanza= $query_istanza . ", '" . $r['login']."'";
		}
		$i=$i+1; */
	} 
	/* $query_istanza= $query_istanza . ') ORDER BY data_istanza desc;';
    //echo $query_log ."<br>";
	$result_log = pg_query($conn_isernia, $query_istanza);
	while($rl = pg_fetch_assoc($result_log)){
		//array_push($rows, pg_fetch_array($result_log));
		//echo $rl;
		array_push($rows,$rl);
	}
	 */
	pg_close($conn_isernia);
	if (empty($rows)==FALSE){
		//print $rows;
		print json_encode($rows);
	} else {
		echo $query_users;
		echo "[{\"NOTE\":'Nessun dato presente'}]";
	}
}

?>