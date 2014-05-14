<?php
/**
 * top3サイト
 *
 * validate class
 * ここにポストするだけでチェックと整形をして
 * エラーメッセージの返しと整形した値の返しを行うことを目指している。
 * リターンするときは親クラスの_retをかましてください
 *
 */
require_once (MODEL_DIR ."/validate/abstractValidate.php");
class staffValidate extends abstractValidate {

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

		//staff_name
		$this->_staffNameValidate($param);

		//email
		$this->_emailValidate($param);

		//status
		$this->_statusValidate($param);

		//password
		$this->_passwordValidate($param);

		//kpassword
		$this->_kpasswordValidate($param);

		//必ずこの関数は通すこと
		$ret = $this->_ret($this->validate);

		return $ret;
	}



/*
 * ▼▼▼▼▼▼▼ここから下が用意しておくもの▼▼▼▼▼▼▼
 */

	//スタッフ担当者名
	protected function _staffNameValidate ($param) {
		if (isset($param['staff_name']) and $param['staff_name'] =="") {
			$staff_name       = $param['staff_name'];
			$this->validate['error_message']['0'] = '担当者名が入力されていません';
			$this->validate['error_flg'] = true;
			$this->validate['staff_name'] = "";
		} else {
			$this->validate['staff_name'] = $param['staff_name'];
		}
	}
	
	//スタッフメールアドレス
	protected function _emailValidate ($param) {
			if (isset($param['email'])) {
			$email = $param['email'];
			$this->validate['email']  = $param['email'];
		    $regex="/^[a-z]([a-z0-9]*[-_\.]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[\.][a-z]{2,3}([\.][a-z]{2})?$/i";
		    if ($email != "") {
			    if(preg_match($regex, $email)){
			    	$this->validate['email'] = $param['email'];
			    } 
			    else {
				    $this->validate['error_message']['3'] = 'メールアドレスの形が正しくありません';
					$this->validate['error_flg'] = true;
					$this->validate['email']  ="";
			    }
		    }
		    else {
		    	$email = $param['email'];
		    	$this->validate['error_message']['0'] = 'メールアドレスが入力されていません';
		    	$this->validate['error_flg'] = true;
		    	$this->validate['status'] = "";
		    }
		}
	}
	
	//スタッフ権限
	protected function _statusValidate ($param) {
		if (isset($param['status']) and $param['status'] =="") {
			$status       = $param['status'];
			$this->validate['error_message']['0'] = '権限が入力されていません';
			$this->validate['error_flg'] = true;
			$this->validate['status'] = "";
		} else {
			$this->validate['status'] = $param['status'];
		}
	}
	
	//スタッフパスワード
	protected function _passwordValidate ($param) {
		if (isset($param['password']) and $param['password'] =="") {
			$password     = $param['password'];
			$this->validate['error_message']['0'] = 'パスワードが入力されていません';
			$this->validate['error_flg'] = true;
			$this->validate['password'] = "";
		} else {
			$this->validate['password'] = $param['password'];
		}
	}
	
	//スタッフパスワード確認
	protected function _kpasswordValidate ($param) {
		if (isset($param['kpassword']) and $param['kpassword'] != $param['password']) {
			$password     = $param['password'];
			$this->validate['error_message']['0'] = '二度同じパスワードである必要があります';
			$this->validate['error_flg'] = true;
			$this->validate['kpassword'] = "";
		} else {
			$this->validate['kpassword'] = $param['kpassword'];
		}
	}

}
?>
