//search.js
$(document).ready(function(){
    function init() {
        var pref = "";
        var city = "";
        pref = $("#in_pref").val();
        city = $("#in_city").val();
        if ((pref!="-1") && (pref !="")){
        	ajax_search(pref,city);
        	$('.show03_inner').show('slow');
        }

        var genre1 = "";
        var genre2 = "";
        genre1 = $("#in_genre1").val();
        genre2 = $("#in_genre2").val();
        if ( (genre1 != null) && (genre1 != undefined) && (genre1 !="") && (genre1 !="-1")){
        	ajax_search_genre(genre1,genre2);
        	$('.show04_inner').show('slow');
        }

        elements = document.getElementsByTagName("*") ;
        for (var i = 0; i < elements.length; ++i)
        with (elements[i])
        if (className == "thumRankTitleText" && innerHTML.length > showLength)
        innerHTML = innerHTML.substr(0, showLength) + '...' ;

        }
    // ONLOADイベントにセット
    window.onload = init();

	//都道府県プルダウンchangeイベント
	$("#search-pref").change(function(){
        var pref = $(this).children("option:selected").val();
        $("#city").html("<option value=\"-1\">--</option>");
        if (pref!="-1"){
            ajax_search(pref,"");
        }
    });

	//料理ジャンル1プルダウンchangeイベント
	$("#genre1").change(function(){
        var genre1 = $(this).children("option:selected").val();
        $("#genre2").html("<option value=\"-1\">--</option>");
        if (genre1!="-1"){
            ajax_search_genre(genre1,"");
        }
    });

	//ランキング検索もっと見る
    $("#ranking_readmore").click(function() {
        $(this).text("読込中…");
        var intPage = $("#intPage").val();
        var display_num = $("#display_num").val();
        var pref = $("#pref").val();
        var city = $("#city").val();
        var checks=[];
        $("[name='large_id[]']:checked").each(function(){
            checks.push(this.value);
        });
        var keyword = $("#search_keyword").val();
        $.post("/search/ajaxrankingmore", {limitnum:intPage,pref:pref,city:city,checks:checks,keyword:keyword}, function(strHtml){
            if (strHtml.length>0){
            	$("#intPage").val(Number(intPage) + Number(display_num));
                    $('div#ranklist').append(strHtml);

                    var showLength = 14 ; // タイトル表示文字数
                    elements = $('#ranklist, #ranklist *').children();
                    for (var i = 0; i < elements.length; ++i)
                    with (elements[i])
                    if (className == "thumRankTitleText" && innerHTML.length > showLength)
                    innerHTML = innerHTML.substr(0, showLength) + '...' ;

                    $("#ranking_readmore").text("もっと見る").attr("href", "javascript:void(0)");

                    //$("#readmore a").text("もっと見る").attr("href", "javascript:void(0)");
              } else {
            	  $("#readmore").addClass('disnon');
                 //$("#readmore a").text("The End")
                 //.attr("href", "#");
             }
        });
    });


  //店検索もっと見る
    $("#shop_readmore").click(function() {
        $(this).text("読込中…");
        var intPage = $("#intPage").val();
        var display_num = $("#display_num").val();
        var pref = $("#pref").val();
        var city = $("#city").val();
        var genre1 = $("#genre1").val();
        var genre2 = $("#genre2").val();
        var keyword = $("#search_keyword").val();
        $.post("/search/ajaxshopmore", {limitnum:intPage,pref:pref,city:city,genre1:genre1,genre2:genre2,keyword:keyword}, function(strHtml){
        	if (strHtml.length>0){
                $("#intPage").val(Number(intPage) + Number(display_num));

                $('div#shoplist').append(strHtml);

                $("#shop_readmore").text("もっと読む").attr("href", "javascript:void(0)");

                //$("#shopreadmore a").text("もっと読む").attr("href", "javascript:void(0)");
              } else {
                  $("#shopreadmore").addClass('disnon');
                 //$("#shopreadmore a").text("The End")
                 //.attr("href", "#");

             }
        });
    });

});


//小ジャンルajax連動
function ajax_search_genre( genre1,genre2){
    $.ajax({
        type: 'POST',
        data: {
            genre1:genre1,
        },
        url: '/search/ajaxgetgenre/',
        dataType: 'json',
        success: function(data) {
            $.each(data, function(i){
                if (genre2 == data[i].genre_id) {
                    $("#genre2").append("<option value=\"" + data[i].genre_id + "\"selected>" + data[i].value + "</option>");
                } else {
                    $("#genre2").append("<option value=\"" + data[i].genre_id + "\">" + data[i].value + "</option>");
                }
            });
        },
        error:function() {
            alert('見つかりませんでした。');
        }
    });
}

function send(url){
    var frm = this.document.forms[0];
    frm.action = url;
}

//大カテゴリチェックされた場合該当小カテゴリ一覧表示
function ajax_search_smalllist(obj,id,small_id){
    if(obj.checked){
    	var small_id ="";
    	small_id = $("#smalllist_"+id).val();
        $("#category_samll_list_"+id).show();
        $.post("/ranking/ajaxgetsmalllist/", {large_id:id,small_id:small_id}, function(data){
            if (data.length>0){
                $("#category_samll_list_"+id).html(data);
            }
        })
    }else{
        $("#category_samll_list_"+id).empty();
    }
}

//小カテゴリ一覧取得（初期表示）
function ajax_search_smalllist1(id,small_id){
    $("#category_samll_list_"+id).show();
    $.post("/ranking/ajaxgetsmalllist/", {large_id:id,small_id:small_id}, function(data){
       if (data.length>0){
            $("#category_samll_list_"+id).html(data);
        }
    })
}

//小カテゴリ選択されたイベント
function SelectSmallCategory(large_id) {
    var id= $("#category_largeid_"+large_id).val();
    $("#smallid_"+large_id).val(id);
}


//大カテゴリ選択されたイベント
function large_category_checked(obj,large_id) {
    if (obj.checked){
    	//$("#largecategory_"+large_id).attr({'checked':'checked'});
    } else{
    	$("input[name='smalllist_" + large_id + "']").attr("checked",false);
    }
}

//小カテゴリ選択されたイベント
function small_category_checked(obj,large_id) {
    if(obj.checked){
    	$("#largecategory_"+large_id).attr({'checked':'checked'});
    }
}
