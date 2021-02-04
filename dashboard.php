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
                    <div class="col-lg-8 text-center">

<img src="./assets/img/logo-postgis.png" style="width:25%;" alt="">
<hr class="light">


<?php
session_start();
if(isset($_POST['Submit'])){
	include("root_connection.php");

    if(empty($_POST['username']))
    {
        $this->HandleError("UserName is empty!");
        return false;
    }
     
    if(empty($_POST['password']))
    {
        $this->HandleError("Password is empty!");
        return false;
    }
     
    $username = pg_escape_string($_POST['username']);
    $password = pg_escape_string($_POST['password']);
	$dbname = pg_escape_string($_POST['dbname']);
    $_SESSION['user']=$username;
    $_SESSION['pwd']=$password;


	$conn = @pg_connect('host=localhost port=5432 dbname='.$dbname.' user='.$username.' password='.$password.'');
	$check=0;
	if(!$conn) {
		// non c'è postGis MA ESISTE UN UTENTE
		$query = "SELECT * FROM \"gishosting_admin\".\"users\" where \"nome\"='".$_SESSION['user']."' AND \"pwd\"='".$password."' ;";
		$result = pg_query($conn2, $query);
		while($r = pg_fetch_assoc($result)) {
    		$nome=$r["nome"];
			$check=1;
		}

		if ($check==1){
			echo "<h1>Caro <i>". $_SESSION['user']."</i>, <br> il tuo piano non prevede l'utilizzo del DataBase PostGIS</h1>";
			echo "<hr class=\"light\"><a href=\"dashboard.php\" class='btn btn-default'>New check</a></div></div></div></section>";
			include("dati_utente.php");
		} else {
    		//die('<h1>Dear <i>'.$username.'</i>,<br> your connection details are wrong or you do not have a PostGIS DB in your GisHosting page.</h1> <hr class="light"><a href="dashboard.php" class=\'btn btn-default\'>New check</a></div></div></div></section>');
			die('<h1>Caro <i>'.$username.'</i>,<br> utente o password errati.</h1> <hr class="light"><a href="dashboard.php" class=\'btn btn-default\'>New check</a></div></div></div></section>');
		}
	} else {


		$query = " SELECT pg_size_pretty(pg_database_size('".$dbname."'));";

		$result = pg_query($conn, $query);


		while($r = pg_fetch_assoc($result)) {
					echo "<h1>Dear <i>". $_SESSION['user']."</i>, <br> you are using <b>". $r["pg_size_pretty"]. "</b> of your PostGIS geoDB</h1>";
					echo "<hr class=\"light\"><a href=\"dashboard.php\" class='btn btn-default'>New check</a></div></div></div></section>";
			}
	

	pg_close($conn);

	include("dati_utente.php");
	

	}	

		

    		//$rows[] = $rows[]. "<a href='puntimodifica.php?id=" . $r["NAME"] . "'>edit <img src='../../famfamfam_silk_icons_v013/icons/database_edit.png' width='16' height='16' alt='' /> </a>";
	//}


pg_close($conn2);

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
<label for='password' >Nome DB*:</label>
<input type='text' class="form-control" name='dbname' id='dbname' maxlength="50" required=""/>
 </div>
<!--input type='submit' name='Submit' value='Submit' /-->

<div class="form-group">
<button type="submit" name='Submit' class="btn btn-default">Submit</button>
</div>
</form>




</div>
</div>
</div>
</section>



<?php

}
?>






<?php
require('footer.php');
require('req_bottom.php');
?>


</body>

</html>
