<?php
session_start();
//echo basename($_SERVER['PHP_SELF']);
//require_once('req.php');
?>
<?php 
    $user = pg_escape_string($_SESSION['user']);
    //$curPageName = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);  
    //echo "The current page name is: ".$curPageName;  
    //echo "</br>";
    //echo $_SERVER['HTTP_REFERER'];  
?>


<!--body id="page-top" onLoad="$('#myModal').modal('show');"-->


<!-- body id="page-top" -->

    <!-- Navigation-->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top py-3" id="mainNav">
            <div class="container">
                <a class="navbar-brand js-scroll-trigger" href="./index.php#page-top">Home</a>
                <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <ul class="navbar-nav ml-auto my-2 my-lg-0">
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" href="./index.php#about">Come funziona</a></li>
                        <!-- Mostra i due pulsanti solo nella navbar della homepage-->
                        <?php if(basename($_SERVER['PHP_SELF']) == 'index.php') {?>
                            <li class="nav-item"><a class="nav-link js-scroll-trigger" href="#account">Crea account</a></li>
                            <!--li class="nav-item"><a class="nav-link js-scroll-trigger" href="#moduli">Moduli</a></li-->
                        <?php } ?>
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" href="#contact">Contatti</a></li>
                        <li class="nav-item"><a class="nav-link" href="https://cdu-istanze-manuale.readthedocs.io/it/latest/index.html" target="_blank"><i class="fas fa-info-circle"></i> Guida</a></li>
                        <!-- Mostra i 3 pulsanti solo se l'utente è già loggato altrimenti mostra tasto per andare al login-->
                        <?php if ($_SESSION['user'] != ''){ ?>
                            <li class="nav-item"><a class="nav-link js-scroll-trigger" href="./dashboard.php#about">Dashboard</a></li>
                            <li class="nav-item"><a class="nav-link js-scroll-trigger" href="./logout.php">Esci</a></li>
                            <li class="nav-item"><a class="nav-link js-scroll-trigger"><?php echo $user;?></a></li>
                        <?php }else { ?>
                            <li class="nav-item"><a class="nav-link js-scroll-trigger" href="./dashboard.php#about">Accedi</a></li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </nav>
<!--/html-->
