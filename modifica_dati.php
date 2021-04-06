<!DOCTYPE html>
<html lang="en">
<?php
session_start();
$user_admin="comuneisernia";
//$gruppo = 'comuneisernia3_group';
$cliente = 'Comune di Isernia';
//salva nelle varibili id e username prese da url
$idu = pg_escape_string($_GET['u']);
$_SESSION['user']=pg_escape_string($_GET['user']);

//richiama connessione al DB
include("root_connection.php");
?>

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="roberto" >


	<link rel="icon" href="favicon.ico"/>

    <title>Modifica dati utenti esterni del <?php echo $cliente; ?></title>
	<!-- Richiama Stili e CSS-->
    <?php
    
    require('req.php');
    ?>
   



</head>

<body id="page-top">
<!-- Richiama la navbar-->
<div id="navbar1">
<?php
require('navbar.php');
?>
</div>

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

<section class="page-section bg-primary" id="about">
<div class="container">
<div class="row justify-content-center">
<div class="col-lg-8 text-center">

<!--img src="img/logo-postgis.png" style="width:25%;" alt=""-->
<i class="fa fa-3x fa-user-edit wow bounceIn" data-wow-delay=".1s" style="color:white;"></i>
<hr class="light">


<?php
//check se bottone invia è stato cliccato
if(isset($_POST['Submit'])){

    //recupera dati inseriti nel form

	$mail = pg_escape_string($_POST['mail']);
	$name = pg_escape_string($_POST['name']);
	$surname = pg_escape_string($_POST['surname']);
	$codfisc = pg_escape_string($_POST['codfisc']);
	$docid = pg_escape_string($_POST['docid']);
	$docdate = pg_escape_string($_POST['docdate']);
	$street = pg_escape_string($_POST['street']);
	$cap = pg_escape_string($_POST['cap']);
	$city = pg_escape_string($_POST['city']);
	$tel = pg_escape_string($_POST['tel']);
	$affil = pg_escape_string($_POST['affil']);

$check_user=1;
// check if name exist
/*$query = "SELECT nome, mail from jlx_user where usr_login ='".$username."';";
$result = pg_query($conn_lizmap, $query);*/
	

	echo "I dati sono stati modificati correttamente.<br>";
	
	echo '<br><a href="./dashboard.php#about" class="btn btn-light btn-xl"> Torna alla dashboard </a>';


	// query per aggiornare i dati utente
	$query_user = "UPDATE utenti.utenti SET
		usr_email = $1, firstname = $2, lastname = $3, cf = $4, doc_id = $5, street = $6, postcode = $7, city = $8, phonenumber = $9, organization = $10, doc_exp = $11
		where id = $12;";

	//echo $query_lizmap;
	$result = pg_prepare($conn_isernia, "myquery1", $query_user);
	$result = pg_execute($conn_isernia, "myquery1", array($mail, $name, $surname, $codfisc, $docid, $street, $cap, $city, $tel, $affil, $docdate, $idu));

	
} else {
?>
<?php
//query per recuperare i dati utente che vengono scritti nel form
$query = "SELECT * from utenti.utenti where id=$1;";
	$result = pg_prepare($conn_isernia, "myquery1", $query);
	$result = pg_execute($conn_isernia, "myquery1", array($idu));
	while($r = pg_fetch_assoc($result)) {
		$maildb = $r["usr_email"];
		$nomedb = $r["firstname"];
		$cognomedb = $r["lastname"];
		$cfdb = $r["cf"];
		$docdb = $r["doc_id"];
		$docdatedb = $r["doc_exp"];
		$streetdb = $r["street"];
		$capdb = $r["postcode"];
		$citydb = $r["city"];
		$teldb = $r["phonenumber"];
		$affildb = $r["organization"];
	}
?>


<!-- form per modifica dei dati come value viene passato il valore corrispondente nel DB-->
<form id='login' action='modifica_dati.php?u=<?php echo $idu; ?>#about' method='post' accept-charset='UTF-8'>
<input type='hidden' name='submitted' id='submitted' value='1'/>

<div class="form-group">
<label>E-mail</label>
<input type="email" class="form-control" name="mail" value="<?php echo $maildb; ?>" required>
<div class="help-block with-errors"></div>
</div>

<div class="form-group">
<label>Nome</label>
<input type="text" class="form-control" name="name" value="<?php echo $nomedb; ?>" required>
<div class="help-block with-errors"></div>
</div>

<div class="form-group">
<label>Cognome</label>
<input type="text" class="form-control" name="surname" value="<?php echo $cognomedb; ?>" required>
<div class="help-block with-errors"></div>
</div>

<div class="form-group">
<label>Codice Fiscale</label>
<input type="text" class="form-control" name="codfisc" maxlength="16" value="<?php echo $cfdb; ?>" required>
<div class="help-block with-errors"></div>
</div>

<div class="form-group">
<label>Numero Documento di Identità</label>
<input type="text" class="form-control" name="docid" value="<?php echo $docdb; ?>" required>
<div class="help-block with-errors"></div>
</div>

<div class="form-group">
<label>Data Scadenza del Documento</label>
<input id="datePickerId" type="date" class="form-control" name="docdate" value="<?php echo $docdatedb; ?>" required>
<div class="help-block with-errors"></div>
</div>

<div class="form-group">
<label>Via e civico</label>
<input type="text" class="form-control" name="street" value="<?php echo $streetdb; ?>" required>
<div class="help-block with-errors"></div>
</div>

<div class="form-group">
<label>Codice di Avviamento Postale</label>
<input type="text" class="form-control" name="cap" value="<?php echo $capdb; ?>" required>
<div class="help-block with-errors"></div>
</div>

<div class="form-group">
<label>Città</label>
<input type="text" class="form-control" name="city" value="<?php echo $citydb; ?>" required>
<div class="help-block with-errors"></div>
</div>

<div class="form-group">
<label>Telefono</label>
<input type="text" class="form-control" name="tel" value="<?php echo $teldb; ?>" required>
<div class="help-block with-errors"></div>
</div>

<div class="form-group">
<label>Affiliazione</label>
<input type="text" class="form-control" name="affil" value="<?php echo $affildb; ?>">
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
<!-- Richiama librerie JS e contatti-->
<?php
require('footer.php');
require('req_bottom.php');
?>
<!-- Script per attivare il boostrap validator sui dati inseriti nel form -->
<script type="text/javascript">
	$(document).ready(function() {
	// Generate a simple captcha

	$('#login').validator();
	});
</script>
<!-- Script per evitare che inseriscano date precedenti a quella attuale-->
<script type="text/javascript">
	datePickerId.min = new Date().toISOString().split("T")[0];
</script>
<!-- Script per abilitare il tasto invia solo se consenso checcato -->
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
