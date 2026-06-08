<?php

namespace Manufacturing\Controllers;

use App\Controllers\Security_Controller;
use App\Models\Crud_model;

class Manufacturing extends Security_Controller {

	protected $manufacturing_model;
	function __construct() {

		parent::__construct();
		$this->manufacturing_model = new \Manufacturing\Models\Manufacturing_model();
		app_hooks()->do_action('manufacturing_init');

	}

	public function index() {
		app_redirect('manufacturing/dashboard');
	}


	/**
	 * work center manage
	 * @return [type] 
	 */
	public function work_center_manage()
	{
		if (!mrp_has_permission('manufacturing_can_view_global') ) {
			app_redirect('work_center');
		}

		$data['title'] = app_lang('mrp_work_centers');
		
		$required_inventory_purchase = mrp_required_inventory_purchase_module();
		//required inventory purchase
		if($required_inventory_purchase['inventory'] == false || $required_inventory_purchase['purchase'] == false){
			return $this->template->rander('Manufacturing\Views/settings/required_inventory_module', $data);
		}else{
			return $this->template->rander('Manufacturing\Views/work_centers/work_center_manage', $data);
		}
	}

	/**
	 * work center table
	 * @return [type] 
	 */
	public function work_center_table()
	{
		$dataPost = $this->request->getPost();
		$this->manufacturing_model->get_table_data(module_views_path('Manufacturing', 'work_centers/work_center_table'), $dataPost);
	}

	/**
	 * work center modal
	 * @return [type] 
	 */
	public function work_center_modal()
	{

		$this->load->model('staff_model');

		$data=[];
		if ($this->request->getPost('slug') === 'update') {
			$id = $this->request->getPost('id');
			$data['work_center'] = $this->manufacturing_model->get_work_centers($id);
		}
		return $this->template->rander('Manufacturing\Views/settings/work_center_modal', $data);
	}


	/**
	 * add edit work center
	 * @param string $id 
	 */
	public function add_edit_work_center($id = '')
	{
		if (!mrp_has_permission('manufacturing_can_view_global')  && !is_admin()) {
			app_redirect('work_center');
		}
		
		if ($this->request->getPost()) {
			$data = $this->request->getPost();
			
			if ($id == '') {
				if (!mrp_has_permission('manufacturing_can_create') && !is_admin()) {
					app_redirect('work_center');
				}

				$id = $this->manufacturing_model->add_work_center($data);
				if ($id) {
					$success = true;
					$message = app_lang('mrp_added_successfully', app_lang('work_center'));
				}

				if ($id) {
					$this->session->setFlashdata("success_message", app_lang("mrp_added_successfully"));
					app_redirect(('manufacturing/work_center_manage'));
				}

			} else {
				if (!mrp_has_permission('manufacturing_can_edit') && !is_admin()) {
					app_redirect('work_center');
				}

				$response = $this->manufacturing_model->update_work_center($data, $id);

				if ($response == true) {
					$this->session->setFlashdata("success_message", app_lang("mrp_updated_successfully"));
				}
				app_redirect(('manufacturing/work_center_manage'));
			}
		}
		
		$data=[];
		if ($id != ''){
			$data['work_center'] = $this->manufacturing_model->get_work_centers($id);
		}
		$data['working_hours'] = $this->manufacturing_model->get_working_hours();

		return $this->template->rander('Manufacturing\Views/work_centers/add_edit_work_center', $data);
	}


	/**
	 * view work center
	 * @param  [type] $id 
	 * @return [type]     
	 */
	public function view_work_center($id)
	{
		if (!mrp_has_permission('manufacturing_can_view_global')  && !is_admin()) {
			app_redirect('work_center');
		}

		$work_center = $this->manufacturing_model->get_work_centers($id);

		if (!$work_center) {
			blank_page('Work Center Not Found', 'danger');
		}

		$data['work_center'] = $work_center;
		return $this->template->rander('Manufacturing\Views/work_centers/view_work_center', $data);
	}


	/**
	 * delete work center
	 * @param  [type] $id 
	 * @return [type]     
	 */
	public function delete_work_center()
	{
		$id = $this->request->getPost('id');
		if (!mrp_has_permission('manufacturing_can_delete')  && !is_admin()) {
			app_redirect('work_center');
		}

		$success = $this->manufacturing_model->delete_work_center($id);
		if ($success) {
			$this->session->setFlashdata("success_message", app_lang("mrp_deleted"));
		} else {
			$this->session->setFlashdata("error_message", app_lang("problem_deleting"));
		}
		app_redirect(('manufacturing/work_center_manage'));

	}

	/**
	 * working hours
	 * @return [type] 
	 */
	public function working_hours()
	{
		$data= [];

		$required_inventory_purchase = mrp_required_inventory_purchase_module();
		//required inventory purchase
		if($required_inventory_purchase['inventory'] == false || $required_inventory_purchase['purchase'] == false){
			return $this->template->rander('Manufacturing\Views/settings/required_inventory_module', $data);
		}else{
			return $this->template->rander("Manufacturing\Views\settings\working_hour", $data);
		}
	}

	/**
	 * working hour table
	 * @return [type] 
	 */
	public function working_hour_table()
	{
		$dataPost = $this->request->getPost();
		$this->manufacturing_model->get_table_data(module_views_path('Manufacturing', 'settings/working_hour_table'), $dataPost);

	}


	/**
	 * add edit working hour
	 * @param string $id 
	 */
	public function add_edit_working_hour($id = '')
	{
		if (!mrp_has_permission('manufacturing_can_view_global')  && !is_admin()) {
			app_redirect("forbidden");
		}
		
		if ($this->request->getPost()) {
			$data = $this->request->getPost();

			if ($id == '') {
				if (!mrp_has_permission('manufacturing_can_create') && !is_admin()) {
					app_redirect("forbidden");
				}

				$id = $this->manufacturing_model->add_working_hour($data);
				if ($id) {
					$this->session->setFlashdata("success_message", app_lang("mrp_added_successfully"));
					app_redirect(('manufacturing/working_hours'));
				}

			} else {
				if (!mrp_has_permission('manufacturing_can_edit') && !is_admin()) {
					app_redirect("forbidden");
				}

				$response = $this->manufacturing_model->update_working_hour($data, $id);

				if ($response == true) {
					$this->session->setFlashdata("success_message", app_lang("mrp_updated_successfully"));
				}
				app_redirect(('manufacturing/working_hours'));
			}
		}
		
		$data=[];
		if ($id != ''){
			$working_hour = $this->manufacturing_model->get_working_hour($id);
			$data['working_hour'] = $working_hour['working_hour'];
			$data['working_hour_details'] = $working_hour['working_hour_details'];
			$data['time_off'] = $working_hour['time_off'];
			
		}

		$day_period_type =[];
		$day_period_type[] = [
			'id' => 'morning',
			'label' => app_lang('morning'),
		];
		$day_period_type[] = [
			'id' => 'afternoon',
			'label' => app_lang('afternoon'),
		];

		$day_of_week_types=[];
		foreach (mrp_date_of_week() as $key => $value) {
			array_push($day_of_week_types, [
				'id' => $key,
				'label' => app_lang($value),
			]);
		}

		$data['day_of_week_types'] = $day_of_week_types;
		$data['day_period_type'] = $day_period_type;
		$data['working_hour_sample_data'] = working_hour_sample_data();
		
		return $this->template->rander('Manufacturing\Views/settings/add_edit_working_hour', $data);
	}


	/**
	 * delete working hour
	 * @param  [type] $id 
	 * @return [type]     
	 */
	public function delete_working_hour()
	{
		$id = $this->request->getPost('id');
		if (!mrp_has_permission('manufacturing_can_delete')  && !is_admin()) {
			app_redirect('work_center');
		}

		$success = $this->manufacturing_model->delete_working_hour($id);
		if ($success) {
			$this->session->setFlashdata("success_message", app_lang("mrp_deleted"));
		} else {
			$this->session->setFlashdata("error_message", app_lang("problem_deleting"));
		}
		app_redirect(('manufacturing/working_hours'));
	}


	/*Routings*/

	/**
	 * routing manage
	 * @return [type] 
	 */
	public function routing_manage()
	{
		if (!mrp_has_permission('manufacturing_can_view_global') ) {
			app_redirect('work_center');
		}

		$data['title'] = app_lang('routing');
		
		$required_inventory_purchase = mrp_required_inventory_purchase_module();
		//required inventory purchase
		if($required_inventory_purchase['inventory'] == false || $required_inventory_purchase['purchase'] == false){
			return $this->template->rander('Manufacturing\Views/settings/required_inventory_module', $data);
		}else{
			return $this->template->rander("Manufacturing\Views/routings/routing_manage", $data);
		}
	}

	/**
	 * routing table
	 * @return [type] 
	 */
	public function routing_table()
	{
		$dataPost = $this->request->getPost();
		$this->manufacturing_model->get_table_data(module_views_path('Manufacturing', 'routings/routing_table'), $dataPost);
	}

	/**
	 * add routing modal
	 */
	public function routing_modal()
	{
		$data=[];
		$data['routing_code'] = $this->manufacturing_model->create_code('routing_code');
		return $this->template->view('Manufacturing\Views/routings/add_routing_modal', $data);

	}


	/**
	 * add routing modal
	 * @param string $id 
	 */
	public function add_routing_modal($id='')
	{

		if (!mrp_has_permission('manufacturing_can_view_global')  && !is_admin()) {
			app_redirect('routing');
		}
		
		if ($this->request->getPost()) {
			$data = $this->request->getPost();

			if ($id == '') {
				if (!mrp_has_permission('manufacturing_can_create') && !is_admin()) {
					app_redirect('routing');
				}

				$id = $this->manufacturing_model->add_routing($data);
				if ($id) {
					$this->session->setFlashdata("success_message", app_lang("mrp_added_successfully"));
					app_redirect(('manufacturing/operation_manage/'.$id));
				}

			} else {
				if (!mrp_has_permission('manufacturing_can_edit') && !is_admin()) {
					app_redirect('routing');
				}

				$response = $this->manufacturing_model->update_routing($data, $id);

				if ($response == true) {
					$this->session->setFlashdata("success_message", app_lang("mrp_updated_successfully"));
				}
				app_redirect(('manufacturing/operation_manage/'.$id));
			}
		}
		
		$data=[];
		if ($id != ''){
			$working_hour = $this->manufacturing_model->get_working_hour($id);
			$data['working_hour'] = $working_hour['working_hour'];
			$data['working_hour_details'] = $working_hour['working_hour_details'];
			$data['time_off'] = $working_hour['time_off'];
			
		}
		

		$day_period_type =[];
		$day_period_type[] = [
			'id' => 'morning',
			'label' => app_lang('morning'),
		];
		$day_period_type[] = [
			'id' => 'afternoon',
			'label' => app_lang('afternoon'),
		];

		$day_of_week_types=[];
		foreach (mrp_date_of_week() as $key => $value) {
			array_push($day_of_week_types, [
				'id' => $key,
				'label' => $value,
			]);
		}

		$data['day_of_week_types'] = $day_of_week_types;
		$data['day_period_type'] = $day_period_type;
		
		return $this->template->rander('Manufacturing\Views/settings/add_edit_working_hour', $data);
	}

