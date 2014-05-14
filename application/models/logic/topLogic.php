<?php

require_once (MODEL_DIR ."/logic/abstractLogic.php");
require_once 'Zend/Exception.php';
require_once 'Zend/Db.php';

/**
 * トップロジック
 *
 * @package   top
 * @author    xiuhui yang 2013/07/01 新規作成
 */
class topLogic extends abstractLogic {


    /**
     * 皆が作ったランキング現在の登録件数
     * @auther: xiuhui yang
     * @param array $param
     *
     * @return int $ret
     *
    */
    public function getTotalCount()
    {
        $ret = 0;
        //データ抽出SQLクエリ
        $sql = "
                SELECT
                     COUNT(*) AS CNT
                FROM
                    ranking
                WHERE
                    delete_flg = 0
                ";
        $results = $this->db->selectPlaceQuery($sql,array());
        //一件も取れていなければFALSEを返す
        if ( $results == NULL  or count($results) == 0) {
            $ret = 0;
        } else {
            $ret = $results[0]['CNT'];
        }
        return $ret;
    }


    /**
     * タイムラインランキング一覧
     * @auther: xiuhui yang
     * @param none
     *
     * @return array $results
     *
    */
    public function getTimelineList($param)
    {

        //follower一覧取得
        $sql1 = "
                SELECT
                     follow,follower
                FROM
                     follow
                WHERE
                    follower = ?
                ";
        $fl_list = $this->db->selectPlaceQuery($sql1,array($param['user_id']));
        $followers = array();
        array_push($followers,$param['user_id']);
        if ( $fl_list != NULL  and count($fl_list) > 0 ) {
        	 for ( $i = 0; $i < count($fl_list); $i++ ) {
        	    if (isset($fl_list[$i]['follow']) != "") {
        	       array_push($followers,$fl_list[$i]['follow']);
        	    }
        	 }
        }
        $follower_list = join(',',$followers);
        $now_post_num = $param['now_post_num'];
        $get_post_num = $param['get_post_num'];
        //$user_id = $param['user_id'];
        //データ抽出SQLクエリ
        $sql = "
                SELECT
                    tl.*,r.rank_id,r.title,
                    ifnull(v.page_view,'0') as page_view,
                    ifnull(u.photo,'') as user_photo,
                    ifnull(u.user_name,'') as user_name,
                    u.fb_pic as user_fb_photo,
                    bt.shop_id as bt_shop_id,
                    wt.shop_id as wt_shop_id,
                    oen.shop_id as oen_shop_id
                FROM
                     (SELECT
                        tl5.*
                        FROM (
                            select tl1.* from timeline tl1 inner join beento bt on tl1.bt_id = bt.bt_id and bt.bt_id is not null where tl1.user_id in ( {$follower_list} )
                            union all
                            select tl2.* from timeline tl2 inner join shop_voting  on tl2.voting_id = shop_voting.voting_id and shop_voting.voting_id is not null where tl2.user_id in ( {$follower_list} )
                            union all
                            select tl3.* from timeline tl3 inner join oen  on tl3.oen_id = oen.oen_id and oen.oen_id is not null where tl3.user_id in ( {$follower_list} )
                            union all
                            select tl4.* from timeline tl4 inner join ranking  on tl4.rank_id = ranking.rank_id and ranking.delete_flg = 0 where tl4.user_id in ( {$follower_list} )) tl5
                            ) tl
                LEFT JOIN
                    ranking r
                ON
                    tl.rank_id = r.rank_id
                AND
                    tl.tl_type = ".Utility::CONST_VALUE_TIMELINE_TYPE_RANKING."
                LEFT JOIN
                    rank_pageview v
                ON
                    r.rank_id = v.rank_id
                LEFT JOIN
                    user u
                ON
                    tl.created_user_id = u.user_id
                LEFT JOIN
                    beento bt
                ON
                    tl.bt_id = bt.bt_id
                AND
                    tl.tl_type = ".Utility::CONST_VALUE_TIMELINE_TYPE_BEENTO."
                LEFT JOIN
                    shop_voting wt
                ON
                    tl.voting_id = wt.voting_id
                AND
                    tl.tl_type = ".Utility::CONST_VALUE_TIMELINE_TYPE_WANTTO."
                LEFT JOIN
                    oen
                ON
                    tl.oen_id = oen.oen_id
                AND
                    tl.tl_type = ".Utility::CONST_VALUE_TIMELINE_TYPE_OEN."
                WHERE
                    tl.user_id in ( {$follower_list} )
                AND
                    tl.delete_flg = 0
                ORDER BY
                   UNIX_TIMESTAMP(tl.created_at) desc, UNIX_TIMESTAMP(tl.updated_at) desc
                LIMIT
                    $now_post_num, $get_post_num
                ";
        $results = $this->db->selectPlaceQuery($sql,array());
        //一件も取れていなければFALSEを返す
        if ( $results == NULL  or count($results) == 0) {
            return FALSE;
        } else {
            return $results;
        }
    }


