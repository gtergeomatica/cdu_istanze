<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">




<head>
<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="roberto" >


	<link rel="icon" href="favicon.ico"/>

    <title>GisHosting dashboard</title>
<?php
require('req.php');
?>
    

</head>

<body id="page-top">
<?php
require('navbar.php');
?>


<!-- Masthead-->
<header class="masthead">
            <div class="container h-100">
                <div class="row h-100 align-items-center justify-content-center text-center">
                    <div class="col-lg-10 align-self-end">
                        <h1 class="text-uppercase text-white font-weight-bold">Istanze CDU - Dashboard utente</h1>
                        <hr class="divider my-4" />
                    </div>
                    <div class="col-lg-8 align-self-baseline">
                        <p class="text-white-75 font-weight-light mb-5">Descrizione rapida cdu e pubblicità del plugin???? Start Bootstrap can help you build better websites using the Bootstrap framework! Just download a theme and start customizing, no strings attached!</p>
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
if(isset($_POST['Submit']) || $_SESSION['user'] != ''){
	include("root_connection.php");
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
     
    
	//$dbname = pg_escape_string($_POST['dbname']);
    

        $username = pg_escape_string($_POST['username']);
        $password = pg_escape_string($_POST['password']);
        $_SESSION['user']=$username;
        $_SESSION['pwd']=$password;
    }
    /* echo "<br>" .$password; */


	//$conn = @pg_connect('host=localhost port=5432 dbname='.$dbname.' user='.$username.' password='.$password.'');
	$check=0;
	//if(!$conn) {
		// non c'è postGis MA ESISTE UN UTENTE
    $query = "SELECT * FROM utenti.utenti where usr_login=$1";
    $result = pg_prepare($conn_isernia, "myquery0", $query);
    $result = pg_execute($conn_isernia, "myquery0", array($_SESSION['user']));
    while($r = pg_fetch_assoc($result)) {
        if (password_verify($_SESSION['pwd'], $r["usr_password"])){
            $nome=$r["usr_login"];
            $check=1;
        }
        else{
            die('<h1>Caro <i>'.$username.'</i>,<br> password errata.</h1> <hr class="light"><a href="dashboard.php" class=\'btn btn-light btn-xl\'>Riprova</a></div></div></div></section>');
        }
    }

    if ($check==1){
        include("dati_utente.php");
    } else {
        //die('<h1>Dear <i>'.$username.'</i>,<br> your connection details are wrong or you do not have a PostGIS DB in your GisHosting page.</h1> <hr class="light"><a href="dashboard.php" class=\'btn btn-default\'>New check</a></div></div></div></section>');
        die('<h1>l\'utente <i>'.$username.'</i> non esiste.</h1> <hr class="light"><a href="dashboard.php" class=\'btn btn-light btn-xl\'>Riprova</a></div></div></div></section>');
    }


pg_close($conn_isernia);

?>



<?php
} else {
?>

<form id='login' action='dashboard.php#about' method='post' accept-charset='UTF-8'>
<input type='hidden' name='submitted' id='submitted' value='1'/>
<div class="form-group">
<label for='username' >UserName*:</label>
<input type='text' class="form-control" name='username' id='username'  maxlength="50" required=""/>
</div>

<div class="form-group">
<label for='password' >Password*:</label>
<input type='password' class="form-control" name='password' id='password' maxlength="50" required=""/>
 </div>

 <div class="form-group">
 <a class="text-white mt-0" href="./cambia_password.php">Hai dimenticato la tua password?</a>
 </div>
 
<div class="form-group">
<button type="submit" name='Submit' class="btn btn-light btn-xl">Invia</button>
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


</body>

</html>
