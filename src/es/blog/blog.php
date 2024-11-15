<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>The Give Hub - Blockchain Charity Simplified</title>
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <meta content="keywords" name="crowdfund,gofundme,charity,social causes,stellar,blockchain">
        <meta content="description" name="The Give Hub is a blockchain backed crowdfunding platform designed to make running campaigns and donating simple and easy to use.">

        <!-- Favicon -->
        <link href="/img/favicon.svg" type="image/svg+xml" rel="icon">

        <!-- Google Font -->
        <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        
        <!-- CSS Libraries -->
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
        <link href="/lib/flaticon/font/flaticon.css" rel="stylesheet">
        <link href="/lib/animate/animate.min.css" rel="stylesheet">
        <link href="/lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

        <!-- Template Stylesheet -->
        <link href="/css/style.css" rel="stylesheet">
    </head>

    <body>

        <!-- Top Bar Start -->
        <div class="top-bar d-none d-md-block">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-8">
                        <div class="top-bar-left">
                            <div class="text">
                                <i class="fa fa-phone-alt"></i>
                                <p>(415) 300-0180</p>
                            </div>
                            <div class="text">
                                <i class="fa fa-envelope"></i>
                                <p>info@thegivehub.com</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="top-bar-right">
                            <div class="social">
                                <a href="https://x.com/thegivehub97501"><i class="fab fa-twitter"></i></a>
                                <a href="https://www.facebook.com/people/The-Give-Hub/61568306136450/"><i class="fab fa-facebook-f"></i></a>
                                <a href="https://linkedin.com/company/thegivehub"><i class="fab fa-linkedin-in"></i></a>
                            </div>
                            <div class="lang">
                                <a href="/en/"><img class="lang-icon" src="/img/hello.svg"></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Top Bar End -->

        <!-- Nav Bar Start -->
        <div class="navbar navbar-expand-lg bg-dark navbar-dark">
            <div class="container-fluid">
                <a href="index.html" class="navbar-brand">The Give Hub</a>
                <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">
                    <div class="navbar-nav ml-auto">
                        <a href="index.html" class="nav-item nav-link">Inicio</a>
                        <div class="nav-item dropdown">
                            <a href="about.html" class="nav-link dropdown-toggle" data-toggle="dropdown">Acerca de</a>
                            <div class="dropdown-menu">
                                <a href="service.html" class="dropdown-item">Qué Hacemos</a>
                                <a href="team.html" class="dropdown-item">Conoce al Equipo</a>
                                <a href="event.html" class="dropdown-item">Eventos</a>
                            </div>
                        </div>
                        <a href="blog.php" class="nav-item nav-link">Blog</a>
                        <a href="causes.html" class="nav-item nav-link">Causas</a>
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Participa</a>
                            <div class="dropdown-menu">
                                <a href="donate.html" class="dropdown-item">Dona Ahora</a>
                                <a href="volunteer.html" class="dropdown-item">Hazte Voluntario</a>
                                <a href="campaign.html" class="dropdown-item">Inicia una Campaña</a>
                                <a href="register-cause.html" class="dropdown-item">Registrar Causa</a>
                            </div>
                        </div>
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Páginas</a>
                            <div class="dropdown-menu">
                                <a href="user-docs.html" class="dropdown-item">Documentación del Usuario</a>
                                <a href="api.html" class="dropdown-item">Documentación API</a>
                                <a href="faqs.html" class="dropdown-item">Preguntas Frecuentes</a>
                                <a href="architecture.html" class="dropdown-item">Arquitectura de la Plataforma</a>
                                <a href="pitchdeck.html" class="dropdown-item">Presentación de Financiación</a>
                            </div>
                        </div>
                         <a href="contact.html" class="nav-item nav-link">Contacto</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Nav Bar End -->

       <!-- Page Header Start -->
        <div class="page-header">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h2>Blog</h2>
                    </div>
                    <div class="col-12">
                        <a href="/es/">Inicio</a>
                        <a href="/es/blog/">Blog</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Page Header End -->
        <!-- Blog Start -->
        <div class="blog">
            <div class="container">
                <div class="section-header text-center">
                    <p>Nuestro blog</p>
                    <h2>Últimas noticias y artículos directamente desde nuestro blog</h2>
                </div>
                <div class="row">
<?php
$tpl = <<<EOL
    <div class="col-lg-4">
        <div class="blog-item">
            <div class="blog-head">
                <h4><a href="%%url%%">“%%title%%”</a></h3>
                <h4 style="font-size: 16px;font-weight:400;"><a href="%%url%%">%%subtitle%%</a></h4>
            </div>
            <div class="blog-img">
                <img src="%%post_image%%" alt="Image">
            </div>
            <div class="blog-text">
                <p>
                    %%excerpt%%
                </p>
            </div>
            <div class="blog-foot" style="margin:0.5em 1em 1em;text-align:right;padding-right:3em;font-size:15px;"><a style="color:#00c;text-decoration:underline;" href="%%url%%">Mas...</a></div>
            <div class="blog-meta" style="white-space: nowrap;">
                <p><i class="fa fa-user"></i><a href="">Chris Robison</a></p>
                <p><i class="fa fa-clock"></i>%%readtime%%</p>
                <p><i class="fa fa-comments"></i><a href="">0</a></p>
            </div>
        </div>
    </div>
EOL;
$in = $_REQUEST;
$page =  (isset($in['page'])) ? $in['page'] : 0;
$xtra = " LIMIT " . ($page * 6) . ", 6";

$link = new mysqli("localhost", "pimp", "pimpin", "givehub");
$result = $link->query("SELECT count(*) as posts FROM posts where language='es'");
$postCount = $result->fetch_object();

