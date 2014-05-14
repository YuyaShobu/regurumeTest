<?php
//require_once (MODEL_DIR."/service/sampleServiceModel.php");

function smarty_function_print_current_date($params, &$smarty)
{
	//$svModel = new sampleServiceModel();
	//$res = $svModel-> getSampleDate();
	//var_dump(strftime($res);exit();

	if(empty($params['format'])) {
		$format = "%b %e, %Y";
	} else {
		$format = $params['format'];
	}
	return strftime($format,time());
}

?>