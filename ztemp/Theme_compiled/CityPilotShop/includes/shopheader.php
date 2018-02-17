<?php /* Smarty version 2.6.28, created on 2017-12-10 15:16:07
         compiled from includes/shopheader.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'shopdetails', 'includes/shopheader.tpl', 1, false),array('function', 'modurl', 'includes/shopheader.tpl', 18, false),array('function', 'gt', 'includes/shopheader.tpl', 19, false),array('function', 'shoprating', 'includes/shopheader.tpl', 50, false),array('modifier', 'replace', 'includes/shopheader.tpl', 53, false),)), $this); ?>
<?php echo smarty_function_shopdetails(array('shop_id' => $_REQUEST['shop_id']), $this);?>


 <?php $this->assign('menuActive1', ""); ?>
 <?php $this->assign('menuActive2', ""); ?>
 <?php $this->assign('menuActive3', ""); ?>
 <?php $this->assign('menuActive4', ""); ?>
<?php if ($_REQUEST['func'] == 'site'): ?>
     <?php $this->assign('menuActive1', 'active'); ?>
<?php elseif ($_REQUEST['func'] == 'shop'): ?>     
     <?php $this->assign('menuActive2', 'active'); ?>
<?php elseif ($_REQUEST['func'] == 'pages' || $_REQUEST['func'] == 'page'): ?>     
     <?php $this->assign('menuActive3', 'active'); ?>
<?php elseif ($_REQUEST['func'] == 'findus'): ?>     
     <?php $this->assign('menuActive4', 'active'); ?>
<?php endif; ?>

<?php if ($this->_tpl_vars['perm']): ?>
<a href="<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'admin','func' => 'shopsettings','shop_id' => $_REQUEST['shop_id']), $this);?>
#aInformation" class="edit-link"><i class="fa fa-pencil-square" aria-hidden="true"></i>
    <?php echo smarty_function_gt(array('text' => 'Edit Content'), $this);?>

</a>
<?php endif; ?>
<nav class="navbar shop-navigation">
    <div class="navbar-header pull-left">
        <button type="button" class="navbar-toggle collapsed shop-nav-btn" data-toggle="collapse" data-target="#shop-navigation" aria-expanded="false">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
    </div>
    <div class="collapse navbar-collapse shop-nav-links pull-left" id="shop-navigation">
        <ul class="nav navbar-nav">
            <li class="<?php echo $this->_tpl_vars['menuActive1']; ?>
"><a href="<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'user','func' => 'site','id' => $_REQUEST['shop_id']), $this);?>
"><?php echo smarty_function_gt(array('text' => 'Home'), $this);?>
</a></li>
            <li class="<?php echo $this->_tpl_vars['menuActive2']; ?>
"><a href="<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'user','func' => 'shop','shop_id' => $_REQUEST['shop_id']), $this);?>
"><?php echo smarty_function_gt(array('text' => 'Products'), $this);?>
</a></li>
            <li class="<?php echo $this->_tpl_vars['menuActive3']; ?>
"><a href="<?php echo smarty_function_modurl(array('modname' => 'ZTEXT','type' => 'user','func' => 'pages','shop_id' => $_REQUEST['shop_id']), $this);?>
"><?php echo smarty_function_gt(array('text' => 'Pages'), $this);?>
</a></li>
            <li class="<?php echo $this->_tpl_vars['menuActive4']; ?>
"><a href="<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'user','func' => 'findus','shop_id' => $_REQUEST['shop_id']), $this);?>
"><?php echo smarty_function_gt(array('text' => 'Find Us'), $this);?>
</a></li>
        </ul>
    </div>
    <div class="shop-nav-right pull-right clearfix">
                        <div class="ajax rating" title="<?php echo smarty_function_gt(array('text' => 'You can rate shop'), $this);?>
">
       
         <?php echo smarty_function_shoprating(array('shop_id' => $_REQUEST['shop_id'],'user_id' => $this->_tpl_vars['uid']), $this);?>

        </div>
        <?php if ($this->_tpl_vars['aff_id'] > 0): ?>
            <?php $this->assign('imagename', ((is_array($_tmp=$this->_tpl_vars['aff_image'])) ? $this->_run_mod_handler('replace', true, $_tmp, ' ', '%20') : smarty_modifier_replace($_tmp, ' ', '%20'))); ?>
            <?php $this->assign('image', "modules/ZSELEX/images/affiliates/".($this->_tpl_vars['imagename'])); ?>
            <?php if (is_file ( $this->_tpl_vars['image'] )): ?>
                <div class="shop-icon">
                    <img src="<?php echo $this->_tpl_vars['baseurl']; ?>
/modules/ZSELEX/images/affiliates/<?php echo $this->_tpl_vars['aff_image']; ?>
" alt="" width="39" height="40">
                </div>
            <?php endif; ?>
        <?php endif; ?>

    </div>
</nav>