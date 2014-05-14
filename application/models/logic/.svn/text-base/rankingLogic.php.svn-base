<?php

require_once (MODEL_DIR ."/logic/abstractLogic.php");
require_once 'Zend/Exception.php';
require_once 'Zend/Db.php';
require_once (LIB_PATH ."/Utility.php");

/**
 * ランキングロジック
 *
 * @package   ranking
 * @author    xiuhui yang 2013/07/01 新規作成
 */
class rankingLogic extends abstractLogic {


    /**
     * ランキング一覧データ取得
     * @auther: xiuhui yang
     * @param array $param
     *              keyname is user_id
     *
     * @return array $resultsret
     *
    */
    public function getRankingList($param)
    {
    //データ抽出SQLクエリ
        $sql = "
                SELECT
                    a.*,b.*,c.shop_name
                FROM
                    ranking a
                INNER JOIN
                    ranking_detail b
                ON
                    a.user_id = b.user_id
                AND
                    a.rank_id = b.rank_id
                INNER JOIN
                    shop c
                ON
                    b.shop_id = c.shop_id
                WHERE
                    a.delete_flg = 0
                AND
                    b.delete_flg = 0
                order by b.user_id,b.rank_id,b.rank_no
                ";
        $results = $this->db->selectPlaceQuery($sql,array( ));
        //一件も取れていなければFALSEを返す
        if ( $results == NULL  or count($results) == 0) {
            return FALSE;
        } else {
            return $results;
        }
    }


    /**
     * getLargeCategoryList
     * @auther: xiuhui yang
     * 大カテゴリ一覧を取得
     * @param none
     *
     * @return array $results
     *               keyname is large_id
     *                          large_value
     */
    public function getLargeCategoryList()
    {
        //データ抽出SQLクエリ
        $sql = "
                SELECT
                    large_id,large_value
                FROM
                    ranking_category_large
                WHERE
                    delete_flg = 0
                ORDER BY large_id
                ";
        $results = $this->db->selectPlaceQuery($sql,array ());
        //一件も取れていなければFALSEを返す
        if ( $results == NULL  or count($results) == 0) {
            return FALSE;
        } else {
            return $results;
        }
    }



    /**
     * getSmallCategoryList
     * @auther: xiuhui yang
     * 小カテゴリ一覧を取得
     * @param array $param
     *              keyname is large_id
     *
     * @return array $results
     *               keyname is
     *                          large_id
     *                          small_id
     *                          small_value
     */
    public function getSmallCategoryList($param)
    {
        //データ抽出SQLクエリ
        $sql = "
                SELECT
                    large_id,small_id,small_value
                FROM
                    ranking_category_small
                WHERE
                    delete_flg = 0
                AND
                    large_id = ?
                ORDER BY large_id,small_id
                ";
        $results = $this->db->selectPlaceQuery($sql,array ($param['large_id']));
        //一件も取れていなければFALSEを返す
        if ( $results == NULL  or count($results) == 0) {
            return FALSE;
        } else {
            return $results;
        }
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
        //データ抽出SQLクエリ
        $sql = "
                SELECT
                    a.rank_id,a.large_id,a.small_id,l.large_value as category_name
                FROM
                    ranking_category a
                INNER JOIN
                    ranking_category_large l
                ON
                    a.large_id = l.large_id
                WHERE
                    a.delete_flg = 0
                AND
                    a.rank_id = ?
                ORDER BY
                    a.large_id
                ";
            if (isset($param['now_post_num']) !="" && isset($param['get_post_num']) !="") {
                $now_post_num = $param['now_post_num'];
                $get_post_num = $param['get_post_num'];
                $sql.= " LIMIT $now_post_num, $get_post_num ";
            }
        $results = $this->db->selectPlaceQuery($sql,array ($param['rank_id']));
        //一件も取れていなければFALSEを返す
        if ( $results == NULL  or count($results) == 0) {
            return FALSE;
        } else {
            return $results;
        }
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
        //データ抽出SQLクエリ
        $sql = "
                SELECT
                    small_id
                FROM
                    ranking_category
                WHERE
                    delete_flg = 0
                AND
                    user_id = ?
                AND
                    rank_id = ?
                AND
                    large_id = ?
                ";
        $results = $this->db->selectPlaceQuery($sql,array ($param['user_id'],$param['rank_id'],$param['large_id']));
        //一件も取れていなければFALSEを返す
        if ( $results == NULL  or count($results) == 0) {
            return FALSE;
        } else {
            return $results[0]['small_id'];
        }
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
                    a.*,
                    d.*,
                    b.shop_name,
                    b.latitude,
                    b.longitude,
                    b.address,
                    b.pref_code,
                    ifnull(v.page_view,'0') as page_view,
                    ifnull(u.photo,'') as user_photo,
                    ifnull(u.user_name,'') as user_name,
                    u.fb_pic as user_fb_photo,
                    ifnull(p.value,'') as pref,
                    ifnull(ct.value,'') as city,
                    g1.value as genre1_value,
                    g2.value as genre2_value,
                    g3.value as genre3_value
                FROM
                    ranking a
                INNER JOIN
                    ranking_detail d
                ON
                    a.rank_id = d.rank_id
                INNER JOIN
                    shop b
                ON
                    b.shop_id = d.shop_id
                LEFT JOIN
                    pref p
                ON
                    b.pref_code = p.pref_code
                LEFT JOIN
                    city ct
                ON
                    b.pref_code = ct.pref_code
                AND
                    b.city_code = ct.city_code

                LEFT JOIN
                    rank_pageview v
                ON
                    a.rank_id = v.rank_id
                LEFT JOIN
                    user u
                ON
                    a.user_id = u.user_id
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
                WHERE
                    a.delete_flg = 0
                AND
                    a.rank_id = ?
                AND
                    b.delete_flg = 0
                ";
        $results = $this->db->selectPlaceQuery($sql,array($param['rank_id']));
        //一件も取れていなければFALSEを返す
        if ( $results == NULL  or count($results) == 0) {
            return FALSE;
        } else {
            return $results;
        }
    }


