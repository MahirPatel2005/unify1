<div id="page-content" class="page-wrapper clearfix">
	<div class="row">
		<?php 
		$id = '';
		$title = '';
		if(isset($manufacturing_order)){
			$title .= app_lang('update_manufacturing_order_lable');
			$id    = $manufacturing_order->id;
		}else{
			$title .= app_lang('add_manufacturing_order_lable');
		}

		?>

		<?php echo form_open_multipart(site_url('manufacturing/add_edit_manufacturing_order/'.$id), array('id' => 'add_update_manufacturing_order','autocomplete'=>'off', 'class' => 'general-form')); ?>

		<div class="col-md-12" >
			<div class="card">
				<div class="page-title clearfix">
					<h4 class="no-margin font-bold"><i class="fa fa-clone menu-icon menu-icon" aria-hidden="true"></i> <?php echo html_entity_decode($title); ?></h4>
					
				</div>

				<!-- start tab -->
				<div class="modal-body">
					<div class="tab-content">
						<!-- start general infor -->
						<?php 

						$product_id = isset($manufacturing_order) ? $manufacturing_order->product_id : '';
						$product_qty = isset($manufacturing_order) ? $manufacturing_order->product_qty : 1;
						$unit_id = isset($manufacturing_order) ? $manufacturing_order->unit_id : '';
						$manufacturing_order_code = isset($manufacturing_order) ? $manufacturing_order->manufacturing_order_code : $mo_code;
						$staff_id = isset($manufacturing_order) ? $manufacturing_order->staff_id : get_staff_user_id1();
						$bom_id = isset($manufacturing_order) ? $manufacturing_order->bom_id : '';
						$routing_id = isset($manufacturing_order) ? $manufacturing_order->routing_id : '';
						$components_warehouse_id = isset($manufacturing_order) ? $manufacturing_order->components_warehouse_id : '';
						$finished_products_warehouse_id = isset($manufacturing_order) ? $manufacturing_order->finished_products_warehouse_id : '';
						$date_deadline = isset($manufacturing_order) ? format_to_date($manufacturing_order->date_deadline, false) : '';
						$date_plan_from = isset($manufacturing_order) ? format_to_date($manufacturing_order->date_plan_from, false) : '';
						$routing_id_view = isset($manufacturing_order) ? mrp_get_routing_name($manufacturing_order->routing_id) : '';
						$routing_id = isset($manufacturing_order) ? ($manufacturing_order->routing_id) : '';

						$disabled_edit=[];
						if(isset($manufacturing_order) && $manufacturing_order->status != 'draft'){
							$disabled_edit = ['disabled' => true];
						}

						?>
						<div class="row">
							<div class="row">
								<div class="col-md-6"> 
									<?php echo render_select1('product_id',$products,array('id', array('commodity_code', 'title')),'product_label', $product_id, $disabled_edit, [], '', '', true, true ); ?>
								</div>
								<div class="col-md-6"> 
									<?php echo render_date_input1('date_deadline','date_deadline', $date_deadline, [], [], '', '', true); ?>
								</div>
							</div>

							<div class="row">
								<div class="col-md-6">
									<?php echo render_input1('product_qty','product_qty', $product_qty,'number', $disabled_edit, [], '', '', true); ?> 
								</div>
								<div class="col-md-6"> 
									<?php echo render_date_input1('date_plan_from','date_plan_from', $date_plan_from, [], [], '', '', true); ?>
								</div>
							</div>

							<div class="row">
								<div class="col-md-6">
									<?php echo render_select1('unit_id',$units,array('unit_type_id', 'unit_name'), 'unit_of_measure', $unit_id, $disabled_edit, [], '', '' , false, true); ?>
								</div>
								<div class="col-md-6">
									<?php echo render_select1('staff_id',$staffs,array('id', array('first_name', 'last_name')), 'responsible', $staff_id,[], [], '', '' , false); ?>
								</div>
							</div>

							<div class="row">
								<div class="col-md-6">
									<?php echo render_select1('bom_id',$bill_of_materials,array('id', 'description'), 'bill_of_material_label', $bom_id, $disabled_edit, [], '', '' , true, true); ?>
								</div>
								<div class="col-md-6">
									<?php echo render_input1('manufacturing_order_code', 'reference_code', $manufacturing_order_code, '', $disabled_edit, [], '', '', true); ?>
								</div>
							</div>

							<div class="row">
								<div class="col-md-6">
									<?php echo render_input1('routing_id_view', 'routing_label', $routing_id_view, '', ['disabled' => true]); ?>
									<input type="hidden" name="routing_id" value="<?php echo html_entity_decode($routing_id) ?>">
								</div>

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
										<div class="col-md-12">
											<?php echo render_select1('components_warehouse_id', $warehouses,array('warehouse_id', 'warehouse_name'), 'components_warehouse', $components_warehouse_id,['data-none-selected-text' => app_lang('mrp_all')], [], '', '' , true); ?>
										</div>
										<div class="col-md-12">
											<?php echo render_select1('finished_products_warehouse_id', $warehouses,array('warehouse_id', 'warehouse_name'), 'finished_products_warehouse', $finished_products_warehouse_id,[], [], '', '' , false, true); ?>
										</div>

									</div>
								</div>

							</div>
						</div>

					</div>

					<div class="modal-footer">
						<a href="<?php echo site_url('manufacturing/manufacturing_order_manage'); ?>"  class="btn btn-default mr-2 "><?php echo app_lang('close'); ?></a>
						<?php if(mrp_has_permission('manufacturing_can_create') || mrp_has_permission('manufacturing_can_edit')){ ?>
							<button type="button" class="btn btn-info pull-right add_manufacturing_order text-white"><span data-feather="check-circle" class="icon-16" ></span> <?php echo app_lang('submit'); ?></button>

						<?php } ?>
					</div>

				</div>

			</div>

			<?php echo form_close(); ?>
		</div>

		<!-- init_tail -->
		<?php 
		require 'plugins/Manufacturing/assets/js/manufacturing_orders/add_edit_manufacturing_order_js.php';
		?>
	</body>
	</html>


