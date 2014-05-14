<?php

class VotingController extends AbstractController {

    /** ユーザー詳細画面にフルコース「いいね」ボタン押されたアクション */
    public function fullcourseAction () {

        // 入力画面からデータを取得して登録
        $req = $this->getRequest();
        $posts = $req->getPost();
        $inputData = array();
        if( $posts ){
            $inputData['USER_ID'] =$this->user_info['user_id'];
            $inputData['FC_ID'] = $posts['fc_id'];
            $inputData['VOTING_TYPE'] = $posts['voting_type'];
            $inputData['VOTING_KIND'] = $posts['voting_kind'];
            // 存在チェック
            $ret = $this->service('voting','checkVotingExist',$inputData);
            if ($ret == 0 ) {
                $inputData['CREATED_AT'] = date("Y-m-d H:i:s");
                $inputData['UPDATED_AT'] = date("Y-m-d H:i:s");
                $this->service('voting','insertCategoryVoting',$inputData);
            }
        }
        $this->_redirect("./index");
    }


    /** shop詳細画面応援ボタン押された登録アクション */
    public function ajaxshopoenAction () {

        $ret = true;
        //viewは読み込まない
        $this->_helper->viewRenderer->setNoRender();
        // パラメター取得
        $inputData = array();
        $user_id = $this->user_info['user_id'];
        $shop_id = $this->getRequest()->getParam('shop_id');
        if( $user_id != "" && $shop_id != "" ){
            $inputData['user_id'] = intval($user_id);
            $inputData['shop_id'] = intval($shop_id);
            //beento、timelineテーブルに登録する
            $ret = $this->service('voting','registShopOen',$inputData);
            //応援情報一覧取得
            $res = $this->service('shop','getOenUserList',$shop_id);
            //noimage対応
            $res = Utility::userImgExists($res);
            echo json_encode($res);
            exit;
        }
        echo false;
        exit;
        /*
        $bttag="";
        if ($ret != false){
            $bttag = "
                    <li id=\"shopoen\">
                        <input type=\"button\" id=\"bt_oen\" class=\"btn btnF btnC03\" value=\"応援しました\" onclick=\"ajax_shopvoting({$shop_id},'shopoen','/voting/ajaxshopoencancel/');\"/>
                    </li>
                    ";
        }
        echo $bttag;
        */
    }


    /** 店詳細画面「応援」削除アクション、応援データ論理削除する */
    public function ajaxshopoencancelAction () {
        $ret = true;
        //viewは読み込まない
        $this->_helper->viewRenderer->setNoRender();
        // パラメター取得
        $user_id = $this->user_info['user_id'];
        $shop_id = $this->getRequest()->getParam('shop_id');
        $inputData = array();
        if( $user_id != "" && $shop_id != "" ){
            $inputData['user_id'] = $user_id;
            $inputData['shop_id'] = $shop_id;
            //$inputData['delete_flg'] = 1;
            //$inputData['updated_at'] = date("Y-m-d H:i:s");
            // データ物理削除する
            $ret = $this->service('voting','deleteShopOen',$inputData);
            //応援情報一覧取得
            $res = $this->service('shop','getOenUserList',$shop_id);
            //noimage対応
            $res = Utility::userImgExists($res);
            echo json_encode($res);
            exit;
        }
        /*
        $bttag="";
        if ($ret != false){
            $bttag = "<li id=\"shopoen\">
                        <input type=\"button\" id=\"bt_oen\" class=\"btn btnF btnC03\" value=\"応援する\" onclick=\"ajax_shopvoting({$shop_id},'shopoen','/voting/ajaxshopoen/');\"/>
                      </li>
                    ";
        }
        echo $bttag;
        */
        echo false;
        exit;
    }


    /** shop詳細画面行ったボタン押された登録アクション */
    public function ajaxshopbeentoAction () {

        $ret = true;
        //viewは読み込まない
        $this->_helper->viewRenderer->setNoRender();
        // パラメター取得
        $inputData = array();
        $user_id = $this->user_info['user_id'];
        $shop_id = $this->getRequest()->getParam('shop_id');
        if( $user_id != "" && $shop_id != "" ){
            $inputData['user_id'] = $user_id;
            $inputData['shop_id'] = $shop_id;
            //beento、timelineテーブルに登録する
            $this->service('voting','registBeento',$inputData);
            //行った一覧データ取得
            $res = $this->service('shop','getBeentoUserList',$shop_id);
            //noimage対応
            $res = Utility::userImgExists($res);
            echo json_encode($res);
            exit;
        }
        echo false;
        exit;
    }


