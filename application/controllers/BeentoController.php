<?php

/**
 *
 *
 * @package   BeentoController
 * @copyright 2013
 * @author    yuya yamamoto
 *
 */

require_once (LIB_PATH ."/Utility.php");

class BeentoController extends AbstractController {


    /** 行った事あるお店一覧画面 */
    public function indexAction () {
        $res = $this->service('beento','getBeentoList',$this->user_info['user_id']);
        $this->view->list = $res;
    }


    /** 行った店新規登録アクション */
    public function inputAction () {
        //ログイン状況チェック、未ログインの場合ログイン画面に飛ばす
        $this->_caseUnloginRedirect();
        //FB連携があったらフィードのチェックボックスを表示するためのフラグをアサイン
        $social_info = $this->service('oauth' , 'getSocialInfo' , $this->user_info['user_id']);
        $this->view->fb_connect_flg   =  $social_info['fb_connect_flg'];
    }


    /** 行った店編集アクション */
    public function editAction () {
        //ログイン状況チェック、未ログインの場合ログイン画面に飛ばす
        $this->_caseUnloginRedirect();
        //パラメータを取得
        $req = $this->getRequest();
        $bt_id = $req->getParam('id');
        if ( $bt_id != "" ) {
            // 詳細情報を取得
            $res = $this->service('beento','getBeentoDetail',$bt_id);
            //改行コード除去
            $res['longitude'] = str_replace(array("\r\n","\r","\n"), '', $res['longitude']);
            //画像存在チェック
            var_dump($res['photo']);
            if( !isset($res['photo']) or $res['photo'] =="" or Utility::isImagePathExisted(Utility::CONST_BEENTO_IMAGE_PATH.$res['photo']) != true){
                $res['photo'] = '';
            } else {
                $res['photo'] = Utility::CONST_BEENTO_IMAGE_PATH.$res['photo'];
            }
            $this->view->detail = $res;
        } else {
            $this->_redirect("/");
        }

    }

    /** 登録アクション */
    public function insertAction () {
        $param['user_id'] = $this->user_info['user_id'];
        // 入力画面からデータを取得して登録
        $req = $this->getRequest();
        $posts = $req->getPost();
        //新規店舗登録のバリデーション
        $posts['photo'] = $_FILES["photo"];
        $posts['user_id'] =  $this->user_info['user_id'];
        $posts['user_name'] =  $this->user_info['user_name'];

        $validate = $this->validate('beento','registValidate', $posts);
        //パラメータ不正の場合
        if (isset($validate['error_flg'])) {
            //値を保持して入力画面へ飛ばす。
            $this->_back_input_page($validate);
        } else {
            //編集の場合
            if ( isset($posts['bt_id']) !="" ) {
                $ret = $this->service('beento','updateBeento',$posts);
             } else {
                $ret = $this->service('beento','insertBeento',$posts);
             }
            $this->_redirect("/user/beentoshop/id/".$this->user_info['user_id']);
        }

    }


