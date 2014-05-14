<?php
require_once (LIB_PATH . '/Social/Facebook/facebook.php');
require_once (MODEL_DIR ."/validate/loginValidate.php");
require_once (MODEL_DIR ."/service/rankingService.php");
require_once (MODEL_DIR ."/service/oauthService.php");
require_once (LIB_PATH ."/Utility.php");
define("HAMULET", 139);

class LoginController extends Zend_Controller_Action
{

	//新規登録入力画面
	public function newsignupAction () {
	    /*
		 * facebook連携のためのURL生成
		 */

        $utility = new Utility;
        $config_path = $utility->_getSocialSignUpConfigPath();
        $config = yaml_parse_file($config_path);
		$fb_config = $config['facebook'];

		$config = array(
		    'appId'  => $fb_config['appId'],
		    'secret' => $fb_config['secret'],
			'cookie' => true
		);

		$facebook = new Facebook($config);

		//未ログインならログイン URL を取得してリンクを出力
		$params = array(//'display'=>'popup',
						'redirect_uri' => $fb_config['redirect_uri'],
						'scope' => 'email,publish_stream'
	 			);
		$facebook_login_url = $facebook->getLoginUrl($params);
		$this->view->facebook_login_url = $facebook_login_url;

		$this->view->error = "";
		$error = $this->getRequest()->getParam('error');
		$this->view->error = $error;
		//$pref = yaml_parse_file(DATA_PATH."/pref.yml");
        //$this->view->pref =  $pref;
        //都道府県マスタ取得
        $obj = new rankingService();
        $pref = $obj->getPrefList($param="");
        //$pref = $this->service('ranking','getPrefList', $param="");
        $this->view->pref= $pref;
		//誕生日プルダウン初期設定
        $birthday["birthday_year"] = "";
        $birthday["birthday_month"] = "";
        $birthday["birthday_day"] = "";
        $birthday = Utility::_makeBirthdayList($birthday);
        $this->view->birthday = $birthday;
        //タイトルとパンくず作成
        $this->_setTdkPankuzu('login/newsignup');
	}


	//新規登録アクション
	public function signupAction () {

		//ログイン画面からの遷移でない場合はログイン画面に飛ばす
		$signup_flg  = $this->getRequest()->getParam('signup_flg');
		if ($signup_flg != "1") {
			$this->_redirect("login/newsignup/");
		}

		//入力チェック
        //登録のバリデーション
        $post_param = $this->getRequest()->getParams();
        $obj = new loginValidate();
        $validate = $obj->registValidate($post_param);

        //パラメータ不正の場合
        if (isset($validate['error_flg'])) {
            //値を保持して入力画面へ飛ばす。
            $this->_buck_regist_page($validate);
        } else {
			//ユーザ名とパスワードを取得する
			$user_name  = $this->getRequest()->getParam('user_name');
			$password1   = $this->getRequest()->getParam('password1');
			$password2   = $this->getRequest()->getParam('password2');
			$address1    = $this->getRequest()->getParam('address1');
            $email    = $this->getRequest()->getParam('email');
			$birthday_year    = $this->getRequest()->getParam('birthday_year');
            $birthday_month    = $this->getRequest()->getParam('birthday_month');
            $birthday_day    = $this->getRequest()->getParam('birthday_day');
            $gender    = $this->getRequest()->getParam('gender');
            //未入力の場合はリダイレクト
			if ($email =="" or $password1 == "" or $password2 =="" or $address1 =="") {
				$this->_redirect("login/newsignup/error/password/");
			}

			//入力パスワードが間違っている場合はリダイレクト
			if ($password1 != $password2) {
				$this->_redirect("login/newsignup/error/password/");
			} else {
				$password = $password1;
			}

			$params['user_name']      = $user_name;
			$params['password']       = $password;
			$params['address1']       =  $address1;
            $params['address1']       =  $address1;
            $params['email']          =  $email;
            $params['birthday_year']  =  $birthday_year;
            $params['birthday_month'] =  $birthday_month;
            $params['birthday_day']   =  $birthday_day;
            $params['gender']         =  $gender;

			//登録（ユーザテーブルとoauthテーブルに登録する
			require_once (MODEL_DIR ."/service/userService.php");
			$userService = new userService();
			$res = $userService->signup($params);

			//そのユーザ名は既に使われています系
			if (isset($res['user_exist_flg'])) {
				$this->_redirect("login/newsignup/error/already/");
			}

			//indexへredirect
		 	session_cache_limiter('none');
		 	session_start();
	        // セッションIDを新規に発行する
	        session_regenerate_id(TRUE);
	        $_SESSION["USERID"]    =  $res['user_id'];
	        ini_set('session.gc_maxlifetime', 1);
	       	require_once (MODEL_DIR ."/service/userService.php");
			$service = new userService();
			$param = array('user_id' =>  $res['user_id'], 'follow_user_id' => HAMULET);
			$service->follow($param);

	        $this->_redirect("/index/index/");
        }
	}

