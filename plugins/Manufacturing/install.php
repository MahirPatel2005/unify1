<?php


/**
 * Add setting
 *
 * @since  Version 1.0.0
 *
 * @param string  $name      Option name (required|unique)
 * @param string  $value     Option value
 *
 */

if (!function_exists('add_setting')) {

	function add_setting($name, $value = '')
	{
		if (!setting_exists($name)) {
			$db = db_connect('default');
			$db_builder = $db->table(get_db_prefix() . 'settings');
			$newData = [
				'setting_name'  => $name,
				'setting_value' => $value,
			];

			$db_builder->insert($newData);

			$insert_id = $db->insertID();

			if ($insert_id) {
				return true;
			}

			return false;
		}

		return false;
	}
}

/**
 * @since  1.0.0
 * Check whether an setting exists
 *
 * @param  string $name setting name
 *
 * @return boolean
 */
if (!function_exists('setting_exists')) {

	function setting_exists($name)
	{ 
		
		$db = db_connect('default');
		$db_builder = $db->table(get_db_prefix() . 'settings');

		$count = $db_builder->where('setting_name', $name)->countAllResults();

		return $count > 0;
	}
}


$this_is_required = true;
if (!$this_is_required) {
	echo json_encode(array("success" => false, "message" => "This is required!"));
	exit();
}

//run installation sql
$db = db_connect('default');
$dbprefix = get_db_prefix();


