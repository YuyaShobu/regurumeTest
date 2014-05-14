<?php

require_once (MODEL_DIR ."/logic/abstractLogic.php");

/**
 * ショップブックマックロジック
 *
 * @package   shopBookmarkLogic
 * @author    xiuhui yang 2013/07/01 新規作成
 */
class shopBookmarkLogic extends abstractLogic {


    /**
     * ショップブックマックデータ存在チェック
     * @author: xiuhui yang
     * @param array $param
     *              keyname is user_id
     *                         shop_id
     *
     * @return  int $ret
     *
     *
    */
    public function checkShopBookmarkExist($param)
    {
        $ret = 0;
        //データ抽出SQLクエリ
        $sql = "
                SELECT
                     COUNT(1) AS CNT
                FROM
                     shop_bookmark
                WHERE
                    user_id = ?
                AND
                    shop_id = ?
                ";

        $where = array ($param['user_id'],$param['shop_id']);
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
     * ショップブックマックデータ登録
     * @author: xiuhui yang
     * @param array $param
     *              keyname is user_id
     *                         shop_id
     *                         created_at
     *                         updated_at
     *
     * @return bool true/false
     *
    */
    public function insertShopBookmark($param)
    {
        //トランザクション開始
        $this->db->beginTransaction();
        try{
            $result = $this->db->insertData( "shop_bookmark", $param );
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
     * ショップブックマックデータ更新
     * @author: xiuhui yang
     * @param array $param
     *              keyname is user_id
     *                         shop_id
     *                         updated_at
     *
     * @return bool true/false
     *
    */
    public function updateShopBookmark($param)
    {
        //更新条件
        $where = array( $this->db->quoteInto("user_id = ?",$param['user_id']),
                        $this->db->quoteInto("shop_id = ?",$param["shop_id"])
                        );
        //トランザクション開始
        $this->db->beginTransaction();
        try{
            $result = $this->db->updateData( "shop_bookmark", $param, $where );
            $this->db->commit();
        }
        catch(exception $e)
        {
            $this->db->rollBack();
            return false;
        }
        return true;
    }


}
?>
