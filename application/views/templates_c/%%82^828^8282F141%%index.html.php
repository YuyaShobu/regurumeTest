<?php /* Smarty version 2.6.27, created on 2014-05-13 05:05:13
         compiled from index/index.html */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "./common/head_part_start.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "./common/head_part_meta.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "./common/head_part_js.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<style type="text/css">
    #destination{filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(true,sizingMethod=scale);}
</style>
<script type="text/javascript" src="/js/user/top.js"></script>
<?php if ($this->_tpl_vars['user_id'] != null): ?>
<script type="text/javascript" src="/js/user/destination.js"></script>
<?php endif; ?>
<script src="/js/user/jquery.masonry.min.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$(window).load(function(){
			$('#masonry').masonry({
				itemSelector : '.thumbBox01',
				isAnimated: true,
				isFitWidth: true
			});
		});
	});
</script>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "./common/head_part_css.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "./common/head_part_end.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<body>

<noscript>
<div class="noScript">サイトを快適に利用するためには、JavaScriptを有効にしてください。</div>
</noscript>
<!--#wrapper/-->
<div id="wrapper">
    <!--#header/-->
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "./common/header.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    <!--/#header-->
    <div id="headerUneder">
        <!--#pankuzu/-->
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "./common/pankuzu.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <!--#pankuzu/-->
    </div>
    <!--#contents/-->
<!--#トップ未ログインの場合表示/-->
<?php if ($this->_tpl_vars['user_id'] == null): ?>
<div id="container" class="wrap">
    <div><!--TOP SLIDE/-->
        <div class="block02"><a href="/about/"><img src="/img/pc/common/main01.jpg" alt="" /></a></div>
        <!--/TOP SLIDE--></div>
    <div>
        <div class="contents">
             <h2 class="titleBox01"><span>:</span>みんなが作ったランキング</h2>
            <!--TAB NAViGATION/-->
            <div class="block navTabBox">

                <ul class=" navSmall inline phone-hidden">
                    <li><a href="/ranking/input"><img src="/img/pc/common/icon-ranking_bk.png" alt="" />ランキングを作る</a></li>
                </ul>
                <div class="clearfix">
                    <div class="left">
                    <!--#トップタブエリア/-->
                    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "./index/indextab.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                    <!--#トップタブエリア/-->
                    </div>
                </div>

            </div>
            <!--/TAB NAViGATION-->
        </div>

        <!--#トップ共通エリア/-->
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "./index/common.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <!--#トップ共通エリア/-->
    </div>
</div>

<!--#トップログインの場合表示/-->
<?php else: ?>
    <div id="container" class="wrap clearfix">
        <div id="colomnContents">

            <!--登録/-->
            <!--
            <div class="block01">
                <form enctype="multipart/form-data" id="form_search" name="form_search" action="/beento/insert/" method="post">
                    <div class="boxGotoshop space01">
                        <div class="clearfix">
                            <div class="column1-1"> <img src="/img/pc/common/serach_img3.png" alt="" width="80" height="80" /></div>


                            <div class="column1-2">
                                <ul class="textError">
                                    <?php $_from = $this->_tpl_vars['error']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['error_message']):
?>
                                    <li><?php echo $this->_tpl_vars['error_message']; ?>
</li>
                                    <?php endforeach; endif; unset($_from); ?>
                                </ul>

						        <dl class="listDl1">
						            <dt class="title">「行ったお店」にお店を登録しよう！</dt>
						            <dd>
                                        <select name="pref" id="beento_pref" class="show03">
                                            <option value="-1">お店の場所を選んでください</option>
                                        <?php $_from = $this->_tpl_vars['pref']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['v']):
?>
                                            <option value="<?php echo $this->_tpl_vars['v']['pref_code']; ?>
" <?php if ($this->_tpl_vars['info']['pref'] == $this->_tpl_vars['v']['pref_code']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['v']['value']; ?>
</option>
                                        <?php endforeach; endif; unset($_from); ?>
                                        </select>
                                        <div class="show03_inner">
						                	<input type="text"  disabled="disabled"  name="shop_name" id="shop_name" placeholder="お店の名前を入力して、「店舗を検索」ボタンを押してください。" value="<?php echo $this->_tpl_vars['shop_name']; ?>
