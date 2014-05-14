<?php

class CouponcontenueController extends Zend_Controller_Action {

	public function indexAction () {
        //viewは読み込まない
        //$this->_helper->viewRenderer->setNoRender();
        //if ($_SERVER['REMOTE_ADDR'] != '127.0.0.1') {
        //    exit;
        //}
        require_once (MODEL_DIR ."/service/adminService.php");
        $service = new adminService();
        $arr = $service->updateCouponContenue(array(''));
        if ( $arr ) {
			foreach($arr as $a) {
				$service->updateCouponByCouponId(array('coupon_id'=>$a['coupon_id'],'public_end'=>$a['public_end']));
				//$this->service('admin','updateCouponByCouponId',array('coupon_id'=>$a['coupon_id'],'public_end'=>$a['public_end']));
			}
        }
		//exit;
	}

}