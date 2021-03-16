<?php
include("root_connection.php");
// salva nelle variabili l'id utente, il foglio e il mappale selezionati in form_istanza_cdu.php
$u = pg_escape_string($_POST['u']);
$un = (int)$u;
$m = pg_escape_string($_POST['m']);
$f = pg_escape_string($_POST['f']);

//$list_mappali=array();
//$list_fogli=array();
// se il mappale è selezionato elimina i terreni selezionati più di un'ora fa e aggiunge quelli nuovi
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