	public function signupfacebookAction () {

		$facebook_access_error   = $this->getRequest()->getParam('error');
		if ($facebook_access_error) {
			$this->_redirect("login/index/error/1/");
		}
		require_once (LIB_PATH . '/Social/Facebook/facebook.php');
		require_once (LIB_PATH ."/Utility.php");
		$code = $this->getRequest()->getParam('code');

        /*
		 * facebookユーザ情報取得
		 */
		$utility = new Utility;
        $config_path = $utility->_getSocialSignUpConfigPath();
        $config = yaml_parse_file($config_path);
		$fb_config = $config['facebook'];
		$APP_ID  =  $fb_config['appId'];
		$CLENTID =  $fb_config['secret'];
		$CALLBACKURI = $fb_config['redirect_uri'];

		$token_url = 'https://graph.facebook.com/oauth/access_token?client_id='.
					    $APP_ID .'&redirect_uri=' . urlencode($CALLBACKURI) . '&client_secret='.
					    $CLENTID . '&code=' . $code;

		$access_token = file_get_contents($token_url);

		$access_token = preg_replace('/\&.*/', '', $access_token);
		// ユーザ情報json取得してdecode
		$user_json = file_get_contents('https://graph.facebook.com/me?' . $access_token);
		$fb_user = json_decode($user_json);

		// facebook の user_id + name(表示名)をセット
		$fb_user_id  = $fb_user->id;
		$fb_name     = $fb_user->name;
		$fb_email    = $fb_user->email;
		$fb_pic      = 'https://graph.facebook.com/'.$fb_user_id.'/picture?type=large';

		//既にメールアドレスがあるユーザかチェック


		$fb_address  = @$fb_user->location->name1;
		//string(23) "Nakano-ku, Tokyo, Japan"のように入ります。

		$en_pref = yaml_parse_file(DATA_PATH."/en_pref.yml");
		foreach ($en_pref as $key => $val) {
			if (strstr($fb_address, $val)) {
				$address1 = $key;
			} else {
				$address1 = '13';
			}
		}
		if ($fb_user_id) {
			$param['mail_address'] = $fb_email;
			$param['fb_id'] = $fb_user_id;
			$fb_accsess_token = str_replace("access_token=" ,"",$access_token);

			$param['fb_accsess_token'] = $fb_accsess_token;
			$oauth = new oauthService();
			$auth_info = $oauth->getAuthInfoFromFbId($param);

			if ($auth_info['facebook_exist_flg'] == true) {

				//■このfacebookIDがすでに登録されていたらそこからIDを取得&トークンを更新
				//$auth_info['facebook_exist_flg'] == trueならすでにサービス内でトークン更新している。
				$user_info['user_id'] = $auth_info['user_id'];
			} else {
				//■もし登録されていなかったらIDを新規登録
				//ユーザIDも発行する
				$param['fb_id']        = $fb_user_id;
				$param['fb_name']      = $fb_name;
				$param['mail_address'] = $fb_email;
				$param['fb_pic']       = $fb_pic;
				$param['address1']     =$address1;
				$auth_info = $oauth->registAuthInfoFromFbId($param);
				$user_info['user_id']  = $auth_info['user_id'];
				require_once (MODEL_DIR ."/service/userService.php");
				$service = new userService();
				$param = array('user_id' => $user_info['user_id'], 'follow_user_id' => HAMULET);
				$service->follow($param);
				//新規登録がうまく行かなかった場合はリダイレクト
				if ($auth_info['regist_success_flg'] == false) {
					$this->_redirect("login/index/");
				}
			}
		 	 session_cache_limiter('none');
		 	 session_start();
	        // セッションIDを新規に発行する
	         session_regenerate_id(TRUE);
	         $_SESSION["USERID"]    =  $user_info['user_id'];
	         ini_set('session.gc_maxlifetime', 1);
		} else{
			//■いきなりこのアクションを叩かれたとき
			$this->_redirect("/login/index/");
		}
		$this->_redirect("/index/index/");
	}


