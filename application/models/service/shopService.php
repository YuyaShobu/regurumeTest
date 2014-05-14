<?php

require_once (MODEL_DIR ."/service/abstractService.php");
require_once (LIB_PATH ."/Utility.php");

/**
 * ショップサービス
 *
 * @package   shop
 * @author    xiuhui yang 2013/07/01 新規作成
 */
class shopService extends abstractService {

    /**
     * ショップ一覧データ取得
     * @param array $params
     *              keyname is shopname
     * @return array $res
     */
    function getShopListFromShopname($param)
    {
        $res = $this->logic(get_class($this),__FUNCTION__ ,$param);
        return $res;
    }


    /**
     * ショップ一覧データ取得
     * @param array $params
     *              keyname is shopname
     *                         pref
     * @return array $results
     */
    function getShopListFromPrefShopname($param)
    {
        $res = $this->logic(get_class($this),__FUNCTION__ ,$param);
        return $res;
    }


    /**
     * ajax infoでshop情報取得
     * @param array $param
     * @return array
     */
    function getShopInfoByShopName ($param) {
        $res = $this->logic(get_class($this), __FUNCTION__, $param);
        return $res;
    }

    /**
     * ショップ詳細データ取得
     * @param array $params
     *              keyname is shop_id
     *
     * @return array $res
    */
    public function getShopDetail($param)
    {
        $res = $this->logic(get_class($this),__FUNCTION__ ,$param);
        if ($res) {
            $res['shop_ranking_count'] = $this->logic(get_class($this),  'getRankCountFromShopid' , $param);
            $res['shop_beento_count'] = $this->logic(get_class($this),  'getBeentoCommentFromShopid' , $param);
            //みんな投稿した写真取得
            $photos = $this->logic(get_class($this),  'getShopPhotos' , $param);
            //画像チェック
            if ( $photos) {
                $arr = array();
                $cnt = 0;
	            foreach($photos as $key => $value) {
	            	if ( Utility::isImagePathExisted(Utility::CONST_BEENTO_IMAGE_PATH.$value['photo'])) {
	                   $arr[$cnt] = $photos[$key];
                       $cnt++;
	                }
	            }
            //ユーザー画像表示
            $res['photo'] = Utility::userImgExists($arr);
            }
        }

        return $res;
    }

    /**
     * ショップランクインされたランキング一覧情報取得
     * @param array $params
     *              keyname is shop_id
     *
     * @return array $res
    */
    public function getRankingFromShopid($param)
    {
        $list = $this->logic(get_class($this),__FUNCTION__ ,$param);
        if ($list) {
            //ユーザー画像チェック
            for ($i=0; $i < count($list); $i++) {
                //画像チェック
                $image_existed_flg = Utility::isImagePathExisted(Utility::CONST_RANKING_IMAGE_PATH.$list[$i]['photo']);
                if(!isset($list[$i]['photo']) or $list[$i]['photo'] =="" or $image_existed_flg != true){
                    $list[$i]['photo'] = Utility::CONST_NOIMAGE_IMAGE_PATH.Utility::CONST_NO_IMG_PROFILE_NAME;
                } else {
                    $list[$i]['photo'] = Utility::CONST_RANKING_IMAGE_PATH.$list[$i]['photo'];
                }
                //作成者画像チェック
                $image_existed = Utility::isImagePathExisted(Utility::CONST_USER_IMAGE_PATH.$list[$i]['user_photo']);
                if(!isset($list[$i]['user_photo']) or $list[$i]['user_photo'] =="" or $image_existed != true) {
                    $list[$i]['user_photo'] = Utility::CONST_NOIMAGE_IMAGE_PATH.Utility::CONST_NO_IMG_PROFILE_NAME;
                } else {
                    $list[$i]['user_photo'] = Utility::CONST_USER_IMAGE_PATH.$list[$i]['user_photo'];
                }
            }
        }
        return $list;
    }

