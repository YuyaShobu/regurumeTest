<?php
/**
 * top3サイト
 *
 * validate class
 * ここにポストするだけでチェックと整形をして
 * エラーメッセージの返しと整形した値の返しを行うことを目指している。
 * リターンするときは親クラスの_retをかましてください
 *
 * @copyright 2013
 * @author    xiuhui yang
 */
require_once (MODEL_DIR ."/validate/abstractValidate.php");


class oenValidate extends abstractValidate {

	public $validate;
    const CONST_EXPLAIN_MAX = '255'; // 一言のの最大入力文字数

	/**
	 * データ取得
	 * @param string
	 * @return array
	 */
	function registValidate($param) {
		//$this->validate = array();
         $this->validate = $param;
        $this->validate['error_flg'] = false;
        //shop_name
        $this->_shopNameValidate($param);

        //explain
        $this->_explainpValidate($param);

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
        	$shop_name = $param['shop_name'];
            $this->validate['error_message']['0'] = '店名が入力されていません';
            $this->validate['error_flg'] = true;
            $this->validate['shop_name'] = "";
        } else {
            $this->validate['shop_name'] = $param['shop_name'];
        }
    }


	//explainのチェック
	protected function _explainpValidate ($param) {
		//explainのバリデーション
		if (isset($param['explain'])) {
			$explain = $param['explain'];
		    if ($explain != "") {
	            //長さチェック
	            //最大入力文字数を全角255文字
	            $length = mb_strlen($explain);
	            if ($length > self::CONST_EXPLAIN_MAX) {
                    //$this->validate = $param;
	               $explain = $param['explain'];
	                $this->validate['error_message']['1'] = '一言は255文字以内で入力してください';
	                $this->validate['error_flg'] = true;
	                $this->validate['explain'] = "";
	            } else {
	                $this->validate['explain'] = $param['explain'];
	            }
		   }
		}
	}
}
?>
