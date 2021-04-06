<?php
session_start();
//questo file viene richiamato dal modal quando l'admin clicca sul bottone per caricare il cdu
// salva in $_SESSION lo username passato tramite il value dell'input hidden con name userCdu nel modal serve per check su login
$_SESSION['user'] = pg_escape_string($_POST['userCdu']);
//echo $_SESSION['user'] ."<br>";

include("root_connection.php");
//recupera id istanza da url
$id_istanza=pg_escape_string($_GET['idi']);

if(!$conn_isernia) {
    die('Connessione fallita !<br />');
} else {
	//check se bottone invio nel modal è stato cliccato
	if ( isset( $_FILES['fileToUploadCdu'] ) ) {
		$original_file = pg_escape_string($_FILES["fileToUploadCdu"]["name"]);
		//check se file è in pdf
		if ($_FILES['fileToUploadCdu']['type'] == "application/pdf") {
			$source_file = pg_escape_string($_FILES['fileToUploadCdu']['tmp_name']);
			$dest_dir = "/var/www/html/isernia_upload/cdu/";
			//$dest_file = $dest_dir . basename($_FILES["fileToUploadCdu"]["name"]);
			$filename = hash('sha256', basename($original_file));
			$dest_file = $dest_dir . $filename . '.pdf';

			if (file_exists($dest_file)) {
				print "The file name already exists!!";
			}
			else {
				move_uploaded_file( $source_file, $dest_file )
				or die ("Error!!");
				//check su eventuali errori
				if($_FILES['fileToUploadCdu']['error'] === 0) {
					$query = "UPDATE istanze.istanze SET file_cdu = $1 where id = $2;";
					$result2 = pg_prepare($conn_isernia, "myquery2", $query);
					$result2 = pg_execute($conn_isernia, "myquery2", array($dest_file, $id_istanza));
					//redirect alla dashboard
					header ("Location: dashboard.php#about");
				}else{
					$error_file = pg_escape_string($_FILES['fileToUploadCdu']['error']);
					print "Si è verificato un errore nel caricamento del file: ".$original_file."<br/>";
					print "Codice Errore: ".$error_file."<br/>";
				}

				
			}
		}
		else {
			if ( $_FILES['fileToUploadCdu']['type'] != "application/pdf") {
				print "Si è verificato un errore nel caricamento del file: ".$original_file."<br/>";
				print "Estensione del file non valida, il file deve essere in formato pdf!!"."<br/>";
			}
		}
	}
}
?>