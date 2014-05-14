<?php
require_once (MODEL_DIR ."/logic/abstractLogic.php");

class adminLogic extends abstractLogic {

	/**
	 * ログイン判断
	 * @param array $params
	 * @return array
	 */
	function login ($param) {
        $sql = "
                SELECT
                    id , name
                FROM
                    regurume_manager
                WHERE
                    email = :email
        		AND
        			encrypted_password = :encrypted_password
                ";
        $results = $this->db->selectPlaceQuery($sql, $param);

        if ( $results == NULL  or count($results) == 0) {
            $ret = 0;
        } else {
            return $results[0];
        }
        return $ret;
	}

	/**
	 * admin_idによって管理者情報取得
	 * @param array $param
	 * @return array
	 */
	function searchAdminInfoFromAdminId ($param) {
        $sql = "
                SELECT
                    id , name
                FROM
                    regurume_manager
                WHERE
                    id = :id
                ";
        $results = $this->db->selectPlaceQuery($sql, $param);

        if ( $results == NULL  or count($results) == 0) {
            $ret = 0;
        } else {
            return $results[0];
        }
        return $ret;
	}

	/**
	 * 管理者存在判断
	 * @param array $param
	 * @return array
	 */
	function getAdminInfoExists ($param) {
        $sql = "
                SELECT
                    id
                FROM
                    regurume_manager
                WHERE
                    name = :name
        		OR
        			email = :email
                ";
        $results = $this->db->selectPlaceQuery($sql, $param);

        if ( $results == NULL  or count($results) == 0) {
            $ret = 0;
        } else {
            return $results[0];
        }
        return $ret;
	}

	/**
	 * 管理者挿入
	 * @param array $param
	 * @return bool
	 */
	function insertAdmin ($param) {
    	return $this->db->insertData("regurume_manager", $param);
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
                   	shop_id , shop_name
                FROM
                    shop
        		WHERE
        			delete_flg = 0
        		$search
        		limit 100
                ";
        unset($param['search']);
        $results = $this->db->selectPlaceQuery($sql, $param);

        if ( $results == NULL  or count($results) == 0) {
            $ret = false;
        } else {
            return $results;
        }
        return $ret;
	}

	/**
	 * shopIdでshop情報取得
	 * @param array $param
	 * @return array
	 */
	function getShopInfoByShopId ($param) {
        $sql = "
                SELECT
                    address , shop_url , business_day , regular_holiday , latitude , longitude
                FROM
                    shop
                WHERE
                    shop_id = :shop_id
                ";
        $results = $this->db->selectPlaceQuery($sql, $param);

        if ( $results == NULL  or count($results) == 0) {
            $ret = 0;
        } else {
            return $results;
        }
        return $ret;
	}

	/**
	 * 会員店舗追加(店舗情報ある)
	 * @param array $param
	 * @return bool
	 */
	function insertBillByShopId ($param) {
        $this->db->beginTransaction();
        try{
        	$status = $param['status'];
        	unset($param['status']);
        	if($status == 1) {//店舗と会員登録(status=1)
        		$sql = "
        		    SELECT
        				staff_id
        			FROM
        				shop_staff
        			WHERE
        				status = 1
        			AND
        				shop_id = :shop_id
        		   ";
        		$results = $this->db->selectPlaceQuery($sql, array('shop_id'=>$param['shop_id']));//staff情報存在確認
        		if ( $results == NULL  or count($results) == 0) {
        			$result = $this->db->insertData("shop_staff", array('staff_name'=>$param['staff_name'],'email'=>$param['staff_email'],'shop_id'=>$param['shop_id'],'delete_flg'=>0,'status'=>1));//staff追加
        			if($result === true) {
        				$param['shop_staff_id'] = $this->db->lastInsertId();//staff_id取る
        			}
        			else {
        				$this->db->rollBack();
        				return false;
        			}
        		}
        		else {
        			$this->db->rollBack();
        			return false;
        		}
        	}
        	else {
        		$param['staff_name'] = '';
        		$param['shop_staff_id'] = null;
        	}
        	$result = $this->db->insertData("bill",array('shop_id'=>$param['shop_id'],'shop_staff_id'=>$param['shop_staff_id'],'staff_name'=>$param['staff_name'],'shop_name'=>$param['shop_name'],'shop_address'=>$param['shop_address'],'tel'=>$param['tel'],'fax'=>$param['fax'],'email'=>$param['email'],'plan_status'=>$param['plan_status'],'delete_flg'=>0,'created_at'=>date('Y-m-d H:i:s',time())));//bill追加
        	if($result === true) {
        		$this->db->commit();
        	}
        	else {
        		$this->db->rollBack();
        		return false;
        	}
        }
        catch(exception $e)
        {
            $this->db->rollBack();
            return false;
        }
        return true;
	}

