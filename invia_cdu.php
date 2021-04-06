<?php
session_start();
// questo file viene richiamato quando l'admin clicca il bottone invia cdu sulla tabella istanze
include("root_connection.php");
// salva nelle variabili id e username presi dalla url
$id_istanza=pg_escape_string($_GET['idi']);
$id_utente=pg_escape_string($_GET['idu']);


if(!$conn_isernia) {
    die('Connessione fallita !<br />');
} else {

	//query per recuperare dati utente

	$query_user = "SELECT * from utenti.utenti where usr_login=$1";
	$result_usr = pg_prepare($conn_isernia, "myquery0", $query_user);
	$result_usr = pg_execute($conn_isernia, "myquery0", array($id_utente));
	while($r = pg_fetch_assoc($result_usr)) {
		//$rows[] = $r;
		$username=$r["usr_login"];
		$nome=$r["firstname"];
		$cognome=$r["lastname"];
		$cf=$r["cf"];
		$doc=$r["doc_id"];
		$via=$r["street"];
		$cap=$r["postcode"];
		$city=$r["city"];
		$mail=$r["usr_email"];
		$telefono=$r["phonenumber"];
	}

	// query per aggiornare tabella istanze mettendo istanza terminata = true
	$query = "UPDATE istanze.istanze SET terminato = true where id = $1;";
	$result2 = pg_prepare($conn_isernia, "myquery2", $query);
	$result2 = pg_execute($conn_isernia, "myquery2", array($id_istanza));

	$query_istanza = "SELECT * from istanze.istanze where id=$1";
	$result_ist = pg_prepare($conn_isernia, "myquery3", $query_istanza);
	$result_ist = pg_execute($conn_isernia, "myquery3", array($id_istanza));
	while($r = pg_fetch_assoc($result_ist)) {
		//$rows[] = $r;
		$data=$r["data_istanza"];
		$file=$r["file_cdu"];
		if ($r["tipo"] == 1){
			$tipo = 'CDU';
		}else{
			$tipo = 'Visura';
		}
	}

		// INVIO MAIL
	require('mail_address.php');

	//mail a comune
	if ($tipo == 'CDU'){
    	$oggetto = "CDU inviato";
	}else{
		$oggetto = "Visura inviata";
	}
    $testo = "

Questa mail e' stata generata automaticamente in quanto e' appena stato inviato il file di ".$tipo." relativo all'istanza n. " . $id_istanza . " a:\n
	Nome: ". $nome . " \n
	Cognome: ". $cognome . " \n
	Codice Fiscale: ". $cf . " \n
	N° Documento: ". $doc . " \n
	Tel: ". $telefono . " \n
	Mail: ". $mail . " \n
	Indirizzo: ". $via . ", " . $cap . ", " . $city . " \n

Se riceve questo messaggio per errore, la preghiamo di distruggerlo e di  darcene  immediata  comunicazione  anche  inviando  un  messaggio  di  ritorno  all'indirizzo  e-mail  del mittente. 	In caso di problemi o richieste non esiti a ricontattarci.\n \n
            
Il team di GisHosting
        
--  
GisHosting di Gter srl
Via Ruffini 9R - 16128 Genova
P.IVA/CF 01998770992
Tel. +39 010 8694830
E-mail: gishosting@gter.it
www.gishosting.gter.it
www.twitter.com/Gteronline - www.facebook.com/Gteronline
www.linkedin.com/company/gter-srl-innovazione-in-geomatica-gnss-e-gis\n
            
Le informazioni, i dati e le notizie contenute nella presente comunicazione e i relativi allegati sono di natura  privata  e  come  tali  possono  essere  riservate  e  sono,  comunque,  destinate  esclusivamente  ai destinatari indicati in epigrafe. La diffusione, distribuzione e/o la copiatura del documento trasmesso da parte di qualsiasi soggetto diverso dal destinatario è proibita, sia ai sensi dell’art. 616 c.p., sia ai sensi del D.Lgs. n. 196/2003. \n
Se avete ricevuto questo messaggio per errore, vi preghiamo di distruggerlo e di  darcene  immediata  comunicazione  anche  inviando  un  messaggio  di  ritorno  all’indirizzo  e-mail  del mittente.			

";
	$headers = $nostro_recapito .
	"Cc: " .$mail_admin. "\r\n" .
	"Content-Type: text/plain; charset=utf-8" . "\r\n";
	"Content-Transfer-Encoding: base64" . "\r\n";
	mail ($loro_recapito, $oggetto, $testo, $headers);
    
	// mail a utente per avvisarlo che il cdu è disponibile per il download
    $testo2 = "

Egr. " . $nome . " " .$cognome. ",\n 
questa mail e' stata generata automaticamente in quanto e' appena stato reso disponibile dal Comune di Isernia il file di ".$tipo." per l'istanza aggiunta in data " . $data . ".\n
Accedendo alla sua dashboard potrà scaricare il file di ".$tipo.".
    
Se riceve questo messaggio per errore, la preghiamo di distruggerlo e di comunicarlo immediatamente all'amministratore del sistema rispondendo a questa mail.\n
In caso di problemi o richieste non esiti a contattare l'amministratore del sistema al seguente indirizzo cdu@comune.isernia.it.\n \n
            
Cordiali saluti, \n
L'amministratore del sistema.
        
-- 
Comune di Isernia
Piazza Marconi, 3 - 86170 Isernia (IS)
E-mail: segreteriagenerale@comune.isernia.it

Servizio basato su GisHosting di Gter srl\n

";

	$oggetto2 = $tipo." disponibile per il download";
    $headers2 = $nostro_recapito .
    "Reply-To: " .$loro_recapito. "\r\n" .
    "Cc: " .$mail_admin. "\r\n" .
	"Content-Type: text/plain; charset=utf-8" . "\r\n";
	"Content-Transfer-Encoding: base64" . "\r\n";
	mail ("$mail", "$oggetto2", "$testo2","$headers2");

	header ("Location: dashboard.php#about");
}

?>