    /**
     * タイムラインランキング一覧
     * @auther: xiuhui yang
     * @param none
     *
     * @return array $results
     *
    */
    public function getTimelineList2($param)
    {
        $now_post_num = $param['now_post_num'];
        $get_post_num = $param['get_post_num'];
        $user_id = $param['user_id'];
        //データ抽出SQLクエリ
        $sql = "
				SELECT
				    tl.*,r.rank_id,r.title,
                    ifnull(v.page_view,'0') as page_view,
                    ifnull(u.photo,'') as user_photo,
                    ifnull(u.user_name,'') as user_name,
                    u.fb_pic as user_fb_photo,
                    bt.shop_id as bt_shop_id,
				    wt.shop_id as wt_shop_id,
				    oen.shop_id as oen_shop_id
				FROM
                     (SELECT
                        tl5.*
                        FROM (
                            select tl1.* from timeline tl1 inner join beento bt on tl1.bt_id = bt.bt_id and bt.bt_id is not null where tl1.user_id ={$user_id}
                            union all
                            select tl2.* from timeline tl2 inner join shop_voting  on tl2.voting_id = shop_voting.voting_id and shop_voting.voting_id is not null where tl2.user_id ={$user_id}
                            union all
                            select tl3.* from timeline tl3 inner join oen  on tl3.oen_id = oen.oen_id and oen.oen_id is not null where tl3.user_id ={$user_id}
                            union all
                            select tl4.* from timeline tl4 inner join ranking  on tl4.rank_id = ranking.rank_id and ranking.delete_flg = 0 where tl4.user_id ={$user_id}) tl5
                            ) tl
    		    LEFT JOIN
				    ranking r
				ON
				    tl.rank_id = r.rank_id
				AND
				    tl.tl_type = ".Utility::CONST_VALUE_TIMELINE_TYPE_RANKING."
                LEFT JOIN
                    rank_pageview v
                ON
                    r.rank_id = v.rank_id
                LEFT JOIN
                    user u
                ON
                    tl.created_user_id = u.user_id
                LEFT JOIN
                    beento bt
                ON
                    tl.bt_id = bt.bt_id
                AND
                    tl.tl_type = ".Utility::CONST_VALUE_TIMELINE_TYPE_BEENTO."
                LEFT JOIN
                    shop_voting wt
                ON
                    tl.voting_id = wt.voting_id
                AND
                    tl.tl_type = ".Utility::CONST_VALUE_TIMELINE_TYPE_WANTTO."
                LEFT JOIN
                    oen
                ON
                    tl.oen_id = oen.oen_id
                AND
                    tl.tl_type = ".Utility::CONST_VALUE_TIMELINE_TYPE_OEN."
                WHERE
				    tl.user_id = {$user_id}
				AND
				    tl.delete_flg = 0
                ORDER BY
                   UNIX_TIMESTAMP(tl.created_at) desc, UNIX_TIMESTAMP(tl.updated_at) desc
                LIMIT
                    $now_post_num, $get_post_num
                ";
        $results = $this->db->selectPlaceQuery($sql,array());
        //一件も取れていなければFALSEを返す
        if ( $results == NULL  or count($results) == 0) {
            return FALSE;
        } else {
            return $results;
        }
    }