if (!$db->tableExists($dbprefix . "mrp_work_centers")) {
	$db->query("CREATE TABLE `" . $dbprefix . "mrp_work_centers` (
		`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

		`work_center_name` varchar(200) NULL,
		`work_center_code` varchar(200) NULL,
		`working_hours` varchar(200) NULL,
		`time_efficiency` DECIMAL(15,2)  DEFAULT '0',
		`capacity` DECIMAL(15,2)  DEFAULT '0',
		`oee_target` DECIMAL(15,2)  DEFAULT '0',
		`time_start` DECIMAL(15,2)  DEFAULT '0',
		`time_stop` DECIMAL(15,2)  DEFAULT '0',
		`costs_hour` DECIMAL(15,2)  DEFAULT '0',
		`description` TEXT DEFAULT NULL,

		PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");
}

if (!$db->tableExists($dbprefix . "mrp_routings")) {
	$db->query("CREATE TABLE `" . $dbprefix . "mrp_routings` (
		`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

		`routing_code` varchar(200) NULL,
		`routing_name` varchar(200) NULL,
		`description` TEXT DEFAULT NULL,

		PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");
}


//get work sheet file with rel type mrp_work_sheet
if (!$db->tableExists($dbprefix . "mrp_routing_details")) {
	$db->query("CREATE TABLE `" . $dbprefix . "mrp_routing_details` (
		`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
		`routing_id` int(11) NOT NULL ,
		`operation` TEXT NULL ,
		`work_center_id` INT(11) NULL ,
		`duration_computation` TEXT NULL ,
		`based_on` DECIMAL(15,2) DEFAULT '0',
		`default_duration` DECIMAL(15,2) DEFAULT '0',

		`start_next_operation` TEXT NULL ,
		`quantity_process` DECIMAL(15,2) DEFAULT '0',

		`description` TEXT DEFAULT NULL,
		`display_order` DECIMAL(15,2) DEFAULT '0',

		PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");
}


if (!$db->tableExists($dbprefix . "mrp_working_hours")) {
	$db->query("CREATE TABLE `" . $dbprefix . "mrp_working_hours` (
		`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
		`working_hour_name` TEXT NULL ,
		`hours_per_day` DECIMAL(15,2) DEFAULT '0',

		PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");
}

if (!$db->tableExists($dbprefix . "mrp_working_hour_times")) {
	$db->query("CREATE TABLE `" . $dbprefix . "mrp_working_hour_times` (
		`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
		`working_hour_id` int(11) NOT NULL ,
		`working_hour_name` TEXT NULL ,
		`day_of_week` VARCHAR(100) NULL,
		`day_period` VARCHAR(100) NULL,
		`work_from` TIME NULL,
		`work_to` TIME NULL,
		`starting_date` DATE NULL,
		`end_date` DATE NULL,

		PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");
}

if (!$db->tableExists($dbprefix . "mrp_working_hour_time_off")) {
	$db->query("CREATE TABLE `" . $dbprefix . "mrp_working_hour_time_off` (
		`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
		`working_hour_id` int(11) NOT NULL ,
		`reason` TEXT NULL ,
		`starting_date` DATE NULL,
		`end_date` DATE NULL,

		PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");
}


/**
 * create Unit category
 * Note: this module use table ware_unit_type with Inventory module, and new column
 */

if (!$db->tableExists($dbprefix . "mrp_unit_measure_categories")) {
	$db->query("CREATE TABLE `" . $dbprefix . "mrp_unit_measure_categories` (
		`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

		`category_name` TEXT NOT NULL ,

		PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");
}

/**
 * unit name => Unit of measure
 * display => Active 
 *
 * when create unit type: take "unit code" and "unit symbol" = unit name remove "space", order = 1
 */
if (!$db->tableExists($dbprefix . "ware_unit_type")) {
	$db->query("CREATE TABLE `" . $dbprefix . "ware_unit_type` (
		`unit_type_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
		`unit_code` varchar(100) NULL,
		`unit_name` text NULL,
		`unit_symbol` text NULL,
		`order` int(10) NULL,
		`display` int(1) NULL COMMENT  'display 1: display (yes)  0: not displayed (no)',
		`note` text NULL,
		PRIMARY KEY (`unit_type_id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");
}

//add this script if itegration with Inventory module
if (!$db->fieldExists('category_id' , $dbprefix . 'ware_unit_type')) { 
	$db->query('ALTER TABLE `' . $dbprefix . "ware_unit_type`
		ADD COLUMN `category_id` int(11) NULL ,
		ADD COLUMN `unit_measure_type` VARCHAR(100) DEFAULT 'reference' ,
		ADD COLUMN `bigger_ratio` DECIMAL(15,5) DEFAULT '0' ,
		ADD COLUMN `smaller_ratio` DECIMAL(15,5) DEFAULT '0' ,
		ADD COLUMN `rounding` DECIMAL(15,5) DEFAULT '0'

		;");
}

if (!$db->fieldExists('product_type' , $dbprefix . 'items')) { 
	$db->query('ALTER TABLE `' . $dbprefix . "items`
		ADD COLUMN `product_type` VARCHAR(100) NULL

		;");
}

if (!$db->fieldExists('description_internal_transfers' , $dbprefix . 'items')) { 
	$db->query('ALTER TABLE `' . $dbprefix . "items`
		ADD COLUMN `description_internal_transfers` TEXT NULL 
		;");
}
if (!$db->fieldExists('description_receipts' , $dbprefix . 'items')) { 
	$db->query('ALTER TABLE `' . $dbprefix . "items`
		ADD COLUMN `description_receipts` TEXT NULL
		;");
}

if (!$db->fieldExists('description_delivery_orders' , $dbprefix . 'items')) { 
	$db->query('ALTER TABLE `' . $dbprefix . "items`
		ADD COLUMN `description_delivery_orders` TEXT NULL
		;");
}

if (!$db->fieldExists('customer_lead_time' , $dbprefix . 'items')) { 
	$db->query('ALTER TABLE `' . $dbprefix . "items`
		ADD COLUMN `customer_lead_time` DECIMAL(15,2) NULL DEFAULT '0'
		;");
}

if (!$db->fieldExists('replenish_on_order' , $dbprefix . 'items')) { 
	$db->query('ALTER TABLE `' . $dbprefix . "items`
		ADD COLUMN `replenish_on_order` VARCHAR(100) NULL
		;");
}

if (!$db->fieldExists('supplier_taxes_id' , $dbprefix . 'items')) { 
	$db->query('ALTER TABLE `' . $dbprefix . "items`
		ADD COLUMN `supplier_taxes_id` TEXT NULL
		;");
}

if (!$db->fieldExists('description_sale' , $dbprefix . 'items')) { 
	$db->query('ALTER TABLE `' . $dbprefix . "items`
		ADD COLUMN `description_sale` TEXT NULL
		;");
}

if (!$db->fieldExists('invoice_policy' , $dbprefix . 'items')) { 
	$db->query('ALTER TABLE `' . $dbprefix . "items`
		ADD COLUMN `invoice_policy` VARCHAR(100) NULL DEFAULT 'ordered_quantities'
		;");
}
if (!$db->fieldExists('purchase_unit_measure' , $dbprefix . 'items')) { 
	$db->query('ALTER TABLE `' . $dbprefix . "items`
		ADD COLUMN `purchase_unit_measure` INT(11) NULL 
		;");
}

if (!$db->fieldExists('can_be_sold' , $dbprefix . 'items')) { 
	$db->query('ALTER TABLE `' . $dbprefix . "items`
		ADD COLUMN `can_be_sold` VARCHAR(100) NULL DEFAULT 'can_be_sold'
		;");
}
if (!$db->fieldExists('can_be_purchased' , $dbprefix . 'items')) { 
	$db->query('ALTER TABLE `' . $dbprefix . "items`
		ADD COLUMN `can_be_purchased` VARCHAR(100) NULL DEFAULT 'can_be_purchased' 
		;");
}
if (!$db->fieldExists('can_be_manufacturing' , $dbprefix . 'items')) { 
	$db->query('ALTER TABLE `' . $dbprefix . "items`
		ADD COLUMN `can_be_manufacturing` VARCHAR(100) NULL DEFAULT 'can_be_manufacturing' 
		;");
}
if (!$db->fieldExists('manufacture' , $dbprefix . 'items')) { 
	$db->query('ALTER TABLE `' . $dbprefix . "items`
		ADD COLUMN `manufacture` VARCHAR(100) NULL
		;");
}
if (!$db->fieldExists('manufacturing_lead_time' , $dbprefix . 'items')) { 
	$db->query('ALTER TABLE `' . $dbprefix . "items`
		ADD COLUMN `manufacturing_lead_time` DECIMAL(15,2) NULL DEFAULT '0' 
		;");
}
if (!$db->fieldExists('weight' , $dbprefix . 'items')) { 
	$db->query('ALTER TABLE `' . $dbprefix . "items`
		ADD COLUMN `weight` DECIMAL(15,2) NULL DEFAULT '0' 
		;");
}
if (!$db->fieldExists('volume' , $dbprefix . 'items')) { 
	$db->query('ALTER TABLE `' . $dbprefix . "items`
		ADD COLUMN `volume` DECIMAL(15,2) NULL DEFAULT '0'
		;");
}
if (!$db->fieldExists('hs_code' , $dbprefix . 'items')) { 
	$db->query('ALTER TABLE `' . $dbprefix . "items`
		ADD COLUMN `hs_code` TEXT NULL
		;");
}


//BOM
if (!$db->tableExists($dbprefix . "mrp_bill_of_materials")) {
	$db->query("CREATE TABLE `" . $dbprefix . "mrp_bill_of_materials` (
		`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

		`bom_code` VARCHAR(100) NULL,
		`product_id` int(11) NULL,
		`product_variant_id` int(11) NULL,
		`product_qty` DECIMAL(15,2) DEFAULT '0',
		`unit_id` INT(11) NULL,
		`routing_id` INT(11) NULL,
		`bom_type` VARCHAR(100) NULL,
		`ready_to_produce` TEXT NULL,
		`consumption` TEXT NULL,

		PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");
}


if (!$db->tableExists($dbprefix . "mrp_bill_of_material_details")) {
	$db->query("CREATE TABLE `" . $dbprefix . "mrp_bill_of_material_details` (
		`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
		`bill_of_material_id` int(11) NOT NULL ,

		`product_id` int(11) NULL COMMENT  'Only Product variant do not get parent Product',
		`product_qty` DECIMAL(15,2) DEFAULT '0',
		`unit_id` INT(11) NULL,
		`apply_on_variants` TEXT NULL,
		`operation_id` INT(11) NULL,
		`display_order` DECIMAL(15,2) DEFAULT '0',

		PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");
}

//manufacturing order
//status: draft,confirmed,planned,cancelled,in_progress,done
if (!$db->tableExists($dbprefix . "mrp_manufacturing_orders")) {
	$db->query("CREATE TABLE `" . $dbprefix . "mrp_manufacturing_orders` (
		`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

		`manufacturing_order_code` VARCHAR(100) NULL,
		`product_id` int(11) NULL COMMENT  'Only Product variant do not get parent Product',
		`product_qty` DECIMAL(15,2) DEFAULT '0',
		`unit_id` INT(11) NULL,
		`bom_id` INT(11) NULL,
		`routing_id` INT(11) NULL,
		`date_deadline` DATETIME NULL,
		`date_plan_from` DATETIME NULL,
		`date_planned_start` DATETIME NULL ,
		`date_planned_finished` DATETIME NULL ,

		`status` VARCHAR(100) NULL DEFAULT 'draft',
		`material_availability_status` VARCHAR(100) NULL,
		`staff_id` INT(11) NULL,
		`components_warehouse_id` TEXT NULL,
		`finished_products_warehouse_id` TEXT NULL,
		`purchase_request_id` INT(11) NULL,

		PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");
}


if (!$db->tableExists($dbprefix . "mrp_manufacturing_order_details")) {
	$db->query("CREATE TABLE `" . $dbprefix . "mrp_manufacturing_order_details` (
		`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
		`manufacturing_order_id` int(11) NOT NULL ,

		`product_id` int(11) NULL,
		`unit_id` INT(11) NULL,
		`qty_to_consume` DECIMAL(15,2) DEFAULT '0',
		`qty_reserved` DECIMAL(15,2) DEFAULT '0',
		`qty_done` DECIMAL(15,2) DEFAULT '0',
		`check_inventory_qty` VARCHAR(10) NULL,
		`warehouse_id` TEXT NULL,
		`lot_number` TEXT NULL,
		`expiry_date` TEXT NULL,
		`available_quantity` DECIMAL(15,2) DEFAULT '0',

		PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");
}

//status: waiting for another WO, Ready, in Progress, Finished
if (!$db->tableExists($dbprefix . "mrp_work_orders")) {
	$db->query("CREATE TABLE `" . $dbprefix . "mrp_work_orders` (
		`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
		`manufacturing_order_id` int(11) NOT NULL ,

		`product_id` int(11) NULL ,
		`qty_produced` DECIMAL(15,2) DEFAULT '0',
		`qty_production` DECIMAL(15,2) DEFAULT '0',
		`qty_producing` DECIMAL(15,2) DEFAULT '0',
		`unit_id` INT(11) NULL,
		`routing_detail_id` INT(11) NULL,
		`operation_name` TEXT NULL,
		`work_center_id` INT(11) NULL,

		`date_planned_start` DATETIME NULL ,
		`date_planned_finished` DATETIME NULL ,
		`date_start` DATETIME NULL ,
		`date_finished` DATETIME NULL ,
		`duration_expected` DECIMAL(15,2) DEFAULT '0',
		`real_duration` DECIMAL(15,2) DEFAULT '0',
		`status` VARCHAR(100) NULL ,

		PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");
}

if (!$db->tableExists($dbprefix . "mrp_work_order_details")) {
	$db->query("CREATE TABLE `" . $dbprefix . "mrp_work_order_details` (
		`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
		`work_order_id` int(11) NOT NULL ,

		`product_id` int(11) NULL ,
		`to_consume` DECIMAL(15,2) DEFAULT '0',
		`reserved` DECIMAL(15,2) DEFAULT '0',

		PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");
}


if (!$db->tableExists($dbprefix . "mrp_work_order_time_trackings")) {
	$db->query("CREATE TABLE `" . $dbprefix . "mrp_work_order_time_trackings` (
		`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
		`work_order_id` int(11) NOT NULL ,

		`from_date` DATETIME NULL ,
		`to_date` DATETIME NULL ,
		`duration` DECIMAL(15,2) DEFAULT '0',
		`staff_id` INT(11) NULL,

		PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");
}


//general settings
add_setting('bom_prefix', '#BOM_', 1);
add_setting('bom_number', 1, 1);
add_setting('routing_prefix', '#RO_', 1);
add_setting('routing_number', 1, 1);
add_setting('mo_prefix', '#MO_', 1);
add_setting('mo_number', 1, 1);
add_setting('cost_hour', 0, 0);

