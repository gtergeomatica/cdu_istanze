<?php
include("root_connection.php");
// get the q parameter from URL
$u = pg_escape_string($_POST['u']);
$un = (int)$u;
$m = pg_escape_string($_POST['m']);
$f = pg_escape_string($_POST['f']);

//$list_mappali=array();
//$list_fogli=array();
// Output "no suggestion" if no hint was found or output correct values
if ($m !== ""){

    $query0 = "DELETE from istanze.istanze_temp where id_utente=$1 and data < now() - interval '60 minutes' ";
    $result0 = pg_prepare($conn_isernia, "myquery0", $query0);
    $result0 = pg_execute($conn_isernia, "myquery0", array($un));

    $query = "INSERT into istanze.istanze_temp (id_utente, foglio, mappale) values ($1, $2, $3)";
    $result = pg_prepare($conn_isernia, "myquery1", $query);
    $result = pg_execute($conn_isernia, "myquery1", array($un, $f, $m));

    /* $query1 = "SELECT foglio, mappale from istanze.istanze_temp where id_utente=$1";
    $result1 = pg_prepare($conn_isernia, "myquery2", $query1);
    $result1 = pg_execute($conn_isernia, "myquery2", array($un));
    while($r = pg_fetch_assoc($result1)) {
        echo "<span>Foglio: " .$r["foglio"]. " - Mappale: " .$r["mappale"]. "</span><br>";
    } */
}
?>