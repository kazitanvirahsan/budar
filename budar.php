<?php
namespace Budar;

class Model
{
	// model settings
	private $connection;
	protected $table;
	protected $primaryKey;

	// callbacks
	protected $beforeSave = array();
	protected $afterSave = array();
	protected $beforeCreate = array();
	protected $afterCreate = array();

	// relationships
	protected $hasOne;
	protected $hasMany;
	protected $belongsTo;
	protected $relData;

	public function __construct($connectionName=null) {
		if ($connectionName) {

		} else {

		}
	}

	public static function get() {

	}
}

class Config
{
	private static $instance;
	private $connections;
	private $defaultConnection;

	private function __construct() { }

	/*
	 *
	 * @param array $connectionArr Associate array of PDO connection strings, or single 
	 * @param string $defaultConnection Optional default connection
	 */
	public static function init($connections, $defaultConnection=null) {
		if (self::$instance) {
			// exception: already initialized
		} else if (!is_array($connections) || count($connections) == 0) {
			// exception: must define connections
			return false;
		} else if ($defaultConnection && count($connections) > 1 && !isset($connections[$defaultConnection])) {
			// exception: must define default connection
			return false;
		} else {
			self::$instance = new self();
			self::$instance->connections = $connections;
			self::$defaultConnection = $defaultConnection;
			return true;
		}
	}

	public static function instance() {
		if (isset(self::$instance)) {
			return self::$instance;
		} else {
			// exception: must initialize
			return false;
		}
	}

}