<?php

require_once (MODEL_DIR ."/service/abstractService.php");
require_once (LIB_PATH ."/Utility.php");
require_once (MODEL_DIR ."/service/topService.php");


/**
 * ランキングサービス
 *
 * @package   ranking
 * @author    xiuhui yang 2013/07/01 新規作成
*/
class rankingService extends abstractService {

    /**
     * ランキング一覧データ取得
     * @author: xiuhui yang
     * @param array $param
     *              keyname is user_id
     *
     * @return array $ret
     *               keyname is user_id
     *                          rank_id
     *                          title
     *                          tag_id
     *                          detail
     *
    */
    public function getRankingList($param)
    {
        $res = $this->logic(get_class($this),__FUNCTION__ ,$param);
        $ret = $this->_getRankingList($res);
        return $ret;
    }

    /**
     * getLargeCategoryList
     * @auther: yamamoto
     *  if ctg is 'Large' , show getLargeCategoryList
     *  if ctg is 'Small' , show getSmallCategoryList
     * @param
     * @return
     *
     */
    public function getCategoryList($param)
    {
    	if ($param['ctg'] == 'Large') {
    		unset($param['ctg']);
    		$param = "";
            //大カテゴリ一覧を取得
    		$res = $this->logic(get_class($this),  'getLargeCategoryList' ,$param);
    	} elseif ($param['ctg'] == 'Small') {
    		unset($param['ctg']);
            //小カテゴリ一覧を取得
    		$res = $this->logic(get_class($this), 'getSmallCategoryList' ,$param);
    	} else {
    		print "ctgというキーでカテゴリーレベルを入力してください";
    		exit;
    	}
        return $res;
     }


    /**
     * getrankingCategory
     * @auther: xiuhui yang
     * ランキングカテゴリ一覧を取得
     * @param array $param
     *              keyname is user_id
     *                         rank_id
     *
     * @return array $results
     *               keyname is
     *                          rank_id
     *                          large_id
     *                          small_id
     */
    public function getrankingCategory($param)
    {
        $res = $this->logic(get_class($this),__FUNCTION__ ,$param);
        return $res;
    }

    /**
     * getSmallCategoryDetail
     * @auther: xiuhui yang
     * ランキングカテゴリ小カテゴリIDを取得
     * @param array $param
     *              keyname is user_id
     *                         rank_id
     *                         large_id
     *
     * @return array $results
     *               keyname is
     *                          small_id
     */
    public function getSmallCategoryDetail($param)
    {
        $res = $this->logic(get_class($this),__FUNCTION__ ,$param);
        return $res;
    }


