<?php
require CONTROLLER_DIR.'/AdminabstractController.php';
require_once('Zend/Paginator.php');
require_once('Zend/Paginator/Adapter/Array.php');
class AdminController extends AdminabstractController {

	//ログイン
	public function loginAction () {

	}

	//ログアウト
	public function logoutAction () {
		if (isset($_SESSION["ADMINID"])) {
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
		$this->render('login');
	}

	//管理者マイページ
	public function indexAction () {
		$this->view->zoom =12;
		$this->_index();
	}

	//shop ajax
	public function shopajaxAction () {
		$param = $this->_request->getParams();
		if(!empty($param['shop_name'])) {
			$rs = $this->service('admin','getShopInfoByShopName',array('search'=>$param['shop_name']));
			echo json_encode($rs);
			exit;
		}
		else if(!empty($param['shop_id'])) {
			$rs = $this->service('admin','getShopInfoByShopId',array('shop_id'=>(int)$param['shop_id']));
			echo json_encode($rs);
			exit;
		}
		echo false;
		exit;
	}

	//city ajax
	public function cityajaxAction () {
		$param = $this->_request->getParams();
		if(!empty($param['pref_code'])) {
			$pref_code = (int)$param['pref_code'];
			$rs = $this->service('admin','getCity',array('pref_code'=>$pref_code));
			echo json_encode($rs);
			exit;
		}
		echo false;
		exit;
	}

	public function billconfomAction () {
		$re = $this->_check();
		$action = $re['action'];
		$this->view->action = $re['action'];
		$shop_id = $this->_request->getPost('shop_id');
		$shop_id == null || empty($shop_id) ? $shop_id = '' : '';
		$this->view->shop_id = $shop_id;
		$post_param = $this->getRequest()->getParams();
		$post_param['shop_id'] = $shop_id;
		$validate = $this->validate('bill','editValidate', $post_param);
		if(isset($validate['error_flg'])) {
			$this->_index();

			$this->view->error  = $validate['error_message'];
			$this->view->error_flg = $validate['error_flg'];

			$this->view->shop_name       = $validate['shop_name'];
			$this->view->address 		 = $validate['address'];
			$this->view->shop_url 		 = $validate['shop_url'];
			$this->view->pref_code 		 = $validate['pref_code'];
			$validate['pref_code'] > 0 ? $this->view->citylist = $this->service('admin','getCity',array('pref_code'=>$validate['pref_code'])) : '';
			$this->view->city 		 	 = $validate['city'];
			$this->view->zoom =12;
			if(!empty($validate['latitude'])) {
				$this->view->latitude 		 = '';
				$this->view->_latitude 		 = $validate['latitude'];
			}
			if(!empty($validate['longitude'])) {
				$this->view->_longitude 	 = $validate['longitude'];
				$this->view->longitude 	 	 = '';
				$this->view->zoom =15;
			}
			$this->view->business_day 	 = $post_param['business_day'];
			$this->view->regular_holiday = $post_param['regular_holiday'];
			$this->view->staff_name 	 = $validate['staff_name'];
			$this->view->staff_email 	 = $validate['staff_email'];
			$this->view->tel 	 		 = $post_param['tel'];
			$this->view->email 			 = $validate['email'];
			$this->view->fax 			 = $post_param['fax'];
			$this->view->plan_status 	 = $validate['plan_status'];
			$this->view->status 	 	 = $validate['status'];

			$this->render('index');
		}
		else {
			$param['shop_name'] 		= $validate['shop_name'];
			$param['shop_address'] 		= $validate['address'];
			$param['shop_url'] 			= $validate['shop_url'];
			$param['pref_code'] 		= $validate['pref_code'];
			$param['city'] 				= $validate['city'];
			$param['latitude'] 			= $validate['latitude'];
			$param['longitude']			= $validate['longitude'];
			$param['business_day'] 		= $post_param['business_day'];
			$param['regular_holiday'] 	= $post_param['regular_holiday'];
			$param['staff_name'] 	    = $validate['staff_name'];
			$param['staff_email'] 		= $validate['staff_email'];
			$param['tel'] 				= $post_param['tel'];
			$param['email'] 			= $validate['email'];
			$param['fax'] 				= $post_param['fax'];
			$param['plan_status'] 		= (int)$validate['plan_status'];
			$param['status'] 			= (int)$validate['status'];

			if($action == 'insert') {

				if($shop_id > 0) {
					$param['shop_id'] = $shop_id;
					$rs = $this->service('admin','insertBillByShopId',$param);
					$rs === true ? $this->view->error = array('error_message'=>'会員店舗追加成功しました') : $this->view->error = array('error_message'=>'会員店舗追加失敗しました');
					$this->_index();
					$this->view->error_flg = true;
					$this->view->zoom =12;
					$this->render('index');
				}
				else {
					$rs = $this->service('admin', 'insertBill', $param);
					$rs === true ? $this->view->error = array('error_message'=>'会員店舗追加成功しました') : $this->view->error = array('error_message'=>'会員店舗追加失敗しました');
					$this->_index();
					$this->view->error_flg = true;
					$this->view->zoom =12;
					$this->render('index');
				}
			}
		}
	}

	//管理者追加、編集
	public function admineditAction () {
		$re = $this->_check();
		$this->view->admin_id = $re['admin_id'];
		$this->view->action = $re['action'];
	}

	//管理者追加、編集パラメータ
	public function adminconfomAction () {
		$re = $this->_check();
		$action = $re['action'];
		$this->view->admin_id = $re['admin_id'];
		$this->view->action = $re['action'];

		$post_param = $this->getRequest()->getParams();
		$validate = $this->validate('admin','editValidate', $post_param);
		if(isset($validate['error_flg'])) {
			//エラーメッセージの挿入
			$this->view->error  = $validate['error_message'];
			$this->view->error_flg = $validate['error_flg'];

			$this->view->name         	    = $validate['name'];
			$this->view->email 			    = $validate['email'];
			$this->view->encrypted_password = $validate['encrypted_password'];
			$this->view->kpassword          = $validate['kpassword'];
			$this->render('adminedit');
		}
		else {
			$param['name'] 				 = $validate['name'];
			$param['email'] 			 = $validate['email'];

			if($action == 'insert') {
				$exists = $this->service('admin','getAdminInfoExists' ,$param);
				if($exists['id'] >0 ) {
					$this->view->error_flg = 1;
					$this->view->error = array('既にその管理者を持っている');
					$this->render('adminedit');
				}
				else {
					$param['encrypted_password'] = $validate['encrypted_password'];
					$param['created_at']         = date('Y-m-d H:i:s',time());

					$rs = $this->service('admin','insertAdmin' ,$param);
					if($rs === true) {
						$this->view->admin_id = $this->admin_info['id'];
						$this->view->admin_name = $this->admin_info['name'];
						$this->render('index');
					}
					else {
						$this->render('adminedit');
					}
				}
			}
		}
	}

	//会員店舗一覧
	public function clienteditAction () {
		$this->_list('client', 'getBillList','getBillPaging');
	}

	public function clientconfomAction () {
		$re = $this->_check();
		$action = $re['action'];
		$bill_id = $this->_request->getPost('bill_id');
		if($action == 'delete' && $bill_id>0) {
			$this->service('admin','deleteBill',array('bill_id'=>$bill_id));
		}
		$this->_list('client', 'getBillList','getBillPaging');
		$this->render('clientedit');
	}

	//ユーザ一覧
	public function usereditAction () {
		$this->_list('user', 'getUserList','getUserListPaging');
	}

	//ユーザ一覧削除
	public function userconfomAction () {
		$re = $this->_check();
		$action = $re['action'];
		$user_id = $this->_request->getPost('user_id');
		if($action == 'delete' && $user_id>0) {
			$this->service('admin','deleteUser',array('user_id'=>$user_id));
		}
		$this->_list('user', 'getUserList','getUserListPaging');
		$this->render('useredit');
	}

	public function genreAction () {
		$this->_list('admin', 'getGenreList','getGenrePaging',true);
		$this->view->parent_genre = $this->service('admin','getGenreParent',array(''));
	}

	public function genreconfomAction () {
		$re = $this->_check();
		$action = $re['action'];
		$submit = $this->_request->getPost('submit');
		if($action == 'delete') {
			$genre_id = $this->_request->getPost('genre_id');
			if(!($genre_id == null || empty($genre_id))) {
				$re = $this->service('admin','deleteGenre',array('genre_id'=>$genre_id));
			}
		}
		else if($action == 'insert') {
			$genre_parent = $this->_request->getPost('genre_parent');
			if(isset($genre_parent)) {
				$genre_parent = (int)$genre_parent;
				$post_param = $this->getRequest()->getParams();
				$validate = $this->validate('genre','editValidate', $post_param);

				if (isset($validate['error_flg'])) {
					$this->view->error  = $validate['error_message'];
					$this->view->error_flg = $validate['error_flg'];

					$this->view->genre_parent = $genre_parent;
					$this->view->value        = $validate['value'];
				}
				else {
					if($genre_parent>0) {
						$max = $this->service('admin','getGenreMax',array('genre_parent'=>htmlspecialchars(trim($genre_parent),ENT_QUOTES).'_'));
						$max === false ? $max = 1 : $max = (int)$max['genre_id']+1;
						$max_string = $genre_parent.'_'.$max;
					}
					else {
						$max = $this->service('admin','getGenreParentMax',array());
						$max === false ? $max = 1 : $max = (int)$max['genre_id']+1;
						$max_string = $max;
					}
					$rs = $this->service('admin','getGenreById',array('genre_id'=>$max_string,'value'=>$validate['value']));
					if($rs['genre_id']>0) {
						$this->view->error_flg = 1;
						$this->view->error = array('既にそのジャンルを持っている');
					}
					else {
						$res = $this->service('admin','insertGenre',array('genre_id'=>$max_string,'value'=>$validate['value'],'delete_flg'=>0,'created_at'=>date('Y-m-d H:i:s',time())));
					}
				}
			}
		}
		else if($action == 'update') {
			$value = urldecode($this->_request->getPost('value'));
			$genre_id = $this->_request->getPost('genre_id');
			if($value == null || empty($value) || $genre_id == null || empty($genre_id)) {
				echo false;
				exit;
			}
			else {
				$rs = $this->service('admin','getGenreById',array('genre_id'=>$genre_id,'value'=>$value));
				if(!$rs['genre_id']>0) {
					$r = $this->service('admin','updateGenre',array('genre_id'=>$genre_id,'value'=>$value,'updated_at'=>date('Y-m-d H:i:s',time())));
					echo $r;
					exit;
				}
				else {
					echo false;
					exit;
				}
			}
		}
		$this->view->parent_genre = $this->service('admin','getGenreParent',array(''));
		$this->_list('admin', 'getGenreList','getGenrePaging',true);
		$this->render('genre');
	}

	public function couponcontenueAction () {
        $arr = $this->service('admin','updateCouponContenue',array(''));
		foreach($arr as $a) {
			$this->service('admin','updateCouponByCouponId',array('coupon_id'=>$a['coupon_id'],'public_end'=>$a['public_end']));
		}
		exit;
	}

	//ランキングこだわり
	public function kodawariAction () {
		$large_list = $this->service('admin','getRankingCategoryLarge',array());
		$this->view->large_list = $large_list;
		$this->_list('admin', 'getRankingCategoryList','getRankingCategoryPaging');
	}

	public function kodawariconfomAction () {
		$re = $this->_check();
		$action = $re['action'];
		$submit = $this->_request->getPost('submit');
		if($action == 'delete') {
			$large_id = $this->_request->getPost('large_id');
			$small_id = $this->_request->getPost('small_id');
			if(!($large_id == null || $small_id == null || empty($large_id))) {
				if(empty($small_id)) {
					$re = $this->service('admin','deleteRankingCategoryLarge',array('large_id'=>(int)$large_id));
				}
				else {
					$re = $this->service('admin','deleteRankingCategorySmall',array('large_id'=>(int)$large_id,'small_id'=>(int)$small_id));
				}
			}
		}
		else if($action == 'insert') {
			$kodawari_select = $this->_request->getPost('kodawari_select');
			if(isset($kodawari_select)) {
				$kodawari_select = (int)$kodawari_select;
				$post_param = $this->getRequest()->getParams();
				$validate = $this->validate('kodawari','editValidate', $post_param);

				if (isset($validate['error_flg'])) {
					$this->view->error  = $validate['error_message'];
					$this->view->error_flg = $validate['error_flg'];

					$this->view->kodawari_now = $kodawari_select;
					$this->view->large_value  = $validate['large_value'];
				}
				else {
					if($kodawari_select >0) {
						$rs = $this->service('admin','getRankingCategorySmallExists',array('large_id'=>$kodawari_select,'small_value'=>$validate['large_value']));
						if($rs['large_id']>0) {
							$this->view->error_flg = 1;
							$this->view->error = array('既にそのこだわり名を持っている');
						}
						else {
							$max = $this->service('admin','CategorySmallMax',array('large_id'=>$kodawari_select));
							$max = (int)$max['small_id'] + 1;
							$res = $this->service('admin','insertRankingCategorySmall',array('large_id'=>$kodawari_select,'small_id'=>$max,'small_value'=>$validate['large_value'],'delete_flg'=>0,'created_at'=>date('Y-m-d H:i:s',time())));
						}
					}
					else {
						$rs = $this->service('admin','getRankingCategoryLargeExists',array('large_value'=>$validate['large_value']));
						if($rs['large_id']>0) {
							$this->view->error_flg = 1;
							$this->view->error = array('既にそのこだわり名を持っている');
						}
						else {
							$res = $this->service('admin','insertRankingCategoryLarge',array('large_value'=>$validate['large_value'],'delete_flg'=>0,'created_at'=>date('Y-m-d H:i:s',time())));
						}
					}
				}
			}
		}
		else if($action == 'update') {
			$value = urldecode($this->_request->getPost('value'));
			$large_id = $this->_request->getPost('large_id');
			$small_id = $this->_request->getPost('small_id');
			if($value == null || empty($value) || $large_id == null || empty($large_id) || !isset($small_id)) {
				echo false;
				exit;
			}
			else {
				if(empty($small_id)) {
					$rs = $this->service('admin','getRankingCategoryLargeExists',array('large_value'=>$value));
					if(!$rs['large_id']>0) {
						$r = $this->service('admin','updateRankingCategoryLarge',array('large_id'=>$large_id,'large_value'=>$value,'updated_at'=>date('Y-m-d H:i:s',time())));
						echo $r;
						exit;
					}
					else {
						echo false;
						exit;
					}
				}
				else {
					$rs = $this->service('admin','getRankingCategorySmallExists',array('large_id'=>$large_id,'small_value'=>$value));
					if(!$rs['large_id']>0) {
						$r = $this->service('admin','updateRankingCategorySmall',array('large_id'=>$large_id,'small_id'=>$small_id,'small_value'=>$value,'updated_at'=>date('Y-m-d H:i:s',time())));
						echo $r;
						exit;
					}
					else {
						echo false;
						exit;
					}
				}
			}
		}
		$large_list = $this->service('admin','getRankingCategoryLarge',array());
		$this->view->large_list = $large_list;
		$this->_list('admin', 'getRankingCategoryList','getRankingCategoryPaging');
		$this->render('kodawari');
	}

	//店舗一覧
	public function shopeditAction () {
		$this->_list('shop', 'getShopList','getShopListPaging');
	}

	//新規店舗追加
	public function shopinfoeditAction () {
		$re = $this->_check();
		$this->view->action = $re['action'];
		$this->view->admin_id = $re['admin_id'];
		$this->view->latitude = '35.6938401';
		$this->view->longitude = '139.7035494';
	}

	public function shopconfomAction () {
		$re = $this->_check();
		$this->view->action = $re['action'];
		$this->view->admin_id = $re['admin_id'];
		$action = $re['action'];
		$submit = $this->_request->getPost('submitt');
		if($action == 'delete') {
			$shop_id = $this->_request->getPost('shop_id');
			$shop_id > 0 ? $this->service('admin','deleteShop',array('shop_id'=>$shop_id)) : '';
			$this->_list('shop', 'getShopList','getShopListPaging');
			$this->render('shopedit');
		}
		else {

			if($submit == null || empty($submit)) {
				$this->view->latitude = '35.6938401';
				$this->view->longitude = '139.7035494';
				$this->render('shopinfoedit');
			}
			else {
				$post_param = $this->getRequest()->getParams();
				$validate = $this->validate('shop','registValidate', $post_param);

				//パラメータ不正の場合
				if (isset($validate['error_flg'])) {
					$this->view->error  = $validate['error_message'];
					$this->view->error_flg = $validate['error_flg'];

					$this->view->latitude          = $validate['latitude'];
					$this->view->longitude         = $validate['longitude'];
					$this->view->shop_name         = $validate['shop_name'];
					$this->view->address           = $validate['address'];
					$this->view->shop_url          = $validate['shop_url'];
					$this->view->business_day      = $validate['business_day'];
					$this->view->regular_holiday   = $validate['regular_holiday'];
					$this->render('shopinfoedit');
				} else {

					//パラメータ正常だった場合
					$param['shop_name'] = $validate['shop_name'];
					$param['address']   = $validate['address'];
					$exists = $this->service('admin','getShopInfoExists',$param);

					$param['latitude'] = $validate['latitude'];
					$param['longitude'] = $validate['longitude'];
					$param['shop_name'] = $validate['shop_name'];
					$param['address'] = $validate['address'];
					$param['shop_url'] = $validate['shop_url'];
					$param['business_day'] = $validate['business_day'];
					$param['regular_holiday'] = $validate['regular_holiday'];
					$param['delete_flg'] = 0;

					if($action == 'insert') {
						$param['created_at'] = date('Y-m-d H:i:s',time());
						if(!$exists['shop_id'] >0){
							$ret = $this->service('shop', 'registNewShopInfo' , $param);
							if($ret === true) {
								$this->render('index');
							}
							else {
								$this->render('shopinfoedit');
							}
						}
						else {
							$this->view->error_flg = 1;
							$this->view->error = array('既にその管理者を持っている');
							$this->render('shopinfoedit');
						}
					}
				}
			}
		}
	}

	//クーポン一覧
	public function couponeditAction () {
		$this->_list('coupon', 'getCouponList','getCouponPaging');
	}

	public function couponconfomAction () {
		$re = $this->_check();
		$action = $re['action'];
		$coupon_id = $this->_request->getPost('coupon_id');
		if($action == 'delete' && $coupon_id>0) {
			$this->service('admin','deleteCoupon',array('coupon_id'=>$coupon_id));
		}
		$this->_list('coupon', 'getCouponList','getCouponPaging');
		$this->render('couponedit');
	}

	//ランキング一覧
	public function rankingeditAction () {
		$this->_list('ranking', 'getRankingList','getRankingPaging');
	}

	public function rankingdetailAction () {
		$rank_id = $this->_request->getPost('rank_id');
		$rank_id == null || empty($rank_id) ? $this->redirect('/admin/index') : $params['rank_id'] = $rank_id;

		$detail_list = $this->service('admin', 'getRankingDetailByRankId', $params);
		$this->view->detail_list = $detail_list;
	}

	public function rankingconfomAction () {
		$re = $this->_check();
		$action = $re['action'];
		$rank_id = $this->_request->getPost('rank_id');
		$rank_id == null || empty($rank_id) ? $this->redirect('/admin/index') : '';

		if($action == 'delete') {
			$param['rank_id'] = $rank_id;
			$this->service('admin','deleteRanking',$param);

			$this->_list('ranking', 'getRankingList','getRankingPaging');
			$this->render('rankingedit');
		}
	}

	//お知らせ一覧
	public function newseditAction () {
		$this->_list('ranking', 'getNewsList','getNewsPaging');
	}

	public function newsconfomAction () {
		$re = $this->_check();
		$action = $re['action'];
		$news_id = $this->_request->getPost('news_id');
		if($action == 'delete' && $news_id>0) {
			$this->service('admin','deleteNews',array('news_id'=>$news_id));
		}
		else if($action == 'insert') {
			$post_param = $this->getRequest()->getParams();
			$validate = $this->validate('news','editValidate', $post_param);

			//パラメータ不正の場合
			if (isset($validate['error_flg'])) {
				$this->view->error  = $validate['error_message'];
				$this->view->error_flg = $validate['error_flg'];

				$this->view->title          = $validate['title'];
				$this->view->status         = $validate['status'];
				$this->view->content        = $validate['content'];
				$this->view->public_start   = $post_param['public_start'];
				$this->view->public_end     = $post_param['public_end'];
			} else {

				//パラメータ正常だった場合
				$param['title'] = $validate['title'];
				$exists = $this->service('admin','getNewsInfoExists',$param);

				$param['status'] 		= $validate['status'];
				$param['content'] 		= $validate['content'];
				$param['public_start']  = $post_param['public_start'];
				$param['public_end'] 	= $post_param['public_end'];
				$param['delete_flg'] 	= 0;
				$param['created_at'] 	= date('Y-m-d H:i:s',time());

				if(!$exists['news_id'] >0){
					$ret = $this->service('admin', 'insertNews' , $param);
				}
				else {
					$this->view->error_flg = 1;
					$this->view->error = array('既にそのお知らせを持っている');
				}

			}
		}
		$this->_list('ranking', 'getNewsList','getNewsPaging');
		$this->render('newsedit');
	}
    /** トップページを表示するアクション **/
    public function checkfiletimeAction () {
		$list = $this->_scanFiles("/home/ubuntu/top3/", '*.*');

		$count = 0;
		foreach ($list as $file) {
			if (strstr($file,'Zend') or strstr($file,'_c') or strstr($file,'Smarty') or strstr($file,'vendor') or strstr($file,'.log') or strstr($file,'/img/')) {
				//無視＾＾
			} else {
				$c = $count++;
				$mod = filemtime($file);
				$fileinfo[$c]['time'] = date("Y/m/d H:i",$mod).
				$file = str_replace("/home/ubuntu/top3/", "■", $file);
				$fileinfo[$c]['name'] = $file;
			}
		}

    	$time_arr = $fileinfo;
		$key_arr = array();
		foreach ($fileinfo as $key => $val) {
		  $key_arr[] = strtotime(date('H:i:s', strtotime((string) $val['time'])));
		}
		array_multisort($key_arr, SORT_DESC, $fileinfo);
		$fileinfo = array_reverse($fileinfo);
		$this->view->fileinfo = $fileinfo;

    }

    private function _scanFiles($dir, $option) {
		   $list = array();

		   foreach(glob($dir. '*/', GLOB_ONLYDIR) as $child_dir) {
		       if ($tmp = $this->_scanFiles($child_dir, $option)) {
		           $list = array_merge($list, $tmp);
		       }
		   }

		   foreach(glob($dir. $option, GLOB_NOSORT) as $image) {
		       $list[] =  $image;
		   }

		   return $list;
	}



	//データリスト作る
	private function _list ($type,$list,$paging,$e=false) {
		$orderby = $this->_request->getPost('orderby');
		$search = $this->_request->getPost('search');
		$search == null || empty($search) ? $param['search'] = '' : $param['search'] = htmlspecialchars(trim($search),ENT_QUOTES);
		if($orderby == null || empty($orderby)) {
			switch ($type) {
				case 'shop':
				case 'user':
				case 'client':
					$param['orderby'] = 'rank_count desc';
					break;
				case 'ranking':
					$param['orderby'] = 'riguru_count desc';
					break;
				case 'coupon':
					$param['orderby'] = 'coupon.created_at desc,coupon.updated_at desc';
					break;
			}
			$orderby = '';
		}
		else {
			switch ($type) {
				case 'shop':
				case 'user':
				case 'client':
					switch ($orderby) {
						case 0:
							$param['orderby'] = 'rank_count desc';
							break;
						case 1:
							$param['orderby'] = 'ita_count desc';
							break;
						case 2:
							$param['orderby'] = 'ikitai_count desc';
							break;
						case 3:
							$param['orderby'] = 'oen_count desc';
							break;
						default:
							$param['orderby'] = 'rank_count desc';
					}
					break;
				case 'ranking':
					switch ($orderby) {
						case 0:
							$param['orderby'] = 'riguru_count desc';
							break;
						case 1:
							$param['orderby'] = 'created_at desc,updated_at desc';
							break;
						default:
							$param['orderby'] = 'riguru_count desc';
					}
					break;
				case 'coupon':
					switch ($orderby) {
						case 'new':
							$param['orderby'] = 'coupon.created_at desc,coupon.updated_at desc';
							break;
						case 'old':
							$param['orderby'] = 'created_at asc,updated_at asc';
							break;
						default:
							$param['orderby'] = 'coupon.created_at desc,coupon.updated_at desc';
					}
					break;
			}
		}

		$this->_paging($list,$paging,$param,$e);
		$this->view->orderby = $orderby;
		$this->view->search = $param['search'];
		$this->view->admin_name = $this->admin_info['name'];
	}

	private function _index () {
		$login_flg = $this->_request->getPost('login_flg');
		if ($login_flg == 1) {
			$param['email'] = $this->_request->getPost('email');
			$param['encrypted_password'] = $this->_request->getPost('encrypted_password');
			$admin_info = $this->service('admin', 'login', $param);

			if(!$admin_info['id'] > 0) {
				$this->_redirect("/admin/login/");
			}

			session_cache_limiter('none');
			session_start();
			$_SESSION["ADMINID"] = $admin_info['id'];
			$this->view->admin_id = $admin_info['id'];
			$this->view->admin_name = $admin_info['name'];
		}
		else {
			$this->view->admin_id = $this->admin_info['id'];
			$this->view->admin_name = $this->admin_info['name'];
		}
		$this->view->pref = $this->service('admin','getPrefList',array());
		$this->view->latitude = '35.6938401';
		$this->view->longitude = '139.7035494';
	}

	//パラメータチェック
	private function _check () {
		$admin_id = $this->_request->getPost('admin_id');
		if($admin_id == null || empty($admin_id)) {
			$admin = $this->admin_info;
			if(isset($admin['id']))
			{
				$admin_id = $admin['id'];
			}
		}
		$admin_id == null || empty($admin_id) ? $this->_redirect("/admin/login/") : '';
		$action = '';
		$this->_request->getPost('insert') != null ? $action = 'insert' : '';
		$this->_request->getPost('update') != null ? $action = 'update' : '';
		$this->_request->getPost('delete') != null ? $action = 'delete' : '';
		empty($action) ? $this->_redirect("/admin/login/") : '';

		return array('admin_id'=>$admin_id,'action'=>$action);
	}

	//ページング
	private function _paging ($_list,$_list_paging,$param=null,$e) {
		$this->view->admin_id = $this->admin_info['id'];
		$this->view->admin_name = $this->admin_info['name'];

		$page_temp = $this->_request->getPost('paging');
		is_numeric($page_temp) && $page_temp >0 ? $page = $page_temp : $page = 1;
		$max = 20;
		$now = floor($page);
		$page_count = 4;
		$offset = $max*($now-1);

		$params = array('offset'=>$offset,'max'=>$max);
		$param != null ? $params = array_merge($params,$param) : '';

		$list = $this->service('admin',$_list,$params);
		isset($param['search']) || !empty($param['search']) ? $paging_param['search'] = $param['search'] : $paging_param = array();
		$list_paging = $this->service('admin',$_list_paging,$paging_param);
		Zend_Paginator::setDefaultScrollingStyle('Sliding');
		//$list_paging === false ? $list_paging = array() : '';
		$_paging = array();
		if($list_paging!== false) {
			for($i=0;$i<$list_paging['count'];$i++){
				$_paging[$i] = 1;
			}
		}

		$this->paginator = Zend_Paginator::factory($_paging);
		$this->paginator->setItemCountPerPage($max);
		$this->paginator->setCurrentPageNumber($now);
		$this->paginator->setPageRange($page_count);

		$this->view->pages = $this->paginator->getPages();
		if($e == true) {
			foreach ($list as $key=>$l) {
				if(strstr($l['genre_id'], '_')!==false) {
					$list[$key]['attr'] = '子属性';
				}
				else {
					$list[$key]['attr'] = '親属性';
				}
			}
		}
		$this->view->items = $list;
	}

}