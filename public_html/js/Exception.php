<?php
/**
 * Base exeception for use with the FM2 system
 *
 * @copyright  2008-2009 eFashionSoutions
 * @license    ???
 * @version    $Id: Exception.php 13 2009-06-15 17:29:55Z teastmond $
 * @link       ???
 * @since      Initial Release
 * @package    FM2
 * @subpackage Error Handling
 * @author     Martin Adamec <madamec@efashionsolution.com>
 * @author     Reha Sterbin <rsterbin@efashionsolution.com>
 */

/** @see Zend Exception */
Zend_Loader::loadClass('Zend_Exception');

/**
 * Standard exception class used across FM2
 *
 * @copyright  2008-2009 eFashionSoutions
 * @license    ???
 * @since      Initial Release
 * @package    FM2
 * @subpackage Error Handling
 * @author     Martin Adamec <madamec@efashionsolution.com>
 * @author     Reha Sterbin <rsterbin@efashionsolution.com>
 * @see        library/FM/ErrorCodes.txt
 */
class FM_Exception extends Zend_Exception
{
	/**#@+
	 * All known error codes.  If you need to add a new error type, please add
	 * it here and note its use in the codes document.
	 *
	 * @see library/FM/Docs/ErrorCodes.txt
	 */
	// General Errors
	const CODE_GENERAL               = 1000;
	const CODE_SUBSYSTEM_FAIL        = 1001;
	const CODE_INVALID_ARGS          = 1002;
	const CODE_MISSING_CONFIG        = 1003;
	const CODE_ENVIRONMENT_INVALID   = 1004;
	const CODE_VALIDATION_FAIL       = 1005;
	const CODE_EXTERNAL_ERROR        = 1006;
	const CODE_AUTH_ERROR            = 1007;
	const CODE_WRONG_STATE           = 1008;
	const CODE_DEBUG_IN_PROD         = 1009;
	const CODE_CLASS_MISSING         = 1010;
	const CODE_UNCAUGHT_EXCEPTION    = 1011;
	// Database Errors
	const CODE_DB_GENERAL            = 2000;
	const CODE_DB_CONNECTION_FAIL    = 2001;
	const CODE_DB_QUERY_ERROR        = 2002;
	const CODE_DB_STRUCTURE_ERROR    = 2003;
	// System Errors
	const CODE_SYS_GENERAL           = 5000;
	const CODE_SYS_WARNING           = 5001;
	const CODE_SYS_NOTICE            = 5002;
	const CODE_SYS_USER_ERROR        = 5003;
	const CODE_SYS_USER_WARNING      = 5004;
	const CODE_SYS_USER_NOTICE       = 5005;
	const CODE_SYS_RECOVERABLE_ERROR = 5006;
	const CODE_SYS_DEPRECATED        = 5007;
	const CODE_SYS_USER_DEPRECATED   = 5008;
	// FM Errors
	const CODE_FM_GENERAL           = 9000;
	const CODE_FM_SKU_NOT_FOUND     = 9001;
	const CODE_FM_INSUFFICIENT_INVENTORY = 9002;
	/**#@-*/

	/**
	 * Any data you want to pass to the handler
	 *
	 * @var mixed
	 */
	protected $userData;

	/**
	 * Returns the execption as a string
	 *
	 * @return string the string representation
	 * @author Martin Adamec <madamec@efashionsolution.com>
	 */
	public function __toString() {
		return '[' . $this->getCode() . ']: ' . $this->getMessage() . "\n"
				. ' in: ' . $this->getFile() . ' at line: ' . $this->getLine() . "\n";
	}

	/**
	 * Sets the error code
	 *
	 * @param  string $code the error code
	 * @author Martin Adamec <madamec@efashionsolution.com>
	 */
	public function setCode($code) {
		$this->code = $code;
	}

	/**
	 * Gets the user data
	 *
	 * @return mixed the user data
	 * @author Reha Sterbin <rsterbin@efashionsolutions.com>
	 */
	public function getUserData() {
		return $this->userData;
	}

	/**
	 * Sets the user data
	 *
	 * @param mixed $data the user data
	 * @author Reha Sterbin <rsterbin@efashionsolutions.com>
	 */
	public function setUserData($data) {
		$this->userData = $data;
	}

	/**
	 * Returns whether there is any user data
	 *
	 * @return bool whether there is user data
	 * @author Reha Sterbin <rsterbin@efashionsolutions.com>
	 */
	public function hasUserData() {
		return !empty($this->userData);
	}

	/**
	 * Gets the user data as a string
	 *
	 * @return string the user data
	 * @author Reha Sterbin <rsterbin@efashionsolutions.com>
	 */
	public function getUserDataAsString() {
		ob_start();
		var_dump($this->userData);
		$dump = ob_get_clean();
		return $dump;
	}

	/**
	 * Sets the file
	 *
	 * @param  string $file the file
	 * @author Reha Sterbin <rsterbin@efashionsolutions.com>
	 */
	public function setFile($file) {
		$this->file = (string) $file;
	}

	/**
	 * Sets the line
	 *
	 * @param  int $line the line number
	 * @author Reha Sterbin <rsterbin@efashionsolutions.com>
	 */
	public function setLine($line) {
		$this->line = intval($line);
	}

}

