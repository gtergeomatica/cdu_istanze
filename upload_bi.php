<?php
session_start();
//questo file viene richiamato dal modal quando l'utente clicca sul bottone per caricare il bollo dell'istanza
// salva in $_SESSION lo username passato tramite il value dell'input hidden con name userBi nel modal serve per check su login
//$_SESSION['user'] = pg_escape_string($_POST['userBi']);
$estremi_bi = pg_escape_string($_POST['estremi_bi']);

include("root_connection.php");
//recupera id istanza da url
$id_istanza=pg_escape_string($_GET['idi']);

if(!$conn_isernia) {
    die('Connessione fallita !<br />');
} else {
	//check se bottone invio nel modal è stato cliccato
	if ( isset( $_FILES['fileToUploadBi'] ) ) {
		$original_file = pg_escape_string($_FILES["fileToUploadBi"]["name"]);
		//check se file è in pdf
		if ($_FILES['fileToUploadBi']['type'] == "application/pdf") {
			$source_file = pg_escape_string($_FILES['fileToUploadBi']['tmp_name']);
			$dest_dir = "/var/www/html/isernia_upload/bollo_istanza/";
			//$dest_file = $dest_dir . basename($_FILES["fileToUploadBi"]["name"]);
			$filename = hash('sha256', basename($original_file));
			$dest_file = $dest_dir . $filename . '.pdf';

			if (file_exists($dest_file)) {
				print "The file name already exists!!";
			}
			else {
				//sposta il file caricato nella cartella di destinazione
				move_uploaded_file( $source_file, $dest_file )
				or die ("Error!!");
				//check su eventuali errori
				if($_FILES['fileToUploadBi']['error'] === 0) {
					//query per verificare se il bollo era già stato caricato
					$query = "SELECT * from istanze.pagamento_bollo_ist where id_istanza_bi = $1;";
					$result = pg_prepare($conn_isernia, "myquery0", $query);
					$result = pg_execute($conn_isernia, "myquery0", array($id_istanza));
					while($r = pg_fetch_assoc($result)) {
						$istanza = $r['id_istanza_bi'];
					}
					//se non è stato caricato prima inserisce i dati
					if ($istanza == ''){
						$query = "INSERT into istanze.pagamento_bollo_ist (id_istanza_bi, file_bi, estremi_bi)  values($1, $2, $3);";
						$result1 = pg_prepare($conn_isernia, "myquery1", $query);
						$result1 = pg_execute($conn_isernia, "myquery1", array($id_istanza, $dest_file, $estremi_bi));
					}
					else{
						//altrimenti li aggiorna
						$query = "UPDATE istanze.pagamento_bollo_ist SET file_bi = $1, estremi_bi = $2 where id_istanza_bi = $3;";
						$result2 = pg_prepare($conn_isernia, "myquery2", $query);
						$result2 = pg_execute($conn_isernia, "myquery2", array($dest_file, $estremi_bi, $id_istanza));
					}
					//redirect alla dashboard
					header ("Location: dashboard.php#about");
				}else{
					$error_file = pg_escape_string($_FILES['fileToUploadBi']['error']);
					print "Si è verificato un errore nel caricamento del file: ".$original_file."<br/>";
					print "Codice Errore: ".$error_file."<br/>";
				}
			}
		}
		else {
			if ( $_FILES['fileToUploadBi']['type'] != "application/pdf") {
				print "Si è verificato un errore nel caricamento del file: ".$original_file."<br/>";
				print "Estensione del file non valida, il file deve essere in formato pdf!!"."<br/>";
			}
		}
	}
}
?>