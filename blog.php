<base href="https://thegivehub.com/static/"/>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>The Give Hub - Blockchain Charity Simplified</title>
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <meta content="keywords" name="crowdfund,gofundme,charity,social causes,stellar,blockchain">
        <meta content="description" name="The Give Hub is a blockchain backed crowdfunding platform designed to make running campaigns and donating simple and easy to use.">

        <!-- Favicon -->
        <link href="img/favicon.ico" type="image/svg+xml" rel="icon">

        <!-- Google Font -->
        <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        
        <!-- CSS Libraries -->
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
        <link href="lib/flaticon/font/flaticon.css" rel="stylesheet">
        <link href="lib/animate/animate.min.css" rel="stylesheet">
        <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

        <!-- Template Stylesheet -->
        <link href="css/style.css" rel="stylesheet">
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
                                <a href="https://www.instagram.com/the_givehub/"><i class="fab fa-instagram"></i></a>
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
                        <a href="index.html" class="nav-item nav-link">Home</a>
                        <a href="about.html" class="nav-item nav-link">About</a>
                        <a href="causes.html" class="nav-item nav-link">Causes</a>
                        <a href="event.html" class="nav-item nav-link">Events</a>
                        <a href="blog.html" class="nav-item nav-link">Blog</a>
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Pages</a>
                            <div class="dropdown-menu">
                                <a href="single.html" class="dropdown-item">Detail Page</a>
                                <a href="service.html" class="dropdown-item">What We Do</a>
                                <a href="team.html" class="dropdown-item">Meet The Team</a>
                                <a href="donate.html" class="dropdown-item">Donate Now</a>
                                <a href="volunteer.html" class="dropdown-item">Become A Volunteer</a>
                            </div>
                        </div>
                        <a href="contact.html" class="nav-item nav-link">Contact</a>
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
                        <a href="">Home</a>
                        <a href="">Blog</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Page Header End -->
        <!-- Blog Start -->
        <div class="blog">
            <div class="container">
                <div class="section-header text-center">
                    <p>Our Blog</p>
                    <h2>Latest news & articles directly from our blog</h2>
                </div>
                <div class="row">
<?php
$tpl = <<<EOL
    <div class="col-lg-4">
        <div class="blog-item">
            <div class="blog-img">
                <img src="%%post_image%%" alt="Image">
            </div>
            <div class="blog-text">
                <h3><a href="%%url%%">%%title%%</a></h3>
                <h4>%%subtitle%%</h4>
                <p>
                    %%excerpt%%
                </p>
            </div>
            <div class="blog-meta">
                <p><i class="fa fa-user"></i><a href="">Chris Robison</a></p>
                <p><i class="fa fa-comments"></i><a href="">0 Comments</a></p>
            </div>
        </div>
    </div>
EOL;
include("/simple/.env");
$link = new mysqli($env->db->host, $env->db->user, $env->db->pass, "givehub");
$result = $link->query("select * from blog_posts order by id desc");
while ($obj = $result->fetch_object()) {
    //$obj->content = preg_replace("/^.+?<\/h1>/s", "", $obj->content);
    //$c = preg_split("/\n/", $obj->content);
    //array_shift($c);
    //$obj->content = join("\n", $c);
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
                            <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
                            <li class="page-item"><a class="page-link" href="#">1</a></li>
                            <li class="page-item active"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item"><a class="page-link" href="#">Next</a></li>
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
                            <h2>Our Head Office</h2>
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
                            <h2>Popular Links</h2>
                            <a href="about.html">About Us</a>
                            <a href="contact.html">Contact Us</a>
                            <a href="causes.html">Popular Causes</a>
                            <a href="event.html">Upcoming Events</a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="footer-link">
                            <h2>Useful Links</h2>
                            <a href="legal/tos.html">Terms of use</a>
                            <a href="legal/privacy.html">Privacy policy</a>
                            <a href="legal/cookies.html">Cookies</a>
                            <a href="help.html">Help</a>
                            <a href="faq.html">FAQs</a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="footer-newsletter">
                            <h2>Newsletter</h2>
                            <form>
                                <input class="form-control" placeholder="Email goes here">
                                <button class="btn btn-custom">Submit</button>
                                <label>Don't worry, we don't spam!</label>
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
                        <p>&copy; <a href="#">The Give Hub</a>, All Right Reserved.</p>
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
        <script src="lib/easing/easing.min.js"></script>
        <script src="lib/owlcarousel/owl.carousel.min.js"></script>
        <script src="lib/waypoints/waypoints.min.js"></script>
        <script src="lib/counterup/counterup.min.js"></script>
        <script src="lib/parallax/parallax.min.js"></script>
        
        <!-- Contact Javascript File -->
        <script src="mail/jqBootstrapValidation.min.js"></script>
        <script src="mail/contact.js"></script>

        <!-- Template Javascript -->
        <script src="js/main.js"></script>
    </body>
</html>
