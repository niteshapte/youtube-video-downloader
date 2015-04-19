<?php
/**
 * This class handles and logs the error that occurs in the project. Exceptions will also be caught by this class.
 *
 * @package YouTube Video Downloader
 * @author Nitesh Apte
 * @copyright 2015
 * @version 1.3
 * @license GPL v3
 */
class ErrorHandler {

	/**
	 * @var $singleInstance Single instance variable for ErrorHandler object
	 * @see getInstance()
	 */
	private static $singleInstance;

	/**
	 * @var $MAXLENGTH Maximum length for backtrace message
	 * @see debugBacktrace()
	 */
	private $MAXLENGTH = 64;
	
	/**
	 * @var $errorType PHP defined errors
	 * @see customError()
	 */
	private $errorType = array (
			E_ERROR           	=> 'ERROR',
			E_WARNING         	=> 'WARNING',
			E_PARSE           	=> 'PARSING ERROR',
			E_NOTICE          	=> 'NOTICE',
			E_CORE_ERROR      	=> 'CORE ERROR',
			E_CORE_WARNING    	=> 'CORE WARNING',
			E_COMPILE_ERROR   	=> 'COMPILE ERROR',
			E_COMPILE_WARNING 	=> 'COMPILE WARNING',
			E_USER_ERROR      	=> 'USER ERROR',
			E_USER_WARNING    	=> 'USER WARNING',
			E_USER_NOTICE     	=> 'USER NOTICE',
			E_STRICT 		  	=> 'STRICT',
			E_RECOVERABLE_ERROR => 'RECOVERABLE ERROR',
			E_DEPRECATED 		=> 'DEPRECATED',
			E_USER_DEPRECATED 	=> 'USER_DEPRECATED');
	
	/**
	 * Create the single instance of class
	 * 
	 * @param none
	 * @return Object self::$singleInstance Instance
	 */
	public static function getInstance() {		
		if(!self::$singleInstance instanceof self) {
			self::$singleInstance = new self();
		}
		return self::$singleInstance;
	}
	
	/**
	 * Private constructor
	 */
	private function __construct() {
	}
	
	/**
	 * Stop serialization
	 */
	private function __wakeup() {
		throw new Exception('Unserializing instances of this class is forbidden.');
	}
	
	/**
	 * Override clone method to stop cloning of the object
	 * 
	 * @throws Exception
	 */
	public function __clone() {
		throw new Exception("Cloning is not supported in singleton class.");
	}
	
	/**
	 * Set custom error handler
	 *
	 * @param String $requestFrom
	 * @return none
	 */
	public function enableHandler() {
		error_reporting(1);
		set_error_handler(array($this,'customError'), E_ALL);
		register_shutdown_function(array($this, 'fatalError'));
	}
	