	/**
	 * delete routing
	 * @param  [type] $id 
	 * @return [type]     
	 */
	public function delete_routing()
	{
		$id = $this->request->getPost('id');
		if (!mrp_has_permission('manufacturing_can_delete')  && !is_admin()) {
			app_redirect('routing');
		}

		$success = $this->manufacturing_model->delete_routing($id);
		if ($success) {
			$this->session->setFlashdata("success_message", app_lang("mrp_deleted"));
		} else {
			$this->session->setFlashdata("error_message", app_lang("problem_deleting"));
		}
		app_redirect(('manufacturing/routing_manage'));
	}


	/**
	 * operation manage
	 * @return [type] 
	 */
	public function operation_manage($id='')
	{
		if (!mrp_has_permission('manufacturing_can_view_global') ) {
			app_redirect('work_center');
		}

		$data['title'] = app_lang('operation');
		if($id != ''){
			$data['routing'] = $this->manufacturing_model->get_routings($id);
		}
		
		return $this->template->rander('Manufacturing\Views/routings/routing_details/operation_manage', $data);
	}


	/**
	 * operation table
	 * @return [type] 
	 */
	public function operation_table()
	{
		
		$dataPost = $this->request->getPost();
		$this->manufacturing_model->get_table_data(module_views_path('Manufacturing', 'routings/routing_details/operation_table'), $dataPost);
	}


	/**
	 * operation_modal
	 * @return [type] 
	 */
	public function operation_modal()
	{
		
		$data=[];
		$data = $this->request->getPost();
		if($data['operation_id'] != 0){
			$data['operation'] = $this->manufacturing_model->get_operation($data['operation_id']);
			$data['operation_attachment'] = $this->manufacturing_model->mrp_get_attachments_file($data['operation_id'], 'mrp_operation');
		}

		$data['work_centers'] = $this->manufacturing_model->get_work_centers();
		return $this->template->view('Manufacturing\Views/routings/routing_details/add_edit_operation_modal', $data);
	}


	/**
	 * add edit operation
	 * @param [type] $operation_id 
	 */
	public function add_edit_operation($id='')
	{
		if (!mrp_has_permission('manufacturing_can_view_global')  && !is_admin()) {
			app_redirect('operation');
		}
		
		if ($this->request->getPost()) {
			$data = $this->request->getPost();
			$routing_id = $data['routing_id'];

			if ($id == '') {
				if (!mrp_has_permission('manufacturing_can_create') && !is_admin()) {
					app_redirect('operation');
				}

				$id = $this->manufacturing_model->add_operation($data);
				if ($id) {
					$uploadedFiles = handle_mrp_operation_attachments_array($id,'file');

					$this->session->setFlashdata("success_message", app_lang("mrp_added_successfully"));
					app_redirect(('manufacturing/operation_manage/'.$routing_id));
				}

			} else {
				if (!mrp_has_permission('manufacturing_can_edit') && !is_admin()) {
					app_redirect('operation');
				}

				$response = $this->manufacturing_model->update_operation($data, $id);

				$uploadedFiles = handle_mrp_operation_attachments_array($id,'file');

				if ($response == true) {
					$this->session->setFlashdata("success_message", app_lang("mrp_updated_successfully"));
				}
				app_redirect(('manufacturing/operation_manage/'.$routing_id));

			}
		}

	}


	/**
	 * delete operation
	 * @param  [type] $id 
	 * @return [type]     
	 */
	public function delete_operation()
	{
		$id = $this->request->getPost('id');
		$routing_id = $this->request->getPost('id2');
		if (!mrp_has_permission('manufacturing_can_delete')  && !is_admin()) {
			app_redirect('work_center');
		}

		$success = $this->manufacturing_model->delete_operation($id);
		if ($success) {
			$this->session->setFlashdata("success_message", app_lang("mrp_deleted"));
		} else {
			$this->session->setFlashdata("error_message", app_lang("problem_deleting"));
		}
		app_redirect(('manufacturing/operation_manage/'.$routing_id));


	}

	/**
	 * mrp view attachment file
	 * @param  [type] $id     
	 * @param  [type] $rel_id 
	 * @return [type]         
	 */
	public function mrp_view_attachment_file($id, $rel_id, $rel_type)
	{
		$data['discussion_user_profile_image_url'] = staff_profile_image_url(get_staff_user_id());
		$data['current_user_is_admin']             = is_admin();
		$data['file'] = $this->misc_model->get_file($id);
		if (!$data['file']) {
			header('HTTP/1.0 404 Not Found');
			die;
		}

		switch ($rel_type) {
			case 'operation':
			$folder_link = 'manufacturing/routings/routing_details/view_operation_file';
			break;
			
			default:
				# code...
			break;
		}

		$this->load->view($folder_link, $data);
	}


	/**
	 * delete operation attachment file
	 * @param  [type] $attachment_id 
	 * @return [type]                
	 */
	public function delete_operation_attachment_file($attachment_id)
	{
		if (!mrp_has_permission('manufacturing_can_delete') && !is_admin()) {
			app_redirect('operation');
		}

		echo json_encode([
			'success' => $this->manufacturing_model->delete_mrp_attachment_file($attachment_id, MANUFACTURING_OPERATION_ATTACHMENTS_UPLOAD_FOLDER),
		]);
	}

	/**
	 * unit of measure categories
	 * @return [type] 
	 */
	public function unit_of_measure_categories()
	{
		$data= [];
		$data['categories']	= $this->manufacturing_model->get_unit_categories();

		$required_inventory_purchase = mrp_required_inventory_purchase_module();
		//required inventory purchase
		if($required_inventory_purchase['inventory'] == false || $required_inventory_purchase['purchase'] == false){
			return $this->template->rander('Manufacturing\Views/settings/required_inventory_module', $data);
		}else{
			return $this->template->rander("Manufacturing\Views\settings\unit_of_measure_categories\unit_of_measure_categories", $data);
		}
	}

	/**
	 * add edit category
	 * @param string $id 
	 */
	public function add_edit_category($id='')
	{

		if (!mrp_has_permission('manufacturing_can_view_global')  && !is_admin()) {
			app_redirect('unit_of_measure_categories');
		}
		
		if ($this->request->getPost()) {
			$data = $this->request->getPost();
			if(isset($data['id'])){
				$id = $data['id'];
			}

			if ($id == '') {
				if (!mrp_has_permission('manufacturing_can_create') && !is_admin()) {
					app_redirect('unit_of_measure_categories');
				}

				$id = $this->manufacturing_model->add_unit_categories($data);
				if ($id) {
					$this->session->setFlashdata("success_message", app_lang("mrp_added_successfully"));
					app_redirect(('manufacturing/unit_of_measure_categories'));
				}

			} else {
				if (!mrp_has_permission('manufacturing_can_edit') && !is_admin()) {
					app_redirect('unit_of_measure_categories');
				}

				$response = $this->manufacturing_model->update_unit_categories($data, $id);

				if ($response == true) {
					$this->session->setFlashdata("success_message", app_lang("mrp_updated_successfully"));
				}
				app_redirect(('manufacturing/unit_of_measure_categories'));
			}
		}

	}

	/**
	 * delete category
	 * @param  [type] $id 
	 * @return [type]     
	 */
	public function delete_category()
	{
		$id = $this->request->getPost('id');
		if (!mrp_has_permission('manufacturing_can_delete')  && !is_admin()) {
			app_redirect('unit_of_measure_categories');
		}

		$success = $this->manufacturing_model->delete_unit_categories($id);
		if ($success) {
			$this->session->setFlashdata("success_message", app_lang("mrp_deleted"));
		} else {
			$this->session->setFlashdata("error_message", app_lang("problem_deleting"));
		}
		app_redirect(('manufacturing/unit_of_measure_categories'));
	}

	/**
	 * unit of measures
	 * @return [type] 
	 */
	public function unit_of_measures()
	{
		$data= [];

		$required_inventory_purchase = mrp_required_inventory_purchase_module();
		//required inventory purchase
		if($required_inventory_purchase['inventory'] == false || $required_inventory_purchase['purchase'] == false){
			return $this->template->rander('Manufacturing\Views/settings/required_inventory_module', $data);
		}else{
			return $this->template->rander("Manufacturing\Views\settings\unit_of_measure\unit_of_measure", $data);
		}
	}

	/**
	 * unit of measure table
	 * @return [type] 
	 */
	public function unit_of_measure_table()
	{
		
		$dataPost = $this->request->getPost();
		$this->manufacturing_model->get_table_data(module_views_path('Manufacturing', 'settings/unit_of_measure/unit_of_measure_table'),$dataPost);
	}


	/**
	 * add edit unit of measure
	 * @param string $id 
	 */
	public function add_edit_unit_of_measure($id = '')
	{
		if (!mrp_has_permission('manufacturing_can_view_global')  && !is_admin()) {
			app_redirect('unit_of_measure');
		}
		
		if ($this->request->getPost()) {
			$data = $this->request->getPost();

			if ($id == '') {
				if (!mrp_has_permission('manufacturing_can_create') && !is_admin()) {
					app_redirect('unit_of_measure');
				}

				$id = $this->manufacturing_model->add_unit_of_measure($data);
				if ($id) {
					$this->session->setFlashdata("success_message", app_lang("mrp_added_successfully"));
					app_redirect(('manufacturing/unit_of_measures'));
				}

			} else {
				if (!mrp_has_permission('manufacturing_can_edit') && !is_admin()) {
					app_redirect('unit_of_measure');
				}

				$response = $this->manufacturing_model->update_unit_of_measure($data, $id);

				if ($response == true) {
					$this->session->setFlashdata("success_message", app_lang("mrp_updated_successfully"));
				}
				app_redirect(('manufacturing/unit_of_measures'));
			}
		}
		
		$data=[];
		if ($id != ''){
			$working_hour = $this->manufacturing_model->get_working_hour($id);
			$data['working_hour'] = $working_hour['working_hour'];
			$data['working_hour_details'] = $working_hour['working_hour_details'];
			$data['time_off'] = $working_hour['time_off'];
			
		}
		

		$day_period_type =[];
		$day_period_type[] = [
			'id' => 'morning',
			'label' => app_lang('morning'),
		];
		$day_period_type[] = [
			'id' => 'afternoon',
			'label' => app_lang('afternoon'),
		];

		$day_of_week_types=[];
		foreach (mrp_date_of_week() as $key => $value) {
			array_push($day_of_week_types, [
				'id' => $key,
				'label' => $value,
			]);
		}

		$data['day_of_week_types'] = $day_of_week_types;
		$data['day_period_type'] = $day_period_type;
		
		return $this->template->rander('Manufacturing\Views/manufacturing/settings/add_edit_working_hour', $data);
	}

