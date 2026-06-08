<div id="page-content" class="page-wrapper clearfix">
	<div class="row">
			<div class="col-md-12">
				<div class="panel_s">
					<div class="panel-body">               
						<div class="clearfix"></div>
						<h4>
							<?php echo html_entity_decode($commodity_item->title); ?>
						</h4>


						<hr class="hr-panel-heading" /> 
						<div class="clearfix"></div> 
						<div class="col-md-12">

							<div class="row col-md-12">

								<h4 class="h4-color"><?php echo app_lang('general_infor'); ?></h4>
								<hr class="hr-color">

								<div class="col-md-7 panel-padding">
									<table class="table border table-striped table-margintop">
										<tbody>

											<tr class="project-overview">
												<td class="bold"><?php echo app_lang('product_name'); ?></td>
												<td><?php echo html_entity_decode($commodity_item->title) ; ?></td>
											</tr>
											<tr class="project-overview">
												<td class="bold"><?php echo app_lang('product_type'); ?></td>
												<td><?php

												if($commodity_item->product_type == 'storable_product'){
													echo app_lang('mrp_storable_product') ;

												}elseif($commodity_item->product_type == 'mrp_consumable'){
													echo app_lang('mrp_consumable') ;
												}else{
													echo app_lang('mrp_service') ;
												}
											?></td>
										</tr>
										<tr class="project-overview">
											<td class="bold"><?php echo app_lang('product_category'); ?></td>
											<td><?php echo get_wh_group_name(html_entity_decode($commodity_item->category_id)) != null ? get_wh_group_name(html_entity_decode($commodity_item->category_id))->title : '' ; ?></td>
										</tr>
										<tr class="project-overview">
											<td class="bold"><?php echo app_lang('barcode'); ?></td>
											<td><?php echo html_entity_decode($commodity_item->commodity_barcode) ; ?></td>
										</tr>
										<tr class="project-overview">
											<td class="bold"><?php echo app_lang('sku_code'); ?></td>
											<td><?php echo html_entity_decode($commodity_item->sku_code) ; ?></td>
										</tr>
										<tr class="project-overview">
											<td class="bold"><?php echo app_lang('sales_price'); ?></td>
											<td><?php echo to_currency((float)$commodity_item->rate, get_base_currency()) ; ?></td>
										</tr>
										<tr class="project-overview">
											<td class="bold"><?php echo app_lang('unit_id'); ?></td>
											<td><?php echo  $commodity_item->unit_id != '' && get_unit_type($commodity_item->unit_id) != null ? get_unit_type($commodity_item->unit_id)->unit_name : ''; ?></td>
										</tr> 

										<tr class="project-overview">
											<td class="bold"><?php echo app_lang('mrp_cost'); ?></td>
											<td><?php echo to_currency((float)$commodity_item->purchase_price,  get_base_currency()) ; ?></td>
										</tr>

									</tbody>
								</table>
								<div class=" row ">
									<div class="col-md-12">
										<h4 class="h4-color"><?php echo app_lang('internal_notes'); ?></h4>
										<hr class="hr-color">
										<h6><?php echo html_entity_decode($commodity_item->description) ; ?></h6>

									</div>

						</div>

							</div>

							<div class="col-md-5">
								<div class="container-fluid">

									<?php
									if ($model_info->files) {
										$files = @unserialize($model_info->files);
										if (count($files)) {
											?>
											<div class="col-md-12 mt15">
												<?php
												if ($files) {
													$total_files = count($files);
													echo view("includes/timeline_preview", array("files" => $files));
												}
												?>
											</div>
											<?php
										}
									}
									?>
								</div>
							</div>
							<br>
						</div>

						<div class="horizontal-scrollable-tabs preview-tabs-top">
							<div class="scroller arrow-left"><i class="fa fa-angle-left"></i></div>
							<div class="scroller arrow-right"><i class="fa fa-angle-right"></i></div>
							<div class="horizontal-tabs">
								<ul class="nav nav-tabs nav-tabs-horizontal mbot15" role="tablist">

									<li role="presentation" class="active">
										<a href="#child_items" aria-controls="child_items" role="tab" id="tab_child_items" data-toggle="tab">
											<?php echo app_lang('sub_items') ?>
										</a>
									</li>  

								</ul>
							</div>
						</div>

						<div class="tab-content col-md-12">

							<!-- child item -->
							<div role="tabpanel" class="tab-pane active" id="child_items">
								<div class="row">
									<div class="col-md-12">
										<div class="col-md-4 ">
											<?php if (mrp_has_permission('manufacturing_can_create') || is_admin() || mrp_has_permission('manufacturing_can_edit') ) { ?>

												<a href="#" id="dowload_items"  class="btn btn-warning pull-left  mr-4 button-margin-r-b hide"><?php echo app_lang('dowload_items'); ?></a>

											<?php } ?>
										</div>

									</div>  
									<div class="col-md-12">

										<!-- view/manage -->            
										<div class="modal fade bulk_actions" id="table_commodity_list_bulk_actions" tabindex="-1" role="dialog">
											<div class="modal-dialog" role="document">
												<div class="modal-content">
													<div class="modal-header">
														<h4 class="modal-title"><?php echo app_lang('bulk_actions'); ?></h4>
														<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
													</div>
													<div class="modal-body">
														<?php if(mrp_has_permission('manufacturing_can_delete') || is_admin()){ ?>
															<div class="checkbox checkbox-danger">
																<input type="checkbox" class="form-check-input" name="mass_delete" id="mass_delete">
																<label for="mass_delete"><?php echo app_lang('mass_delete'); ?></label>
															</div>

														<?php } ?>
													</div>
													<div class="modal-footer">
														<button type="button" class="btn btn-default" data-bs-dismiss="modal"><i data-feather="x" class="icon-16"></i> <?php echo app_lang('close'); ?></button>

														<?php if(mrp_has_permission('manufacturing_can_delete') || is_admin()){ ?>
															<a href="#" class="btn btn-info text-white" onclick="warehouse_delete_bulk_action(this); return false;"><span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang('confirm'); ?></a>
														<?php } ?>
													</div>
												</div>

											</div>

										</div>

										<!-- update multiple item -->

										<div class="modal export_item" id="table_commodity_list_export_item" tabindex="-1" role="dialog">
											<div class="modal-dialog" role="document">
												<div class="modal-content">
													<div class="modal-header">
														<h4 class="modal-title"><?php echo app_lang('export_item'); ?></h4>
														<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
													</div>
													<div class="modal-body">
														<?php if(mrp_has_permission('manufacturing_can_create') || is_admin()){ ?>
															<div class="checkbox checkbox-danger">
																<input type="checkbox" name="mass_delete" id="mass_delete">
																<label for="mass_delete"><?php echo app_lang('mass_delete'); ?></label>
															</div>

														<?php } ?>
													</div>
													<div class="modal-footer">
														<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo app_lang('close'); ?></button>

														<?php if(mrp_has_permission('manufacturing_can_create') || is_admin()){ ?>
															<a href="#" class="btn btn-info" onclick="warehouse_delete_bulk_action(this); return false;"><?php echo app_lang('confirm'); ?></a>
														<?php } ?>
													</div>
												</div>

											</div>

										</div>

										<!-- print barcode -->      
										<?php echo form_open_multipart(get_uri('manufacturing/download_barcode'), array('id'=>'item_print_barcode')); ?>      
										    
										<div class="modal fade bulk_actions" id="table_commodity_list_print_barcode" tabindex="-1" role="dialog">
											<div class="modal-dialog" role="document">
												<div class="modal-content">
													<div class="modal-header">
														<h4 class="modal-title"><?php echo app_lang('print_barcode'); ?></h4>
														<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
													</div>
													<div class="modal-body">
														<?php if(mrp_has_permission('manufacturing_can_create') || is_admin()){ ?>

															<div class="row">
																<div class="col-md-6">
																	<div class="form-group">
																		<div class="radio radio-primary radio-inline" >
																			<input class="form-check-input" onchange="print_barcode_option(this); return false" type="radio" id="y_opt_1_" name="select_item" value="0" checked >
																			<label for="y_opt_1_"><?php echo app_lang('select_all'); ?></label>
																		</div>
																	</div>
																</div>

																<div class="col-md-6">
																	<div class="form-group">
																		<div class="radio radio-primary radio-inline" >
																			<input class="form-check-input" onchange="print_barcode_option(this); return false" type="radio" id="y_opt_2_" name="select_item" value="1" >
																			<label for="y_opt_2_"><?php echo app_lang('select_item'); ?></label>
																		</div>
																	</div>
																</div>
															</div>     

															<div class="row display-select-item hide ">
																<div class=" col-md-12">
																	<div class="form-group">
																		<select name="item_select_print_barcode[]" id="item_select_print_barcode" class="select2 validate-hidden" data-live-search="true" multiple="true" data-width="100%" data-none-selected-text="<?php echo app_lang('select_item_print_barcode'); ?>">

																			<?php foreach($commodity_filter as $commodity) { ?>
																				<option value="<?php echo html_entity_decode($commodity['id']); ?>"><?php echo html_entity_decode($commodity['commodity_code'] .' '.$commodity['title']); ?></option>
																			<?php } ?>
																		</select>
																	</div>
																</div>
															</div>

														<?php } ?>
													</div>
													<div class="modal-footer">
														<button type="button" class="btn btn-default" data-bs-dismiss="modal"><i data-feather="x" class="icon-16"></i> <?php echo app_lang('close'); ?></button>

														<?php if(mrp_has_permission('manufacturing_can_create') || is_admin()){ ?>

															<button type="submit" class="btn btn-info text-white" ><span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang('confirm'); ?></button>
														<?php } ?>
													</div>
												</div>
											</div>
										</div>
										<?php echo form_close(); ?>


										<a href="#"  onclick="staff_bulk_actions(); return false;" data-toggle="modal" data-table=".table-table_commodity_list" data-target="#leads_bulk_actions" class=" hide bulk-actions-btn table-btn"><?php echo app_lang('bulk_actions'); ?></a>


										<a href="#"  onclick="print_barcode_bulk_actions(); return false;" data-toggle="modal" data-table=".table-table_commodity_list" data-target="#print_barcode_item" class=" hide print_barcode-bulk-actions-btn table-btn"><?php echo app_lang('print_barcode'); ?></a>


										<?php 
										$table_data = array(
											'<span class="hide"> - </span><div class="checkbox mass_select_all_wrap"><input type="checkbox" id="mass_select_all" data-to-table="table_commodity_list" class="form-check-input"><label></label></div>',
											app_lang('_images'),
											app_lang('product_name'),
											app_lang('barcode'),
											app_lang('rate'),
											app_lang('mrp_cost'),
											app_lang('product_category'),
											app_lang('product_type'),
											app_lang('quantity_on_hand'),
											app_lang('unit_name'),                      
											app_lang('options'),                      
										);


										render_datatable1($table_data,'table_commodity_list',
									); ?>
								</div>
							</div>
						</div>

					</div>                                    

				</div>
			</div>
		</div>
	</div>

</div>
</div>

<?php echo form_close(); ?>

<!-- add one commodity list sibar end -->  


<?php echo form_hidden('commodity_id'); ?>
<?php echo form_hidden('parent_item_filter', 'false'); ?>


<!-- init_tail -->
<?php require 'plugins/Manufacturing/assets/js/products/sub_commodity_list_js.php';?>


</body>
</html>

