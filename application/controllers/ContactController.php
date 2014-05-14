<?php

/**
 *
 *
 * @package   ContactController
 *
 * @copyright 2013
 * @author    xiuhui yang
 *
 */

require_once (LIB_PATH ."/Utility.php");
class ContactController extends AbstractController {


    /** お問い合わせ */
    public function indexAction () {
    }


    /** 入力アクション */
    public function ajaxcontactAction () {
        //viewは読み込まない
        $this->_helper->viewRenderer->setNoRender();
        // 入力値取得
        $contact_uid =  $this->getRequest()->getParam('uid');
        $contact_name = $this->getRequest()->getParam('cname');
        $contact_text =  $this->getRequest()->getParam('ctxt') ;
        $ret = true;
        if (!empty($contact_text)) {
            //regurume事務局へ送信
            $send_to = CONST_REGURUME_MAIL_SUPPORT;
            $subject = '【Regurume】のご意見';// お問合わせメール件名
            $message = $this->_makeMessageText($contact_name,$contact_text);
            $send_from = CONST_REGURUME_MAIL_SUPPORT; //FROMアドレス
            $ret = Utility::send_light($send_to, $subject, $message, $send_from);
        }
        echo json_encode($ret);
        exit;

    }


//▼▼▼▼▼▼▼▼チップス▼▼▼▼▼▼▼▼▼▼▼▼

    /**
     * 送信内容編集
     * @return unknown_type
     */
    private function _makeMessageText($contact_name,$contact_text)
    {

        $body = "";
        $body .= "{$contact_name}さんのメッセージが来ました。\n";
        $body .= "メール内容は下記です。\n";
        $body .="{$contact_text}";
        return $body;
    }

}