	/**
	 * unit of measure modal
	 * @return [type] 
	 */
	public function unit_of_measure_modal()
	{
		$data=[];
		$data = $this->request->getPost();
		if($data['unit_id'] != 0){
			$data['unit_of_measure'] = $this->manufacturing_model->get_unit_of_measure($data['unit_id']);
		}

		$unit_types=[];
		$unit_types[] = [
			'id' => 'bigger',
			'value' => app_lang('bigger_than_the_reference_Unit_of_Measure'),
		];
		$unit_types[] = [
			'id' => 'reference',
			'value' => app_lang('reference_Unit_of_Measure_for_this_category'),
		];
		$unit_types[] = [
			'id' => 'smaller',
			'value' => app_lang('smaller_than_the_reference_Unit_of_Measure'),
		];
		$data['unit_types'] = $unit_types;

		$data['categories'] = $this->manufacturing_model->get_unit_categories();
		return $this->template->view('Manufacturing\Views/settings/unit_of_measure/add_edit_unit_of_measure_modal', $data);
	}

	/**
	 * delete unit of measure
	 * @param  [type] $id 
	 * @return [type]     
	 */
	public function delete_unit_of_measure()
	{
		$id = $this->request->getPost('id');
		if (!mrp_has_permission('manufacturing_can_delete')  && !is_admin()) {
			app_redirect('work_center');
		}

		$success = $this->manufacturing_model->delete_unit_of_measure($id);
		if ($success) {
			$this->session->setFlashdata("success_message", app_lang("mrp_deleted"));
		} else {
			$this->session->setFlashdata("error_message", app_lang("problem_deleting"));
		}
		app_redirect(('manufacturing/unit_of_measures'));
	}


	/**
	 * product table
	 * @return [type] 
	 */
	public function product_table()
	{
		$dataPost = $this->request->getPost();
		$this->manufacturing_model->get_table_data(module_views_path('Manufacturing', 'products/products/product_table'), $dataPost);
	}


	/**
	 * product management
	 * @param  string $id 
	 * @return [type]     
	 */
	public function product_management($id = '')
	{

		$data['title'] = app_lang('product_management');
		$data['commodity_filter'] = $this->manufacturing_model->get_product();
		$data['product_id'] = $id;
		$data['parent_products'] = $this->manufacturing_model->get_parent_product();
		$data['product_types'] = mrp_product_type();
		$data['product_categories'] = $this->manufacturing_model->mrp_get_item_group();

		$required_inventory_purchase = mrp_required_inventory_purchase_module();
		//required inventory purchase
		if($required_inventory_purchase['inventory'] == false || $required_inventory_purchase['purchase'] == false){
			return $this->template->rander('Manufacturing\Views/settings/required_inventory_module', $data);
		}else{
			return $this->template->rander("Manufacturing\Views/products/products/product_manage", $data);

		}

	}


	/**
	 * add edit product
	 * @param [type] $type : product or product variant
	 * @param string $id   
	 */
	public function add_edit_product($type, $id = '')
	{
		if (!mrp_has_permission('manufacturing_can_view_global')  && !is_admin()) {
			app_redirect('work_center');
		}
		
		if ($this->request->getPost()) {
			$data = $this->request->getPost();

			$target_path = get_setting("timeline_file_path");
			$files_data = move_files_from_temp_dir_to_permanent_dir($target_path, "item");
			$new_files = unserialize($files_data);

			if ($id) {
				$item_info = $this->Items_model->get_one($id);
				$timeline_file_path = get_setting("timeline_file_path");

				$new_files = update_saved_files($timeline_file_path, $item_info->files, $new_files);
			}
			$data["files"] = serialize($new_files);

			if ($id == '') {
				if (!mrp_has_permission('manufacturing_can_create') && !is_admin()) {
					app_redirect('work_center');
				}

				$result = $this->manufacturing_model->add_product($data, $type);
				$this->session->setFlashdata("success_message", app_lang("mrp_added_successfully"));

				if($type == 'product_variant'){
					app_redirect("manufacturing/product_variant_management");

				}else{
					app_redirect("manufacturing/product_management");
				}

				$this->session->setFlashdata("error_message", app_lang("mrp_added_failed"));

				die;

			} else {
				if (!mrp_has_permission('manufacturing_can_edit') && !is_admin()) {
					app_redirect('work_center');
				}
				$success = $this->manufacturing_model->update_product($data, $id, $type);
				/*update file*/
				$this->session->setFlashdata("success_message", app_lang("mrp_updated_successfully"));

				if($type == 'product_variant'){
					app_redirect("manufacturing/product_variant_management");
					
				}else{
					app_redirect("manufacturing/product_management");
				}
			}
		}
		
		$data=[];
		$data['title'] = app_lang('add_product');
		if ($id != ''){
			$data['product'] = $this->manufacturing_model->get_product($id);
			$data['product_attachments'] = $this->manufacturing_model->mrp_get_attachments_file($id, 'commodity_item_file');
			$data['title'] = app_lang('update_product');
		}

		$data['array_product_type'] = mrp_product_type();
		$data['type'] = $type;
		$data['product_group'] = $this->manufacturing_model->mrp_get_item_group();
		$data['units'] = $this->manufacturing_model->mrp_get_unit();
		$data['taxes'] = mrp_get_taxes();
		$data['model_info'] = $this->Items_model->get_one($id);

		return $this->template->rander('Manufacturing\Views/products/add_edit_product', $data);
	}


	/**
	 * check sku duplicate
	 * @return [type] 
	 */
	public function check_sku_duplicate()
	{
		$data = $this->request->getPost();
		$result = $this->manufacturing_model->check_sku_duplicate($data);

		echo json_encode([
			'message' => $result
		]);
		die;	
	}


    /**
     * add product attachment
     * @param [type] $id 
     */
    public function add_product_attachment($id, $rel_type, $add_variant='')
    {

    	mrp_handle_product_attachments($id);

    	if($rel_type == 'product_variant'){
    		$url = admin_url('manufacturing/product_variant_management');
    	}else{
    		$url = admin_url('manufacturing/product_management');
    	}

    	echo json_encode([
    		'url' => $url,
    		'id' => $id,
    		'rel_type' => $rel_type,
    		'add_variant' => $add_variant,
    	]);
    }


	/**
	 * delete product attachment
	 * @param  [type] $attachment_id 
	 * @param  [type] $rel_type      
	 * @return [type]                
	 */
	public function delete_product_attachment($attachment_id, $rel_type)
	{
		if (!mrp_has_permission('manufacturing_can_delete') && !is_admin()) {
			app_redirect('manufacturing');
		}

		$folder_name = '';

		switch ($rel_type) {
			case 'manufacturing':
			$folder_name = MANUFACTURING_PRODUCT_UPLOAD;
			break;
			case 'warehouse':
			$folder_name = module_dir_path('warehouse', 'uploads/item_img/');
			break;
			case 'purchase':
			$folder_name = module_dir_path('purchase', 'uploads/item_img/');
			break;
		}

		echo json_encode([
			'success' => $this->manufacturing_model->delete_mrp_attachment_file($attachment_id, $folder_name),
		]);
	}


	/**
	 * delete product
	 * @param  [type] $id       
	 * @param  [type] $rel_type 
	 * @return [type]           
	 */
	public function delete_product()
	{
		$id = $this->request->getPost('id');
		$rel_type = $this->request->getPost('id2');

		if (!$id) {
			app_redirect(('manufacturing/product_management'));
		}

		if(!mrp_has_permission('manufacturing_can_delete')  &&  !is_admin()) {
			app_redirect('manufacturing');
		}

		$response = $this->manufacturing_model->delete_product($id, $rel_type);
		if (is_array($response) && isset($response['referenced'])) {
			$this->session->setFlashdata("error_message", app_lang("is_referenced"));
		} elseif ($response == true) {
			$this->session->setFlashdata("success_message", app_lang("mrp_deleted"));
		} else {
			$this->session->setFlashdata("error_message", app_lang("problem_deleting"));
		}
		if($rel_type == 'product_variant'){
			app_redirect(('manufacturing/product_variant_management'));
		}else{
			app_redirect(('manufacturing/product_management'));
		}
	}


	/**
	 * product variant table
	 * @return [type] 
	 */
	public function product_variant_table()
	{
		$dataPost = $this->request->getPost();
		$this->manufacturing_model->get_table_data(module_views_path('Manufacturing', 'products/product_variants/product_variant_table'), $dataPost);
	}


	/**
	 * product variant management
	 * @param  string $id 
	 * @return [type]     
	 */
	public function product_variant_management($id = '')
	{

		$data['title'] = app_lang('product_variant_management');
		$data['commodity_filter'] = $this->manufacturing_model->get_product();
		$data['product_id'] = $id;
		$data['product_variants'] = $this->manufacturing_model->get_product_variant();
		$data['product_types'] = mrp_product_type();
		$data['product_categories'] = $this->manufacturing_model->mrp_get_item_group();
		
		$required_inventory_purchase = mrp_required_inventory_purchase_module();
		//required inventory purchase
		if($required_inventory_purchase['inventory'] == false || $required_inventory_purchase['purchase'] == false){
			return $this->template->rander('Manufacturing\Views/settings/required_inventory_module', $data);
		}else{
			return $this->template->rander('Manufacturing\Views/products/product_variants/product_variant_manage', $data);
		}
	}


	/**
	 * copy product image
	 * @param  [type] $id       
	 * @param  [type] $rel_type 
	 * @return [type]           
	 */
	public function copy_product_image($id, $rel_type)
	{

		$this->manufacturing_model->copy_product_image($id);
		if($rel_type == 'product_variant'){
			$url = admin_url('manufacturing/product_variant_management');
		}else{
			$url = admin_url('manufacturing/product_management');
		}

		echo json_encode([
			'url' => $url,
		]);
	}


