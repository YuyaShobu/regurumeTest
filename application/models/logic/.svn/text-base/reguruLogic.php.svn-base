<?php

require_once (MODEL_DIR ."/logic/abstractLogic.php");
require_once (LIB_PATH ."/Utility.php");
/**
 * リグルロジック
 *
 * @package   timeline
 * @author    xiuhui yang 2013/08/06 新規作成
 */
class reguruLogic extends abstractLogic {


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
        $ret = 0;
        //データ抽出SQLクエリ
        $sql = "
                SELECT
                     COUNT(1) AS CNT
                FROM
                     reguru_info
                WHERE
                    reguru_uid = ?
                AND
                    rank_id = ?
                AND
                    delete_flg = 1
                ";

        $where = array ($param['REGURU_UID'],$param['RANK_ID']);

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
        //トランザクション開始
        $this->db->beginTransaction();
        try{
            $result = $this->db->insertData( "reguru_info", $param );
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
        //更新条件
        $where = array( $this->db->quoteInto("REGURU_UID = ?",$param['REGURU_UID']),
                        $this->db->quoteInto("RANK_ID = ?",$param["RANK_ID"])
                        );
        //トランザクション開始
        $this->db->beginTransaction();
        try{
            $result = $this->db->updateData( "reguru_info", $param, $where );
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
        $ret = 0;
        //データ抽出SQLクエリ
        $sql = "
                SELECT
                     COUNT(1) AS CNT
                FROM
                     reguru_info
                WHERE
                    reguru_uid = ?
                AND
                    rank_id = ?
                ";

        $where = array ($param['REGURU_UID'],$param['RANK_ID']);
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
     * タイムラインに登録処理
     * @author: xiuhui yang
     * @param int $rank_id
     *
     *
     * @return none
     *
    */
    public function insertTimeline($params)
    {
        $insertTimeLine = array();
        $insertTimeLine['RANK_ID'] = $params['RANK_ID'];
        $insertTimeLine['TL_TYPE'] = Utility::CONST_VALUE_TIMELINE_TYPE_RANKING;

        //存在チェック行う
        $sql2 = "
                SELECT
                    COUNT(1) AS CNT
                FROM
                    timeline
                WHERE
                    user_id = ?
                AND
                    tl_type = 1
                AND
                    rank_id = ?
                AND
                    created_user_id = ?
                ";
        $where = array( $params['REGURU_UID'],$params['RANK_ID'],$params['CREATED_USER_ID'] );
        $results = $this->db->selectPlaceQuery( $sql2, $where );
        //一件も取れていなければFALSEを返す
        if ( $results[0]['CNT'] == 0) {
            //timelineテーブルに登録する
            $insertTimeLine['USER_ID'] = $params['REGURU_UID'];
            $insertTimeLine['CREATED_USER_ID'] = $params['CREATED_USER_ID'];
            $insertTimeLine['CREATED_AT'] = date("Y-m-d H:i:s");
            $insertTimeLine['UPDATED_AT'] = date("Y-m-d H:i:s");
            $this->db->insertData( "timeline", $insertTimeLine );
        } else {
            //更新条件
            $inputwhere = array($this->db->quoteInto("user_id = ?",$params['REGURU_UID']),
                                $this->db->quoteInto("tl_type = ?",Utility::CONST_VALUE_TIMELINE_TYPE_RANKING),
                                $this->db->quoteInto("rank_id = ?",$params['RANK_ID']),
                                $this->db->quoteInto("created_user_id = ?",$params['CREATED_USER_ID'])
                                );
            $insertTimeLine['UPDATED_AT'] = date("Y-m-d H:i:s");
            $this->db->updateData( "timeline", $insertTimeLine,$inputwhere );
        }
    }

    /**
     * タイムラインに登録処理
     * @author: xiuhui yang
     * @param int $rank_id
     *
     *
     * @return none
     *
    */
    public function insertTimeline1($params)
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
        $fl_list = $this->db->selectPlaceQuery($sql1,array($params['REGURU_UID']));
        $insertTimeLine = array();
        $insertTimeLine['RANK_ID'] = $params['RANK_ID'];
        $insertTimeLine['TL_TYPE'] = Utility::CONST_VALUE_TIMELINE_TYPE_RANKING;
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
                                tl_type = 1
                            AND
                                rank_id = ?
                            AND
                                created_user_id = ?
                            ";
                    $where = array( $fl_list[$i]['follower'],$params['RANK_ID'],$params['CREATED_USER_ID'] );
                    $results = $this->db->selectPlaceQuery( $sql2, $where );
                    //一件も取れていなければFALSEを返す
                    if ( $results[0]['CNT'] == 0) {
                        //新規投稿区別のため、作成者情報登録しない
                        $insertTimeLine['CREATED_USER_ID'] = $params['CREATED_USER_ID'];
                        $insertTimeLine['CREATED_AT'] = date("Y-m-d H:i:s");
                        $insertTimeLine['UPDATED_AT'] = date("Y-m-d H:i:s");
                        $this->db->insertData( "timeline", $insertTimeLine );
                        if ( $i == 0 ){
                            //自分のユーザーIDに対してtimelineテーブルに登録する
					        $insertTimeLine['USER_ID'] = $params['REGURU_UID'];
					        $insertTimeLine['CREATED_USER_ID'] = $params['CREATED_USER_ID'];
					        $insertTimeLine['CREATED_AT'] = date("Y-m-d H:i:s");
					        $insertTimeLine['UPDATED_AT'] = date("Y-m-d H:i:s");
					        $this->db->insertData( "timeline", $insertTimeLine );
                        }
                    } else {
                        //更新条件
                        $inputwhere = array( $this->db->quoteInto("user_id = ?",$fl_list[$i]['follower']),
                                            $this->db->quoteInto("tl_type = ?",Utility::CONST_VALUE_TIMELINE_TYPE_RANKING),
                                            $this->db->quoteInto("rank_id = ?",$params['RANK_ID']),
                                            $this->db->quoteInto("created_user_id = ?",$params['CREATED_USER_ID'])
                        );
                    	$insertTimeLine['UPDATED_AT'] = date("Y-m-d H:i:s");
                        $this->db->updateData( "timeline", $insertTimeLine,$inputwhere );

                    }

                }
            }
        }

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
        $now_post_num = $param['now_post_num'];
        $get_post_num = $param['get_post_num'];
    	//データ抽出SQLクエリ
        $sql = "
                SELECT
                    a.*,
                    b.user_name,
                    b.photo as user_photo,
                    b.fb_pic as user_fb_photo
                FROM
                    reguru_info a
                INNER JOIN
                    user b
                ON
                    a.reguru_uid = b.user_id
                WHERE
                    a.rank_id = :rank_id
                AND
                    a.delete_flg = 1
                AND
                    b.delete_flg = 0
                ORDER BY
                    a.updated_at
                ";
        $sql.= " LIMIT $now_post_num, $get_post_num ";
        $results = $this->db->selectPlaceQuery($sql,array($param['rank_id']));
        //一件も取れていなければFALSEを返す
        if ( $results == NULL  or count($results) == 0) {
            return FALSE;
        } else {
            return $results;
        }
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
        //データ抽出SQLクエリ
        $sql = "
                SELECT
                    count(*) as CNT
                FROM
                    reguru_info a
                INNER JOIN
                    user b
                ON
                    a.reguru_uid = b.user_id
                WHERE
                    a.rank_id = :rank_id
                AND
                    a.delete_flg = 1
                AND
                    b.delete_flg = 0
                ";
        $results = $this->db->selectPlaceQuery($sql,array($param['rank_id']));
        //一件も取れていなければFALSEを返す
        if ( $results == NULL  or count($results) == 0) {
            return 0;
        } else {
            return $results[0]['CNT'];
        }
    }
}
?>
