<?php /* Smarty version 2.6.27, created on 2014-05-13 05:05:13
         compiled from ./common/header.html */ ?>
    <div id="header">
        <div class="wrap">
            <div class="left">
                <h1><a href="/"><img src="/img/pc/common/header_logo.png" alt="Re:gurume（リグルメ）-私的ランキングで交流するグルメサイト" /></a></h1>
            </div>
            <div class="right">
                <ul id="navAction01">
                    <li><a href="/">トップ</a></li>
                    <li><a href="/about/">リグルメの楽しみ方</a></li>
                    <?php if ($this->_tpl_vars['user_id'] == null): ?>
                    <li><a href="/login/newsignup">会員登録</a></li>
                    <li><a href="/login/index">ログイン</a></li>
                    <?php else: ?>
                    <li id="navLogin">マイメニュー▼
                        <ul class="navLoginInner">
                            <li><a href="/user/myranking/id/<?php echo $this->_tpl_vars['user']['user_id']; ?>
">マイページ</a></li>
                            <li><a href="/user/index">プロフィール設定</a></li>
                            <li><a href="/bookmarklet/ikitai/">ブックマークレット</a></li>
                            <li><a href="/login/logout">ログアウト</a></li>
                        </ul>
                    </li>
                    <?php endif; ?>
                </ul>
                <div>
                <!--
                <form  id="form_main" name="form_main" action="/search/searchranking" method="post" class="searcHeaderRankingForm">
                    <input type="hidden" id="user_id" name="user_id" value="<?php echo $this->_tpl_vars['user_id']; ?>
" />
                    <ul class="searcHeaderRanking" >
                        <li>
                            <select name="pref" id="pref">
                                <option value="-1">都道府県</option>
                                <?php $_from = $this->_tpl_vars['pref']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['v']):
?>
                                    <option value="<?php echo $this->_tpl_vars['v']['pref_code']; ?>
" <?php if ($this->_tpl_vars['info']['pref'] == $this->_tpl_vars['v']['pref_code']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['v']['value']; ?>
</option>
                                <?php endforeach; endif; unset($_from); ?>
                            </select>
                        </li>
                        <li><input type="text" id="search_keyword" name="search_keyword"  value="<?php echo $this->_tpl_vars['info']['search_keyword']; ?>
" class="searchFreeword" placeholder="ランキングを検索"  /><button type="submit" class="searchIconBtn" title="検索する">
                        <img src="/img/pc/common/icon-search_bk.png" alt="検索する" /></button>
                        </li>
                    </ul>
                 </form>
                  -->
                    <ul id="navAction02">
                        <li><a href="/search/searchranking"><img src="/img/pc/common/icon-search.png" alt="" />ランキングを探す</a></li>
                        <li><a href="/search/searchshop"><img src="/img/pc/common/icon-search.png" alt="" />お店を探す</a></li>
                        <?php if ($this->_tpl_vars['user_id'] == null): ?>
                            <li>
                                <a href="/login/index"><img src="/img/pc/common/icon-men.png" alt="" />行ったお店を登録する</a>
                            </li>
                        <?php else: ?>
                            <li>
                                <a href="/beento/input"><img src="/img/pc/common/icon-men.png" alt="" />行ったお店を登録する</a>
                            </li>
                        <?php endif; ?>
                        <li><a href="/ranking/input"><img src="/img/pc/common/icon-ranking.png" alt="" />ランキングを作る</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>