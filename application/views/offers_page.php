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
	<style>
		#offers-table{font-size:12px;}
	</style>

</head>
<body style="background-color:#edf0f5;">
	<div class="container-flex">
		<?php include_once("Menu.php");?>
		<div class="container-fluid">
			<div class="row">
				
				<div class="col-lg-12">
					<div class="panel panel-default">
						<div class="panel-heading">
							Demand offers
						
						</div>
						<div class="panel-body">
							<div class="row row-sm">
							
								<div class="col-sm-2">
									<label>Select Demand Source</label>
									<select id="demand_source" name="demand_source" class="form-control" onchange="getOffers(this.value,'')">
									<?php foreach($demand_sources as $demand_source){?>
										<option value="<?php echo $demand_source->demand_source_id;?>"><?php echo $demand_source->demand_source_title;?></option>
										<?php }?>									
									</select>
								</div>
								<div class="col-sm-2">
									<label>Select Offer Status</label>
									<select id="offer_status" name="offer_status" class="form-control" onchange="getOffers('',this.value)">
										<option value="active">Active</option>
										<option value="stopped">stopped</option>
										<option value="All">All</option>
									</select>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-12">
					<div class="panel">
						<div class="panel-body">
							<div class="table-responsive">
								<div id="offers-table_wrapper">
									
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
					<h4 class="modal-title" id="myModalLabel">Confirm Delete</h4>
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
<script src="//code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.15/js/dataTables.bootstrap.min.js"></script>



<script>

$('#confirm-delete').on('show.bs.modal', function (e) {
	$(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
    $('.source-name').html('<strong>' + $(e.relatedTarget).data('source-title') + '</strong>');
});
$(document).ready(function() {
$('#offers-table').DataTable({bFilter: false, bInfo: false});
} );
$(document).ready(function (){
if($('#demand_source').val()!=''){
	getOffers($('#demand_source').val(),$('#offer_status').val());
}

	//$('#offers-table_wrapper').load('<?php echo base_url()?>');
})
function getOffers(demandsource,offerStatus){
	if(demandsource==''){
		demandsource=$('#demand_source').val();
	}
	if(offerStatus==''){
		offerStatus=$('#offer_status').val();
	}
var offersUrl="<?php echo base_url();?>"+"/sources/getOffers/"+demandsource+"/"+offerStatus;
 $("#offers-table_wrapper").html("<center><img src='<?php echo base_url();?>/images/loader-blue.gif' style='height:100px;'></center>");

 $.ajax({url: offersUrl,
        type: 'GET',
        success: function (result) {
        $("#offers-table_wrapper").html(result);
		$('#offers-table').DataTable({bFilter: false, bInfo: true, "dom": '<"top"fli>rt<"bottom"p><"clear">',"pageLength": 50});
		$('.dataTables_length').addClass('col-md-3 pull-right');
		$('.dataTables_info').addClass('btn btn-sm btn-primary');
   }});
}
</script>
</body>
</html>