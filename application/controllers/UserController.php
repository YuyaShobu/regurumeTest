<?php

/**
 *
 *
 * @package   UserController
 * @copyright 2013
 * @author    xiuhui yang
 *
 */



require_once (LIB_PATH . '/Social/Facebook/facebook.php');
require_once (LIB_PATH ."/Utility.php");

class UserController extends AbstractController {
    private $user_cominfo = "";
    Const DISPLAY_NUM_INIT =  6;//1ページ目表示件数(件)
    Const DISPLAY_NUM = 6;//1ページ表示件数(件)
    const CONST_VOTING_TYPE = '1'; // フルコース
    const CONST_VOTING_KIND = '1'; // いいね
    const CONST_BK_TYPE = '1'; // TOP3のブックマーク

    /** ユーザー編集画面 */
    public function indexAction () {
    	$this->_caseUnloginRedirect ();

    	$post_param = $this->getRequest()->getParams();

    	//ソーシャル認証時にすでに別アカウントを作成しているソーシャルアカウントは連携しないようにする
    	$this->view->user_already = "";
    	if (isset($post_param['error']) and ($post_param['error'] == 'useralready')) {
    		$this->view->user_already = 'あなたが今ログインしているfacebookユーザは既に今のアカウントとは別にリグルメにアカウントを作っています。';
    	}
    	//ユーザIDからユーザの情報を取得
        $res = $this->service('user','getUserDetail',$this->user_info['user_id']);

        //ソーシャル連携の有無を確認する
        $cannnot_social_denied_flg = 0;
        $social_info = $this->service('oauth' , 'getSocialInfo' , $this->user_info['user_id']);

        if ($res['password'] == '0' and $social_info['fb_login_flg'] == '1') {
        	$cannnot_social_denied_flg =1;
        }

        //もしfecabook連携がなかったら連携用URLを生成
        if ($social_info['fb_connect_flg'] == 0) {
	        /*
			 * facebook連携のためのURL生成
			 */
		    $utility = new Utility;
        	$config_path = $utility->_getSocialConnectConfigPath();
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
        }

        //$pref = $this->getPrefList();
        //生年月日プルダウン表示
        //生年月日の分割処理
        $args =  explode('-', $res['birthday']);
        if(count($args) == 3){
            $birthday["birthday_year"] = $args[0];
            $birthday["birthday_month"] = str_pad($args[1], 2, '0', STR_PAD_LEFT);
            $birthday["birthday_day"] = str_pad($args[2], 2, '0', STR_PAD_LEFT);
        } else {
            $birthday["birthday_year"] = "";
            $birthday["birthday_month"] = "";
            $birthday["birthday_day"] = "";
        }
        $birthday = $this->_makeBirthdayList($birthday);
        // YAMLドキュメントを読み込む
        //$pref = yaml_parse_file(DATA_PATH."/pref.yml");
        //$this->view->pref =  $pref;
        //都道府県初期表示
        $this->_setCommonView($res);
        $this->view->user = $res;
        $this->view->fb_connect_flg = $social_info['fb_connect_flg'];
        $this->view->cannnot_social_denied_flg =  $cannnot_social_denied_flg;
        $this->view->birthday = $birthday;
        //完了メッセージを表示する
        $complete_flg   = $this->getRequest()->getParam('flg');
        if ($complete_flg == '1') {
            $this->view->complete_msg = "変更した内容を保存しました。";
        }
    }


