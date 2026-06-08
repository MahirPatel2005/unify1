<?php
defined('PLUGINPATH') or exit('No direct script access allowed');

/*
Plugin Name: Manufacturing Management
Description: This solution supports the entire spectrum of manufacturing styles, from high volume to engineer‐to‐order, and coordinates orders, equipment, facilities, inventory, and work-in-progress to minimize costs and maximize on-time delivery
Version: 1.0.0
Requires at least: 3.0
Author: GreenTech Solutions
Author URI: https://codecanyon.net/user/greentech_solutions
*/

use App\Libraries\Template;
use App\Controllers\Security_Controller;

if(!defined('VERSION_MANUFACTURING')){
	define('VERSION_MANUFACTURING', 100);
}

/*Modules Path*/
if(!defined('APP_MODULES_PATH')){
	define('APP_MODULES_PATH', FCPATH . 'plugins/');
}
if(!defined('MANUFACTURING_MODULE_UPLOAD_FOLDER')){
	define('MANUFACTURING_MODULE_UPLOAD_FOLDER', 'plugins/Manufacturing/Uploads');
}
if(!defined('MANUFACTURING_PATH')){
	define('MANUFACTURING_PATH', 'plugins/Manufacturing/Uploads/');
}

if(!defined('MANUFACTURING_OPERATION_ATTACHMENTS_UPLOAD_FOLDER')){
	define('MANUFACTURING_OPERATION_ATTACHMENTS_UPLOAD_FOLDER', 'plugins/Manufacturing/Uploads/operations/');
}
if(!defined('MANUFACTURING_PRODUCT_UPLOAD')){
	define('MANUFACTURING_PRODUCT_UPLOAD', 'plugins/Manufacturing/Uploads/products/');
}

if(!defined('OPERATION_ATTACHMENTS')){
	define('OPERATION_ATTACHMENTS', 'plugins/Manufacturing/Uploads/operations/');
}
if(!defined('MANUFACTURING_PRINT_ITEM')){
	define('MANUFACTURING_PRINT_ITEM', 'plugins/Manufacturing/Uploads/print_item/');
}

if(!defined('EXT')){
	define('EXT', '.php');
}
if(!defined('MANUFACTURING_VIEWPATH')){
    define('MANUFACTURING_VIEWPATH', 'plugins/Manufacturing');    
}

app_hooks()->add_filter('app_hook_head_extension', function () {
	$viewuri = $_SERVER['REQUEST_URI'];
	/*add css file*/

	if(!(strpos($viewuri,'manufacturing') === false)){
		echo '<link href="' . base_url('plugins/Manufacturing/assets/css/styles.css') . '?v=' . VERSION_MANUFACTURING. '"  rel="stylesheet" type="text/css" />';
		echo '<link href="' . base_url('plugins/Manufacturing/assets/plugins/main/main.css') . '?v=' . VERSION_MANUFACTURING. '"  rel="stylesheet" type="text/css" />';
	}
	if(!(strpos($viewuri,'manufacturing/add_edit_work_center') === false)){
		echo '<link href="' . base_url('plugins/Manufacturing/assets/css/chart_on_header.css') . '?v=' . VERSION_MANUFACTURING. '"  rel="stylesheet" type="text/css" />';
	}

	if(!(strpos($viewuri,'manufacturing/add_edit_working_hour') === false) || !(strpos($viewuri,'manufacturing/add_edit_manufacturing_order') === false) || !(strpos($viewuri,'manufacturing/view_manufacturing_order') === false) || !(strpos($viewuri,'manufacturing/view_work_order') === false) ){
		echo '<link href="' . base_url('plugins/Manufacturing/assets/plugins/handsontable/handsontable.full.min.css') . '"  rel="stylesheet" type="text/css" />';
		echo '<link href="' . base_url('plugins/Manufacturing/assets/plugins/handsontable/chosen.css') . '"  rel="stylesheet" type="text/css" />';
		echo '<script src="' . base_url('plugins/Manufacturing/assets/plugins/handsontable/handsontable.full.min.js') . '"></script>';
	}

	if(!(strpos($viewuri,'manufacturing/add_edit_product') === false)){
		echo '<link href="' . base_url('plugins/Manufacturing/assets/css/products/product_chart_on_header.css') . '?v=' . VERSION_MANUFACTURING. '"  rel="stylesheet" type="text/css" />';
		echo '<link href="' . base_url('plugins/Manufacturing/assets/css/loading.css') . '?v=' . VERSION_MANUFACTURING. '"  rel="stylesheet" type="text/css" />';
	}

	if(!(strpos($viewuri,'manufacturing/view_work_order') === false)){
		echo '<link href="' . base_url('plugins/Manufacturing/assets/css/work_orders/view_work_order.css') . '?v=' . VERSION_MANUFACTURING. '"  rel="stylesheet" type="text/css" />';
	}

	if(!(strpos($viewuri,'manufacturing/mo_work_order_manage') === false)){
		echo '<link href="' . base_url('plugins/Manufacturing/assets/plugins/frappe-gantt/frappe-gantt.css') . '"  rel="stylesheet" type="text/css" />';
	}
	if(!(strpos($viewuri,'manufacturing/dashboard') === false)){
		echo '<link href="' . base_url('plugins/Manufacturing/assets/css/dashboard.css') . '?v=' . VERSION_MANUFACTURING. '"  rel="stylesheet" type="text/css" />';
	}

	if (!(strpos($viewuri, '/manufacturing/view_product_detail') === false)) {
		echo '<link href="' . base_url('plugins/Manufacturing/assets/plugins/simplelightbox/simple-lightbox.min.css') . '"  rel="stylesheet" type="text/css" />';
		echo '<link href="' . base_url('plugins/Manufacturing/assets/plugins/simplelightbox/masonry-layout-vanilla.min.css') . '"  rel="stylesheet" type="text/css" />';
	}  

});

