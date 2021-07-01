<?php
session_start();
$curPageName = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
$user_admin="comuneisernia";
//$gruppo = 'comuneisernia3_group';
//commento 2
$cliente = 'Comune di Isernia';
?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Sistema Istanze CDU del <?php echo $cliente; ?></title>
        <!-- Richiama stili e file css-->
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
        <!-- Navigation-->
        <!--nav class="navbar navbar-expand-lg navbar-light fixed-top py-3" id="mainNav">
            <div class="container">
                <a class="navbar-brand js-scroll-trigger" href="#page-top">Home</a>
                <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <ul class="navbar-nav ml-auto my-2 my-lg-0">
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" href="#about">Come funziona</a></li>
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" href="#account">Crea account</a></li>
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" href="#moduli">Moduli</a></li>
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" href="#contact">Contatti</a></li>
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" href="./dashboard.php">Accedi</a></li>
                    </ul>
                </div>
            </div>
        </nav-->
        <!-- Masthead-->
        <header class="masthead">
            <div class="container h-100">
                <div class="row h-100 align-items-center justify-content-center text-center">
                    <div class="col-lg-10 align-self-end">
                        <h1 class="text-uppercase text-white font-weight-bold">Sistema Istanze CDU del <?php echo $cliente; ?></h1>
                        <hr class="divider my-4" />
                    </div>
                    <div class="col-lg-8 align-self-baseline">
                        <p class="text-white-75 font-weight-light mb-5">Il nuovo Sistema Online per la richiesta dei Certificati di Destinazione Urbanistica del <?php echo $cliente; ?><br>
                        Creando il tuo account potrai gestire l'intero processo con pochi e semplici click, dall'invio della richiesta fino al Download del CDU. </p>
                        <a class="btn btn-primary btn-xl js-scroll-trigger" href="#about">Scopri di più</a>
                    </div>
                </div>
            </div>
        </header>
        <!-- About-->
        <section class="page-section" id="about">
            <div class="container">
                <h2 class="text-center mt-0">Come Funziona?</h2>
                <hr class="divider my-4" />
                <div class="row">
                    <div class="col-lg-3 col-md-6 text-center">
                        <div class="mt-5">
                            <i class="fas fa-4x fa-user-lock text-primary mb-4"></i>
                            <h3 class="h4 mb-2">Crea il tuo account</h3>
                            <p class="text-muted mb-0">Per accedere al sistema devi avere un account e effettuare il login, le tue informazioni saranno protette da password e solo tu potrai vederle.</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 text-center">
                        <div class="mt-5">
                            <i class="fas fa-4x fa-file-alt text-primary mb-4"></i>
                            <h3 class="h4 mb-2">Richiedi il CDU</h3>
                            <p class="text-muted mb-0">Entrando con utente e password puoi richiedere il CDU selezionando per i terreni di tuo interesse selezionando numero di foglio e mappale. </p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 text-center">
                        <div class="mt-5">
                            <i class="fas fa-4x fa-file-invoice-dollar text-primary mb-4"></i>
                            <h3 class="h4 mb-2">Pagamento</h3>
                            <p class="text-muted mb-0">Una volta pagati il bollo e diritti di segreteria, carica il file di autocertificazione di avvenuto pagamento e invia la richiesta al Comune.</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 text-center">
                        <div class="mt-5">
                            <i class="fas fa-4x fa-file-download text-primary mb-4"></i>
                            <h3 class="h4 mb-2">Scarica il CDU</h3>
                            <p class="text-muted mb-0">Il Comune compilerà il CDU e, pagati i bolli, potrai scaricare il file del CDU direttamente dalla tua dashboard accedendo con utente e password.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Account-->
        <section class="page-section bg-primary" id="account">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8 text-center">
                        <h2 class="text-white mt-0">Non sei ancora registrato?</h2>
                        <hr class="divider light my-4" />
                        <p class="text-white-50 mb-4">Compila il form per creare il tuo account, inserisci tutte le informazioni richieste e inizia subito a gestire in autonomia le tue istanze di CDU!</p>
                        <a class="btn btn-light btn-xl js-scroll-trigger" href="./form_external_user.php">Crea il tuo Account!</a>
                    </div>
                </div>
            </div>
        </section>
        <!-- Portfolio-->
        <!--div id="portfolio">
            <div class="container-fluid p-0">
                <div class="row no-gutters">
                    <div class="col-lg-4 col-sm-6">
                        <a class="portfolio-box" href="assets/img/portfolio/fullsize/1.jpg">
                            <img class="img-fluid" src="assets/img/portfolio/thumbnails/1.jpg" alt="" />
                            <div class="portfolio-box-caption">
                                <div class="project-category text-white-50">Category</div>
                                <div class="project-name">Project Name</div>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-4 col-sm-6">
                        <a class="portfolio-box" href="assets/img/portfolio/fullsize/2.jpg">
                            <img class="img-fluid" src="assets/img/portfolio/thumbnails/2.jpg" alt="" />
                            <div class="portfolio-box-caption">
                                <div class="project-category text-white-50">Category</div>
                                <div class="project-name">Project Name</div>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-4 col-sm-6">
                        <a class="portfolio-box" href="assets/img/portfolio/fullsize/3.jpg">
                            <img class="img-fluid" src="assets/img/portfolio/thumbnails/3.jpg" alt="" />
                            <div class="portfolio-box-caption">
                                <div class="project-category text-white-50">Category</div>
                                <div class="project-name">Project Name</div>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-4 col-sm-6">
                        <a class="portfolio-box" href="assets/img/portfolio/fullsize/4.jpg">
                            <img class="img-fluid" src="assets/img/portfolio/thumbnails/4.jpg" alt="" />
                            <div class="portfolio-box-caption">
                                <div class="project-category text-white-50">Category</div>
                                <div class="project-name">Project Name</div>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-4 col-sm-6">
                        <a class="portfolio-box" href="assets/img/portfolio/fullsize/5.jpg">
                            <img class="img-fluid" src="assets/img/portfolio/thumbnails/5.jpg" alt="" />
                            <div class="portfolio-box-caption">
                                <div class="project-category text-white-50">Category</div>
                                <div class="project-name">Project Name</div>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-4 col-sm-6">
                        <a class="portfolio-box" href="assets/img/portfolio/fullsize/6.jpg">
                            <img class="img-fluid" src="assets/img/portfolio/thumbnails/6.jpg" alt="" />
                            <div class="portfolio-box-caption p-3">
                                <div class="project-category text-white-50">Category</div>
                                <div class="project-name">Project Name</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div-->
        <!-- Scarica autocertificazioni -->
        <!--section class="page-section bg-dark text-white" id="moduli">
            <div class="container text-center">
                <h2 class="mb-4">Scarica i moduli per le autocertificazioni di pagamento</h2>
                <a style="margin-right:20px;" class="btn btn-light btn-xl" href="./download2.php">Diritti Istruttori</a>
                <a style="margin-left:20px;" class="btn btn-light btn-xl" href="./download.php">Marca da Bollo</a>
            </div>
        </section-->
<!-- Richiama fotter della pagina con contatti e librerie javascript-->
<?php
require('footer.php');
require('req_bottom.php');
?>
    </body>
</html>
