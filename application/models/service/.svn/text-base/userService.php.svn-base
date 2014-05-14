<?php

require_once (MODEL_DIR ."/service/abstractService.php");
require_once (LIB_PATH ."/Utility.php");
require_once (MODEL_DIR ."/service/topService.php");

/**
 * ユーザーサービス
 *
 * @package   user
 * @author    xiuhui yang 2013/07/01 新規作成
 */
class userService extends abstractService {


    /**
     * ユーザー詳細情報取得
     * @author: xiuhui yang
     * @param array $param
     *              keyname is user_id
     * @return array $res
     */
    public function getUserDetail($param)
    {
        $res = $this->logic(get_class($this),__FUNCTION__ ,$param);
        //画像チェック
        if ( $res ) {
            //画像名ファイルサーバーにあるかチェック、なければnoimage表示
            $image_existed_flg = Utility::isImagePathExisted(Utility::CONST_USER_IMAGE_PATH.$res["photo"]);
            if(!isset($res["photo"]) or $res["photo"] =="" or $image_existed_flg != true) {
                //FBユーザーの画像があったら、FB画像パスセット
                if (isset($res["fb_pic"]) !="" ) {
                    $res["photo"] = $res["fb_pic"];
                } else {
                    $res["photo"] = Utility::CONST_NOIMAGE_IMAGE_PATH.Utility::CONST_NO_IMG_PROFILE_NAME;
                }
                //$res["photo"] = Utility::CONST_NOIMAGE_IMAGE_PATH.Utility::CONST_NO_IMG_FILE_NAME;
            } else {
                $res["photo"] = Utility::CONST_USER_IMAGE_PATH.$res["photo"];
            }
        }
        return $res;
    }


    /**
     * ユーザーデータ更新処理
     * @author: xiuhui yang
     * @param array $param
     *              keyname is USER_ID
     *                         USER_NAME　　ユーザー名
     *                         MAIL_ADDRESS　メールアドレス
     *                         GENDER　　　　性別
     *                         BIRTHDAY　　　生年月日
     *                         ADDRESS1　　　住所1
     *                         ADDRESS2　　　住所2
     *                         ADDRESS3　　　住所3
     *                         UPDATED_AT　　更新日
     *                         PHOTO　　　　　画像
     * @return bool $res
     */
    public function updateUser($param)
    {
        $res = $this->logic(get_class($this),__FUNCTION__ ,$param);
        return $res;
    }


    /**
     * データ取得
     * @author: yuya yamamoto
     * @param array $param
     *              keyname is user_id
     * @return array $ret
     *               keyname is address1　都道府県
     *                          address2　市区町村
     *                          address　都道府県市区町村
     *                          lat　　　緯度
     *                          lon　　　軽度
     *
     */
    public function getUserAddressLatLon($param)
    {
        $res = $this->logic(get_class($this),'getUserAddress1Adress2' ,$param);
		$ret = $this->_getUserAddressLatLon($res);
        return $ret;
    }


    /**
     * 該当ユーザーのフルコース情報取得
     * @author: xiuhui yang
     * @param array $param
     *              keyname is user_id
     * @return array
     */
    public function getUserFullcourse($param)
    {
        $res = $this->logic(get_class($this),__FUNCTION__ ,$param);
        return $res;
    }


    /**
     * 該当ユーザーのTOP3登録情報取得
     * @author: xiuhui yang
     * @param array $param
     *              keyname is user_id
     *
     * @return array $ret
     *               keyname is category_id
     *                          category_name　　　カテゴリ名
     *                          detail　　　　　　　詳細情報
     */
    public function getUserTop3List($param)
    {
        $res = $this->logic(get_class($this),__FUNCTION__ ,$param);
        $ret = $this->_getUserTop3List($res);
        return $ret;
    }


   /**
     * ログイン時、該当ユーザー検索
     * @author: yuya yamamoto
     * @param array $param
     *              keyname is user_name
     *                        papassword
     * @return bool
     *             keyname is user_exist_flg　
     */
    public function searchUserIdFromMailAndPassward($param)
    {
        $res = $this->logic(get_class($this),__FUNCTION__ ,$param);
        if ($res) {
        	$res['user_exist_flg'] = true;
        } else {
        	$res['user_exist_flg'] = false;
        }
        return $res;
    }


