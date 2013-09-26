<?php
namespace Budar;

class Model
{
	// model settings
	private $pdo;
	protected $table;
	protected $primaryKey;
	private $isNew;
	private $attributes = array();
	private $relModels = array();
	private $dirtyFields = array();

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
	public function __construct($connectionName=null, $isNew=true) {
		if (!$this->table) {
			throw new \Exception('Undefined member variable "table" in class ' . get_class($this));
		} else if (!$this->primaryKey) {
			throw new \Exception('Undefined member variable "primaryKey" in class ' . get_class($this));
		}

		// get connection from config
		$this->pdo = Config::instance()->getConnection($connectionName);

		$this->isNew = $isNew;
	}

	public function __set($name, $value) {
		$this->dirtyFields[$name] = true;
		$this->attributes[$name] = $value;
	}

	public function __get($name) {
		if (isset($this->attributes[$name])) {
			return $this->attributes[$name];
		} else {
			// check relationships
		}
	}

	public static function get() {

	}

	public function save() {
		if ($this->isNew) {
			$params = array();
			foreach ($this->attributes as $key=>$val) {
				$params[':' . $key] = $val;
			}

			if (count($params) > 0) {
				// build insert query
				$colParams = implode(',', array_keys($params));
				$colNames = str_replace(':', '', $colParams);

				$st = $this->pdo->prepare('INSERT INTO ' . $this->table . ' (' . $colNames . ') VALUES (' . $colParams . ')');
				if ($st->execute($params)) {
					$this->isNew = false;
					// TODO: research lastInsertId for different drivers
					$this->attributes[$this->primaryKey] = $this->pdo->lastInsertId();
					$this->dirtyFields = array();
					return $this;
				} else {
					// failed insert
					throw new \Exception('PDO Error: ' . implode(' ', $st->errorInfo()));
					return null;
				}
			} else {
				// nothing to insert?
				return $this;
			}
		} else {
			if (count($this->dirtyFields) > 0) {
				// build update query
				$q = 'UPDATE ' . $this->table . ' SET ';
				$params = array();
				foreach ($this->dirtyFields as $key=>$yes) {
					$q .= $key . '=:' . $key . ' ';
					$params[':' . $key] = $this->attributes[$key];
				}
				$q .= 'WHERE ' . $this->primaryKey . '=' . $this->attributes[$this->primaryKey];

				$st = $this->pdo->prepare($q);
				if ($st->execute($params)) {
					return $this;
				} else {
					// failed update
					throw new \Exception('PDO Error: ' . implode(' ', $st->errorInfo()));
					return null;
				}
			} else {
				// nothing to update?
			}

			return $this;
		}
	}

	private function buildQueryParams() {
		$params = array();
		foreach ($this->attributes as $key=>$val) {
			$params[':' . $key] = $val;
		}

		if (count($params) > 0) {
			return $params;
		} else {
			return false;
		}
	}
}