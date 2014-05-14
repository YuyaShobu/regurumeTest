<?php

/**
 *
 * @package   TagController
 *
 * @copyright 2013/10
 * @author    xiuhui yang
 *
 */
require_once (LIB_PATH ."/Utility.php");
class TagController extends AbstractController {

    Const DISPLAY_NUM_INIT =  6;//1ページ目表示件数(件)20
    Const DISPLAY_NUM = 6;//1ページ表示件数(件)10

    /** サーチトップ画面 */
    public function indexAction () {

        //パラメータ受け取り
        $tag_name = $this->getRequest()->getParam('name');
        if ( isset($tag_name) && $tag_name !="") {
            $params['tag_name'] = $tag_name;
            $params['now_post_num'] = 0;
            $params['get_post_num'] = self::DISPLAY_NUM_INIT;
            //一覧データ取得して表示
            $rank_list = $this->service('ranking','getTagRankList', $params);
            $this->view->rank_list= $rank_list;
            $this->view->display_numinit= self::DISPLAY_NUM_INIT;
            $this->view->display_num= self::DISPLAY_NUM;
            $this->view->tag_name = $tag_name;
            //パンくずにユーザー名を追加
            $this->_getPankuzuList($tag_name);
        } else {
            //トップページ遷移する
            $this->_redirect("/index/index");
        }

    }


    /** ランキング一覧「もっと見る」ajax取得アクション */
    public function ajaxrankingmoreAction () {

        //viewは読み込まない
        $this->_helper->viewRenderer->setNoRender();
        // パラメター取得
        //データ抽出limit順番
        $limitnum= $this->getRequest()->getParam('limitnum');
        $tag_name = $this->getRequest()->getParam('tagname');

        //検索条件
        if ($limitnum !="" && $tag_name !="") {
            $posts['tag_name'] =  $tag_name;
            $posts['now_post_num'] = $limitnum;
            $posts['get_post_num'] = self::DISPLAY_NUM;
            //該当件数のデータ抽出;
            $list = $this->service('ranking','getTagRankList',$posts);
            //タグ作成
            $tags = "";
            $tags = Utility::makeRankMoreReadTag($list);
            echo $tags;
        }
    }


//▼▼▼▼▼▼▼▼チップス▼▼▼▼▼▼▼▼▼▼▼▼

}


