<?php if(!EMPTY($demand_offers)){?>
<table id="offers-table" class="table table-stats table-bordered table-condensed table-adaptive size-11 table-sort table-striped table-hover table-float-thead dataTable no-footer" role="grid" aria-describedby="sources-table_info" style="width: 1169px; display: table;">
										<thead>
											<tr class="tr-center table-headers" role="row">
											<!--<th  rowspan="1" colspan="1"  style="width: 60px;">Logo</th>-->
											<th  rowspan="1" colspan="1"  style="width: 90px;">Id</th>
											<th  rowspan="1" colspan="1"  style="width: 664px;">Title</th>
											<th  rowspan="1" colspan="1"  style="width: 102px;">Payout</th>
											<th  rowspan="1" colspan="1"  style="width: 53px;">Status</th>
											
											
											</tr>
										</thead>
										<tbody>
										<?php  foreach($demand_offers as $demand_offer){?>
											<tr role="row">
												<!--<?php if($demand_offer->logo!=''){?>
												<td ><img style="height:50px;width:50px;" src="<?php echo $demand_offer->logo;?>"></td>
												<?php }else{?>
												<td ><img style="height:50px;width:50px;" src="<?php echo base_url('images/no-logo.gif');?>"></td>
												<?php }?>-->
												<td ><?php echo $demand_offer->id;?></td>
												<td ><?php echo urldecode($demand_offer->title);?></td>
												<td><?php echo $demand_offer->payments;?></td>
												<?php if($demand_offer->status=='active'){?>
												<td ><span class="btn btn-xs btn-success"><?php echo $demand_offer->status;?></span></td>
												<?php }else{?>
												<td ><span class="btn btn-xs btn-default"><?php echo $demand_offer->status;?></span></td>
												<?php }?>
												
												
											</tr>
										<?php }?>										
										</tbody>
									</table>
<?php  }else{ echo "No Offers Found";}?>		