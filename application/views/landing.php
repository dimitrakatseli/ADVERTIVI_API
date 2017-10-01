<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="ADVERTIVI">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <!-- SITE TITLE -->
    <title>ADVERTIVI- Campaign Automation</title>

    <!-- =========================
     FAV AND TOUCH ICONS AND FONT  
    ============================== -->
    <link rel="icon" href="/favicon.ico">
    <link href='https://fonts.googleapis.com/css?family=Cabin:400,600|Open+Sans:300,600,400' rel='stylesheet'>

    <!-- =========================
     STYLESHEETS   
    ============================== -->
    <!-- BOOTSTRAP AND ANIMATION -->
    <link rel="stylesheet" href="<?php echo base_url();?>/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url();?>/css/animate.min.css">

    <!-- FONT ICONS -->
    <link rel="stylesheet" href="<?php echo base_url();?>/assets/icon/icons.css">

    <!-- CAROUSEL AND LIGHTBOX -->
    <link rel="stylesheet" href="<?php echo base_url();?>/css/owl.theme.css">
    <link rel="stylesheet" href="<?php echo base_url();?>/css/owl.carousel.css">
    <link rel="stylesheet" href="<?php echo base_url();?>/css/nivo-lightbox.css">
    <link rel="stylesheet" href="<?php echo base_url();?>/css/default.css">

    <!-- CUSTOM STYLESHEETS -->
    <link rel="stylesheet" href="<?php echo base_url();?>/css/styles.css">

    <!-- RESPONSIVE FIXES -->
    <link rel="stylesheet" href="<?php echo base_url();?>/css/responsive.css">

    <!-- COLORS -->
    <link rel="stylesheet" href="<?php echo base_url();?>/css/colors/blue.css">


    <!-- CPAPI -->
    <link rel="stylesheet" href="<?php echo base_url();?>/css/advertivi.css">

</head>
<body>

<!-- =========================
 PRE LOADER       
============================== -->
<div class="preloader">
    <div class="status">
        &nbsp;
    </div>
</div>

<!-- =========================
 SECTION: HOME / HEADER  
============================== -->
<header class="header" data-stellar-background-ratio="0.5" id="home">

    <!-- COLOR OVER IMAGE -->
    <div class="overlay-layer">
        <!-- STICKY NAVIGATION -->
        <div class="navbar navbar-inverse bs-docs-nav navbar-fixed-top sticky-navigation" role="navigation">
            <div class="container">
                <div class="navbar-header">

                    <!-- LOGO ON STICKY NAV BAR -->
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#stamp-navigation">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-grid-2x2"></span>
                    </button>

                    <!-- LOGO -->
                    <a class="navbar-brand" href="/">
                        ADVERTIVI-API
                    </a>

                </div>

                <!-- TOP BAR -->
                <div class="navbar-collapse collapse" id="stamp-navigation">

                                                                                                    
                    <!-- LOGIN REGISTER -->
                    <ul class="nav navbar-nav navbar-right login-register small-text">
                        
                        <li class="register-button inpage-scroll">
                            <a href="<?php echo base_url();?>user/login" class="btn btn-default">Login</a>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- /END CONTAINER -->
        </div>
        <!-- /END STICKY NAVIGATION -->


        <!-- CONTAINER -->
        <div class="container">

            <!-- ONLY LOGO ON HEADER 
                  <div class="only-logo">
                     <div class="navbar">
                            <div class="navbar-header">
                               <img src="images/logo-2.png" alt="">
                            </div>
                     </div>
                  </div>
                  /END ONLY LOGO ON HEADER -->

            <div class="row">
                <h1 style="color:#fff;font-family:'FuturaRound-Bold', Helvetica Neue, Sans-serif;">ADVERTIVI-API</h1>                                                                                                                                                
            </div>
        </div>
    </div>

    <!-- IMAGE -->
    <div class="container overlay1">
        <div class="row text-right">
            <div class="slogan">The Best Way for Campaign Automation</div>
        </div>
    </div>
