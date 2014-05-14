<?php
/**
 * top3サイト
 *
 * validate class
 * ここにポストするだけでチェックと整形をして
 * エラーメッセージの返しと整形した値の返しを行うことを目指している。
 * リターンするときは親クラスの_retをかましてください
 *
 * @copyright 2013
 * @author    xiuhui yang
 */
require_once (MODEL_DIR ."/validate/abstractValidate.php");
require_once (LIB_PATH ."/Utility.php");


class beentoValidate extends abstractValidate {

	public $validate;
    const CONST_EXPLAIN_MAX = '255'; // 一言のの最大入力文字数

	/**
	 * データ取得
	 * @param string
	 * @return array
	 */
	function registValidate($param) {
		//$this->validate = array();
        $this->validate = $param;
        $this->validate['error_flg'] = false;
        //shop_name
        //$this->_shopNameValidate($param);
        //新規登録の場合ショップ入力必須チェック
        if (!isset($param['bt_id']) or $param['bt_id'] == "") {
            $this->_shopValidate($param);
        }

        //画像
        $this->_photoValidate($param);

        //explain
        //$this->_explainpValidate($param);

        //必ずこの関数は通すこと
        $ret = $this->_ret($this->validate);

    return $ret;
    }



/*
 * ▼▼▼▼▼▼▼ここから下が用意しておくもの▼▼▼▼▼▼▼
 */

    //ショップのチェック
    protected function _shopValidate ($param) {
        if (!isset($param['shop_id']) or $param['shop_id'] == "" or $param['shop_id'] == "-1") {
            $this->validate['error_message']['0'] = 'お店を選択してください。';
            $this->validate['error_flg'] = true;
        }
    }

    //画像のチェック
    protected function _photoValidate ($param) {
        //画像1
        //if ( !isset($param['photo']) ) {
        //    $this->validate['error_message']['1'] = '店の画像ファイルを選択してください。';
        //    $this->validate['error_flg'] = true;
        //} else {
            //アップロード画像ファイル
            $upfile = $param['photo'];
            // 正常にアップロードされていれば，imgディレクトリにデータを保存
            if ( $upfile[ 'error' ] == 0 && $upfile['tmp_name']!="" ) {
                $name  = $upfile['name'];
                //ファイルパスに関する情報
                $file_name = pathinfo($name);
                //ファイルサイズ
                $upfile_size = $upfile["size"];
                $upfile_type = $file_name['extension'];
                if ( $upfile[ 'error' ] == 0 && $upfile['tmp_name']!="" ) {

                // 拡張子でチェック
                $fileType = Utility::VALID_IMAGE_EXTENSIONS();
                    if ( !in_array( $upfile_type,$fileType) ){
                        $this->validate['error_message']['1'] = '画像はjpg、gif、pngファイルを選択してください。';
                        $this->validate['error_flg'] = true;
                    }else{
                        $maxSize = Utility::MAX_FILE_SIZE();
                        if ( ( intval($upfile_size) > intval($maxSize) ) ){
                            $this->validate['error_message']['1'] = '画像ファイルサイズをオーバーしています';
                            $this->validate['error_flg'] = true;
                        }
                    }
                }
            }
        //}
    }

	//explainのチェック
	protected function _explainpValidate ($param) {
		//explainのバリデーション
		if ( !isset($param['explain']) or $param['explain'] == "") {
            //長さチェック
            //最大入力文字数を全角255文字
            //$length = mb_strlen($explain);
            //if ($length > self::CONST_EXPLAIN_MAX) {
            //$this->validate = $param;
            $this->validate['error_message']['2'] = '一言を入力してください';
            //$this->validate['error_message']['1'] = '一言は255文字以内で入力してください';
            $this->validate['error_flg'] = true;
            $this->validate['explain'] = "";
            //} else {
            //    $this->validate['explain'] = $param['explain'];
            //}
		   }
		}
}
?>