app_hooks()->add_filter('app_hook_head_extension', function () {
	$viewuri = $_SERVER['REQUEST_URI'];
	/*add js file*/

	if (!(strpos($viewuri, '/manufacturing') === false)) {
		echo '<script src="' . base_url('plugins/Manufacturing/assets/plugins/main/main.js') . '?v=' . VERSION_MANUFACTURING . '"></script>';
	}

	if(!(strpos($viewuri,'manufacturing/dashboard') === false)){

		echo '<script src="'.base_url('plugins/Manufacturing/assets/plugins/highcharts/highcharts.js').'?v=' . VERSION_MANUFACTURING.'"></script>';
		echo '<script src="'.base_url('plugins/Manufacturing/assets/plugins/highcharts/variable-pie.js').'?v=' . VERSION_MANUFACTURING.'"></script>';
		echo '<script src="'.base_url('plugins/Manufacturing/assets/plugins/highcharts/export-data.js').'?v=' . VERSION_MANUFACTURING.'"></script>';
		echo '<script src="'.base_url('plugins/Manufacturing/assets/plugins/highcharts/accessibility.js').'?v=' . VERSION_MANUFACTURING.'"></script>';
		echo '<script src="'.base_url('plugins/Manufacturing/assets/plugins/highcharts/exporting.js').'?v=' . VERSION_MANUFACTURING.'"></script>';
		echo '<script src="'.base_url('plugins/Manufacturing/assets/plugins/highcharts/highcharts-3d.js').'?v=' . VERSION_MANUFACTURING.'"></script>';
	}

	if (!(strpos($viewuri, 'manufacturing/add_edit_working_hour') === false) || !(strpos($viewuri, 'manufacturing/add_edit_manufacturing_order') === false)|| !(strpos($viewuri, 'manufacturing/view_manufacturing_order') === false) || !(strpos($viewuri, 'manufacturing/view_work_order') === false) ) {
		echo '<script src="' . base_url('plugins/Manufacturing/assets/plugins/handsontable/chosen.jquery.js') . '"></script>';
		echo '<script src="' . base_url('plugins/Manufacturing/assets/plugins/handsontable/handsontable-chosen-editor.js') . '"></script>';
	}

	if(!(strpos($viewuri,'manufacturing/mo_work_order_manage') === false)){
		echo '<script src="' . base_url('plugins/Manufacturing/assets/plugins/frappe-gantt/frappe-gantt.min.js') . '"></script>';
	}

	if (!(strpos($viewuri, '/manufacturing/view_product_detail') === false)) { 
		echo '<script src="' . base_url('plugins/Manufacturing/assets/plugins/simplelightbox/simple-lightbox.min.js') . '"></script>';
		echo '<script src="' . base_url('plugins/Manufacturing/assets/plugins/simplelightbox/simple-lightbox.jquery.min.js') . '"></script>';
		echo '<script src="' . base_url('plugins/Manufacturing/assets/plugins/simplelightbox/masonry-layout-vanilla.min.js') . '"></script>';

	}
});

app_hooks()->add_action('app_hook_role_permissions_extension_plugins', function ($permissions){
	$viewuri = $_SERVER['REQUEST_URI'];

	$permission_data = [];

	if((strpos($viewuri,'manufacturing/role_permissions') === false)){

		$permission_data['manufacturing_can_view_global'] = get_array_value($permissions, "manufacturing_can_view_global");
		$permission_data['manufacturing_can_create'] = get_array_value($permissions, "manufacturing_can_create");
		$permission_data['manufacturing_can_edit'] = get_array_value($permissions, "manufacturing_can_edit");
		$permission_data['manufacturing_can_delete'] = get_array_value($permissions, "manufacturing_can_delete");

		$Template = new Template(false);

		$ci = new Security_Controller(false);
		$access_manufacturing = get_array_value($permissions, "manufacturing");
		if (is_null($access_manufacturing)) {
			$access_manufacturing = "";
		}

		echo  $Template->view('Manufacturing\Views\settings/manufacturing_permissions', $permission_data);

	}else{
		echo '';
	}
});