    /** 登録アクション */
    public function insertbakAction () {
        $param['user_id'] = $this->user_info['user_id'];
        // 入力画面からデータを取得して登録
        $req = $this->getRequest();
        $posts = $req->getPost();

        //新規店舗登録のバリデーション
        $post_param = $this->getRequest()->getParams();
        $posts['photo'] = $_FILES["photo"];

        $validate = $this->validate('beento','registValidate', $posts);
        //パラメータ不正の場合
        if (isset($validate['error_flg'])) {
            //値を保持して入力画面へ飛ばす。
            $this->_back_input_page($validate);
        } else {

            //$term = explode('_', $posts['shop_id'] );
            //$shop_id = $term[0];
            $shop_id = $posts['shop_id'];

            //画像情報
            $upfile = $_FILES["photo"];
            //アップロードしたファイルの名称
            $name  = $upfile['name'];
            $file_name_1 = pathinfo($name);
            //ファイル名をrenameする
            $new_name = $shop_id.'_'.$this->user_info['user_id'].'_beento';
            $new_name .= ".";

            //登録項目編集
            $inputData = array();
            $inputData['USER_ID'] = $this->user_info['user_id'];
            $inputData['SHOP_ID'] = $posts['shop_id'];
            $inputData['EXPLAIN'] = $posts['explain'];
            $inputData['UPDATED_AT'] = date("Y-m-d H:i:s");

            //画像uploadされた場合、ファイル名作成してDB項目にセット
            if ( $upfile[ 'error' ] == UPLOAD_ERR_OK && is_uploaded_file( $upfile['tmp_name'] ) ) {
            	$new_name .= $file_name_1['extension'];
                $photo = $shop_id.'/'.$new_name;
                $inputData['PHOTO'] = $photo;
            } else {
                //画像削除フラグ立った場合、DB項目クリア且つファイルサーバ画像削除
                if ( $posts['bt_id'] !="" && $posts['photo_delflg'] == "1") {
                    //画像削除する
                    $inputData['PHOTO'] = "";
                }
            }

            //編集の場合
            if ( $posts['bt_id'] !="" ) {
                $inputData['BT_ID'] = $posts['bt_id'];
                $ret = $this->service('beento','updateBeento',$inputData);
                $regist_flg = false;

             } else {
                // 新規登録の場合データ存在チェック
                //$param['user_id'] = $this->user_info['user_id'];
                //$param['shop_id'] =  $shop_id;
                //$data = $this->service('beento','checkBeentoExist',$param);
                //if ($data == 0) {
                    $ret = $this->service('beento','insertBeento',$inputData);
                    $regist_flg = true;
                //} else {
                //    echo'既に登録しています。';exit;
                //}
             }
	        //DBに正常登録されたら、画像UPLOAD処理する
	        if ( $ret == true ) {
	            // アップロード先のパス
	            $updir_pc  = ROOT_PATH."/img/pc/shop/".$shop_id;
	            $updir_sp  = ROOT_PATH."/img/sp/shop/".$shop_id;

	            $img_upload_flg = false;//イメージがアップされたかどうかでfacebookAPIを変えるのでフラグを持たせる。
	            //画像uploadされた場合、ファイル名作成してDB項目にセット
	            if ( $upfile[ 'error' ] == UPLOAD_ERR_OK && is_uploaded_file( $upfile['tmp_name'] ) ) {
	                Utility::uploadFile($updir_pc,$updir_sp,$new_name,$upfile);
	                $img_upload_flg = true;
	            } else {
	                //画像削除フラグ立った場合、DB項目クリア且つファイルサーバ画像削除
	                if ( $posts['bt_id'] !="" && $posts['photo_delflg'] == "1") {
	                    //画像削除する
	                    $old_photo_path =  ROOT_PATH.$posts['old_photo_path'];
	                    //PCアップロード先のパス
	                    Utility::deleteUpFile($old_photo_path);
	                }
                }
	            /*
	             * facebookへフィードする
	             * 編集ではなく、且つ投稿が成功した場合のみフィードする
	             *
	             */
	            if ( $regist_flg == true ) {
	                //ソーシャルフィード
	                $fb_feed_flg    = false;
	                $fb_feed_flg    = $this->getRequest()->getParam('fb_feed_flg');
	                //この値はテンプレート側でFBにフィードをするというチェックボックスからきている
	                if ($fb_feed_flg == '1') {
	                    $message   = "";
	                    $shop_id   = "";
	                    $shop_name = "";
	                    $shop_id   = $this->getRequest()->getParam('shop_id');
	                    $shop_name = $this->getRequest()->getParam('shop_name');
	                    $message   = $this->getRequest()->getParam('explain');

	                    //拡張子がある場合とない場合で使うAPIを分ける。
	                    if ($img_upload_flg == true) {
	                        $updir     = "img/pc/shop/".$shop_id.'/'.$shop_id.'_'.$this->user_info['user_id'].'_beento.jpg';
	                        //例：http://regurume.com/img/pc/shop/27587/27587_157_beento.jpg
	                        $kind = 'beento_albam';
	                        //
	                    } else {
	                        $updir     = 'http://regurume.com/img/pc/common/header_logo.png';
	                        $kind = 'beento_feed';
	                    }
	                    $param     = array (
	                        'user_id'   => $this->user_info['user_id'],
	                        'kind'      => $kind ,
	                        'message'   => $message ,
	                        'shop_id'   => $shop_id,
	                        'shop_name' => $shop_name,
	                        'user_name' => $this->user_info['user_name'],
	                        'photo_path'=> $updir
	                    );

	                    $this->service('socialapi', 'facebookfeed', $param);
	                }
	            }
	        }
            $this->_redirect("/user/beentoshop/id/".$this->user_info['user_id']);
        }

    }


    /** 削除アクション */
    public function ajaxdeletebeentoAction () {
        //ログイン状況チェック、未ログインの場合ログイン画面に飛ばす
        $this->_caseUnloginRedirect();
        //viewは読み込まない
        $this->_helper->viewRenderer->setNoRender();
        // 削除ボタン押された場合パラメター取得
        $res = true;
        $bt_id = $this->getRequest()->getParam('bt_id');
        $photo = $this->getRequest()->getParam('photo');
        if ( $bt_id != "" ) {
            $param['BEENTO_ID'] = $bt_id;
            $res = $this->service('beento','deleteBeento',$param);
            if ($res == true) {
                //画像削除処理
                if ($res && $photo != Utility::CONST_NOIMAGE_IMAGE_PATH.Utility::CONST_NO_IMG_FILE_NAME) {
                    // アップロード先のファイル
                    $utility = new Utility;
                    $res = Utility::deleteUpFile(ROOT_PATH.$photo);
                }
            }
        }
        echo json_encode($res);
        exit;
    }


    /** 行った店一覧画面(応援数高い順) */
    public function listAction () {
        $res = $this->service('beento','getShopBeentoList','');
        $this->view->list = $res;
    }


    /** 応援順のユーザー一覧) */
    public function beentoUserlistAction () {
        $id = $this->getRequest()->getParam('id');
        if ( isset($id) ) {
            $param['shop_id'] = $id;
            $res = $this->service('beento','getBeentoUserList',$param);
        }
        $this->view->list = $res;
    }


//▼▼▼▼▼▼▼▼チップス▼▼▼▼▼▼▼▼▼▼▼▼

    private function _back_input_page($validate) {
        //入力した値を保持
        $this->view->detail = $validate;
        $this->view->error  = $validate['error_message'];
        $this->view->errorflg = $validate['error_flg'];
        //エラー画面入力値再セット
        $this->view->param = $validate;
        $this->view->latitude = $validate['latitude'];
        $this->view->longitude = $validate['longitude'];
        //入力画面に戻って、エラーメッセージを表示する
        if ($validate['bt_id'] != "") {
            $this->_helper->viewRenderer('edit');
        } else {
            $this->_helper->viewRenderer('input');
        }
        //$this->_redirect("/index/index");
     }

}