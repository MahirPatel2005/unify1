<div class="modal fade" id="appointmentModal">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"><?php echo html_entity_decode(app_lang('add_bills_of_material_l')); ?></h4>
			</div>
			<?php echo form_open(site_url('manufacturing/add_bill_of_material_modal'), array('id' => 'add_bill_of_material', 'autocomplete'=>'off', 'class' => 'general-form')); ?>
			<div class="modal-body">
	
				<div class="tab-content">
					<div class="row">
						<?php $ready_to_produce = 'components_for_1st'; ?>
							<div class="col-md-12">
								<?php echo render_input1('bom_code','BOM_code', $bom_code,'text', [], [], '', '', true); ?> 
							</div>
							<div class="col-md-12">
								<?php echo render_select1('product_id',$parent_product,array('id',array('commodity_code', 'title')),'product_label','', [], [], '', '', true, true ); ?>
							</div>
							<div class="col-md-12">
								<?php echo render_select1('product_variant_id',$parent_product,array('id',array('commodity_code', 'title')),'product_variant',''); ?>

							</div>

							<div class="col-md-6">
								<?php echo render_input1('product_qty','product_qty', 1.0,'number'); ?> 
							</div>

							<div class="col-md-6">
								<?php echo render_select1('unit_id',$units,array('unit_type_id', 'unit_name'), 'unit_of_measure', '',[], [], '', '' , false); ?>
							</div>

							<div class="col-md-6">
								<?php echo render_select1('routing_id',$routings,array('id', array('routing_code','routing_name')), 'routing_label', '',[], [], '', '' , true); ?>
							</div>

							<div class="col-md-6">
								<div class="form-group">
									<label for="profit_rate" class="control-label clearfix"><?php echo app_lang('bom_type'); ?></label>
									<div class="radio radio-primary radio-inline" >
										<input type="radio" class="form-check-input" id="manufacture_this_product" name="bom_type" value="manufacture_this_product" checked="true">
										<label for="manufacture_this_product"><?php echo app_lang('manufacture_this_product'); ?></label>

									</div>
								
									<div class="radio radio-primary radio-inline" >
										<input type="radio" id="kit" name="bom_type" value="kit" >
										<label for="kit"><?php echo app_lang('kit'); ?></label>

									</div>
									<div class="kit_hide hide">
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
								<?php echo render_select1('consumption',$consumption_type,array('name', 'label'), 'consumption', '',[], [], '', '' , false); ?>
							</div>
							
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> <?php echo app_lang('close'); ?></button>
				<button type="submit" class="btn btn-info text-white"><span data-feather="check-circle" class="icon-16" ></span> <?php echo app_lang('submit'); ?></button>

			</div>

		</div>

		<?php echo form_close(); ?>
	</div>
</div>
</div>
<?php require 'plugins/Manufacturing/assets/js/bill_of_materials/add_edit_bill_of_material_js.php'; ?>