    /**
     * ランキング詳細データ取得
     * @author: xiuhui yang
     * @param array $param
     *              keyname is user_id
     *                         rank_id
     *
     * @return array $results
     *
    */
    public function getRankingDetail($param)
    {
        $res = $this->logic(get_class($this),__FUNCTION__ ,$param);
        $ret = $this->_getRankingDetail($res);
        if (isset($ret['rank_id']) && $ret['rank_id']!="") {
            //タグ情報を取得
            $param['rank_id'] = $ret['rank_id'];
            $ret['tags'] = $this->logic(get_class($this),  'getrankingTags' ,$param);

            //カテゴリ情報を取得
            $category_ranking = $this->logic(get_class($this),  'getrankingCategory' ,$param);

            //カテゴリチェック情報表示のため
            $check_list = array();
            if (is_array($category_ranking) && count($category_ranking)>0) {
                foreach ($category_ranking as $key => $val){
                    if ( isset($val['large_id']) != "") {
                        $check_list[$key]['large_id'] = $val['large_id'];
                        $check_list[$key]['category_name'] = $val['category_name'];
                        $param2 = array();
                        $param2['large_id'] = $val['large_id'];
                        $param2['ctg'] = 'Small';
                        $res= $this->getCategoryList($param2);
                        if ( $res ) {
                            $check_list[$key]['smalllist'] = $res;
                        }
                        if ( isset($val['small_id']) != "") {
                            $check_list[$key]['small_id'] = $val['small_id'];
                        }
                    }
                }
            }
            $ret['category'] = $check_list;

            //作成者関連情報取得
            if (isset($ret['user_id']) && $ret['user_id']!="") {
                //作成者のフォロー数
                $ret['follows'] = $this->logic('user',  'followCount' , $ret);
                //作成者のフォロワー数
                $ret['followers'] = $this->logic('user',  'followerCount' , $ret);
                //作成者の他の投稿ランキング一覧(最新5件)
                $param['user_id'] = $ret['user_id'];
                $ret['otherranking'] = $this->logic(get_class($this),  'getOtherRanking' , $param);
            }
            //類似ランキング情報取得
            $list = $this->logic(get_class($this),  'getSimilarRanking' , $param);
            if ($list) {
                //類似度高い順でタイトル一覧取得
                for ($i=0; $i < count($list); $i++) {
                    $title = $list[$i]['title'];
                    //類似度計算
                    $similar = similar_text($ret['title'], $title, $per);
                    $list[$i]['similar_per'] = $per;
                }
                //類似度降順でソート
                foreach ($list as $key => $value){
                    $key_id[$key] = $value['similar_per'];
                }
                array_multisort ( $key_id , SORT_DESC , $list);
                $limit_list = array();
                $cnt = 0;
                for ($i=0; $i < count($list); $i++) {
                    if($cnt < 3 && isset($list[$cnt])){
                        //画像チェック
                        $image_existed_flg = Utility::isImagePathExisted(Utility::CONST_RANKING_IMAGE_PATH.$list[$cnt]['photo']);
                        if(!isset($list[$cnt]['photo']) or $list[$cnt]['photo'] =="" or $image_existed_flg != true){
                            $list[$cnt]['photo'] = Utility::CONST_NOIMAGE_IMAGE_PATH.Utility::CONST_NO_IMG_FILE_NAME;
                        } else {
                            $list[$cnt]['photo'] = Utility::CONST_RANKING_IMAGE_PATH.$list[$cnt]['photo'];
                        }

                        //作成者画像チェック
                        $image_existed = Utility::isImagePathExisted(Utility::CONST_USER_IMAGE_PATH.$list[$cnt]['user_photo']);
                        if(!isset($list[$cnt]['user_photo']) or $list[$cnt]['user_photo'] =="" or $image_existed != true) {

                            //FBユーザーの画像があったら、FB画像パスセット
                            if (isset($list[$cnt]["user_fb_photo"]) !="" ) {
                                $list[$cnt]['user_photo'] = $list[$cnt]["user_fb_photo"];
                            } else {
                                $list[$cnt]['user_photo'] = Utility::CONST_NOIMAGE_IMAGE_PATH.Utility::CONST_NO_IMG_PROFILE_NAME;
                            }

                            //$list[$cnt]['user_photo'] = Utility::CONST_NOIMAGE_IMAGE_PATH.Utility::CONST_NO_IMG_FILE_NAME;
                        } else {
                            $list[$cnt]['user_photo'] = Utility::CONST_USER_IMAGE_PATH.$list[$cnt]['user_photo'];
                        }

                        $limit_list[$cnt] = $list[$cnt];
                        $cnt++;
                    }
                }
                $ret['similarranking'] = $limit_list;
            }
        }

        /*
        for ( $i=1; $i<=3; $i++ ) {
            if (isset($ret['shop_id_'.$i]) && $ret['shop_id_'.$i]!="") {
                $where['shop_id'] = $ret['shop_id_'.$i];
                $where['rank_id'] = $ret['rank_id'];
                $ret['shop_ranking_list'.$i] = $this->logic(get_class($this),  'getRankingFromShopid' , $where);
            }
        }*/
        return $ret;
     }


    /**
     * getShopGenreList
     * @auther: xiuhui yang
     * ジャンル一覧を取得
     * @param array $param
     *              keyname is shop_id
     *
     * @return array $results
     *               keyname is
     *                          genre1_value
     *                          genre2_value
     *                          genre3_value
     */
    public function getShopGenreList($param)
    {
        $res = $this->logic(get_class($this),__FUNCTION__ ,$param);
        return $res;
    }


    /**
     * getGenreList
     * @auther: xiuhui yang
     * 大ジャンルマスタ一覧を取得
     * @param none
     *
     * @return array $results
     *               keyname is
     *                          genre1_id
     *                          genre1_value
     */
    public function getGenreList($param)
    {
        $res = array();
        //大ジャンルマスタ
        $res['genre1'] = $this->logic(get_class($this),'getGenre1List' ,$param);
        //中ジャンルマスタ
        $res['genre2'] = $this->logic(get_class($this),'getGenre2List' ,$param);
        //小ジャンルマスタ
        $res['genre3'] = $this->logic(get_class($this),'getGenre3List' ,$param);
        return $res;
    }


