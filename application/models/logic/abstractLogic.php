<?php
/**
 * top3サイト
 *
 * logic利用モデルクラスの基底クラス
 *
 * @copyright 2013
 * @author    yang
 */
require_once (LIB_PATH ."/DbConn.php");

class abstractLogic
{
	protected $db = NULL;

	/**
	 * コンストラクタ
	 */
	public function __construct(){
		$this->db = DbConn::getInstance();
	}


}//============================ END::CLASS
