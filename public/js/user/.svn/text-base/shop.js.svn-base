//top

	$(document).ready(function(){
        function init() {
        	var latitude = $("#latitude").val();
        	var longitude = $("#longitude").val();
            var latlng = new google.maps.LatLng(latitude,longitude);
            var opts = {
                zoom: 18,
                center: latlng,
                mapTypeId: google.maps.MapTypeId.ROADMAP
              };
              map = new google.maps.Map(document.getElementById("map_canvas"), opts);
              // マーカーの生成
              var marker = createMarker(latlng);
              // 中心位置の移動
              map.setCenter(latlng);
        }
        // ONLOADイベントにセット
        window.onload = init();

    $("#tab li").click(function() {
        var num = $("#tab li").index(this);
        $(".content_wrap").addClass('disnon');
        $(".content_wrap").eq(num).removeClass('disnon');
        $("#tab li").removeClass('select');
        $(this).addClass('select')
    });

    $("#readmore a").click(function() {
        $(this).text("読込中…");
        var intPage = $("#intPage").val();
        var display_num = $("#display_num").val();
        var shop_id = $("#shop_id").val();
        var shop_name = $("#shop_name").val();
        $.post("/shop/ajaxrankingmore", {limitnum:intPage,shop_id:shop_id}, function(strHtml){
            if (strHtml.length>0){
                $("#intPage").val(Number(intPage) + Number(display_num));
                $('dl#ranklist').append(strHtml);
                $("#readmore a").text(shop_name+"がランクインされているランキングをもっと見る")
                .attr("href", "javascript:void(0)");
              } else {
            	  $("#readmore").addClass('disnon');
                 //$("#readmore a").text("The End")
                 //.attr("href", "#");
             }
        });
    });

    $("#readmore_beento a").click(function() {
        $(this).text("読込中…");
        var intPage = $("#intPage_beento").val();
        var display_num = $("#display_num_beento").val();
        var shop_id = $("#shop_id").val();
        var shop_name = $("#shop_name").val();
        $.post("/shop/ajaxbeentocommentmore", {limitnum:intPage,shop_id:shop_id}, function(strHtml){
            if (strHtml.length>0){
                $("#intPage_beento").val(Number(intPage) + Number(display_num));
                $('div#beentolist').append(strHtml);
                //slimbox rebind after ajax callback
                $('div#beentolist').find("a[rel^='lightbox']").slimbox({}, null, function(el) {
                    return (this == el) || ((this.rel.length > 8) && (this.rel == el.rel));
                });
                $("#readmore_beento a").text(shop_name+"コメントをもっと見る")
                .attr("href", "javascript:void(0)");
                } else {
            	  $("#readmore_beento").addClass('disnon');
                 //$("#readmore a").text("The End")
                 //.attr("href", "#");
             }
        });
    });

});

//ショップ詳細画面各種ボタン押された場合
function ajax_shopvoting(shop_id,btname,url){
    var ret = logincheck();
    if (ret == true) {
    $.ajax({
        type: 'POST',
        data: {
           shop_id:shop_id
           },
        url: url,
        dataType: 'html',
        cache: false,
        success: function(data) {
            if (data.length>0){
                $("#"+btname).empty();
                $("#"+btname).html(data);
            }
        },
        error:function() {
            alert('エラーです。');
        }
    });
    }
}

