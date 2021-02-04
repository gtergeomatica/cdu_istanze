<!DOCTYPE html>
<html lang="en">
<?php

$user_admin="astergenova";
$gruppo = 'astergenova5_group';
$cliente = 'ASTER';

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

    <title>Iscrizione utenti esterni di <?php echo $cliente; ?></title>

    <?php
    
    require('req.php');
    ?>
   



</head>

<body id="page-top">
<header>
        <div class="header-content">
            <div class="header-content-inner">
                <h1>Iscrizione utenti esterni di <?php echo $cliente; ?></h1>
				<!--h3>Admin: <--?php echo $user_admin;?> </h3-->
				<hr> 
				
             	<a href="#about" class="btn btn-default btn-xl page-scroll"> Completa il form </a>
            </div>
        </div>
</header>


<section class="bg-primary" id="about">
<div class="container">
<div class="row">
<div class="col-lg-8 col-lg-offset-2 text-center">

<!--img src="img/logo-postgis.png" style="width:25%;" alt=""-->
<i class="fa fa-3x fa-user-plus wow bounceIn " data-wow-delay=".1s"></i>
<hr class="light">


<?php
session_start();
if(isset($_POST['Submit'])){



	$username = trim($_POST['username']);

	$password = trim($_POST['password']);
	
	$password2 = password_hash($_POST['password'],PASSWORD_DEFAULT);

    //echo $password2;
    //exit;

	$mail = trim($_POST['mail']);

	/* for($i = 0; $i < count($_POST["gruppi"]); $i++) {
		echo $_POST["gruppi"][$i].'<br>';
	} */
    

    
	//$gruppi = pg_escape_string($_POST['gruppi[]']);
	
	//echo '<h1>'.$username.'</h1>';
	//echo '<h1>'.$gruppo.'</h1>';
	//exit;

	$name = pg_escape_string($_POST['name']);

	$surname = pg_escape_string($_POST['surname']);

	$affil = pg_escape_string($_POST['affil']);

	$address = pg_escape_string($_POST['address']);

	$tel = pg_escape_string($_POST['tel']);

	$note = pg_escape_string($_POST['note']);



$check_user=1;
// check if name exist
/*$query = "SELECT nome, mail from jlx_user where usr_login ='".$username."';";
$result = pg_query($conn_lizmap, $query);*/
$query = "SELECT nome, mail from jlx_user where usr_login =$1;";
$result = pg_prepare($conn_lizmap, "myquery0", $query);
$result = pg_execute($conn_lizmap, "myquery0", array($username));
while($r = pg_fetch_assoc($result)) {
	$check_user=-1;
	$mail_old=$r['mail'];
}

if ($check_user==1){
	

	echo "L'utente " . $name . " " .$surname. " con username <b>" . $username . "</b> è stato creato. <br>Eseguire il Login per iniziare a consultare le mappe online di ASTER.<br>";
	
	echo '<br><a href="https://www.gishosting.gter.it/lizmap-web-client/lizmap/www/admin.php" class="btn btn-default"> Vai alle Mappe </a>';


	$result = pg_query($conn2, $query);

	pg_close($conn2);


	// creo l'utente lizmap
	$query_lizmap = "INSERT INTO jlx_user(
				usr_login, usr_password, usr_email, firstname, lastname, organization, 
				phonenumber, street,  comment)
		VALUES ('$username','$password2', '$mail', '$name', '$surname', '$affil', 
				'$tel', '$address', '$note');";

	//echo $query_lizmap;
	$result = pg_query($conn_lizmap, $query_lizmap);



	/*$query_lizmap = "INSERT INTO jacl2_group(
				id_aclgrp, name, grouptype)
		VALUES ('".$username."_group', '".$username."_group', 0);";
	$result = pg_query($conn_lizmap, $query_lizmap);*/


	// Non serve perchè credo sia gestito da un trigger interno
	/*$query_lizmap = "INSERT INTO jacl2_group(
				id_aclgrp, name, grouptype, ownerlogin)
		VALUES ('__priv_".$username."', '$username', 2, '$username');";
	$result = pg_query($conn_lizmap, $query_lizmap);*/
    
    $query_lizmap = "INSERT INTO jacl2_user_group(
				login, id_aclgrp)
    VALUES ('$username', '".$gruppo."');";
    $result = pg_query($conn_lizmap, $query_lizmap);


	/* for($i = 0; $i < count($_POST["gruppi"]); $i++) {
		
		$query_lizmap = "INSERT INTO jacl2_user_group(
				login, id_aclgrp)
		VALUES ('$username', '".$gruppo."');";
		$result = pg_query($conn_lizmap, $query_lizmap);
	} */
	//exit;

    //recupero nome, cognome e indirizzo mail dell'amministratore per invio mail
	$query_lizmap = "SELECT usr_email, firstname, lastname FROM jlx_user where usr_login = '" . $user_admin . "';";
	$result = pg_query($conn_lizmap, $query_lizmap);
	while($r = pg_fetch_assoc($result)) {
		$mail_admin=trim($r['usr_email']);
		$f_admin = $r['firstname'];
		$l_admin = $r['lastname'];
	}
	
    //echo '<br><h1>la mail è: '. $mail_admin .'</h1>';
	//*******************************************************//
	// INVIO MAIL

	$nostro_recapito = "From: GisHosting Gter <gishosting.gter@gmail.com>\r\n";
    $loro_recapito = "DL_Cartografia@astergenova.it";



    $oggetto = "Creazione utente su GisHosting";

    $testo = "