   /**
     * ログイン時、該当ユーザー検索
     * @author: yuya yamamoto
     * @param array $param
     *              keyname is user_id
     * @return bool $res
     *              keyname is user_exist_flg　
     */
    public function searchUserInfoFromUserId($param)
    {
        $res = $this->logic(get_class($this),__FUNCTION__ ,$param);
        if ($res) {
        	$res['user_exist_flg'] = true;
        } else {
        	$res['user_exist_flg'] = false;
        }
        return $res;
    }


   /**
     * 新規登録時
     * @author: yuya yamamoto
     * @param array $param
     *              keyname is user_name
     *                        papassword
     *                        address1
     * @return array $res
     */
    public function signup($param)
    {

        $res = $this->logic(get_class($this),__FUNCTION__ ,$param);

        return $res;
    }

   /**
     * 退会時
     * @aouther: yuya yamamoto
     * @param  string
     *              user_id
     * @return boolian
     */
    public function retire($user_id)
    {

        $res = $this->logic(get_class($this),__FUNCTION__ ,$user_id);

        return $res;
    }


   /**
     * ユーザをフォローする
     * @aouther: yuya yamamoto
     * @param  array
     *              user_id
     *              follow_user_id
     * @return boolian
     */
    public function follow($param)
    {

        $res = $this->logic(get_class($this),__FUNCTION__ ,$param);

        return $res;
    }
   /**
     * ユーザをフォロー解除する
     * @aouther: yuya yamamoto
     * @param  array
     *              user_id
     *              follow_user_id
     * @return boolian
     */
    public function unfollow($param)
    {

        $res = $this->logic(get_class($this),__FUNCTION__ ,$param);

        return $res;
    }
   /**
     * ユーザがユーザをフォローしているかチェックする
     * @aouther: yuya yamamoto
     * @param  array
     *              user_id
     *              follow_user_id
     * @return boolian
     */
    public function checkFollow($param)
    {

        $res = $this->logic(get_class($this),__FUNCTION__ ,$param);

        return $res;
    }

   /**
     * ユーザがフォローされてる数をカウント
     * @aouther: yuya yamamoto
     * @param  array
     *              user_id
     * @return boolian
     */
    public function followCount($param)
    {

        $res = $this->logic(get_class($this),__FUNCTION__ ,$param);

        return $res;
    }


    /**
     * 任意のユーザのフォロワー数を調べる
     * @param string $param
     * @return  array $results
     *                keyname is CNT
    */
    public function followerCount($param)
    {
        $res = $this->logic(get_class($this),__FUNCTION__ ,$param);
        return $res;
    }

    /**
     * ユーザの使えるクーポンを全て取得
     * @param array   user_id
     * @return  array coupon_info
     *
    */
    public function getCouponInfo($param)
    {
        $res = $this->logic(get_class($this),__FUNCTION__ ,$param);
        return $res;
    }

    /**
     * 該当ユーザーの共通情報取得
     * @author: xiuhui yang
     * @param array $param
     *              keyname is user_id
     * @return array $res
     */
    public function getUserComInfoByUserid($param)
    {
        $res = array();
        //ユーザーの詳細情報を取得
        $res =  $this->logic(get_class($this),'getUserComDetail',$param);

        if($res['user_detail']) {
            //画像名ファイルサーバーにあるかチェック、なければnoimage表示
            $image_existed_flg = Utility::isImagePathExisted(Utility::CONST_USER_IMAGE_PATH.$res['user_detail']["photo"]);
            if(!isset($res['user_detail']["photo"]) or $res['user_detail']["photo"] =="" or $image_existed_flg != true) {
                if (isset($res['user_detail']["fb_pic"]) and $res['user_detail']["fb_pic"]) {
            		$res['user_detail']["photo"] = $res['user_detail']["fb_pic"];
            	} else {
	                $res['user_detail']["photo"] = Utility::CONST_NOIMAGE_IMAGE_PATH.Utility::CONST_NO_IMG_PROFILE_NAME;
            	}

            } else {
                $res['user_detail']["photo"] = Utility::CONST_USER_IMAGE_PATH.$res['user_detail']["photo"];
            }
        }
        return $res;
    }

    /**
     * 該当ユーザー作ったランキング詳細情報取得
     * @author: xiuhui yang
     * @param array $param
     *              keyname is user_id
     * @return array $res
     */
    public function getMyRankList($param)
    {
        $res = array();
        //マイランキング
        $myranklist =  $this->logic(get_class($this),__FUNCTION__,$param);
        //ユーザー画像ファイルチェック
        $myranklist = Utility::userImgExists($myranklist);

        //ランキング詳細情報を取得
        $top = new topService();
        $res = $top->_getRankingDetail($myranklist);

        //$res = $this->_getRankingDetail($myranklist);
        return $res;
    }

