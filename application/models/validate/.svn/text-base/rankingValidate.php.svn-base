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
require_once 'Zend/Validate/StringLength.php';


class rankingValidate extends abstractValidate {

    public $validate;
    const CONST_EXPLAIN_MAX = '600'; // 感想文の最大入力文字数

    /**
     * データ取得
     * @param string
     * @return array
     */
    function registValidate($param) {
        //$this->validate = array();
         $this->validate = $param;
        $this->validate['error_flg'] = false;

        //title
        $this->_titleValidate($param);

        //category
        $this->_categoryValidate($param);

        //shop
        $ret = $this->_shopValidate($param);

        //shop1のgenre
        //$this->_genre1Validate($param);

        //shop1のgenre
        //$this->_genre2Validate($param);

        //shop1のgenre
        //$this->_genre3Validate($param);

        //画像
        $this->_photoValidate($param);
        //感想文
        $this->_explainValidate($param);


        //必ずこの関数は通すこと
        $ret = $this->_ret($this->validate);

    return $ret;
    }



/*
 * ▼▼▼▼▼▼▼ここから下が用意しておくもの▼▼▼▼▼▼▼
 */

    //タイトルのチェック
    private function _titleValidate ($param) {
    	$param['title'] = strip_tags($param['title']);
        if ( !isset($param['title']) || $param['title']=="" ) {
            $this->validate['error_message']['0'] = 'タイトルを入力してください。';
            $this->validate['error_flg'] = true;
        }
    }

    //カテゴリのチェック
    private function _categoryValidate ($param) {
        if ( !isset($param['large_id']) ) {
            $this->validate['error_message']['1'] = 'カテゴリがチェックされていません';
            $this->validate['error_flg'] = true;
        }
    }

    //ショップのチェック
    private function _shopValidate ($param) {
        if ( $param['shop_id_1']=="" && $param['shop_id_2']=="" && $param['shop_id_3']=="") {
            $this->validate['error_message']['2'] = '店舗を入力してください。';
            $this->validate['error_flg'] = true;
        } else {
            $arr = array();
            if ($param['shop_id_1'] !="" ) {
                array_push($arr,$param['shop_id_1']);
            }
            if ($param['shop_id_2'] !="" ) {
                array_push($arr,$param['shop_id_2']);
            }
            if ($param['shop_id_3'] !="" ) {
                array_push($arr,$param['shop_id_3']);
            }
            //$array = array($param['shop_id_1'],$param['shop_id_2'],$param['shop_id_3']);
            // 各値の出現回数をここで抽出
            $array_value = array_count_values($arr);
            $num = count($arr);
            for($i=0; $i < $num; $i++){
                $key = $arr[$i];
                // 出現回数を格納
                $count = $array_value[$key];
                if($count > 1){
                    $this->validate['error_message']['2'] = '店舗が重複しています。';
                    $this->validate['error_flg'] = true;
                }
            }
        }
    }

    //画像のチェック
    private function _photoValidate ($param) {
        //画像1
        if ( isset($param['photo1']) ) {
            //アップロード画像ファイル
            $upfile = $param['photo1'];
            // 正常にアップロードされていれば，imgディレクトリにデータを保存
            if ( $upfile[ 'error' ] == 0 && $upfile['tmp_name']!="" ) {
                if ($param['shop_id_1'] !="" ) {
                    $name  = $upfile['name'];
                    //ファイルパスに関する情報
                    $file_name = pathinfo($name);
                    //ファイルサイズ
                    $upfile_size = $upfile["size"];
                    $upfile_type = $file_name['extension'];
                    $ret1_1 = $this->chkValueFileType($upfile_type);
                        if ($ret1_1 == false) {
                            $this->validate['error_message']['3'] = '店1の画像はjpg、gif、pngファイルを選択してください。';
                            $this->validate['error_flg'] = true;
                        }else{
                            $ret1_2 = $this->chkValueFileSize($upfile_size);
                            if ($ret1_2 == false) {
                                $this->validate['error_message']['3'] = '店1の画像ファイルサイズをオーバーしています。';
                                $this->validate['error_flg'] = true;
                            }
                        }
                    } else {
                        $this->validate['error_message']['3'] = '店名1を入力してから画像アップロードしてください。';
                        $this->validate['error_flg'] = true;
                    }
                }
            }

        //画像2
        if ( isset($param['photo2']) ) {
            //アップロード画像ファイル
            $upfile2 = $param['photo2'];
            // 正常にアップロードされていれば，imgディレクトリにデータを保存
            if ( $upfile2[ 'error' ] == 0 && $upfile2['tmp_name']!="" ) {
                if ($param['shop_id_2'] !="" ) {
                    $name2  = $upfile2['name'];
                    //ファイルパスに関する情報
                    $file_name2 = pathinfo($name2);
                    //ファイルサイズ
                    $upfile_size2 = $upfile2["size"];
                    $upfile_type2 = $file_name2['extension'];
                        $ret2_1 = $this->chkValueFileType($upfile_type2);
                        if ($ret2_1 == false) {
                            $this->validate['error_message']['4'] = '店2の画像はjpg、gif、pngファイルを選択してください。';
                            $this->validate['error_flg'] = true;
                        }else{
                            $ret2_2 = $this->chkValueFileSize($upfile_size2);
                            if ($ret2_2 == false) {
                                $this->validate['error_message']['4'] = '店2の画像ファイルサイズをオーバーしています。';
                                $this->validate['error_flg'] = true;
                            }
                        }
                } else {
                    $this->validate['error_message']['4'] = '店名2を入力してから画像アップロードしてください。';
                    $this->validate['error_flg'] = true;
                }
            }
        }

        //画像3
        if ( isset($param['photo3']) ) {
            //アップロード画像ファイル
            $upfile3 = $param['photo3'];
            // 正常にアップロードされていれば，imgディレクトリにデータを保存
            if ( $upfile3[ 'error' ] == 0 && $upfile3['tmp_name']!="" ) {
                if ($param['shop_id_3'] !="" ) {
                    $name3  = $upfile3['name'];
                    //ファイルパスに関する情報
                    $file_name3 = pathinfo($name3);
                    //ファイルサイズ
                    $upfile_size3 = $upfile3["size"];
                    $upfile_type3 = $file_name3['extension'];
                    $ret3_1 = $this->chkValueFileType($upfile_type3);
                    if ($ret3_1 == false) {
                        $this->validate['error_message']['5'] = '店3の画像はjpg、gif、pngファイルを選択してください。';
                        $this->validate['error_flg'] = true;
                    } else {
                        $ret3_2 = $this->chkValueFileSize($upfile_size3);
                        if ($ret3_2 == false) {
                            $this->validate['error_message']['5'] = '店3の画像ファイルサイズをオーバーしています。';
                            $this->validate['error_flg'] = true;
                        }
                    }
                } else {
                    $this->validate['error_message']['5'] = '店名3を入力してから画像アップロードしてください。';
                    $this->validate['error_flg'] = true;
                }
            }
        }
    }

