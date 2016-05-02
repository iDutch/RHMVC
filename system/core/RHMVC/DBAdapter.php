<?php

/**
 * PDO Database Adapter, gives you SQL injection safe CRUD operations.
 * See <http://en.wikipedia.org/wiki/Create,_read,_update_and_delete> 
 * for information about CRUD. 
 *
 * @author Richard Hoogstraaten <richard@hoogstraaten.eu>
 * @version 1.1.3
 */

class DBAdapter {

	private static $instance    	= null;
    private static $dbsettings  	= array();
	private $connection         	= null;
    const SHOW_ERROR            	= true;
	private $hasActiveTransaction 	= false;

	/**
	 * Return instance or create one first if there is none...
	 */
	public static function getInstance() {
        $local  = array();

        $db_global_config_file  = __DIR__ . '/../../../config/database.global.php';
        $db_local_config_file   = __DIR__ . '/../../../config/database.local.php';

        if (file_exists($db_global_config_file)) {
            $global = require $db_global_config_file;
            if (file_exists($db_local_config_file)) {
                $local = require $db_local_config_file;
            }
            self::$dbsettings = array_merge($global, $local);
        } else {
            throw new Exception('DBAdapter error: Cannot load config file: ' . $db_global_config_file);
        }

        $dsn = 'mysql:host=' . self::$dbsettings['hostname'] . ';dbname=' . self::$dbsettings['database'];

		if (!isset(self::$instance[$dsn])) {
			self::$instance[$dsn] = new self($dsn, self::$dbsettings['username'], self::$dbsettings['password']);
		}
		return self::$instance[$dsn];
	}

	/**
	 * Constructor
	 */
	private function __construct($dsn, $user, $pass) {
		$this->connection = new PDO($dsn, $user, $pass);
	}

	/**
	 * Errorhandler
	 * 
	 * @param	object $query	PDO query object
	 * 
	 * TODO: Build error handler instead of dumping DB errors on screen
	 */
	private function handleError($query) {
		if ($this->hasActiveTransaction) {
			$this->rollback();
		}
        if (self::SHOW_ERROR) {
		    var_dump($query->errorInfo());
		    echo '<pre>';
		    var_dump($query->debugDumpParams());
		    echo '</pre>';
        }
		exit;
	}

	/**
	 * Delete statement builder
	 * 
	 * @param	string	$table	Database table
	 * @param	array	$params	Custom field => array(value => value, type => type) as deletion criteria
	 */
	public function delete($table, array $params = array()) {
		$sql = "DELETE FROM `" . $table . "` ";
        $where = "WHERE ";
		foreach ($params as $field => $array) {
			if (is_array($array['value'])) {
                $query_params = null;
				foreach ($array['value'] as $k => $v) {
                    $query_params .= ":p" . $k . ",";
				}
				$where .= "`" . $field . "` IN (" . substr($query_params, 0, -1) . ") AND ";
			} else {
				$where .= "`" . $field . "` = :" . $field . " AND ";
			}
		}
		//Strip of last AND
		$where = substr($where, 0, -5);
		$query = $this->connection->prepare($sql . $where);

		foreach ($params as $field => &$array) {
			if (is_array($array['value'])) {
				foreach ($array['value'] as $key => &$val) {
					$query->bindParam(':p' . $key, $val);
				}
			} else {
				$query->bindParam(':' . $field, $array['value'], isset($array['type']) ? $array['type'] : PDO::PARAM_STR);
			}
		}

		if (!$query->execute()) {
			$this->handleError($query);
		}
	}

	/**
	 * Select prepared statement
	 * 
	 * @param	string	$statement	SQL prepared statement 
	 * @param	array	$params		field => value pairs as selection criteria
     * @return  array   Result set
	 */
	public function query($statement, array $params = array()) {
		$query = $this->connection->prepare($statement);
		if (count($params)) {
			foreach ($params as $field => &$array) {
				$query->bindParam(':' . $field, $array['value'], isset($array['type']) ? $array['type'] : PDO::PARAM_STR);
			}
		}
		if (!$query->execute()) {
			$this->handleError($query);
		}
		return $query->fetchAll(PDO::FETCH_OBJ);
	}

	/**
	 * Insert statement builder
	 * 
	 * @param	string	$table	Database table
	 * @param	array	$params	Field => value pairs to insert
     * @return  int     Last insert ID
	 */
	public function insert($table, array $params = array()) {
		$sql = "INSERT INTO `" . $table . "` (`" . implode('`, `', array_keys($params)) . "`) VALUES (:" . implode(", :", array_keys($params)) . ")";
		$query = $this->connection->prepare($sql);
		if (count($params)) {
			foreach ($params as $field => &$value) {
				$query->bindParam(':' . $field, $value);
			}
		}
		if (!$query->execute()) {
			$this->handleError($query);
		}
		return $this->connection->lastInsertId();
	}

	/**
	 * Update statement builder
	 * 
	 * @param	string	$table  Database table
	 * @param	array	$data   field => value pairs to update
	 * @param	array	$params	field => array(value => value, type => type) as update criteria
	 */
	public function update($table, array $data = array(), array $params = array()) {
		$qry = null;
        if (count($data)) {
		    foreach ($data as $field => $value) {
			    $qry.= ($qry != '' ? ',' : '') . "`" . $field . "` = :" . $field . "";
		    }
        }
		$sql = "UPDATE `" . $table . "` SET " . $qry . " ";

		$where = "WHERE ";
		foreach ($params as $field => $array) {
			if (is_array($array['value'])) {
                $query_params = null;
				foreach ($array['value'] as $k => $v) {
                    $query_params .= ":p" . $k . ",";
				}
				$where .= "`" . $field . "` IN (" . substr($query_params, 0, -1) . ") AND ";
			} else {
				$where .= "`" . $field . "` = :" . $field . " AND ";
			}
		}
		//Strip of last AND
		$where = substr($where, 0, -5);

		$query = $this->connection->prepare($sql . $where);
		if (count($data)) {
			foreach ($data as $field => &$value) {
				$query->bindParam(':' . $field, $value);
			}
		}
		foreach ($params as $field => &$array) {
			if (is_array($array['value'])) {
				foreach ($array['value'] as $key => &$val) {
					$query->bindParam(':p' . $key, $val);
				}
			} else {
				$query->bindParam(':' . $field, $array['value'], isset($array['type']) ? $array['type'] : PDO::PARAM_STR);
			}
		}
		if (!$query->execute()) {
			$this->handleError($query);
		}
	}

	public function beginTransaction() {
		if ($this->hasActiveTransaction) {
			return false;
		} else {
			$this->hasActiveTransaction = $this->connection->beginTransaction();
			return $this->hasActiveTransaction;
		}
	}

	public function commit() {
		$this->connection->commit();
		$this->hasActiveTransaction = false;
	}

	public function rollback() {
		$this->connection->rollback();
		$this->hasActiveTransaction = false;
	}

}