    /**
     * bill of material manage
     * @return [type] 
     */
    public function bill_of_material_manage()
    {
    	if (!mrp_has_permission('manufacturing_can_view_global') ) {
    		app_redirect('work_center');
    	}

    	$data['title'] = app_lang('bill_of_material');
    	$data['products'] = $this->manufacturing_model->get_product();
    	$data['routings'] = $this->manufacturing_model->get_routings();
    	$bom_type=[];

    	$bom_type[] = [
    		'name' => 'kit',
    		'label' => app_lang('kit'),
    	];

    	$bom_type[] = [
    		'name' => 'manufacture_this_product',
    		'label' => app_lang('manufacture_this_product'),
    	];
    	$data['bom_types'] = $bom_type;

    	$required_inventory_purchase = mrp_required_inventory_purchase_module();
		//required inventory purchase
    	if($required_inventory_purchase['inventory'] == false || $required_inventory_purchase['purchase'] == false){
    		return $this->template->rander('Manufacturing\Views/settings/required_inventory_module', $data);
    	}else{
    		return $this->template->rander('Manufacturing\Views/bill_of_materials/bill_of_material_manage', $data);
    	}
    }


	/**
	 * bill of material table
	 * @return [type] 
	 */
	public function bill_of_material_table()
	{
		
		$dataPost = $this->request->getPost();
		$this->manufacturing_model->get_table_data(module_views_path('Manufacturing', 'bill_of_materials/bill_of_material_table'), $dataPost);
	}


	/**
	 * bill of material modal
	 * @return [type] 
	 */
	public function bill_of_material_modal()
	{
		
		$data=[];
		
		$ready_to_produce_type=[];
		$consumption_type=[];
		
		$ready_to_produce_type[] = [
			'name' => 'all_available',
			'label' => app_lang('when_all_components_are_available'),
		];

		$ready_to_produce_type[] = [
			'name' => 'components_for_1st',
			'label' => app_lang('when_components_for_1st_operation_are_available'),
		];

		$consumption_type[] = [
			'name' => 'strict',
			'label' => app_lang('strict'),
		];

		$consumption_type[] = [
			'name' => 'flexible',
			'label' => app_lang('flexible'),
		];

		

		$data['title'] = app_lang('bills_of_materials');
		$data['units'] = $this->manufacturing_model->mrp_get_unit();
		$data['ready_to_produce_type'] = $ready_to_produce_type;
		$data['consumption_type'] = $consumption_type;
		$data['routings'] = $this->manufacturing_model->get_routings();
		$data['parent_product'] = $this->manufacturing_model->get_parent_product();
		$data['bom_code'] = $this->manufacturing_model->create_code('bom_code');


		return $this->template->view('Manufacturing\Views/bill_of_materials/add_edit_bill_of_material_modal', $data);
	}


	/**
	 * add bill of material modal
	 * @param string $id 
	 */
	public function add_bill_of_material_modal($id='')
	{

		if (!mrp_has_permission('manufacturing_can_view_global')  && !is_admin()) {
			app_redirect('bill_of_material_label');
		}
		
		if ($this->request->getPost()) {
			$data = $this->request->getPost();

			if ($id == '') {
				if (!mrp_has_permission('manufacturing_can_create') && !is_admin()) {
					app_redirect('bill_of_material_label');
				}

				$id = $this->manufacturing_model->add_bill_of_material($data);
				if ($id) {
					$this->session->setFlashdata("success_message", app_lang("mrp_added_successfully"));
					app_redirect(('manufacturing/bill_of_material_detail_manage/'.$id));
				}

			} else {
				if (!mrp_has_permission('manufacturing_can_edit') && !is_admin()) {
					app_redirect('bill_of_material_label');
				}

				$response = $this->manufacturing_model->update_bill_of_material($data, $id);

				if ($response == true) {
					$this->session->setFlashdata("success_message", app_lang("mrp_updated_successfully"));
				}
				app_redirect(('manufacturing/bill_of_material_detail_manage/'.$id));
			}
		}
		
		$data=[];
		if ($id != ''){
			$working_hour = $this->manufacturing_model->get_working_hour($id);
			$data['working_hour'] = $working_hour['working_hour'];
			$data['working_hour_details'] = $working_hour['working_hour_details'];
			$data['time_off'] = $working_hour['time_off'];
			
		}
		

		$ready_to_produce_type=[];
		$consumption_type=[];
		
		$ready_to_produce_type[] = [
			'name' => 'all_available',
			'label' => app_lang('when_all_components_are_available'),
		];

		$ready_to_produce_type[] = [
			'name' => 'components_for_1st',
			'label' => app_lang('when_components_for_1st_operation_are_available'),
		];

		$consumption_type[] = [
			'name' => 'strict',
			'label' => app_lang('strict'),
		];

		$consumption_type[] = [
			'name' => 'flexible',
			'label' => app_lang('flexible'),
		];
		$data['units'] = $this->manufacturing_model->mrp_get_unit();
		$data['ready_to_produce_type'] = $ready_to_produce_type;
		$data['consumption_type'] = $consumption_type;
		$data['routings'] = $this->manufacturing_model->get_routings();
		$data['parent_product'] = $this->manufacturing_model->get_parent_product();

		
		return $this->template->rander('Manufacturing\Views/manufacturing/settings/add_edit_working_hour', $data);
	}

	/**
	 * delete bill of material
	 * @param  [type] $id 
	 * @return [type]     
	 */
	public function delete_bill_of_material()
	{
		$id = $this->request->getPost('id');
		if (!mrp_has_permission('manufacturing_can_delete')  && !is_admin()) {
			app_redirect('routing');
		}

		$success = $this->manufacturing_model->delete_bill_of_material($id);
		if ($success) {
			$this->session->setFlashdata("success_message", app_lang("mrp_deleted"));
		} else {
			$this->session->setFlashdata("error_message", app_lang("problem_deleting"));
		}
		app_redirect(('manufacturing/bill_of_material_manage'));
	}


	/**
	 * bill of material detail manage
	 * @param  string $id 
	 * @return [type]     
	 */
	public function bill_of_material_detail_manage($id='')
	{
		if (!mrp_has_permission('manufacturing_can_view_global') ) {
			app_redirect('bill_of_material_label');
		}

		$data['title'] = app_lang('bill_of_material_label');
		if($id != ''){
			$data['bill_of_material'] = $this->manufacturing_model->get_bill_of_materials($id);
		}
		
		$ready_to_produce_type=[];
		$consumption_type=[];
		
		$ready_to_produce_type[] = [
			'name' => 'all_available',
			'label' => app_lang('when_all_components_are_available'),
		];

		$ready_to_produce_type[] = [
			'name' => 'components_for_1st',
			'label' => app_lang('when_components_for_1st_operation_are_available'),
		];

		$consumption_type[] = [
			'name' => 'strict',
			'label' => app_lang('strict'),
		];

		$consumption_type[] = [
			'name' => 'flexible',
			'label' => app_lang('flexible'),
		];
		$data['units'] = $this->manufacturing_model->mrp_get_unit();
		$data['ready_to_produce_type'] = $ready_to_produce_type;
		$data['consumption_type'] = $consumption_type;
		$data['routings'] = $this->manufacturing_model->get_routings();
		$data['parent_product'] = $this->manufacturing_model->get_parent_product();
		$data['product_variant'] = $this->manufacturing_model->get_product_variant();

		return $this->template->rander('Manufacturing\Views/bill_of_materials/bill_of_material_details/bill_of_material_detail_manage', $data);
	}


	/**
	 * bill_of_material_detail table
	 * @return [type] 
	 */
	public function bill_of_material_detail_table()
	{
		
		$dataPost = $this->request->getPost();
		$this->manufacturing_model->get_table_data(module_views_path('Manufacturing', 'bill_of_materials/bill_of_material_details/bill_of_material_detail_table'), $dataPost);
	}


	/**
	 * bill of material detail modal
	 * @return [type] 
	 */
	public function bill_of_material_detail_modal()
	{

		$data=[];
		$data = $this->request->getPost();


		if($data['component_id'] != 0){
			$data['bill_of_material_detail'] = $this->manufacturing_model->get_bill_of_material_details($data['component_id']);
		}
		//get variant of product
		$data['arr_variants'] = $this->manufacturing_model->get_variant_attribute($data['bill_of_material_product_id']);
		//get operation of routing
		$data['arr_operations'] = $this->manufacturing_model->get_operation(false, $data['routing_id']);

		$data['products'] = $this->manufacturing_model->get_product();
		$data['product_variants'] = $this->manufacturing_model->get_product_variant();
		$data['units'] = $this->manufacturing_model->mrp_get_unit();

		return $this->template->view('Manufacturing\Views/bill_of_materials/bill_of_material_details/add_edit_bill_of_material_detail_modal', $data);
	}


	/**
	 * add edit bill of material detail
	 * @param string $id 
	 */
	public function add_edit_bill_of_material_detail($id='')
	{
		if (!mrp_has_permission('manufacturing_can_view_global')  && !is_admin()) {
			app_redirect('component');
		}
		
		if ($this->request->getPost()) {
			$data = $this->request->getPost();
			$bill_of_material_id = $data['bill_of_material_id'];

			if ($id == '') {
				if (!mrp_has_permission('manufacturing_can_create') && !is_admin()) {
					app_redirect('component');
				}

				$id = $this->manufacturing_model->add_bill_of_material_detail($data);
				if ($id) {

					$this->session->setFlashdata("success_message", app_lang("mrp_added_successfully"));
					app_redirect(('manufacturing/bill_of_material_detail_manage/'.$bill_of_material_id));
				}

			} else {
				if (!mrp_has_permission('manufacturing_can_edit') && !is_admin()) {
					app_redirect('component');
				}

				$response = $this->manufacturing_model->update_bill_of_material_detail($data, $id);

				if ($response == true) {
					$this->session->setFlashdata("success_message", app_lang("mrp_updated_successfully"));
				}
				app_redirect(('manufacturing/bill_of_material_detail_manage/'.$bill_of_material_id));

			}
		}

	}


	/**
	 * delete bill of material detail
	 * @param  [type] $id         
	 * @param  [type] $routing_id 
	 * @return [type]             
	 */
	public function delete_bill_of_material_detail()
	{
		$id = $this->request->getPost('id');
		$bill_of_material_id = $this->request->getPost('id2');

		if (!mrp_has_permission('manufacturing_can_delete')  && !is_admin()) {
			app_redirect('work_center');
		}

		$success = $this->manufacturing_model->delete_bill_of_material_detail($id);
		if ($success) {
			$this->session->setFlashdata("success_message", app_lang("mrp_deleted"));
		} else {
			$this->session->setFlashdata("error_message", app_lang("problem_deleting"));
		}
		app_redirect(('manufacturing/bill_of_material_detail_manage/'.$bill_of_material_id));


	}

	/**
	 * get product variants
	 * @param  [type] $id 
	 * @return [type]     
	 */
	public function get_product_variants($id)
	{
		$product_variants = $this->manufacturing_model->get_product_variants($id);
		$product = $this->manufacturing_model->get_product($id);
		if($product){
			$unit_id = $product->unit_id;
		}else{
			$unit_id = '';
		}

		echo json_encode([
			'product_variants' => $product_variants,
			'unit_id' => $unit_id,
		]);

	}