    /**
     * ランキング登録
     * @auther: xiuhui yang
     * @param array $params
     *
     *
     * @return bool true/false
     *
    */
    public function registRanking($params)
    {
        $res = $this->logic(get_class($this),__FUNCTION__ ,$params);
        return $res;
    }

    /**
     * ランキング編集
     * @auther: xiuhui yang
     * @param array $params
     *
     *
     * @return bool true/false
     *
    */
    public function updateRanking($params)
    {
        $res = $this->logic(get_class($this),__FUNCTION__ ,$params);
        return $res;
    }

    /**
     * ランキングデータ削除
     * @author: xiuhui yang
     * @param array $param
     *              keyname is USER_ID
     *                         RANK_ID
     *                         DELETE_FLG
     *                         UPDATED_AT
     *
     * @return bool true/false
     *
    */
    public function deleteRanking($param)
    {
        $res = $this->logic(get_class($this),__FUNCTION__ ,$param);
        return $res;
    }


        /**
     * ランキング情報取得する
     * @author: xiuhui yang
     * @param array $param
     *              keyname is rank_id
     *
     * @return int
     *
    */
    public function getrankingTags( $param ){
        $res = $this->logic(get_class($this),__FUNCTION__ ,$param);
        return $res;
    }


    /**
     * getPrefList
     * @auther: xiuhui yang
     * 都道府県マスタを取得
     * @param none
     *
     * @return array $results
     *               keyname is
     *                          pref_code
     *                          value
     */
    public function getPrefList($param)
    {
        $res = $this->logic(get_class($this),__FUNCTION__ ,$param);
        return $res;
    }



    /**
     * getCityList
     * @auther: xiuhui yang
     * 市区町村マスタ一覧を取得
     * @param array param
     *              keyname is pref_code
     *
     * @return array $results
     *               keyname is
     *                          pref_code
     *                          city_code
     *                          value
     */
    public function getCityList($param)
    {
        $res = $this->logic(get_class($this),__FUNCTION__ ,$param);
        return $res;
    }

