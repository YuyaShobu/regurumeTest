<?php

class ShopController extends AbstractController {
    Const DISPLAY_NUM_INIT =  3;//1ページ目表示件数(件)
    Const DISPLAY_NUM = 2;//1ページ表示件数(件)

    /** トップページを表示するアクション */
    public function indexAction () {
        //$svModel = new sampleService();
        //$res = $svModel-> getSampleDate();
        $res = $this->service('shop','getShopList',1);
        //var_dump($res);exit();
        $this->view->str = $res['name'];
    }

    public function getgenreajaxAction () {
    	$parent_id = $this->_request->getPost('id');
    	if(!is_null($parent_id) && !empty($parent_id)) {
    		$genre_ko = $this->service('shop','getGenreByGenreId',array('genre_parent'=>(int)$parent_id));
    		if($genre_ko === false) {
    			echo false;
    		}
    		else {
    			echo json_encode($genre_ko);
    		}
    		exit;
    	}
    	else {
    		echo false;
    		exit;
    	}
    }

    /** 新規店舗登録を表示するアクション */
    public function registAction () {

        //ログイン状況チェック、未ログインの場合ログイン画面に飛ばす
        $this->_caseUnloginRedirect();

        //食べログのＵＲＬを張ると反映されますは管理者しか使えない対応
        $admin_flg = $this->_check_amdin();

        $post_param = $this->getRequest()->getParams();

        $city_list = $this->service('ranking','getPrefList',array());
        $this->view->city_list = $city_list;

        $genre_parent = $this->service('shop','getGenreParent',array());
        $this->view->genre_parent = $genre_parent;

        //確認画面から戻るが押された場合
        if (isset($post_param['backflg']) and $post_param['backflg']=='1') {

            //確認画面から戻るボタンが押された場合
            $this->view->latitude    = $post_param['latitude'];
            $this->view->longitude   = $post_param['longitude'];
            $this->view->error = "";

            //入力した値を保持
            $this->view->shop_name         = $post_param['shop_name'];
            $this->view->address           = $post_param['address'];
            $this->view->shop_url          = $post_param['shop_url'];
            $this->view->business_day      = $post_param['business_day'];
            $this->view->regular_holiday   = $post_param['regular_holiday'];
            //エラーメッセージの挿入
            $this->view->errorflg = false;

        } else {
            //新規登録画面
            $param['user_id'] = $this->user_info['user_id'];
            $address_info = $this->service('user','getUserAddressLatLon',$param);

            $lat = $address_info['latitude'];
            $lon = $address_info['longitude'];
            $this->view->latitude = $lat;
            $this->view->longitude = $lon;
            $this->view->error = "";

            //入力した値を保持
            $this->view->shop_name         = "";
            $this->view->address           = "";
            $this->view->shop_url          = "";
            $this->view->business_day      = "";
            $this->view->regular_holiday  = "";
            //エラーメッセージの挿入
            $this->view->errorflg = false;
        }
        $this->view->admin_flg = $admin_flg;
    }

