<div class="modal fade" id="appointmentModal">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"><?php echo html_entity_decode(app_lang('add_routing')); ?></h4>
			</div>
			<?php echo form_open(site_url('manufacturing/add_routing_modal'), array('id' => 'add_routing', 'class' => 'general-form')); ?>
			<div class="modal-body">
				<div class="tab-content">
					<div class="row">

						<div class="col-md-12">
							<div class="row">
								<div class="col-md-4">
									<?php echo render_input1('routing_code','routing_code', $routing_code,'text', [], [], '', '', true); ?>   
								</div>
								<div class="col-md-8">
									<?php echo render_input1('routing_name','routing_name','','text', [], [], '', '', true); ?>   
								</div>
							</div>
							
							<div class="col-md-12">
								<?php echo render_textarea1('description','routing_description', '',array(),array(),'',''); ?>
							</div>	
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> <?php echo app_lang('close'); ?></button>
				<button type="submit" class="btn btn-info text-white"><span data-feather="check-circle" class="icon-16" ></span> <?php echo app_lang('submit'); ?></button>
			</div>

		</div>

		<?php echo form_close(); ?>
	</div>
</div>
</div>
<?php require 'plugins/Manufacturing/assets/js/routings/add_edit_routing_js.php';?>
