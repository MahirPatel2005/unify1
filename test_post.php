<?php
$_SERVER['HTTP_HOST'] = '127.0.0.1:8001';
$_SERVER['REQUEST_METHOD'] = 'POST';
$_SERVER['REQUEST_URI'] = '/index.php/plugins/save';
$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['SCRIPT_FILENAME'] = __DIR__ . '/index.php';

$_POST['file_name'] = 'inventory-management-plugin-for-mingrow-crm-1.0.0.zip';

// Set CI_ENVIRONMENT to development to see the full stack trace
putenv('CI_ENVIRONMENT=development');
$_ENV['CI_ENVIRONMENT'] = 'development';

require 'index.php';
