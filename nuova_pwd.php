<?php
//session_start();
/* echo $_SESSION['user'] ."<br>";
echo $_POST['user']."<br>"; */
//echo $_SESSION['user'] ."<br>";
$cliente = 'Comune di Isernia';

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
<section class="page-section bg-primary" id="about">
<div class="container">
<div class="row justify-content-center">
<div class="col-lg-8 text-center">

<!--img src="img/logo-postgis.png" style="width:25%;" alt=""-->
<i class="fa fa-3x fa-user-lock wow bounceIn" data-wow-delay=".1s" style="color:white;"></i>
<hr class="light">


<?php

if(!$conn_isernia) {
    die('Connessione fallita !<br />');
} else {

	if ( isset( $_POST['submitpwd'] ) ) {

		$username = pg_escape_string($_POST['myUser']);
		$checkuser=0;

		$query = "SELECT * FROM utenti.utenti where usr_login = $1";
		$result = pg_prepare($conn_isernia, "myquery3", $query);
		$result = pg_execute($conn_isernia, "myquery3", array($username));
		while($r = pg_fetch_assoc($result)) {
				//$rows[] = $r;
				$fullname=$r["firstname"]. " " .$r["lastname"];
				$user_email=$r["usr_email"];
				$checkuser=1;
		}

if ($checkuser==1){	
		require('mail_address.php');
$testo = "

Egr. " . $fullname. ",\n 
questa mail e' stata generata automaticamente in quanto ha richiesto di cambiare password per l'utente " . $username . " registrato sul Sistema di Istanze Online del " . $cliente . ".\n

Può cambiare la sua password a questo link https://gishosting.gter.it/isernia/cambia_password.php \n
    
Se riceve questo messaggio per errore, la preghiamo di distruggerlo e di comunicarlo immediatamente all'amministratore del sistema rispondendo a questa mail.\n
In caso di problemi o richieste non esiti a contattare l'amministratore del sistema al seguente indirizzo segreteriagenerale@comune.isernia.it.\n \n
            
Cordiali saluti, \n
L'amministratore del sistema.
        
-- 
Comune di Isernia
Piazza Marconi, 3 - 86170 Isernia (IS)
E-mail: segreteriagenerale@comune.isernia.it

Servizio basato su GisHosting di Gter srl\n

";

	$oggetto ="Cambio password Sistema Istanza Online Comune di Isernia";
    $headers = $nostro_recapito .
    "Reply-To: " .$loro_recapito. "\r\n".
	"Content-Type: text/plain; charset=utf-8" . "\r\n";
	"Content-Transfer-Encoding: base64" . "\r\n";
	mail ("$user_email", "$oggetto", "$testo","$headers");

		//header ("Location: dashboard.php#about");

		$hidden_mail = explode("@", $user_email);

		echo "E' appena stata inviata una mail all'indirizzo <b>xxxxx@" . $hidden_mail[1] . "</b> con il link per procedere al cambiamento della password.<br>";
		echo '<br><a href="./dashboard.php" class="btn btn-light btn-xl"> Torna alla dasboard </a>';
}else{
	die('<h1>L\'utente <i>'.$username.'</i> non esiste.</h1> <hr class="light"><a href="dashboard.php" class=\'btn btn-light btn-xl\'>Riprova</a></div></div></div></section>');
}

}

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
</body>

</html>