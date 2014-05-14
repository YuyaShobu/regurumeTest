//pagetop
$(function() {
    var topBtn = $('.pageTop');
    //最初はボタンを隠す
    topBtn.hide();
    //スクロールが300に達したらボタンを表示させる
    $(window).scroll(function () {
        if ($(this).scrollTop() > 200) {
            topBtn.fadeIn();
        } else {
            topBtn.fadeOut();
        }
    });
    //スクロールしてトップに戻る
    //500の数字を大きくするとスクロール速度が遅くなる
    topBtn.click(function () {
        $('body,html').animate({
            scrollTop: 0
        }, 500);
        return false;
    });

    //フッタエリアご意見のメールのajax処理
    $("#btnContactSend").click(function(){
        var contact_uid =  $("#contact_user_id").val();
        var contact_name =  $("#contact_user_name").val();
        var contact_text = $("#contact_text").val();
    	$.ajax({
	      type: 'POST',
	      data: {
	          uid:contact_uid,cname:contact_name,ctxt:contact_text
	      },
	      url: '/contact/ajaxcontact/',
	      dataType: 'json',
	      success: function(data) {
	         if (data != false) {
                 $("#contact_input").hide();
                 $("#contact_done").show();
	         } else {
                 $("#contact_input").hide();
                 $("#mail_failure").show();
	         }
	      },
	      error:function() {
              $("#contact_input").hide();
              $("#mail_failure").show();
	      }
	  });

	  });
});

//login表示
$(function(){
$('.navLoginInner').hide();
$('.show01_inner').hide();
$('.show02_inner').hide();
$('.show03_inner').hide();
$('.show04_inner').hide();

$('#navLogin').on('click', function() {
$(this).children().slideToggle();
});
$('.show01').on('click', function() {
});
$('.show02').on('click', function() {
$('.show02_inner').show('slow');
});
$('.show03').on('change', function() {
$('.show03_inner').show('slow');
});
$('.show04').on('change', function() {
$('.show04_inner').show('slow');
});


});


function logincheck(){
    var uid = "";
    var ruri = "";
    uid = $("#user_id").val();
    if (uid == "") {
    	ruri = window.location.pathname;
    	request = ruri.replace(/\u002f/g, '_');
        window.location = '/login/index/unlogin/' + request + '/';
    } else{
        return true;
    }
}

//都道府県と市町区村連動
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

/*ランキングのサムネイル表示の時の文字表示制限*/
var showLength = 19 ; // 表示文字数
onload = function() {
	elements = document.getElementsByTagName("*") ;
	for (var i = 0; i < elements.length; ++i)
    with (elements[i])
    if (className == "thumRankTitleText" && innerHTML.length > showLength)
    innerHTML = innerHTML.substr(0, showLength) + '...' ;
}
