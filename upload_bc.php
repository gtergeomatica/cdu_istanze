<?php
session_start();
//questo file viene richiamato dal modal quando l'utente clicca sul bottone per caricare il bollo del cdu
// salva in $_SESSION lo username passato tramite il value dell'input hidden con name userBC nel modal serve per check su login
$_SESSION['user'] = pg_escape_string($_POST['userBc']);
$estremi_bc = pg_escape_string($_POST['estremi_bc']);

include("root_connection.php");

//recupera id istanza da url
$id_istanza=pg_escape_string($_GET['idi']);

if(!$conn_isernia) {
    die('Connessione fallita !<br />');
} else {
	//check se bottone invio nel modal è stato cliccato
	if ( isset( $_FILES['fileToUploadBc'] ) ) {
		$original_file = pg_escape_string($_FILES["fileToUploadBc"]["name"]);
		//check se file è in pdf
		if ($_FILES['fileToUploadBc']['type'] == "application/pdf") {
			$source_file = pg_escape_string($_FILES['fileToUploadBc']['tmp_name']);
			$dest_dir = "/var/www/html/isernia_upload/bollo_cdu/";
			//$dest_file = $dest_dir . basename(pg_escape_string($_FILES["fileToUploadBc"]["name"]));
			$filename = hash('sha256', basename($original_file));
			$dest_file = $dest_dir . $filename . '.pdf';
			//echo $dest_file;

			if (file_exists($dest_file)) {
				print "The file name already exists!!";
			}
			else {
				//sposta il file caricato nella cartella di destinazione
				move_uploaded_file( $source_file, $dest_file )
				or die ("Error!!");
				//$check_bollo = 0;
				//check su eventuali errori
				if($_FILES['fileToUploadBc']['error'] === 0) {
					//query per verificare se il bollo era già stato caricato
					$query = "SELECT * from istanze.pagamento_bollo_cdu where id_istanza_bc = $1;";
					$result = pg_prepare($conn_isernia, "myquery0", $query);
					$result = pg_execute($conn_isernia, "myquery0", array($id_istanza));
					while($r = pg_fetch_assoc($result)) {
						$istanza = $r['id_istanza_bc'];
					}
					//se non è stato caricato prima inserisce i dati
					if ($istanza == ''){
						$query = "INSERT into istanze.pagamento_bollo_cdu (id_istanza_bc, file_bc, estremi_bc)  values($1, $2, $3);";
						$result1 = pg_prepare($conn_isernia, "myquery1", $query);
						$result1 = pg_execute($conn_isernia, "myquery1", array($id_istanza, $dest_file, $estremi_bc));
					}
					else{
						//altrimenti li aggiorna
						$query = "UPDATE istanze.pagamento_bollo_cdu SET file_bc = $1, estremi_bc = $2 where id_istanza_bc = $3;";
						$result2 = pg_prepare($conn_isernia, "myquery2", $query);
						$result2 = pg_execute($conn_isernia, "myquery2", array($dest_file, $estremi_bc, $id_istanza));
						//$check_bollo = 1;
					}
/* 					//query per recuperare la data in cui l'utente ha inviato l'istanza
					$query = "SELECT * from istanze.istanze where id = $1;";
					$result3 = pg_prepare($conn_isernia, "myquery3", $query);
					$result3 = pg_execute($conn_isernia, "myquery3", array($id_istanza));
					while($r = pg_fetch_assoc($result3)) {
						$data = $r['data_invio'];
					}
					require('mail_address.php');
					//se è la prima volta che viene caricato manda una mail per informare il comune che l'utente ha caricato il bollo
					if ($check_bollo == 0){
$testo3 = "

COMUNICAZIONE DI SERVIZIO \n
questa mail e' stata generata automaticamente in quanto l'utente " . $_SESSION['user'] . " ha appena caricato l'autocertificazione di pagamento dei bolli dovuti per l'istanza di CDU inviata in data ". $data .".
Verificare il pagamento e quindi inviare il CDU.
	
Se riceve questo messaggio per errore, la preghiamo di distruggerlo e di comunicarlo immediatamente all'amministratore del sistema rispondendo a questa mail.\n
			
Cordiali saluti, \n
L'amministratore del sistema.
		
-- 
Comune di Isernia
Piazza Marconi, 3 - 86170 Isernia (IS)
E-mail: segreteriagenerale@comune.isernia.it

Servizio basato su GisHosting di Gter srl\n

";
					
						$oggetto3 ="Caricamento bolli per CDU";
						$headers3 = $nostro_recapito .
						"Content-Type: text/plain; charset=utf-8" . "\r\n";
						"Content-Transfer-Encoding: base64" . "\r\n";
						mail ("$loro_recapito", "$oggetto3", "$testo3","$headers3");
					}else{
						//altrimenti manda mail per informare il comune che il file del bollo è stato modificato
$testo2 = "

COMUNICAZIONE DI SERVIZIO \n
questa mail e' stata generata automaticamente in quanto l'utente " . $_SESSION['user'] . " ha appena caricato una nuova autocertificazione di pagamento dei bolli dovuti per l'istanza di CDU inviata in data ". $data .".
Verificare il pagamento e quindi inviare il CDU.
	
Se riceve questo messaggio per errore, la preghiamo di distruggerlo e di comunicarlo immediatamente all'amministratore del sistema rispondendo a questa mail.\n
			
Cordiali saluti, \n
L'amministratore del sistema.
		
-- 
Comune di Isernia
Piazza Marconi, 3 - 86170 Isernia (IS)
E-mail: segreteriagenerale@comune.isernia.it

Servizio basato su GisHosting di Gter srl\n

";
					
						$oggetto2 ="Errata Corrige - Caricamento bolli per CDU";
						$headers2 = $nostro_recapito .
						"Content-Type: text/plain; charset=utf-8" . "\r\n";
						"Content-Transfer-Encoding: base64" . "\r\n";
						mail ("$loro_recapito", "$oggetto2", "$testo2","$headers2");						
					} */
					//redirect alla dashboard
					header ("Location: dashboard.php#about");
				}else{
					$error_file = pg_escape_string($_FILES['fileToUploadBc']['error']);
					print "Si è verificato un errore nel caricamento del file: ".$original_file."<br/>";
					print "Codice Errore: ".$error_file."<br/>";
				}
				
			}
		}else {
			if ( $_FILES['fileToUploadBc']['type'] != "application/pdf") {
				print "Si è verificato un errore nel caricamento del file: ".$original_file."<br/>";
				print "Estensione del file non valida, il file deve essere in formato pdf !!"."<br/>";
			}
		}
	}
}
?>