    /** ユーザー更新画面 */
    public function updateAction () {
        // 入力値取得
        $req = $this->getRequest();
        $posts = $req->getPost();
        //登録のバリデーション
        $post_param = $this->getRequest()->getParams();
        $validate = $this->validate('user','registValidate', $post_param);
        //パラメータ不正の場合
        if (isset($validate['error_flg'])) {
            //値を保持して入力画面へ飛ばす。
            $this->_buck_regist_page($validate);
        } else {

            //更新項目編集
            $inputData = array();
            $inputData['USER_ID']   = intval($req->getPost("user_id"));
            $inputData['USER_NAME'] = $req->getPost("user_name");
            $inputData['MAIL_ADDRESS'] = $req->getPost("mail_address");
            $inputData['GENDER'] = $req->getPost("gender");
            if ( $req->getPost("new_password") && $req->getPost("new_password") !="") {
                $inputData['PASSWORD'] = $req->getPost("new_password");
            }
            //$inputData['BIRTHDAY'] = $req->getPost("birthday");
            $birthday_year = $req->getPost("birthday_year");
            $birthday_month = $req->getPost("birthday_month");
            $birthday_day = $req->getPost("birthday_day");
            if ($birthday_year != '0' && $birthday_month != '0' && $birthday_day != '0' ) {
                $inputData['BIRTHDAY'] = $birthday_year.'-'.$birthday_month.'-'.$birthday_day;
            }
            $inputData['ADDRESS1'] = $req->getPost("address1");
            $inputData['ADDRESS2'] = $req->getPost("address2");
            $inputData['ADDRESS3'] = $req->getPost("address3");
            $inputData['SELF_INTRO'] = $req->getPost("self_intro");
            $inputData['BLOG_SITE'] = $req->getPost("blog_site");
            $inputData['FOLLOW_NOTICEFLAG'] = intval($req->getPost("follow_noticeflag"));
            $inputData['REGURU_NOTICEFLAG'] = intval($req->getPost("reguru_noticeflag"));
            $inputData['UPDATED_AT'] = date("Y-m-d H:i:s");
            //画像uploadされた場合、ファイル名作成してDB項目にセット
            //画像情報
            $upfile = $_FILES["photo"];
            if ( $upfile[ 'error' ] == UPLOAD_ERR_OK && is_uploaded_file( $upfile['tmp_name'] ) ) {

                //アップロードしたファイルの名称
                $name  = $upfile[ 'name' ];
                $file_name = pathinfo($name);
                //ファイル名をrenameする
                $new_name = $this->user_info['user_id'];
                $new_name .= ".";
                $new_name .= $file_name['extension'];
                $photo = $new_name;
                $inputData['PHOTO'] = $photo;
            }
            //更新処理
            $this->service('user','updateUser',$inputData);
            //DBに正常登録されたら、画像UPLOAD処理する
            //PCアップロード先のパス
            $updir  = ROOT_PATH."/img/pc/user";
            //モバイルアップロード先のパス
            $updir_sp  = ROOT_PATH."/img/sp/user";
            //アップロード
            Utility::uploadFile($updir,$updir_sp,$new_name,$upfile);
            $this->_redirect("/user/index/flg/1");
            //完了メッセージを表示する
            //$this->view->complete_msg = "登録完了しました。";
        }
    }


    /** 詳細ページ--マイランキング一覧*/
    public function myrankingAction () {

        $uid = $this->getRequest()->getParam('id');
        if ( $uid != "" ) {
            $this->_setCommonDetail($uid);
            if ($this->user_cominfo) {
                //マイランキングデータ一覧取得
                $params['now_post_num'] = 0;
                $params['get_post_num'] = self::DISPLAY_NUM_INIT;
                $params['user_id'] =  $uid;
                $myrank_list = $this->service('user','getMyRankList',$params);
                $this->view->rank_list= $myrank_list;
                $this->view->uid = $uid;
            } else {
                //urlに不正なユーザID入力した場合トップページに遷移する
                $this->_redirect("/index/index");
            }
        } else {
            $this->_redirect("/index/index");
        }
    }