	/**
	 * Custom error logging in custom format
	 *
	 * @param Int $errNo Error number
	 * @param String $errStr Error string
	 * @param String $errFile Error file
	 * @param Int $errLine Error line
	 * @return none
	 */
	public function customError($errNo, $errStr, $errFile, $errLine) {

		if(error_reporting() == 0) {
			return;
		}
		$backTrace = $this->debugBacktrace(2);
		
		$css = <<<EOT
		<style>	
		.errormessage {
			margin:0px;padding:0px;
			width:100%;
		}
		.errormessage table{
		    border-collapse: collapse;
		    border-spacing: 0;
			width:100%;			
			margin:0px;padding:0px;
		}
		.errormessage tr:last-child td:last-child {
			-moz-border-radius-bottomright:0px;
			-webkit-border-bottom-right-radius:0px;
			border-bottom-right-radius:0px;
		}
		.errormessage table tr:first-child td:first-child {
			-moz-border-radius-topleft:0px;
			-webkit-border-top-left-radius:0px;
			border-top-left-radius:0px;
		}
		.errormessage table tr:first-child td:last-child {
			-moz-border-radius-topright:0px;
			-webkit-border-top-right-radius:0px;
			border-top-right-radius:0px;
		}
		.errormessage tr:last-child td:first-child{
			-moz-border-radius-bottomleft:0px;
			-webkit-border-bottom-left-radius:0px;
			border-bottom-left-radius:0px;
		}
		.errormessage tr:hover td{
			
		}
		.errormessage tr:nth-child(odd){ 
			background-color:#e5e5e5; 
		}
		.errormessage tr:nth-child(even) { 
			background-color:#ffffff; 
		}.errormessage td {
			vertical-align:middle;
			border:1px solid #000000;
			border-width:0px 1px 1px 0px;
			text-align:left;
			padding:5px;
			font-size:12px;
			font-family:Arial;
			font-weight:normal;
			color:#000000;
		}.errormessage tr:last-child td{
			border-width:0px 1px 0px 0px;
		}.errormessage tr td:last-child{
			border-width:0px 0px 1px 0px;
		}.errormessage tr:last-child td:last-child{
			border-width:0px 0px 0px 0px;
		}
		.errorhead {
			font: 20px Arial;
			margin: 5px 0 10px 0;
			font-weight: bold;
		}
		</style>
EOT;
		
		
		$errorMessage = "<title>YouTube Video Downloader Error and Exception Handler - {$this->errorType[$errNo]}</title><div class='errorhead'>YouTube Video Downloader Error and Exception Handler</div><div class='errormessage'><table border=1>";		
		$errorMessage .= "<tr><td><b>ERROR NO : </b></td><td><font color='red'>{$errNo}</font></td></tr>";
		$errorMessage .= "<tr><td><b>ERROR TYPE : </b></td><td><i><b><font color='red'>{$this->errorType[$errNo]}</font></b></i></td></tr>";
		$errorMessage .= "<tr><td><b>TEXT : </b></td><td><font color='red'>{$errStr}</font></td></tr>";
		$errorMessage .= "<tr><td><b>LOCATION : </b></td><td><font color='red'>{$errFile}</font>, <b>line</b> {$errLine}, at ".date("F j, Y, g:i a")."</td></tr>";
		$errorMessage .= "<tr><td width='120px'><b>Showing Backtrace : </b></td><td>{$backTrace} </td></tr></table></div>";
		
		echo $css.$errorMessage;
		exit();
	}

	/**
	 * Build backtrace message
	 *
	 * @param $entriesMade Irrelevant entries in debug_backtrace, first two characters
	 * @return
	 */
	public function debugBacktrace($entriesMade) {
		
		$traceArray = debug_backtrace();
		$argsDefine = array();
		
		$traceMessage = '';

		for($i=0;$i<$entriesMade;$i++) {
			array_shift($traceArray);
		}
		
		$defineTabs = sizeof($traceArray) - 1;
		foreach($traceArray as $newArray) {
				
			$defineTabs -= 1;
			if(isset($newArray['class'])) {
				$traceMessage .= $newArray['class'].'.';
			}
			if(!empty($newArray['args'])) {

				foreach($newArray['args'] as $newValue) {
					if(is_null($newValue)) {
						$argsDefine[] = NULL;
					} elseif(is_array($newValue)) {
						$argsDefine[] = 'Array['.sizeof($newValue).']';
					} elseif(is_object($newValue)) {
						$argsDefine[] = 'Object: '.get_class($newValue);
					}
					elseif(is_bool($newValue)) {
						$argsDefine[] = $newValue ? 'TRUE' : 'FALSE';
					} else {
						$newValue = (string)@$newValue;
						$stringValue = htmlspecialchars(substr($newValue, 0, $this->MAXLENGTH));
						if(strlen($newValue)>$this->MAXLENGTH) {
							$stringValue = '...';
						}
						$argsDefine[] = "\"".$stringValue."\"";
					}
				}
			}
			
			$traceMessage .= $newArray['function'].'('.implode(',', $argsDefine).')';
			$lineNumber = (isset($newArray['line']) ? $newArray['line']:"unknown");
			$fileName = (isset($newArray['file']) ? $newArray['file']:"unknown");

			$traceMessage .= sprintf(" # line %4d. file: %s", $lineNumber, $fileName, $fileName);
			$traceMessage .= "\n";
		}
		return $traceMessage;
	}

	/**
	 * Method to catch fatal and parse error
	 *
	 * @param none
	 * @return none
	 */
	public function fatalError() {
		$lastError =  error_get_last();
		if($lastError['type'] == 1 || $lastError['type'] == 4 || $lastError['type'] == 16 || $lastError['type'] == 64 || $lastError['type'] == 256 || $lastError['type'] == 4096) {
			$this->customError($lastError['type'], $lastError['message'], $lastError['file'], $lastError['line']);
		}
	}	
}
?>
