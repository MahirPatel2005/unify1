<?php
$Manufacturing_model = model("Manufacturing\Models\Manufacturing_model");

$aColumns = [
	'id',
	'work_center_code',
	'work_center_name',
	'working_hours',
	'1',
];
$sIndexColumn = 'id';
$sTable = get_db_prefix() . 'mrp_work_centers';

$where = [];
$join= [];


$result = data_tables_init1($aColumns, $sIndexColumn, $sTable, $join, $where, ['id'], '', [], $dataPost);

$output = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
	$row = [];

	for ($i = 0; $i < count($aColumns); $i++) {

		if($aColumns[$i] == 'id') {
			$_data = $aRow['id'];

		}elseif ($aColumns[$i] == 'work_center_code') {

			$_data = $aRow['work_center_code'];


		}elseif($aColumns[$i] == 'work_center_name'){
			$_data =  $aRow['work_center_name'];

		}elseif($aColumns[$i] == 'working_hours'){
			$working_hours_name = '';

			if($aRow['working_hours'] != '' && $aRow['working_hours'] != null && $aRow['working_hours'] != 0){
				$working_hour = $Manufacturing_model->get_working_hour($aRow['working_hours']);
				if($working_hour['working_hour']){
					$working_hours_name .= $working_hour['working_hour']->working_hour_name;
				}
			}

			$_data =  $working_hours_name;

		} elseif($aColumns[$i] == '1'){
			$_data ='';

			$view = '';
			$edit = '';
			$delete = '';

			if(mrp_has_permission('manufacturing_can_view')) {
				$view .=	'<li role="presentation"><a href="'.site_url('manufacturing/view_work_center/'.$aRow['id']).'"  class="dropdown-item" data-toggle="sidebar-right" ><span data-feather="eye" class="icon-16"></span> ' . _l('view') . '</a></li>';
			}

			if(mrp_has_permission('manufacturing_can_edit')) {
				$edit .=	'<li role="presentation"><a href="'.site_url('manufacturing/add_edit_work_center/'.$aRow['id']).'"  class="dropdown-item" data-toggle="sidebar-right" ><span data-feather="edit" class="icon-16"></span> ' . _l('edit') . '</a></li>';
			} 

			if(mrp_has_permission('manufacturing_can_delete')) {
				$delete .= '<li role="presentation">' .modal_anchor(get_uri("manufacturing/confirm_delete_modal_form"), "<i data-feather='x' class='icon-16'></i> " . app_lang('delete'), array("title" => app_lang('delete'). "?", "data-post-id" => $aRow['id'], "data-post-function" => 'delete_work_center', "class" => 'dropdown-item' )). '</li>';

			} 

			$_data = '';
			if(strlen($view) > 0 || strlen($edit) > 0 || strlen($delete) > 0){

				$_data = '
				<span class="dropdown inline-block">
				<button class="btn btn-default dropdown-toggle caret mt0 mb0" type="button" data-bs-toggle="dropdown" aria-expanded="true" data-bs-display="static">
				<i data-feather="tool" class="icon-16"></i>
				</button>
				<ul class="dropdown-menu dropdown-menu-end" role="menu">'. $view .$edit . $delete . '</ul>
				</span>';
			}
		}


		$row[] = $_data;
	}

	$output['aaData'][] = $row;
}

