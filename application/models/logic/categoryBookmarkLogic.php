<?php

require_once (MODEL_DIR ."/logic/abstractLogic.php");

/**
 * カテゴリブックマックロジック
 *
 * @package   categoryBookmark
 * @author    xiuhui yang 2013/07/01 新規作成
 */
class categoryBookmarkLogic extends abstractLogic {


    /**
     * カテゴリブックマックデータ存在チェック
     * @author: xiuhui yang
     * @param array $param
     *              keyname is user_id
     *                         bk_type
     *                         create_user_id
     *                         category_id
     *
     * @return  array $res
     *                keyname is CNT
     *
    */
    public function checkCategoryBookmarkExist($param)
    {
        $ret = 0;
        //データ抽出SQLクエリ
        $sql = "
                SELECT
                     COUNT(1) AS CNT
                FROM
                     category_bookmark
                WHERE
                    user_id = ?
                AND
                    bk_type = ?
                AND
                    create_user_id = ?
                AND
                    category_id = ?
                AND
                    delete_flg = 0
                ";

        $where = array ($param['USER_ID'],$param['BK_TYPE'],$param['CREATE_USER_ID'],$param['CATEGORY_ID']);
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
     * カテゴリブックマックデータ登録
     * @author: xiuhui yang
     * @param array $param
     *              keyname is USER_ID
     *                         BK_TYPE
     *                         CATEGORY_ID
     *                         CREATE_USER_ID
     *                         CREATED_AT
     *                         UPDATED_AT
     *
     * @return bool true/false
     *
    */
    public function insertCategoryBookmark($param)
    {
        //トランザクション開始
        $this->db->beginTransaction();
        try{
            $result = $this->db->insertData( "category_bookmark", $param );
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
