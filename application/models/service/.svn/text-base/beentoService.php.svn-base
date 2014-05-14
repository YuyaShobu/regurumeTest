<?php

require_once (MODEL_DIR ."/service/abstractService.php");

/**
 * 行ったサービス
 *
 * @package   beento
 * @author    yuya yamamoto 2013/07/01 新規作成
 */
class beentoService extends abstractService {

    /**
     * 行った事あるお店一覧データ取得
     * @author: yuya yamamoto
     * @param array $param
     *              keyname is user_id
     *
     * @return array $res
     *
    */
    public function getBeentoList($param)
    {
        $res = $this->logic(get_class($this),__FUNCTION__ ,$param);
        return $res;
    }


    /**
     * 行ったよ詳細データ取得
     * @author: yuya yamamoto
     * @param array $param
     *              keyname is user_id
     *
     * @return array $res
     *
    */
    public function getBeentoDetail($param)
    {
        $res = $this->logic(get_class($this),__FUNCTION__ ,$param);
        return $res;
    }


    /**
     * 行ったよデータ登録
     * @author: yuya yamamoto
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
    public function insertBeento($param)
    {
        $res = $this->logic(get_class($this),__FUNCTION__ ,$param);
        return $res;
    }


    /**
     * 行ったよデータ更新
     * @author: yuya yamamoto
     * @param array $param
     *              keyname is USER_ID
     *                         SHOP_ID
     *                         EXPLAIN
     *                         PHOTO
     *                         BT_ID
     *                         UPDATED_AT
     *
     * @return bool true/false
     *
    */
    public function updateBeento($param)
    {
        $res = $this->logic(get_class($this),__FUNCTION__ ,$param);
        return $res;
    }


    /**
     * 行ったよデータ存在チェック
     * @author: yuya yamamoto
     * @param array $param
     *              keyname is user_id
     *                         shop_id
     *
     * @return  array $results
     *                keyname is CNT
     *
    */
    public function checkBeentoExist($param)
    {
        $res = $this->logic(get_class($this),__FUNCTION__ ,$param);
        return $res;
    }


    /**
     * 行ったよデータ削除
     * @author: yuya yamamoto
     * @param array $param
     *              keyname is BT_ID
     *                         DELETE_FLG
     *                         UPDATED_AT
     *
     * @return  bool true/false
     *
    */
    public function deleteBeento($param)
    {
        $res = $this->logic(get_class($this),__FUNCTION__ ,$param);
        return $res;
    }


    /**
     * 行ったよ店一覧　応援高い順
     * @author: yuya yamamoto
     * @param none
     *
     * @return array $results
     *
    */
    public function getShopBeentoList($param)
    {
        $res = $this->logic(get_class($this),__FUNCTION__ ,$param);
        return $res;
    }


    /**
     * 行ったことあるユーザー一覧
     * @author: yuya yamamoto
     * @param string $shop_id
     *
     * @return array $results
     *               keyname is user_id
     *                          mail_address
     *
    */
    public function getBeentoUserList($param)
    {
        $res = $this->logic(get_class($this),__FUNCTION__ ,$param);
        return $res;
    }


    /**
     * カテゴリーIDからそのカテゴリーIDの行ったことあるリスト(ショップリスト)を取得
     * @author: yuya yamamoto
     * @param
     *
     * @return array $results
     *               keyname is shop_id
     *                          photo
     *                          user_id
     *                          shop_name
     *                          user_name
     *
    */
     public function getBeentoRanking($param) {
        $res = $this->logic(get_class($this),__FUNCTION__ ,$param);
        return $res;
    }
}
?>
