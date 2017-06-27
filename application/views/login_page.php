<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if (isset($this->session->userdata['logged_in'])) {
header("location: ".base_url()."sources");
}

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
    <!-- FONT ICONS -->
    <link rel="stylesheet" href="<?php echo base_url();?>/assets/icon/icons.css">

    <!-- CAROUSEL AND LIGHTBOX -->
    
    <!-- CUSTOM STYLESHEETS -->
    <link rel="stylesheet" href="<?php echo base_url();?>/css/styles.css">

    <!-- RESPONSIVE FIXES -->
    <link rel="stylesheet" href="<?php echo base_url();?>/css/responsive.css">

    <!-- COLORS -->
    <link rel="stylesheet" href="<?php echo base_url();?>/css/colors/blue.css">


    <!-- ADVERTIVI -->
    <link rel="stylesheet" href="<?php echo base_url();?>/css/advertivi.css">

</head>
<body style="background-color:#edf0f5;">
	<div class="container">
		<div class="row">
					
			<div class="col-md-2"></div>
			<div class="col-md-8">
				<div class="panel" style="margin-top:100px;">
					<div class="panel-body">
						<h2>Sign in</h2>
						<?php echo form_open('user/login','class="form-horizontal" id="myform"'); ?>
						<?php
						echo "<div class='error_msg'>";
						if (isset($error_message)) {
						echo $error_message;
						}
						echo validation_errors();
						echo "</div>";
						?>
						<div class="form-group">
                                <label class="col-sm-2 control-label" for="email">Username</label>
                                <div class="col-sm-8">
                                    <input type="text" id="email" name="email" placeholder="Username" class="form-control" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="password">Password</label>
                                <div class="col-sm-8">
                                    <input type="password" id="password" name="password" placeholder="Password" class="form-control" required>
                                </div>
                            </div>
							<div class="form-group">
                                <div class="col-sm-8 col-sm-offset-2">
                                    <button type="submit" id="submit" name="submit" class="btn btn-primary">Sign in</button>
                                    <!--<a style="margin-left: 10px;" href="/user/forgot-password">Forgot your password?</a> -->
                                </div>
                            </div>
						<?php echo form_close(); ?>
                      
                    </div>
                </div>
            </div>
        </div>
    </div>
	
<script src="<?php echo base_url();?>/js/jquery.min.js"></script>
<script src="<?php echo base_url();?>/js/smoothscroll.js"></script>
<script src="<?php echo base_url();?>/js/bootstrap.min.js"></script>
<script src="<?php echo base_url();?>/js/jquery.nav.js"></script>
</body>
</html>