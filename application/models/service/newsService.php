<?php

require_once (MODEL_DIR ."/service/abstractService.php");

/**
 * お知らせサービス
 *
 * @package   news
 * @author    xiuhui yang 2013/09/30 新規作成
 */
class newsService extends abstractService {

	/**
	 * データ取得
	 * @param string
	 * @return array
	 */
	//データ抽出SQLクエリ
	function getNewsList($param)
	{
        $res = $this->logic(get_class($this),__FUNCTION__ ,$param);
        return $res;
	}

    /**
     * データ取得
     * @param string
     * @return array
     */
    //データ抽出SQLクエリ
    function getNewsDetail($param)
    {
        $res = $this->logic(get_class($this),__FUNCTION__ ,$param);
        return $res;
    }
}
?>
