<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function currency( $number = null )
{
    if( is_numeric( $number ) ) {
	return money_format( "%!n", $number );
    }
    else {
	return "N/A";
    }
}

?>