    /** 新規店舗登録を確認するアクション */
    public function conformAction () {
        $param['user_id'] = $this->user_info['user_id'];
        $post_param = $this->getRequest()->getParams();

        //都道府県リスト
 		$city_list = $this->service('ranking','getPrefList',array());
        $this->view->city_list = $city_list;

        //ジャンル一覧
        $genre_parent = $this->service('shop','getGenreParent',array());
        $this->view->genre_parent = $genre_parent;
        //新規店舗登録のバリデーション
        $validate = $this->validate('shop','registValidate', $post_param);

        //パラメータ不正の場合
        if (isset($validate['error_flg'])) {
            //値を保持して入力画面へ飛ばす。
            $this->_buck_regist_page($validate);
        } else {
            preg_match('/(東京都|北海道|(?:京都|大阪)府|.{6,9}県)((?:四日市|廿日市|野々市|かすみがうら|つくばみらい|いちき串木野)市|(?:杵島郡大町|余市郡余市|高市郡高取)町|.{3,12}市.{3,12}区|.{3,9}区|.{3,15}市(?=.*市)|.{3,15}市|.{6,27}町(?=.*町)|.{6,27}町|.{9,24}村(?=.*村)|.{9,24}村)(.*)/', $post_param['address'], $matches3);
            if (count($matches3) > 0 ) {
                $pref = !empty($matches3[1]) ? $matches3[1] : '';
                // 都道府県YAMLドキュメントを読み込むして、pref_codeを求める
                $pref_list = yaml_parse_file(DATA_PATH."/pref.yml");
                foreach ($pref_list as $key=>$value) {
                    if($pref == $value ) {
                        $pref_code = $key;
                        $this->view->pref_code = $pref_code;
                    }
                }
                //city_codeを求める
                if ( !empty($matches3[2]) ) {
                    $param['city'] = $matches3[2];
                    $city_code = $this->service('shop', 'getCityCode' , $param);
                    $this->view->city_code = $city_code;
                }

            }
            if(isset($post_param['genre1']) && $post_param['genre1'] > 0) {
            	$genre1 = '';
            	if(isset($post_param['genre1-ko']) && $post_param['genre1-ko'] > 0) {
            		$genre1 = $post_param['genre1-ko'];
            	}
            	else {
            		$genre1 = $post_param['genre1'];
            	}
            	$this->view->genre1_value = $this->service('shop','getGenreById',array('genre_id'=>$genre1));
            	$this->view->genre1_key = $genre1;
            }
            if(isset($post_param['genre2']) && $post_param['genre2'] > 0) {
            	$genre2 = '';
            	if(isset($post_param['genre2-ko']) && $post_param['genre2-ko'] > 0) {
            		$genre2 = $post_param['genre2-ko'];
            	}
            	else {
            		$genre2 = $post_param['genre2'];
            	}
            	$this->view->genre2_value = $this->service('shop','getGenreById',array('genre_id'=>$genre2));
            	$this->view->genre2_key = $genre2;
            }
            if(isset($post_param['genre3']) && $post_param['genre3'] > 0) {
            	$genre3 = '';
            	if(isset($post_param['genre3-ko']) && $post_param['genre3-ko'] > 0) {
            		$genre3 = $post_param['genre3-ko'];
            	}
            	else {
            		$genre3 = $post_param['genre3'];
            	}
            	$this->view->genre3_value = $this->service('shop','getGenreById',array('genre_id'=>$genre3));
            	$this->view->genre3_key = $genre3;
            }

            //パラメータ正常だった場合
            //確認画面表示
            $this->view->latitude          = $validate['latitude'];
            $this->view->longitude         = $validate['longitude'];
            $this->view->shop_name         = $validate['shop_name'];
            $this->view->address           = $validate['address'];
            $this->view->shop_url          = $validate['shop_url'];
            $this->view->business_day      = $validate['business_day'];
            $this->view->regular_holiday   = $validate['regular_holiday'];
            $this->view->pref_code         = $post_param['pref'];
            $this->view->pref_value        = $this->service('shop','getPrefById',array('pref_code'=>$post_param['pref']));
            $this->view->city_code         = $post_param['city'];
            $this->view->city_value        = $this->service('shop','getCityById',array('city_code'=>$post_param['city']));
        }

    }


    /** 新規店舗登録を確認するアクション */
    public function completeAction () {


        $param['user_id'] = $this->user_info['user_id'];
        $post_param = $this->getRequest()->getParams();
        //新規店舗登録のバリデーション

        $validate = $this->validate('shop','registValidate', $post_param);
        //パラメータ不正の場合
        if (isset($validate['error_flg'])) {


        } else {

            //登録
            $validate['pref_code'] = $post_param['pref_code'];
            $validate['city_code'] = $post_param['city_code'];
            $validate['genre1']    = $post_param['genre1'];
            $validate['genre2']    = $post_param['genre2'];
            $validate['genre3']    = $post_param['genre3'];
            //$ret = $this->service('shop', 'registNewShopInfo' , $post_param);
            $ret = $this->service('shop', 'registNewShopInfo' , $validate);
            //登録失敗の場合入浴画面に飛ばす
            if ($ret == false) {
                $this->_buck_regist_page($validate);
            }
        }

    }

