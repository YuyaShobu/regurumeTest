<?php

/**
 *
 * @package   ReguruController
 *
 * @copyright 2013/08
 * @author    xiuhui yang
 *
 */

class ReguruController extends AbstractController {


    /** ranking詳細画面「リグル」ボタン押された登録アクション */
    public function ajaxreguruAction () {
        $this->_caseUnloginRedirect();
        $ret = true;
        //viewは読み込まない
        $this->_helper->viewRenderer->setNoRender();
        // パラメター取得
        $inputData = array();
        $inputData['REGURU_UID'] =$this->user_info['user_id'];
        $inputData['RANK_ID'] = $this->getRequest()->getParam('rank_id');
        $cuid = $this->getRequest()->getParam('cuid');
        $inputData['COMMENT'] = $this->getRequest()->getParam('comment');
        $inputData['DELETE_FLG'] = 1;
        $inputData['UPDATED_AT'] = date("Y-m-d H:i:s");
        // りぐったフラグ立つ
        //$ret = $this->service('reguru','updateReguru',$inputData);
        // データ存在チェック
        $data = $this->service('reguru','checkRankingReguru1',$inputData);
        if ( $data["CNT"] > 0 ) {
            $inputData['UPDATED_AT'] = date("Y-m-d H:i:s");
            $ret = $this->service('reguru','updateReguru',$inputData);
        } else {
            $inputData['CREATED_AT'] = date("Y-m-d H:i:s");
            $ret = $this->service('reguru','insertReguru',$inputData);
        }
        $referbttag="";
        if ($ret != false){

            $referbttag = "<li><a href=\"javascript:;\" class=\"btn btnReguru btnCD\" id=\"reguru_button\" onclick=\"ajax_reguru('cancel');\">　リグルメしました　</a></li>";
            //follower一覧のタイムラインに登録
            $inputData['CREATED_USER_ID'] = $cuid;
            $this->service('reguru','insertTimeline',$inputData);
        }
        echo $referbttag;
        //$encode = json_encode($ret);
        //echo $encode;
        //exit;
    }


    /** ranking詳細画面「リグルしました」ボタン押されたアクション、参考データ論理削除する */
    public function ajaxregurucancelAction () {
        $ret = true;
        //viewは読み込まない
        $this->_helper->viewRenderer->setNoRender();
        // パラメター取得
        $inputData = array();
        $inputData['REGURU_UID'] =$this->user_info['user_id'];
        $inputData['RANK_ID'] = $this->getRequest()->getParam('rank_id');
        $inputData['COMMENT'] = '';
        $inputData['DELETE_FLG'] = 0;
        $inputData['UPDATED_AT'] = date("Y-m-d H:i:s");

        // データ論理削除する
        $ret = $this->service('reguru','updateReguru',$inputData);


        $referbttag="";
        if ($ret != false){
            $referbttag = "<li><a href=\"javascript:;\" class=\"btn btnReguru btnC01\" id=\"reguru_button\" onclick=\"ajax_reguru('reguru');\">　リグルメ　</a></li>";
        }
        echo $referbttag;

        //$encode = json_encode($ret);
        //echo $encode;
        //exit;
    }

}