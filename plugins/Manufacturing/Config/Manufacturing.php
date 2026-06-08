<?php

namespace Manufacturing\Config;

use CodeIgniter\Config\BaseConfig;
use Manufacturing\Models\Manufacturing_model;

class Manufacturing extends BaseConfig {

	public $app_settings_array = array(
		"manufacturing_file_path" => PLUGIN_URL_PATH . "Manufacturing/files/manufacturing_files/"
	);

	public function __construct() {
		
	}

}
