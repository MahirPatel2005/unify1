<div id="page-content" class="page-wrapper clearfix">
	<div class="row">
		<div class="col-md-12">

			<div class="card">
				<div class="page-title clearfix">
					<h4 class="no-margin font-bold"><i class="fa fa-clone menu-icon menu-icon" aria-hidden="true"></i> <?php echo app_lang('work_orders'); ?></h4>
				</div>

				<div class="row ml2 mr5 mt8">
					<div  class="col-md-4 leads-filter-column">
						<div class="form-group">
							<select name="manufacturing_order_filter[]" id="manufacturing_order_filter" data-live-search="true" class="select2 validate-hidden" multiple="true" data-actions-box="true" data-width="100%" placeholder="<?php echo app_lang('manufacturing_order'); ?>">
								<?php foreach($manufacturing_orders as $manufacturing_order) { ?>
									<option value="<?php echo html_entity_decode($manufacturing_order['id']); ?>"><?php echo html_entity_decode($manufacturing_order['manufacturing_order_code']); ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
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
							<select name="status_filter[]" id="status_filter" data-live-search="true" class="select2 validate-hidden" multiple="true" data-actions-box="true" data-width="100%" placeholder="<?php echo app_lang('status'); ?>">
								<?php foreach($status_data as $status) { ?>
									<option value="<?php echo html_entity_decode($status['name']); ?>"><?php echo html_entity_decode($status['label']); ?></option>
								<?php } ?>
							</select>
						</div>
					</div> 
				</div>

				<div class="modal bulk_actions" id="work_order_table_bulk_actions" tabindex="-1" role="dialog">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title"><?php echo app_lang('hr_bulk_actions'); ?></h4>
							</div>
							<div class="modal-body">
								<?php if(mrp_has_permission('manufacturing_can_delete') || is_admin()){ ?>
									<div class="checkbox checkbox-danger">
										<input type="checkbox" name="mass_delete" id="mass_delete">
										<label for="mass_delete"><?php echo app_lang('hr_mass_delete'); ?></label>
									</div>
								<?php } ?>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo app_lang('hr_close'); ?></button>

								<?php if(mrp_has_permission('manufacturing_can_delete') || is_admin()){ ?>
									<a href="#" class="btn btn-info" onclick="staff_delete_bulk_action(this); return false;"><?php echo app_lang('hr_confirm'); ?></a>
								<?php } ?>
							</div>
						</div>
					</div>
				</div>




				<?php render_datatable1(array(

					app_lang('id'),
					app_lang('work_order_label'),
					app_lang('scheduled_date_start'),
					app_lang('work_center_label'),
					app_lang('manufacturing_order'),
					app_lang('product_label'),
					app_lang('product_qty'),
					app_lang('unit_id'),
					app_lang('status'),
					app_lang('options'),
				),'work_order_table',

			); ?>


		</div>
	</div>

	<div id="modal_wrapper"></div>

</div>
</div>
<!-- init_tail -->
<?php 
require 'plugins/Manufacturing/assets/js/work_orders/work_order_manage_js.php';
?>
</body>
</html>
