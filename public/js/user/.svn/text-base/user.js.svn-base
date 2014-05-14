//user.js
$(document).ready(function(){

    $("#change_psw a").click(function() {
        $("#view_psw").attr("style","display:");
        $("#change_psw").attr("style","display:none");
    });

  //都道府県選択で市町区自動セット
    $("#address1").change(function(){
      var pref = $(this).children("option:selected").val();
      var city ="";
      //city =  $("#city_code").val();

      //var filename = "/search/ajaxgetcity/";
      $("#city").html("<option value=\"-1\">--</option>");
      if (pref!="-1"){
          ajax_search(pref,city);
      }
    });

    $("#delphoto").click(function(e){
        e.preventDefault();
        ajax_delphoto();
    });

});

function ajax_search( pref,city){
  $.ajax({
      type: 'POST',
      data: {
          pref_code:pref,
      },
      url: '/search/ajaxgetcity/',
      dataType: 'json',
      success: function(data) {
          $.each(data, function(i){
              if (city == data[i].city_code) {
                  $("#city").append("<option value=\"" + data[i].city_code + "\"selected>" + data[i].value + "</option>");
              } else {
                  $("#city").append("<option value=\"" + data[i].city_code + "\">" + data[i].value + "</option>");
              }
          });
      },
      error:function() {
          alert('見つかりませんでした。');
      }
  });
}

//プロフィール編集画面画像削除処理
function ajax_delphoto(){

	  $('#photo').val('');
      $("#destination img").attr("src","/img/pc/common/noimg_profile.jpg");
	  $("#showdelphoto").addClass("disnon");
	  var user_photo = "";
	  user_photo = $("#user_photo").val();
	  if (user_photo !="") {
		   $.ajax({
	       type: 'POST',
	       data: {photo:user_photo},
	       url: '/user/ajaxdelphoto/',
	       dataType: 'json',
	       success: function(data) {
	    	  $("#destination img").attr("src","/img/pc/common/noimg_profile.jpg");
	       },
	       error:function() {
	          alert('処理失敗しました。');
	       }
	    });
	  }
	}

function chkForm(){
    if ($('#view_psw').css('display') != 'none' && $('#new_password').val() == '') {
        alert(' パスワードを入力してください。');
        return false
    }
}

$(function() {

	//ランキングもっと見る
    $("#readmore a").click(function() {
        $(this).text("読込中…");
        var flg = $("#flg").val();
        var uid = $("#uid").val();
        var intPage = $("#intPage").val();
        var display_num = $("#display_num").val();
        $.post("/user/ajaxrankingmore", {limitnum:intPage,uid:uid,flg:flg}, function(strHtml){
            if (strHtml.length>0){
                $("#intPage").val(Number(intPage) + Number(display_num));
                $('div#ranklist').append(strHtml);

                var showLength = 13 ; // タイトル表示文字数
                elements = $('#ranklist, #ranklist *').children();
                for (var i = 0; i < elements.length; ++i)
                with (elements[i])
                if (className == "thumRankTitleText" && innerHTML.length > showLength)
                innerHTML = innerHTML.substr(0, showLength) + '...' ;

                $("#readmore a").text("もっと見る").attr("href", "javascript:void(0)");
              } else {
                  $("#readmore").addClass('disnon');
             }
        });
    });

    //店もっと見る
    $("#shopreadmore a").click(function() {

        var flg = $("#flg").val();
        var uid = $("#uid").val();
        var intPage = $("#intPage").val();
        var display_num = $("#display_num").val();
        $.post("/user/ajaxshopmore", {limitnum:intPage,uid:uid,flg:flg}, function(strHtml){
        	if (strHtml.length>0){
                $("#intPage").val(Number(intPage) + Number(display_num));
                $('div#shoplist').append(strHtml);
                //slimbox rebind after ajax callback
                $('div#shoplist').find("a[rel^='lightbox']").slimbox({}, null, function(el) {
                    return (this == el) || ((this.rel.length > 8) && (this.rel == el.rel));
                });
                $("#shopreadmore a").text("もっと読む")
                .attr("href", "javascript:void(0)");
              } else {
                 $("#shopreadmore").addClass('disnon');
             }
        });
    });

});

//follow、unfollowのajax処理
function ajax_follow(btname,url,f_user_id){
    var ret = logincheck();
    if (ret == true) {
        var uid = $("#user_com_id").val();
        var login_uid = $("#login_uid").val();
        $.ajax({
        type: 'POST',
        data: {
           f_user_id:f_user_id,btname:btname
           },
        url: url,
        dataType: 'html',
        cache: false,
        success: function(data) {
            if (data.length>0){
	        	//画面リロードする
            	if (url == "/user/ajaxfollow/") {
            		window.location.href = "/user/follow/id/"+uid;
            	} else {
            		window.location.href = "/user/follower/id/"+uid;
            	}
                //$("#"+btname).empty();
                //$("#"+btname).html(data);
            }
        },
        error:function() {
            alert('見つかりません。');
        }
    	});
    }
}

//beentoデータ削除のajax処理
function ajax_delbeento(bt_id,photo,uid){
    var ret = logincheck();
    if (ret == true) {
    	// 「OK」時の処理開始 ＋ 確認ダイアログの表示
    	if(window.confirm('行ったお店を本当に削除しますか？')){
	        $.ajax({
	        type: 'POST',
	        data: {
	           bt_id:bt_id,photo:photo
	           },
	        url: '/beento/ajaxdeletebeento',
	        dataType: 'json',
	        cache: false,
	        success: function(data) {
		        //画面リロードする
	            window.location.href = "/user/beentoshop/id/"+uid;
	        },
	        error:function() {
	            alert('削除エラー。');
	        }
	    	});
    	}
    	// 「キャンセル」時の処理開始
    	else{
    		return false;
    	}
    } else {
    	return false;
    }
}