    /** 詳細ページ--リグルランキング一覧*/
    public function regururankingAction () {

        $uid = $this->getRequest()->getParam('id');
        if ( $uid != "" ) {
            $this->_setCommonDetail($uid);
            if ($this->user_cominfo) {
                //マイランキングデータ一覧取得
                $params['now_post_num'] = 0;
                $params['get_post_num'] = self::DISPLAY_NUM_INIT;
                $params['user_id'] =  $uid;
                $myreguru_list = $this->service('user','getMyReguruRankList',$params);
                $this->view->rank_list= $myreguru_list;
                $this->view->uid = $uid;
            } else {
                //urlに不正なユーザID入力した場合トップページに遷移する
                $this->_redirect("/index/index");
            }
        } else {
            $this->_redirect("/index/index");
        }
    }

    /** 詳細ページ--行きたい店*/
    public function wanttoshopAction () {

        $uid = $this->getRequest()->getParam('id');
        if ( $uid != "" ) {
            //共通情報
            $this->_setCommonDetail($uid);
            if ($this->user_cominfo) {
                $params['now_post_num'] = 0;
                $params['get_post_num'] = self::DISPLAY_NUM_INIT;
                $params['user_id'] =  $uid;
                $shop_list = $this->service('user','getMyWantto',$params);
                $shop_lan_log_list = $this->service('user','getMyWanttolatlog',$params);
                $this->view->shop_lan_log_list= $shop_lan_log_list;
                $this->view->shop_list= $shop_list;
                $this->view->uid = $uid;
            } else {
                //urlに不正なユーザID入力した場合トップページに遷移する
                $this->_redirect("/index/index");
            }
        } else {
            $this->_redirect("/index/index");
        }
    }

    /** 詳細ページ--応援店*/
    public function oenshopAction () {

        $uid = $this->getRequest()->getParam('id');
        if ( $uid != "" ) {
            //共通情報
            $this->_setCommonDetail($uid);
            if ( $this->user_cominfo ) {
                $params['now_post_num'] = 0;
                $params['get_post_num'] = self::DISPLAY_NUM_INIT;
                $params['user_id'] =  $uid;
                $shop_list = $this->service('user','getMyOen',$params);
                $shop_lan_log_list = $this->service('user','getMyOenlatlog',$params);
                $this->view->shop_lan_log_list= $shop_lan_log_list;
                $this->view->shop_list= $shop_list;
                $this->view->uid = $uid;
            } else {
                //urlに不正なユーザID入力した場合トップページに遷移する
                $this->_redirect("/index/index");
            }
        } else {
            $this->_redirect("/index/index");
        }
    }

    /** 詳細ページ--行った店*/
    public function beentoshopAction () {

        $uid = $this->getRequest()->getParam('id');
        if ( $uid != "" ) {
            //共通情報
            $this->_setCommonDetail($uid);
            if ( $this->user_cominfo ) {
                $params['now_post_num'] = 0;
                $params['get_post_num'] = self::DISPLAY_NUM_INIT;
                $params['user_id'] =  $uid;
                $shop_list = $this->service('user','getMyBeeto',$params);
                $shop_lan_log_list = $this->service('user','getMyBeetolatlog',$params);
                $this->view->shop_list= $shop_list;
                $this->view->shop_lan_log_list= $shop_lan_log_list;
                $this->view->uid = $uid;
            } else{
                //urlに不正なユーザID入力した場合トップページに遷移する
                $this->_redirect("/index/index");
            }
        } else {
            $this->_redirect("/index/index");
        }
    }

    /** フォロー一覧ページ */
    public function followAction () {
        $uid = $this->getRequest()->getParam('id');
        if ( $uid != "" ) {
            //共通情報
            $this->_setCommonDetail($uid);
            if ( $this->user_cominfo ) {
                $param['user_id'] = $uid;
                $param['login_uid'] = $this->user_info['user_id'];
                $follow_list = $this->service('user','getFollowList',$param);
                $this->view->follow_list= $follow_list;
                $this->view->uid = $uid;
            } else {
                //urlに不正なユーザID入力した場合トップページに遷移する
                $this->_redirect("/index/index");
            }
        } else {
            $this->_redirect("/index/index");
        }
    }

