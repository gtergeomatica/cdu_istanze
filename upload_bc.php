<?php
session_start();
/* echo $_SESSION['user'] ."<br>";
echo $_POST['user']."<br>"; */
$_SESSION['user'] = pg_escape_string($_POST['userBc']);
//echo $_SESSION['user'] ."<br>";

include("root_connection.php");

$id_istanza=$_GET['idi'];

if(!$conn_isernia) {
    die('Connessione fallita !<br />');
} else {

	if ( isset( $_FILES['fileToUploadBc'] ) ) {
		if ($_FILES['fileToUploadBc']['type'] == "application/pdf") {
			$source_file = $_FILES['fileToUploadBc']['tmp_name'];
			$dest_dir = "/var/www/html/isernia_upload/bollo_cdu/";
			$dest_file = $dest_dir . basename($_FILES["fileToUploadBc"]["name"]);
			//echo $dest_file;

			if (file_exists($dest_file)) {
				print "The file name already exists!!";
			}
			else {
				move_uploaded_file( $source_file, $dest_file )
				or die ("Error!!");
				$check_bollo = 0;
				if($_FILES['fileToUploadBc']['error'] == 0) {
					//$query = "SELECT exists (SELECT * from istanze.pagamento_segreteria where id_istanza_s = $1);";
					$query = "SELECT * from istanze.pagamento_bollo_cdu where id_istanza_bc = $1;";
					$result = pg_prepare($conn_isernia, "myquery0", $query);
					$result = pg_execute($conn_isernia, "myquery0", array($id_istanza));
					while($r = pg_fetch_assoc($result)) {
						$istanza = $r['id_istanza_bc'];
					}
					if ($istanza == ''){
						$query = "INSERT into istanze.pagamento_bollo_cdu (id_istanza_bc, file_bc)  values($1, $2);";
						$result1 = pg_prepare($conn_isernia, "myquery1", $query);
						$result1 = pg_execute($conn_isernia, "myquery1", array($id_istanza, $dest_file));
					}
					else{
						$query = "UPDATE istanze.pagamento_bollo_cdu SET file_bc = $1 where id_istanza_bc = $2;";
						$result2 = pg_prepare($conn_isernia, "myquery2", $query);
						$result2 = pg_execute($conn_isernia, "myquery2", array($dest_file, $id_istanza));
						$check_bollo = 1;
					}
					$query = "SELECT * from istanze.istanze where id = $1;";
					$result3 = pg_prepare($conn_isernia, "myquery3", $query);
					$result3 = pg_execute($conn_isernia, "myquery3", array($id_istanza));
					while($r = pg_fetch_assoc($result3)) {
						$data = $r['data_invio'];
					}
					require('mail_address.php');
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
					}

					header ("Location: dashboard.php#about");
				}else{
					print "Si è verificato un errore nel caricamento del file: ".$_FILES['fileToUploadBc']['name']."<br/>";
					print "Codice Errore: ".$_FILES['fileToUploadBc']['error']."<br/>";
				}
				
			}
		}else {
			if ( $_FILES['fileToUploadBc']['type'] != "application/pdf") {
				print "Si è verificato un errore nel caricamento del file: ".$_FILES['fileToUploadBc']['name']."<br/>";
				print "Estensione del file non valida, il file deve essere in formato pdf !!"."<br/>";
				print "Codice Errore: ".$_FILES['fileToUploadBc']['error']."<br/>";
			}
		}
	}
}
?>