<?php

//require('navbar.php');
include("root_connection.php");

$selectvalue=$_GET['svalue'];
//$foglio="0001";
$query_mappale = 'SELECT "Mappale" from particelle where "Fg" =:Fg';
$result = $conn_catasto->prepare($query_mappale);
//$result = $result->bindValue(":Fg", $foglio);
$result->execute([":Fg"=> $selectvalue]);

//$result->execute();
while ($row = $result->fetch()) {
    $num_mappale=$row['Mappale'];
	echo $num_mappale;
	echo "<option value='$num_mappale'>$num_mappale</option>";
}
?>