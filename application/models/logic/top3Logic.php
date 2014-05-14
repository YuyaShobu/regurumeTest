<?php

require_once (MODEL_DIR ."/logic/abstractLogic.php");

/**
 * トップ３ロジック
 *
 * @package   top3
 * @author    xiuhui yang 2013/07/01 新規作成
 */
class top3Logic extends abstractLogic {


    /**
     * カテゴリマスタ一覧取得
     * @author: xiuhui yang
     * @param string $category_id
     *
     * @return array $results
     *
     *
    */
    public function getCategoryTop3($category_id)
    {
        //データ抽出SQLクエリ
        $sql = "
                SELECT
                    *
                FROM
                    category
                WHERE
                    delete_flg = 0
                ";
        if ( $category_id != "") {
            $sql .= " AND category_id =?";
            $results = $this->db->selectPlaceQuery($sql,array($category_id));
        } else {
            $results = $this->db->selectPlaceQuery($sql,array());
        }
        //一件も取れていなければFALSEを返す
        if ( $results == NULL  or count($results) == 0) {
            return FALSE;
        } else {
            return $results;
        }
    }


    /**
     * TOP3一覧情報取得
     * @author: xiuhui yang
     * @param array $param
     *              keyname is uid
     *                         category_id
     *
     * @return array $results
     *
     *
    */
    public function getCategoryTop3List($param)
    {
        //データ抽出SQLクエリ
        $sql = "
                SELECT
                    a.*,b.shop_name
                FROM
                    top3 a
                LEFT OUTER JOIN
                    shop b
                ON
                    a.shop_id = b.shop_id
                WHERE
                    a.delete_flg = 0
                AND
                    a.uid = ?
                AND
                    a.category_id=?
                ";
        $results = $this->db->selectPlaceQuery($sql,array($param['uid'],$param['category_id']));
        //一件も取れていなければFALSEを返す
        if ( $results == NULL  or count($results) == 0) {
            return FALSE;
        } else {
            return $results;
        }
    }


    /**
     * TOP3詳細情報取得
     * @author: xiuhui yang
     * @param array $param
     *              keyname is uid
     *                         category_id
     *                         top3_no
     *
     * @return array $results
     *
     *
    */
    public function getTop3Detail($param)
    {
        //データ抽出SQLクエリ
        $sql = "
                SELECT
                    a.*,b.shop_name,b.latitude,b.longitude
                FROM
                    top3 a
                LEFT OUTER JOIN
                    shop b
                ON
                    a.shop_id = b.shop_id
                WHERE
                    a.uid = ?
                AND
                    a.category_id = ?
                AND
                    a.top3_no =?
                AND
                    a.delete_flg = 0
                ";
        $results = $this->db->selectPlaceQuery($sql,array($param['uid'],$param['category_id'],$param['top3_no']));
        //一件も取れていなければFALSEを返す
        if ( $results == NULL  or count($results) == 0) {
            return FALSE;
        } else {
            return $results[0];
        }
    }


    /**
     * トップ３データ登録
     * @author: xiuhui yang
     * @param array $param
     *              keyname is UID
     *                         CATEGORY_ID
     *                         TOP3_NO
     *                         SHOP_ID
     *                         EXPLAIN
     *                         PHOTO
     *                         DELETE_FLG
     *                         CREATED_AT
     *                         UPDATED_AT
     *
     * @return bool true/false
     *
    */
    public function insertTop3($param)
    {
        //トランザクション開始
        $this->db->beginTransaction();
        try{
            $result = $this->db->insertData( "top3", $param );
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
     * トップ３データ更新
     * @author: xiuhui yang
     * @param array $param
     *              keyname is UID
     *                         CATEGORY_ID
     *                         TOP3_NO
     *                         SHOP_ID
     *                         EXPLAIN
     *                         PHOTO
     *                         DELETE_FLG
     *                         UPDATED_AT
     *
     * @return bool true/false
     *
    */
    public function updateTop3($param)
    {
        //更新条件
        $where = array( $this->db->quoteInto("UID = ?",$param['UID']),
                        $this->db->quoteInto("CATEGORY_ID = ?",$param["CATEGORY_ID"]),
                        $this->db->quoteInto("TOP3_NO = ?",$param["TOP3_NO"])
                        );
        //トランザクション開始
        $this->db->beginTransaction();
        try{
            $result = $this->db->updateData( "top3", $param, $where );
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
     * トップ３データ存在チェック
     * @author: xiuhui yang
     * @param array $param
     *              keyname is uid
     *                         category_id
     *                         top3_no
     *
     * @return  array $res
     *                keyname is CNT
     *
    */
    public function checkTop3Exist($param)
    {
        //データ抽出SQLクエリ
        $results[0] = 0;
        $sql = "
                SELECT
                     COUNT(1) AS CNT
                FROM
                     top3
                WHERE
                    uid = ?
                AND
                    category_id = ?
                AND
                    top3_no = ?
                ";
        $results = $this->db->selectPlaceQuery($sql,array($param['uid'],$param['category_id'],$param['top3_no']));
        return $results[0];
    }

}
?>
