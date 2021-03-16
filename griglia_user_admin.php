<?php
session_start();
include("root_connection.php");

$user_id=$_GET['u'];


if(!$conn_isernia) {
    die('Connessione fallita !<br />');
} else {

	// Query per polare la tabella utenti nella dashboard amministratore

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
	} 

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