    public function detailAction () {
        // パラメター取得
        $shop_id = $this->getRequest()->getParam('id');
        if (!empty($shop_id)) {
            //詳細ページ開かれた場合、ページビューテーブルに登録
            $this->service('shop','insertUpdateShopPageview',$shop_id);
            //詳細情報取得
            $res = $this->service('shop','getShopDetail',$shop_id);
            if ($res) {

                //ランクインされたランキング情報取得
                $param['now_post_num'] = 0;
                $param['get_post_num'] = self::DISPLAY_NUM_INIT;
                $param['shop_id'] = $shop_id;
                $res['shop_ranking_list'] = $this->service('shop','getRankingFromShopid',$param);

                //行ったユーザのコメント情報取得
                $res['shop_beento_list'] = $this->service('shop','getBeentoUserCommentList',$param);
                //みんな投稿画像表示/img/pc/shopフォルダ画像あったら表示
                //$photolist = $this->_getPhotoList($shop_id);
                //$res['photo'] = $photolist;

                // その他各種情報取得
                $para['user_id'] = $this->user_info['user_id'];
                $para['shop_id'] = $shop_id;
                $para['voting_kind'] = Utility::CONST_VALUE_VOTING_BUTTON_WANTTO;
                $ret = $this->service('shop','getShopOtherInfo',$para);
                $res = array_merge($res,$ret);

                //TDKにタイトルを追加
                $title = $res['shop_name'].'｜'.$res['address'];
            	$this->_getTdk($title);

                //パンくずにユーザー名を追加
                $this->_getPankuzuList($res['shop_name']);

                $this->view->shop = $res;
                $this->view->wantto_list = $res['wantto_list'];
                $this->view->beento_list = $res['beento_list'];
                $this->view->oen_list = $res['oen_list'];
                $this->view->latitude = $res['latitude'];
                $this->view->longitude = $res['longitude'];
                $this->view->display_numinit= self::DISPLAY_NUM_INIT;
                $this->view->display_num= self::DISPLAY_NUM;
                $this->view->shop_id = $shop_id;
            } else {
               //データがない場合トップページに遷移する
               $this->_redirect("/index/index");
            }
        } else {
           //パラメタがない場合トップページに遷移する
           $this->_redirect("/index/index");
        }
    }

    public function ajaxgetshopnameAction() {
        $param = $this->_request->getParams();
        if(!empty($param['shop_name'])) {
            $rs = $this->service('shop','getShopInfoByShopName',array('search'=>$param['shop_name']));
            echo json_encode($rs);
            exit;
        }
        else if(!empty($param['shop_id'])) {
            $rs = $this->service('shop','getShopInfoByShopId',array('shop_id'=>(int)$param['shop_id']));
            echo json_encode($rs);
            exit;
        }
        echo false;
        exit;
    }

    public function ajaxgetshoplistfromshopnameAction () {
        //viewは読み込まない
        $this->_helper->viewRenderer->setNoRender();

        // パラメター取得
        $shop_name = $this->getRequest()->getParam('shopname');
        $pref = $this->getRequest()->getParam('pref');
        $param['shopname'] = htmlspecialchars($shop_name);
        $param['pref'] = $pref;

        $res = $this->service('shop','getShopListFromPrefShopname', $param);
         $shoplisttag = false;
         if ($res != false){
            $shoplisttag = "<select id=\"shop_id\" name=\"shop_id\"><option selected=\"true\" value=\"-1\">▼もしかしてこのお店？▼</option>";
            foreach ($res as $key => $val){
                $shoplisttag .= "<option value=\"".$val["shop_id"]."\">".$val["shop_name"]."　" .$val["address"]."</option>";
            }
            $shoplisttag .= "<option value=\"shopregist\">お店を登録する場合はこちら</option>";
            $shoplisttag .= "</select><br>";
         }else{
            $shoplisttag = false;
         }

        echo $shoplisttag;
    }

