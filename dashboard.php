<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="it">




<head>
<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="roberto" >


	<link rel="icon" href="favicon.ico"/>

    <title>Sistema Istanza Online dashboard</title>
<!-- Richiama Stili e CSS-->
<?php
require('req.php');
?>
    

</head>

<body id="page-top">
<!-- Richiama la navbar -->
<div id="navbar1">
<?php
require('navbar.php');
?>
</div>

<!-- Masthead-->
<header class="masthead">
            <div class="container h-100">
                <div class="row h-100 align-items-center justify-content-center text-center">
                    <div class="col-lg-10 align-self-end">
                        <h1 class="text-uppercase text-white font-weight-bold">Istanze CDU/Visura - Dashboard utente</h1>
                        <hr class="divider my-4" />
                    </div>
                    <div class="col-lg-8 align-self-baseline">
                        <p class="text-white-75 font-weight-light mb-5">Accedi alla tua dashboard e in pochi e semplici passi potrai ottenere il tuo CDU o la tua Visura.<br> Scopri come richiedere il tuo CDU/Visura consultando <a  href="https://cdu-istanze-manuale.readthedocs.io/it/latest/index.html" target="_blank">il manuale del Sistema di Istanze Online!</a></p>
                        <a class="btn btn-primary btn-xl js-scroll-trigger" href="#about">Inserisci i tuoi dati</a>
                    </div>
                </div>
            </div>
</header>




<section class="page-section bg-primary" id="about">
    <div class="container">
        <div class="row justify-content-center">
            <div class="text-center">

            <!--img src="./assets/img/logo-postgis.png" style="width:25%;" alt=""-->
            <i class="fa fa-3x fa-user wow bounceIn " data-wow-delay=".1s" style="color:white;"></i>
            <hr class="light">


<?php
//session_start();
/* echo $_SESSION['user'];
echo $_SESSION['pwd']; */

//Se tasto Invia è cliccato
if(isset($_POST['Submit']) || $_SESSION['user'] != ''){
    // Richiama connessione al DB
	include("root_connection.php");
    // check su inserimento dati nel form
    if ($_SESSION['user'] == ''){

        if(empty($_POST['username']))
        {
            $this->HandleError("Inserire lo Username!");
            return false;
        }
        
        if(empty($_POST['password']))
        {
            $this->HandleError("Inserire la Password!");
            return false;
        }
     
    
	//salva user e pwd in una variabile e poi nella variabile $_SESSION necessarie per check su login
        $username = pg_escape_string($_POST['username']);
        $password = pg_escape_string($_POST['password']);
        $_SESSION['user']=$username;
        $_SESSION['pwd']=$password;
    }

	$check=0;
    // query su DB verifica se utente è attivo, se esiste già e se la pwd è corretta
    $query = "SELECT * FROM utenti.utenti where usr_login=$1";
    $result = pg_prepare($conn_isernia, "myquery0", $query);
    $result = pg_execute($conn_isernia, "myquery0", array($_SESSION['user']));
    while($r = pg_fetch_assoc($result)) {
        if (password_verify($_SESSION['pwd'], $r["usr_password"]) && $r["nascosto"]!='t'){
            $nome=$r["usr_login"];
            $_SESSION['admin']=$r["admin"];
            $check=1;
            echo $r["nascosto"];
        }elseif($r["nascosto"]=='t'){
            $_SESSION['user'] = '';
            $_SESSION['pwd'] = '';
            die('<h1>Caro <i>'.$username.'</i>,<br> Il tuo account non è più attivo, creane uno nuovo.</h1> <hr class="light"><a href="form_external_user.php" class=\'btn btn-light btn-xl\'>Crea Account</a></div></div></div></section>');
        }
        else{
            $_SESSION['user'] = '';
            $_SESSION['pwd'] = '';
            die('<h1>Caro <i>'.$username.'</i>,<br> password errata.</h1> <hr class="light"><a href="dashboard.php" class=\'btn btn-light btn-xl\'>Riprova</a></div></div></div></section>');
        }
    }
    // se utente è admin richiama dati_admin altrimneti dati_utente oppure segnala che l'utente non esiste
    if ($check==1 && $_SESSION['admin']!='t'){
        include("dati_utente.php");
    }elseif ($check==1 && $_SESSION['admin']=='t'){
        include("dati_admin.php");
    }else {
        //echo $check;
        $_SESSION['user'] = '';
        $_SESSION['pwd'] = '';
        //die('<h1>Dear <i>'.$username.'</i>,<br> your connection details are wrong or you do not have a PostGIS DB in your GisHosting page.</h1> <hr class="light"><a href="dashboard.php" class=\'btn btn-default\'>New check</a></div></div></div></section>');
        die('<h1>l\'utente <i>'.$username.'</i> non esiste.</h1> <hr class="light"><a href="dashboard.php" class=\'btn btn-light btn-xl\'>Riprova</a></div></div></div></section>');
    }


pg_close($conn_isernia);

?>
<!-- Ricarica la navbar una volta fatto il login per poter vedere i bottoni corretti-->
<script>
    $('#navbar1').load('navbar.php');
</script>

<?php
} else {
?>
<!-- Form del login che rimanda a dashboard-->
<form id='login' action='dashboard.php#about' method='post' accept-charset='UTF-8'>
<input type='hidden' name='submitted' id='submitted' value='1'/>
<div class="form-group">
<label for='username' >UserName*:</label>
<input type='text' class="form-control" name='username' id='username' required/>
<div class="help-block with-errors"></div>
</div>

<div class="form-group">
<label for='password' >Password*:</label>
<input type='password' class="form-control" name='password' id='password' required/>
<div class="help-block with-errors"></div>
 </div>

 <div class="form-group">
 <a class="text-white mt-0" href="#cambiaPwd" data-toggle="modal">Hai dimenticato la tua password?</a>
 </div>
 
<div class="form-group">
<button type="submit" name='Submit' class="btn btn-light btn-xl">Invia</button>
</div>
</form>

<!-- Modal per il cambio password richiama il file nuova_pwd.php"-->
<div class="modal fade" id="cambiaPwd" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Cambia la password</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <!-- Form che rimanda a nuova_pwd per cambiare pwd-->
	  <form action="nuova_pwd.php" method="post" enctype="multipart/form-data">
	  <div class="form-group">
  		Inserisci il tuo username:<br><br>
      <!--input type="hidden" name="user" id="user'+row.id_istanza+'" value="<?php echo $_SESSION['user']; ?>"-->
  		<input type="text" name="myUser" id="myUser" required><br><br>
  		<input type="submit" value="Invia" name="submitpwd">
		  </div>
		</form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Chiudi</button>
      </div>
    </div>
  </div>
</div>

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
<!-- script per attivare il validatore sulla compilazione dei form-->
<script type="text/javascript">
$(document).ready(function() {
// Generate a simple captcha

$('#login').validator({});
});
</script>
<!-- primo tentativo per cambiare background alle righe in funzione di una condizione,
non funziona manda in loop la pagina, gestito con metodo di boostrap table-->
<!--script> 
$('#usr').bootstrapTable({
    onLoadSuccess: function(data){
    $('#usr').bootstrapTable('getData').forEach(function(r, index){
        //console.log(r['doc_exp'])
        //console.log(index)
        if (r['doc_exp'] < new Date().toISOString().substring(0,10)){
            //$('table#usr tr[data-index="'+index+'"]').css('background-color', 'yellow');
            $('table#usr tr[data-index="'+index+'"]').attr('style', 'background-color: yellow !important');
        }
    })
    //$('#usr').bootstrapTable('refresh')
    }
});

</script-->
</body>

</html>
