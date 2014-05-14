<?php

/**
 *
 * @package   RankingController
 *
 * @copyright 2013/07
 * @author    xiuhui yang
 *
 */

require_once (LIB_PATH ."/Utility.php");

class RankingController extends AbstractController {
    //りぐる一覧表示件数
    Const DISPLAY_NUM_INIT =  3;//1ページ目表示件数(件)
    Const DISPLAY_NUM = 2;//1ページ表示件数(件)


    /** ランキングトップ画面 */
    public function indexAction () {
    	header('Location: http://regurume.com');
        //ランキング一覧データ取得
        $res = $this->service('ranking','getRankingList',$param="");
        //一覧データ画面に表示
        $this->view->list = $res;
    }


    /** 入力画面 */
    public function inputAction () {
        //ログイン状況チェック、未ログインの場合ログイン画面に飛ばす
        $this->_caseUnloginRedirect();
        //セッションクリア
        $_SESSION['photo'] = array();
        $_SESSION['check_list'] = array();
        $_SESSION['post_info'] = array();
        //画像削除フラグ
        $posts['photo_delflg_1'] = "";
        $posts['photo_delflg_2'] = "";
        $posts['photo_delflg_3'] = "";
        //テンポラリフォルダのごみ画像ファイル削除
        $updir  = ROOT_PATH."/img/tmp/ranking/". $this->user_info['user_id'];
        Utility::removeDirectory($updir);
        //チェックボックス、プルダウン値デフォルトセット
        $this->_setDefaultToForm();
    }

    /** 編集画面 */
    public function editAction () {
        //ログイン状況チェック、未ログインの場合ログイン画面に飛ばす
        $this->_caseUnloginRedirect();
        //セッションクリア
        $_SESSION['photo'] = array();
        $_SESSION['check_list'] = array();
        $_SESSION['post_info'] = array();
        //画像削除フラグ
        $posts['photo_delflg_1'] = "";
        $posts['photo_delflg_2'] = "";
        $posts['photo_delflg_3'] = "";
        //テンポラリフォルダのごみ画像ファイル削除
        $updir  = ROOT_PATH."/img/tmp/ranking/". $this->user_info['user_id'];
        Utility::removeDirectory($updir);

        //パラメータを受け取る
        $req = $this->getRequest();
        $posts = $req->getPost();
        $check_list = array();
        if ( isset($posts['rank_id']) != "" ) {
            $param['user_id'] = $this->user_info['user_id'];
            $param['rank_id'] = $posts['rank_id'];
            // 詳細情報を取得
            $res = $this->service('ranking','getRankingDetail',$param);
            //画面表示
            $this->view->detail =  $res;
            $this->view->tag_list =  $res['tags'];
            $this->view->check_list = $res['category'];
        }
        //チェックボックス、プルダウン値デフォルトセット
        $this->_setDefaultToForm();
    }

    /**
     * 確認画面から戻ってきた場合
     *
     */
    public function backtoinputAction()
    {
        //ログイン状況チェック、未ログインの場合ログイン画面に飛ばす
        $this->_caseUnloginRedirect();
        //確認画面のhidden項目取得
        $req = $this->getRequest();
        $posts = $req->getPost();

        //画像削除フラグ
        $posts['photo_delflg_1'] = "";
        $posts['photo_delflg_2'] = "";
        $posts['photo_delflg_3'] = "";
        //テンポラリフォルダの画像ファイルを再表示
        for ( $i=1; $i<=3; $i++) {
            if( isset($_SESSION['photo']['photo_'.$i]) && !empty($_SESSION['photo']['photo_'.$i]) ){
                $upfile = $_SESSION['photo']['photo_'.$i];
                $photo_name = basename($upfile['tmp_name']);
                //テンポラリパス
                //$tmp_file_path  = ROOT_PATH."/img/tmp/ranking/".$photo_name;
                //画像ファイル削除
                //Utility::deleteUpFile($tmp_file_path);
                $photo[$i] = $photo_name;
            }
        }

        //画像再表示のため
        if (isset($photo[1])) {
            $this->view->photo_1 = $photo[1];
        }
        if (isset($photo[2])) {
            $this->view->photo_2 = $photo[2];
        }
        if (isset($photo[3])) {
            $this->view->photo_3 = $photo[3];
        }

        //大小カテゴリチェックされた情報表示
        $check_list = $_SESSION['check_list'];
        //チェックボックス初期表示値セット
        $this->_setDefaultToForm();

        //入力値画面再セット
        $this->view->detail =  $posts;
        //カテゴリ
        $this->view->check_list =  $check_list;
        //入力画面を表示する
        $this->_helper->viewRenderer('input');
    }

