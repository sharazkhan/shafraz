<?php /* Smarty version 2.6.28, created on 2017-12-10 15:14:43
         compiled from admin/theme_admin_view.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'ajaxheader', 'admin/theme_admin_view.tpl', 1, false),array('function', 'gt', 'admin/theme_admin_view.tpl', 10, false),array('function', 'shopheader', 'admin/theme_admin_view.tpl', 13, false),array('function', 'icon', 'admin/theme_admin_view.tpl', 15, false),array('function', 'pagerabcshoptheme', 'admin/theme_admin_view.tpl', 19, false),array('function', 'modurl', 'admin/theme_admin_view.tpl', 20, false),array('function', 'img', 'admin/theme_admin_view.tpl', 40, false),array('function', 'homepage', 'admin/theme_admin_view.tpl', 56, false),array('function', 'cycle', 'admin/theme_admin_view.tpl', 68, false),array('function', 'previewimage', 'admin/theme_admin_view.tpl', 97, false),array('function', 'pager', 'admin/theme_admin_view.tpl', 114, false),array('function', 'adminfooter', 'admin/theme_admin_view.tpl', 115, false),array('block', 'pageaddvarblock', 'admin/theme_admin_view.tpl', 2, false),array('modifier', 'gt', 'admin/theme_admin_view.tpl', 40, false),array('modifier', 'strstr', 'admin/theme_admin_view.tpl', 62, false),array('modifier', 'strtolower', 'admin/theme_admin_view.tpl', 68, false),array('modifier', 'safetext', 'admin/theme_admin_view.tpl', 78, false),array('modifier', 'default', 'admin/theme_admin_view.tpl', 103, false),)), $this); ?>
<?php echo smarty_function_ajaxheader(array('ui' => true), $this);?>

<?php $this->_tag_stack[] = array('pageaddvarblock', array()); $_block_repeat=true;smarty_block_pageaddvarblock($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
<script type="text/javascript"><?php echo '
    document.observe("dom:loaded", function() {
        Zikula.UI.Tooltips($$(\'.tooltips\'));
    });
'; ?>
</script>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_pageaddvarblock($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php echo smarty_function_gt(array('text' => 'Extension database','assign' => 'extdbtitle'), $this);?>

<?php $this->assign('extdblink', "<strong><a href=\"http://community.zikula.org/module-Extensions.htm\">".($this->_tpl_vars['extdbtitle'])."</a></strong>"); ?>

<?php echo smarty_function_shopheader(array(), $this);?>

<div class="z-admin-content-pagetitle">
    <?php echo smarty_function_icon(array('type' => 'view','size' => 'small'), $this);?>

    <h3><?php echo smarty_function_gt(array('text' => 'Themes list'), $this);?>
</h3>
</div>

<div id="themes-alphafilter" style="padding:0 0 1em;"><strong>[<?php echo smarty_function_pagerabcshoptheme(array('posvar' => 'startlet','forwardvars' => '','shop_id' => $_REQUEST['shop_id']), $this);?>
]</strong></div>
<form class="z-form" id="country_filter" action="<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'admin','func' => 'configureshoptheme','shop_id' => $_REQUEST['shop_id'],'startlet' => $this->_tpl_vars['startlet']), $this);?>
" method="post" enctype="application/x-www-form-urlencoded">
    <fieldset id="zselex_multicategory_filter"<?php if ($this->_tpl_vars['filter_active']): ?> class='filteractive'<?php endif; ?>>
        <?php if ($this->_tpl_vars['filter_active']): ?><?php echo smarty_function_gt(array('text' => 'active','assign' => 'filteractive'), $this);?>
<?php elseif ($this->_tpl_vars['filter_active'] != ''): ?><?php echo smarty_function_gt(array('text' => 'inactive','assign' => 'filteractive'), $this);?>
<?php else: ?><?php echo smarty_function_gt(array('text' => 'All','assign' => 'filteractive'), $this);?>
<?php endif; ?>
       
        <label for="searchtext"><?php echo smarty_function_gt(array('text' => 'No Of Pages'), $this);?>
</label>
        <select name="no_of_pages">
            <option value=''><?php echo smarty_function_gt(array('text' => 'Select'), $this);?>
</option>
           
            <option value='5' <?php if ($this->_tpl_vars['no_of_pages'] == '5'): ?>selected=selected<?php endif; ?>>5</option>
            <option value='10' <?php if ($this->_tpl_vars['no_of_pages'] == '10'): ?>selected=selected<?php endif; ?>>10</option>
            <option value='15' <?php if ($this->_tpl_vars['no_of_pages'] == '15'): ?>selected=selected<?php endif; ?>>15</option>
            <option value='25' <?php if ($this->_tpl_vars['no_of_pages'] == '25'): ?>selected=selected<?php endif; ?>>25</option>
            <option value='50' <?php if ($this->_tpl_vars['no_of_pages'] == '50'): ?>selected=selected<?php endif; ?>>50</option>
         
        </select>
       
        &nbsp;
        &nbsp;&nbsp;
        <span class="z-nowrap z-buttons">
            <input class='z-bt-filter' name="" type="submit" value="<?php echo smarty_function_gt(array('text' => 'Filter'), $this);?>
" />
            <a href="<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'admin','func' => 'configureshoptheme','shop_id' => $_REQUEST['shop_id']), $this);?>
" title="<?php echo smarty_function_gt(array('text' => 'Reset'), $this);?>
"><?php echo smarty_function_img(array('modname' => 'core','src' => "button_cancel.png",'set' => "icons/extrasmall",'alt' => ((is_array($_tmp='Reset')) ? $this->_run_mod_handler('gt', true, $_tmp, $this->_tpl_vars['zikula_view']) : smarty_modifier_gt($_tmp, $this->_tpl_vars['zikula_view'])),'title' => ((is_array($_tmp='Reset')) ? $this->_run_mod_handler('gt', true, $_tmp, $this->_tpl_vars['zikula_view']) : smarty_modifier_gt($_tmp, $this->_tpl_vars['zikula_view']))), $this);?>
 <?php echo smarty_function_gt(array('text' => 'Reset'), $this);?>
</a>
        </span>
    </fieldset>
</form>
<table class="z-datatable">
    <thead>
        <tr>
             <?php if (! $this->_tpl_vars['expired'] && ! $this->_tpl_vars['servicedisable']): ?>
            <th class="z-right"><?php echo smarty_function_gt(array('text' => 'Actions'), $this);?>
</th>
             <?php endif; ?>
            <th><?php echo smarty_function_gt(array('text' => 'Name'), $this);?>
</th>
            <th><?php echo smarty_function_gt(array('text' => 'Description'), $this);?>
</th>
        </tr>
    </thead>
    <tbody>
        <?php $_from = $this->_tpl_vars['themes']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['theme']):
?>
        <?php echo smarty_function_homepage(array('assign' => 'homepageurl'), $this);?>

        <?php if ($this->_tpl_vars['modvars']['ZConfig']['shorturls'] == 1 && $this->_tpl_vars['modvars']['ZConfig']['shorturlsstripentrypoint'] != 1): ?>
        <?php $this->assign('themeurl', ($this->_tpl_vars['homepageurl'])."/".($this->_tpl_vars['theme']['name'])); ?>
        <?php elseif ($this->_tpl_vars['modvars']['ZConfig']['shorturls'] == 1 && $this->_tpl_vars['modvars']['ZConfig']['shorturlsstripentrypoint'] == 1): ?>
        <?php $this->assign('themeurl', ($this->_tpl_vars['homepageurl']).($this->_tpl_vars['theme']['name'])); ?>
        <?php else: ?>
        <?php if (((is_array($_tmp=$this->_tpl_vars['homepageurl'])) ? $this->_run_mod_handler('strstr', true, $_tmp, "?") : strstr($_tmp, "?"))): ?>
        <?php $this->assign('themeurl', ($this->_tpl_vars['homepageurl'])."&theme=".($this->_tpl_vars['theme']['name'])); ?>
        <?php else: ?>
        <?php $this->assign('themeurl', ($this->_tpl_vars['homepageurl'])."?theme=".($this->_tpl_vars['theme']['name'])); ?>
        <?php endif; ?>
        <?php endif; ?>
        <tr class="<?php echo smarty_function_cycle(array('values' => "z-odd,z-even"), $this);?>
<?php if (((is_array($_tmp=$this->_tpl_vars['theme']['name'])) ? $this->_run_mod_handler('strtolower', true, $_tmp) : strtolower($_tmp)) == ((is_array($_tmp=$this->_tpl_vars['currenttheme'])) ? $this->_run_mod_handler('strtolower', true, $_tmp) : strtolower($_tmp))): ?> z-defaulttablerow<?php endif; ?>">
            <?php if (! $this->_tpl_vars['expired'] && ! $this->_tpl_vars['servicedisable']): ?>
            <td class="z-right z-nowrap">
                <?php echo smarty_function_gt(array('text' => 'Preview: %s','tag1' => $this->_tpl_vars['theme']['displayname'],'assign' => 'strPreviewTheme'), $this);?>

                <?php echo smarty_function_gt(array('text' => 'Edit: %s','tag1' => $this->_tpl_vars['theme']['displayname'],'assign' => 'strEditTheme'), $this);?>

                <?php echo smarty_function_gt(array('text' => 'Delete: %s','tag1' => $this->_tpl_vars['theme']['displayname'],'assign' => 'strDeleteTheme'), $this);?>

                <?php echo smarty_function_gt(array('text' => 'Set as default: %s','tag1' => $this->_tpl_vars['theme']['displayname'],'assign' => 'strSetDefaultTheme'), $this);?>

                <?php echo smarty_function_gt(array('text' => 'Credits: %s','tag1' => $this->_tpl_vars['theme']['displayname'],'assign' => 'strCreditsTheme'), $this);?>

            
              
                <a href="<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'user','func' => 'site','id' => $_REQUEST['shop_id'],'theme' => ((is_array($_tmp=$this->_tpl_vars['theme']['displayname'])) ? $this->_run_mod_handler('safetext', true, $_tmp) : smarty_modifier_safetext($_tmp))), $this);?>
" title="<?php echo ((is_array($_tmp=$this->_tpl_vars['theme']['displayname'])) ? $this->_run_mod_handler('safetext', true, $_tmp) : smarty_modifier_safetext($_tmp)); ?>
"><?php echo smarty_function_icon(array('type' => 'preview','size' => 'extrasmall','alt' => ((is_array($_tmp='Preview')) ? $this->_run_mod_handler('gt', true, $_tmp, $this->_tpl_vars['zikula_view']) : smarty_modifier_gt($_tmp, $this->_tpl_vars['zikula_view'])),'title' => $this->_tpl_vars['strPreviewTheme'],'class' => 'tooltips'), $this);?>
</a>
                 <?php if ($this->_tpl_vars['theme']['name'] != $this->_tpl_vars['currenttheme'] && $this->_tpl_vars['theme']['user'] && $this->_tpl_vars['theme']['state'] != 2): ?>
                <a href="<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'admin','func' => 'setasdefaultshoptheme','shop_id' => $_REQUEST['shop_id'],'themename' => $this->_tpl_vars['theme']['name']), $this);?>
"><?php echo smarty_function_icon(array('type' => 'ok','size' => 'extrasmall','alt' => ((is_array($_tmp='Set as default')) ? $this->_run_mod_handler('gt', true, $_tmp, $this->_tpl_vars['zikula_view']) : smarty_modifier_gt($_tmp, $this->_tpl_vars['zikula_view'])),'title' => $this->_tpl_vars['strSetDefaultTheme'],'class' => 'tooltips'), $this);?>
</a>
                <?php endif; ?>
                <a href="<?php echo smarty_function_modurl(array('modname' => 'Theme','type' => 'admin','func' => 'credits','themename' => $this->_tpl_vars['theme']['name']), $this);?>
"><?php echo smarty_function_icon(array('type' => 'info','size' => 'extrasmall','alt' => ((is_array($_tmp='Credits')) ? $this->_run_mod_handler('gt', true, $_tmp, $this->_tpl_vars['zikula_view']) : smarty_modifier_gt($_tmp, $this->_tpl_vars['zikula_view'])),'title' => $this->_tpl_vars['strCreditsTheme'],'class' => 'tooltips'), $this);?>
</a>
           
                </td>
                <?php endif; ?>

            <td>
                <a href="<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'user','func' => 'site','id' => $_REQUEST['shop_id'],'theme' => ((is_array($_tmp=$this->_tpl_vars['theme']['displayname'])) ? $this->_run_mod_handler('safetext', true, $_tmp) : smarty_modifier_safetext($_tmp))), $this);?>
" title="<?php echo ((is_array($_tmp=$this->_tpl_vars['theme']['displayname'])) ? $this->_run_mod_handler('safetext', true, $_tmp) : smarty_modifier_safetext($_tmp)); ?>
">
                  
                    <span title="#title_<?php echo $this->_tpl_vars['theme']['name']; ?>
" class="tooltips marktooltip"><?php echo ((is_array($_tmp=$this->_tpl_vars['theme']['displayname'])) ? $this->_run_mod_handler('safetext', true, $_tmp) : smarty_modifier_safetext($_tmp)); ?>
</span>
                   
                </a>
                <?php if (((is_array($_tmp=$this->_tpl_vars['theme']['name'])) ? $this->_run_mod_handler('strtolower', true, $_tmp) : strtolower($_tmp)) == ((is_array($_tmp=$this->_tpl_vars['currenttheme'])) ? $this->_run_mod_handler('strtolower', true, $_tmp) : strtolower($_tmp))): ?><span title="<?php echo smarty_function_gt(array('text' => 'Default theme'), $this);?>
" class="tooltips z-form-mandatory-flag">*</span><?php endif; ?>
                <div id="title_<?php echo $this->_tpl_vars['theme']['name']; ?>
" class="theme_preview z-center" style="display: none;">
                    <h4><?php echo $this->_tpl_vars['theme']['displayname']; ?>
</h4>
                    <?php if ($this->_tpl_vars['themeinfo']['system'] != 1): ?>
                    <p><?php echo smarty_function_previewimage(array('name' => $this->_tpl_vars['theme']['name']), $this);?>
</p>
                    <?php endif; ?>
                </div>
            </td>
            <td>
              
                <?php echo ((is_array($_tmp=@$this->_tpl_vars['theme']['description'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['theme']['displayname']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['theme']['displayname'])); ?>

            </td>
               
        </tr>
        <?php endforeach; else: ?>
        <tr class="z-datatableempty"><td colspan="3"><?php echo smarty_function_gt(array('text' => "No items found."), $this);?>
</td></tr>
        <?php endif; unset($_from); ?>
    </tbody>
</table>

<em><span class="z-form-mandatory-flag">*</span> = <?php echo smarty_function_gt(array('text' => 'Default theme'), $this);?>
</em>
<?php echo smarty_function_pager(array('rowcount' => $this->_tpl_vars['pager']['numitems'],'limit' => $this->_tpl_vars['pager']['itemsperpage'],'posvar' => 'startnum','maxpages' => 10), $this);?>

<?php echo smarty_function_adminfooter(array(), $this);?>