	/**
	 * 会員店舗追加(店舗情報ない)
	 * @param array $param
	 * @return bool
	 */
	function insertBill ($param) {
        $this->db->beginTransaction();
        try{
        	$status = $param['status'];
        	unset($param['status']);
        	$shop_rs = $this->db->insertData('shop',array('shop_name'=>$param['shop_name'],'shop_url'=>$param['shop_url'],'address'=>$param['shop_address'],'pref_code'=>$param['pref_code'],'city_code'=>$param['city'],'business_day'=>$param['business_day'],'regular_holiday'=>$param['regular_holiday'],'latitude'=>$param['latitude'],'longitude'=>$param['longitude'],'delete_flg'=>0,'created_at'=>date('Y-m-d H:i:s',time())));//shop追加
        	if($shop_rs === true) {
        		$param['shop_id'] = $this->db->lastInsertId();//shop_id取る
        	}
        	else {
        		$this->db->rollBack();
        		return false;
        	}
        	if($status == 1) {//店舗と会員登録(status=1)
        		$sql = "
        		    SELECT
        				staff_id
        			FROM
        				shop_staff
        			WHERE
        				status = 1
        			AND
        				shop_id = :shop_id
        		   ";
        		$results = $this->db->selectPlaceQuery($sql, array('shop_id'=>$param['shop_id']));//staff情報存在確認
        		if ( $results == NULL  or count($results) == 0) {
        			$result = $this->db->insertData("shop_staff", array('staff_name'=>$param['staff_name'],'email'=>$param['staff_email'],'shop_id'=>$param['shop_id'],'delete_flg'=>0,'status'=>1));//staff追加
        			if($result === true) {
        				$param['shop_staff_id'] = $this->db->lastInsertId();//staff_id取る
        			}
        			else {
        				$this->db->rollBack();
        				return false;
        			}
        		}
        		else {
        			$this->db->rollBack();
        			return false;
        		}
        	}
        	else {
        		$param['staff_name'] = '';
        		$param['shop_staff_id'] = null;
        	}
        	$result = $this->db->insertData("bill",array('shop_id'=>$param['shop_id'],'shop_staff_id'=>$param['shop_staff_id'],'staff_name'=>$param['staff_name'],'shop_name'=>$param['shop_name'],'shop_address'=>$param['shop_address'],'tel'=>$param['tel'],'fax'=>$param['fax'],'email'=>$param['email'],'plan_status'=>$param['plan_status'],'delete_flg'=>0,'created_at'=>date('Y-m-d H:i:s',time())));//bill追加
        	if($result === true) {
        		$this->db->commit();
        	}
        	else {
        		$this->db->rollBack();
        		return false;
        	}
        }
        catch(exception $e)
        {
            $this->db->rollBack();
            return false;
        }
        return true;
	}

	/**
	 * ユーザーページング情報取得
	 * @param unknown $param
	 * @return mixed
	 */
	function getUserListPaging($param) {
		isset($param['orderby']) && !empty($param['orderby']) ? $orderby = 'order by '.$param['orderby'] : $orderby = '';
		if(isset($param['search']) && !empty($param['search'])) {
			$search = "and user_name like '%{$param['search']}%'";
		}
		else {
			$search = '';
			unset($param['search']);
		}
        $sql = "
                SELECT
                    count(user_id) count
                FROM
                    user
        		WHERE
        			delete_flg = 0
        		$search
                ";
        unset($param['search']);
        $results = $this->db->selectPlaceQuery($sql, $param);

        if ( $results == NULL  or count($results) == 0) {
            $ret = false;
        } else {
            return $results[0];
        }
        return $ret;
	}

