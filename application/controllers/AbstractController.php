<?php
require_once "Zend/Controller/Action.php";
require_once (LIB_PATH ."/Utility.php");
require_once (LIB_PATH . '/Smarty-2.6.27/libs/Smarty.class.php');
require_once('./spyc.php');

class AbstractController extends Zend_Controller_Action {

	public $login_status;
	public $login_user_info;
	protected static $tdkllist;
	protected static $pankuzulist;
	/**
	 * コンストラクタ内 初期処理
	 *
	 * @return void
	 */
    public function init () {
		$this->user_info = $this->_loginCheck();

        //ユーザーエージェントを判別してテンプレートにフラグセット
        $userAgent = Utility::getUerAgent();
        $this->view->user_agent = $userAgent;
        $this->view->user_name =  $this->user_info['user_name'];
        $this->view->user_id   =  $this->user_info['user_id'];
        $this->view->user  =  $this->user_info;
        //if (isset($this->user_info['photo'])) {
        //    $this->view->user_photo   =  $this->user_info['photo'];
        //}
        //ログインの場合右サイド共通情報取得
        if($this->user_info['user_id'] != null) {
        $side_info=$this->service('user','getUserComInfoByUserid',$this->user_info);
            $this->view->side_info   =  $side_info;
        }
        //お知らせ情報取得
        $new_list = $this->service('news','getNewsList', "");
        if ($new_list) {
            $this->view->news_list = $new_list;
        }

        //ヘッダエリア都道府県マスタ取得
        $pref = $this->service('ranking','getPrefList', '');
        $this->view->pref= $pref;
        $this->view->root_url= 'ROOT_URL';
        $this->_getTdk();
        $this->_getPankuzuList();

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

		if (!isset($_SESSION["USERID"])) {
			//$this->_redirect("/login/index/");
			$user_info['user_name'] = 'ゲスト';
			$user_info['user_id'] = null;
			$this->login_status = 0;
			return $user_info;

		} else {
			//ログイン中ならこっちにはいる。

			//セッションに保存されているユーザIDからユーザ情報を取得
			$param['user_id'] = $_SESSION["USERID"];

			$user_info = $this->service('user','searchUserInfoFromUserId' ,$param);
			//ユーザ情報がなかったらログイン画面へ
			if ($user_info['user_exist_flg'] == false) {
				$this->login_status = 0;
				$user_info['user_name'] = 'ゲスト';
				$user_info['user_id'] = null;
				return $user_info;
			} else {
				$this->login_status = 1;
				return $user_info;
			}
		}
	}

	//ログインしないと見れないページがある場合のリダイレクトのために使用する
	protected function _caseUnloginRedirect () {
		if ($this->login_status == 0) {
			/*
		     * ログイン前にコンテンツを見ていて飛ばされてきた場合は
		     * 直前のURLを取得してログイン後にリダイレクトしてあげる
		     */
			$origin_url = $_SERVER["REQUEST_URI"];
			$origin_url = str_replace("/", "_", $origin_url);
			$this->_redirect("/login/index/unlogin/$origin_url/");
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

   /**
     * SEO用TDK
     * param  string
     * return string
     */
    protected function _getTdk($title = null)
    {
    	$Utility = new Utility;
    	$Utility->redirectEc2toLiveEnv();
    	$use_tdk_engine_flg = $Utility->tdkEngine();

    	if ($use_tdk_engine_flg == true) {
	        $uri          = $_SERVER["REQUEST_URI"];
	        //トップ以外の場合は /*** のケースと /***/以上のケースがある
	        if ($uri != "/") {
		        $nuberRequest = explode("/", $uri);
		        $count = count($nuberRequest);
		        if ($count == 2) {
		        	//ex  /aboutとかの時
		        	$uri = $nuberRequest[1];
		        } elseif ($count == 3) {
		        	if ($nuberRequest[2] == "") {
		        		//ex /about/ とかの時
						$uri = $nuberRequest[1];
		        	} else {
		        		//ex /search/ranking とかの時
						$uri = $nuberRequest[1]."/".$nuberRequest[2];
		        	}

		        } else {
		        	    //ex  /search/ranking/id/3とかの時
		        	$uri = $nuberRequest[1]."/".$nuberRequest[2];

		        }

	        }
	        if (!strstr($uri, 'ajax')) {
		        self::$tdkllist  = yaml_parse_file(DATA_PATH."/tdk.yml");
		        $tdk = self::$tdkllist;
		        if (!isset($tdk[$uri])) {
		        	$uri = 'key';
		        	$tdk[$uri] = array('title' => 'みんなでつなぐグルメランキング｜
		        	リグルメ', 'keywords' => 'リグルメ,regurume,グルメランキング,
		        	おいしい店ランキング' , 'discription' => '食べた記録に、
		        	おいしいお店のシェアに、新たなお店の開拓に自由にお使いください。
		        	ソーシャルだけれど私的なグルメランキングを繋いでいこう。');
		        }
		        if ($title != null) {
		        	$tdk[$uri]['title'] = $title . $tdk[$uri]['title'];
		        }
		        $this->view->tdk = $tdk[$uri];
	        }
    	} else {
    		$this->view->tdk = "";
    	}
    }


   /**
     * パンくず自動作成
     * param  string
     * return string
     */
    protected function _getPankuzuList($info = null){
        $uri = $_SERVER["REQUEST_URI"];
        //トップ以外の場合は /*** のケースと /***/以上のケースがある
        if ($uri != "/") {
            $nuberRequest = explode("/", $uri);
            #var_dump($nuberRequest);exit;
            $count = count($nuberRequest);
            if ($count == 2) {
                //ex  /aboutとかの時
                $uri = $nuberRequest[1];
            } elseif ($count == 3) {
                if ($nuberRequest[2] == "") {
                    //ex /about/ とかの時
                    $uri = $nuberRequest[1];
                } else {
                    //ex /search/ranking とかの時
                    $uri = $nuberRequest[1]."/".$nuberRequest[2];
                }
            } else {
                //ex  /search/ranking/id/3とかの時
                $uri = $nuberRequest[1]."/".$nuberRequest[2];
            }
        }
        if (!strstr($uri, 'ajax')) {
        	
        	#yaml使用---------------------------------------
        	$urlArrRow = Spyc::YAMLLoad(DATA_PATH."/pankuzu.yml");
        	#print"<pre>";var_dump($urlArr);exit;
        	$urlArr = Utility::yamlUrlCre($urlArrRow , $uri);
			#print "<pre>";
			#var_dump($urlArr);exit;
            #-----------------------------------------------
        				
			#yaml不使用--------------------------------------
        	#$urlArr = Utility::urlCre();
        	#var_dump($urlArr);exit;
        	#-----------------------------------------------

            //$pankuzu = self::$pankuzulist;
            $this->view->pankuzu = $urlArr;
        }
    }
}

