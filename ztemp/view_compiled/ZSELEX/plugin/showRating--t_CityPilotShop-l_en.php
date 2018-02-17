<?php /* Smarty version 2.6.28, created on 2018-02-03 06:49:26
         compiled from plugin/showRating.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'pageaddvar', 'plugin/showRating.tpl', 2, false),array('modifier', 'intval', 'plugin/showRating.tpl', 9, false),)), $this); ?>
  
<?php echo smarty_function_pageaddvar(array('name' => 'stylesheet','value' => "themes/CityPilotResponsive/style/rating.css"), $this);?>

<?php echo smarty_function_pageaddvar(array('name' => 'javascript','value' => ($this->_tpl_vars['themepath'])."/javascript/rate.js"), $this);?>

           <input type='hidden' id='shop_id' value=<?php echo $this->_tpl_vars['shop_id']; ?>
>
           <div class="r">
               <div class="rating-wrap">
                    <div class="rating-star">
                      <?php unset($this->_sections['starcount']);
$this->_sections['starcount']['name'] = 'starcount';
$this->_sections['starcount']['loop'] = is_array($_loop=$this->_tpl_vars['dec_rating']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
               </div>
                        <div class="review-count"><?php echo $this->_tpl_vars['ratingCount']; ?>
</div>
                   
                </div>
                   
               
                                    
         