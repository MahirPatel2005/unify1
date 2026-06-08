<div id="page-content" class="page-wrapper clearfix">
	<div class="row">

		<div class="col-sm-3 col-lg-2">
			<?php
			$tab_view['active_tab'] = "mrp_general_setting";
			echo view("Manufacturing\Views\settings/tabs", $tab_view);
			?>
		</div>

		<div class="col-sm-9 col-lg-10">
			<div class="card">
				<div class="page-title clearfix">
					<h4 class="no-margin font-bold"><i class="fa fa-clone menu-icon menu-icon" aria-hidden="true"></i> <?php echo app_lang('mrp_general_setting'); ?></h4>
				</div>

				<?php echo form_open_multipart(site_url('manufacturing/prefix_number'),array('class'=>'prefix_number','autocomplete'=>'off')); ?>
				<div class="card-body">

					<div class="row">
						<div class="col-md-12">
							<h5 class="no-margin font-bold h5-color"><?php echo app_lang('BOM_code') ?></h5>
							<hr class="hr-color">
						</div>
					</div>

					<div class="form-group">
						<label><?php echo app_lang('mrp_bom_prefix'); ?></label>
						<div  class="form-group" app-field-wrapper="bom_prefix">
							<input type="text" id="bom_prefix" name="bom_prefix" class="form-control" value="<?php echo get_setting('bom_prefix'); ?>"></div>
						</div>

						<div class="form-group">
							<label><?php echo app_lang('mrp_bom_number'); ?></label>
							<i class="fa fa-question-circle i_tooltip" data-toggle="tooltip" title="" data-original-title="<?php echo app_lang('mrp_next_number_tooltip'); ?>"></i>
							<div  class="form-group" app-field-wrapper="bom_number">
								<input type="number" min="0" id="bom_number" name="bom_number" class="form-control" value="<?php echo get_setting('bom_number'); ?>">
							</div>

						</div>

						<div class="row">
							<div class="col-md-12">
								<h5 class="no-margin font-bold h5-color"><?php echo app_lang('routing_code') ?></h5>
								<hr class="hr-color">
							</div>
						</div>

						<div class="form-group">
							<label><?php echo app_lang('mrp_routing_prefix'); ?></label>
							<div  class="form-group" app-field-wrapper="routing_prefix">
								<input type="text" id="routing_prefix" name="routing_prefix" class="form-control" value="<?php echo get_setting('routing_prefix'); ?>"></div>
							</div>

							<div class="form-group">
								<label><?php echo app_lang('mrp_routing_number'); ?></label>
								<i class="fa fa-question-circle i_tooltip" data-toggle="tooltip" title="" data-original-title="<?php echo app_lang('mrp_next_number_tooltip'); ?>"></i>
								<div  class="form-group" app-field-wrapper="routing_number">
									<input type="number" min="0" id="routing_number" name="routing_number" class="form-control" value="<?php echo get_setting('routing_number'); ?>">
								</div>

							</div>

							<div class="row">
								<div class="col-md-12">
									<h5 class="no-margin font-bold h5-color"><?php echo app_lang('mo_code') ?></h5>
									<hr class="hr-color">
								</div>
							</div>

							<div class="form-group">
								<label><?php echo app_lang('mrp_mo_prefix'); ?></label>
								<div  class="form-group" app-field-wrapper="mo_prefix">
									<input type="text" id="mo_prefix" name="mo_prefix" class="form-control" value="<?php echo get_setting('mo_prefix'); ?>">
								</div>
							</div>

							<div class="form-group">
								<label><?php echo app_lang('mrp_mo_number'); ?></label>
								<i class="fa fa-question-circle i_tooltip" data-toggle="tooltip" title="" data-original-title="<?php echo app_lang('mrp_next_number_tooltip'); ?>"></i>
								<div  class="form-group" app-field-wrapper="mo_number">
									<input type="number" min="0" id="mo_number" name="mo_number" class="form-control" value="<?php echo get_setting('mo_number'); ?>">
								</div>

							</div>

							<div class="row">
								<div class="col-md-12">
									<h5 class="no-margin font-bold h5-color"><?php echo app_lang('working_hour') ?></h5>
									<hr class="hr-color">
								</div>
							</div>

							<div class="form-group">
								<label><?php echo app_lang('cost_hour'); ?></label>
								<div  class="form-group" app-field-wrapper="cost_hour">
									<input type="number" id="cost_hour" name="cost_hour" class="form-control" value="<?php echo get_setting('cost_hour'); ?>">
								</div>
							</div>

							<div class="clearfix"></div>

							<div class="modal-footer">
								<?php if(mrp_has_permission('manufacturing_can_create') || mrp_has_permission('manufacturing_can_edit') ){ ?>
									<button type="submit" class="btn btn-info text-white"><span data-feather="check-circle" class="icon-16" ></span>  <?php echo app_lang('submit'); ?></button>
								<?php } ?>
							</div>


							<?php echo form_close(); ?>

						</div>
					</div>
				</div>
			</div>
		</body>
		</html>


