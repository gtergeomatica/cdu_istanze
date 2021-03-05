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
					}
				}

				header ("Location: dashboard.php#about");
			}
		}
		else {
			if ( $_FILES['fileToUploadBc']['type'] != "application/pdf") {
				print "Error occured while uploading file : ".$_FILES['fileToUploadBc']['name']."<br/>";
				print "Invalid  file extension, should be pdf !!"."<br/>";
				print "Error Code : ".$_FILES['fileToUploadBc']['error']."<br/>";
			}
		}
	}
}
?>