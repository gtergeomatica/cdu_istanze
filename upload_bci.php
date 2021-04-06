<?php
session_start();
//questo file viene richiamato dal modal quando l'utente clicca sul bottone per caricare il bollo del cdu
// salva in $_SESSION lo username passato tramite il value dell'input hidden con name userBC nel modal serve per check su login
$_SESSION['user'] = pg_escape_string($_POST['userBci']);
$estremi_bci = pg_escape_string($_POST['estremi_bci']);
//check su validità estremi integrativi lato php (sostituito con check lato js)
/* $n_bolli=$_GET['nb'];
$estremi_cut = explode(",", $estremi_bci);
$check_error = 0;
if(count($estremi_cut) == 1){
	if (strlen($estremi_cut[0]) > 14 ){
    	//print_r ('Gli estremi devono essere separati da virgola');
		$check_error += 1;
    }
}else{
	if(count($estremi_cut) != $n_bolli){
		$check_error += 2;
	}else{
		foreach($estremi_cut as $ec){
			if (strlen($ec) != 14){
				$check_error += 3;
			}
		}
	}
} */
/* foreach($estremi_cut as $ec){
	if (strlen($ec) != 14){
		echo 'errore';
	}
} */

include("root_connection.php");

//recupera id istanza da url
$id_istanza=$_GET['idi'];

