<!DOCTYPE html>
<html lang="it">
<?php
$user_admin="comuneisernia";
$cliente = 'Comune di Isernia';
//salva nelle variabile id e username prese da url e salva username nella var $_SESSION per check su login
$user_id=pg_escape_string($_GET['u']);
$user_idn=(int)$user_id;
$usr_login=pg_escape_string($_GET['user']);
$_SESSION['user']=$usr_login;

//creo array vuoti per numero mappali e fogli selezionati
$list_mappali=array();
$list_fogli=array();

//richiama connessioni ai DB;
include("root_connection.php");

if(!$conn_catasto) {
    die('Connessione fallita !<br />');
} else {

?>

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="roberto" >


	<link rel="icon" href="favicon.ico"/>

    <title>Istanze del <?php echo $cliente; ?></title>

    <?php
    //ruchiama stili e CSS
    require('req.php');
    ?>

</head>

<body id="page-top">
<!-- Richiama navbar -->
<div id="navbar1">
<?php
require('navbar.php');
?>
</div>
<section class="page-section bg-primary" id="about">
<div class="container">
<div class="row justify-content-center">
<div class="col-lg-8 text-center">

<!--img src="img/logo-postgis.png" style="width:25%;" alt=""-->
<i class="fa fa-3x fa-file wow bounceIn" data-wow-delay=".1s" style="color:white;"></i>
<hr class="light">


<?php
session_start();
// se si clicca su tasto aggiungi istanza
if(isset($_POST['Submit2'])){
	//salva nelle variabili i dati inseriti dall'utente

    $tipo = pg_escape_string($_POST['tipo']);
    $ruolo = pg_escape_string($_POST['ruolo']);
    $motivo = pg_escape_string($_POST['motivo']);

    if ($motivo == 'Altro'){
		$motivo = pg_escape_string($_POST['motivotxt']);
	}

echo "L'istanza è stata aggiunta. Riceverai una mail con i dettagli per il pagamento.";
echo $prezzo;
echo '<br><br><a href="./dashboard.php#about" class="btn btn-light btn-xl"> Torna alla Dashboard </a>';


// query per inserire nuova istanza nel DB
$query = "INSERT into istanze.istanze (doc_id, id_utente, ruolo, motivo, tipo) 
            values((select doc_id from utenti.utenti where id=$1), (select id from utenti.utenti where id=$1), $2, $3, $4) ;";
$result = pg_prepare($conn_isernia, "myquery0", $query);
$result = pg_execute($conn_isernia, "myquery0", array($user_idn, $ruolo, $motivo, $tipo));

// query per selezionare id istanza appena aggiunta
/* $query = "SELECT max(id) as ids from istanze.istanze where id_utente=$1;";
$result = pg_prepare($conn_isernia, "myquery1", $query);
$result = pg_execute($conn_isernia, "myquery1", array($user_idn));
while($r = pg_fetch_assoc($result)) {
	$id_istanza=$r['ids'];
} */
$query = "SELECT id as ids, tipo from istanze.istanze i where id_utente = $1 and id= (select max(id) from  istanze.istanze)";
$result = pg_prepare($conn_isernia, "myquery1", $query);
$result = pg_execute($conn_isernia, "myquery1", array($user_idn));
while($r = pg_fetch_assoc($result)) {
	$id_istanza=$r['ids'];
    /* if ($r['tipo'] == 1){
        $query = "UPDATE istanze.istanze SET n_bolli = 1 where id = $1;";
        $result2 = pg_prepare($conn_isernia, "myquery8", $query);
        $result2 = pg_execute($conn_isernia, "myquery8", array($id_istanza));
    } */
}

// query per selezionare foglio e mappali dei terreni selezionati e insert nella tabella con i dettagli dell'istanza
$query1 = "SELECT data, foglio, mappale from istanze.istanze_temp where id_utente=$1 and data > now() - interval '60 minutes' ";
    $result1 = pg_prepare($conn_isernia, "myquery2", $query1);
    $result1 = pg_execute($conn_isernia, "myquery2", array($user_idn));
    $rows_num = pg_num_rows($result1);
    while($r = pg_fetch_assoc($result1)) {
        $query = "INSERT into istanze.dettagli_istanze (id_istanza, foglio, mappale) values($1, $2, $3);";
        $result = pg_prepare($conn_isernia, "myquery3", $query);
        $result = pg_execute($conn_isernia, "myquery3", array($id_istanza, $r["foglio"], $r["mappale"]));
    }

// query per selezionare il prezzo dei diritti istruttori in funzione del numero di terreni selezionati
$queryprezzo = "SELECT prezzo from istanze.listino where id=$1";
$resultprezzo = pg_prepare($conn_isernia, "myquery6", $queryprezzo);
$resultprezzo = pg_execute($conn_isernia, "myquery6", array($rows_num));
while($r = pg_fetch_assoc($resultprezzo)) {
    $prezzo = $r['prezzo'];
}

// query per eliminare dettagli dei terreni selezionati
$query0 = "DELETE from istanze.istanze_temp where id_utente=$1";
$result0 = pg_prepare($conn_isernia, "myquery5", $query0);
$result0 = pg_execute($conn_isernia, "myquery5", array($user_idn));

// query per recuperare i dati dell'utente
$query = "SELECT * FROM utenti.utenti where id=$1";
$result = pg_prepare($conn_isernia, "myquery7", $query);
$result = pg_execute($conn_isernia, "myquery7", array($user_idn));
while($r = pg_fetch_assoc($result)) {
        //$rows[] = $r;
        $fullname=$r["firstname"]. " " .$r["lastname"];
        $user_email=$r["usr_email"];
}

//richiama file con indirizzi mail
require('mail_address.php');
if ($tipo == 1){
//mail all'utente
$testo = "

Egr. " . $fullname. ",\n 
questa mail e' stata generata automaticamente in quanto ha appena aggiunto un'istanza di CDU dal sistema online del Comune di Isernia.\n
In particolare, il CDU è stato richiesto per n° " . $rows_num . " mappali. Di seguito sono riportati i dettagli di pagamento:\n
    - Diritti istruttori da versare: " . $prezzo ." euro \n
    - 1 Marca da bollo da 16,00 euro per l'Istanza \n
    - 1 Marca da bollo da 16,00 euro per il CDU \n

I Diritti Istruttori sono da versare su Conto Corrente Postale n. 14459861 intestato a COMUNE DI ISERNIA Servizio Tesoreria con CAUSALE: Capitolo n. 377 - Diritti di Istruttoria. \n
Le marche da bollo da 16,00 euro possono essere assolte tramite Modello F23 o acquistate presso un rivenditore.\n

Una volta effettuati i pagamenti, dovrà caricare sulla sua dashboard, in corrispondenza dell'istanza presentata, i seguenti documenti:\n
    - la scansione del bollettino pagato e i riferimenti del versamento per i diritti istruttori\n
    - la scansione della marca da bollo e il numero identificativo di 14 cifre per l'istanza\n
    - la scansione della marca da bollo e il numero identificativo di 14 cifre per il CDU *\n

Una volta caricati i dati relativi al pagamento potrà inviare l'istanza al Comune che provvederà a compilare il CDU richiesto.

* Si ricorda che nel caso in cui il CDU sia stato richiesto per Successione Ereditaria o Esproprio, il Bollo per il CDU non è dovuto.
    
Se riceve questo messaggio per errore, la preghiamo di distruggerlo e di comunicarlo immediatamente all'amministratore del sistema rispondendo a questa mail.\n
In caso di problemi o richieste non esiti a contattare l'amministratore del sistema al seguente indirizzo cdu@comune.isernia.it.\n \n
            
Cordiali saluti, \n
L'amministratore del sistema.
        
-- 
Comune di Isernia
Piazza Marconi, 3 - 86170 Isernia (IS)
E-mail: cdu@comune.isernia.it

Servizio basato su GisHosting di Gter srl\n

";
$oggetto ="Aggiunta istanza di CDU online del Comune di Isernia";
}else{
$testo = "

Egr. " . $fullname. ",\n 
questa mail e' stata generata automaticamente in quanto ha appena aggiunto un'istanza di Visura dal sistema online del Comune di Isernia.\n
In particolare, la visura è stata richiesta per n° " . $rows_num . " mappali. Di seguito sono riportati i dettagli di pagamento:\n
    - Diritti istruttori da versare: " . $prezzo ." euro \n

I Diritti Istruttori sono da versare su Conto Corrente Postale n. 14459861 intestato a COMUNE DI ISERNIA Servizio Tesoreria con CAUSALE: Capitolo n. 377 - Diritti di Istruttoria. \n

Una volta effettuati i pagamenti, dovrà caricare sulla sua dashboard, in corrispondenza dell'istanza presentata, la scansione del bollettino pagato e i riferimenti del versamento.
Una volta caricati i dati relativi al pagamento potrà inviare l'istanza al Comune che provvederà a compilare la visura richiesta.
    
Se riceve questo messaggio per errore, la preghiamo di distruggerlo e di comunicarlo immediatamente all'amministratore del sistema rispondendo a questa mail.\n
In caso di problemi o richieste non esiti a contattare l'amministratore del sistema al seguente indirizzo cdu@comune.isernia.it.\n \n
            
Cordiali saluti, \n
L'amministratore del sistema.
        
-- 
Comune di Isernia
Piazza Marconi, 3 - 86170 Isernia (IS)
E-mail: cdu@comune.isernia.it

Servizio basato su GisHosting di Gter srl\n

";
$oggetto ="Aggiunta istanza di Visura online del Comune di Isernia";
}

    $headers = $nostro_recapito .
    "Reply-To: " .$loro_recapito. "\r\n" .
    "Content-Type: text/plain; charset=utf-8" . "\r\n";
	"Content-Transfer-Encoding: base64" . "\r\n";
	mail ("$user_email", "$oggetto", "$testo","$headers");
	
} else {
    //se non viene aggiunta l'istanza vengono rimossi dettagli dei terreni selezionati più di un'ora fa
    $query0 = "DELETE from istanze.istanze_temp where data < now() - interval '60 minutes' ";
    $result0 = pg_prepare($conn_isernia, "myquery4", $query0);
    $result0 = pg_execute($conn_isernia, "myquery4", array());
?>
<!--form id="defaultForm" method="post" class="form-horizontal"-->
<?php
//query per popolare il menù di selezione dei fogli parte 1
$query_foglio = 'SELECT DISTINCT "Fg" from particelle';
$result = $conn_catasto->prepare($query_foglio);
//$result = $result->bindValue(":Fg", $foglio);
$result->execute();
?>
<select class="form-control form-control-sm" style="display: inline; width: auto;" name="foglio" id="foglio">
<option value="">Selezionare un foglio...</option>
<?php
//query per popolare il menù di selezione dei fogli parte 2
while ($row = $result->fetch()) {
    $num_fogli=$row['Fg'];
	echo "<option value='$num_fogli'>$num_fogli</option>";
}
?>
</select>
<!-- Menù per selezione del mappale in funzione del foglio selezionato richiama -->
<select class="form-control form-control-sm" style="display: inline; width: auto;" name="mappale" id="mappale"></select>
<!-- pulsante per aggiungere terreno selezionato richiama funzione showmappale() -->
<input id="fgmp" type="button" name="fgmp" value="+" onclick="showmappale()"/>
<div id="num_map"><small id="num_mapHelp" class="form-text text-muted">E' possibile selezionare un massimo di 20 mappali</small></div>
<br>
<!-- Tabella che si popola dinamicamente quando l'utente aggiunge un mappale richiama griglia_istanze.php per i dati -->
<div>
    <h2> <i class="fas fa-file-alt" style="color:white;"></i> Dettagli Istanza CDU di <?php echo $usr_login; ?></h2>
	<!--div id="toolbar2">
            <select class="form-control">
                <option value="">Esporta i dati visualizzati</option>
                <option value="all">Esporta tutto (lento)</option>
                <option value="selected">Esporta solo selezionati</option>
            </select>
        </div-->
	<div style="overflow-x:auto;">
    <table style="background-color:white;" id="ist" class="table-hover" data-toggle="table" data-filter-control="true" 
  data-show-search-clear-button="true" data-page-size="50" 
  data-url="griglia_istanze.php?u=<?php echo $user_id; ?>" 
	data-show-export="false" data-search="true" data-click-to-select="true" data-pagination="true" 
  data-sidePagination="true" data-show-refresh="true" data-show-toggle="true" data-show-columns="true" data-toolbar="#toolbar2" data-locale="it-IT">
<thead>

 <tr>
            <!--th data-field="state" data-checkbox="true"></th-->
            <th data-field="id" data-sortable="true" data-formatter="nameFormatterEdit" data-visible="true" >Rimuovi</th>
			<th data-field="data" data-sortable="true" data-filter-control="input" data-visible="true">Data</th>
            <th data-field="foglio" data-sortable="true" data-filter-control="input" data-visible="true">Foglio</th>
            <th data-field="mappale" data-sortable="true" data-filter-control="input" data-visible="true">Mappale</th>
            <!--th data-field="mappale" data-sortable="true" data-filter-control="input" data-visible="true">Mappale</th>
            <th data-field="log_repository" data-sortable="true" data-filter-control="select" data-visible="true">Repository</th>
            <th data-field="log_project" data-sortable="true" data-filter-control="select" data-visible="true">Progetti</th-->

        </tr>
</thead>

</table>

</div>	
</div>
<div id="maxrow">
</div>
<hr class="light">
<!--form per inserire info necessarie all'aggiunta dell'istanza-->
<form id='istanza' action='form_istanza_cdu.php?u=<?php echo $user_id; ?>&user=<?php echo $usr_login; ?>' method='post' accept-charset='UTF-8'>
<input type='hidden' name='submitted' id='submitted' value='1'/>
<div class="form-group">
    <label>Si richiede il documento di:*</label><br>
    <div class="form-check form-check-inline">
    <input class="form-check-input tipo1" type="radio" name="tipo" id="CDU" value=1 required>
    <label class="form-check-label" for="CDU">Certificato di Destinazione Urbanistica</label>
    </div>
    <div class="form-check form-check-inline">
    <input class="form-check-input tipo1" type="radio" name="tipo" id="visura" value=2>
    <label class="form-check-label" for="visura">Visura</label>
    </div>
    <div class="help-block with-errors"></div>

</div>
<div class="form-group">
    <label>Il presente documento è richiesto in qualità di*</label>
    <input type="text" class="form-control" data-error="Il campo è obbligatorio, non può essere lasciato vuoto" name="ruolo" required>
    <div class="help-block with-errors"></div>
</div>
<div class="form-group">
    <label>Il presente documento è richiesto per uso:*</label><br>
    <div class="form-check form-check-inline">
    <input class="form-check-input motivo1" type="radio" name="motivo" id="inlineRadio1" value="Atto notarile" required>
    <label class="form-check-label" for="inlineRadio1">Stipula atto notarile</label>
    </div>
    <div class="form-check form-check-inline">
    <input class="form-check-input motivo1" type="radio" name="motivo" id="inlineRadio2" value="Successione ereditaria">
    <label class="form-check-label" for="inlineRadio2">Successione ereditaria</label>
    </div>
    <div class="form-check form-check-inline">
    <input class="form-check-input motivo1" type="radio" name="motivo" id="inlineRadio4" value="Esproprio">
    <label class="form-check-label" for="inlineRadio4">Esproprio</label>
    </div>
    <div class="form-check form-check-inline">
    <!--input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio3" value="Altro" onclick="if (this.checked){ document.getElementById('altrotxt').style.display = 'block';}else{document.getElementById('altrotxt').style.display = 'none';}"-->
    <input class="form-check-input motivo1" type="radio" name="motivo" id="inlineRadio3" value="Altro">
    <label class="form-check-label" for="inlineRadio3">Altro</label>
    <input style="display: none; margin-left:10px;" placeholder='es. sgravi fiscali, ecc.' type="text" id="altrotxt" class="form-control" data-error="Il campo è obbligatorio, non può essere lasciato vuoto" name="motivotxt" style="margin-left: 10px;">
    </div>
    <div class="help-block with-errors"></div>

</div>

<hr class="light">
<!--input type='submit' name='Submit' value='Submit' /-->

<div class="form-group mytip" id="mytip">
<button id="btnsubmit2" type="submit" name='Submit2' class="btn btn-light btn-xl" disabled>Aggiungi Istanza</button>
<span class="tooltiptext">Seleziona almeno un mappale</span>
</div>
</form>





<?php

}
?>


</div>
</div>
</div>
</section>
<!--richiama contatti e librerie JS-->
<?php
require('footer.php');
require('req_bottom.php');
?>
<!-- script per attivare boostrap validator su dati inseriti nel form -->
<script type="text/javascript">
$(document).ready(function() {
// Generate a simple captcha

$('#istanza').validator();
});
</script>
<!-- <script>
    if ($('#ist').bootstrapTable('getOptions').totalRows == 0){
        $('#btnsubmit2').prop( "disabled", true );
    }
</script> -->

<!-- script per popolare il menù mappale in funzione del foglio selezionato richiama get_foglio.php -->
<script>
/* var selected_option_value=$("#foglio option:selected").val();
console.log(selected_option_value) */

$(document).ready(function($) {
  var list_foglio_id = 'foglio'; //first select list ID
  var list_mappale_id = 'mappale'; //second select list ID
  var initial_target_html = '<option value="">Selezionare un mappale...</option>'; //Initial prompt for target select
 
  $('#'+list_mappale_id).html(initial_target_html); //Give the target select the prompt option
 
  $('#'+list_foglio_id).change(function(e) {
    //Grab the chosen value on first select list change
    selectvalue = $(this).val();
	console.log(selectvalue);
 
    //Display 'loading' status in the target select list
    $('#'+list_mappale_id).html('<option value="">Loading...</option>');
 
    if (selectvalue == "") {
        //Display initial prompt in target select if blank value selected
       $('#'+list_mappale_id).html(initial_target_html);
    } else {
      //Make AJAX request, using the selected value as the GET
      $.ajax({url: 'get_foglio.php?svalue='+selectvalue,
             success: function(output) {
                //alert(output);
                $('#'+list_mappale_id).html(output);
            },
          error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status + " "+ thrownError);
          }});
        }
    });
});

