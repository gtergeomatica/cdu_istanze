<?php
session_start();
//questo file viene richiamato dal modal quando l'admin clicca sul bottone per caricare il cdu
// salva in $_SESSION lo username passato tramite il value dell'input hidden con name userCdu nel modal serve per check su login
$_SESSION['user'] = pg_escape_string($_POST['userCdu']);
//echo $_SESSION['user'] ."<br>";

include("root_connection.php");
//recupera id istanza da url
$id_istanza=$_GET['idi'];

if(!$conn_isernia) {
    die('Connessione fallita !<br />');
} else {
	//check se bottone invio nel modal è stato cliccato
	if ( isset( $_FILES['fileToUploadCdu'] ) ) {
		//check se file è in pdf
		if ($_FILES['fileToUploadCdu']['type'] == "application/pdf") {
			$source_file = $_FILES['fileToUploadCdu']['tmp_name'];
			$dest_dir = "/var/www/html/isernia_upload/cdu/";
			$dest_file = $dest_dir . basename($_FILES["fileToUploadCdu"]["name"]);

			if (file_exists($dest_file)) {
				print "The file name already exists!!";
			}
			else {
				move_uploaded_file( $source_file, $dest_file )
				or die ("Error!!");
				//check su eventuali errori
				if($_FILES['fileToUploadCdu']['error'] == 0) {
					$query = "UPDATE istanze.istanze SET file_cdu = $1 where id = $2;";
					$result2 = pg_prepare($conn_isernia, "myquery2", $query);
					$result2 = pg_execute($conn_isernia, "myquery2", array($dest_file, $id_istanza));
					//redirect alla dashboard
					header ("Location: dashboard.php#about");
				}else{
					print "Si è verificato un errore nel caricamento del file: ".$_FILES['fileToUploadCdu']['name']."<br/>";
					print "Codice Errore: ".$_FILES['fileToUploadCdu']['error']."<br/>";
				}

				
			}
		}
		else {
			if ( $_FILES['fileToUploadCdu']['type'] != "application/pdf") {
				print "Si è verificato un errore nel caricamento del file: ".$_FILES['fileToUploadCdu']['name']."<br/>";
				print "Estensione del file non valida, il file deve essere in formato pdf!!"."<br/>";
				print "Codice Errore: ".$_FILES['fileToUploadCdu']['error']."<br/>";
			}
		}
	}
}
?>