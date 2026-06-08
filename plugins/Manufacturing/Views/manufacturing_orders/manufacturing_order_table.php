<?php


$aColumns = [
	'id',
	'manufacturing_order_code',
	'product_id',
	'bom_id',
	'product_qty',
	'unit_id',
	'routing_id',
	'status',
	'1',

];
$sIndexColumn = 'id';
$sTable = get_db_prefix() . 'mrp_manufacturing_orders';

$where = [];
$join= [];


if (isset($dataPost['products_filter'])) {
$products_filter = $dataPost['products_filter'];

	$where_products_ft = '';
	foreach ($products_filter as $product_id) {
		if ($product_id != '') {
			if ($where_products_ft == '') {
				$where_products_ft .= 'AND (get_'.db_prefix().'mrp_manufacturing_orders.product_id = "' . $product_id . '"';
			} else {
				$where_products_ft .= ' or '.get_db_prefix().'mrp_manufacturing_orders.product_id = "' . $product_id . '"';
			}
		}
	}
	if ($where_products_ft != '') {
		$where_products_ft .= ')';
		array_push($where, $where_products_ft);
	}
}

if (isset($dataPost['routing_filter'])) {
$routing_filter = $dataPost['routing_filter'];

	$where_routing_ft = '';
	foreach ($routing_filter as $routing_id) {
		if ($routing_id != '') {
			if ($where_routing_ft == '') {
				$where_routing_ft .= 'AND ('.get_db_prefix().'mrp_manufacturing_orders.routing_id = "' . $routing_id . '"';
			} else {
				$where_routing_ft .= ' or '.get_db_prefix().'mrp_manufacturing_orders.routing_id = "' . $routing_id . '"';
			}
		}
	}
	if ($where_routing_ft != '') {
		$where_routing_ft .= ')';
		array_push($where, $where_routing_ft);
	}
}

if (isset( $dataPost['status_filter'])) {
$status_filter = $dataPost['status_filter'];

	$where_status_ft = '';
	foreach ($status_filter as $status) {
		if ($status != '') {
			if ($where_status_ft == '') {
				$where_status_ft .= 'AND ('.get_db_prefix().'mrp_manufacturing_orders.status = "' . $status . '"';
			} else {
				$where_status_ft .= ' or '.get_db_prefix().'mrp_manufacturing_orders.status = "' . $status . '"';
			}
		}
	}
	if ($where_status_ft != '') {
		$where_status_ft .= ')';
		array_push($where, $where_status_ft);
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

		}elseif ($aColumns[$i] == 'manufacturing_order_code') {
			$_data = $aRow['manufacturing_order_code'];


		}elseif($aColumns[$i] == 'product_id'){
			$_data =  mrp_get_product_name($aRow['product_id']);

		}elseif($aColumns[$i] == 'bom_id'){

			$_data =  mrp_get_bill_of_material_code($aRow['bom_id']).' '.mrp_get_product_name(mrp_get_bill_of_material($aRow['bom_id']));

		}elseif($aColumns[$i] == 'product_qty'){

			$_data =  to_decimal_format($aRow['product_qty']);

		}elseif($aColumns[$i] == 'unit_id'){

			$_data =  mrp_get_unit_name($aRow['unit_id']);

		}elseif($aColumns[$i] == 'routing_id'){

			$_data =  mrp_get_routing_name($aRow['routing_id']);

		}elseif($aColumns[$i] == 'status'){

			$_data = ' <span class="badge label-'.$aRow['status'].' mt-0" > '.app_lang($aRow['status']).' </span>';


		}elseif($aColumns[$i] == '1'){
			$_data ='';

			$view = '';
			$edit = '';
			$delete = '';

			if(mrp_has_permission('manufacturing_can_view')) {
				$view .=	'<li role="presentation"><a href="'.site_url('manufacturing/view_manufacturing_order/'.$aRow['id']).'"  class="dropdown-item" data-toggle="sidebar-right" ><span data-feather="eye" class="icon-16"></span> ' . _l('view') . '</a></li>';
			}

			if(mrp_has_permission('manufacturing_can_edit')) {
				$edit .=	'<li role="presentation"><a href="'.site_url('manufacturing/add_edit_manufacturing_order/'.$aRow['id']).'"  class="dropdown-item" data-toggle="sidebar-right" ><span data-feather="edit" class="icon-16"></span> ' . _l('edit') . '</a></li>';
			} 

			if(mrp_has_permission('manufacturing_can_delete')) {
				$delete .= '<li role="presentation">' .modal_anchor(get_uri("manufacturing/confirm_delete_modal_form"), "<i data-feather='x' class='icon-16'></i> " . app_lang('delete'), array("title" => app_lang('delete'). "?", "data-post-id" => $aRow['id'],"data-post-id2" => 'product', "data-post-function" => 'delete_manufacturing_order', "class" => 'dropdown-item' )). '</li>';

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

