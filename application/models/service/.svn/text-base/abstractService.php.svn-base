<?php
/**
 * top3サイト
 *
 * service利用モデルクラスの基底クラス
 *
 * @copyright 2013
 * @author    yang wrote base
 * @extend    yamamoto wrote pravateFunc about require
 */


class abstractService
{
	/**
	 * コンストラクタ
	 *
	 * @param
	 * @return void
	 */
	public function __construct()
	{
		//$this->_className = get_class($this) ;
	}//--end:function


	function logic($className , $methodName,$params)
	{

		try {
			if (mb_strpos($className, "Service")){
				$className = str_replace("Service", "", $className);
			}
			$this->_requireSelect('logic',$className);
			$reflClass = new ReflectionClass($className.'Logic');
			$obj = $reflClass->newInstance();
			$refMethod = $reflClass->getMethod($methodName);
			$res = $refMethod->invoke($obj,$params);
			return $res;
		} catch(exception $e) {
			echo $e;
		}
	}

	/*
	 * 自動ローダー
	 * ex it s make : require_once (MODEL_DIR ."/logic/oauthLogic.php");
	 */
	private function _requireSelect($reqKind,$classname){
		$class_name_space = '/'.$reqKind .'/'. $classname.ucwords($reqKind).'.php';
		require_once (MODEL_DIR. $class_name_space);
	}

}//============================ END::CLASS
