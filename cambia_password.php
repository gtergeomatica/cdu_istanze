<!DOCTYPE html>
<html lang="en">
<?php
//questo file è quello che viene indicato nella mail che arriva all'utente quando clicca su hai dimenticato pwd
$user_admin="comuneisernia";
//$gruppo = 'comuneisernia3_group';
$cliente = 'Comune di Isernia';

//Richiama connessioni al DB
include("root_connection.php");
?>

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="roberto" >


	<link rel="icon" href="favicon.ico"/>

    <title>Cambio password</title>

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
<i class="fa fa-3x fa-user-lock wow bounceIn" data-wow-delay=".1s" style="color:white;"></i><h4>Inserisci la nuova Password</h4>
<hr class="light">


<?php
session_start();
if(isset($_POST['submitpwd'])){



	$username = pg_escape_string($_POST['username']);
	//echo $username;

	$password = pg_escape_string($_POST['password']);
	
	$password2 = password_hash(pg_escape_string($_POST['password']),PASSWORD_DEFAULT);


$check_user=0;
// verifica se esiste l'utente
$query = "SELECT usr_login, usr_email from utenti.utenti where usr_login =$1;";
$result = pg_prepare($conn_isernia, "myquery0", $query);
$result = pg_execute($conn_isernia, "myquery0", array($username));
while($r = pg_fetch_assoc($result)) {
	$check_user=1;
	//$mail_old=$r['usr_email'];
}

if ($check_user==1){
	

	echo "La password dell'utente <b>" . $username . "</b> è stata modificata correttamente. <br>Eseguire il Login per richiedere il CDU.<br>";
	
	echo '<br><a href="./dashboard.php" class="btn btn-light btn-xl"> Vai a Richiesta CDU </a>';


	// aggiorna la pwd secondo quanto indicato nel form
	$query_pwd = "UPDATE utenti.utenti SET usr_password = $1 where usr_login = $2;";
	$result = pg_prepare($conn_isernia, "myquery1", $query_pwd);
	$result = pg_execute($conn_isernia, "myquery1", array($password2, $username));


} else {
	die('<h1>L\'utente <i>'.$username.'</i> non esiste.</h1> <hr class="light"><a href="cambia_password.php" class=\'btn btn-light btn-xl\'>Riprova</a></div></div></div></section>');
}

	
} else {
?>
<!--form per cambio pwd-->

<form id='newPwd' action='cambia_password.php#about' method='post' accept-charset='UTF-8'>
<input type='hidden' name='submitted' id='submitted' value='1'/>

<div class="form-group">
<label for='username' >UserName*:</label>
<!--small id="usernameHelp" class="form-text text-muted">Lo username non deve contenere numeri, lettere maiuscole e caratteri speciali</small-->
<input type='text' class="form-control" name='username' id='username' maxlength="15" minlength="8" pattern="^[a-z_]+$" required>
<div class="help-block with-errors"></div>
</div>

<div class="form-group">
<label >Password*:</label>
<input type='password' class="form-control" name='password' id='password' maxlength="50" data-different="" required>
<div class="help-block with-errors"></div>
 </div>

<div class="form-group">
<label >Conferma password*</label>
<input type="password" class="form-control" name="confirmPassword" data-match="#password" data-match-error="La conferma della password non corrisponde alla password inserita" required>
<div class="help-block with-errors"></div>
</div>

<hr class="light">
<!--input type='submit' name='Submit' value='Submit' /-->

<div class="form-group">
<button id="btnsubmit" type="submit" name='submitpwd' class="btn btn-light btn-xl">Invia</button>
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

<!-- Script per attivare il boostrap validator sui dati inseriti nel form -->
<script type="text/javascript">
$(document).ready(function() {
// Creato un custom validator per verificare che pwd sia diversa da username

$('#newPwd').validator({
custom: {
  different: function() {
    var matchValue = $('#username').val() // foo
    if ($('#password').val() == matchValue) {
      return "La password deve essere diversa dallo username"
    }
  }
}
});
});
</script>

</body>

</html>