Egr. " . $f_admin . " " .$l_admin. "\n
questa mail e' stata generata automaticamente in quanto si è appena registrato su GisHosting un utente con i seguenti dettagli:\n
    Username:". $username . " \n	
    Affiliazione:". $affil . " \n
    Tel:". $tel . " \n	
    Indirizzo:". $address . " \n \n

Se riceve questo messaggio per errore, la preghiamo di distruggerlo e di  darcene  immediata  comunicazione  anche  inviando  un  messaggio  di  ritorno  all'indirizzo  e-mail  del mittente. 	In caso di problemi o richieste non esiti a ricontattarci.\n \n
            
Il team di GisHosting
        
--  
GisHosting di Gter srl\n
Via Ruffini 9R - 16128 Genova\n
P.IVA/CF 01998770992\n
Tel. +39 010 8694830\n
E-mail: gishosting@gter.it\n
www.gishosting.gter.it\n
www.twitter.com/Gteronline - www.facebook.com/Gteronline \n
www.linkedin.com/company/gter-srl-innovazione-in-geomatica-gnss-e-gis\n\n
            
Le informazioni, i dati e le notizie contenute nella presente comunicazione e i relativi allegati sono di natura  privata  e  come  tali  possono  essere  riservate  e  sono,  comunque,  destinate  esclusivamente  ai destinatari indicati in epigrafe. La diffusione, distribuzione e/o la copiatura del documento trasmesso da parte di qualsiasi soggetto diverso dal destinatario è proibita, sia ai sensi dell’art. 616 c.p., sia ai sensi del D.Lgs. n. 196/2003. \n
Se avete ricevuto questo messaggio per errore, vi preghiamo di distruggerlo e di  darcene  immediata  comunicazione  anche  inviando  un  messaggio  di  ritorno  all’indirizzo  e-mail  del mittente.			

";


	mail ("$mail_admin", "$oggetto", "$testo", "$nostro_recapito");


	$testo2 = "

INFO DI SERVIZIO!\n" . $name . " " .$surname. " e' stato appena registrato su GisHosting con i seguenti dettagli:\n
    Username: ". $username . " \n
    Mail: ". $mail . " \n
    Affiliazione: ". $affil . " \n
    Tel: ". $tel . " \n	
    Indirizzo: ". $address . " \n \n
    
I dati sono stati memorizzati sul DB Lizmap (PostgreSQL). Non sono richieste altre azioni \n \n
        
