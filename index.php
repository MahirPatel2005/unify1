<?php

if (isset($_GET['debug_deploy'])) {
    header('Content-Type: text/plain');
    echo "CI_ENVIRONMENT: " . var_export(getenv('CI_ENVIRONMENT'), true) . "\n";
    echo "DB_HOST: " . var_export(getenv('DB_HOST'), true) . "\n";
    echo "DB_PORT: " . var_export(getenv('DB_PORT'), true) . "\n";
    echo "DB_USER: " . var_export(getenv('DB_USER'), true) . "\n";
    echo "DB_NAME: " . var_export(getenv('DB_NAME'), true) . "\n";
    echo "DB_SSL_CA: " . var_export(getenv('DB_SSL_CA'), true) . " (exists: " . var_export(file_exists(getenv('DB_SSL_CA')), true) . ")\n";
    
    $mysqli = mysqli_init();
    $ca = getenv('DB_SSL_CA');
    if ($ca && file_exists($ca)) {
        $mysqli->ssl_set(NULL, NULL, $ca, NULL, NULL);
    }
    $host = getenv('DB_HOST');
    $user = getenv('DB_USER');
    $pass = getenv('DB_PASSWORD');
    $db = getenv('DB_NAME') ?: 'defaultdb';
    $port = (int)(getenv('DB_PORT') ?: 3306);
    
    echo "Connecting to $host:$port...\n";
    if (@$mysqli->real_connect($host, $user, $pass, $db, $port, NULL, $ca && file_exists($ca) ? MYSQLI_CLIENT_SSL : 0)) {
        echo "Successfully connected to DB!\n";
        $mysqli->close();
    } else {
        echo "Connection failed: " . mysqli_connect_error() . "\n";
    }
    exit;
}

/*
 *---------------------------------------------------------------
 * CHECK PHP VERSION
 *---------------------------------------------------------------
 */

$minPhpVersion = '8.1'; // If you update this, don't forget to update `spark`.
if (version_compare(PHP_VERSION, $minPhpVersion, '<')) {
    $message = sprintf(
        'Your PHP version must be %s or higher to run CodeIgniter. Current version: %s',
        $minPhpVersion,
        PHP_VERSION
    );

    header('HTTP/1.1 503 Service Unavailable.', true, 503);
    echo $message;

    exit(1);
}


//set the variable to 'installed' after installation
$app_state = "installed";

// we don't want to access the main project before installation. redirect to installation page
if ($app_state === 'pre_installation') {
    $domain = $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'];

    $domain = preg_replace('/index.php.*/', '', $domain); //remove everything after index.php
    if (!empty($_SERVER['HTTPS'])) {
        $domain = 'https://' . $domain;
    } else {
        $domain = 'http://' . $domain;
    }

    header("Location: $domain./install/index.php");
    exit;
}

/*
 *---------------------------------------------------------------
 * SET THE CURRENT DIRECTORY
 *---------------------------------------------------------------
 */

// Path to the front controller (this file)
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);

// Ensure the current directory is pointing to the front controller's directory
if (getcwd() . DIRECTORY_SEPARATOR !== FCPATH) {
    chdir(FCPATH);
}

/*
 *---------------------------------------------------------------
 * BOOTSTRAP THE APPLICATION
 *---------------------------------------------------------------
 * This process sets up the path constants, loads and registers
 * our autoloader, along with Composer's, loads our constants
 * and fires up an environment-specific bootstrapping.
 */

// LOAD OUR PATHS CONFIG FILE
// This is the line that might need to be changed, depending on your folder structure.
require FCPATH . 'app/Config/Paths.php';
// ^^^ Change this line if you move your application folder

try {
    $paths = new Config\Paths();

    // LOAD THE FRAMEWORK BOOTSTRAP FILE
    require $paths->systemDirectory . '/Boot.php';

    exit(CodeIgniter\Boot::bootWeb($paths));
} catch (\Throwable $e) {
    header('Content-Type: text/plain', true, 500);
    echo "Uncaught Exception: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " on line " . $e->getLine() . "\n";
    echo "Stack Trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}
