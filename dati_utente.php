
<!--div class="container"-->
<div>
	<h2>Dati utente</h2>
<?php

	$query = "SELECT * FROM utenti.utenti where  usr_login=$1";
	$result = pg_prepare($conn_isernia, "myquery0", $query);
    $result = pg_execute($conn_isernia, "myquery0", array($_SESSION['user']));
	//echo $query;
	//exit;
	//$rows = array();
	while($r = pg_fetch_assoc($result)) {
    		//$rows[] = $r;
			echo "<b>User</b>: ".$r["usr_login"];
			echo "<br><b>Nome e Cognome</b>: ".$r["firstname"]. " " .$r["lastname"];
			echo "<br><b>Codice Fiscale</b>: ".$r["cf"];
			echo "<br><b>Documento identità</b>: ".$r["doc_id"];
			echo "<br><b>Indirizzo</b>: ".$r["street"]. " - " .$r["postcode"]. ", " .$r["city"];
			echo "<br><b>E-mail</b>: ".$r["usr_email"];
			echo "<br><b>Telefono</b>: ".$r["phonenumber"];

?>

<hr class="light">
<div>
	<a class="btn btn-light btn-xl" href="https://www.gter.it/">Richiedi CDU</a>
</div>
<hr class="light">

<?php
}
?>
</div>