    /**
     * getRankingList
     * @auther: xiuhui yang
     * ランキング一覧を取得(検索画面のリスト)
     * @param array $params
     *
     *
     *
     * @return array $results
     *
     */
    public function getRankList($params)
    {
        //$rankwhere = $this->_makeRankWhere($params);
        $rankwhere = $this->_makeRankWhereText($params);


        $res = $this->logic(get_class($this),__FUNCTION__ ,$rankwhere);
        $res[0]['count'] = $this->logic(get_class($this),'getRankingCount' ,$rankwhere);

        if (is_array($res) && $res[0]['count'] > 0) {

            for ($i=0; $i < count($res); $i++) {
                $where = $rankwhere;
                if (isset($res[$i]['rank_id']) && $res[$i]['rank_id'] !="") {
	                $where['rank_id'] = $res[$i]['rank_id'];
	                $res[$i]['detail'] = $this->logic(get_class($this),'getRankingDetail' ,$where);

	                //カテゴリ情報を取得 デフォルト三件
                    //$where['now_post_num'] = 0;
                    //$where['get_post_num'] = 3;
	                $res[$i]['category'] = $this->logic(get_class($this),  'getrankingCategory' ,$where);

	                //エリア情報表示
	                if (is_array($res[$i]['detail']) && count($res[$i]['detail']) > 0) {
	                    $str_pref = array();
                        $utility = new Utility;
	                    for ( $j = 0; $j < 3; $j++ ) {
	                        if ( isset($res[$i]['detail'][$j]['pref']) !="" ) {
	                            array_push($str_pref,$res[$i]['detail'][$j]['pref']);
	                        }

	                        //設定の場合
	                        //画像名ファイルサーバーにあるかチェック、なければノーイメージ表示
                            $res[$i]['detail'][$j]["width_size"] = "";
                            $res[$i]['detail'][$j]["height_size"] = "";
	                        if (isset($res[$i]['detail'][$j]['rank_no'])) {
		                        $image_existed_flg = Utility::isImagePathExisted(Utility::CONST_RANKING_IMAGE_PATH.$res[$i]['detail'][$j]['photo']);
	                            if(!isset($res[$i]['detail'][$j]['photo']) or $res[$i]['detail'][$j]['photo'] =="" or $image_existed_flg != true){
	                                $res[$i]['detail'][$j]['photo'] = Utility::CONST_NOIMAGE_IMAGE_PATH.Utility::CONST_NO_IMG_FILE_NAME;
                                    $res[$i]['detail'][$j]["height_size"] = Utility::CONST_IMG_THUMNAILER_SIZE;
                                    $res[$i]['detail'][$j]["style"] = Utility::CONST_IMG_THUMNAILER_STYLE;
	                            } else {
	                                $res[$i]['detail'][$j]['photo'] = Utility::CONST_RANKING_IMAGE_PATH.$res[$i]['detail'][$j]['photo'];
                                    //サムネイルサイズ指定
                                    if ($utility->tdkEngine()) {
                                        $image_url = ROOT_URL.$res[$i]['detail'][$j]["photo"];
                                    } else {
                                         $image_url = ROOT_URL_TEST.$res[$i]['detail'][$j]["photo"];
                                    }
                                    /*
                                    list($width,$height)=getimagesize($image_url);
                                    //$info = getimagesize($image_url);
                                    if($width > $height) {
                                        //横長の場合
                                        $res[$i]['detail'][$j]["height_size"] = Utility::CONST_IMG_THUMNAILER_SIZE;
                                    } else {
                                        //縦長の場合
                                        $res[$i]['detail'][$j]["width_size"] = Utility::CONST_IMG_THUMNAILER_SIZE;
                                    }
                                    */
                                    $image = Utility::setThumnailerSize($image_url);
                                    $res[$i]['detail'][$j]["width_size"] = $image["width_size"];
                                    $res[$i]['detail'][$j]["height_size"] = $image["height_size"];
                                    $res[$i]['detail'][$j]["style"] = $image["style"];
	                            }
	                           //ランキング未設定の場合noimg_pendent.jpg画像を表示
	                           } else {
	                               $res[$i]['detail'][$j]["rank_no"] = $j;
	                               $res[$i]['detail'][$j]["shop_name"] ="";
	                               $res[$i]['detail'][$j]["photo"] = Utility::CONST_NOIMAGE_IMAGE_PATH.Utility::CONST_NO_IMG_PENDENT_NAME;
                                   $res[$i]['detail'][$j]["height_size"] = Utility::CONST_IMG_THUMNAILER_SIZE;
                                   $res[$i]['detail'][$j]["style"] = Utility::CONST_IMG_THUMNAILER_STYLE;
	                           }
                           }

	                    $pref = array_unique($str_pref);
	                    $res[$i]['pref'] = implode('・',$pref);
	                }

                    //作成者画像チェック
                    $image_existed = Utility::isImagePathExisted(Utility::CONST_USER_IMAGE_PATH.$res[$i]['user_photo']);
                    if(!isset($res[$i]['user_photo']) or $res[$i]['user_photo'] =="" or $image_existed != true) {

                        //FBユーザーの画像があったら、FB画像パスセット
                        if (isset($res[$i]["user_fb_photo"]) !="" ) {
                            $res[$i]['user_photo'] = $res[$i]["user_fb_photo"];
                        } else {
                            $res[$i]['user_photo'] = Utility::CONST_NOIMAGE_IMAGE_PATH.Utility::CONST_NO_IMG_PROFILE_NAME;
                        }
                        //$res[$i]['user_photo'] = Utility::CONST_NOIMAGE_IMAGE_PATH.Utility::CONST_NO_IMG_FILE_NAME;
                    } else {
                        $res[$i]['user_photo'] = Utility::CONST_USER_IMAGE_PATH.$res[$i]['user_photo'];
                    }
                }
            }
        }
        return $res;
    }


    /**
     * getTagRankList
     * @auther: xiuhui yang
     * タグ名一致するランキング一覧
     * @param array $params
     *
     *
     *
     * @return array $results
     *
     */
    public function getTagRankList($params)
    {

        $tag_list = $this->logic(get_class($this),__FUNCTION__ ,$params);
        //ユーザー画像ファイルチェック
        $tag_list = Utility::userImgExists($tag_list);
        //ランキング詳細情報を取得
        $top = new topService();
        $res = $top->_getRankingDetail($tag_list);

        return $res;

    }


