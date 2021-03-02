<?php
session_start();
/* echo $_SESSION['user'] ."<br>";
echo $_POST['user']."<br>"; */
$_SESSION['user'] = pg_escape_string($_POST['userCdu']);
//echo $_SESSION['user'] ."<br>";

include("root_connection.php");

$id_istanza=$_GET['idi'];

if(!$conn_isernia) {
    die('Connessione fallita !<br />');
} else {

	if ( isset( $_FILES['fileToUploadCdu'] ) ) {
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
				if($_FILES['fileToUploadCdu']['error'] == 0) {
					$query = "UPDATE istanze.istanze SET file_cdu = $1 where id = $2;";
					$result2 = pg_prepare($conn_isernia, "myquery2", $query);
					$result2 = pg_execute($conn_isernia, "myquery2", array($dest_file, $id_istanza));
					
				}

				header ("Location: dashboard.php#about");
			}
		}
		else {
			if ( $_FILES['fileToUploadCdu']['type'] != "application/pdf") {
				print "Error occured while uploading file : ".$_FILES['fileToUploadCdu']['name']."<br/>";
				print "Invalid  file extension, should be pdf !!"."<br/>";
				print "Error Code : ".$_FILES['fileToUploadCdu']['error']."<br/>";
			}
		}
	}
}
?>