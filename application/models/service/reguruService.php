<?php

require_once (MODEL_DIR ."/service/abstractService.php");

/**
 * リグル
 *
 * @package   reguru
 * @author    xiuhui yang 2013/08/06 新規作成
 */
class reguruService extends abstractService {

    /**
     * リグルデータ存在チェック
     * @author: xiuhui yang
     * @param array $param
     *              keyname is user_id
     *                         rank_id
     *
     * @return  array $res
     *                keyname is CNT
     *
    */
    public function checkRankingReguru($param)
    {
        $res = $this->logic(get_class($this),__FUNCTION__ ,$param);
        return $res;
    }


    /**
     * データ登録
     * @author: xiuhui yang
     * @param array $param
     *              keyname is RANK_ID
     *                         REGURU_UID
     *                         CREATED_AT
     *                         UPDATED_AT
     *
     *
     * @return bool true/false
     *
    */
    public function insertReguru($param)
    {
        $res = $this->logic(get_class($this),__FUNCTION__ ,$param);
        return $res;
    }

    /**
     * データ更新
     * @author: xiuhui yang
     * @param array $param
     *              keyname is REGURU_UID
     *                         RANK_ID
     *                         UPDATED_AT
     *
     * @return bool true/false
     *
    */
    public function updateReguru($param)
    {
        $res = $this->logic(get_class($this),__FUNCTION__ ,$param);
        return $res;
    }

        /**
     * リグルデータ存在チェック
     * @author: xiuhui yang
     * @param array $param
     *              keyname is user_id
     *                         rank_id
     *
     * @return  array $res
     *                keyname is CNT
     *
    */
    public function checkRankingReguru1($param)
   {
        $res = $this->logic(get_class($this),__FUNCTION__ ,$param);
        return $res;
    }

    /**
     * タイムラインに登録処理
     * @author: xiuhui yang
     * @param int $i
     *
     *
     * @return bool
     *
    */
    public function insertTimeline($param)
    {
        $res = $this->logic(get_class($this),__FUNCTION__ ,$param);
        return $res;
    }


    /**
     * ランキングりぐる一覧情報
     * @author: xiuhui yang
     * @param  array
     *              keyname is rank_id
     *
     *
     * @return array $res
     *
    */
    public function getReguruList($param)
    {
        $res = $this->logic(get_class($this),__FUNCTION__ ,$param);
        return $res;
    }

        /**
     * ランキングりぐる数
     * @author: xiuhui yang
     * @param  array
     *              keyname is rank_id
     *
     *
     * @return array $res
     *
    */
    public function getReguruListCount($param)
    {
        $res = $this->logic(get_class($this),__FUNCTION__ ,$param);
        return $res;
    }
}
?>