	/**
	 * ユーザー情報取得
	 * @return array
	 */
	function getUserList ($param) {
		isset($param['orderby']) && !empty($param['orderby']) ? $orderby = 'order by '.$param['orderby'] : $orderby = '';
		if(isset($param['search']) && !empty($param['search'])) {
			$search = "and user_name like '%{$param['search']}%'";
		}
		else {
			$search = '';
			unset($param['search']);
		}
        $sql = "
                SELECT
                    user_id , user_name , created_at ,
        		    (select count(rank_id) from ranking where user.user_id = ranking.user_id) as rank_count ,
        			(select count(bt_id) from beento where user.user_id = beento.user_id) as ita_count ,
        			(select count(voting_id) from shop_voting where user.user_id = shop_voting.user_id and shop_voting.voting_kind=2) as ikitai_count ,
        			(select count(oen_id) from oen where user.user_id = oen.user_id) as oen_count
                FROM
                    user
        		WHERE
        			delete_flg = 0
        		$search
        		$orderby
        		LIMIT :offset,:max
                ";
        unset($param['orderby']);
        unset($param['search']);
        $results = $this->db->selectPlaceQuery($sql, $param);

        if ( $results == NULL  or count($results) == 0) {
            $ret = false;
        } else {
            return $results;
        }
        return $ret;
	}

	/**
	 * ユーザー削除
	 * @param array $param
	 * @return bool
	 */
	function deleteUser ($param) {
		return $this->db->updateData('user',array('delete_flg'=>1),array('user_id=?'=>$param['user_id']));
	}

	/**
	 * shop情報取得
	 * @param array $param
	 * @return array
	 */
	function getShopList ($param) {
		isset($param['orderby']) && !empty($param['orderby']) ? $orderby = 'order by '.$param['orderby'] : $orderby = '';
		if(isset($param['search']) && !empty($param['search'])) {
			$search = "and shop.shop_name like '%{$param['search']}%'";
		}
		else {
			$search = '';
			unset($param['search']);
		}
	        $sql = "
                SELECT
                    shop_id , shop_name ,created_at ,
                    (select count(rank_id) from ranking_detail where shop.shop_id = ranking_detail.shop_id) as rank_count ,
        			(select count(bt_id) from beento where shop.shop_id = beento.shop_id) as ita_count ,
        			(select count(voting_id) from shop_voting where shop.shop_id = shop_voting.shop_id and shop_voting.voting_kind=2) as ikitai_count ,
        			(select count(oen_id) from oen where shop.shop_id = oen.shop_id) as oen_count
                FROM
                    shop
                WHERE
                    delete_flg = 0
	        	$search
        		$orderby
				LIMIT :offset,:max
                ";
        unset($param['orderby']);
        unset($param['search']);
        $results = $this->db->selectPlaceQuery($sql, $param);

        if ( $results == NULL  or count($results) == 0) {
            return FALSE;
        } else {
            return $results;
        }
	}
	/**
	 * shopページング情報取得
	 * @param array $param
	 * @return array
	 */
	function getShopListPaging ($param) {
		if(isset($param['search']) && !empty($param['search'])) {
			$search = "and shop.shop_name like '%{$param['search']}%'";
		}
		else {
			$search = '';
		}
        $sql = "
                SELECT
                   	count(shop_id) as count
                FROM
                    shop
        		WHERE
        			delete_flg = 0
        		$search
                ";
        unset($param['search']);
        $results = $this->db->selectPlaceQuery($sql, $param);

        if ( $results == NULL  or count($results) == 0) {
            $ret = false;
        } else {
            return $results[0];
        }
        return $ret;
	}

	/**
	 * shop情報存在判断
	 * @param array $param
	 * @return array
	 */
	function getShopInfoExists ($param) {
        $sql = "
                SELECT
                    shop_id
                FROM
                    shop
                WHERE
                    shop_name = :shop_name
        		OR
        			address = :address
                ";
        $results = $this->db->selectPlaceQuery($sql, $param);

        if ( $results == NULL  or count($results) == 0) {
            $ret = 0;
        } else {
            return $results[0];
        }
        return $ret;
	}

