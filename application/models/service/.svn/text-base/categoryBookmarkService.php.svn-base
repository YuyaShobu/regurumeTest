<?php

require_once (MODEL_DIR ."/service/abstractService.php");

/**
 * カテゴリブックマックサービス
 *
 * @package   categoryBookmark
 * @author    xiuhui yang 2013/07/01 新規作成
 */
class categoryBookmarkService extends abstractService {

    /**
     * カテゴリブックマック一覧データ取得
     * @author: xiuhui yang
     * @param
     *
     * @return array $res
     *
    */
    function getCategoryBookmarkList($param)
    {
        $res = $this->logic('categoryBookmarkList','getCategoryBookmarkList',$param);
        return $res;
    }


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
        $res = $this->logic(get_class($this),__FUNCTION__ ,$param);
        return $res;
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
        $res = $this->logic(get_class($this),__FUNCTION__ ,$param);
        return $res;
    }

}
?>
