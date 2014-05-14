<?php
use Zend\Form\Element;
require_once (MODEL_DIR ."/validate/abstractValidate.php");
class genreValidate extends abstractValidate {

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

		$this->_valueValidate($param);

		//必ずこの関数は通すこと
		$ret = $this->_ret($this->validate);

		return $ret;
	}

	//店名のチェック
	protected function _valueValidate ($param) {
		if (isset($param['value'])) {
			$value        = $param['value'];
			if($param['value'] =="") {
				$this->validate['error_message']['0'] = 'ジャンル名が入力されていません';		
				$this->validate['error_flg'] = true;
				$this->validate['value'] = "";
			}
		    else if(mb_strlen($param['value']) > 100) {
		    	$this->validate['error_message']['0'] = '一言は100文字以内で入力してください';
		    	$this->validate['error_flg'] = true;
		    	$this->validate['value'] = "";
		    }
		    else {
		    	$this->validate['value'] = $value;
		    }
		} else {
	    	$this->validate['error_message']['0'] = 'ジャンル名が入力されていません';
	    	$this->validate['error_flg'] = true;
	    	$this->validate['value'] = "";
		}
	}


}
?>
