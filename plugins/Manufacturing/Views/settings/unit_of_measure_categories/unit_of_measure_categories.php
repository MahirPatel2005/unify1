<div id="page-content" class="page-wrapper clearfix">
	<div class="row">

		<div class="col-sm-3 col-lg-2">
			<?php
			$tab_view['active_tab'] = "unit_of_measure_categories";
			echo view("Manufacturing\Views\settings/tabs", $tab_view);
			?>
		</div>

		<div class="col-sm-9 col-lg-10">
			<div class="card">

				<div class="page-title clearfix">
					<h4 class="no-margin font-bold"><i class="fa fa-clone menu-icon menu-icon" aria-hidden="true"></i> <?php echo app_lang('unit_of_measure_categories'); ?></h4>
					<div class="title-button-group">
						<?php if(mrp_has_permission('manufacturing_can_create')){ ?>
							<a href="#" onclick="new_category(); return false;" class="btn btn-info pull-left display-block mright5 text-white"><span data-feather="plus-circle" class="icon-16"></span> <?php echo app_lang('mrp_add'); ?></a>
						<?php } ?>
					</div>
				</div>

				<?php render_datatable1(array(
					app_lang('id'),
					app_lang('category_name'),
					app_lang('options'),
				),'unit_of_measure_category_table'); ?>

				<div class="modal fade" id="measure_category" tabindex="-1" role="dialog">
					<div class="modal-dialog setting-handsome-table">
						<?php echo form_open_multipart(site_url('manufacturing/add_edit_category'), array('id'=>'add_edit_category', 'class' => 'general-form')); ?>

						<div class="modal-content">
							<div class="modal-header">
								<h4 class="modal-title">
									<span class="add-title"><?php echo app_lang('add_category'); ?></span>
									<span class="edit-title"><?php echo app_lang('update_category'); ?></span>
								</h4>
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
							</div>

							<div class="modal-body">
								<div class="row">
									<div class="col-md-12">
										<div id="categories_id"></div>   
										<div class="form"> 
											<div class="col-md-12">
												<?php echo render_input1('category_name', 'category_name', '', '', [], [], '', '', true); ?>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> <?php echo app_lang('close'); ?></button>

								<button type="submit" class="btn btn-info intext-btn text-white"><span data-feather="check-circle" class="icon-16" ></span> <?php echo app_lang('submit'); ?></button>
							</div>
						</div>
						<?php echo form_close(); ?>
					</div>
				</div>   
			</div>   
		</div>   

		<?php require 'plugins/Manufacturing/assets/js/settings/add_edit_categories_js.php';?>

	</body>
	</html>
