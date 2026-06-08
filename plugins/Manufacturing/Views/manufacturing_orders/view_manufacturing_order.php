<div id="page-content" class="page-wrapper clearfix">
	<div class="row">
		<?php 
		$id = '';
		$title = '';
		$title .= app_lang('view_manufacturing_order_lable');

		?>

		<div class="col-md-12" >
			<div class="panel_s">

				<div class="panel-body">
					<!-- action related work order -->
					<div class="row">
						<div class="col-md-12">
							<?php if(mrp_has_permission('manufacturing_can_create') || mrp_has_permission('manufacturing_can_edit') ){ ?>
								<?php 
								$check_availability_status = true;
								?>
								<?php if($check_availability && $manufacturing_order->status != 'draft'){ ?>
									<button type="button" class="label-planned btn btn-success pull-left mark_check_availability mright5  "><?php echo app_lang('mark_as_check_availability'); ?></button>
									<?php 
									$check_availability_status = false;
									?>
								<?php } ?>

								<?php if($manufacturing_order->status == 'draft'){ ?>
									<button type="button" class="label-confirmed  btn btn-info pull-left mark_as_todo mright5 text-white"><?php echo app_lang('mark_as_todo'); ?></button>
								<?php } ?>

								<?php if($manufacturing_order->status == 'confirmed' && $check_planned){ ?>
									<button type="button" class="label-planned btn btn-success pull-left mark_as_planned mright5 text-white"><?php echo app_lang('mark_as_planned'); ?></button>
								<?php } ?>

								<?php if($manufacturing_order->status == 'confirmed'){ ?>
									<button type="button" class="label-warning btn btn-success pull-left mark_as_unreserved mright5 text-white"><?php echo app_lang('mark_as_unreserved'); ?></button>
								<?php } ?>

								<?php if($check_mark_done && $manufacturing_order->status == 'in_progress' && $check_availability_status ){ ?>
									<button type="button" class="btn btn-success pull-left mark_as_done mright5 text-white"><?php echo app_lang('mark_as_done'); ?></button>
								<?php } ?>

								<?php if(($check_create_purchase_request && $manufacturing_order->status != 'draft') ){ ?>
									<?php if(!$pur_order_exist){ ?>
										<button type="button" class="btn btn-success pull-left mo_create_purchase_request mright5 text-white" data-toggle="tooltip" title="" data-original-title="<?php echo app_lang('create_purchase_request_title'); ?>"><?php echo app_lang('mo_create_purchase_request'); ?> <i class="fa fa-question-circle i_tooltip" ></i></button>
									<?php } ?>
								<?php } ?>

								<?php if($manufacturing_order->status != 'cancelled' && $manufacturing_order->status != 'done'){ ?>
									<button type="button" class="btn btn-default pull-left mark_as_cancel mright5"><?php echo app_lang('mrp_cancel'); ?></button>
								<?php } ?>

								<?php if($manufacturing_order->status == 'planned' || $manufacturing_order->status == 'in_progress' || $manufacturing_order->status == 'done' ){ ?>

									<a href="<?php echo site_url('manufacturing/mo_work_order_manage/'.$manufacturing_order->id); ?>" class="btn btn-warning pull-right display-block mright5 text-white"><i class="fa fa-play-circle-o"></i> <?php echo app_lang('mrp_work_orders'); ?></a>

								<?php } ?>


							<?php } ?>
						</div>
					</div>
					<!-- action related work order -->

					<div class="row">
						<h4 class="no-margin"><?php echo html_entity_decode($manufacturing_order->manufacturing_order_code); ?> 
					</div>
					<hr class="hr-color no-margin">

					<!-- start tab -->
					<div class="modal-body">
						<div class="tab-content">
							<!-- start general infor -->
							<?php 

							$id = isset($manufacturing_order) ? $manufacturing_order->id : '';
							$product_id = isset($manufacturing_order) ? $manufacturing_order->product_id : '';
							$product_qty = isset($manufacturing_order) ? $manufacturing_order->product_qty : 1;
							$unit_id = isset($manufacturing_order) ? $manufacturing_order->unit_id : '';
							$manufacturing_order_code = isset($manufacturing_order) ? $manufacturing_order->manufacturing_order_code : '';
							$staff_id = isset($manufacturing_order) ? $manufacturing_order->staff_id : '';
							$bom_id = isset($manufacturing_order) ? $manufacturing_order->bom_id : '';
							$routing_id = isset($manufacturing_order) ? $manufacturing_order->routing_id : '';
							$components_warehouse_id = isset($manufacturing_order) ? $manufacturing_order->components_warehouse_id : '';
							$finished_products_warehouse_id = isset($manufacturing_order) ? $manufacturing_order->finished_products_warehouse_id : '';
							$date_deadline = isset($manufacturing_order) ? format_to_date($manufacturing_order->date_deadline, false) : '';
							$date_plan_from = isset($manufacturing_order) ? format_to_date($manufacturing_order->date_plan_from, false) : '';
							$routing_id_view = isset($manufacturing_order) ? mrp_get_routing_name($manufacturing_order->routing_id) : '';
							$routing_id = isset($manufacturing_order) ? ($manufacturing_order->routing_id) : '';
							$status = isset($manufacturing_order) ? ($manufacturing_order->status) : '';
							$reference_purchase_request = isset($manufacturing_order) ? ($manufacturing_order->purchase_request_id) : '';

							$components_warehouse_name='';
							$finished_products_warehouse_name= mrp_get_warehouse_name($finished_products_warehouse_id);
							if($components_warehouse_id != ''){
								$components_warehouse_name .= mrp_get_warehouse_name($components_warehouse_id);
							}else{
								$components_warehouse_name .= app_lang('mrp_all');
							}

							$date_planned_start = '';
							if(isset($manufacturing_order) && $manufacturing_order->date_planned_start != null && $manufacturing_order->date_planned_start != ''){

								$date_planned_start = format_to_date($manufacturing_order->date_planned_start, false).' '.app_lang('mrp_to').' '. format_to_date($manufacturing_order->date_planned_finished, false);
							};
							?>
							<div class="row">
								<div class="col-md-6 panel-padding" >
									<input type="hidden" name="id" value="<?php echo html_entity_decode($id) ?>">

									<table class="table border table-striped table-margintop" >
										<tbody>
											<tr class="project-overview">
												<td class="bold td-width"><?php echo app_lang('product_label'); ?></td>
												<td><?php echo mrp_get_product_name($product_id) ; ?></td>
											</tr>
											<tr class="project-overview">
												<td class="bold"><?php echo app_lang('unit_of_measure'); ?></td>
												<td><?php echo mrp_get_unit_name($unit_id) ; ?></td>
											</tr>
											<tr class="project-overview">
												<td class="bold"><?php echo app_lang('product_qty'); ?></td>
												<td><?php echo html_entity_decode($product_qty)  ?></td>
											</tr>
											<tr class="project-overview">
												<td class="bold"><?php echo app_lang('bill_of_material_label'); ?></td>
												<td><?php echo mrp_get_product_name(mrp_get_bill_of_material($bom_id))  ?></td>
											</tr>
											<tr class="project-overview">
												<td class="bold"><?php echo app_lang('routing_label'); ?></td>
												<td><?php echo mrp_get_routing_name($routing_id)  ?></td>
											</tr>


										</tbody>
									</table>
								</div>

								<div class="col-md-6 panel-padding" >
									<table class="table table-striped table-margintop">
										<tbody>
											<tr class="project-overview">
												<td class="bold" width="40%"><?php echo app_lang('date_deadline'); ?></td>
												<td><?php echo html_entity_decode($date_deadline)  ?></td>
											</tr>
											<tr class="project-overview">
												<td class="bold"><?php echo app_lang('date_plan_from'); ?></td>
												<td><?php echo html_entity_decode($date_plan_from)  ?></td>
											</tr>
											<tr class="project-overview">
												<td class="bold"><?php echo app_lang('planned_date'); ?></td>
												<td><?php echo html_entity_decode($date_planned_start)  ?></td>
											</tr>


											<tr class="project-overview">
												<td class="bold"><?php echo app_lang('responsible'); ?></td>
												<td><?php echo html_entity_decode(get_staff_full_name1($staff_id))  ?></td>
											</tr>
											<tr class="project-overview">
												<td class="bold"><?php echo app_lang('status'); ?></td>
												<td><span class="badge label-<?php echo  html_entity_decode($status) ?> mt-0" ><?php echo app_lang($status); ?></span></td>
											</tr>

											<?php if($reference_purchase_request != ''){ ?>
												<tr class="project-overview">
													<td class="bold"><?php echo app_lang('reference_purchase_request'); ?></td>
													<td><a href="<?php echo site_url('purchase/view_pur_request/'.$reference_purchase_request) ?>" target="_blank"><?php echo mrp_purchase_request_code($reference_purchase_request) ?></a></td>
												</tr>
											<?php } ?>


										</tbody>
									</table>
								</div>

							</div>


							<div class="row">
								<h5 class="h5-color"><?php echo app_lang('work_center_info'); ?></h5>
								<hr class="hr-color">
							</div>

							<div class="row">

								<ul class="nav nav-tabs pb15" id="myTab" role="tablist">
									<li class="nav-item" role="presentation">
										<button class="nav-link active" id="component_tab-tab" data-bs-toggle="tab" data-bs-target="#component_tab" type="button" role="tab" aria-controls="component_tab" aria-selected="true"><?php echo _l('tab_component_tab'); ?></button>
									</li>
									<li class="nav-item " role="presentation" class="">
										<button class="nav-link" id="finished_product_tab-tab" data-bs-toggle="tab" data-bs-target="#finished_product_tab" type="button" role="tab" aria-controls="finished_product_tab" aria-selected="false"><?php echo _l('finished_product_tab'); ?></button>
									</li>

									<li class="nav-item" role="presentation" class="">
										<button class="nav-link" id="miscellaneous_tab-tab" data-bs-toggle="tab" data-bs-target="#miscellaneous_tab" type="button" role="tab" aria-controls="miscellaneous_tab" aria-selected="false"><?php echo _l('miscellaneous_tab'); ?></button>
									</li>
									<li class="nav-item" role="presentation" class="">
										<button class="nav-link" id="costing-tab" data-bs-toggle="tab" data-bs-target="#costing" type="button" role="tab" aria-controls="costing" aria-selected="false"><?php echo _l('costing'); ?></button>
									</li>
								</ul>


								
								<div class="tab-content" id="myTabContent">
									<div class="tab-pane fade show active" id="component_tab" role="tabpanel" aria-labelledby="component_tab-tab">
										<div class="table-responsive pt15 pl15 pr15">
											<div class="form"> 
												<div id="product_tab_hs" class="product_tab handsontable htColumnHeaders">
												</div>
												<?php echo form_hidden('product_tab_hs'); ?>
											</div>
										</div>

									</div>
									<div class="tab-pane fade " id="finished_product_tab" role="tabpanel" aria-labelledby="finished_product_tab-tab">

										<?php echo app_lang('Use_the_Produce_button_or_process_the_work_orders_to_create_some_finished_products'); ?>
									</div>
									<div class="tab-pane fade " id="miscellaneous_tab" role="tabpanel" aria-labelledby="miscellaneous_tab-tab">

										<div class="row">
											<div class="col-md-6 panel-padding" >
												<table class="table table-striped table-margintop">
													<tbody>
														<tr class="project-overview">
															<td class="bold" width="40%"><?php echo app_lang('components_warehouse'); ?></td>
															<td><?php echo html_entity_decode($components_warehouse_name)  ?></td>
														</tr>
														<tr class="project-overview">
															<td class="bold"><?php echo app_lang('finished_products_warehouse'); ?></td>
															<td><?php echo html_entity_decode($finished_products_warehouse_name)  ?></td>
														</tr>

													</tbody>
												</table>
											</div>
										</div>
									</div>
									<div class="tab-pane fade " id="costing" role="tabpanel" aria-labelledby="costing-tab">

										<div class="row">
											<div class="col-md-6 panel-padding" >
												<table class="table table-striped table-margintop">
													<tbody>
														<tr class="project-overview">
															<td class="bold" width="40%"><?php echo app_lang('total_material_cost'); ?></td>
															<td><?php echo to_currency($manufacturing_order_costing['total_material_cost'], get_base_currency())  ?></td>
														</tr>
														<tr class="project-overview">
															<td class="bold"><?php echo app_lang('total_labour_cost'); ?></td>
															<td>
																<?php echo to_currency($manufacturing_order_costing['total_labour_cost'], get_base_currency())  ?>
																<br>
															</td>
														</tr>
														<tr class="project-overview">
															<td class="" width="40%">    +   <?php echo app_lang('total_work_center_cost'); ?></td>
															<td><?php echo to_currency($manufacturing_order_costing['total_work_center_cost'], get_base_currency())  ?></td>
														</tr>
														<tr class="project-overview">
															<td class="" width="40%">    +   <?php echo app_lang('total_employee_working_cost'); ?></td>
															<td><?php echo to_currency($manufacturing_order_costing['total_employee_working_cost'], get_base_currency())  ?></td>
														</tr>

													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
							</div>

						</div>

						<div class="modal-footer">
							<a href="<?php echo site_url('manufacturing/manufacturing_order_manage'); ?>"  class="btn btn-default mr-2 "><span data-feather="x" class="icon-16" ></span> <?php echo app_lang('close'); ?></a>

							<?php if(mrp_has_permission('manufacturing_can_create') ){ ?>
								<a href="<?php echo site_url('manufacturing/add_edit_manufacturing_order'); ?>" class="btn btn-info pull-right display-block mright5 text-white"><span data-feather="plus-circle" class="icon-16" ></span> <?php echo app_lang('add_manufacturing_order'); ?></a>
							<?php } ?>

							<?php if( mrp_has_permission('manufacturing_can_edit')){ ?>
								<a href="<?php echo site_url('manufacturing/add_edit_manufacturing_order/'.$manufacturing_order->id); ?>" class="btn btn-primary pull-right display-block mright5"><span data-feather="edit" class="icon-16" ></span> <?php echo app_lang('edit_manufacturing'); ?></a>
							<?php } ?>

						</div>

					</div>
				</div>
			</div>

		</div>

		<?php 
		require 'plugins/Manufacturing/assets/js/manufacturing_orders/view_manufacturing_order_js.php';
		?>
	</body>
	</html>