    /**
     * 確認画面から戻ってきた場合
     *
     */
    public function backtoeditAction()
    {
        //ログイン状況チェック、未ログインの場合ログイン画面に飛ばす
        $this->_caseUnloginRedirect();
        //確認画面のhidden項目取得
        $req = $this->getRequest();
        $posts = $req->getPost();

        //画像削除フラグ
        $posts['photo_delflg_1'] = "";
        $posts['photo_delflg_2'] = "";
        $posts['photo_delflg_3'] = "";
        //テンポラリフォルダの画像ファイルを再表示
        for ( $i=1; $i<=3; $i++) {
            if( isset($_SESSION['photo']['photo_'.$i]) && !empty($_SESSION['photo']['photo_'.$i]) ){
                $upfile = $_SESSION['photo']['photo_'.$i];
                $photo_name = basename($upfile['tmp_name']);
                $photo[$i] = $photo_name;
            }
        }
        //画像再表示のため
        if (isset($photo[1])) {
            $this->view->photo_1 = $photo[1];
        }
        if (isset($photo[2])) {
            $this->view->photo_2 = $photo[2];
        }
        if (isset($photo[3])) {
            $this->view->photo_3 = $photo[3];
        }
        //大小カテゴリチェックされた情報表示
        $check_list = $_SESSION['check_list'];

        //チェックボックス初期表示値セット
        $this->_setDefaultToForm();

        //入力値画面再セット
        $this->view->detail =  $posts;

        //カテゴリ
        $this->view->check_list =  $check_list;
        //入力画面を表示する
        $this->_helper->viewRenderer('edit');
    }