    /** 店詳細画面「行った」削除アクション、beentoデータ論理削除する */
    public function ajaxshopbeentocancelAction () {
        $ret = true;
        //viewは読み込まない
        $this->_helper->viewRenderer->setNoRender();
        // パラメター取得
        $user_id = $this->user_info['user_id'];
        $shop_id = $this->getRequest()->getParam('shop_id');
        $inputData = array();
        if( $user_id != "" && $shop_id != "" ){
            $inputData['user_id'] = $user_id;
            $inputData['shop_id'] = $shop_id;
            //$inputData['delete_flg'] = 1;
            //$inputData['updated_at'] = date("Y-m-d H:i:s");
            // データ物理削除する
            $ret = $this->service('voting','deleteBeento',$inputData);
            //行った一覧データ取得
            $res = $this->service('shop','getBeentoUserList',$shop_id);
            //noimage対応
            $res = Utility::userImgExists($res);
            echo json_encode($res);
            exit;
        }
        echo false;
        exit;
    }


    /** shop詳細画面行きたいボタン押された登録アクション */
    public function ajaxshopwanttoAction () {

        $ret = true;
        //viewは読み込まない
        $this->_helper->viewRenderer->setNoRender();
        // パラメター取得
        $inputData = array();
        $user_id = $this->user_info['user_id'];
        $shop_id = $this->getRequest()->getParam('shop_id');
        $voting_kind = Utility::CONST_VALUE_VOTING_BUTTON_WANTTO;
        if( $user_id != "" && $shop_id != "" ){
            $inputData['user_id'] = $user_id;
            $inputData['shop_id'] = $shop_id;
            $inputData['voting_kind'] = $voting_kind;

            //beento、timelineテーブルに登録する
            $ret = $this->service('voting','registShopVoting',$inputData);

            //行きたい一覧データ取得
            $para['shop_id'] = $shop_id;
            $para['voting_kind'] = Utility::CONST_VALUE_VOTING_BUTTON_WANTTO;
            $para['delete_flg'] = 0;
            $res = $this->service('voting','getUserList',$para);
            //noimage対応
            $res = Utility::userImgExists($res);
            echo json_encode($res);
            exit;
        }
        /*
        $bttag="";
        if ($ret != false){
            $bttag = "
                     <li id=\"wantto\">
                        <input type=\"button\" id=\"bt_wantto\" class=\"btn btnF btnC01\" value=\"行きたい削除\" onclick=\"ajax_shopvoting({$shop_id},'wantto','/voting/ajaxshopwanttocancel/');\"/>
                     </li>
                    ";
        }
        echo $bttag;
        */
        echo false;
        exit;
    }


    /** 店詳細画面行きたい削除アクション、shop_votingデータ論理削除する */
    public function ajaxshopwanttocancelAction () {
        $ret = true;
        //viewは読み込まない
        $this->_helper->viewRenderer->setNoRender();
        // パラメター取得
        $user_id = $this->user_info['user_id'];
        $shop_id = $this->getRequest()->getParam('shop_id');
        $voting_kind = Utility::CONST_VALUE_VOTING_BUTTON_WANTTO;
        $inputData = array();
        if( $user_id != "" && $shop_id != "" ){
            $inputData['user_id'] = $user_id;
            $inputData['shop_id'] = $shop_id;
            $inputData['voting_kind'] = $voting_kind;
            //$inputData['delete_flg'] = 1;
            //$inputData['updated_at'] = date("Y-m-d H:i:s");
            // データ物理削除する
            $ret = $this->service('voting','deleteShopVoting',$inputData);
            //$ret = $this->service('voting','deleteShopVoting',$inputData);
            //行きたい一覧データ取得
            $para['shop_id'] = $shop_id;
            $para['voting_kind'] = Utility::CONST_VALUE_VOTING_BUTTON_WANTTO;
            $para['delete_flg'] = 0;
            $res = $this->service('voting','getUserList',$para);
            //noimage対応
            $res = Utility::userImgExists($res);
            echo json_encode($res);
            exit;
        }
        echo false;
        exit;
    }


    /** shop詳細画面bookmarkボタン押された登録アクション */
    public function ajaxshopbookmarkAction () {

        $ret = true;
        //viewは読み込まない
        $this->_helper->viewRenderer->setNoRender();
        // パラメター取得
        $inputData = array();
        $user_id = $this->user_info['user_id'];
        $shop_id = $this->getRequest()->getParam('shop_id');
        if( $user_id != "" && $shop_id != "" ){
            $inputData['user_id'] = $user_id;
            $inputData['shop_id'] = $shop_id;
            // データ存在チェック
            $data = $this->service('shopBookmark','checkShopBookmarkExist',$inputData);
            if ( $data > 0 ) {
                // 削除されたデータがあるため、delete_flg=0に戻す
                $inputData['delete_flg'] = 0;
                $inputData['updated_at'] = date("Y-m-d H:i:s");
                $ret = $this->service('shopBookmark','updateShopBookmark',$inputData);
            } else {
                $inputData['created_at'] = date("Y-m-d H:i:s");
                $inputData['updated_at'] = date("Y-m-d H:i:s");
                $ret = $this->service('shopBookmark','insertShopBookmark',$inputData);
            }
        }
        $bttag="";
        if ($ret != false){
            $bttag = "<input id=\"bt_shopbk\" type=\"button\" onClick=\"ajax_shopvoting('shopbookmark','/voting/ajaxshopbookmarkcancel/');\" value=\"bookmarkしました\">";
        }
        echo $bttag;
    }