    public function ajaxgetshoplistAction () {
        //viewは読み込まない
        $this->_helper->viewRenderer->setNoRender();

        // パラメター取得
        $shop_name = $this->getRequest()->getParam('shopname');
        $param['shopname'] = htmlspecialchars($shop_name);

        $res = $this->service('shop','getShopListFromShopname', $param);
         $shoplisttag="";
         if ($res != false){
            $shoplisttag = "<select id=\"searchshop\" name=\"shop_id\" onClick=\"SelectChange();\" value=\"0_0_0\"><option selected=\"true\">▼もしかしてこのお店？▼</option>";
            foreach ($res as $key => $val){
                $shoplisttag .= "<option value=\"".$val["shop_id"]."_".$val["latitude"]."_".$val["longitude"]."\">".$val["shop_name"]."</option>";
            }
            $shoplisttag .= "</select><br>";
         }else{
            $shoplisttag = "見つかりません";
         }

        echo $shoplisttag;
    }

    public function ajaxgetshoplistfromlatlonAction () {
        //viewは読み込まない
        $this->_helper->viewRenderer->setNoRender();
        // パラメター取得
        $latitude  = $this->getRequest()->getParam('latitude');
        $longitude = $this->getRequest()->getParam('longitude');
        //$ctg     = $this->getRequest()->getParam('ctg');
        $param['latitude']  = $latitude;
        $param['longitude'] = $longitude;
        //$param['ctg']     = $ctg;
        if (!empty($param['latitude'])) {
            $res = $this->service('shop','getShopListFromLatLon', $param);
            $shoplisttag="";
            if ($res != false){
                $shoplisttag = "<ul>";
                foreach ($res as $key => $val){
                    $shoplisttag .= "<li>shopid=".$val['shop_id']."<a href=\"".$val['shop_url']."\">".$val['shop_name']."</a></li>";
                }
                $shoplisttag .= "</ul>";
            }else{
                $shoplisttag = "見つかりません";
            }
        } else {
            $shoplisttag = "見つかりませんでした。";
        }

        echo $shoplisttag;
    }


    /** ランキング一覧「もっと見る」ajax取得アクション */
    public function ajaxrankingmoreAction () {

        //viewは読み込まない
        $this->_helper->viewRenderer->setNoRender();
        // パラメター取得
        //データ抽出limit順番
        $limitnum= $this->getRequest()->getParam('limitnum');
        $shop_id= $this->getRequest()->getParam('shop_id');
        //検索条件
        $where['shop_id'] = $shop_id;
        $where['now_post_num'] = $limitnum;
        $where['get_post_num'] = self::DISPLAY_NUM;
        //該当件数のデータ抽出;
        $res = $this->service('shop','getRankingFromShopid',$where);
        if ( $res ) {
            $tags = $this->_getReadmoreTag($res);
            echo $tags;
        } else {
            echo false;
        }
    }


    /** 行ったユーザーコメント「もっと見る」ajax取得アクション */
    public function ajaxbeentocommentmoreAction() {

        //viewは読み込まない
        $this->_helper->viewRenderer->setNoRender();
        // パラメター取得
        //データ抽出limit順番
        $limitnum= $this->getRequest()->getParam('limitnum');
        $shop_id= $this->getRequest()->getParam('shop_id');
        //検索条件
        $where['shop_id'] = $shop_id;
        $where['now_post_num'] = $limitnum;
        $where['get_post_num'] = self::DISPLAY_NUM;
        //該当件数のデータ抽出;
        $res = $this->service('shop','getBeentoUserCommentList',$where);
        if ( $res ) {
            $tags = $this->_getReadmoreBeentoCommentTag($res,$this->user_info['user_id']);
            echo $tags;
        } else {
            echo false;
        }
    }

