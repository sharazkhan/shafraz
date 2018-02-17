<?php /* Smarty version 2.6.28, created on 2018-02-17 19:13:12
         compiled from user/pages.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'ajaxheader', 'user/pages.tpl', 1, false),array('function', 'modurl', 'user/pages.tpl', 6, false),array('function', 'gt', 'user/pages.tpl', 7, false),array('function', 'fblikeservice', 'user/pages.tpl', 38, false),array('function', 'fbshare', 'user/pages.tpl', 39, false),array('function', 'pager', 'user/pages.tpl', 45, false),array('modifier', 'cleantext', 'user/pages.tpl', 11, false),array('modifier', 'strip_tags', 'user/pages.tpl', 38, false),)), $this); ?>
 <?php echo smarty_function_ajaxheader(array('imageviewer' => 'true'), $this);?>

<div class="ShopRatingDiv">
<?php $_from = $this->_tpl_vars['pages']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
 <?php if ($this->_tpl_vars['perm']): ?>
<div class="OrageEditSec EditEvent">
    <a href="<?php echo smarty_function_modurl(array('modname' => 'ZTEXT','type' => 'admin','func' => 'pages','shop_id' => $_REQUEST['shop_id'],'text_id' => $this->_tpl_vars['item']['text_id']), $this);?>
">
        <img src="<?php echo $this->_tpl_vars['themepath']; ?>
/images/OrageEdit.png"><?php echo smarty_function_gt(array('text' => 'Edit Page'), $this);?>

    </a>
</div>
 <?php endif; ?>
    <h3><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['headertext'])) ? $this->_run_mod_handler('cleantext', true, $_tmp) : smarty_modifier_cleantext($_tmp)); ?>
</h3>
     <?php echo $this->_tpl_vars['item']['bodytext']; ?>

   
     <?php if ($this->_tpl_vars['item']['extension'] == 'pdf'): ?>
       <?php $this->assign('imageExist', "zselexdata/".($_REQUEST['shop_id'])."/ztext/pdf/medium/".($this->_tpl_vars['item']['image'])); ?>
        <?php if (is_file ( $this->_tpl_vars['imageExist'] )): ?>
        <p>
            <a target="_blank" href="<?php echo smarty_function_modurl(array('modname' => 'ZTEXT','type' => 'user','func' => 'pdfView','id' => $this->_tpl_vars['item']['text_id']), $this);?>
">
        <img  src="<?php echo $this->_tpl_vars['baseurl']; ?>
zselexdata/<?php echo $_REQUEST['shop_id']; ?>
/ztext/pdf/medium/<?php echo $this->_tpl_vars['item']['image']; ?>
">
            </a>
        </p>
       <?php endif; ?>
      <?php else: ?>
        <?php $this->assign('imageExist', "zselexdata/".($_REQUEST['shop_id'])."/ztext/medium/".($this->_tpl_vars['item']['image'])); ?>
        <?php if (is_file ( $this->_tpl_vars['imageExist'] )): ?>
        <p>
             <a id="my<?php echo $this->_tpl_vars['item']['text_id']; ?>
" rel="imageviewer[pageGallery]" title="<?php echo $this->_tpl_vars['item']['headertext']; ?>
" href="<?php echo $this->_tpl_vars['baseurl']; ?>
zselexdata/<?php echo $_REQUEST['shop_id']; ?>
/ztext/fullsize/<?php echo $this->_tpl_vars['item']['image']; ?>
">
        <img  src="<?php echo $this->_tpl_vars['baseurl']; ?>
zselexdata/<?php echo $_REQUEST['shop_id']; ?>
/ztext/medium/<?php echo $this->_tpl_vars['item']['image']; ?>
">
             </a>
        </p>
       <?php endif; ?>
     <?php endif; ?>
     
       <?php $this->assign('imagePath', ($this->_tpl_vars['baseurl'])."zselexdata/".($_REQUEST['shop_id'])."/ztext/medium/".($this->_tpl_vars['item']['image'])); ?>
       <?php echo smarty_function_modurl(array('modname' => 'ZTEXT','type' => 'user','func' => 'page','shop_id' => $_REQUEST['shop_id'],'text_id' => $this->_tpl_vars['item']['text_id'],'assign' => 'url'), $this);?>

       <?php $this->assign('pageUrl', ($this->_tpl_vars['baseurl']).($this->_tpl_vars['url'])); ?>
       
     <div><?php echo smarty_function_fblikeservice(array('action' => 'like','url' => $this->_tpl_vars['pageUrl'],'width' => '500px','height' => '21px','layout' => 'horizontal','shop_id' => $_REQUEST['shop_id'],'addmetatags' => true,'metatitle' => $this->_tpl_vars['item']['headertext'],'metatype' => 'website','metaimage' => $this->_tpl_vars['imagePath'],'description' => ((is_array($_tmp=$this->_tpl_vars['item']['bodytext'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp) : smarty_modifier_strip_tags($_tmp)),'faces' => true), $this);?>
</div>
     <div><?php echo smarty_function_fbshare(array('shop_id' => $_REQUEST['shop_id'],'url' => $this->_tpl_vars['pageUrl']), $this);?>
</div>


<?php endforeach; endif; unset($_from); ?>
</div>
<div align="center" style="float:center">
    <?php echo smarty_function_pager(array('rowcount' => $this->_tpl_vars['total_count'],'limit' => $this->_tpl_vars['itemsperpage'],'posvar' => 'startnum','maxpages' => 5), $this);?>

</div>
<?php if ($this->_tpl_vars['pages']): ?><br><?php endif; ?>

 

