<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if (!isset($this->session->userdata['logged_in'])) {
header("location: ".base_url()."user");
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
 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	 <link rel="stylesheet" href="<?php echo base_url();?>/css/nav.css">
    <!-- FONT ICONS -->
    <link rel="stylesheet" href="<?php echo base_url();?>/assets/icon/icons.css">

    <!-- CAROUSEL AND LIGHTBOX -->
    
    <!-- CUSTOM STYLESHEETS -->
    <link rel="stylesheet" href="<?php echo base_url();?>/css/styles-inner.css">

    <!-- RESPONSIVE FIXES -->
    <link rel="stylesheet" href="<?php echo base_url();?>/css/responsive.css">

 

    <!-- ADVERTIVI -->
    <link rel="stylesheet" href="<?php echo base_url();?>/css/advertivi.css">
	<link rel="stylesheet" href="<?php echo base_url();?>/assets/fonts/font-awesome.css">

</head>
<body style="background-color:#edf0f5;">
	<div class="container-flex">
		<?php include_once("Menu.php");?>
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-1"></div>
			<div class="col-lg-10">
				<div class="panel panel-default">
					<div class="panel-heading">
						Source
					</div>
					<div class="panel-body">
						<?php echo form_open('sources/addNew','class="form-horizontal" id="addsources"'); ?>
						<?php
						echo "<div class='error_msg'>";
						if (isset($error_message)) {
						echo $error_message;
						}
						echo validation_errors();
						echo "</div>";
						?>
						<div class="form-group">
							<label class="control-label col-sm-2"> <label for="title" class="required">Title</label></label>
							<div class="col-sm-4">
								<input type="text" id="title" name="title" required="required" class="form-control">
							</div>
						</div>
					
					<div class="form-group">
							<label class="control-label col-sm-2"> <label for="title" class="required">Advertiser</label></label>
							<div class="col-sm-4">
							
							<select id="advertiser" name="advertiser" class="form-control">
							<?php foreach($advertiserdata as $adver){?>
							<option value="<?php echo $adver->advertiserId;?>"><?php echo $adver->advertiser_name;?></option>
							<?php }?>
							</select>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-2"> <label for="title" class="required">Status</label></label>
							<div class="col-sm-4">
							
							<select id="status" name="status" class="form-control">
								<option value="1">ACTIVE</option>
								<option value="0">INACTIVE</option>
							</select>
							</div>
						</div>

         
    
    <button type="submit" id="submit" name="submit" class="btn btn-success">Save</button>
</form>
						<?php echo form_close(); ?>
                      
                    </div>
				</div>
			</div>
			<div class="col-lg-1"></div>

    </div>
	</div>
	
<script src="<?php echo base_url();?>/js/jquery.min.js"></script>
<script src="<?php echo base_url();?>/js/smoothscroll.js"></script>
<script src="<?php echo base_url();?>/js/bootstrap.min.js"></script>
<script src="<?php echo base_url();?>/js/jquery.nav.js"></script>

</body>
</html>