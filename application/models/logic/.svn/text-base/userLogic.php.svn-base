<?php

require_once (MODEL_DIR ."/logic/abstractLogic.php");
/**
 * ユーザーロジック
 *
 * @package   user
 * @author    xiuhui yang 2013/07/01 新規作成
 */
class userLogic extends abstractLogic {

    /**
     * ユーザー詳細データ取得
     *
     * @author: xiuhui yang
     * @param array $param
     *              keyname is user_id
     * @return array $results
     */
    public function getUserDetail($param)
    {
        //データ抽出SQLクエリ
        $sql = "
                SELECT
                    a.*,p.value as pref
                FROM
                    user a
                INNER JOIN
                    pref p
                ON
                    a.address1 = p.pref_code
                WHERE
                    a.user_id = :user_id
                AND
                    a.delete_flg = 0
                ";
        $results = $this->db->selectPlaceQuery($sql,$param);
        //一件も取れていなければFALSEを返す
        if ( $results == NULL  or count($results) == 0) {
            return FALSE;
        } else {
            return $results[0];
        }
    }

    /**
     * ユーザー詳細データ取得
     *
     * @author: xiuhui yang
     * @param array $param
     *              keyname is user_id
     * @return array $results
     */
    public function getUserComDetail($param)
    {
        $res = array();
        //データ抽出SQLクエリ
        $sql = "
                SELECT
                    a.*,p.value as pref
                FROM
                    user a
                LEFT JOIN
                    pref p
                ON
                    a.address1 = p.pref_code
                WHERE
                    a.user_id = :user_id
                ";
        $results = $this->db->selectPlaceQuery($sql,$param['user_id']);
        //一件も取れていなければFALSEを返す
        if ( $results == NULL  or count($results) == 0) {
            return FALSE;
        } else {
            $res['user_detail'] = $results[0];
            //ユーザがフォローされてる数をカウント
            $res['follow'] =  $this->followCount($param);
            //任意のユーザのフォロワー数を調べる
            $res['follower'] =  $this->followerCount($param);
            //マイランキング
            $res['rank_list'] =  $this->getMyrank($param);
            //参考にするランキング
            $res['reguru_list'] =  $this->getMyReguruRank($param);
            //行きたいお店
            $res['wantto_list'] =  $this->getMyWantto($param);
            //応援しているお店
            $res['oen_list'] =  $this->getMyOen($param);
            //行ったお店
            $res['beento_list'] =  $this->getMyBeeto($param);
            //投稿の多いジャンル情報取得
            $res['genre'] = $this->getMaxUserReportGenre($param);
            return $res;
        }
    }
    /**
     * ユーザーデータ更新
     *
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
     *                         SELF_INSTR
     *                         SITE_BLOG
     *                         FOLLOW_NOTICEFLAG
     *                         REGURU_NOTICEFLAG
     *
     * @return bool true/false
     */
    public function updateUser($param)
    {
        //更新条件
        $where = array($this->db->quoteInto("USER_ID = ?", intval($param['USER_ID'])));
        //トランザクション開始
        $this->db->beginTransaction();
        try{
            $result = $this->db->updateData( "user", $param, $where );
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
     * ユーザー住所情報取得ｓ
     * @author: yuya yamamoto
     * @param array $param
     *              keyname is user_id
     * @return array $results
     *             keyname is address1
     *                        address2
     */
    public function getUserAddress1Adress2($param)
    {
    	$params['user_id'] = $param['user_id'];
        //データ抽出SQLクエリ
        $sql = "
                SELECT
                    address1 , address2
                FROM
                    user
                WHERE
                    user_id = :user_id
                AND
                    delete_flg = 0
                ";
        $results = $this->db->selectPlaceQuery($sql,$params);

        //一件も取れていなければFALSEを返す
        if ( $results == NULL  or count($results) == 0) {
            return FALSE;
        } else {
            return $results[0];
        }
    }


    /**
     * 該当ユーザーのフルコース情報取得
     * @author: xiuhui yang
     * @param array $param
     *              keyname is user_id
     * @return array $results
     */
    public function getUserFullcourse($param)
    {
        //データ抽出SQLクエリ
        $sql = "
                SELECT
                    a.*,b.course_name,c.shop_name
                FROM
                    fullcourse a
                INNER JOIN
                    fullcourse_master b
                ON
                    a.course_id = b.course_id
                LEFT OUTER JOIN
                    shop c
                ON
                    a.shop_id = c.shop_id
                WHERE
                    a.user_id = :user_id
                AND
                    a.delete_flg = 0
                ORDER BY
                    a.course_id
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
     * 該当ユーザーのTOP3登録情報取得
     * @author: xiuhui yang
     * @param array $param
     *              keyname is user_id
     * @return array $resuts
     */
    public function getUserTop3List($param)
    {
        //データ抽出SQLクエリ
        $sql = "
                SELECT
                    a.*,b.*,c.shop_name
                FROM
                    top3 a
                INNER JOIN
                    category b
                ON
                    a.category_id = b.category_id
                LEFT OUTER JOIN
                    shop c
                ON
                    a.shop_id = c.shop_id
                WHERE
                    a.uid = :user_id
                AND
                    a.delete_flg = 0
                ORDER BY
                    a.category_id,a.top3_no
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
     * 該当ユーザーのログイン情報
     * @author: yuya yamamoto
     * @param array $param
     *              keyname is user_name
     *                        papassword
     * @return bool
     *             keyname is user_id
     *                        user_name　　　ユーザー名
     */
    public function searchUserIdFromMailAndPassward($param)
    {
        if( $param['mail_address'] == "" or  $param['password'] == "") {
			return false;
		}
    	$params['mail_address']  = $param['mail_address'];
    	$params['password'] = $param['password'];

        //データ抽出SQLクエリ
        $sql = "
                SELECT
                    user_id , user_name
                FROM
                    user
                WHERE
                    mail_address = :mail_address
                AND
                    password = :password
                AND
                    delete_flg = 0
                ";
        $results = $this->db->selectPlaceQuery($sql,$params);

        //一件も取れていなければFALSEを返す
        if (isset($results[0])) {
            return $results[0];
        } else {
            return false;
        }
    }


    /**
     * ユーザIDからユーザ情報を取り出す
     *
     * @author: yuya yamamoto
     * @param array $param
     *              keyname is user_id
     * @return array $results
     *
     */
    public function searchUserInfoFromUserId($param)
    {
        if( !isset($param['user_id'])) {
			return false;
		}
    	$params['user_id']  = $param['user_id'];

        //データ抽出SQLクエリ
        $sql = "
                SELECT
                    *
                FROM
                    user
                WHERE
                    user_id = :user_id
                AND
                    delete_flg = 0
                ";
        $results = $this->db->selectPlaceQuery($sql,$params);

        //一件も取れていなければFALSEを返す
        if (isset($results[0])) {
            return $results[0];
        } else {
            return false;
        }
    }


    /**
     * 既存ユーザ名があるかどうか調べ、なかったら新規登録
     * @author: yuya yamamoto
     * @param array $param
     *              keyname is user_name
     *                        password
     *                        address1
     * @return array
     */
    public function signup($param)
    {
    	$params['mail_address']  = $param['email'];

        //データ抽出SQLクエリ
        $sql = "
                SELECT
                    user_id
                FROM
                    user
                WHERE
                    mail_address = :mail_address
                    and
                    delete_flg =  0
                ";
        $results = $this->db->selectPlaceQuery($sql,$params);

		$params['address1']  = $param['address1'];
        //ユーザがいたら登録はできないためfalseを返す
        if (isset($results[0])) {
        	$ret['reason'] = 'すでにユーザがいます。';
        	$ret['user_exist_flg'] = 1;
			return $ret;
        }
        $birthday = "";
        if (isset($param['birthday_year']) != '0' && isset($param['birthday_month']) !='0' && isset($param['birthday_day']) != '0') {
            $birthday = $param["birthday_year"].'-'
                       .$param["birthday_month"].'-'
                       .$param["birthday_day"];
        }
        $param_1 = array (
	    'user_name' => strip_tags($param['user_name']),
        'mail_address' => strip_tags($param['email']),
       	'password' => strip_tags($param['password']),
        'gender' => $param['gender'],
        'birthday' => $birthday,
       	'address1' => $param['address1'],
	    'delete_flg' =>  0,
		);
        //トランザクション開始
        $this->db->beginTransaction();
        try{
            $result = $this->db->insertData("user", $param_1,false);
            $oauth_id = $this->db->lastInsertId();
        }
        catch(exception $e)
        {
            $this->db->rollBack();
        	$ret['reason'] = 'ユーザテーブルにユーザ名とパスワードのinsert失敗';
			return $ret;
        }
/*
        //②登録されたuser_idがそのままoauth_idになるのでそれを取得
        $param2['mail_address'] = $params['mail_address'];
        $param2['password'] = $params['password'];
        //データ抽出SQLクエリ
        $sql = "
                SELECT
                    user_id
                FROM
                    user
                WHERE
                    mail_address = :mail_address
                AND
                	password  = :password
                AND
                	delete_flg = 0
               ";
        $res2 = $this->db->selectPlaceQuery($sql,$param2);
        if (!isset($res2[0])) {
        	$ret['reason'] = 'ユーザIDの取得ができていない';
        	$this->db->rollBack();
			return $ret;
        }
*/
        $res2[0]['user_id'] = $oauth_id ;
       //③oauthテーブルにオースIDを登録
	   	$param_oauth = array (
		    'oauth_id'  	=> $res2[0]['user_id'],
	   		'fb_delete_flg' => 0,
	   		'fb_connect_flg' => 0,
	   		'fb_login_flg' => 0
		);
        //トランザクション開始
        try{
            $res_oauth = $this->db->insertData("oauth", $param_oauth , false);
        }
        catch(exception $e)
        {
            $this->db->rollBack();
        	$ret['reason'] = 'oauthテーブルとuserテーブルにuser_idをoauth_idとして登録できない';
			return $ret;
        }


        //④ユーザテーブルのオースIDを更新
        $where = array($this->db->quoteInto("USER_ID = ?", $res2[0]['user_id']));
        //トランザクション開始
        $param_user_oauth['oauth_id'] = $res2[0]['user_id'];
        try{
            $result = $this->db->updateData( "user", $param_user_oauth, $where );
            $this->db->commit();
        }
        catch(exception $e)
        {
            $this->db->rollBack();
            return false;
        }

		$return['user_id'] =  $res2[0]['user_id'];
	    return $return;
    }
    /**
     * ユーザー退会
     *
     * @aouther: yuyayamamoto
     * @param string   USER_ID

     * @return bool true/false
     */
    public function retire($user_id)
    {
    	$oauth_id = $user_id;
    	//userテーブルとoauthテーブルのdelete_flgを更新
    	$param['delete_flg'] = 1;

    	//ユーザテーブルから更新
        $where = array($this->db->quoteInto("USER_ID = ?", $user_id));
        //トランザクション開始
        $this->db->beginTransaction();
        try{
            $result = $this->db->updateData( "user", $param, $where );
            $this->db->commit();
        }
        catch(exception $e)
        {
            $this->db->rollBack();
            return false;
        }

        //oauthテーブルもから更新
        $socialparam['fb_delete_flg'] = 1;
        $where = array($this->db->quoteInto("OAUTH_ID = ?", $oauth_id));
        //トランザクション開始
        $this->db->beginTransaction();
        try{
            $result = $this->db->updateData( "oauth", $socialparam, $where );
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
     * ユーザをフォローする
     * @aouther: yuya yamamoto
     * @param  array
     *              user_id
     *              follow_user_id
     * @return boolian
     */
    public function follow($params)
    {

        //followフォローする人、followerフォローされる人
       	$param = array (
	    'follower' => $params['user_id'],
   		'follow' => $params['follow_user_id']
		);

        //トランザクション開始
        $this->db->beginTransaction();
        try{
            $result = $this->db->insertData("follow", $param);
            $this->db->commit();
        }
        catch(exception $e)
        {
            $this->db->rollBack();
        	$ret['reason'] = '失敗';
			return false;
        }
        return true;
    }
   /**
     * ユーザフォローを解除する
     * @aouther: yuya yamamoto
     * @param  array
     *              user_id
     *              follow_user_id
     * @return boolian
     */
    public function unfollow($params)
    {

        //followフォローする人、followerフォローされる人
       	$param = array (
	    'follower' => $params['user_id'],
   		'follow' => $params['follow_user_id']
		);
        //更新条件
        $where = array( $this->db->quoteInto("follower = ?",$param['follower']),
                        $this->db->quoteInto("follow = ?",$param['follow'])
                        );
        //トランザクション開始
        $this->db->beginTransaction();
        try{
            $this->db->deleteData( "follow", $where );
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
     * ユーザがユーザをフォローしているかチェック
     * @aouther: yuya yamamoto
     * @param  array
     *              user_id
     *              follow_user_id
     * @return boolian
     */
    public function checkFollow($params)
    {
            //データ抽出SQLクエリ
        $sql = "
                SELECT
                    id
                FROM
                    follow
                WHERE
                    follow   = :follow
                AND
                    follower = :follower
                ";
        $results = $this->db->selectPlaceQuery($sql,$params);
        //一件も取れていなければFALSEを返す
        if ( $results == NULL  or count($results) == 0) {
            return FALSE;
        } else {
            return true;
        }
    }

   /**
     * 任意のユーザのフォロー数を調べる
     * @aouther: yuya yamamoto
     * @param  array
     *              user_id
     * @return boolian
     */
    public function followCount($params)
    {
    	$ret = 0;
    	$follow = $params['user_id'];

        $sql = "
                SELECT
                    a.follow,COUNT(*) AS CNT
                FROM
                    follow a
                INNER JOIN
                    user b
                ON
                    a.follow = b.user_id
                WHERE
                    a.follower = ?
                AND
                    b.delete_flg = 0

                ";
        $results = $this->db->selectPlaceQuery($sql,  array($follow));

        if ( $results == NULL  or count($results) == 0) {
            $ret = 0;
        } else {
            return $results[0]['CNT'];
        }
        return $ret;
    }

    /**
     * 任意のユーザのフォロワー数を調べる
     * @param string $param
     * @return  array $results
     *                keyname is CNT
    */
    public function followerCount($param)
    {
    	$ret = 0;
        $follower = $param['user_id'];
        $sql = "
                SELECT
                    a.follower,COUNT(*) AS CNT
                FROM
                    follow a
                INNER JOIN
                    user b
                ON
                    a.follower = b.user_id
                WHERE
                    a.follow = ?
                AND
                    b.delete_flg = 0
                ";
        $results = $this->db->selectPlaceQuery($sql, array($follower));

        if ( $results == NULL  or count($results) == 0) {
            $ret = 0;
        } else {
            return $results[0]['CNT'];
        }
        return $ret;
    }

    /**
     * 任意のユーザのクーポン情報を取得する
     * @author: yuya yamamoto
     * @param array $param
     *              keyname is user_id
     * @return array $resuts
     */
    public function getCouponInfo($param)
    {

    	//常連クーポン
    	$sql = "
                SELECT
                    s.shop_name , c.view_flg, c.coupon ,c.contenue, c.title , c.public_start , c.public_end , c.shop_id, c.coupon_id , r.user_id , s.latitude , s.longitude
                FROM
                    coupon c
                LEFT JOIN
                    regular_customer_coupon r
                ON
                    c.coupon_id = r.coupon_id
                LEFT JOIN
                    shop s
                ON
                    s.shop_id = c.shop_id
                WHERE
                     (c.view_flg = 4)
                 AND
                     r.user_id   = ?
                 AND
                    c.delete_flg  = 0
                 AND
                    c.publish_flg = 1
	             ORDER BY
                    UNIX_TIMESTAMP(c.updated_at)desc,UNIX_TIMESTAMP(c.created_at) desc
                ";
    	$ret1 = $this->db->selectPlaceQuery($sql,array($param['user_id']));
    	if ($ret1 == NULL  or count($ret1) == 0) {
    		$results['regular'] = "";
    	} else {
    		$results['regular'] = $ret1;
    	}
    	//招待クーポン
    	$sql = "
                SELECT
                    s.shop_name , c.view_flg, c.coupon ,c.contenue, c.title , c.public_start , c.public_end , c.shop_id, c.coupon_id , i.user_id , s.latitude , s.longitude
                FROM
                    coupon c
                LEFT JOIN
                    inviting_coupon i
                ON
                    c.coupon_id = i.coupon_id
                LEFT JOIN
                    shop s
                ON
                    s.shop_id = c.shop_id
                WHERE
                    c.view_flg = 5
                 AND
                    i.user_id   = ?
                 AND
                    c.delete_flg  = 0
                 AND
                    c.publish_flg = 1
	             ORDER BY
                    UNIX_TIMESTAMP(c.updated_at)desc,UNIX_TIMESTAMP(c.created_at) desc
                ";
    	$ret1 = $this->db->selectPlaceQuery($sql,array($param['user_id']));
    	if ($ret1 == NULL  or count($ret1) == 0) {
    		$results['inviting'] = "";
    	} else {
    		$results['inviting'] = $ret1;
    	}


    	//応援クーポン取得
    	$sql = "
                SELECT
				    s.shop_name , c.view_flg ,s.shop_id , s.latitude , s.longitude , c.coupon ,c.contenue, c.title , c.public_start , c.public_end , c.coupon_id , o.user_id
                FROM
                    coupon c
                LEFT JOIN
                    oen o
                ON
                    o.shop_id = c.shop_id
                LEFT JOIN
                    shop s
                ON
                    s.shop_id = c.shop_id
                WHERE
                    o.user_id   = ?
                AND
                	c.view_flg  = 2
                AND
                    c.delete_flg  = 0
                AND
                    c.publish_flg = 1
	            ORDER BY
                    UNIX_TIMESTAMP(c.updated_at)desc,UNIX_TIMESTAMP(c.created_at) desc
                ";

    	$ret2 = $this->db->selectPlaceQuery($sql,array($param['user_id']));
    	if ($ret2 == NULL  or count($ret2) == 0) {
    		$results['oen'] = "";
    	} else {
    		$results['oen'] = $ret2;
    	}

    	/*招待されているクーポン
    	 テーブルのinvitingはユーザが紹介した人のユーザIDが登録されるが、
    	invitee_idとしてselectする（自分が招待されている人）
    	invitee_idは紹介した人のuser_id
    	英語的にDBと逆になっているという…ごめんなさい。。。
    	*/
    	$param2['inviting'] = (int)$param['user_id'];
    	$sql5 = "
                SELECT
                    s.shop_name , c.view_flg, c.coupon ,c.contenue, c.title , c.public_start , c.public_end , c.shop_id, c.coupon_id , r.inviting as invitee_id , r.user_id as inviting_id , s.latitude , s.longitude , u.user_name
                FROM
                    coupon c
                LEFT JOIN
                    regular_customer_coupon r
                ON
                    c.coupon_id = r.coupon_id
                LEFT JOIN
                    shop s
                ON
                    s.shop_id = c.shop_id
                LEFT JOIN
                	user u
                ON
                    u.user_id = r.user_id
                WHERE
                    c.view_flg = 5
                AND
                    r.inviting   = ?
                AND
                    c.delete_flg  = 0
                AND
                    c.publish_flg = 1
	            ORDER BY
                    UNIX_TIMESTAMP(c.updated_at)desc,UNIX_TIMESTAMP(c.created_at) desc
                ";

    	$ret3 = $this->db->selectPlaceQuery($sql5,array($param2['inviting']));
    	if ($ret3 == NULL  or count($ret3) == 0) {
    		$results['invitee'] = "";
    	} else {
    		$results['invitee'] = $ret3;
    	}
    	return $results;
    }

    /**
     * 任意のユーザのランキング一覧情報取得
     * @author: xiuhui yang
     * @param array $param
     *              keyname is user_id
     * @return array $resuts
     */
    public function getMyrank($param)
    {
        //データ抽出SQLクエリ
        $sql = "
                SELECT
                    rank_id,title
                FROM
                    ranking
                WHERE
                    user_id = ?
                AND
                    delete_flg = 0
                ORDER BY
                     UNIX_TIMESTAMP(updated_at)desc,UNIX_TIMESTAMP(created_at) desc
                ";
        $results = $this->db->selectPlaceQuery($sql,array($param['user_id']));
        //一件も取れていなければFALSEを返す
        if ( $results == NULL  or count($results) == 0) {
            return FALSE;
        } else {
            return $results;
        }
    }


    /**
     * 任意のユーザの参考にするランキング一覧
     * @author: xiuhui yang
     * @param array $param
     *              keyname is user_id
     * @return array $resuts
     */
    public function getMyReguruRank($param)
    {
        //データ抽出SQLクエリ
        $sql = "
                SELECT
                    a.*,b.title
                FROM
                    reguru_info a
                INNER JOIN
                    ranking b
                ON
                      a.rank_id = b.rank_id
                WHERE
                    a.reguru_uid = ?
                AND
                    a.delete_flg = 1
                AND
                    b.delete_flg = 0
                ORDER BY
                     UNIX_TIMESTAMP(a.updated_at)desc,UNIX_TIMESTAMP(a.created_at) desc
                ";
        $results = $this->db->selectPlaceQuery($sql,array($param['user_id']));
        //一件も取れていなければFALSEを返す
        if ( $results == NULL  or count($results) == 0) {
            return FALSE;
        } else {
            return $results;
        }
    }

    /**
     * 行きたい店全部経度、緯度、店名取得
     * @param array $param
     * @return array
     */
    public function getMyWanttolatlog($param)
    {
            $sql = "
                SELECT
					b.shop_name,
        			b.latitude,
        			b.longitude
                FROM
                    shop_voting a
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
                WHERE
                    a.user_id = ?
                AND
                    a.voting_kind = 2
                AND
                    a.delete_flg = 0
                AND
                    b.delete_flg = 0
                ";
        $results = $this->db->selectPlaceQuery($sql,array($param['user_id']));
        //一件も取れていなければFALSEを返す
        if ( $results == NULL  or count($results) == 0) {
            return FALSE;
        } else {
            return $results;
        }
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
                    g3.value as genre3_value
                FROM
                    shop_voting a
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
                WHERE
                    a.user_id = ?
                AND
                    a.voting_kind = 2
                AND
                    a.delete_flg = 0
                AND
                    b.delete_flg = 0
                ORDER BY
                     UNIX_TIMESTAMP(a.updated_at)desc,UNIX_TIMESTAMP(a.created_at) desc
                ";
                if (isset($param['now_post_num']) !="" && isset($param['get_post_num']) !="") {
                    $now_post_num = $param['now_post_num'];
                    $get_post_num = $param['get_post_num'];
                    $sql.= " LIMIT $now_post_num, $get_post_num ";
                }
        $results = $this->db->selectPlaceQuery($sql,array($param['user_id']));
        //一件も取れていなければFALSEを返す
        if ( $results == NULL  or count($results) == 0) {
            return FALSE;
        } else {
            return $results;
        }
    }

    /**
     * 応援している店全部経度、緯度、店名取得
     * @param array $param
     * @return array
     */
    public function getMyOenlatlog($param)
    {
            $sql = "
               SELECT
					b.shop_name,
        			b.latitude,
        			b.longitude
                FROM
                    oen a
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
                WHERE
                    a.user_id = ?
                AND
                    a.delete_flg = 0
                AND
                    b.delete_flg = 0
                ";
        $results = $this->db->selectPlaceQuery($sql,array($param['user_id']));
        //一件も取れていなければFALSEを返す
        if ( $results == NULL  or count($results) == 0) {
            return FALSE;
        } else {
            return $results;
        }
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
                    g3.value as genre3_value
                FROM
                    oen a
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
                WHERE
                    a.user_id = ?
                AND
                    a.delete_flg = 0
                AND
                    b.delete_flg = 0
                ORDER BY
                     UNIX_TIMESTAMP(a.updated_at)desc,UNIX_TIMESTAMP(a.created_at) desc
                ";
                if (isset($param['now_post_num']) !="" && isset($param['get_post_num']) !="") {
                    $now_post_num = $param['now_post_num'];
                    $get_post_num = $param['get_post_num'];
                    $sql.= " LIMIT $now_post_num, $get_post_num ";
                }
        $results = $this->db->selectPlaceQuery($sql,array($param['user_id']));
        //一件も取れていなければFALSEを返す
        if ( $results == NULL  or count($results) == 0) {
            return FALSE;
        } else {
            return $results;
        }
    }

	public function getMyBeetolatlog ($param) {
		$sql = "
                SELECT
					b.shop_name,
        			b.latitude,
        			b.longitude
                FROM
                    beento a
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
                WHERE
                    a.user_id = ?
                AND
                    a.delete_flg = 0
                AND
                    b.delete_flg = 0
                ";
		$results = $this->db->selectPlaceQuery($sql,array($param['user_id']));
		//一件も取れていなければFALSEを返す
		if ( $results == NULL  or count($results) == 0) {
			return FALSE;
		} else {
			return $results;
		}
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
        //データ抽出SQLクエリ
        $sql = "
                SELECT
                    a.*,
                    b.shop_name,
                    b.pref_code,
                    b.city_code,
                    b.latitude,
                    b.longitude,
                    c.value as pref,
                    d.value as city,
                    g1.value as genre1_value,
                    g2.value as genre2_value,
                    g3.value as genre3_value,
                    u.photo as user_photo,
                    u.fb_pic as user_fb_photo,
                    ifnull(u.user_name,'') as user_name
                FROM
                    beento a
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
                    a.user_id = u.user_id
                WHERE
                    a.user_id = ?
                AND
                    a.delete_flg = 0
                AND
                    b.delete_flg = 0
                ORDER BY
                     UNIX_TIMESTAMP(a.updated_at)desc,UNIX_TIMESTAMP(a.created_at) desc
                ";
                if (isset($param['now_post_num']) !="" && isset($param['get_post_num']) !="") {
                    $now_post_num = $param['now_post_num'];
                    $get_post_num = $param['get_post_num'];
                    $sql.= " LIMIT $now_post_num, $get_post_num ";
                }
        $results = $this->db->selectPlaceQuery($sql,array($param['user_id']));
        //一件も取れていなければFALSEを返す
        if ( $results == NULL  or count($results) == 0) {
            return FALSE;
        } else {
            return $results;
        }
    }

    /**
     * getMyRankList
     * @auther: xiuhui yang
     * ランキング一覧を取得
     * @param array param
     *              keyname is now_post_num
     *                         get_post_num
     *                         user_id
     *
     * @return array $results
     *
     */
    public function getMyRankList($param)
    {
        $now_post_num = $param['now_post_num'];
        $get_post_num = $param['get_post_num'];
        //データ抽出SQLクエリ
        $sql = "
                SELECT
                    a.rank_id,
                    a.title,
                    a.user_id,
                    a.updated_at,
                    ifnull(v.page_view,'0') as page_view,
                    u.photo as user_photo,
                    u.fb_pic as user_fb_photo,
                    ifnull(u.user_name,'') as user_name,
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
                AND
                    a.user_id = ?
                ORDER BY
                    UNIX_TIMESTAMP(a.updated_at)desc
                LIMIT
                    $now_post_num, $get_post_num
                ";

        $results = $this->db->selectPlaceQuery($sql,array($param['user_id']));
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
    public function getMyReguruRankList($param)
    {
        $now_post_num = $param['now_post_num'];
        $get_post_num = $param['get_post_num'];
        //データ抽出SQLクエリ
        $sql = "
                SELECT
                    a.rank_id,
                    a.title,
                    a.user_id,
                    a.updated_at,
                    ifnull(v.page_view,'0') as page_view,
                    u.photo as user_photo,
                    u.fb_pic as user_fb_photo,
                    ifnull(u.user_name,'') as user_name,
                    bb.reguru_uid,bb.delete_flg,
                    '' as detail
                FROM
                    ranking a
                INNER JOIN
                    reguru_info bb
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
                AND
                    bb.reguru_uid = ?
                AND
                    bb.delete_flg = 1
                ORDER BY
                    UNIX_TIMESTAMP(a.updated_at)desc
                LIMIT
                    $now_post_num, $get_post_num
                ";
        $results = $this->db->selectPlaceQuery($sql,array($param['user_id']));
        //一件も取れていなければFALSEを返す
        if ( $results == NULL  or count($results) == 0) {
            return FALSE;
        } else {
            return $results;
        }
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
        //データ抽出SQLクエリ
        $sql = "
                SELECT
                    a.follow,
                    b.user_id,
                    b.user_name,
                    b.photo as user_photo,
                    b.fb_pic
                FROM
                    follow a
                INNER JOIN
                    user b
                ON
                    a.follow = b.user_id
                WHERE
                    a.follower = ?
                AND
                    b.delete_flg = 0
                ";
        $results = $this->db->selectPlaceQuery($sql,array($param));
        //一件も取れていなければFALSEを返す
        if ( $results == NULL  or count($results) == 0) {
            return FALSE;
        } else {
            return $results;
        }
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
        //データ抽出SQLクエリ
        $sql = "
                SELECT
                    a.follower,
                    b.user_id,
                    b.user_name,
                    b.photo as user_photo,
                    b.fb_pic
                FROM
                    follow a
                INNER JOIN
                    user b
                ON
                    a.follower = b.user_id
                WHERE
                    a.follow = ?
                AND
                    b.delete_flg = 0
                ";
        $results = $this->db->selectPlaceQuery($sql,array($param));
        //一件も取れていなければFALSEを返す
        if ( $results == NULL  or count($results) == 0) {
            return FALSE;
        } else {
            return $results;
        }
    }

   /**
     * メールアドレスによりユーザー存在チェック
     * @aouther: yang
     * @param  string $email
     *
     * @return boolian
     */
    public function checkMailExist($email)
    {
        $sql = "
                SELECT
                    COUNT(*) AS CNT
                FROM
                    user
                WHERE
                    mail_address = ?
                ";
        $results = $this->db->selectPlaceQuery($sql,  array($email));

        if ( $results == NULL  or count($results) == 0) {
            $ret = 0;
        } else {
            return $results[0]['CNT'];
        }
        return $ret;
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
        //更新条件
        $where = array( $this->db->quoteInto("MAIL_ADDRESS = ?",$params['mail_address'])
                        );
        //トランザクション開始
        $this->db->beginTransaction();
        try{
            $result = $this->db->updateData( "user", $params, $where );
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
     * 投稿の多いジャンル
     * @author: xiuhui yang
     * @param array $params
     *              keyname is user_id
     *
     * @return array $resuts
    */
    public function getMaxUserReportGenre($param) {
        $sql = "
                SELECT
                        a.* ,
                        g1.value as genre1_value,
                        g2.value as genre2_value,
                        g3.value as genre3_value
               FROM
                        ( (select shop_id,count(*) as cnt from beento where user_id = {$param['user_id']} group by shop_id order by cnt desc  limit 1)
                        union all
                        (select shop_id,count(*) as cnt from ranking_detail where user_id = {$param['user_id']}  group by shop_id order by cnt desc  limit 1)
                        order by cnt desc limit 1  )  as a
                INNER JOIN
                    shop b
                ON
                    a.shop_id = b.shop_id
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
                ";
        $results = $this->db->selectPlaceQuery($sql,array());
        //一件も取れていなければFALSEを返す
        if ( $results == NULL  or count($results) == 0) {
            return FALSE;
        } else {
            return $results[0];
        }
    }
}
?>
