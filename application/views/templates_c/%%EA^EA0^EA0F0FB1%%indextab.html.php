<?php /* Smarty version 2.6.27, created on 2014-05-13 05:05:13
         compiled from ./index/indextab.html */ ?>
<!--#トップタブエリア/-->
<ul class="navTab inline" id="tab">
    <li  <?php if ($this->_tpl_vars['view_flg'] == 'new' || $this->_tpl_vars['view_flg'] == ""): ?>class="current" <?php endif; ?>>
        <a href="/" >みんなの新着</a>
    </li>
    <li <?php if ($this->_tpl_vars['view_flg'] == 'reguru'): ?>class="current" <?php endif; ?>>
        <a href="/index/reguru">みんなのリグルメ</a>
    </li>
    <li <?php if ($this->_tpl_vars['view_flg'] == 'pageview'): ?>class="current" <?php endif; ?>>
        <a href="/index/pageview">みんなの閲覧数順</a>
    </li>

<?php if ($this->_tpl_vars['user_id'] != null): ?>
    <li <?php if ($this->_tpl_vars['view_flg'] == 'follow'): ?>class="current" <?php endif; ?>>
        <a href="/index/follow">フォロー</a>
    </li>
<?php endif; ?>

</ul>

<!--#トップタブエリア/-->