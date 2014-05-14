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

	//クーポン情報ページ
	public function couponinfoAction () {
		$shop_id = $this->_request->getPost('shop_id');
		!$shop_id ? $this->redirect('/client/index') : '';
		$this->view->shop_id = $shop_id;
		$this->shop_info($shop_id);
	}

	//クーポン編集、挿入ページ
	public function couponinfoeditAction () {
		$coupon_action = '';
		$this->_request->getPost('insert') ? $coupon_action = 'insert' : '';
		$this->_request->getPost('update') ? $coupon_action = 'update' : '';
		$this->_request->getPost('delete') ? $coupon_action = 'delete' : '';
		$this->_request->getPost('copy') ? $coupon_action = 'copy' : '';
		empty($coupon_action) ? $this->redirect('/client/index') : '';
		
		$shop_id = $this->_request->getPost('shop_id');
		$coupon_id = $this->_request->getPost('coupon_id');
		
		if($coupon_action == 'update' || $coupon_action == 'copy') {
			$coupon_info = $this->service('client', 'getCouponByCouponId', $coupon_id);
			
			if($coupon_info !== FALSE) {
				$this->view->title = $coupon_info[0]['title'];
				$coupon_action == 'update' ? $this->view->publication_date1 = $coupon_info[0]['public_start'] : '';
				$coupon_action == 'update' ? $this->view->publication_date2 = $coupon_info[0]['public_end'] : '';
				$this->view->view_flg = $coupon_info[0]['view_flg'];
				$this->view->coupon = $coupon_info[0]['coupon'];
				$this->view->coupon_id = $coupon_id;
				$coupon_info[0]['publish_flg'] == 0 || $coupon_info[0]['publish_flg'] == 1 ? $this->view->publish_flg = $coupon_info[0]['publish_flg'] : $this->view->publish_flg = 0;
			}
			
		}
		else if($coupon_action == 'delete') {
			$param['coupon_id'] = $coupon_id;
			$rs = $this->service('client', 'deleteCouponByCouponId', $param);
			$this->view->shop_id = $shop_id;
			$this->shop_info($shop_id);
			$this->render('couponinfo');
		}
		
		$this->view->shop_id = $shop_id;
		$this->view->coupon_action = $coupon_action;
	}
	
	//クーポン確認
	public function couponconfomAction () {
		if($this->_request->getPost('shop_id')) {
			//追加または変更確認
			$this->_request->getPost('coupon_action') ? $coupon_action = $this->_request->getPost('coupon_action') : $coupon_action = 'insert';
			
			$post_param = $this->getRequest()->getParams();
			$post_param['shop_id'] = $this->_request->getPost('shop_id');
			$post_param['shop_id'] >0 ? $shop_id = $post_param['shop_id'] : $this->render('index');
			isset($post_param['coupon_id']) ? $this->view->coupon_id = $post_param['coupon_id'] : $this->render('index');
			$post_param['publish_flg'] == 0 || $post_param['publish_flg'] == 1 ? '' : $post_param['publish_flg'] = 0;
			
			$this->view->publish_flg = $post_param['publish_flg'];
			$this->view->shop_id = $post_param['shop_id'];
			$this->view->coupon_action = $coupon_action;
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
					$exists = $this->service('client', 'getCouponExists', $param);
					!$exists['coupon_id'] > 0 ? $ret = $this->service('client', 'insertCoupon', $param) : $ret = true;
				}

				if($ret !== FALSE) {
					$this->shop_info($shop_id);
					$this->render('couponinfo');
				}
				else {
					if($coupon_action == 'update') {
						$coupon_id = $this->_request->getPost('coupon_id');
						$params['coupon_id'] = $coupon_id;
						$coupon_info = $this->service('client', 'getCouponByCouponId', $params);
						if($coupon_info !== FALSE) {
							$this->view->title = $coupon_info[0]['title'];
							$this->view->publication_date1 = $coupon_info[0]['public_start'];
							$this->view->publication_date2 = $coupon_info[0]['public_end'];
							$this->view->view_flg = $coupon_info[0]['view_flg'];
							$this->view->coupon = $coupon_info[0]['coupon'];
							$this->view->coupon_id = $coupon_id;
						}	
					}
					$this->render('couponinfoedit');
				}
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
		!isset($shop_id) ? $shop_id = $this->_request->getPost('shop_id') : $this->redirect('/client/index');
		$shop_info = $this->service('client', 'getShopById', $shop_id);
		
		$this->view->shop_id = $shop_id;
		$this->view->shop_name         = $shop_info[0]['shop_name'];
		$this->view->address           = $shop_info[0]['address'];
		$this->view->shop_url          = $shop_info[0]['shop_url'];
		$this->view->business_day      = $shop_info[0]['business_day'];
		$this->view->regular_holiday   = $shop_info[0]['regular_holiday'];
		$this->view->latitude          = $shop_info[0]['latitude'];
		$this->view->longitude   = $shop_info[0]['longitude'];
	}
	//店舗情報編集ページ
	public function shopinfoeditAction () {
		///$shop_id = $this->_request->getPost('shop_id');
		!isset($shop_id) ? $shop_id = $this->_request->getPost('shop_id') : $this->redirect('/client/index');
		$this->view->shop_id = $shop_id;
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
			if (isset($validate['error_flg'])) {
				//エラーメッセージの挿入
				$this->view->error  = $validate['error_message'];
				$this->view->error_flg = $validate['error_flg'];
			}
			else {
				$data['shop_name'] = $post_param['shop_name'];
				$data['address'] = $post_param['address'];
				$data['shop_url'] = $post_param['shop_url'];
				$data['business_day'] = $post_param['business_day'];
				$data['regular_holiday'] = $post_param['regular_holiday'];
				$data['shop_id'] = $shop_id;
				$ret = $this->service('client','updateShopById',$data);
				if($ret !== false)
				{
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
		}
	}
	
	//スタッフ一覧
	public function staffinfoAction () {
		$shop_id = $this->_request->getPost('shop_id');
		!$shop_id >0 ? $this->redirect('/client/index') : '';
		$this->view->shop_id = $shop_id;

		$staff_info = $this->service('client', 'getStaffByShopId', $shop_id);
		$this->view->staff_info = $staff_info;
	}
	
	//スタッフ追加
	public function staffinfoeditAction () {
		$post_param = $this->getRequest()->getParams();
		$shop_id = $this->_request->getPost('shop_id');
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
		!$shop_id >0 ? $this->redirect('/client/index') : '';
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
	
	//shop情報取得
	private function shop_info ($shop_id) {
		$params['shop_id'] = $shop_id;
		$params['view_flg'] = 1;
		$coupon_info_1 = $this->service('client', 'getCouponByShopId', $params);

		if($coupon_info_1 !== false)
		{
			$coupon_info = array();
			foreach($coupon_info_1 as $key=>$info)
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
			$this->view->coupon_info_1 = $coupon_info;
		}
			
		$params['view_flg'] = 2;
		$coupon_info_2 = $this->service('client', 'getCouponByShopId', $params);
		if($coupon_info_2 !== false)
		{
			$coupon_info = array();
			foreach($coupon_info_2 as $key=>$info)
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
			$this->view->coupon_info_2 = $coupon_info;
		}
			
		$params['view_flg'] = 3;
		$coupon_info_3 = $this->service('client', 'getCouponByShopId', $params);
		if($coupon_info_3 !== false)
		{
			$coupon_info = array();
			foreach($coupon_info_3 as $key=>$info)
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
			$this->view->coupon_info_3 = $coupon_info;
		}
	}

}