    /** 新規店舗登録画面で食べログから持ってくるためのajax取得アクション */
    public function ajaxgettabeloginfoAction() {

        //viewは読み込まない
        $this->_helper->viewRenderer->setNoRender();
        // パラメター取得
		$message = 'none';
		$pars = $this->_request->getParams();
		$html = file_get_contents($pars['url']);

		$shop_name = $region = $locality = $address = $lat = $lng = $homepage = '';
		$genre = array();
		if(strpos($pars['url'],'tabelog') > 0) {
			preg_match("/<meta property=\"mixi:title\" content=\"(.*)\" \/>/",$html,$out);
			if(isset($out[1])) {
				$temp = strip_tags($out[1]);
				$len = strpos($temp,'(');
				$shop_name = trim(substr($temp,0,$len));
			}
			preg_match("/<span property=\"v:region\"><a[^>]+>[^>]+a><\/span>/",$html,$out);
			if(isset($out[0])) {
				$region = trim(strip_tags($out[0]));
			}
			preg_match("/<span property=\"v:locality\"><a[^>]+>[^>]+a>/",$html,$out);
			if(isset($out[0])) {
				$locality = trim(strip_tags($out[0]));
			}
			preg_match("/<p rel=\"v:addr\">(.*)<\/p>/",$html,$out);
			if(isset($out[1])) {
				$address = trim(strip_tags($out[1]));
			}
			preg_match('/<a[^>]+rel="nofollow"[^>]+>[^>]+a>/u',$html,$out);
			if(isset($out[0])) {
				$homepage = trim(strip_tags($out[0]));
			}
			preg_match_all("/<span property=\"v:category\">[^(<\/span>)]+<\/span>/", $html,$out);
			if(isset($out[0]) && count($out[0] > 0)) {
				foreach($out[0] as $o) {
					$genre[] = trim(strip_tags($o));
				}
			}
			preg_match("/<div id=\"for_print_pop\" data-url=\"(.*)\" data-button.*<\/div>/",$html,$out);
			if(isset($out[1])) {
				$temp = trim(strip_tags($out[1]));
				$url = parse_url($temp);
				$url_pare_array = explode('&amp;',$url['query']);
				if(!is_array($url_pare_array)) {
					$url_pare_array = explode('&',$url['query']);
				}
				foreach($url_pare_array as $u) {
					$u_array = explode('=',$u);
					if(is_array($u_array) && count($u_array)>0) {
						if($u_array[0] == 'lat') {
							$lat = $u_array[1];
						}
						else if($u_array[0] == 'lng') {
							$lng = $u_array[1];
						}
					}
				}
			}
		}
		$ret = array('shop_name' => $shop_name , 'region'=>$region , 'locality'=>$locality , 'lat' => $lat , 'lng' => $lng, 'address' => $address , 'homepage' => $homepage , 'genre' => $genre);
		$ret =  json_encode($ret);
		echo $ret;
    }

    public function genreajaxAction() {
    	$value = $this->getRequest()->getParam('value');
		$genre_arr = explode('|',trim($value,'|'));
		$out_rentrn = array();
		foreach($genre_arr as $key=>$a) {
			$genre_return = $this->service('shop','getGenreByValue',array('value'=>$a));
			if($genre_return !== false) {
				$out_rentrn[$key]['oya'] = (int)$genre_return['genre_id'];
				if(strpos($genre_return['genre_id'],'_') > 0) {
					$out_rentrn[$key]['ko'] = $genre_return['genre_id'];
				}
				else {
					$out_rentrn[$key]['ko'] = false;
				}
			}
			else {
				$out_rentrn[$key]['oya'] = 20;
				$out_rentrn[$key]['ko'] = false;
			}
		}
		echo json_encode($out_rentrn);
		exit;
    }

    //▼▼▼▼▼▼▼▼チップス▼▼▼▼▼▼▼▼▼▼▼▼

