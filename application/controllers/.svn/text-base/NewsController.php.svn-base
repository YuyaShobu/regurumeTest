<?php
/*
 * 2013.9.30 xiuhui yang
 * お知らせ詳細ページ
 *
 */

class NewsController extends AbstractController {

    /** 詳細ページ */
    public function indexAction () {

        //パラメータ受け取り
        $news_id = $this->getRequest()->getParam('id');
        if ( isset($news_id) !="") {
            // 詳細情報を取得
            $res = $this->service('news','getNewsDetail',$news_id);
            $this->view->detail =  $res;
        } else {
            $this->_redirect("/index/index");
        }
    }

}