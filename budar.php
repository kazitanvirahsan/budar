<?php
namespace Budar;

class Model
{
	// model settings
	private $pdo;
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

	// NOTE: May be able to move constructor logic to child class for PHP < 5.3
	public function __construct($connectionName=null) {
		if (!$this->table) {
			throw new \Exception('Undefined member variable "table" in class ' . get_class($this));
		} else if (!$this->primaryKey) {
			throw new \Exception('Undefined member variable "primaryKey" in class ' . get_class($this));
		}

		// get connection from config
		$this->pdo = Config::instance()->getConnection($connectionName);
	}

	public static function get() {

	}
}

class Config
{
	private static $instance;
	private $connections;
	private $defaultConnection;
	private $pdoArr = array();

	private function __construct() { }

	/*
	 *
	 * @param array $connectionArr Associate array of PDO connection info array (protocol, host, db, user, pass)
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

			if (count($connections) == 1) {
				self::$instance->defaultConnection = key($connections);
			} else {
				self::$instance->defaultConnection = $defaultConnection;
			}

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

	public function getConnection($name=null) {
		if (!$name) {
			$name = $this->defaultConnection;
		}

		if (!isset($this->pdoArr[$name])) {
			if (!isset($this->connections[$name])) {
				// exception: unknown connection
				return false;
			} else {
				// make connection
				$conn = $this->connections[$name];
				$protocol = array_shift($conn);
				$host = array_shift($conn);
				$database = array_shift($conn);
				$user = array_shift($conn);
				$pass = array_shift($conn);

				$this->pdoArr[$name] = new \PDO("$protocol:host=$host;dbname=$database", $user, $pass);

				return $this->pdoArr[$name];
			}
		} else {
			return $this->pdoArr[$name];
		}
	}

}
