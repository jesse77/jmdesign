<!DOCTYPE html>
<!--[if IE 8]>			<html class="ie ie8"> <![endif]-->
<!--[if IE 9]>			<html class="ie ie9"> <![endif]-->
<!--[if gt IE 9]><!-->	<html> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <title>JMDesign | Jesse Martineau</title>

        <meta name="description" content="Jesse Martineau, Edmonton, Alberta, Canada, Photographer, website, film">
        <meta name="author" content="jessemartineau.com">

        <!-- Mobile Metas -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!-- Google Fonts -->
        <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800'
              rel='stylesheet' type='text/css'>
        <link href='http://fonts.googleapis.com/css?family=Oswald:400,300,700'
              rel='stylesheet' type='text/css'>

        <!-- Library CSS -->
        <link rel="stylesheet" href="<?= base_url() ?>/css/bootstrap.css">
        <link rel="stylesheet" href="<?= base_url() ?>/css/fonts/font-awesome/css/font-awesome.css">
        <link rel="stylesheet" href="<?= base_url() ?>/css/animations.css" media="screen">
        <link rel="stylesheet" href="<?= base_url() ?>/css/superfish.css" media="screen">
        <link rel="stylesheet" href="<?= base_url() ?>/css/team-member.css" media="screen">
        <link rel="stylesheet" href="<?= base_url() ?>/css/revolution-slider/css/settings.css" media="screen">
        <link rel="stylesheet" href="<?= base_url() ?>/css/prettyPhoto.css" media="screen">

        <!-- Theme CSS -->
        <link rel="stylesheet" href="<?= base_url() ?>/css/style.css">

        <!-- Skin -->
        <link rel="stylesheet" href="<?= base_url() ?>/css/colors/green.css">

        <!-- Responsive CSS -->
        <link rel="stylesheet" href="<?= base_url() ?>/css/theme-responsive.css">

        <!-- Favicons -->
        <link rel="shortcut icon" href="<?= base_url() ?>/favicon.ico">
        <link rel="apple-touch-icon" href="<?= base_url() ?>/img/ico/apple-touch-icon.png">
        <link rel="apple-touch-icon" sizes="72x72" href="<?= base_url() ?>/img/ico/apple-touch-icon-72.png">
        <link rel="apple-touch-icon" sizes="114x114" href="<?= base_url() ?>/img/ico/apple-touch-icon-114.png">
        <link rel="apple-touch-icon" sizes="144x144" href="<?= base_url() ?>/img/ico/apple-touch-icon-144.png">

        <!-- The HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
            <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
            <script src="js/respond.min.js"></script>
            <![endif]-->
        <!--[if IE]>
            <link rel="stylesheet" href="<?= base_url() ?>/css/ie.css">
            <![endif]-->
        
        <!-- showcss: /server/jmdesign/css/bootstrap.css -->
        <!-- showcss: /server/jmdesign/css/fonts/font-awesome/css/font-awesome.css -->
        <!-- showcss: /server/jmdesign/css/animations.css -->
        <!-- showcss: /server/jmdesign/css/superfish.css -->
        <!-- showcss: /server/jmdesign/css/revolution-slider/css/settings.css  -->
        <!-- showcss: /server/jmdesign/css/prettyPhoto.css  -->
        <!-- showcss: /server/jmdesign/css/style.css -->
        <!-- showcss: /server/jmdesign/css/colors/green.css -->
        <!-- showcss: /server/jmdesign/css/theme-responsive.css -->

        <!-- Scripts -->
        <script src="<?= base_url() ?>/js/jquery.min.js"></script>
        <script src="<?= base_url() ?>/js/bootstrap.js"></script>

    </head>
    <body class="home-3">
        <div class="wrap">
            
            <!-- Header Start -->
            <header id="header">
                
                <!-- Header Top Bar Start -->
                <div class="top-bar">
                    <div class="slidedown collapse">
                        <div class="container">
                            <div class="phone-email pull-left">
                                <a><i class="icon-phone"></i> Phone : +780.937.4722</a>
                                <a href="contact.html"><i class="icon-envelope"></i> Contact us</a>
                            </div>
                            <div class="pull-right">
                                <!-- Place this tag where you want the +1 button to render. -->
                                <div class="g-plusone"></div>
                                <div class="fb-like zin" data-href="http://jessemartineau.com" data-width="250" data-layout="button_count" data-show-faces="true" data-send="false"></div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Main Header Start -->
                <div class="main-header">
                    <div class="container">
                        
                        <!-- TopNav Start -->
                        <div class="topnav navbar-header">
                            <a class="navbar-toggle down-button" data-toggle="collapse" data-target=".slidedown">
                                <i class="icon-angle-down icon-current"></i>
                            </a> 
                        </div>
                        
                        <!-- Logo Start -->
                        <div class="logo pull-left">
                            <h1>
                                <a href="<?= base_url() ?>">
                                    <img src="<?= base_url() ?>/img/logo.png" alt="JMDesign" width="125" height="60">
                                </a>
                            </h1>
                        </div>
                        
                        <!-- Mobile Menu Start -->
                        <div class="mobile navbar-header">
                            <a class="navbar-toggle" data-toggle="collapse" href=".navbar-collapse">
                                <i class="icon-reorder icon-2x"></i>
                            </a> 
                        </div>
                        
                        <!-- Menu Start -->
                        <nav class="collapse navbar-collapse menu">
                            <ul class="nav navbar-nav sf-menu">
                                <li>
                                    <a <?= $data['active'] == 'home' ? 'id="current"' : '' ?>
     					href="<?= base_url() ?>">
                                        Home
                                    </a>
                                    
                                </li>
                                <li>
                                    <a <?= $data['active'] == 'gallery' ? 'id="current"' : '' ?>
                                       href="<?= site_url( 'gallery' ) ?>" class="sf-with-ul">
                                        Gallery
                                    </a>
                                    
                                </li>
                                <li>
                                    <a <?= $data['active'] == 'about' ? 'id="current"' : '' ?>
                                       href="<?= site_url( 'about' ) ?>" class="sf-with-ul">
                                        About Me
                                    </a>
                                </li>
                                <li>
                                    <a <?= $data['active'] == 'communities' ? 'id="current"' : '' ?>
                                       href="communities" class="sf-with-ul">
                                        Communities
                                    </a>
                                    
                                </li>
                                <li>
                                    <a <?= $data['active'] == 'contact' ? 'id="current"' : '' ?>
                                       href="contact.html" class="sf-with-ul">
                                        Contact Me
                                    </a>
                                    
                                </li>
                                
                                <li>
                                    <a href="http://jesseandacamera.blogspot.ca" target="_blank" class="sf-with-ul">
                                        Blog Me
                                    </a>
                                </li>
                                
                                <li>
                                    <a href="https://docs.google.com/forms/d/1V1HRH1vYxKPpM2JQ5_iu4XKyS7aD2Qt4VatOzWVSZ7k/viewform" target="_blank" class="sf-with-ul">
                                        Buy Prints!
                                    </a>
                                </li>
                            </ul>
                        </nav>
                        <!-- Menu End --> 
                    </div>
                </div>
                <!-- Main Header End -->
            </header>
            <!-- Header End --> 
            
            <!-- Content Start -->
            <div id="main">
                <?php if( isset( $data['title'] ) ): ?>
                <div class="breadcrumb-wrapper">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <h2 class="title"><?= $data['title'] ?></h2>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                <!-- Load CodeIgniter Views -->
                <?php if( is_array( $view ) ): ?>
                <?php foreach( $view as $v  ): ?>
		<?php $this->load->view( $v, $data ); ?>
		<?php endforeach; ?>
		<?php else : ?>
		<?php $this->load->view( $view, $data ); ?>
                <?php endif; ?>
            </div>
            <!-- Footer Start -->
            <footer id="footer">
                
                <!-- Footer Top Start -->
                <div class="footer-top">
                    <div class="container">
                        <div class="row">
                            <section class="col-lg-3 col-md-3 col-xs-12 col-sm-3 footer-one">
                                <h3>About</h3>
                                <p> JMDesign operated by Jesse Martineau in Edmonton, Alberta, Canada. Jesse is
                                    passionate about photography and blends a unique humor with his work. He
                                    draws people in with a stunning image and "seals the deal" with a witty
                                    comment that will make you grin.
                                </p>
                            </section>
                            <section class="col-lg-3 col-md-3 col-xs-12 col-sm-3 footer-two">
                                <a class="twitter-timeline" href="https://twitter.com/jessemartineau"
                                   data-widget-id="413874684405374976"> Tweets by @jessemartineau
                                </a>
                            </section>
                            <section class="col-lg-3 col-md-3 col-xs-12 col-sm-3 footer-three">
                                <h3>Contact Us</h3>
                                <ul class="contact-us">
                                    <li>
                                        <i class="icon-phone"></i>
                                        <p>
                                            <strong>Phone:</strong> +780.937.4722
                                        </p>
                                    </li>
                                    <li>
                                        <i class="icon-envelope"></i>
                                        <p>
                                            <strong>Email:</strong>
                                            <a href="contact.html">Contact us here</a>
                                        </p>
                                    </li>
                                </ul>
                            </section>
                            <section class="col-lg-3 col-md-3 col-xs-12 col-sm-3 footer-four">
                                <h3>Flickr Photostream</h3>
                                <ul id="flickrfeed" class="thumbs"></ul>
                            </section>
                        </div>
                    </div>
                </div>
                <!-- Footer Top End -->
                
                <!-- Footer Bottom Start -->
                <div class="footer-bottom">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-12 col-md-6 col-xs-12 col-sm-6 ">
                                <p>
                                    &copy; 2013 JMDesign Jesse Martineau &middot;
                                    <a rel="license" target="_blank"
                                       href="http://creativecommons.org/licenses/by-nc-sa/3.0/deed.en_US">
                                        <img alt="Creative Commons License" style="border-width:0"
                                             src="http://i.creativecommons.org/l/by-nc-sa/3.0/80x15.png" />
                                    </a>
                                    &nbsp This work is licensed under a
                                    <a rel="license" target="_blank"
                                       href="http://creativecommons.org/licenses/by-nc-sa/3.0/deed.en_US" >
                                        Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported
                                        License
                                    </a>.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Footer Bottom End --> 
            </footer>
            
            <!-- Scroll To Top --> 
            <a href="#" class="scrollup">
                <i class="icon-angle-up"></i>
            </a>
        </div>
        <!-- Wrap End -->
        
        <!-- Google+ Link -->
        <script type="text/javascript">
            (function() {
                var po		= document.createElement('script');
                po.type		= 'text/javascript';
                po.async	= true;
                po.src		= 'https://apis.google.com/js/plusone.js';
                var s   	= document.getElementsByTagName('script')[0];
                s.parentNode.insertBefore(po, s);
            })();
        </script>
        
        <!-- Facebook Link -->
        <div id="fb-root"></div>
        <script type="text/javascript">
            (function(d, s, id) {
                var js,
                fjs		= d.getElementsByTagName(s)[0];
                if (d.getElementById(id))
                    return;
                js		= d.createElement(s); js.id = id;
                js.src		= "//connect.facebook.net/en_US/all.js#xfbml=1";
                fjs.parentNode.insertBefore( js, fjs );
            } ( document, 'script', 'facebook-jssdk' ) );
        </script>
        
        <!-- Google Analytics -->
        <script type="text/javascript">
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                                     m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
                                    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
            
            ga('create', 'UA-46682215-1', 'jessemartineau.com');
            ga('send', 'pageview');
            
        </script>
        
        <script src="<?= base_url() ?>/js/jquery.parallax.js"></script> 
        <script src="<?= base_url() ?>/js/modernizr-2.6.2.min.js"></script> 
        <script src="<?= base_url() ?>/js/revolution-slider/js/jquery.themepunch.revolution.min.js"></script>
        <script src="<?= base_url() ?>/js/jquery.nivo.slider.pack.js"></script>
        <script src="<?= base_url() ?>/js/jquery.prettyPhoto.js"></script>
        <script src="<?= base_url() ?>/js/superfish.js"></script>
        <script src="<?= base_url() ?>/js/tweetMachine.js"></script>
        <script src="<?= base_url() ?>/js/tytabs.js"></script>
        <script src="<?= base_url() ?>/js/jquery.gmap.min.js"></script>
        <script src="<?= base_url() ?>/js/circularnav.js"></script>
        <script src="<?= base_url() ?>/js/jquery.sticky.js"></script>
        <script src="<?= base_url() ?>/js/jflickrfeed.js"></script>
        <script src="<?= base_url() ?>/js/imagesloaded.pkgd.min.js"></script>
        <script src="<?= base_url() ?>/js/waypoints.min.js"></script>
        <script src="<?= base_url() ?>/js/custom.js"></script>
        <script src="<?= base_url() ?>/js/jquery.isotope.js"></script>
        <script src="<?= base_url() ?>/js/portfolio.js"></script>
    </body>
</html>