    /**
     * 該当ユーザーりぐったランキング詳細情報取得
     * @author: xiuhui yang
     * @param array $param
     *              keyname is user_id
     * @return array $res
     */
    public function getMyReguruRankList($param)
    {
        $res = array();
        //参考にするランキング
        $myregurulist =  $this->logic(get_class($this),__FUNCTION__,$param);
        //ユーザー画像ファイルチェック
        $myregurulist = Utility::userImgExists($myregurulist);
        //$res = $this->_getRankingDetail($myregurulist);

        //ランキング詳細情報を取得
        $top = new topService();
        $res = $top->_getRankingDetail($myregurulist);

        return $res;
    }

    /**
     * 行きたい店全部経度、緯度、店名取得
     * @param array $param
     * @return array
     */
    public function getMyWanttolatlog($param)
    {
    	$res = $this->logic(get_class($this),__FUNCTION__ ,$param);
    	return $res;
    }

   /**
     * 任意のユーザの行きたいお店
     * @author: xiuhui yang
     * @param array $param
     *              keyname is user_id
     * @return array $resuts
     */
    public function getMyWantto($param)
    {
        $res = $this->logic(get_class($this),__FUNCTION__ ,$param);
        if ($res) {
            for ( $i = 0; $i < count($res); $i++ ) {
                $shop_id = $res[$i]['shop_id'];
                $res[$i]['rankin_count'] = $this->logic('shop','getRankCountFromShopid' ,$shop_id);
            }
        }
        return $res;
    }

    /**
     * 応援している店全部経度、緯度、店名取得
     * @param array $param
     * @return array
     */
    public function getMyOenlatlog($param)
    {
    	$res = $this->logic(get_class($this),__FUNCTION__ ,$param);
    	return $res;
    }

    /**
     * 任意のユーザの応援している店一覧
     * @author: xiuhui yang
     * @param array $param
     *              keyname is user_id
     * @return array $resuts
     */
    public function getMyOen($param)
    {
        $res = $this->logic(get_class($this),__FUNCTION__ ,$param);
            if ($res) {
            for ( $i = 0; $i < count($res); $i++ ) {
                $shop_id = $res[$i]['shop_id'];
                $res[$i]['rankin_count'] = $this->logic('shop','getRankCountFromShopid' ,$shop_id);
            }
        }
        return $res;
    }

    /**
     * 行った店全部経度、緯度、店名取得
     * @param array $param
     * @return array
     */
    public function getMyBeetolatlog($param)
    {
    	$res = $this->logic(get_class($this),__FUNCTION__ ,$param);
    	return $res;
    }

