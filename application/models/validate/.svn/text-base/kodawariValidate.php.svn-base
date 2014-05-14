<?php
use Zend\Form\Element;
require_once (MODEL_DIR ."/validate/abstractValidate.php");
class kodawariValidate extends abstractValidate {

	public $validate;

	/**
	 * データ取得
	 * @param string
	 * @return array
	 */
	//データ抽出SQLクエリ
	function editValidate($param) {
		$this->validate = array();
		$this->validate['error_flg'] = false;

		$this->_largeValueValidate($param);

		//必ずこの関数は通すこと
		$ret = $this->_ret($this->validate);

		return $ret;
	}

	//店名のチェック
	protected function _largeValueValidate ($param) {
		if (isset($param['large_value'])) {
			$large_value        = $param['large_value'];
			if($param['large_value'] =="") {
				$this->validate['error_message']['0'] = 'こだわり名が入力されていません';		
				$this->validate['error_flg'] = true;
				$this->validate['large_value'] = "";
			}
		    else if(mb_strlen($param['large_value']) > 100) {
		    	$this->validate['error_message']['0'] = '一言は100文字以内で入力してください';
		    	$this->validate['error_flg'] = true;
		    	$this->validate['large_value'] = "";
		    }
		    else {
		    	$this->validate['large_value'] = $large_value;
		    }
		} else {
	    	$this->validate['error_message']['0'] = 'こだわり名が入力されていません';
	    	$this->validate['error_flg'] = true;
	    	$this->validate['large_value'] = "";
		}
	}


}
?>
