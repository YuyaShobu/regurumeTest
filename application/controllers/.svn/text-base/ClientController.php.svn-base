<?php
/*
 * 2013.7.30 chou
 * //店舗会員の
 *
 */
require CONTROLLER_DIR.'/ClientabstractController.php';
class ClientController extends ClientabstractController {

	//ログインページ
	public function loginAction () {

		//ログイン画面からの遷移でない場合はログイン画面に飛ばす
		$login_flg  = $this->getRequest()->getParam('login_flg');
		if ($login_flg != "1") {
			$this->_redirect("client/index/");
		}

		$email  = $this->getRequest()->getParam('email');
		$password   = $this->getRequest()->getParam('password');

		// エラーメッセージ
		$errorMessage = "";

		$email  = htmlspecialchars($email, ENT_QUOTES);
		$password   = htmlspecialchars($password, ENT_QUOTES);

		$param['email']  = $email;
		$param['password']   = $password;
		$user_info = $this->service('client' , 'login' , $param);

		if ($user_info['staff_id'] > 0) {
			session_cache_limiter('none');
			session_start();

			$_SESSION["STAFFID"] = $user_info['staff_id'];

			$this->_redirect("/client/index/");
		}

		$this->_redirect("/client/index/");
	}

	public function logoutAction () {
		if (isset($_SESSION["STAFFID"])) {
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

		$this->view->massage = $errorMessage;
		$this->render('index');
	}

	//トップページ
	public function indexAction () {
		$shop_id = $this->getRequest()->getParam('shopid');

		if($this->staff_info !==false) {
			$shop_id = $this->staff_info;
			$shop_id = $shop_id['shop_id'];
		}

		//②please count the people who have been to this shop.（been to table）
        $beento_count = $this->service('client', 'getBeentoCount', $shop_id);
		//③please count the people who want to assistance this shop ( from oen)
        $oen_count = $this->service('client', 'getOenCount', $shop_id);
		//④please get all info from rank table , if shop_id is same.
		$top3_list = $this->service('client', 'getTop3List', $shop_id);
		$beento_user_list = $this->service('client', 'getBeentoListByShopId', $shop_id);
		$oen_user_list = $this->service('client', 'getOenListByShopId', $shop_id);
		$shop_info = $this->service('client', 'getShopInfoByShopId', $shop_id);
		$coupon_info = $this->service('client', 'getCouponByShopIdFromIndex', $shop_id);
		$ikitai_List = $this->service('client', 'getIkitaiListByShopId', $shop_id);

		isset($shop_info['shop_url']) ? $this->view->shop_url = $shop_info['shop_url'] : '';
		isset($shop_info['address']) ? $this->view->shop_address = $shop_info['address'] : '';
		isset($shop_info['business_day']) ? $this->view->shop_business_day = $shop_info['business_day'] : '';
		$this->view->shop_id      = $shop_id;
		$this->view->beento_count = $beento_count;
		$this->view->oen_count    = $oen_count;
		$this->view->top3_list    = $top3_list;
		$this->view->coupon_info  = $coupon_info;
		$this->view->beento_user_list = $beento_user_list;
		$this->view->oen_user_list = $oen_user_list;
		$this->view->ikitai_List = $ikitai_List;
		$top3_list === FALSE ? $this->view->top3_list_count = 0 : $this->view->top3_list_count = count($top3_list);
		$ikitai_List === FALSE ? $this->view->ikitai_list_count = 0 : $this->view->ikitai_list_count = count($ikitai_List);
	}

	//広告一覧ページ
	public function advertisementlistAction () {
		$shop_id = $this->getRequest()->getParam('shopid');
		$this->service('client', 'getTop3List', $shop_id);
	}

	public function couponviewajaxAction () {
		$shop_id = $this->staff_info['shop_id'];
		$user_list = $this->service('client', 'getOenUserByShopId', array('shop_id'=>(int)$shop_id));
		echo json_encode($user_list);
		exit;
	}

	//クーポン情報ページ
	public function couponinfoAction () {
		$coupon_kind = "";
		$coupon_kind = $this->getRequest()->getParam('coupon_kind');
		$shop_id = $this->staff_info['shop_id'];
		$this->view->shop_id = $shop_id;
		$this->view->coupon_kind = $coupon_kind;
		$this->shop_info($shop_id,array(1,2,3,4,5));

		if (!$coupon_kind =="") {
			$this->shop_info($shop_id,array($coupon_kind));
			if ($coupon_kind == "1") {
				$this->render('shoptopcouponinfo');
			} elseif ($coupon_kind == "2") {
				$this->render('oencouponinfo');
			} elseif ($coupon_kind == "3") {
				$this->render('communitycouponinfo');
			} elseif ($coupon_kind == "4") {
				$this->render('jorencouponinfo');
			} elseif ($coupon_kind == "5") {
			    $this->render('jorenfriendcouponinfo');
			} else  {

			}
		}
	}

	//クーポン編集、挿入ページ
	public function couponinfoeditAction () {
		$coupon_action = '';
		$this->_request->getPost('insert') ? $coupon_action = 'insert' : '';
		$this->_request->getPost('update') ? $coupon_action = 'update' : '';
		$this->_request->getPost('delete') ? $coupon_action = 'delete' : '';
		$this->_request->getPost('copy') ? $coupon_action = 'copy' : '';
		empty($coupon_action) ? $this->redirect('/client/couponinfo') : '';

		$shop_id = $this->staff_info['shop_id'];
		$coupon_id = $this->_request->getPost('coupon_id');

		if($coupon_action == 'update' || $coupon_action == 'copy') {
			$coupon_info = $this->service('client', 'getCouponByCouponId', $coupon_id);

			if($coupon_info !== FALSE) {
				if($coupon_info[0]['view_flg'] == 4 || $coupon_info[0]['view_flg'] == 5) {
					$user_list = $this->service('client', 'getOenUserByShopId', array('shop_id'=>(int)$shop_id));
					$user_list_now = $this->service('client', 'getJouRenByShopId', array('coupon_id'=>$coupon_info[0]['coupon_id'],'view_flg'=>$coupon_info[0]['view_flg']));
					$this->view->user_list = $user_list;
					$this->view->user_list_now = $user_list_now['user_id'];
				}
				$this->view->title = $coupon_info[0]['title'];
				$coupon_action == 'update' ? $this->view->publication_date1 = $coupon_info[0]['public_start'] : '';
				$coupon_action == 'update' ? $this->view->publication_date2 = $coupon_info[0]['public_end'] : '';
				$this->view->view_flg = $coupon_info[0]['view_flg'];
				$this->view->coupon = $coupon_info[0]['coupon'];
				$this->view->coupon_id = $coupon_id;
				$this->view->contenue = $coupon_info[0]['contenue'];
				$coupon_info[0]['publish_flg'] == 0 || $coupon_info[0]['publish_flg'] == 1 ? $this->view->publish_flg = $coupon_info[0]['publish_flg'] : $this->view->publish_flg = 0;
			}

		}
		else if($coupon_action == 'delete') {
			$param['coupon_id'] = $coupon_id;
			$rs = $this->service('client', 'deleteCouponByCouponId', $param);
			$this->view->shop_id = $shop_id;
			$coupon_kind = $this->getRequest()->getParam('coupon_kind');
			if($coupon_kind == null || empty($coupon_kind)) {
				$this->shop_info($shop_id,array(1,2,3,4,5));
				$this->render('couponinfo');
			}
			else {
				$this->shop_info($shop_id,array($coupon_kind));
				$this->_redirect('/client/couponinfo/coupon_kind/'.$coupon_kind.'/');
			}
		}

		$coupon_king = $this->_request->getPost('coupon_kind');
		if($coupon_king > 0) {
			$this->view->coupon_king = $coupon_king;
		}
		$this->view->shop_id = $shop_id;
		$this->view->coupon_action = $coupon_action;
	}

	//クーポン確認
	public function couponconfomAction () {
		if($this->staff_info['shop_id'] > 0 && !is_null($this->_request->getPost('submit'))) {
			//追加または変更確認
			$this->_request->getPost('coupon_action') ? $coupon_action = $this->_request->getPost('coupon_action') : $coupon_action = 'insert';

			$post_param = $this->getRequest()->getParams();
			$post_param['shop_id'] = $this->staff_info['shop_id'];
			$post_param['shop_id'] >0 ? $shop_id = $post_param['shop_id'] : $this->render('index');
			isset($post_param['coupon_id']) ? $this->view->coupon_id = $post_param['coupon_id'] : $this->render('index');
			$post_param['publish_flg'] == 0 || $post_param['publish_flg'] == 1 ? '' : $post_param['publish_flg'] = 0;

			$this->view->publish_flg = $post_param['publish_flg'];
			$this->view->shop_id = $post_param['shop_id'];
			$this->view->coupon_action = $coupon_action;
			if(isset($post_param['contenue'])) {
				$this->view->contenue = $post_param['contenue'];
			}
			//postパラメータ検証
			$validate = $this->validate('coupon','changeValidate', $post_param);

			if(isset($validate['error_flg'])) {
				//エラーメッセージの挿入
				$this->view->error  = $validate['error_message'];
				$this->view->error_flg = $validate['error_flg'];

				$this->view->title         	   = $validate['title'];
				$this->view->publication_date1 = $validate['publication_date1'];
				$this->view->publication_date2 = $validate['publication_date2'];
				$this->view->coupon            = $validate['coupon'];
				$this->view->view_flg   	   = $validate['view_flg'];
				if($validate['view_flg'] == 4 || $validate['view_flg'] == 5) {
					$user_list = $this->service('client', 'getOenUserByShopId', array('shop_id'=>(int)$shop_id));
					$this->view->user_list = $user_list;
					$this->view->user_list_now = $validate['user'];
				}
				$this->render('couponinfoedit');
			}
			else {
				$param['shop_id'] 			= $post_param['shop_id'];
				$param['title'] 			= $validate['title'];
				$param['public_start'] 		= $validate['publication_date1'];
				$param['public_end'] 		= $validate['publication_date2'];
				$param['coupon'] 			= $validate['coupon'];
				$param['view_flg'] 			= $validate['view_flg'];
				$param['publish_flg'] 		= $post_param['publish_flg'];
				$param['delete_flg']        = 0;
				$param['created_at']        = date('Y-m-d H:i:s',time());
				$param['user_id']           = $validate['user'];
				if(isset($post_param['contenue'])) {
					$param['contenue']      = (int)$post_param['contenue'];
				}
				else {
					$param['contenue']      = 0;
				}

				if($coupon_action == 'update') {
					//クーポン変更
					$param['updated_at'] = date('Y-m-d H:i:s',time());
					isset($post_param['coupon_id']) ? $param['coupon_id'] = $post_param['coupon_id'] : '';
					$ret = $this->service('client', 'updateCoupon', $param);
				}
				else {
					//クーポン追加
					$param['created_at'] = date('Y-m-d H:i:s',time());
					//クーポン存在判断
					if($coupon_action == 'copy') {
						$exists = $this->service('client', 'getCouponCopyExists', $param);
					}
					else {
						$exists = $this->service('client', 'getCouponExists', $param);
					}
					!$exists['coupon_id'] > 0 ? $ret = $this->service('client', 'insertCoupon', $param) : $ret = true;
				}

				if($ret !== FALSE) {
					$coupon_action == 'update' ? $coupon_king = $this->_request->getPost('coupon_king') : $coupon_king = $validate['view_flg'];
					if($coupon_king == null || empty($coupon_king)) {
						$this->shop_info($shop_id,array(1,2,3,4,5));
					}
					else {
						$this->shop_info($shop_id,array($validate['view_flg']));
						$this->_redirect('/client/couponinfo/coupon_kind/'.$validate['view_flg'].'/');
					}
				}
// 				else {
// 					if($coupon_action == 'update') {
// 						$coupon_id = $this->_request->getPost('coupon_id');
// 						$params['coupon_id'] = $coupon_id;
// 						$coupon_info = $this->service('client', 'getCouponByCouponId', $params);
// 						if($coupon_info !== FALSE) {
// 							$this->view->title = $coupon_info[0]['title'];
// 							$this->view->publication_date1 = $coupon_info[0]['public_start'];
// 							$this->view->publication_date2 = $coupon_info[0]['public_end'];
// 							$this->view->view_flg = $coupon_info[0]['view_flg'];
// 							$this->view->coupon = $coupon_info[0]['coupon'];
// 							$this->view->coupon_id = $coupon_id;
// 						}
// 					}
// 					$this->render('couponinfoedit');
// 				}
				$this->render('couponinfo');
			}
		}
		else {
			$this->render('index');
		}
	}

	//店舗情報ページ
	public function shopinfoAction () {
		//
		//①please get "shop_name , shop_url, address , business_day, regular_holiday, latitude. longitude, updated_at" from shoptable, deleteflg is not 1
   	    //please dont change latitude and longitude.
		!isset($shop_id) ? $shop_id = $this->staff_info['shop_id'] : $this->redirect('/client/index');
		$shop_info = $this->service('client', 'getShopById', $shop_id);
		$genre_parent = $this->service('client','getGenreParent',array());
		$this->view->genre_parent = $genre_parent;

		$this->view->shop_id = $shop_id;
		$this->view->shop_name         = $shop_info[0]['shop_name'];
		$this->view->address           = $shop_info[0]['address'];
		$this->view->shop_url          = $shop_info[0]['shop_url'];
		$this->view->business_day      = $shop_info[0]['business_day'];
		$this->view->regular_holiday   = $shop_info[0]['regular_holiday'];
		$this->view->latitude          = $shop_info[0]['latitude'];
		$this->view->longitude   	   = $shop_info[0]['longitude'];
		$this->view->catchcopy  	   = $shop_info[0]['catchcopy'];
		$this->view->bodycopy  	   	   = $shop_info[0]['bodycopy'];
		$this->view->parking  	   	   = $shop_info[0]['parking'];
		$this->view->access  	   	   = $shop_info[0]['access'];
		$this->view->station  	   	   = $shop_info[0]['station'];
		$this->view->approvalnumber1   = $shop_info[0]['approvalnumber1'];
		$this->view->approvalnumber2   = $shop_info[0]['approvalnumber2'];
		$this->view->approvalnumber3   = $shop_info[0]['approvalnumber3'];
		$this->view->approvalnumber4   = $shop_info[0]['approvalnumber4'];
		$this->view->approvalnumber5   = $shop_info[0]['approvalnumber5'];
		$this->view->groupname  	   = $shop_info[0]['groupname'];
		$this->view->etc  	   		   = $shop_info[0]['etc'];
		$this->view->shop_kana  	   = $shop_info[0]['shop_kana'];
		$this->view->zipcode1  	   	   = $shop_info[0]['zipcode1'];
		$this->view->zipcode2 	   	   = $shop_info[0]['zipcode2'];
		$this->view->tel 	   	       = $shop_info[0]['tel'];
		$this->view->fax 	   	       = $shop_info[0]['fax'];
		$this->view->lunchbudget 	   = $shop_info[0]['lunchbudget'];
		$this->view->dinnerbudget 	   = $shop_info[0]['dinnerbudget'];
		$this->view->totalseats 	   = $shop_info[0]['totalseats'];
		$this->view->genre1 	   	   = $this->service('client','getGenreNameByGenreId',array('genre_id'=>$shop_info[0]['genre1']));
		$this->view->genre2 	       = $this->service('client','getGenreNameByGenreId',array('genre_id'=>$shop_info[0]['genre2']));
		$this->view->genre3 	       = $this->service('client','getGenreNameByGenreId',array('genre_id'=>$shop_info[0]['genre3']));
		$this->_genre(array('genre1'=>$shop_info[0]['genre1'],'genre2'=>$shop_info[0]['genre2'],'genre3'=>$shop_info[0]['genre3']));
		$shop_info[0]['card'] != '' ? $card = explode('|',$shop_info[0]['card']) : $card = array();
		$this->view->card 	   		   = $card;
	}
	//店舗情報編集ページ
	public function shopinfoeditAction () {
		///$shop_id = $this->_request->getPost('shop_id');
		!isset($shop_id) ? $shop_id = $this->staff_info['shop_id'] : $this->redirect('/client/index');
		$this->view->shop_id = $shop_id;

		$genre_parent = $this->service('client','getGenreParent',array());
		$this->view->genre_parent = $genre_parent;

		//post submit判断(submit_flg)
		if($this->_request->getPost('shop_name'))
		{
			$post_param = $this->getRequest()->getParams();
			//postパラメータ検証
			$validate = $this->validate('shop','registValidate', $post_param);

			$this->view->shop_name         = $validate['shop_name'];
			$this->view->address           = $validate['address'];
			$this->view->shop_url          = $validate['shop_url'];
			$this->view->business_day      = $validate['business_day'];
			$this->view->regular_holiday   = $validate['regular_holiday'];
			$this->view->latitude      	   = $validate['latitude'];
			$this->view->longitude  	   = $validate['longitude'];
			$this->view->catchcopy  	   = $validate['catchcopy'];
			$this->view->bodycopy  	   	   = $validate['bodycopy'];
			$this->view->parking  	   	   = $validate['parking'];
			$this->view->access  	   	   = $validate['access'];
			$this->view->station  	   	   = $validate['station'];
			$this->view->approvalnumber1   = $validate['approvalnumber1'];
			$this->view->approvalnumber2   = $validate['approvalnumber2'];
			$this->view->approvalnumber3   = $validate['approvalnumber3'];
			$this->view->approvalnumber4   = $validate['approvalnumber4'];
			$this->view->approvalnumber5   = $validate['approvalnumber5'];
			$this->view->groupname  	   = $validate['groupname'];
			$this->view->etc  	   		   = $validate['etc'];
			$this->view->shop_kana  	   = $post_param['shop_kana'];
			$this->view->zipcode1  	   	   = $post_param['zipcode1'];
			$this->view->zipcode2 	   	   = $post_param['zipcode2'];
			$this->view->tel 	   	       = $post_param['tel'];
			$this->view->fax 	   	       = $post_param['fax'];
			$this->view->lunchbudget 	   = $post_param['lunchbudget'];
			$this->view->dinnerbudget 	   = $post_param['dinnerbudget'];
			$this->view->totalseats 	   = $post_param['totalseats'];

			if (isset($validate['error_flg'])) {
				//エラーメッセージの挿入
				$this->view->error  = $validate['error_message'];
				$this->view->error_flg = $validate['error_flg'];
				$this->_genre(array('genre1'=>$post_param['genre1'],'genre2'=>$post_param['genre2'],'genre3'=>$post_param['genre3']));
			}
			else {
				$data['shop_name'] = $post_param['shop_name'];
				$data['address'] = $post_param['address'];
				$data['shop_url'] = $post_param['shop_url'];
				$data['business_day'] = $post_param['business_day'];
				$data['regular_holiday'] = $post_param['regular_holiday'];
				$data['latitude'] = $post_param['latitude'];
				$data['longitude'] = $post_param['longitude'];
				$data['catchcopy'] = $post_param['catchcopy'];
				$data['bodycopy'] = $post_param['bodycopy'];
				$data['parking'] = $post_param['parking'];
				$data['access'] = $post_param['access'];
				$data['station'] = $post_param['station'];
				$data['approvalnumber1'] = $post_param['approvalnumber1'];
				$data['approvalnumber2'] = $post_param['approvalnumber2'];
				$data['approvalnumber3'] = $post_param['approvalnumber3'];
				$data['approvalnumber4'] = $post_param['approvalnumber4'];
				$data['approvalnumber5'] = $post_param['approvalnumber5'];
				$data['groupname'] = $post_param['groupname'];
				$data['etc'] = $post_param['etc'];
				$data['shop_kana'] = $post_param['shop_kana'];
				$data['zipcode1'] = $post_param['zipcode1'];
				$data['zipcode2'] = $post_param['zipcode2'];
				$data['tel'] = $post_param['tel'];
				$data['fax'] = $post_param['fax'];
				$data['lunchbudget'] = $post_param['lunchbudget'];
				$data['dinnerbudget'] = $post_param['dinnerbudget'];
				$data['totalseats'] = $post_param['totalseats'];
				isset($post_param['genre1']) && $post_param['genre1'] > 0 ? $data['genre1'] = $post_param['genre1'] : $data['genre1'] = '';
				isset($post_param['genre2']) && $post_param['genre2'] > 0 ? $data['genre2'] = $post_param['genre2'] : $data['genre2'] = '';
				isset($post_param['genre3']) && $post_param['genre3'] > 0 ? $data['genre3'] = $post_param['genre3'] : $data['genre3'] = '';
				$data['shop_id'] = $shop_id;
				$card = '';
				if(isset($post_param['card']) && is_array($post_param['card'])) {
					foreach($post_param['card'] as $c) {
						if($c > 0) {
							$c = (int)$c;
							$card.= '|'.$c;
						}
					}
				}
				$card = trim($card,'|');
				$data['card'] = $card;
				$ret = $this->service('client','updateShopById',$data);
				if($ret !== false)
				{
					$this->view->card 	   		   = $post_param['card'];
					$this->view->genre1 	   	   = $this->service('client','getGenreNameByGenreId',array('genre_id'=>$post_param['genre1']));
					$this->view->genre2 	       = $this->service('client','getGenreNameByGenreId',array('genre_id'=>$post_param['genre2']));
					$this->view->genre3 	       = $this->service('client','getGenreNameByGenreId',array('genre_id'=>$post_param['genre3']));
					$this->render('shopinfo');
				}
			}
		}
		if (!isset($validate['error_flg'])) {
			//shop情報取得
			$shop_info = $this->service('client', 'getShopById', $shop_id);

			$this->view->shop_name         = $shop_info[0]['shop_name'];
			$this->view->address           = $shop_info[0]['address'];
			$this->view->shop_url          = $shop_info[0]['shop_url'];
			$this->view->business_day      = $shop_info[0]['business_day'];
			$this->view->regular_holiday   = $shop_info[0]['regular_holiday'];
			$this->view->latitude      	   = $shop_info[0]['latitude'];
			$this->view->longitude  	   = $shop_info[0]['longitude'];
			$this->view->catchcopy  	   = $shop_info[0]['catchcopy'];
			$this->view->bodycopy  	   	   = $shop_info[0]['bodycopy'];
			$this->view->parking  	   	   = $shop_info[0]['parking'];
			$this->view->access  	   	   = $shop_info[0]['access'];
			$this->view->station  	   	   = $shop_info[0]['station'];
			$this->view->approvalnumber1   = $shop_info[0]['approvalnumber1'];
			$this->view->approvalnumber2   = $shop_info[0]['approvalnumber2'];
			$this->view->approvalnumber3   = $shop_info[0]['approvalnumber3'];
			$this->view->approvalnumber4   = $shop_info[0]['approvalnumber4'];
			$this->view->approvalnumber5   = $shop_info[0]['approvalnumber5'];
			$this->view->groupname  	   = $shop_info[0]['groupname'];
			$this->view->etc  	   		   = $shop_info[0]['etc'];
			$this->view->shop_kana  	   = $shop_info[0]['shop_kana'];
			$this->view->zipcode1  	   	   = $shop_info[0]['zipcode1'];
			$this->view->zipcode2 	   	   = $shop_info[0]['zipcode2'];
			$this->view->tel 	   	       = $shop_info[0]['tel'];
			$this->view->fax 	   	       = $shop_info[0]['fax'];
			$this->view->lunchbudget 	   = $shop_info[0]['lunchbudget'];
			$this->view->dinnerbudget 	   = $shop_info[0]['dinnerbudget'];
			$this->view->totalseats 	   = $shop_info[0]['totalseats'];
			$this->_genre(array('genre1'=>$shop_info[0]['genre1'],'genre2'=>$shop_info[0]['genre2'],'genre3'=>$shop_info[0]['genre3']));

			$shop_info[0]['card'] != '' ? $card = explode('|',$shop_info[0]['card']) : $card = array();
			$this->view->card 	   		   = $card;
		}
	}

	public function genreajaxAction () {
		$parent_id = $this->_request->getPost('id');
		if(!is_null($parent_id) && !empty($parent_id)) {
			$genre_ko = $this->service('client','getGenreByGenreId',array('genre_parent'=>(int)$parent_id));
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

	//スタッフ一覧
	public function staffinfoAction () {
		$shop_id = $this->staff_info['shop_id'];
		!$shop_id >0 ? $this->redirect('/client/index') : '';
		$this->view->shop_id = $shop_id;

		$staff_info = $this->service('client', 'getStaffByShopId', $shop_id);
		$this->view->staff_info = $staff_info;
	}

	//スタッフ追加
	public function staffinfoeditAction () {
		$post_param = $this->getRequest()->getParams();
		$shop_id = $this->staff_info['shop_id'];
		!$shop_id >0 || !isset($post_param['action']) ? $this->redirect('/client/index') : '';
		!isset($post_param['insert']) && !isset($post_param['update']) ? $this->redirect('/client/index') : '';
		isset($post_param['insert']) == 'insert' ? $action = 'insert' : $action = 'update';
		if($action == 'update') {
			if($post_param['staff_id'] > 0) {
				$staff_info = $this->service('client', 'getStaffByStaffId', $post_param['staff_id']);
				$this->view->staff_name     = $staff_info['staff_name'];
				$this->view->email          = $staff_info['email'];
				$this->view->status   		= $staff_info['status'];
				$this->view->staff_id   	= $staff_info['staff_id'];
			}
			else {
				$this->redirect('/client/index');
			}
		}
		$this->view->shop_id = $shop_id;
		$this->view->action = $action;
	}

	//スタッフ確認
	public function staffconfomAction () {
		$post_param = $this->getRequest()->getParams();
		$shop_id = $this->_request->getPost('shop_id');
		!$shop_id >0 ? $this->redirect('/client/staffinfo') : '';
		$this->view->shop_id = $shop_id;

		//postパラメータ検証
		$validate = $this->validate('staff','editValidate', $post_param);

		$this->view->staff_name     = $validate['staff_name'];
		$this->view->email          = $validate['email'];
		$this->view->password       = $validate['password'];
		$this->view->kpassword      = $validate['kpassword'];
		$this->view->status   		= $validate['status'];

		if (isset($validate['error_flg'])) {
			//エラーメッセージの挿入
			$this->view->error  = $validate['error_message'];
			$this->view->error_flg = $validate['error_flg'];
			$this->render('staffinfoedit');
		}
		else {
			$data['shop_id']    = (int)$shop_id;
			$data['staff_name'] = $validate['staff_name'];
			$data['email']      = $validate['email'];
			$data['password']   = $validate['password'];
			$data['status'] 	= $validate['status'];
			if(isset($post_param['update'])) {
				!isset($post_param['staff_id']) ? $this->redirect('/client/index') : $data['staff_id'] = $post_param['staff_id'];
				$data['updated_at'] = date('Y-m-d H:i:s',time());
				$ret = $this->service('client','updateStaff',$data);
			}
			else {
				$data['created_at'] = date('Y-m-d H:i:s',time());
				$r = $this->service('client','getStaffExisit',$data);
				if($r === false) {
					$ret = $this->service('client','insertStaff',$data);
				}
				else {
					$ret = true;
				}
			}
			if($ret !== false)
			{
				$staff_info = $this->service('client', 'getStaffByShopId', $shop_id);
				$this->view->staff_info = $staff_info;
				$this->render('staffinfo');
			}
			else {
				$this->render('staffinfoedit');
			}
		}
	}

	private function _get_shop_info ($params) {
		$coupon = 'coupon_info_'.$params['view_flg'];
		$$coupon = $this->service('client', 'getCouponByShopId', $params);
		if($$coupon !== false)
		{
			$coupon_info = array();
			foreach($$coupon as $key=>$info)
			{
				$coupon_info[$key]['title'] = $info['title'];
				$coupon_info[$key]['coupon'] = $info['coupon'];
				$coupon_info[$key]['coupon_id'] = $info['coupon_id'];
				$coupon_info[$key]['public_start'] = $info['public_start'];
				$coupon_info[$key]['public_end'] = $info['public_end'];
				$coupon_info[$key]['publish_flg'] = $info['publish_flg'];
				$coupon_info[$key]['updated_at'] = $info['updated_at'];
				strtotime('-7 days',time()) <= strtotime($info['public_end']) && strtotime($info['public_end']) <= time() ? $coupon_info[$key]['is_alert'] = 1 : $coupon_info[$key]['is_alert'] = 0;
			}
			$this->view->$coupon = $coupon_info;
		}
	}

	//shop情報取得
	private function shop_info ($shop_id,$type) {
		$params['shop_id'] = $shop_id;

		if(in_array(1,$type)) {
			$params['view_flg'] = 1;
			$this->_get_shop_info($params);
		}
		if(in_array(2,$type)) {
			$params['view_flg'] = 2;
			$this->_get_shop_info($params);
		}
		if(in_array(3,$type)) {
			$params['view_flg'] = 3;
			$this->_get_shop_info($params);
		}
		if(in_array(4,$type)) {
			$params['view_flg'] = 4;
			$this->_get_shop_info($params);
		}
		if(in_array(5, $type)) {
			$params['view_flg'] = 5;
			$this->_get_shop_info($params);
		}
	}

	private function _genre ($params) {
		$arr_genre1 = explode('_',$params['genre1']);
		if(is_array($arr_genre1) && count($arr_genre1) == 2) {
			$this->view->genre1_parent = (int)$arr_genre1[0];
			$this->view->genre1_ko_id = $params['genre1'];
			$genre_ko = $this->service('client','getGenreByGenreId',array('genre_parent'=>(int)$arr_genre1[0]));
			$this->view->genre1_ko = $genre_ko;
		}
		$arr_genre2 = explode('_',$params['genre2']);
		if(is_array($arr_genre2) && count($arr_genre2) == 2) {
			$this->view->genre2_parent = (int)$arr_genre2[0];
			$this->view->genre2_ko_id = $params['genre2'];
			$genre_ko = $this->service('client','getGenreByGenreId',array('genre_parent'=>(int)$arr_genre2[0]));
			$this->view->genre2_ko = $genre_ko;
		}
		$arr_genre3 = explode('_',$params['genre3']);
		if(is_array($arr_genre3) && count($arr_genre3) == 2) {
			$this->view->genre3_parent = (int)$arr_genre3[0];
			$this->view->genre3_ko_id = $params['genre3'];
			$genre_ko = $this->service('client','getGenreByGenreId',array('genre_parent'=>(int)$arr_genre3[0]));
			$this->view->genre3_ko = $genre_ko;
		}
	}

}