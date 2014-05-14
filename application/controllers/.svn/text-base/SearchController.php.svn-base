<?php

require_once (LIB_PATH ."/Utility.php");
class SearchController extends AbstractController {

    //Const PageCnt = 3; //1ページ表示件数(件)
    Const DISPLAY_NUM_INIT =  9;//1ページ目表示件数(件)20
    Const DISPLAY_NUM = 6;//1ページ表示件数(件)10

    /** サーチトップ画面 */
    public function indexAction () {

    	//各カテゴリーの値を持ってくる
        $category_info = $this->service('top3','getCategoryTop3','');
        $this->view->category_list      = $category_info;
		$this->view->user_name =  $this->user_info['user_name'];
		$this->view->user_id   =  $this->user_info['user_id'];
    }


    /** 入力アクション */
    public function latlonAction () {
        // 入力値取得
        $req = $this->getRequest();
    }

    /** カテゴリー別検索結果表示 */
    public function categoryAction () {

        $category_id = $this->getRequest()->getParam('category_id');
        //カテゴリーIDがないことはありえないのでリダイレクト(直打ち防止)

		if (!$category_id) {
			$this->_redirect("login/index/");
		}
		$param['category_id'] = $category_id;

		$ret = $this->service('shop' , 'getShopRankingFromCategory' , $param);
		$this->view->categoly_ranking_info = $ret;
    }

    /** 応援されているお店一覧取得 */
    public function oenAction () {

		$ret = $this->service('oen' , 'getOenRanking' , '');
		$this->view->categoly_ranking_info = $ret;
    }

    /** ランキング検索アクション */
    public function searchrankingAction () {
        //入力値取得
        $req = $this->getRequest();
        $posts = $req->getPost();
//        if ( is_array($posts) && count($posts)>0 ) {

            $posts['now_post_num'] = 0;
            $posts['get_post_num'] = self::DISPLAY_NUM_INIT;
            //一覧データ取得して表示
            $rank_list = $this->service('ranking','getRankList', $posts);
            $this->view->rank_list= $rank_list;

            //結果画面チェックボックス再表示
            if ( isset($posts['large_id']) && is_array($posts['large_id']) && count($posts['large_id']) > 0 ) {
                for ( $t=0; $t<count($posts['large_id']); $t++) {
                    $large_id = $posts['large_id'][$t];
                    $check_list[$t]['large_id'] =  htmlspecialchars($large_id);

                    //チェックされた小カテゴリ一覧データ
                    //$param = array();
                    //$param['large_id'] = $large_id;
                    //$param['ctg'] = 'Small';
                    //$res= $this->service('ranking','getCategoryList', $param);
                    //$check_list[$t]['smalllist'] = $res;
                }
                $this->view->check_list= $check_list;
            }
//        }
        $this->view->display_numinit= self::DISPLAY_NUM_INIT;
        $this->view->display_num= self::DISPLAY_NUM;
        //初期処理用のプルダウンセット
        $this->_setCommonView($posts);
        $this->view->info= $posts;
    }

    /** お店検索アクション */
    public function searchshopAction () {
        //入力値取得
        $req = $this->getRequest();
        $posts = $req->getPost();

        //if ( is_array($posts) && count($posts)>0 ) {
        $posts['now_post_num'] = 0;
        $posts['get_post_num'] = self::DISPLAY_NUM_INIT;
        //一覧データ取得して表示
        $shop_list = $this->service('ranking','getShopList', $posts);
        $this->view->shop_list= $shop_list;
       //}

        $this->view->display_numinit= self::DISPLAY_NUM_INIT;
        $this->view->display_num= self::DISPLAY_NUM;
        //初期処理用のプルダウンセット
        $this->_setCommonView();
        $this->view->info= $posts;
    }


    /** ajax市町区取得アクション */
    public function ajaxgetcityAction () {
        //viewは読み込まない
        $this->_helper->viewRenderer->setNoRender();
        // パラメター取得
        $pref_code= $this->getRequest()->getParam('pref_code');
        if(isset($pref_code) && $pref_code !="-1") {
            $param['pref_code'] = htmlspecialchars($pref_code);
            //データ抽出
            $res = $this->service('ranking','getCityList', $param);
            $encode = json_encode($res);
            echo $encode;
            exit;
        }else {
            echo false;
            exit;
        }
    }

    /** ajaxジャンルマスタ取得アクション */
    public function ajaxgetgenreAction () {
        //viewは読み込まない
        $this->_helper->viewRenderer->setNoRender();
        // パラメター取得
        $genre1= $this->getRequest()->getParam('genre1');
        if(isset($genre1) && $genre1 !="-1") {
            $param['genre_id'] = htmlspecialchars($genre1);
            //データ抽出
            $res = $this->service('genre','getGenreChild', $param);
            $encode = json_encode($res);
            echo $encode;
            exit;
        }else {
            echo false;
            exit;
        }
    }