app_hooks()->add_filter('app_filter_role_permissions_save_data_plugin', function ($permissions,$data) {
	$viewuri = $_SERVER['REQUEST_URI'];
	
	/*data*/
	$manufacturing_data=[];

	if((strpos($viewuri,'manufacturing/role_permissions') === false)){

		$manufacturing_data['manufacturing_can_view_global'] = isset($data['manufacturing_can_view_global']) ? $data['manufacturing_can_view_global'] : NULL;
		$manufacturing_data['manufacturing_can_create'] = isset($data['manufacturing_can_create']) ? $data['manufacturing_can_create'] : NULL;
		$manufacturing_data['manufacturing_can_edit'] = isset($data['manufacturing_can_edit']) ? $data['manufacturing_can_edit'] : NULL;
		$manufacturing_data['manufacturing_can_delete'] = isset($data['manufacturing_can_delete']) ? $data['manufacturing_can_delete'] : NULL;

		$permissions = array_merge($permissions, $manufacturing_data);
	}

	return $permissions;
});

app_hooks()->add_filter('app_filter_notification_config', function ($events) {

	return $events;
});


/*add menu item to left menu*/
app_hooks()->add_filter('app_filter_staff_left_menu', function ($sidebar_menu) {
	$manufacturing_submenu = array();
	$ci = new Security_Controller(false);
	$permissions = $ci->login_user->permissions;

	if ($ci->login_user->is_admin || mrp_has_permission("manufacturing_can_view_global")) {
		$manufacturing_submenu["dashboard"] = array(
			"name" => "mrp_dashboard", 
			"url" => "manufacturing/dashboard", 
			"class" => "industry",
		);
		
		$manufacturing_submenu["product_management"] = array(
			"name" => "mrp_products", 
			"url" => "manufacturing/product_management", 
			"class" => "users",
		);
		$manufacturing_submenu["product_variant_management"] = array(
			"name" => "mrp_product_variants", 
			"url" => "manufacturing/product_variant_management", 
			"class" => "users",
		);
		$manufacturing_submenu["bill_of_material_manage"] = array(
			"name" => "mrp_bills_of_materials", 
			"url" => "manufacturing/bill_of_material_manage", 
			"class" => "users",
		);
		$manufacturing_submenu["routing_manage"] = array(
			"name" => "mrp_routings", 
			"url" => "manufacturing/routing_manage", 
			"class" => "users",
		);
		$manufacturing_submenu["work_center_manage"] = array(
			"name" => "mrp_work_centers", 
			"url" => "manufacturing/work_center_manage", 
			"class" => "users",
		);
		$manufacturing_submenu["manufacturing_order_manage"] = array(
			"name" => "mrp_manufaturing_orders", 
			"url" => "manufacturing/manufacturing_order_manage", 
			"class" => "users",
		);
		$manufacturing_submenu["work_order_manage"] = array(
			"name" => "mrp_work_orders", 
			"url" => "manufacturing/work_order_manage", 
			"class" => "users",
		);
		
		$manufacturing_submenu["working_hour"] = array(
			"name" => "mrp_settings", 
			"url" => "manufacturing/prefix_numbers", 
			"class" => "users",
		);
		

		$sidebar_menu["manufacturing"] = array(
			"name" => "manufacturing_name",
			"url" => "manufacturing",
			"class" => "inbox",
			"submenu" => $manufacturing_submenu,
			"position" => 7,

		);
	}

	return $sidebar_menu;

});


/*install dependencies*/
register_installation_hook("Manufacturing", function ($item_purchase_code) {
/*
* you can verify the item puchase code from here if you want. 
* you'll get the inputted puchase code with $item_purchase_code variable
* use exit(); here if there is anything doesn't meet it's requirements
*/
include PLUGINPATH . "Manufacturing/lib/gtsverify.php";
require_once(__DIR__ . '/install.php');
});

/*Active action*/
register_activation_hook("Manufacturing", function ($item_purchase_code) {
	require_once(__DIR__ . '/install.php');
});

/*add setting link to the plugin setting*/
app_hooks()->add_filter('app_filter_action_links_of_Manufacturing', function () {
	$action_links_array = array(
	);

	return $action_links_array;
});

/*update plugin*/
register_update_hook("Manufacturing", function () {
	require_once __DIR__ . '/install.php';
});

/*uninstallation: remove data from database*/
register_uninstallation_hook("Manufacturing", function () {
	require_once __DIR__ . '/uninstall.php';
});

app_hooks()->add_action('app_hook_manufacturing_init', function (){
    require_once __DIR__ .'/lib/gtsslib.php';
    $lic_mf = new ManufacturingLic();
    $mf_gtssres = $lic_mf->verify_license(true);    
    if(!$mf_gtssres || ($mf_gtssres && isset($mf_gtssres['status']) && !$mf_gtssres['status'])){
        echo '<strong>YOUR MANUFACURING MANAGEMENT PLUGIN FAILED ITS VERIFICATION. PLEASE <a href="/index.php/Plugins">REINSTALL</a> OR CONTACT SUPPORT</strong>';
        exit();
    } 
});
app_hooks()->add_action('app_hook_uninstall_plugin_Manufacturing', function (){
    require_once __DIR__ .'/lib/gtsslib.php';
    $lic_mf = new ManufacturingLic();
    $lic_mf->deactivate_license();    
});
