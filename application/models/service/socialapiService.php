<?php

require_once (MODEL_DIR ."/service/abstractService.php");
require_once (LIB_PATH . '/Social/Facebook/facebook.php');
require_once (LIB_PATH ."/Utility.php");
/*
 * ログイン周りはこのクラスではなくoauthサービスを見てください。
 * このクラスはユーザにフィードするためのサービスです。
 */
class socialapiService extends abstractService {

	/*
	 * $param : array
	 * 			  kind
	 *               'beento'
	 *               'category'
	 *               'special'
	 *               'oen'
	 *            user_id
	 *            message
	 *            photo_path
	 *            name
	 *            caption
	 *            description
	 *            action
	 *               name
	 *               link
	 *            shop_name
	 *            shop_id
	 *            user_name
	 */
	public function facebookfeed ($param) {

        $res = $this->logic('oauth','getSocialInfo' ,$param);

		try {
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
		    $fb_contents = $this->_switchFeedContents($param);
		    if ($param['kind'] == 'beento_feed') {
				$post = '/me/feed';
				try {

				        $result = $facebook->api($post, "post", array(
				        'access_token' => $res['fb_accsess_token'],
				        'message'     => $fb_contents['message'],
			            "picture"     => $fb_contents['picture'],
			            "link"        => $fb_contents['link'], //'フィード上でクリックされた際のリンク先'
			            "name"        => $fb_contents['name'],//'リンク名'
			            "caption"     => $fb_contents['caption'],//キャプション
			            "description" => $fb_contents['description']//'詳細文'//
			        ));
				} catch (FacebookApiException $e) {
		            throw $e;
				}

		    } elseif ($param['kind'] == 'beento_albam') {
				//photoAPIを使う場合
		        $source = '@' . $fb_contents['picture'];

		        try {
		            $requestParameters = array(
		                    'access_token' => $res['fb_accsess_token'],
		                    'message'      => $fb_contents['message'].'

		                    				  Regurumeから
		                    				  '.$fb_contents['link'],
		                    'source'       => $source
		                    );

		            $facebook->setFileUploadSupport(true);
		            $result = $facebook->api('/me/photos', 'POST', $requestParameters);

		        } catch (FacebookApiException $e) {
		            throw $e;
		        }
		    }

		    //結果を返す
	        return $result;
	    } catch (FacebookApiException $e) {
	        throw new Exception($e->getMessage());
	    }

	}
//▼▼▼▼▼▼▼▼▼▼▼▼チップス▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼

	private function _switchFeedContents($param) {

		if ($param['kind'] == 'beento_feed') {
			$link = 'http://regurume.com/user/beentoshop/id/'.$param['user_id'].'/';
			$fb_contents['message']     =  $param['message'];
			$fb_contents['picture']     =  $param['photo_path'];
			$fb_contents['link']        =  $link;
			$fb_contents['name']        =  "行ったことのあるお店の一覧です。";
			$fb_contents['description'] =  'Regurumeから投稿｜ソーシャル私的ランキング';
			$fb_contents['caption']     =  $param['shop_name'].'に行きました';
			return $fb_contents ;
		} elseif ($param['kind'] == 'beento_albam') {
			$link = 'http://regurume.com/user/beentoshop/id/'.$param['user_id'].'/';
			$fb_contents['picture']     =  $param['photo_path'];
			$fb_contents['link']        =  $link;
			$fb_contents['message']     =  $param['message'];
			return $fb_contents ;
		}
	}
}