    /** フォロワー一覧ページ */
    public function followerAction () {
        $uid = $this->getRequest()->getParam('id');
        if ( $uid != "" ) {
            $this->_setCommonDetail($uid);
            if ( $this->user_cominfo ) {
                $param['user_id'] = $uid;
                $param['login_uid'] = $this->user_info['user_id'];
            	$follower_list = $this->service('user','getFollowerList',$param);
                $this->view->follower_list= $follower_list;
                $this->view->uid = $uid;
            } else {
            //urlに不正なユーザID入力した場合トップページに遷移する
                $this->_redirect("/index/index");
            }
        } else {
            $this->_redirect("/index/index");
        }
    }

    /** 詳細ページ */
    public function detailAction () {

        $param['user_id'] = $this->getRequest()->getParam('id');
        if ( isset($param['user_id']) && $param['user_id'] !="" ) {
            // フルコース詳細情報取得
            $fc_info = $this->service('user','getUserFullcourse',$param);
            if ($fc_info) {
                // 「いいね」ボタン押せるかチェック
                for ( $i=0; $i<count($fc_info); $i++ ) {
                    $para = array();
                    $para['USER_ID'] = $this->user_info['user_id'];
                    $para['FC_ID'] = $fc_info[$i]['fc_id'];
                    $para['VOTING_TYPE'] = self::CONST_VOTING_TYPE;
                    $para['VOTING_KIND'] = self::CONST_VOTING_KIND;
                    // 存在チェック
                    $fc_info[$i]['v_flg'] = $this->service('voting','checkVotingExist',$para);
                }
                $this->view->fc_info =  $fc_info;
            }
            // TOP3情報取得
            $top3_list = $this->service('user','getUserTop3List',$param);
            if ($top3_list) {
                // 「bookmark」ボタン押せるかチェック
                foreach($top3_list as $key => $value) {
                    $keyName = $value['category_name'];
                    $arr = array();
                    $arr['USER_ID'] = $this->user_info['user_id'];
                    $arr['BK_TYPE'] = self::CONST_BK_TYPE;
                    $arr['CATEGORY_ID'] = $value['category_id'];
                    $arr['CREATE_USER_ID'] = $param['user_id'];
                    // 存在チェック
                    $top3_list[$key]['bk_flg'] = $this->service('categoryBookmark','checkCategoryBookmarkExist',$arr);
                }
                $this->view->top3_list =  $top3_list;
            }

            //フォローチェック
            $f_check_params['follower'] = $this->user_info['user_id'];
            $f_check_params['follow']   =  $param['user_id'];
            //フォローチェック
            $follow_flg = $this->service('user' , 'checkFollow' , $f_check_params);

            //このユーザをフォローしている人の数をカウントする
            $count_param['user_id'] = $param['user_id'];
            $follow_count = $this->service('user' , 'followCount' , $count_param);

            $this->view->user_id      = $this->getRequest()->getParam('id');
            $this->view->follow_flg   = $follow_flg;
            $this->view->follow_count = $follow_count;
        }
    }



	public function facebookconnectAction () {
		$facebook_access_error = $this->getRequest()->getParam('error');
		if ($facebook_access_error) {
			$this->_redirect("/user/index/error/1/");
		}

		$code = $this->getRequest()->getParam('code');
        /*
		 * facebookユーザ情報取得
		 */
	    $utility = new Utility;
        $config_path = $utility->_getSocialConnectConfigPath();
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
		$fb_user_id = $fb_user->id;
		$fb_name    = $fb_user->name;
		$fb_email    = $fb_user->email;

		//facebookからユーザー情報を取得
		if ($fb_user_id) {

			$param['fb_id'] = $fb_user_id;
			$fb_accsess_token = str_replace("access_token=" ,"",$access_token);
			$param['fb_accsess_token'] = $fb_accsess_token;
			$param['user_id'] = $this->user_info['user_id'];
			$param['fb_id'] = $fb_user_id;
			$param['fb_name'] = $fb_name;
			$param['mail_address'] = $fb_email;
			$res = $this->service('oauth' , 'checkAnotherFbAccount' , $param);

			//facebook連携
			if ($res != false) {
				if ($param['user_id'] != $res['oauth_id']) {
					//ユーザが複数いる場合は警告
					$this->_redirect("user/index/error/useralready/");
				}
			}

			$ret = $this->service('oauth' , 'connectFb' , $param);

		} else{
			//■いきなりこのアクションを叩かれたとき
			$this->_redirect("user/index/");
		}

		$this->_redirect("/user/index/");

	}

