//tagranking.js

$(function() {

	//ランキングもっと見る
    $("#readmore a").click(function() {
        $(this).text("読込中…");
        var tag_name = $("#tag_name").val();
        var intPage = $("#intPage").val();
        var display_num = $("#display_num").val();
        $.post("/tag/ajaxrankingmore", {limitnum:intPage,tagname:tag_name}, function(strHtml){
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

});