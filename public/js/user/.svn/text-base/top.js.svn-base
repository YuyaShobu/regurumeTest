//top
$(document).ready(function(){
	//$("#shop_name").bind('keyup',function(e){
	//	e.preventDefault();
    //    ajax_search();
	//});
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
        ajax_search();
    });

    $("#delphoto").click(function(e){
        e.preventDefault();
  	  	$('#photo').val('');
  	  	$("#destination").empty().append("<p class=\"fileCaption\">写真をアップしましょう</p>");
  	  	$("#showdelphoto").addClass('disnon');
    });


	//タブのクリックイベント
	$("#tab li").click(function(e) {
		$("#tab li").removeClass('current');
		$(this).addClass('current');
	});

    //タイムライン一覧もっと見る
    $("#atag_timeline").click(function() {
    	ajax_readmore('timeline');
    });

    //新着一覧もっと見る
    $("#atag_new").click(function() {
    	ajax_readmore('new');
    });

    //お気に入り一覧もっと見る
    $("#atag_reguru").click(function() {
    	ajax_readmore('reguru');
    });

    //閲覧数一覧もっと見る
    $("#atag_pv").click(function() {
    	ajax_readmore('pv');
    });
});

(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/ja_JP/all.js#xfbml=1&appId=1392971240920479";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

//ランキングもっと見る
function ajax_readmore(flg){
    var intPage = $("#intPage_"+flg).val();
    var display_num = $("#display_num_"+flg).val();
    $.post("/index/ajaxrankingmore", {limitnum:intPage,flg:flg}, function(strHtml){
        if (strHtml.length>0){
            $("#intPage_"+flg).val(Number(intPage) + Number(display_num));
            //$('div#ranklist_'+flg).append(strHtml).masonry('reload');
            if (flg == 'timeline') {
            	$('div#masonry').append(strHtml).masonry('reload');
            } else {
            	$('div#ranklist_'+flg).append(strHtml);
            }

            var showLength = 13 ; // タイトル表示文字数
            elements = $('#ranklist_'+flg+', #ranklist_'+flg+' *').children();
            for (var i = 0; i < elements.length; ++i)
            with (elements[i])
            if (className == "thumRankTitleText" && innerHTML.length > showLength)
            innerHTML = innerHTML.substr(0, showLength) + '...' ;

            $("#atag_" +flg).text("もっと見る").attr("href", "javascript:void(0)");
          } else {
             $("#readmore_"+flg).addClass('disnon');
         }
    });
}

//行った店登録入力チェック
function inputcheck(obj){
    //var shop_name = $("#shop_name").val();
	var shop_id = "";
    shop_id = $("#shop_id").val();
    if (shop_id == null || shop_id == undefined ||shop_id == "" || shop_id =="-1") {
    	alert("店名を入力してください。");
    	return false;
    } else {
        var frm = this.document.form_search; //フォームオブジェクト取得
        //var frm = this.document.forms[1];
        frm.action ="/beento/insert/";
        frm.submit();
    }
}

$('#shop_name').autocomplete({
	source: function( request, response ) {
		var shop_name = $('#shop_name').val();
		$('#shop_id').val('');
		$.ajax({
			url: "/shop/ajaxgetshopname",
			dataType :'json',
			data: {
				shop_name: shop_name
			},
			success: function( data ) {
				if (data != false) {
					response( $.map( data, function( item ) {
						return {
							lable: item.shop_id,
							value: item.shop_name
						}
					}));
				} else {
					$("#shop_regist_link").empty();
					$("#shop_regist_link").append("<p class=\"btn show02\" id=\"regist_shop_button\" >見つかりませんでした。お店を登録します</p>");
					$("#regist_shop_button").click(function(){
						//window.open("/shop/regist/");
						window.location.href = "/shop/regist/"
					});
				}
			}
		});
	},
	select: function( event, ui ) {
		 $('#shop_id').val(ui.item.lable);
		 $("#regist_shop_button").addClass('disnon');
    },
	minLength: 1
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
				    	var shopname = term[0];
				    	var address = term[1];
			    		$("#shop_name").val(shopname);
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

