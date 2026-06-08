<?php


$aColumns = [
	'id',
	'routing_code',
	'routing_name',
	'description',
	'1',
];
$sIndexColumn = 'id';
$sTable = get_db_prefix() . 'mrp_routings';

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

		}elseif ($aColumns[$i] == 'routing_code') {
			$_data = $aRow['routing_code'];
		}elseif($aColumns[$i] == 'routing_name'){
			$_data =  $aRow['routing_name'];

		}elseif($aColumns[$i] == 'description'){
			/*get frist 400 character */

			if(strlen($aRow['description']) > 400){
				$pos=strpos($aRow['description'], ' ', 400);
				$description_sub = substr($aRow['description'],0,$pos ); 
			}else{
				$description_sub = $aRow['description'];
			}

			$_data =   $description_sub;

		}elseif($aColumns[$i] == '1'){
			$_data ='';

			$view = '';
			$edit = '';
			$delete = '';

			if(mrp_has_permission('manufacturing_can_view')) {
				$view .=	'<li role="presentation"><a href="'.site_url('manufacturing/operation_manage/'.$aRow['id']).'"  class="dropdown-item" data-toggle="sidebar-right" ><span data-feather="eye" class="icon-16"></span> ' . _l('view') . '</a></li>';
			}

			if(mrp_has_permission('manufacturing_can_edit')) {
				$edit .=	'<li role="presentation"><a href="'.site_url('manufacturing/operation_manage/'.$aRow['id']).'"  class="dropdown-item" data-toggle="sidebar-right" ><span data-feather="edit" class="icon-16"></span> ' . _l('edit') . '</a></li>';
			} 

			if(mrp_has_permission('manufacturing_can_delete')) {
				$delete .= '<li role="presentation">' .modal_anchor(get_uri("manufacturing/confirm_delete_modal_form"), "<i data-feather='x' class='icon-16'></i> " . app_lang('delete'), array("title" => app_lang('delete'). "?", "data-post-id" => $aRow['id'], "data-post-function" => 'delete_routing', "class" => 'dropdown-item' )). '</li>';

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