    /** ランキング登録確認画面 */
    public function comfirmAction () {

        //ログイン状況チェック、未ログインの場合ログイン画面に飛ばす
        $this->_caseUnloginRedirect();
        //入力値取得
        $req = $this->getRequest();
        $posts = $req->getPost();
        $post_param = $this->getRequest()->getParams();
        if ( isset($post_param['input_flg']) == "1"  && $this->user_info['user_id'] !="") {
	        $post_param['photo1'] = $_FILES["photo_1"];
	        $post_param['photo2'] = $_FILES["photo_2"];
	        $post_param['photo3'] = $_FILES["photo_3"];

	        //バリデーション
	        $validate = $this->validate('ranking','registValidate', $post_param);
	        //パラメータ不正の場合
	        if (isset($validate['error_flg'])) {
	            //値を保持して入力画面へ飛ばす。
	            $this->_buck_regist_page($validate);
	        } else {
	            //パラメータ正常だった場合
	            //画像情報をテンポラリフォルダへ保存
	            $user_id = $this->user_info['user_id'];
	            //画像再表示
	            for ( $i=1; $i<=3; $i++) {
	                if ( isset($_SESSION['photo']['photo_'.$i]['tmp_name']) ) {
	                    $photo[$i] = basename($_SESSION['photo']['photo_'.$i]['tmp_name']);
	                }
	                //セッションにアップロードファイルの情報がある場合はそのファイル名をセットする
	                if ( $_FILES["photo_".$i][ 'error' ] == UPLOAD_ERR_OK && is_uploaded_file( $_FILES["photo_".$i]['tmp_name'] ) ) {
	                    $upfile = $_FILES["photo_".$i];
	                    $shop_id = $posts['shop_id_'.$i];
	                    //ユニーク画像名を保持するためtmp/ranking/[user_id]/date_[userid]_[shopid]_[rankid]_no[1].jpg形式で画像名作成
	                    $new_name = $this->_make_imagename($upfile,$shop_id,$user_id,$i);
	                    //テンポラリパスimg/tmp/ranking/rank_id
	                    $updir  =  ROOT_PATH."/img/tmp/ranking/". $this->user_info['user_id'];
	                    // ディレクトリがない場合は作成する
	                    if (!file_exists($updir) && !is_dir($updir)) {
	                        mkdir($updir,0777,true);
	                    }
	                    $path  = $updir.'/'.$new_name;
	                    //テンポラリフォルダへ保存、ファイルパスなどをセッションに格納
	                    $this->_tmp_file_upload($upfile,$path,$i);
	                    $photo[$i] =  basename($_SESSION['photo']['photo_'.$i]['tmp_name']);
	               } else {
	                    if ( $posts['photo_delflg_'.$i] == "1") {
	                        $_SESSION['photo']['photo_'.$i] = "";
	                    }
	               }
	               //店名クリアされた場合はDBに登録しない
	               if ( isset($posts['shop_name_'.$i]) == "") {
	                   $posts['shop_id_'.$i] = "";
	                   $_SESSION['photo']['photo_'.$i] = "";
	               }
	            }
	            //大小カテゴリチェックされた情報表示
	            $check_list = $this->_setCategoryToForm($posts);
	            //大カテゴリマスター一覧(確認ページ大カテゴリ名表示用)
	            $this->_setDefaultToForm();

	            //チェックされた情報をセッションの保存
	            $_SESSION['check_list'] = $check_list;
	            //確認画面表示
	            //入力値


	            //本来はバリデーションクラスでやるけれどちょっと後から対応
	            $param['explain_1'] = strip_tags($param['explain_1']);
	            $param['explain_2'] = strip_tags($param['explain_2']);
	            $param['explain_3'] = strip_tags($param['explain_3']);
	            $this->view->detail = $posts;
	            $this->view->check_list =  $check_list;

	            //画像表示
	            if(isset($photo[1])) {
	                $this->view->photo_1 = $photo[1];
	            }
	            if(isset($photo[2])) {
	                $this->view->photo_2 = $photo[2];
	            }
	            if(isset($photo[3])) {
	                $this->view->photo_3 = $photo[3];
	            }
	        }
        } else {
            //いきなりこのアクションを叩かれた場合トップページに遷移する
            $this->_redirect("/ranking/input");
        }

    }


    /** ランキング登録画面 */
    public function completeAction () {
        $req = $this->getRequest();
        $posts = $req->getPost();
        $posts['user_id'] = $this->user_info['user_id'];
        if ( isset($posts['comfirm_flg']) == "1"  && $this->user_info['user_id'] !="") {
            if ( $posts['rank_id'] != ""  ) {
	            //編集
	            $ret = $this->service('ranking','updateRanking',$posts);
            } else {
                //新規登録
                $ret = $this->service('ranking','registRanking',$posts);
            }

            if ( $ret['status'] == true ) {
                //テンポラリフォルダのごみ画像ファイル削除
                $updir  = ROOT_PATH."/img/tmp/ranking/". $this->user_info['user_id'];
                Utility::removeDirectory($updir);
                //セッション情報クリア
                $_SESSION['photo'] = array();
                $this->view->msg = "登録完了しました。";
                $this->view->rank_id = $ret['rank_id'];
            } else {
                //エラー画面表示
                $this->view->msg = "登録処理の際にエラーが発生しました。大変お手数ですが、info@regurume.com までご連絡いただけますと幸いです。";
            }
        } else {
            //いきなりこのアクションを叩かれた場合トップページに遷移する
            $this->_redirect("/index/index");
        }
    }

