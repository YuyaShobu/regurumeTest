<?php

require_once (MODEL_DIR ."/service/abstractService.php");

//class sampleService {
class valueService extends abstractService {

    /**
     * データ取得
     * @param string
     * @return array
     */
    //データ抽出SQLクエリ
    function getValueList($param)
    {
        $res = $this->logic('value','getValueList',$param);
        return $res;
    }


    /**
     * ショップ詳細画面その他各種情報取得
     * @author: xiuhui yang
     * @param array $para
     *              keyname is user_id
     *                         shop_id
     *
     * @return  array $results
     *                keyname is CNT
     *
    */
    public function getShopOtherInfo($para)
    {
        $ret = array();
        // 各種ボタン押せるかチェック
        // 「応援」ボタン押せるかチェック
        $ret['oen_flg'] = $this->logic('oen','checkOenExist',$para);

        // 「行った」ボタン押せるかチェック
        $ret['beento_flg'] = $this->logic('beento','checkBeentoExist',$para);

        // 「行きたい」ボタン押せるかチェック
        $ret['wantto_flg'] = $this->logic('voting','checkShopVotingExist',$para);

        // このshopに行きたいユーザー情報
        $ret['wantto_list'] = $this->logic('voting','getUserList',$para);

        // このshopに行ったユーザー情報
        $where['shop_id'] = $para['shop_id'];
        $ret['beento_list'] = $this->logic('shop','getBeentoUserList',$where);

        // 応援ユーザー一覧
        $ret['oen_list'] = $this->logic('shop','getOenUserList',$where);
        return $ret;
    }

}
?>