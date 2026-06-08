<div id="page-content" class="page-wrapper clearfix">
	<div class="row">
		<div class="col-md-12" id="small-table">

			<div class="card">
				<?php 
				$type ='product';
				?>

				<div class="page-title clearfix">
					<h4 class="no-margin font-bold"><i class="fa fa-clone menu-icon menu-icon" aria-hidden="true"></i> <?php echo html_entity_decode($title); ?></h4>
					<div class="title-button-group">
						<?php if(mrp_has_permission('manufacturing_can_create')){ ?>
							<a href="<?php echo site_url('manufacturing/add_edit_product/'.$type); ?>" class="btn btn-info pull-left display-block mright5 text-white"><span data-feather="plus-circle" class="icon-16"></span> <?php echo app_lang('add'); ?></a>
						<?php } ?>
					</div>
				</div>

				<div class="row  ml2 mr5 mt8">
					<div class=" col-md-3 hide">
						<select name="commodity_filter[]" id="commodity_filter" class="select2 validate-hidden pull-right" data-live-search="true" multiple="true" data-width="100%" placeholder="<?php echo app_lang('Commodity'); ?>">
							<?php foreach($commodity_filter as $commodity) { ?>
								<option value="<?php echo html_entity_decode($commodity['id']); ?>"><?php echo html_entity_decode($commodity['commodity_code'].' '.$commodity['title']); ?></option>
							<?php } ?>
						</select>
					</div>

					<div class=" col-md-4 ">
						<div class="form-group">
							<select name="item_filter[]" id="item_filter" class="select2 validate-hidden" multiple="true" data-actions-box="true" data-live-search="true" data-width="100%" placeholder="<?php echo app_lang('product_label'); ?>">

								<?php foreach($parent_products as $parent_product) { ?>
									<option value="<?php echo html_entity_decode($parent_product['id']); ?>"><?php echo html_entity_decode($parent_product['commodity_code'].' '.$parent_product['title']); ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class=" col-md-4 ">
						<div class="form-group">
							<select name="product_type_filter[]" id="product_type_filter" class="select2 validate-hidden" multiple="true" data-actions-box="true"  data-live-search="true" data-width="100%" placeholder="<?php echo app_lang('product_type'); ?>">

								<?php foreach($product_types as $product_type) { ?>
									<option value="<?php echo html_entity_decode($product_type['name']); ?>"><?php echo html_entity_decode($product_type['label']); ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class=" col-md-4 ">
						<div class="form-group">
							<select name="product_category_filter[]" id="product_category_filter" class="select2 validate-hidden" multiple="true" data-actions-box="true" data-live-search="true" data-width="100%" placeholder="<?php echo app_lang('product_category'); ?>">

								<?php foreach($product_categories as $product_categorie) { ?>
									<option value="<?php echo html_entity_decode($product_categorie['id']); ?>"><?php echo html_entity_decode($product_categorie['title']); ?></option>
								<?php } ?>

							</select>
						</div>
					</div>


				</div>

				<div class="row">
					<div class="col-md-12">
						<!-- view/manage -->            
						<div class="modal fade bulk_actions" id="product_table_bulk_actions" tabindex="-1" role="dialog">
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

						<div class="modal export_item hide" id="product_table_export_item" tabindex="-1" role="dialog">
							<div class="modal-dialog" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h4 class="modal-title"><?php echo _l('export_item'); ?></h4>
										<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
									</div>
									<div class="modal-body">
										<?php if(mrp_has_permission('manufacturing_can_delete') || is_admin()){ ?>
											<div class="checkbox checkbox-danger">
												<input type="checkbox" name="mass_delete" class="form-check-input" id="mass_delete">
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

						<!-- print barcode -->      
						<?php echo form_open_multipart(get_uri('manufacturing/download_barcode'), array('id'=>'item_print_barcode')); ?>      
						<div class="modal fade bulk_actions" id="table_commodity_list_print_barcode" tabindex="-1" role="dialog">
							<div class="modal-dialog" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h4 class="modal-title"><?php echo _l('print_barcode'); ?></h4>
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
														<select name="item_select_print_barcode[]" id="item_select_print_barcode" class="select2 validate-hidden" data-live-search="true" multiple="true" data-actions-box="true" data-width="100%" placeholder="<?php echo app_lang('select_item_print_barcode'); ?>">

															<?php foreach($commodity_filter as $commodity) { ?>
																<option value="<?php echo html_entity_decode($commodity['id']); ?>"><?php echo html_entity_decode($commodity['description']); ?></option>
															<?php } ?>
														</select>
													</div>
												</div>
											</div>

										<?php } ?>
									</div>
									<div class="modal-footer">

										<button type="button" class="btn btn-default" data-bs-dismiss="modal"><i data-feather="x" class="icon-16"></i> <?php echo app_lang('close'); ?></button>

										<?php if(mrp_has_permission('manufacturing_can_create')){ ?>
											<button type="submit" class="btn btn-info text-white" ><span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang('confirm'); ?></button>
										<?php } ?>

									</div>
								</div>
							</div>
						</div>
						<?php echo form_close(); ?>


						<a href="#"  onclick="staff_bulk_actions(); return false;" data-toggle="modal" data-table=".table-product_table" data-target="#leads_bulk_actions" class=" hide bulk-actions-btn table-btn"><?php echo app_lang('bulk_actions'); ?></a>

						<a class="hide" href="#"  onclick="staff_export_item(); return false;" data-toggle="modal" data-table=".table-product_table" data-target="#leads_export_item" class=" hide bulk-actions-btn table-btn"><?php echo app_lang('export_item'); ?></a>

						<a href="#"  onclick="print_barcode_bulk_actions(); return false;" data-toggle="modal" data-table=".table-product_table" data-target="#print_barcode_item" class=" hide print_barcode-bulk-actions-btn table-btn"><?php echo app_lang('print_barcode'); ?></a>

						<?php 
						$table_data = array(
							'<span class="hide"> - </span><div class="checkbox mass_select_all_wrap"><input type="checkbox" id="mass_select_all" data-to-table="product_table" class="form-check-input"><label></label></div>',
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

						render_datatable1($table_data,'product_table',
					); ?>

				</div>
			</div>


		</div>
	</div>


</div>

<?php require 'plugins/Manufacturing/assets/js/products/product_management_js.php';?>
</body>
</html>