</script>

<!-- script con funziona showmappale che salva foglio e mappale selezionati e richima get_foglio_mappale.php -->
<script>
	function showmappale(){
		var mappale_value=$("#mappale option:selected").val(); //get the value of the current selected option.
		var foglio_value=$("#foglio option:selected").val();

		$.post("get_foglio_mappale.php", {u: <?php echo $user_id?> , m: mappale_value, f: foglio_value},
			function(data){ //this will be executed once the `script_that_receives_value.php` ends its execution, `data` contains everything said script echoed.
				//data.split(",");
                //console.log($('#ist').bootstrapTable('getData').length);
                //$("#num_map").append(data);
                $('#ist').bootstrapTable('refresh', {silent: true});
                /* if ($('#btnsubmit2').prop( "disabled")){
                    $('#btnsubmit2').prop( "disabled", false );
                } */
				//alert(data); //just to see what it returns
			}
		);
		//$('#btnsubmit2').removeAttr('disabled');
	} 

</script>

<!-- script per diabilitare i menù e il tasto più se sono già stati selezionati 20 terreni e attivare il bottone di aggiungi istanza se ne è stato selezionato almeno 1-->
<script>
    //$('#ist').on('load-success.bs.table', function (data){
        $('#ist').bootstrapTable({
            onLoadSuccess: function(data){
                console.log($('#ist').bootstrapTable('getData').length);
                if ($('#ist').bootstrapTable('getData').length >= 20){
                    //console.log('ciao2');
                    $('#fgmp').prop( "disabled", true );
                    $('#foglio').prop( "disabled", true );
                    $('#mappale').prop( "disabled", true );
                    $("#maxrow").append("ATTENZIONE: hai raggiunto il numero massimo di particelle. Se necessiti di ulteriori particelle devi generare una nuova istanza.");
                }else if ($('#ist').bootstrapTable('getData').length >= 1){
                    $('#btnsubmit2').prop( "disabled", false );
                    $('#mytip').removeClass('mytip');
                }
            }
        });
