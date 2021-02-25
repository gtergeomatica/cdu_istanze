<!DOCTYPE html>
<html lang="en">
<?php
session_start();
$user_admin="comuneisernia";
//$gruppo = 'comuneisernia3_group';
$cliente = 'Comune di Isernia';
$idu = $_GET['u'];

//require('navbar.php');
include("root_connection.php");
?>

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="roberto" >


	<link rel="icon" href="favicon.ico"/>

    <title>Iscrizione utenti esterni del <?php echo $cliente; ?></title>

    <?php
    
    require('req.php');
    ?>
   



</head>

<body id="page-top">

<!--header class="masthead">
            <div class="container h-100">
                <div class="row h-100 align-items-center justify-content-center text-center">
                    <div class="col-lg-10 align-self-end">
						<h1 class="text-uppercase text-white font-weight-bold">Iscrizione al sistema Istanze CDU del <?php echo $cliente; ?></h1>
                        <hr class="divider my-4" />
					</div>
                    <div class="col-lg-8 align-self-baseline">
                        <a class="btn btn-primary btn-xl js-scroll-trigger" href="#about">Completa il form</a>
                    </div>
                </div>
            </div>
</header-->
<!--header class="masthead">
        <div class="header-content">
            <div class="header-content-inner">
                <h1>Iscrizione utenti esterni del <!--?php echo $cliente; ?></h1>
				<!--h3>Admin: <--?php echo $user_admin;?> </h3-->
				<!--hr> 
				
             	<a href="#about" class="btn btn-default btn-xl page-scroll"> Completa il form </a>
            </div>
        </div>
</header-->


<section class="page-section bg-primary" id="about">
<div class="container">
<div class="row justify-content-center">
<div class="col-lg-8 text-center">

<!--img src="img/logo-postgis.png" style="width:25%;" alt=""-->
<i class="fa fa-3x fa-user-edit wow bounceIn" data-wow-delay=".1s" style="color:white;"></i>
<hr class="light">


<?php

