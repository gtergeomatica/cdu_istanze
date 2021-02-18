<?php
session_start();
include("root_connection.php");

$user_id=$_GET['u'];
$user_idn=(int)$user_id;

if(!$conn_isernia) {
    die('Connessione fallita !<br />');
} else {

	$query1 = "SELECT data, foglio, mappale from istanze.istanze_temp where id_utente=$1 and data > now() - interval '60 minutes' ";
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
		echo $query1;
		echo "[{\"NOTE\":'Nessun dato presente'}]";
	}
}

?>