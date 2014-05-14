<?php /* Smarty version 2.6.27, created on 2014-05-13 10:41:11
         compiled from ./common/pankuzu.html */ ?>

<!--#pankuzu/-->
    <div class="wrap">
    <!--#breadcrumb/-->
    <ul id="breadcrumb" class="inline">
        <li><a href="/">Re:gurume（リグルメ）-私的ランキングで交流するグルメサイト</a></li>
        
        　<a href="/">Top</a>

		<?php $_from = $this->_tpl_vars['pankuzu']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['id'] => $this->_tpl_vars['data']):
?>
			　>　<a href="/<?php echo $this->_tpl_vars['data']; ?>
"><?php echo $this->_tpl_vars['data']; ?>
</a>
		<?php endforeach; endif; unset($_from); ?>
        
        
        
        
    </ul>
    <!--/#breadcrumb-->
</div>
<!--#pankuzu/-->