    /**
     * getShopList
     * @auther: xiuhui yang
     * ショップ一覧を取得
     * @param array param
     *              keyname is whereText
     *                         para
     *
     * @return array $results
     *
     */
    public function getShopList($params)
    {
        $shopwhere = $this->_makeShopWhereText($params);
        $list = $this->logic(get_class($this),__FUNCTION__ ,$shopwhere);
        if ($list) {
            $list[0]['count'] = $this->logic(get_class($this),'getShopCount' ,$shopwhere);
            for ( $i = 0; $i < count($list); $i++ ) {
                $shop_id = $list[$i]['shop_id'];
                $list[$i]['rankin_count'] = $this->logic('shop','getRankCountFromShopid' ,$shop_id);
            }
        }
        return $list;
    }


    /**
     * カテゴリ評価ランキングデータ存在チェック
     * @author: xiuhui yang
     * @param array $param
     *              keyname is user_id
     *                         rank_id
     *                         voting_type
     *                         voting_kind
     *                         create_user_id
     *
     * @return  array $res
     *                keyname is CNT
     *
    */
    public function checkRankingVotingExist($param)
    {
        $res = $this->logic(get_class($this),__FUNCTION__ ,$param);
        return $res;
    }



    /**
     * ランキングページビューデータ存在チェック
     * @auther: xiuhui yang
     * @param array $param
     *              keyname is USER_ID
     *                         RANK_ID
     *
     *
     * @return int
     *
    */
    public function checkRankPageviewExist($param)
    {
        $res = $this->logic(get_class($this),__FUNCTION__ ,$param);
        return $res;
    }


    /**
     * ランキングページビュー登録
     * @auther: xiuhui yang
     * @param array $param
     *              keyname is USER_ID
     *                         RANK_NO
     *                         PAGE_VIEW
     *
     * @return bool true/false
     *
    */
    public function insertRankPageview($param)
    {
        $res = $this->logic(get_class($this),__FUNCTION__ ,$param);
        return $res;
    }


    /**
     * ランキングページビュー更新
     * @author: xiuhui yang
     * @param array $param
     *              keyname is user_id
     *                         rank_id
     *
     * @return  bool true
     *
    */
     function updateRankPageview($param) {
        $res = $this->logic(get_class($this),__FUNCTION__ ,$param);
        return $res;
     }

     /**
     * ランキング順位変更1→2 or 2→1
     *
     *
     * @author: xiuhui yang
     * @param array $params
     *              keyname is rank_id
     *                         rank_no
     *
     * @return bool $ret
     */
    function changeRankNo1to2($params)
    {
    	$res = $this->logic(get_class($this),__FUNCTION__ ,$params);
        return $res;
    }


     /**
     * ランキング順位変更2→3 or 3→2
     *
     *
     * @author: xiuhui yang
     * @param array $params
     *              keyname is rank_id
     *                         rank_no
     *
     * @return bool $ret
     */
    function changeRankNo2to3($params)
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
     * ランキング一覧情報整形処理(N:1対応)
     *
     * @param array $results
     * @return array $buf
     */
    private function _getRankingList($results) {
        $buf = array();
        if (is_array($results) && count($results) > 0) {
            foreach($results as $key => $value) {
            $keyName = $value['rank_id'];
            if (!isset($buf[$keyName])) {
                $buf[$keyName] = array(
                    'user_id' => $value['user_id'],
                    'rank_id' => $value['rank_id'],
                    'title' => $value['title']
                );
            }
            if ($value['shop_id'] != '') {
                $buf[$keyName]['detail'][] = $value;
            }
        }
        return $buf;
        }
    }