	/**
	 * manufacturing order manage
	 * @return [type] 
	 */
	public function manufacturing_order_manage()
	{
		if (!mrp_has_permission('manufacturing_can_view_global') ) {
			app_redirect('manufacturing_order');
		}

		
		$data['title'] = app_lang('manufacturing_order');
		$data['products'] = $this->manufacturing_model->get_product();
		$data['routings'] = $this->manufacturing_model->get_routings();
		$status_data=[];
		$status_data[]=[
			'name' => 'draft',
			'label' => app_lang('mrp_draft'),
		];
		$status_data[]=[
			'name' => 'planned',
			'label' => app_lang('mrp_planned'),
		];
		$status_data[]=[
			'name' => 'cancelled',
			'label' => app_lang('mrp_cancelled'),
		];
		$status_data[]=[
			'name' => 'confirmed',
			'label' => app_lang('mrp_confirmed'),
		];
		$status_data[]=[
			'name' => 'done',
			'label' => app_lang('mrp_done'),
		];
		$status_data[]=[
			'name' => 'in_progress',
			'label' => app_lang('mrp_in_progress'),
		];
		
		$data['status_data'] = $status_data;
		
		$required_inventory_purchase = mrp_required_inventory_purchase_module();
		//required inventory purchase
		if($required_inventory_purchase['inventory'] == false || $required_inventory_purchase['purchase'] == false){
			return $this->template->rander('Manufacturing\Views/settings/required_inventory_module', $data);
		}else{
			return $this->template->rander('Manufacturing\Views/manufacturing_orders/manufacturing_order_manage', $data);
		}
	}

	
	/**
	 * manufacturing order table
	 * @return [type] 
	 */
	public function manufacturing_order_table()
	{
		
		$dataPost = $this->request->getPost();
		$this->manufacturing_model->get_table_data(module_views_path('Manufacturing', 'manufacturing_orders/manufacturing_order_table'), $dataPost);
	}

	
	/**
	 * add edit manufacturing order
	 * @param string $id 
	 */
	public function add_edit_manufacturing_order($id = '')
	{
		if (!mrp_has_permission('manufacturing_can_view_global')  && !is_admin()) {
			app_redirect('manufacturing_order');
		}
		
		$Warehouse_model = model("Warehouse\Models\Warehouse_model");

		if ($this->request->getPost()) {
			$data = $this->request->getPost();

			if ($id == '') {
				if (!mrp_has_permission('manufacturing_can_create') && !is_admin()) {
					app_redirect('manufacturing_order');
				}

				$id = $this->manufacturing_model->add_manufacturing_order($data);
				if ($id) {
					$this->session->setFlashdata("success_message", app_lang("mrp_added_successfully"));
					app_redirect(('manufacturing/view_manufacturing_order/'.$id));
				}

			} else {
				if (!mrp_has_permission('manufacturing_can_edit') && !is_admin()) {
					app_redirect('manufacturing_order');
				}

				$response = $this->manufacturing_model->update_manufacturing_order($data, $id);

				if ($response == true) {
					$this->session->setFlashdata("success_message", app_lang("mrp_updated_successfully"));
				}
				app_redirect(('manufacturing/view_manufacturing_order/'.$id));
			}
		}
		
		$data=[];
		if ($id != ''){
			$data['title'] = app_lang('update_manufacturing_order_lable');
			$manufacturing_order = $this->manufacturing_model->get_manufacturing_order($id);
			$data['manufacturing_order'] = $manufacturing_order['manufacturing_order'];
			$data['product_tab_details'] = $manufacturing_order['manufacturing_order_detail'];
			$data['bill_of_materials'] = $this->manufacturing_model->get_list_bill_of_material_by_product($data['manufacturing_order']->product_id);

		}else{
			$data['title'] = app_lang('add_manufacturing_order_lable');
			$data['bill_of_materials'] = [];
			// $data['bill_of_materials'] = $this->manufacturing_model->get_bill_of_material_detail_with_product_name();
		}

		$data['products'] = $this->manufacturing_model->get_product();
		$data['units'] = $this->manufacturing_model->mrp_get_unit();
		$data['product_for_hansometable'] = $this->manufacturing_model->get_product_for_hansometable();
		$data['unit_for_hansometable'] = $this->manufacturing_model->get_unit_for_hansometable();
		$options = array(
			"status" => "active",
			"user_type" => "staff",
		);
		$data['staffs'] = $this->Users_model->get_details($options)->getResultArray();
		$data['warehouses'] = $Warehouse_model->get_warehouse();
		$data['mo_code'] = $this->manufacturing_model->create_code('mo_code');

		return $this->template->rander('Manufacturing\Views/manufacturing_orders/add_edit_manufacturing_order', $data);
	}


	/**
	 * delete manufacturing order
	 * @param  [type] $id 
	 * @return [type]     
	 */
	public function delete_manufacturing_order()
	{
		$id = $this->request->getPost('id');
		if (!mrp_has_permission('manufacturing_can_delete')  && !is_admin()) {
			app_redirect('manufacturing_order');
		}

		$success = $this->manufacturing_model->delete_manufacturing_order($id);
		if ($success) {
			$this->session->setFlashdata("success_message", app_lang("mrp_deleted"));
		} else {
			$this->session->setFlashdata("error_message", app_lang("problem_deleting"));
		}
		app_redirect(('manufacturing/manufacturing_order_manage'));
	}

	/**
	 * get data create manufacturing order
	 * @param  [type] $id 
	 * @return [type]     
	 */
	public function get_data_create_manufacturing_order($id)
	{

		$results = $this->manufacturing_model->get_data_create_manufacturing_order($id);

		echo json_encode([
			'bill_of_material_option' =>$results['bill_of_material_option'],
			'routing_id' => $results['routing_option'],
			'routing_name' => mrp_get_routing_name($results['routing_option']),
			'component_arr' => $results['component_arr'],
			'component_row' => $results['component_row'],
			'unit_id' => $results['unit_id'],
		]);
	}


	/**
	 * get bill of material detail
	 * @param  [type] $id 
	 * @return [type]     
	 */
	public function get_bill_of_material_detail($bill_of_material_id, $product_id, $product_qty='')
	{
	
		$component_arr=[];
		$routing_id=0;

		$product = $this->manufacturing_model->get_product($product_id);
		if($product){
			$component_arr = $this->manufacturing_model->get_bill_of_material_details_by_product($bill_of_material_id, $product->attributes, $product_qty);
		}

		$bill_of_material = $this->manufacturing_model->get_bill_of_materials($bill_of_material_id);
		if($bill_of_material){
			$routing_id = $bill_of_material->routing_id;
		}

		echo json_encode([
			'component_arr' => $component_arr,
			'component_row' => count($component_arr),
			'routing_id' => $routing_id,
			'routing_name' => mrp_get_routing_name($routing_id),
		]);
	}


	/**
	 * view manufacturing order
	 * @param  [type] $id 
	 * @return [type]     
	 */
	public function view_manufacturing_order($id)
	{
		if (!mrp_has_permission('manufacturing_can_view_global')  && !is_admin()) {
			app_redirect('manufacturing_order');
		}

		$manufacturing_order = $this->manufacturing_model->get_manufacturing_order($id);
		$data['manufacturing_order'] = $manufacturing_order['manufacturing_order'];
		$data['product_tab_details'] = $manufacturing_order['manufacturing_order_detail'];
		$data['product_for_hansometable'] = $this->manufacturing_model->get_product_for_hansometable();
		$data['unit_for_hansometable'] = $this->manufacturing_model->get_unit_for_hansometable();
		$data['manufacturing_order_costing'] = $this->manufacturing_model->get_manufacturing_order_costing($id);
		$check_manufacturing_order = $this->manufacturing_model->check_manufacturing_order_type($id);

		if($data['manufacturing_order']->status == 'confirmed'){
			$check_planned = $check_manufacturing_order['check_planned'];
		}else{
			$check_planned = false;
		}
		$data['check_planned'] = $check_planned;
		$data['check_mark_done'] = $check_manufacturing_order['check_mo_done'];
		$data['check_create_purchase_request'] = $check_manufacturing_order['check_create_purchase_request'];
		$data['check_availability'] = $check_manufacturing_order['check_availability'];
		$data['data_color'] = $check_manufacturing_order['data_color'];
		$data['title'] = app_lang('manufacturing_order_details');
		$data['currency'] = get_base_currency();

		//check pur order exist
		$pur_order_exist = false;
		if(is_numeric($data['manufacturing_order']->purchase_request_id)){
			$Purchase_model = model("Purchase\Models\Purchase_model");

			$get_purchase_request = $Purchase_model->get_purchase_request($data['manufacturing_order']->purchase_request_id);
			if($get_purchase_request){
				$pur_order_exist = true;
			}
		}
		
		$data['pur_order_exist'] = $pur_order_exist;
		if (!$data['manufacturing_order']) {
			blank_page(app_lang('manufacturing_order'), 'danger');
		}

		return $this->template->rander('Manufacturing\Views/manufacturing_orders/view_manufacturing_order', $data);
	}

	/**
	 * mo mark as todo
	 * @param  [type] $id 
	 * @return [type]     
	 */
	public function mo_mark_as_todo($id, $type)
	{
		//Check inventory quantity => create purchase request on work order


		if (!mrp_has_permission('manufacturing_can_create')  && !mrp_has_permission('manufacturing_can_edit')  && !is_admin()) {
			app_redirect('manufacturing_order');
		}

		$mo_mark_as_todo = $this->manufacturing_model->mo_mark_as_todo($id, $type);

		if($mo_mark_as_todo['status']){
			$status='success';
			$message = app_lang('mrp_updated_successfully');
		}else{
			$status='warning';
			$message = $mo_mark_as_todo['message'];
		}

		echo json_encode([
			'status' => $status,
			'message' => $message,
		]);
	}

	/**
	 * mo mark as todo
	 * @param  [type] $id 
	 * @return [type]     
	 */
	public function mo_mark_as_planned($id)
	{


		if (!mrp_has_permission('manufacturing_can_create')  && !mrp_has_permission('manufacturing_can_edit')  && !is_admin()) {
			app_redirect('manufacturing_order');
		}

		$mo_mark_as_planned = $this->manufacturing_model->mo_mark_as_planned($id);

		if($mo_mark_as_planned){
			$status='success';
			$message = app_lang('mrp_updated_successfully');
		}else{
			$status='warning';
			$message = app_lang('mrp_updated_failed');
		}

		echo json_encode([
			'status' => $status,
			'message' => $message,
		]);
	}

