<?php
require_once "Zend/Controller/Action.php";
require_once (LIB_PATH ."/Utility.php");
require_once (LIB_PATH . '/Smarty-2.6.27/libs/Smarty.class.php');


class ClientabstractController extends Zend_Controller_Action {

	public $staff_info;
	public $shop_info;
	/**
	 * コンストラクタ内 初期処理
	 *
	 * @return void
	 */
    public function init () {
		$login_flg = false;
		$social_login_flg = 0;
    	$request  = $this->getRequest()->getParams();

    	//ログイン時だけ_loginCheckは読み込ませてはいけないのでフラグをつける
    	if (isset($request['login_flg'])) {
    		$login_flg = true;
    	} elseif (isset($request['state']) and $request['state'] == "1") {
    		$login_flg = true;
    	}


		if ($login_flg == false) {

			$this->staff_info = $this->_loginCheck();
			$temp = $this->staff_info;
			$temp !== false ? $this->view->shop_name = $temp['shop_name'] : '';
		}
        //ユーザーエージェントを判別してテンプレートにフラグセット
        $userAgent = Utility::getUerAgent();
        $this->view->user_agent = $userAgent;

    }


	public function service($className,$MethodName,$params)
	{
		$this->_requireSelect('service' , $className);
		try {
			$reflClass = new ReflectionClass($className.'Service');
			$obj = $reflClass->newInstance();
			$refMethod = $reflClass->getMethod($MethodName);
			$res = $refMethod->invoke($obj,$params);
			return $res;
		} catch(exception $e) {
            echo $e->getMessage();
            return FALSE;
		}
	}

	public function validate($className,$MethodName,$params)
	{
		$this->_requireSelect('validate' , $className);
		try {
			$reflClass = new ReflectionClass($className.'Validate');
			$obj = $reflClass->newInstance();
			$refMethod = $reflClass->getMethod($MethodName);
			$res = $refMethod->invoke($obj,$params);
			return $res;
		} catch(exception $e) {
            echo $e->getMessage();
           return FALSE;
	}
	}

	protected function _loginCheck ($session_already_flg = null) {

		session_cache_limiter('none');
		session_start();
		$post_param = $this->getRequest()->getParams();

		if (!isset($_SESSION["STAFFID"])) {
			$post_param['action'] != 'index' ? $this->_redirect("/client/index") : '';
		} else {
			//ログイン中ならこっちにはいる。

			//セッションに保存されているユーザIDからユーザ情報を取得
			$param['staff_id'] = $_SESSION["STAFFID"];

			//★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★
			$user_info = $this->service('client','searchShopInfoFromStaffId' ,$param);
			//★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★
			//ユーザ情報がなかったらログイン画面へ
			if ($user_info == false) {
				$post_param['action'] != 'index' ? $this->_redirect("/client/index") : '';
			} else {
				return $user_info;
			}
		}
	}

	/*
	 * 自動ローダー
	 * ex it s make : require_once (MODEL_DIR ."/logic/oauthService.php");
	 */
	private function _requireSelect($reqKind,$classname){
		$class_name_space = '/'.$reqKind .'/'. $classname.ucwords($reqKind).'.php';
		require_once (MODEL_DIR. $class_name_space);
	}

}

