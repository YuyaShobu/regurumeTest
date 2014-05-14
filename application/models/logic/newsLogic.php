<?php

require_once (MODEL_DIR ."/logic/abstractLogic.php");

/**
 * お知らせロジック
 *
 * @package   news
 * @author    xiuhui yang 2013/09/30 新規作成
 */
class newsLogic extends abstractLogic {


    /**
     * 応援一覧データ取得
     * @author: xiuhui yang
     * @param none
     *
     *
     * @return array $results
     *
    */
    public function getNewsList()
    {
        //データ抽出SQLクエリ
        $sql = "
                SELECT
                    *
                FROM
                    news
                WHERE
                    delete_flg = 0
                AND
                    status = 6
                AND
                    unix_timestamp(public_start) < ".time()."
                AND
                    unix_timestamp(public_end) > ".time()."
                ORDER BY
                    created_at desc
                LIMIT 5;
                ";
        $results = $this->db->selectPlaceQuery($sql,array());
        //一件も取れていなければFALSEを返す
        if ( $results == NULL  or count($results) == 0) {
            return FALSE;
        } else {
            return $results;
        }
    }


    /**
     * 応援一覧データ取得
     * @author: xiuhui yang
     * @param array $param
     *              keyname is oen_id
     *
     * @return array $results
     *
    */
    public function getNewsDetail($param)
    {
        //データ抽出SQLクエリ
        $sql = "
                SELECT
                    *
                FROM
                    news
                WHERE
                    news_id = ?
                ";
        $results = $this->db->selectPlaceQuery($sql,array($param));
        //一件も取れていなければFALSEを返す
        if ( $results == NULL  or count($results) == 0) {
            return FALSE;
        } else {
            return $results[0];
        }
    }

}
?>
