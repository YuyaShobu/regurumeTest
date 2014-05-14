<?php
/**
 * top3サイト
 *
 *
 * @copyright 2013
 * @author    xiuhui yang
 */
require_once (MODEL_DIR ."/validate/abstractValidate.php");
require_once( 'Zend/Validate.php' );
require_once( 'Zend/Validate/Date.php' );
require_once( 'Zend/Validate/EmailAddress.php' );
require_once 'Zend/Validate/NotEmpty.php';
require_once 'Zend/Validate/Alnum.php';
require_once 'Zend/Validate/StringLength.php';



class loginValidate extends abstractValidate {

	public $validate;

	/**
	 * データ取得
	 * @param string
	 * @return array
	 */
	function registValidate($param) {
		$this->validate = array();
		$this->validate = $param;
		$this->validate['error_flg'] = false;

        //user_name
        $this->_userNameValidate($param);

        //mail_address
        $this->_mailaddressValidate($param);

        //パスワード
        $this->_passwordValidate($param);

        //birthday
        $this->_birthdayValidate($param);

        //address1
        $this->_address1Validate($param);

        //address2
        $this->_address2Validate($param);

        //rules
        $this->_rulesValidate($param);

        //必ずこの関数は通すこと
        $ret = $this->_ret($this->validate);

    return $ret;
    }


/*
 * ▼▼▼▼▼▼▼ここから下が用意しておくもの▼▼▼▼▼▼▼
 */

    //名前のチェック
    protected function _userNameValidate ($param) {
        //必須チェック
        $validator_emp = new Zend_Validate_NotEmpty();
        if ((!$validator_emp->isValid($param['user_name']))){
            $this->validate['error_message']['0'] = 'ニックネームを入力してください。';
            $this->validate['error_flg'] = true;
        } else {
            //半角英数字チェック
            //$validator_aln = new Zend_Validate_Alnum();
            //if ((!$validator_aln->isValid($param['user_name']))){
            //    $this->validate['error_message']['0'] = 'ニックネームは半角英数字のみで入力してください。';
            //    $this->validate['error_flg'] = true;
            //} else {
                //桁数チェック
                $validator_str = new Zend_Validate_StringLength(4, 60);
                if ((!$validator_str->isValid($param['user_name']))){
                    $this->validate['error_message']['0'] = 'ニックネームは4~20文字で入力してください。';
                    $this->validate['error_flg'] = true;
                }
            //}
        }
    }

    //メールアドレスのチェック
    protected function _mailaddressValidate ($param) {
        //必須チェック
        $validator_emp = new Zend_Validate_NotEmpty();
        if ((!$validator_emp->isValid($param['email']))){
            $this->validate['error_message']['1'] = 'メールアドレスを入力してください。';
            $this->validate['error_flg'] = true;
        } else {
            $validatorEmail = new Zend_Validate_EmailAddress();
            if (!$validatorEmail->isValid($param['email'])){
                $this->validate['error_message']['1'] = 'メールアドレスに誤りがあります。';
                $this->validate['error_flg'] = true;
            }
        }
    }


    //パスワード
    protected function _passwordValidate ($param) {
     if (isset($param['password1']) && $param['password1'] !="") {
     //半角英数字チェック
            $validator_aln1 = new Zend_Validate_Alnum();
            if ((!$validator_aln1->isValid($param['password1']))){
                $this->validate['error_message']['2'] = 'パスワードは半角英数字のみで入力してください。';
                $this->validate['error_flg'] = true;
            } else {
                //桁数チェック
                $validator_str1 = new Zend_Validate_StringLength(6, 20);
                if ((!$validator_str1->isValid($param['password1']))){
                    $this->validate['error_message']['2'] = 'パスワードは6~20文字で入力してください。';
                    $this->validate['error_flg'] = true;
                } else {
                    if ($param['password1'] != $param['password2']){
                        $this->validate['error_message']['2'] = 'パスワード一致していません。';
                        $this->validate['error_flg'] = true;
                    }
                }
            }
        }
    }


    //生年月日のチェック
    protected function _birthdayValidate ($param) {
        if ($param['birthday_year'] != '0' && $param['birthday_month'] !='0' && $param['birthday_day'] != '0') {
            $birthday = $param["birthday_year"].'-'
                       .str_pad($param["birthday_month"], 2, '0', STR_PAD_LEFT).'-'
                       .str_pad($param["birthday_day"], 2, '0', STR_PAD_LEFT);
            $validDate = new Zend_Validate_Date();
            if (!$validDate->isValid($birthday)){
                $this->validate['error_message']['3'] = '生年月日入力不正です。';
                $this->validate['error_flg'] = true;
            }
        }
    }


    //住所1のチェック
    protected function _address1Validate ($param) {
        if (!isset($param['address1']) or $param['address1'] =="") {
            $this->validate['error_message']['4'] = '出没エリアに都道府県を入力してください。';
            $this->validate['error_flg'] = true;
        } else {
            $this->validate['address1'] = $param['address1'];
        }
    }

    //出没エリア2のチェック
    protected function _address2Validate ($param) {
        if (!isset($param['address2']) or $param['address2'] =="-1") {
            $this->validate['error_message']['5'] = '出没エリアに市区町村郡番地を入力してください。';
            $this->validate['error_flg'] = true;
        } else {
            $this->validate['address2'] = $param['address2'];
        }
    }

    //グルメ利用規約とプライバシーポリシー同意フラグ
    protected function _rulesValidate ($param) {
        if (!isset($param['rules']) or $param['rules'] =="") {
            $this->validate['error_message']['6'] = 'グルメ利用規約とプライバシーポリシーに同意するチェックしてください。';
            $this->validate['error_flg'] = true;
        }
    }

}
?>
