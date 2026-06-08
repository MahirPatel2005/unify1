<?php


$aColumns = [
	get_db_prefix().'mrp_bill_of_material_details.id as id',
	get_db_prefix().'mrp_bill_of_material_details.display_order as display_order',
	'product_id',
	'product_qty',
	get_db_prefix().'mrp_bill_of_material_details.unit_id as unit_id',
	'apply_on_variants',
	'operation_id',
	'1',
];
$sIndexColumn = 'id';
$sTable = get_db_prefix() . 'mrp_bill_of_material_details';

$where = [];

$join = [
	'LEFT JOIN ' . get_db_prefix() . 'items ON '.get_db_prefix().'items.id = ' . get_db_prefix() . 'mrp_bill_of_material_details.product_id',
	'LEFT JOIN ' . get_db_prefix() . 'ware_unit_type ON '.get_db_prefix().'ware_unit_type.unit_type_id = ' . get_db_prefix() . 'mrp_bill_of_material_details.unit_id',
	'LEFT JOIN ' . get_db_prefix() . 'mrp_routing_details ON ' . get_db_prefix() . 'mrp_routing_details.routing_id = ' . get_db_prefix() . 'mrp_bill_of_material_details.operation_id'
];


$bill_of_material_id = $dataPost['bill_of_material_id'];
$bill_of_material_product_id = $dataPost['bill_of_material_product_id'];
$bill_of_material_routing_id = $dataPost['bill_of_material_routing_id'];

if(isset($dataPost['bill_of_material_id'])){
	$bill_of_material_id = $dataPost['bill_of_material_id'];
	$where_bill_of_material_id = '';
	if($bill_of_material_id != '')
	{
		if($where_bill_of_material_id == ''){
			$where_bill_of_material_id .= 'AND bill_of_material_id = "'.$bill_of_material_id. '"';
		}else{
			$where_bill_of_material_id .= ' or bill_of_material_id = "' .$bill_of_material_id.'"';
		}
	}
	if($where_bill_of_material_id != '')
	{
		array_push($where, $where_bill_of_material_id);
	}
}

$result = data_tables_init1($aColumns, $sIndexColumn, $sTable, $join, $where, [get_db_prefix().'mrp_bill_of_material_details.id','bill_of_material_id','product_id','product_qty',get_db_prefix().'mrp_bill_of_material_details.unit_id','apply_on_variants','operation_id', get_db_prefix().'mrp_bill_of_material_details.display_order', get_db_prefix().'items.description', get_db_prefix().'ware_unit_type.unit_name', get_db_prefix().'mrp_routing_details.operation' ], '', [], $dataPost);

$output = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
	$row = [];
	$row[] = '<div class="checkbox"><input type="checkbox" value="' . $aRow['id'] . '" class="form-check-input"><label></label></div>';

	for ($i = 0; $i < count($aColumns); $i++) {

		if($aColumns[$i] == get_db_prefix().'mrp_bill_of_material_details.id as id') {
			$_data = $aRow['id'];

		}elseif($aColumns[$i] == get_db_prefix().'mrp_bill_of_material_details.display_order as display_order'){
			$_data = round($aRow['display_order'],0);

		}elseif ($aColumns[$i] == 'product_id') {

			$code = mrp_get_product_name($aRow['product_id']) ;
			$_data = $code;


		}elseif($aColumns[$i] == 'product_qty'){
			$_data =  $aRow['product_qty'];

		}elseif($aColumns[$i] == get_db_prefix().'mrp_bill_of_material_details.unit_id as unit_id'){
			$_data = mrp_get_unit_name($aRow['unit_id']);

		}elseif($aColumns[$i] == 'apply_on_variants'){
			
			$_data =  $aRow['apply_on_variants'];

		}elseif($aColumns[$i] == 'operation_id'){
			$_data =  mrp_get_routing_detail_name($aRow['operation_id']);

		}elseif($aColumns[$i] == '1'){
			$_data ='';

			$view = '';
			$edit = '';
			$delete = '';

			
			if(mrp_has_permission('manufacturing_can_edit')) {
				$edit .=	'<li role="presentation"><a href="#" onclick="add_component('. $bill_of_material_id .','. $aRow['id'] .','. $bill_of_material_product_id.','. $bill_of_material_routing_id .',\'updated\'); return false;"  class="dropdown-item" data-toggle="sidebar-right" ><span data-feather="edit" class="icon-16"></span> ' . _l('edit') . '</a></li>';
			} 

			if(mrp_has_permission('manufacturing_can_delete')) {
				$delete .= '<li role="presentation">' .modal_anchor(get_uri("manufacturing/confirm_delete_modal_form"), "<i data-feather='x' class='icon-16'></i> " . app_lang('delete'), array("title" => app_lang('delete'). "?", "data-post-id" => $aRow['id'], "data-post-id2" => $bill_of_material_id, "data-post-function" => 'delete_bill_of_material_detail', "class" => 'dropdown-item' )). '</li>';

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

