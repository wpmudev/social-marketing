<?php

function wdsm_getval ($val, $prop, $next=false) {
	if (is_object($val)) {
		if (isset($val->$prop)) return $next ? (isset($val->$prop->$next) ? $val->$prop->$next : false) : $val->$prop;
		else return false;
	} else if (is_array($val)) {
		if (isset($val[$prop])) return $next ? (isset($val[$prop][$next]) ? $val[$prop][$next] : false) : $val[$prop];
		else return false;		
	} 
	return false;
}

/**
 * General plugin-specific exception.
 */
class Wdsm_Exception extends Exception {};