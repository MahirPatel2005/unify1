<?php

namespace Config;

use CodeIgniter\Database\Config;

/**
 * Database Configuration
 */
class Database extends Config
{
	/**
	 * The directory that holds the Migrations
	 * and Seeds directories.
	 *
	 * @var string
	 */
	public $filesPath = APPPATH . 'Database' . DIRECTORY_SEPARATOR;

	/**
	 * Lets you choose which connection group to
	 * use if no other is specified.
	 *
	 * @var string
	 */
	public $defaultGroup = 'default';

	/**
	 * The default database connection.
	 *
	 * @var array
	 */
	public $default = [
		'DSN'      => '',
		'hostname' => 'localhost',
		'username' => 'root',
		'password' => 'M@hir2005',
		'database' => 'rise_crm',
		'DBDriver' => 'MySQLi',
		'DBPrefix' => 'rise_crm_',
		'pConnect' => false,
		'DBDebug'  => (ENVIRONMENT !== 'production'),
		'charset'  => 'utf8',
		'DBCollat' => 'utf8_general_ci',
		'swapPre'  => '',
		'encrypt'  => false,
		'compress' => false,
		'strictOn' => false,
		'failover' => [],
		'port'     => 3306,
	];

	/**
	 * This database connection is used when
	 * running PHPUnit database tests.
	 *
	 * @var array
	 */
	public $tests = [
		'DSN'      => '',
		'hostname' => '127.0.0.1',
		'username' => '',
		'password' => '',
		'database' => ':memory:',
		'DBDriver' => 'SQLite3',
		'DBPrefix' => 'db_',  // Needed to ensure we're working correctly with prefixes live. DO NOT REMOVE FOR CI DEVS
		'pConnect' => false,
		'DBDebug'  => (ENVIRONMENT !== 'production'),
		'charset'  => 'utf8',
		'DBCollat' => 'utf8_general_ci',
		'swapPre'  => '',
		'encrypt'  => false,
		'compress' => false,
		'strictOn' => false,
		'failover' => [],
		'port'     => 3306,
	];

	//--------------------------------------------------------------------

	public function __construct()
	{
		parent::__construct();

		if (ENVIRONMENT === 'testing') {
			$this->defaultGroup = 'tests';
			return;
		}

		// Production / cloud (Render + Aiven): use environment variables
		if (getenv('DB_HOST')) {
			$this->default['hostname'] = getenv('DB_HOST');
			$this->default['username'] = getenv('DB_USER') ?: '';
			$this->default['password'] = getenv('DB_PASSWORD') ?: '';
			$this->default['database'] = getenv('DB_NAME') ?: 'defaultdb';
			$this->default['port']     = (int) (getenv('DB_PORT') ?: 3306);
			$this->default['DBPrefix'] = getenv('DB_PREFIX') ?: 'rise_crm_';

			$sslCa = getenv('DB_SSL_CA');
			if ($sslCa && is_file($sslCa)) {
				$this->default['encrypt'] = [
					'ssl_ca' => $sslCa,
				];
			}
		}
	}

	//--------------------------------------------------------------------

}