    /**
     * タイムラインランキング一覧件数取得
     * @param array $param
     * @return array
     */
    function getTimelineCount ($param) {

        //follower一覧取得
        $sql1 = "
                SELECT
                     follow,follower
                FROM
                     follow
                WHERE
                    follower = ?
                ";
        $fl_list = $this->db->selectPlaceQuery($sql1,array($param['user_id']));
        $followers = array();
        array_push($followers,$param['user_id']);
        if ( $fl_list != NULL  and count($fl_list) > 0 ) {
             for ( $i = 0; $i < count($fl_list); $i++ ) {
                if (isset($fl_list[$i]['follow']) != "") {
                   array_push($followers,$fl_list[$i]['follow']);
                }
             }
        }
        $follower_list = join(',',$followers);

        $sql = "
               SELECT
                   count(*) as CNT
                FROM
                     (SELECT
                        tl5.*
                        FROM (
                            select tl1.* from timeline tl1 inner join beento bt on tl1.bt_id = bt.bt_id and bt.bt_id is not null where tl1.user_id in ( {$follower_list} )
                            union all
                            select tl2.* from timeline tl2 inner join shop_voting  on tl2.voting_id = shop_voting.voting_id and shop_voting.voting_id is not null where tl2.user_id in ( {$follower_list} )
                            union all
                            select tl3.* from timeline tl3 inner join oen  on tl3.oen_id = oen.oen_id and oen.oen_id is not null where tl3.user_id in ( {$follower_list} )
                            union all
                            select tl4.* from timeline tl4 inner join ranking  on tl4.rank_id = ranking.rank_id and ranking.delete_flg = 0 where tl4.user_id in ( {$follower_list} )) tl5
                            ) tl
                LEFT JOIN
                    ranking r
                ON
                    tl.rank_id = r.rank_id
                AND
                    tl.tl_type = ".Utility::CONST_VALUE_TIMELINE_TYPE_RANKING."
                LEFT JOIN
                    rank_pageview v
                ON
                    r.rank_id = v.rank_id
                LEFT JOIN
                    user u
                ON
                    tl.created_user_id = u.user_id
                LEFT JOIN
                    beento bt
                ON
                    tl.bt_id = bt.bt_id
                AND
                    tl.tl_type = ".Utility::CONST_VALUE_TIMELINE_TYPE_BEENTO."
                LEFT JOIN
                    shop_voting wt
                ON
                    tl.voting_id = wt.voting_id
                AND
                    tl.tl_type = ".Utility::CONST_VALUE_TIMELINE_TYPE_WANTTO."
                LEFT JOIN
                    oen
                ON
                    tl.oen_id = oen.oen_id
                AND
                    tl.tl_type = ".Utility::CONST_VALUE_TIMELINE_TYPE_OEN."
                WHERE
                    tl.user_id in ( {$follower_list} )
                AND
                    tl.delete_flg = 0
                ";
        $results = $this->db->selectPlaceQuery($sql, array());

        if ( $results == NULL  or count($results) == 0) {
            $ret = 0;
        } else {
            $ret = $results[0]['CNT'];
        }
        return $ret;
    }

