<?php

/**
 *
 *
 * @package   FullcourseController
 * @copyright 2013
 * @author    xiuhui yang
 *
 */

require_once (LIB_PATH ."/Utility.php");

class FullcourseController extends AbstractController {

	public static $list;
    /** トップページを表示するアクション */
    public function indexAction () {

        // フルコースマスタ一覧取得
        $list = $this->service('fullcourse','getFullcourseMaster','');
        if ( $list == false) {
            // YAMLドキュメントを読み込む
            $list = yaml_parse_file(DATA_PATH."/fullcourse.yml");
        }
        for ( $i = 0; $i < count($list); $i++ ) {
            $param['user_id'] = $this->user_info['user_id'];
            $param['course_id'] = $list[$i]['course_id'];
            // フルコース詳細設定情報取得
            $list[$i]['detail'] = $this->service('fullcourse','getFullcourseDetail',$param);
        }
        $this->view->list = $list;
    }


    /** 入力アクション */
    public function inputAction () {
        $param['user_id'] = $this->user_info['user_id'];
        // 編集ボタン押された場合パラメター取得
        $param['course_id'] = $this->getRequest()->getParam('id');
        $res = $this->service('fullcourse','getFullcourseDetail',$param);
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
            $address_info = $this->service('user','getUserAddressLatLon',$param);
			$lat = $address_info['latitude'];
			$lon = $address_info['longitude'];
            // フルコースYAMLドキュメントを読み込む
            $list = yaml_parse_file(DATA_PATH."/fullcourse.yml");
            if(array_key_exists($param['course_id']-1, $list)){
                $course_name = $list[$param['course_id']-1]['course_name'];
            } else {
                // フルコースマスタ一覧取得
                $list = $this->service('fullcourse','getFullcourseMaster','');
                $course_name = $list[$param['course_id']-1]['course_name'];
            }
            $detail['course_name'] = $course_name;
            $this->view->detail = $detail;
        }
		$this->view->latitude = $lat;
		$this->view->longitude = $lon;
        $this->view->course_id = $this->getRequest()->getParam('id');
    }


    /** 登録アクション */
    public function insertAction () {
        // 入力画面からデータを取得して登録
        //$request = $this->_getAllParams();
        $req = $this->getRequest();
        $posts = $req->getPost();

        //登録のバリデーション
        $post_param = $this->getRequest()->getParams();
        $validate = $this->validate('fullcourse','registValidate', $post_param);

        //パラメータ不正の場合
        if (isset($validate['error_flg'])) {
            //値を保持して入力画面へ飛ばす。
            $this->_buck_regist_page($validate);
        } else {
            //登録項目編集
	        $inputData = array();
            $inputData['USER_ID'] =  $this->user_info['user_id'];
            $inputData['COURSE_ID'] = $req->getPost("course_id");
            $inputData['MENU'] = $req->getPost("menu");
            $shopid_lat_lon = $req->getPost("shop_id");
            $shopid_split = explode("_", $shopid_lat_lon);
            $shop_id = $shopid_split[0];
            $inputData['SHOP_ID'] = $shop_id;
            $inputData['EXPLAIN'] = $req->getPost("explain");
            //$inputData['PHOTO'] = $req->getPost("photo");

            //画像情報
            $upfile = $_FILES["photo"];
            //アップロードしたファイルの名称
            $name  = $upfile[ 'name' ];
            $file_name = pathinfo($name);
            //ファイル名をrenameする
            $new_name = $shop_id.'_'.$this->user_info['user_id'].'_fullcourse';
            $new_name .= $req->getPost("course_id");
            $new_name .= ".";
            $new_name .= $file_name['extension'];
            //画像uploadされた場合、ファイル名作成してDB項目にセット
            if ( $upfile[ 'error' ] == UPLOAD_ERR_OK && is_uploaded_file( $upfile['tmp_name'] ) ) {
                $photo = $shop_id.'/'.$new_name;
                $inputData['PHOTO'] = $photo;
            }

            // データ存在チェック
            $param['user_id'] = $this->user_info['user_id'];
            $param['course_id'] =  $req->getPost("course_id");
            $res = $this->service('fullcourse','checkFullcourseExist',$param);
            if ( $res["CNT"] > 0 ) {
                // 削除されたデータに再設定の場合delete_flg=0に設定
                $inputData['delete_flg'] = 0;
                $inputData['CREATED_AT'] = date("Y-m-d H:i:s");
                $inputData['UPDATED_AT'] = date("Y-m-d H:i:s");
                $ret = $this->service('fullcourse','updateFullcourse',$inputData);
            } else {
                $inputData['CREATED_AT'] = date("Y-m-d H:i:s");
                $ret = $this->service('fullcourse','insertFullcourse',$inputData);
            }
            //DBに正常登録されたら、画像UPLOAD処理する
            //PCアップロード先のパス
            $updir  = ROOT_PATH."/img/pc/shop/".$shop_id;
            //モバイルアップロード先のパス
            $updir_sp = ROOT_PATH."/img/sp/shop/".$shop_id;
            //アップロード
            Utility::uploadFile($updir,$updir_sp,$new_name,$upfile);
            $this->_redirect("/fullcourse/index");
        }
    }


    /** 削除アクション */
    public function deleteAction () {
        // 削除ボタン押された場合パラメター取得
        $id = $this->getRequest()->getParam('id');
        if ($id ) {
            $param['USER_ID'] =  $this->user_info['user_id'];
            $param['COURSE_ID'] = $id;
            $param['DELETE_FLG'] = 1;
            $param['UPDATED_AT'] = date("Y-m-d H:i:s");

            $where['user_id'] = $this->user_info['user_id'];
            $where['course_id'] = $id;
            $detail_info = $this->service('fullcourse','getFullcourseDetail',$where);
            $res = $this->service('fullcourse','deleteFullcourse',$param);
            //画像削除処理
            if ($detail_info) {
                $photo_name = $detail_info['photo'];
                //PCアップロード先のファイル
                $upload_file_path  = ROOT_PATH."/img/pc/shop/".$photo_name;
                //モバイルアップロード先のファイル
                $upload_file_path_sp  = ROOT_PATH."/img/sp/shop/".$photo_name;
                //PCファイル削除
                Utility::deleteUpFile($upload_file_path);
                //モバイルファイル削除
                Utility::deleteUpFile($upload_file_path_sp);
            }
        }
        $this->_redirect("/fullcourse/index");
    }


//▼▼▼▼▼▼▼▼チップス▼▼▼▼▼▼▼▼▼▼▼▼

    private function _buck_regist_page($validate) {
        //入力した値を保持
        $this->view->detail = $validate;
        $this->view->latitude = $validate['latitude'];
        $this->view->longitude = $validate['longitude'];
        $this->view->param = $validate;
        $this->view->course_id = $validate['course_id'];
        $this->view->error  = $validate['error_message'];
        $this->view->errorflg = $validate['error_flg'];
        //入力画面に戻って、エラーメッセージを表示する
        $this->render('input');
       }


}
