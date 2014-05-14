<?php

function smarty_modifier_chain($data, $method) {
	if ( func_num_args() > 2 ) {
		$args = func_get_args();
		array_shift($args);
		$method = array_shift($args);
		return call_user_func_array(array(&$data,$method),$args);
	}
	return $data->$method();
}