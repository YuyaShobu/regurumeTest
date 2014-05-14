<?php

function smarty_function_html_jp_pref($params, &$smarty)
{
	$attributes = array();
	 
	$name = (isset($params['name'])) ? ' name="' . $params['name'] . '"' : NULL ;
	$class = (isset($params['class'])) ? ' class="' . $params['class'] . '"' : NULL ;
	$id = (isset($params['id'])) ? ' class="' . $params['id'] . '"' : NULL ;
	 
	$default = (isset($params['default'])) ? $params['default'] : NULL ;
	 
	$prefArray = array(
	array("value"=>"01", "text"=>"北海道")
	, array("value"=>"02", "text"=>"青森県")
	, array("value"=>"03", "text"=>"岩手県")
	, array("value"=>"04", "text"=>"宮城県")
	, array("value"=>"05", "text"=>"秋田県")
	, array("value"=>"06", "text"=>"山形県")
	, array("value"=>"07", "text"=>"福島県")
	, array("value"=>"08", "text"=>"茨城県")
	, array("value"=>"09", "text"=>"栃木県")
	, array("value"=>"10", "text"=>"群馬県")
	, array("value"=>"11", "text"=>"埼玉県")
	, array("value"=>"12", "text"=>"千葉県")
	, array("value"=>"13", "text"=>"東京都")
	, array("value"=>"14", "text"=>"神奈川県")
	, array("value"=>"15", "text"=>"新潟県")
	, array("value"=>"16", "text"=>"富山県")
	, array("value"=>"17", "text"=>"石川県")
	, array("value"=>"18", "text"=>"福井県")
	, array("value"=>"19", "text"=>"山梨県")
	, array("value"=>"20", "text"=>"長野県")
	, array("value"=>"21", "text"=>"岐阜県")
	, array("value"=>"22", "text"=>"静岡県")
	, array("value"=>"23", "text"=>"愛知県")
	, array("value"=>"24", "text"=>"三重県")
	, array("value"=>"25", "text"=>"滋賀県")
	, array("value"=>"26", "text"=>"京都府")
	, array("value"=>"27", "text"=>"大阪府")
	, array("value"=>"28", "text"=>"兵庫県")
	, array("value"=>"29", "text"=>"奈良県")
	, array("value"=>"30", "text"=>"和歌山県")
	, array("value"=>"31", "text"=>"鳥取県")
	, array("value"=>"32", "text"=>"島根県")
	, array("value"=>"33", "text"=>"岡山県")
	, array("value"=>"34", "text"=>"広島県")
	, array("value"=>"35", "text"=>"山口県")
	, array("value"=>"36", "text"=>"徳島県")
	, array("value"=>"37", "text"=>"香川県")
	, array("value"=>"38", "text"=>"愛媛県")
	, array("value"=>"39", "text"=>"高知県")
	, array("value"=>"40", "text"=>"福岡県")
	, array("value"=>"41", "text"=>"佐賀県")
	, array("value"=>"42", "text"=>"長崎県")
	, array("value"=>"43", "text"=>"熊本県")
	, array("value"=>"44", "text"=>"大分県")
	, array("value"=>"45", "text"=>"宮崎県")
	, array("value"=>"46", "text"=>"鹿児島県")
	, array("value"=>"47", "text"=>"沖縄県")
	);

	$options = array();
	foreach ($prefArray as $val) {
		$options[] = ($default==$val['value'] || $default==$val['text']) ? "\t<option value=\"{$val['value']}\" selected=\"selected\">{$val['text']}</option>" : "\t<option value=\"{$val['value']}\">{$val['text']}</option>";
	}
	$options = implode("\n", $options);
	 
	return <<<EOD
 
<select{$name}{$id}{$class}>
    <option value="-1">都道府県を選択</option>
	{$options}
</select>
EOD;
}

?>