<?php

/**
 * PDO Database Adapter, gives you SQL injection safe CRUD operations.
 * See <http://en.wikipedia.org/wiki/Create,_read,_update_and_delete> 
 * for information about CRUD. 
 *
 * @author Richard Hoogstraaten <richard@hoogstraaten.eu>
 * @version 1.1.3
 */

namespace core\RHMVC;

use libs\PDODebugger\PDODebugger;
use PDO;
use PDOStatement;

class DBAdapter
{

    private static $instance = null;
    private static $dbsettings = array();
    private $connection = null;

    const SHOW_ERROR = true;

    private $hasActiveTransaction = false;

    /**
     * Return instance or create one first if there is none...
     */
    public static function getInstance()
    {
        $local = array();

        $db_global_config_file = __DIR__ . '/../../../config/database.global.php';
        $db_local_config_file = __DIR__ . '/../../../config/database.local.php';

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
    private function __construct($dsn, $user, $pass)
    {
        $this->connection = new PDO($dsn, $user, $pass);
    }

    /**
     * Errorhandler
     *
     * @param	object $query	PDO query object
     *
     * TODO: Build error handler instead of dumping DB errors on screen
     */
    private function handleError(PDOStatement $query, $sql, array $params = [], array $data = [])
    {
        if ($this->hasActiveTransaction) {
            $this->rollback();
        }
        if (self::SHOW_ERROR) {
            echo "<textarea style='width: 100%; height: 200px;'>\n";
            echo $query->errorInfo()[2] . "\n";
            echo PDODebugger::getQuery($sql, $params, $data). "\n";
            echo "</textarea>";
        }
        exit;
    }

    /**
     * Delete statement builder
     * 
     * @param	string	$table	Database table
     * @param	array	$params	Custom field => array(value => value, type => type) as deletion criteria
     */
    public function delete($table, array $params = array())
    {
        $sql = "DELETE FROM `" . $table . "` ";
        $where = "WHERE ";
        foreach ($params as $field => $value) {
            if (is_array($value)) {
                $query_params = null;
                foreach ($value as $k => $v) {
                    $query_params .= ':p_' . $field . $k . ',';
                }
                $where .= '`' . $field . '` IN (' . substr($query_params, 0, -1) . ') AND ';
            } else {
                $where .= '`' . $field . '` = :' . $field . ' AND ';
            }
        }
        //Strip of last AND
        $where = substr($where, 0, -5);
        $query = $this->connection->prepare($sql . $where);

        foreach ($params as $field => &$value) {
            if (is_array($value)) {
                foreach ($value as $key => &$val) {
                    $query->bindParam(':p_' . $field . $key, $val);
                }
            } else {
                $query->bindParam(':' . $field, $value, $this->getFieldType($value));
            }
        }

        if (!$query->execute()) {
            $this->handleError($query, $sql, $params);
        }
    }

    /**
     * Select prepared statement
     *
     * @param	string	$statement	SQL prepared statement 
     * @param	array	$params		field => value pairs as selection criteria
     * @return  array   Result set
     */
    public function query($statement, array $params = array())
    {
        $query = $this->connection->prepare($statement);
        if (count($params)) {
            foreach ($params as $field => &$value) {
                $query->bindParam(':' . $field, $value, $this->getFieldType($value));
            }
        }
        if (!$query->execute()) {
            $this->handleError($query, $statement, $params);
        }
        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Insert statement builder
     *
     * @param	string	$table	Database table
     * @param	array	$data	Field => value pairs to insert
     * @return  int     Last insert ID
     */
    public function insert($table, array $data = array())
    {
        $sql = "INSERT INTO `" . $table . "` (`" . implode('`, `', array_keys($data)) . "`) VALUES (:" . implode(", :", array_keys($data)) . ")";
        $query = $this->connection->prepare($sql);
        if (count($data)) {
            foreach ($data as $field => &$value) {
                $query->bindParam(':' . $field, $value);
            }
        }
        if (!$query->execute()) {
            $this->handleError($query, $sql, [], $data);
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
    public function update($table, array $data = array(), array $params = array())
    {
        $qry = null;
        if (count($data)) {
            foreach ($data as $field => $value) {
                $qry.= ($qry != '' ? ',' : '') . "`" . $field . "` = :" . $field . "";
            }
        }
        $sql = "UPDATE `" . $table . "` SET " . $qry . " ";

        $where = "WHERE ";
        foreach ($params as $field => $value) {
            if (is_array($value)) {
                $query_params = null;
                foreach ($value as $k => $v) {
                    $query_params .= ':p_' . $field . $k . ',';
                }
                $where .= '`' . $field . '` IN (' . substr($query_params, 0, -1) . ') AND ';
            } else {
                $where .= '`' . $field . '` = :' . $field . ' AND ';
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
        foreach ($params as $field => &$value) {
            if (is_array($value)) {
                foreach ($value as $key => &$val) {
                    $query->bindParam(':p_' . $field . $key, $val, $this->getFieldType($val));
                }
            } else {
                $query->bindParam(':' . $field, $value,  $this->getFieldType($value));
            }
        }
        if (!$query->execute()) {
            $this->handleError($query, $sql . $where, $params, $data);
        }
    }

    public function beginTransaction()
    {
        if ($this->hasActiveTransaction) {
            return false;
        } else {
            $this->hasActiveTransaction = $this->connection->beginTransaction();
            return $this->hasActiveTransaction;
        }
    }

    public function commit()
    {
        $this->connection->commit();
        $this->hasActiveTransaction = false;
    }

    public function rollback()
    {
        $this->connection->rollback();
        $this->hasActiveTransaction = false;
    }

    private function getFieldType($value)
    {
        if (is_int($value)) {
            return PDO::PARAM_INT;
        } else if (is_null($value)) {
            return PDO::PARAM_NULL;
        } else if (is_bool($value)) {
            return PDO::PARAM_BOOL;
        } else {
            return PDO::PARAM_STR;
        }
    }

}
