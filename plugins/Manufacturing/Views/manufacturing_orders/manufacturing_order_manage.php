<div id="page-content" class="page-wrapper clearfix">
	<div class="row">
		<div class="col-md-12">

			<div class="card">
				<div class="page-title clearfix">
					<h4 class="no-margin font-bold"><i class="fa fa-clone menu-icon menu-icon" aria-hidden="true"></i> <?php echo app_lang('manufacturing_orders'); ?></h4>
					<div class="title-button-group">
						<?php if(mrp_has_permission('manufacturing_can_create')){ ?>
							<a href="<?php echo site_url('manufacturing/add_edit_manufacturing_order'); ?>" class="btn btn-info pull-left display-block mright5 text-white"><span data-feather="plus-circle" class="icon-16"></span> <?php echo app_lang('add_manufacturing_order'); ?></a>
						<?php } ?>
					</div>
				</div>

				<div class="row  ml2 mr5 mt8">
					<div  class="col-md-4 leads-filter-column">
						<div class="form-group">
							<select name="products_filter[]" id="products_filter" data-live-search="true" class="select2 validate-hidden" multiple="true" data-actions-box="true" data-width="100%" placeholder="<?php echo app_lang('product_label'); ?>">
								<?php foreach($products as $product) { ?>
									<option value="<?php echo html_entity_decode($product['id']); ?>"><?php echo html_entity_decode($product['description']); ?></option>
								<?php } ?>
							</select>
						</div>
					</div> 
					<div  class="col-md-4 leads-filter-column">
						<div class="form-group">
							<select name="routing_filter[]" id="routing_filter" data-live-search="true" class="select2 validate-hidden" multiple="true" data-actions-box="true" data-width="100%" placeholder="<?php echo app_lang('routing_label'); ?>">
								<?php foreach($routings as $routing) { ?>
									<option value="<?php echo html_entity_decode($routing['id']); ?>"><?php echo html_entity_decode($routing['routing_name']); ?></option>
								<?php } ?>
							</select>
						</div>
					</div> 
					<div  class="col-md-4 leads-filter-column">
						<div class="form-group">
							<select name="status_filter[]" id="status_filter" data-live-search="true" class="select2 validate-hidden" multiple="true" data-actions-box="true" data-width="100%" placeholder="<?php echo app_lang('status'); ?>">
								<?php foreach($status_data as $status) { ?>
									<option value="<?php echo html_entity_decode($status['name']); ?>"><?php echo html_entity_decode($status['label']); ?></option>
								<?php } ?>
							</select>
						</div>
					</div> 
				</div>

				<div class="modal fade bulk_actions" id="manufacturing_order_table_bulk_actions" tabindex="-1" role="dialog">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h4 class="modal-title"><?php echo app_lang('bulk_actions'); ?></h4>
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
							</div>
							<div class="modal-body">
								<?php if(mrp_has_permission('manufacturing_can_delete') || is_admin()){ ?>
									<div class="checkbox checkbox-danger">
										<input type="checkbox" class="form-check-input"  name="mass_delete" id="mass_delete">
										<label for="mass_delete"><?php echo app_lang('hr_mass_delete'); ?></label>
									</div>
								<?php } ?>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-bs-dismiss="modal"><i data-feather="x" class="icon-16"></i> <?php echo app_lang('close'); ?></button>

								<?php if(mrp_has_permission('manufacturing_can_delete') || is_admin()){ ?>
									<a href="#" class="btn btn-info text-white" onclick="mo_delete_bulk_action(this); return false;"><span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang('confirm'); ?></a>
								<?php } ?>
							</div>
						</div>
					</div>
				</div>

				<?php if (mrp_has_permission('manufacturing_can_delete')) { ?>
					<a href="#"  onclick="staff_bulk_actions(); return false;" data-toggle="modal" data-table=".table-manufacturing_order_table" data-target="#leads_bulk_actions" class=" hide bulk-actions-btn table-btn"><?php echo app_lang('hr_bulk_actions'); ?></a>
				<?php } ?>


				<?php render_datatable1(array(
					'<span class="hide"> - </span><div class="checkbox mass_select_all_wrap"><input type="checkbox" id="mass_select_all" data-to-table="manufacturing_order_table" class="form-check-input"><label></label></div>',

					app_lang('id'),
					app_lang('manufacturing_order_code'),
					app_lang('product_label'),
					app_lang('bill_of_material_label'),
					app_lang('product_qty'),
					app_lang('unit_id'),
					app_lang('routing_label'),
					app_lang('status'),
					app_lang('options'),
				),'manufacturing_order_table',
			); ?>


		</div>
	</div>

	<div id="modal_wrapper"></div>


</div>
</div>
<!-- init_tail -->
<?php 
require 'plugins/Manufacturing/assets/js/manufacturing_orders/manufacturing_order_manage_js.php';
?>
</body>
</html>
