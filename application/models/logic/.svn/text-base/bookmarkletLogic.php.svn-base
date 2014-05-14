<?php
require_once (MODEL_DIR ."/logic/abstractLogic.php");

class bookmarkletLogic extends abstractLogic {
	
	/**
	 * 店名存在確認
	 * @param array $param
	 * @return array
	 */
	public function getShopExists ($param) {
		$pre = htmlspecialchars(trim($param['region']));
		$city = htmlspecialchars(trim($param['locality']));
		$sql = "
                SELECT
                    shop.shop_id , shop.shop_name , shop.address
                FROM
                    shop , city , pref
                WHERE
                    shop.pref_code = pref.pref_code
				AND
					shop.city_code = city.city_code
				AND
					shop.pref_code = city.pref_code
        		AND
        			pref.value like '".$pre."%'
        		AND
        			city.value like '".$city."%'
        		AND
        			shop.delete_flg = 0
                ";
		$results = $this->db->selectPlaceQuery($sql, array());
		
		if ( $results == NULL  or count($results) == 0) {
			$ret = false;
		} else {
			return $results;
		}
		return $ret;
	}
	
	/**
	 * 追加時店名存在確認
	 * @param array $param
	 * @return array
	 */
	public function insertShopExists ($param) {
		$sql = "
                SELECT
                    shop_id
                FROM
                    shop
                WHERE
					shop_name = :shop_name
				AND
					address = :address
				AND
					pref_code = :pref_code
				AND
					city_code = :city_code
        		AND
        			delete_flg = 0
                ";
		$results = $this->db->selectPlaceQuery($sql, $param);
		
		if ( $results == NULL  or count($results) == 0) {
			$ret = false;
		} else {
			return $results[0];
		}
		return $ret;
	}
	
	/**
	 * 店追加
	 * @param array $param
	 * @return int
	 */
	public function insertShop ($param) {
    	$result = $this->db->insertData("shop", $param);
    	if($result > 0) {
    		return $this->db->lastInsertId();
    	}
    	else {
    		return false;
    	}
	}
	
	/**
	 * pref_code取得
	 * @param array $param
	 * @return array
	 */
	public function getPrefCode ($param) {
		$sql = "
                SELECT
                    pref_code
                FROM
                    pref
                WHERE
					value = :value
                ";
		$results = $this->db->selectPlaceQuery($sql, $param);
		
		if ( $results == NULL  or count($results) == 0) {
			$ret = false;
		} else {
			return $results[0];
		}
		return $ret;
	}
	
	/**
	 * city_code取得
	 * @param array $param
	 * @return array
	 */
	public function getCityCode ($param) {
		$sql = "
                SELECT
                    city_code
                FROM
                    city
                WHERE
					pref_code = :pref_code
				AND
					value = :value
                ";
		$results = $this->db->selectPlaceQuery($sql, $param);
		
		if ( $results == NULL  or count($results) == 0) {
			$ret = false;
		} else {
			return $results[0];
		}
		return $ret;
	}
}