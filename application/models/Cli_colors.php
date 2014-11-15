<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CLI_Colors extends CI_Model {

    private $fg = array();
    private $bg = array();

    function __construct()
    {
	parent::__construct();

	// Set up shell colors
	$this->fg['black']= '0;30';
	$this->fg['red']= '0;31';
	$this->fg['green']= '0;32';
	$this->fg['yellow']= '0;33';
	$this->fg['blue']= '0;34';
	$this->fg['purple']= '0;35';
	$this->fg['cyan']= '0;36';
	$this->fg['igray']= '0;37';

	$this->fg['gray']= '1;30';
	$this->fg['iblack']= '1;30';
	$this->fg['iblue']= '1;34';
	$this->fg['igreen']= '1;32';
	$this->fg['icyan']= '1;36';
	$this->fg['ired']= '1;31';
	$this->fg['ipurple']= '1;35';
	$this->fg['iyellow']= '1;33';
	$this->fg['white']= '1;37';

	$this->fg['ublack']= '4;30';
	$this->fg['ured']= '4;31';
	$this->fg['ugreen']= '4;32';
	$this->fg['uyellow']= '4;33';
	$this->fg['ublue']= '4;34';
	$this->fg['upurple']= '4;35';
	$this->fg['ucyan']= '4;36';
	$this->fg['ulight_gray']= '4;37';

	$this->bg['black']= '40';
	$this->bg['red']= '41';
	$this->bg['green']= '42';
	$this->bg['yellow']= '43';
	$this->bg['blue']= '44';
	$this->bg['purple']= '45';
	$this->bg['cyan']= '46';
	$this->bg['white']= '47';
	$this->bg['iblack']= '0;100';
	$this->bg['ired']= '0;101';
	$this->bg['igreen']= '0;102';
	$this->bg['iyellow']= '0;103';
	$this->bg['iblue']= '0;104';
	$this->bg['ipurple']= '0;105';
	$this->bg['icyan']= '0;106';
	$this->bg['iwhite']= '0;107';
    }

    // Returns colored string
    public function string( $string,
			    $fg = null,
			    $bg = null )
    {
	$colored_string= "";

	if( isset( $this->fg[$fg] ) ) {
	    $colored_string       .= sprintf( "\033[%sm", $this->fg[$fg] );
	}

	if (isset($this->bg[$bg])) {
	    $colored_string       .= sprintf( "\033[%sm", $this->bg[$bg] );
	}

	$colored_string       .=  $string . "\033[0m";

	return $colored_string;
    }

    public function examples(  )
    {
	printf( "\nForeground Colors:\n" );

	foreach( $this->fg as $k => $v ) {
	    printf( "\033[%sm    %s\033[0m\n", $v, $k );
	}

	printf( "\nBackground Colors:\n" );

	foreach( $this->bg as $k => $v ) {
	    printf( "\033[0;30m\033[%sm    %s\033[0m\n", $v, $k );
	}
    }

    public function getForegroundColors()
    {
	return array_keys($this->fg);
    }

    public function getBackgroundColors()
    {
	return array_keys($this->bg);
    }

}

?>