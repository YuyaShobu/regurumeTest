<?php
/**
 *
 * @package   TopController
 *
 * @copyright 2013/08
 * @author    xiuhui yang
 *
 */
class TopController extends AbstractController {

    Const DISPLAY_NUM_INIT =  8;//1ページ目表示件数(件)
    Const DISPLAY_NUM = 8;//1ページ表示件数(件)

    /**トップ画面 */
    public function indexAction ()
    {
        //トップページの初期表示
        $params['now_post_num'] = 0;
        $params['get_post_num'] = self::DISPLAY_NUM_INIT;
        if ($this->user_info['user_id']) {
            $params['user_id'] =  $this->user_info['user_id'];
        }
        $top_info = $this->service('top','getTopInfo', $params);
        //画面へ表示
        $this->view->top= $top_info;
        $this->view->display_numinit= self::DISPLAY_NUM_INIT;
        $this->view->display_num= self::DISPLAY_NUM;
    }


    /** ランキング一覧「もっと見る」ajax取得アクション */
    public function ajaxrankingmoreAction () {

        //viewは読み込まない
        $this->_helper->viewRenderer->setNoRender();
        // パラメター取得
        //データ抽出limit順番
        $limitnum= $this->getRequest()->getParam('limitnum');
        $flg= $this->getRequest()->getParam('flg');
        //検索条件
        $posts['now_post_num'] = $limitnum;
        $posts['get_post_num'] = self::DISPLAY_NUM;
        if ($this->user_info['user_id']) {
            $posts['user_id'] =  $this->user_info['user_id'];
        }

        //該当件数のデータ抽出;
        $res = $this->service('top','getTopInfo', $posts);
        if ( $flg == 'new' && is_array($res['new_rank']) && count($res['new_rank']) > 0 ) {
            $tags = $this->_getReadmoreTag($res['new_rank']);
            echo $tags;
        } else if ($flg == 'reguru' && is_array($res['reguru_rank']) && count($res['reguru_rank']) > 0) {
            $tags = $this->_getReadmoreTag($res['reguru_rank']);
            echo $tags;
        } else if($flg == 'pv' && is_array($res['pv_rank']) && count($res['pv_rank']) > 0) {
            $tags = $this->_getReadmoreTag($res['pv_rank']);
            echo $tags;
        } else if ($flg == 'timeline' && is_array($res['time_line']) && count($res['time_line']) > 0 ) {
            $tags = $this->_getReadmoreTag($res['time_line']);
            echo $tags;
        }
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
    private function _setCommonView()
    {
        //都道府県マスタ取得
        $pref = $this->service('ranking','getPrefList', '');
        $this->view->pref= $pref;

        //大カテゴリ一覧
        $param['ctg'] = 'Large';
        $list = $this->service('ranking','getCategoryList', $param);
        $this->view->category_list =  $list;
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
         for ($i=0; $i<count($res); $i++) {
            $ret = $res[$i];
            $divtag = "
                        <div class=\"thumbBox01\">
                            <div class=\"deco01\"></div>
                            <p class=\"thumArea01\">{$ret['pref']}</p>
                            <div class=\"mosaic-block01 fade\"><a href=\"/ranking/detail/id/{$ret['rank_id']}\" target=\"_blank\" class=\"mosaic-overlay\" style=\"display: inline; opacity: 0;\"></a>
                            <div class=\"mosaic-backdrop\" style=\"display: block;\">
                                <div class=\"space02\">
                                <p class=\"thumRankTitle\"><a href=\"#\">{$ret['title']}</a></p>
                                <ul class=\"thumPhoto01\">
                        ";
                $ultags = "";
                    for ($j=0; $j<count($ret['detail']); $j++) {
                        $dret = $ret['detail'][$j];
                        $litag ="
                                <li>
                                    <span class=\"iconRank{$dret['rank_no']}\"></span>
                                    <img src=\"/img/sp/ranking/{$dret['photo']}\" width=\"60\" height=\"60\"  alt=\"\" title=\"{$dret['shop_name']}\" />
                                </li>
                                ";
                        $ultags .= $litag;
                    }
                $divtag = $divtag.$ultags ."
                                    </ul>
                                <div class=\"thumRankReaction clearfix\">
                                    <div class=\"text-right\">{$ret['page_view']} <span>view</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                <div class=\"thumUser\"><a href=\"/user/myranking/id/{$ret['user_id']}\">
                    <img src=\"/img/sp/user/{$ret['user_photo']}\"  width=\"60\" height=\"60\"  alt=\"\" title=\"{$ret['user_name']}\" /> {$ret['user_name']}</a>
                </div>
            </div>";
            $tags .= $divtag;
        }
        return $tags;
    }

}