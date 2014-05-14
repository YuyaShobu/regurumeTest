<?php

require_once (MODEL_DIR ."/service/abstractService.php");

/**
 * 評価サービス
 *
 * @package   voting
 * @author    xiuhui yang 2013/07/01 新規作成
 */
class votingService extends abstractService {


    /**
     * ショップ評価データ登録
     * @author: xiuhui yang
     * @param array $param
     *              keyname is user_id
     *                         voting_kind
     *                         shop_id
     *                         created_at
     *                         updated_at
     *
     * @return bool true/false
     *
    */
    public function registShopVoting($param)
    {
        $res = $this->logic(get_class($this),__FUNCTION__ ,$param);
        return $res;
    }


    /**
     * ショップ評価データ削除
     * @author: xiuhui yang
     * @param array $param
     *              keyname is user_id
     *                         shop_id
     *                         voting_kind
     *
     * @return bool true/false
     *
    */
    public function deleteShopVoting($param)
    {
        $res = $this->logic(get_class($this),__FUNCTION__ ,$param);
        return $res;
    }


    /**
     * 行きたいなどユーザー情報取得
     * @param array $param
     *              keyname is shop_id
     *                         voting_kind
     * @return array $results
     */
    public function getUserList($param)
    {
        $list = $this->logic(get_class($this),__FUNCTION__ ,$param);
        if ($list) {
        	$list[0]['cnt'] = count($list);
            for ($i=0; $i < count($list); $i++) {

                //作成者画像チェック
                $image_existed = Utility::isImagePathExisted(Utility::CONST_USER_IMAGE_PATH.$list[$i]['user_photo']);
                if(!isset($list[$i]['user_photo']) or $list[$i]['user_photo'] =="" or $image_existed != true) {
                    $list[$i]['user_photo'] = Utility::CONST_NOIMAGE_IMAGE_PATH.Utility::CONST_NO_IMG_FILE_NAME;
                } else {
                    $list[$i]['user_photo'] = Utility::CONST_USER_IMAGE_PATH.$list[$i]['user_photo'];
                }
            }
        }
        return $list;
    }


    /**
     * 応援データ登録
     * @author: xiuhui yang
     * @param array $param
     *              keyname is USER_ID
     *                         SHOP_ID
     *                         CREATED_AT
     *                         UPDATED_AT
     *
     * @return bool true/false
     *
    */
    public function registShopOen($param)
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
     *              keyname is USER_ID
     *                         SHOP_ID
     *
     * @return bool true/false
     *
    */
    public function deleteShopOen($param)
    {
        $res = $this->logic(get_class($this),__FUNCTION__ ,$param);
        return $res;
    }


    /**
     * 行ったよデータ登録
     * @author: xiuhui yang
     * @param array $param
     *              keyname is USER_ID
     *                         SHOP_ID
     *                         CREATED_AT
     *                         UPDATED_AT
     *
     * @return bool true/false
     *
    */
    public function registBeento($param)
    {
        $res = $this->logic(get_class($this),__FUNCTION__ ,$param);
        return $res;
    }

    /**
     * 行ったよデータ更新
     * @author: xiuhui yang
     * @param array $param
     *              keyname is USER_ID
     *                         SHOP_ID
     *
     * @return bool true/false
     *
    */
    public function deleteBeento($param)
    {
        $res = $this->logic(get_class($this),__FUNCTION__ ,$param);
        return $res;
    }


    /**
     * カテゴリ評価データ存在チェック
     * @author: xiuhui yang
     * @param array $param
     *              keyname is user_id
     *                         fc_id
     *                         voting_type
     *                         voting_kind
     *
     * @return  array $res
     *                keyname is CNT
     *
    */
    public function checkVotingExist($param)
    {
        $res = $this->logic(get_class($this),__FUNCTION__ ,$param);
        return $res;
    }


    /**
     * カテゴリ評価データ登録
     * @author: xiuhui yang
     * @param array $param
     *              keyname is USER_ID
     *                         FC_ID
     *                         VOTING_TYPE
     *                         VOTING_KIND
     *                         CREATED_AT
     *                         UPDATED_AT
     *
     * @return bool true/false
     *
    */
    public function insertCategoryVoting($param)
    {
        $res = $this->logic(get_class($this),__FUNCTION__ ,$param);
        return $res;
    }



    /**
     * カテゴリ評価ランキングデータ存在チェック
     * @author: xiuhui yang
     * @param array $param
     *              keyname is user_id
     *                         rank_id
     *                         voting_type
     *                         voting_kind
     *
     * @return  array $res
     *                keyname is CNT
     *
    */
    public function checkRankVotingExist($param)
    {
        $res = $this->logic(get_class($this),__FUNCTION__ ,$param);
        return $res;
    }


    /**
     * カテゴリ評価データ更新
     * @author: xiuhui yang
     * @param array $param
     *              keyname is USER_ID
     *                         RANK_ID
     *                         VOTING_TYPE
     *                         VOTING_KIND
     *                         CREATE_USER_ID
     *                         CREATED_AT
     *                         UPDATED_AT
     *
     * @return bool true/false
     *
    */
    public function updateCategoryVoting($param)
    {
        $res = $this->logic(get_class($this),__FUNCTION__ ,$param);
        return $res;
    }

    /**
     * ショップ評価データ存在チェック
     * @author: xiuhui yang
     * @param array $param
     *              keyname is user_id
     *                         shop_id
     *                         voting_kind
     *
     * @return  array $res
     *                keyname is CNT
     *
    */
    public function checkShopVotingExist($param)
    {
        $res = $this->logic(get_class($this),__FUNCTION__ ,$param);
        return $res;
    }


    /**
     * ショップ評価データ登録
     * @author: xiuhui yang
     * @param array $param
     *              keyname is user_id
     *                         voting_kind
     *                         shop_id
     *                         created_at
     *                         updated_at
     *
     * @return bool true/false
     *
    */
    public function insertShopVoting($param)
    {
        $res = $this->logic(get_class($this),__FUNCTION__ ,$param);
        return $res;
    }

}
?>