if(!$conn_isernia) {
    die('Connessione fallita !<br />');
} else {
	$check_bollo = 0;
	$query = "SELECT * from istanze.pagamento_bollo_cdu where id_istanza_bc = $1;";
	$result = pg_prepare($conn_isernia, "myquery0", $query);
	$result = pg_execute($conn_isernia, "myquery0", array($id_istanza));
		while($r = pg_fetch_assoc($result)) {
			$file_bci = $r['file_bc_integr'];
			if ($r['file_bc_integr'] != null){
				$check_bollo = 1;
			}
		}
	$query = "SELECT data_invio, firstname, lastname from istanze.istanze i, utenti.utenti u where i.id = $1 
		group by i.data_invio, u.firstname, u.lastname;";
	$result3 = pg_prepare($conn_isernia, "myquery3", $query);
	$result3 = pg_execute($conn_isernia, "myquery3", array($id_istanza));
		while($r = pg_fetch_assoc($result3)) {
			$data = $r['data_invio'];
			$fullname = $r["firstname"]. " " .$r["lastname"];
		}
	//check se è stato caricato il file
	if ( isset( $_FILES['fileToUploadBci'] ) ) {
		$original_file = pg_escape_string($_FILES["fileToUploadBci"]["name"]);
		//check se file è in pdf
		if ($_FILES['fileToUploadBci']['type'] == "application/pdf") {
			$source_file = pg_escape_string($_FILES['fileToUploadBci']['tmp_name']);
			$dest_dir = "/var/www/html/isernia_upload/bollo_cdu/";
			//$dest_file = $dest_dir . basename($_FILES["fileToUploadBci"]["name"]);
			$filename = hash('sha256', basename($original_file));
			$dest_file = $dest_dir . $filename . '.pdf';
			//echo $dest_file;
			if ($file_bci != null){
				unlink($file_bci);
			}
			if (file_exists($dest_file)) {
				print "The file name already exists!!";
			}
			else {
				//sposta il file caricato nella cartella di destinazione
				move_uploaded_file( $source_file, $dest_file )
				or die ("Error!!");
				//$check_bollo = 0;
				//check su eventuali errori
				if($_FILES['fileToUploadBci']['error'] === 0) {
					//query per verificare se il bollo era già stato caricato
					/* $query = "SELECT * from istanze.pagamento_bollo_cdu where id_istanza_bc = $1;";
					$result = pg_prepare($conn_isernia, "myquery0", $query);
					$result = pg_execute($conn_isernia, "myquery0", array($id_istanza));
					while($r = pg_fetch_assoc($result)) {
						if ($r['file_bc_integr'] != null){
							$check_bollo = 1;
						}
					} */
					//altrimenti li aggiorna
					$query = "UPDATE istanze.pagamento_bollo_cdu SET file_bc_integr = $1, estremi_bc_integr = $2 where id_istanza_bc = $3;";
					$result2 = pg_prepare($conn_isernia, "myquery2", $query);
					$result2 = pg_execute($conn_isernia, "myquery2", array($dest_file, $estremi_bci, $id_istanza));

 					//query per recuperare la data in cui l'utente ha inviato l'istanza
					/* $query = "SELECT data_invio, firstname, lastname from istanze.istanze i, utenti.utenti u where i.id = $1 
								group by i.data_invio, u.firstname, u.lastname;";
					$result3 = pg_prepare($conn_isernia, "myquery3", $query);
					$result3 = pg_execute($conn_isernia, "myquery3", array($id_istanza));
					while($r = pg_fetch_assoc($result3)) {
						$data = $r['data_invio'];
						$fullname = $r["firstname"]. " " .$r["lastname"];
					} */
					require('mail_address.php');
					//se è la prima volta che viene caricato manda una mail per informare il comune che l'utente ha caricato il bollo
					if ($check_bollo == 0){
$testo3 = "

COMUNICAZIONE DI SERVIZIO \n
questa mail e' stata generata automaticamente in quanto l'utente " . $fullname . " ha appena caricato i bolli integrativi dovuti per l'istanza di CDU inviata in data ". $data .".
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
					
						$oggetto3 ="Caricamento bolli integrativi per CDU";
						$headers3 = $nostro_recapito .
						"Content-Type: text/plain; charset=utf-8" . "\r\n";
						"Content-Transfer-Encoding: base64" . "\r\n";
						mail ("$loro_recapito", "$oggetto3", "$testo3","$headers3");
					}else{
						//altrimenti manda mail per informare il comune che il file del bollo è stato modificato
$testo2 = "

COMUNICAZIONE DI SERVIZIO \n
questa mail e' stata generata automaticamente in quanto l'utente " . $fullname . " ha appena caricato una nuova copia dei bolli integrativi dovuti per l'istanza di CDU inviata in data ". $data .".
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
					
						$oggetto2 ="Errata Corrige - Caricamento bolli integrativi per CDU";
						$headers2 = $nostro_recapito .
						"Content-Type: text/plain; charset=utf-8" . "\r\n";
						"Content-Transfer-Encoding: base64" . "\r\n";
						mail ("$loro_recapito", "$oggetto2", "$testo2","$headers2");						
					}
					//redirect alla dashboard
					header ("Location: dashboard.php#about");
				}else{
					$error_file = pg_escape_string($_FILES['fileToUploadBci']['error']);
					print "Si è verificato un errore nel caricamento del file: ".$original_file."<br/>";
					print "Codice Errore: ".$error_file."<br/>";
				}
				
			}
		}else {
			if ($_FILES['fileToUploadBci']['name']=='' && $check_bollo == 1){
				//$query = "UPDATE istanze.pagamento_bollo_cdu SET estremi_bc_integr = $1, errore = $2 where id_istanza_bc = $3;";
				$query = "UPDATE istanze.pagamento_bollo_cdu SET estremi_bc_integr = $1 where id_istanza_bc = $2;";
					$result2 = pg_prepare($conn_isernia, "myquery1", $query);
					//$result2 = pg_execute($conn_isernia, "myquery1", array($estremi_bci, $check_error, $id_istanza));
					$result2 = pg_execute($conn_isernia, "myquery1", array($estremi_bci, $id_istanza));
				require('mail_address.php');
$testo3 = "

COMUNICAZIONE DI SERVIZIO \n
questa mail e' stata generata automaticamente in quanto l'utente " . $fullname . " ha appena inserito gli estremi corretti dei bolli integrativi dovuti per l'istanza di CDU inviata in data ". $data .".
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
					
						$oggetto3 ="Errata Corrige - Estremi bolli integrativi per CDU";
						$headers3 = $nostro_recapito .
						"Content-Type: text/plain; charset=utf-8" . "\r\n";
						"Content-Transfer-Encoding: base64" . "\r\n";
						mail ("$loro_recapito", "$oggetto3", "$testo3","$headers3");						
					
					//redirect alla dashboard
					header ("Location: dashboard.php#about");

			}else if ( $_FILES['fileToUploadBci']['type'] != "application/pdf") {

				print "Si è verificato un errore nel caricamento del file: ".$original_file."<br/>";
				print "Estensione del file non valida, il file deve essere in formato pdf !!"."<br/>";
			}
		}
	}
}
?>