//ショップ詳細画面行ったボタンイベント
function ajax_shopbeento(shop_id,btname,url){
    var ret = logincheck();
    if (ret == true) {
    $.ajax({
        type: 'POST',
        data: {
           shop_id:shop_id
           },
        url: url,
        dataType: 'json',
        cache: false,
        success: function(data) {
		        //画面リロードする
	            window.location.href = "/shop/detail/id/"+shop_id;
            	//var tag = "<input type=\"button\" id=\"bt_beento\" class=\"btn btnF btnCD\" value=\"行った\"/>"
                //$("#beento").empty();
                //$("#beento").append(tag);
                //if ( data!=false ) {
                //	$("#count_beento").html(data[0].cnt);
                //	$("#beentolist").empty();
                //	$.each(data, function(i){
                //	$("#beentolist").append("<li><a href=\"/user/myranking/id/" + data[i].user_id + "\" title=\"" + data[i].user_name+ "\"><img alt=\"" + data[i].user_name + "\" src=\"" + data[i].user_photo + "\" ></a></li>");
                //	});
           		//} else {
                //	$("#count_beento").html('0');
                //	$("#beentolist").empty().append("<li>行ったお店は上部の【行った】ボタンを押そう</li>");
                //}
        },
        error:function() {
            alert('エラーです。');
        }
    });
    }
}

//ショップ詳細画面行った削除ボタンイベント
function ajax_shopbeentocancel(shop_id,btname,url){
    var ret = logincheck();
    if (ret == true) {
    $.ajax({
        type: 'POST',
        data: {
           shop_id:shop_id
           },
        url: url,
        dataType: 'json',
        cache: false,
        success: function(data) {
            	var tag = "<input type=\"button\" id=\"bt_beento\" class=\"btn btnF btnC01\" value=\"行った\" onclick=\"ajax_shopbeento("+shop_id+",'beento','/voting/ajaxshopbeento/');\"/>"
                $("#beento").empty();
            	$("#beento").append(tag);
                if (data !=false ) {
                	$("#count_beento").html(data[0].cnt);
                	$("#beentolist").empty();
                	$.each(data, function(i){
                		$("#beentolist").append("<li><a href=\"/user/myranking/id/" + data[i].user_id + "\" title=\"" + data[i].user_name+ "\"><img alt=\"" + data[i].user_name + "\" src=\"" + data[i].user_photo + "\"  ></a></li>");
                	});
                } else {
                	$("#count_beento").html('0');
                	$("#beentolist").empty().append("<li>行ったお店は上部の【行った】ボタンを押そう</li>");
                }
        },
        error:function() {
            alert('エラーです。');
        }
    });
    }
}

//ショップ詳細画面行きたいボタンイベント
function ajax_shopwantto(shop_id,btname,url){
    var ret = logincheck();
    if (ret == true) {
    $.ajax({
        type: 'POST',
        data: {
           shop_id:shop_id
           },
        url: url,
        dataType: 'json',
        cache: false,
        success: function(data) {
            	var tag = "<input type=\"button\" id=\"bt_wantto\" class=\"btn btnF btnCD\" value=\"行きたい削除\" onclick=\"ajax_shopwanttocancel("+shop_id+",'beento','/voting/ajaxshopwanttocancel/');\"/>"
                $("#wantto").empty();
                $("#wantto").append(tag);
                if ( data!=false ) {
                	$("#count_wantto").html(data[0].cnt);
                	$("#wanttolist").empty();
                	$.each(data, function(i){
                	$("#wanttolist").append("<li><a href=\"/user/myranking/id/" + data[i].user_id + "\" title=\"" + data[i].user_name+ "\"><img alt=\"" + data[i].user_name + "\" src=\"" + data[i].user_photo + "\" ></a></li>");
                	});
           		} else {
                	$("#count_wantto").html('0');
                	$("#wanttolist").empty().append("<li>行きたいと思ったら上部の【行きたい】ボタンを押そう</li>");
                }
        },
        error:function() {
            alert('エラーです。');
        }
    });
    }
}

