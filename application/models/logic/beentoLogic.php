<?php

require_once (MODEL_DIR ."/logic/abstractLogic.php");
require_once (MODEL_DIR ."/logic/votingLogic.php");
require_once (MODEL_DIR ."/service/socialapiService.php");


/**
 * 行ったロジック
 *
 * @package   beento
 * @author    yuya yamamoto 2013/07/01 新規作成
 */
class beentoLogic extends abstractLogic {


    /**
     * 行った事あるお店一覧データ取得
     * @author: yuya yamamoto
     * @param array $param
     *              keyname is user_id
     *
     * @return array $results
     *
    */
    public function getBeentoList($param)
    {
        //データ抽出SQLクエリ
        $sql = "
                SELECT
                    a.*,b.shop_name
                FROM
                    beento a
                INNER JOIN
                    shop b
                ON
                    a.shop_id = b.shop_id
                WHERE
                    a.delete_flg = 0
                AND
                    a.user_id = ?
                ";
        $results = $this->db->selectPlaceQuery($sql,array( $param ));
        //一件も取れていなければFALSEを返す
        if ( $results == NULL  or count($results) == 0) {
            return FALSE;
        } else {
            return $results;
        }
    }


    /**
     * 行ったよ詳細データ取得
     * @author: yuya yamamoto
     * @param array $param
     *              keyname is bt_id
     *
     * @return array $results
     *
    */
    public function getBeentoDetail($param)
    {
        //データ抽出SQLクエリ
        $sql = "
                SELECT
                    a.*,b.shop_name,b.latitude,b.longitude
                FROM
                    beento a
                LEFT JOIN
                    shop b
                ON
                    a.shop_id = b.shop_id
                WHERE
                    a.delete_flg = 0
                AND
                    a.bt_id = ?
                ";
        $results = $this->db->selectPlaceQuery($sql,array($param));
        //一件も取れていなければFALSEを返す
        if ( $results == NULL  or count($results) == 0) {
            return FALSE;
        } else {
            return $results[0];
        }
    }


    /**
     * 行ったよデータ登録
     * @author: yuya yamamoto
     * @param array $param
     *              keyname is USER_ID
     *                         SHOP_ID
     *                         EXPLAIN
     *                         PHOTO
     *                         CREATED_AT
     *                         UPDATED_AT
     *
     * @return bool true/false
     *
    */
    public function insertBeento($param)
    {
        //登録項目編集
        $inputData = array();
        $inputData['USER_ID'] = $param['user_id'];
        $inputData['SHOP_ID'] = $param['shop_id'];
        $explain              = strip_tags($param['explain']);
        $inputData['EXPLAIN'] = $explain;
        $inputData['PHOTO'] = "";
        $inputData['UPDATED_AT'] = date("Y-m-d H:i:s");

        //トランザクション開始
        $this->db->beginTransaction();
        try{
            $result = $this->db->insertData( "beento", $inputData ,FALSE);
            $bt_id = $this->db->lastInsertId();
            if ($bt_id != "") {
		        //画像情報
		        $upfile = $param["photo"];
		        //画像uploadされた場合、ファイル名作成してDB項目にセット
	            $img_upload_flg = false;
		        if ( $upfile[ 'error' ] == UPLOAD_ERR_OK && is_uploaded_file( $upfile['tmp_name'] ) ) {
		            //アップロードしたファイルの名称
		            $name  = $upfile['name'];
		            $file_name_1 = pathinfo($name);
		            //ファイル名をrenameする
    		        //$new_name = $param['shop_id'].'_'.$this->user_info['user_id'].'_beento';
                    //例：http://regurume.com/img/pc/shop/27587/266_beento.jpg
		            $new_name = $bt_id.'_beento';
		            $new_name .= ".";
		            $new_name .= $file_name_1['extension'];
	                // アップロード先のパス
	                $updir_pc  = ROOT_PATH."/img/pc/shop/".$param['shop_id'];
	                $updir_sp  = ROOT_PATH."/img/sp/shop/".$param['shop_id'];
	                $img_upload_flg = Utility::uploadFile($updir_pc,$updir_sp,$new_name,$upfile);

	                //画像アップロード成功するなら、beentoテーブルに画像項目更新する
	                if ( $img_upload_flg != false ) {
	                    //更新条件
	                    $updateData = array();
	                    $where = array( $this->db->quoteInto("BT_ID = ?",$bt_id));
	                    $updateData['PHOTO'] = $param['shop_id'].'/'.$new_name;;
                        $updateData['UPDATED_AT'] = date("Y-m-d H:i:s");
	                    $upres = $this->db->updateData( "beento", $updateData, $where ,FALSE);

	                }

		        }
                //ソーシャルフィード
                $param['bt_id'] = $bt_id;
                //イメージがアップされたかどうかでfacebookAPIを変えるのでフラグを持たせる。
                $param['img_upload_flg'] = $img_upload_flg;
                $this->_facebookfeed($param);

                //フォローワーのtimelineテーブルに登録する
                $logic = new votingLogic();
                $params_tl['user_id'] = $param['user_id'];
                $params_tl['shop_id'] = $param['shop_id'];
                $params_tl['bt_id'] = $bt_id;
                $params_tl['tl_type'] = 2;
                $ret = $logic->insertTimeline($params_tl,'bt_id');

            }

            $this->db->commit();
        }
        catch(exception $e)
        {
            $this->db->rollBack();
            /*
            //例外の場合ファイルサーバでUPLOADされた画像画像があったら、削除
                if (Utility::isImagePathExisted("/img/pc/shop/".$param['shop_id']."/".$new_name)) {
                //画像削除する
                $uploaded_photo_path = ROOT_PATH."/img/pc/shop/".$param['shop_id']."/".$new_name;
                //PCアップロード先のパス
                Utility::deleteUpFile($uploaded_photo_path);
            }
            */

            return false;
        }
        return true;
    }