	/**
	 * work order manage
	 * @return [type] 
	 */
	public function work_order_manage()
	{
		if (!mrp_has_permission('manufacturing_can_view_global') ) {
			app_redirect('manufacturing_order');
		}

		
		$data['title'] = app_lang('manufacturing_order');
		$data['products'] = $this->manufacturing_model->get_product();
		$data['routings'] = $this->manufacturing_model->get_routings();
		$status_data=[];
		$status_data[]=[
			'name' => 'waiting_for_another_wo',
			'label' => app_lang('waiting_for_another_wo'),
		];
		$status_data[]=[
			'name' => 'ready',
			'label' => app_lang('ready'),
		];
		$status_data[]=[
			'name' => 'in_progress',
			'label' => app_lang('in_progress'),
		];
		$status_data[]=[
			'name' => 'finished',
			'label' => app_lang('finished'),
		];
		$status_data[]=[
			'name' => 'pause',
			'label' => app_lang('pause'),
		];
		
		$data['status_data'] = $status_data;
		$data['manufacturing_orders'] = $this->manufacturing_model->get_list_manufacturing_order();

		$required_inventory_purchase = mrp_required_inventory_purchase_module();
		//required inventory purchase
		if($required_inventory_purchase['inventory'] == false || $required_inventory_purchase['purchase'] == false){
			return $this->template->rander('Manufacturing\Views/settings/required_inventory_module', $data);
		}else{
			return $this->template->rander('Manufacturing\Views/work_orders/work_order_manage', $data);
		}
	}

	/**
	 * work order table
	 * @return [type] 
	 */
	public function work_order_table()
	{
		$dataPost = $this->request->getPost();
		$this->manufacturing_model->get_table_data(module_views_path('Manufacturing', 'work_orders/work_order_table'), $dataPost);
	}

	/**
	 * view work order
	 * @param  [type] $id 
	 * @return [type]     
	 */
	public function view_work_order($id, $manufacturing_order_id)
	{
		if (!mrp_has_permission('manufacturing_can_view_global') && !mrp_has_permission('manufacturing_can_create')  && !mrp_has_permission('manufacturing_can_edit')  && !is_admin()) {
			app_redirect('work_order_label');
		}

		$data=[];
		$data['work_order'] = $this->manufacturing_model->get_work_order($id);

		if (!$data['work_order']) {
			blank_page(app_lang('work_order_label'), 'danger');
		}
		$data['work_order_file'] = $this->manufacturing_model->mrp_get_attachments_file($data['work_order']->routing_detail_id, 'mrp_operation');
		$work_order_prev_next = $this->manufacturing_model->get_work_order_previous_next($id, $manufacturing_order_id);
		$data['prev_id'] = $work_order_prev_next['prev_id'];
		$data['next_id'] = $work_order_prev_next['next_id'];
		$data['pager_value'] = $work_order_prev_next['pager_value'];
		$data['pager_limit'] = $work_order_prev_next['pager_limit'];
		$data['manufacturing_order_id'] = $manufacturing_order_id;
		$data['header'] = app_lang('work_order_label').' / '.mrp_get_manufacturing_code($manufacturing_order_id).' - '.mrp_get_product_name($data['work_order']->product_id).' - '.$data['work_order']->operation_name;
		$time_tracking_details = $this->manufacturing_model->get_time_tracking_details($id);

		$data['time_tracking_details'] = $time_tracking_details['time_trackings'];
		$data['rows'] = $time_tracking_details['rows'];
		$mo = $this->manufacturing_model->get_manufacturing_order($manufacturing_order_id);
		$check_mo_cancelled= false;
		if($mo['manufacturing_order']){
			if($mo['manufacturing_order']->status == 'cancelled'){
				$check_mo_cancelled= true;
			}
		}
		$data['check_mo_cancelled'] = $check_mo_cancelled;

		return $this->template->rander('Manufacturing\Views/work_orders/view_work_order', $data);
	}

	/**
	 * mo mark as start working
	 * @param  [type] $work_order_id 
	 * @return [type]                
	 */
	public function mo_mark_as_start_working($work_order_id, $manufacturing_order)
	{


		if (!mrp_has_permission('manufacturing_can_create')  && !mrp_has_permission('manufacturing_can_edit')  && !is_admin()) {
			app_redirect('manufacturing_order');
		}

		$current_time=date('Y-m-d H:i:s');

		$mo_mark_as_start_working = $this->manufacturing_model->update_work_order_status($work_order_id, ['status' => 'in_progress', 'date_start' => to_sql_date($current_time, true)]);
		//update MO to in process
		$this->manufacturing_model->update_manufacturing_order_status($manufacturing_order, ['status' => 'in_progress']);
		
		//Add time tracking
		$data_tracking=[
			'work_order_id' => $work_order_id,
			'from_date' => $current_time,
			'staff_id' => get_staff_user_id(),
		];
		$this->manufacturing_model->add_time_tracking($data_tracking);


		if($mo_mark_as_start_working){
			$status='success';
			$message = app_lang('mrp_updated_successfully');
		}else{
			$status='warning';
			$message = app_lang('mrp_updated_failed');
		}

		echo json_encode([
			'status' => $status,
			'message' => $message,
		]);
	}

	/**
	 * mo mark as mark pause
	 * @param  [type] $work_order_id 
	 * @return [type]                
	 */
	public function mo_mark_as_mark_pause($work_order_id)
	{


		if (!mrp_has_permission('manufacturing_can_create')  && !mrp_has_permission('manufacturing_can_edit')  && !is_admin()) {
			app_redirect('manufacturing_order');
		}

		$mo_mark_as_start_working = $this->manufacturing_model->update_work_order_status($work_order_id, ['status' => 'pause']);

		$current_time=date('Y-m-d H:i:s');

		//Update time tracking
		$data_update=[
			'work_order_id' => $work_order_id,
			'to_date' => $current_time,
			'staff_id' => get_staff_user_id(),
		];
		$update_time_tracking = $this->manufacturing_model->update_time_tracking($work_order_id, $data_update);

		if($update_time_tracking){
			$status='success';
			$message = app_lang('mrp_updated_successfully');
		}else{
			$status='warning';
			$message = app_lang('mrp_updated_failed');
		}

		echo json_encode([
			'status' => $status,
			'message' => $message,
		]);
	}

	/**
	 * mo mark as mark done
	 * @param  [type] $work_order_id 
	 * @return [type]                
	 */
	public function mo_mark_as_mark_done($work_order_id, $manufacturing_order_id)
	{

		if (!mrp_has_permission('manufacturing_can_create')  && !mrp_has_permission('manufacturing_can_edit')  && !is_admin()) {
			app_redirect('manufacturing_order');
		}

		$wo_mark_as_done = $this->manufacturing_model->wo_mark_as_done($work_order_id, $manufacturing_order_id);

		if($wo_mark_as_done){
			$status='success';
			$message = app_lang('mrp_updated_successfully');
		}else{
			$status='warning';
			$message = app_lang('mrp_updated_failed');
		}

		echo json_encode([
			'status' => $status,
			'message' => $message,
		]);
	}
	
	/**
	 * mo work order manage
	 * @return [type] 
	 */
	public function mo_work_order_manage($mo_id='')
	{
		if (!mrp_has_permission('manufacturing_can_view_global') ) {
			app_redirect('manufacturing_order');
		}

		
		$data['title'] = app_lang('manufacturing_order');
		$data['products'] = $this->manufacturing_model->get_product();
		$data['routings'] = $this->manufacturing_model->get_routings();
		$status_data=[];
		$status_data[]=[
			'name' => 'draft',
			'label' => app_lang('mrp_draft'),
		];
		$status_data[]=[
			'name' => 'planned',
			'label' => app_lang('mrp_planned'),
		];
		$status_data[]=[
			'name' => 'cancelled',
			'label' => app_lang('mrp_cancelled'),
		];
		$status_data[]=[
			'name' => 'confirmed',
			'label' => app_lang('mrp_confirmed'),
		];
		$status_data[]=[
			'name' => 'done',
			'label' => app_lang('mrp_done'),
		];
		$status_data[]=[
			'name' => 'in_progress',
			'label' => app_lang('mrp_in_progress'),
		];
		
		$data['status_data'] = $status_data;
		$data['manufacturing_orders'] = $this->manufacturing_model->get_list_manufacturing_order();
		$data['mo_id'] = $mo_id;
		$data['data_timeline'] = $this->manufacturing_model->get_work_order_timeline($mo_id);

		return $this->template->rander('Manufacturing\Views/manufacturing_orders/mo_list_work_order', $data);
	}

	/**
	 * mo work order table
	 * @return [type] 
	 */
	public function mo_work_order_table()
	{
		
		$dataPost = $this->request->getPost();
		$this->manufacturing_model->get_table_data(module_views_path('Manufacturing', 'manufacturing_orders/mo_list_work_order_table'),$dataPost);
	}

	/**
	 * mo mark as done
	 * @param  [type] $id 
	 * @return [type]     
	 */
	public function mo_mark_as_done($id)
	{


		if (!mrp_has_permission('manufacturing_can_create')  && !mrp_has_permission('manufacturing_can_edit')  && !is_admin()) {
			app_redirect('manufacturing_order');
		}

		$mo_mark_as_done = $this->manufacturing_model->mo_mark_as_done($id);

		if($mo_mark_as_done){
			$status='success';
			$message = app_lang('mrp_updated_successfully');
		}else{
			$status='warning';
			$message = app_lang('mrp_updated_failed');
		}

		echo json_encode([
			'status' => $status,
			'message' => $message,
		]);
	}

	/**
	 * mo create purchase request
	 * @param  [type] $id 
	 * @return [type]     
	 */
	public function mo_create_purchase_request($id)
	{

		if (!mrp_has_permission('manufacturing_can_create')  && !mrp_has_permission('manufacturing_can_edit')  && !is_admin()) {
			app_redirect('manufacturing_order');
		}

		$purchase_request_id = $this->manufacturing_model->mo_create_purchase_request($id);

		if($purchase_request_id){
			//update Purchase request id to Manufacturing order
			$this->manufacturing_model->update_manufacturing_order_status($id, ['purchase_request_id' => $purchase_request_id]);

			$status='success';
			$message = app_lang('mrp_added_successfully');
		}else{
			$status='warning';
			$message = app_lang('mrp_added_failed');
		}

		echo json_encode([
			'status' => $status,
			'message' => $message,
		]);
	}

