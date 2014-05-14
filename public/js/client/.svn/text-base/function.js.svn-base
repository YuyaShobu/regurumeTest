/*pagetop*/
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
});

/*login表示*/
$(function(){
$('.navGInner').hide();
//$('#navList').mouseover(function()  {
$('#navList') .on('click', function() {
$('.navGInner').show();
});
});

/*form拡張 IEでもplaceholder属性を使えるように*/
(function(w){
	var is_ael = ('function' == (typeof w.addEventListener) ),
	is_ae = ( 'object' == (typeof w.attachEvent) ),
	
	init = function() {
		var d = document;
		
		//placeholder属性がサポートされていれば無視
		if ( 'placeholder' in d.createElement('input') ) {
			return false;
		}
		
		//placeholder表示時のテキスト色
		var phc = '#999',
		
		onFocus = function( e, dc, txt ) {
			if ( e.value === txt ) {
				e.value = '';
				e.style.color = dc;
			}
		},
		
		onBlur = function( e, txt ) {
			var val = e.value;
			if ( !val || val.replace(/[\s\t\n\r]/g,'') === '') {
				e.value = txt;
				e.style.color = phc;
			} else if (val === txt) {
				e.style.color = phc;
			}
		},
		
		setPlaceholder = function( form, e ) {
			var txt = e.getAttribute( 'placeholder' );
			if ( 'string' != (typeof txt) ) {
				return false;
			}
			
			//フォームのデフォルトテキスト色を取得
			var dc = '#000';
			if ( e.currentStyle ) {
				dc = e.currentStyle['color'];
			} else if ( d.defaultView && d.defaultView.getComputedStyle ) {
				dc = d.defaultView.getComputedStyle(e,"")['color'];
			} else {
				dc = e.style['color'];
			}
			
			if ( is_ael ) {
				e.addEventListener("focus", function(){
					onFocus( e, dc, txt );
				}, false);
				e.addEventListener("blur", function(){
					onBlur( e, txt );
				}, false);
				form.addEventListener("submit", function(){
					if ( e.value === txt ) {
						e.value = '';
					}
				});
			} else if (is_ae) {
				e.attachEvent("onfocus", function(){
					onFocus( e, dc, txt );
				});
				e.attachEvent("onblur", function(){
					onBlur( e, txt );
				});
				form.attachEvent("onsubmit", function(){
					if ( e.value === txt ) {
						e.value = '';
					}
				});
			}
			
			onBlur( e, txt );
		};
		
		var forms = d.getElementsByTagName('form');
		for ( var i = forms.length; i--; ) {
			var form = forms[i];
			
			var inputs = form.getElementsByTagName('input');
			for ( var j=inputs.length; j--; ) {
				setPlaceholder( form, inputs[j] );
			}
			
			var textareas = form.getElementsByTagName('textarea');
			for ( var j = textareas.length; j--; ) {
				setPlaceholder( form, textareas[j] );
			}
		}
	};
	
	if ( is_ael ) {
		w.addEventListener("load", init, false);
	} else if ( is_ae ) {
		w.attachEvent("onload", init);
	}
})(window);