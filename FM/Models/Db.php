<?php
/**
 * DB Class File. 
 *
 * Creates a single instance of db connection so were not wasting resources
 *
 * @copyright 	2008 richardmowatt.com
 * @license		???
 * @version 	???
 * @link 		???
 * @since		Class available since initial Release
 * @package 	MowattMedia
 * @subpackage 	Application
 * @author 		Richard Mowatt <rmowatt@richardmowatt.com>
 */


class FM_Models_Db {

	private static $dbInstance = null;
	protected $_profiler = true;
	protected $_host = 'localhost';
	protected $_userName = '';
	protected $_password = '';
	protected $_dbName = 'FM';
	protected $_conn;

	private function __construct() {
		$this->_conn = Zend_Db::factory('Pdo_Mysql',
		array( 'host' => $this->_host,
		'username' => $this->_userName,
		'password' => $this->_password,
		'dbname' => $this->_dbName,
		'profiler' => $this->_profiler));
	}

	static public function getInstance() {
		if(!self::$dbInstance) {
			self::$dbInstance = new FM_Models_Db();
		}
		return self::$dbInstance;
	}
	
	public function getConnection() {
		return $this->_conn;
	}

}