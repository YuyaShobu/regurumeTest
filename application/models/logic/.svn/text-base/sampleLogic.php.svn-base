<?php

require_once (LIB_PATH ."/DbConn.php");

class sampleLogic {

	protected $db = NULL;

	/**
	 * コンストラクタ
	 */
	public function __construct(){
		$this->db = DbConn::getInstance();
	}

	/**
	 * データ取得
	 * @param string
	 * @return array $results
	 */
	//データ抽出SQLクエリ
	function getSampleDate($para)
	{
		//データ抽出SQLクエリ
		$sql = "
                SELECT
                    *
                FROM
                    testtable
                WHERE
                    id =?
                ";
		$results = $this->db->selectPlaceQuery($sql, array($para));
		return $results[0];
	}

}
?>
