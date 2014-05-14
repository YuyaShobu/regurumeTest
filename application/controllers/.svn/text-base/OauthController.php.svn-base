<?php
require_once (LIB_PATH . '/Social/Facebook/facebook.php');
require_once (LIB_PATH ."/Utility.php");

class OauthController extends  AbstractController
{
	public function indexAction () {

		/*
		 * facebookログインのためのURL生成
		 */
	    $utility = new Utility;
        $config_path = $utility->_getSocialLoginConfigPath();
        $config = yaml_parse_file($config_path);
		$fb_config = $config['facebook'];

		$config = array(
		    'appId'  => $fb_config['appId'],
		    'secret' => $fb_config['secret']
		);

		$facebook = new Facebook($config);

		//未ログインならログイン URL を取得してリンクを出力
		$params = array(//'display'=>'popup',
						'redirect_uri' => $fb_config['redirect_uri'],
						'scope' => 'email,publish_stream','state' =>'1'
 				);
		$facebook_login_url = $facebook->getLoginUrl($params);
		$this->view->facebook_login_url = $facebook_login_url;
	}


	public function disconnectAction () {
		//viewは読み込まない
		$this->_helper->viewRenderer->setNoRender();

		//user_idガポストされてくる
		$post_param = $this->getRequest()->getParams();
		$user_id = $post_param['user_id'];

		//つながりを切ってリダイレクト
		$ret = $this->service('oauth','disconnectFb', $user_id);

		$this->_redirect("/user/index/");
	}

	//userコントローラーのfacebookConnectActionからだけ呼ばれる
	public function connectAction () {
		//viewは読み込まない
		$this->_helper->viewRenderer->setNoRender();

		//user_idがポストされてくる
		$post_param = $this->getRequest()->getParams();
		$user_id = $post_param['user_id'];

		//FB連携してリダイレクト
		$ret = $this->service('oauth','connectFb', $user_id);

		$this->_redirect("/user/index/");
	}
}

