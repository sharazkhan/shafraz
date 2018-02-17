<?php /* Smarty version 2.6.28, created on 2018-02-03 08:18:11
         compiled from blocks/selection.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'pageaddvar', 'blocks/selection.tpl', 2, false),array('function', 'gt', 'blocks/selection.tpl', 9, false),array('function', 'modurl', 'blocks/selection.tpl', 38, false),array('modifier', 'cleantext', 'blocks/selection.tpl', 33, false),)), $this); ?>

<?php echo smarty_function_pageaddvar(array('name' => 'stylesheet','value' => ($this->_tpl_vars['themepath'])."/style/breadcrum.css?v=1.1"), $this);?>

<section class="search-wrap">
    <div class="container">
        <div class="form-inline clearfix">
            <div class="mobi-controls">
                <div class="region-select inline-select" id='region-div'>
                    <select id="region-combo" class="chosen-select-search form-control">
                        <option value=""><?php echo smarty_function_gt(array('text' => 'search region'), $this);?>
</option>
                        <?php $_from = $this->_tpl_vars['regions']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['region']):
?>
                        <option value="<?php echo $this->_tpl_vars['region']['region_id']; ?>
" <?php if ($_COOKIE['region_cookie'] == $this->_tpl_vars['region']['region_id']): ?> selected="selected" <?php endif; ?>><?php echo $this->_tpl_vars['region']['region_name']; ?>
</option>
                        <?php endforeach; endif; unset($_from); ?>
                    </select>
                </div>
                <div class="city-select inline-select" id="city-div">
                    <select id="city-combo1" class="chosen-select-search form-control">
                        <option value=""><?php echo smarty_function_gt(array('text' => 'search city'), $this);?>
</option>
                        <?php $_from = $this->_tpl_vars['cities']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['city']):
?>
                        <option value="<?php echo $this->_tpl_vars['city']['city_id']; ?>
" <?php if ($_COOKIE['city_cookie'] == $this->_tpl_vars['city']['city_id']): ?> selected="selected" <?php endif; ?>><?php echo $this->_tpl_vars['city']['city_name']; ?>
</option>
                        <?php endforeach; endif; unset($_from); ?>
                    </select>
                </div>
                <div class="category-select inline-select" id="cat-div">
                    <select id="cat-combo" class="chosen-select-search form-control">
                        <option value=""><?php echo smarty_function_gt(array('text' => 'search category'), $this);?>
</option>
                        <?php $_from = $this->_tpl_vars['category']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['cat']):
?>
                        <option value="<?php echo $this->_tpl_vars['cat']['category_id']; ?>
" <?php if ($_COOKIE['category_cookie'] == $this->_tpl_vars['cat']['category_id']): ?> selected="selected" <?php endif; ?>><?php echo $this->_tpl_vars['cat']['category_name']; ?>
</option>
                        <?php endforeach; endif; unset($_from); ?>

                    </select>
                </div>
                            <input id="searchfield" type="text" value="<?php if ($this->_tpl_vars['search_cookie'] != ''): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['search_cookie'])) ? $this->_run_mod_handler('cleantext', true, $_tmp) : smarty_modifier_cleantext($_tmp)); ?>
<?php endif; ?>" placeholder='<?php echo $this->_tpl_vars['search_place_holder']; ?>
'  class="form-control"  onkeyup="searchvalue(this.value)" >
            </div>

            <button class="btn btn-primary search-btn" onclick="document.forms['shopform'].submit();"><?php echo smarty_function_gt(array('text' => 'Show shops'), $this);?>
 <i class="fa fa-angle-double-right"></i></button>
            <span class="btn btn-default reset-btn" onClick='resets();'><i class="fa fa-times"></i><?php echo smarty_function_gt(array('text' => 'reset'), $this);?>
</span>
            <a href="<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'user','func' => 'cart'), $this);?>
" class="desk-cart">
                <span class="btn btn-default shopping-bag"><span class="carts_total" id="carts_total"><?php echo $this->_tpl_vars['cartCount']; ?>
<span class="dkk"> DKK</span></span> <i class="fa fa-shopping-bag"></i></span>
            </a>
        </div>
             <!-- Row -->
             <div id="BreadCrumHead">
                <ul class="search-breadcrumb BrudcomeTree">
                                  </ul>
             </div>
                <!-- End -->
    </div>
</section>

<form id="shopform" name="shopform" action="<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'user','func' => 'shoplists'), $this);?>
" method='post'>
    <input type='hidden' id='default_country_id' name='default_country_id' value=<?php echo $this->_tpl_vars['country_id']; ?>
> 
    <input type='hidden' id='default_country_name' name='default_country_name' value=<?php echo ((is_array($_tmp=$this->_tpl_vars['country_name'])) ? $this->_run_mod_handler('cleantext', true, $_tmp) : smarty_modifier_cleantext($_tmp)); ?>
>   
    <input type='hidden' id='hcountry' name='country_id' value=<?php echo $this->_tpl_vars['country_id']; ?>
>
    <input type='hidden' id='hregion' name='region_id' value="<?php echo ((is_array($_tmp=$this->_tpl_vars['region_cookie'])) ? $this->_run_mod_handler('cleantext', true, $_tmp) : smarty_modifier_cleantext($_tmp)); ?>
">
    <input type='hidden' id='hcity' name='city_id' value="<?php echo ((is_array($_tmp=$this->_tpl_vars['city_cookie'])) ? $this->_run_mod_handler('cleantext', true, $_tmp) : smarty_modifier_cleantext($_tmp)); ?>
">
    <input type='hidden' id='hshop' name='shop_id' value="<?php echo ((is_array($_tmp=$this->_tpl_vars['shop_cookie'])) ? $this->_run_mod_handler('cleantext', true, $_tmp) : smarty_modifier_cleantext($_tmp)); ?>
">
    <input type='hidden' id='harea' name='area_id' value="<?php echo ((is_array($_tmp=$this->_tpl_vars['area_cookie'])) ? $this->_run_mod_handler('cleantext', true, $_tmp) : smarty_modifier_cleantext($_tmp)); ?>
">
    <input type='hidden' id='hcategory' name='category_id' value="<?php echo ((is_array($_tmp=$this->_tpl_vars['category_cookie'])) ? $this->_run_mod_handler('cleantext', true, $_tmp) : smarty_modifier_cleantext($_tmp)); ?>
">
    <input type='hidden' id='hbranch' name='branch_id'  value="<?php echo ((is_array($_tmp=$this->_tpl_vars['branch_cookie'])) ? $this->_run_mod_handler('cleantext', true, $_tmp) : smarty_modifier_cleantext($_tmp)); ?>
">
    <input type='hidden' id='hbranch_name' name='hbranch_name'  value="<?php echo ((is_array($_tmp=$this->_tpl_vars['branchNameCookie'])) ? $this->_run_mod_handler('cleantext', true, $_tmp) : smarty_modifier_cleantext($_tmp)); ?>
">
    <input type='hidden' id='hsearch' name='hsearch' value="<?php if ($this->_tpl_vars['search_cookie'] != ''): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['search_cookie'])) ? $this->_run_mod_handler('cleantext', true, $_tmp) : smarty_modifier_cleantext($_tmp)); ?>
<?php endif; ?>">
        <input type="hidden" id="aff_id" name="aff_id" value='<?php echo $this->_tpl_vars['affiliate_cookie']; ?>
'>
    <input type="hidden" id="aff_name" name="aff_name" value='<?php echo $this->_tpl_vars['affNameCookie']; ?>
'>
</form>

<input type='hidden' id='level'  value=''>
<input type='hidden' id='type'  value=''>
<input type='hidden' id='name'  value=''>

<input type='hidden' id='hcountryname' name='hcountry' value=<?php echo ((is_array($_tmp=$this->_tpl_vars['country_name'])) ? $this->_run_mod_handler('cleantext', true, $_tmp) : smarty_modifier_cleantext($_tmp)); ?>
>
<input type='hidden' id='hregionname' name='hregion' value=''>
<input type='hidden' id='hcity_name' name='hcity' value=''>
<input type='hidden' id='hshop_name' name='hshop' value=''>
<input type='hidden' id='hareaname' name='harea' value=''>
<input type='hidden' id='hcatname' value=<?php echo ((is_array($_tmp=$this->_tpl_vars['categoryName_cookie'])) ? $this->_run_mod_handler('cleantext', true, $_tmp) : smarty_modifier_cleantext($_tmp)); ?>
>
<input type="hidden" id="current_theme" value="<?php echo $this->_tpl_vars['current_theme']; ?>
">
<input type="hidden" id="curr_lang" value="<?php echo $this->_tpl_vars['thislang']; ?>
">


<input type='hidden' id='offset' name='offset' value='0'>
<input type='hidden' id='pageload' name='pageload' value=1>
<input type='hidden' id='curr_func' name='curr_func' value="<?php echo $_REQUEST['func']; ?>
">

<input type='hidden' id='shop_url' name='shop_url' value="<?php if ($_REQUEST['shop_id']): ?><?php echo $this->_tpl_vars['baseurl']; ?>
<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'user','func' => 'shop','shop_id' => $_REQUEST['shop_id']), $this);?>
<?php endif; ?>">
<input type='hidden' id='site_url' name='site_url' value="<?php if ($_REQUEST['shop_id']): ?><?php echo $this->_tpl_vars['baseurl']; ?>
<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'user','func' => 'site','shop_id' => $_REQUEST['shop_id']), $this);?>
<?php endif; ?>">
<input type='hidden' id='pages_url' name='pages_url' value="<?php if ($_REQUEST['shop_id']): ?><?php echo $this->_tpl_vars['baseurl']; ?>
<?php echo smarty_function_modurl(array('modname' => 'ZTEXT','type' => 'user','func' => 'pages','shop_id' => $_REQUEST['shop_id']), $this);?>
<?php endif; ?>">

<script><?php echo '
    jQuery(document).ready(function(){
    resetAutocomplete();
    });
'; ?>
</script>