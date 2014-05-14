<?php

require_once (MODEL_DIR ."/logic/abstractLogic.php");

/**
 * ショップロジック
 *
 * @package   client
 * @author    playab 2013/07/29 新規作成
 */
class clientLogic extends abstractLogic {

    /**
     * 自分のお店に行ったことのある人の人数と応援している人の人数
     * @param string $param
     * @return  array $results
     *                keyname is CNT
    */
    public function getOenCount($param)
    {
        $sql = "
                SELECT
                    COUNT(*) AS CNT
                FROM
                    oen
                WHERE
                    shop_id = ?
                AND
                    delete_flg = 0
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
     * 自分のお店に行ったことある人の数
     * @param string $param
     * @return  array $results
     *                keyname is CNT
    */
    public function getBeentoCount($param)
    {
        $sql = "
                SELECT
                    COUNT(*) AS CNT
                FROM
                    beento
                WHERE
                    shop_id = ?
                AND
                    delete_flg = 0
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
     * 自分のお店が入っているランキングは
     * @param string $param
     * @return  array $results
    */
    public function getTop3List($param)
    {
        $sql = "
                SELECT
                    ranking_detail.*,ranking.title,
        			(select page_view from rank_pageview where ranking_detail.rank_id=rank_pageview.rank_id) as pv,
        		    (select count(comment) from reguru_info where ranking_detail.rank_id=reguru_info.rank_id) as com
                FROM
                    ranking_detail
        		LEFT JOIN
        			ranking
        		ON
        			ranking_detail.rank_id=ranking.rank_id
                WHERE
                    shop_id = ?
                AND
                    ranking_detail.delete_flg = 0
        		order by ranking_detail.created_at desc
        		LIMIT 0,3
                ";
        $results = $this->db->selectPlaceQuery($sql, array($param));

        if ( $results == NULL  or count($results) == 0) {
            return FALSE;
        } else {
            return $results;
        }
    }
    
    /**
     * shop_idによってshop情報取得
     * @param int $shop_id
     * @return array $results
     */
    public function getShopById($shop_id)
    {
    	$sql = "
    			SELECT
    			     *
    			FROM
    			    shop 
    			WHERE
    				shop_id = ?
    			AND 
    				delete_flg = 0
    		   ";
    	$results = $this->db->selectPlaceQuery($sql, $shop_id);
    	if ( $results == NULL  or count($results) == 0) {
    		return FALSE;
    	} else {
    		return $results;
    	}
    }
    
    /**
     * coupon_idによってクーポン情報取得
     * @param int $coupon_id
     * @return array $res
     */
    function getCouponByCouponId($coupon_id)
    {
        	$sql = "
    			SELECT
    			     *
    			FROM
    			    coupon 
    			WHERE
    				coupon_id = ?
    			AND 
    				delete_flg = 0
    		   ";
    	$results = $this->db->selectPlaceQuery($sql, $coupon_id);
    	if ( $results == NULL  or count($results) == 0) {
    		return FALSE;
    	} else {
    		return $results;
    	}
    }
    
    /**
     * shop_idによってshop情報アップデート
     * @param array $param
     * @return array $results
     */
    public function updateShopById($param)
    {
    	$results = $this->db->updateData('shop',$param,array('shop_id=?'=>$param['shop_id']));
    	if( $results === FALSE) {
    		return FALSE;
    	} else {
    		return $results;
    	}
    }
    
    /**
     * shop_idによってクーポン情報取得
     * @param int $shop_id
     */
    public function getCouponByShopId($param)
    {
    	$sql = "
    			SELECT
    			     *
    			FROM
    			    coupon 
    			WHERE
    				shop_id = :shop_id
    			AND
    				view_flg = :view_flg
    			AND 
    				delete_flg = 0
    		   ";
        $results = $this->db->selectPlaceQuery($sql, $param);
    	if ( $results == NULL  or count($results) == 0) {
    		return FALSE;
    	} else {
    		return $results;
    	}
    }
    
    /**
     * クーポン存在判断
     * @param array $param
     * @return array
     */
    function getCouponExists($param)
    {
            $sql = "
                SELECT
                    coupon_id
                FROM
                    coupon
                WHERE
                    title = :title
            	AND
            		shop_id = :shop_id
            	AND
            		coupon = :coupon
                ";
        $results = $this->db->selectPlaceQuery($sql,array('title'=>$param['title'],'shop_id'=>$param['shop_id'],'coupon'=>$param['coupon']));

        if (isset($results[0])) {
            return $results[0];
        } else {
            return false;
        }
    }
    
    function getCouponCopyExists ($param) {
                $sql = "
                SELECT
                    coupon_id
                FROM
                    coupon
                WHERE
                    title = :title
            	AND
            		shop_id = :shop_id
            	AND
            		coupon = :coupon
                AND
            		public_start = :public_start
                AND
            		public_end = :public_end
               AND
            		view_flg = :view_flg
               AND
                	delete_flg = 0
                ";
        $results = $this->db->selectPlaceQuery($sql,array('title'=>$param['title'],'shop_id'=>$param['shop_id'],'coupon'=>$param['coupon'],'public_start'=>$param['public_start'],'public_end'=>$param['public_end'],'view_flg'=>$param['view_flg']));

        if (isset($results[0])) {
            return $results[0];
        } else {
            return false;
        }
    }
    
    /**
     * クーポン挿入
     * @param array $param
     * @return bool $res
     */
    public function insertCoupon($param)
    {
    	//トランザクション開始
    	$this->db->beginTransaction();
    	try{
    		$user_id = $param['user_id'];
    		unset($param['user_id']);
    		$result = $this->db->insertData("coupon", $param);
    		$coupon_id = $this->db->lastInsertId();
    		if($param['view_flg'] == 4) {
    			$this->db->insertData("regular_customer_coupon", array('user_id'=>$user_id,'coupon_id'=>$coupon_id));
    		}
    		else if($param['view_flg'] == 5) {
    			$this->db->insertData("inviting_coupon", array('user_id'=>$user_id,'coupon_id'=>$coupon_id));
    		}
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
     * クーポンアップデート
     * @param array $param
     * @return bool $res
     */
    function updateCoupon($param)
    {
    	$this->db->beginTransaction();
    	try{
    		if($param['view_flg'] == 4 || $param['view_flg'] == 5) {
     			$param['view_flg'] == 4 ? $from	= 'regular_customer_coupon' : $from	= 'inviting_coupon';
				$this->db->deleteData('regular_customer_coupon',array('coupon_id=?'=>$param['coupon_id']));
				$this->db->deleteData('inviting_coupon',array('coupon_id=?'=>$param['coupon_id']));
				$this->db->insertData($from, array('coupon_id'=>$param['coupon_id'],'user_id'=>$param['user_id']));
    		}
    		unset($param['user_id']);
    		$this->db->updateData('coupon',$param,array('coupon_id=?'=>$param['coupon_id']));
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
     * shop_idによってスタッフ取得
     * @param int $shop_id
     * @return array $results
     */
    function getStaffByShopId($shop_id)
    {
    	$sql = "
    			SELECT
    			     *
    			FROM
    			    shop_staff
    			WHERE
    				shop_id = ?
    			AND
    				delete_flg = 0
    		   ";
    	$results = $this->db->selectPlaceQuery($sql, $shop_id);
    	if ( $results == NULL  or count($results) == 0) {
    		return FALSE;
    	} else {
    		return $results;
    	}
    }
    
    /**
     * staff_idによってスタッフ取得
     * @param int $staff_id
     * @return array
     */
    function getStaffByStaffId($staff_id)
    {
        $sql = "
                SELECT
                    *
                FROM
                    shop_staff
                WHERE
                    staff_id = ?
                AND
                    delete_flg = 0
                ";
        $results = $this->db->selectPlaceQuery($sql,$staff_id);

        if (isset($results[0])) {
            return $results[0];
        } else {
            return false;
        }
    }
    
    /**
     * staff存在判断
     * @param array $param
     * @return array
     */
    function getStaffExisit($param)
    {
    	$sql = "
                SELECT
                    staff_id
                FROM
                    shop_staff
                WHERE
                    shop_id = :shop_id
            	AND
            		email = :email
            	AND
            		staff_name = :staff_name
                ";
    	$results = $this->db->selectPlaceQuery($sql,array('shop_id'=>$param['shop_id'],'email'=>$param['email'],'staff_name'=>$param['staff_name']));
    	
    	if (isset($results[0])) {
    		return $results[0];
    	} else {
    		return false;
    	}
    }
    
    /**
     * shopIdによって店舗ページクーポン情報取得
     * @param int $shopId
     * @return array $res
     */
    function getCouponByShopIdFromIndex($shopId)
    {
            $sql = "
                SELECT
                    coupon_id , title , coupon , public_start , public_end
                FROM
                    coupon
                WHERE
                    view_flg = 1
                AND
                    delete_flg = 0
            	AND
            		publish_flg = 1
            	AND
            		shop_id = ?
                ";
        $results = $this->db->selectPlaceQuery($sql,$shopId);

        if (isset($results)) {
            return $results;
        } else {
            return false;
        }
    }
    
    /**
     * スタッフ挿入
     * @param array $param
     * @return bool $res
     */
    function insertStaff($param)
    {
    	//トランザクション開始
    	$this->db->beginTransaction();
    	try{
    		$result = $this->db->insertData("shop_staff", $param);
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
     * スタッフアップデート
     * @param array $param
     * @return bool $res
     */
    function updateStaff($param)
    {
    	try{
    		$rs = $this->db->updateData('shop_staff',array('password'=>$param['password'],'email'=>$param['email'],'status'=>$param['status'],'staff_name'=>$param['staff_name'],'updated_at'=>$param['updated_at']),array('staff_id=?'=>$param['staff_id'],'shop_id=?'=>$param['shop_id']));
    	}
    	catch(exception $e)
    	{
    		echo $e->getMessage();exit;
    		return false;
    	}
    	return $rs;
    }
    
    /**
     * スタッフログイン
     * @param array $param
     * @return array $res
     */
    function login($param)
    {
        $sql = "
                SELECT
                    staff_id , staff_name
                FROM
                    shop_staff
                WHERE
                    email = :email
                AND
                    password = :password
                AND
                    delete_flg = 0
                ";
        $results = $this->db->selectPlaceQuery($sql,$param);

        if (isset($results[0])) {
            return $results[0];
        } else {
            return false;
        }
    }
    
    /**
     * StaffIdによってshop_id取得
     * @param array $param
     */
    function searchShopInfoFromStaffId($param)
    {
            $sql = "
                SELECT
                    shop.shop_id,shop.shop_name
                FROM
                    shop_staff,shop
                WHERE
                    shop_staff.staff_id = :staff_id
            	AND
            		shop_staff.shop_id = shop.shop_id
                AND
                    shop_staff.delete_flg = 0
                ";
        $results = $this->db->selectPlaceQuery($sql,$param);

        if (isset($results[0])) {
            return $results[0];
        } else {
            return false;
        }
    }
    
    /**
     * shop_idによってShopInfo取得
     * @param int $shop_id
     */
    function getShopInfoByShopId($shop_id)
    {
        $sql = "
                SELECT
                    shop_url,address,business_day,shop_name
                FROM
                    shop
                WHERE
                    shop_id = ?
                AND
                    delete_flg = 0
                ";
        $results = $this->db->selectPlaceQuery($sql,$shop_id);

        if (isset($results[0])) {
            return $results[0];
        } else {
            return false;
        }
    }
    
    /**
     * 削除coupon
     * @param array $param
     * @return bool
     */
    function deleteCouponByCouponId($param)
    {
		return $this->db->updateData('coupon',array('delete_flg'=>1),array('coupon_id=?'=>$param['coupon_id']));
    }
    
    /**
     * shop_idによって行ったのユーザ取得
     * @param int $shop_id
     * @return array
     */
    function getBeentoListByShopId($shop_id)
    {
    	$sql = "
                SELECT
                    user_id
                FROM
                    beento
                WHERE
                    shop_id = ?
                AND
                    delete_flg = 0
    			order by created_at desc
        		Limit 0,6
                ";
    	$results = $this->db->selectPlaceQuery($sql, $shop_id);
    	
    	if ( $results == NULL  or count($results) == 0) {
    		$ret = FALSE;
    	} else {
    		return $results;
    	}
    	return $ret;		
    }
    
    /**
     * shop_idによって応援のユーザー取得
     * @param int $shop_id
     * @return array
     */
    function getOenListByShopId($shop_id)
    {
        $sql = "
                SELECT
                     user_id
                FROM
                    oen
                WHERE
                    shop_id = ?
                AND
                    delete_flg = 0
        		order by created_at desc
        		Limit 0,6
                ";
        $results = $this->db->selectPlaceQuery($sql, array($shop_id));

        if ( $results == NULL  or count($results) == 0) {
            $ret = FALSE;
        } else {
            return $results;
        }
        return $ret;
    }
    
    /**
     * shop_idによって行きたいのユーザー取得
     * @param int $shop_id
     * @return array
     */
    function getIkitaiListByShopId($shop_id)
    {
        $sql = "
                SELECT
                     user_id
                FROM
                    shop_voting
                WHERE
                    shop_id = ?
                AND
        			voting_kind = 2
        		AND
                    delete_flg = 0
        		order by created_at desc
        		Limit 0,6
                ";
        $results = $this->db->selectPlaceQuery($sql, array($shop_id));

        if ( $results == NULL  or count($results) == 0) {
            $ret = FALSE;
        } else {
            return $results;
        }
        return $ret;
    }
    
    /**
     * shop情報存在判断
     * @param array $param
     * @return array
     */
    function getShopExists($param) {
        $sql = "
                SELECT
                    shop_id
                FROM
                    shop
                WHERE
                    shop_name = :shop_name
            	AND
            		address = :address
                ";
        $results = $this->db->selectPlaceQuery($sql,array('shop_name'=>$param['shop_name'],'address'=>$param['address']));

        if (isset($results[0])) {
            return $results[0];
        } else {
            return false;
        }
    }
    
    /**
     * shop_idで応援しているのユーザー取得
     * @param array $param
     * @return array
     */
    function getOenUserByShopId ($param) {
            $sql = "
                SELECT
                    a.user_id,b.user_name
                FROM
                    oen a , user b
                WHERE
            		a.user_id = b.user_id
            	AND
                    a.shop_id = :shop_id
                ";
        $results = $this->db->selectPlaceQuery($sql,$param);
        if ( $results == NULL  or count($results) == 0) {
            return FALSE;
        } else {
            return $results;
        }
    }
    
    /**
     * shop_idで常連ユーザー取得
     * @param array $param
     * @return array
     */
    function getJouRenByShopId ($param) {
    	if($param['view_flg'] == '4') {
    		$from = 'regular_customer_coupon';
    	}
    	else if($param['view_flg'] == '5') {
    		$from = 'inviting_coupon';
    	}
    	else {
    		return false;
    	}
    	unset($param['view_flg']);
    	$sql = "
                SELECT
                    user_id
                FROM
                    $from
                WHERE
            		coupon_id = :coupon_id
                ";
    	$results = $this->db->selectPlaceQuery($sql,$param);
    	if ( $results == NULL  or count($results) == 0) {
    		return FALSE;
    	} else {
    		return $results[0];
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
    
    function getGenreNameByGenreId ($param) {
    	$sql = "
                SELECT
					value
                FROM
                    genre
        		WHERE
					genre_id = :genre_id
                ";
    	$results = $this->db->selectPlaceQuery($sql, array('genre_id'=>$param['genre_id']));
    
    	if ( $results == NULL  or count($results) == 0) {
    		$ret = false;
    	} else {
    		return $results[0]['value'];
    	}
    	return $ret;
    }
}
?>