    /**
     * タイムラインランキング一覧
     * @auther: xiuhui yang
     * @param none
     *
     * @return array $results
     *
    */
    public function getTimelineList1($param)
    {
        $now_post_num = $param['now_post_num'];
        $get_post_num = $param['get_post_num'];
        $user_id = $param['user_id'];
        //データ抽出SQLクエリ
        $sql = "
                 SELECT
                    a.rank_id,
                    a.created_user_id,
                    a.created_at,
                    b.title,
                    ifnull(b.user_id,'') as user_id,
                    ifnull(v.page_view,'0') as page_view,
                    ifnull(u.photo,'') as user_photo,
                    ifnull(u.user_name,'') as user_name,
                    '' as detail
                FROM
                    (select rank_id,created_user_id,created_at from timeline where user_id ={$user_id} and delete_flg=0 ) a
                INNER JOIN
                    ranking b
                ON
                    a.rank_id = b.rank_id
                LEFT JOIN
                    rank_pageview v
                ON
                    a.rank_id = v.rank_id
                LEFT JOIN
                    user u
                ON
                    a.created_user_id = u.user_id
                WHERE
                    b.delete_flg = 0
                ORDER BY
                   UNIX_TIMESTAMP(a.created_at) desc
                LIMIT
                    $now_post_num, $get_post_num
                ";
        $results = $this->db->selectPlaceQuery($sql,array());
        //一件も取れていなければFALSEを返す
        if ( $results == NULL  or count($results) == 0) {
            return FALSE;
        } else {
            //総件数取得
            $results[0]['cnt'] = $this-> getTimelineCount($param);
            return $results;
        }
    }


    /**
     * タイムラインランキング一覧件数取得
     * @param array $param
     * @return array
     */
    function getTimelineCount1 ($param) {

        $sql = "
                SELECT
                    count(a.rank_id) as CNT
                FROM
                    (select rank_id,created_user_id,created_at from timeline where user_id ={$param['user_id']} and delete_flg=0 ) a
                INNER JOIN
                    ranking b
                ON
                    a.rank_id = b.rank_id
                LEFT JOIN
                    rank_pageview v
                ON
                    a.rank_id = v.rank_id
                LEFT JOIN
                    user u
                ON
                    a.created_user_id = u.user_id
                WHERE
                    b.delete_flg = 0
                ";
        $results = $this->db->selectPlaceQuery($sql, array());

        if ( $results == NULL  or count($results) == 0) {
            $ret = 0;
        } else {
            $ret = $results[0]['CNT'];
        }
        return $ret;
    }

    /**
     * 新着ランキング一覧
     * @auther: xiuhui yang
     * @param none
     *
     *
     * @return array $results
     *
    */
    public function getNewRankList($param)
    {
        $now_post_num = $param['now_post_num'];
        $get_post_num = $param['get_post_num'];
        //データ抽出SQLクエリ
        $sql = "
                SELECT
                    a.rank_id,
                    a.title,
                    ifnull(a.user_id,'') as user_id,
                    ifnull(v.page_view,'0') as page_view,
                    ifnull(u.photo,'') as user_photo,
                    ifnull(u.user_name,'') as user_name,
                    u.fb_pic as user_fb_photo,
                    '' as detail
                FROM
                    ranking a
                LEFT JOIN
                    rank_pageview v
                ON
                    a.rank_id = v.rank_id
                LEFT JOIN
                    user u
                ON
                    a.user_id = u.user_id
                WHERE
                    a.delete_flg = 0
                ORDER BY
                    UNIX_TIMESTAMP(a.updated_at) desc,UNIX_TIMESTAMP(a.created_at) desc
                LIMIT
                    $now_post_num, $get_post_num
                ";
        $results = $this->db->selectPlaceQuery($sql,array());
        //一件も取れていなければFALSEを返す
        if ( $results == NULL  or count($results) == 0) {
            return FALSE;
        } else {
            //総件数取得
            $results[0]['cnt'] = $this-> getNewRankCount();
        	return $results;
        }
    }

    /**
     * 新着ランキング一覧件数取得
     * @param array $param
     * @return array
     */
    function getNewRankCount () {

        $sql = "
                SELECT
                    count(a.rank_id) as CNT
                 FROM
                    ranking a
                LEFT JOIN
                    rank_pageview v
                ON
                    a.rank_id = v.rank_id
                LEFT JOIN
                    user u
                ON
                    a.user_id = u.user_id
                WHERE
                    a.delete_flg = 0
                ";
        $results = $this->db->selectPlaceQuery($sql, array());

        if ( $results == NULL  or count($results) == 0) {
            $ret = 0;
        } else {
            $ret = $results[0]['CNT'];
        }
        return $ret;
    }

