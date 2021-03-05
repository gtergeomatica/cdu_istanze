<?php
session_start();
/* echo $_SESSION['user'] ."<br>";
echo $_POST['user']."<br>"; */
//echo $_SESSION['user'] ."<br>";

include("root_connection.php");

$id_user=$_GET['idu'];
$cliente = 'Comune di Isernia';

if(!$conn_isernia) {
    die('Connessione fallita !<br />');
} else {

		$query = "SELECT * FROM utenti.utenti where id = $1;";
		$result = pg_prepare($conn_isernia, "myquery3", $query);
		$result = pg_execute($conn_isernia, "myquery3", array($id_user));
		while($r = pg_fetch_assoc($result)) {
				//$rows[] = $r;
				$fullname=$r["firstname"]. " " .$r["lastname"];
				$user_email=$r["usr_email"];
				$doc=$r["doc_id"];
				$docdata=$r["doc_exp"];
		}

		require('mail_address.php');
$testo = "

Egr. " . $fullname. ",\n 
questa mail è stata generata automaticamente in quanto il documento di identità da lei indicato nel Sistema di Istanze Online del " . $cliente . " risulta scaduto.\n
Di seguito i dettagli del suo documento: \n
    N° documento: ".$doc." \n
    Data scadenza: ".$docdata."\n

La invitiamo a modificare quanto prima i dati relativi al suo documento di identità collegandosi alla sua dashboard (https://gishosting.gter.it/isernia/dashboard.php).\n
Le ricordiamo che finchè non modificherà i dati relativi al documento di identità non le sarà possibile inviare una nuova istanza di CDU al Comune.
    
Se riceve questo messaggio per errore, la preghiamo di distruggerlo e di comunicarlo immediatamente all'amministratore del sistema rispondendo a questa mail.\n
In caso di problemi o richieste non esiti a contattare l'amministratore del sistema al seguente indirizzo segreteriagenerale@comune.isernia.it.\n \n
            
Cordiali saluti, \n
L'amministratore del sistema.
        
-- 
Comune di Isernia
Piazza Marconi, 3 - 86170 Isernia (IS)
E-mail: segreteriagenerale@comune.isernia.it

Servizio basato su GisHosting di Gter srl\n

";

	$oggetto ="Documento Scaduto Sistema Istanze Online del Comune di Isernia";
    $headers = $nostro_recapito .
    "Reply-To: " .$loro_recapito. "\r\n" .
	"Content-Type: text/plain; charset=utf-8" . "\r\n";
	"Content-Transfer-Encoding: base64" . "\r\n";
	mail ("$user_email", "$oggetto", "$testo","$headers");

		header ("Location: dashboard.php#about");
	

}
?>