	/**
	 * mo mark as unreserved
	 * @param  [type] $id 
	 * @return [type]     
	 */
	public function mo_mark_as_unreserved($id)
	{


		if (!mrp_has_permission('manufacturing_can_create')  && !mrp_has_permission('manufacturing_can_edit')  && !is_admin()) {
			app_redirect('manufacturing_order');
		}

		$mo_mark_as_unreserved = $this->manufacturing_model->mo_mark_as_unreserved($id);

		if($mo_mark_as_unreserved){
			$status='success';
			$message = app_lang('mrp_updated_successfully');
		}else{
			$status='warning';
			$message = app_lang('mrp_updated_failed');
		}

		echo json_encode([
			'status' => $status,
			'message' => $message,
		]);
	}

	/**
	 * mo mark as cancel
	 * @param  [type] $id 
	 * @return [type]     
	 */
	public function mo_mark_as_cancel($id)
	{


		if (!mrp_has_permission('manufacturing_can_create')  && !mrp_has_permission('manufacturing_can_edit')  && !is_admin()) {
			app_redirect('manufacturing_order');
		}

		$mo_mark_as_cancel = $this->manufacturing_model->mo_mark_as_cancel($id);

		if($mo_mark_as_cancel){
			$status='success';
			$message = app_lang('mrp_updated_successfully');
		}else{
			$status='warning';
			$message = app_lang('mrp_updated_failed');
		}

		echo json_encode([
			'status' => $status,
			'message' => $message,
		]);
	}
	
	/**
	 * mrp product delete bulk action
	 * @return [type] 
	 */
	public function mrp_product_delete_bulk_action()
	{
		if (!mrp_has_permission('manufacturing_can_delete')) {
			app_redirect();
		}

		$total_deleted = 0;

		if ($this->request->getPost()) {

			$ids                   = $this->request->getPost('ids');
			$rel_type                   = $this->request->getPost('rel_type');

			/*check permission*/
			switch ($rel_type) {
				case 'commodity_list':
				if (!mrp_has_permission('manufacturing_can_delete') && !is_admin()) {
					app_redirect('product');
				}
				break;

				case 'bill_of_material':
				if (!mrp_has_permission('manufacturing_can_delete') && !is_admin()) {
					app_redirect('product');
				}
				break;

				case 'manufacturing_order':
				if (!mrp_has_permission('manufacturing_can_delete') && !is_admin()) {
					app_redirect('product');
				}
				break;

				case 'component_bill_of_material':
				if (!mrp_has_permission('manufacturing_can_delete') && !is_admin()) {
					app_redirect('product');
				}
				break;
				

				default:
				break;
			}

			/*delete data*/
			if ($this->request->getPost('mass_delete')) {
				if (is_array($ids)) {
					switch ($rel_type) {
						case 'commodity_list':
						foreach ($ids as $id) {
							if ($this->manufacturing_model->delete_product($id, 'product')) {
								$total_deleted++;
							}
						}

						break;

						case 'bill_of_material':
						$builder = db_connect('default');
						$builder = $builder->table('mrp_bill_of_material_details');
						$builder->where('bill_of_material_id IN ('.implode(",",$ids) .')');
						$affected_row = $builder->delete();
						$delete_bom_detail = $affected_row;

							//delete data
						$builder = db_connect('default');
						$builder = $builder->table('mrp_bill_of_materials');
						$builder->where('id IN ('.implode(",",$ids) .')');
						$affected_row = $builder->delete();
						$delete_bom = $affected_row;
						if ($delete_bom > 0) {
							$total_deleted += $delete_bom;
						}

						break;

						case 'manufacturing_order':
						foreach ($ids as $id) {
							if ($this->manufacturing_model->delete_manufacturing_order($id)) {
								$total_deleted++;
							}
						}

						break;

						case 'component_bill_of_material':
						$builder = db_connect('default');
						$builder = $builder->table('mrp_bill_of_material_details');
						$builder->where('id IN ('.implode(",",$ids) .')');
						$affected_row = $builder->delete();
						$delete_bom_detail = $affected_row;

						if ($delete_bom_detail > 0) {
							$total_deleted += $delete_bom_detail;
						}

						break;
						
						default:
							# code...
						break;
					}

				}

				/*return result*/
				switch ($rel_type) {
					case 'commodity_list':
					$this->session->setFlashdata("success_message", app_lang("total_product"). ": " .$total_deleted);
					break;

					case 'bill_of_material':
					$this->session->setFlashdata("success_message", app_lang("total_bill_of_material"). ": " .$total_deleted);
					break;
					
					case 'manufacturing_order':
					$this->session->setFlashdata("success_message", app_lang("total_manufacturing_order"). ": " .$total_deleted);
					break;

					case 'component_bill_of_material':
					$this->session->setFlashdata("success_message", app_lang("total_component_bill_of_material"). ": " .$total_deleted);
					break;
					

					default:
					break;

				}


			}

		}

	}

	function download_barcode() {
		$mode = "download";
		$select_item = $this->request->getPost('select_item');
		$item_select_print_barcode = $this->request->getPost('item_select_print_barcode');

		/*Making data*/
		$goods_receipt_data = [];
		$goods_receipt_data['select_item'] = (int)$select_item;
		$goods_receipt_data['item_select_print_barcode'] = $item_select_print_barcode;

		mrp_prepare_barcode_pdf($goods_receipt_data, $mode);
	}

	/**
	 * item print barcode
	 * @return [type] 
	 */
	public function item_print_barcode()
	{
		$data = $this->request->getPost();

		$stock_export = $this->manufacturing_model->get_print_barcode_pdf_html($data);
		
		try {
			$pdf = $this->manufacturing_model->print_barcode_pdf($stock_export);

		} catch (Exception $e) {
			echo html_entity_decode($e->getMessage());
			die;
		}

		$type = 'I';

		if ($this->request->getGet('output_type')) {
			$type = $this->request->getGet('output_type');
		}

		if ($this->request->getGet('print')) {
			$type = 'I';
		}


		$pdf->Output('print_barcode_'.strtotime(date('Y-m-d H:i:s')).'.pdf', $type);

	}

	/**
	 * dashboard
	 * @return [type] 
	 */
	public function dashboard()
	{
		if (!mrp_has_permission('manufacturing_can_view_global')  && !is_admin()) {
			app_redirect('dashboard');
		}

		$data['title'] = app_lang('dashboard');
		$data['work_centers'] = $this->manufacturing_model->dasboard_get_work_center();

		$mo_measures_type=[];
		
		$mo_measures_type[]=[
			'name' => 'count',
			'label' => app_lang('count'),
		];
		$mo_measures_type[]=[
			'name' => 'total_qty',
			'label' => app_lang('total_qty'),
		];

		$wo_measures_type=[];
		
		$wo_measures_type[]=[
			'name' => 'count',
			'label' => app_lang('count'),
		];
		
		$wo_measures_type[]=[
			'name' => 'duration_per_unit',
			'label' => app_lang('duration_per_unit'),
		];
		$wo_measures_type[]=[
			'name' => 'expected_duration',
			'label' => app_lang('expected_duration'),
		];
		$wo_measures_type[]=[
			'name' => 'quantity',
			'label' => app_lang('quantity'),
		];
		$wo_measures_type[]=[
			'name' => 'real_duration',
			'label' => app_lang('real_duration'),
		];
		
		$data['mo_measures_type'] = $mo_measures_type;
		$data['wo_measures_type'] = $wo_measures_type;

		$required_inventory_purchase = mrp_required_inventory_purchase_module();
		//required inventory purchase
		if($required_inventory_purchase['inventory'] == false || $required_inventory_purchase['purchase'] == false){
			return $this->template->rander('Manufacturing\Views/settings/required_inventory_module', $data);
		}else{
			return $this->template->rander('Manufacturing\Views\dashboards\dashboard', $data);
		}

	}

	/**
	 * report by manufacturing order
	 * @param  [type] $sort_from     
	 * @param  string $months_report 
	 * @param  string $report_from   
	 * @param  string $report_to     
	 * @return [type]                
	 */
	public function report_by_manufacturing_order()
	{
		$data = $this->request->getGet();

		$mo_measures = $data['mo_measures'];
		$months_report = $data['months_report'];
		$report_from = $data['report_from'];
		$report_to = $data['report_to'];

		if($months_report == ''){

			$from_date = date('Y-m-d', strtotime('1997-01-01'));
			$to_date = date('Y-m-d', strtotime(date('Y-12-31')));
		}

		if($months_report == 'this_month'){
			$from_date = date('Y-m-01');
			$to_date   = date('Y-m-t');
		}

		if($months_report == '1'){ 
			$from_date = date('Y-m-01', strtotime('first day of last month'));
			$to_date   = date('Y-m-t', strtotime('last day of last month'));
		}

		if($months_report == 'this_year'){
			$from_date = date('Y-m-d', strtotime(date('Y-01-01')));
			$to_date = date('Y-m-d', strtotime(date('Y-12-31')));
		}

		if($months_report == 'last_year'){

			$from_date = date('Y-m-d', strtotime(date(date('Y', strtotime('last year')) . '-01-01')));
			$to_date = date('Y-m-d', strtotime(date(date('Y', strtotime('last year')) . '-12-31')));  


		}

		if($months_report == '3'){
			$months_report = 3;
			$months_report--;
			$from_date = date('Y-m-01', strtotime("-$months_report MONTH"));
			$to_date   = date('Y-m-t');

		}

		if($months_report == '6'){
			$months_report = 6;
			$months_report--;
			$from_date = date('Y-m-01', strtotime("-$months_report MONTH"));
			$to_date   = date('Y-m-t');
		}

		if($months_report == '12'){
			$months_report = 12;
			$months_report--;
			$from_date = date('Y-m-01', strtotime("-$months_report MONTH"));
			$to_date   = date('Y-m-t');
		}

		if($months_report == 'custom'){
			$from_date = to_sql_date($report_from);
			$to_date   = to_sql_date($report_to);
		}

		$mo_data = $this->manufacturing_model->get_mo_report_data($mo_measures, $from_date, $to_date);


		echo json_encode([
			'categories' => $mo_data['categories'],
			'draft' => $mo_data['draft'],
			'planned' => $mo_data['planned'],
			'cancelled' => $mo_data['cancelled'],
			'confirmed' => $mo_data['confirmed'],
			'done' => $mo_data['done'],
			'in_progress' => $mo_data['in_progress'],
		]); 
	}

