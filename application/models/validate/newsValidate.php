<?php
require_once (MODEL_DIR ."/validate/abstractValidate.php");
class newsValidate extends abstractValidate {

	public $validate;
	const CONST_EXPLAIN_MAX = '100'; // 一言のの最大入力文字数

	/**
	 * データ取得
	 * @param string
	 * @return array
	 */
	//データ抽出SQLクエリ
	function editValidate($param) {
		$this->validate = array();
		$this->validate['error_flg'] = false;
		
		//title
		$this->_titleValidate($param);

		//status
		$this->_statusValidate($param);
		
		//date
		$this->_dateValidate($param);
		
		//content
		$this->_contentValidate($param);

		//必ずこの関数は通すこと
		$ret = $this->_ret($this->validate);

		return $ret;
	}



/*
 * ▼▼▼▼▼▼▼ここから下が用意しておくもの▼▼▼▼▼▼▼
 */
	
	//title
	protected function _titleValidate ($param) {
		if (isset($param['title'])) {
			$title = $param['title'];
		    if ($title != "") {
	            //長さチェック
	            //最大入力文字数を全角100文字
	            $length = mb_strlen($title);
	            if ($length > self::CONST_EXPLAIN_MAX) {
	                $title = $param['title'];
	                $this->validate['error_message']['1'] = '一言は100文字以内で入力してください';
	                $this->validate['error_flg'] = true;
	                $this->validate['title'] = "";
	            } else {
	                $this->validate['title'] = $param['title'];
	            }
		   }
		   else {
			   	$this->validate['error_message']['1'] = 'タイトルが入力されていません';
			   	$this->validate['error_flg'] = true;
			   	$this->validate['title'] = "";		   	
		   }
		}
		else {
			$this->validate['error_message']['1'] = 'タイトルが入力されていません';
			$this->validate['error_flg'] = true;
			$this->validate['title'] = "";
		}
	}

	//status
	protected function _statusValidate ($param) {
		if (isset($param['status'])) {
			$title = $param['status'];
			if ($title != "") {
				if(preg_match('/[1-7]+/',$title)) {
					$this->validate['status'] = $param['status'];
				}
				else {
					$this->validate['error_message']['1'] = 'ステータスの形が正しくありません';
					$this->validate['error_flg'] = true;
					$this->validate['status'] = "";					
				}
			}
			else {
				$this->validate['error_message']['1'] = 'ステータスが入力されていません';
				$this->validate['error_flg'] = true;
				$this->validate['status'] = "";
			}
		}
		else {
			$this->validate['error_message']['1'] = 'ステータスが入力されていません';
			$this->validate['error_flg'] = true;
			$this->validate['status'] = "";
		}
	}	
	
	protected function _dateValidate ($param) {
		if (isset($param['public_start']) && !empty($param['public_start']) && isset($param['public_end']) && !empty($param['public_end'])) {
			if(date($param['public_end']) < date($param['public_start'])) {
				$this->validate['error_message']['1'] = '開始日時と終了日時の形が正しくありません';
				$this->validate['error_flg'] = true;
				$this->validate['status'] = "";				
			}
		}
	}
	
	//content
	protected function _contentValidate ($param) {
		if (isset($param['content'])) {
			$title = $param['content'];
			if ($title != "") {
				$length = mb_strlen($title);
	            if ($length > self::CONST_EXPLAIN_MAX) {
	                $title = $param['content'];
	                $this->validate['error_message']['1'] = '一言は100文字以内で入力してください';
	                $this->validate['error_flg'] = true;
	                $this->validate['content'] = "";
	            } else {
	                $this->validate['content'] = $param['content'];
	            }
			}
			else {
				$this->validate['error_message']['1'] = 'コンテンツが入力されていません';
				$this->validate['error_flg'] = true;
				$this->validate['content'] = "";
			}
		}
		else {
			$this->validate['error_message']['1'] = 'コンテンツが入力されていません';
			$this->validate['error_flg'] = true;
			$this->validate['content'] = "";
		}
	}
}
?>
