<div id="page-content" class="page-wrapper clearfix">
	<div class="row">
		<div class="col-md-12">

			<div class="card">

				<div class="page-title clearfix">
					<h4 class="no-margin font-bold"><i class="fa fa-clone menu-icon menu-icon" aria-hidden="true"></i> <?php echo app_lang('work_center'); ?></h4>
					<div class="title-button-group">
						<?php if(mrp_has_permission('manufacturing_can_create')){ ?>
							<a href="<?php echo site_url('manufacturing/add_edit_work_center'); ?>" class="btn btn-info pull-left display-block mright5 text-white"><span data-feather="plus-circle" class="icon-16"></span> <?php echo app_lang('add_work_center'); ?></a>
						<?php } ?>
					</div>
				</div>

				<?php render_datatable1(array(
					app_lang('id'),
					app_lang('work_center_code'),
					app_lang('work_center_name'),
					app_lang('work_center_working_hours'),
					app_lang('options'),
				),'work_center_table'); ?>

			</div>
		</div>

	</div>
</div>

<?php require 'plugins/Manufacturing/assets/js/work_centers/work_center_manage_js.php';?>

</body>
</html>
