<?php /* Smarty version 2.6.28, created on 2018-02-17 19:13:53
         compiled from admin/viewshop.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'adminheader', 'admin/viewshop.tpl', 3, false),array('function', 'pageaddvar', 'admin/viewshop.tpl', 4, false),array('function', 'gt', 'admin/viewshop.tpl', 78, false),array('function', 'modurl', 'admin/viewshop.tpl', 83, false),array('function', 'icon', 'admin/viewshop.tpl', 89, false),array('function', 'img', 'admin/viewshop.tpl', 134, false),array('function', 'html_options', 'admin/viewshop.tpl', 231, false),array('function', 'cycle', 'admin/viewshop.tpl', 280, false),array('function', 'pager', 'admin/viewshop.tpl', 421, false),array('function', 'adminfooter', 'admin/viewshop.tpl', 422, false),array('block', 'securityutil_checkpermission_block', 'admin/viewshop.tpl', 76, false),array('modifier', 'gt', 'admin/viewshop.tpl', 134, false),array('modifier', 'upper', 'admin/viewshop.tpl', 146, false),array('modifier', 'safetext', 'admin/viewshop.tpl', 247, false),array('modifier', 'stripslashes', 'admin/viewshop.tpl', 290, false),array('modifier', 'ucfirst', 'admin/viewshop.tpl', 313, false),array('insert', 'csrftoken', 'admin/viewshop.tpl', 240, false),)), $this); ?>

<?php echo smarty_function_adminheader(array(), $this);?>

<?php echo smarty_function_pageaddvar(array('name' => 'javascript','value' => 'modules/ZSELEX/javascript/zselex_admin.js'), $this);?>

<?php echo smarty_function_pageaddvar(array('name' => 'javascript','value' => 'jquery'), $this);?>

<?php echo smarty_function_pageaddvar(array('name' => 'javascript','value' => 'modules/ZSELEX/javascript/jquery.jscroll.min.js'), $this);?>

<?php echo smarty_function_pageaddvar(array('name' => 'javascript','value' => 'modules/ZSELEX/javascript/jquery.jscroll.js'), $this);?>

<link href="modules/ZSELEX/style/combo/sexy-combo.css" rel="stylesheet" type="text/css" />
<link href="modules/ZSELEX/style/combo/sexy/sexy.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="modules/ZSELEX/javascript/combo/jquery.sexy-combo.js"><?php echo ''; ?>
</script>

             <script type="text/javascript" ><?php echo '
                     jQuery(function () {
                     jQuery("#country").ZselexCombo({
                        emptyText: Zikula.__("Select Country...")
                        //autoFill: true
                        //triggerSelected: true
                        });
                        
                     jQuery("#region").ZselexCombo({
                        emptyText: Zikula.__("Select Region...")
                        //autoFill: true
                        //triggerSelected: true
                        });
                        
                     jQuery("#city").ZselexCombo({
                        emptyText: Zikula.__("Select City...")
                        //autoFill: true
                        //triggerSelected: true
                        });
                        
                     jQuery("#area").ZselexCombo({
                        emptyText: Zikula.__("Select Area...")
                        //autoFill: true
                        //triggerSelected: true
                        });
                        
                      jQuery("#category").ZselexCombo({
                        emptyText: Zikula.__("Select Category...")
                        //autoFill: true
                        //triggerSelected: true
                        });
                        
                      jQuery("#branch").ZselexCombo({
                        emptyText: Zikula.__("Select Branch...")
                        //autoFill: true
                        //triggerSelected: true
                        });
                      
                      jQuery("#affiliate").ZselexCombo({
                        emptyText: Zikula.__("Select Affiliate...")
                        //autoFill: true
                        //triggerSelected: true
                        });
                        
                       jQuery("#bundle").ZselexCombo({
                        emptyText: Zikula.__("Select Bundle...")
                        //autoFill: true
                        //triggerSelected: true
                        });

                    }); 

                '; ?>
</script>

<style><?php echo '
#ajax-container input[type="text"],#ajax-container textarea {
padding: 0.09em;
}

#ajax-container ul {
  margin:0px;
}
'; ?>
</style>
<link rel="stylesheet" type="text/css" href="<?php echo $this->_tpl_vars['stylepath']; ?>
/selectionbox.css"/>
 <?php $this->_tag_stack[] = array('securityutil_checkpermission_block', array('component' => 'ZSELEX::','instance' => '::','level' => 'ACCESS_ADMIN')); $_block_repeat=true;smarty_block_securityutil_checkpermission_block($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
<div class="z-admin-content-pagetitle">
    <a href="index.php?module=zselex&amp;type=admin&amp;func=createshop" class="z-iconlink z-icon-es-add"><?php echo smarty_function_gt(array('text' => 'Create Shop'), $this);?>
</a>
</div>
 <?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_securityutil_checkpermission_block($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
  <!--
          <div>
            <a  id="defwindowajax1" href="<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'info','func' => 'testPage'), $this);?>
" title="Information Link">
                <img  src="<?php echo $this->_tpl_vars['baseurl']; ?>
images/icons/small/info.png"></a>
          </div>
  -->
       
<div class="z-admin-content-pagetitle">
    <?php echo smarty_function_icon(array('type' => 'view','size' => 'small'), $this);?>

    <h3><?php echo smarty_function_gt(array('text' => 'New shops list'), $this);?>
</h3>
</div>

<form class="z-form" id="shop_filter" action="<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'admin','func' => 'viewshop'), $this);?>
" method="post" enctype="application/x-www-form-urlencoded">
    <fieldset id="zselex_multicategory_filter"<?php if ($this->_tpl_vars['filter_active']): ?> class='filteractive'<?php endif; ?>>
        <?php if ($this->_tpl_vars['filter_active']): ?><?php echo smarty_function_gt(array('text' => 'active','assign' => 'filteractive'), $this);?>
<?php elseif ($this->_tpl_vars['filter_active'] != ''): ?><?php echo smarty_function_gt(array('text' => 'inactive','assign' => 'filteractive'), $this);?>
<?php else: ?><?php echo smarty_function_gt(array('text' => 'All','assign' => 'filteractive'), $this);?>
<?php endif; ?>
        <legend><?php echo smarty_function_gt(array('text' => 'Filter %1$s, %2$s shop listed','plural' => 'Filter %1$s, %2$s shops listed','count' => $this->_tpl_vars['total_shops'],'tag1' => $this->_tpl_vars['filteractive'],'tag2' => $this->_tpl_vars['total_shops']), $this);?>
</legend>
        <input type="hidden" name="startnum" value="<?php echo $this->_tpl_vars['startnum']; ?>
" />
        <input type="hidden" name="order" value="<?php echo $this->_tpl_vars['order']; ?>
" />
        <input type="hidden" name="sdir" value="<?php echo $this->_tpl_vars['sdir']; ?>
" />
        
       <!-- <input type="hidden" name="sql" value="<?php echo $this->_tpl_vars['sql']; ?>
" />-->
       <div style="float:left; padding-left: 10px">       
		<div style="float:left; padding-left: 10px">
        	<label for="searchtext"><?php echo smarty_function_gt(array('text' => 'Shop Name'), $this);?>
</label>
       		<div><input type="text" name="searchtext" value="<?php echo $this->_tpl_vars['searchtext']; ?>
" /></div>
        </div>
        
        <div style="float:left; padding-left: 10px">
	        <label for="address"><?php echo smarty_function_gt(array('text' => 'Address'), $this);?>
</label>
    	    <div><textarea name="address"><?php echo $this->_tpl_vars['address']; ?>
</textarea></div>
        </div>

	<div style="float:left; padding-left: 10px">
	        <label for="telephone"><?php echo smarty_function_gt(array('text' => 'Telephone'), $this);?>
</label>
    	    <div><input type="text" name="telephone" value="<?php echo $this->_tpl_vars['telephone']; ?>
" /></div>
	</div>
        
	<div style="float:left; padding-left: 10px">
	        <label for="email"><?php echo smarty_function_gt(array('text' => 'Email'), $this);?>
</label>
    	    <div><input type="text" name="email" value="<?php echo $this->_tpl_vars['email']; ?>
" /></div>
	</div>
       
        <?php if ($this->_tpl_vars['admin']): ?>
		<div style="float:left; padding-left: 10px">
	        <label for="email"><?php echo smarty_function_gt(array('text' => 'Owner'), $this);?>
</label>
    	    <div><input type="text" name="owner" value="<?php echo $this->_tpl_vars['owner']; ?>
" /></div>
		</div>
        <?php endif; ?>
        
		<div style="float:right; padding-left: 100px">
        <span class="z-nowrap z-buttons">
        
            <input class='z-bt-filter' name="submit" type="submit" value="<?php echo smarty_function_gt(array('text' => 'Filter'), $this);?>
" />
            <a href="<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'admin','func' => 'viewshop','clear' => 1), $this);?>
" title="<?php echo smarty_function_gt(array('text' => 'Clear'), $this);?>
"><?php echo smarty_function_img(array('modname' => 'core','src' => "button_cancel.png",'set' => "icons/extrasmall",'alt' => ((is_array($_tmp='Clear')) ? $this->_run_mod_handler('gt', true, $_tmp, $this->_tpl_vars['zikula_view']) : smarty_modifier_gt($_tmp, $this->_tpl_vars['zikula_view'])),'title' => ((is_array($_tmp='Clear')) ? $this->_run_mod_handler('gt', true, $_tmp, $this->_tpl_vars['zikula_view']) : smarty_modifier_gt($_tmp, $this->_tpl_vars['zikula_view']))), $this);?>
 <?php echo smarty_function_gt(array('text' => 'Clear'), $this);?>
</a>
          
        </span>
		</div>

</div>
<div style="float:left; padding-left: 10px">        
           <div id="ajax-container" style="float:left; padding-left: 10px">
             <label for="country"><?php echo smarty_function_gt(array('text' => 'Country'), $this);?>
</label>
              <select id="country" name="country" size="1">
              <option value=''><?php echo smarty_function_gt(array('text' => 'Select Country...'), $this);?>
</option>
                  <?php $_from = $this->_tpl_vars['countries']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['country']):
?>
                  <option value="<?php echo $this->_tpl_vars['country']['country_name']; ?>
"  <?php if ($this->_tpl_vars['countryname'] == $this->_tpl_vars['country']['country_name']): ?> selected='selected' <?php endif; ?>><?php echo ((is_array($_tmp=$this->_tpl_vars['country']['country_name'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</option>
                  <?php endforeach; endif; unset($_from); ?>
              </select>
          </div>
      
        <div id="ajax-container" class="regions" style="float:left; padding-left: 10px">
              <label for="region"><?php echo smarty_function_gt(array('text' => 'Region'), $this);?>
</label>
             <select id="region" name="region" size="1">
             <option value=''><?php echo smarty_function_gt(array('text' => 'Select Region...'), $this);?>
</option>
                 <?php $_from = $this->_tpl_vars['regions']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['region']):
?>
                 <option value="<?php echo $this->_tpl_vars['region']['region_name']; ?>
"  <?php if ($this->_tpl_vars['regionname'] == $this->_tpl_vars['region']['region_name']): ?> selected='selected' <?php endif; ?>><?php echo ((is_array($_tmp=$this->_tpl_vars['region']['region_name'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</option>
                 <?php endforeach; endif; unset($_from); ?>
             </select>
         </div>
         <div id="ajax-container" class="city"  style="float:left; padding-left: 10px">
              <label for="city"><?php echo smarty_function_gt(array('text' => 'City'), $this);?>
</label>
                    <select id="city" name="city" size="1">
                    <option value=''><?php echo smarty_function_gt(array('text' => 'Select City...'), $this);?>
</option>
                        <?php $_from = $this->_tpl_vars['cities']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['city']):
?>
                        <option value="<?php echo $this->_tpl_vars['city']['city_name']; ?>
"  <?php if ($this->_tpl_vars['city_name'] == $this->_tpl_vars['city']['city_name']): ?> selected='selected' <?php endif; ?>><?php echo ((is_array($_tmp=$this->_tpl_vars['city']['city_name'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</option>
                        <?php endforeach; endif; unset($_from); ?>
                    </select>
         </div>
        <div id="ajax-container" class="area" style="float:left; padding-left: 10px">
            <label for="area"><?php echo smarty_function_gt(array('text' => 'Area'), $this);?>
</label>
            <select id="area" name="area" size="1">
            <option value=''><?php echo smarty_function_gt(array('text' => 'Select Area...'), $this);?>
</option>
               <?php $_from = $this->_tpl_vars['areas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['area']):
?>
               <option value="<?php echo $this->_tpl_vars['area']['area_name']; ?>
"  <?php if ($this->_tpl_vars['areaname'] == $this->_tpl_vars['area']['area_name']): ?> selected='selected' <?php endif; ?>><?php echo ((is_array($_tmp=$this->_tpl_vars['area']['area_name'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</option>
               <?php endforeach; endif; unset($_from); ?>
            </select>
        </div>
            
        <div id="ajax-container" style="float:left; padding-left: 10px">
             <label for="category"><?php echo smarty_function_gt(array('text' => 'Category'), $this);?>
</label>
                    <select id="category" name="category" size="1">
                    <option value=''><?php echo smarty_function_gt(array('text' => 'Select Category...'), $this);?>
</option>
                        <?php $_from = $this->_tpl_vars['categories']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['category']):
?>
                        <option value="<?php echo $this->_tpl_vars['category']['category_id']; ?>
"  <?php if ($this->_tpl_vars['category_name'] == $this->_tpl_vars['category']['category_id']): ?> selected='selected' <?php endif; ?>><?php echo ((is_array($_tmp=$this->_tpl_vars['category']['category_name'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</option>
                        <?php endforeach; endif; unset($_from); ?>
                    </select>
        </div>
                    
        <div id="ajax-container" style="float:left; padding-left: 10px">
             <label for="branch"><?php echo smarty_function_gt(array('text' => 'Branch'), $this);?>
</label>
                    <select id="branch" name="branch" size="1">
                    <option value=''><?php echo smarty_function_gt(array('text' => 'Select Branch...'), $this);?>
</option>
                        <?php $_from = $this->_tpl_vars['branches']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['branch']):
?>
                        <option value="<?php echo $this->_tpl_vars['branch']['branch_id']; ?>
"  <?php if ($this->_tpl_vars['branchname'] == $this->_tpl_vars['branch']['branch_id']): ?> selected='selected' <?php endif; ?>><?php echo ((is_array($_tmp=$this->_tpl_vars['branch']['branch_name'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</option>
                        <?php endforeach; endif; unset($_from); ?>
                    </select>
         </div>
         <div id="ajax-container" style="float:left; padding-left: 10px">
             <label for="affiliate"><?php echo smarty_function_gt(array('text' => 'Affiliate'), $this);?>
</label>
                    <select id="affiliate" name="affiliate" size="1">
                   <option value='0'><?php echo smarty_function_gt(array('text' => 'Select Affiliate...'), $this);?>
</option>
                    <?php $_from = $this->_tpl_vars['affiliates']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['affiliate']):
?>
                     <option value="<?php echo $this->_tpl_vars['affiliate']['aff_id']; ?>
" <?php if ($this->_tpl_vars['shop_affiliate'] == $this->_tpl_vars['affiliate']['aff_id']): ?> selected='selected' <?php endif; ?>><?php echo $this->_tpl_vars['affiliate']['aff_name']; ?>
</option>
                    <?php endforeach; endif; unset($_from); ?>
                    </select>
         </div>
        <div id="ajax-container" style="float:left; padding-left: 10px">
             <label for="bundle"><?php echo smarty_function_gt(array('text' => 'Bundles'), $this);?>
</label>
                    <select id="bundle" name="bundle" size="1">
                   <option value='0'><?php echo smarty_function_gt(array('text' => 'Select Bundle...'), $this);?>
</option>
                    <?php $_from = $this->_tpl_vars['bundles']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['bundle']):
?>
                     <option value="<?php echo $this->_tpl_vars['bundle']['bundle_id']; ?>
" <?php if ($this->_tpl_vars['shop_bundle'] == $this->_tpl_vars['bundle']['bundle_id']): ?> selected='selected' <?php endif; ?>><?php echo $this->_tpl_vars['bundle']['bundle_name']; ?>
</option>
                    <?php endforeach; endif; unset($_from); ?>
                    </select>
         </div>
       
       <div style="float:left; padding-left: 10px">
        <label for="itemsperpage"><?php echo smarty_function_gt(array('text' => 'Show'), $this);?>
</label>
        <div>
         <select id="itemsperpage" name="itemsperpage" size="1">
                   <option value='20' <?php if ($this->_tpl_vars['itemsperpage'] == '20'): ?> selected='selected' <?php endif; ?>>20</option>
                   <option value='50' <?php if ($this->_tpl_vars['itemsperpage'] == '50'): ?> selected='selected' <?php endif; ?>>50</option>
                   <option value='100' <?php if ($this->_tpl_vars['itemsperpage'] == '100'): ?> selected='selected' <?php endif; ?>>100</option>
                   <option value='200' <?php if ($this->_tpl_vars['itemsperpage'] == '200'): ?> selected='selected' <?php endif; ?>>200</option>
                    
        </select>
       </div>
     </div>
     <div style="float:left; padding-left: 10px">
        <label for="status"><?php echo smarty_function_gt(array('text' => 'Status'), $this);?>
</label>
        <div><?php echo smarty_function_html_options(array('name' => 'status','id' => 'status','options' => $this->_tpl_vars['itemstatus'],'selected' => $this->_tpl_vars['status']), $this);?>
</div>
     </div>
</div>
        
    </fieldset>
</form>
          
<form class="z-form" id="zselex_bulkaction_form" action="<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'admin','func' => 'viewshop'), $this);?>
" method="post">
    <div style="overflow:auto;">
        <input type="hidden" name="csrftoken" value="<?php require_once(SMARTY_CORE_DIR . 'core.run_insert_handler.php');
echo smarty_core_run_insert_handler(array('args' => array('name' => 'csrftoken')), $this); ?>
" />
       <table id="zselex_admintable" class="z-datatable">
            <thead>
                <tr>
                    <!--  <th></th> -->
                    <th>#<input type="checkbox" id="select_all"></th>
                    <th><?php echo smarty_function_gt(array('text' => 'Actions'), $this);?>
</th>
                    <th><a class='<?php echo $this->_tpl_vars['sort']['class']['shop_id']; ?>
' href='<?php echo ((is_array($_tmp=$this->_tpl_vars['sort']['url']['shop_id'])) ? $this->_run_mod_handler('safetext', true, $_tmp) : smarty_modifier_safetext($_tmp)); ?>
'><?php echo smarty_function_gt(array('text' => 'ID'), $this);?>
</a></th>
                    <th><a class='<?php echo $this->_tpl_vars['sort']['class']['shop_name']; ?>
' href='<?php echo ((is_array($_tmp=$this->_tpl_vars['sort']['url']['shop_name'])) ? $this->_run_mod_handler('safetext', true, $_tmp) : smarty_modifier_safetext($_tmp)); ?>
'><?php echo smarty_function_gt(array('text' => 'Shop'), $this);?>
</a></th>
                    <th><?php echo smarty_function_gt(array('text' => 'Title'), $this);?>
</th>
                    <th><?php echo smarty_function_gt(array('text' => 'Bundle'), $this);?>
</th>
                    <th><a class='<?php echo $this->_tpl_vars['sort']['class']['address']; ?>
' href='<?php echo ((is_array($_tmp=$this->_tpl_vars['sort']['url']['address'])) ? $this->_run_mod_handler('safetext', true, $_tmp) : smarty_modifier_safetext($_tmp)); ?>
'><?php echo smarty_function_gt(array('text' => 'Address'), $this);?>
</a></th>
                    <th><a class='<?php echo $this->_tpl_vars['sort']['class']['telephone']; ?>
' href='<?php echo ((is_array($_tmp=$this->_tpl_vars['sort']['url']['telephone'])) ? $this->_run_mod_handler('safetext', true, $_tmp) : smarty_modifier_safetext($_tmp)); ?>
'><?php echo smarty_function_gt(array('text' => 'Telephone'), $this);?>
</a></th>
                   
                    <th><a class='<?php echo $this->_tpl_vars['sort']['class']['email']; ?>
' href='<?php echo ((is_array($_tmp=$this->_tpl_vars['sort']['url']['email'])) ? $this->_run_mod_handler('safetext', true, $_tmp) : smarty_modifier_safetext($_tmp)); ?>
'><?php echo smarty_function_gt(array('text' => 'Email'), $this);?>
</a></th>
                    <th><a class='<?php echo $this->_tpl_vars['sort']['class']['owner']; ?>
' href='<?php echo ((is_array($_tmp=$this->_tpl_vars['sort']['url']['uname'])) ? $this->_run_mod_handler('safetext', true, $_tmp) : smarty_modifier_safetext($_tmp)); ?>
'><?php echo smarty_function_gt(array('text' => 'Owner'), $this);?>
</a></th>
                    <th><?php echo smarty_function_gt(array('text' => 'Admin'), $this);?>
</th>
                    <th><?php echo smarty_function_gt(array('text' => 'Affiliate'), $this);?>
</th>
                    <th><?php echo smarty_function_gt(array('text' => 'Shop Type'), $this);?>
</th>
                     <th><?php echo smarty_function_gt(array('text' => 'Fax'), $this);?>
</th>
                    <th><a class='<?php echo $this->_tpl_vars['sort']['class']['country_name']; ?>
' href='<?php echo ((is_array($_tmp=$this->_tpl_vars['sort']['url']['country_name'])) ? $this->_run_mod_handler('safetext', true, $_tmp) : smarty_modifier_safetext($_tmp)); ?>
'><?php echo smarty_function_gt(array('text' => 'Country'), $this);?>
</a></th>
                    <th><a class='<?php echo $this->_tpl_vars['sort']['class']['region_name']; ?>
' href='<?php echo ((is_array($_tmp=$this->_tpl_vars['sort']['url']['region_name'])) ? $this->_run_mod_handler('safetext', true, $_tmp) : smarty_modifier_safetext($_tmp)); ?>
'><?php echo smarty_function_gt(array('text' => 'Region'), $this);?>
</a></th>
                    <th><a class='<?php echo $this->_tpl_vars['sort']['class']['cityName']; ?>
' href='<?php echo ((is_array($_tmp=$this->_tpl_vars['sort']['url']['cityName'])) ? $this->_run_mod_handler('safetext', true, $_tmp) : smarty_modifier_safetext($_tmp)); ?>
'><?php echo smarty_function_gt(array('text' => 'City'), $this);?>
</a></th>
                    <th><a class='<?php echo $this->_tpl_vars['sort']['class']['area_name']; ?>
' href='<?php echo ((is_array($_tmp=$this->_tpl_vars['sort']['url']['area_name'])) ? $this->_run_mod_handler('safetext', true, $_tmp) : smarty_modifier_safetext($_tmp)); ?>
'><?php echo smarty_function_gt(array('text' => 'Area'), $this);?>
</a></th>
                                       <th><?php echo smarty_function_gt(array('text' => 'Category'), $this);?>
</th>
                                       <th><?php echo smarty_function_gt(array('text' => 'Branch'), $this);?>
</th>
                    <th><?php echo smarty_function_gt(array('text' => 'Description'), $this);?>
</th>
                  
                    <th><a class='<?php echo $this->_tpl_vars['sort']['class']['status']; ?>
' href='<?php echo ((is_array($_tmp=$this->_tpl_vars['sort']['url']['status'])) ? $this->_run_mod_handler('safetext', true, $_tmp) : smarty_modifier_safetext($_tmp)); ?>
'><?php echo smarty_function_gt(array('text' => 'Status'), $this);?>
</th>
                                      <th><a class='<?php echo $this->_tpl_vars['sort']['class']['cr_date']; ?>
' href='<?php echo ((is_array($_tmp=$this->_tpl_vars['sort']['url']['cr_date'])) ? $this->_run_mod_handler('safetext', true, $_tmp) : smarty_modifier_safetext($_tmp)); ?>
'><?php echo smarty_function_gt(array('text' => 'Created'), $this);?>
</a></th>
                                        <th><a class='<?php echo $this->_tpl_vars['sort']['class']['lu_date']; ?>
' href='<?php echo ((is_array($_tmp=$this->_tpl_vars['sort']['url']['lu_date'])) ? $this->_run_mod_handler('safetext', true, $_tmp) : smarty_modifier_safetext($_tmp)); ?>
'><?php echo smarty_function_gt(array('text' => 'Updated'), $this);?>
</a></th>
                   
                </tr>
            </thead>
            <tbody>
                <?php $_from = $this->_tpl_vars['shopsitems']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['shopitem']):
?>
                <tr class="<?php echo smarty_function_cycle(array('values' => 'z-odd,z-even'), $this);?>
">
                    <!--  <td><input type="checkbox" name="zselex_selected_shops[]" value="<?php echo $this->_tpl_vars['shopitem']['shop_id']; ?>
" class="zselex_checkbox" /></td> -->
                    <td><input type="checkbox" class="shop_ids shop_ids1" name="shop_ids[]" value="<?php echo $this->_tpl_vars['shopitem']['shop_id']; ?>
"></td>
                    <td>
                        <?php $this->assign('options', $this->_tpl_vars['shopitem']['options']); ?>
                        <?php unset($this->_sections['options']);
$this->_sections['options']['name'] = 'options';
$this->_sections['options']['loop'] = is_array($_loop=$this->_tpl_vars['options']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['options']['show'] = true;
$this->_sections['options']['max'] = $this->_sections['options']['loop'];
$this->_sections['options']['step'] = 1;
$this->_sections['options']['start'] = $this->_sections['options']['step'] > 0 ? 0 : $this->_sections['options']['loop']-1;
if ($this->_sections['options']['show']) {
    $this->_sections['options']['total'] = $this->_sections['options']['loop'];
    if ($this->_sections['options']['total'] == 0)
        $this->_sections['options']['show'] = false;
} else
    $this->_sections['options']['total'] = 0;
if ($this->_sections['options']['show']):

            for ($this->_sections['options']['index'] = $this->_sections['options']['start'], $this->_sections['options']['iteration'] = 1;
                 $this->_sections['options']['iteration'] <= $this->_sections['options']['total'];
                 $this->_sections['options']['index'] += $this->_sections['options']['step'], $this->_sections['options']['iteration']++):
$this->_sections['options']['rownum'] = $this->_sections['options']['iteration'];
$this->_sections['options']['index_prev'] = $this->_sections['options']['index'] - $this->_sections['options']['step'];
$this->_sections['options']['index_next'] = $this->_sections['options']['index'] + $this->_sections['options']['step'];
$this->_sections['options']['first']      = ($this->_sections['options']['iteration'] == 1);
$this->_sections['options']['last']       = ($this->_sections['options']['iteration'] == $this->_sections['options']['total']);
?>
                        <a href="<?php echo ((is_array($_tmp=$this->_tpl_vars['options'][$this->_sections['options']['index']]['url'])) ? $this->_run_mod_handler('safetext', true, $_tmp) : smarty_modifier_safetext($_tmp)); ?>
"><?php echo smarty_function_img(array('modname' => 'core','set' => 'icons/extrasmall','src' => $this->_tpl_vars['options'][$this->_sections['options']['index']]['image'],'title' => $this->_tpl_vars['options'][$this->_sections['options']['index']]['title'],'alt' => $this->_tpl_vars['options'][$this->_sections['options']['index']]['title'],'class' => 'tooltips'), $this);?>
</a>
                        <?php endfor; endif; ?>
                    </td>
                    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['shopitem']['shop_id'])) ? $this->_run_mod_handler('safetext', true, $_tmp) : smarty_modifier_safetext($_tmp)); ?>
</td>
                    <td><a href='index.php?module=zselex&type=admin&func=shopinnerview&shop_id=<?php echo $this->_tpl_vars['shopitem']['shop_id']; ?>
'><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['shopitem']['shop_name'])) ? $this->_run_mod_handler('safetext', true, $_tmp) : smarty_modifier_safetext($_tmp)))) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)); ?>
</a>
                    </td>
                    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['shopitem']['urltitle'])) ? $this->_run_mod_handler('safetext', true, $_tmp) : smarty_modifier_safetext($_tmp)); ?>
</td>
                    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['shopitem']['bundle_name'])) ? $this->_run_mod_handler('safetext', true, $_tmp) : smarty_modifier_safetext($_tmp)); ?>
</td>
                    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['shopitem']['address'])) ? $this->_run_mod_handler('safetext', true, $_tmp) : smarty_modifier_safetext($_tmp)); ?>
</td>
                    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['shopitem']['telephone'])) ? $this->_run_mod_handler('safetext', true, $_tmp) : smarty_modifier_safetext($_tmp)); ?>
</td>
                   
                    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['shopitem']['email'])) ? $this->_run_mod_handler('safetext', true, $_tmp) : smarty_modifier_safetext($_tmp)); ?>
</td>
                    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['shopitem']['owner'])) ? $this->_run_mod_handler('safetext', true, $_tmp) : smarty_modifier_safetext($_tmp)); ?>
</td>
                    <td>
	                <?php $_from = $this->_tpl_vars['shopitem']['adminnames']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['adminname']):
?>
    	                <?php echo ((is_array($_tmp=$this->_tpl_vars['adminname']['uname'])) ? $this->_run_mod_handler('safetext', true, $_tmp) : smarty_modifier_safetext($_tmp)); ?>
<br />
	                <?php endforeach; endif; unset($_from); ?>
                    </td>
                    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['shopitem']['aff_name'])) ? $this->_run_mod_handler('safetext', true, $_tmp) : smarty_modifier_safetext($_tmp)); ?>
</td>
                    <td><?php if ($this->_tpl_vars['shopitem']['shoptype'] != ''): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['shopitem']['shoptype'])) ? $this->_run_mod_handler('safetext', true, $_tmp) : smarty_modifier_safetext($_tmp)); ?>
<?php else: ?><i><?php echo smarty_function_gt(array('text' => 'not configured'), $this);?>
</i><?php endif; ?></td>
                    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['shopitem']['fax'])) ? $this->_run_mod_handler('safetext', true, $_tmp) : smarty_modifier_safetext($_tmp)); ?>
</td>
                    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['shopitem']['country_name'])) ? $this->_run_mod_handler('safetext', true, $_tmp) : smarty_modifier_safetext($_tmp)); ?>
</td>
                    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['shopitem']['region_name'])) ? $this->_run_mod_handler('safetext', true, $_tmp) : smarty_modifier_safetext($_tmp)); ?>
</td>
                    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['shopitem']['city_name'])) ? $this->_run_mod_handler('safetext', true, $_tmp) : smarty_modifier_safetext($_tmp)); ?>
</td>
                    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['shopitem']['area_name'])) ? $this->_run_mod_handler('safetext', true, $_tmp) : smarty_modifier_safetext($_tmp)); ?>
</td>
                    <td>
                        <?php $_from = $this->_tpl_vars['shopitem']['shop_categories']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['shopcat']):
?>
                            <?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['shopcat']['category_name'])) ? $this->_run_mod_handler('ucfirst', true, $_tmp) : ucfirst($_tmp)))) ? $this->_run_mod_handler('safetext', true, $_tmp) : smarty_modifier_safetext($_tmp)); ?>

                        <?php endforeach; endif; unset($_from); ?>
                    </td>
                    <td>
                       <?php $_from = $this->_tpl_vars['shopitem']['shop_branches']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['shopbranch']):
?>
                            <?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['shopbranch']['branch_name'])) ? $this->_run_mod_handler('ucfirst', true, $_tmp) : ucfirst($_tmp)))) ? $this->_run_mod_handler('safetext', true, $_tmp) : smarty_modifier_safetext($_tmp)); ?>

                        <?php endforeach; endif; unset($_from); ?>
                    </td>
                    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['shopitem']['description'])) ? $this->_run_mod_handler('safetext', true, $_tmp) : smarty_modifier_safetext($_tmp)); ?>
</td>
                   
                    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['aStatus'][$this->_tpl_vars['shopitem']['status']])) ? $this->_run_mod_handler('safetext', true, $_tmp) : smarty_modifier_safetext($_tmp)); ?>
</td>
                                       <td><?php echo ((is_array($_tmp=$this->_tpl_vars['shopitem']['cr_date'])) ? $this->_run_mod_handler('safetext', true, $_tmp) : smarty_modifier_safetext($_tmp)); ?>
</td>
                                        <td><?php echo ((is_array($_tmp=$this->_tpl_vars['shopitem']['lu_date'])) ? $this->_run_mod_handler('safetext', true, $_tmp) : smarty_modifier_safetext($_tmp)); ?>
</td>
                  
                </tr>
                <?php endforeach; else: ?>
                <tr class="z-datatableempty"><td colspan="24"><?php echo smarty_function_gt(array('text' => 'No shops currently in database.'), $this);?>
</td></tr>
                <?php endif; unset($_from); ?>
            </tbody>
        </table>
        <!-- 
        <p id='zselex_bulkaction_control'>
            <?php echo smarty_function_img(array('modname' => 'core','set' => 'icons/extrasmall','src' => '2uparrow.png','alt' => ((is_array($_tmp='doubleuparrow')) ? $this->_run_mod_handler('gt', true, $_tmp, $this->_tpl_vars['zikula_view']) : smarty_modifier_gt($_tmp, $this->_tpl_vars['zikula_view']))), $this);?>
<a href="javascript:void(0);" id="zselex_select_all"><?php echo smarty_function_gt(array('text' => 'Check all'), $this);?>
</a> / <a href="javascript:void(0);" id="zselex_deselect_all"><?php echo smarty_function_gt(array('text' => 'Uncheck all'), $this);?>
</a>
            <select id='zselex_bulkaction_select' name='zselex_bulkaction_select'>
                <option value='0' selected='selected'><?php echo smarty_function_gt(array('text' => 'With selected:'), $this);?>
</option>
                <option value='1'><?php echo smarty_function_gt(array('text' => 'Delete'), $this);?>
</option>
                <option value='2'><?php echo smarty_function_gt(array('text' => 'active'), $this);?>
</option>
            </select>
        </p>
        -->
    </div>
            <input disabled type="hidden" name="chg_cat" id="chg_cat">
            <input disabled type="hidden" name="chg_brnch" id="chg_brnch">
            <input disabled type="hidden" name="chg_aff" id="chg_aff">
            <input disabled type="hidden" name="chg_stat" id="chg_stat">
            <input disabled type="hidden" name="chg_del" id="chg_del">
            <input disabled type="hidden" name="chg_demo" id="chg_demo">
            <input disabled type="hidden" name="chg_type" id="chg_type">
            <input disabled type="hidden" name="chg_group" id="chg_group">
            <p id='news_bulkaction_control'>
   <select id='select_type' name='select_type'>
        <option value='0' selected='selected'><?php echo smarty_function_gt(array('text' => 'With selected:'), $this);?>
</option>
        <option value='aff'><?php echo smarty_function_gt(array('text' => 'Change Affiliate'), $this);?>
</option>
        <option value='cat'><?php echo smarty_function_gt(array('text' => 'Change Category'), $this);?>
</option>
        <option value='rm_cat'><?php echo smarty_function_gt(array('text' => 'Remove Category'), $this);?>
</option>
        <option value='brnch'><?php echo smarty_function_gt(array('text' => 'Change Branch'), $this);?>
</option>
        <option value='rm_brnch'><?php echo smarty_function_gt(array('text' => 'Remove Branch'), $this);?>
</option>
        <option value='stat'><?php echo smarty_function_gt(array('text' => 'Change Status'), $this);?>
</option>
        <option value='rdemo'><?php echo smarty_function_gt(array('text' => 'Reactivate Demo'), $this);?>
</option>
        <option value='upbundle'><?php echo smarty_function_gt(array('text' => 'Update Bundles'), $this);?>
</option>
        <option value='del'><?php echo smarty_function_gt(array('text' => 'Delete'), $this);?>
</option>
        <option value='group'><?php echo smarty_function_gt(array('text' => 'Assign to Group'), $this);?>
</option>
   </select>
</p>
</form>
                       <script type="text/javascript"><?php echo '
                        var defwindowajax = new Zikula.UI.Window($(\'defwindowajax1\'),{resizable: true});
                     
                        '; ?>
</script>
<!-- 
<form class="z-form" action="<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'admin','func' => 'modifyshop'), $this);?>
" method="post">
    <div>
        <fieldset>
            <label for="directshop_id"><?php echo smarty_function_gt(array('text' => 'Access a past shop via its ID'), $this);?>
:</label>
            <input type="text" id="directshop_id" name="shop_id" value="" size="5" maxlength="8" />
            <span class="z-nowrap z-buttons">
                <input class="z-bt-small" name="submit" type="submit" value="<?php echo smarty_function_gt(array('text' => 'Go retrieve'), $this);?>
" />
                <input class="z-bt-small" name="reset" type="reset" value="<?php echo smarty_function_gt(array('text' => 'Reset'), $this);?>
" />
            </span>
        </fieldset>
    </div>
</form>
-->
<div>

  <select id="cat" style="display:none">
                    <option value=''><?php echo smarty_function_gt(array('text' => 'select category'), $this);?>
</option>
                    <?php $_from = $this->_tpl_vars['categories']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['category']):
?>
                    <option value="<?php echo $this->_tpl_vars['category']['category_id']; ?>
" ><?php echo $this->_tpl_vars['category']['category_name']; ?>
</option>
                    <?php endforeach; endif; unset($_from); ?>
  </select>
  <select id="brnch"  style="display:none">
                    <option value=''><?php echo smarty_function_gt(array('text' => 'select branch'), $this);?>
</option>
                        <?php $_from = $this->_tpl_vars['branches']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['branch']):
?>
                        <option value="<?php echo $this->_tpl_vars['branch']['branch_id']; ?>
" ><?php echo $this->_tpl_vars['branch']['branch_name']; ?>
</option>
                        <?php endforeach; endif; unset($_from); ?>
                    </select>
  <select id="aff" style="display:none">
             <option value='0'><?php echo smarty_function_gt(array('text' => 'select affiliate'), $this);?>
</option>
            <?php $_from = $this->_tpl_vars['affiliates']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['affiliate']):
?>
             <option value="<?php echo $this->_tpl_vars['affiliate']['aff_id']; ?>
" ><?php echo ((is_array($_tmp=$this->_tpl_vars['affiliate']['aff_name'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</option>
            <?php endforeach; endif; unset($_from); ?>
  </select>
  <select id="stat" style="display:none">
        <option value=''><?php echo smarty_function_gt(array('text' => 'select status'), $this);?>
</option>
        <option value='1'><?php echo smarty_function_gt(array('text' => 'Active'), $this);?>
</option>
        <option value='0'><?php echo smarty_function_gt(array('text' => 'InActive'), $this);?>
</option>
        
  </select>
  <select id="group" style="display:none">
             <option value='0'><?php echo smarty_function_gt(array('text' => 'select group'), $this);?>
</option>
            <?php $_from = $this->_tpl_vars['groups']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['group']):
?>
             <option value="<?php echo $this->_tpl_vars['group']['gid']; ?>
" ><?php echo $this->_tpl_vars['group']['name']; ?>
</option>
            <?php endforeach; endif; unset($_from); ?>
  </select>
 </div>           
<?php echo smarty_function_pager(array('rowcount' => $this->_tpl_vars['total_shops'],'limit' => $this->_tpl_vars['itemsperpage'],'posvar' => 'startnum','maxpages' => 10), $this);?>

<?php echo smarty_function_adminfooter(array(), $this);?>

<!-- This form below appears as a formdialog when a bulk action of 'change categories' is selected -->

<script type="text/javascript"><?php echo '
// <![CDATA[
    Zikula.UI.Tooltips($$(\'.tooltips\'));
// ]]>
'; ?>
</script>

<style><?php echo '
     .cat_content {
       background-color: white;
        /* border: 5px solid #DD511D;*/
        left: 50%;
        min-height: 70px;
        margin-left: -270px;
        /* overflow: hidden;*/
        height: auto;
        position: absolute;
        padding: 20px;
        top: 10%;
        width: 500px;
        z-index: 10002;
    }
    
    
    .backshield {
        background-color: #333333;
        height: 300%;
        left: 0;
        opacity: 0.8;
        position: absolute;
        top: 0;
        width: 100%;
        z-index: 1000;
    }
'; ?>
</style>
<div id="updateBundles" class="cat_content" style="display:none">

</div>
<div id="backshield" class="backshield" style="min-height: 12000px;height:auto;display:none"></div> 