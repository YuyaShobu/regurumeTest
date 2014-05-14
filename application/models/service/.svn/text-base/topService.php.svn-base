<?php

require_once (MODEL_DIR ."/service/abstractService.php");
require_once (LIB_PATH ."/Utility.php");

/**
 * トップサービス
 *
 * @package   top
 * @author    xiuhui yang 2013/07/01 新規作成
*/
class topService extends abstractService {

    /**
     * トップページ情報所得
     * @author: xiuhui yang
     * @param array $params
     *
     *
     * @return array $res
     *               keyname is total_count
     *                          new_rank
     *                          reguru_rank
     *                          pv_rank
     *
     *
    */
    public function getTopInfo($param)
    {
        //皆が作ったランキング現在の登録件数
        //$res['total_count'] = $this->logic(get_class($this),'getTotalCount' ,$param);
        if ($param['flg'] == 'reguru') {
            //お気に入りランキング一覧
            $param['flg'] = "";
            unset($param['flg']);
            $reguru_ranklist = $this->logic(get_class($this),'getReguruRankList' ,$param);
            //ユーザー画像ファイルチェック
            $reguru_ranklist = Utility::userImgExists($reguru_ranklist);
            $res = $this->_getRankingDetail($reguru_ranklist);
        } else if ($param['flg'] == 'pageview') {
            //閲覧数順ランキング一覧
            $param['flg'] = "";
            unset($param['flg']);
            $pv_ranklist = $this->logic(get_class($this),'getPageviewRankList' ,$param);
            //ユーザー画像ファイルチェック
            $pv_ranklist = Utility::userImgExists($pv_ranklist);
            $res = $this->_getRankingDetail($pv_ranklist);
        } else if (isset($param['user_id']) && $param['flg'] == 'follow') {
            //ログインしている場合、タイムライン一覧取得
            $param['flg'] = "";
            unset($param['flg']);
            $res_timeline = $this->logic(get_class($this),'getTimelineList' ,$param);
            //詳細情報を取得
            $timeline_info = $this->_getTimelineDetail($res_timeline,$param['user_id']);
            $res = $timeline_info;
            //$res['time_line'] = $this->_getTimeLineRanking($tl_ranklist);
        } else {
            //新着ランキング一覧
            $new_ranklist = $this->logic(get_class($this),'getNewRankList' ,$param);
            //ユーザー画像ファイルチェック
            $new_ranklist = Utility::userImgExists($new_ranklist);
            $res = $this->_getRankingDetail($new_ranklist);
        }
        return $res;
    }


    /**
     * ajaxタイムラインデータ取得
     * @author: xiuhui yang
     * @param array $params
     *
     *
     * @return array $res
     *
    */
    public function getTimelineList($param)
    {
        $timeline_ranklist = $this->logic(get_class($this),'getTimelineList' ,$param);

        //ランキング詳細情報を取得
        $res = $this->_getTimelineDetail($timeline_ranklist,$param['user_id']);

        $tags = Utility::makeTimeLineMoreReadTag($res);
        //$res['time_line'] = $timeline_info;
        //タイムラインデータ取得
        /*
        $timeline_ranklist = $this->logic(get_class($this),'getTimelineList' ,$param);
        //ユーザー画像ファイルチェック
        $timeline_ranklist = Utility::userImgExists($timeline_ranklist);
        $res = $this->_getRankingDetail($timeline_ranklist);
        */
        return $tags;
    }

    /**
     * ajax新着情報
     * @author: xiuhui yang
     * @param array $params
     *
     *
     * @return array $res
     *
    */
    public function getNewRankList($param)
    {
        //新着ランキング一覧
        $new_ranklist = $this->logic(get_class($this),'getNewRankList' ,$param);
        //ユーザー画像ファイルチェック
        $new_ranklist = Utility::userImgExists($new_ranklist);
        $res = $this->_getRankingDetail($new_ranklist);
        return $res;
    }

    /**
     * ajaxお気に入りランキング一覧
     * @author: xiuhui yang
     * @param array $params
     *
     *
     * @return array $res
     *
    */
    public function getReguruRankList($param)
    {
        $reguru_ranklist = $this->logic(get_class($this),'getReguruRankList' ,$param);
        //ユーザー画像ファイルチェック
        $reguru_ranklist = Utility::userImgExists($reguru_ranklist);
        $res = $this->_getRankingDetail($reguru_ranklist);
        return $res;
    }

