<?php

/**
 *
 *
 * @package   OenController
 * @copyright 2013
 * @author    xiuhui yang
 *
 */


class OenController extends AbstractController {


    /** 応援トップ画面 */
    public function indexAction () {
        $res = $this->service('oen','getOenList',$this->user_info['user_id']);
        $this->view->list = $res;
    }


    /** 入力アクション */
    public function inputAction () {
        $param['user_id'] = $this->user_info['user_id'];
        // 入力値取得
        $req = $this->getRequest();
        $oen_id = $req->getParam('id');
        //$new_flg = $req->getParam('new');
        if ( isset( $oen_id ) && $oen_id != "" ) {
            // 詳細情報を取得
            $res = $this->service('oen','getOenDetail',$oen_id);
            //店の住所取得
            $param['shop_id'] = $res['shop_id'];
            $address_info = $this->service('shop','getShopAddressLatLon',$param);
            $lat = $address_info['latitude'];
            $lon = $address_info['longitude'];
            //改行コード除去
            $lon = str_replace(array("\r\n","\r","\n"), '', $lon);
            $this->view->detail = $res;
            $this->view->oen_id = $oen_id;
        } else {
            $address_info = $this->service('user','getUserAddressLatLon',$param);
            $lat = $address_info['latitude'];
            $lon = $address_info['longitude'];
        }
        $this->view->latitude = $lat;
        $this->view->longitude = $lon;
    }


    /** 登録アクション */
    public function insertAction () {
        // 入力画面からデータを取得して登録
        $req = $this->getRequest();
        $posts = $req->getPost();

        //新規店舗登録のバリデーション
        $post_param = $this->getRequest()->getParams();
        $validate = $this->validate('oen','registValidate', $post_param);
        //パラメータ不正の場合
        if (isset($validate['error_flg'])) {
            //値を保持して入力画面へ飛ばす。
            $this->_buck_regist_page($validate);
        } else {
            $oen_id = $posts['oen_id'];
            $term = explode('_', $posts['shop_id'] );
            $shop_id = $term[0];

            //画像情報
            $upfile = $_FILES["photo"];
            //アップロードしたファイルの名称
            $name  = $upfile[ 'name' ];
            $file_name_1 = pathinfo($name);
            //ファイル名をrenameする
            $new_name = $shop_id.'_'.$this->user_info['user_id'].'_oen';
            $new_name .= ".";
            $new_name .= $file_name_1['extension'];

            //登録項目編集
            $inputData = array();
            $inputData['USER_ID'] = $this->user_info['user_id'];
            $inputData['SHOP_ID'] = $shop_id;
            $explain = strip_tags($posts['explain']);
            $inputData['EXPLAIN'] = $explain;

            //画像uploadされた場合、ファイル名作成してDB項目にセット
            if ( $upfile[ 'error' ] == UPLOAD_ERR_OK && is_uploaded_file( $upfile['tmp_name'] ) ) {
                $photo = $shop_id.'/'.$new_name;
                $inputData['PHOTO'] = $photo;
            }

            //編集の場合
            if ( $oen_id !="" ) {
                $inputData['OEN_ID'] = $oen_id;
                $inputData['UPDATED_AT'] = date("Y-m-d H:i:s");
                $ret = $this->service('oen','updateOen',$inputData);
             } else {
                // 新規登録の場合データ存在チェック
                $param['user_id'] = $this->user_info['user_id'];
                $param['shop_id'] =  $shop_id;
                $data = $this->service('oen','checkOenExist',$param);
                if ($data == 0) {
                    $inputData['CREATED_AT'] = date("Y-m-d H:i:s");
                    $inputData['UPDATED_AT'] = date("Y-m-d H:i:s");
                    $ret = $this->service('oen','insertOen',$inputData);
                } else {
                    echo'既に登録しています。';exit;
                }
            }

            //DBに正常登録されたら、画像UPLOAD処理する
            // アップロード先のパス
            $updir_pc  = ROOT_PATH."/img/pc/shop/".$shop_id;
            $updir_sp  = ROOT_PATH."/img/sp/shop/".$shop_id;
            Utility::uploadFile($updir_pc,$updir_sp,$new_name,$upfile);
            $this->_redirect("/oen/index");
        }
    }


    /** 削除アクション */
    public function deleteAction () {
        // 削除ボタン押された場合パラメター取得
        $oen_id = $this->getRequest()->getParam('id');
        if ( isset($oen_id) ) {
            $oen_info = $this->service('oen','getOenDetail',$oen_id);
            $param['OEN_ID'] = $oen_id;
            $param['DELETE_FLG'] = 1;
            $param['UPDATED_AT'] = date("Y-m-d H:i:s");
            $res = $this->service('oen','deleteOen',$param);
            if ($res == true) {
                //画像削除処理
                if ($oen_info) {
                    $photo_name = $oen_info['photo'];
                    // アップロード先のファイル
                    $upload_file_path_pc  = ROOT_PATH."/img/pc/shop/".$photo_name;
                    $upload_file_path_sp  = ROOT_PATH."/img/sp/shop/".$photo_name;
                    Utility::deleteUpFile($upload_file_path_pc);
                    Utility::deleteUpFile($upload_file_path_sp);
                }
            }
        }
        $this->_redirect("/oen/index");
    }


    /** oen一覧画面(応援数高い順) */
    public function listAction () {
        $res = $this->service('oen','getShopOenList','');
        $this->view->list = $res;
    }


    /** 応援順のユーザー一覧) */
    public function oenuserlistAction () {
        $id = $this->getRequest()->getParam('id');
        if ( isset($id) ) {
            $param['shop_id'] = $id;
            $res = $this->service('oen','getOenUserList',$param);
        }
        $this->view->list = $res;
    }


//▼▼▼▼▼▼▼▼チップス▼▼▼▼▼▼▼▼▼▼▼▼

    private function _buck_regist_page($validate) {
        //入力した値を保持
        $this->view->detail = $validate;
        $this->view->error  = $validate['error_message'];
        $this->view->errorflg = $validate['error_flg'];
        //エラー画面入力値再セット
        $this->view->param = $validate;
        $this->view->latitude = $validate['latitude'];
        $this->view->longitude = $validate['longitude'];
        //入力画面に戻って、エラーメッセージを表示する
        $this->_helper->viewRenderer('input');
       }

}