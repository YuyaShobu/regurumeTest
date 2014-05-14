<?php
/*
 * 2013.6.28 yuya yamamoto
 * oauth周りはyamamotoまで(￣ω￣)
 *
 */

require_once (LIB_PATH ."/Utility.php");
define("HAMULET", 139);
class IndexController extends AbstractController {

    Const DISPLAY_NUM_INIT =  8;//1ページ目表示件数(件)
    Const DISPLAY_NUM = 8;//1ページ表示件数(件)


    public function facebookloginAction () {
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
        $config_path = $utility->_getSocialLoginConfigPath();
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
            $auth_info = $this->service('oauth','getAuthInfoFromFbId',$param);

            if ($auth_info['facebook_exist_flg'] == true) {

                //■このfacebookIDがすでに登録されていたらそこからIDを取得&トークンを更新
                //$auth_info['facebook_exist_flg'] == trueならすでにサービス内でトークン更新している。
                $user_info['user_id'] = $auth_info['user_id'];
            } else {
                //■もし登録されていなかったら新規会員登録
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
					$param['address1']     = $address1;
					$param['fb_pic']       = $fb_pic;
					$auth_info = $oauth->registAuthInfoFromFbId($param);
					$user_info['user_id']  = $auth_info['user_id'];

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
                require_once (MODEL_DIR ."/service/userService.php");
                $service = new userService();
                $param = array('user_id' =>  $user_info['user_id'], 'follow_user_id' => HAMULET);
                $service->follow($param);
                $this->_redirect("/index/index/");
            }
             session_cache_limiter('none');
             session_start();
            // セッションIDを新規に発行する
             session_regenerate_id(TRUE);
             $_SESSION["USERID"]    =  $user_info['user_id'];
             ini_set('session.gc_maxlifetime', 1);
        } else{
            //■いきなりこのアクションを叩かれたとき
            $this->_redirect("login/index/");
        }

        //ログイン前に見ているページがあったらリダイレクトしてあげる
        $origin_url  = $this->getRequest()->getParam('state');
        if ($origin_url != "") {
            $origin_url = str_replace("_", "/", $origin_url);
            $this->_redirect($origin_url);
        } else {
            $this->_redirect("/index/index/");
        }
    }
    /** トップページを表示するアクション */
    public function loginAction () {

        //ログイン画面からの遷移でない場合はログイン画面に飛ばす
        $login_flg  = $this->getRequest()->getParam('login_flg');
        if ($login_flg != "1") {
            $this->_redirect("login/index/");
        }
        $origin_url  = $this->getRequest()->getParam('origin_url');

        //ユーザ名とパスワードを取得する
         $email  = $this->getRequest()->getParam('email');
         $password   = $this->getRequest()->getParam('password');

         //ソーシャルログインのみの人の場合はパスワードが０で登録されているため、０でのログインを回避している
         if ($password == '0') {
            $this->_redirect("login/index/error/1/");
         }

         // エラーメッセージ
         $errorMessage = "";

         // 画面に表示するため特殊文字をエスケープする
         $email  = htmlspecialchars($email, ENT_QUOTES);
         $password   = htmlspecialchars($password, ENT_QUOTES);

         if ($email == "" or $password =="") {
            $this->_redirect("login/index/error/noinput/");
         } else {
	         //検索されたユーザ名とパスワードがあるかどうか調べる
	         $param['mail_address']  = $email;
	         $param['password']   = $password;
	         $user_info = $this->service('user' , 'searchUserIdFromMailAndPassward' , $param );
	         if ($user_info['user_exist_flg'] == true) {
				//abstractでセッションはすでに発行されている。
	             $_SESSION["USERID"]    =  $user_info['user_id'];
	         } else {
	             $this->_redirect("login/index/error/nodata/");
	         }
         }

         if ($origin_url) {
            $origin_url = str_replace("_", "/", $origin_url);
            $this->_redirect($origin_url);
         }
         $this->_redirect('index/index/');
    }


    /** トップページを表示するアクション */
    public function indexAction () {

        //トップページの初期表示(新着ランキングデフォルト表示)
        $params['now_post_num'] = 0;
        $params['get_post_num'] = self::DISPLAY_NUM_INIT;

        $params['flg'] = "new";
        $top_new = $this->service('top','getTopInfo', $params);
        //画面へ表示
        $this->view->top_new= $top_new;
        $this->view->view_flg   =  "new";
        $this->view->display_numinit= self::DISPLAY_NUM_INIT;
        $this->view->display_num= self::DISPLAY_NUM;

        //初期表示共通情報セット
        $this->_setCommonView();
    }

    /** トップページを表示するアクション */
    public function reguruAction () {

        //トップページの初期表示(新着ランキングデフォルト表示)
        $params['now_post_num'] = 0;
        $params['get_post_num'] = self::DISPLAY_NUM_INIT;

        $params['flg'] = "reguru";
        $top_reguru = $this->service('top','getTopInfo', $params);
        //画面へ表示
        $this->view->top_reguru= $top_reguru;
        $this->view->view_flg   =  "reguru";
        $this->view->display_numinit= self::DISPLAY_NUM_INIT;
        $this->view->display_num= self::DISPLAY_NUM;
        $this->_helper->viewRenderer('index');

        //初期表示共通情報セット
        $this->_setCommonView();
    }

    /** トップページを表示するアクション */
    public function pageviewAction () {

        //トップページの初期表示(新着ランキングデフォルト表示)
        $params['now_post_num'] = 0;
        $params['get_post_num'] = self::DISPLAY_NUM_INIT;

        $params['flg'] = "pageview";
        $top_pageview = $this->service('top','getTopInfo', $params);

        //画面へ表示
        $this->view->top_pageview= $top_pageview;
        $this->view->view_flg   =  "pageview";
        $this->view->display_numinit= self::DISPLAY_NUM_INIT;
        $this->view->display_num= self::DISPLAY_NUM;
        $this->_helper->viewRenderer('index');

        //初期表示共通情報セット
        $this->_setCommonView();
    }

    /** トップページを表示するアクション */
    public function followAction () {
        if ($this->login_status == 0) {
            //ログインしてない場合、トップに戻る
            $this->_redirect("/");
        }
        //トップページの初期表示(新着ランキングデフォルト表示)
        $params['now_post_num'] = 0;
        $params['get_post_num'] = self::DISPLAY_NUM_INIT;
        if ($this->user_info['user_id']) {
            $params['user_id'] =  $this->user_info['user_id'];
        }
        $params['flg'] = "follow";
        $top_follow = $this->service('top','getTopInfo', $params);

        //画面へ表示
        $this->view->top_follow= $top_follow;
        $this->view->view_flg   =  "follow";
        $this->view->display_numinit= self::DISPLAY_NUM_INIT;
        $this->view->display_num= self::DISPLAY_NUM;
        $this->_helper->viewRenderer('index');

        //初期表示共通情報セット
        $this->_setCommonView();
    }

    /** ランキング一覧「もっと見る」ajax取得アクション */
    public function ajaxrankingmoreAction () {

        //viewは読み込まない
        $this->_helper->viewRenderer->setNoRender();
        // パラメター取得
        //データ抽出limit順番
        $limitnum= $this->getRequest()->getParam('limitnum');
        $flg= $this->getRequest()->getParam('flg');
        //検索条件
        $posts['now_post_num'] = $limitnum;
        $posts['get_post_num'] = self::DISPLAY_NUM;
        if ($this->user_info['user_id']) {
            $posts['user_id'] =  $this->user_info['user_id'];
        }
        //該当件数のデータ抽出;
        if ( $flg == 'new' ) {
            $res = $this->service('top','getNewRankList', $posts);

            $tags = Utility::makeRankMoreReadTag($res);
        } else if ($flg == 'reguru') {
            $res = $this->service('top','getReguruRankList', $posts);
            $tags = Utility::makeRankMoreReadTag($res);
        } else if($flg == 'pv') {
            $res = $this->service('top','getPageviewRankList', $posts);
            $tags = Utility::makeRankMoreReadTag($res);
        } else if ($flg == 'timeline') {
            $tags = $this->service('top','getTimelineList', $posts);
            //$tags = $this->_getReadmoreTag($res);
        }
        //$tags = Utility::makeRankMoreReadTag($res);
        echo $tags;
    }