	public function retireAction () {
		$this->_caseUnloginRedirect();
	}

	public function retirenextAction () {
		$this->_caseUnloginRedirect();
		$this->view->user_id         = $this->user_info['user_id'];
		$this->view->mail_address    = $this->user_info['mail_address'];
		$this->view->address         = $this->user_info['user_name'];
		$this->view->retire_flg      = 0;
	}

	public function retirecompleteAction () {

		//直打ちで来られたらリダイレクト
		$retire_flg = $this->getRequest()->getParam('retire_flg');

		if ($retire_flg == null) {
			$this->_redirect("/login/index/");
		}
		$user_id    = $this->user_info['user_id'];
		$user_name  = $this->user_info['user_name'];
		$retire_reason = $this->getRequest()->getParam('retire_reason');



		//退会できなかったらリダイレクト
		$ret = $this->service('user', 'retire', $user_id);
		if ($retire_reason == true and $ret == true) {
			$params = array ('user_name' => $user_name, 'retire_reason' => $retire_reason);
			$ret = $this->_sendmail($params);
		}

		if ($ret == false) {
			$this->_redirect("/login/index/error/cannnotretire/");
		} else {
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
		}
	}

	public function couponAction () {

		$this->_caseUnloginRedirect();
		//直打ちで来られたらリダイレクト
		$user_id = $this->user_info['user_id'];
		if (!$user_id) {
			$this->_redirect("/login/index/");
		}
		$param = array('user_id' => $user_id);
		//クーポン情報（応援クーポン・常連クーポン・紹介クーポンを取得)
		$coupon_info = $this->service('user' , 'getCouponInfo' , $param);
		$keidoido_array = array();
		$z = 0;
		foreach($coupon_info as $c) {
			if(is_array($c) && count($c) > 0) {
				foreach($c as $i) {
					if(is_array($i) && count($i) > 0) {
						if(isset($i['latitude']) && isset($i['longitude'])) {
							if(isset($i['shop_name'])) {
								$keidoido_array[$z]['shop_name'] = $i['shop_name'];
							}
							$keidoido_array[$z]['latitude'] = $i['latitude'];
							$keidoido_array[$z]['longitude'] = $i['longitude'];
							$z++;
						}
					}
				}
			}
		}

		$this->view->keidoido = $keidoido_array;
		$this->view->coupon_info = $coupon_info;

	}
	public function coupondetailAction () {

		$this->_caseUnloginRedirect();
		//直打ちで来られたらリダイレクト
		$user_id = $this->user_info['user_id'];
		if (!$user_id) {
			$this->_redirect("/login/index/");
		}
		$coupon_id = $this->getRequest()->getParam('coupon_id');
		$invite_coupon_id = $this->getRequest()->getParam('invite_coupon_id');
		if ($coupon_id == null) {
			//自分がクーポンを発行できるクーポンの詳細なので自分をフォローしてくれているを取得する
			$follower_list = $this->service('user','getFollowerList',$user_id);
			$coupon_id = $invite_coupon_id;
		} else {
			$follower_list = null;
		}


		//クーポンIDからクーポン詳細を取得
		$param = array('user_id' => $user_id);
		$coupon_info = $this->service('user' , 'getCouponInfo' , $param);
		$keidoido_array     = array();
		$coupon_detail_info = array();
		$z = 0;
		foreach($coupon_info as $c) {
			if(is_array($c) && count($c) > 0) {
				foreach($c as $i) {
					if(is_array($i) && count($i) > 0) {
						if(isset($i['latitude']) && isset($i['longitude']) && $i['coupon_id'] == $coupon_id) {
							if(isset($i['shop_name'])) {
								$keidoido_array[$z]['shop_name'] = $i['shop_name'];
							}
							$keidoido_array[$z]['latitude']     = $i['latitude'];
							$keidoido_array[$z]['longitude']    = $i['longitude'];
							$coupon_detail_info['latitude']     = $i['latitude'];
							$coupon_detail_info['coupon_id']     = $i['coupon_id'];
							$coupon_detail_info['longitude']    = $i['longitude'];
							$coupon_detail_info['shop_name']    = $i['shop_name'];
							$coupon_detail_info['shop_id']      = $i['shop_id'];
							$coupon_detail_info['title']        = $i['title'];
							$coupon_detail_info['coupon']       = $i['coupon'];
							$coupon_detail_info['public_start'] = $i['public_start'];
							$coupon_detail_info['public_end']   = $i['public_end'];
							$z++;
						}
					}
				}
			}
		}
		$this->view->coupon_detail_info = $coupon_detail_info;
		$this->view->keidoido = $keidoido_array;
		$this->view->follower_list = $follower_list;

	}
	public function couponpublishAction () {

		$this->_caseUnloginRedirect();
		//直打ちで来られたらリダイレクト
		$user_id = $this->user_info['user_id'];
		if (!$user_id) {
			$this->_redirect("/login/index/");
		}
		$coupon_id = $this->getRequest()->getParam('coupon_id');

		//このIDはregular_customer_couponテーブルのinvitingに入ります。
		$follower_id = $this->getRequest()->getParam('follower');

		if ($coupon_id == null) {
			$this->_redirect("/user/coupon/");
		}
		if ($follower_id == null) {
			$this->_redirect("/user/coupondetail/coupon_id/".$coupon_id."/noselect/1/");
		}
		//クーポンIDからクーポン詳細を取得
		$param = array('user_id' => $user_id , 'coupon_id' => $coupon_id, 'inviting' => $follower_id);
		$coupon_info = $this->service('coupon' , 'registInvitingCoupon' , $param);


	}
    /** ユーザフォロー */
    public function ajaxfollowAction () {
        $ret = true;
        //viewは読み込まない
        $this->_helper->viewRenderer->setNoRender();
        // パラメター取得
        $user_id = $this->user_info['user_id'];
        $follow_user_id = $this->getRequest()->getParam('f_user_id');
        $btname = $this->getRequest()->getParam('btname');
        $inputData = array();
        if( $user_id != "" && $follow_user_id != "" ){
            $inputData['user_id'] = $user_id;
            $inputData['follow_user_id'] = $follow_user_id;
        }
        // データ論理削除する
        $ret = $this->service('user','follow',$inputData);
        $bttag="";

        if ($ret != false){
        	if ($btname == "followlist") {
        		$bttag = "<button class=\"btn btnM\" id=\"bt_followlist\" type=\"button\" onclick=\"ajax_follow('{$btname}','/user/ajaxunfollow/',{$follow_user_id});\">フォロー解除</button>";
        	} else {
                $bttag = "<button class=\"btn btnM\" id=\"bt_follow\" type=\"button\" onclick=\"ajax_follow('{$btname}','/user/ajaxunfollow/',{$follow_user_id});\">フォロー解除</button>";
        	}
        }
        echo $bttag;
    }

