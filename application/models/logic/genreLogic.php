<?php

require_once (MODEL_DIR ."/logic/abstractLogic.php");

/**
 * ジャンルロジック
 *
 * @package   oen
 * @author    xiuhui yang 2013/09 新規作成
 */
class genreLogic extends abstractLogic {

    /**
     * getGenreChild
     * @auther: xiuhui yang
     * 小ジャンルマスタ一覧を取得
     * @param array$param
     *
     * @return array $results
     *               keyname is
     *                          genre_id
     *                          genre_id_1
     *                          genre_id_2
     *                          genre_value
     */
    public function getGenreChild($param)
    {
        //データ抽出SQLクエリ
        $sql = "
                SELECT
                    genre_id,
                    SUBSTRING(genre_id,1,LOCATE('_',genre_id)-1) genre_id_1,
                    SUBSTRING( genre_id,LOCATE('_',genre_id)+1,
                    LOCATE('_',genre_id,LOCATE('_',genre_id) )) genre_id_2,
                    value
                FROM
                     genre
                WHERE
                     genre_id  like '%\_%'
                AND
                     SUBSTRING(genre_id,1,LOCATE('_',genre_id)-1) = {$param['genre_id']}
                ";
        $results = $this->db->selectPlaceQuery($sql,array());
        //一件も取れていなければFALSEを返す
        if ( $results == NULL  or count($results) == 0) {
            return FALSE;
        } else {
            return $results;
        }
    }
}
?>