    /** 店詳細画面「bookmark」削除アクション、bookmardデータ論理削除する */
    public function ajaxshopbookmarkcancelAction () {
        $ret = true;
        //viewは読み込まない
        $this->_helper->viewRenderer->setNoRender();
        // パラメター取得
        $user_id = $this->user_info['user_id'];
        $shop_id = $this->getRequest()->getParam('shop_id');
        $inputData = array();
        if( $user_id != "" && $shop_id != "" ){
            $inputData['user_id'] = $user_id;
            $inputData['shop_id'] = $shop_id;
            $inputData['delete_flg'] = 1;
            $inputData['updated_at'] = date("Y-m-d H:i:s");
        }

        // データ論理削除する
        $ret = $this->service('shopBookmark','updateShopBookmark',$inputData);
        $bttag="";
        if ($ret != false){
            $bttag = "<input id=\"bt_shopbk\" type=\"button\" onClick=\"ajax_shopvoting('shopbookmark','/voting/ajaxshopbookmark/');\" value=\"bookmark\">";
        }
        echo $bttag;
    }
    /** ranking詳細画面「参考にする」などのボタン押された登録アクション */
    public function ajaxrankvotingAction () {
        $ret = true;
        //viewは読み込まない
        $this->_helper->viewRenderer->setNoRender();
        // パラメター取得
        $inputData = array();
        $inputData['USER_ID'] =$this->user_info['user_id'];
        $inputData['VOTING_TYPE']= $this->getRequest()->getParam('voting_type');
        $inputData['VOTING_KIND'] = $this->getRequest()->getParam('voting_kind');
        $inputData['RANK_ID'] = $this->getRequest()->getParam('rank_id');
        $inputData['CREATE_USER_ID'] = $this->getRequest()->getParam('create_user_id');
        // データ存在チェック
        $data = $this->service('voting','checkRankVotingExist',$inputData);
        if ( $data > 0 ) {
            // 削除されたデータがあるため、delete_flg=0に戻す
            $inputData['delete_flg'] = 0;
            $inputData['UPDATED_AT'] = date("Y-m-d H:i:s");
            $ret = $this->service('voting','updateCategoryVoting',$inputData);
        } else {
            $inputData['CREATED_AT'] = date("Y-m-d H:i:s");
            $inputData['UPDATED_AT'] = date("Y-m-d H:i:s");
            $ret = $this->service('voting','insertCategoryVoting',$inputData);
        }
        $referbttag="";
        if ($ret != false){
            $referbttag = "<input id=\"refer_button\" type=\"button\" onClick=\"ajax_rankvoting('cancel');\" value=\"参考にしました\">";
        }
        echo $referbttag;
        //$encode = json_encode($ret);
        //echo $encode;
        //exit;
    }


    /** ranking詳細画面「参考にしました」ボタン押されたアクション、参考データ論理削除する */
    public function ajaxrankcancelAction () {
        $ret = true;
        //viewは読み込まない
        $this->_helper->viewRenderer->setNoRender();
        // パラメター取得
        $inputData = array();
        $inputData['USER_ID'] =$this->user_info['user_id'];
        $inputData['VOTING_TYPE']= $this->getRequest()->getParam('voting_type');
        $inputData['VOTING_KIND'] = $this->getRequest()->getParam('voting_kind');
        $inputData['RANK_ID'] = $this->getRequest()->getParam('rank_id');
        $inputData['CREATE_USER_ID'] = $this->getRequest()->getParam('create_user_id');
        $inputData['DELETE_FLG'] = 1;
        $inputData['UPDATED_AT'] = date("Y-m-d H:i:s");

        // データ論理削除する
        $ret = $this->service('voting','updateCategoryVoting',$inputData);
        $referbttag="";
        if ($ret != false){
            $referbttag = "<input id=\"refer_button\" type=\"button\" onClick=\"ajax_rankvoting('voting');\" value=\"参考にする\">";
        }
        echo $referbttag;

        //$encode = json_encode($ret);
        //echo $encode;
        //exit;
    }

}