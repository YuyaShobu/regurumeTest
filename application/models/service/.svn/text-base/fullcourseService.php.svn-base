<?php

require_once (MODEL_DIR ."/service/abstractService.php");

/**
 * フルコースサービス
 *
 * @package   fullcourse
 * @author    xiuhui yang 2013/07/01 新規作成
 */
class fullcourseService extends abstractService {

    /**
     * フルコースマスタ一覧データ取得
     * @author: xiuhui yang
     * @param
     *
     * @return array $res
     *               keyname is course_id
     *                          course_name
    */
	function getFullcourseMaster($param)
    {
        $res = $this->logic(get_class($this),__FUNCTION__ ,$param);
        return $res;
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
    function getFullcourseList($param)
    {
        $res = $this->logic('fullcourse','getFullcourseList',$param);
        return $res;
    }


    /**
     * フルコース詳細データ取得
     * @author: xiuhui yang
     * @param array $param
     *              keyname is user_id
     *                         course_id
     *
     * @return array $res
     *
    */
    function getFullcourseDetail($param)
    {
        $res = $this->logic('fullcourse','getFullcourseDetail',$param);
        return $res;
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
    function insertFullcourse($param)
    {
        $res = $this->logic('fullcourse','insertFullcourse',$param);
        return $res;
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
    function updateFullcourse($param)
    {
        $res = $this->logic('fullcourse','updateFullcourse',$param);
        return $res;
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
     * @return bool $res true/false
     *
    */
    function deleteFullcourse($param)
    {
        $res = $this->logic(get_class($this),__FUNCTION__ ,$param);
        return $res;
    }


    /**
     * フルコースデータ存在チェック
     * @author: xiuhui yang
     * @param array $param
     *              keyname is user_id
     *                         course_id
     *
     * @return  array $res
     *                keyname is CNT
     *
    */
    function checkFullcourseExist($param)
    {
        $res = $this->logic(get_class($this),__FUNCTION__ ,$param);
        return $res;
    }


}
?>
