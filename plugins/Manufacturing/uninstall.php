<?php
$db = db_connect('default');
$dbprefix = get_db_prefix();

if ($db->tableExists($dbprefix . 'mrp_work_centers')) {
    $db->query('DROP TABLE `'.$dbprefix .'mrp_work_centers`;');
}
if ($db->tableExists($dbprefix . 'mrp_routings')) {
    $db->query('DROP TABLE `'.$dbprefix .'mrp_routings`;');
}
if ($db->tableExists($dbprefix . 'mrp_routing_details')) {
    $db->query('DROP TABLE `'.$dbprefix .'mrp_routing_details`;');
}
if ($db->tableExists($dbprefix . 'mrp_working_hours')) {
    $db->query('DROP TABLE `'.$dbprefix .'mrp_working_hours`;');
}
if ($db->tableExists($dbprefix . 'mrp_working_hour_times')) {
    $db->query('DROP TABLE `'.$dbprefix .'mrp_working_hour_times`;');
}
if ($db->tableExists($dbprefix . 'mrp_working_hour_time_off')) {
    $db->query('DROP TABLE `'.$dbprefix .'mrp_working_hour_time_off`;');
}
if ($db->tableExists($dbprefix . 'mrp_unit_measure_categories')) {
    $db->query('DROP TABLE `'.$dbprefix .'mrp_unit_measure_categories`;');
}
if ($db->tableExists($dbprefix . 'mrp_bill_of_materials')) {
    $db->query('DROP TABLE `'.$dbprefix .'mrp_bill_of_materials`;');
}
if ($db->tableExists($dbprefix . 'mrp_bill_of_material_details')) {
    $db->query('DROP TABLE `'.$dbprefix .'mrp_bill_of_material_details`;');
}
if ($db->tableExists($dbprefix . 'mrp_manufacturing_orders')) {
    $db->query('DROP TABLE `'.$dbprefix .'mrp_manufacturing_orders`;');
}
if ($db->tableExists($dbprefix . 'mrp_manufacturing_order_details')) {
    $db->query('DROP TABLE `'.$dbprefix .'mrp_manufacturing_order_details`;');
}
if ($db->tableExists($dbprefix . 'mrp_work_orders')) {
    $db->query('DROP TABLE `'.$dbprefix .'mrp_work_orders`;');
}
if ($db->tableExists($dbprefix . 'mrp_work_order_details')) {
    $db->query('DROP TABLE `'.$dbprefix .'mrp_work_order_details`;');
}
if ($db->tableExists($dbprefix . 'mrp_work_order_time_trackings')) {
    $db->query('DROP TABLE `'.$dbprefix .'mrp_work_order_time_trackings`;');
}