    /** ユーザフォロー解除 */
    public function ajaxunfollowAction () {
        $ret = true;
        //viewは読み込まない
        $this->_helper->viewRenderer->setNoRender();
        // パラメター取得
        $user_id = $this->user_info['user_id'];
        $follow_user_id = $this->getRequest()->getParam('f_user_id');
        $btname = $this->getRequest()->getParam('btname');
        $inputData = array();
        if( $user_id != "" && $follow_user_id != "" ){
            $inputData['user_id'] = $user_id;
            $inputData['follow_user_id'] = $follow_user_id;
        }
        // データ論理削除する
        $ret = $this->service('user','unfollow',$inputData);
        $bttag="";

        if ($ret != false){
            if ($btname == "followlist") {
        	   $bttag = "<button class=\"btn btnM\" id=\"bt_followlist\" type=\"button\" onclick=\"ajax_follow('{$btname}','/user/ajaxfollow/',{$follow_user_id});\">フォローする</button>";
            } else {
                $bttag = "<button class=\"btn btnM\" id=\"bt_follow\" type=\"button\" onclick=\"ajax_follow('{$btname}','/user/ajaxfollow/',{$follow_user_id});\">フォローする</button>";
            }
        }
        echo $bttag;
    }

    /** 画像削除処理 */
    public function ajaxdelphotoAction () {
        //viewは読み込まない
        $this->_helper->viewRenderer->setNoRender();
        $photo = $this->getRequest()->getParam('photo');
        // パラメター取得
        $user_id = $this->user_info['user_id'];
        $inputData = array();
        if( $user_id != ""){
            $inputData['USER_ID'] = $user_id;
            $inputData['PHOTO'] = '';
            $inputData['UPDATED_AT'] = date("Y-m-d H:i:s");
            //更新処理
            $ret = $this->service('user','updateUser',$inputData);
            if ($ret) {
                //ファイルサーバーの画像削除する
                //PCアップロード先のパス
                $updir  = ROOT_PATH."/img/pc/user".$photo;
                //モバイルアップロード先のパス
                $updir_sp  = ROOT_PATH."/img/sp/user".$photo;
                Utility::deleteUpFile($updir);
                Utility::deleteUpFile($updir_sp);
                $encode = json_encode($ret);
                echo $encode;
                exit;
            }
        }
    }