	/**
	 * ランキング情報取得
	 * @param array $param
	 * @return array
	 */
	function getRankingList ($param) {
		isset($param['orderby']) && !empty($param['orderby']) ? $orderby = 'order by '.$param['orderby'] : $orderby = '';
		if(isset($param['search']) && !empty($param['search'])) {
			$search = "and ranking.title like '%{$param['search']}%'";
		}
		else {
			$search = '';
			unset($param['search']);
		}
	        $sql = "
                SELECT
                    ranking.* , (select count(reguru_uid) from reguru_info where ranking.rank_id = reguru_info.rank_id) as riguru_count
                FROM
                    ranking
                WHERE
                    delete_flg = 0
	        	$search
        		$orderby
				LIMIT :offset,:max
                ";
        unset($param['orderby']);
        unset($param['search']);
        $results = $this->db->selectPlaceQuery($sql, $param);

        if ( $results == NULL  or count($results) == 0) {
            return FALSE;
        } else {
            return $results;
        }
	}

	/**
	 * ランキングページング情報取得
	 * @param array $param
	 * @return array
	 */
	function getRankingPaging ($param) {
		if(isset($param['search']) && !empty($param['search'])) {
			$search = "and ranking.title like '%{$param['search']}%'";
		}
		else {
			$search = '';
		}
        $sql = "
                SELECT
                   	count(user_id) count
                FROM
                    ranking
        		WHERE
        			delete_flg = 0
        		$search
                ";
        unset($param['search']);
        $results = $this->db->selectPlaceQuery($sql, $param);

        if ( $results == NULL  or count($results) == 0) {
            $ret = false;
        } else {
            return $results[0];
        }
        return $ret;
	}

	/**
	 * ランキング削除
	 * @param array $param
	 * @return bool
	 */
	function deleteRanking ($param) {
		return $this->db->updateData('ranking',array('delete_flg'=>1),array('rank_id=?'=>$param['rank_id']));
	}

	/**
	 * ランキング詳細
	 * @param array $param
	 * @return array
	 */
	function getRankingDetailByRankId ($param) {
        $sql = "
                SELECT
                   	*
                FROM
                    ranking_detail
        		WHERE
        			delete_flg = 0
        		AND
        		    rank_id = :rank_id
                ";
        $results = $this->db->selectPlaceQuery($sql, $param);

        if ( $results == NULL  or count($results) == 0) {
            $ret = false;
        } else {
            return $results;
        }
        return $ret;
	}

	/**
	 * クーポン情報取得
	 * @param array $param
	 * @return array
	 */
	function getCouponList ($param) {
		isset($param['orderby']) && !empty($param['orderby']) ? $orderby = 'order by '.$param['orderby'] : $orderby = '';
		if(isset($param['search']) && !empty($param['search'])) {
			$search = "and coupon.title like '%{$param['search']}%'";
		}
		else {
			$search = '';
			unset($param['search']);
		}
        $sql = "
                SELECT
                   	coupon.* , shop.shop_name
                FROM
                    coupon left join shop on shop.shop_id = coupon.shop_id
        		WHERE
        			coupon.delete_flg = 0
        		$search
        		$orderby
				LIMIT :offset,:max
                ";
        unset($param['orderby']);
        unset($param['search']);
        $results = $this->db->selectPlaceQuery($sql, $param);

        if ( $results == NULL  or count($results) == 0) {
            $ret = false;
        } else {
            return $results;
        }
        return $ret;
	}

	/**
	 * クーポンページング情報取得
	 * @param array $param
	 * @return array
	 */
	function getCouponPaging ($param) {
		if(isset($param['search']) && !empty($param['search'])) {
			$search = "and coupon.title like '%{$param['search']}%'";
		}
		else {
			$search = '';
		}
        $sql = "
                SELECT
                   	count(coupon_id) count
                FROM
                    coupon
        		WHERE
        			delete_flg = 0
        		$search
                ";
        unset($param['search']);
        $results = $this->db->selectPlaceQuery($sql, $param);

        if ( $results == NULL  or count($results) == 0) {
            $ret = false;
        } else {
            return $results[0];
        }
        return $ret;
	}

