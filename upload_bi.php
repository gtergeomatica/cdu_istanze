<?php
session_start();
/* echo $_SESSION['user'] ."<br>";
echo $_POST['user']."<br>"; */
$_SESSION['user'] = pg_escape_string($_POST['userBi']);
//echo $_SESSION['user'] ."<br>";

include("root_connection.php");

$id_istanza=$_GET['idi'];

if(!$conn_isernia) {
    die('Connessione fallita !<br />');
} else {

	if ( isset( $_FILES['fileToUploadBi'] ) ) {
		if ($_FILES['fileToUploadBi']['type'] == "application/pdf") {
			$source_file = $_FILES['fileToUploadBi']['tmp_name'];
			$dest_dir = "/var/www/html/isernia_upload/bollo_istanza/";
			$dest_file = $dest_dir . basename($_FILES["fileToUploadBi"]["name"]);

			if (file_exists($dest_file)) {
				print "The file name already exists!!";
			}
			else {
				move_uploaded_file( $source_file, $dest_file )
				or die ("Error!!");
				if($_FILES['fileToUploadBi']['error'] == 0) {
					//$query = "SELECT exists (SELECT * from istanze.pagamento_segreteria where id_istanza_s = $1);";
					$query = "SELECT * from istanze.pagamento_bollo_ist where id_istanza_bi = $1;";
					$result = pg_prepare($conn_isernia, "myquery0", $query);
					$result = pg_execute($conn_isernia, "myquery0", array($id_istanza));
					while($r = pg_fetch_assoc($result)) {
						$istanza = $r['id_istanza_bi'];
					}
					if ($istanza == ''){
						$query = "INSERT into istanze.pagamento_bollo_ist (id_istanza_bi, file_bi)  values($1, $2);";
						$result1 = pg_prepare($conn_isernia, "myquery1", $query);
						$result1 = pg_execute($conn_isernia, "myquery1", array($id_istanza, $dest_file));
					}
					else{
						$query = "UPDATE istanze.pagamento_bollo_ist SET file_bi = $1 where id_istanza_bi = $2;";
						$result2 = pg_prepare($conn_isernia, "myquery2", $query);
						$result2 = pg_execute($conn_isernia, "myquery2", array($dest_file, $id_istanza));
					}
					header ("Location: dashboard.php#about");
				}else{
					print "Si è verificato un errore nel caricamento del file: ".$_FILES['fileToUploadBi']['name']."<br/>";
					print "Codice Errore: ".$_FILES['fileToUploadBi']['error']."<br/>";
				}
			}
		}
		else {
			if ( $_FILES['fileToUploadBi']['type'] != "application/pdf") {
				print "Si è verificato un errore nel caricamento del file: ".$_FILES['fileToUploadBi']['name']."<br/>";
				print "Estensione del file non valida, il file deve essere in formato pdf!!"."<br/>";
				print "Codice Errore: ".$_FILES['fileToUploadBi']['error']."<br/>";
			}
		}
	}
}
?>