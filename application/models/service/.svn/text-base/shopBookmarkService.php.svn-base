<?php

require_once (MODEL_DIR ."/service/abstractService.php");

/**
 * ショップブックマックサービス
 *
 * @package   shopBookmark
 * @author    xiuhui yang 2013/07/01 新規作成
 */
class shopBookmarkService extends abstractService {

    /**
     * ショップブックマック一覧データ取得
     * @author: xiuhui yang
     * @param
     *
     * @return array $res
     *
    */
    function getShopBookmarkList($param)
    {
        $res = $this->logic('shopBookmark','getShopBookmarkList',$param);
        return $res;
    }


    /**
     * ショップブックマックデータ存在チェック
     * @author: xiuhui yang
     * @param array $param
     *              keyname is user_id
     *                         shop_id
     *
     * @return  int $res
     *
     *
    */
    public function checkShopBookmarkExist($param)
    {
        $res = $this->logic(get_class($this),__FUNCTION__ ,$param);
        return $res;
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
        $res = $this->logic(get_class($this),__FUNCTION__ ,$param);
        return $res;
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
        $res = $this->logic(get_class($this),__FUNCTION__ ,$param);
        return $res;
    }
}
?>