    /**
     * 任意のユーザの行った店一覧
     * @author: xiuhui yang
     * @param array $param
     *              keyname is user_id
     * @return array $resuts
     */
    public function getMyBeeto($param)
    {
        $res = $this->logic(get_class($this),__FUNCTION__ ,$param);
            if ($res) {
            for ( $i = 0; $i < count($res); $i++ ) {
                $shop_id = $res[$i]['shop_id'];
                $res[$i]['ranking_count'] = $this->logic('shop','getRankCountFromShopid' ,$shop_id);

                //画像存在チェック
                $utility = new Utility;
                $image_existed = Utility::isImagePathExisted(Utility::CONST_BEENTO_IMAGE_PATH.$res[$i]['photo']);
                if(!isset($res[$i]['photo']) or $res[$i]['photo'] =="" or $image_existed != true) {
                    $res[$i]['photo'] = Utility::CONST_NOIMAGE_IMAGE_PATH.Utility::CONST_NO_IMG_FILE_NAME;
                    $res[$i]["photo_width_size"] = "";
                    $res[$i]["photo_height_size"] = Utility::CONST_IMG_THUMNAILER_SIZE_BEENTO_PHOTO;
                    $res[$i]["photo_style"] = Utility::CONST_IMG_THUMNAILER_STYLE;
                } else {
                    $res[$i]['photo'] = Utility::CONST_BEENTO_IMAGE_PATH.$res[$i]['photo'];
                    //サムネイルサイズ指定
                    if ($utility->tdkEngine()) {
                        $image_url = ROOT_URL.$res[$i]['photo'];
                    } else {
                        $image_url = ROOT_URL_TEST.$res[$i]['photo'];
                    }
                    $image = Utility::setBeentoThumnailerSize($image_url,Utility::CONST_IMG_THUMNAILER_SIZE_BEENTO_PHOTO);
                    $res[$i]["photo_width_size"] = $image["width_size"];
                    $res[$i]["photo_height_size"] = $image["height_size"];
                    $res[$i]["photo_style"] = $image["style"];

                }

                //ユーザー画像チェック
                $image_existed = Utility::isImagePathExisted(Utility::CONST_USER_IMAGE_PATH.$res[$i]['user_photo']);
                if(!isset($res[$i]['user_photo']) or $res[$i]['user_photo'] =="" or $image_existed != true) {
	                if (isset($res[$i]["user_fb_photo"]) and $res[$i]["user_fb_photo"]) {
	                    $res[$i]['user_photo'] = $res[$i]["user_fb_photo"];
	                    $image_url = $res[$i]["user_fb_photo"];
                        $image = Utility::setBeentoThumnailerSize($image_url,Utility::CONST_IMG_THUMNAILER_SIZE_BEENTO_USER);
                        $res[$i]["userphoto_width_size"] = $image["width_size"];
                        $res[$i]["userphoto_height_size"] = $image["height_size"];
                        $res[$i]["userphoto_style"] = $image["style"];
	                } else {
                        $res[$i]["userphoto_height_size"] = Utility::CONST_IMG_THUMNAILER_SIZE_BEENTO_USER;
                        $res[$i]["userphoto_style"] = Utility::CONST_IMG_THUMNAILER_STYLE;
	                	$res[$i]['user_photo'] = Utility::CONST_NOIMAGE_IMAGE_PATH.Utility::CONST_NO_IMG_PROFILE_NAME;
	                	$res[$i]["userphoto_width_size"] = "";
	                }
                } else {
                    $res[$i]['user_photo'] = Utility::CONST_USER_IMAGE_PATH.$res[$i]['user_photo'];

                    //サムネイルサイズ指定
                    if ($utility->tdkEngine()) {
                        $image_url = ROOT_URL.$res[$i]['user_photo'];
                    } else {
                        $image_url = ROOT_URL_TEST.$res[$i]['user_photo'];
                    }
                    $image = Utility::setBeentoThumnailerSize($image_url,Utility::CONST_IMG_THUMNAILER_SIZE_BEENTO_USER);
                    $res[$i]["userphoto_width_size"] = $image["width_size"];
                    $res[$i]["userphoto_height_size"] = $image["height_size"];
                    $res[$i]["userphoto_style"] = $image["style"];
                }

            }
        }
        return $res;
    }

     /**
     * 任意のユーザのfollow一覧情報取得
     * @author: xiuhui yang
     * @param array $param
     *              keyname is user_id
     * @return array $resuts
     */
    public function getFollowList($param)
    {
        $uid = $param['user_id'];
        $login_uid = $param['login_uid'];
        $list = $this->logic(get_class($this),__FUNCTION__ ,$uid);
        if ($list) {
            //画像チェック
            for ($i=0; $i < count($list); $i++) {
                $image_existed = Utility::isImagePathExisted(Utility::CONST_USER_IMAGE_PATH.$list[$i]['user_photo']);
                if(!isset($list[$i]['user_photo']) or $list[$i]['user_photo'] =="" or $image_existed != true) {

                if (isset($list[$i]["fb_pic"]) and $list[$i]["fb_pic"]) {
                    $list[$i]['user_photo'] = $list[$i]["fb_pic"];
                } else {
                    $list[$i]['user_photo'] = Utility::CONST_NOIMAGE_IMAGE_PATH.Utility::CONST_NO_IMG_PROFILE_NAME;
                }

                //$list[$i]['user_photo'] = Utility::CONST_NOIMAGE_IMAGE_PATH.Utility::CONST_NO_IMG_FILE_NAME;
                } else {
                    $list[$i]['user_photo'] = Utility::CONST_USER_IMAGE_PATH.$list[$i]['user_photo'];
                }
                //フォローチェック
                $f_check_params['follower'] = $login_uid;
                $f_check_params['follow']   =  $list[$i]['user_id'];
                $follow_flg = $this->checkFollow($f_check_params);
                if ($follow_flg) {
                    $list[$i]['follow_flg'] = true;
                } else {
                    $list[$i]['follow_flg'] = false;
                }
            }
        }
        return $list;
    }


