<?php


$aColumns = [
	'unit_type_id',
	'unit_name',
	'category_id',
	'unit_measure_type',
	'1',
];
$sIndexColumn = 'unit_type_id';
$sTable = get_db_prefix() . 'ware_unit_type';

$where = [];
$join= [];


$result = data_tables_init1($aColumns, $sIndexColumn, $sTable, $join, $where, ['unit_type_id'], '', [], $dataPost);

$output = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
	$row = [];

	for ($i = 0; $i < count($aColumns); $i++) {

		if($aColumns[$i] == 'unit_type_id') {
			$_data = $aRow['unit_type_id'];

		}elseif ($aColumns[$i] == 'unit_name') {
			$code = $aRow['unit_name'];
			$_data = $code;

		}elseif($aColumns[$i] == 'category_id'){
			$_data =  get_category_name($aRow['category_id']);

		}elseif($aColumns[$i] == 'unit_measure_type'){
			$type ='';
			switch ($aRow['unit_measure_type']) {
				case 'bigger':
					$type .= app_lang('bigger_than_the_reference_Unit_of_Measure');
					break;

				case 'reference':
					$type .= app_lang('reference_Unit_of_Measure_for_this_category');
					break;

				case 'smaller':
					$type .= app_lang('smaller_than_the_reference_Unit_of_Measure');
					break;
			}

			$_data = $type;

		}elseif($aColumns[$i] == '1') {
			
			$_data ='';

			$edit = '';
			$delete = '';

			if(mrp_has_permission('manufacturing_can_edit')) {
				$edit .=	'<li role="presentation"><a href="#" onclick="add_edit_unit_measure('. $aRow['unit_type_id'] .',\'updated\'); return false;"  class="dropdown-item" data-toggle="sidebar-right" ><span data-feather="edit" class="icon-16"></span> ' . _l('edit') . '</a></li>';
			} 

			if(mrp_has_permission('manufacturing_can_delete')) {
				$delete .= '<li role="presentation">' .modal_anchor(get_uri("manufacturing/confirm_delete_modal_form"), "<i data-feather='x' class='icon-16'></i> " . app_lang('delete'), array("title" => app_lang('delete'). "?", "data-post-id" => $aRow['unit_type_id'], "data-post-function" => 'delete_unit_of_measure', "class" => 'dropdown-item' )). '</li>';

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

