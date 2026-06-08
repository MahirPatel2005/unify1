<div id="page-content" class="page-wrapper clearfix">
	<div class="row">
		<div class="col-md-12">
			<div class="card">
				<div class="page-title clearfix">
					<h4 class="no-margin font-bold"><i class="fa fa-clone menu-icon menu-icon" aria-hidden="true"></i> <?php echo html_entity_decode($work_center->work_center_name); ?></h4>
				</div>

				<div class="card-body">
					<div class="row col-md-12">
						<div class="col-md-6 panel-padding">
							<table class="table border table-striped table-margintop">
								<tbody>
									<tr class="project-overview">
										<td class="bold" width="30%"><?php echo app_lang('work_center_code'); ?></td>
										<td><?php echo html_entity_decode($work_center->work_center_code) ; ?></td>
									</tr>
									<tr class="project-overview">
										<td class="bold"><?php echo app_lang('work_center_name'); ?></td>
										<td><?php echo html_entity_decode($work_center->work_center_name) ; ?></td>
									</tr>
									<tr class="project-overview">
										<td class="bold"><?php echo app_lang('work_center_working_hours'); ?></td>
										<?php 
										$working_hours_name = '';
										$Manufacturing_model = model("Manufacturing\Models\Manufacturing_model");

										if($work_center->working_hours != '' && $work_center->working_hours != null && $work_center->working_hours != 0){
											$working_hour = $Manufacturing_model->get_working_hour($work_center->working_hours);
											if($working_hour['working_hour']){
												$working_hours_name .= $working_hour['working_hour']->working_hour_name;
											}
										}

										?>
										<td><?php echo html_entity_decode($working_hours_name) ?></td>
									</tr>
								</tbody>
							</table>
						</div>

					</div>


					<h4 class="h4-color"><?php echo app_lang('work_center_info'); ?></h4>
					<hr class="hr-color">

					<div class="row">
						<div class="col-md-6 panel-padding" >
							<table class="table border table-striped table-margintop" >
								<tbody>
									<tr class="project-overview">
										<td class="bold td-width"><?php echo app_lang('time_efficiency'); ?></td>
										<td><?php echo html_entity_decode($work_center->time_efficiency) ; ?></td>
									</tr>
									<tr class="project-overview">
										<td class="bold"><?php echo app_lang('work_center_capacity'); ?></td>
										<td><?php echo html_entity_decode($work_center->capacity) ; ?></td>
									</tr>
									<tr class="project-overview">
										<td class="bold"><?php echo app_lang('work_center_time_start'); ?></td>
										<td><?php echo html_entity_decode($work_center->time_start)  ?></td>
									</tr>

								</tbody>
							</table>
						</div>

						<div class="col-md-6 panel-padding" >
							<table class="table table-striped table-margintop">
								<tbody>
									<tr class="project-overview">
										<td class="bold" width="40%"><?php echo app_lang('costs_hour'); ?></td>
										<td><?php echo html_entity_decode($work_center->costs_hour)  ?></td>
									</tr>
									<tr class="project-overview">
										<td class="bold"><?php echo app_lang('oee_target'); ?></td>
										<td><?php echo html_entity_decode($work_center->oee_target)  ?></td>
									</tr>

									<tr class="project-overview">
										<td class="bold"><?php echo app_lang('time_stop'); ?></td>
										<td><?php echo html_entity_decode($work_center->time_stop)  ?></td>
									</tr> 

								</tbody>
							</table>
						</div>
					</div>
					<div class=" row ">
						<div class="col-md-12">
							<h4 class="h4-color"><?php echo app_lang('work_center_description'); ?></h4>
							<hr class="hr-color">
							<h5><?php echo html_entity_decode($work_center->description) ; ?></h5>
						</div>
					</div>
				</div>

				<div class="modal-footer">
					<a href="<?php echo site_url('manufacturing/work_center_manage'); ?>"  class="btn btn-default mr-2 "><?php echo app_lang('hr_close'); ?></a>

					<?php if(mrp_has_permission('manufacturing_can_create') ){ ?>
						<a href="<?php echo site_url('manufacturing/add_edit_work_center'); ?>" class="btn btn-info pull-right display-block mright5 text-white"><span data-feather="plus-circle" class="icon-16"></span> <?php echo app_lang('add_work_center'); ?></a>
					<?php } ?>

					<?php if( mrp_has_permission('manufacturing_can_edit')){ ?>
						<a href="<?php echo site_url('manufacturing/add_edit_work_center/'.$work_center->id); ?>" class="btn btn-primary pull-right display-block mright5"><span data-feather="edit" class="icon-16"></span> <?php echo app_lang('edit_work_center'); ?></a>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</div>

</body>
</html>