//▼▼▼▼▼▼▼▼チップス▼▼▼▼▼▼▼▼▼▼▼▼

    /**
     * 初期表示共通情報セット
     * @author: xiuhui yang
     * @param none
     *
     *
     * @return none
     *
    */
    private function _setCommonView()
    {
        //FB連携があったらフィードのチェックボックスを表示するためのフラグをアサイン
        $social_info = $this->service('oauth' , 'getSocialInfo' , $this->user_info['user_id']);
        $this->view->fb_connect_flg   =  $social_info['fb_connect_flg'];

        //ログイン行った店登録エラーがある場合、エラーメッセージ表示
        $beento_error  = $this->getRequest()->getParam('error');
        if ($beento_error == "1") {
            //ログイン状況チェック、未ログインの場合ログイン画面に飛ばす
            $this->_caseUnloginRedirect();
        }
    }

    /**
     * ajaxタグ作成
     * @author: xiuhui yang
     * @param none
     *
     *
     * @return none
     *
    */
    private function _getReadmoreTag($res)
    {
         $tags = "";
         if ($res) {
             for ($i=0; $i<count($res); $i++) {
                $ret = $res[$i];
                if (isset($ret['pref'])) {
                   $pref = $ret['pref'];
                } else{
                   $pref = "";
                }

                $divtag = "
                        <div class=\"thumbBox01\">
                        <div class=\"deco01\"></div>
                        <p class=\"thumArea01\">{$pref}</p>
                        <a href=\"/ranking/detail/id/{$ret['rank_id']}\" target=\"_blank\" class=\"thumRankLink\" title=\"{$ret['title']}\"><div class=\"space02\">
                            <p class=\"thumRankTitle\"><span class=\"thumRankTitleText\">{$ret['title']}</span><span class=\"rankDefiniteText\">Best3</span><span class=\"thumRankReaction\">{$ret['page_view']}view</span></p>
                            <ul class=\"thumPhoto01\">
                                    ";
                            $ultags = "";
                                for ($j=0; $j<count($ret['detail']); $j++) {
                                    $dret = $ret['detail'][$j];
                                    $litag ="<li>
                                                <span class=\"iconRank{$dret['rank_no']}\"></span>
                                                <img src=\"{$dret['photo']}\" alt=\"{$dret['shop_name']}\" />
                                            </li>";
                                    $ultags .= $litag;
                                }
                                  $divtag = $divtag.$ultags ."
                            </ul>
                            </div>
                        </a>
                        <div class=\"thumUser\">
                            <a href=\"/user/myranking/id/{$ret['user_id']}\" title=\"{$ret['user_name']}\">
                            <img src=\"{$ret['user_photo']}\" alt=\"{$ret['user_name']}\" />
                              {$ret['user_name']}
                            </a>
                        </div>
                        </div>
                        ";
                $tags .= $divtag;
            }
         }
        return $tags;
    }

}