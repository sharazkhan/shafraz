<?php /* Smarty version 2.6.28, created on 2017-10-29 15:28:34
         compiled from ajax/loadimages.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'replace', 'ajax/loadimages.tpl', 3, false),)), $this); ?>
  <ul class="ImagePrivew">
            <?php $_from = $this->_tpl_vars['minisite_images']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['index'] => $this->_tpl_vars['item']):
?>
            <li onClick="editImage(<?php echo $this->_tpl_vars['item']['file_id']; ?>
)" style="background: url(<?php echo $this->_tpl_vars['baseurl']; ?>
zselexdata/<?php echo $this->_tpl_vars['shop_id']; ?>
/minisiteimages/thumb/<?php echo ((is_array($_tmp=$this->_tpl_vars['item']['name'])) ? $this->_run_mod_handler('replace', true, $_tmp, ' ', '%20') : smarty_modifier_replace($_tmp, ' ', '%20')); ?>
) no-repeat center center;cursor:pointer" >
                <a id="my<?php echo $this->_tpl_vars['item']['file_id']; ?>
" rel="imageviewer[minisiteimageGallery]" title="<?php echo $this->_tpl_vars['item']['filedescription']; ?>
" href="<?php echo $this->_tpl_vars['baseurl']; ?>
zselexdata/<?php echo $this->_tpl_vars['shop_id']; ?>
/minisiteimages/fullsize/<?php echo $this->_tpl_vars['item']['name']; ?>
" >

                </a>
            </li>
            <?php endforeach; endif; unset($_from); ?>    
  </ul>