"/>
                                        	<input type="button"  disabled="disabled" value="店舗を検索" id="search_button" class="btn btnF btnC01 show01" />
                                        </div>
                                        <div id="search_results"></div>
                                    </dd>
					  		    </dl>
					  		    <div class="show01_inner">
	                                <dl class="listDl1">
						                    <dt>感想をどうぞ！</dt>
						                    <dd><textarea name="explain" id="explain" class="explain" rows="4" placeholder="お店の良かったところや、美味しかった料理を書いてください＾＾"></textarea></dd>
						                    <dt>撮影した写真を記録に残そう！</dt>
						                    <dd> <div class="file">
	                                                    <input name="photo"  id="photo" type="file"/>
	                                                    <div id="destination">
	                                                       <p class="fileCaption"> </p>
	                                                    </div>
	                                                </div>
	                                                <br>
	                                                <div id="showdelphoto" class="disnon">
	                                                   <a href="" id="delphoto"  class="linkArrow">画像を削除</a>
	                                                </div>
						                	</dd>
						            </dl>

						            <div class=" text-center space02">
	                                        <div>
	                                            <button class="btn btnL" type="submit" onclick = "inputcheck();return false;">登録する</button>
	                                        </div>
	                                        <?php if ($this->_tpl_vars['fb_connect_flg']): ?>
	                                        <input id="facebook" type="checkbox"  name="fb_feed_flg" value="1"/>
	                                        <label for="facebook"> Facebookにも投稿</label>
	                                        <?php endif; ?>
	                               </div>
                               </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            -->

            <!--/登録-->
            <h2 class="titleBox01"><span>:</span>みんなが作ったランキング</h2>
            <!--TAB NAViGATION/-->
            <div class="block navTabBox">
                <div class="clearfix">
                    <div class="left">
                <!--#トップタブエリア/-->
                    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "./index/indextab.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                <!--#トップタブエリア/-->
                    </div>
                </div>
            </div>
            <!--/TAB NAViGATION-->


        <!--#トップ共通エリア/-->
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "./index/common.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <!--#トップ共通エリア/-->

<?php if ($this->_tpl_vars['view_flg'] == 'follow'): ?>
        <!--#タイムライン/-->
        <div id="tl" class="content_wrap">
            <div class="block thumbnailBox" id="ranklist_timeline">
            <div id="masonry">

                <?php if ($this->_tpl_vars['top_follow'] != array ( )): ?>
                    <?php $_from = $this->_tpl_vars['top_follow']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['rank'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['rank']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['value']):
        $this->_foreach['rank']['iteration']++;
?>

                        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "./common/ranking_follow.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

                    <?php endforeach; endif; unset($_from); ?>
                <?php endif; ?>
            </div>
            </div>
            <?php if ($this->_tpl_vars['top_follow'] && ( $this->_tpl_vars['top_follow']['0']['cnt'] > $this->_tpl_vars['display_numinit'] )): ?>
                <div class="block moreRead" id="readmore_timeline">
                    <a href="javascript:void(0);"  id="atag_timeline">もっと見る</a>
                    <input type="hidden" id="intPage_timeline" name="intPage_timeline" value="<?php echo $this->_tpl_vars['display_numinit']; ?>
">
                    <input type="hidden" id="display_num_timeline" name="display_num_timeline" value="<?php echo $this->_tpl_vars['display_num']; ?>
">
                </div>
            <?php endif; ?>
        </div>
        <!--#タイムライン/-->
<?php endif; ?>

        </div>

        <!--#ログイン右サイド/-->
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "./common/rightside.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <!--#ログイン右サイド/-->

    </div>
<?php endif; ?>

    <!--/#container-->
    <!--#footer/-->
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "./common/footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    <!--/#footer-->
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "./common/pagetop.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    </div>
<!--/#wrapper-->
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "./common/tag.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</body>
</html>