//ショップ詳細画面行きたい削除ボタンイベント
function ajax_shopwanttocancel(shop_id,btname,url){
    var ret = logincheck();
    if (ret == true) {
    $.ajax({
        type: 'POST',
        data: {
           shop_id:shop_id
           },
        url: url,
        dataType: 'json',
        cache: false,
        success: function(data) {
            	var tag = "<input type=\"button\" id=\"bt_wantto\" class=\"btn btnF btnC01\" value=\"行きたい\" onclick=\"ajax_shopwantto("+shop_id+",'beento','/voting/ajaxshopwantto/');\"/>"
                $("#wantto").empty();
            	$("#wantto").append(tag);
                if (data !=false ) {
                	$("#count_wantto").html(data[0].cnt);
                	$("#wanttolist").empty();
                	$.each(data, function(i){
                		$("#wanttolist").append("<li><a href=\"/user/myranking/id/" + data[i].user_id + "\" title=\"" + data[i].user_name+ "\"><img alt=\"" + data[i].user_name + "\" src=\"" + data[i].user_photo + "\"  ></a></li>");
                	});
                } else {
                	$("#count_wantto").html('0');
                	$("#wanttolist").empty().append("<li>行きたいと思ったら上部の【行きたい】ボタンを押そう</li>");
                }
        },
        error:function() {
            alert('エラーです。');
        }
    });
    }
}


//ショップ詳細画面応援ボタンイベント
function ajax_shopoen(shop_id,btname,url){
    var ret = logincheck();
    if (ret == true) {
    $.ajax({
        type: 'POST',
        data: {
           shop_id:shop_id
           },
        url: url,
        dataType: 'json',
        cache: false,
        success: function(data) {
            	var tag = "<input type=\"button\" id=\"bt_oen\" class=\"btn btnF btnCD\" value=\"応援しました\" onclick=\"ajax_shopoencancel("+shop_id+",'beento','/voting/ajaxshopoencancel/');\"/>"
                $("#shopoen").empty();
                $("#shopoen").append(tag);
                if ( data!=false ) {
                	$("#count_oen").html(data[0].cnt);
                	$("#oenlist").empty();
                	$.each(data, function(i){
                	$("#oenlist").append("<li><a href=\"/user/myranking/id/" + data[i].user_id + "\" title=\"" + data[i].user_name+ "\"><img alt=\"" + data[i].user_name + "\" src=\"" + data[i].user_photo + "\" ></a></li>");
                	});
           		} else {
                	$("#count_oen").html('0');
                	var shop_name = $("#shop_name").val();
                	$("#oenlist").empty().append("<li>"+shop_name+"を応援する場合は上部の【応援する】ボタンを押そう</li>");
                }
        },
        error:function() {
            alert('エラーです。');
        }
    });
    }
}

//ショップ詳細画面応援削除ボタンイベント
function ajax_shopoencancel(shop_id,btname,url){
    var ret = logincheck();
    if (ret == true) {
    $.ajax({
        type: 'POST',
        data: {
           shop_id:shop_id
           },
        url: url,
        dataType: 'json',
        cache: false,
        success: function(data) {
            	var tag = "<input type=\"button\" id=\"bt_oen\" class=\"btn btnF btnC03\" value=\"応援する\" onclick=\"ajax_shopoen("+shop_id+",'beento','/voting/ajaxshopoen/');\"/>"
                $("#shopoen").empty();
            	$("#shopoen").append(tag);
                if (data !=false ) {
                	$("#count_oen").html(data[0].cnt);
                	$("#oenlist").empty();
                	$.each(data, function(i){
                		$("#oenlist").append("<li><a href=\"/user/myranking/id/" + data[i].user_id + "\" title=\"" + data[i].user_name+ "\"><img alt=\"" + data[i].user_name + "\" src=\"" + data[i].user_photo + "\"  ></a></li>");
                	});
                } else {
                	$("#count_oen").html('0');
                	var shop_name = $("#shop_name").val();
                	$("#oenlist").empty().append("<li>"+shop_name+"を応援する場合は上部の【応援する】ボタンを押そう</li>");
                }
        },
        error:function() {
            alert('エラーです。');
        }
    });
    }
}

//beentoデータ削除のajax処理
function ajax_delbeento(bt_id,photo,sid){
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
	            window.location.href = "/shop/detail/id/"+sid;
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