    /** ランキング一覧「もっと見る」ajax取得アクション */
    public function ajaxrankingmoreAction () {

        //viewは読み込まない
        $this->_helper->viewRenderer->setNoRender();
        // パラメター取得
        //データ抽出limit順番
        $limitnum= $this->getRequest()->getParam('limitnum');
        $uid = $this->getRequest()->getParam('uid');
        $flg = $this->getRequest()->getParam('flg');

        //検索条件
        if ($limitnum !="" && $uid !="") {
            $posts['now_post_num'] = $limitnum;
            $posts['get_post_num'] = self::DISPLAY_NUM;
            $posts['user_id'] =  $uid;
            //該当件数のデータ抽出;
            if ($flg == 'myranking') {
                $myrank_list = $this->service('user','getMyRankList',$posts);
            } else {
                $myrank_list = $this->service('user','getMyReguruRankList',$posts);
            }
            //タグ作成
            $tags = Utility::makeRankMoreReadTag($myrank_list);
            echo $tags;
        }
    }

    /** ショップ一覧「もっと見る」ajax取得アクション */
    public function ajaxshopmoreAction () {
        //viewは読み込まない
        $this->_helper->viewRenderer->setNoRender();
        // パラメター取得
        //データ抽出limit順番
        $limitnum= $this->getRequest()->getParam('limitnum');
        $uid = $this->getRequest()->getParam('uid');
        $flg = $this->getRequest()->getParam('flg');

        //検索条件
        if ($limitnum !="" && $uid !="") {
            $posts['now_post_num'] = $limitnum;
            $posts['get_post_num'] = self::DISPLAY_NUM;
            $posts['user_id'] =  $uid;
            //該当件数のデータ抽出;
            if ($flg == 'mywantto') {
                $shop_list = $this->service('user','getMyWantto',$posts);
                //タグ作成
                $tags = Utility::makeShopMoreReadTag($shop_list);
            } elseif ($flg == 'myoen') {
                $shop_list = $this->service('user','getMyOen',$posts);
                //タグ作成
                $tags = Utility::makeShopMoreReadTag($shop_list);
            } else{
                $shop_list = $this->service('user','getMyBeeto',$posts);
                //タグ作成
                $tags = Utility::makeBeentoShopMoreReadTag($shop_list,$this->user_info['user_id']);
            }
            echo $tags;
        }

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
        $birthday = $this->_makeBirthdayList($birthday);
        $this->view->birthday = $birthday;

        //都道府県プルダウンにセットする
        //$pref = yaml_parse_file(DATA_PATH."/pref.yml");
        //$this->view->pref =  $pref;
        $this->_setCommonView($validate);
        //入力画面に戻って、エラーメッセージを表示する
        $this->_helper->viewRenderer('index');
    }

