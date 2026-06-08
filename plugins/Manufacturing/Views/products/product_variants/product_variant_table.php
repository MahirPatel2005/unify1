<?php

$Warehouse_model = model("Warehouse\Models\Warehouse_model");
$Manufacturing_model = model("Manufacturing\Models\Manufacturing_model");
$list_product_type = mrp_product_type();

$aColumns = [
	'1',
	get_db_prefix() . 'items.id',
	get_db_prefix() . 'items.title',
	'commodity_barcode',
	'rate',
	'purchase_price',
	'category_id', //product category
	'product_type', //product type
	'2', // inventory qty 
	'unit_id',
	'3',
];
$sIndexColumn = 'id';
$sTable = get_db_prefix() . 'items';

$where = [];



$join= [];

$where[] = 'AND '.db_prefix().'items.deleted = 0 AND '.db_prefix().'items.parent_id is not null AND  '.db_prefix().'items.parent_id != 0 AND  '.db_prefix().'items.attributes is not null ';

if (isset($dataPost['item_filter'])) {
	$commodity_ft = $dataPost['item_filter'];

	$where_commodity_ft = '';
	foreach ($commodity_ft as $commodity_id) {
		if ($commodity_id != '') {
			if ($where_commodity_ft == '') {
				$where_commodity_ft .= ' AND ('.db_prefix().'items.id = "' . $commodity_id . '"';
			} else {
				$where_commodity_ft .= ' or '.db_prefix().'items.id = "' . $commodity_id . '"';
			}
		}
	}
	if ($where_commodity_ft != '') {
		$where_commodity_ft .= ')';
		array_push($where, $where_commodity_ft);
	}
}

if (isset($dataPost['product_type_filter'])) {
	$product_type_ft = $dataPost['product_type_filter'];

	$where_product_type_ft = '';
	foreach ($product_type_ft as $product_type) {
		if ($product_type != '') {
			if ($where_product_type_ft == '') {
				$where_product_type_ft .= ' AND ('.db_prefix().'items.product_type = "' . $product_type . '"';
			} else {
				$where_product_type_ft .= ' or '.db_prefix().'items.product_type = "' . $product_type . '"';
			}
		}
	}
	if ($where_product_type_ft != '') {
		$where_product_type_ft .= ')';
		array_push($where, $where_product_type_ft);
	}
}

if (isset($dataPost['product_category_filter'])) {
	$product_category_ft = $dataPost['product_category_filter'];

	$where_product_category_ft = '';
	foreach ($product_category_ft as $product_category) {
		if ($product_category != '') {
			if ($where_product_category_ft == '') {
				$where_product_category_ft .= ' AND ('.db_prefix().'items.category_id = "' . $product_category . '"';
			} else {
				$where_product_category_ft .= ' or '.db_prefix().'items.category_id = "' . $product_category . '"';
			}
		}
	}
	if ($where_product_category_ft != '') {
		$where_product_category_ft .= ')';
		array_push($where, $where_product_category_ft);
	}
}


$result = data_tables_init1($aColumns, $sIndexColumn, $sTable, $join, $where, [db_prefix().'items.id', 'files', 'commodity_code'], '', [], $dataPost);

$output = $result['output'];
$rResult = $result['rResult'];


