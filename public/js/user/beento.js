//beento.js
$(document).ready(function(){
	//エラー画面など初期表示
	function init() {
		var pref = "";
		pref = $("#beento_pref option:selected").val();
	    if (pref!="-1"){
	    	$('.show03_inner').show('slow');
			$('.show01_inner').show('slow');
	    }
	}
	// ONLOADイベントにセット
    window.onload = init();


	//都道府県プルダウンchangeイベント
	$("#beento_pref").change(function(){
        var pref = $(this).children("option:selected").val();
        if (pref!="-1"){
        	$('#shop_name').removeAttr('disabled');
        	$('#search_button').removeAttr('disabled');
        }
    });

    $("#search_button").click(function(e){
        e.preventDefault();
        var input_val = $("#shop_name").val();
        if (input_val != "") {
        	ajax_search();
        } else {
        	alert("店名を入力してください。");return false;
        }
    });

    $("#delphoto").click(function(e){
        e.preventDefault();
  	  	$('#photo').val('');
  	  	$("#destination").empty().append("<p class=\"fileCaption\"></p>");
  	  	$("#showdelphoto").addClass('disnon');
        //画像削除フラグ
        $('#photo_delflg').val(1);
    });
});

function ajax_search(){
	$("#search_results").show();
	var search_val = $("#shop_name").val();
	var pref = $("#beento_pref").val();
	if (search_val != "") {
		$.post("/shop/ajaxgetshoplistfromshopname/", {shopname : search_val, pref : pref}, function(data){
	    	if (data != false){
	    		$("#search_results").html(data);
				$("#shop_id").change(function(){
					$('.show01_inner').show('slow');
					var val =  $("#shop_id option:selected").val();
			    	var txt = $("#shop_id option:selected").text();
			    	if (val =="shopregist") {
			    		$("#shop_name").val('');
			    		if ( !window.open( "/shop/regist/") ) {
			    			window.location.href = "/shop/regist/";
			    	    }
			    	} else {
				    	var term = txt.split( '　' );
				    	if (term.length == 2) {
				    		var shopname = term[0];
				    		var address = term[1];
				    		$("#shop_name").val(shopname);
				    	}
			    	}
				});
			} else {
				$("#search_results").empty();
				$("#search_results").append("<p class=\"btn show02\" id=\"regist_shop_button\" >見つかりませんでした。お店を登録します</p>");
				$("#regist_shop_button").click(function(){
					window.open("/shop/regist/");
				});
			}
		})
	}
}