    private function _buck_regist_page($validate) {
        //緯度経度の入力が無い場合はもう一度プロフィールの位置を表示してあげる
        if ($validate['latitude'] and $validate['longitude']) {
            $lat = $validate['latitude'];
            $lon = $validate['longitude'];
        } else {
            $address_info = $this->service('user','getUserAddressLatLon',$param);
            $lat = $address_info['latitude'];
            $lon = $address_info['longitude'];
        }
        $admin_flg = $this->_check_amdin();

        //入力した値を保持
        $this->view->shop_name         = $validate['shop_name'];
        $this->view->address           = $validate['address'];
        $this->view->shop_url          = $validate['shop_url'];
        $this->view->business_day      = $validate['business_day'];
        $this->view->regular_holiday   = $validate['regular_holiday'];

        //エラーメッセージの挿入
        $this->view->error  = $validate['error_message'];
        $this->view->errorflg = $validate['error_flg'];

        //緯度経度を格納
        $this->view->latitude = $lat;
        $this->view->longitude = $lon;

        $this->view->admin_flg = $admin_flg;
        //入力テンプレートをレンダリング
        $this->render('regist');
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
                $date = date('Y年n月j日', strtotime($res[$i]['created_at']));
                $tag = "
                        <dt>
                            <a href=\"/ranking/detail/id/{$res[$i]['rank_id']}\">
                            <img src=\"{$res[$i]['photo']}\" alt=\"\" title=\"{$res[$i]['title']}\" />
                            </a>
                        </dt>
                        <dd>
                            <p class=\"rankTitle\">
                                <a href=\"/ranking/detail/id/{$res[$i]['rank_id']}\">{$res[$i]['title']}</a>
                            </p>
                            <div>$date  {$res[$i]['page_view']}view｜<img src=\"{$res[$i]['user_photo']}\" alt=\"\" width=\"15\" height=\"15\" title=\"{$res[$i]['user_name']}\" />
                                <a href=\"/user/myranking/id/{$res[$i]['user_id']}\">{$res[$i]['user_name']}</a>
                            </div>
                        </dd>
                        ";
                $tags .= $tag;
            }
         }
        return $tags;
    }

    /**
     * ajaxタグ作成（行ったユーザーコメント）
     * @author: xiuhui yang
     * @param none
     *
     *
     * @return none
     *
    */
    private function _getReadmoreBeentoCommentTag($res,$user_id)
    {
         $tags = "";
         if ($res) {
            for ($i=0; $i<count($res); $i++) {
                $ret = $res[$i];
                $created_at = date('Y年n月j日', strtotime($res[$i]['created_at']));
                $edit_link = "";
                if ($res[$i]['user_id'] and  ($res[$i]['user_id'] == $user_id) ) {
                    $edit_link = "
                                <a class=\"btn btnF btnC01\" href=\"/beento/edit/id/{$res[$i]['bt_id']}\">編集</a>
                                <a class=\"btn btnF btnC01\" href=\"javascript:void(0);\" onclick=\"ajax_delbeento({$res[$i]['bt_id']},'{$res[$i]['photo']}',{$res[$i]['shop_id']});return false;\">削除</a>
                                ";
                }

                //店のリストのもっと見るで、一言がセットされているときのみ表示する
                $beento_explain = "";
                if ($res[$i]['explain'] !="") {
                    $beento_explain =  nl2br($res[$i]['explain']);
                } else {
                    $beento_explain = "行きました。";
                }

                $photo_width_size = "";
                if ($res[$i]['photo_width_size'] !="") {
                    $photo_width_size = " width=\"{$res[$i]['photo_width_size']}px\" ";
                }
                $photo_height_size = "";
                if ($res[$i]['photo_height_size'] !="") {
                    $photo_height_size = " height=\"{$res[$i]['photo_height_size']}px\" ";
                }
                $userphoto_width_size = "";
                if ($res[$i]['userphoto_width_size'] !="") {
                    $userphoto_width_size = " width=\"{$res[$i]['userphoto_width_size']}px\" ";
                }
                $userphoto_height_size = "";
                if ($res[$i]['userphoto_height_size'] !="") {
                    $userphoto_height_size = " height=\"{$res[$i]['userphoto_height_size']}px\" ";
                }


                $tag = "
                        <p class=\"rankTitle\"><a href=\"/user/myranking/id/{$res[$i]['user_id']}\">{$res[$i]['user_name']}</a></p>
                        <dl class=\"innerCommentUserdetail01\">
                            <dt>
                                 <a href=\"{$res[$i]['photo']}\" rel=\"lightbox\" title=\"{$res[$i]['shop_name']}\" onclick=\"lightbox.start(this, false, false, false, false); return false;\">
                                     <span>
                                         <img src=\"{$res[$i]['photo']}\" alt=\"{$res[$i]['shop_name']}\" style=\"{$res[$i]['photo_style']}\" {$photo_width_size} {$photo_height_size}/>
                                     </span>
                                 </a>
                             </dt>
                            <dd>
                                <dl class=\"innerCommentUserdetail02\">
                                    <dt>
                                       <a href=\"/user/myranking/id/{$res[$i]['user_id']}\">
                                       <span>
                                           <img src=\"{$res[$i]['user_photo']}\" alt=\"{$res[$i]['user_name']}\" style=\"{$res[$i]['userphoto_style']}\" {$userphoto_width_size} {$userphoto_height_size}/>
                                       </span>
                                       </a>
                                    </dt>
                                    <dd>
                                        <p>{$created_at}</p>
                                        <p class=\"rankCommentBox\">
                                        {$edit_link} 　　{$beento_explain}
                                        </p>
                                    </dd>
                                </dl>
                            </dd>
                        </dl>
                    ";
                $tags .= $tag;
            }
         }
        return $tags;
    }

    /**
     * ajaxタグ作成（行ったユーザーコメント）
     * @author: xiuhui yang
     * @param none
     *
     *
     * @return none
     *
    */
    private function _getReadmoreBeentoCommentTagbak($res)
    {
         $tags = "";
         if ($res) {
            for ($i=0; $i<count($res); $i++) {
                $ret = $res[$i];
                $created_at = date('Y年n月j日', strtotime($res[$i]['created_at']));
                $tag = "
                        <dt>
                            <a href=\"\">
                            <img src=\"{$res[$i]['photo']}\" alt=\"\" title=\"\" />
                            </a>
                        </dt>
                        <dd>
                            <p class=\"userShopComment\">
                                <a href=\"\">{$res[$i]['explain']}</a>
                            </p>
                            <div>{$created_at} ｜<img src=\"{$res[$i]['user_photo']}\" alt=\"\" width=\"15\" height=\"15\" title=\"{$res[$i]['user_name']}\" />
                                <a href=\"/user/myranking/id/{$res[$i]['user_id']}\">{$res[$i]['user_name']}</a>
                            </div>
                        </dd>
                        ";
                $tags .= $tag;
            }
         }
        return $tags;
    }


    /**
     * みんな投稿した画像取得
     * @author: xiuhui yang
     * @param string $shop_id
     *
     *
     * @return array $res
     *
    */
    private function _getPhotoList($shop_id)
    {
        //該当shopディレクトリの画像一覧を取得する
        $list = array();
        $photoList = array();
        $dir = ROOT_PATH."/img/pc/shop/".$shop_id."/";
        if (file_exists($dir) && is_dir($dir)) {
            $dh = opendir($dir);
            while(false !== ($fn = readdir($dh))){
                if($fn !== '.' && $fn !== '..' && !is_dir($dir.$fn)){
                    array_push($photoList, $fn);
                }
            }
            closedir($dh);
            //作成者画像チェック
            if (is_array($photoList) && count($photoList) > 0) {
                for ($i=0; $i<count($photoList); $i++) {
                    $img_filename = $photoList[$i];
                    $args =  explode('_', $img_filename);
                    if(isset($args[1])){
                        $list[$i]['user_id'] = $args[1];
                        //ユーザー画像チェック
                        //作成者画像チェック
                        $user_photo = $args[1].'jpg';
                        $image_existed = Utility::isImagePathExisted(Utility::CONST_USER_IMAGE_PATH.$user_photo);
                        if( $image_existed != true) {
                            $list[$i]['user_photo'] = Utility::CONST_NOIMAGE_IMAGE_PATH.Utility::CONST_NO_IMG_FILE_NAME;
                        } else {
                            $list[$i]['user_photo'] = Utility::CONST_USER_IMAGE_PATH.$user_photo;
                        }
                    }
                    $list[$i]['photo'] = $photoList[$i];
                }
            }
        }
        return $list;
    }

    private function _check_amdin() {
        $Utility = new Utility;
        $use_engine_flg = $Utility->tdkEngine();
        $admin_flg = false;
        if ($use_engine_flg == true) {
            if ($this->user_info['user_id'] == Utility::CONST_VALUE_ADMIN_HAMULET
                || $this->user_info['user_id'] == Utility::CONST_VALUE_ADMIN_KINUE) {
                $admin_flg = true;
            }
        } else {
            if ($this->user_info['user_id'] == Utility::CONST_VALUE_ADMIN_HAMULET_TEST
                || $this->user_info['user_id'] == Utility::CONST_VALUE_ADMIN_KINUE_TEST) {
                $admin_flg = true;
            }
        }
        return $admin_flg;
    }

}