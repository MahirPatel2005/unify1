
<div id="page-content" class="page-wrapper clearfix">
	<div class="row">

		<div class="col-md-5">
			<div class="row">
				<div class="card">
					<?php 

					$bill_of_material_id = isset($bill_of_material) ? $bill_of_material->id : '';
					$product_id = isset($bill_of_material) ? $bill_of_material->product_id : '';
					$product_variant_id = isset($bill_of_material) ? $bill_of_material->product_variant_id : '';
					$product_qty = isset($bill_of_material) ? $bill_of_material->product_qty : '';
					$unit_id = isset($bill_of_material) ? $bill_of_material->unit_id : '';
					$routing_id = isset($bill_of_material) ? $bill_of_material->routing_id : '';
					$bom_code = isset($bill_of_material) ? $bill_of_material->bom_code : '';

					$bom_type = isset($bill_of_material) ? $bill_of_material->bom_type : '';

					$manufacture_this_product_checked='';
					$kit_checked='';
					$kit_hide ='hide';

					if($bom_type == 'manufacture_this_product'){
						$manufacture_this_product_checked = 'checked';
						$kit_hide ='hide';

					}else{
						$kit_checked = 'checked';
						$kit_hide ='';

					}

					$ready_to_produce = isset($bill_of_material) ? $bill_of_material->ready_to_produce : '';
					$consumption = isset($bill_of_material) ? $bill_of_material->consumption : '';

					$product_variant_name='';
					if($product_variant_id != '' && $product_variant_id != 0){
						$product_variant_name = '( '.mrp_get_product_name($product_variant_id).' )';
					}
					?>
					<?php echo form_open(admin_url('manufacturing/add_bill_of_material_modal/'.$bill_of_material_id), array('id' => 'add_bill_of_material', 'autocomplete'=>'off', 'class' => 'general-form')); ?>

					<div class="page-title clearfix">
						<h4 class="no-margin font-bold"><i class="fa fa-clone menu-icon menu-icon" aria-hidden="true"></i> <?php echo html_entity_decode(mrp_get_product_name($product_id) .$product_variant_name); ?></h4>
						<div class="title-button-group">
						</div>
					</div>

					<div class="card-body">


						<div class="row">
							<div class="col-md-12">
								<?php echo render_input1('bom_code','BOM_code', $bom_code,'text'); ?> 
							</div>

							<div class="col-md-12">
								<?php echo render_select1('product_id',$parent_product,array('id',array('commodity_code', 'title')),'product_label', $product_id); ?>
							</div>
							<div class="col-md-12">
								<?php echo render_select1('product_variant_id',$product_variant,array('id',array('commodity_code', 'title')),'product_variant', $product_variant_id); ?>

							</div>

							<div class="col-md-6">
								<?php echo render_input1('product_qty','product_qty', $product_qty ,'number'); ?> 
							</div>

							<div class="col-md-6">
								<?php echo render_select1('unit_id',$units,array('unit_type_id', 'unit_name'), 'unit_of_measure', $unit_id,[], [], '', '' , false); ?>
							</div>

							<div class="col-md-6">
								<?php echo render_select1('routing_id',$routings,array('id', 'routing_name'), 'routing_label', $routing_id,[], [], '', '' , true); ?>
							</div>

							<div class="col-md-6">
								<div class="form-group">
									<label for="profit_rate" class="control-label clearfix"><?php echo app_lang('bom_type'); ?></label>
									<div class="radio radio-primary radio-inline" >
										<input type="radio" class="control-label clearfix" id="manufacture_this_product" name="bom_type" value="manufacture_this_product" <?php echo html_entity_decode($manufacture_this_product_checked ) ?>>
										<label for="manufacture_this_product"><?php echo app_lang('manufacture_this_product'); ?></label>

									</div>
									<div class="radio radio-primary radio-inline" >
										<input type="radio" class="control-label clearfix" id="kit" name="bom_type" value="kit" <?php echo html_entity_decode($kit_checked ) ?>>
										<label for="kit"><?php echo app_lang('kit'); ?></label>

									</div>
									<div class="kit_hide <?php echo html_entity_decode($kit_hide); ?>">
										<?php echo app_lang('A_BoM_of_type_kit_is_used_to_split_the_product_into_its_components'); ?><br>
										<?php echo app_lang('At_the_creation_of_a_Manufacturing_Order'); ?><br>
										<?php echo app_lang('At_the_creation_of_a_Stock_Transfer'); ?><br>
									</div>
								</div>
							</div>

							<h4><?php echo app_lang('miscellaneous') ?></h4>

							<div class="col-md-12">
								<?php echo render_select1('ready_to_produce',$ready_to_produce_type,array('name', 'label'), 'ready_to_produce', $ready_to_produce,[], [], '', '' , false); ?>
							</div>
							<div class="col-md-12">
								<?php echo render_select1('consumption',$consumption_type,array('name', 'label'), 'consumption', $consumption,[], [], '', '' , false); ?>
							</div>

						</div>

						<div class="modal-footer">
							<a href="<?php echo site_url('manufacturing/bill_of_material_manage'); ?>"  class="btn btn-default mr-2 "><?php echo app_lang('close'); ?></a>
							<?php if(mrp_has_permission('manufacturing_can_create') || mrp_has_permission('manufacturing_can_edit')){ ?>
								<button type="submit" class="btn btn-info pull-right text-white"><span data-feather="check-circle" class="icon-16" ></span> <?php echo app_lang('submit'); ?></button>

							<?php } ?>
						</div>

					</div>
					<?php echo form_close(); ?>
				</div>

			</div>
		</div>

		<div class="col-md-7">
			<div class="row ml15">

					<div class="card">
						<div class="page-title clearfix">
							<h4 class="no-margin font-bold"><i class="fa fa-clone menu-icon menu-icon" aria-hidden="true"></i> <?php echo app_lang('component'); ?></h4>

							<div class="title-button-group">
								<?php if(mrp_has_permission('manufacturing_can_create')){ ?>
									<a href="#" onclick="add_component(<?php echo html_entity_decode($bill_of_material_id) ?>,0, <?php echo html_entity_decode($product_id) ?>, <?php echo html_entity_decode($routing_id) ?>,'add'); return false;" class="btn btn-info pull-left display-block mright5 text-white"><span data-feather="plus-circle" class="icon-16"></span> <?php echo app_lang('add_component'); ?></a>

								<?php } ?>
							</div>
						</div>


						<div class="modal fade bulk_actions" id="bill_of_material_detail_table_bulk_actions" tabindex="-1" role="dialog">
							<div class="modal-dialog" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h4 class="modal-title"><?php echo _l('bulk_actions'); ?></h4>
										<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
									</div>
									<div class="modal-body">
										<?php if(mrp_has_permission('manufacturing_can_delete') || is_admin()){ ?>
											<div class="checkbox checkbox-danger">
												<input type="checkbox"  class="form-check-input" name="mass_delete" id="mass_delete">
												<label for="mass_delete"><?php echo app_lang('hr_mass_delete'); ?></label>
											</div>
										<?php } ?>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-default" data-bs-dismiss="modal"><i data-feather="x" class="icon-16"></i> <?php echo app_lang('close'); ?></button>


										<?php if(mrp_has_permission('manufacturing_can_delete')){ ?>
											<a href="#" class="btn btn-info text-white" onclick="staff_delete_bulk_action(this); return false;"><span data-feather="check-circle" class="icon-16" ></span> <?php echo app_lang('submit'); ?></a>
										<?php } ?>
									</div>
								</div>
							</div>
						</div>

						<?php if(mrp_has_permission('manufacturing_can_delete')){ ?>

							<a href="#"  onclick="staff_bulk_actions(); return false;" data-toggle="modal" data-table=".table-bill_of_material_detail_table" data-target="#leads_bulk_actions" class=" hide bulk-actions-btn table-btn"><?php echo app_lang('hr_bulk_actions'); ?></a>
						<?php } ?>

						<?php render_datatable1(array(
							'<span class="hide"> - </span><div class="checkbox mass_select_all_wrap"><input type="checkbox" id="mass_select_all" data-to-table="bill_of_material_detail_table"  class="form-check-input"><label></label></div>',
							app_lang('id'),
							app_lang('display_order'),
							app_lang('component'),
							app_lang('product_qty'),
							app_lang('unit_id'),
							app_lang('apply_on_variants'),
							app_lang('consumed_in_operation'),
							app_lang('options'),


						),'bill_of_material_detail_table',
					); ?>

				</div>
			<div id="modal_wrapper"></div>
		</div>


	</div>

</div>
<div id="contract_file_data"></div>

<?php echo form_hidden('bill_of_material_id',$bill_of_material_id); ?>
<?php echo form_hidden('bill_of_material_product_id',$product_id); ?>
<?php echo form_hidden('bill_of_material_routing_id',$routing_id); ?>
<!-- init_tail -->
<?php 
require 'plugins/Manufacturing/assets/js/bill_of_materials/add_edit_bill_of_material_js.php';
require 'plugins/Manufacturing/assets/js/bill_of_materials/bill_of_material_details/bill_of_material_detail_manage_js.php';

?>
</body>
</html>
