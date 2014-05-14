<?php
require_once (MODEL_DIR ."/validate/abstractValidate.php");
class adminValidate extends abstractValidate {

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
		
		//name
		$this->_nameValidate($param);

		//encrypted_password
		$this->_passwordValidate($param);

		//kpassword
		$this->_kpasswordValidate($param);

		//email
		$this->_emailValidate($param);

		//必ずこの関数は通すこと
		$ret = $this->_ret($this->validate);

		return $ret;
	}



/*
 * ▼▼▼▼▼▼▼ここから下が用意しておくもの▼▼▼▼▼▼▼
 */
	
	//管理者名
	protected function _nameValidate ($param) {
		if (isset($param['name']) and $param['name'] =="") {
			$name        = $param['name'];
		    $this->validate['error_message']['0'] = '管理者名が入力されていません';
		    $this->validate['error_flg'] = true;
		    $this->validate['name'] = "";
		} else {
			$this->validate['name'] = $param['name'];
		}
	}


	//管理者パスワード
	protected function _passwordValidate ($param) {
		if (isset($param['encrypted_password']) and $param['encrypted_password'] =="" ) {
			$encrypted_password          = $param['encrypted_password'];
			$this->validate['error_message']['0'] = '管理者パスワードが入力されていません';
		    $this->validate['error_flg'] = true;
			$this->validate['encrypted_password']   = "";
		} else {
			$this->validate['encrypted_password'] = $param['encrypted_password'];
		}
	}

	//管理者パスワード
	protected function _kpasswordValidate ($param) {
		if (isset($param['kpassword']) and isset($param['encrypted_password'])) {
			if($param['kpassword'] != $param['encrypted_password']){
				$kpassword          = $param['kpassword'];
				$this->validate['error_message']['0'] = '二度同じパスワードである必要があります';
			    $this->validate['error_flg'] = true;
				$this->validate['kpassword']   = "";
			}
			else {
				$this->validate['kpassword'] = $param['kpassword'];
			}
		} else {
			$this->validate['kpassword'] = $param['kpassword'];
		}
	}
	
	//管理者メール
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
		    	$this->validate['email'] = "";
		    }
		}
	}
}
?>
