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
    <link rel="stylesheet" href="<?php echo base_url();?>/css/bootstrap.min.css">
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
				<div class="col-md-12">
					<div class="alert alert-info alert-dismissible demand-sources-info-alert">
						<button type="button" class="close" data-hide="demand-sources-info-alert" aria-label="Close"><span aria-hidden="true">×</span></button>
						<p>The list of available demand sources with meta-info about last syncs and offers count.</p>
					</div>
				</div>
				<div class="col-lg-12">
					<div class="panel panel-default">
						<div class="panel-heading">
							Demand sources
								<div class="pull-right">
									<a href="<?php echo base_url();?>sources/addNew" class="btn btn-success btn-xs">New Demand source</a>
								</div>
						</div>
					
					</div>
				</div>
				<div class="col-md-12">
					<div class="panel">
						<div class="panel-body">
							<div class="table-responsive">
								<div id="sources-table_wrapper">
									<table id="sources-table" class="table table-stats table-bordered table-condensed table-adaptive size-11 table-sort table-striped table-hover table-float-thead dataTable no-footer" role="grid" aria-describedby="sources-table_info" style="width: 1169px; display: table;">
										<thead>
											<tr class="tr-center table-headers" role="row">
											<th  rowspan="1" colspan="1"  style="width: 52px;">ID</th>
											<th  rowspan="1" colspan="1"  style="width: 201px;">Title</th>
											<th  rowspan="1" colspan="1"  style="width: 109px;">Status</th>
											<th  rowspan="1" colspan="1"  style="width: 465px;">Last Updates</th>
											<th  rowspan="1" colspan="1"  style="width: 90px;">Actions</th></tr>
										</thead>
										<tbody>
										<?php if(!EMPTY($demand_sources)>0){ foreach($demand_sources as $demand_source){?>
											<tr role="row">
												<td ><?php echo $demand_source->demand_source_id;?></td>
												<td ><?php echo $demand_source->demand_source_title;?></td>
												<td >
												<?php if( $demand_source->status==1){?>
													<span class="label label-success" title="Enabled">Enabled</span>
												<?php }else{?>
													<span class="label label-danger" title="Disabled">Disabled</span>
												<?php }?>												
												</td>
												<td><?php echo $demand_source->last_update;?></td>
												<td class="td-center">
													<a href="<?php echo base_url();?>/sources/edit/<?php echo $demand_source->demand_source_id;?>" class="btn btn-default btn-xs" title="Edit">
														Edit
													</a>
												<?php if($demand_source->status==1)	{?>
												<a href="<?php echo base_url();?>/sources/enabledisable/0/<?php echo $demand_source->demand_source_id;?>" class="btn btn-danger btn-xs" title="Disable">
														Disable
													</a>
													
												<?php }else{?>
											<a href="<?php echo base_url();?>/sources/enabledisable/1/<?php echo $demand_source->demand_source_id;?>" class="btn btn-success btn-xs" title="Enable">
														Enable
													</a>
												<?php }?>
												</td>
											</tr>
										<?php }}?>	
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
  <!-- Modal -->
	<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
		<div class="modal-dialog">
			<div class="modal-content">

				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h4 class="modal-title" id="myModalLabel">Confirm Change Status</h4>
				</div>

				<div class="modal-body">
					<p>You are about to delete demand source <span class="source-name"><strong></strong></span></p>
					<p>Do you want to proceed?</p>
					<p class="debug-url"></p>
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
					<a class="btn btn-danger btn-ok" href="">Delete</a>
				</div>
			</div>
		</div>
	</div>

<script src="<?php echo base_url();?>/js/jquery.min.js"></script>
<script src="<?php echo base_url();?>/js/smoothscroll.js"></script>
<script src="<?php echo base_url();?>/js/bootstrap.min.js"></script>
<script src="<?php echo base_url();?>/js/jquery.nav.js"></script>
<script>

$('#confirm-delete').on('show.bs.modal', function (e) {
	$(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
    $('.source-name').html('<strong>' + $(e.relatedTarget).data('source-title') + '</strong>');
});

</script>
</body>
</html>