    /**
     * ランキング詳細情報整形処理(N:1対応)
     *
     * @param array $results
     * @return array $buf
     */
    private function _getRankingDetail($results) {
        $buf = array();
        if (is_array($results) && count($results) > 0) {
            for ( $i=1; $i<=3; $i++ ) {
                if (isset($results[$i-1]['rank_no'])) {
                    $rank_no = $results[$i-1]['rank_no'];
                    $buf['rank_no_'.$rank_no] = $results[$i-1]['rank_no'];
                    $buf['shop_id_'.$rank_no] = $results[$i-1]['shop_id'];
                    $buf['shop_name_'.$rank_no] = $results[$i-1]['shop_name'];
                    $buf['explain_'.$rank_no] = $results[$i-1]['explain'];
                    $buf['latitude_'.$rank_no] = $results[$i-1]['latitude'];
                    $buf['longitude_'.$rank_no] = $results[$i-1]['longitude'];
                    $buf['address_'.$rank_no] = $results[$i-1]['address'];
                    $buf['pref_'.$rank_no] = $results[$i-1]['pref'];
                    $buf['pref_code_'.$rank_no] = $results[$i-1]['pref_code'];
                    $buf['city_'.$rank_no] = $results[$i-1]['city'];
                    $buf['genre1_value_'.$rank_no] = $results[$i-1]['genre1_value'];
                    $buf['genre2_value_'.$rank_no] = $results[$i-1]['genre2_value'];
                    $buf['genre3_value_'.$rank_no] = $results[$i-1]['genre3_value'];
                    //画像名ファイルサーバーにあるかチェック、なければnoimage表示
                    $image_existed_flg = Utility::isImagePathExisted(Utility::CONST_RANKING_IMAGE_PATH.$results[$i-1]['photo']);
                    if(!isset($results[$i-1]['photo']) or $results[$i-1]['photo'] =="" or $image_existed_flg != true){
                        $buf['photo_'.$rank_no] = Utility::CONST_NOIMAGE_IMAGE_PATH.Utility::CONST_NO_IMG_FILE_NAME;
                        //サムネイル対応
                        $buf['height_size_'.$rank_no] = Utility::CONST_IMG_THUMNAILER_SIZE_RANKING_DETAIL;
                        $buf['style_'.$rank_no] = Utility::CONST_IMG_THUMNAILER_STYLE;

                    } else {
                        $buf['photo_'.$rank_no] = Utility::CONST_RANKING_IMAGE_PATH.$results[$i-1]['photo'];
                        $utility = new Utility;
                        //サムネイルサイズ指定
                        if ($utility->tdkEngine()) {
                            $image_url = ROOT_URL.Utility::CONST_RANKING_IMAGE_PATH.$results[$i-1]['photo'];
                        } else {
                            $image_url = ROOT_URL_TEST.Utility::CONST_RANKING_IMAGE_PATH.$results[$i-1]['photo'];
                        }
                        $image = Utility::setRankDetailThumnailerSize($image_url);
                        $buf['width_size_'.$rank_no] = $image["width_size"];
                        $buf['height_size_'.$rank_no] = $image["height_size"];
                        $buf['style_'.$rank_no] = $image["style"];
                    }
                }
            }
            $buf['rank_id'] = $results[0]['rank_id'];
            $buf['title'] = $results[0]['title'];
            $buf['user_id'] = $results[0]['user_id'];
            $buf['page_view'] = $results[0]['page_view'];
            $buf['user_name'] = $results[0]['user_name'];
            //$buf['user_photo'] = $results[0]['user_photo'];
            //ユーザー画像名ファイルサーバーにあるかチェック、なければnoimage表示
            $image_existed = Utility::isImagePathExisted(Utility::CONST_USER_IMAGE_PATH.$results[0]['user_photo']);
            if(!isset($results[0]['user_photo']) or $results[0]['user_photo'] =="" or $image_existed != true) {

                //FBユーザーの画像があったら、FB画像パスセット
                if (isset($results[0]["user_fb_photo"]) !="" ) {
                    $buf['user_photo'] = $results[0]["user_fb_photo"];
                } else {
                    $buf['user_photo'] = Utility::CONST_NOIMAGE_IMAGE_PATH.Utility::CONST_NO_IMG_PROFILE_NAME;
                }

                //$buf['user_photo'] = Utility::CONST_NOIMAGE_IMAGE_PATH.Utility::CONST_NO_IMG_FILE_NAME;
            } else {
                $buf['user_photo'] = Utility::CONST_USER_IMAGE_PATH.$results[0]['user_photo'];
            }
            $buf['updated_at'] = $results[0]['updated_at'];
        }
        return $buf;
    }


    /**
     * 中カテゴリ一覧情報整形処理(N:1対応)
     *
     * @param array $results
     * @return array $buf
     */
    private function _getGenre2List($results) {
        $buf = array();
        if (is_array($results) && count($results) > 0) {
            foreach($results as $key => $value) {
            $keyName = $value['genre1_id'];
            if (!isset($buf[$keyName])) {
                $buf[$keyName] = array(
                    'genre1_id' => $value['genre1_id']
                );
            }
            $buf[$keyName]['detail'][] = $value;
        }
        return $buf;
        }
    }


    /**
     * 小カテゴリ一覧情報整形処理(N:1対応)
     *
     * @param array $results
     * @return array $buf
     */
    private function _getGenre3List($results) {
        $buf = array();
        if (is_array($results) && count($results) > 0) {
            foreach($results as $key => $value) {
            $keyName = $value['genre1_id'].'-'.$value['genre2_id'];
            if (!isset($buf[$keyName])) {
                $buf[$keyName] = array(
                'genre1_id' => $value['genre1_id'],
                'genre2_id' => $value['genre2_id']
                );
            }
            $buf[$keyName]['detail'][] = $value;
        }
        return $buf;
        }
    }