</header>


<!-- =========================
 SECTION: CLIENTS LOGOs
============================== -->
<div class="clients white-bg">
    <ul class="client-logos">
        <li>
            <img src="<?php echo base_url();?>/images/companies/1.png" alt="">
        </li>
        <li>
            <img src="<?php echo base_url();?>/images/companies/2.png" alt="">
        </li>
        <li>
            <img src="<?php echo base_url();?>/images/companies/3.png" alt="">
        </li>
        <li>
            <img src="<?php echo base_url();?>/images/companies/4.png" alt="">
        </li>
        <li>
            <img src="<?php echo base_url();?>/images/companies/5.png" alt="">
        </li>
        <li>
            <img src="<?php echo base_url();?>/images/companies/6.png" alt="">
        </li>
    </ul>
</div>

<section class="services grey-bg" id="section1">
    <div class="container">
        <div class="section-header" style="margin-top: 60px;">
            <div class="sub-heading" style="margin-bottom: 20px;">We here at Affise see CPAPI as our response to the challenges CPA marketers face
                today.
                Precision-driven automation. Ultimate ease of use. Built with maximizing your profits in mind. This is
                CPAPI. Find out why it is right for you by checking out the advantages right here below.
            </div>
            <div class="colored-line">
            </div>
        </div>
    </div>
</section>


<!-- =========================
 SECTION: INFO 1
============================== -->
<section class="brief white-bg-border text-left" id="section2">
    <div class="container">
        <div class="row">
            <!-- SCREENSHOTS -->
            <div class="col-md-12">
                <!-- SINGLE SCREENSHOT -->
                <div class="screenshot">
                    <!-- SINGLE ELEMENT -->
                    <!-- BRIEF IMAGE -->
                    <div class="col-md-6 pull-right wow fadeInLeft" data-wow-offset="20" data-wow-duration="1.75s">
                        <div class="brief-image-right">
                            <a href="<?php echo base_url();?>/images/screenshots/1@2x.png" data-lightbox-gallery="gallery1">
                                <img src="<?php echo base_url();?>/images/screenshots/1.png" alt=""/>
                            </a>
                        </div>
                    </div>

                    <!-- BRIEF HEADING -->
                    <div class="col-md-6 content-section pull-left wow fadeInRight" data-wow-offset="20"
                         data-wow-duration="1.75s">
                        <div class="small-text-medium uppercase colored-text">
                            Connect
                        </div>
                        <h2 class="text-left dark-text"><strong>Easy</strong> two-way communication</h2>
                        <div class="colored-line-left">
                        </div>
                        <p class="text-left">
                            Add affiliate programs, sync data, stop and start offers. All of that takes literally a
                            couple of clicks and only a tiny bit of your valuable time. Compatibility concerns? Having
                            to hire coders? It’s so easy to forget about these things now.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- =========================
 SECTION: INFO 1
============================== -->
<section class="brief white-bg-border text-left" id="section3">
    <div class="container">
        <div class="row">
            <!-- SCREENSHOTS -->
            <div class="col-md-12">
                <!-- SINGLE SCREENSHOT -->
                <div class="screenshot">
                    <!-- SINGLE ELEMENT -->
                    <!-- BRIEF IMAGE -->
                    <div class="col-md-6 pull-left wow fadeInRight" data-wow-offset="20" data-wow-duration="1.75s">
                        <div class="brief-image-left">
                            <a href="<?php echo base_url();?>/images/screenshots/2@2x.png" data-lightbox-gallery="gallery1">
                                <img src="<?php echo base_url();?>/images/screenshots/2.png" alt=""/>
                            </a>
                        </div>
                    </div>

                    <!-- BRIEF HEADING -->
                    <div class="col-md-6 content-section pull-right wow fadeInLeft" data-wow-offset="20"
                         data-wow-duration="1.75s">
                        <div class="small-text-medium uppercase colored-text">
                            Unify
                        </div>
                        <h2 class="text-left dark-text"><strong>Rebrokering</strong>, reinvented</h2>
                        <div class="colored-line-left">
                        </div>
                        <p class="text-left">
                            Grab as many offers from partners as you like in just a few clicks. Specify your margin and
                            rebrokering parameters, and you are done. Analysing and moving offers manually? You do not
                            have to do this anymore.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- =========================
 SECTION: INFO 1
