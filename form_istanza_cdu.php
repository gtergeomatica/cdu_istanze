<!DOCTYPE html>
<html lang="en">
<?php

$user_admin="comuneisernia";
//$gruppo = 'comuneisernia3_group';
$cliente = 'Comune di Isernia';
$user_id=$_GET['u'];
$user_idn=(int)$user_id;
$usr_login=$_GET['user'];

$list_mappali=array();
$list_fogli=array();

//require('navbar.php');
include("root_connection.php");

if(!$conn_catasto) {
    die('Connessione fallita !<br />');
} else {




/* while($r = pg_fetch_assoc($result)) {
	$check_user=-1;
	$mail_old=$r['usr_email'];
} */
?>

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="roberto" >


	<link rel="icon" href="favicon.ico"/>

    <title>Istanza CDU del <?php echo $cliente; ?></title>

    <?php
    
    require('req.php');
    ?>

</head>

<body id="page-top">
<section class="page-section bg-primary" id="about">
<div class="container">
<div class="row justify-content-center">
<div class="col-lg-8 text-center">

<!--img src="img/logo-postgis.png" style="width:25%;" alt=""-->
<i class="fa fa-3x fa-file wow bounceIn" data-wow-delay=".1s" style="color:white;"></i>
<hr class="light">


<?php
session_start();
if(isset($_POST['Submit2'])){
	//include("get_foglio_mappale.php");


echo "L'istnza è stata inviata.";
//print_r($list_mappali);

// check if name exist
/*$query = "SELECT nome, mail from jlx_user where usr_login ='".$username."';";
$result = pg_query($conn_lizmap, $query);*/
$query = "INSERT into istanze.istanze (doc_id, id_utente) select doc_id, id from utenti.utenti where id=$1;";
$result = pg_prepare($conn_isernia, "myquery0", $query);
$result = pg_execute($conn_isernia, "myquery0", array($user_idn));

$query = "SELECT max(id) as ids from istanze.istanze where id_utente=$1;";
$result = pg_prepare($conn_isernia, "myquery1", $query);
$result = pg_execute($conn_isernia, "myquery1", array($user_idn));
while($r = pg_fetch_assoc($result)) {
	$id_istanza=$r['ids'];
}

$query1 = "SELECT data, foglio, mappale from istanze.istanze_temp where id_utente=$1 and data > now() - interval '60 minutes' ";
    $result1 = pg_prepare($conn_isernia, "myquery2", $query1);
    $result1 = pg_execute($conn_isernia, "myquery2", array($user_idn));
    while($r = pg_fetch_assoc($result1)) {
        $query = "INSERT into istanze.dettagli_istanze (id_istanza, foglio, mappale) values($1, $2, $3);";
        $result = pg_prepare($conn_isernia, "myquery3", $query);
        $result = pg_execute($conn_isernia, "myquery3", array($id_istanza, $r["foglio"], $r["mappale"]));
    }
	
} else {
?>
<!--form id="defaultForm" method="post" class="form-horizontal"-->
<?php
$query_foglio = 'SELECT DISTINCT "Fg" from particelle';
$result = $conn_catasto->prepare($query_foglio);
//$result = $result->bindValue(":Fg", $foglio);
$result->execute();
?>
<select name="foglio" id="foglio">
<option value="">Selezionare un foglio...</option>
<?php
while ($row = $result->fetch()) {
    $num_fogli=$row['Fg'];
	echo "<option value='$num_fogli'>$num_fogli</option>";
}
?>
</select>
<select name="mappale" id="mappale"></select>
<input id="fgmp" type="button" name="fgmp" value="+" onclick="showmappale()"/>
<div id="num_map"></div>
<br>
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
  data-sidePagination="true" data-show-refresh="true" data-show-toggle="true" data-show-columns="true" data-toolbar="#toolbar2">
<thead>

 <tr>
            <th data-field="state" data-checkbox="true"></th>
			<th data-field="data" data-sortable="true" data-filter-control="select" data-visible="true">Data</th>
            <th data-field="foglio" data-sortable="true" data-filter-control="select" data-visible="true">Foglio</th>
            <th data-field="mappale" data-sortable="true" data-filter-control="select" data-visible="true">Mappale</th>
            <!--th data-field="mappale" data-sortable="true" data-filter-control="input" data-visible="true">Mappale</th>
            <th data-field="log_repository" data-sortable="true" data-filter-control="select" data-visible="true">Repository</th>
            <th data-field="log_project" data-sortable="true" data-filter-control="select" data-visible="true">Progetti</th-->

        </tr>
</thead>

</table>

</div>	
</div>	

<form id='login' action='form_istanza_cdu.php?u=<?php echo $user_id; ?>' method='post' accept-charset='UTF-8'>
<input type='hidden' name='submitted' id='submitted' value='1'/>

<div class="form-group">
    <!--h3>L'utente creato sarà automaticamente inserito nel Gruppo di <!--?php echo $cliente; ?></h3-->
    <!--select multiple class="form-control" id="gruppi" name="gruppi"-->
	<!--ul-->
<!--?php 
$query="SELECT g.id_aclgrp, g.name FROM jacl2_group g
JOIN jacl2_user_group ug ON ug.id_aclgrp=g.id_aclgrp
where ug.login='".$user_admin."';";
				
$result = pg_query($conn_lizmap, $query);
$i=0;
while($r = pg_fetch_assoc($result)) {
?-->	
	<!--br><input name="gruppi[]" type="checkbox" value="<!--?php echo $r['id_aclgrp'];?>" required><label--><!--?php echo $r['id_aclgrp'];?></label><!--/li-->
	<!--option--><!--?php echo $r['id_aclgrp'];?></option-->
<!--?php
	$i=$i+1;   
}
?-->
<!--/ul-->
<!--/select-->
</div>
<?php //echo $query;?>

<hr class="light">
<!--input type='submit' name='Submit' value='Submit' /-->

<div class="form-group">
<button id="btnsubmit2" type="submit" name='Submit2' class="btn btn-light btn-xl" disabled>Invia Istanza</button>
</div>
</form>





<?php

}
?>


</div>
</div>
</div>
</section>

<?php
require('footer.php');
require('req_bottom.php');
?>

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

<!--script>
/* var selected_option_value=$("#foglio option:selected").val();
console.log(selected_option_value) */
$(document).ready(function(){
      // everything here will be executed once index.html has finished loading, so at the start when the user is yet to do anything.
      $("#fgmp").click(showmappale()); //this translates to: "when the element with id='select1' changes its value execute load_new_content() function"
});
</script-->
<script>
	function showmappale(){
		var mappale_value=$("#mappale option:selected").val(); //get the value of the current selected option.
		var foglio_value=$("#foglio option:selected").val();

		$.post("get_foglio_mappale.php", {u: <?php echo $user_id?> , m: mappale_value, f: foglio_value},
			function(data){ //this will be executed once the `script_that_receives_value.php` ends its execution, `data` contains everything said script echoed.
				//data.split(",");
                //console.log(data[0]);
                //$("#num_map").append(data);
                $('#ist').bootstrapTable('refresh', {silent: true});
				//alert(data); //just to see what it returns
			}
		);
		$('#btnsubmit2').removeAttr('disabled');
	} 

</script>

</body>
<?php
}
?>
</html>
