<?php
/**
 * top3サイト
 *
 *
 * @copyright 2013
 * @author    xiuhui yang
 */
require_once (MODEL_DIR ."/validate/abstractValidate.php");


class top3Validate extends abstractValidate {

	public $validate;

	/**
	 * データ取得
	 * @param string
	 * @return array
	 */
	function registValidate($param) {
		$this->validate = array();
		$this->validate['error_flg'] = false;

        //shop_name
        $this->_shopNameValidate($param);

        //必ずこの関数は通すこと
        $ret = $this->_ret($this->validate);

    return $ret;
    }


/*
 * ▼▼▼▼▼▼▼ここから下が用意しておくもの▼▼▼▼▼▼▼
 */

    //店名のチェック
    protected function _shopNameValidate ($param) {
        if (isset($param['shop_name']) and $param['shop_name'] =="") {
            $this->validate = $param;
            $this->validate['error_message']['0'] = '店名が入力されていません';
            $this->validate['error_flg'] = true;
        } else {
            $this->validate['shop_name'] = $param['shop_name'];
        }
    }
}
?>