-- 
GisHosting di Gter srl\n
Via Ruffini 9R - 16128 - 16123 Genova\n
P.IVA/CF 01998770992\n
Tel. +39 010 8694830\n
E-mail: gishosting@gter.it\n
www.gishosting.gter.it\n
www.twitter.com/Gteronline - www.facebook.com/Gteronline \n
www.linkedin.com/company/gter-srl-innovazione-in-geomatica-gnss-e-gis\n\n

";

	$oggetto2 ="Nuovo utente registrato su GisHosting";
	mail ("assistenzagis@gter.it", "$oggetto2", "$testo2","$nostro_recapito");
    
    $testo3 = "

Egr. " . $name . " " .$surname. ",\n 
questa mail e' stata generata automaticamente in quanto si è appena registrato/a sul portale GisHosting di ASTER con i seguenti dettagli:\n
    Username: ". $username . " \n
    Mail: ". $mail . " \n
    Affiliazione: ". $affil . " \n
    Tel: ". $tel . " \n	
    Indirizzo: ". $address . " \n \n
    
Se riceve questo messaggio per errore, la preghiamo di distruggerlo e di comunicarlo immediatamente all'amministratore del portale ASTER rispondendo a questa mail. Se invece si è effettivamente registrato/a le ricordiamo che il suo utente è già attivo e può quindi iniziare a consultare i dati online all'indirizzo https://www.gishosting.gter.it/lizmap-web-client/lizmap/www/admin.php \n
In caso di problemi o richieste non esiti a contattare l'amministratore del portale ASTER al seguente indirizzo DL_Cartografia@astergenova.it.\n \n
            
Cordiali saluti, \n
L'amministratore del portale ASTER.
        
-- 
ASTER SPA
Via XX Settembre, 15 - 16121, Genova
E-mail: DL_Cartografia@astergenova.it

Servizio basato su GisHosting di Gter srl\n

";

	$oggetto3 ="Iscrizione a GisHosting";
    $headers3 = $nostro_recapito .
    "Reply-To: " .$loro_recapito. "\r\n" .
    "Cc: " .$mail_admin. "\r\n";
	mail ("$mail", "$oggetto3", "$testo3","$headers3");

} else {
	echo "Egr. " . $name . " " .$surname. ", lo username prescelto è già in uso sul nostro sistema. La preghiamo di registrarsi nuovamente scegliendo un username diverso. <br>";
}

	
} else {
?>
<!--form id="defaultForm" method="post" class="form-horizontal"-->




<form id='login' action='form_external_aster.php#about' method='post' accept-charset='UTF-8'>
<input type='hidden' name='submitted' id='submitted' value='1'/>

<div class="form-group">
    <h3>L'utente creato sarà automaticamente inserito nel Gruppo di <?php echo $cliente; ?></h3>
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
	<!--br><input name="gruppi[]" type="checkbox" value="<!--?php echo $r['id_aclgrp'];?>" required><label><!--?php echo $r['id_aclgrp'];?></label><!--/li-->
	<!--option><!--?php echo $r['id_aclgrp'];?></option-->
<!--?php
	$i=$i+1;   
}
?-->
<!--/ul-->
<!--/select-->
</div>
<?php //echo $query;?>

<div class="form-group">
<label for='username' >UserName*:</label>
<input type='text' class="form-control" name='username' id='username' maxlength="15" required>
</div>

<div class="form-group">
<label >Password*:</label>
<input type='password' class="form-control" name='password' maxlength="50" required>
 </div>

<div class="form-group">
<label >Conferma password*</label>
<input type="password" class="form-control" name="confirmPassword" required>
</div>

<div class="form-group">
<label>E-mail*</label>
<input type="text" class="form-control" name="mail" required>
</div>

<div class="form-group">
<label>Nome*</label>
<input type="text" class="form-control" name="name" required>
</div>

<div class="form-group">
<label>Cognome*</label>
<input type="text" class="form-control" name="surname" required>
</div>


<div class="form-group">
<label>Affiliazione*</label>
<input type="text" class="form-control" name="affil" required>
</div>

<div class="form-group">
<label>Indirizzo</label>
<input type="text" class="form-control" name="address">
</div>