if(isset($_POST['Submit'])){

	$query = "SELECT * from utenti.utenti where id=$1;";
	$result = pg_prepare($conn_isernia, "myquery0", $query);
	$result = pg_execute($conn_isernia, "myquery0", array($idu));
	while($r = pg_fetch_assoc($result)) {
		$maildb = $r["usr_email"];
		$nomedb = $r["firstname"];
		$cognomedb = $r["lastname"];
		$cfdb = $r["cf"];
		$docdb = $r["doc_id"];
		$streetdb = $r["street"];
		$capdb = $r["postcode"];
		$citydb = $r["city"];
		$teldb = $r["phonenumber"];
		$affildb = $r["organization"];
	}

    //echo $password2;
    //exit;
	if (empty($_POST['mail'])){
		$mail = $maildb;
	}else{
		$mail = pg_escape_string($_POST['mail']);
	}

	if (empty($_POST['name'])){
		$name = $nomedb;
	}else{
		$name = pg_escape_string($_POST['name']);
	}

	if (empty($_POST['surname'])){
		$surname = $cognomedb;
	}else{
		$surname = pg_escape_string($_POST['surname']);
	}

	if (empty($_POST['codfisc'])){
		$codfisc = $cfdb;
	}else{
		$codfisc = pg_escape_string($_POST['codfisc']);
	}

	if (empty($_POST['docid'])){
		$docid = $docdb;
	}else{
		$docid = pg_escape_string($_POST['docid']);
	}

	if (empty($_POST['street'])){
		$street = $streetdb;
	}else{
		$street = pg_escape_string($_POST['street']);
	}

	if (empty($_POST['cap'])){
		$cap = $capdb;
	}else{
		$cap = pg_escape_string($_POST['cap']);
	}

	if (empty($_POST['city'])){
		$city = $citydb;
	}else{
		$city = pg_escape_string($_POST['city']);
	}

	if (empty($_POST['tel'])){
		$tel = $teldb;
	}else{
		$tel = pg_escape_string($_POST['tel']);
	}

	if (empty($_POST['affil'])){
		$affil = $affildb;
	}else{
		$affil = pg_escape_string($_POST['affil']);
	}

$check_user=1;
// check if name exist
/*$query = "SELECT nome, mail from jlx_user where usr_login ='".$username."';";
$result = pg_query($conn_lizmap, $query);*/
	

	echo "I dati sono stati modificati correttamente.<br>";
	
	echo '<br><a href="./dashboard.php#about" class="btn btn-light btn-xl"> Torna alla dashboard </a>';

	//$result = pg_query($conn2, $query);

	//pg_close($conn2);


	// creo l'utente lizmap
	/* $query_lizmap = "INSERT INTO jlx_user(
				usr_login, usr_password, usr_email, firstname, lastname, organization, 
				phonenumber, street,  comment)
		VALUES ('$username','$password2', '$mail', '$name', '$surname', '$affil', 
				'$tel', '$address', '$note');";

	//echo $query_lizmap;
	$result = pg_query($conn_lizmap, $query_lizmap); */

	// creo l'utente lizmap
	$query_user = "UPDATE utenti.utenti SET
		usr_email = $1, firstname = $2, lastname = $3, cf = $4, doc_id = $5, street = $6, postcode = $7, city = $8, phonenumber = $9, organization = $10
		where id = $11;";

	//echo $query_lizmap;
	$result = pg_prepare($conn_isernia, "myquery1", $query_user);
	$result = pg_execute($conn_isernia, "myquery1", array($mail, $name, $surname, $codfisc, $docid, $street, $cap, $city, $tel, $affil, $idu));

	
} else {
?>
<!--form id="defaultForm" method="post" class="form-horizontal"-->




<form id='login' action='modifica_dati.php?u=<?php echo $idu; ?>#about' method='post' accept-charset='UTF-8'>
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

<div class="form-group">
<label>E-mail</label>
<input type="email" class="form-control" name="mail">
<div class="help-block with-errors"></div>
</div>

<div class="form-group">
<label>Nome</label>
<input type="text" class="form-control" name="name">
<div class="help-block with-errors"></div>
</div>

<div class="form-group">
<label>Cognome</label>
<input type="text" class="form-control" name="surname">
<div class="help-block with-errors"></div>
</div>

<div class="form-group">
<label>Codice Fiscale</label>
<input type="text" class="form-control" name="codfisc" maxlength="16">
<div class="help-block with-errors"></div>
</div>

<div class="form-group">
<label>Numero Documento di Identità</label>
<input type="text" class="form-control" name="docid">
<div class="help-block with-errors"></div>
</div>

<div class="form-group">
<label>Via e civico</label>
<input type="text" class="form-control" name="street">
<div class="help-block with-errors"></div>
</div>

<div class="form-group">
<label>Codice di Avviamento Postale</label>
<input type="text" class="form-control" name="cap">
<div class="help-block with-errors"></div>
</div>

<div class="form-group">
<label>Città</label>
<input type="text" class="form-control" name="city">
<div class="help-block with-errors"></div>
</div>

<div class="form-group">
<label>Telefono</label>
<input type="text" class="form-control" name="tel">
<div class="help-block with-errors"></div>
</div>

<div class="form-group">
<label>Affiliazione</label>
<input type="text" class="form-control" name="affil">
</div>
<hr class="light">
<div class="form-group">
<label>Informativa sulla privacy</label>
<br>
<div class="form-group" style="text-align: justify;">
Il D.lgs. n. 196 del 30 giugno 2003 ("Codice in materia di protezione dei dati personali") prevede la tutela delle persone e di altri soggetti rispetto al trattamento dei dati personali. Secondo la normativa indicata, tale trattamento sarà improntato ai principi di correttezza, liceità e trasparenza e di tutela della Sua riservatezza e dei Suoi diritti. Ai sensi dell'articolo 13 del D.lgs. n.196/2003, pertanto, Le forniamo le seguenti informazioni: 
<ul>
<li> I dati da Lei forniti verranno trattati per attivare il mese di prova del servizio GisHosting </li>
<li> Il trattamento sarà effettuato in maniera automatizzata. 
<li> Il conferimento dei dati è obbligatorio poichè necessario per l’iscrizione sul servizio </li>
<li> I dati non saranno comunicati ad altri soggetti, né saranno oggetto di diffusione.</li>
</ul>
 Acquisite le informazioni fornite dal titolare del trattamento, ai sensi dell'articolo 13 del D.Lgs. 196/2003, presta il suo consenso al trattamento dei dati personali per i fini indicati nella suddetta informativa? 
<br>
</div>
<input type="radio" id="consenso" name="consenso" value="consenso" required> Presto il consenso

</div>
<hr class="light">
<!--input type='submit' name='Submit' value='Submit' /-->

<div class="form-group">
<button id="btnsubmit" type="submit" name='Submit' class="btn btn-light btn-xl" disabled>Modifica</button>
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
	$('#consenso').click(function () {
		//check if checkbox is checked
		if ($(this).is(':checked')) {
			$('#btnsubmit').removeAttr('disabled'); //enable input
		} else {
			$('#btnsubmit').attr('disabled', true); //disable input
		}
	});
</script>

</body>

</html>
