<?php
session_start();
include("root_connection.php");

$user_id=$_GET['u'];


if(!$conn_isernia) {
    die('Connessione fallita !<br />');
} else {

	
	$query_istanza = "SELECT id_istanza, string_agg(concat('F',foglio,' M',mappale), ', ') as terreni, data_istanza 
		FROM istanze.dettagli_istanze d, istanze.istanze i 
		where d.id_istanza = i.id and i.id_utente = $1
		group by d.id_istanza, i.data_istanza 
		;";
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
		echo $query_istanza;
		echo "[{\"NOTE\":'Nessun dato presente'}]";
	}
}

?>