<?php
/**
 * top3サイト
 *
 *
 * @copyright 2013
 * @author    xiuhui yang
 */
require_once (MODEL_DIR ."/validate/abstractValidate.php");
require_once (MODEL_DIR ."/logic/userLogic.php");
require_once( 'Zend/Validate.php' );
require_once( 'Zend/Validate/EmailAddress.php' );
require_once 'Zend/Validate/NotEmpty.php';
require_once 'Zend/Validate/Alnum.php';
require_once 'Zend/Validate/StringLength.php';




class passwordValidate extends abstractValidate {

	public $validate;

	/**
	 * データ取得
	 * @param string
	 * @return array
	 */
	function checkValidate($param) {
		$this->validate = array();
		$this->validate = $param;
		$this->validate['error_flg'] = false;

        //mail_address
        $this->_mailaddressValidate($param);

        //必ずこの関数は通すこと
        $ret = $this->_ret($this->validate);

    return $ret;
    }

    /**
     * データ取得
     * @param string
     * @return array
     */
    function checkPasswordValidate($param) {
        $this->validate = array();
        $this->validate = $param;
        $this->validate['error_flg'] = false;

        //password
        $this->_passwordValidate($param);

        //必ずこの関数は通すこと
        $ret = $this->_ret($this->validate);

        return $ret;
    }


/*
 * ▼▼▼▼▼▼▼ここから下が用意しておくもの▼▼▼▼▼▼▼
 */

    //メールアドレスのチェック
    protected function _mailaddressValidate ($param) {
        //必須チェック
        $validator_emp = new Zend_Validate_NotEmpty();
        if ((!$validator_emp->isValid($param['email']))){
            $this->validate['error_message']['0'] = 'メールアドレスが入力されていません。';
            $this->validate['error_flg'] = true;
        } else {
            $validatorEmail = new Zend_Validate_EmailAddress();
            if (!$validatorEmail->isValid($param['email'])){
                $this->validate['error_message']['0'] = 'メールアドレスに誤りがあります。';
                $this->validate['error_flg'] = true;
            } else {
                //存在チェック
                $obj = new userLogic();
                $cnt = $obj->checkMailExist(trim($param['email']));
                if ($cnt == 0) {
                    $this->validate['error_message']['0'] = '登録されていないメールアドレスです。';
                    $this->validate['error_flg'] = true;
                }
            }
        }
    }

    //パスワード
    protected function _passwordValidate ($param) {
        //必須チェック
        $validator_emp = new Zend_Validate_NotEmpty();
        if ((!$validator_emp->isValid($param['new_password']))){
            $this->validate['error_message']['0'] = 'パスワードが入力されていません。';
            $this->validate['error_flg'] = true;
        } else {
	       //半角英数字チェック
	            $validator_aln1 = new Zend_Validate_Alnum();
	            if ((!$validator_aln1->isValid($param['new_password']))){
	                $this->validate['error_message']['0'] = 'パスワードは半角英数字のみで入力してください。';
	                $this->validate['error_flg'] = true;
	            } else {
	                //桁数チェック
	                $validator_str1 = new Zend_Validate_StringLength(6, 20);
	                if ((!$validator_str1->isValid($param['new_password']))){
	                    $this->validate['error_message']['0'] = 'パスワードは6~20文字で入力してください。';
	                    $this->validate['error_flg'] = true;
	                } else {
	                    if ($param['new_password'] != $param['conf_password']){
	                        $this->validate['error_message']['2'] = 'パスワード一致していません。';
	                        $this->validate['error_flg'] = true;
	                    }
	                }
	            }
            }
    }


}
?>