    /**
     * ショップ行ったことユーザーコメント一覧取得
     * @param array $params
     *              keyname is shop_id
     *
     * @return array $res
    */
    public function getBeentoUserCommentList($param)
    {
        $list = $this->logic(get_class($this),__FUNCTION__ ,$param);
        if ($list) {
            //ユーザー画像チェック
            $utility = new Utility;
            for ($i=0; $i < count($list); $i++) {
                //画像チェック
                $image_existed_flg = Utility::isImagePathExisted(Utility::CONST_BEENTO_IMAGE_PATH.$list[$i]['photo']);

                if(!isset($list[$i]['photo']) or $list[$i]['photo'] =="" or $image_existed_flg != true){
                    $list[$i]['photo'] = Utility::CONST_NOIMAGE_IMAGE_PATH.Utility::CONST_NO_IMG_FILE_NAME;
                    $list[$i]["photo_width_size"] = "";
                    $list[$i]["photo_height_size"] = Utility::CONST_IMG_THUMNAILER_SIZE_BEENTO_PHOTO;
                    $list[$i]["photo_style"] = Utility::CONST_IMG_THUMNAILER_STYLE;
                } else {
                    $list[$i]['photo'] = Utility::CONST_BEENTO_IMAGE_PATH.$list[$i]['photo'];
                    //サムネイルサイズ指定
                    if ($utility->tdkEngine()) {
                        $image_url = ROOT_URL.$list[$i]['photo'];
                    } else {
                        $image_url = ROOT_URL_TEST.$list[$i]['photo'];
                    }
                    $image = Utility::setBeentoThumnailerSize($image_url,Utility::CONST_IMG_THUMNAILER_SIZE_BEENTO_PHOTO);
                    $list[$i]["photo_width_size"] = $image["width_size"];
                    $list[$i]["photo_height_size"] = $image["height_size"];
                    $list[$i]["photo_style"] = $image["style"];
                }

                //作成者画像チェック
                $image_existed = Utility::isImagePathExisted(Utility::CONST_USER_IMAGE_PATH.$list[$i]['user_photo']);
                if(!isset($list[$i]['user_photo']) or $list[$i]['user_photo'] =="" or $image_existed != true) {
                    if (isset($list[$i]["user_fb_photo"]) and $list[$i]["user_fb_photo"]) {
                        $list[$i]['user_photo'] = $list[$i]["user_fb_photo"];
                        $image_url = $list[$i]["user_fb_photo"];
                        $image = Utility::setBeentoThumnailerSize($image_url,Utility::CONST_IMG_THUMNAILER_SIZE_BEENTO_USER);
                        $list[$i]["userphoto_width_size"] = $image["width_size"];
                        $list[$i]["userphoto_height_size"] = $image["height_size"];
                        $list[$i]["userphoto_style"] = $image["style"];
                    } else {
                        $list[$i]["userphoto_width_size"] = "";
                        $list[$i]["userphoto_height_size"] = Utility::CONST_IMG_THUMNAILER_SIZE_BEENTO_USER;
                        $list[$i]["userphoto_style"] = Utility::CONST_IMG_THUMNAILER_STYLE;
                        $list[$i]['user_photo'] = Utility::CONST_NOIMAGE_IMAGE_PATH.Utility::CONST_NO_IMG_PROFILE_NAME;
                    }
                } else {
                    //サムネイルサイズ指定
                    if ($utility->tdkEngine()) {
                        $image_url = ROOT_URL.Utility::CONST_USER_IMAGE_PATH.$list[$i]['user_photo'];
                    } else {
                        $image_url = ROOT_URL_TEST.Utility::CONST_USER_IMAGE_PATH.$list[$i]['user_photo'];
                    }
                    $image = Utility::setBeentoThumnailerSize($image_url,Utility::CONST_IMG_THUMNAILER_SIZE_BEENTO_USER);
                    $list[$i]["userphoto_width_size"] = $image["width_size"];
                    $list[$i]["userphoto_height_size"] = $image["height_size"];
                    $list[$i]["userphoto_style"] = $image["style"];
                    $list[$i]['user_photo'] = Utility::CONST_USER_IMAGE_PATH.$list[$i]['user_photo'];
                }
            }
        }
        return $list;
    }

    /**
     * 新店舗情報登録
     * @author: xiuhui yang
     * @param array $param
     *              keyname is shop_name
     *                         address
     *                         shop_url
     *                         latitude
     *                         longitude
     *                         business_day
     *                         regular_holiday
     *
     * @return bool $res true/false
     *
    */
    public function registNewShopInfo($param)
    {
        $res = $this->logic(get_class($this),__FUNCTION__ ,$param);
        return $res;
    }


    /**
     * search画面でajaxで位置情報によりショップデータ取得
     *
     * @param array $params
     *              keyname is longitude
     *                         latitude
     *
     * @return array $results
     *               keyname is shop_name
     *                          shop_id
     *                          shop_url
    */
    public function getShopListFromLatLon($param) {
        $res = $this->logic(get_class($this),__FUNCTION__ ,$param);
        return $res;
    }


    /**
     * ショップ住所情報所得
     *
     * @param array $params
     *              keyname is shop_id
     *
     *
     * @return array $res
     *               keyname is latitude
     *                          longitude
     *
     */
     public function getShopAddressLatLon($param) {
        $res = $this->logic(get_class($this),__FUNCTION__ ,$param);
        return $res;
    }


    /**
     * カテゴリーIDからそのカテゴリーIDのランキングリスト(ショップリスト)を取得
     * @param none
     *
     * @return array $res
    */
    public function getShopRankingFromCategory($param) {
        $res = $this->logic(get_class($this),__FUNCTION__ ,$param);
        return $res;
    }


    /**
     * そのショップ行ったよユーザー一覧
     * @author: xiuhui yang
     * @param string $shop_id
     *
     * @return array $results
     *               keyname is user_id
     *                          mail_address
     *
    */
    public function getBeentoUserList($param)
    {
        $list = $this->logic(get_class($this),__FUNCTION__ ,$param);
        if ($list) {
            $list[0]['cnt'] = count($list);
            for ($i=0; $i < count($list); $i++) {
                //作成者画像チェック
                $image_existed = Utility::isImagePathExisted(Utility::CONST_USER_IMAGE_PATH.$list[$i]['user_photo']);
                if(!isset($list[$i]['user_photo']) or $list[$i]['user_photo'] =="" or $image_existed != true) {
                    $list[$i]['user_photo'] = Utility::CONST_NOIMAGE_IMAGE_PATH.Utility::CONST_NO_IMG_FILE_NAME;
                } else {
                    $list[$i]['user_photo'] = Utility::CONST_USER_IMAGE_PATH.$list[$i]['user_photo'];
                }
            }
        }
        return $list;
    }

