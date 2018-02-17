<?php /* Smarty version 2.6.28, created on 2018-02-17 19:33:10
         compiled from ajax/newshops.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'modurl', 'ajax/newshops.tpl', 4, false),array('function', 'shorttext', 'ajax/newshops.tpl', 7, false),array('function', 'gt', 'ajax/newshops.tpl', 50, false),array('modifier', 'cleantext', 'ajax/newshops.tpl', 7, false),array('modifier', 'replace', 'ajax/newshops.tpl', 7, false),array('modifier', 'round', 'ajax/newshops.tpl', 31, false),array('modifier', 'intval', 'ajax/newshops.tpl', 32, false),)), $this); ?>
<?php $_from = $this->_tpl_vars['newshops']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
<div class="col-sm-4 btm-special-product hover-border">
    <div class="thumbnail">
        <a href="<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'user','func' => 'site','shop_id' => $this->_tpl_vars['item']['shopid']), $this);?>
">
            <div class="btm-product-hed">
                <div class="btm-product-sub-text">
                    <span><?php echo smarty_function_shorttext(array('text' => ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['item']['shop_name'])) ? $this->_run_mod_handler('cleantext', true, $_tmp) : smarty_modifier_cleantext($_tmp)))) ? $this->_run_mod_handler('replace', true, $_tmp, '<br>', ' ') : smarty_modifier_replace($_tmp, '<br>', ' ')),'len' => 23), $this);?>
,</span>
                    <span class="sub-name"><?php echo smarty_function_shorttext(array('text' => $this->_tpl_vars['item']['city_name'],'len' => 15), $this);?>
</span>
                </div>
                <?php if ($this->_tpl_vars['item']['aff_id'] > 0): ?>
                <?php $this->assign('imagename', ((is_array($_tmp=$this->_tpl_vars['item']['affiliate_image'])) ? $this->_run_mod_handler('replace', true, $_tmp, ' ', '%20') : smarty_modifier_replace($_tmp, ' ', '%20'))); ?>
                <?php $this->assign('image', "modules/ZSELEX/images/affiliates/".($this->_tpl_vars['imagename'])); ?>
                <?php if (is_file ( $this->_tpl_vars['image'] )): ?>
                <span class="icon-member">
                    <img src="<?php echo $this->_tpl_vars['baseurl']; ?>
modules/ZSELEX/images/affiliates/<?php echo $this->_tpl_vars['item']['affiliate_image']; ?>
" alt="" width="49" height="50">
                </span>
                <?php endif; ?>
                <?php endif; ?>
            </div>
            <div class="pro-image">
                <?php if (! $this->_tpl_vars['item']['no_image']): ?>
                <?php echo $this->_tpl_vars['item']['image']; ?>

                <?php endif; ?>
            </div>
        </a>
        <div class="btm-product-caption clearfix">
            <div class="rating-star">
                                <?php unset($this->_sections['starcount']);
$this->_sections['starcount']['name'] = 'starcount';
$this->_sections['starcount']['loop'] = is_array($_loop=((is_array($_tmp=$this->_tpl_vars['item']['rating'])) ? $this->_run_mod_handler('round', true, $_tmp) : round($_tmp))) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['starcount']['show'] = true;
$this->_sections['starcount']['max'] = $this->_sections['starcount']['loop'];
$this->_sections['starcount']['step'] = 1;
$this->_sections['starcount']['start'] = $this->_sections['starcount']['step'] > 0 ? 0 : $this->_sections['starcount']['loop']-1;
if ($this->_sections['starcount']['show']) {
    $this->_sections['starcount']['total'] = $this->_sections['starcount']['loop'];
    if ($this->_sections['starcount']['total'] == 0)
        $this->_sections['starcount']['show'] = false;
} else
    $this->_sections['starcount']['total'] = 0;
if ($this->_sections['starcount']['show']):

            for ($this->_sections['starcount']['index'] = $this->_sections['starcount']['start'], $this->_sections['starcount']['iteration'] = 1;
                 $this->_sections['starcount']['iteration'] <= $this->_sections['starcount']['total'];
                 $this->_sections['starcount']['index'] += $this->_sections['starcount']['step'], $this->_sections['starcount']['iteration']++):
$this->_sections['starcount']['rownum'] = $this->_sections['starcount']['iteration'];
$this->_sections['starcount']['index_prev'] = $this->_sections['starcount']['index'] - $this->_sections['starcount']['step'];
$this->_sections['starcount']['index_next'] = $this->_sections['starcount']['index'] + $this->_sections['starcount']['step'];
$this->_sections['starcount']['first']      = ($this->_sections['starcount']['iteration'] == 1);
$this->_sections['starcount']['last']       = ($this->_sections['starcount']['iteration'] == $this->_sections['starcount']['total']);
?>
                <?php $this->assign('i', ((is_array($_tmp=$this->_sections['starcount']['iteration'])) ? $this->_run_mod_handler('intval', true, $_tmp) : intval($_tmp))); ?>
                <div class="star" id=<?php echo $this->_tpl_vars['i']; ?>
></div>
                <?php endfor; endif; ?>
            </div>
            <div class="transparent">
                <div class="star" id="1"></div>
                <div class="star" id="2"></div>
                <div class="star" id="3"></div>
                <div class="star" id="4"></div>
                <div class="star" id="5"></div>
            </div>
            <div class="rating-right">
                <?php echo $this->_tpl_vars['item']['see_ful_store']; ?>

            </div>
        </div>
    </div>
</div>
<?php endforeach; else: ?>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo smarty_function_gt(array('text' => 'No shops found'), $this);?>
 

<?php endif; unset($_from); ?>    

