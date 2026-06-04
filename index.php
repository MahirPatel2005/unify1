<?php

if (isset($_GET['debug_speed'])) {
    header('Content-Type: text/plain');
    $host = getenv('DB_HOST');
    $user = getenv('DB_USER');
    $pass = getenv('DB_PASSWORD');
    $db   = getenv('DB_NAME') ?: 'defaultdb';
    $port = (int)(getenv('DB_PORT') ?: 3306);
    $ca   = getenv('DB_SSL_CA');
    
    echo "Starting Render Speed Diagnostics...\n\n";
    
    // 1. Measure DNS resolution time
    $start = microtime(true);
    $ip = gethostbyname($host);
    $dns_time = microtime(true) - $start;
    echo "1. DNS resolution for $host:\n";
    echo "   Resolved IP: $ip\n";
    echo "   Took: " . round($dns_time, 4) . " seconds\n\n";
    
    // 2. Measure TCP connection time
    $start = microtime(true);
    $fp = @fsockopen($ip, $port, $errno, $errstr, 5);
    $tcp_time = microtime(true) - $start;
    echo "2. TCP Socket Connection to $ip:$port:\n";
    if ($fp) {
        echo "   Took: " . round($tcp_time, 4) . " seconds\n\n";
        fclose($fp);
    } else {
        echo "   Failed: $errstr ($errno) (took " . round($tcp_time, 4) . " seconds)\n\n";
    }
    
    // 3. Measure mysqli connection with SSL
    $start = microtime(true);
    $mysqli = mysqli_init();
    if ($ca && file_exists($ca)) {
        $mysqli->ssl_set(NULL, NULL, $ca, NULL, NULL);
    }
    $conn_ok = @$mysqli->real_connect($ip, $user, $pass, $db, $port, NULL, $ca && file_exists($ca) ? MYSQLI_CLIENT_SSL : 0);
    $conn_time = microtime(true) - $start;
    echo "3. MySQL real_connect with SSL:\n";
    if ($conn_ok) {
        echo "   Took: " . round($conn_time, 4) . " seconds\n\n";
        
        // 4. Measure simple query time
        $start = microtime(true);
        $res = $mysqli->query("SELECT 1");
        $query_time = microtime(true) - $start;
        echo "4. Simple query (SELECT 1):\n";
        echo "   Took: " . round($query_time, 4) . " seconds\n\n";
        
        $mysqli->close();
    } else {
        echo "   Failed: $mysqli->connect_error (took " . round($conn_time, 4) . " seconds)\n\n";
    }
    
    echo "Diagnostics completed.\n";
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
    if (getenv('CI_ENVIRONMENT') !== 'production') {
        echo "Uncaught Exception: " . $e->getMessage() . "\n";
        echo "File: " . $e->getFile() . " on line " . $e->getLine() . "\n";
        echo "Stack Trace:\n" . $e->getTraceAsString() . "\n";
    } else {
        echo "An unexpected error occurred. Please try again later.\n";
    }
    exit(1);
}
