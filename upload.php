<?php
session_start();
//questo file viene richiamato dal modal quando l'utente clicca sul bottone per caricare i diritti istruttori
// salva in $_SESSION lo username passato tramite il value dell'input hidden con name user nel modal serve per check su login
//$_SESSION['user'] = pg_escape_string($_POST['user']);
$estremi_s = pg_escape_string($_POST['estremi_s']);

include("root_connection.php");
//recupera id istanza da url
$id_istanza=pg_escape_string($_GET['idi']);

if(!$conn_isernia) {
    die('Connessione fallita !<br />');
} else {
	//check se bottone invio nel modal è stato cliccato
	if ( isset( $_FILES['fileToUpload'] ) ) {
		$original_file = pg_escape_string($_FILES["fileToUpload"]["name"]);
		//check se file è in pdf
		if ($_FILES['fileToUpload']['type'] == "application/pdf") {
			$source_file = pg_escape_string($_FILES['fileToUpload']['tmp_name']);
			$dest_dir = "/var/www/html/isernia_upload/segreteria/";
			//$dest_file = $dest_dir . basename($_FILES["fileToUpload"]["name"]);
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
				if($_FILES['fileToUpload']['error'] === 0) {
					//query per verificare se il file era già stato caricato
					$query = "SELECT * from istanze.pagamento_segreteria where id_istanza_s = $1;";
					$result = pg_prepare($conn_isernia, "myquery0", $query);
					$result = pg_execute($conn_isernia, "myquery0", array($id_istanza));
					while($r = pg_fetch_assoc($result)) {
						$istanza = $r['id_istanza_s'];
					}
					//se non è stato caricato prima inserisce i dati
					if ($istanza == ''){
						$query = "INSERT into istanze.pagamento_segreteria (id_istanza_s, file_s, estremi_s)  values($1, $2, $3);";
						$result1 = pg_prepare($conn_isernia, "myquery1", $query);
						$result1 = pg_execute($conn_isernia, "myquery1", array($id_istanza, $dest_file, $estremi_s));
					}
					else{
						//altrimenti li aggiorna
						$query = "UPDATE istanze.pagamento_segreteria SET file_s = $1, estremi_s = $2  where id_istanza_s = $3;";
						$result2 = pg_prepare($conn_isernia, "myquery2", $query);
						$result2 = pg_execute($conn_isernia, "myquery2", array($dest_file, $estremi_s, $id_istanza));
					}
					//redirect alla dashboard
					header ("Location: dashboard.php#about");
				}else{
					$error_file = pg_escape_string($_FILES['fileToUpload']['error']);
					print "Si è verificato un errore nel caricamento del file: ".$original_file."<br/>";
					print "Codice Errore: ".$error_file."<br/>";
				}
			}
		}
		else {
			if ( $_FILES['fileToUpload']['type'] != "application/pdf") {
				print "Si è verificato un errore nel caricamento del file: ".$original_file."<br/>";
				print "Estensione del file non valida, il file deve essere in formato pdf!!"."<br/>";
			}
		}
	}
}
?>