	/**
	 * クーポン削除
	 * @param array $param
	 * @return bool
	 */
	function deleteCoupon ($param) {
		return $this->db->updateData('coupon',array('delete_flg'=>1),array('coupon_id=?'=>$param['coupon_id']));
	}

	/**
	 * Shop削除
	 * @param array $param
	 * @return array
	 */
	function deleteShop ($param) {
		return $this->db->updateData('shop',array('delete_flg'=>1),array('shop_id=?'=>$param['shop_id']));
	}

	/**
	 * 会員店舗情報取得
	 * @param array $param
	 * @return array
	 */
	function getBillList ($param) {
		isset($param['orderby']) && !empty($param['orderby']) ? $orderby = 'order by '.$param['orderby'] : $orderby = '';
		if(isset($param['search']) && !empty($param['search'])) {
			$search = "and shop.shop_name like '%{$param['search']}%'";
		}
		else {
			$search = '';
			unset($param['search']);
		}
        $sql = "
                SELECT
                   	bill.bill_id , bill.shop_id , bill.plan_status , bill.created_at , shop.shop_name ,
                   	(select count(rank_id) from ranking_detail where bill.shop_id = ranking_detail.shop_id) as rank_count ,
        			(select count(bt_id) from beento where bill.shop_id = beento.shop_id) as ita_count ,
        			(select count(voting_id) from shop_voting where bill.shop_id = shop_voting.shop_id and shop_voting.voting_kind=2) as ikitai_count ,
        			(select count(oen_id) from oen where bill.shop_id = oen.shop_id) as oen_count
                FROM
                    bill left join shop on shop.shop_id = bill.shop_id
        		WHERE
        			bill.delete_flg = 0
        		$search
        		$orderby
				LIMIT :offset,:max
                ";
        unset($param['orderby']);
        unset($param['search']);
        $results = $this->db->selectPlaceQuery($sql, $param);

        if ( $results == NULL  or count($results) == 0) {
            $ret = false;
        } else {
            return $results;
        }
        return $ret;
	}

	/**
	 * 会員店舗ページング情報取得
	 * @param array $param
	 * @return array
	 */
	function getBillPaging ($param) {
		if(isset($param['search']) && !empty($param['search'])) {
			$search = "and shop.shop_name like '%{$param['search']}%'";
		}
		else {
			$search = '';
		}
        $sql = "
                SELECT
                   	count(bill.bill_id) count
                FROM
                    bill left join shop on shop.shop_id = bill.shop_id
        		WHERE
        			bill.delete_flg = 0
        		$search
                ";
        unset($param['search']);
        $results = $this->db->selectPlaceQuery($sql, $param);

        if ( $results == NULL  or count($results) == 0) {
            $ret = false;
        } else {
            return $results[0];
        }
        return $ret;
	}

	/**
	 * 会員店舗削除
	 * @param array $param
	 * @return bool
	 */
	function deleteBill ($param) {
		return $this->db->updateData('bill',array('delete_flg'=>1),array('bill_id=?'=>$param['bill_id']));
	}

	/**
	 * お知らせ情報取得
	 * @param array $param
	 * @return array
	 */
	function getNewsList ($param) {
		unset($param['orderby']);
		unset($param['search']);
		$sql = "
				SELECT
				news_id , title , public_start , public_end , status
				FROM
				news
				WHERE
				delete_flg = 0
				LIMIT :offset,:max
			";
		$results = $this->db->selectPlaceQuery($sql, $param);
		if ( $results == NULL  or count($results) == 0) {
			$ret = false;
		}
		else {
			return $results;
		}
		return $ret;
	}

