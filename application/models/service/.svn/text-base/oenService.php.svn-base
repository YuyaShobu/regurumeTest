<?php

require_once (MODEL_DIR ."/service/abstractService.php");

/**
 * 応援サービス
 *
 * @package   oen
 * @author    xiuhui yang 2013/07/01 新規作成
*/
class oenService extends abstractService {

    /**
     * 応援一覧データ取得
     * @author: xiuhui yang
     * @param array $param
     *              keyname is user_id
     *
     * @return array $res
     *
    */
	public function getOenList($param)
    {
        $res = $this->logic(get_class($this),__FUNCTION__ ,$param);
        return $res;
    }


    /**
     * 応援一覧データ取得
     * @author: xiuhui yang
     * @param array $param
     *              keyname is oen_id
     *
     * @return array $res
     *
    */
    public function getOenDetail($param)
    {
        $res = $this->logic(get_class($this),__FUNCTION__ ,$param);
        return $res;
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
     * @return bool $res true/false
     *
    */
    public function insertOen($param)
    {
        $res = $this->logic(get_class($this),__FUNCTION__ ,$param);
        return $res;
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
        $res = $this->logic(get_class($this),__FUNCTION__ ,$param);
        return $res;
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
        $res = $this->logic(get_class($this),__FUNCTION__ ,$param);
        return $res;
    }


    /**
     * 応援データ削除
     * @author: xiuhui yang
     * @param array $param
     *              keyname is OEN_ID
     *                         DELETE_FLG
     *                         UPDATED_AT
     *
     * @return bool $res true/false
     *
    */
    public function deleteOen($param)
    {
        $res = $this->logic(get_class($this),__FUNCTION__ ,$param);
        return $res;
    }


    /**
     * 応援高い順一覧
     * @param none
     * @return array $res
     */
    public function getShopOenList($param)
    {
        $res = $this->logic(get_class($this),__FUNCTION__ ,$param);
        return $res;
    }


    /**
     * 応援ユーザー一覧
     * @param array $param
     *              keyname is shop_id
     * @return array $res
     */
    public function getOenUserList($param)
    {
        $res = $this->logic(get_class($this),__FUNCTION__ ,$param);
        return $res;
    }


    /**
     * カテゴリーIDからそのカテゴリーIDのランキングリスト(ショップリスト)を取得
     * @param none
     *
     * @return array $res
    */
    public function getOenRanking($param) {
        $res = $this->logic(get_class($this),__FUNCTION__ ,$param);
        return $res;
    }
}
?>
