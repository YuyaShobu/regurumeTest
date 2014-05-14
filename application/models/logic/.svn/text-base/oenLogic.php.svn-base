<?php

require_once (MODEL_DIR ."/logic/abstractLogic.php");

/**
 * 応援ロジック
 *
 * @package   oen
 * @author    xiuhui yang 2013/07/01 新規作成
 */
class oenLogic extends abstractLogic {


    /**
     * 応援一覧データ取得
     * @author: xiuhui yang
     * @param array $param
     *              keyname is user_id
     *
     * @return array $results
     *
    */
    public function getOenList($param)
    {
        //データ抽出SQLクエリ
        $sql = "
                SELECT
                    a.*,b.shop_name
                FROM
                    oen a
                INNER JOIN
                    shop b
                ON
                    a.shop_id = b.shop_id
                WHERE
                    a.delete_flg = 0
                AND
                    a.user_id = ?
                ";
        $results = $this->db->selectPlaceQuery($sql,array( $param ));
        //一件も取れていなければFALSEを返す
        if ( $results == NULL  or count($results) == 0) {
            return FALSE;
        } else {
            return $results;
        }
    }


    /**
     * 応援一覧データ取得
     * @author: xiuhui yang
     * @param array $param
     *              keyname is oen_id
     *
     * @return array $results
     *
    */
    public function getOenDetail($param)
    {
        //データ抽出SQLクエリ
        $sql = "
                SELECT
                    a.*,b.shop_name,b.latitude,b.longitude
                FROM
                    oen a
                INNER JOIN
                    shop b
                ON
                    a.shop_id = b.shop_id
                WHERE
                    a.delete_flg = 0
                AND
                    a.oen_id = ?
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
     * 応援データ登録
     * @author: xiuhui yang
     * @param array $param
     *              keyname is USER_ID
     *                         SHOP_ID
     *                         EXPLAIN
     *                         PHOTO
     *                         CREATED_AT
     *                         UPDATED_AT
     *
     * @return bool true/false
     *
    */
    public function insertOen($param)
    {
        //トランザクション開始
        $this->db->beginTransaction();
        try{
            $result = $this->db->insertData( "oen", $param );
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
     * 応援データ更新
     * @author: xiuhui yang
     * @param array $param
     *              keyname is USER_ID
     *                         SHOP_ID
     *                         EXPLAIN
     *                         PHOTO
     *                         OEN_ID
     *                         UPDATED_AT
     *
     * @return bool true/false
     *
    */
    public function updateOen($param)
    {
        //更新条件
        $where = array( $this->db->quoteInto("OEN_ID = ?",$param['OEN_ID']));
        //トランザクション開始
        $this->db->beginTransaction();
        try{
            $result = $this->db->updateData( "oen", $param, $where );
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
     * 応援データ存在チェック
     * @author: xiuhui yang
     * @param array $param
     *              keyname is user_id
     *                         shop_id
     *
     * @return  array $res
     *                keyname is CNT
     *
    */
    public function checkOenExist($param)
    {
        //データ抽出SQLクエリ
        $ret = 0;
        $sql = "
                SELECT
                     COUNT(1) AS CNT
                FROM
                     oen
                WHERE
                    user_id = ?
                AND
                    shop_id = ?
                AND
                    delete_flg = 0
                ";
        $results = $this->db->selectPlaceQuery($sql,array($param['user_id'],$param['shop_id']));
        //一件も取れていなければFALSEを返す
        if ( $results == NULL  or count($results) == 0) {
            $ret = 0;
        } else {
            return $results[0]['CNT'];
        }
        return $ret;
    }


    /**
     * 応援データ削除
     * @author: xiuhui yang
     * @param array $param
     *              keyname is OEN_ID
     *                         DELETE_FLG
     *                         UPDATED_AT
     *
     * @return bool true/false
     *
    */
    public function deleteOen($param)
    {
        $where = array($this->db->quoteInto("OEN_ID = ?", $param['OEN_ID']));
        //トランザクション開始
        $this->db->beginTransaction();
        try{
            $result = $this->db->updateData( "oen", $param, $where );
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
     * 応援高い順一覧
     *
     * @param none
     * @return array $results
     */
    public function getShopOenList()
    {
        //データ抽出SQLクエリ
        $sql = "
                SELECT
                    a.shop_id,count(*) as cnt,b.shop_name
                FROM
                    (select * from oen where delete_flg = 0)  a
                LEFT OUTER JOIN
                    shop b
                ON
                    a.shop_id = b.shop_id
                GROUP BY a.shop_id
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
     * 応援ユーザー一覧
     * @param array $param
     *              keyname is shop_id
     * @return array
     */
    public function getOenUserList($param)
    {
        //データ抽出SQLクエリ
        $sql = "
                SELECT
                    a.user_id,b.mail_address
                FROM
                    oen a
                LEFT OUTER JOIN
                    user b
                ON
                    a.user_id = b.user_id
                WHERE
                    a.shop_id  = :shop_id
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
     * カテゴリーIDからそのカテゴリーIDのランキングリスト(ショップリスト)を取得
     * @param none
     *
     * @return array $results
    */
    public function getOenRanking($param)
    {
	    //データ抽出SQLクエリ
        $sql = "
                SELECT
                    o.shop_id , o.photo,o.user_id, s.shop_name , u.user_name
                FROM
                   ( oen o
	                INNER JOIN
	                	shop s
	                ON
	                    o.shop_id = s.shop_id
                   )
                INNER JOIN
                    user u
                ON
                    u.user_id = o.user_id
                WHERE
                     o.delete_flg = 0
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
}
?>