    /** 順位変更画面 */
    public function sortAction () {
        //ログイン状況チェック、未ログインの場合ログイン画面に飛ばす
        //$this->_caseUnloginRedirect();
        //パラメータを受け取る
        $req = $this->getRequest();
        $posts = $req->getPost();
        $rank_id = $posts['rank_id'];
        //$rank_id = $this->getRequest()->getParam('id');
        $check_list = array();
        if ( $rank_id != "" ) {
            $param['user_id'] = $this->user_info['user_id'];
            $param['rank_id'] = $rank_id;
            // 詳細情報を取得
            $res = $this->service('ranking','getRankingDetail',$param);


            //画面表示
            $this->view->detail =  $res;
            $this->view->tag_list =  $res['tags'];
            $this->view->check_list = $res['category'];
        } else {
            //いきなりこのアクションを叩かれた場合トップページに遷移する
            $this->_redirect("/index/index");
        }
    }

    /** 順位変更(上へ)アクション */
    public function uplistAction () {
        //ログイン状況チェック、未ログインの場合ログイン画面に飛ばす
        $this->_caseUnloginRedirect();
        //パラメータを受け取る
        $req = $this->getRequest();
        $posts = $req->getPost();
        if ( isset($posts['rank_id']) != "" && isset($posts['rank_no']) != "") {
            // 入れ替えの最新情報をDBに更新
            if ($posts['rank_no'] == "2") {
                $ret = $this->_changeRankNo1to2($posts);
            } elseif ($posts['rank_no'] == "3") {
                $ret = $this->_changeRankNo2to3($posts);
            }
            //変更後詳細情報を取得して順位変更画面再表示
            $param['user_id'] = $this->user_info['user_id'];
            $param['rank_id'] = $posts['rank_id'];
            // 詳細情報を取得
            $res = $this->service('ranking','getRankingDetail',$param);
            $this->view->detail =  $res;
            $this->_helper->viewRenderer('sort');
            //$this->_redirect("/ranking/detail/id/".$posts['rank_id']);

        } else {
            //いきなりこのアクションを叩かれた場合トップページに遷移する
            $this->_redirect("/index/index");
        }
    }

    /** 順位変更(下へ)アクション */
    public function downlistAction () {
        //ログイン状況チェック、未ログインの場合ログイン画面に飛ばす
        $this->_caseUnloginRedirect();
        //パラメータを受け取る
        $req = $this->getRequest();
        $posts = $req->getPost();
        if ( isset($posts['rank_id']) != ""  && isset($posts['rank_no']) != "") {
            // 入れ替えの最新情報をDBに更新
            if ($posts['rank_no'] == "1") {
                $this->_changeRankNo1to2($posts);
            } elseif ($posts['rank_no'] == "2") {
                $this->_changeRankNo2to3($posts);
            }
            //詳細ページへ戻る
            //$this->_redirect("/ranking/detail/id/".$posts['rank_id']);
            //変更後詳細情報を取得して順位変更画面再表示
            $param['user_id'] = $this->user_info['user_id'];
            $param['rank_id'] = $posts['rank_id'];
            // 詳細情報を取得
            $res = $this->service('ranking','getRankingDetail',$param);
            $this->view->detail =  $res;
            $this->_helper->viewRenderer('sort');
        } else {
            //いきなりこのアクションを叩かれた場合トップページに遷移する
            $this->_redirect("/index/index");
        }
    }

