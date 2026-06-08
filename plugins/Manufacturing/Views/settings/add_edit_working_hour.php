
<div id="page-content" class="page-wrapper clearfix">
	<div class="row">
		<div class="col-sm-12 col-lg-12">
			<?php 
			$id = '';
			$title = '';
			if(isset($working_hour)){
				$title .= app_lang('update_working_hour');
				$id    = $working_hour->id;
			}else{
				$title .= app_lang('add_working_hour');
			}

			?>

			<?php echo form_open_multipart(site_url('manufacturing/add_edit_working_hour/'.$id), array('id' => 'add_update_working_hour','autocomplete'=>'off')); ?>

			<div class="card">
				<div class="page-title clearfix">
					<h4 class="no-margin font-bold"><i class="fa fa-clone menu-icon menu-icon" aria-hidden="true"></i> <?php echo html_entity_decode($title); ?></h4>
				</div>

				<!-- start tab -->
				<div class="card-body">
					<div class="tab-content">
						<!-- start general infor -->
						<div class="row">
							<div class="row">
								<div class="col-md-6">
									<?php 
									$working_hour_name = isset($working_hour) ? $working_hour->working_hour_name : '';
									$hours_per_day = isset($working_hour) ? $working_hour->hours_per_day : '';

									?>

									<?php echo render_input1('working_hour_name','working_hour_name',$working_hour_name,'text', [], [], '', '', true); ?>   
								</div>
								<div class="col-md-6">
									<?php echo render_input1('hours_per_day','hours_per_day',$hours_per_day,'text', [], [], '', '', true); ?>   
								</div>
							</div>
						</div>


						<div class="row">
						<div class="col-md-12">
							<h5 class="h5-color"><?php echo app_lang('working_hour_info'); ?></h5>
							<hr class="hr-color">

							<div class="form"> 
								<div id="working_hour_hs" class="working_hour handsontable htColumnHeaders">
								</div>
								<?php echo form_hidden('working_hour_hs'); ?>
							</div>
						</div>
						</div>

						<br>
						<br>
						<div class="table-responsive pt15 pl15 pr15">
							<h5 class="h5-color"><?php echo app_lang('global_time_off_info'); ?></h5>
							<hr class="hr-color">

							<div class="form"> 
								<div id="global_time_off_hs" class="global_time_off handsontable htColumnHeaders">
								</div>
								<?php echo form_hidden('global_time_off_hs'); ?>
							</div>
						</div>

					</div>

					<div class="modal-footer">
						<a href="<?php echo site_url('manufacturing/working_hours'); ?>"  class="btn btn-default mr-2 "><?php echo app_lang('hr_close'); ?></a>
						<?php if(mrp_has_permission('manufacturing_can_create') || mrp_has_permission('manufacturing_can_edit')){ ?>

							<a href="#"class="btn btn-info pull-right mright10 display-block add_working_hours text-white" ><span data-feather="check-circle" class="icon-16" ></span> <?php echo app_lang('submit'); ?></a>


						<?php } ?>
					</div>

				</div>


				<?php echo form_close(); ?>
			</div>
		</div>
		<!-- init_tail -->
		
		<?php require 'plugins/Manufacturing/assets/js/settings/add_edit_working_hour_js.php';?>
		
	</body>
	</html>