	public function indexAction () {

	    $unlogin_url = $this->getRequest()->getParam('unlogin');

        $login_error_noinput = $this->getRequest()->getParam('unlogin');

	    //初期化
        if ( $unlogin_url && $unlogin_url !="" ) {
            $this->view->unlogin_flg = 1;
            $this->view->origin_url = $unlogin_url;
        }

	    //メールアドレスとパスワード入力されてない場合
        $error = $this->getRequest()->getParam('error');
        if ( $error && $error =="noinput" ) {
            $this->view->error  = "メールアドレスとパスワードを入力してください。";
            $this->view->errorflg = true;
        } else if ($error && $error =="nodata") {
    	    //メールアドレスとパスワード存在してない場合
            $this->view->error  = "正しいメールアドレスまたはパスワードを入れてください。";
            $this->view->errorflg = true;
        }
        /*
		 * facebookログインのためのURL生成
		 */
		$utility = new Utility;
        $config_path = $utility->_getSocialLoginConfigPath();
        $config = yaml_parse_file($config_path);
		$fb_config = $config['facebook'];

		$config = array(
		    'appId'  => $fb_config['appId'],
		    'secret' => $fb_config['secret'],
			'cookie' => true
		);


		$facebook = new Facebook($config);

		//未ログインならログイン URL を取得してリンクを出力
		$params = array(//'display'=>'popup',
						'redirect_uri' => $fb_config['redirect_uri'],
						'scope' => 'email,publish_stream','state' =>$unlogin_url
 				);
		$facebook_login_url = $facebook->getLoginUrl($params);
		$this->view->facebook_login_url = $facebook_login_url;
        //都道府県マスタ取得
        $obj = new rankingService();
        $pref = $obj->getPrefList($param="");
        $this->view->pref= $pref;
        //タイトルとパンくず作成
        $this->_setTdkPankuzu('login/index');
	}


	public function logoutAction () {
		if (isset($_SESSION["USERID"])) {
		  $errorMessage = "ログアウトしました。";
		}
		else {
		  $errorMessage = "タイムアウトしました。";
		}
		// セッション変数のクリア
		$_SESSION = array();
		// クッキーの破棄
		if (ini_get("session.use_cookies")) {
		    $params = session_get_cookie_params();
		    setcookie(session_name(), '', time() - 42000,
		        $params["path"], $params["domain"],
		        $params["secure"], $params["httponly"]
		    );
		}
		// セッションクリア
		@session_destroy();
		$this->_redirect("/index/index/");

	}

//▼▼▼▼▼▼▼▼チップス▼▼▼▼▼▼▼▼▼▼▼▼
    private function _buck_regist_page($validate) {
        //入力した値を保持
        $this->view->user = $validate;
        $this->view->error  = $validate['error_message'];
        $this->view->errorflg = $validate['error_flg'];

        //生年月日
        $birthday = array();
        $birthday['birthday_year'] = $validate['birthday_year'];
        $birthday['birthday_month'] = $validate['birthday_month'];
        $birthday['birthday_day'] = $validate['birthday_day'];
        $birthday = Utility::_makeBirthdayList($birthday);
        $this->view->birthday = $birthday;

        //都道府県プルダウンにセットする
        //$pref = yaml_parse_file(DATA_PATH."/pref.yml");
        //$this->view->pref =  $pref;
        //都道府県マスタ取得
        $obj = new rankingService();
        $pref = $obj->getPrefList($param="");
        $this->view->pref= $pref;
        //タイトルとパンくず作成
        $this->_setTdkPankuzu('login/newsignup');
        //入力画面に戻って、エラーメッセージを表示する
        $this->_helper->viewRenderer('newsignup');
    }


   /**
     * SEO用TDK設定、pankuzu作成
     * abstractController.phpの_getTdk、_getPankuzuList使えないため,ここで個別対応
     *
     * param  string
     * return string
     */
    private function _setTdkPankuzu($uri)
    {
        //SEO用TDK設定
        $Utility = new Utility;
        $use_tdk_engine_flg = $Utility->tdkEngine();
        if ($use_tdk_engine_flg == true) {
            $tdk  = yaml_parse_file(DATA_PATH."/tdk.yml");
            if (!isset($tdk[$uri])) {
                $uri = 'key';
                $tdk[$uri] = array('title' => 'みんなでつなぐグルメランキング｜リグルメ', 'keywords' => 'リグルメ,regurume,グルメランキング,おいしい店ランキング' , 'discription' => '食べた記録に、おいしいお店のシェアに、新たなお店の開拓に自由にお使いください。ソーシャルだけれど私的なグルメランキングを繋いでいこう。');
              }
            $this->view->tdk = $tdk[$uri];
        } else {
            $this->view->tdk = "";
        }
        //pakuzu作成
        $pankuzu  = yaml_parse_file(DATA_PATH."/pakuzu.yml");
        if (!isset($pankuzu[$uri])) {
            $pankuzu['info'] = "";
        }
        $this->view->pankuzu = $pankuzu[$uri];
    }

}