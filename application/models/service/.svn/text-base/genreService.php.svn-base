<?php

require_once (MODEL_DIR ."/service/abstractService.php");

/**
 * ジャンルサービス
 *
 * @package   oen
 * @author    xiuhui yang 2013/09
*/
class genreService extends abstractService {

    /**
     * getGenreChild
     * @auther: xiuhui yang
     * 小ジャンルマスタ一覧を取得
     * @param string $param
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
        $res = $this->logic(get_class($this),__FUNCTION__ ,$param);
        //$arr = $this->_getGenre2List($res);
        return $res;
    }


//▼▼▼▼▼▼▼▼チップス▼▼▼▼▼▼▼▼▼▼▼▼

    /**
     * 小ジャンル一覧情報整形処理(N:1対応)
     *
     * @param array $results
     * @return array $buf
     */
    private function _getGenre2List($results) {
        $buf = array();
        if ( $results ) {
            foreach($results as $key => $value) {
            $keyName = $value['genre_id_1'];
            if (!isset($buf[$keyName])) {
                $buf[$keyName] = array(
                    'genre_id_1' => $value['genre_id_1']
                );
            }
            $buf[$keyName]['detail'][] = $value;
        }
        return $buf;
        }
    }
}
?>

