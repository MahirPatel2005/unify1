<div class="modal fade" id="appointmentModal">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<?php 
				$title='';
				$id='';

				if(isset($bill_of_material_detail)){
					$title =app_lang('update_bill_of_material_detail');
					$id= $bill_of_material_detail->id;

					$product_id= $bill_of_material_detail->product_id;
					$product_qty= $bill_of_material_detail->product_qty;
					$unit_id= $bill_of_material_detail->unit_id;
					$operation_id= $bill_of_material_detail->operation_id;
					$display_order= $bill_of_material_detail->display_order;

					if(strlen($bill_of_material_detail->apply_on_variants) > 0 ){

						$array_apply_on_variants = explode(',', $bill_of_material_detail->apply_on_variants);
					}

				}else{
					$title =app_lang('add_bill_of_material_detail');

					$product_id= '';
					$product_qty= 1.0;
					$unit_id= '';
					$operation_id= '';
					$display_order= 1;

				}

				$bill_of_material_id = isset($bill_of_material_id) ? $bill_of_material_id : '';

				?>
				<h4 class="modal-title"><?php echo html_entity_decode($title); ?></h4>
			</div>
			<?php echo form_open_multipart(site_url('manufacturing/add_edit_bill_of_material_detail/'.$id), array('id' => 'add_edit_bill_of_material_detail', 'class' => 'general-form')); ?>
			
			<div class="modal-body">
				<div class="tab-content">
					<div class="row">
						<input type="hidden" value="<?php echo html_entity_decode($bill_of_material_id); ?>" name="bill_of_material_id">

						<div class="col-md-12">
							<div class="col-md-12"> 
								<?php echo render_select1('product_id',$products,array('id','description'),'component', $product_id, [], [], '', '', true, true); ?>
							</div>

							<div class="row">
								<div class="col-md-6">
									<?php echo render_input1('product_qty','product_qty', $product_qty,'number', [], [], '', '', true); ?> 
								</div>
								<div class="col-md-6">
									<?php echo render_select1('unit_id',$units,array('unit_type_id', 'unit_name'), 'unit_of_measure', $unit_id,[], [], '', '' , false, true); ?>
								</div>
							</div>

							<div class="col-md-12">
								<div class="form-group">
									<label class="control-label" for="apply_on_variants"><?php echo app_lang('apply_on_variants'); ?></label>
									<select class="select2 validate-hidden" data-width="100%" id="apply_on_variants" name="apply_on_variants[]" multiple="true" data-actions-box="true" data-none-selected-text="<?php echo app_lang('dropdown_non_selected_tex'); ?>">
										<?php foreach($arr_variants as $variant){ ?>
											<?php 
											$apply_on_variants_selected='';

											if(isset($array_apply_on_variants) && count($array_apply_on_variants) > 0){
												if(in_array($variant['name'], $array_apply_on_variants)){
													$apply_on_variants_selected .= 'selected';
												}
											}
											?>
											<option value="<?php echo html_entity_decode($variant['name']); ?>" <?php echo html_entity_decode($apply_on_variants_selected); ?>><?php echo html_entity_decode($variant['label']); ?></option>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="col-md-12">
								<?php echo render_select1('operation_id',$arr_operations,array('id', 'operation'), 'consumed_in_operation', $operation_id,[], [], '', '' , true); ?>
							</div>

						</div>

						<div class="col-md-12">
							<div class="col-md-6">
								<?php echo render_input1('display_order','display_order', $display_order,'number', [], [], '', '', true); ?>   
							</div>
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
<?php require 'plugins/Manufacturing/assets/js/bill_of_materials/bill_of_material_details/add_edit_bill_of_material_detail_js.php'; ?>