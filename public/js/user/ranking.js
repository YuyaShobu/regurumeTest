//ランキング入力ページ
    $(document).ready(function(){
    	//初期非表示
    	$('.pref_inner_1').hide();
    	$('.pref_inner_2').hide();
    	$('.pref_inner_3').hide();

    	$('.show_inner_1').hide();
    	$('.show_inner_2').hide();
    	$('.show_inner_3').hide();

    	//都道府県選択イベント
    	$('#pref_1').on('change', function() {
    		$('.pref_inner_1').show('slow');
    		//画面上の値resetする
    		resetInput('1');
		});
    	$('#pref_2').on('change', function() {
    		$('.pref_inner_2').show('slow');
    		//画面上の値resetする
    		resetInput('2');
		});
    	$('#pref_3').on('change', function() {
    		$('.pref_inner_3').show('slow');
    		//画面上の値resetする
    		resetInput('3');
		});

    	//編集の確認画面戻ってきた場合初期設定
    	var pref_1 = $('#pref_1').val();
    	var pref_2 = $('#pref_2').val();
    	var pref_3 = $('#pref_3').val();
        if (pref_1!="-1"){
        	$('.pref_inner_1').show('slow');
        }
        if (pref_2!="-1"){
        	$('.pref_inner_2').show('slow');
        }
        if (pref_3!="-1"){
        	$('.pref_inner_3').show('slow');
        }

    	var shop_id_1 = $('#shop_id_1').val();
    	var shop_id_2 = $('#shop_id_2').val();
    	var shop_id_3 = $('#shop_id_3').val();
    	if ( shop_id_1 != "" && shop_id_1 !="shopregist") {
			$('.show_inner_1').show('slow');
    	}
    	if ( shop_id_2 != "" && shop_id_2 !="shopregist") {
			$('.show_inner_2').show('slow');
    	}
    	if ( shop_id_3 != "" && shop_id_3 !="shopregist") {
			$('.show_inner_3').show('slow');
    	}

        //ENTERキーによるSubmitを防止する
        $('input').keypress(function(ev) {
            if ((ev.which && ev.which === 13) || (ev.keyCode && ev.keyCode === 13)) {
                return false;
            } else {
                return true;
            }
        });

        //お店の候補を選択ボタン押すイベント
        $("#search_button_1").click(function(e){
            e.preventDefault();
            ajax_search(1);
        });
        $("#search_button_2").click(function(e){
            e.preventDefault();
            ajax_search(2);
        });
        $("#search_button_3").click(function(e){
            e.preventDefault();
            ajax_search(3);
        });

    	//画像プレビュー
        imgplayview('photo_1','destination_1','1');
        imgplayview('photo_2','destination_2','2');
        imgplayview('photo_3','destination_3','3');

        //クリック先によってアクションを変更
        $('[actionURL]').click(function(){
            if($(this).attr('actionURL') != ""){
                //タグ入力重複チェック
                if ($(this).attr('actionURL') == '/ranking/comfirm/') {
                    ret = check1();
                    if ( ret == false){
                        return false;
                    }
                }
                $('form').attr('action', $(this).attr('actionURL'));
            }
        });
    });


    //画像アップロードプレビュー
    function imgplayview(photo_id,destination_id,no) {
        if(typeof FileReader =='undefined')
        {
            if($.browser.msie===true)
            {
                if($.browser.version==6)
                {
                    $("#"+photo_id).change(function(event){
                          var src = event.target.value;
                          var img = '<img src="'+src+'" width="100px" height="100px" /><p><a href="" onclick="deletephoto('+no+');return false;">画像を削除</a></p>';
                          $("#"+destination_id).empty().append(img);
                      });
                }
                else if($.browser.version==7 || $.browser.version==8)
                {
                    $("#"+photo_id).change(function(event){
                          $(event.target).select();
                          var src = document.selection.createRange().text;
                          var dom = document.getElementById(destination_id);
                          dom.filters.item('DXImageTransform.Microsoft.AlphaImageLoader').src= src;
                          dom.innerHTML = '';
                     });
                }
            }
            else if($.browser.mozilla===true)
            {
                $("#"+photo_id).change(function(event){
                    if(event.target.files)
                    {
                      for(var i=0;i<event.target.files.length;i++)
                      {
                          var img = '<img src="'+event.target.files.item(i).getAsDataURL()+'" width="100px" height="100px"/><p><a href="" onclick="deletephoto('+no+');return false;">画像を削除</a></p>';
                          $("#"+destination_id).empty().append(img);
                      }
                    }
                });
            }
        }
        else
        {
    	   $("#"+photo_id).change(function(e){
               for(var i=0;i<e.target.files.length;i++)
               {
                    var file = e.target.files.item(i);
                    if(!(/^image\/.*$/i.test(file.type)))
                    {
                        continue;
                    }
                    var freader = new FileReader();
                    freader.readAsDataURL(file);
                    freader.onload=function(e)
                    {
                        var img = '<img src="'+e.target.result+'" width="100px" height="100px"/><p><a href="" onclick="deletephoto('+no+');return false;">画像を削除</a></p>';
                        $("#"+destination_id).empty().append(img);
                    }
               }
    	   });
          var destDom = document.getElementById(destination_id);
          destDom.addEventListener('dragover',function(event){
              event.stopPropagation();
              event.preventDefault();
              },false);

          destDom.addEventListener('drop',function(event){
              event.stopPropagation();
              event.preventDefault();
              var img_file = event.dataTransfer.files.item(0);
              if(!(/^image\/.*$/.test(img_file.type)))
              {
                  return false;
              }
              fReader = new FileReader();
              fReader.readAsDataURL(img_file);
              fReader.onload = function(event){
                  destDom.innerHTML='';
                  destDom.innerHTML = '<img src="'+event.target.result+'" width="100px" height="100px"/><p><a href="" onclick="deletephoto('+no+');return false;">画像を削除</a></p>';
                  };
          },false);
        }
        //$('#'+destination_id).css('margin-top','5px');
    }

  //大カテゴリチェックされた場合該当小カテゴリ一覧表示
    function ajax_search_smalllist(obj,id){
        if(obj.checked){
            $("#category_samll_list_"+id).show();
            $.post("/ranking/ajaxgetsmalllist/", {large_id:id}, function(data){
                if (data.length>0){
                    $("#category_samll_list_"+id).html(data);
                }
            })
        }else{
            $("#category_samll_list_"+id).empty();
        }
    }

    //小カテゴリ一覧取得（初期表示）
    function ajax_search_smalllist1(id,rank_id){
        $("#category_samll_list_"+id).show();
        $.post("/ranking/ajaxgetsmalllist/", {large_id:id,rank_id:rank_id}, function(data){
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

    //タブ追加
    function addInput() {
        var arInput = $("#rankingtag li").length;
        if ( arInput < 5) {
           var tagtxt = $("#tagtxt").val();
           tagtxt =  String(tagtxt)
				.replace(/&(?!\w+;)/g, '&amp;')
				.replace( /</g, "&lt;" )
				.replace( />/g, "&gt;" )
				.replace( /"/g, "&quot;" )
				.replace( /'/g, "&#39;" );

           if (tagtxt != "") {
               if (check(tagtxt)) {
                   $("#tagtxt").val('');
                   arInput ++;
                   $("#rankingtag").append('<span id=\"group'+arInput+'\"><li>'+tagtxt+'<button type=\"button\" onclick=\"delInput('+arInput+')\">×</button><input type=\"hidden\" name=\"tag[]\" value=\"'+tagtxt+'\" /></li></span>');
               } else {
                   alert("タグ重複です。");
               }
           } else {
               alert("タグを入力してください。");return false;
           }
        } else {
           alert("タグの登録は最大5つです。");return false;
        }
    }

    //タグ入力値重複チェック
    function check(obj) {
        var str = obj;
        var values = [];
        $("[name='tag[]']").each(function(){
            if (this.value != "") {
                values.push(this.value);
            }
         });
            var j = 0;
            for(var i =0; i <= values.length; i++){
                if( str == values[i]){
                    j = j +1;
                }
            }
            if (j >= 1) {
                //alert("タグ重複です。");
                return false;
            }
        return true;
    }

    //submitボタン押された場合タグ入力値重複チェック
    function check1() {
        var values = [];
        $("[name='tag[]']").each(function(){
            if (this.value != "") {
                values.push(this.value);
            }
         });
        if (values.length > 1) {
            for(var i = 0; i < values.length; i++){
                var j = 0;
                var str = values[i];
                for(var t = 0; t < values.length; t++){
                    if (str == values[t]) {
                        j = j+1;
                    }
                    if (j > 1) {
                        alert("タグ重複です。");
                        return false;
                    }
                }
            }
        }
    }

    //タグ削除
    function delInput(i) {
        var rank_id = $("#rank_id").val();
        $("#group"+i).remove();
    }

    //画像削除
    function deletephoto(no){
        $('#photo_'+no).val('');
        $("#destination_"+no).empty().append("<p class=\"fileCaption\">写真をアップしましょう</p>");
        //$("#destination_"+no+" img").attr("src","/img/pc/common/dammy.jpg");
        //画像削除フラグ
        $('#photo_delflg_'+no).val(1);
        return false;
    }

    //店名ajax検索
    function ajax_search(no){
        $("#search_results_"+no).show();
        var search_val =$("#shop_name_"+no).val();
        var pref =$("#pref_"+no).val();
        if (search_val != "") {
	        $.post("/ranking/ajaxgetshoplist/", {shopname : search_val , id : no , pref : pref }, function(data){
	            if (data != false){
	                $("#search_results_"+no).html(data);
	   			 	$("#regist_shop_button_"+no).addClass('disnon');
					$("#searchshop_"+no).change(function(){
		   			 	$('.show_inner_'+no).show('slow');
				    	var obj = $('#searchshop_'+no).val();
				    	var txt = $("#searchshop_"+no+" option:selected").text();
				    	if (obj =="shopregist") {
				    		$("#shop_name_"+no).val('');
				    		//safari window.open効かない対策
				    		if ( !window.open( "/shop/regist/") ) {
				    			window.location.href = "/shop/regist/";
				    	    }
				    	} else {
					    	$("#shop_id_"+no).val(obj);
					    	var term = txt.split( '　' );
					    	var shopname = term[0];
					    	var address = term[1];
				    		$("#shop_name_"+no).val(shopname);
				    	}
					});
				 } else {
					$("#search_results_"+no).empty();
					$("#search_results_"+no).append("<p class=\"btn show02\" id=\"regist_shop_button_" + no + "\" >お店が見つかりませんこちら</p>");
					$("#regist_shop_button_"+no).click(function(){
						window.open("/shop/regist/");
					});
	            }
	        })
        }
    }

    //都道府県選びましたら、画面上の値resetする
    function resetInput(no){
    	$("#shop_name_"+no).val('');
    	$("select#searchshop_"+no).remove();
    	$("#shop_id_"+no).val('');
    	$("#explain_"+no).val('');
    	//画像クリア
        $('#photo_'+no).val('');
        //$("#destination_"+no).empty().append("<p class=\"fileCaption\">写真をアップしましょう</p>");
    	//deletephoto(no);
    }

    //ajax店名検索(autocomplete)
    function search_shop(no) {
        //shop1店名検索
    	$('#shop_name_'+no).autocomplete({
    		source: function( request, response ) {
    			var shop_name_no = $('#shop_name_'+no).val();
    			$('#shop_id_'+no).val('');
    			$.ajax({
    				url: "/shop/ajaxgetshopname",
    				dataType :'json',
    				data: {
    					shop_name: shop_name_no
    				},
    				success: function( data ) {
    					if (data != false) {
    						response( $.map( data, function( item ) {
    							return {
    								lable: item.shop_id,
    								value: item.shop_name+item.address
   							}
    						}));
    					} else {
    						$("#shop_regist_link_"+no).empty();
    						$("#shop_regist_link_"+no).append("<p class=\"btn show02\" id=\"regist_shop_button_" + no + "\" >お店が見つかりませんこちら</p>");
        					$("#regist_shop_button_"+no).click(function(){
        						window.open("/shop/regist/");
        					});
    					}
    				}
    			});
    		},
    		select: function( event, ui ) {
    			 $('#shop_id_'+no).val(ui.item.lable);
    			 $('.show_inner_'+no).show('slow');
    			 $("#regist_shop_button_"+no).addClass('disnon');
    	    },
    		minLength: 1
    	});
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
