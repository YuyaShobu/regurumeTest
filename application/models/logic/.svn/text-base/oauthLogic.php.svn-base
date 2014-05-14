<?php

require_once (MODEL_DIR ."/logic/abstractLogic.php");

/**
 * オースロジック
 *
 * @package   oauth
 * @author    yuya yamamoto 2013/07/01 新規作成
 */
class oauthLogic extends abstractLogic {


    /**
     * facebookIDからユーザIDを取得
     * あったらトークンも更新する
     * @param array  : fb_id fb_accsess_token
     * @return array $results
     */
    public function getAuthInfoFromFbId($params)
    {

    	$param['fb_id'] = $params['fb_id'];
    	$param['fb_delete_flg'] = 0;
        //oauth_idチェック
        $sql = "
                SELECT
                    oauth_id
                FROM
                    oauth
                WHERE
                    fb_id = :fb_id
                AND
                	fb_delete_flg = :fb_delete_flg
                ";
        $results = $this->db->selectPlaceQuery($sql,$param);
        //一件も取れていなければFALSEを返す
        if (!isset($results[0])) {
            return FALSE;
        } else {
            $res =  $results[0];
        }

        $param2['oauth_id']  = $res['oauth_id'];
		$param2['delete_flg']    = 0 ;
        //データ抽出SQLクエリ
        $sql = "
                SELECT
                    user_id
                FROM
                    user
                WHERE
                    oauth_id = :oauth_id
                AND
                    delete_flg = :delete_flg
                ";
        $userress = $this->db->selectPlaceQuery($sql,$param2);

        if (!isset($userress[0])) {
            return FALSE;
        } else {
            $ret =  $userress[0];
	        //トークン更新
	        //token更新
	        $param3['fb_accsess_token'] = $params['fb_accsess_token'];
	        $where = array( $this->db->quoteInto("oauth_id = ?",$res['oauth_id']));
	        //トランザクション開始

	        $this->db->beginTransaction();
	        try{
	            $result = $this->db->updateData( "oauth", $param3, $where );
	            $this->db->commit();
	        }
	        catch(exception $e)
	        {
	            $this->db->rollBack();
	            return false;
	        }

			return $ret;
	        //$oauth_param['fb_accsess_token'] = $param['fb_accsess_token'];
        }

   	}


    /**
     * facebookから新規ユーザ登録
     * @param string
     * @return array $results
     */
    public function registAuthInfoFromFbId($params)
    {
    	//①oauthテーブルに登録
	   	$param = array (
		    'fb_id' => $params['fb_id'],
		    'fb_name' => $params['fb_name'],
	   		'fb_accsess_token' => $params['fb_accsess_token'],
		    'fb_delete_flg' =>  0,
	   		'fb_connect_flg' =>  1,
	   		'fb_login_flg' => 1
		);

        //トランザクション開始
        $this->db->beginTransaction();
        try{
            $result = $this->db->insertData("oauth", $param ,false);
        }
        catch(exception $e)
        {
            $this->db->rollBack();
            return false;
        }


        //②登録されたoauthNoがそのままユーザIDになるのでそれを取得
        $param2['fb_id'] = $params['fb_id'];
        //データ抽出SQLクエリ
        $sql = "
                SELECT
                    oauth_id
                FROM
                    oauth
                WHERE
                    fb_id = :fb_id
                    and
                    fb_delete_flg = '0'
               ";
        $res2 = $this->db->selectPlaceQuery($sql,$param2);

        if (!isset($res2[0])) {
        	$this->db->rollBack();
            return FALSE;
        }
		$password    =  '0';

        //③userテーブルにoauth_id とユーザIDを登録
	   	$param3 = array (
		    'oauth_id'  	=> $res2[0]['oauth_id'],
		    'user_id'   	=> $res2[0]['oauth_id'],
	   		'user_name' 	=> $params['fb_name'],
	   		'password'  	=> $password,
	   		'address1'      => $params['address1'],
	   		'mail_address'  => $params['mail_address'],
	   		'fb_pic'        => $params['fb_pic'],
		    'delete_flg'    => '0'
		);
        try{
            $res3 = $this->db->insertData("user", $param3, false);
            $this->db->commit();
        }
        catch(exception $e)
        {
            $this->db->rollBack();
            return false;
        }

        if ($res3 == false) {
        	return false;
        } else {
			$return['user_id'] = $param3['user_id'];
	        return $return;
        }
    }