    /**
     * ランキング一覧情報整形処理(N:1対応)
     *
     * @param array $results
     * @return array $buf
     */
    private function _getRankList($results) {
        $buf = array();
        if (is_array($results) && count($results) > 0) {
            foreach($results as $key => $value) {
            $keyName = $value['user_id'].'-'.$value['rank_id'].'-'.$value['title'];
            if (!isset($buf[$keyName])) {
                $buf[$keyName] = array(
                    'user_id' => $value['user_id'],
                    'rank_id' => $value['rank_id'],
                    'title' => $value['title'],
                    'page_view' => $value['page_view']
                );
            }
            if ($value['shop_id'] != '') {
                $buf[$keyName]['detail'][] = $value;
            }
        }
        return $buf;
        }
    }


    /**
     * _makeRankWhereText
     *
     * ランキング抽出条件作成
     * @param  array $posts
     *
     * @return array ['wheretext'/'categorytext'/'keywordtext'/'now_post_num'/'get_post_num']
     */
    protected function _makeRankWhereText($posts)
    {
            //カテゴリ検索
            $cagetoryText = "";
            if (isset($posts['large_id']) && $posts['large_id']!="") {
                $cagetoryText = " INNER JOIN ( select distinct rank_id from ranking_category  d where d.delete_flg = 0 ";
                $wt3 ="";
                if ( is_array($posts['large_id']) && count($posts['large_id'])>1 ) {
                    for ($i=0;$i<count($posts['large_id']);$i++) {

                        if ($i>0) { $wt3 .= " or "; }
                            $large_id = $posts['large_id'][$i];
                            if (isset($posts['smalllist_'.$large_id]) && $posts['smalllist_'.$large_id] !="-1") {
                                $small_id = $posts['smalllist_'.$large_id];
                                $wt3 .= " d.large_id ={$large_id} and d.small_id = {$small_id} ";
                            } else {
                                $wt3 .= " d.large_id ={$large_id} ";
                            }
                    }

                    $cagetoryText .= " and ( $wt3 ) ";

                } else {
                    $large_id = $posts['large_id'][0];
                    $wt3 .= " and d.large_id = {$large_id}";

                    if (isset($posts['smalllist_'.$large_id]) && $posts['smalllist_'.$large_id] !="-1") {
                        $small_id = $posts['smalllist_'.$large_id];
                        $wt3 .= " and d.small_id = {$small_id} ";
                    }
                    $cagetoryText .=  $wt3;
                }
                $cagetoryText .= " ) e on a.rank_id = e.rank_id ";
            }

            $whereText = " r.delete_flg = 0 ";
            $para = array();
            // 条件文作成
            if (is_array($posts) && count($posts > 0)) {
            //エリア1検索
            if (isset($posts['pref']) && $posts['pref']!="-1") {
                $wt1 = " b.pref_code = {$posts['pref']} ";
                $whereText .= " and ( $wt1 ) ";
                //$para['pref_code'] = $posts['pref'];
            }

            //エリア2検索
            if (isset($posts['city']) && $posts['city']!="-1") {
                $wt2 = " b.city_code = {$posts['city']} ";
                $whereText .= " and ( $wt2 ) ";
                //$para['city_code'] = $posts['city'];
            }

            //フリキーワード検索(ランキングタイトル＋店名)
            $keywordText = "";
            $wt4 = "";
            if (isset($posts['search_keyword']) && trim($posts['search_keyword'])!="") {
                //店名XI'ANみたいの対策
                $posts['search_keyword'] = str_replace('\'', ' ', trim($posts['search_keyword']));
                $posts['search_keyword'] = str_replace('　', ' ', $posts['search_keyword']);
                $pr = explode(" ",$posts['search_keyword']);
                if ( is_array($pr) && count($pr)>1 ) {
                    for ($i=0;$i<count($pr);$i++) {
                        if ($i>0) {$wt4 .= " and ";}
                        $wt4 .= " r.title like '%{$pr[$i]}%' ";
                        //$para['search_keyword'][] = "%".$pr[$i]."%";
                    }
                    $wt5 = "";
                    for ($i=0;$i<count($pr);$i++) {
                        if ($i>0) {
                            $wt5 .= " and ";
                        }
                        $wt5 .= " b.shop_name collate utf8_unicode_ci like '%{$pr[$i]}%' ";
                        //$para['search_keyword1'][] = "%".$pr[$i]."%";
                    }
                    $keywordText .= "  and ( ( $wt4 ) or ( $wt5 ) ) ";
                } else {
                    $wt4 .= " and ( r.title collate utf8_unicode_ci like '%{$posts['search_keyword']}%' or b.shop_name collate utf8_unicode_ci like '%{$posts['search_keyword']}%'  ) ";
                    //$para['search_keyword'] = "%".$posts['search_keyword']."%";
                    //$para['search_keyword1'] = "%".$posts['search_keyword']."%";
                    $keywordText .=  $wt4;
                }
            }
        }

        $ret = array();
        $ret['wheretext'] = $whereText;
        $ret['categorytext'] = $cagetoryText;
        $ret['keywordtext'] = $keywordText;
        $ret['now_post_num'] = $posts['now_post_num'];
        $ret['get_post_num'] = $posts['get_post_num'];
        return $ret;
    }


