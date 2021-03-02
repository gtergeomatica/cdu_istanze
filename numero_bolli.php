<?php
session_start();
/* echo $_SESSION['user'] ."<br>";
echo $_POST['user']."<br>"; */
$_SESSION['user'] = pg_escape_string($_POST['userNb']);
//echo $_SESSION['user'] ."<br>";

include("root_connection.php");

$id_istanza=$_GET['idi'];

if(!$conn_isernia) {
    die('Connessione fallita !<br />');
} else {

	if ( isset( $_POST['submitnum'] ) ) {

		$numero = pg_escape_string($_POST['numeroBolli']);

		$query = "UPDATE istanze.istanze SET n_bolli = $1 where id = $2;";
		$result2 = pg_prepare($conn_isernia, "myquery2", $query);
		$result2 = pg_execute($conn_isernia, "myquery2", array($numero, $id_istanza));

		$query = "SELECT firstname, lastname, usr_email, data_istanza
			FROM utenti.utenti u
			left join istanze.istanze i
			on u.id = id_utente
			where i.id = $1
			group by u.firstname, u.lastname, u.usr_email, i.data_istanza 
			order by i.data_istanza;";
		$result = pg_prepare($conn_isernia, "myquery3", $query);
		$result = pg_execute($conn_isernia, "myquery3", array($id_istanza));
		while($r = pg_fetch_assoc($result)) {
				//$rows[] = $r;
				$fullname=$r["firstname"]. " " .$r["lastname"];
				$user_email=$r["usr_email"];
				$data=$r["data_istanza"];
		}

		require('mail_address.php');
$testo = "

Egr. " . $fullname. ",\n 
questa mail e' stata generata automaticamente in quanto il Comune di Isernia ha elaborato la sua istanza di CDU aggiunta in data " . $data . ".\n
Per poter scaricare il CDU è necessario che venga caricata sulla sua dashboard l'autocertificazione di avvenuto pagamento di n° " . $numero . " bolli
da 16,00 euro da assolvere tramite Modello F23 o acquisto presso un rivenditore.\n

Può scaricare il modulo di autocertificazione a questo link: https://gishosting.gter.it/isernia/#moduli \n
Una volta caricato il modulo potrà scaricare il CDU.
    
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

	$oggetto ="Assolvimento Bolli per CDU del Comune di Isernia";
    $headers = $nostro_recapito .
    "Reply-To: " .$loro_recapito. "\r\n";
	mail ("$user_email", "$oggetto", "$testo","$headers");

		header ("Location: dashboard.php#about");
	}

}
?>