    /**
     * 行ったよデータ更新
     * @author: yuya yamamoto
     * @param array $param
     *              keyname is USER_ID
     *                         SHOP_ID
     *                         EXPLAIN
     *                         PHOTO
     *                         BT_ID
     *                         UPDATED_AT
     *
     * @return bool true/false
     *
    */
    public function updateBeento($param)
    {

	    //登録項目編集
	    $updateData = array();
	    $updateData['USER_ID'] = $param['user_id'];
	    $updateData['SHOP_ID'] = $param['shop_id'];
	    $explain = strip_tags($param['explain']);
	    $updateData['EXPLAIN'] = $explain;
	    $updateData['UPDATED_AT'] = date("Y-m-d H:i:s");

	    //画像uploadされた場合、ファイル名作成してDB項目にセット
	    //画像情報
	    $upfile = $param["photo"];
	    if ( $upfile[ 'error' ] == UPLOAD_ERR_OK && is_uploaded_file( $upfile['tmp_name'] ) ) {
	        //アップロードしたファイルの名称
	        $name  = $upfile['name'];
	        $file_name_1 = pathinfo($name);
	        //ファイル名をrenameする
	        //$new_name = $shop_id.'_'.$this->user_info['user_id'].'_beento';
	        $new_name = $param['bt_id'].'_beento';
	        $new_name .= ".";
	        $new_name .= $file_name_1['extension'];
	        $photo = $param['shop_id'].'/'.$new_name;
	        $updateData['PHOTO'] = $photo;
            // アップロード先のパス
            $updir_pc  = ROOT_PATH."/img/pc/shop/".$param['shop_id'];
            $updir_sp  = ROOT_PATH."/img/sp/shop/".$param['shop_id'];
            Utility::uploadFile($updir_pc,$updir_sp,$new_name,$upfile);
	    } else {
	        //画像削除フラグ立った場合、DB項目クリア且つファイルサーバ画像削除
	        if ( $param['bt_id'] !="" && $param['photo_delflg'] == "1") {
	            //画像削除する
	            $old_photo_path =  ROOT_PATH.$param['old_photo_path'];
	            //PCアップロード先のパス
	            Utility::deleteUpFile($old_photo_path);
	        }
	    }
        //更新条件
        $where = array( $this->db->quoteInto("BT_ID = ?",$param['bt_id']));
        //トランザクション開始
        $this->db->beginTransaction();
        try{
            $result = $this->db->updateData( "beento", $updateData, $where );
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
     * 行ったよデータ更新
     * @author: yuya yamamoto
     * @param array $param
     *              keyname is USER_ID
     *                         SHOP_ID
     *                         EXPLAIN
     *                         PHOTO
     *                         BT_ID
     *                         UPDATED_AT
     *
     * @return bool true/false
     *
    */
    public function updateBeentobak($param)
    {
        //更新条件
        $where = array( $this->db->quoteInto("BT_ID = ?",$param['BT_ID']));
        //トランザクション開始
        $this->db->beginTransaction();
        try{
            $result = $this->db->updateData( "beento", $param, $where );
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
     * 行ったよデータ存在チェック
     * @author: yuya yamamoto
     * @param array $param
     *              keyname is user_id
     *                         shop_id
     *
     * @return  array $results
     *                keyname is CNT
     *
    */
    public function checkBeentoExist($param)
    {
        //データ抽出SQLクエリ
        $ret = 0;
        $sql = "
                SELECT
                     COUNT(1) AS CNT
                FROM
                     beento
                WHERE
                    user_id = ?
                AND
                    shop_id = ?
                AND
                    delete_flg = 0
                ";
        $results = $this->db->selectPlaceQuery($sql,array($param['user_id'],$param['shop_id']));
        //一件も取れていなければFALSEを返す
        if ( $results == NULL  or count($results) == 0) {
            $ret = 0;
        } else {
            return $results[0]['CNT'];
        }
        return $ret;
    }


    /**
     * 行ったよデータ削除
     * @author: yuya yamamoto
     * @param array $param
     *              keyname is BT_ID
     *                         DELETE_FLG
     *                         UPDATED_AT
     *
     * @return  bool true/false
     *
    */
    public function deleteBeento($param)
    {
        $where = array($this->db->quoteInto("BT_ID = ?", $param['BEENTO_ID']));
        //トランザクション開始
        $this->db->beginTransaction();
        try{
            //$result = $this->db->updateData( "beento", $param, $where );
            $result = $this->db->deleteData( "beento", $where );
            //$result = $this->db->deleteData( "fullcourse", $where );
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
     * 行ったよ店一覧　応援高い順
     * @author: yuya yamamoto
     * @param none
     *
     * @return array $results
     *
    */
    public function getShopBeentoList()
    {
        //データ抽出SQLクエリ
        $sql = "
                SELECT
                    a.shop_id,count(*) as cnt,b.shop_name
                FROM
                    (select * from beento where delete_flg = 0)  a
                LEFT OUTER JOIN
                    shop b
                ON
                    a.shop_id = b.shop_id
                GROUP BY a.shop_id
                ";
        $results = $this->db->selectPlaceQuery($sql,array());
        //一件も取れていなければFALSEを返す
        if ( $results == NULL  or count($results) == 0) {
            return FALSE;
        } else {
            return $results;
        }
    }


    /**
     * 行ったことあるユーザー一覧
     * @author: yuya yamamoto
     * @param string $shop_id
     *
     * @return array $results
     *               keyname is user_id
     *                          mail_address
     *
    */
    public function getBeentoUserList($param)
    {
        //データ抽出SQLクエリ
        $sql = "
                SELECT
                    a.user_id,b.mail_address
                FROM
                    beento a
                LEFT OUTER JOIN
                    user b
                ON
                    a.user_id = b.user_id
                WHERE
                    a.shop_id  = :shop_id
                ";
        $results = $this->db->selectPlaceQuery($sql,$param);
        //一件も取れていなければFALSEを返す
        if ( $results == NULL  or count($results) == 0) {
            return FALSE;
        } else {
            return $results;
        }
    }


    /**
     * カテゴリーIDからそのカテゴリーIDの行ったことあるリスト(ショップリスト)を取得
     * @author: yuya yamamoto
     * @param
     *
     * @return array $results
     *               keyname is shop_id
     *                          photo
     *                          user_id
     *                          shop_name
     *                          user_name
     *
    */
    public function getBeentoRanking($param)
    {

        //データ抽出SQLクエリ
        $sql = "
                SELECT
                    o.shop_id , o.photo,o.user_id, s.shop_name , u.user_name
                FROM
                   ( beento o
	                INNER JOIN
	                	shop s
	                ON
	                    o.shop_id = s.shop_id
                   )
                INNER JOIN
                    user u
                ON
                    u.user_id = o.user_id
                WHERE
                    o.delete_flg = 0
                limit 50
                ";
                $results = $this->db->selectPlaceQuery($sql,array());
        //一件も取れていなければFALSEを返す
        if ( $results == NULL  or count($results) == 0) {
            return FALSE;
        } else {
            return $results;
        }
    }

    //▼▼▼▼▼▼▼▼チップス▼▼▼▼▼▼▼▼▼▼▼▼

    /*
     * facebookへフィードする
     * 編集ではなく、且つ投稿が成功した場合のみフィードする
     * @author: xiuhui yang
     * @param array $params
     *
     *
     * @return none
     *
    */
    private function _facebookfeed($params)
    {
        //ソーシャルフィード
        $fb_feed_flg    = false;
        $fb_feed_flg    = $params['fb_feed_flg'];
        //この値はテンプレート側でFBにフィードをするというチェックボックスからきている
        if ($fb_feed_flg == '1') {
            $message   = "";
            $shop_id   = "";
            $shop_name = "";
            $shop_id   = $params['shop_id'];
            $shop_name = $params['shop_name'];
            $message   = $params['explain'];

            //拡張子がある場合とない場合で使うAPIを分ける。
            if ($params['img_upload_flg'] != false ) {
                //$updir     = "img/pc/shop/".$shop_id.'/'.$shop_id.'_'.$this->user_info['user_id'].'_beento.jpg';
                $updir     = "img/pc/shop/".$shop_id.'/'.$params['bt_id'].'_beento.jpg';
                //例：http://regurume.com/img/pc/shop/27587/27587_157_beento.jpg
                $kind = 'beento_albam';
                //
            } else {
                $updir     = 'http://regurume.com/img/pc/common/header_logo.png';
                $kind = 'beento_feed';
            }
            $param     = array (
                'user_id'   => $params['user_id'],
                'kind'      => $kind ,
                'message'   => $message ,
                'shop_id'   => $shop_id,
                'shop_name' => $shop_name,
                'user_name' => $params['user_name'],
                'photo_path'=> $updir
            );

            $obj = new socialapiService();
            $obj->facebookfeed($param);
        }
    }
}
?>