    /**
     * 検索画面初期表示共通情報セット
     * @author: xiuhui yang
     * @param array $res
     *
     *
     * @return none
     *
    */
    private function _setCommonView($res)
    {
        //都道府県マスタ取得
        $pref = $this->service('ranking','getPrefList', $param="");
        $this->view->pref= $pref;
        if ($res['address1'] !="") {
            $param['pref_code'] = $res['address1'];
            $city_list = $this->service('ranking','getCityList', $param);
            if ($city_list){
                $this->view->city= $city_list;
            }
        }
    }

    /**
     * ユーザー各詳細ページ共通情報セット
     * @author: xiuhui yang
     * @param none
     *
     *
     * @return none
     *
    */
    private function _setCommonDetail($uid)
    {
        $param['user_id'] = $uid;
        if ( isset($param['user_id']) && $param['user_id'] !="" ) {
            //フォローチェック
            $f_check_params['follower'] = $this->user_info['user_id'];
            $f_check_params['follow']   =  $param['user_id'];
            //フォローチェック
            $follow_flg = $this->service('user' , 'checkFollow' , $f_check_params);
            $this->view->follow_flg   = $follow_flg;
            //該当ユーザー詳細ページの共通情報取得
            $this->user_cominfo = $this->service('user','getUserComInfoByUserid', $param);
            $this->view->user_cominfo   =  $this->user_cominfo;
            $this->view->display_numinit= self::DISPLAY_NUM_INIT;
            $this->view->display_num= self::DISPLAY_NUM;
            if ( $this->user_cominfo['user_detail']['user_name'] !="" ) {
            	//パンくずにユーザー名を追加
                $user_name = $this->user_cominfo['user_detail']['user_name'] .'さんの';
                $this->_getPankuzuList($user_name);
            }
        }
    }

    //生年月日プルダウンにセット処理
    private function _makeBirthdayList($results)
    {
        $nowyear = date("Y");
        $nextyear = $nowyear + 1;
        //年候補
        for ($i= 1900; $i<$nextyear; $i++) {
            $results["yearlist"][$i-1900]=$i;
        }
        //月候補
        for ($m=1;$m<=12;$m++) {
            $results["monthlist"][$m-1]=$m;
        }
        //日候補
        for ($j=1;$j<=31;$j++) {
            $results["daylist"][$j-1]=$j;
        }
        //年月日プルダウン初期値

        if($results["birthday_year"] != "" && $results["birthday_month"] != "" && $results["birthday_day"] != "")
        {
            $results['selyear'] = $results["birthday_year"];
            $results['selmonth'] = $results["birthday_month"];
            $results['selday'] = $results["birthday_day"];
        }

        //結果を返す
        return $results;
    }


    private function _sendmail($params) {
		mb_language("ja");
		// 内部文字エンコードを設定する
		mb_internal_encoding("UTF-8");
		$content = $params['user_name']."さんから「".$params['retire_reason']."」との理由で退会";
		$mailto  = "jazttijaztti@yahoo.co.jp";
		$subject = "リグルメで退会者がでたけど気にしなくていいよ。";
		$mailfrom="From:" .mb_encode_mimeheader("リグルメ事務局") ."<info@regurume.com>";
		$ret = mb_send_mail($mailto,$subject,$content,$mailfrom);
		return $ret;
    }
}