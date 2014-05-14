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



class userValidate extends abstractValidate {

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

        //blog_site
        $this->_blogsiteValidate($param);

        //address1
        $this->_address1Validate($param);

        //address2
        $this->_address2Validate($param);

        //自己紹介文字数チェック
        $this->_selfintroValidate($param);

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
        if ((!$validator_emp->isValid($param['mail_address']))){
            $this->validate['error_message']['1'] = 'メールアドレスを入力してください。';
            $this->validate['error_flg'] = true;
        } else {
            $validatorEmail = new Zend_Validate_EmailAddress();
            if (!$validatorEmail->isValid($param['mail_address'])){
                $this->validate['error_message']['1'] = 'メールアドレスに誤りがあります。';
                $this->validate['error_flg'] = true;
            }
        }
    }


    //パスワード
    protected function _passwordValidate ($param) {
     if (isset($param['new_password']) && $param['new_password'] !="") {
     //半角英数字チェック
            $validator_aln1 = new Zend_Validate_Alnum();
            if ((!$validator_aln1->isValid($param['new_password']))){
                $this->validate['error_message']['2'] = 'パスワードは半角英数字のみで入力してください。';
                $this->validate['error_flg'] = true;
            } else {
                //桁数チェック
                $validator_str1 = new Zend_Validate_StringLength(6, 20);
                if ((!$validator_str1->isValid($param['new_password']))){
                    $this->validate['error_message']['2'] = 'パスワードは6~20文字で入力してください。';
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


    //生年月日のチェック
    protected function _birthdayValidate ($param) {
        if ($param['birthday_year'] != '0' and $param['birthday_month'] !='0' and $param['birthday_day'] != '0') {
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

    //URLのチェック
    protected function _blogsiteValidate ($param) {
        //URLのバリデーション
        if (isset($param['blog_site']) && $param['blog_site'] !="") {
            $url = $param['blog_site'];
            $regex = "/^http?:\/\/([A-Za-z0-9;\?:@&=\+\$,\-_\.!~\*'\(\)%]+)(:\d+)?(\/?[A-Za-z0-9;\/\?:@&=\+\$,\-_\.!~\*'\(\)%#])*$/D";
            if ($url != "") {
                if(preg_match($regex, $url, $matches)){
                    $array = explode(".", $matches[1]);
                    if(count($array) == 1 or in_array("", $array)) {
                        $this->validate['error_message']['4'] = '正しいURLを入力してください。';
                        $this->validate['error_flg'] = true;
                        $this->validate['shop_url']  ="";
                    }
                } else {
                    $this->validate['error_message']['4'] = '正しいURLを入力してください。';
                    $this->validate['error_flg'] = true;
                    $this->validate['shop_url']  ="";
                }
            }
        }
    }

    //住所1のチェック
    protected function _address1Validate ($param) {
        if (!isset($param['address1']) or $param['address1'] =="-1") {
            $this->validate['error_message']['5'] = '出没エリアに都道府県を入力してください。';
            $this->validate['error_flg'] = true;
        } else {
            $this->validate['address1'] = $param['address1'];
        }
    }

    //住所2のチェック
    protected function _address2Validate ($param) {
        if (!isset($param['address2']) or $param['address2'] =="-1") {
            $this->validate['error_message']['5'] = '出没エリアに市区町村郡番地を入力してください。';
            $this->validate['error_flg'] = true;
        } else {
            $this->validate['address2'] = $param['address2'];
        }
    }


    //自己紹介文字数のチェック
    protected function _selfintroValidate ($param) {
        //explainのバリデーション
        if (!empty($param['self_intro'])) {
            //長さチェック
            //最大入力文字数を全角60文字
            $length = mb_strlen($param['self_intro']);
            if ($length > 1000) {
                $explain = $param['self_intro'];
                $this->validate['error_message']['6'] = '自己紹介は1000文字以内で入力してください';
                $this->validate['error_flg'] = true;
            }
        }
    }

}
?>