     /**
     * そのショップ応援ユーザー一覧
     * @author: xiuhui yang
     * @param string $shop_id
     *
     * @return array $results
     *               keyname is user_id
     *                          mail_address
     *
    */
    public function getOenUserList($param)
    {
        $list = $this->logic(get_class($this),__FUNCTION__ ,$param);
        if ($list) {
            $list[0]['cnt'] = count($list);
            for ($i=0; $i < count($list); $i++) {
                //作成者画像チェック
                $image_existed = Utility::isImagePathExisted(Utility::CONST_USER_IMAGE_PATH.$list[$i]['user_photo']);
                if(!isset($list[$i]['user_photo']) or $list[$i]['user_photo'] =="" or $image_existed != true) {
                    $list[$i]['user_photo'] = Utility::CONST_NOIMAGE_IMAGE_PATH.Utility::CONST_NO_IMG_FILE_NAME;
                } else {
                    $list[$i]['user_photo'] = Utility::CONST_USER_IMAGE_PATH.$list[$i]['user_photo'];
                }
            }
        }
        return $list;
    }

    /**
     * ショップページビュー登録更新処理
     * @auther: xiuhui yang
     * @param array $param
     *              keyname is SHOP_ID
     *
     *
     *
     * @return int
     *
    */
    public function insertUpdateShopPageview($param)
    {
        //データ存在チェック
        $res = $this->logic(get_class($this),'insertUpdateShopPageview' ,$param);
        return $res;
    }

        /**
     * 市町区村コード取得
     * @author: xiuhui yang
     * @param string $city
     *
     * @return array $results
     *               keyname is city_code
     *
    */
    public function getCityCode($param)
    {
        $res = $this->logic(get_class($this),__FUNCTION__ ,$param);
        return $res;
    }

    /**
     * ショップ詳細画面その他各種情報取得
     * @author: xiuhui yang
     * @param array $para
     *              keyname is user_id
     *                         shop_id
     *
     * @return  array $results
     *                keyname is CNT
     *
    */
    public function getShopOtherInfo($para)
    {
        $ret = array();
        // 各種ボタン押せるかチェック
        // 「応援」ボタン押せるかチェック
        $ret['oen_flg'] = $this->logic('oen','checkOenExist',$para);

        // 「行った」ボタン押せるかチェック
        $ret['beento_flg'] = $this->logic('beento','checkBeentoExist',$para);

        // 「行きたい」ボタン押せるかチェック
        $ret['wantto_flg'] = $this->logic('voting','checkShopVotingExist',$para);

        // このshopに行きたいユーザー情報
         $wantto_list = $this->logic('voting','getUserList',$para);
        //ユーザー画像noimage対応
        $ret['wantto_list'] = Utility::userImgExists($wantto_list);


        // このshopに行ったユーザー情報
        $where['shop_id'] = $para['shop_id'];
        $beento_list = $this->logic('shop','getBeentoUserList',$where);
        //ユーザー画像noimage対応
        $ret['beento_list'] = Utility::userImgExists($beento_list);

        // 応援ユーザー一覧
        $oen_list = $this->logic('shop','getOenUserList',$where);
        //ユーザー画像noimage対応
        $ret['oen_list'] = Utility::userImgExists($oen_list);

        return $ret;
    }

    /**
     * ジャンル名でジャンルid取得
     * @param array $param
     */
    public function getGenreByValue ($param) {
    	$res = $this->logic(get_class($this),__FUNCTION__ ,$param);
    	return $res;
    }

    /**
     * ジャンルidでジャンル名取得
     * @param array $param
     */
    public function getGenreById ($param) {
    	$res = $this->logic(get_class($this),__FUNCTION__ ,$param);
    	return $res;
    }

    /**
     * 都/道/府/県コードで都/道/府/県取得
     * @param array $param
     * @return array
     */
    public function getPrefById ($param) {
    	$res = $this->logic(get_class($this),__FUNCTION__ ,$param);
    	return $res;
    }

    /**
     * 市/区/町/村コードで市/区/町/村取得
     * @param array $param
     * @return array
     */
    public function getCityById ($param) {
    	$res = $this->logic(get_class($this),__FUNCTION__ ,$param);
    	return $res;
    }

    /**
     * 親ジャンル取得
     * @return array
     */
    function getGenreParent ($param) {
    	$res = $this->logic(get_class($this),__FUNCTION__, $param);
    	return $res;
    }

    /**
     * 親genre_idで子genre_id取得
     * @param array $param
     * @return array
     */
    function getGenreByGenreId ($param) {
    	$res = $this->logic(get_class($this),__FUNCTION__, $param);
    	return $res;
    }

}
?>