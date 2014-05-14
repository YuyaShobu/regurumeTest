<?php

require_once (MODEL_DIR ."/service/abstractService.php");

/**
 * トップ３サービス
 *
 * @package   top3
 * @author    xiuhui yang 2013/07/01 新規作成
 */
class top3Service extends abstractService {

    /**
     * カテゴリマスタ一覧取得
     * @author: xiuhui yang
     * @param string $param
     *
     * @return array $res
     *
     *
    */
    public function getCategoryTop3($param)
    {
        $res = $this->logic('top3','getCategoryTop3',$param);
        return $res;
    }


    /**
     * TOP3一覧情報取得
     * @author: xiuhui yang
     * @param array $param
     *              keyname is uid
     *                         category_id
     *
     * @return array $res
     *
     *
    */
    public function getCategoryTop3List($param)
    {
        $res = $this->logic(get_class($this),__FUNCTION__ ,$param);
        return $res;
    }


    /**
     * TOP3詳細情報取得
     * @author: xiuhui yang
     * @param array $param
     *              keyname is uid
     *                         category_id
     *                         top3_no
     *
     * @return array $res
     *
     *
    */
    public function getTop3Detail($param)
    {
        $res = $this->logic(get_class($this),__FUNCTION__ ,$param);
        return $res;
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
        $res = $this->logic(get_class($this),__FUNCTION__ ,$param);
        return $res;
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
        $res = $this->logic(get_class($this),__FUNCTION__ ,$param);
        return $res;
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
        $res = $this->logic(get_class($this),__FUNCTION__ ,$param);
        return $res;
    }


}
?>