foreach ($rResult as $aRow) {
	$row = [];
	for ($i = 0; $i < count($aColumns); $i++) {

		if (strpos($aColumns[$i], 'as') !== false && !isset($aRow[$aColumns[$i]])) {
			$_data = $aRow[strafter($aColumns[$i], 'as ')];
		} else {
			$_data = $aRow[$aColumns[$i]];
		}


		/*get commodity file*/
		if($aColumns[$i] == db_prefix() . 'items.id'){
			if ($aRow['files']){

				$files = unserialize($aRow['files']);

				if (count($files)) {

					$timeline_file_path = get_setting("timeline_file_path");
					foreach ($files as $file_key => $file) {
						if($file_key == 0){
							$file_name = get_array_value($file, "file_name");
							$thumbnail = get_source_url_of_file($file, $timeline_file_path, "thumbnail");
							if (is_viewable_image_file($file_name)) {
								$_data = "<img class='sortable-file images_w_table' src='".$thumbnail."' alt='".$file_name."'/>";

							} else {
								$_data = get_file_icon(strtolower(pathinfo($file_name, PATHINFO_EXTENSION)));
							}
						}
					}

				}else{
					$thumbnail = get_file_uri('plugins/Warehouse/Uploads/nul_image.jpg');
					$_data = "<img class='sortable-file images_w_table' src='".$thumbnail."' alt='null_image'/>";
				}
			}else{
				$thumbnail = get_file_uri('plugins/Warehouse/Uploads/nul_image.jpg');
				$_data = "<img class='sortable-file images_w_table' src='".$thumbnail."' alt='null_image'/>";
			}
		}

		if ($aColumns[$i] == get_db_prefix() . 'items.title') {

			$_data = $aRow['commodity_code'].' '.$aRow[get_db_prefix() . 'items.title'];

		}elseif($aColumns[$i] == '1'){
			$_data = '<div class="checkbox"><input type="checkbox" value="' . $aRow['id'] . '" class="form-check-input"><label></label></div>';
		}elseif ($aColumns[$i] == 'unit_id') {
			if ($aRow['unit_id'] != null) {
				$_data = mrp_get_unit_name($aRow['unit_id']);
			} else {
				$_data = '';
			}
		} elseif ($aColumns[$i] == 'rate') {
			$_data = to_decimal_format((float) $aRow['rate']);
		} elseif ($aColumns[$i] == 'purchase_price') {
			$_data = to_decimal_format((float) $aRow['purchase_price']);

		} elseif ($aColumns[$i] == 'category_id') {
			$_data = get_wh_group_name($aRow['category_id']) != null ? get_wh_group_name($aRow['category_id'])->title : '';

		} elseif ($aColumns[$i] == 'product_type') {

			$product_type_name ='';

			if($aRow['product_type'] !== null){

				foreach ($list_product_type as $value) {
					if($value['name'] == $aRow['product_type']){
						$product_type_name .= $value['label'];
					}
				}

			}
			$_data = $product_type_name;

		} elseif ($aColumns[$i] == '2') {
			$_data ='';
			$arr_warehouse = get_warehouse_by_commodity($aRow['id']);

			$str = '';
			if(count($arr_warehouse) > 0){
				foreach ($arr_warehouse as $wh_key => $warehouseid) {
					$str = '';
					if ($warehouseid['warehouse_id'] != '' && $warehouseid['warehouse_id'] != '0') {
							//get inventory quantity
						$inventory_quantity = $Warehouse_model->get_quantity_inventory($warehouseid['warehouse_id'], $aRow['id']);
						$quantity_by_warehouse =0;
						if($inventory_quantity){
							$quantity_by_warehouse = $inventory_quantity->inventory_number;
						}

						$team = get_warehouse_name($warehouseid['warehouse_id']);
						if($team){
							$value = $team != null ? get_object_vars($team)['warehouse_name'] : '';
							$str .= '<span class="badge bg-success large mt-0">' . $value . ': ( '.$quantity_by_warehouse.' )</span>';
							
							$_data .= $str;
							if($wh_key%3 ==0){
								$_data .='<br/>';
							}
						}

					}
				}

			} else {
				$_data = '';
			}

		}elseif($aColumns[$i] == '3'){
			$_data ='';

			$view = '';
			$edit = '';
			$delete = '';

			if(mrp_has_permission('manufacturing_can_view')) {
				$view .=	'<li role="presentation"><a href="'.site_url('manufacturing/view_product_detail/'.$aRow['id']).'"  class="dropdown-item" data-toggle="sidebar-right" ><span data-feather="eye" class="icon-16"></span> ' . _l('view') . '</a></li>';
			}

			if(mrp_has_permission('manufacturing_can_edit')) {
				$edit .=	'<li role="presentation"><a href="'.site_url('manufacturing/add_edit_product/product_variant/'.$aRow['id']).'"  class="dropdown-item" data-toggle="sidebar-right" ><span data-feather="edit" class="icon-16"></span> ' . _l('edit') . '</a></li>';
			} 

			if(mrp_has_permission('manufacturing_can_delete')) {
				$delete .= '<li role="presentation">' .modal_anchor(get_uri("manufacturing/confirm_delete_modal_form"), "<i data-feather='x' class='icon-16'></i> " . app_lang('delete'), array("title" => app_lang('delete'). "?", "data-post-id" => $aRow['id'],"data-post-id2" => 'product_variant', "data-post-function" => 'delete_product', "class" => 'dropdown-item' )). '</li>';

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

