<?php

require_once (MODEL_DIR ."/service/abstractService.php");

/**
 * オースサービス
 *
 * @package   oauth
 * @author    yuya yamamoto 2013/07/01 新規作成
 */
class oauthService extends abstractService {

    /**
     * facebookIDからユーザIDを取得
     * あったらトークンも更新する
     * @author: yuya yamamoto
     * @param array $param
     *             keyname is fb_id
     *                        fb_delete_flg
     * @return array $res
     *               keyname is facebook_exist_flg
     *
     */
    public function getAuthInfoFromFbId($param)
    {
    	$res['facebook_exist_flg'] = false;
    	//fb_idがあるかどうかを探してくる
        $res = $this->logic(get_class($this),__FUNCTION__ ,$param);
        if ($res != false) {
        	$res['facebook_exist_flg'] = true;
        }
	    return $res;
    }


    /**
     * facebookから新規ユーザ登録
     * @author: yuya yamamoto
     * @param array $param
     *              keyname is fb_id
     *                         fb_name
     *                         fb_accsess_token
     * @return array $res
     *               keyname is regist_success_flg
     *
     */
    public function registAuthInfoFromFbId($param)
    {
    	$res['regist_success_flg'] = false;
    	//fb_idがあるかどうかを探してくる
        $res = $this->logic(get_class($this),__FUNCTION__ ,$param);

        if ($res != false) {
        	$res['regist_success_flg'] = true;
        }
	    return $res;
    }


    /**
     * facebookユーザー詳細情報取得
     * @author: yuya yamamoto
     * @param string $user_id
     *
     * @return array $res
     *               keyname is regist_success_flg
     *
     */
    public function getSocialInfo($user_id) {
    	$param['user_id'] = $user_id;
        $res = $this->logic(get_class($this),__FUNCTION__ ,$param);

        if ($res != false) {
        	$res['regist_success_flg'] = true;
        }
	    return $res;
    }


    /**
     * facebookと解除
     * @author: yuya yamamoto
     * @param string $user_id
     *
     * @return array $res
     *               keyname is regist_success_flg
     *
    */
    public function disconnectFb($user_id) {
    	$param['user_id'] = $user_id;
        $res = $this->logic(get_class($this),__FUNCTION__ ,$param);

        if ($res != false) {
        	$res['regist_success_flg'] = true;
        }
	    return $res;
    }


    /**
     * facebookと連携
     * @author: yuya yamamoto
     * @param array $param
     *              keyname is user_id
     *                         fb_accsess_token
     *                         fb_id
     *                         fb_name
     *
     * @return array $res
     *               keyname is regist_success_flg
     *
    */
    public function connectFb($param) {
        $res = $this->logic(get_class($this),__FUNCTION__ ,$param);

        if ($res != false) {
        	$res['regist_success_flg'] = true;
        }
	    return $res;
    }


    /**
     * facebookユーザー存在チェック
     * @author: yuya yamamoto
     * @param string $fb_id
     *
     * @return array $res
     *               keyname is oauth_id
     *
    */
    public function checkAnotherFbAccount($param) {
        $res = $this->logic(get_class($this),__FUNCTION__ ,$param);

	    return $res;
    }
}
?>
