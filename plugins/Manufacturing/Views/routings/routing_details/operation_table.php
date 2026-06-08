<?php

$aColumns = [
	'id',
	'display_order',
	'operation',
	'work_center_id',
	'duration_computation',
	'1',
];
$sIndexColumn = 'id';
$sTable = get_db_prefix() . 'mrp_routing_details';

$where = [];
$join= [];

$routing_id = $dataPost['routing_id'];
if(isset($dataPost['routing_id'])){
	$where_routing_id = '';
	$routing_id = $dataPost['routing_id'];
	if($routing_id != '')
	{
		if($where_routing_id == ''){
			$where_routing_id .= 'AND routing_id = "'.$routing_id. '"';
		}else{
			$where_routing_id .= ' or routing_id = "' .$routing_id.'"';
		}
	}
	if($where_routing_id != '')
	{
		array_push($where, $where_routing_id);
	}
}

$result = data_tables_init1($aColumns, $sIndexColumn, $sTable, $join, $where, ['id', 'based_on', 'default_duration'], '', [], $dataPost);

$output = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
	$row = [];

	for ($i = 0; $i < count($aColumns); $i++) {

		if($aColumns[$i] == 'id') {
			$_data = $aRow['id'];

		}elseif($aColumns[$i] == 'display_order'){
			$_data = round($aRow['display_order'],0);

		}elseif ($aColumns[$i] == 'operation') {

			$code = $aRow['operation'] ;
			$_data = $code;


		}elseif($aColumns[$i] == 'work_center_id'){
			$_data =  get_work_center_name($aRow['work_center_id']);

		}elseif($aColumns[$i] == 'duration_computation'){
			if($aRow['duration_computation'] == 'set_duration_manually'){
				$_data =  round($aRow['default_duration'],0);
			}else{
				$_data =  round($aRow['based_on'],0);
			}

		}elseif($aColumns[$i] == '1'){
			$_data ='';

			$view = '';
			$edit = '';
			$delete = '';

			
			if(mrp_has_permission('manufacturing_can_edit')) {
				$edit .=	'<li role="presentation"><a href="#" onclick="add_operation('. $routing_id .','. $aRow['id'] .',\'updated\'); return false;"  class="dropdown-item" data-toggle="sidebar-right" ><span data-feather="edit" class="icon-16"></span> ' . _l('edit') . '</a></li>';
			} 

			if(mrp_has_permission('manufacturing_can_delete')) {
				$delete .= '<li role="presentation">' .modal_anchor(get_uri("manufacturing/confirm_delete_modal_form"), "<i data-feather='x' class='icon-16'></i> " . app_lang('delete'), array("title" => app_lang('delete'). "?", "data-post-id" => $aRow['id'], "data-post-id2" => $routing_id, "data-post-function" => 'delete_operation', "class" => 'dropdown-item' )). '</li>';

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