    /** 削除アクション */
    public function deleteAction () {
        // 削除ボタン押された場合パラメター取得
        $req = $this->getRequest();
        $posts = $req->getPost();
        $rank_id = $posts['rank_id'];
        //$rank_id = $this->getRequest()->getParam('id');
        if ( isset($rank_id) ) {
            $param['USER_ID'] = $this->user_info['user_id'];
            $param['RANK_ID'] = $rank_id;
            $param['DELETE_FLG'] = 1;
            $param['UPDATED_AT'] = date("Y-m-d H:i:s");
            $res = $this->service('ranking','deleteRanking',$param);
            if ( $res == true ) {
                //画像削除処理
                //中身のファイルを削除してからディレクトリを削除する
                $upload_file_dir =  ROOT_PATH."/img/pc/ranking/".$rank_id;
                $upload_file_dir_sp = ROOT_PATH."/img/sp/ranking/".$rank_id;
                //PC版画像
                Utility::removeDirectory($upload_file_dir);
                //スマートフォン画像
                Utility::removeDirectory($upload_file_dir_sp);
            } else {
                //エラー画面に遷移する
                //$this->_redirect('/error/index');
            }
        }
        $this->_redirect("/ranking/index");
    }


    /** /img/tmp配下のごみ画像定期的に削除アクション */
    public function removetmpimgAction () {
        //img/tmp中身のファイルを削除してからディレクトリを削除する
        $img_tmp_dir =   ROOT_PATH."/img/tmp/ranking/";
        //削除処理
        Utility::removeDirectory($img_tmp_dir);
    }

    /** 詳細ページ */
    public function detailAction () {

        //パラメータ受け取り
        $rank_id = $this->getRequest()->getParam('id');
        if ( isset($rank_id) && $rank_id !="") {
            //$param['user_id'] = $user_id;
            $param['rank_id'] = $rank_id;
            //詳細ページ開かれた場合、ページビューテーブルに登録
            $this->_insertUpdateRankPageview($param);
            // 詳細情報を取得
            $res = $this->service('ranking','getRankingDetail',$param);
            if ( $res ) {
                //「リグル」ボタン押せるかチェック
                $arr['REGURU_UID'] = $this->user_info['user_id'];
                $arr['RANK_ID'] = $res['rank_id'];
                // 存在チェック
                $bt_viewflg = $this->service('reguru','checkRankingReguru',$arr);

                //詳細情報画面に表示
                $this->view->bt_viewflg =  $bt_viewflg;
                $this->view->detail =  $res;
                //TDKにタイトルを追加
                $tdk = $res['title'].'のグルメランキング';
                $this->_getTdk($tdk);

               //リぐる一覧情報取得
                $param['now_post_num'] = 0;
                $param['get_post_num'] = self::DISPLAY_NUM_INIT;
                $reguru_list = $this->service('reguru','getReguruList',$param);
                $reguru_list_count = $this->service('reguru','getReguruListCount',$param);
                //ユーザー画像ファイルチェック
                $reguru_ranklist = Utility::userImgExists($reguru_list);
                $this->view->display_numinit= self::DISPLAY_NUM_INIT;
                $this->view->display_num= self::DISPLAY_NUM;
                $this->view->reguru_list =  $reguru_list;
                $this->view->reguru_list_count =  $reguru_list_count;
            } else {
                //データが存在しない場合
                if ( $this->user_info['user_id'] ) {
                    //ログインの場合ランキング作成ページ遷移する
                    $this->_redirect("/ranking/input");
                } else {
                    //未ログインの場合トップページ遷移する
                    $this->_redirect("/index/index");
                }
            }
        } else {
            //ランキング作成ページ遷移する
            if ( $this->user_info['user_id'] ) {
                //ログインの場合ランキング作成ページ遷移する
                $this->_redirect("/ranking/input");
            } else {
                //未ログインの場合トップページ遷移する
                $this->_redirect("/index/index");
            }
        }
    }


