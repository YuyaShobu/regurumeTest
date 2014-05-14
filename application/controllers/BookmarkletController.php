<?php

class BookmarkletController extends AbstractController {

	public function ikitaiAction () {

	}

	public function ajaxurlAction () {
		$message = 'none';
		$pars = $this->_request->getParams();
		$html = file_get_contents($pars['url']);
		if(strpos($pars['url'],'gnavi') > 0){
			$ch = curl_init($pars['url']);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_REFERER, 'http://www.google.co.jp/');
			curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.2; SV1; .NET CLR 1.1.4322)');
			$html = curl_exec($ch);
			curl_close($ch);
		}
		$shop_name = $region = $locality = $address = $lat = $lng = '';
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
		else if(strpos($pars['url'],'gnavi') > 0) {
			preg_match("/<p id=\"info-name\".*>(.*)<\/p>/",$html,$out);
			if(isset($out[0])) {
				$shop_name = strip_tags($out[0]);
			}
			preg_match("/<span class=\"region\">(.*)<\/span><\/p>/",$html,$out);
			if(isset($out[1])) {
				$address = trim(strip_tags($out[1]));
				if(!empty($address)) {
					preg_match('/[^(都道府県)]+(都|道|府|県){1}/u',$address,$region_arr);
					if(isset($region_arr[0])) {
						$region = $region_arr[0];
						$temp = str_replace($region,'',$address);
						preg_match('/[^(市区町村)]+(市|区|町|村){1}/u',$temp,$locality_arr);
						if(isset($locality_arr[0])) {
							$locality = $locality_arr[0];
						}
					}
				}
			}
			preg_match("/<img src=\"http:\/\/maps.googleapis.com(.*)\" width=\"216\".*\/>/",$html,$out);
			if(isset($out[1])) {
				$temp = 'http://maps.googleapis.com'.trim(strip_tags($out[1]));
				$url = parse_url($temp);
				$url_pare_array = explode('&amp;',$url['query']);
				if(!is_array($url_pare_array)) {
					$url_pare_array = explode('&',$url['query']);
				}
				$l = strpos($url_pare_array[0],'|');
				$ls = substr($url_pare_array[0],$l+1);
				if($l == 0) {
					$l = strpos($url_pare_array[0],'%7C');
					$ls = substr($url_pare_array[0],$l+3);
				}
				$lat_lng_arr = explode(',',$ls);
				$lat = $lat_lng_arr[0];
				$lng = $lat_lng_arr[1];
			}
		}
		else if(strpos($pars['url'],'hotpepper') > 0) {
			$message = 'mata';
		}
		if(!empty($region) || !empty($locality)) {
			$shop = $this->service('bookmarklet','getShopExists',array('region'=>$region,'locality'=>$locality));
			$shop_arr = array();
			if($shop !== false) {
				foreach($shop as $key=>$s) {
					similar_text($s['shop_name'],$shop_name,$percent);
					similar_text($s['address'],$address,$percent_add);
					if($percent >= 70 and $percent_add >= 90) {
						$shop_arr[$s['shop_id']]['address'] = $s['address'];
						$shop_arr[$s['shop_id']]['shop_name'] = $s['shop_name'];
					}
				}
				$shop = $shop_arr;
			}
			$out_arr = array('shop'=>$shop,'lat'=>$lat,'lng'=>$lng,'shop_name'=>$shop_name,'region'=>$region,'locality'=>$locality,'address'=>$address,'message'=>$message);
		}
		else {
			$out_arr= array('message'=>$message);
		}
		echo json_encode($out_arr);
		exit;
	}

	public function bookmarkletAction () {
		$is_login = false;
		if(isset($_SESSION["USERID"]) && $_SESSION["USERID"] > 0) {
			$is_login = true;
		}
		$pars = $this->_request->getParams();
		$this->view->site_url = $pars['url'];
		$this->view->is_login = $is_login;
	}

	public function ajaxshopwanttoAction () {
		$ret = true;
		$user_id = '';
		if(isset($_SESSION["USERID"]) && $_SESSION["USERID"] > 0) {
			$inputData = array();
			$user_id = (int)$_SESSION["USERID"];
			$shop_id = $this->getRequest()->getParam('shop_id');
			$shop_name = $this->getRequest()->getParam('shop_name');
			$address = $this->getRequest()->getParam('address');
			$region = $this->getRequest()->getParam('region');
			$locality = $this->getRequest()->getParam('locality');
			$lat = $this->getRequest()->getParam('lat');
			$lng = $this->getRequest()->getParam('lng');
			if(!empty($region) && !empty($locality)) {
				$re = $this->service('bookmarklet','getPrefCode',array('value'=>$region));
				$re !== false ? $pref_code = $re['pref_code'] : $pref_code = '';
				$re = $this->service('bookmarklet','getCityCode',array('value'=>$locality,'pref_code'=>$pref_code));
				$re !== false ? $city_code = $re['city_code'] : $city_code = '';
				if(empty($pref_code) && empty($city_code)) {
					echo false;
					exit;
				}
				empty($shop_id) && !empty($shop_name) && !empty($address) ? $in_res = $this->service('bookmarklet','insertShopExists',array('shop_name'=>$shop_name,'address'=>$address,'pref_code'=>$pref_code,'city_code'=>$city_code)) : $ret = false;
				$ret === true && isset($in_res) && $in_res === false ? $res = $this->service('bookmarklet','insertShop',array('shop_name'=>$shop_name,'address'=>$address,'pref_code'=>$pref_code,'city_code'=>$city_code,'latitude'=>$lat,'longitude'=>$lng,'delete_flg'=>0,'created_at'=>date("Y-m-d H:i:s"))) : $res = false;
				$res !== false ? $shop_id = $res : '';
				$voting_kind = Utility::CONST_VALUE_VOTING_BUTTON_WANTTO;
				if( !empty($user_id) && !empty($shop_id) ){
					$inputData['user_id'] = $user_id;
					$inputData['shop_id'] = $shop_id;
					$inputData['voting_kind'] = $voting_kind;
					// データ存在チェック
					$data = $this->service('voting','checkShopVotingExist',$inputData);
					if ( $data > 0 ) {
						// 削除されたデータがあるため、delete_flg=0に戻す
						$inputData['delete_flg'] = 0;
						$inputData['updated_at'] = date("Y-m-d H:i:s");
						$ret = $this->service('voting','deleteShopVoting',$inputData);
						$ret >0 ? $ret = true : $ret = false;
					} else {
						$inputData['created_at'] = date("Y-m-d H:i:s");
						$inputData['updated_at'] = date("Y-m-d H:i:s");
						$ret = $this->service('voting','registShopVoting',$inputData);
						$ret >0 ? $ret = true : $ret = false;
					}
				}
				else {
					$ret = false;
				}
			}
			else {
				$ret = false;
			}
		}
		else {
			$ret = false;
		}
		echo $ret;
		exit;
	}

}