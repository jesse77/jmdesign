<?php if ( ! defined ('BASEPATH')) exit ('No direct script access allowed'); 

class Logging {
    
    function __construct ( $namespace = false) {
	$this->CI		= get_instance();
	$this->namespace	= $namespace;
	$this->ns_width		= 70;
    }

    public function ns ( $namespace) {
	return new logging ( $namespace);
    }

    private function clip_namespace ( $namespace, $width) {
	$clipped_ns		= trim ( substr ( $namespace, 0, $width-3)). "...";
	return $clipped_ns;
    }

    private function message ( $type, $args) {
	$traceback		= debug_backtrace();

	if ( ! $this->namespace) {
	    $source		= $traceback [2];
	    $class		= isset ( $source ["class"])
		? $source ["class"] . "::"
		: "";
	    $func		= isset ( $source ["function"])
		? $source ["function"]
		: "";

	    $namespace		= $class . $func . "()";
	}

	$level			= count ( $traceback) - 5;

	$userid			= $this->CI->input->ip_address();
	if ( $level > 0) {
	    $prefix		= str_repeat ( "	   ", $level-1) . "`--" . " ";
	    $mprefix		= str_repeat ( "..", $level);
	}
	else {
	    $prefix		= "";
	    $mprefix		= "";
	}

	$message		= call_user_func_array ( "sprintf", $args);
	$nsw			= $this->ns_width;

	$namespace		= strlen ($namespace) > $nsw
	    ? $this->clip_namespace ( $namespace, $nsw)
	    : $namespace;

	// Adding filter:: is a way to filter out codeigniter logs
	$message			= sprintf ( "filter::%-15s | %-".$nsw. ".".$nsw. "s | %s",
						  $userid, $prefix.$namespace, $mprefix.$message);

	$log_message= log_message ( $type, $message);

	return sprintf ( "%-10s%s", "$type:", $message);
    }
    
    public function critical () {
	$type			= strtoupper ( __FUNCTION__);
	return $this->message ( $type, func_get_args ());
    }

    public function fatal () {
	$type			= "CRITICAL";
	return $this->message ( $type, func_get_args ());
    }

    public function error () {
	$type			= strtoupper ( __FUNCTION__);
	return $this->message ( $type, func_get_args ());
    }
    
    public function warn () {
	$type			= "WARNING";
	return $this->message ( $type, func_get_args ());
    }

    public function warning () {
	$type			= strtoupper ( __FUNCTION__);
	return $this->message ( $type, func_get_args ());
    }

    public function info () {
	$type			= strtoupper ( __FUNCTION__);
	return $this->message ( $type, func_get_args ());
    }

    public function debug () {
	$type			= strtoupper ( __FUNCTION__);
	return $this->message ( $type, func_get_args ());
    }

    public function trace () {
	$type			= strtoupper ( __FUNCTION__);
	return $this->message ( $type, func_get_args ());
    }

    public function trace1 () {
	$type			= strtoupper ( __FUNCTION__);
	return $this->message ( $type, func_get_args ());
    }

    public function trace2 () {
	$type			= strtoupper ( __FUNCTION__);
	return $this->message ( $type, func_get_args ());
    }

    public function trace3 () {
	$type			= strtoupper ( __FUNCTION__);
	return $this->message ( $type, func_get_args ());
    }

    public function query ( $query = 'no query') {
	$args= func_get_args ();
	$args= array (
		      "Query:\n\n%s\n",
		      $query);
	return call_user_func_array ( array ( $this, 'trace'), $args);
    }

    public function print_r ( $var = 'no value') {
	$args= func_get_args ();
	$args= array (
		      "print_r:\n\n%s\n",
		      print_r ( $var, true));
	return call_user_func_array ( array ( $this, 'trace'), $args);
    }

}
