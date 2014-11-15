<?php  if ( ! defined ('BASEPATH')) exit ('No direct script access allowed');

/**
 * Logging Class
 *
 * @packageCodeIgniter
 * @subpackageLibraries
 * @categoryLogging
 * @authorExpressionEngine Dev Team
 * @linkhttp://codeigniter.com/user_guide/general/errors.html
 */
class MY_Log extends CI_Log {

    public function __construct ()
    {
	parent::__construct ();
    }


    protected $_log_path;
    protected $_threshold= 1;
    protected $_date_fmt= 'Y-m-d H:i:s';
    protected $_enabled= TRUE;
    protected $_levels = array (
				'CRITICAL'	=> 0,
				'ERROR'		=> 1,
				'WARNING'	=> 2,
				'INFO'		=> 3,
				'DEBUG'		=> 4,
				'TRACE'		=> 5,
				'TRACE1'	=> 6,
				'TRACE2'	=> 7,
				'TRACE3'	=> 8);
    protected $_leveltext = array (
				   'CRITICAL'	=> "FATAL",
				   'ERROR'	=> "ERROR",
				   'WARNING'	=> "WARN",
				   'INFO'	=> "INFO",
				   'DEBUG'	=> "DEBUG",
				   'TRACE'	=> "TRACE",
				   'TRACE1'	=> "TRAC1",
				   'TRACE2'	=> "TRAC2",
				   'TRACE3'	=> "TRAC3");

    public function write_log ($level = 'error', $msg, $php_error = FALSE, $my_log_filter = false)
    {
	if ( substr ( $msg, 0, 8) == 'filter::') {
	    // this is one of my logs; remove filter::
	    $msg= substr ( $msg, 8);
	}
	elseif ( substr ( $msg, 0, 8) == 'Severity') {
	    $msg= substr ( $msg, 10);
	}
	elseif ( substr ( $msg, 0, 32) == 'Could not find the language line') {
	    $level= "TRACE3";
	}
	else {
	    // this is a code igniter log
	    $level= "TRACE";
	}

	if ($this->_enabled === FALSE) {
	    return FALSE;
	}

	if ( isset ( $_GET ['log_level'])) {
	    $log_level= $_GET ['log_level'];

	    if ( is_numeric ( $log_level)) {
		$this->_threshold= $log_level;
	    }
	}

	$level= strtoupper ( $level);

	if ( ! isset ( $this->_levels [ $level])
	     OR ($this->_levels [$level] > $this->_threshold)) {
	    return FALSE;
	}

	$filepath = $this->_log_path.'codeigniter.log';

	$message  = '';

	if ( ! file_exists ($filepath)) {
	    $message .= "<". "?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?". ">\n\n";
	}

	if ( ! $fp = @fopen ($filepath, FOPEN_WRITE_CREATE)) {
	    return FALSE;
	}

	$level= substr ( $this->_leveltext [$level], 0, 5);
	$time= explode ( " ", microtime ());

	$message .= date ( "Y-m-d H:i:s") . substr ( $time [0], 1, 7)
	    . ' --> '. $level
	    . ' '. ( ( strlen ($level) == 4) ? ' -' : '-')
	    . ' '. $msg. "\n";

	flock ($fp, LOCK_EX);
	if ( ! $error = fwrite ($fp, $message)) {
	    echo $error;
	}

	flock ($fp, LOCK_UN);
	fclose ($fp);

	/* @chmod ($filepath, FILE_WRITE_MODE); */

    }
}
