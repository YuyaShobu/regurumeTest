<?php

/**
 *
 *
 * @package   PasswordController
 * @copyright 2013
 * @author    xiuhui yang
 *
 */
require_once (LIB_PATH ."/Utility.php");

class PasswordController extends AbstractController {


    /** 初期入力画面 */
    public function indexAction () {
    }


    /** 送信アクション */
    public function mailAction () {
        // 入力値取得
        $posts = $this->getRequest()->getParams();
        $validate = $this->validate('password','checkValidate', $posts);
        //パラメータ不正の場合
        if (isset($validate['error_flg'])) {
            //値を保持して入力画面へ飛ばす。
            $this->_buck_regist_page($validate);
        } else {
            //入力アドレスに送信
            $email = trim($posts['email']);
            $send_to = $email;
            $subject = '【regurume】パスワード再設定';
            $message = $this->_makeMessageText($email);
            $send_from = CONST_REGURUME_MAIL_SUPPORT; //FROMアドレス
            $ret = Utility::send_light($send_to, $subject, $message, $send_from);
            if ($ret) {
                $this->view->msg = "入力したメールアドレスにパスワードの再設定の案内を送信しました。";
            } else {
                $this->view->msg = "送信失敗しました。";
            }
        }
    }

    /** パスワード再設定入力アクション */
    public function editAction () {
        $email = $this->getRequest()->getParam('mail');
        var_dump($email);

        $this->view->email = $email;
    }


    /** パスワード再設定更新アクション */
    public function updateAction () {
        // 入力値取得
        $posts = $this->getRequest()->getParams();
        $validate = $this->validate('password','checkPasswordValidate', $posts);
        //パラメータ不正の場合
        if (isset($validate['error_flg'])) {
            //値を保持して入力画面へ飛ばす。
            $this->_back_edit_page($validate);
        } else {
            //入力アドレスに送信
            $email = trim($posts['email']);
            if (!empty($email)) {
                ////更新処理
                $email = md5($email);
                $email = str_replace("1980", "", $email);
                $params['mail_address'] = $email;
 var_dump($email);
 exit;
                $params['password']  = $posts['new_password'];
                $ret = $this->service('user','updatePassword',$params);
                //更新実行にエラーがなく、入力したメールアドレス宛に確認メールを送付する
                if ($ret) {
                    $send_to = $email;
                    $subject = '【regurume】パスワード再設定完了のお知らせ';
                    $message .= "パスワードの再設定が完了しました。次回から新しいパスワード【".$posts['new_password']."】をご使用ください。";
                    $send_from = CONST_REGURUME_MAIL_SUPPORT; //FROMアドレス
                    $res = Utility::send_light($send_to, $subject, $message, $send_from);
                    $this->_redirect("/login/index/");
                } else {
                    //更新失敗の場合、エラー画面戻す
                    //入力した値を保持
                    $this->view->mail = $email;
                    $this->view->error  = "パスワードの更新処理の際にエラーが発生しました。お手数ですが、もう一度やり直してください。";
                    $this->view->errorflg = true;
                    //入力画面に戻って、エラーメッセージを表示する
                    $this->_helper->viewRenderer('edit');
                }
            }  else {
                $this->_redirect("/index/index/");
            }
        }
    }

//▼▼▼▼▼▼▼▼チップス▼▼▼▼▼▼▼▼▼▼▼▼

    private function _buck_regist_page($validate) {
        //入力した値を保持
        $this->view->info = $validate;
        $this->view->error  = $validate['error_message'];
        $this->view->errorflg = $validate['error_flg'];
        //入力画面に戻って、エラーメッセージを表示する
        $this->_helper->viewRenderer('index');
       }


    /**
     * 送信内容編集
     * @return unknown_type
     */
    private function _makeMessageText($email)
    {
        //SEO用TDK設定
        $Utility = new Utility;
        $use_tdk_engine_flg = $Utility->tdkEngine();

        //$email_md5 = md5($email);
        //$url = $this->url_link($email);
        $body = '';
        $body .= "メール有難うございます。\n";

        //このクラス内にprivateで作ってあります。
        $this->_encry($email,'go');

        if ($use_tdk_engine_flg == true) {
            $body .= "パスワードの再設定はこちらです".ROOT_URL."/password/edit/mail/".$email;
        } else {
            $body .= "パスワードの再設定はこちらです".ROOT_URL_TEST."/password/edit/mail/".$email;
        }
        return $body;
    }

    //エラー画面戻る
    private function _back_edit_page($validate) {
        //入力した値を保持
        $this->view->info = $validate;
        $this->view->error  = $validate['error_message'];
        $this->view->errorflg = $validate['error_flg'];
        //入力画面に戻って、エラーメッセージを表示する
        $this->_helper->viewRenderer('edit');
       }


    //メール本文文字列を自動的にリンクに変換
    private function url_link($email){
        $reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
        // The Text you want to filter for urls
        $text = ROOT_URL;
        $url = ROOT_URL;
        // Check if there is a url in the text
        if(preg_match($reg_exUrl, $text, $url)) {
            // make the urls hyper links
            return preg_replace($reg_exUrl, "パスワードの再設定はこちらです".$url[0]."/password/edit/mail/".$email , $text);
        }
    }

    //メールアドレスの暗号化
    private function _encry($mail , $status) {
    	if ($status == 'go') {
    	} else {
    	}
    }

}