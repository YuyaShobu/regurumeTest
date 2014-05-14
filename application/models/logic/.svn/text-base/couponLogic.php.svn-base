<?php

require_once (MODEL_DIR ."/logic/abstractLogic.php");

/**
 * 行ったロジック
 *
 * @package   beento
 * @author    yuya yamamoto 2013/07/01 新規作成
 */
class couponLogic extends abstractLogic {


    /**
     * 行った事あるお店一覧データ取得
     * @author: yuya yamamoto
     * @param array $param
     *              keyname is user_id , inviting , coupon_id
     *
     * @return array $results
     *
    */
    public function registInvitingCoupon($param)
    {

        //トランザクション開始
        $this->db->beginTransaction();

        $sql = "
                SELECT
                    rcc_id
                FROM
                    regular_customer_coupon
                WHERE
                    user_id = :user_id
        		AND
        			inviting = :inviting
        		AND
        			coupon_id = :coupon_id
                ";
        $results = $this->db->selectPlaceQuery($sql, $param);

        if ( $results == NULL  or count($results) == 0) {
	        try{
	            $result = $this->db->insertData( "regular_customer_coupon", $param );
	            $this->db->commit();
	        }
	        catch(exception $e)
	        {
	            $this->db->rollBack();
	            return false;
	        }
	        return true;
        } else {
            return false;
        }


    }
}
?>
