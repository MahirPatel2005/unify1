<?php

namespace Config;

$routes = Services::routes();

$routes->get('manufacturing', 'Manufacturing::index', ['namespace' => 'Manufacturing\Controllers']);
$routes->get('manufacturing/(:any)', 'Manufacturing::$1', ['namespace' => 'Manufacturing\Controllers']);

$routes->post('manufacturing/(:any)', 'Manufacturing::$1', ['namespace' => 'Manufacturing\Controllers']);


