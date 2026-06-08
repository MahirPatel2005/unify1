<?php

$aColumns = [
	'id',
	'working_hour_name',
	'hours_per_day',
	'1',
];
$sIndexColumn = 'id';
$sTable = get_db_prefix() . 'mrp_working_hours';

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

		}elseif ($aColumns[$i] == 'working_hour_name') {
			$code = $aRow['working_hour_name'];
			$_data = $code;

		}elseif($aColumns[$i] == 'hours_per_day'){
			$_data =  $aRow['hours_per_day'];

		}elseif($aColumns[$i] == '1') {
			$_data ='';

			$edit = '';
			$delete = '';

			if(mrp_has_permission('manufacturing_can_edit')) {
				$edit .=	'<li role="presentation"><a href="'.site_url('manufacturing/add_edit_working_hour/'.$aRow['id']).'"  class="dropdown-item" data-toggle="sidebar-right" ><span data-feather="edit" class="icon-16"></span> ' . _l('edit') . '</a></li>';
			} 

			if(mrp_has_permission('manufacturing_can_delete')) {
				$delete .= '<li role="presentation">' .modal_anchor(get_uri("manufacturing/confirm_delete_modal_form"), "<i data-feather='x' class='icon-16'></i> " . app_lang('delete'), array("title" => app_lang('delete'). "?", "data-post-id" => $aRow['id'], "data-post-function" => 'delete_working_hour', "class" => 'dropdown-item' )). '</li>';

			} 

			$_data = '';
			if(strlen($edit) > 0 || strlen($delete) > 0){

				$_data = '
				<span class="dropdown inline-block">
				<button class="btn btn-default dropdown-toggle caret mt0 mb0" type="button" data-bs-toggle="dropdown" aria-expanded="true" data-bs-display="static">
				<i data-feather="tool" class="icon-16"></i>
				</button>
				<ul class="dropdown-menu dropdown-menu-end" role="menu">'.$edit . $delete . '</ul>
				</span>';
			}

		}

		$row[] = $_data;
	}

	$output['aaData'][] = $row;
}

