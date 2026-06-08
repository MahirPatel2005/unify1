<div id="page-content" class="page-wrapper clearfix">
	<div class="row">

		<div class="col-sm-3 col-lg-2">
			<?php
			$tab_view['active_tab'] = "working_hour";
			echo view("Manufacturing\Views\settings/tabs", $tab_view);
			?>
		</div>

		<div class="col-sm-9 col-lg-10">
			<div class="card">
				<div class="page-title clearfix">
					<h4 class="no-margin font-bold"><i class="fa fa-clone menu-icon menu-icon" aria-hidden="true"></i> <?php echo app_lang('working_hour'); ?></h4>
					<div class="title-button-group">
						<?php if(mrp_has_permission('manufacturing_can_create')){ ?>
							<a href="<?php echo site_url('manufacturing/add_edit_working_hour'); ?>" class="btn btn-info pull-left display-block mright5 text-white"><span data-feather="plus-circle" class="icon-16"></span> <?php echo app_lang('add_working_hour'); ?></a>
						<?php } ?>
					</div>
				</div>
				<?php render_datatable1(array(
					app_lang('id'),
					app_lang('working_hour_name'),
					app_lang('hours_per_day'),
					app_lang('options'),
				),'working_hour_table'); ?>
			</div>
		</div>
	</div>
</div>
<?php require 'plugins/Manufacturing/assets/js/settings/working_hour_js.php';?>

</body>
</html>
