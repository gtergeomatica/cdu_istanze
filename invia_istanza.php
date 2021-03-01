<?php
session_start();
include("root_connection.php");

$id_istanza=$_GET['idi'];
$id_utente=$_GET['idu'];


if(!$conn_isernia) {
    die('Connessione fallita !<br />');
} else {

	$query = "UPDATE istanze.istanze SET inviato = true where id = $1;";
	$result2 = pg_prepare($conn_isernia, "myquery2", $query);
	$result2 = pg_execute($conn_isernia, "myquery2", array($id_istanza));

	$query_user = "SELECT * from utenti.utenti where id=$1";
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

	$dest_dir = "/var/www/html/isernia_upload/mappali_cdu/";
	$dest_name = date("Ymd_his") ."_istanza_" .$id_istanza.".txt";
	$dest_file = $dest_dir. $dest_name;
	$fp = fopen( $dest_file, 'a');

	$query_mappali = "SELECT foglio, mappale from istanze.dettagli_istanze where id_istanza=$1";
	$result_map = pg_prepare($conn_isernia, "myquery1", $query_mappali);
	$result_map = pg_execute($conn_isernia, "myquery1", array($id_istanza));
	while($r = pg_fetch_assoc($result_map)) {
		//$rows[] = $r;
		$rows=array($r["foglio"],$r["mappale"]);
		$test=fputs($fp, implode(',',$rows)."\n");
	}	
	fclose($fp);

	$query_istanza = "SELECT ruolo, motivo from istanze.istanze where id_istanza=$1";
	$result_ist = pg_prepare($conn_isernia, "myquery3", $query_istanza);
	$result_ist = pg_execute($conn_isernia, "myquery3", array($id_istanza));
	while($r = pg_fetch_assoc($result_ist)) {
		//$rows[] = $r;
		$ruolo=$r["ruolo"];
		$motivo=$r["motivo"];
	}

		// INVIO MAIL
	require('mail_address.php');


    $oggetto = "Nuovo istanza CDU";

    $testo = "

Questa mail è stata generata automaticamente in quanto è appena stata inviata un'istanza di CDU da:\n
	Nome: ". $nome . " \n
	Cognome: ". $cognome . " \n
	Codice Fiscale: ". $cf . " \n
	N° Documento: ". $doc . " \n
	Tel: ". $telefono . " \n
	Mail: ". $mail . " \n
	Indirizzo: ". $via . ", " . $cap . ", " . $city . " \n
	In qualità di " . $ruolo . " \n\n

La presente richiesta è per uso: " . $motivo . " \n

In allegato il file di testo con l'elenco delle particelle.

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

// Boundary  
//$semi_rand = md5(time());  
//$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x"; 

// Headers for attachment  
//$headers = "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime_boundary}\""; 
 
// Multipart boundary  
$message = "--{$mime_boundary}\n" . "Content-Type: text/html; charset=\"UTF-8\"\n" . 
"Content-Transfer-Encoding: 7bit\n\n" . $testo . "\n\n";  
 
// Preparing attachment 
if(!empty($dest_file) > 0){ 
	echo $dest_file;
    if(is_file($dest_file)){
		echo $dest_file;
        //$message .= "--{$mime_boundary}\n"; 
        $fpr = fopen($dest_file,"r"); 
        $data = fread($fpr,filesize($dest_file)); 
		echo $data;
        fclose($fpr);
		$encoded_content = chunk_split(base64_encode($data)); 
  
    	$boundary = md5("random"); // define boundary with a md5 hashed value 
    } 
}
//header 
$headers = "MIME-Version: 1.0\r\n"; // Defining the MIME version 
$headers .= "From: GisHosting Gter <".$nostra_mail.">\r\n"; // Sender Email 
//$headers .= "Reply-To: ".$mail_admin."\r\n"; // Email addrress to reach back
$headers .= "Cc: ".$mail_admin."\r\n"; // Email addrress to reach back 
$headers .= "Content-Type: multipart/mixed;\r\n"; // Defining Content-Type 
$headers .= "boundary = $boundary\r\n"; //Defining the Boundary 
	  
//plain text  
$body = "--$boundary\r\n"; 
$body .= "Content-Type: text/plain; charset=UTF-8\r\n"; 
$body .= "Content-Transfer-Encoding: base64\r\n\r\n";  
$body .= chunk_split(base64_encode($testo));  
	  
//attachment 
$body .= "--$boundary\r\n"; 
$body .="Content-Type: text/plain; name=".$dest_name."\r\n"; 
$body .="Content-Disposition: attachment; filename=".$dest_name."\r\n"; 
$body .="Content-Transfer-Encoding: base64\r\n"; 
$body .="X-Attachment-Id: ".rand(1000, 99999)."\r\n\r\n";  
$body .= $encoded_content; // Attaching the encoded file with email 
//$message .= "--{$mime_boundary}--";


	mail ($loro_recapito, $oggetto, $body, $headers);
    
    $testo2 = "

Egr. " . $nome . " " .$cognome. ",\n 
questa mail e' stata generata automaticamente in quanto ha appena inviato un'istanza di CDU.\n

    
Se riceve questo messaggio per errore, la preghiamo di distruggerlo e di comunicarlo immediatamente all'amministratore del sistema rispondendo a questa mail. Se invece ha effettivamente inviato un'istanza di CDU, riceverà una nuova mail non appena il documento sarà disponibile sulla sua dashboard al seguente link https://gishosting.gter.it/isernia/dashboard.php \n
In caso di problemi o richieste non esiti a contattare l'amministratore del sistema al seguente indirizzo DL_Cartografia@astergenova.it.\n \n
            
Cordiali saluti, \n
L'amministratore del sistema.
        
-- 
Comune di Isernia
Piazza Marconi, 3 - 86170 Isernia (IS)
E-mail: segreteriagenerale@comune.isernia.it

Servizio basato su GisHosting di Gter srl\n

";

	$oggetto2 ="Nuova Istanza CDU";
    $headers2 = $nostro_recapito .
    "Reply-To: " .$loro_recapito. "\r\n" .
    "Cc: " .$mail_admin. "\r\n";
	mail ("$mail", "$oggetto2", "$testo2","$headers2");
}

?>