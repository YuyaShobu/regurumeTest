//ランキング詳細ページ

    //詳細ページ地図情報表示
$(document).ready(function(){

	 $('a[rel*=lightbox]').slimbox({
		 initialWidth      : 300,
		 initialHeight   : 300
 	 });

	var latitude_1 = $("#latitude_1").val();
	var latitude_2 = $("#latitude_2").val();
	var latitude_3 = $("#latitude_3").val();


	var longitude_1 = $("#longitude_1").val();
	var longitude_2 = $("#longitude_2").val();
	var longitude_3 = $("#longitude_3").val();


	var shop_name_1 = $("#shop_name_1").val();
	var shop_name_2 = $("#shop_name_2").val();
	var shop_name_3 = $("#shop_name_3").val();


	var address_1 = $("#address_1").val();
	var address_2 = $("#address_2").val();
	var address_3 = $("#address_3").val();

	function init() {
            var latlng1 = new google.maps.LatLng(latitude_1,longitude_1);
            var opts = {
            zoom: 10,
            center: latlng1,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        map = new google.maps.Map(document.getElementById("map_canvas"), opts);
        // 位置情報と表示データの組み合わせ
        var data = new Array();
        data.push({position: new google.maps.LatLng(latitude_1,longitude_1), content: shop_name_1});
        data.push({position: new google.maps.LatLng(latitude_2,longitude_2), content: shop_name_2});
        data.push({position: new google.maps.LatLng(latitude_3,longitude_3), content: shop_name_3});
        for (i = 0; i < data.length; i++) {
            var myMarker = new google.maps.Marker({
              position: data[i].position,
              map: map
            });
            attachMessage(myMarker, data[i].content);
          }
    }
    // ONLOADイベントにセット
    window.onload = init();

    //コメント欄もっと見るイベント
    $("#commentreadmore a").click(function() {

        $(this).text("読込中…");
        var initPage = $("#initPage").val();
        var display_num = $("#display_num").val();
        var rank_id = $("#rank_id").val();
        $.post("/ranking/ajaxcommentmore", {limitnum:initPage,rank_id:rank_id}, function(strHtml){
        	if (strHtml.length>0){
                $("#initPage").val(Number(initPage) + Number(display_num));

                $('ul#commentlist').append(strHtml);

                $("#commentreadmore a").text("もっと読む")
                .attr("href", "javascript:void(0)");
              } else {
                  $("#commentreadmore").addClass('disnon');
                 //$("#commentreadmore a").text("The End")
                 //.attr("href", "#");
             }
        });
    });


    //クリック先によってアクションを変更
    $('[actionURL]').click(function(){
        if($(this).attr('actionURL') != ""){
            $('form').attr('action', $(this).attr('actionURL'));
        }
    });

});

	//Google Mapsマーカーの表示
    function myMarker(p) {
        var marker = new google.maps.Marker(new google.maps.LatLng(p[0], p[1]));
        google.maps.Event.addListener(marker, "click", function() {
            marker.openInfoWindowHtml(p[2]);
        });
        return marker;
    }

    function attachMessage(marker, msg) {
        google.maps.event.addListener(marker, 'click', function(event) {
          new google.maps.InfoWindow({
            content: msg
          }).open(marker.getMap(), marker);
        });
    }

    //リぐるボタン押すイベント
    function ajax_reguru(flg){
        var rank_id = $("#rank_id").val();
        var cuid = $("#create_user_id").val();
        var comment = "";
        var ret = logincheck();
        if (ret == true) {
        	var url = '/reguru/ajaxreguru/';
        	reguru(flg, url,rank_id,comment,cuid);
        	if(flg == 'cancel') {
        		url = '/reguru/ajaxregurucancel/';
        		reguru(flg, url,rank_id,comment,cuid);
        	}
        	else {
	           	 $( "#reguru-dialog" ).dialog({
	        		 resizable: false,
	        		 height:200,
	        		 modal: true,
	        		 buttons: {
		        		 "コメントを付ける": function() {
		            		 var comment =  $("#comment").val();
		        			 reguru(flg, url,rank_id,comment,cuid);
		        			 $( this ).dialog( "close" );
		        		 },
		        		 Cancel: function() {
		        			 $( this ).dialog( "close" );
		        		 }
	        		 }
	        	 });
        	}
        }
    }

    function reguru(flg,url,rank_id,comment,cuid){
        $.ajax({
            type: 'POST',
            data: {
                   rank_id:rank_id,comment:comment,cuid:cuid
            },
            url: url,
            dataType: 'html',
            cache: false,
            success: function(data) {
                if (data.length>0){
                    $("#bt_reguru").empty();
                    $("#bt_reguru").html(data);
                    if (flg == 'cancel') {
                        $("#view_comment").attr("style","display:");
                        $("#comment").val('');
                    }else{
                        $("#view_comment").attr("style","display:none");
                        $("#comment").val('');
                    }
                }
            },
            error:function() {
                alert('エラーです。');
            }
        });
    }
