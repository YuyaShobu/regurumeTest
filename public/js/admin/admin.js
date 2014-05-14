$(document).ready(function(){
	$('.admin_submit').on('click',function(){
		var temp = $(this).attr('id');
		if($('#'+temp+'_form').find("input[name='search']").length == 0)
		{
			$('#'+temp+'_form').append('<input type="hidden" value="'+$('#search').val()+'" name="search" >');
		}
		if($('#'+temp+'_form').find("select[name='orderby']").length == 0)
		{
			$('#'+temp+'_form').append('<input type="hidden" value="'+$('#order').val()+'" name="orderby" >');
		}
		$('#'+temp+'_form').submit();
	});
	$('.paging').on('click',function(){
		var page = $(this).attr('page');
		if($('#select_action_form').length>0)
		{
			var admin_select = $('.admin_select').val();
			$('#paging_form').append('<input type="hidden" value="'+admin_select+'" name="select_action" >');
		}
		if($('#order').val()!='')
		{
			$('#paging_form').append('<input type="hidden" value="'+$('#order').val()+'" name="orderby" >');
		}
		if($('#search').val()!='')
		{
			$('#paging_form').append('<input type="hidden" value="'+$('#search').val()+'" name="search" >');
		}
		$('#paging_form').append('<input type="hidden" value="'+page+'" name="paging" >').submit();
	});
	$('.admin_submit_td').on('click',function(){
		var now_page = $('#now_page').html();
		$(this).parent().append('<input type="hidden" value="'+now_page+'" name="paging" >').submit();
	});
	$('.admin_select').on('change',function(){
		$(this).parent().submit();
	});
	$('form .del').on('click',function(){
		if($('#order').val()!='')
		{
			$(this).parent().append('<input type="hidden" value="'+$('#order').val()+'" name="orderby" >');
		}
		if($('#search').val()!='')
		{
			$(this).parent().append('<input type="hidden" value="'+$('#search').val()+'" name="search" >');
		}
		if($('#now_page').val()!='')
		{
			$(this).parent().append('<input type="hidden" value="'+$('#now_page').val()+'" name="paging" >');
		}
		$(this).parent().append('<input name="delete" type="hidden" value="delete" />').submit();
	});
	var ajax_id = '';
	var ajax_action = '';
	var ajax_data = '';
	var ajax_error_value = '';
	$('.ajaxedit').on('click',function(){
		$('.edit-text').remove();
		$('.edit-a').css('display','block');
		ajax_id = $(this).attr('data-edit');
		ajax_action = $(this).parent().attr('action');
		var _type = $(this).attr('data-type');
		var _value = $('#edit-a-'+ajax_id).html();
		var  _parent = $('#edit-a-'+ajax_id).parent();
		ajax_error_value = _value;
		$('#edit-a-'+ajax_id).css('display','none');
		_parent.append('<input class="edit-text" type="text" value="'+_value+'" />');
		if(_type == 'kodawari') {
			ajax_data='update=update&large_id='+$(this).attr('data-large')+'&small_id='+$(this).attr('data-small_id');
		}
		else if(_type == 'genre') {
			ajax_data='update=update&genre_id='+$(this).attr('data-genre_id');
		}
	});
	$('body').on('click',function(event){
		var _this = $(this);
		if(ajax_id!='' && ajax_action!='' && event.target.nodeName !='A' && event.target.nodeName !='INPUT') {
			var _ajax_id = ajax_id;
			var value = $('.edit-text').val();
			$.ajax({
				url:ajax_action,
				type:'post',
				data:ajax_data+'&value='+encodeURIComponent(value),
				success: function( data ) {
					if(data == 1) {
						$('.edit-text').remove();
						$('#edit-a-'+_ajax_id).css('display','inline');
						$('#edit-a-'+_ajax_id).html(value);					
					}
					else {
						$('.msg-ok').html('<p><strong>編集失敗</strong></p><a href="#" class="close">close</a>');
						$('.edit-text').remove();
						$('#edit-a-'+_ajax_id).html(ajax_error_value).css('display','block');
					}
				},
				error : function () {
					$('.msg-ok').html('<p><strong>編集失敗</strong></p><a href="#" class="close">close</a>');
					$('.edit-text').remove();
					$('#edit-a-'+_ajax_id).html(ajax_error_value).css('display','block');
				}
			});
			ajax_id = '';
		}
	});
})