    /**
     * ランキング情報取得する
     * @author: xiuhui yang
     * @param none
     *
     * @return int
     *
    */
    public function getrankingTags( ){
        $sql = "
                SELECT
                    *
                FROM
                    ranking_tag
                WHERE
                    rank_id = ?
                ";
        $results = $this->db->selectPlaceQuery( $sql, array( $param['rank_id'] ) );
        //一件も取れていなければFALSEを返す
            if ( $results == NULL  or count($results) == 0) {
            return FALSE;
        } else {
            return $results;
        }
    }


    /**
     * お気に入りランキング一覧
     * @auther: xiuhui yang
     * @param none
     *
     * @return array $results
     *
    */
    public function getReguruRankList($param)
    {
        $now_post_num = $param['now_post_num'];
        $get_post_num = $param['get_post_num'];
        //データ抽出SQLクエリ
        $sql = "
                 SELECT
                    a.rank_id,
                    a.title,
                    ifnull(a.user_id, '') user_id,
                    a.updated_at,
                    a.created_at,
                    ifnull(v.page_view,'0') as page_view,
                    ifnull(u.photo, '') user_photo,
                    ifnull(u.user_name,'') as user_name,
                    u.fb_pic as user_fb_photo,
                    bb.cnt,
                    '' as detail
                FROM
                    ranking a
                INNER JOIN
                    (select rank_id,count(*) as cnt from reguru_info  group by rank_id order by cnt desc ) bb
                ON
                    a.rank_id = bb.rank_id
                LEFT JOIN
                    rank_pageview v
                ON
                    a.rank_id = v.rank_id
                LEFT JOIN
                    user u
                ON
                    a.user_id = u.user_id
                WHERE
                    a.delete_flg = 0
                ORDER BY
                    bb.cnt desc
                LIMIT
                    $now_post_num, $get_post_num
                ";
        $results = $this->db->selectPlaceQuery($sql,array());
        //一件も取れていなければFALSEを返す
        if ( $results == NULL  or count($results) == 0) {
            return FALSE;
        } else {
            //総件数取得
            $results[0]['cnt'] = $this-> getReguruRankCount();
            return $results;
        }
    }

    /**
     * お気に入りランキング一覧件数取得
     * @param array $param
     * @return array
     */
    function getReguruRankCount () {

        $sql = "
                SELECT
                    count(a.rank_id) as CNT
                FROM
                    ranking a
                INNER JOIN
                    (select rank_id,count(*) as cnt from reguru_info  group by rank_id order by cnt desc ) bb
                ON
                    a.rank_id = bb.rank_id
                LEFT JOIN
                    rank_pageview v
                ON
                    a.rank_id = v.rank_id
                LEFT JOIN
                    user u
                ON
                    a.user_id = u.user_id
                WHERE
                    a.delete_flg = 0
                ";
        $results = $this->db->selectPlaceQuery($sql, array());

        if ( $results == NULL  or count($results) == 0) {
            $ret = 0;
        } else {
            $ret = $results[0]['CNT'];
        }
        return $ret;
    }

    /**
     * 閲覧数順ランキング一覧
     * @auther: xiuhui yang
     * @param none
     *
     * @return array $results
     *
    */
    public function getPageviewRankList($param)
    {
        $now_post_num = $param['now_post_num'];
        $get_post_num = $param['get_post_num'];
        //データ抽出SQLクエリ
        $sql = "
                SELECT
                    a.rank_id,
                    a.title,
                    ifnull(a.user_id,'') as user_id,
                    a.updated_at,
                    a.created_at,
                    ifnull(v.page_view,'0') as page_view,
                    ifnull(u.photo,'') as user_photo,
                    ifnull(u.user_name,'') as user_name,
                    u.fb_pic as user_fb_photo,
                    pv.page_view,
                    '' as detail
                FROM
                    ranking a
                INNER JOIN
                    (select rank_id,page_view from rank_pageview order by page_view desc ) pv
                ON
                    a.rank_id = pv.rank_id
                LEFT JOIN
                    rank_pageview v
                ON
                    a.rank_id = v.rank_id
                LEFT JOIN
                    user u
                ON
                    a.user_id = u.user_id
                WHERE
                    a.delete_flg = 0
                ORDER BY
                    pv.page_view desc
                LIMIT
                    $now_post_num, $get_post_num
                ";
        $results = $this->db->selectPlaceQuery($sql,array());
        //一件も取れていなければFALSEを返す
        if ( $results == NULL  or count($results) == 0) {
            return FALSE;
        } else {
            //総件数取得
            $results[0]['cnt'] = $this-> getPageviewRankCount();
            return $results;
        }
    }

