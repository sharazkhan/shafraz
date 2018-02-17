<?php /* Smarty version 2.6.28, created on 2017-10-29 15:00:51
         compiled from blocks/banner/banner1.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'replace', 'blocks/banner/banner1.tpl', 19, false),array('function', 'blockposition', 'blocks/banner/banner1.tpl', 20, false),array('function', 'modurl', 'blocks/banner/banner1.tpl', 27, false),array('function', 'gt', 'blocks/banner/banner1.tpl', 28, false),)), $this); ?>
<script><?php echo '
    jQuery(document).ready(function () {
        jQuery(\'.bxslider-banner\').bxSlider({
            mode: \'fade\',
            pager: false,
            controls: false,
            auto: false
        });
    });
'; ?>
</script>
<?php if (! empty ( $this->_tpl_vars['getBanner']['banner_image'] )): ?>
<?php $this->assign('image', "zselexdata/".($this->_tpl_vars['shop_id'])."/banner/resized/".($this->_tpl_vars['getBanner']['banner_image'])); ?>
<?php if (file_exists ( $this->_tpl_vars['image'] )): ?>
<section class="slider-wrapper">
    <div class="container">
        <div class="banner-slider">
            <ul class="bxslider-banner clearfix">
                <li>
                    <img src="<?php echo $this->_tpl_vars['baseurl']; ?>
zselexdata/<?php echo $this->_tpl_vars['shop_id']; ?>
/banner/resized/<?php echo ((is_array($_tmp=$this->_tpl_vars['getBanner']['banner_image'])) ? $this->_run_mod_handler('replace', true, $_tmp, ' ', '%20') : smarty_modifier_replace($_tmp, ' ', '%20')); ?>
" >
                    <?php echo smarty_function_blockposition(array('name' => 'minisite-announcement','assign' => 'announcementText'), $this);?>

                    <?php if ($this->_tpl_vars['announcementText']): ?><span class="banner-band"><?php echo $this->_tpl_vars['announcementText']; ?>
</span><?php endif; ?>
                </li>

            </ul>
        </div>
        <?php if ($this->_tpl_vars['perm']): ?>
    <a href="<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'admin','func' => 'shopsettings','shop_id' => $this->_tpl_vars['shop_id']), $this);?>
#aBanner" class="edit-link"><i class="fa fa-pencil-square" aria-hidden="true"></i>
        <?php echo smarty_function_gt(array('text' => 'Edit Banner'), $this);?>

    </a>
         <?php endif; ?>
    </div>
</section>
<?php endif; ?>
<?php endif; ?>
