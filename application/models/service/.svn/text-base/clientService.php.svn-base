<?php

require_once (MODEL_DIR ."/service/abstractService.php");

/**
 * ショップサービス
 *
 * @package   client
 * @author    playab 2013/07/29 新規作成
 */
class clientService extends abstractService {
	
    /**
     * 自分のお店に行ったことのある人の人数と応援している人の人数
     * @param string $param
     * @return array $res
     */
    function getOenCount($param)
    {
        $res = $this->logic(get_class($this), __FUNCTION__, $param);
        return $res;
    }
    
    /**
     * 自分のお店に行ったことある人の数はbeentoテーブルで
     * @param string $param
     * @return array $res
     */
    function getBeentoCount($param)
    {
        $res = $this->logic(get_class($this), __FUNCTION__, $param);
        return $res;
    }

    /**
     * 自分のお店が入っているランキングは
     * @param string $param
     * @return array $res
     */
    function getTop3List($param)
    {
        $res = $this->logic(get_class($this), __FUNCTION__, $param);
        return $res;
    }
    
    /**
     * shop_idによってshop情報取得
     * @param int $shop_id
     * @return array $res
     */
    function getShopById($shop_id)
    {
    	$res = $this->logic(get_class($this), __FUNCTION__, $shop_id);
    	return $res;
    }
    
    /**
     * shop_idによってshop情報アップデート
     * @param array $param
     * @return array $res
     */
    function updateShopById($param)
    {
    	$res = $this->logic(get_class($this), __FUNCTION__, $param);
    	return $res;    	
    }
    
    /**
     * shop_idによってクーポン情報取得
     * @param array $param
     * @return array $res
     */
    function getCouponByShopId($param)
    {
    	$res = $this->logic(get_class($this),__FUNCTION__, $param);
    	return $res;
    }
    
    /**
     * coupon_idによってクーポン情報取得
     * @param int $coupon_id
     * @return array $res
     */
    function getCouponByCouponId($coupon_id) 
    {
    	$res = $this->logic(get_class($this),__FUNCTION__, $coupon_id);
    	return $res;
    }
    
    /**
     * shopIdによって店舗ページクーポン情報取得
     * @param int $shopId
     * @return array $res
     */
    function getCouponByShopIdFromIndex($shopId)
    {
    	$res = $this->logic(get_class($this),__FUNCTION__, $shopId);
    	return $res;
    }
    
    /**
     * クーポン存在判断
     * @param array $param
     * @return array
     */
    function getCouponExists($param)
    {
    	$res = $this->logic(get_class($this),__FUNCTION__, $param);
    	return $res;
    }
    
    /**
     * クーポン挿入
     * @param array $param
     * @return bool $res
     */
    function insertCoupon($param)
    {
    	$res = $this->logic(get_class($this),__FUNCTION__, $param);
    	return $res;
    }
    
    /**
     * クーポンアップデート
     * @param array $param
     * @return bool $res
     */
    function updateCoupon($param)
    {
    	$res = $this->logic(get_class($this),__FUNCTION__, $param);
    	return $res;
    }
    
    /**
     * shop_idによってスタッフ取得
     * @param int $shop_id
     * @return array $res
     */
	function getStaffByShopId($shop_id)
	{
		$res = $this->logic(get_class($this),__FUNCTION__, $shop_id);
		return $res;		
	}
	
	/**
	 * staff_idによってスタッフ取得
	 * @param int $staff_id
	 * @return array
	 */
	function getStaffByStaffId($staff_id)
	{
		$res = $this->logic(get_class($this),__FUNCTION__, $staff_id);
		return $res;		
	}
	
	/**
	 * staff存在判断
	 * @param array $param
	 * @return array
	 */
	function getStaffExisit($param)
	{
		$res = $this->logic(get_class($this),__FUNCTION__, $param);
		return $res;		
	}
    
	/**
	 * スタッフ挿入
	 * @param array $param
	 * @return bool $res
	 */
	function insertStaff($param)
	{
		$res = $this->logic(get_class($this),__FUNCTION__, $param);
		return $res;		
	}
	
	/**
	 * スタッフアップデート
	 * @param array $param
	 * @return bool $res
	 */
	function updateStaff($param)
	{
		$res = $this->logic(get_class($this),__FUNCTION__, $param);
		return $res;
	}
	
	/**
	 * スタッフログイン
	 * @param array $param
	 * @return array $res
	 */
	function login($param)
	{
		$res = $this->logic(get_class($this),__FUNCTION__, $param);
		return $res;		
	}
	
	/**
	 * StaffIdによってshop_id取得
	 * @param array $param
	 */
	function searchShopInfoFromStaffId($param)
	{
		$res = $this->logic(get_class($this),__FUNCTION__, $param);
		return $res;		
	}

	/**
	 * shop_idによってShopInfo取得
	 * @param array $param
	 */
	function getShopInfoByShopId($shop_id)
	{
		$res = $this->logic(get_class($this),__FUNCTION__, $shop_id);
		return $res;		
	}
	
	/**
	 * 削除coupon
	 * @param array $param
	 * @return bool
	 */
	function deleteCouponByCouponId($param)
	{
		$res = $this->logic(get_class($this),__FUNCTION__, $param);
		return $res;		
	}
	
	/**
	 * shop_idによって行ったのユーザー取得
	 * @param int $shop_id
	 * @return array
	 */
	function getBeentoListByShopId($shop_id)
	{
		$res = $this->logic(get_class($this),__FUNCTION__, $shop_id);
		return $res;		
	}
	
	/**
	 * shop_idによって応援のユーザー取得
	 * @param int $shop_id
	 * @return array
	 */
	function getOenListByShopId($shop_id)
	{
		$res = $this->logic(get_class($this),__FUNCTION__, $shop_id);
		return $res;		
	}
	
	/**
	 * shop_idによって行きたいのユーザー取得
	 * @param int $shop_id
	 * @return array
	 */
	function getIkitaiListByShopId($shop_id)
	{
		$res = $this->logic(get_class($this),__FUNCTION__, $shop_id);
		return $res;		
	}
	
	/**
	 * shop情報存在判断
	 * @param array $param
	 * @return array
	 */
	function getShopExists($param) {
		$res = $this->logic(get_class($this),__FUNCTION__, $param);
		return $res;		
	}
	
	/**
	 * shop_idで応援しているのユーザー取得
	 * @param array $param
	 * @return array
	 */
	function getOenUserByShopId ($param) {
		$res = $this->logic(get_class($this),__FUNCTION__, $param);
		return $res;		
	}
	
	/**
	 * shop_idで常連ユーザー取得
	 * @param array $param
	 * @return array
	 */
	function getJouRenByShopId ($param) {
		$res = $this->logic(get_class($this),__FUNCTION__, $param);
		return $res;		
	}
	
	function getCouponCopyExists ($param) {
		$res = $this->logic(get_class($this),__FUNCTION__, $param);
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
	
	function getGenreNameByGenreId ($param) {
		$res = $this->logic(get_class($this),__FUNCTION__, $param);
		return $res;		
	}
}
?>