    /**
     * 閲覧数順ランキング一覧件数取得
     * @param array $param
     * @return array
     */
    function getPageviewRankCount () {

        $sql = "
                SELECT
                    count(a.rank_id) as CNT
                FROM
                    ranking a
                INNER JOIN
                    (select rank_id,page_view from rank_pageview order by page_view desc ) pv
                ON
                    a.rank_id = pv.rank_id
                LEFT JOIN
                    rank_pageview v
                ON
                    a.rank_id = v.rank_id
                LEFT JOIN
                    user u
                ON
                    a.user_id = u.user_id
                WHERE
                    a.delete_flg = 0
                ";
        $results = $this->db->selectPlaceQuery($sql, array());

        if ( $results == NULL  or count($results) == 0) {
            $ret = 0;
        } else {
            $ret = $results[0]['CNT'];
        }
        return $ret;
    }

    /**
     * ランキング詳細データ取得
     * @auther: xiuhui yang
     * @param array $param
     *              keyname is user_id
     *                         rank_id
     *
     * @return array $results
     *
    */
    public function getRankingDetail($param)
    {
        //データ抽出SQLクエリ
        $sql = "
                SELECT
                    d.*,
                    b.shop_name,
                    b.address,
                    ifnull(p.value,'') as pref
                FROM
                    ranking_detail d
                LEFT JOIN
                    shop b
                ON
                    b.shop_id = d.shop_id
                LEFT JOIN
                    pref p
                ON
                    b.pref_code = p.pref_code
                WHERE
                    d.rank_id = ?
                AND
                    d.delete_flg =0
                AND
                    b.delete_flg = 0
                ";
        $results = $this->db->selectPlaceQuery($sql,array($param['rank_id']));

        //一件も取れていなければFALSEを返す
        if ( $results == NULL  or count($results) == 0) {
            return FALSE;
        }
        return $results;
    }

    /**
     * topページ店バージョン詳細情報を取る
     * @author: xiuhui yang
     * @param array $param
     *              keyname is user_id
     *                         sqltext
     * @return array $resuts
     */
    public function getShopDetail($param)
    {
        //データ抽出SQLクエリ
        $sql = "
                SELECT
                    a.*,
                    b.shop_name,
                    b.pref_code,
                    b.city_code,
                    c.value as pref,
                    d.value as city,
                    g1.value as genre1_value,
                    g2.value as genre2_value,
                    g3.value as genre3_value,
                    u.user_name,
                    u.photo as user_photo,
                    u.fb_pic as user_fb_photo
                FROM
                    ( {$param['sqltext']}) as a
                INNER JOIN
                    shop b
                ON
                    a.shop_id = b.shop_id
                LEFT JOIN
                    pref c
                ON
                    b.pref_code = c.pref_code
                LEFT JOIN
                    city d
                ON
                    b.pref_code = d.pref_code
                AND
                    b.city_code = d.city_code
                LEFT JOIN
                    genre g1
                ON
                    b.genre1 = g1.genre_id
                LEFT JOIN
                    genre g2
                ON
                    b.genre2 = g2.genre_id
                LEFT JOIN
                    genre g3
                ON
                    b.genre3 = g3.genre_id
                LEFT JOIN
                    user u
                ON
                    a.created_user_id = u.user_id
                WHERE
                    b.delete_flg = 0
                ";
        $results = $this->db->selectPlaceQuery($sql,array());
        //一件も取れていなければFALSEを返す
        if ( $results == NULL  or count($results) == 0) {
            return FALSE;
        } else {
            return $results;
        }
    }

}
?>