     /**
     * 任意のユーザのfollower一覧情報取得
     * @author: xiuhui yang
     * @param array $param
     *              keyname is user_id
     * @return array $resuts
     */
    public function getFollowerList($param)
    {
        $uid = $param['user_id'];
        $login_uid = $param['login_uid'];
        $list = $this->logic(get_class($this),__FUNCTION__ ,$uid);
        if ($list) {
            //画像チェック
            for ($i=0; $i < count($list); $i++) {
                $image_existed = Utility::isImagePathExisted(Utility::CONST_USER_IMAGE_PATH.$list[$i]['user_photo']);
                if(!isset($list[$i]['user_photo']) or $list[$i]['user_photo'] =="" or $image_existed != true) {
                    if (isset($list[$i]["fb_pic"]) and $list[$i]["fb_pic"]) {
                        $list[$i]['user_photo'] = $list[$i]["fb_pic"];
                    } else {
                        $list[$i]['user_photo'] = Utility::CONST_NOIMAGE_IMAGE_PATH.Utility::CONST_NO_IMG_PROFILE_NAME;
                    }
                    //$list[$i]['user_photo'] = Utility::CONST_NOIMAGE_IMAGE_PATH.Utility::CONST_NO_IMG_FILE_NAME;
                } else {
                    $list[$i]['user_photo'] = Utility::CONST_USER_IMAGE_PATH.$list[$i]['user_photo'];
                }
	            //フォローチェック
	            $f_check_params['follower'] = $login_uid;
	            $f_check_params['follow']   =  $list[$i]['user_id'];
                $follow_flg = $this->checkFollow($f_check_params);
	            if ($follow_flg) {
	                $list[$i]['follow_flg'] = true;
	            } else {
	                $list[$i]['follow_flg'] = false;
	            }
            }
        }
        return $list;
    }


     /**
     * パスワード再設定
     * @author: xiuhui yang
     * @param array $params
     *              keyname is mail_address
     *                         password
     * @return array $resuts
     */
    public function updatePassword($params)
    {
        $res = $this->logic(get_class($this),__FUNCTION__ ,$params);
        return $res;
    }

 /*
 * ▼▼▼▼▼▼▼▼▼▼▼▼▼ここから整形用▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼
 * 【ルール】
 * 　■privateメソッドにすること
 * 　■$_privateの形でメソッドを表記する
 * __________________________________________________________________________
 *
 */
    /**
     * TOP3一覧情報整形処理(N:1対応)
     *
     * @author: xiuhui yang
     * @param  array $results
     * @return array $buf
     *               keyname is category_id
     *                          category_name　　　カテゴリ名
     *                          detail　　　　　　　詳細情報
     */
    private function _getUserTop3List($results) {
        $buf = array();
        if (is_array($results) && count($results) > 0) {
            foreach($results as $key => $value) {
                $keyName = $value['category_name'];
                if (!isset($buf[$keyName])) {
                    $buf[$keyName] = array(
                    'category_id' => $value['category_id'],
                    'category_name' => $value['category_name'],
                    'detail' => array()
                    );
                }
                if ($value['category_id'] != '') {
                    $buf[$keyName]['detail'][] = $value;
                }
            }
        }
        return $buf;
    }

   /**
     * 新規登録時
     * @author: yuya yamamoto
     * @param  array $res
     *               keyname is address1
     *                          address2
     * @return array $ret
     *               keyname is latitude　　　緯度
     *                          longitude　　　軽度
     */
    private function _getUserAddressLatLon($res)
    {
    	$address = $res['address1'] . $res['address2'];
    	$ret['address']  = $address;
    	$ret['address1'] = $res['address1'];
    	$ret['address2'] = $res['address2'];
		$address_url = "?sensor=false&region=jp&address=".urlencode(mb_convert_encoding($address, 'UTF8', 'auto'));
		$google_latlon_datas = file_get_contents('http://maps.googleapis.com/maps/api/geocode/json'.$address_url);
		// JSONデータをPHPの値に変換する
		$geo = json_decode($google_latlon_datas);
		$status = $geo->status;
		// エラー判定をして値を取得する
		if($status == "OK"){
			$results = $geo->results[0];
			$geometry = $results->geometry;
			$location = $geometry->location;
			$ret['latitude'] = $location->lat; // 緯度を取得
			$ret['longitude'] = $location->lng; // 経度を取得
		} else {
	        $ret['latitude']  = 35.6938401;   //デフォルトは新宿にしておく
	        $ret['longitude'] = 139.7035494;
		}
		return $ret;
    }

}
?>