    /** ランキング一覧「もっと見る」ajax取得アクション */
    public function ajaxrankingmoreAction () {

        //viewは読み込まない
        $this->_helper->viewRenderer->setNoRender();
        // パラメター取得
        //データ抽出limit順番
        $limitnum= $this->getRequest()->getParam('limitnum');
        //検索条件
        $posts['pref']= $this->getRequest()->getParam('pref');
        $posts['city']= $this->getRequest()->getParam('city');
        $posts['large_id']= $this->getRequest()->getParam('checks');
        $posts['search_keyword']= $this->getRequest()->getParam('keyword');
        $posts['now_post_num'] = $limitnum;
        $posts['get_post_num'] = self::DISPLAY_NUM;
        //該当件数のデータ抽出;
        $res = $this->service('ranking','getRankList', $posts);
        //タグ作成
        $tags = Utility::makeRankMoreReadTag($res);
        echo $tags;
    }



    /** ショップ一覧「もっと見る」ajax取得アクション */
    public function ajaxshopmoreAction () {

        //viewは読み込まない
        $this->_helper->viewRenderer->setNoRender();
        // パラメター取得
        //データ抽出limit順番
        $limitnum= $this->getRequest()->getParam('limitnum');
        //検索条件
        $posts['pref']= $this->getRequest()->getParam('pref');
        $posts['city']= $this->getRequest()->getParam('city');
        $posts['genre1']= $this->getRequest()->getParam('genre1');
        $posts['genre2']= $this->getRequest()->getParam('genre2');
        //$posts['large_id']= $this->getRequest()->getParam('checks');
        $posts['search_keyword']= $this->getRequest()->getParam('keyword');
        $posts['now_post_num'] = $limitnum;
        $posts['get_post_num'] = self::DISPLAY_NUM;
        //データ抽出;
        $res = $this->service('ranking','getShopList', $posts);
        //タグ作成
        $tags = Utility::makeShopMoreReadTag($res);
        echo $tags;
    }


//▼▼▼▼▼▼▼▼チップス▼▼▼▼▼▼▼▼▼▼▼▼

    /**
     * 検索画面初期表示共通情報セット
     * @author: xiuhui yang
     * @param none
     *
     *
     * @return none
     *
    */
    private function _setCommonView($posts_array="")
    {
        //都道府県マスタ取得
        $pref = $this->service('ranking','getPrefList', '');
        $this->view->pref= $pref;

        //大カテゴリ一覧
        $param['ctg'] = 'Large';
        $res = $this->service('ranking','getCategoryList', $param);
        if ($res) {
            //各小カテゴリ一覧取得
            $list = $this->_get_small_categoryname($res,$posts_array);
            $this->view->category_list =  $list;
        }
        //ショップ親ジャンル一覧
        $this->view->parent_genre = $this->service('admin','getGenreParent',array(''));
        //ショップ子ジャンル一覧
        //$this->view->parent_child = $this->service('genre','getGenreChild',array(''));
    }

    /**
     * 小カテゴリ名取得
     * @author: xiuhui yang
     * @param string $large_id
     *
     *
     * @return string $res
     *
    */
    private function _get_small_cgname($large_id,$small_id) {
        $param['large_id'] = htmlspecialchars($large_id);
        //小カテゴリマスタ取得
        $param['ctg'] = 'Small';
        $res = $this->service('ranking','getCategoryList', $param);
        $small_cgname = "";
        foreach ($res as $key => $val){
            if ($val['small_id'] == $small_id) {
                $small_cgname = $val['small_value'];
            }
        }
        return $small_cgname;
    }

    /**
     * 小カテゴリ名取得
     * @author: xiuhui yang
     * @param string $large_id
     *
     *
     * @return string $res
     *
    */
    private function _get_small_categoryname($list,$posts_array) {
        for ( $t=0; $t<count($list); $t++) {
            $large_id = $list[$t]['large_id'];
            //小カテゴリ一覧データ
            $param = array();
            $param['large_id'] = $large_id;
            $param['ctg'] = 'Small';
            $res= $this->service('ranking','getCategoryList', $param);
            $list[$t]['smalllist'] = $res;
            if (isset($posts_array['smalllist_'.$large_id]) && $posts_array['smalllist_'.$large_id]!="") {
                $list[$t]['selected_smallid'] = $posts_array['smalllist_'.$large_id];
            } else {
                $list[$t]['selected_smallid'] = "";
            }
        }
        return $list;
    }

}