    //感想文の文字数チェック（最大全角200文字可）
    private function _explainValidate ($param) {
        //感想文1
        $param['explain_1'] = strip_tags($param['explain_1']);
        if ( !empty($param['explain_1'])) {
            if ($param['shop_id_1'] !="" ) {
                //最大入力文字数を全角200文字
                $length = mb_strlen($param['explain_1']);
                if ($length > self::CONST_EXPLAIN_MAX) {
                    $this->validate['error_message']['7'] = '店舗1の感想文は200文字以内で入力してください。';
                    $this->validate['error_flg'] = true;
                }
            } else {
                $this->validate['error_message']['7'] = '店名1を入力してから感想文を入力してください。';
                $this->validate['error_flg'] = true;
            }
        }

        //感想文1
        $param['explain_2'] = strip_tags($param['explain_2']);
        if ( !empty($param['explain_2'])) {
            if ($param['shop_id_2'] !="" ) {
                //最大入力文字数を全角200文字
                $length = mb_strlen($param['explain_2']);
                if ($length > self::CONST_EXPLAIN_MAX) {
                    $this->validate['error_message']['8'] = '店舗2の感想文は200文字以内で入力してください。';
                    $this->validate['error_flg'] = true;
                }
            } else {
                $this->validate['error_message']['8'] = '店名2を入力してから感想文を入力してください。';
                $this->validate['error_flg'] = true;
            }
        }

        //感想文3
         $param['explain_3'] = strip_tags($param['explain_3']);
        if ( !empty($param['explain_3'])) {
            if ($param['shop_id_3'] !="" ) {
                //最大入力文字数を全角60文字
                $length = mb_strlen($param['explain_3']);
                if ($length > self::CONST_EXPLAIN_MAX) {
                    $this->validate['error_message']['9'] = '店舗3の感想文は200文字以内で入力してください。';
                    $this->validate['error_flg'] = true;
                }
            }  else {
                $this->validate['error_message']['9'] = '店名3を入力してから感想文を入力してください。';
                $this->validate['error_flg'] = true;
            }
        }
    }

    /**
     * chkValueFileSize
     *
     * 「ファイルのMAXサイズ」のチェック
     *
     * @param $chkValue チェックしたい値
     * @return boolean (OK:true/NG:false)
    */
    protected function chkValueFileSize ($chkValue)
    {
        $ret = true;
        // サイズと比較
        $maxSize = Utility::MAX_FILE_SIZE();
        if ( ( intval($chkValue) > intval($maxSize) ) ){
            $ret = false;
        }
        return $ret;
    }//-----end::function


    /**
     * chkValueFileValidType
     *
     * 「ファイルの拡張子」のチェック
     *
     * @param $chkValue チェックしたい値
     * @return boolean (OK:true/NG:false)
    */
    protected function chkValueFileType ($chkValue)
    {
        $ret = true;
        // 拡張子でチェック
        $fileType = Utility::VALID_IMAGE_EXTENSIONS();
        if ( !in_array( $chkValue,$fileType) ){
            $ret = false;
        }
        return $ret;
    }//-----end::function

}
?>
