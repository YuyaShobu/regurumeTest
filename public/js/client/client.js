$(document).ready(function(){
	$('.shop_info').on('click',function(){
		$('#shop_info_form').submit();
	});
	$('.client_submit').on('click',function(){
		var form = $(this).attr('id');
		$('#'+form+'_form').submit();
	});
	$('.coupon_action').on('click',function(){
		var action = $(this).attr('action');
		$(this).parent().parent().parent().append('<input type="hidden" name="'+action+'" value="'+action+'" />').submit();
	});
	$('.client_td_submit').on('click',function(){
		$(this).parent().submit();
	});
	$('#view_flg').on('change',function(){
		var _value = $(this).val();
		var _this = $(this);
		if(_value == '4' || _value == '5') {
			$('.user_select').remove();
			$.ajax({
				url: '/client/couponviewajax',
				type: 'post',
				dataType :'json',
				data: {
					value : _value,
				},
				success: function( data ) {
					var html='<select class="user_select" style="margin-left:10px;" name="user"><option value="-1">選んでください</option>';
					if(data!=false){
					    $.each(data, function(key,v){
					    	html+='<option value="'+v.user_id+'">'+v.user_name+'</option>';
					    });
					    html+='</select>';
					    _this.after(html);
					}
				}
			});
		}
	});
	$('.genre_parent').on('change',function(){
		var _this = $(this);
		$parent_id = $(this).val();
		var name = $(this).attr('data-name');
		if($parent_id > -1) {
			$.ajax({
				url: '/client/genreajax',
				type: 'post',
				dataType :'json',
				data: {
					id : $parent_id,
				},
				success: function( data ) {
					_this.parent().find('.genre_ko').remove();
					var html='<select class="genre_ko" style="margin-left:10px;" name="'+name+'"><option value="-1">--子カテゴリー</option>';
					if(data!=false){
					    $.each(data, function(key,v){
					    	html+='<option value="'+v.genre_id+'">'+v.value+'</option>';
					    });
					    html+='</select>';
					    _this.parent().append(html);
					}
				}
			});
		}
	});
});