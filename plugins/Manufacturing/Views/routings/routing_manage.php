<div id="page-content" class="page-wrapper clearfix">
	<div class="row">
		<div class="col-md-12">
			<div class="card">

				<div class="page-title clearfix">
					<h4 class="no-margin font-bold"><i class="fa fa-clone menu-icon menu-icon" aria-hidden="true"></i> <?php echo app_lang('routing'); ?></h4>
					<div class="title-button-group">
						<?php if(mrp_has_permission('manufacturing_can_create')){ ?>
							<a href="#" onclick="add_routing(0,0,' hide'); return false;" class="btn btn-info pull-left display-block mright5 text-white"><span data-feather="plus-circle" class="icon-16"></span> <?php echo app_lang('add_routing'); ?></a>
						<?php } ?>
					</div>
				</div>

				<?php render_datatable1(array(
					app_lang('id'),
					app_lang('routing_code'),
					app_lang('routing_name'),
					app_lang('routing_description'),
					app_lang('options'),
					
				),'routing_table'); ?>
			</div>

		</div>
	</div>
	<div id="modal_wrapper"></div>
</div>

<?php require 'plugins/Manufacturing/assets/js/routings/routing_manage_js.php';?>
</body>
</html>
