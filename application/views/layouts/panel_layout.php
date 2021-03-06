<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?php echo $this->lang->line('app_site_title') ?></title>

    <!-- Bootstrap Core CSS -->
    <link href="<?php echo site_url('css/bootstrap.min.css') ?>" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="<?php echo site_url('css/shop-item.css') ?>" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?php echo site_url() ?>">CaptchaWS</a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li>
                        <a href="#"><?php echo $this->session->userdata('email'); ?></a>
                    </li>
                    <li>
                        <a href="<?php echo site_url('sign/documentation') ?>">Docomention</a>
                    </li>
                </ul>
                <ul class="nav navbar-nav pull-right">
                    <li>
                        <a href="<?php echo site_url('panel/dashboard/api_add') ?>">Add API</a>
                    </li>
                    <li>
                        <a href="<?php echo site_url('panel/dashboard/api_list') ?>">API List</a>
                    </li>
                    <li>
                        <a href="<?php echo site_url('sign/out') ?>">Logout</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>

    <!-- Page Content -->
    <div class="container">
        <?php 
        if($this->session->flashdata('error'))
            echo '<p class="alert alert-danger" >' . $this->session->flashdata('error') . '</p>'; 
        if($this->session->flashdata('info'))
            echo '<p class="alert alert-info" >' . $this->session->flashdata('info') . '</p>'; 
        if($this->session->flashdata('success'))
            echo '<p class="alert alert-success" >' . $this->session->flashdata('success') . '</p>'; 
        ?>
        <div class="row">

            <div class="col-md-9">

                <?php 
                //load content view "should set in controller"
                $this->load->view($content_view, $c_data) 
                ?>    
            </div>

        </div>

    </div>
    <!-- /.container -->

    <div class="container">

        <hr>

        <!-- Footer -->
        <footer>
            <div class="row">
                <div class="col-lg-12">
                    <p><?php echo$this->lang->line('app_layout_footer_copyright')?></p>
                </div>
            </div>
        </footer>

    </div>
    <!-- /.container -->

    <!-- jQuery -->
    <script src="<?php echo site_url('js/jquery.js') ?>"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="<?php echo site_url('js/bootstrap.min.js') ?>"></script>

</body>

</html>
