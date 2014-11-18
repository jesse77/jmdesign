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

        <!-- Library CSS -->
        <link rel="stylesheet" href="<?= base_url() ?>/css/bootstrap.css">
        <link rel="stylesheet" href="<?= base_url() ?>css/bootstrap-glyphicons.css">

        <link rel="stylesheet" href="<?= base_url() ?>/css/custom.css">

        <!-- showcss: /server/jmdesign/css/bootstrap.css -->
        <!-- showcss: /server/jmdesign/css/bootstrap-glyphicons.css -->

        <!-- Scripts -->
        <script src="<?= base_url() ?>/js/jquery.min.js"></script>
        <script src="<?= base_url() ?>/js/bootstrap.js"></script>

    </head>
    <body>
        <nav class="navbar navbar-inverse" role="navigation">
            <div class="container-fluid">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <div class="logo">
                        <h1>
                            <a href="<?= base_url() ?>">
                                <img src="<?= base_url() ?>/img/logo.png" alt="JMDesign" width="125" height="60">
                            </a>
                            <span class="text-muted" style="top: 2px; position: relative;">
                                ADMIN
                            </span>
                        </h1>
                    </div>
                </div>
                <ul class="nav navbar-nav sf-menu" style="margin-top: 20px">
                    <li>
                        <a href="<?= site_url('admin/photos') ?>">
                            Photos
                        </a>
                    </li>
                    <li>
                        <a href="<?= site_url('admin/tags') ?>">
                            Tags
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
        <div class="container">
            <?php if( $this->input->get( 'error' ) ): ?>
            <div class="alert alert-danger">
                <?= $this->input->get('error'); ?>
            </div>
            <?php endif; ?>
        <?php
     	    if( is_array( $view ) ) {
		foreach( $view as $v  ) {
		    $this->load->view( $v, $data );
		}
	    }
	    else {
		$this->load->view( $view, $data );
	    }
	?>
        </div>
    </body>
</html>
