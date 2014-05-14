<?php

require_once (MODEL_DIR ."/logic/abstractLogic.php");

/**
 * ショップロジック
 *
 * @package   shop
 * @author    xiuhui yang 2013/07/01 新規作成
 */
class shopLogic extends abstractLogic {


    /**
     * ショップ一覧データ取得
     * @param array $params
     *              keyname is shopname
     * @return array $results
     */
    function getShopListFromShopname($params)
    {
        $shop_name = $params['shopname'];
        if (!$shop_name) {
             return FALSE;
        }
            //データ抽出SQLクエリ
        $sql = "
                SELECT
                    shop_id , shop_name , latitude, longitude,address
                FROM
                    shop
                WHERE
                    shop_name like '%{$shop_name}%'
                limit 50
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
     * ショップ一覧データ取得
     * @param array $params
     *              keyname is shopname
     *                         pref
     * @return array $results
     */
    function getShopListFromPrefShopname($params)
    {
        $shop_name = $params['shopname'];
        $pref = $params['pref'];
        //データ抽出SQLクエリ
        $sql = "
                SELECT
                    shop_id , shop_name , address
                FROM
                    shop
                WHERE
                   delete_flg = 0
                ";
        $keywordText = "";
        if (!$shop_name) {
             return FALSE;
        } else {
            $shop_name = str_replace('\'', ' ', $shop_name);
            $shop_name = str_replace('　', ' ', $shop_name);
            $pr = explode(" ",trim($shop_name));
            $wt5 = "";
            if ( is_array($pr) && count($pr)>1 ) {
                for ($i=0;$i<count($pr);$i++) {
                    if ($i>0) {
                        $wt5 .= " and ";
                    }
                    $wt5 .= " shop_name collate utf8_unicode_ci like '%{$pr[$i]}%' ";
                }
                $keywordText .= "  and ( $wt5 )  ";
                } else {
                    $wt5 .= " and ( shop_name collate utf8_unicode_ci like '%{$shop_name}%'  ) ";
                    $keywordText .=  $wt5;
                }
        }
        $sql .= $keywordText;
        /*
        $sql = "
                SELECT
                    shop_id , shop_name , address
                FROM
                    shop
                WHERE
                    shop_name like '%{$shop_name}%'
                ";
        */
        if ( $pref != "" && $pref != "-1" ) {
            $sql .= " and pref_code = {$pref} ";
        }
        $results = $this->db->selectPlaceQuery($sql,array());

        //一件も取れていなければFALSEを返す
        if ( $results == NULL  or count($results) == 0) {
            return FALSE;
        } else {
            return $results;
        }
    }

    /**
     * ajax infoでshop情報取得
     * @param array $param
     * @return array
     */
    function getShopInfoByShopName ($param) {
        if(isset($param['search']) && !empty($param['search'])) {
            $search = "and shop.shop_name like '%{$param['search']}%'";
        }
        else {
            $search = '';
        }
        $sql = "
                SELECT
                    shop_id , shop_name,address,CONCAT(shop_name, '  ',address) as shop_info
                FROM
                    shop
                WHERE
                    delete_flg = 0
                $search
                limit 50
                ";
        unset($param['search']);
        $results = $this->db->selectPlaceQuery($sql, $param);

        if ( $results == NULL  or count($results) == 0) {
            return false;
        } else {
            return $results;
        }
    }

    /**
     * ショップ詳細データ取得
     * @param array $params
     *              keyname is shop_id
     * @return array $results
    */
    public function getShopDetail($param)
    {
        //データ抽出SQLクエリ
        $sql = "
                SELECT
                    a.*,
                    ifnull(b.page_view,'0') as page_view,
                    p.value as pref,
                    ct.value as city,
                    g1.value as genre1_value,
                    g2.value as genre2_value,
                    g3.value as genre3_value
                FROM
                    shop a
                LEFT JOIN
                    shop_pageview b
                ON
                    a.shop_id = b.shop_id
                LEFT JOIN
                    pref p
                ON
                    a.pref_code = p.pref_code
                LEFT JOIN
                    city ct
                ON
                    a.pref_code = ct.pref_code
                AND
                    a.city_code = ct.city_code
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
                WHERE
                    a.shop_id = ?
                AND
                    a.delete_flg = 0
                ";
        $results = $this->db->selectPlaceQuery($sql,array($param));
        //一件も取れていなければFALSEを返す
        if ( $results == NULL  or count($results) == 0) {
            return FALSE;
        } else {
            return $results[0];
        }
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
     * @return bool true/false
     *
    */
    public function registNewShopInfo($param)
    {

        $param['delete_flg'] = '0';

        //トランザクション開始
        $this->db->beginTransaction();
        try{
            $result = $this->db->insertData("shop", $param);
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
	function getShopListFromLatLon($params)
	{

		$longitude  = $params['longitude'];
		$latitude   = $params['latitude'];

	    //データ抽出SQLクエリ
		$sql = "SELECT
					shop_name ,shop_id ,shop_url
				FROM
					shop
				WHERE
					shop_name is not null
				ORDER BY ABS( latitude - '{$latitude}') + ABS(longitude - '{$longitude}')
				ASC
				limit 50
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
     * ショップ住所情報所得
     *
     * @param array $params
     *              keyname is shop_id
     *
     *
     * @return array $results
     *               keyname is latitude
     *                          longitude
     *
     */
    public function getShopAddressLatLon($param)
    {

    	$shop_id = $param['shop_id'];
		if (!$shop_id) {
			 return FALSE;
		}
	        //データ抽出SQLクエリ
        $sql = "
                SELECT
                    latitude, longitude
                FROM
                    shop
                WHERE
                    shop_id = ?
                limit 50
                ";
        $results = $this->db->selectPlaceQuery($sql,array($param['shop_id']));
        //一件も取れていなければFALSEを返す
        if ( $results == NULL  or count($results) == 0) {
            return FALSE;
        } else {
            return $results[0];
        }
    }


    /**
     * カテゴリーIDからそのカテゴリーIDのランキングリスト(ショップリスト)を取得
     * @param array $param
     *              keyname is category_id
     *
     * @return array $results
    */
    public function getShopRankingFromCategory($param)
    {
        $category_id = $param['category_id'];
        if (!$category_id) {
             return FALSE;
        }
            //データ抽出SQLクエリ
        $sql = "
                SELECT
                    t.shop_id , t .top3_no, t.photo,t.uid,s.shop_name,u.user_name
                FROM
                   ( top3 t
                    INNER JOIN
                        shop s
                    ON
                        t.shop_id = s.shop_id
                   )
                INNER JOIN
                    user u
                ON
                    u.user_id = t.uid
                WHERE
                    t.category_id = ?
                    and t.delete_flg is not null
                limit 50
                ";
                $results = $this->db->selectPlaceQuery($sql,array($param['category_id']));
        //一件も取れていなければFALSEを返す
        if ( $results == NULL  or count($results) == 0) {
            return FALSE;
        } else {
            return $results;
        }
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
        //データ抽出SQLクエリ
        $sql = "
                SELECT
                     a.user_id,a.explain,b.user_name,b.mail_address,b.photo as user_photo, b.fb_pic as user_fb_photo
                FROM
                    beento a
                LEFT OUTER JOIN
                    user b
                ON
                    a.user_id = b.user_id
                WHERE
                    a.shop_id  = :shop_id
                AND
                    a.delete_flg = 0
                AND
                    b.delete_flg = 0
                ";
        $results = $this->db->selectPlaceQuery($sql,$param);
        //一件も取れていなければFALSEを返す
        if ( $results == NULL  or count($results) == 0) {
            return FALSE;
        } else {
            return $results;
        }
    }

    /**
     * 該当ショップ行ったことユーザーコメント一覧取得
     * @param array $param
     *              keyname is shop_id
     *
     *
     * @return array $results
    */
    public function getBeentoUserCommentList($param)
    {
        $now_post_num = $param['now_post_num'];
        $get_post_num = $param['get_post_num'];
        //データ抽出SQLクエリ
        $sql = "
                SELECT
                   a.*,b.photo as user_photo,b.user_name,b.fb_pic as user_fb_photo,s.shop_name
                FROM
                    beento a
                LEFT OUTER JOIN
                    user b
                ON
                    a.user_id = b.user_id
                LEFT OUTER JOIN
                    shop s
                ON
                    a.shop_id = s.shop_id
                WHERE
                    a.shop_id  = ?
                AND
                    a.delete_flg = 0
                AND
                    b.delete_flg = 0
                ";
        $sql.= " LIMIT $now_post_num, $get_post_num ";
        $results = $this->db->selectPlaceQuery($sql,array($param['shop_id']));
        //一件も取れていなければFALSEを返す
        if ( $results == NULL  or count($results) == 0) {
            return FALSE;
        } else {
            return $results;
        }
    }

    /**
     * 該当ショップ行ったことユーザーコメント件数取得
     * @param array $param
     *              keyname is shop_id
     *
     *
     * @return array $results
    */
    public function getBeentoCommentFromShopid($param)
    {
        $ret = 0;
        //データ抽出SQLクエリ
        $sql = "
                SELECT
                   COUNT(*) AS CNT
                FROM
                    beento a
                LEFT OUTER JOIN
                    user b
                ON
                    a.user_id = b.user_id
                WHERE
                    a.shop_id  = ?
                AND
                    a.delete_flg = 0
                AND
                    b.delete_flg = 0
                ";
        $results = $this->db->selectPlaceQuery($sql,array($param));
        //一件も取れていなければFALSEを返す
        if ( $results == NULL  or count($results) == 0) {
            $ret = 0;
        } else {
            return $results[0]['CNT'];
        }
        return $ret;
    }

    /**
     * ショップページビュー更新
     * @author: xiuhui yang
     * @param array $param
     *              keyname is user_id
     *                         rank_id
     *
     * @return  bool true
     *
    */
     function insertUpdateShopPageview($param) {
        //データ抽出SQLクエリ
        $sql = "
                SELECT
                    COUNT(1) AS CNT
                FROM
                    shop_pageview
                WHERE
                    shop_id =?
                ";
        $results = $this->db->selectPlaceQuery($sql,array($param) );
        //一件も取れていなければFALSEを返す
        if ( $results[0]['CNT'] > 0) {
            // 更新sql
            $sql = "UPDATE shop_pageview SET page_view = page_view+1 WHERE shop_id = ?";
            $para = array($param);
            $result = $this->db->executeSql($sql,$para);
        } else {
            $params['SHOP_ID'] = $param;
            $params['PAGE_VIEW'] = 1;
            $result = $this->db->insertData("shop_pageview", $params);
        }
     }//--end:function


    /**
     * 該当ショップのランクインされたランキングデータ取得
     * @param array $param
     *              keyname is shop_id
     *
     *
     * @return array $results
    */
    public function getRankingFromShopid($param)
    {
        $now_post_num = $param['now_post_num'];
        $get_post_num = $param['get_post_num'];
        //データ抽出SQLクエリ
        $sql = "
                SELECT
                    a.shop_id,
                    a.photo,
                    b.rank_id,
                    b.title,
                    b.user_id,
                    b.created_at,
                    ifnull(v.page_view,'0') as page_view,
                    ifnull(u.user_name,'') as user_name,
                    u.photo as user_photo,
                    s.shop_name
                FROM
                    ranking_detail a
                INNER JOIN
                    ranking b
                ON
                    a.rank_id = b.rank_id
                LEFT JOIN
                    shop s
                ON
                    a.shop_id = s.shop_id
                LEFT JOIN
                    rank_pageview v
                ON
                    a.rank_id = v.rank_id
                LEFT JOIN
                    user u
                ON
                    b.user_id = u.user_id
                WHERE
                    a.shop_id = ?
                AND
                    b.delete_flg = 0
                ORDER BY
                    a.rank_no
                ";
        $sql.= " LIMIT $now_post_num, $get_post_num ";
        $results = $this->db->selectPlaceQuery($sql,array($param['shop_id']));
        //一件も取れていなければFALSEを返す
        if ( $results == NULL  or count($results) == 0) {
            return FALSE;
        } else {
            return $results;
        }
    }

    /**
     * 該当ショップのランクインされたランキングデータ件数取得
     * @param array $param
     *              keyname is shop_id
     *
     *
     * @return array $results
    */
    public function getRankCountFromShopid($param)
    {
        $ret = 0;
        //データ抽出SQLクエリ
        $sql = "
	            SELECT
	               COUNT(*) AS CNT
                FROM
                    ranking_detail a
                INNER JOIN
                    ranking b
                ON
                    a.rank_id = b.rank_id

                WHERE
                    a.delete_flg = 0
                AND
                    a.shop_id = ?
                ";
        $results = $this->db->selectPlaceQuery($sql,array($param));
        //一件も取れていなければFALSEを返す
        if ( $results == NULL  or count($results) == 0) {
            $ret = 0;
        } else {
            return $results[0]['CNT'];
        }
        return $ret;
    }

    /**
     * みんな投稿した写真取得
     * @param array $param
     *              keyname is shop_id
     *
     *
     * @return array $results
    */
    public function getShopPhotos($param)
    {
        //データ抽出SQLクエリ
        $sql = "
                SELECT
                   a.user_id,a.photo,b.user_name,b.photo as user_photo,b.fb_pic as user_fb_photo
                FROM
                    beento a
                LEFT JOIN
                    user b
                ON
                    a.user_id = b.user_id

                WHERE
                    a.delete_flg = 0
                AND
                    a.shop_id = ?
                AND
                    a.photo is not null
                AND
                    a.photo <> ''
                ";
        $results = $this->db->selectPlaceQuery($sql,array($param));
        //一件も取れていなければFALSEを返す
        if ( $results == NULL  or count($results) == 0) {
            return false;
        } else {
            return $results;
        }
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
        //データ抽出SQLクエリ
        $sql = "
                SELECT
                    a.user_id,b.user_name,b.mail_address,b.photo as user_photo,b.fb_pic as user_fb_photo
                FROM
                    oen a
                LEFT OUTER JOIN
                    user b
                ON
                    a.user_id = b.user_id
                WHERE
                    a.shop_id  = :shop_id
                AND
                    a.delete_flg = 0
                AND
                    b.delete_flg = 0
                ";
        $results = $this->db->selectPlaceQuery($sql,$param);
        //一件も取れていなければFALSEを返す
        if ( $results == NULL  or count($results) == 0) {
            return FALSE;
        } else {
            return $results;
        }
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
        //データ抽出SQLクエリ
        $sql = "
                SELECT
                    city_code,value
                FROM
                    city
                WHERE
                    value = ?
                ";
        $results = $this->db->selectPlaceQuery($sql,array($param['city']));
        //一件も取れていなければFALSEを返す
        if ( $results == NULL  or count($results) == 0) {
            return FALSE;
        } else {
            return $results[0]['city_code'];
        }
    }

    /**
     * ジャンル名でジャンルid取得
     * @param array $param
     */
    public function getGenreByValue ($param) {
        $sql = "
                SELECT
                    genre_id,value
                FROM
                    genre
                WHERE
                    value = ?
                ";
        $results = $this->db->selectPlaceQuery($sql,array($param['value']));

        if ( $results == NULL  or count($results) == 0) {
            return FALSE;
        } else {
            return $results[0];
        }
    }

    /**
     * ジャンルidでジャンル名取得
     * @param array $param
     */
    public function getGenreById ($param) {
        $sql = "
                SELECT
                    genre_id,value
                FROM
                    genre
                WHERE
                    genre_id = ?
                ";
        $results = $this->db->selectPlaceQuery($sql,array($param['genre_id']));

        if ( $results == NULL  or count($results) == 0) {
            return FALSE;
        } else {
            return $results[0]['value'];
        }
    }

    /**
     * 都/道/府/県コードで都/道/府/県取得
     * @param array $param
     * @return array
     */
    public function getPrefById ($param) {
         $sql = "
                SELECT
                    value
                FROM
                    pref
                WHERE
                    pref_code = ?
                ";
        $results = $this->db->selectPlaceQuery($sql,array($param['pref_code']));
        if ( $results == NULL  or count($results) == 0) {
            return FALSE;
        } else {
            return $results[0]['value'];
        }
    }

    /**
     * 市/区/町/村コードで市/区/町/村取得
     * @param array $param
     * @return array
     */
    public function getCityById ($param) {
        $sql = "
                SELECT
                    value
                FROM
                    city
                WHERE
                    city_code = ?
                ";
        $results = $this->db->selectPlaceQuery($sql,array($param['city_code']));
        if ( $results == NULL  or count($results) == 0) {
            return FALSE;
        } else {
            return $results[0]['value'];
        }
    }

    /**
     * 親ジャンル取得
     * @return array
     */
    function getGenreParent ($param) {
    	$sql = "
                SELECT
                    genre_id , value
                FROM
                    genre
        		WHERE
					genre_id not like '%\_%'
				AND
				    (delete_flg = 0 or delete_flg is null)
                ";
    	$results = $this->db->selectPlaceQuery($sql, array());

    	if ( $results == NULL  or count($results) == 0) {
    		$ret = 0;
    	} else {
    		return $results;
    	}
    	return $ret;
    }

    /**
     * 親genre_idで子genre_id取得
     * @param array $param
     * @return array
     */
    function getGenreByGenreId ($param) {
    	$param['genre_parent'] = htmlentities(htmlspecialchars($param['genre_parent']),ENT_NOQUOTES);
    	$sql = "
                SELECT
					genre_id , value
                FROM
                    genre
        		WHERE
					genre_id like '".$param['genre_parent']."\_%'
                ";
    	$results = $this->db->selectPlaceQuery($sql, array());

    	if ( $results == NULL  or count($results) == 0) {
    		$ret = false;
    	} else {
    		return $results;
    	}
    	return $ret;
    }

}
?>
