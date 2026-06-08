<div id="items-dropzone" class="post-dropzone">
<div id="page-content" class="page-wrapper clearfix">
	<div class="row">
		<?php 
		$id = '';
		$title = '';
		$can_be_sold = '';
		$can_be_purchased ='';
		$can_be_manufacturing= '';
		$product_type ='';
		$sku_code ='';
		$unit_id = '';
		$purchase_unit_measure='';
		$description='';
		$description_sale='';
		$product_title='';
		$replenish_on_order='';
		$manufacture='';
		$weight='';
		$volume='';
		$hs_code='';
		$category_id='';
		$tax1='';
		$tax2='';

		$description_delivery_orders='';
		$description_receipts='';
		$description_internal_transfers='';
		$supplier_taxes_id='';
		$ordered_quantities='';
		$delivered_quantities='';

		if(isset($product)){
			$title .= app_lang('update_product');
			$id    = $product->id;
			$type    = $type;

			if($product->can_be_sold =='can_be_sold'){
				$can_be_sold = 'checked';
			}
			if($product->can_be_purchased =='can_be_purchased'){
				$can_be_purchased = 'checked';
			}
			if($product->can_be_manufacturing =='can_be_manufacturing'){
				$can_be_manufacturing = 'checked';
			}

			if($product->replenish_on_order =='replenish_on_order'){
				$replenish_on_order = 'checked';
			}
			if($product->manufacture =='manufacture'){
				$manufacture = 'checked';
			}


			$product_type = $product->product_type;
			$rate = $product->rate;
			$barcode = $product->commodity_barcode;
			$purchase_price = $product->purchase_price;
			$sku_code = $product->sku_code;
			$unit_id = $product->unit_id;
			$description = $product->description;
			$description_sale = $product->description_sale;
			$product_title = $product->title;
			$category_id = $product->category_id;
			$tax1 = $product->tax;
			$tax2 = $product->tax2;

			$weight = $product->weight;
			$volume = $product->volume;
			$hs_code = $product->hs_code;

			$purchase_unit_measure = $product->purchase_unit_measure;
			$manufacturing_lead_time = $product->manufacturing_lead_time;
			$customer_lead_time = $product->customer_lead_time;

			if($product->invoice_policy == 'ordered_quantities'){
				$ordered_quantities = 'checked';
			}else{
				$delivered_quantities = 'checked';
			}
			

			$description_delivery_orders= $product->description_delivery_orders;
			$description_receipts= $product->description_receipts;
			$description_internal_transfers= $product->description_internal_transfers;
			if(strlen($product->supplier_taxes_id) > 0 ){
				$array_supplier_taxes_id = explode(',', $product->supplier_taxes_id);
			}

		}else{
			$title .= app_lang('add_product');

			$can_be_sold = 'checked';
			$can_be_purchased ='checked';
			$can_be_manufacturing= 'checked';

			$product_type = 'storable_product';
			$rate = 1.0;
			$barcode = mrp_generate_commodity_barcode();
			$purchase_price = 0.0;
			$manufacturing_lead_time = 0.0;
			$customer_lead_time = 0.0;
			$weight = 0.0;
			$volume = 0.0;

			$ordered_quantities = 'checked';
			$delivered_quantities = '';
		}

		?>

		<?php echo form_open_multipart(site_url('manufacturing/add_edit_product/'.$type.'/'.$id), array('id' => 'add_update_product','autocomplete'=>'off', 'class' => 'general-form')); ?>

		<div class="col-md-12" >

			<div class="card">
				<div class="page-title clearfix">
					<h4 class="no-margin font-bold"><i class="fa fa-clone menu-icon menu-icon" aria-hidden="true"></i> <?php echo html_entity_decode($title); ?></h4>
					<div class="title-button-group">
					</div>
				</div>

				<div class="row mb-5 d-none">

					<div class="col-md-7 ">

						<div class="o_not_full oe_button_box"><button type="button" name="240" class="btn oe_stat_button"><i class="fa fa-fw o_button_icon fa-pie-chart"></i><div class="o_field_widget o_stat_info"><span class="o_stat_value"><div name="oee" class="o_field_widget o_stat_info o_readonly_modifier" data-original-title="" title="">
							<span class="o_stat_value">0.00</span>
							<span class="o_stat_text"></span>
						</div>%</span><span class="o_stat_text">OEE</span></div></button><button type="button" name="241" class="btn oe_stat_button"><i class="fa fa-fw o_button_icon fa-bar-chart"></i><div class="o_field_widget o_stat_info"><span class="o_stat_value"><div name="blocked_time" class="o_field_widget o_stat_info o_readonly_modifier" data-original-title="" title="">
							<span class="o_stat_value">0.00</span>
							<span class="o_stat_text"></span>
						</div> Hours</span><span class="o_stat_text">Lost</span></div></button><button type="button" name="237" class="btn oe_stat_button" context="{'search_default_workcenter_id': id}"><i class="fa fa-fw o_button_icon fa-bar-chart"></i><div class="o_field_widget o_stat_info"><span class="o_stat_value"><div name="workcenter_load" class="o_field_widget o_stat_info o_readonly_modifier">
							<span class="o_stat_value">0.00</span>
							<span class="o_stat_text"></span>
						</div> Minutes</span><span class="o_stat_text">Load</span></div></button><button type="button" name="243" class="btn oe_stat_button" context="{'search_default_workcenter_id': id, 'search_default_thisyear': True}"><i class="fa fa-fw o_button_icon fa-bar-chart"></i><div class="o_field_widget o_stat_info"><span class="o_stat_value"><div name="performance" class="o_field_widget o_stat_info o_readonly_modifier" data-original-title="" title="">
							<span class="o_stat_value">0</span>
							<span class="o_stat_text"></span>
						</div>%</span><span class="o_stat_text">Performance</span></div></button>
					</div>

				</div>
			</div>

			<div class="card-body">

				<!-- start tab -->
				<div class="tab-content">
					<!-- start general infor -->
					<div class="row">
						<div class="row">

							<div class="col-md-12">
								<input type="hidden" name="id" value="<?php echo html_entity_decode($id) ?>">

								<?php echo render_input1('title','product_name',$product_title,'text', [], [], '', '', true); ?>

								<div class="form-group">
									<div class="checkbox checkbox-primary">
										<input  type="checkbox" id="can_be_sold" class="form-check-input" name="can_be_sold" value="can_be_sold" <?php echo html_entity_decode($can_be_sold); ?>>
										<label for="can_be_sold"><?php echo app_lang('can_be_sold'); ?></label>
									</div>
									<div class="checkbox checkbox-primary">
										<input  type="checkbox" id="can_be_purchased" class="form-check-input" name="can_be_purchased" value="can_be_purchased" <?php echo html_entity_decode($can_be_purchased); ?>>
										<label for="can_be_purchased"><?php echo app_lang('can_be_purchased'); ?></label>
									</div>
									<div class="checkbox checkbox-primary">
										<input  type="checkbox" id="can_be_manufacturing" class="form-check-input" name="can_be_manufacturing" value="can_be_manufacturing" <?php echo html_entity_decode($can_be_manufacturing); ?>>
										<label for="can_be_manufacturing"><?php echo app_lang('can_be_manufacturing'); ?></label>
									</div>
								</div>
							</div>

						</div>

						<?php if(isset($product) && $type == 'product_variant'){ ?>
							<?php if($product->attributes != null) { ?>
								<div class="row">
									<div class="col-md-12">
										<?php $array_attributes = json_decode($product->attributes);
										foreach ($array_attributes as $att_key => $att_value) {
											?>
											<button type="button" class="btn btn-sm btn-primary btn_text_tr"><?php echo html_entity_decode($att_value->name.' : '.$att_value->option); ?></button>
										<?php } ?>
									</div>
								</div>
							<?php } ?>
						<?php } ?>
					</div>
					<div class="row">

						<ul class="nav nav-tabs pb15" id="myTab" role="tablist">
							<li class="nav-item" role="presentation">
								<button class="nav-link active" id="general_information-tab" data-bs-toggle="tab" data-bs-target="#general_information" type="button" role="tab" aria-controls="general_information" aria-selected="true"><?php echo _l('tab_general_information'); ?></button>
							</li>
							<li class="nav-item <?php if($type == 'product_variant'){ echo 'd-none';} ?>" role="presentation" class="">
								<button class="nav-link" id="tab_variants-tab" data-bs-toggle="tab" data-bs-target="#tab_variants" type="button" role="tab" aria-controls="tab_variants" aria-selected="false"><?php echo _l('tab_variants'); ?></button>
							</li>

							<li class="nav-item" role="presentation" class="hide tab_sales_hide">
								<button class="nav-link" id="tab_sales-tab" data-bs-toggle="tab" data-bs-target="#tab_sales" type="button" role="tab" aria-controls="tab_sales" aria-selected="false"><?php echo _l('tab_sales'); ?></button>
							</li>
							<li class="nav-item" role="presentation" class="tab_purchase_hide">
								<button class="nav-link" id="tab_purchase-tab" data-bs-toggle="tab" data-bs-target="#tab_purchase" type="button" role="tab" aria-controls="tab_purchase" aria-selected="false"><?php echo _l('tab_purchase'); ?></button>
							</li>
							<li class="nav-item" role="presentation">
								<button class="nav-link" id="tab_inventory-tab" data-bs-toggle="tab" data-bs-target="#tab_inventory" type="button" role="tab" aria-controls="tab_inventory" aria-selected="false"><?php echo _l('tab_inventory'); ?></button>
							</li>

						</ul>

						<div class="tab-content" id="myTabContent">
							<div class="tab-pane fade show active" id="general_information" role="tabpanel" aria-labelledby="general_information-tab">

								<div class="row">
									<div class="col-md-6">
										<?php echo render_select1('product_type',$array_product_type,array('name', 'label'), 'product_type', $product_type,[], [], '', '' , false); ?>   
									</div>
									<div class="col-md-6">
										<?php echo render_input1('rate','sales_price',$rate,'number'); ?> 
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<?php echo render_select1('category_id',$product_group,array('id', 'title'), 'product_category','',[], [], $category_id, '' , false); ?>   
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label class="control-label" for="tax"><?php echo app_lang('tax_1'); ?></label>
											<select class="select2 validate-hidden display-block" data-width="100%" name="tax" data-none-selected-text="<?php echo app_lang('no_tax'); ?>">
												<option value=""></option>
												<?php foreach($taxes as $tax){ ?>
													<?php 
													$tax1_select='';
													if($tax['id'] == $tax1){
														$tax1_select .='selected';
													}
													?>
													<option value="<?php echo html_entity_decode($tax['id']); ?>" data-subtext="<?php echo html_entity_decode($tax['title']); ?>" <?php echo html_entity_decode($tax1_select) ?>><?php echo html_entity_decode($tax['percentage']); ?>%</option>
												<?php } ?>
											</select>
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label class="control-label" for="tax2"><?php echo app_lang('tax_2'); ?></label>
											<select class="select2 validate-hidden display-block" data-width="100%" name="tax2" data-none-selected-text="<?php echo app_lang('no_tax'); ?>">
												<option value=""></option>
												<?php foreach($taxes as $tax){ ?>
													<?php 
													$tax2_select='';
													if($tax['id'] == $tax2){
														$tax2_select .='selected';
													}
													?>
													<option value="<?php echo html_entity_decode($tax['id']); ?>" data-subtext="<?php echo html_entity_decode($tax['title']); ?>" <?php echo html_entity_decode($tax2_select) ?>><?php echo html_entity_decode($tax['percentage']); ?>%</option>
												<?php } ?>
											</select>
										</div>
									</div>

								</div>	
								<div class="row">
									<div class="col-md-6">
										<?php echo render_input1('commodity_barcode','barcode',$barcode,'text'); ?> 
									</div>
									<div class="col-md-6">
										<?php echo render_input1('purchase_price','mrp_cost', $purchase_price,'number'); ?> 
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<?php echo render_input1('sku_code','sku_code', $sku_code,'text'); ?> 
									</div>
									<div class="col-md-6">
										<?php echo render_select1('unit_id',$units,array('unit_type_id', 'unit_name'), 'unit_of_measure', $unit_id,[], [], '', '' , false, true); ?>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6"></div>
									<div class="col-md-6">
										<?php echo render_select1('purchase_unit_measure',$units,array('unit_type_id', 'unit_name'), 'purchase_unit_measure', $purchase_unit_measure,[], [], '', '' , false, true); ?>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<?php echo render_textarea1('description', 'internal_notes', $description); ?>
									</div>
								</div>

								<div class="form-group">
									<div class="row">
										<div class="col-md-12 row pr0">
											<?php
											echo view("includes/file_list", array("files" => $model_info->files, "image_only" => true));
											?>
										</div>
									</div>
								</div>

								<?php echo view("includes/dropzone_preview"); ?>

								<button class="btn btn-default upload-file-button float-start btn-sm round me-auto color-7988a2" type="button" ><i data-feather="camera" class="icon-16"></i> <?php echo app_lang("upload_image"); ?></button>


							</div>

							<div class="tab-pane fade <?php if($type == 'product_variant'){ echo 'd-none';} ?>" id="tab_variants" role="tabpanel" aria-labelledby="tab_variants-tab">

								<label class="variant_note"><?php echo app_lang('variant_note'); ?></label>
								<div class="row">
									<div class="list_approve">
										<?php if($type == 'product_variant'){ 
											echo view("Manufacturing\Views\products/render_attribute");

										}else{
											echo view("Manufacturing\Views\products/render_variant");
										} ?>

									</div>

								</div>
							</div>
							<div class="tab-pane fade tab_sales_hide" id="tab_sales" role="tabpanel" aria-labelledby="tab_sales-tab">
								<div class="row">
									<div class="col-md-6">

										<div class="form-group">
											<label for="profit_rate" class="control-label clearfix"><?php echo app_lang('invoice_policy_label'); ?></label>
											<div class="radio radio-primary radio-inline" >
												<input  type="radio" class="form-check-input" id="ordered_quantities" name="invoice_policy" value="ordered_quantities" <?php echo html_entity_decode($ordered_quantities) ; ?>>
												<label for="ordered_quantities"><?php echo app_lang('ordered_quantities'); ?></label>

											</div>
											<div class="radio radio-primary radio-inline" >
												<input  type="radio" class="form-check-input" id="delivered_quantities" name="invoice_policy" value="delivered_quantities" <?php  echo html_entity_decode($delivered_quantities); ?>>
												<label for="delivered_quantities"><?php echo app_lang('delivered_quantities'); ?></label>

											</div>
										</div>
									</div>
								</div>

								<div class="row">
									<div class="col-md-12">
										<?php echo render_textarea1('description_sale', 'description_sale', $description_sale); ?>
									</div>
								</div>
							</div>

							<div class="tab-pane fade tab_purchase_hide" id="tab_purchase" role="tabpanel" aria-labelledby="tab_purchase-tab">


								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<label class="control-label" for="supplier_taxes_id"><?php echo app_lang('supplier_taxes_label'); ?></label>
											<select class="select2 validate-hidden display-block" data-width="100%" id="supplier_taxes_id" name="supplier_taxes_id[]" multiple="true" data-actions-box="true" data-none-selected-text="<?php echo app_lang('no_tax'); ?>">
												<?php foreach($taxes as $tax){ ?>
													<?php 
													$supplier_taxes_selected='';

													if(isset($array_supplier_taxes_id) && count($array_supplier_taxes_id) > 0){
														if(in_array($tax['id'], $array_supplier_taxes_id)){
															$supplier_taxes_selected .= 'selected';
														}
													}
													?>
													<option value="<?php echo html_entity_decode($tax['id']); ?>" data-subtext="<?php echo html_entity_decode($tax['title']); ?>" <?php echo html_entity_decode($supplier_taxes_selected); ?>><?php echo html_entity_decode($tax['percentage']); ?>%</option>
												<?php } ?>
											</select>
										</div>
									</div>
								</div>
							</div>
							<div class="tab-pane fade " id="tab_inventory" role="tabpanel" aria-labelledby="tab_inventory-tab">

								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<h4><?php echo app_lang('operations') ; ?></h4>
											<label><?php echo app_lang('routes'); ?></label>
											<div class="checkbox checkbox-primary">
												<input  type="checkbox" class="form-check-input" id="replenish_on_order" name="replenish_on_order" value="replenish_on_order" <?php echo html_entity_decode($replenish_on_order) ?>>
												<label for="replenish_on_order"><?php echo app_lang('replenish_on_order_MTO'); ?></label>
											</div>
											<div class="checkbox checkbox-primary">
												<input  type="checkbox" class="form-check-input" id="manufacture" name="manufacture" value="manufacture" <?php echo html_entity_decode($manufacture) ?> >
												<label for="manufacture"><?php echo app_lang('manufacture'); ?></label>
											</div>

										</div>
										<?php echo render_input1('manufacturing_lead_time','manufacturing_lead_time',$manufacturing_lead_time,'number'); ?> 
										<?php echo render_input1('customer_lead_time','customer_lead_time',$customer_lead_time,'number'); ?> 
									</div>
									<div class="col-md-6">
										<h4><?php echo app_lang('logistics') ; ?></h4>
										<?php echo render_input1('weight','product_weight',$weight,'number'); ?> 
										<?php echo render_input1('volume','product_volume',$volume,'number'); ?> 
										<?php echo render_input1('hs_code','hs_code',$hs_code,'text'); ?> 
									</div>

								</div>
								<div class="row">
									<div class="col-md-12">
										<?php echo render_textarea1('description_delivery_orders', 'description_delivery_orders', $description_delivery_orders); ?>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<?php echo render_textarea1('description_receipts', 'description_receipts', $description_receipts); ?>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<?php echo render_textarea1('description_internal_transfers', 'description_internal_transfers', $description_internal_transfers); ?>
									</div>
								</div>

							</div>
						</div>
					</div>

				</div>

				<div class="modal-footer">
					<?php if($type == 'product_variant'){ ?>
						<a href="<?php echo site_url('manufacturing/product_variant_management'); ?>"  class="btn btn-default mr-2 "><?php echo app_lang('close'); ?></a>
					<?php }else{ ?>
						<a href="<?php echo site_url('manufacturing/product_management'); ?>"  class="btn btn-default mr-2 "><?php echo app_lang('close'); ?></a>
					<?php } ?>
					<?php if(mrp_has_permission('manufacturing_can_create') || mrp_has_permission('manufacturing_can_edit')){ ?>
						<button type="submit" class="btn btn-info pull-right submit_button text-white"><span data-feather="check-circle" class="icon-16" ></span> <?php echo app_lang('submit'); ?></button>

					<?php } ?>
				</div>


			</div>

			<?php echo form_close(); ?>
		</div>
		</div>

		<div id="box-loading"></div>

		<!-- init_tail -->
		<?php 
		require 'plugins/Manufacturing/assets/js/products/add_edit_product_js.php';
		?>
	</body>
	</html>
