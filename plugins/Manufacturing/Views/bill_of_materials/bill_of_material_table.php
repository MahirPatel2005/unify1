<?php

$Manufacturing_model = model("Manufacturing\Models\Manufacturing_model");

$aColumns = [
	'id',
	'product_id',
	'bom_code',
	'bom_type',
	'product_variant_id',
	'product_qty',
	'unit_id',
	'routing_id',
	'1',
];
$sIndexColumn = 'id';
$sTable = get_db_prefix() . 'mrp_bill_of_materials';

$where = [];
$join= [];


if (isset($dataPost['products_filter'])) {
	$products_filter = $dataPost['products_filter'];

	$products = $Manufacturing_model->bom_get_product_filter($products_filter);

	$where_products_filter = '';
	foreach ($products as $product) {
		if ($where_products_filter == '') {

			if(isset($product['parent_id']) && $product['parent_id'] != 0){
				$where_products_filter .= "AND ( ( (product_id = ".$product['parent_id']." AND product_variant_id = ".$product['id'].") OR( product_id = ".$product['parent_id']." AND (product_variant_id = 0 OR product_variant_id is null))) "; 
			}else{
				$where_products_filter .= "AND ( product_id = ".$product['id'];
			}

		} else {
			if(isset($product['parent_id']) && $product['parent_id'] != 0){
				$where_products_filter .= " OR  ( (product_id = ".$product['parent_id']." AND product_variant_id = ".$product['id'].") OR( product_id = ".$product['parent_id']." AND (product_variant_id = 0 OR product_variant_id is null))) "; 
			}else{
				$where_products_filter .= " OR  product_id = ".$product['id'];
			}

		}
	}

	if ($where_products_filter != '') {
		$where_products_filter .= ')';

		array_push($where, $where_products_filter);
	}
}

if (isset($dataPost['bom_type_filter'])) {
	$bom_type_filter = $dataPost['bom_type_filter'];

	$where_bom_type_filter = '';
	foreach ($bom_type_filter as $bom_type) {
		if ($bom_type != '') {
			if ($where_bom_type_filter == '') {
				$where_bom_type_filter .= 'AND ('.db_prefix().'mrp_bill_of_materials.bom_type = "' . $bom_type . '"';
			} else {
				$where_bom_type_filter .= ' or '.db_prefix().'mrp_bill_of_materials.bom_type = "' . $bom_type . '"';
			}
		}
	}
	if ($where_bom_type_filter != '') {
		$where_bom_type_filter .= ')';
		array_push($where, $where_bom_type_filter);
	}
}

if (isset($dataPost['routing_filter'])) {
	$routing_filter = $dataPost['routing_filter'];

	$where_routing_filter = '';
	foreach ($routing_filter as $routing_id) {
		if ($routing_id != '') {
			if ($where_routing_filter == '') {
				$where_routing_filter .= 'AND ('.db_prefix().'mrp_bill_of_materials.routing_id = "' . $routing_id . '"';
			} else {
				$where_routing_filter .= ' or '.db_prefix().'mrp_bill_of_materials.routing_id = "' . $routing_id . '"';
			}
		}
	}
	if ($where_routing_filter != '') {
		$where_routing_filter .= ')';
		array_push($where, $where_routing_filter);
	}
}



$result = data_tables_init1($aColumns, $sIndexColumn, $sTable, $join, $where, ['id'], '', [], $dataPost);

$output = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
	$row = [];

	$row[] = '<div class="checkbox"><input type="checkbox" value="' . $aRow['id'] . '" class="form-check-input"><label></label></div>';

	for ($i = 0; $i < count($aColumns); $i++) {

		if($aColumns[$i] == 'id') {
			$_data = $aRow['id'];

		}elseif ($aColumns[$i] == 'product_id') {
			$_data = mrp_get_product_name($aRow['product_id']);
		}elseif($aColumns[$i] == 'bom_code'){
			$_data =  $aRow['bom_code'];
		}elseif($aColumns[$i] == 'bom_type'){
			$_data =  app_lang($aRow['bom_type']);

		}elseif($aColumns[$i] == 'product_variant_id'){

			$_data =  mrp_get_product_name($aRow['product_variant_id']);

		}elseif($aColumns[$i] == 'product_qty'){

			$_data =  to_decimal_format($aRow['product_qty']);

		}elseif($aColumns[$i] == 'unit_id'){

			$_data =  mrp_get_unit_name($aRow['unit_id']);

		}elseif($aColumns[$i] == 'routing_id'){

			$_data =  mrp_get_routing_name($aRow['routing_id']);

		}elseif($aColumns[$i] == '1'){
			$_data ='';

			$view = '';
			$edit = '';
			$delete = '';

			if(mrp_has_permission('manufacturing_can_view')) {
				$view .=	'<li role="presentation"><a href="'.site_url('manufacturing/bill_of_material_detail_manage/'.$aRow['id']).'"  class="dropdown-item" data-toggle="sidebar-right" ><span data-feather="eye" class="icon-16"></span> ' . _l('view') . '</a></li>';
			}

			if(mrp_has_permission('manufacturing_can_edit')) {
				$edit .=	'<li role="presentation"><a href="'.site_url('manufacturing/bill_of_material_detail_manage/'.$aRow['id']).'"  class="dropdown-item" data-toggle="sidebar-right" ><span data-feather="edit" class="icon-16"></span> ' . _l('edit') . '</a></li>';
			} 

			if(mrp_has_permission('manufacturing_can_delete')) {
				$delete .= '<li role="presentation">' .modal_anchor(get_uri("manufacturing/confirm_delete_modal_form"), "<i data-feather='x' class='icon-16'></i> " . app_lang('delete'), array("title" => app_lang('delete'). "?", "data-post-id" => $aRow['id'], "data-post-function" => 'delete_bill_of_material', "class" => 'dropdown-item' )). '</li>';

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