	/**
	 * report by work order
	 * @return [type] 
	 */
	public function report_by_work_order()
	{
		$data = $this->request->getGet();

		$mo_measures = $data['wo_measures'];
		$months_report = $data['months_report'];
		$report_from = $data['report_from'];
		$report_to = $data['report_to'];

		if($months_report == ''){

			$from_date = date('Y-m-d', strtotime('1997-01-01'));
			$to_date = date('Y-m-d', strtotime(date('Y-12-31')));
		}

		if($months_report == 'this_month'){
			$from_date = date('Y-m-01');
			$to_date   = date('Y-m-t');
		}

		if($months_report == '1'){ 
			$from_date = date('Y-m-01', strtotime('first day of last month'));
			$to_date   = date('Y-m-t', strtotime('last day of last month'));
		}

		if($months_report == 'this_year'){
			$from_date = date('Y-m-d', strtotime(date('Y-01-01')));
			$to_date = date('Y-m-d', strtotime(date('Y-12-31')));
		}

		if($months_report == 'last_year'){

			$from_date = date('Y-m-d', strtotime(date(date('Y', strtotime('last year')) . '-01-01')));
			$to_date = date('Y-m-d', strtotime(date(date('Y', strtotime('last year')) . '-12-31')));  


		}

		if($months_report == '3'){
			$months_report = 3;
			$months_report--;
			$from_date = date('Y-m-01', strtotime("-$months_report MONTH"));
			$to_date   = date('Y-m-t');

		}

		if($months_report == '6'){
			$months_report = 6;
			$months_report--;
			$from_date = date('Y-m-01', strtotime("-$months_report MONTH"));
			$to_date   = date('Y-m-t');
		}

		if($months_report == '12'){
			$months_report = 12;
			$months_report--;
			$from_date = date('Y-m-01', strtotime("-$months_report MONTH"));
			$to_date   = date('Y-m-t');
		}

		if($months_report == 'custom'){
			$from_date = to_sql_date($report_from);
			$to_date   = to_sql_date($report_to);
		}

		$mo_data = $this->manufacturing_model->get_wo_report_data($mo_measures, $from_date, $to_date);


		echo json_encode([
			'categories' => $mo_data['categories'],
			'mo_data' => $mo_data['mo_data'],

		]); 
	}

	/**
	 * prefix numbers
	 * @return [type] 
	 */
	public function prefix_numbers() {
		$data= [];

		$required_inventory_purchase = mrp_required_inventory_purchase_module();
		//required inventory purchase
		if($required_inventory_purchase['inventory'] == false || $required_inventory_purchase['purchase'] == false){
			return $this->template->rander('Manufacturing\Views/settings/required_inventory_module', $data);
		}else{
			return $this->template->rander("Manufacturing\Views\settings\prefix_number", $data);
		}
	}

	/**
	 * prefix number
	 * @return [type] 
	 */
	public function prefix_number()
	{
		if (!mrp_has_permission('manufacturing_can_edit') && !is_admin() && !mrp_has_permission('manufacturing_can_create')) {
			app_redirect('manufacturing');
		}

		$data = $this->request->getPost();

		if ($data) {

			$success = $this->manufacturing_model->update_prefix_number($data);

			if ($success == true) {
				$this->session->setFlashdata("success_message", app_lang("mrp_updated_successfully"));
			}
			app_redirect(('manufacturing/prefix_numbers'));
		}
	}

	public function view_product_detail($product_id) {
		$commodity_item = get_commodity_name($product_id);

		if (!$commodity_item) {
			blank_page('Product item Not Found', 'danger');
		}
		$Warehouse_model = model("Warehouse\Models\Warehouse_model");

		//user for sub
		$data['units'] = $Warehouse_model->get_unit_add_commodity();
		$data['commodity_types'] = $Warehouse_model->get_commodity_type_add_commodity();
		$data['commodity_groups'] = $Warehouse_model->get_commodity_group_add_commodity();
		$data['warehouses'] = $Warehouse_model->get_warehouse_add_commodity();
		$tax_options = array(
			"deleted" => 0,
		);
		$data['taxes'] = $this->Taxes_model->get_details($tax_options)->getResultArray();
		$data['styles'] = $Warehouse_model->get_style_add_commodity();
		$data['models'] = $Warehouse_model->get_body_add_commodity();
		$data['sizes'] = $Warehouse_model->get_size_add_commodity();
		$data['sub_groups'] = $Warehouse_model->get_sub_group();
		$data['colors'] = $Warehouse_model->get_color_add_commodity();
		$data['commodity_filter'] = $Warehouse_model->get_commodity_active();
		$data['title'] = app_lang("item_detail");
		$model_info = $this->Items_model->get_details(array("id" => $product_id, "login_user_id" => $this->login_user->id))->getRow();
        $data['model_info'] = $model_info;

		$data['commodity_item'] = $commodity_item;

		return $this->template->rander('Manufacturing\Views/products/view_product_detail', $data);

	}

	public function table_commodity_list() {
		
		$dataPost = $this->request->getPost();
		$this->manufacturing_model->get_table_data(module_views_path('Manufacturing', 'products/view_table_product_detail'), $dataPost);
	}

	/**
	 * confirm delete modal form
	 * @return [type] 
	 */
	public function confirm_delete_modal_form() {
		$this->access_only_team_members();

		$this->validate_submitted_data(array(
			"id" => "numeric"
		));

		if($this->request->getPost('id')){
			$data['function'] = $this->request->getPost('function');
			$data['id'] = $this->request->getPost('id');
			$data['id2'] = $this->request->getPost('id2');
			return $this->template->view('Manufacturing\Views\settings\confirm_delete_modal_form', $data);
		}
	}

	/*ROLE for module Start*/

	//load the role view
	function roles() {
		return $this->template->rander("Manufacturing\Views/roles/index");
	}

    //load the role add/edit modal
	function role_modal_form() {

		$this->validate_submitted_data(array(
			"id" => "numeric"
		));

		$view_data['model_info'] = $this->Roles_model->get_one($this->request->getPost('id'));
		$view_data['roles_dropdown'] = array("" => "-") + $this->Roles_model->get_dropdown_list(array("title"), "id");
		return $this->template->view('Manufacturing\Views\roles/modal_form', $view_data);
	}

    //get permisissions of a role
	function role_permissions($role_id) {
		if ($role_id) {
			validate_numeric_value($role_id);
			$view_data['model_info'] = $this->Roles_model->get_one($role_id);

			$permissions = unserialize($view_data['model_info']->plugins_permissions1);

			if (!$permissions) {
				$permissions = array();
			}

			$view_data['manufacturing_can_view_global'] = get_array_value($permissions, "manufacturing_can_view_global");
			$view_data['manufacturing_can_create'] = get_array_value($permissions, "manufacturing_can_create");
			$view_data['manufacturing_can_edit'] = get_array_value($permissions, "manufacturing_can_edit");
			$view_data['manufacturing_can_delete'] = get_array_value($permissions, "manufacturing_can_delete");

			$view_data['permissions'] = $permissions;

			return $this->template->view("Manufacturing\Views/roles/permissions", $view_data);
		}
	}

    //save a role
	function role_save() {
		$this->validate_submitted_data(array(
			"id" => "numeric",
			"title" => "required"
		));

		$id = $this->request->getPost('id');
		$copy_settings = $this->request->getPost('copy_settings');
		$data = array(
			"title" => $this->request->getPost('title'),
		);

		if ($copy_settings) {
			$role = $this->Roles_model->get_one($copy_settings);
			$data["permissions"] = $role->plugins_permissions1;
		}

		$save_id = $this->Roles_model->ci_save($data, $id);
		if ($save_id) {
			echo json_encode(array("success" => true, "data" => $this->role_row_data($save_id), 'id' => $save_id, 'message' => app_lang('record_saved')));
		} else {
			echo json_encode(array("success" => false, 'message' => app_lang('error_occurred')));
		}
	}

    //save permissions of a role
	function role_save_permissions() {
		$this->validate_submitted_data(array(
			"id" => "numeric|required"
		));

		$id = $this->request->getPost('id');
		$data = $this->request->getPost();
		$permissions = [];

		$permissions['manufacturing_can_view_global'] = isset($data['manufacturing_can_view_global']) ? $data['manufacturing_can_view_global'] : NULL;
		$permissions['manufacturing_can_create'] = isset($data['manufacturing_can_create']) ? $data['manufacturing_can_create'] : NULL;
		$permissions['manufacturing_can_edit'] = isset($data['manufacturing_can_edit']) ? $data['manufacturing_can_edit'] : NULL;
		$permissions['manufacturing_can_delete'] = isset($data['manufacturing_can_delete']) ? $data['manufacturing_can_delete'] : NULL;

        

		$options = array("id" => $id);
		$data_role = $this->Roles_model->get_details($options)->getRow();
		$old_role_permissions = is_array(unserialize($data_role->plugins_permissions1)) ? unserialize($data_role->plugins_permissions1) : array();

        $permissions = app_hooks()->apply_filters('app_filter_role_permissions_save_data_plugin', $permissions, $this->request->getPost());

        foreach ($permissions as $key => $permission) {
        	$old_role_permissions[$key] = $permission;
        }

		$data = array(
			"plugins_permissions1" => serialize($old_role_permissions),
		);

		$save_id = $this->Roles_model->ci_save($data, $id);
		if ($save_id) {
			echo json_encode(array("success" => true, "data" => $this->role_row_data($id), 'id' => $save_id, 'message' => app_lang('record_saved')));
		} else {
			echo json_encode(array("success" => false, 'message' => app_lang('error_occurred')));
		}
	}

    //get role list data
	function role_list_data() {
		$list_data = $this->Roles_model->get_details()->getResult();
		$result = array();
		foreach ($list_data as $data) {
			$result[] = $this->role_make_row($data);
		}
		echo json_encode(array("data" => $result));
	}

    //get a row of role list
	private function role_row_data($id) {
		$options = array("id" => $id);
		$data = $this->Roles_model->get_details($options)->getRow();
		return $this->role_make_row($data);
	}

    //make a row of role list table
	private function role_make_row($data) {
		return array("<a href='#' data-id='$data->id' class='role-row link'>" . $data->title . "</a>",
			"<a class='edit'><i data-feather='sliders' class='icon-16'></i></a>"
		);
	}

	/*ROLE for module End*/

	/**
	 * operation file
	 * @param  [type] $id     
	 * @param  [type] $rel_id 
	 * @return [type]         
	 */
	public function operation_file($id, $rel_id) {
		$data['discussion_user_profile_image_url'] = get_staff_image(get_staff_user_id1());
		$data['current_user_is_admin'] = is_admin();
		$data['file'] = $this->manufacturing_model->get_file($id, $rel_id);
		if (!$data['file']) {
			header('HTTP/1.0 404 Not Found');
			die;
		}
		return $this->template->view('Manufacturing\Views/routings/routing_details/_file', $data);
	}

	/**
	 * unit of measure category table
	 * @return [type] 
	 */
	public function unit_of_measure_category_table()
	{
		
		$dataPost = $this->request->getPost();
		$this->manufacturing_model->get_table_data(module_views_path('Manufacturing', 'settings/unit_of_measure_categories/unit_of_measure_category_table'),$dataPost);
	}

//end file
}
