<div id="page-content" class="page-wrapper clearfix">
	<div class="row">

		<div class="col-md-5">
			<div class="row">
				<div class="card">
					<?php 

					$routing_id = isset($routing) ? $routing->id : '';
					$routing_code = isset($routing) ? $routing->routing_code : '';
					$routing_name = isset($routing) ? $routing->routing_name : '';
					$description = isset($routing) ? $routing->description : '';
					?>
					<?php echo form_open(site_url('manufacturing/add_routing_modal/'.$routing_id), array('id' => 'add_routing', 'class' => 'general-form')); ?>

					<div class="page-title clearfix">
						<h4 class="no-margin font-bold"><i class="fa fa-clone menu-icon menu-icon" aria-hidden="true"></i> <?php echo html_entity_decode($routing_code); ?></h4>
						<div class="title-button-group">
						</div>
					</div>

					<!-- start tab -->
					<div class="card-body">
						<div class="row">
							<div class="col-md-12">
								<?php echo render_input1('routing_code','routing_code', $routing_code,'text'); ?>   
							</div>
							<div class="col-md-12">
								<?php echo render_input1('routing_name','routing_name', $routing_name,'text'); ?>   
							</div>

							<div class="col-md-12">
								<?php echo render_textarea1('description','routing_description', $description,array(),array(),'',''); ?>
							</div>	
						</div>

						<div class="modal-footer">
							<a href="<?php echo site_url('manufacturing/routing_manage'); ?>"  class="btn btn-default mr-2 "><?php echo app_lang('close'); ?></a>
							<?php if(mrp_has_permission('manufacturing_can_create') || mrp_has_permission('manufacturing_can_edit')){ ?>
								<button type="submit" class="btn btn-info pull-right text-white"><span data-feather="check-circle" class="icon-16" ></span> <?php echo app_lang('submit'); ?></button>

							<?php } ?>
						</div>
					</div>
					<?php echo form_close(); ?>
				</div>

			</div>
		</div>

		<div class="col-md-7">
			<div class="row ml15">
				<div class="card">
					<div class="page-title clearfix">
						<h4 class="no-margin font-bold"><i class="fa fa-clone menu-icon menu-icon" aria-hidden="true"></i> <?php echo app_lang('operations'); ?></h4>

						<div class="title-button-group">
							<?php if(mrp_has_permission('manufacturing_can_create')){ ?>
								<a href="#" onclick="add_operation(<?php echo html_entity_decode($routing_id) ?>,0,'add'); return false;"  class="btn btn-info pull-left display-block mright5 text-white"><span data-feather="plus-circle" class="icon-16"></span> <?php echo app_lang('add_operation'); ?></a>

							<?php } ?>
						</div>
					</div>


						<?php render_datatable1(array(
							app_lang('id'),
							app_lang('display_order'),
							app_lang('operation'),
							app_lang('work_center_name'),
							app_lang('duration_computation'),
							app_lang('options'),
							
						),'operation_table'); ?>
				</div>

			</div>
			<div id="modal_wrapper"></div>
		</div>



	</div>
	<div id="contract_file_data"></div>

	<?php echo form_hidden('routing_id',$routing_id); ?>
	<!-- init_tail -->
	<?php 
	require 'plugins/Manufacturing/assets/js/routings/add_edit_routing_js.php';
	require 'plugins/Manufacturing/assets/js/routings/routing_details/operation_manage_js.php';

	?>

</body>
</html>
