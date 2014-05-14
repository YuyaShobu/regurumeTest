<?php

/**
 *
 *
 * @package   Top3Controller
 * @copyright 2013
 * @author    xiuhui yang
 *
 */

require_once (LIB_PATH ."/Utility.php");

class Top3Controller extends AbstractController {

    /** カテゴリ選択画面 */
    public function indexAction () {
        $res = $this->service('top3','getCategoryTop3','');
        $this->view->list = $res;
    }

    /** カテゴリtop3一覧画面 */
    public function listAction () {
        // 入力値取得
        $req = $this->getRequest();
        $category_id = $req->getPost("category_id");
        $category_name = $req->getPost("category_name");
        // カテゴリ毎に一覧情報取得
        for ( $i = 1; $i <= 3; $i++ ) {
            $param['uid'] = $this->user_info['user_id'];
            $param['category_id'] = $category_id;
            $param['top3_no'] = $i;
            $res[$i-1] = $this->service('top3','getTop3Detail',$param);
        }
        $this->view->list = $res;
        $this->view->category_id = $category_id;
        $this->view->category_name = $category_name;
    }

    /** top3設定画面 */
    public function inputAction () {
        // 入力値取得
        $req = $this->getRequest();
        $param['uid'] = $this->user_info['user_id'];
        $param['category_id'] = $this->getRequest()->getParam('id');
        $param['top3_no'] = $this->getRequest()->getParam('no');
        // カテゴリ名取得
        $cg_name = $this->service('top3','getCategoryTop3',$param['category_id']);
        if ( is_array($cg_name) && count($cg_name) > 0) {
            $category_name = $cg_name[0]['category_name'];
            $this->view->category_name = $category_name;
        }
        // 詳細情報を取得
        $res = $this->service('top3','getTop3Detail',$param);
        if ( isset($res['shop_id']) && $res['shop_id'] !=0 ) {
            //店の住所取得
            $param['shop_id'] = $res['shop_id'];
            $address_info = $this->service('shop','getShopAddressLatLon',$param);
            $lat = $address_info['latitude'];
            $lon = $address_info['longitude'];
            //改行コード除去
            $lon = str_replace(array("\r\n","\r","\n"), '', $lon);
            $this->view->detail = $res;
        } else {
            $param['user_id'] = $this->user_info['user_id'];
            $address_info = $this->service('user','getUserAddressLatLon',$param);
            $lat = $address_info['latitude'];
            $lon = $address_info['longitude'];
        }
        $this->view->latitude = $lat;
        $this->view->longitude = $lon;
        $this->view->param = $param;
    }