$result = $link->query("SELECT * FROM posts WHERE language='es' ORDER BY ID DESC $xtra");
while ($obj = $result->fetch_object()) {
    //$obj->content = preg_replace("/^.+?<\/h1>/s", "", $obj->content);
    //$c = preg_split("/\n/", $obj->content);
    //array_shift($c);
    //$obj->content = join("\n", $c);
    $words = preg_split("/\s+/", $obj->markdown);
    $obj->readtime = ceil(count($words) / 200) . " min";
    $out = preg_replace_callback("/\%\%(.+?)\%\%/", function($m) {
        global $obj;
        if (isset($obj->{$m[1]})) {
            return $obj->{$m[1]};
        } else {
            return "";
        }
    }, $tpl);
    print $out;
}
?>
                </div>
                <div class="row">
                    <div class="col-12">
                        <ul class="pagination justify-content-center">
<?php

$perpage = 6;
$prevDisabled = "";
$nextDisabled = "";
$prevpage = $page - 1;
$nextpage = $page + 1;
if ($page == 0) {
    $prevDisabled = " disabled";
    $prevpage = 0;
} 
if ($page > (floor($postCount->posts / $perpage)-1))  {
    $nextDisabled = " disabled";
    $nextpage = 0;
}
$lastpage = (floor($postCount->posts / $perpage));

print <<<EOT
<li class="page-item{$prevDisabled}"><a class="page-link" href="blog.php?page=0">&lt;&lt;</a></li>
<li class="page-item{$prevDisabled}"><a class="page-link" href="blog.php?page={$prevpage}">&lt;</a></li>
EOT;

for ($i=0; $i<ceil($postCount->posts / $perpage); $i++) {
    $active = ($page == $i) ? ' active' : ''; 
    print '<li class="page-item' . $active . '"><a class="page-link" href="blog.php?page='.$i.'">'.($i + 1).'</a></li>';
}
print <<<EOT
<li class="page-item{$nextDisabled}"><a class="page-link" href="blog.php?page={$nextpage}">&gt;</a></li>
<li class="page-item{$nextDisabled}"><a class="page-link" href="blog.php?page={$lastpage}">&gt;&gt;</a></li>
EOT;
?>
                        </ul> 
                    </div>
                </div>
            </div>
        </div>
        <!-- Blog End -->
        <!-- Footer Start -->
        <div class="footer">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 col-md-6">
                        <div class="footer-contact">
                            <h2>Nuestra Oficina Central</h2>
                            <p><i class="fa fa-map-marker-alt"></i>621 Holloway Ave, <br>San Francisco CA, USA</p>
                            <p><i class="fa fa-phone-alt"></i>(415) 300-0180</p>
                            <p><i class="fa fa-envelope"></i>info@thegivehub.com</p>
                            <div class="footer-social">
                                <a class="btn btn-custom" href="https://twitter.com/thegivehub"><i class="fab fa-twitter"></i></a>
                                <a class="btn btn-custom" href="https://www.facebook.com/people/The-Give-Hub/61568306136450/"><i class="fab fa-facebook-f"></i></a>
                                <a class="btn btn-custom" href="https://youtube.com/@thegivehub"><i class="fab fa-youtube"></i></a>
                                <a class="btn btn-custom" href="https://www.instagram.com/the_givehub/"><i class="fab fa-instagram"></i></a>
                                <a class="btn btn-custom" href="https://linkedin.com/company/thegivehub"><i class="fab fa-linkedin-in"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="footer-link">
                            <h2>Enlaces Populares</h2>
                            <a href="about.html">Sobre Nosotros</a>
                            <a href="contact.html">Contáctenos</a>
                            <a href="causes.html">Causas Populares</a>
                            <a href="event.html">Próximos Eventos</a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="footer-link">
                            <h2>Enlaces Útiles</h2>
                            <a href="legal/tos.html">Términos de Uso</a>
                            <a href="legal/privacy.html">Política de Privacidad</a>
                            <a href="legal/cookies.html">Política de Cookies</a>
                            <a href="help.html">Ayuda</a>
                            <a href="faq.html">Preguntas Frecuentes</a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="footer-newsletter">
                            <h2>Boletín Informativo</h2>
                            <form>
                                <input class="form-control" placeholder="Correo electrónico aquí">
                                <button class="btn btn-custom">Enviar</button>
                                <label>No te preocupes, no enviaremos spam</label>
                            </form>
                        </div>
                        <div>
                    </div>
                </div>
            </div>
            <div class="container copyright">
                <div class="row">
                    <div class="col-md-6">
                   </div>
                    <div class="col-md-6">
                        <p>&copy; <a href="#">The Give Hub</a>, Todos los derechos reservados.</p>
                    </div>
                    <div class="col-md-6">
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer End -->

        <!-- Back to top button -->
        <a href="#" class="back-to-top"><i class="fa fa-chevron-up"></i></a>
        
        <!-- Pre Loader -->
        <div id="loader" class="show">
            <div class="loader"></div>
        </div>

        <!-- JavaScript Libraries -->
        <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
        <script src="/lib/easing/easing.min.js"></script>
        <script src="/lib/owlcarousel/owl.carousel.min.js"></script>
        <script src="/lib/waypoints/waypoints.min.js"></script>
        <script src="/lib/counterup/counterup.min.js"></script>
        <script src="/lib/parallax/parallax.min.js"></script>
        
        <!-- Contact Javascript File -->
        <script src="/mail/jqBootstrapValidation.min.js"></script>
        <script src="/mail/contact.js"></script>

        <!-- Template Javascript -->
        <script src="/js/main.js"></script>
    </body>
</html>