    /** ajaxショップ一覧取得アクション */
    public function ajaxgetshoplistAction () {
        //viewは読み込まない
        $this->_helper->viewRenderer->setNoRender();

        // パラメター取得
        $shop_name = $this->getRequest()->getParam('shopname');
        $pref = $this->getRequest()->getParam('pref');
        $param['shopname'] = htmlspecialchars($shop_name);
        $param['pref'] = $pref;

        //複数店対応
        $id = $this->getRequest()->getParam('id');
        $res = $this->service('shop','getShopListFromPrefShopname', $param);

         $shoplisttag="";
         if ($res != false){
            $shoplisttag = "<select id=\"searchshop_{$id}\" name=\"shop_id_{$id}\"><option selected=\"true\" value=\"\">▼もしかしてこのお店？▼</option>";
            foreach ($res as $key => $val){
                $shoplisttag .= "<option value=\"".$val["shop_id"]."\">".$val["shop_name"]."　【".$val["address"]."】"."</option>";
            }
            $shoplisttag .= "<option value=\"shopregist\">お店を登録する場合はこちら</option>";
            $shoplisttag .= "</select><br>";
         } else {
            $shoplisttag = false;
         }

        echo $shoplisttag;
    }


    /** ajax小カテゴリ一覧取得アクション */
    public function ajaxgetsmalllistAction () {
        //viewは読み込まない
        $this->_helper->viewRenderer->setNoRender();

        // パラメター取得
        $large_id = $this->getRequest()->getParam('large_id');
        $param['large_id'] = htmlspecialchars($large_id);

        $small_id = $this->getRequest()->getParam('small_id');
//        $param['large_id'] = htmlspecialchars($large_id);

        $small_id = "";
        $rank_id = $this->getRequest()->getParam('rank_id');
        if ( $rank_id !="" ) {
            $param['rank_id'] = $rank_id;
            $param['user_id'] = $this->user_info['user_id'];
            $small_id = $this->service('ranking','getSmallCategoryDetail', $param);
        }
        //データ抽出
        $param['ctg'] = 'Small';
        $res = $this->service('ranking','getCategoryList', $param);
         $smalllisttag="";
         if ($res != false){
            $smalllisttag = "<select id=\"categorysmall_$large_id\" name=\"smalllist_$large_id\" onchange=\"SelectSmallCategory($large_id);\" value=\"0_0_0\"><option  value=\"-1\">▼小カテゴリ</option>";
            foreach ($res as $key => $val){
                if ($small_id == $val["small_id"]) {
                    $smalllisttag .= "<option selected=\selected\ value=\"".$val["small_id"]."\">".$val["small_value"]."</option>";
                } else {
                    $smalllisttag .= "<option value=\"".$val["small_id"]."\">".$val["small_value"]."</option>";
                }
            }
            $smalllisttag .= "</select><br />";
         }else{
            $smalllisttag = "見つかりません";
         }
        echo $smalllisttag;
    }


    /** ajaxジャンル一覧取得アクション */
    public function ajaxgetgenreAction () {
        //viewは読み込まない
        $this->_helper->viewRenderer->setNoRender();

        // パラメター取得
        $shop_id = $this->getRequest()->getParam('shop_id');
        $param['shop_id'] = htmlspecialchars($shop_id);

        //データ抽出
        $res = $this->service('ranking','getShopGenreList', $param);

         $genrelisttag="";
         if ($res != false){
            foreach ($res as $key => $val){
                $genrelisttag .= $val["genre1_value"]." > ".$val["genre2_value"]." > ".$val["genre3_value"]."<br />";
            }
         }else{
            $genrelisttag = false;
         }
        echo $genrelisttag;
    }

    /** 詳細ページコメント欄「もっと見る」アクション */
    public function ajaxcommentmoreAction () {

        //viewは読み込まない
        $this->_helper->viewRenderer->setNoRender();
        // パラメター取得
        //データ抽出limit順番
        $param = $this->_request->getParams();
        if (!empty($param['limitnum']) && !empty($param['rank_id'])) {
            //検索条件
            $posts['rank_id']= $param['rank_id'];
            $posts['now_post_num'] = $param['limitnum'];
            $posts['get_post_num'] = self::DISPLAY_NUM;
            //データ抽出;
            $res =  $this->service('reguru','getReguruList',$posts);
            $listtag="";
            if ( $res ) {
                for ($i=0; $i<count($res); $i++) {
                    $litag = "
                            <li>
                                <a href=\"/user/regururanking/id/{$res[$i]['reguru_uid']}\">{$res[$i]['user_name']}</a>さんがリグルメしました
                            ";
                            if($res[$i]['comment'] !="") {
                                $litag .= "<div class=\"commentInner\">{{ nl2br($res[$i]['comment'])}}</div>";
                            }
                    $litag .= "</li>";
                    $listtag .= $litag;
                }
            } else {
                $listtag = false;
            }
            echo $listtag;
        }

    }

//▼▼▼▼▼▼▼▼チップス▼▼▼▼▼▼▼▼▼▼▼▼