    /**
     * facebookユーザー詳細情報取得
     * @aouther: yuya yamamoto
     * @param string $user_id
     *
     * @return array $res
     *
     */
    public function getSocialInfo($params) {
            $param['user_id'] = $params['user_id'];
	        //oauth_idチェック

	        $sql = "
	                SELECT
	                    o.fb_delete_flg,o.fb_name,o.fb_accsess_token,o.oauth_id,o.fb_login_flg,o.fb_connect_flg,o.fb_id
	                FROM
	                    user u
	                INNER JOIN
	                    oauth o
	                ON
	                    o.oauth_id = u.oauth_id
	                WHERE
	                    u.user_id = :user_id
	                ";
	        $results = $this->db->selectPlaceQuery($sql,$param);

	        //一件も取れていなければFALSEを返す
	        if (!isset($results[0])) {
	            return FALSE;
	        } else {
	            return  $results[0];
	        }
    }


    /**
     * facebookと解除
     * @aouther: yuya yamamoto
     * @param string $user_id
     *
     * @return bool true/false
     *
    */
    public function  disconnectFb($params) {

    	//フェイスブック連携ステータスは１で解除０で連携
    	$params['oauth_id'] = $params['user_id'];
        $param['fb_connect_flg'] = 0;
       //更新条件
	    $where = array( $this->db->quoteInto("oauth_id = ?",$params['oauth_id']));
	    //トランザクション開始
	    $this->db->beginTransaction();
	    try{
	       $result = $this->db->updateData( "oauth", $param, $where);
	        $this->db->commit();
	    }
	    catch(exception $e)
	    {
	        $this->db->rollBack();
	        return false;
	    }
	        return true;
    }


    /**
     * facebookと連携
     * @aouther: yuya yamamoto
     * @param array $params
     *              keyname is user_id
     *                         fb_accsess_token
     *                         fb_id
     *                         fb_name
     *
     * @return bool true/false
     *
    */
    public function  connectFb($params) {

    	//フェイスブック連携ステータスは１で解除０で連携
    	$param['oauth_id']        = $params['user_id'];
        $param['fb_connect_flg']   = 1;
        $param['fb_accsess_token'] = $params['fb_accsess_token'];
        $param['fb_id']            = $params['fb_id'];
		$param['fb_name']          = $params['fb_name'];
       //更新条件
	    $where = array( $this->db->quoteInto("oauth_id = ?",$param['oauth_id']));
	    //トランザクション開始
	    $this->db->beginTransaction();
	    try{
	       $result = $this->db->updateData( "oauth", $param, $where );
	        $this->db->commit();
	    }
	    catch(exception $e)
	    {
	        $this->db->rollBack();
	        return false;
	    }
	        return true;
    }


    /**
     * facebookユーザー存在チェック
     * @aouther: yuya yamamoto
     * @param string $fb_id
     *
     * @return array $res
     *               keyname is oauth_id
     *
    */
    public function checkAnotherFbAccount($params) {
        $param['fb_id']  = $params['fb_id'];

        $sql = "
                SELECT
                    oauth_id
                FROM
                    oauth
                WHERE
                    fb_id = :fb_id
                    AND
                    fb_delete_flg = 0

               ";
        $res = $this->db->selectPlaceQuery($sql,$param);

        if (!isset($res[0])) {
            return FALSE;
        } else {
        	return $res[0];
        }
    }
}
?>