============================== -->
<section class="brief white-bg-border text-left" id="section4">
    <div class="container">
        <div class="row">
            <!-- SCREENSHOTS -->
            <div class="col-md-12">
                <!-- SINGLE SCREENSHOT -->
                <div class="screenshot">
                    <!-- SINGLE ELEMENT -->
                    <!-- BRIEF IMAGE -->
                    <div class="col-md-6 pull-right wow fadeInLeft" data-wow-offset="20" data-wow-duration="1.75s">
                        <div class="brief-image-right">
                            <a href="<?php echo base_url();?>/images/screenshots/3@2x.png" data-lightbox-gallery="gallery1">
                                <img src="<?php echo base_url();?>/images/screenshots/3.png" alt=""/>
                            </a>
                        </div>
                    </div>

                    <!-- BRIEF HEADING -->
                    <div class="col-md-6 content-section pull-left wow fadeInRight" data-wow-offset="20"
                         data-wow-duration="1.75s">
                        <div class="small-text-medium uppercase colored-text">
                            Automate
                        </div>
                        <h2 class="text-left dark-text"><strong>The power</strong> of automation</h2>
                        <div class="colored-line-left">
                        </div>
                        <p class="text-left">
                            Create automated triggers that take the human factor out of the equation and maximize your
                            KPI. Take advantage of our faster, more scalable system. Let the system test an offer with
                            multiple partners and tell you which one makes you more money. Focus on the important things
                            while CPAPI does the rest.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- =========================
 SECTION: CALL TO ACTION   
============================== -->


<!-- FOOTER -->
<footer class="footer grey-bg">
    © Advertivi. All Rights Reserved.<br/>

    <!-- OPTIONAL FOOTER LINKS -->
    <ul class="footer-links small-text">
        <li><a href="http://advertivi.com/#intro" class="dark-text">About</a>
        </li>
        <li><a href="http://advertivi.com/#contact" class="dark-text">Contact</a>
        </li>
        
    </ul>

    <!-- SOCIAL ICONS -->
    <ul class="social-icons">
        <li>
            <a href="#">
                <span class="icon-social-facebook transparent-text-dark"></span>
            </a>
        </li>
        <li>
            <a href="#">
                <span class="icon-social-twitter transparent-text-dark"></span>
            </a>
        </li>
        <li>
            <a href="#">
                <span class="icon-social-linkedin transparent-text-dark"></span>
            </a>
        </li>
    </ul>

</footer>


<!-- =========================
 SCRIPTS   
============================== -->
<script src="<?php echo base_url();?>/js/jquery.min.js"></script>
<script>
    /* PRE LOADER */
    jQuery(window).load(function () {
        "use strict";
        jQuery(".status").fadeOut();
        jQuery(".preloader").delay(1000).fadeOut("slow");
    })
</script>
<script src="<?php echo base_url();?>/js/smoothscroll.js"></script>
<script src="<?php echo base_url();?>/js/bootstrap.min.js"></script>
<script src="<?php echo base_url();?>/js/jquery.nav.js"></script>
<script src="<?php echo base_url();?>/js/wow.min.js"></script>
<script src="<?php echo base_url();?>/js/nivo-lightbox.min.js"></script>
<script src="<?php echo base_url();?>/js/owl.carousel.min.js"></script>
<script src="<?php echo base_url();?>/js/jquery.stellar.min.js"></script>
<script src="<?php echo base_url();?>/js/retina.min.js"></script>
<script src="<?php echo base_url();?>/js/jquery.ajaxchimp.min.js"></script>

<!-- CUSTOM JS  -->
<script src="<?php echo base_url();?>/js/custom.js"></script>
</html>