<div id="page-content" class="page-wrapper clearfix">
	<div class="row">

		<div class="col-md-12">
			<div class="card">
				<div class="page-title clearfix">
					<h4 class="no-margin font-bold"><i class="fa fa-clone menu-icon menu-icon" aria-hidden="true"></i> <?php echo app_lang('work_orders').' / '.mrp_get_manufacturing_code($mo_id); ?></h4>
					<div class="title-button-group">
						<a href="#" onclick="change_work_order_view(); return false;" class=" toggle-articles-list btn btn-info pull-left display-block mright5 text-white"><span data-feather="bar-chart" class="icon-16"></span> </a>
					</div>
				</div>

				<div class="row hide">
					<div  class="col-md-3 leads-filter-column pull-right">
						<select name="status_filter[]" id="status_filter" data-live-search="true" class="selectpicker" multiple="true" data-actions-box="true" data-width="100%" data-none-selected-text="<?php echo app_lang('status'); ?>">
							<?php foreach($status_data as $status) { ?>
								<option value="<?php echo html_entity_decode($status['name']); ?>"><?php echo html_entity_decode($status['label']); ?></option>
							<?php } ?>
						</select>
					</div> 

					<div  class="col-md-3 leads-filter-column pull-right">
						<select name="routing_filter[]" id="routing_filter" data-live-search="true" class="selectpicker" multiple="true" data-actions-box="true" data-width="100%" data-none-selected-text="<?php echo app_lang('routing_label'); ?>">
							<?php foreach($routings as $routing) { ?>
								<option value="<?php echo html_entity_decode($routing['id']); ?>"><?php echo html_entity_decode($routing['routing_name']); ?></option>
							<?php } ?>
						</select>
					</div> 

					<div  class="col-md-3 leads-filter-column pull-right">
						<select name="products_filter[]" id="products_filter" data-live-search="true" class="selectpicker" multiple="true" data-actions-box="true" data-width="100%" data-none-selected-text="<?php echo app_lang('product_label'); ?>">
							<?php foreach($products as $product) { ?>
								<option value="<?php echo html_entity_decode($product['id']); ?>"><?php echo html_entity_decode($product['description']); ?></option>
							<?php } ?>
						</select>
					</div>

					<div  class="col-md-3 leads-filter-column pull-right">
						<select name="manufacturing_order_filter[]" id="manufacturing_order_filter" data-live-search="true" class="selectpicker" multiple="true" data-actions-box="true" data-width="100%" data-none-selected-text="<?php echo app_lang('manufacturing_order'); ?>">
							<?php foreach($manufacturing_orders as $manufacturing_order) { ?>
								<option value="<?php echo html_entity_decode($manufacturing_order['id']); ?>"><?php echo html_entity_decode($manufacturing_order['manufacturing_order_code']); ?></option>
							<?php } ?>
						</select>
					</div>



				</div>
				<br>

				<div class="col-md-12 tab-content">
					<div role="tabpanel" class="tab-pane kb-kan-ban kan-ban-tab " id="kan-ban">

						<div class="mx-auto mt-3 btn-group fc" role="group">
							<button type="button" class=" button-text-transform fc-quarter-day-button btn btn-sm btn-default active"><?php echo app_lang('quarter_day') ?></button>
							<button type="button" class="button-text-transform fc-half-day-button btn btn-sm btn-default"><?php echo app_lang('half_day') ?></button>
							<button type="button" class="button-text-transform fc-day-button btn btn-sm btn-default"><?php echo app_lang('mrp_day') ?></button>
							<button type="button" class="button-text-transform fc-week-button btn btn-sm btn-default"><?php echo app_lang('mrp_week') ?></button>
							<button type="button" class="button-text-transform fc-month-button btn btn-sm btn-default"><?php echo app_lang('mrp_month') ?></button>
						</div>
						<br>
						<br>
						<div class="table-responsive pt15 pl15 pr15">
							<div class="col-md-12">
								
						<svg class="timeline"></svg>
							</div>
					</div>
					</div>

					<div role="tabpanel" class="tab-pane active" id="list_tab">
						<div class="modal bulk_actions" id="mo_work_order_table_bulk_actions" tabindex="-1" role="dialog">
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
						),'mo_work_order_table',

					); ?>
				</div>

			</div>

		</div>

		<div id="modal_wrapper"></div>

	</div>
	
</div>
<?php echo form_hidden('manufacturing_order_id',$mo_id); ?>

<!-- init_tail -->
<?php 
require 'plugins/Manufacturing/assets/js/manufacturing_orders/mo_list_work_order_js.php';
?>
</body>
</html>