	/**
	 * お知らせページング情報取得
	 * @param array $param
	 * @return array
	 */
	function getNewsPaging ($param) {
		unset($param['orderby']);
		unset($param['search']);
		$sql = "
				SELECT
				count(news_id) count
				FROM
				news
				WHERE
				delete_flg = 0
			";
		$results = $this->db->selectPlaceQuery($sql, $param);
		if ( $results == NULL  or count($results) == 0) {
			$ret = false;
		}
		else {
			return $results[0];
		}
		return $ret;
	}

	/**
	 * お知らせ存在判断
	 * @param array $param
	 * @return array
	 */
	function getNewsInfoExists ($param) {
        $sql = "
                SELECT
                    news_id
                FROM
                    news
                WHERE
                    title = :title
                ";
        $results = $this->db->selectPlaceQuery($sql, $param);

        if ( $results == NULL  or count($results) == 0) {
            $ret = 0;
        } else {
            return $results[0];
        }
        return $ret;
	}

	/**
	 * お知らせ削除
	 * @param array $param
	 * @return array
	 */
	function deleteNews ($param) {
		return $this->db->updateData('news',array('delete_flg'=>1),array('news_id=?'=>$param['news_id']));
	}

	/**
	 * お知らせ挿入
	 * @param array $param
	 * @return bool
	 */
	function insertNews ($param) {
		return $this->db->insertData("news", $param);
	}

	/**
	 * 都道府県情報取得
	 * @param array $param
	 * @return array
	 */
	function getPrefList ($param) {
        $sql = "
                SELECT
                    *
                FROM
                    pref
                ";
        $results = $this->db->selectPlaceQuery($sql, $param);

        if ( $results == NULL  or count($results) == 0) {
            $ret = 0;
        } else {
            return $results;
        }
        return $ret;
	}

	/**
	 * 市区町村
	 * @param array $param
	 * @return array
	 */
	function getCity ($param) {
        $sql = "
                SELECT
                    city_code , value
                FROM
                    city
        		WHERE
        			pref_code = :pref_code
                ";
        $results = $this->db->selectPlaceQuery($sql, $param);

        if ( $results == NULL  or count($results) == 0) {
            $ret = 0;
        } else {
            return $results;
        }
        return $ret;
	}

