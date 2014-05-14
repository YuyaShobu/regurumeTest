<?php /* Smarty version 2.6.27, created on 2014-05-13 05:05:13
         compiled from ./index/common.html */ ?>
<?php if ($this->_tpl_vars['view_flg'] == 'new'): ?>

 <!--#新着/-->

<div id="current" class="content_wrap">

<div class="block thumbnailBox" id="ranklist_new">
    <?php if ($this->_tpl_vars['top_new'] != array ( )): ?>
        <?php $_from = $this->_tpl_vars['top_new']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['rank'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['rank']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['value']):
        $this->_foreach['rank']['iteration']++;
?>

            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "./common/ranking.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

        <?php endforeach; endif; unset($_from); ?>
    <?php endif; ?>
</div>

<?php if ($this->_tpl_vars['top_new'] && ( $this->_tpl_vars['top_new']['0']['cnt'] > $this->_tpl_vars['display_numinit'] )): ?>
<div class="block moreRead" id="readmore_new">
    <a href="javascript:void(0);" id="atag_new">もっと見る</a>
        <input type="hidden" id="intPage_new" name="intPage_new" value="<?php echo $this->_tpl_vars['display_numinit']; ?>
">
        <input type="hidden" id="display_num_new" name="display_num_new" value="<?php echo $this->_tpl_vars['display_num']; ?>
">
</div>
<?php endif; ?>

</div>

<!--#新着/-->

<?php endif; ?>


<?php if ($this->_tpl_vars['view_flg'] == 'reguru'): ?>

 <!--#気に入り/-->
 <div id="reguru" class="content_wrap">
 <div class="block thumbnailBox" id="ranklist_reguru">
     <?php if ($this->_tpl_vars['top_reguru'] != array ( )): ?>
         <?php $_from = $this->_tpl_vars['top_reguru']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['rank'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['rank']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['value']):
        $this->_foreach['rank']['iteration']++;
?>

            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "./common/ranking.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

         <?php endforeach; endif; unset($_from); ?>
     <?php endif; ?>
 </div>

<?php if ($this->_tpl_vars['top_reguru'] && ( $this->_tpl_vars['top_reguru']['0']['cnt'] > $this->_tpl_vars['display_numinit'] )): ?>
 <div class="block moreRead" id="readmore_reguru">
     <a href="javascript:void(0);" id="atag_reguru">もっと見る</a>
         <input type="hidden" id="intPage_reguru" name="intPage_reguru" value="<?php echo $this->_tpl_vars['display_numinit']; ?>
">
         <input type="hidden" id="display_num_reguru" name="display_num_reguru" value="<?php echo $this->_tpl_vars['display_num']; ?>
">
 </div>
 <?php endif; ?>

 </div>
 <!--#気に入り/-->

<?php endif; ?>


<?php if ($this->_tpl_vars['view_flg'] == 'pageview'): ?>
 <!--#ページビュー数/-->
 <div id="pv" class="content_wrap">
 <div class="block thumbnailBox" id="ranklist_pv">
     <?php if ($this->_tpl_vars['top_pageview'] != array ( )): ?>
         <?php $_from = $this->_tpl_vars['top_pageview']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['rank'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['rank']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['value']):
        $this->_foreach['rank']['iteration']++;
?>

            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "./common/ranking.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

         <?php endforeach; endif; unset($_from); ?>
     <?php endif; ?>
 </div>
<?php if ($this->_tpl_vars['top_pageview'] && ( $this->_tpl_vars['top_pageview']['0']['cnt'] > $this->_tpl_vars['display_numinit'] )): ?>
 <div class="block moreRead" id="readmore_pv">
     <a href="javascript:void(0);"  id="atag_pv">もっと見る</a>
         <input type="hidden" id="intPage_pv" name="intPage_pv" value="<?php echo $this->_tpl_vars['display_numinit']; ?>
">
         <input type="hidden" id="display_num_pv" name="display_num_pv" value="<?php echo $this->_tpl_vars['display_num']; ?>
">
 </div>
 <?php endif; ?>

 </div>
 <!--#ページビュー数/-->

 <?php endif; ?>