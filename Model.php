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

	public function save() {
		if (!isset($this->{$this->primaryKey}))
	}
}