<div class="form-group">
<label>Telefono</label>
<input type="text" class="form-control" name="tel">
</div>

<div class="form-group">
<label>Note</label>
<input type="text" class="form-control" maxlength="300" name="note">
</div>




<div class="form-group">
<label>Informativa sulla privacy</label>
<br>
Il D.lgs. n. 196 del 30 giugno 2003 ("Codice in materia di protezione dei dati personali") prevede la tutela delle persone e di altri soggetti rispetto al trattamento dei dati personali. Secondo la normativa indicata, tale trattamento sarà improntato ai principi di correttezza, liceità e trasparenza e di tutela della Sua riservatezza e dei Suoi diritti. Ai sensi dell'articolo 13 del D.lgs. n.196/2003, pertanto, Le forniamo le seguenti informazioni: 
<ul>
<li> I dati da Lei forniti verranno trattati per attivare il mese di prova del servizio GisHosting </li>
<li> Il trattamento sarà effettuato in maniera automatizzata. 
<li> Il conferimento dei dati è obbligatorio poichè necessario per l’iscrizione sul servizio </li>
<li> I dati non saranno comunicati ad altri soggetti, né saranno oggetto di diffusione.</li>
</ul>
 Acquisite le informazioni fornite dal titolare del trattamento, ai sensi dell'articolo 13 del D.Lgs. 196/2003, presta il suo consenso al trattamento dei dati personali per i fini indicati nella suddetta informativa? 
<br>
<input type="radio" id="consenso" name="consenso" value="consenso" required> Presto il consenso

</div>

<!--input type='submit' name='Submit' value='Submit' /-->

<div class="form-group">
<button type="submit" name='Submit' class="btn btn-default">Submit</button>
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


<script type="text/javascript">
$(document).ready(function() {
// Generate a simple captcha
function randomNumber(min, max) {
return Math.floor(Math.random() * (max - min + 1) + min);
};
$('#captchaOperation').html([randomNumber(1, 100), '+', randomNumber(1, 200), '='].join(' '));

$('#login').bootstrapValidator({
message: 'Questo valore non è valido',
fields: {
username: {
message: 'Lo username non è valido',
validators: {
notEmpty: {
message: 'Lo username è obbligatorio e non può essere lasciato vuoto'
},
stringLength: {
min: 8,
max: 15,
message: 'Lo username deve avere più di 8 caratteri e meno di 15 caratteri'
},
regexp: {
regexp: /^[a-z_]+$/,
//regexp: /^[a-zA-Z0-9_\.]+$/,
message: 'Lo username può essere composto solo da lettere alfabetiche (no maiuscole) e underscore (non utilizzare numeri o caratteri speciali)'
},
different: {
field: 'password',
message: 'Lo username e la password non possono essere uguali'
}
}
},
email: {
validators: {
notEmpty: {
message: 'L\'indirizzo email è obbligatorio e non può essere lasciato vuoto'
},
emailAddress: {
message: 'L\'indirizzo email fornito non è un indirizzo email valido'
}
}
},
password: {
validators: {
notEmpty: {
message: 'La password è obbligatoria e non può essere lasciata vuota'
},
identical: {
field: 'confirmPassword',
message: 'La password e la sua conferma non sono uguali'
},
different: {
field: 'username',
message: 'La password non può essere uguale allo username'
}
}
},
confirmPassword: {
validators: {
notEmpty: {
message: 'La conferma della password è obbligatoria e non può essere lasciata vuota'
},
identical: {
field: 'password',
message: 'La password e la sua conferma non sono uguali'
},
different: {
field: 'username',
message: 'La password non può essere uguale allo username'
}
}
},
affil: {
validators: {
notEmpty: {
message: 'L\'affiliazione è obbligatoria e non può essere lasciata vuota'
}
}
},
captcha: {
validators: {
callback: {
message: 'Wrong answer',
callback: function(value, validator) {
var items = $('#captchaOperation').html().split(' '), sum = parseInt(items[0]) + parseInt(items[2]);
return value == sum;
}
}
}
}
}
});
});
</script>

</body>

</html>
