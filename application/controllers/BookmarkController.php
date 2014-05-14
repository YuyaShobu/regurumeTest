<?php

class BookmarkController extends AbstractController {

    /** トップページを表示するアクション */
    public function indexAction () {

        // 入力画面からデータを取得して登録
        $req = $this->getRequest();
        $posts = $req->getPost();
        $inputData = array();
        if( $posts ){
            $inputData['USER_ID'] = $this->user_id;
            $inputData['BK_TYPE'] = $posts['bk_type'];
            $inputData['CATEGORY_ID'] = $posts['category_id'];
            $inputData['CREATE_USER_ID'] = $posts['create_user_id'];
            // 存在チェック
            $ret = $this->service('categoryBookmark','checkCategoryBookmarkExist',$inputData);
            if ($ret == 0 ) {
                $inputData['CREATED_AT'] = date("Y-m-d H:i:s");
                $inputData['UPDATED_AT'] = date("Y-m-d H:i:s");
                $this->service('categoryBookmark','insertCategoryBookmark',$inputData);
            }
        }
        $this->_redirect("./index");
    }


    /** shopブックマークアクション */
    public function shopAction () {

        // 入力画面からデータを取得して登録
        $req = $this->getRequest();
        $posts = $req->getPost();
        $inputData = array();
        if( $posts ){
            $inputData['user_id'] =$this->user_info['user_id'];
            $inputData['shop_id'] = $posts['shop_id'];
            // 存在チェック
            $ret = $this->service('shopBookmark','checkShopBookmarkExist',$inputData);
            if ($ret == 0 ) {
                $inputData['created_at'] = date("Y-m-d H:i:s");
                $inputData['updated_at'] = date("Y-m-d H:i:s");
                $this->service('shopBookmark','insertShopBookmark',$inputData);
            }
        }
        $this->_redirect("./index");
    }

}