    /**
     * _makeShopWhereText
     *
     * ショップ一覧抽出条件作成
     * @param  array $posts
     *
     * @return array ['wheretext'/'keywordtext'/'now_post_num'/'get_post_num']
     */
    protected function _makeShopWhereText($posts)
    {
        $whereText = " a.delete_flg = 0 ";
        $para = array();
        // 条件文作成
        if (is_array($posts) && count($posts > 0)) {

            //エリア1検索
            if (isset($posts['pref']) && $posts['pref']!="-1") {
                $wt1 = " a.pref_code = {$posts['pref']} ";
                $whereText .= " and ( $wt1 ) ";
            }

            //エリア2検索
            if (isset($posts['city']) && $posts['city']!="-1") {
                $wt2 = " a.city_code = {$posts['city']} ";
                $whereText .= " and ( $wt2 ) ";
            }

            //ジャンル1検索
            if (isset($posts['genre1']) && $posts['genre1']!="-1") {
                //エリア2検索
                if ($posts['genre2'] != "-1") {
                    $wt3 = " a.genre1 = '{$posts['genre2']}' or a.genre2 = '{$posts['genre2']}' or a.genre3 = '{$posts['genre2']}'";
                    $whereText .= " and ( $wt3 ) ";
                } else {
                    $wt3 = " SUBSTRING(a.genre1,1,LOCATE('_',a.genre1)-1) = '{$posts['genre1']}'
                            or
                                SUBSTRING(a.genre2,1,LOCATE('_',a.genre2)-1) = '{$posts['genre1']}'
                            or
                                SUBSTRING(a.genre3,1,LOCATE('_',a.genre3)-1) = '{$posts['genre1']}'
                            or a.genre1 = '{$posts['genre1']}' or a.genre2 = '{$posts['genre1']}' or a.genre3 = '{$posts['genre1']}'
                            ";
                    $whereText .= " and ( $wt3 ) ";
                }
            }

            //フリキーワード検索(店名+料理ジャンル名)
            $keywordText = "";
            if (isset($posts['search_keyword']) && trim($posts['search_keyword'])!="") {
                //店名XI'ANみたいの対策
                $posts['search_keyword'] = str_replace('\'', ' ', trim($posts['search_keyword']));
                $posts['search_keyword'] = str_replace('　', ' ', $posts['search_keyword']);
                $pr = explode(" ",$posts['search_keyword']);
                $wt5 = "";
                if ( is_array($pr) && count($pr)>1 ) {
                    for ($i=0;$i<count($pr);$i++) {
                        if ($i>0) {
                            $wt5 .= " and ";
                        }
                        $wt5 .= " a.shop_name collate utf8_unicode_ci like '%{$pr[$i]}%' ";
                    }
                    $keywordText .= "  and ( $wt5 )  ";
                } else {
                    $wt5 .= " and ( a.shop_name collate utf8_unicode_ci like '%{$posts['search_keyword']}%'  ) ";
                    $keywordText .=  $wt5;
                }
            }
        }
        $ret = array();
        $ret['wheretext'] = $whereText;
        $ret['keywordtext'] = $keywordText;
        $ret['now_post_num'] = $posts['now_post_num'];
        $ret['get_post_num'] = $posts['get_post_num'];
        return $ret;
    }

}
?>
