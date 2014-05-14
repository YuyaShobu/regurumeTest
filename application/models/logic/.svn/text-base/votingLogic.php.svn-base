<?php

require_once (MODEL_DIR ."/logic/abstractLogic.php");
require_once (LIB_PATH ."/Utility.php");

/**
 * 評価ロジック
 *
 * @package   voting
 * @author    xiuhui yang 2013/07/01 新規作成
 */
class votingLogic extends abstractLogic {


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
        //トランザクション開始
        $this->db->beginTransaction();
        try{
            // データ存在チェック

                $sql = "
                    SELECT
                        COUNT(1) AS CNT
                    FROM
                        shop_voting
                    WHERE
                        user_id = ?
                    AND
                        shop_id = ?
                    AND
                        voting_kind = ?
                    AND
                        delete_flg = 0
                    ";
                $data = $this->db->selectPlaceQuery($sql,array($param['user_id'],$param['shop_id'],$param["voting_kind"]),FALSE);
                if ( $data[0]['CNT'] > 0 ) {
                } else {
                    $param['created_at'] = date("Y-m-d H:i:s");
                    $param['updated_at'] = date("Y-m-d H:i:s");
                    $ret = $this->db->insertData( "shop_voting", $param, FALSE);
                    //タイムテーブルに登録する
                    $id = $this->db->lastInsertId();
                    $param['voting_id'] = $id;
                    $param['tl_type'] = Utility::CONST_VALUE_TIMELINE_TYPE_WANTTO;
                    $this ->insertTimeline($param,'voting_id');
                }

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
     * ショップ評価データ更新
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
        //更新条件
        $where = array( $this->db->quoteInto("user_id = ?",$param['user_id']),
                        $this->db->quoteInto("voting_kind = ?",$param["voting_kind"]),
                        $this->db->quoteInto("shop_id = ?",$param["shop_id"])
                        );
        //トランザクション開始
        $this->db->beginTransaction();
        try{
            $result = $this->db->deleteData( "shop_voting", $where );
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
        //トランザクション開始
        $this->db->beginTransaction();
        try{
            // データ存在チェック

                $sql = "
                    SELECT
                        COUNT(1) AS CNT
                    FROM
                        oen
                    WHERE
                        user_id = ?
                    AND
                        shop_id = ?
                    AND
                        delete_flg = 0
                    ";
                $data = $this->db->selectPlaceQuery($sql,array($param['user_id'],$param['shop_id']),FALSE);
                if ( $data[0]['CNT'] > 0 ) {
                } else {
                    $param['created_at'] = date("Y-m-d H:i:s");
                    $param['updated_at'] = date("Y-m-d H:i:s");
                    $ret = $this->db->insertData( "oen", $param, FALSE);
                    //タイムテーブルに登録する
                    $id = $this->db->lastInsertId();
                    $param['oen_id'] = $id;
                    $param['tl_type'] = Utility::CONST_VALUE_TIMELINE_TYPE_OEN;
                    $this ->insertTimeline($param,'oen_id');
                }

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
     * 応援データ更新
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
        //更新条件
        $where = array( $this->db->quoteInto("user_id = ?",$param['user_id']),
                        $this->db->quoteInto("shop_id = ?",$param['shop_id'])
                        );
        //トランザクション開始
        $this->db->beginTransaction();
        try{
            $result = $this->db->deleteData( "oen", $where );
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
        //トランザクション開始
        $this->db->beginTransaction();
        try{
            // データ存在チェック

                $sql = "
                    SELECT
                        COUNT(1) AS CNT
                    FROM
                        beento
                    WHERE
                        user_id = ?
                    AND
                        shop_id = ?
                    AND
                        delete_flg = 0
                    ";
                $data = $this->db->selectPlaceQuery($sql,array($param['user_id'],$param['shop_id']),FALSE);
                //$data = $this->service('voting','checkBeentoExist',$inputData);
                if ( $data[0]['CNT'] > 0 ) {
                    // 削除されたデータがあるため、delete_flg=0に戻す
                    /*
                    $param['delete_flg'] = 0;
                    $param['updated_at'] = date("Y-m-d H:i:s");
                    //更新条件
                    $where = array( $this->db->quoteInto("user_id = ?",$param['user_id']),
                                    $this->db->quoteInto("shop_id = ?",$param['shop_id'])
                                   );
                    $ret = $this->db->updateData( "beento", $param, $where, FALSE);
                    */
                    //$ret = $this->service('voting','updateBeento',$inputData);
                } else {
                    $param['created_at'] = date("Y-m-d H:i:s");
                    $param['updated_at'] = date("Y-m-d H:i:s");
                    $ret = $this->db->insertData( "beento", $param, FALSE);
                    //タイムテーブルに登録する
                    $id = $this->db->lastInsertId();
                    $param['bt_id'] = $id;
                    $param['tl_type'] = Utility::CONST_VALUE_TIMELINE_TYPE_BEENTO;
                    $this ->insertTimeline($param,'bt_id');
                }

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
        //更新条件
        $where = array( $this->db->quoteInto("user_id = ?",$param['user_id']),
                        $this->db->quoteInto("shop_id = ?",$param['shop_id'])
                        );
        //トランザクション開始
        $this->db->beginTransaction();
        try{
            $result = $this->db->deleteData( "beento", $where );
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
     * 行った、行きたい、応援投稿したら、タイムラインに登録処理
     * @author: xiuhui yang
     * @param int $rank_id
     *
     *
     * @return none
     *
    */
    public function insertTimeline($params,$keyname)
    {
        $insertTimeLine = array();
        $insertTimeLine[$keyname] = $params[$keyname];
        $insertTimeLine['TL_TYPE'] = $params['tl_type'];
        //存在チェック行う
        $sql2 = "
                SELECT
                     COUNT(1) AS CNT
                FROM
                    timeline
                WHERE
                    user_id = ?
                AND
                    tl_type = {$params['tl_type']}
                AND
            ";
        $sql2 .= $keyname ."= ? ";
        $where = array( $params['user_id'],$params[$keyname] );
        $results = $this->db->selectPlaceQuery( $sql2, $where ,FALSE);
        //一件も取れていなければFALSEを返す
        if ( $results[0]['CNT'] == 0) {
            //自分のユーザーIDに対してtimelineテーブルに登録する
            $insertTimeLine['USER_ID'] = $params['user_id'];
            $insertTimeLine['CREATED_USER_ID'] = $params['user_id'];
            $insertTimeLine['CREATED_AT'] = date("Y-m-d H:i:s");
            $insertTimeLine['UPDATED_AT'] = date("Y-m-d H:i:s");
            $this->db->insertData( "timeline", $insertTimeLine ,FALSE );
        } else {
            //更新条件
            /*
            $inputwhere = array( $this->db->quoteInto("user_id = ?",$fl_list[$i]['follower']),
                                $this->db->quoteInto("tl_type = ?",$params['tl_type']),
                                $this->db->quoteInto($keyname ." = ?",$params[$keyname])
            );
            $insertTimeLine['UPDATED_AT'] = date("Y-m-d H:i:s");
            $this->db->updateData( "timeline", $insertTimeLine,$inputwhere,FALSE);
            */

        }
    }


    /**
     * 行った、行きたい、応援投稿したら、タイムラインに登録処理
     * @author: xiuhui yang
     * @param int $rank_id
     *
     *
     * @return none
     *
    */
    public function insertTimeline1($params,$keyname)
    {
        //follower一覧取得
        $sql1 = "
                SELECT
                     follow,follower
                FROM
                     follow
                WHERE
                    follow = ?
                ";
        $fl_list = $this->db->selectPlaceQuery($sql1,array($params['user_id']),FALSE);
        $insertTimeLine = array();
        $insertTimeLine[$keyname] = $params[$keyname];
        $insertTimeLine['TL_TYPE'] = $params['tl_type'];
        if ( is_array($fl_list) && count($fl_list) > 0) {
            for ( $i = 0; $i<count($fl_list); $i++) {
                if (isset($fl_list[$i]['follower']) && $fl_list[$i]['follower'] !="") {
                    $insertTimeLine['USER_ID'] = $fl_list[$i]['follower'];
                    //存在チェック行う
                    $sql2 = "
                            SELECT
                                 COUNT(1) AS CNT
                            FROM
                                timeline
                            WHERE
                                user_id = ?
                            AND
                                tl_type = {$params['tl_type']}
                            AND
                            ";
                    $sql2 .= $keyname ."= ? ";
                    $where = array( $fl_list[$i]['follower'],$params[$keyname] );

                    $results = $this->db->selectPlaceQuery( $sql2, $where ,FALSE);
                    //一件も取れていなければFALSEを返す
                    if ( $results[0]['CNT'] == 0) {
                        $insertTimeLine['CREATED_USER_ID'] = $params['user_id'];
                        $insertTimeLine['CREATED_AT'] = date("Y-m-d H:i:s");
                        $insertTimeLine['UPDATED_AT'] = date("Y-m-d H:i:s");
                        $this->db->insertData( "timeline", $insertTimeLine ,FALSE );
                    } else {
                        //更新条件
                        /*
                        $inputwhere = array( $this->db->quoteInto("user_id = ?",$fl_list[$i]['follower']),
                                            $this->db->quoteInto("tl_type = ?",$params['tl_type']),
                                            $this->db->quoteInto($keyname ." = ?",$params[$keyname])
                        );
                        $insertTimeLine['UPDATED_AT'] = date("Y-m-d H:i:s");
                        $this->db->updateData( "timeline", $insertTimeLine,$inputwhere,FALSE);
                        */
                    }
                }
            }
        }
        //自分のユーザーIDに対してtimelineテーブルに登録する
        $insertTimeLine['USER_ID'] = $params['user_id'];
        $insertTimeLine['CREATED_USER_ID'] = $params['user_id'];
        $insertTimeLine['CREATED_AT'] = date("Y-m-d H:i:s");
        $insertTimeLine['UPDATED_AT'] = date("Y-m-d H:i:s");
        $this->db->insertData( "timeline", $insertTimeLine ,FALSE );

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
        //データ抽出SQLクエリ
        $ret = 0;
        $sql = "
                SELECT
                     COUNT(1) AS CNT
                FROM
                     oen
                WHERE
                    user_id = ?
                AND
                    shop_id = ?
                ";
        $results = $this->db->selectPlaceQuery($sql,array($param['user_id'],$param['shop_id']));
        //一件も取れていなければFALSEを返す
        if ( $results == NULL  or count($results) == 0) {
            $ret = 0;
        } else {
           $ret = $results[0]['CNT'];
        }
         return $ret;
    }

    /**
     * 行ったよデータ存在チェック
     * @author: xiuhui yang
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
        //データ抽出SQLクエリ
        $ret = 0;
        $sql = "
                SELECT
                     COUNT(1) AS CNT
                FROM
                     beento
                WHERE
                    user_id = ?
                AND
                    shop_id = ?
                ";
        $results = $this->db->selectPlaceQuery($sql,array($param['user_id'],$param['shop_id']));
        //一件も取れていなければFALSEを返す
        if ( $results == NULL  or count($results) == 0) {
            $ret = 0;
        } else {
            return $results[0]['CNT'];
        }
        return $ret;
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
    public function insertBeento($param)
    {
        //トランザクション開始
        $this->db->beginTransaction();
        try{
            $result = $this->db->insertData( "beento", $param );
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
        $ret = 0;
        //データ抽出SQLクエリ
        $sql = "
                SELECT
                     COUNT(1) AS CNT
                FROM
                     category_voting
                WHERE
                    user_id = ?
                AND
                    fc_id = ?
                AND
                    voting_type = ?
                AND
                    voting_kind = ?
                AND
                    delete_flg = 0
                ";

        $where = array ($param['USER_ID'],$param['FC_ID'],$param['VOTING_TYPE'],$param['VOTING_KIND']);
        $results = $this->db->selectPlaceQuery( $sql, $where );
        //一件も取れていなければFALSEを返す
        if ( $results == NULL  or count($results) == 0) {
            $ret = 0;
        } else {
            $ret = $results[0]['CNT'];
        }
        return $ret;
    }


    /**
     * カテゴリ評価データ登録
     * @author: xiuhui yang
     * @param array $param
     *              keyname is USER_ID
     *                         FC_ID
     *                         VOTING_TYPE
     *                         VOTING_KIND
     *                         CREATE_USER_ID
     *                         CREATED_AT
     *                         UPDATED_AT
     *
     * @return bool true/false
     *
    */
    public function insertCategoryVoting($param)
    {
        //トランザクション開始
        $this->db->beginTransaction();
        try{
            $result = $this->db->insertData( "category_voting", $param );
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

        $ret = 0;
        //データ抽出SQLクエリ
        $sql = "
                SELECT
                     COUNT(1) AS CNT
                FROM
                     shop_voting
                WHERE
                    user_id = ?
                AND
                    shop_id = ?
                AND
                    voting_kind = ?
                ";
        if (isset($param['delete_flg']) !="") {
            $sql .= "delete_flg = 0";
        }
        $where = array ($param['user_id'],$param['shop_id'],$param['voting_kind']);
        $results = $this->db->selectPlaceQuery( $sql, $where );
        //一件も取れていなければFALSEを返す
        if ( $results == NULL  or count($results) == 0) {
            $ret = 0;
        } else {
            $ret = $results[0]['CNT'];
        }
        return $ret;
    }


    /**
     * カテゴリ評価ランキングデータ存在チェック
     * @author: xiuhui yang
     * @param array $param
     *              keyname is user_id
     *                         rank_id
     *                         voting_type
     *                         voting_kind
     *                         create_user_id
     *
     * @return  array $res
     *                keyname is CNT
     *
    */
    public function checkRankVotingExist($param)
    {
        $ret = 0;
        //データ抽出SQLクエリ
        $sql = "
                SELECT
                     COUNT(1) AS CNT
                FROM
                     category_voting
                WHERE
                    user_id = ?
                AND
                    rank_id = ?
                AND
                    voting_type = ?
                AND
                    voting_kind = ?
                AND
                    create_user_id = ?
                ";

        $where = array ($param['USER_ID'],$param['RANK_ID'],$param['VOTING_TYPE'],$param['VOTING_KIND'],$param['CREATE_USER_ID']);
        $results = $this->db->selectPlaceQuery( $sql, $where );
        //一件も取れていなければFALSEを返す
        if ( $results == NULL  or count($results) == 0) {
            $ret = 0;
        } else {
            $ret = $results[0]['CNT'];
        }
        return $ret;
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
        //更新条件
        $where = array( $this->db->quoteInto("USER_ID = ?",$param['USER_ID']),
                        $this->db->quoteInto("RANK_ID = ?",$param["RANK_ID"]),
                        $this->db->quoteInto("VOTING_TYPE = ?",$param["VOTING_TYPE"]),
                        $this->db->quoteInto("VOTING_KIND = ?",$param["VOTING_KIND"]),
                        $this->db->quoteInto("CREATE_USER_ID = ?",$param["CREATE_USER_ID"])
                        );
        //トランザクション開始
        $this->db->beginTransaction();
        try{
            $result = $this->db->updateData( "category_voting", $param, $where );
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
     * 行きたいなどユーザー情報取得
     * @param array $param
     *              keyname is shop_id
     *                         voting_kind
     * @return array $results
     */
    public function getUserList($param)
    {
        //データ抽出SQLクエリ
        $sql = "
                SELECT
                    a.user_id,b.user_name,b.mail_address, b.fb_pic as user_fb_photo , b.photo as user_photo
                FROM
                    shop_voting a
                INNER JOIN
                    user b
                ON
                    a.user_id = b.user_id
                WHERE
                    a.shop_id  = ?
                AND
                    a.voting_kind = ?
                AND
                    a.delete_flg = 0
                AND
                    b.delete_flg = 0
                ";
        $results = $this->db->selectPlaceQuery($sql,array($param['shop_id'],$param['voting_kind']));
        //一件も取れていなければFALSEを返す
        if ( $results == NULL  or count($results) == 0) {
            return FALSE;
        } else {
            return $results;
        }
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
        //トランザクション開始
        $this->db->beginTransaction();
        try{
            $result = $this->db->insertData( "shop_voting", $param );
            $this->db->commit();
        }
        catch(exception $e)
        {
            $this->db->rollBack();
            return false;
        }
        return true;
    }
}
?>