	/**
	 * 全ての店の住所から店舗コードを割だし登録していくバッチ用メソッド
	 * @param
	 * @return bool
	 */
	function changeAddressTocode () {
        $sql = "
                SELECT
                    *
                FROM
                    shop
                ";
        return $ret;
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
	 * 親genre_idで最大の数取得
	 * @param array $param
	 * @return array
	 */
	function getGenreMax ($param) {
		$sql = "
                SELECT
					REPLACE(genre_id,'".$param['genre_parent']."','') as genre_id
                FROM
                    genre
        		WHERE
					genre_id like '".$param['genre_parent']."%'
				ORDER BY 
					CAST(REPLACE(genre_id,'_','') AS UNSIGNED) desc
				LIMIT 0,1
                ";
		$results = $this->db->selectPlaceQuery($sql, array());

		if ( $results == NULL  or count($results) == 0) {
			$ret = false;
		} else {
			return $results[0];
		}
		return $ret;	
	}
	
	/**
	 * ジャンル追加
	 * @param array $param
	 * @return bool
	 */
	function insertGenre ($param) {
		$result = $this->db->insertData("genre", $param);
		return $result;
	}
	
	/**
	 * ジャンルで情報存在確認
	 * @param array $param
	 * @return array
	 */
	function getGenreById ($param) {
		$sql = "
                SELECT
                    genre_id
                FROM
                    genre
        		WHERE
					genre_id = :genre_id
				AND
					value = :value
				AND
					(delete_flg = 0 or delete_flg is null)
                ";
		$results = $this->db->selectPlaceQuery($sql,$param);

		if ( $results == NULL  or count($results) == 0) {
			$ret = false;
		} else {
			return $results[0];
		}
		return $ret;	
	}
	
	function getGenreParentMax ($param) {
		$sql = "
                SELECT
                    genre_id
                FROM
                    genre
        		WHERE
					genre_id not like '%\_%'
				ORDER BY 
					CAST(genre_id AS UNSIGNED) desc
				Limit 0,1
                ";
		$results = $this->db->selectPlaceQuery($sql, array());

		if ( $results == NULL  or count($results) == 0) {
			$ret = false;
		} else {
			return $results[0];
		}
		return $ret;
	}

	/**
	 * ジャンル情報取得
	 * @param array $param
	 * @return array
	 */
	function getGenreList ($param) {
		unset($param['search']);
		$sql = "
                SELECT
                    genre_id , value
                FROM
                    genre
        		WHERE
				    delete_flg = 0 
				OR 
					delete_flg is null
				group by CAST(genre_id AS UNSIGNED), genre_id
				LIMIT :offset,:max
                ";
		$results = $this->db->selectPlaceQuery($sql,$param);

		if ( $results == NULL  or count($results) == 0) {
			$ret = 0;
		} else {
			return $results;
		}
		return $ret;	
	}
	
	/**
	 * ジャンルページング取得
	 * @param array $param
	 * @return int
	 */
	function getGenrePaging ($param) {
		$sql = "
                SELECT
                    count(genre_id) as count
                FROM
                    genre
        		WHERE
				    delete_flg = 0 
				OR 
					delete_flg is null
                ";
		$results = $this->db->selectPlaceQuery($sql, array());

		if ( $results == NULL  or count($results) == 0) {
			$ret = 0;
		} else {
			return $results[0];
		}
		return $ret;
	}
	
	/**
	 * ジャンル削除
	 * @param array $param
	 * @return bool
	 */
	function deleteGenre ($param) {
		return $this->db->updateData('genre',array('delete_flg'=>1),array('genre_id=?'=>$param['genre_id']));
	}
	
	/**
	 * ジャンル編集
	 * @param array $param
	 * @return bool
	 */
	function updateGenre ($param) {
		return $this->db->updateData('genre',array('value'=>$param['value'],'updated_at'=>$param['updated_at']),array('genre_id=?'=>$param['genre_id']));
	}
	
	/**
	 * ランキング親取得
	 * @param array $param
	 * @return array
	 */
	function getRankingCategoryLarge ($param) {
		$sql = "
                SELECT
                    large_id , large_value
                FROM
                    ranking_category_large
        		WHERE
				    delete_flg = 0 
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
	 *
	 * @param array $param
	 * @return bool
	 */
	function getRankingCategoryLargeExists ($param) {
		$sql = "
                SELECT
                    large_id
                FROM
                    ranking_category_large
        		WHERE
					large_value = :large_value
				AND
					delete_flg = 0
                ";
		$results = $this->db->selectPlaceQuery($sql,$param);

		if ( $results == NULL  or count($results) == 0) {
			$ret = false;
		} else {
			return $results[0];
		}
		return $ret;	
	}
	
	/**
	 * ランキング子存在確認
	 * @param array $param
	 * @return bool
	 */
	function getRankingCategorySmallExists ($param) {
		$sql = "
                SELECT
                    large_id
                FROM
                    ranking_category_small
        		WHERE
					large_id = :large_id
				AND
					small_value = :small_value
				AND
					delete_flg = 0
                ";
		$results = $this->db->selectPlaceQuery($sql,$param);

		if ( $results == NULL  or count($results) == 0) {
			$ret = false;
		} else {
			return $results[0];
		}
		return $ret;
	}
	
	/**
	 * ランキング親追加
	 * @param array $param
	 * @return bool
	 */
	function insertRankingCategoryLarge ($param) {
		$result = $this->db->insertData("ranking_category_large", $param);
		return $result;
	}
	
	/**
	 * small_idで最大の数取得
	 * @param array $param
	 * @return array
	 */
	function CategorySmallMax ($param) {
		$sql = "
                SELECT
                    small_id
                FROM
                    ranking_category_small
        		WHERE
					large_id = :large_id
				order by small_id desc
				limit 0,1	
                ";
		$results = $this->db->selectPlaceQuery($sql,$param);

		if ( $results == NULL  or count($results) == 0) {
			$ret = false;
		} else {
			return $results[0];
		}
		return $ret;
	}
	
	/**
	 * ランキング子追加
	 * @param array $param
	 * @return bool
	 */
	function insertRankingCategorySmall ($param) {
		$result = $this->db->insertData("ranking_category_small", $param);
		return $result;
	}
	
	/**
	 * ランキングこだわり取得
	 * @param array $param
	 * @return array
	 */
	function getRankingCategoryList ($param) {
		unset($param['search']);
		$sql = "
				SELECT large_id,small_id,large_value,small_value 
				FROM 
				(SELECT l.large_id , s.small_id , l.large_value , s.small_value 
				from ranking_category_large as l 
				left join  ranking_category_small as s 
				on l.large_id=s.large_id  
				WHERE 
					l.delete_flg =0
				AND
					s.delete_flg =0
				UNION 
				SELECT large_id , null as small_id , large_value , null as small_value 
				FROM ranking_category_large
				WHERE 
					delete_flg =0
				) a 
				group by large_id,small_id
				LIMIT :offset,:max
                ";
		$results = $this->db->selectPlaceQuery($sql,$param);

		if ( $results == NULL  or count($results) == 0) {
			$ret = false;
		} else {
			return $results;
		}
		return $ret;
	}
	
	/**
	 * ランキングこだわりページング取得
	 * @param array $param
	 * @return int
	 */
	function getRankingCategoryPaging ($param) {
		$sql = "
				SELECT count(large_id) as count
				FROM 
				(SELECT l.large_id , s.small_id
				from ranking_category_large as l 
				left join  ranking_category_small as s 
				on l.large_id=s.large_id  
				WHERE 
					l.delete_flg =0
				AND
					s.delete_flg =0
				UNION 
				SELECT large_id , null as small_id
				FROM ranking_category_large
				WHERE 
					delete_flg =0
				) a 
                ";
		$results = $this->db->selectPlaceQuery($sql,array());
		if ( $results == NULL  or count($results) == 0) {
			$ret = false;
		} else {
			return $results[0];
		}
		return $ret;
	}
	
	/**
	 * ランキングこだわり親削除
	 * @param array $param
	 * @return bool
	 */
	function deleteRankingCategoryLarge ($param) {
		return $this->db->updateData('ranking_category_large',array('delete_flg'=>1),array('large_id=?'=>$param['large_id']));
	}
	
	/**
	 * ランキングこだわり子削除
	 * @param array $param
	 * @return bool
	 */
	function deleteRankingCategorySmall ($param) {
		return $this->db->updateData('ranking_category_small',array('delete_flg'=>1),array('large_id=?'=>$param['large_id'],'small_id=?'=>$param['small_id']));
	}
	
	/**
	 * ランキングこだわり親編集
	 * @param array $param
	 * @return bool
	 */
	function updateRankingCategoryLarge ($param) {
		return $this->db->updateData('ranking_category_large',array('large_value'=>$param['large_value'],'updated_at'=>$param['updated_at']),array('large_id=?'=>$param['large_id']));
	}
	
	/**
	 * ランキングこだわり子編集
	 * @param array $param
	 * @return bool
	 */
	function updateRankingCategorySmall ($param) {
		return $this->db->updateData('ranking_category_small',array('small_value'=>$param['small_value'],'updated_at'=>$param['updated_at']),array('large_id=?'=>$param['large_id'],'small_id=?'=>$param['small_id']));
	}
	
	/**
	 * クーポンcountenue cronjob
	 * @param unknown $param
	 * @return array
	 */
	function updateCouponContenue ($param) {
		$sql = "
                SELECT
                    coupon_id , public_end
                FROM
                    coupon
        		WHERE
					contenue = 1
				AND
				    delete_flg = 0
				AND
					unix_timestamp(public_end) <= ".time()."
                ";
		$results = $this->db->selectPlaceQuery($sql,array());

		if ( $results == NULL  or count($results) == 0) {
			$ret = false;
		} else {
			return $results;
		}
		return $ret;
	}
	
	/**
	 * クーポン更新
	 * @param array $param
	 * @return bool
	 */
	function updateCouponByCouponId ($param) {
		return $this->db->updateData('coupon',array('public_end'=>date('Y-m-d H:i:s',strtotime('+1 month',strtotime($param['public_end'])))),array('coupon_id=?'=>$param['coupon_id']));
	}
}