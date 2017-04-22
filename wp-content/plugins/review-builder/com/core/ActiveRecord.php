<?php

define('SGRB_ACTIVE_RECORD_STATE_NEW', 1);
define('SGRB_ACTIVE_RECORD_STATE_CLEAN', 2);
define('SGRB_ACTIVE_RECORD_STATE_DIRTY', 3);
define('SGRB_ACTIVE_RECORD_STATE_REMOVED', 4);

abstract class SGRB_ActiveRecord
{
	private $ar_state = 0;
	private $ar_dirties = array();
	const TABLE_CONST = 'TABLE';

	/**
     *
     * Constructor
     *
     * @return none
     */
	public function __construct()
	{
		$this->ar_state = SGRB_ACTIVE_RECORD_STATE_NEW;
	}

	public static function finder($class = __CLASS__)
	{
		return new $class;
	}

	/**
     *
     * Magic method: call
     *
     * @param string $name  name of the called function
     * @param array $args  arguments
     * @return mixed/none
     */
	public function __call($name, $args)
	{
		$methodPrefix = substr($name, 0, 3);
		$methodProperty = lcfirst(substr($name,3));

		if ($methodPrefix=='get') {
			return $this->$methodProperty;
		}
		else if ($methodPrefix=='set') {
			if ($this->ar_state==SGRB_ACTIVE_RECORD_STATE_REMOVED) return;

			if(count($args)==1 && $this->$methodProperty!=$args[0]) {
				if ($this->ar_state==SGRB_ACTIVE_RECORD_STATE_CLEAN) $this->ar_state = SGRB_ACTIVE_RECORD_STATE_DIRTY;
				if (!in_array($methodProperty, $this->ar_dirties)) $this->ar_dirties[] = $methodProperty;
				$this->$methodProperty = $args[0];
			}
		}
	}

	protected function getTableName()
	{
		global $wpdb, $sgrb;

		$class = new ReflectionClass($this);
		if ($class->hasConstant(self::TABLE_CONST)) {
			return $wpdb->prefix.$sgrb->prefix.$class->getConstant(self::TABLE_CONST);
		}

		return '';
	}

	/**
     *
     * Create an SGRB_ActiveRecordCriteria object from a string and params
     *
     * @param string $criteria  the condition of the criteria
     * @param array $params  parameters
     * @return SGRB_ActiveRecordCriteria object
     */
	private function prepareCriteria($criteria='', $params=array())
	{
		if (!$criteria instanceof SGRB_ActiveRecordCriteria) {
			$criteria = new SGRB_ActiveRecordCriteria($criteria, $params);
		}

		return $criteria;
	}

	/**
     *
     * Create a new instance of the current class
     *
     * @param array $data  data to assign to the new created instance
     * @return object of current class
     */
	private function instantiate($data)
	{
		$className = get_class($this);
		$obj = new $className;

		foreach ($data as $key=>$val) {
			$obj->$key = $val;
		}

		$obj->ar_state = SGRB_ACTIVE_RECORD_STATE_CLEAN;

		return $obj;
	}

	/**
     *
     * Execute sql query using a criteria
     *
     * @param string $query  the sql query string
     * @param object $criteria  criteria object
     * @param object &$st  PDO statement object
     * @return boolean
     */
	private function executeQueryWithCriteria($query, $criteria, $fetch = false)
	{
		global $wpdb;

		$query .= $criteria;
		if (count($criteria->parameters)) {
			$query = $wpdb->prepare($query, $criteria->parameters);
		}
		if ($fetch) $result = $wpdb->get_results($query, ARRAY_A);

		else $result = $wpdb->query($query);

		return $result;
	}

	/**
     *
     * Find one single record that matches the criteria
	 *
	 * Usage:
     *
	 * CUser::find('username = ? AND password = ?', array($user, $pass));
	 *
     * @param string/SGRB_ActiveRecordCriteria $criteria  SQL condition or criteria object
     * @param array $params  parameter values
     * @return object  matching record object, false if no result is found
     */
	public function find($criteria='', $params=array())
	{
		$query = 'SELECT * FROM '.$this->getTableName();

		$criteria = $this->prepareCriteria($criteria, $params);
		$criteria->limit = 1;

		$res = $this->executeQueryWithCriteria($query, $criteria, true);

		if (count($res)) {
			return $this->instantiate($res[0]);
		}

		return false;
	}

	/**
     *
     * Same as find() but returns an array of objects
	 *
     * @param string/SGRB_ActiveRecordCriteria $criteria  SQL condition or criteria object
     * @param array $params  parameter values
     * @return array  matching active records
     */
	public function findAll($criteria='', $params=array())
	{
		$query = 'SELECT * FROM '.$this->getTableName();

		$criteria = $this->prepareCriteria($criteria, $params);

		$res = $this->executeQueryWithCriteria($query, $criteria, true);

		$objs = array();

		if (count($res)) {
			foreach ($res as $row) {
				$objs[] = $this->instantiate($row);
			}
		}

		return $objs;
	}

	/**
     *
     * Find records using full SQL, returns corresponding record object
	 *
     * @param string $query  select SQL
     * @param array $params  parameter values
     * @return array  matching active records
     */
	public function findAllBySql($query, $params=array())
	{
		$criteria = $this->prepareCriteria('', $params);

		$res = $this->executeQueryWithCriteria($query, $criteria, true);

		$objs = array();

		if (count($res)) {
			foreach ($res as $row) {
				$objs[] = $this->instantiate($row);
			}
		}

		return $objs;
	}