    /** 登録画面 */
    public function insertAction () {
        // 入力画面からデータを取得
        $req = $this->getRequest();
        $posts = $req->getPost();

        //登録のバリデーション
        $post_param = $this->getRequest()->getParams();
        $validate = $this->validate('top3','registValidate', $post_param);
        //パラメータ不正の場合
        if (isset($validate['error_flg'])) {
            //値を保持して入力画面へ飛ばす。
            $this->_buck_regist_page($validate);
        } else {
            $term = explode('_', $posts['shop_id'] );
            $shop_id = $term[0];
            //画像情報
            $upfile = $_FILES["photo"];
            //アップロードしたファイルの名称
            $name  = $upfile[ 'name' ];
            $file_name = pathinfo($name);
            //ファイル名をrenameする
            $new_name = $shop_id.'_'.$this->user_info['user_id'].'_top3';
            $new_name .= '-cg'.$posts['category_id'].'-no'.$posts['top3_no'];
            $new_name .= ".";
            $new_name .= $file_name['extension'];

            //登録項目編集
            $inputData = array();
            $inputData['UID'] = intval($this->user_info['user_id']);
            $inputData['CATEGORY_ID'] = $posts['category_id'];
            $inputData['TOP3_NO'] = $posts['top3_no'];
            $inputData['SHOP_ID'] = intval($shop_id);
            $explain = strip_tags($posts['explain']);
            $inputData['EXPLAIN'] = $explain;
            //画像uploadされた場合、ファイル名作成してDB項目にセット
            if ( $upfile[ 'error' ] == UPLOAD_ERR_OK && is_uploaded_file( $upfile['tmp_name'] ) ) {
                $photo = $shop_id.'/'.$new_name;
                $inputData['PHOTO'] = $photo;
            }

            // データ存在チェック
            $param['uid'] = $this->user_info['user_id'];
            $param['category_id'] =  $posts['category_id'];
            $param['top3_no'] =  $posts['top3_no'];
            $res = $this->service('top3','checkTop3Exist',$param);
            if ( $res["CNT"] > 0 ) {
                // 削除されたデータに再設定の場合delete_flg=0に設定
                $inputData['delete_flg'] = 0;
                $inputData['CREATED_AT'] = date("Y-m-d H:i:s");
                $this->service('top3','updateTop3',$inputData);
            } else {
                $inputData['CREATED_AT'] = date("Y-m-d H:i:s");
                $inputData['UPDATED_AT'] = date("Y-m-d H:i:s");
                //var_dump($inputData);exit;
                $this->service('top3','insertTop3',$inputData);
            }
            //DBに正常登録されたら、画像UPLOAD処理する
            // PCアップロード先のパス
            // モバイルアップロード先のパス
            $updir  = ROOT_PATH."/img/pc/shop/".$shop_id;
            $updir_sp  = ROOT_PATH."/img/sp/shop/".$shop_id;
            Utility::uploadFile($updir,$updir_sp,$new_name,$upfile);
            $this->_redirect("/top3/index");
        }
    }

    /** 削除アクション */
    public function deleteAction () {
        // 削除ボタン押された場合パラメター取得
        $category_id = $this->getRequest()->getParam('id');
        $top3_no = $this->getRequest()->getParam('no');
        if ( $category_id!="" && $top3_no!="" ) {
            //top3詳細情報取得
            $param['uid'] = $this->user_info['user_id'];
            $param['category_id'] = $category_id;
            $param['top3_no'] = $top3_no;
            $detail_info = $this->service('top3','getTop3Detail',$param);
            //削除条件
            $updateData['UID'] = $this->user_info['user_id'];
            $updateData['CATEGORY_ID'] = $category_id;
            $updateData['TOP3_NO'] = $top3_no;
            //削除フラグ
            $updateData['DELETE_FLG'] = 1;
            //削除時刻
            $updateData['UPDATED_AT'] = date("Y-m-d H:i:s");
            //データ削除

            $res = $this->service('top3','updateTop3',$updateData);

            if ($res == true) {
                //画像削除処理
                if ($detail_info) {
                    $photo_name = $detail_info['photo'];
                    // PCアップロード先のファイル
                    $upload_file_path  = ROOT_PATH."/img/pc/shop/".$photo_name;
                    // モバイルアップロード先のファイル
                    $upload_file_path_sp  = ROOT_PATH."/img/sp/shop/".$photo_name;
                    Utility::deleteUpFile($upload_file_path);
                    Utility::deleteUpFile($upload_file_path_sp);
                }
            }
        }
        $this->_redirect("/top3/index");
    }

//▼▼▼▼▼▼▼▼チップス▼▼▼▼▼▼▼▼▼▼▼▼

    private function _buck_regist_page($validate) {
        //入力した値を保持
        $this->view->detail = $validate;
        $this->view->latitude = $validate['latitude'];
        $this->view->longitude = $validate['longitude'];
        //エラー画面入力値再セット
        $this->view->param = $validate;
        $this->view->error  = $validate['error_message'];
        $this->view->errorflg = $validate['error_flg'];
        //入力画面に戻って、エラーメッセージを表示する
        $this->_helper->viewRenderer('input');
       }
}