    /**
     * ランキングタグ情報取得する
     * @author: xiuhui yang
     * @param array $param
     *              keyname is rank_id
     *
     * @return int
     *
    */
    public function getrankingTags( $param ){
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
     * フォローしているユーザー数
     * @param string $param
     * @return  array $results
     *                keyname is CNT
    */
    public function getFollowsFromUserId($param)
    {
        $sql = "
                SELECT
                    COUNT(*) AS CNT
                FROM
                    follow
                WHERE
                    follower = ?
                ";
        $results = $this->db->selectPlaceQuery($sql, array($param));

        if ( $results == NULL  or count($results) == 0) {
            $ret = 0;
        } else {
            return $results[0]['CNT'];
        }
        return $ret;
    }


   /**
     * 任意のユーザのフォロワー数を調べる
     * @param  array
     *              user_id
     * @return boolian
     */
    public function getFollowersFromUserId($param)
    {

        $sql = "
                SELECT
                    COUNT(*) AS CNT
                FROM
                    follow
                WHERE
                    follow = ?
                ";
        $results = $this->db->selectPlaceQuery($sql,  array($param));

        if ( $results == NULL  or count($results) == 0) {
            $ret = 0;
        } else {
            return $results[0]['CNT'];
        }
        return $ret;
    }

   /**
     * 作成者の他の投稿ランキング一覧
     * @param  array $param
     *               keyname is user_id
     *                          rank_id
     * @return array $results
     */
    public function getOtherRanking($param)
    {
        $sql = "
                SELECT
                   rank_id,title
                FROM
                    ranking
                WHERE
                    user_id = ?
                AND
                    delete_flg = 0
                AND
                    rank_id <> ?
                ORDER BY
                    created_at desc
                LIMIT 5

                ";
        $results = $this->db->selectPlaceQuery($sql,  array($param['user_id'],$param['rank_id']));

        if ( $results == NULL  or count($results) == 0) {
            return false;
        } else {
            return $results;
        }
    }

   /**
     * 類似関連ランキング一覧
     * @param  array $param
     *               keyname is rank_id
     *
     *
     * @return array $results
     *
     */
    public function getSimilarRanking($param)
    {
        $sql = "
                SELECT
                    a.rank_id,
                    a.title,
                    a.user_id,
                    a.created_at,
                    d.shop_id,
                    d.photo,
                    ifnull(v.page_view,'0') as page_view,
                    ifnull(u.user_name,'') as user_name,
                    u.photo as user_photo,
                    u.fb_pic as user_fb_photo,
                    s.shop_name

                FROM
                    ranking a
                LEFT JOIN
                    ranking_detail d
                ON
                    a.rank_id = d.rank_id
                AND
                    d.rank_no = 1
                LEFT JOIN
                    shop s
                ON
                    d.shop_id = s.shop_id
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
                AND
                    a.rank_id <> ?
                ";
        $results = $this->db->selectPlaceQuery($sql,  array($param['rank_id']));

        if ( $results == NULL  or count($results) == 0) {
            return false;
        } else {
            return $results;
        }
    }


    /**
     * search画面でajaxで位置情報によりランキングデータ取得
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
    function getShopListFromLatLon($params)
    {

        $longitude  = $params['longitude'];
        $latitude   = $params['latitude'];

        //データ抽出SQLクエリ
        $sql = "SELECT
                    a.rank_id,b.shop_name ,b.shop_id ,b.shop_url
                FROM
                    ranking a
                INNER JOIN
                    shop b
                ON
                    a.shop_id = b.shop_id
                WHERE
                    b.pref_code = ?
                AND
                    b.city_code = ?
                ";
        $results = $this->db->selectPlaceQuery($sql,array());

        //一件も取れていなければFALSEを返す
        if ($results) {
            return $results;
        } else {
            return false;
        }
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
    public function getPrefList()
    {
        //データ抽出SQLクエリ
        $sql = "
                SELECT
                    pref_code,value
                FROM
                    pref
                ORDER BY pref_code
                ";
        $results = $this->db->selectPlaceQuery($sql,array ());
        //一件も取れていなければFALSEを返す
        if ( $results == NULL  or count($results) == 0) {
            return FALSE;
        } else {
            return $results;
        }
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
        //データ抽出SQLクエリ
        $sql = "
                SELECT
                    pref_code,city_code,value
                FROM
                    city
                WHERE
                    pref_code = ?
                ORDER BY pref_code,city_code
                ";
        $results = $this->db->selectPlaceQuery($sql,array ($param['pref_code']));
        //一件も取れていなければFALSEを返す
        if ( $results == NULL  or count($results) == 0) {
            return FALSE;
        } else {
            return $results;
        }
    }


    /**
     * getRankList
     * @auther: xiuhui yang
     * 検索画面ランキング一覧を取得
     * @param array param
     *              keyname is whereText
     *                         para
     *
     * @return array $results
     *
     */
    public function getRankList($param)
    {

        $now_post_num = $param['now_post_num'];
        $get_post_num = $param['get_post_num'];
        //データ抽出SQLクエリ
        $sql = "
                SELECT
                    distinct r.user_id, r.rank_id,r.title,
                    ifnull(v.page_view,'0') as page_view,
                    ifnull(u.photo,'') as user_photo,
                    u.fb_pic as user_fb_photo,
                    ifnull(u.user_name,'') as user_name,
                    '' as detail
                FROM
                    ranking_detail a
                INNER JOIN
                    ranking r
                ON
                    a.user_id = r.user_id
                AND
                    a.rank_id = r.rank_id
                INNER JOIN
                    shop b
                ON
                    a.shop_id = b.shop_id
                LEFT JOIN
                    rank_pageview v
                ON
                    a.rank_id = v.rank_id
                LEFT JOIN
                    user u
                ON
                    r.user_id = u.user_id
                ";
        if ( $param['categorytext'] != '') {
            $sql.=  $param['categorytext'];
        }
        if ( $param['wheretext'] != '') {
            $sql.= " where " . $param['wheretext'];
        }
        if ( $param['keywordtext'] != '') {
            $sql.=  $param['keywordtext'];
        }

        $sql.= " order by a.user_id,a.rank_id,a.rank_no ";

        $sql.= " limit $now_post_num, $get_post_num ";
        $results = $this->db->selectPlaceQuery($sql,array());
        //一件も取れていなければFALSEを返す
        if ( $results == NULL  or count($results) == 0) {
            return FALSE;
        } else {
            return $results;
        }
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
    public function getTagRankList($param)
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
                INNER JOIN
                    ranking_tag b
                ON
                    a.rank_id = b.rank_id
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
                AND
                    b.tag_name like '%{$param['tag_name']}%'
                LIMIT
                    $now_post_num, $get_post_num
                ";
        $results = $this->db->selectPlaceQuery($sql,array());
        //一件も取れていなければFALSEを返す
        if ( $results == NULL  or count($results) == 0) {
            return FALSE;
        } else {
            //総件数取得
            $results[0]['count'] = $this-> getTagRankCount($param);
            return $results;
        }
    }

    /**
     * タグランキング一覧件数取得
     * @param array $param
     * @return array
     */
    function getTagRankCount($param) {

        $sql = "
                SELECT
                    count(a.rank_id) as CNT
                 FROM
                    ranking a
                 INNER JOIN
                    ranking_tag b
                ON
                    a.rank_id = b.rank_id
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
                AND
                    b.tag_name like '%{$param['tag_name']}%'
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
     * getRankList
     * @auther: xiuhui yang
     * 検索画面ランキング件数を取得
     * @param array param
     *              keyname is whereText
     *                         para
     *
     * @return array $results
     *
     */
    public function getRankingCount($param)
    {
        $ret = 0;
        //データ抽出SQL
        $sql = "
                SELECT
                    count(distinct a.rank_id) as CNT
                FROM
                    ranking_detail a
                INNER JOIN
                    shop b
                ON
                    a.shop_id = b.shop_id
                INNER JOIN
                    ranking r
                ON
                    a.user_id = r.user_id
                AND
                    a.rank_id = r.rank_id
                LEFT JOIN
                    rank_pageview v
                ON
                    a.rank_id = v.rank_id

                ";
        if ( $param['categorytext'] != '') {
            $sql.=  $param['categorytext'];
        }
        if ( $param['wheretext'] != '') {
            $sql.= " where " . $param['wheretext'];
        }
        if ( $param['keywordtext'] != '') {
            $sql.=  $param['keywordtext'];
        }

        $sql.= " order by a.user_id,a.rank_id,a.rank_no ";

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
     * getShopList
     * @auther: xiuhui yang
     * ショップ検索一覧を取得
     * @param array param
     *              keyname is whereText
     *                         para
     *
     * @return array $results
     *
     */
    public function getShopList($param)
    {
        $now_post_num = $param['now_post_num'];
        $get_post_num = $param['get_post_num'];
        //データ抽出SQLクエリ
        $sql = "
                SELECT
                    distinct a.shop_id,
                    a.shop_name,
                    a.pref_code,
                    a.city_code,
                    a.genre1,
                    a.genre2,
                    a.genre3,
                    b.value as pref,
                    c.value as city,
                    g1.value as genre1_value,
                    g2.value as genre2_value,
                    g3.value as genre3_value,
                    ss.cnt
                FROM
                    shop a
                LEFT JOIN
                    pref b
                ON
                    a.pref_code = b.pref_code
                LEFT JOIN
                    city c
                ON
                    a.pref_code = c.pref_code
                AND
                    a.city_code = c.city_code
                LEFT JOIN
                    genre g1
                ON
                    a.genre1 = g1.genre_id
                LEFT JOIN
                    genre g2
                ON
                    a.genre2 = g2.genre_id
                LEFT JOIN
                    genre g3
                ON
                    a.genre3 = g3.genre_id
                LEFT JOIN (
                    select distinct s.shop_id,s.cnt from
                    (
                    select shop_id ,count(*) as cnt from beento where delete_flg = 0 group by shop_id
                    union
                    select shop_id ,count(*) as cnt from oen where delete_flg = 0 group by shop_id
                    union
                    select shop_id ,count(*) as cnt from shop_voting where delete_flg = 0 and voting_kind = 2 group by shop_id
                    ) s ) ss
                ON
                    a.shop_id = ss.shop_id
                ";
        if ( isset($param['wheretext']) != '') {
            $sql.= " where " . $param['wheretext'];
        }
        if ( isset($param['keywordtext']) != '') {
            $sql.=  $param['keywordtext'];
        }

        $sql.= " order by ss.cnt desc ";
        $sql.= " limit $now_post_num, $get_post_num ";
        $results = $this->db->selectPlaceQuery($sql,array());
        //一件も取れていなければFALSEを返す
        if ( $results == NULL  or count($results) == 0) {
            return FALSE;
        } else {
            return $results;
        }
    }

    /**
     * getShopCount
     * @auther: xiuhui yang
     * 店検索件数を取得
     * @param array param
     *              keyname is whereText
     *                         para
     *
     * @return array $results
     *
     */
    public function getShopCount($param)
    {
        $ret = 0;
        //データ抽出SQLクエリ
        $sql = "
                SELECT
                    count(*) as CNT
                FROM
                    shop a
                ";
        if ( isset($param['wheretext']) != '') {
            $sql.= " where " . $param['wheretext'];
        }
        if ( isset($param['keywordtext']) != '') {
            $sql.=  $param['keywordtext'];
        }

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
        $ret = 0;
        //データ抽出SQLクエリ
        $sql = "
                SELECT
                     COUNT(1) AS CNT
                FROM
                     category_voting
                WHERE
                    user_id = ?
                AND
                    rank_id = ?
                AND
                    voting_type = ?
                AND
                    voting_kind = ?
                AND
                    create_user_id = ?
                AND
                    delete_flg = 0
                ";

        $where = array ($param['USER_ID'],$param['RANK_ID'],$param['VOTING_TYPE'],$param['VOTING_KIND'],$param['CREATE_USER_ID']);
        $results = $this->db->selectPlaceQuery( $sql, $where );
        //一件も取れていなければFALSEを返す
        if ( $results == NULL  or count($results) == 0) {
            $ret = 0;
        } else {
            $ret = $results[0]['CNT'];
        }
        return $ret;
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
        //データ抽出SQLクエリ
        $ret = 0;
        $sql = "
                SELECT
                     COUNT(1) AS CNT
                FROM
                     rank_pageview
                WHERE
                    rank_id =?
                ";
        $results = $this->db->selectPlaceQuery($sql,array($param['rank_id']));
        //一件も取れていなければFALSEを返す
        if ( $results == NULL  or count($results) == 0) {
            $ret = 0;
        } else {
            return $results[0]['CNT'];
        }
        return $ret;
    }



    /**
     * ランキングページビュー登録
     * @auther: xiuhui yang
     * @param array $param
     *              keyname is USER_ID
     *                         RANK_ID
     *                         PAGE_VIEW
     *
     * @return bool true/false
     *
    */
    public function insertRankPageview($param)
    {
        //トランザクション開始
        $this->db->beginTransaction();
        try{
            $result = $this->db->insertData( "rank_pageview", $param );
            $this->db->commit();
        }
        catch(exception $e)
        {
            $this->db->rollBack();
            return false;
        }
        return true;
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
        // 更新sql
        $sql = "UPDATE rank_pageview SET page_view = page_view+1 WHERE rank_id = ?";
        $para = array($param['rank_id']);
        //トランザクション開始
        $this->db->beginTransaction();
        try{
            $result = $this->db->executeSql($sql,$para);
            $this->db->commit();
        }
        catch(exception $e)
        {
            $this->db->rollBack();
            return false;
        }
        return true;
     }//--end:function

    /**
     * ランキングデータ登録、更新
     * @author: xiuhui yang
     * @param array $param
     *
     *
     * @return bool true/false
     *
    */
    public function registRanking($posts)
    {
    	$ret_input = array();
        $this->db->beginTransaction();
        try{
            //ランキングテーブル登録項目編集
            $inputRanking = array();
            $inputRanking['USER_ID'] =  intval($posts['user_id']);
            //新規ランキングID取得

            $inputRanking['TITLE'] = $posts['title'];
            $inputRanking['TITLE'] = strip_tags($inputRanking['TITLE']);

            //$inputRanking['CREATED_AT'] = date("Y-m-d H:i:s");
            $inputRanking['UPDATED_AT'] = date("Y-m-d H:i:s");
            //1.ランキングテーブル登録
            //ランキングデータ存在チェック（ユーザー＋タイトル重複）
            $sql = "
                    SELECT
                        COUNT(1) AS CNT
                    FROM
                        ranking
                    WHERE
                        user_id = ?
                    AND
                        title =?
                    AND
                        delete_flg = 0
                  ";
            $ret = $this->db->selectPlaceQuery($sql,array($posts['user_id'],$posts['title']),FALSE);

            //一件も取れていなければ
            if ( $ret == NULL  or $ret[0]['CNT'] == 0) {

                $res_ranking = $this->db->insertData( "ranking", $inputRanking, FALSE );
                $id = $this->db->lastInsertId();
                $posts['new_id'] = $id;
                //2.ランキング詳細情報登録
                $this->_insertRankingDetail($posts);

                //チェックされたカテゴリをランキングカテゴリテーブルに登録
                if ( is_array($_SESSION['check_list']) && count($_SESSION['check_list']) > 0) {

            	   $this->_insertRankingGategory($posts);
                    $_SESSION['check_list'] = array();
                }

                //自由タグ登録
                $this->_insertRankingTag($posts);
            }

            //コミット
            $this->db->commit();
        }
        catch(Zend_Db_Exception $e)
        {

            $this->db->rollBack();
            //echo $e->getMessage();exit;
            //テンポラリフォルダの画像ファイル削除
            $updir  = ROOT_PATH."/img/tmp/ranking/". $posts['user_id'];
            Utility::removeDirectory($updir);
            //セッション情報クリア
            $_SESSION['photo'] = array();
            return false;
        }

        $ret_input['status'] = true;
        //新規投稿したら、timelineテーブルに登録
        if ( $posts['rank_id'] == "" && $posts['new_id'] !="") {
            $this->_insertTimeline($posts['new_id'],$posts['user_id']);
            $ret_input['rank_id'] = $posts['new_id'];
        } else {
            $ret['rank_id'] = $posts['rank_id'];
            $ret_input['status'] = false;
        }
        return $ret_input;
    }


    /**
     * ランキングデータ更新
     * @author: xiuhui yang
     * @param array $param
     *
     *
     * @return bool true/false
     *
    */
    public function updateRanking($posts)
    {
        $ret_update = array();
        $this->db->beginTransaction();
        try{
            //ランキングテーブル登録項目編集
            $updateRanking = array();
            $updateRanking['USER_ID'] =  intval($posts['user_id']);
            //ランキングテーブル更新
            $updateRanking['RANK_ID'] = $posts['rank_id'];
            $title = strip_tags($posts['title']);
            $updateRanking['TITLE'] = $title;
            $updateRanking['UPDATED_AT'] = date("Y-m-d H:i:s");
            $where = array(
                $this->db->quoteInto("RANK_ID = ?", $posts['rank_id'])
            );
            $res_ranking = $this->db->updateData( "ranking", $updateRanking, $where, FALSE );
            //2.ランキング詳細情報更新
            $this->_updateRankingDetail($posts);

            //チェックされたカテゴリをランキングカテゴリテーブルに登録
            if ( is_array($_SESSION['check_list']) && count($_SESSION['check_list']) > 0) {
                $this->_insertRankingGategory($posts);
                $_SESSION['check_list'] = array();
            }

            //自由タグ登録
            $this->_insertRankingTag($posts);

            //コミット
            $this->db->commit();
        }
        catch(Zend_Db_Exception $e)
        {
            $this->db->rollBack();
            //echo $e->getMessage();exit;
            //テンポラリフォルダの画像ファイル削除
            $updir  = ROOT_PATH."/img/tmp/ranking/". $posts['user_id'];
            Utility::removeDirectory($updir);
            //セッション情報クリア
            $_SESSION['photo'] = array();
            return false;
        }
        $ret_update['status'] = true;
        $ret_update['rank_id'] = $posts['rank_id'];

        return $ret_update;
    }

    /**
     * ランキングデータ登録、更新
     * @author: xiuhui yang
     * @param array $param
     *
     *
     * @return bool true/false
     *
    */
    public function registRankingbak($posts)
    {
        $ret = array();
        $this->db->beginTransaction();
        try{
            //ランキングテーブル登録項目編集
            $inputRanking = array();
            $inputRanking['USER_ID'] =  intval($posts['user_id']);
            //新規の場合
            if ( $posts['rank_id'] == "" ) {
                //新規ランキングID取得
                //$inputRanking['RANK_ID'] = intval($posts['new_id']);
                $posts['title'] = strip_tags($posts['title']);
                $inputRanking['TITLE'] = $posts['title'];
                $inputRanking['UPDATED_AT'] = date("Y-m-d H:i:s");
                //1.ランキングテーブル登録
                //ランキングデータ存在チェック（ユーザー＋タイトル重複）
                $sql = "
                        SELECT
                            COUNT(1) AS CNT
                        FROM
                            ranking
                        WHERE
                            user_id = ?
                        AND
                            title =?
                        AND
                            delete_flg = 0
                        ";
                 $ret = $this->db->selectPlaceQuery($sql,array($posts['user_id'],$posts['title']),FALSE);
                //一件も取れていなければ
                if ( $ret == NULL  or $ret[0]['CNT'] == 0) {
                    $res_ranking = $this->db->insertData( "ranking", $inputRanking, FALSE );
                    $id = $this->db->lastInsertId();
                    $posts['new_id'] = $id;
                    //2.ランキング詳細情報登録
                    $this->_insertRankingDetail($posts);
                }
                //else {
                //    echo "同じタイトルのランキング既に登録されています。";exit;
                //}
            } else {
                //ランキングテーブル更新
                $inputRanking['RANK_ID'] = $posts['rank_id'];
                $inputRanking['TITLE'] = $posts['title'];
                $inputRanking['UPDATED_AT'] = date("Y-m-d H:i:s");
                $where = array(
                        $this->db->quoteInto("RANK_ID = ?", $posts['rank_id'])
                        );
                $res_ranking = $this->db->updateData( "ranking", $inputRanking, $where, FALSE );
                //2.ランキング詳細情報更新
                $this->_updateRankingDetail($posts);
            }

            //チェックされたカテゴリをランキングカテゴリテーブルに登録
            if ( is_array($_SESSION['check_list']) && count($_SESSION['check_list']) > 0) {
                $this->_insertRankingGategory($posts);
                $_SESSION['check_list'] = array();
            }

            //自由タグ登録
            $this->_insertRankingTag($posts);

            //コミット
            $this->db->commit();
        }
        catch(Zend_Db_Exception $e)
        {
            $this->db->rollBack();
            //echo $e->getMessage();exit;
            //テンポラリフォルダの画像ファイル削除
            $updir  = ROOT_PATH."/img/tmp/ranking/". $posts['user_id'];
            Utility::removeDirectory($updir);
            //セッション情報クリア
            $_SESSION['photo'] = array();
            return false;
        }
        $ret['status'] = true;
        //新規投稿したら、timelineテーブルに登録
        if ( $posts['rank_id'] == "" && $posts['new_id'] !="") {
            $this->_insertTimeline($posts['new_id'],$posts['user_id']);
            $ret['rank_id'] = $posts['new_id'];
        } else {
            $ret['rank_id'] = $posts['rank_id'];
        }
        return $ret;
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
        $where = array(
                        $this->db->quoteInto("USER_ID = ?", $param['USER_ID']),
                        $this->db->quoteInto("RANK_ID = ?", $param['RANK_ID'])
                        );
        $this->db->beginTransaction();
        try{
            //ランキングカテゴリデータ物理削除する
            //$res1 = $this->db->deleteData( "ranking_category", $where, FALSE );
            //ランキングデータ論理削除する
            $res2 = $this->db->updateData( "ranking", $param, $where, FALSE);
            //ランキング詳細データ論理削除する
            //$res3 = $this->db->updateData( "ranking_detail", $param, $where, FALSE );
            $this->db->commit();
        }
        catch(Zend_Db_Exception $e)
        {
            $this->db->rollBack();
            //echo $e->getMessage();exit;
            return false;
        }
        return true;
    }


    /**
     * ランキング順位変更1→2 or 2→1
     * @author: xiuhui yang
     * @param array $params
     *              keyname is USER_ID
     *                         RANK_ID
     *                         RANK_NO
     *
     *
     * @return bool true/false
     *
    */
    public function changeRankNo1to2($params)
    {
    	$this->db->beginTransaction();
        try{
            //ランキング詳細データ更新(rank_no:1->101番に変更)
            // 更新sql1
            $sql1 = "
                     UPDATE
                        ranking_detail
                     SET
                        rank_no = 101
                     WHERE
                        user_id = ?
                     AND
                        rank_id = ?
                     AND
                        rank_no = 1";
            $para1 = array( $params['user_id'], $params['rank_id']);
            $ret1 = $this->db->executeSql($sql1,$para1);

            //ランキング詳細データ更新(rank_no:2->102番に変更)
            // 更新sql2
            $sql2 = "
                     UPDATE
                        ranking_detail
                     SET
                        rank_no = 102
                     WHERE
                        user_id = ?
                     AND
                        rank_id = ?
                     AND
                        rank_no = 2";
            $para2 = array( $params['user_id'], $params['rank_id']);
            $ret2 = $this->db->executeSql($sql2,$para2);
            //ランキング詳細データ更新(rank_no:101->2番に変更)
            // 更新sql3
            $sql3 = "
                     UPDATE
                        ranking_detail
                     SET
                        rank_no = 2
                     WHERE
                        user_id = ?
                     AND
                        rank_id = ?
                     AND
                        rank_no = 101";
            $para3 = array( $params['user_id'], $params['rank_id']);
            $ret3 = $this->db->executeSql($sql3,$para3);

            //ランキング詳細データ更新(rank_no:102->1番に変更)
            // 更新sql4
            $sql4 = "
                     UPDATE
                        ranking_detail
                     SET
                        rank_no = 1
                     WHERE
                        user_id = ?
                     AND
                        rank_id = ?
                     AND
                        rank_no = 102";
            $para4 = array( $params['user_id'], $params['rank_id']);
            $ret4 = $this->db->executeSql($sql4,$para4);

            $this->db->commit();
        }
        catch(Zend_Db_Exception $e)
        {
            $this->db->rollBack();
            //echo $e->getMessage();exit;
            return false;
        }
        return true;
    }


    /**
     * ランキング順位変更2→3 or 3→2
     * @author: xiuhui yang
     * @param array $params
     *              keyname is USER_ID
     *                         RANK_ID
     *                         RANK_NO
     *
     *
     * @return bool true/false
     *
    */
    public function changeRankNo2to3($params)
    {
        $this->db->beginTransaction();
        try{
            //ランキング詳細データ更新(rank_no:2->102番に変更)
            // 更新sql1
            $sql1 = "
                     UPDATE
                        ranking_detail
                     SET
                        rank_no = 103
                     WHERE
                        user_id = ?
                     AND
                        rank_id = ?
                     AND
                        rank_no = 3";
            $para1 = array( $params['user_id'], $params['rank_id']);
            $ret1 = $this->db->executeSql($sql1,$para1);

            //ランキング詳細データ更新(rank_no:2->102番に変更)
            // 更新sql2
            $sql2 = "
                     UPDATE
                        ranking_detail
                     SET
                        rank_no = 102
                     WHERE
                        user_id = ?
                     AND
                        rank_id = ?
                     AND
                        rank_no = 2";
            $para2 = array( $params['user_id'], $params['rank_id']);
            $ret2 = $this->db->executeSql($sql2,$para2);

            //ランキング詳細データ更新(rank_no:103->2番に変更)
            // 更新sql3
            $sql3 = "
                     UPDATE
                        ranking_detail
                     SET
                        rank_no = 2
                     WHERE
                        user_id = ?
                     AND
                        rank_id = ?
                     AND
                        rank_no = 103";
            $para3 = array( $params['user_id'], $params['rank_id']);
            $ret3 = $this->db->executeSql($sql3,$para3);

            //ランキング詳細データ更新(rank_no:102->3番に変更)
            // 更新sql4
            $sql4 = "
                     UPDATE
                        ranking_detail
                     SET
                        rank_no = 3
                     WHERE
                        user_id = ?
                     AND
                        rank_id = ?
                     AND
                        rank_no = 102";
            $para4 = array( $params['user_id'], $params['rank_id']);
            $ret4 = $this->db->executeSql($sql4,$para4);

            $this->db->commit();
        }
        catch(Zend_Db_Exception $e)
        {
            $this->db->rollBack();
            //echo $e->getMessage();exit;
            return false;
        }
        return true;
    }

//▼▼▼▼▼▼▼▼チップス▼▼▼▼▼▼▼▼▼▼▼▼

    /**
     * ランキングテーブル登録処理
     * @author: xiuhui yang
     * @param string $rank_id
     *        string $new_id
     *        int    $i
     *
     *
     * @return none
     *
    */
    private function _insertRankingDetail($posts)
    {

        for ( $i=1; $i<=3; $i++) {
            //登録項目編集
            $inputRankingDetail = array();
            $inputRankingDetail['USER_ID'] =  $posts['user_id'];
            $inputRankingDetail['RANK_ID'] =  $posts['new_id'];
            $inputRankingDetail['RANK_NO'] =  $i;
            if ( $posts['shop_id_'.$i] != "") {
                $inputRankingDetail['SHOP_ID'] = $posts['shop_id_'.$i];
            } else {
            	$inputRankingDetail['SHOP_ID'] = 0;
            }
            $posts['explain_'.$i] = strip_tags($posts['explain_'.$i]);
            $inputRankingDetail['EXPLAIN'] = $posts['explain_'.$i];

            $ret = true;
            //画像uploadされた場合、ファイル名作成してDB項目にセット
            if( isset($_SESSION['photo']['photo_'.$i]) && !empty($_SESSION['photo']['photo_'.$i]) && $posts['shop_id_'.$i] !="" ){
                $upfile = $_SESSION['photo']['photo_'.$i];
                $new_name = $this->_make_imagename($upfile,$posts['shop_id_'.$i],$posts['user_id'],$i);
                $inputRankingDetail['PHOTO'] = $this->_make_dbset_photo($upfile,$posts['new_id'],$new_name);

                //PCアップロード先のパス
                $updir  = ROOT_PATH."/img/pc/ranking/".$posts['new_id'];
                //モバイルアップロード先のパス
                $updir_sp  = ROOT_PATH."/img/sp/ranking/".$posts['new_id'];
                //テンポラリフォルダから本ディレクトリへ移動
                $ret = $this->_uploadFile($updir,$updir_sp,$new_name,$upfile);
            } else {
                $inputRankingDetail['PHOTO'] = "";
            }

            //画像アップロード成功するなら、ランキング詳細テーブルに登録
            if ( $ret != false ) {
                //ランキング詳細テーブルに登録
            	$inputRankingDetail['CREATED_AT'] = date("Y-m-d H:i:s");
                $inputRankingDetail['UPDATED_AT'] = date("Y-m-d H:i:s");
                $res_detail = $this->db->insertData( "ranking_detail", $inputRankingDetail, FALSE );
		            // データ存在チェック(ユーザー＋ランキングID＋ショップID)
		            /*
		            	$sql = "
		                        SELECT
		                            COUNT(1) AS CNT
		                        FROM
		                            ranking_detail
		                        WHERE
		                            user_id = ?
		                        AND
		                            rank_id =?
		                        AND
		                            shop_id = ?
		                        AND
		                            delete_flg = 0
		                        ";
		                 $res = $this->db->selectPlaceQuery($sql,array($posts['user_id'],$posts['rank_id'],$shop_id),FALSE);
		                //一件も取れていなければ
		                if ( $res == NULL  or $res[0]['CNT'] == 0) {
                            $inputRankingDetail['CREATED_AT'] = date("Y-m-d H:i:s");
		                    $inputRankingDetail['UPDATED_AT'] = date("Y-m-d H:i:s");
		                    $res_detail = $this->db->insertData( "ranking_detail", $inputRankingDetail, FALSE );
		                  }
		            */
            }
        }
    }


    /**
     * ランキング詳細テーブル更新処理
     * ランキング画像アップロード
     * @author: xiuhui yang
     * @param string $rank_id
     *        string $new_id
     *        int    $i
     *
     *
     * @return none
     *
    */
    private function _updateRankingDetail($posts)
    {
        for ( $i=1; $i<=3; $i++) {
            $updateRankingDetail = array();
            //登録項目編集
            $updateRankingDetail['USER_ID'] =  $posts['user_id'];
            $updateRankingDetail['RANK_NO'] =  $i;
            if ( $posts['shop_id_'.$i] != "") {
                $updateRankingDetail['SHOP_ID'] = $posts['shop_id_'.$i];
            } else {
                $updateRankingDetail['SHOP_ID'] = 0;
            }
            //$updateRankingDetail['SHOP_ID'] = $shop_id;
            $updateRankingDetail['EXPLAIN'] = $posts['explain_'.$i];
            $updateRankingDetail['RANK_ID'] = $posts['rank_id'];
            $updateRankingDetail['UPDATED_AT'] = date("Y-m-d H:i:s");

            //画像uploadされた場合、ファイル名作成してDB項目にセット
            $ret = true;
            if( isset($_SESSION['photo']['photo_'.$i]) && !empty($_SESSION['photo']['photo_'.$i]) && $posts['shop_id_'.$i] != ""){
                $upfile = $_SESSION['photo']['photo_'.$i];
                $new_name = $this->_make_imagename($upfile,$posts['shop_id_'.$i],$posts['user_id'],$i);
                $updateRankingDetail['PHOTO'] = $this->_make_dbset_photo($upfile,$posts['rank_id'],$new_name);

                //PCアップロード先のパス
                $updir  = ROOT_PATH."/img/pc/ranking/".$posts['rank_id'];
                //モバイルアップロード先のパス
                $updir_sp  = ROOT_PATH."/img/sp/ranking/".$posts['rank_id'];
                //テンポラリフォルダから本ディレクトリへ移動
                $ret = $this->_uploadFile($updir,$updir_sp,$new_name,$upfile);
            } else {
                if ($posts['photo_delflg_'.$i] == "1") {
                    //画像削除する
                    $updateRankingDetail['PHOTO'] = "";
                    $old_photo = $posts['photo_'.$i];
                    $photo = basename($old_photo);
                    //PCアップロード先のパス
                    $updir  = ROOT_PATH."/img/pc/ranking/".$posts['rank_id']."/".$photo;
                    //モバイルアップロード先のパス
                    $updir_sp  = ROOT_PATH."/img/sp/ranking/".$posts['rank_id']."/".$photo;
                    $ret = Utility::deleteUpFile($updir);
                    $ret = Utility::deleteUpFile($updir_sp);
                }
            }

            if ($ret != false) {
                //詳細テーブルに更新
                //更新条件
                $where = array(
                                $this->db->quoteInto("RANK_ID = ?",$posts['rank_id']),
                                $this->db->quoteInto("RANK_NO = ?",$i)
                               );
                $res_detail = $this->db->updateData( "ranking_detail", $updateRankingDetail, $where, FALSE );
                /*
                // データ存在チェック(ユーザー＋ランキングID＋ランキング順位)
	            if ( $shop_id != "" ) {
	                $sql = "
	                        SELECT
	                            COUNT(1) AS CNT
	                        FROM
	                            ranking_detail
	                        WHERE
	                            rank_id =?
	                        AND
	                            rank_no = ?
	                        AND
	                            delete_flg = 0
	                        ";
	                $res = $this->db->selectPlaceQuery($sql,array($posts['rank_id'],$i),FALSE);

	                //一件も取れていなければ
	                if ( $res == NULL  or $res[0]['CNT'] == 0) {
	                   $res_detail = $this->db->insertData( "ranking_detail", $inputRankingDetail, FALSE );
	                    //$ret = $this->service('ranking','insertRankingDetail',$inputData);
	                } else {
	                   //更新条件
	                    $where = array(
	                                    $this->db->quoteInto("RANK_ID = ?",$posts['rank_id']),
	                                    $this->db->quoteInto("RANK_NO = ?",$i)
	                                    );
	                    $res_detail = $this->db->updateData( "ranking_detail", $inputRankingDetail, $where, FALSE );
	                }
	            }
	            */
            }
        }
    }


    /**
     * ランキングカテゴリ登録処理
     * @author: xiuhui yang
     * @param string $rank_id
     *        string $new_id
     *        int    $i
     *
     *
     * @return none
     *
    */
    private function _insertRankingGategory($posts)
    {
    	$check_list = $_SESSION['check_list'];
        //登録項目編集
        $rank_id = "";
        $rank_id = $posts['rank_id'];
        if ( $rank_id != "" ) {
            //ランキングカテゴリデータ削除
            $where = array( $this->db->quoteInto("USER_ID = ?",$posts['user_id']),
                            $this->db->quoteInto("RANK_ID = ?",$rank_id)
                            );
            $ret = $this->db->deleteData( "ranking_category", $where, FALSE );
        } else {
            $rank_id = $posts['new_id'];
        }


        for ( $i = 0; $i<count($check_list); $i++) {
var_dump($check_list);
            $large_id = $check_list[$i]['large_id'];
            $insertdata['USER_ID'] = $posts['user_id'];;
            $insertdata['RANK_ID'] = $rank_id;
            $insertdata['LARGE_ID'] = $large_id;
            if ( isset($check_list[$i]['small_id']) && $check_list[$i]['small_id'] != "") {
                $insertdata['SMALL_ID'] = $check_list[$i]['small_id'];
            }
            $insertdata['CREATED_AT'] = date("Y-m-d H:i:s");
            $insertdata['UPDATED_AT'] = date("Y-m-d H:i:s");
            $this->db->insertData( "ranking_category", $insertdata, FALSE );
        }
    }


    /**
     * ランキング自由タグ登録処理
     * @author: xiuhui yang
     * @param array $posts
     *
     *
     * @return none
     *
    */
    private function _insertRankingTag($posts)
    {
        $tag_list = array();
        //登録項目編集
        $rank_id = "";
        $rank_id = $posts['rank_id'];
        if ( $rank_id == "" ) {
            $rank_id = $posts['new_id'];
        } else {
            //ランキングタグデータ削除
            $where = array( $this->db->quoteInto("RANK_ID = ?",$rank_id));
            $ret = $this->db->deleteData( "ranking_tag", $where, FALSE );
        }

        if (isset($posts['tag']) && is_array($posts['tag']) && count($posts['tag']) > 0) {
            //重複のタグ取り除く
            $list = array_unique($posts['tag']);
            for ( $j = 0; $j<count($list); $j++) {
            	$insertdata = array();
                $insertdata['RANK_ID'] = $rank_id;

                //タグ存在チェック（入力したタグ名）
                $sql = "
                        SELECT
                            *
                        FROM
                            ranking_tag
                        WHERE
                            tag_name like '%{$list[$j]}%'
                        ";
                 $ret = $this->db->selectPlaceQuery($sql,array(),FALSE);
                //一件も取れていなければ
                if ( $ret == NULL  or count($ret) == 0) {
                    $sql1 = "
                        SELECT
                             max(tag_id) as maxid
                        FROM
                            ranking_tag
                        ";
                     $ret1 = $this->db->selectPlaceQuery($sql1,array(),FALSE);
                     if (isset($ret1[0]['maxid']) && $ret1[0]['maxid']!="") {
                       $new_id = intval($ret1[0]['maxid']) + 1;
                     } else {
                       $new_id = 1;
                     }
                    $insertdata['TAG_ID'] = $new_id;
                } else {
                    $insertdata['TAG_ID'] = $ret[0]['tag_id'];
                }
                //$insertdata['TAG_ID'] = $j+1;
                $tagname = strip_tags($list[$j]);
                $insertdata['TAG_NAME'] = $tagname;
                $insertdata['CREATED_AT'] = date("Y-m-d H:i:s");
                $insertdata['UPDATED_AT'] = date("Y-m-d H:i:s");
                $res = $this->db->insertData( "ranking_tag", $insertdata, FALSE );
            }
        }
    }


    /**
     * タイムライン登録処理
     * @author: xiuhui yang
     * @param int $i
     *
     *
     * @return none
     *
    */
    private function _insertTimeline($new_rankid,$user_id)
    {
        $insertTimeLine = array();
        $insertTimeLine['RANK_ID'] = $new_rankid;
        $insertTimeLine['TL_TYPE'] = Utility::CONST_VALUE_TIMELINE_TYPE_RANKING;
        $insertTimeLine['CREATED_USER_ID'] = $user_id;
        $insertTimeLine['CREATED_AT'] = date("Y-m-d H:i:s");
        $insertTimeLine['UPDATED_AT'] = date("Y-m-d H:i:s");
        //follower一覧取得
        /*
        $sql1 = "
                SELECT
                     follow,follower
                FROM
                     follow
                WHERE
                    follow = ?
                ";
        $fl_list = $this->db->selectPlaceQuery($sql1,array($user_id));
        if ( is_array($fl_list) && count($fl_list) > 0) {
            for ( $i = 0; $i<count($fl_list); $i++) {
                if (isset($fl_list[$i]['follower']) && $fl_list[$i]['follower'] !="") {
                    $insertTimeLine['USER_ID'] = $fl_list[$i]['follower'];
                    $this->db->insertData( "timeline", $insertTimeLine );
                }
            }
        }
        */
        //自分のユーザーIDに対してtimelineテーブルに登録する
        $insertTimeLine['USER_ID'] = $user_id;
        $this->db->insertData( "timeline", $insertTimeLine );
    }


    /**
     * 画像DB項目保存内容作成
     * @author: xiuhui yang
     * @param unknown_type $upfile
     *        string $rank_id
     *        string $new_name
     *        array $inputData
     *
     *
     * @return string $inputData
     *
    */
    private function _make_dbset_photo($upfile,$rank_id,$new_name) {
        $photo = "";
        //画像情報
        $photo = $rank_id.'/'.$new_name;
        return $photo;
    }


    /**
     * UPLOAD画像をリネームする
     * @auther: xiuhui yang
     * @param unknown_type $upfile
     *        string $shop_id
     *        string $no
     *
     *
     * @return string $image_name
     *
    */
    private function _make_imagename($upfile,$shop_id,$user_id,$no) {
        //画像情報
        //$upfile = $_FILES["photo"];
        //アップロードしたファイルの名称
        $name  = $upfile[ 'name' ];
        $file_name = pathinfo($name);
        //ファイル名をrenameする
        $image_name = $shop_id.'_'.$user_id.'_ranking';
        $image_name .= '-no'.$no;
        $image_name .= ".";
        $image_name .= $file_name['extension'];
        return $image_name;
    }


    /**
     * ファイルアップロード
     *
     * @author: xiuhui yang
     *
     * @param string $updir PC画像置くバス
     * @param string $updir_sp SmartPhone画像置くパス
     * @param array $new_name 画像名
     * @param string $upfile アップロードされたファイル
     * @return
     */
    private static function _uploadFile($updir,$updir_sp,$new_name,$upfile)
    {
        $ret = true;
        // アップロード先のパス
        // ディレクトリがない場合は作成する
        if (!file_exists($updir) && !is_dir($updir)) {
            mkdir($updir,0777,true);
            chmod($updir,0777);
        }
        if (!file_exists($updir_sp) && !is_dir($updir_sp)) {
            mkdir($updir_sp,0777,true);
            chmod($updir_sp,0777);
        }
        $upload_file_path = $updir."/".$new_name;
        $upload_file_path_sp = $updir_sp."/".$new_name;

        // 正常にアップロードされていれば，imgディレクトリにデータを保存
        if ( $upfile[ 'error' ] == 0 && $upfile['tmp_name']!="" ) {
            @rename($upfile['tmp_name'], $upload_file_path);

            /*
            // 画像ファイルのサイズ変換
            $finfo = pathinfo($upload_file_path);
            $ext = Utility::VALID_IMAGE_EXTENSIONS();
            if ( isset($finfo) &&  in_array($finfo['extension'] , $ext) ) {
                $size = Utility::IMAGE_SIZE_PC();  // ＰＣ用画像サイズ
                $image = new Imagick();

                $t = $image->readImage($upload_file_path);
                $image->thumbnailImage($size[0],$size[1]);
                $image->writeImage($upload_file_path);

                // スマートフォン用縮小サイズ作成
                $size1 = Utility::IMAGE_SIZE_SMARTPHONE();  // スマートフォン用画像サイズ
                $image->thumbnailImage($size1[0],$size1[1]);
                $image->writeImage($upload_file_path_sp);
                unset($image);
            }
            */
        } else {
            $ret = false;
        }
        return $ret;
    }

}
?>