	/**
     *
     * Find one record using only the primary key
	 *
	 * Usage:
     *
	 * CUser::findByPk($key);
	 *
     * @param mixed $pk  primary key
     * @return object  matching record object, false if no result is found
     */
	public function findByPk($pk)
	{
		return $this->find('id=%s', $pk);
	}

	/**
     *
     * Find record using full SQL, returns corresponding record object
	 *
	 * @param string $query  select SQL
     * @param array $params  parameter values
     * @return object  matching record object, false if no result is found
     */
	public function findBySql($query, $params=array())
	{
		$criteria = $this->prepareCriteria('', $params);
		$criteria->limit = 1;

		$res = $this->executeQueryWithCriteria($query, $criteria, true);

		if (count($res)) {
			return $this->instantiate($res[0]);
		}

		return false;
	}

	/**
     *
     * Find the number of records
	 *
	 * @param string/SGRB_ActiveRecordCriteria $criteria  SQL condition or criteria object
     * @param array $params  parameter values
     * @return integer number of records
     */
	public function count($criteria='', $params=array())
	{
		$query = 'SELECT COUNT(*) AS cnt FROM '.$this->getTableName();

		$criteria = $this->prepareCriteria($criteria, $params);

		$res = $this->executeQueryWithCriteria($query, $criteria, true);

		if (count($res)) {
			return intval($res[0]['cnt']);
		}

		return 0;
	}

	/**
     *
     * Delete records by primary key
	 *
	 * Usage:
     *
	 * CUser::deleteByPk($key); //delete record by single key
	 *
     * @param mixed $pk  primary key
     * @return integer  number of records deleted
     */
	public function deleteByPk($pk)
	{
		$criteria = $this->prepareCriteria('id=%s', array($pk));
		$criteria->limit = 1;

		return $this->deleteAll($criteria, array($pk));
	}

	/**
     *
     * Execute custom delete query
	 *
	 * @param string $query  delete SQL
     * @param string/SGRB_ActiveRecordCriteria $criteria  SQL condition or criteria object
     * @param array $params  parameter values
	 * @return integer  number of records deleted
     */
	private function executeDelete($query, $criteria, $params)
	{
		$criteria = $this->prepareCriteria($criteria, $params);

		return $this->executeQueryWithCriteria($query, $criteria);
	}

	/**
     *
     * Delete record using full SQL
	 *
	 * @param string $query  delete SQL
     * @param array $params  parameter values
     * @return integer  number of records deleted
     */
	public function deleteBySql($query, $params=array())
	{
		return $this->executeDelete($query, '', $params);
	}

	/**
     *
     * Delete multiple records using a criteria
	 *
     * @param string/SGRB_ActiveRecordCriteria $criteria  SQL condition or criteria object
     * @param array $params  parameter values
     * @return integer  number of records deleted
     */
	public function deleteAll($criteria='', $params=array())
	{
		$query = 'DELETE FROM '.$this->getTableName();
		return $this->executeDelete($query, $criteria, $params);
	}

	/**
     *
     * Saves the current record to the database, insert or update is automatically determined
	 *
     * @return boolean  true if record was saved successfully, false otherwise
     */
	public function save()
	{
		if ( $this->ar_state == SGRB_ACTIVE_RECORD_STATE_NEW ) { //insert
			$values = array();
			$params = array();
			foreach ($this->ar_dirties as $key) {
				$values[] = '%s';
				$params[] = $this->$key;
			}

			$fields = implode(', ', $this->ar_dirties);
			$values = implode(', ', $values);
			$query = "INSERT INTO ".$this->getTableName()." ($fields) VALUES ($values)";

			$criteria = '';
			$criteria = $this->prepareCriteria($criteria, $params);

			$res = $this->executeQueryWithCriteria($query, $criteria);

			return $res;
		}
		else if ( $this->ar_state == SGRB_ACTIVE_RECORD_STATE_DIRTY ) { //update
			if (!$this->id) return false;

			//prepare params
			$params = array();
			foreach ($this->ar_dirties as $key) {
				$params[] = $this->$key;
			}

			$criteria[] = 'id=%s';
			$params[] = $this->id;

			$values = implode('=%s, ', $this->ar_dirties).'=%s';
			$query = "UPDATE ".$this->getTableName()." SET $values";

			$criteria = implode(' AND ', $criteria);
			$criteria = $this->prepareCriteria($criteria, $params);

			$res = $this->executeQueryWithCriteria($query, $criteria);

			return $res;
		}

		return false;
	}
}

/**
 * SGRB_ActiveRecordCriteria
 */
class SGRB_ActiveRecordCriteria
{
	public $parameters = null;
	public $orderBy = null;
	public $condition = null;
	public $limit = null;
	public $offset = null;

	/**
     *
     * Constructor
     *
     * @return none
     */
	public function __construct($condition='', $params=array())
	{
		if (is_string($condition)) $this->condition = $condition;
		if (count($params)) $this->parameters = $params;
	}

	/**
     *
     * Magic method: toString
     *
     * @return string
     */
	public function __toString()
	{
		$query = '';
		if ($this->condition) {
			$query .= ' WHERE '.trim($this->condition);
		}

		if ($this->orderBy) {
			$query .= ' ORDER BY '.trim($this->orderBy);
		}

		if ($this->limit) {
			$query .= ' LIMIT '.trim($this->limit);
		}

		if ($this->offset) {
			$query .= ' OFFSET '.trim($this->offset);
		}

		return $query;
	}
}
