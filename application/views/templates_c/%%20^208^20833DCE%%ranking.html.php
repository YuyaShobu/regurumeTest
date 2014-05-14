<?php /* Smarty version 2.6.27, created on 2014-05-13 05:05:13
         compiled from ./common/ranking.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', './common/ranking.html', 18, false),)), $this); ?>
<!--#ランキング一覧表示共通タグ/-->
<div class="thumbBox01">
         <div class="deco01"></div>
         <p class="thumArea01"><?php echo $this->_tpl_vars['value']['pref']; ?>
</p>
         <a href="/ranking/detail/id/<?php echo $this->_tpl_vars['value']['rank_id']; ?>
" target="_blank" class="thumRankLink" title="<?php echo $this->_tpl_vars['value']['title']; ?>
"><div class="space02">
             <ul class="inline thumCategory">
             <?php if ($this->_tpl_vars['value']['category'] != array ( )): ?>
                 <?php $_from = $this->_tpl_vars['value']['category']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['category'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['category']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['ctlk'] => $this->_tpl_vars['ctlv']):
        $this->_foreach['category']['iteration']++;
?><li>
                 <span href="" class="cate0<?php echo $this->_tpl_vars['ctlv']['large_id']; ?>
"><?php echo $this->_tpl_vars['ctlv']['category_name']; ?>
</span>
             </li><?php endforeach; endif; unset($_from); ?>
             <?php endif; ?>
             </ul>

             <p class="thumRankTitle"><span class="thumRankTitleText"><?php echo $this->_tpl_vars['value']['title']; ?>
</span><span class="rankDefiniteText">Best3</span><span class="thumRankReaction"><?php echo $this->_tpl_vars['value']['page_view']; ?>
view</span></p>
             <ul class="thumPhoto01">
                  <?php $_from = $this->_tpl_vars['value']['detail']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['detail'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['detail']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['dkey'] => $this->_tpl_vars['dvalue']):
        $this->_foreach['detail']['iteration']++;
?><li>
                     <span class="iconRank<?php echo $this->_tpl_vars['dvalue']['rank_no']; ?>
"></span>
                     <span class="thumPhoto01_thum"><img src="<?php echo $this->_tpl_vars['dvalue']['photo']; ?>
" alt="<?php echo ((is_array($_tmp=$this->_tpl_vars['dvalue']['shop_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"  style="<?php echo $this->_tpl_vars['dvalue']['style']; ?>
" <?php if ($this->_tpl_vars['dvalue']['width_size'] != ""): ?>width="<?php echo $this->_tpl_vars['dvalue']['width_size']; ?>
px"<?php endif; ?> <?php if ($this->_tpl_vars['dvalue']['height_size'] != ""): ?> height="<?php echo $this->_tpl_vars['dvalue']['height_size']; ?>
px" <?php endif; ?>/></span>
                  </li><?php endforeach; endif; unset($_from); ?>
             </ul>
         </div>
         </a>
         <div class="thumUser">
       <a href="/user/myranking/id/<?php echo $this->_tpl_vars['value']['user_id']; ?>
" title="<?php echo $this->_tpl_vars['value']['user_name']; ?>
">
       <img src="<?php echo $this->_tpl_vars['value']['user_photo']; ?>
"  alt="<?php echo $this->_tpl_vars['value']['user_name']; ?>
" />
         <?php echo $this->_tpl_vars['value']['user_name']; ?>

       </a>
      </div>
</div>