    private function _buck_regist_page($validate) {
        //入力した値を保持
        $this->view->error  = $validate['error_message'];
        $this->view->errorflg = $validate['error_flg'];
        //エラー画面入力値再セット
        //大カテゴリマスタ
        $param['ctg'] = 'Large';
        $list = $this->service('ranking','getCategoryList',$param);
        $this->view->list =  $list;
        //入力値
        $this->view->detail =  $validate;
        //大小カテゴリチェックされた情報表示
        $check_list = $this->_setCategoryToForm($validate);
        $this->view->check_list =  $check_list;

        //チェックボックス初期表示値セット
        $this->_setDefaultToForm();


        //画像再表示
        /*
        for ( $i=1; $i<=3; $i++) {
            if( isset($_SESSION['photo']['photo_'.$i]) && !empty($_SESSION['photo']['photo_'.$i]) ){
                $upfile = $_SESSION['photo']['photo_'.$i];
                //テンポラリフォルダのファイル削除
                $photo_name = basename($upfile['tmp_name']);
                //テンポラリパス
                $tmp_file_path  = ROOT_PATH."/img/tmp/ranking/".$photo_name;
                //画像ファイル削除
                //Utility::deleteUpFile($tmp_file_path);

                $photo[$i] = $photo_name;
            }
        }
        if (isset($photo[1])) {
            $this->view->photo_1 = $photo[1];
        }
        if (isset($photo[2])) {
            $this->view->photo_2 = $photo[2];
        }
        if (isset($photo[3])) {
            $this->view->photo_3 = $photo[3];
        }
        */
        //入力画面に戻って、エラーメッセージを表示する
        if ( $validate ['rank_id'] !="") {
        	$this->_helper->viewRenderer('edit');
        } else {
        	$this->_helper->viewRenderer('input');
        }
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
     * 初期表示のマスターデータ取得
     * @author: xiuhui yang
     * @param none
     *
     *
     * @return none
     *
    */
    private function _setDefaultToForm()
    {
        //大カテゴリマスターデータ（チェックボックス表示）
        $param['ctg'] = 'Large';
        $res = $this->service('ranking','getCategoryList',$param);
        if ($res) {
            //各小カテゴリ一覧取得
            $list = $this->_get_small_categoryname($res);
            $this->view->list =  $list;
        }
    }


    /**
     * ランキングページビュー登録
     * @author: xiuhui yang
     * @param array $params
     *              keyname is user_id
     *                         rank_id
     *
     *
     * @return none
     *
    */
    private function _insertUpdateRankPageview($params)
    {
        //データ存在チェック
        $ret = $this->service('ranking','checkRankPageviewExist',$params);
        if ( $ret > 0 ) {
            $this->service('ranking','updateRankPageview',$params);
        } else {
            $params['PAGE_VIEW'] = 1;
            $this->service('ranking','insertRankPageview',$params);
        }
    }

    /**
     * UPLOAD画像をリネームする
     * @auther: xiuhui yang
     * @param unknown_type $upfile
     *        string $shop_id
     *        string $no
     *
     *
     * @return string $image_name
     *
    */
    private function _make_imagename($upfile,$shop_id,$user_id,$no) {
        //画像情報
        //$upfile = $_FILES["photo"];
        //アップロードしたファイルの名称
        $name  = $upfile[ 'name' ];
        $file_name = pathinfo($name);
        //ファイル名をrenameする
        $image_name = date("Y-m-d H:i:s").'_'.$shop_id.'_'.$user_id.'_ranking';
        $image_name .= '-no'.$no;
        $image_name .= ".";
        $image_name .= $file_name['extension'];
        return $image_name;
    }

    /**
     * アップロードファイルをテンポラリフォルダへ保存
     * /img/tmp/ranking/rank_id/userid_shop_id_rankid_no1.jpg
     *
     * @author: xiuhui yang
     * @param unknown_type $upfile
     * @param string $path
     * @param int $i
     *
     * 確認画面表示後にはファイルが削除されてしまうため
     * @return bool $ret
     */
    private function _tmp_file_upload($upfile,$path,$i)
    {
        $ret = true;
        $ret = @move_uploaded_file($upfile['tmp_name'],$path);
        $upfile['tmp_name'] = $path;
        // 一時保存パス情報をセッションに格納
        $_SESSION['photo']['photo_'.$i] = $upfile;
        return $ret;
    }

    /**
     * チェックボックスの再表示
     * @author: xiuhui yang
     * @param array $post_arr
     *
     *
     * @return array $check_list
     *
    */
    private function _setCategoryToForm($post_arr)
    {
        $check_list = array();
        if ( isset($post_arr['large_id']) && is_array($post_arr['large_id']) && count($post_arr['large_id']) > 0 ) {
            for ( $t=0; $t<count($post_arr['large_id']); $t++) {
                $large_id = $post_arr['large_id'][$t];
                $check_list[$t]['large_id'] =  htmlspecialchars($large_id);

                //チェックされた小カテゴリ一覧データ
                $param = array();
                $param['large_id'] = $large_id;
                $param['ctg'] = 'Small';
                $res= $this->service('ranking','getCategoryList', $param);
                //$check_list[$t]['smalllist'] = $res;

                //選択された小カテゴリデータ
                if (isset($post_arr['smalllist_'.$large_id]) && $post_arr['smalllist_'.$large_id]!="") {
                    $check_list[$t]['small_id'] = $post_arr['smalllist_'.$large_id];
                    //選択された小カテゴリ名（確認ページ表示用）
                    foreach ($res as $key => $val){
                        if ($val['small_id'] == $post_arr['smalllist_'.$large_id]) {
                            $check_list[$t]['small_value'] = $val['small_value'];
                        }
                    }
                } else {
                    $check_list[$t]['small_id'] = "";
                }
            }

        }
        return $check_list;
    }

    /**
     * 順位変更1→2 or 2→1
     *
     *
     * @author: xiuhui yang
     * @param array $posts
     *              keyname is rank_id
     *                         rank_no
     *
     * @return bool $ret
     */
    private function _changeRankNo1to2($posts)
    {
        $ret = true;
        $param = array();
        $param['user_id'] = $this->user_info['user_id'];
        $param['rank_id'] = $posts['rank_id'];
        $param['rank_no'] = $posts['rank_no'];
        $ret= $this->service('ranking','changeRankNo1to2', $param);
        return $ret;
    }


    /**
     * 順位変更2→3 or 3→2
     *
     *
     * @author: xiuhui yang
     * @param array $posts
     *              keyname is rank_id
     *                         rank_no
     *
     * @return bool $ret
     */
    private function _changeRankNo2to3($posts)
    {
        $ret = true;
        $param = array();
        $param['user_id'] = $this->user_info['user_id'];
        $param['rank_id'] = $posts['rank_id'];
        $param['rank_no'] = $posts['rank_no'];
        $ret= $this->service('ranking','changeRankNo2to3', $param);
        return $ret;
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
    private function _get_small_categoryname($list) {
        for ( $t=0; $t<count($list); $t++) {
            $large_id = $list[$t]['large_id'];
            //小カテゴリ一覧データ
            $param = array();
            $param['large_id'] = $large_id;
            $param['ctg'] = 'Small';
            $res= $this->service('ranking','getCategoryList', $param);
            $list[$t]['smalllist'] = $res;
        }

        return $list;
    }

}