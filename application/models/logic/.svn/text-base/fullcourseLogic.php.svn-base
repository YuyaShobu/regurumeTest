<?php

require_once (MODEL_DIR ."/logic/abstractLogic.php");

/**
 * フルコースロジック
 *
 * @package   fullcourse
 * @author    xiuhui yang 2013/07/01 新規作成
 */
class fullcourseLogic extends abstractLogic {

    /**
     * フルコースマスタ一覧データ取得
     * @author: xiuhui yang
     * @param
     *
     * @return array $res
     *               keyname is course_id
     *                          course_name
    */
    public function getFullcourseMaster()
    {
        //データ抽出SQLクエリ
        $sql = "
                SELECT
                    course_id, course_name
                FROM
                    fullcourse_master
                WHERE
                    delete_flg = 0
                ORDER BY
                    course_id
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
     * フルコース一覧データ取得
     * @author: xiuhui yang
     * @param array $param
     *              keyname is user_id
     *
     * @return array $results
     *
    */
    public function getFullcourseList($param)
    {
        //データ抽出SQLクエリ
        $sql = "
                SELECT
                    a.*,b.shop_name as f_shop_name
                FROM
                    fullcourse a
                INNER JOIN
                    shop b
                ON
                    a.shop_id = b.shop_id
                WHERE
                    a.delete_flg = 0
                AND
                    a.user_id =?
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
     * フルコース詳細データ取得
     * @author: xiuhui yang
     * @param array $param
     *              keyname is user_id
     *                         course_id
     *
     * @return array $results
     *
    */
    public function getFullcourseDetail($param)
    {
        //データ抽出SQLクエリ
        $sql = "
                SELECT
                    a.*,b.shop_name,c.course_name
                FROM
                    fullcourse a
                LEFT OUTER JOIN
                    shop b
                ON
                    a.shop_id = b.shop_id
                LEFT OUTER JOIN
                    fullcourse_master c
                ON
                    a.course_id = c.course_id
                WHERE
                    a.user_id = ?
                AND
                    a.course_id = ?
                AND
                    a.delete_flg = 0
                ";
        $results = $this->db->selectPlaceQuery($sql,array($param['user_id'],$param['course_id']));
        //一件も取れていなければFALSEを返す
        if ( $results == NULL  or count($results) == 0) {
            return FALSE;
        } else {
            return $results[0];
        }
    }


    /**
     * フルコースデータ登録
     * @author: xiuhui yang
     * @param array $param
     *              keyname is USER_ID
     *                         COURSE_ID
     *                         MENU
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
    public function insertFullcourse($param)
    {
        //トランザクション開始
        $this->db->beginTransaction();
        try{
            $result = $this->db->insertData( "fullcourse", $param );
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
     * フルコースデータ更新
     * @author: xiuhui yang
     * @param array $param
     *              keyname is USER_ID
     *                         COURSE_ID
     *                         MENU
     *                         SHOP_ID
     *                         EXPLAIN
     *                         PHOTO
     *                         DELETE_FLG
     *                         UPDATED_AT
     *
     * @return bool true/false
     *
    */
    public function updateFullcourse($param)
    {
        //更新条件
        $where = array( $this->db->quoteInto("USER_ID = ?",$param['USER_ID']),
                        $this->db->quoteInto("COURSE_ID = ?",$param["COURSE_ID"])
                        );
        //トランザクション開始
        $this->db->beginTransaction();
        try{
            $result = $this->db->updateData( "fullcourse", $param, $where );
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
     * フルコースデータ削除
     * @author: xiuhui yang
     * @param array $param
     *              keyname is USER_ID
     *                         COURSE_ID
     *                         DELETE_FLG
     *                         UPDATED_AT
     *
     * @return bool true/false
     *
    */
    public function deleteFullcourse($param)
    {
        $where = array($this->db->quoteInto("COURSE_ID = ?", $param['COURSE_ID']));
        //トランザクション開始
        $this->db->beginTransaction();
        try{
            $result = $this->db->updateData( "fullcourse", $param, $where );
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
     * フルコースデータ存在チェック
     * @author: xiuhui yang
     * @param array $param
     *              keyname is user_id
     *                         course_id
     *
     * @return  array $results
     *                keyname is CNT
     *
    */
    public function checkFullcourseExist($param)
    {
        //データ抽出SQLクエリ
        $results[0] = 0;
        $sql = "
                SELECT
                     COUNT(1) AS CNT
                FROM
                     fullcourse
                WHERE
                    user_id = ?
                AND
                    course_id = ?
                ";
        $results = $this->db->selectPlaceQuery($sql,array($param['user_id'],$param['course_id']));
        return $results[0];
    }
}
?>
