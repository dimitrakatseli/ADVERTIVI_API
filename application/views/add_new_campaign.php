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
						Network
					</div>
					<div class="panel-body">
						<?php echo form_open('campaign/addNew','class="form-horizontal" id="addcampaign"'); ?>
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
								<input type="text" id="title" name="title" required="required"  class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-2"> <label for="type" class="required">Type</label></label>
							<div class="col-sm-4">
								<select id="type" name="type" class="form-control">
									<?php foreach($demand_source_types as $demand_source_type){?>
									<option value="<?php echo $demand_source_type->demand_source_type_value;?>"><?php echo $demand_source_type->demand_source_type_name;?></option>
									<?php }?>									
								</select>
							</div>
						</div>
						<div class="form-group data-credentials type-hasoffers type-affise type-affise_client type-axonite type-cake type-fuseclick type-appthis type-minimob type-dcyphermedia type-offerslook type-mobusi" style="display: block;">
							<label class="control-label col-sm-2"> <label for="api_key">Api Key</label></label>
							<div class="col-sm-4">
								<input type="text" id="api_key" name="api_key" class="form-control">
							</div>
						</div>
										
					<div class="form-group data-credentials type-affise type-affise_client type-cake type-fuseclick type-offerslook" style="display: block;">
						<label class="control-label col-sm-2"> <label for="api_uri">Api Uri</label></label>
						<div class="col-sm-4">
							<input type="text" id="api_uri" name="api_uri" class="form-control">
						</div>
					</div>

            <div class="form-group">
            <label class="control-label col-sm-2"><label for="tracking_url_params">Tracking Url Params</label>
                <a href="javascript:void(0)" class="popover-btn line-height-30 underline" data-toggle="popover" data-placement="right" data-content="
                        <b>{clickid}</b> - clickid_param_info<br/>
                        <b>{pid}</b> - pid_param_info<br/>
                        <b>{flow}</b> - flow_param_info<br/>
                        <b>{ip}</b> - ip_param_info<br/>
                        <b>{site}</b> - site_param_info<br/>
                        <b>{geo}</b> - geo_param_info<br/>
                        <b>{sub1}</b> - SubId #1<br/>
                        <b>{sub2}</b> - SubId #2<br/>
                        <b>{sub3}</b> - SubId #3<br/>
                        <b>{sub4}</b> - SubId #4<br/>
                        <b>{sub5}</b> - SubId #5<br/>
                        <b>{device_ua}</b> - device_ua_param_info<br/>
                        <b>{offer_id}</b> - Offer ID<br/>
                        <b>{rand}</b> - Unique number<br/>
                        <b>{time}</b> - Unix time<br/>
                        <b>{city}</b> - City
                    " title="" data-original-title="Macroses">Show macroses <i class="fa fa-1x fa-info-circle"></i></a>
            </label>
            <div class="col-sm-10">
                <div class="tracking-url-params-wrapper">
					<div class="row" style="margin-bottom:10px;">
						<div class="col-sm-4">
							<input class="form-control tup-input-key" placeholder="Key" value="aff_sub">
						</div>
						<div class="col-sm-4">
							<input class="form-control tup-input-value" placeholder="Value" value="{clickid}">
						</div>
						<div class="col-sm-4">
							<button class="btn btn-default btn-remove-row" type="button">
							<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
							</button>
						</div>
					</div>
					<div class="row" style="margin-bottom:10px;">
						<div class="col-sm-4">
							<input class="form-control tup-input-key" placeholder="Key" value="aff_sub2">
						</div>
						<div class="col-sm-4">
							<input class="form-control tup-input-value" placeholder="Value" value="{pid}">
						</div>
						<div class="col-sm-4">
							<button class="btn btn-default btn-remove-row" type="button">
								<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
							</button>
						</div>
					</div>
				</div>
                <input type="button" class="btn btn-info btn-plus new-tracking-url-param pull-left" value="+">
                <input type="hidden" id="tracking_url_params" name="tracking_url_params" class="form-control" style="color:#000;">
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