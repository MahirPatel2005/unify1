<div id="page-content" class="page-wrapper clearfix">
	<div class="row">

		<div class="col-sm-3 col-lg-2">
			<?php
			$tab_view['active_tab'] = "unit_of_measure";
			echo view("Manufacturing\Views\settings/tabs", $tab_view);
			?>
		</div>

		<div class="col-sm-9 col-lg-10">
			<div class="card">
				<div class="page-title clearfix">
					<h4 class="no-margin font-bold"><i class="fa fa-clone menu-icon menu-icon" aria-hidden="true"></i> <?php echo app_lang('unit_of_measure'); ?></h4>
					<div class="title-button-group">
						<?php if(mrp_has_permission('manufacturing_can_create')){ ?>
							<a href="#" onclick="add_edit_unit_measure(0,'add'); return false;" class="btn btn-info pull-left display-block mright5 text-white"><span data-feather="plus-circle" class="icon-16"></span> <?php echo app_lang('mrp_add'); ?></a>
						<?php } ?>
					</div>
				</div>


				<?php render_datatable1(array(
					app_lang('id'),
					app_lang('unit_of_measure'),
					app_lang('category'),
					app_lang('mrp_type'),
					app_lang('options'),
				),'unit_of_measure_table'); ?>

				<div id="modal_wrapper"></div>
			</div>
		</div>
	</div>
</div>
<?php require 'plugins/Manufacturing/assets/js/settings/unit_of_measure_js.php';?>

</body>
</html>