</script>

<!-- <script>
    //$('#ist').on('load-success.bs.table', function (data){
        $('#ist').bootstrapTable({
            onLoadSuccess: function(data){
                console.log($('#ist').bootstrapTable('getData').length);
                console.log($('#ist').bootstrapTable('getOptions').totalRows);
                if ($('#ist').bootstrapTable('getOptions').totalRows < 1){
                    $('#btnsubmit2').prop( "disabled", true );
                }else{
                    $('#btnsubmit2').prop( "disabled", false );
                }
            }
        });
                
</script> -->

<!-- script con funzioni richiamate dalla tabella -->
<script>
// funzione per rimuovere un terreno selezionato richiama remove_temp.php
function nameFormatterEdit(value, row) {
	//var test_id= row.id;
	return' <a type="button" class="btn btn-info" href="remove_temp.php?idu='+row.id_utente+'&f='+row.foglio+'&m='+row.mappale+'&user=<?php echo $usr_login; ?>"><i class="fas fa-trash-alt"></i></a>';
}
</script>

<!-- script per far comparire area di testo in caso si selezioni l'opzione altro come motivo della richiesta -->
<script>
    $(".motivo1").change(function () {
        //check if its checked. If checked move inside and check for others value
        if (this.checked && this.value === "Altro") {
            //add a text box next to it
            $("#altrotxt").show();
            $("#altrotxt").prop( "required", true );
        } else {
            //remove if unchecked
            $("#altrotxt").hide();
            $("#altrotxt").prop( "required", false );
        }
    });
</script>
</body>
<?php
}
?>
</html>