    /**
     * ajax閲覧数順ランキング一覧
     * @author: xiuhui yang
     * @param array $params
     *
     *
     * @return array $res
     *
    */
    public function getPageviewRankList($param)
    {
        //閲覧数順ランキング一覧
        $pv_ranklist = $this->logic(get_class($this),'getPageviewRankList' ,$param);
        //ユーザー画像ファイルチェック
        $pv_ranklist = Utility::userImgExists($pv_ranklist);
        $res = $this->_getRankingDetail($pv_ranklist);
        return $res;
    }

    /**
     * ランキング詳細情報取得
     *
     * @param array $results
     * @return array $buf
     */
    public function _getRankingDetail($list) {
        if ($list) {
            //ランキング詳細情報取得
            for ( $i = 0; $i < count($list); $i++ ) {
                if (isset($list[$i]['rank_id'])) {
                    $param['rank_id'] = $list[$i]['rank_id'];
                    $list[$i]['detail'] = $this->logic(get_class($this),'getRankingDetail' ,$param);
                    //カテゴリ情報を取得 デフォルト三件
                    //$param['now_post_num'] = 0;
                    //$param['get_post_num'] = 3;
                    $list[$i]['category'] = $this->logic('ranking',  'getrankingCategory' ,$param);
                    //エリア情報表示
                    if ( $list[$i]['detail'] ) {
                        $str_pref = array();

                        $utility = new Utility;
                        for ( $j = 0; $j < 3; $j++ ) {
                            if ( isset($list[$i]['detail'][$j]['pref']) !="" ) {
                                array_push($str_pref,$list[$i]['detail'][$j]['pref']);
                            }
                            //設定の場合
                            //画像名ファイルサーバーにあるかチェック、なければノーイメージ表示
                            $list[$i]['detail'][$j]["width_size"] = "";
                            $list[$i]['detail'][$j]["style"] = "";
                            if (isset($list[$i]['detail'][$j]['rank_no']) && $list[$i]['detail'][$j]['shop_id'] != 0) {
                                $image_existed_flg = Utility::isImagePathExisted(Utility::CONST_RANKING_IMAGE_PATH.$list[$i]['detail'][$j]["photo"]);
                                if(!isset($list[$i]['detail'][$j]["photo"]) or $list[$i]['detail'][$j]["photo"] =="" or $image_existed_flg != true){
                                    $list[$i]['detail'][$j]["photo"] = Utility::CONST_NOIMAGE_IMAGE_PATH.Utility::CONST_NO_IMG_FILE_NAME;
                                    $list[$i]['detail'][$j]["height_size"] = Utility::CONST_IMG_THUMNAILER_SIZE;
                                    $list[$i]['detail'][$j]["style"] = Utility::CONST_IMG_THUMNAILER_STYLE;
                                } else {
                                    $list[$i]['detail'][$j]["photo"] = Utility::CONST_RANKING_IMAGE_PATH.$list[$i]['detail'][$j]["photo"];
                                    //サムネイルサイズ指定
                                    if ($utility->tdkEngine()) {
                                        $image_url = ROOT_URL.$list[$i]['detail'][$j]["photo"];
                                    } else {
                                         $image_url = ROOT_URL_TEST.$list[$i]['detail'][$j]["photo"];
                                    }
                                    $image = Utility::setThumnailerSize($image_url);
                                    $list[$i]['detail'][$j]["width_size"] = $image["width_size"];
                                    $list[$i]['detail'][$j]["height_size"] = $image["height_size"];
                                    $list[$i]['detail'][$j]["style"] = $image["style"];

                                    /*list($width,$height)=getimagesize($image_url);
                                    if($width > $height) {//横長の場合
                                        $list[$i]['detail'][$j]["height_size"] = Utility::CONST_IMG_THUMNAILER_SIZE;
                                        $list[$i]['detail'][$j]["style"] = Utility::CONST_IMG_THUMNAILER_STYLE;
                                    } else {//縦長の場合
                                        $list[$i]['detail'][$j]["width_size"] = Utility::CONST_IMG_THUMNAILER_SIZE;
                                    }
                                    */
                                }
                            //ランキング未設定の場合noimg_pendent.jpg画像を表示
                            } else {
                                $list[$i]['detail'][$j]["rank_no"] = $j+1;
                                $list[$i]['detail'][$j]["shop_name"] ="";
                                $list[$i]['detail'][$j]["photo"] = Utility::CONST_NOIMAGE_IMAGE_PATH.Utility::CONST_NO_IMG_PENDENT_NAME;
                                $list[$i]['detail'][$j]["height_size"] = Utility::CONST_IMG_THUMNAILER_SIZE;
                                $list[$i]['detail'][$j]["style"] = Utility::CONST_IMG_THUMNAILER_STYLE;
                            }
                        }
                        $pref = array_unique($str_pref);
                        $list[$i]['pref'] = implode('・',$pref);
                    }
                }
            }
        }
        return $list;
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
     * タイムライン詳細情報取得
     *
     * @param array $results
     * @return array $buf
     */
    private function _getTimelineDetail($list,$user_id) {
        //ランキング詳細情報取得
        $tl = array();
        $tl['ranking'] = array();
        $tl['beento'] = array();
        $tl['wantto'] = array();
        $tl['oen'] = array();
        if ( $list ) {

            for ( $i = 0; $i < count($list); $i++ ) {
                $type = $list[$i]['tl_type'];
                     switch ($type) {
                        case Utility::CONST_VALUE_TIMELINE_TYPE_RANKING://ranking情報
                        if ( isset($list[$i]['rank_id']) !="") {
                            $tl['ranking'][] =  $list[$i]['rank_id'];
                        }
                        break;
                        case Utility::CONST_VALUE_TIMELINE_TYPE_BEENTO://beento情報
                        	if ( isset($list[$i]['bt_id']) !="") {
                                $tl['beento'][] = $list[$i]['bt_id'];
                        	}
                        break;
                        case Utility::CONST_VALUE_TIMELINE_TYPE_WANTTO://wantto情報
                        	if ( isset($list[$i]['voting_id']) !="") {
                                $tl['wantto'][] = $list[$i]['voting_id'];
                        	}
                        break;
                        case Utility::CONST_VALUE_TIMELINE_TYPE_OEN://oen情報
                        	if ( isset($list[$i]['oen_id']) !="") {
                                $tl['oen'][] = $list[$i]['oen_id'];
                        	}
                        break;
                     }
                }
            }
            //各種類詳細情報取得
            $tl_list = array();
            //ranking
            if ($tl != array() && $tl['ranking'] != array() && is_array($tl['ranking']) && count($tl['ranking']) > 0 ) {
                for ( $j = 0; $j < count($tl['ranking']); $j++ ) {
                   $rank_id = $tl['ranking'][$j];
                   $detail = $this->_getTimeLineRanking($rank_id);
                   for ( $t = 0; $t < count($list); $t++ ) {
                       if ( $list[$t]['rank_id'] == $rank_id) {
                           $arr = $list[$t];
                           $arr['ranking_detail'] = $detail;
                           array_push($tl_list,$arr);
                       }
                   }
                }
            }
            //店バージョン詳細情報
            if ($tl != array()) {

	            $sql = $this->_getTimeLineShop($tl,$user_id);
	            if ($sql != "") {
	                $param['user_id'] = $user_id;
	                $param['sqltext'] = $sql;
	                $shop_detail = $this->logic('top',  'getShopDetail' ,$param);
	                if ($shop_detail) {

			            for ( $k = 0; $k < count($shop_detail); $k++ ) {
			                array_push($tl_list,$shop_detail[$k]);
			            }

			            //ユーザー画像ファイルチェック
			            $tl_list = Utility::userImgExists($tl_list);

			            //更新日付で降順でソート
			            foreach ($tl_list as $key => $value){
			                $key_id[$key] = $value['created_at'];
			            }
			            array_multisort ( $key_id , SORT_DESC , $tl_list);

	                }
		            //総件数取得
		            $where['user_id'] = $user_id;
		            $tl_list[0]['cnt'] = $this->logic('top',  'getTimelineCount' ,$where);

                }
            }

        return $tl_list;
    }

    /**
     * タイムライン詳細情報取得
     *
     * @param array $results
     * @return array $buf
     */
    private function _getTimelineDetail1($list,$user_id) {
        if ($list) {
            //ランキング詳細情報取得
//            $param['user_id'] = $user_id;
            for ( $i = 0; $i < count($list); $i++ ) {
                $type = $list[$i]['tl_type'];
                     switch ($type) {
                        case '1'://ranking情報
                            $rank_id = $list[$i]['rank_id'];
                            //ユーザー画像情報noimage対応
                            $image_existed_flg = Utility::isImagePathExisted(Utility::CONST_USER_IMAGE_PATH.$list[$i]["user_photo"]);
			                if(!isset($list[$i]["user_photo"]) or $list[$i]["user_photo"] =="" or $image_existed_flg != true){
			                    $list[$i]["user_photo"] = Utility::CONST_NOIMAGE_IMAGE_PATH.Utility::CONST_NO_IMG_FILE_NAME;
			                } else {
			                    $list[$i]["user_photo"] = Utility::CONST_USER_IMAGE_PATH.$list[$i]["user_photo"];
			                }
                            $list[$i]['ranking_detail'] = $this->_getTimeLineRanking($rank_id);
                            break;
                        case '2'://beento
                        	$param['bt_id'] = $list[$i]['bt_id'];
                            $list[$i]['detail'] = $this->logic('user',  'getMyBeeto' ,$param);
                            break;
                        case '3'://wantto
                            $param['voting_id'] = $list[$i]['voting_id'];
                            $list[$i]['detail'] = $this->logic('user',  'getMyWantto' ,$param);
                            break;
                        case '4'://oen
                            $param['oen_id'] = $list[$i]['oen_id'];
                            $list[$i]['detail'] = $this->logic('user',  'getMyOen' ,$param);
                            break;
                    }
                }
            }
        return $list;
    }

    /**
     * ランキング詳細情報取得
     *
     * @param array $results
     * @return array $buf
     */
    private function _getTimeLineRanking($rank_id) {

    	$res = array();
        $param['rank_id'] = $rank_id;
        $res['detail'] = $this->logic(get_class($this),'getRankingDetail' ,$param);
        //カテゴリ情報を取得 デフォルト三件
        $param['now_post_num'] = 0;
        $param['get_post_num'] = 3;
        $res['category'] = $this->logic('ranking',  'getrankingCategory' ,$param);
        //りぐる情報を取得、デフォルト三件
        $reguru_info = $this->logic('reguru',  'getReguruList' ,$param);
        $res['reguru'] = Utility::userImgExists($reguru_info);
        //エリア情報表示
        if ( $res['detail'] ) {
	        $str_pref = array();
            $utility = new Utility;
	        for ( $j = 0; $j < 3; $j++ ) {
	            if ( isset($res['detail'][$j]['pref']) !="" ) {
	                array_push($str_pref,$res['detail'][$j]['pref']);
	            }
	            //設定の場合
	            //画像名ファイルサーバーにあるかチェック、なければノーイメージ表示
                $res['detail'][$j]["width_size"] = "";
                $res['detail'][$j]["height_size"] = "";
	            if (isset($res['detail'][$j]['rank_no'])) {
	                $image_existed_flg = Utility::isImagePathExisted(Utility::CONST_RANKING_IMAGE_PATH.$res['detail'][$j]["photo"]);
	                if(!isset($res['detail'][$j]["photo"]) or $res['detail'][$j]["photo"] =="" or $image_existed_flg != true){
	                    $res['detail'][$j]["photo"] = Utility::CONST_NOIMAGE_IMAGE_PATH.Utility::CONST_NO_IMG_FILE_NAME;
                        $res['detail'][$j]["height_size"] = Utility::CONST_IMG_THUMNAILER_SIZE;
                        $res['detail'][$j]["style"] = Utility::CONST_IMG_THUMNAILER_STYLE;
	                } else {
	                    $res['detail'][$j]["photo"] = Utility::CONST_RANKING_IMAGE_PATH.$res['detail'][$j]["photo"];
                        //サムネイルサイズ指定
                        //$utility = new Utility;
                        if ($utility->tdkEngine()) {
                            $image_url = ROOT_URL.$res['detail'][$j]["photo"];
                        } else {
                             $image_url = ROOT_URL_TEST.$res['detail'][$j]["photo"];
                        }
                        $image = Utility::setThumnailerSize($image_url);
                        $res['detail'][$j]["width_size"] = $image["width_size"];
                        $res['detail'][$j]["height_size"] = $image["height_size"];
                        $res['detail'][$j]["style"] = $image["style"];
                        /*
                        list($width,$height)=getimagesize($image_url);
                        //$info = getimagesize($image_url);
                        if($width > $height) {//横長の場合
                            $res['detail'][$j]["height_size"] = Utility::CONST_IMG_THUMNAILER_SIZE;
                        } else {//縦長の場合
                            $res['detail'][$j]["width_size"] = Utility::CONST_IMG_THUMNAILER_SIZE;
                        }
                        */
	                }
	            //ランキング未設定の場合noimg_pendent.jpg画像を表示
	            } else {
	                $res['detail'][$j]["rank_no"] = $j+1;
	                $res['detail'][$j]["shop_name"] ="";
	                $res['detail'][$j]["photo"] = Utility::CONST_NOIMAGE_IMAGE_PATH.Utility::CONST_NO_IMG_PENDENT_NAME;
                    $res['detail'][$j]["height_size"] = Utility::CONST_IMG_THUMNAILER_SIZE;
                    $res['detail'][$j]["style"] = Utility::CONST_IMG_THUMNAILER_STYLE;
	            }
	        }
	        $pref = array_unique($str_pref);
	        $res['pref'] = implode('・',$pref);
        }
        return $res;
    }


    /**
     * ランキング詳細情報取得
     *
     * @param array $results
     * @return array $buf
     */
    private function _getTimeLineShop($list,$user_id) {

        //follower一覧取得
        $fl_list = $this->logic('user','getFollowList' ,$user_id);
        $followers = array();
        array_push($followers,$user_id);
        if ( $fl_list) {
             for ( $i = 0; $i < count($fl_list); $i++ ) {
                if (isset($fl_list[$i]['follow']) != "") {
                   array_push($followers,$fl_list[$i]['follow']);
                }
             }
        }
        $follower_list = join(',',$followers);

        $sql = "";
        if ($list !=array()) {
        if ( $list['beento'] != array() or $list['wantto'] != array() or  $list['oen'] != array()) {
            //詳細情報を取得する
            if  ( $list['beento'] != array() ) {
                $arr_beento = join(',',$list['beento']);
                $sql .="
                        SELECT
                            tl.timeline_id,
		                    tl.user_id,
                            tl.tl_type,
	                        tl.bt_id as keyname,
	                        tl.created_user_id,
	                        tl.created_at,
	                        tl.updated_at,
	                        bt.shop_id,
	                        bt.photo,
	                        bt.explain
		                FROM
		                    timeline tl
		                LEFT JOIN
		                    beento bt
		                ON
		                    tl.bt_id = bt.bt_id
		                WHERE
		                    tl.bt_id in ( {$arr_beento} )
		                AND
		                    tl.user_id  in ( {$follower_list} )
		               ";
            }
            if  ( $list['wantto'] != array() ) {
                $arr_wantto = join(',',$list['wantto']);
                $sql_wantto = "
                            SELECT
                                  tl.timeline_id,
		                          tl.user_id,
		                          tl.tl_type,
		                          tl.voting_id as keyname,
		                          tl.created_user_id,
		                          tl.created_at,
		                          tl.updated_at,
		                          wt.shop_id,
		                          wt.photo,
		                          wt.explain
			                FROM
			                    timeline tl
			                LEFT JOIN
			                    shop_voting wt
			                ON
			                    tl.voting_id = wt.voting_id
			                WHERE
			                    tl.voting_id in( {$arr_wantto} )
			                AND
			                    wt.voting_kind = 2
			                AND
			                    tl.user_id  in ( {$follower_list} )
			                ";
                if ( $list['beento'] != array() ) {
                    $sql = $sql. " UNION ".$sql_wantto;
                } else {
                    $sql = $sql_wantto;
                }
            }
            if  ( $list['oen'] != array() ) {
                $arr_oen = join(',',$list['oen']);
                $sql_oen = "
                                SELECT
                                    tl.timeline_id,
                                    tl.user_id,
			                        tl.tl_type,
			                        tl.oen_id as keyname,
			                        tl.created_user_id,
			                        tl.created_at,
			                        tl.updated_at,
			                        oen.shop_id,
			                        oen.photo,
			                        oen.explain
				                FROM
				                    timeline tl
				                LEFT JOIN
				                    oen
				                ON
				                    tl.oen_id = oen.oen_id
				                WHERE
				                    tl.oen_id in( {$arr_oen} )
				                AND
				                    tl.user_id  in ( {$follower_list} )
                                ";
                if ( $list['beento'] != array() or $list['wantto'] != array() ) {
                    $sql = $sql. " UNION ".$sql_oen;
                } else {
                    $sql = $sql_oen;
                }
            }

        }
        }
        return $sql;
    }

}
?>
