<?php /* Smarty version 2.6.28, created on 2017-10-29 15:28:17
         compiled from admin/shop_header.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'admincategorymenu', 'admin/shop_header.tpl', 2, false),array('function', 'pageaddvar', 'admin/shop_header.tpl', 3, false),array('function', 'modurl', 'admin/shop_header.tpl', 67, false),array('function', 'shopslinks', 'admin/shop_header.tpl', 72, false),array('modifier', 'cleantext', 'admin/shop_header.tpl', 67, false),)), $this); ?>
<?php echo smarty_function_admincategorymenu(array(), $this);?>

<?php echo smarty_function_pageaddvar(array('name' => 'javascript','value' => 'jquery'), $this);?>

<?php echo smarty_function_pageaddvar(array('name' => 'javascript','value' => 'zikula.ui'), $this);?>


<script><?php echo '
    /*
        jQuery(document).ready(function(){
        //alert(\'comes here\');
        jQuery("#advanceview").click(function(){
            var textval = jQuery(this).text();
            if(jQuery(this).text() == \'switch to advance\'){
                setCookie(\'shop_menu\',\'1\',\'1\');
                jQuery(\'#advanceview\').html(\'switch to basic\');
            }
            else{
                setCookie(\'shop_menu\',\'0\',\'1\');
                jQuery(\'#advanceview\').html(\'switch to advance\');
            }
            jQuery("#shop_menu").slideToggle(function(){
                if(jQuery("#func").val()!=\'shopinnerview\'){
                    if(textval==\'switch to basic\'){
                        var url = "index.php?module=zselex&type=admin&func=shopinnerview&shop_id="+jQuery("#shops_id").val();
                        window.location.href=url;
                    }
             
                }
            });
            jQuery("#admCart").slideToggle();
        });
    });
      
      
    function setCookie(c_name,value,exdays){ // set cookie in js
        var exdate=new Date();
        exdate.setDate(exdate.getDate() + exdays);
        var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString());
        document.cookie=c_name + "=" + c_value;
    }
      */
'; ?>
</script>

<input type="hidden" id="func" value="<?php echo $this->_tpl_vars['func']; ?>
">
<input type="hidden" id="shops_id" value="<?php echo $this->_tpl_vars['shop_id']; ?>
">
<div class="z-admin-content z-clearfix">
    <div class="z-admin-content-modtitle">
                <?php if ($this->_tpl_vars['shop']['default_img_frm'] == 'fromgallery'): ?>
            <?php if ($this->_tpl_vars['shop']['image_name'] != ''): ?>
            <div>
                <img src="<?php echo $this->_tpl_vars['baseurl']; ?>
zselexdata/<?php echo $this->_tpl_vars['shop']['shop_id']; ?>
/minisitegallery/thumb/<?php echo $this->_tpl_vars['shop']['image_name']; ?>
" height='50'>
            </div>
            <?php endif; ?> 
        <?php endif; ?>  
        <?php if ($this->_tpl_vars['shop']['default_img_frm'] == 'fromshop'): ?>
            <?php if ($this->_tpl_vars['shop']['image'] != ''): ?>
            <div>
                <img src="<?php echo $this->_tpl_vars['baseurl']; ?>
zselexdata/<?php echo $this->_tpl_vars['shop']['shop_id']; ?>
/minisiteimages/thumb/<?php echo $this->_tpl_vars['shop']['image']; ?>
" height='50'>
            </div>
            <?php endif; ?>
        <?php endif; ?>  
        <h2><a href="<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'admin','func' => 'shopinnerview','shop_id' => $this->_tpl_vars['shop_id']), $this);?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['shop']['shop_name'])) ? $this->_run_mod_handler('cleantext', true, $_tmp) : smarty_modifier_cleantext($_tmp)); ?>
</a></h2>
            </div>
        <div id="shop_menu" >
        <?php echo smarty_function_shopslinks(array('modname' => 'ZSELEX','type' => 'admin'), $this);?>
 
    </div>
    <!--
    <div id="admCart" style="padding-right:150px;cursor:pointer" align="right" onClick='displayBasket(<?php echo $_REQUEST['shop_id']; ?>
)' >
      <h4>cart(<?php echo $this->_tpl_vars['count']; ?>
)</h4>
    </div>
    -->
       
