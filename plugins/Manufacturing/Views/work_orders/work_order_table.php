<?php

$aColumns = [
	'id',
	'operation_name',
	'date_planned_start',
	'work_center_id',
	'manufacturing_order_id',
	'product_id',
	'qty_production',
	'unit_id',
	'status',
	'1',
];
$sIndexColumn = 'id';
$sTable = get_db_prefix() . 'mrp_work_orders';

$where = [];
$join= [];


if (isset($dataPost['manufacturing_order_filter'])) {
$manufacturing_order_filter = $dataPost['manufacturing_order_filter'];

	$where_manufacturing_order_ft = '';
	foreach ($manufacturing_order_filter as $manufacturing_order) {
		if ($manufacturing_order != '') {
			if ($where_manufacturing_order_ft == '') {
				$where_manufacturing_order_ft .= 'AND ('.get_db_prefix().'mrp_work_orders.manufacturing_order_id = "' . $manufacturing_order . '"';
			} else {
				$where_manufacturing_order_ft .= ' or '.get_db_prefix().'mrp_work_orders.manufacturing_order_id = "' . $manufacturing_order . '"';
			}
		}
	}
	if ($where_manufacturing_order_ft != '') {
		$where_manufacturing_order_ft .= ')';
		array_push($where, $where_manufacturing_order_ft);
	}
}

if (isset($dataPost['products_filter'])) {
$products_filter = $dataPost['products_filter'];

	$where_products_ft = '';
	foreach ($products_filter as $product_id) {
		if ($product_id != '') {
			if ($where_products_ft == '') {
				$where_products_ft .= 'AND ('.get_db_prefix().'mrp_work_orders.product_id = "' . $product_id . '"';
			} else {
				$where_products_ft .= ' or '.get_db_prefix().'mrp_work_orders.product_id = "' . $product_id . '"';
			}
		}
	}
	if ($where_products_ft != '') {
		$where_products_ft .= ')';
		array_push($where, $where_products_ft);
	}
}

if (isset($dataPost['status_filter'])) {
$status_filter = $dataPost['status_filter'];

	$where_status_ft = '';
	foreach ($status_filter as $status) {
		if ($status != '') {
			if ($where_status_ft == '') {
				$where_status_ft .= 'AND ('.get_db_prefix().'mrp_work_orders.status = "' . $status . '"';
			} else {
				$where_status_ft .= ' or '.get_db_prefix().'mrp_work_orders.status = "' . $status . '"';
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

	for ($i = 0; $i < count($aColumns); $i++) {

		if($aColumns[$i] == 'id') {
			$_data = $aRow['id'];

		}elseif ($aColumns[$i] == 'operation_name') {

			$_data = $aRow['operation_name'];


		}elseif($aColumns[$i] == 'date_planned_start'){
			$_data = format_to_date($aRow['date_planned_start'], false);
		}elseif($aColumns[$i] == 'work_center_id'){
			$_data =  get_work_center_name($aRow['work_center_id']);

		}elseif($aColumns[$i] == 'manufacturing_order_id'){

			$_data =  mrp_get_manufacturing_code($aRow['manufacturing_order_id']);

		}elseif($aColumns[$i] == 'product_id'){

			$_data =  mrp_get_product_name($aRow['product_id']);

		}elseif($aColumns[$i] == 'qty_production'){
			$_data =  to_decimal_format($aRow['qty_production']);

		}elseif($aColumns[$i] == 'unit_id'){

			$_data =  mrp_get_unit_name($aRow['unit_id']);

		}elseif($aColumns[$i] == 'status'){

			$_data = ' <span class="badge label-'.$aRow['status'].' mt-0" > '.app_lang($aRow['status']).' </span>';

		}elseif($aColumns[$i] == '1'){
			$_data ='';

			$view = '';
			$edit = '';
			$delete = '';

			if(mrp_has_permission('manufacturing_can_view')) {
				$view .=	'<li role="presentation"><a href="'.site_url('manufacturing/view_work_order/'.$aRow['id']).'/'.$aRow['manufacturing_order_id'].'"  class="dropdown-item" data-toggle="sidebar-right" ><span data-feather="eye" class="icon-16"></span> ' . _l('view') . '</a></li>';
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

