<?php /* Smarty version 2.6.28, created on 2017-10-29 15:28:31
         compiled from admin/shopsettings.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'pageaddvar', 'admin/shopsettings.tpl', 2, false),array('function', 'jscroll', 'admin/shopsettings.tpl', 19, false),array('function', 'timepickerplugin', 'admin/shopsettings.tpl', 27, false),array('function', 'jcrop', 'admin/shopsettings.tpl', 28, false),array('function', 'icon', 'admin/shopsettings.tpl', 36, false),array('function', 'gt', 'admin/shopsettings.tpl', 37, false),array('function', 'img', 'admin/shopsettings.tpl', 53, false),array('function', 'modurl', 'admin/shopsettings.tpl', 57, false),array('function', 'button', 'admin/shopsettings.tpl', 66, false),array('function', 'modfunc', 'admin/shopsettings.tpl', 670, false),array('function', 'imageproportional', 'admin/shopsettings.tpl', 727, false),array('function', 'adminfooter', 'admin/shopsettings.tpl', 894, false),array('block', 'securityutil_checkpermission_block', 'admin/shopsettings.tpl', 39, false),array('modifier', 'gt', 'admin/shopsettings.tpl', 53, false),array('modifier', 'cleantext', 'admin/shopsettings.tpl', 73, false),array('modifier', 'unserialize', 'admin/shopsettings.tpl', 581, false),array('modifier', 'replace', 'admin/shopsettings.tpl', 724, false),array('modifier', 'safetext', 'admin/shopsettings.tpl', 728, false),array('insert', 'csrftoken', 'admin/shopsettings.tpl', 58, false),)), $this); ?>

<?php echo smarty_function_pageaddvar(array('name' => 'javascript','value' => 'jquery'), $this);?>


<!------------------ DND PLUGIN --------------------------------------->
<?php echo smarty_function_pageaddvar(array('name' => 'javascript','value' => 'modules/ZSELEX/javascript/DND/ajaxupload-min.js'), $this);?>

<?php echo smarty_function_pageaddvar(array('name' => 'stylesheet','value' => "modules/ZSELEX/style/DND/classicTheme/style.css"), $this);?>

<!---------------------------------------------------------------------->


<?php echo smarty_function_pageaddvar(array('name' => 'stylesheet','value' => "themes/".($this->_tpl_vars['current_theme'])."/style/shopsetting.css"), $this);?>


<?php echo smarty_function_jscroll(array('file_name' => 'modules/ZSELEX/javascript/scroll.js'), $this);?>


<script src="modules/ZSELEX/javascript/jquerycalendar/jquery-ui.js"><?php echo ''; ?>
</script>
<?php echo smarty_function_pageaddvar(array('name' => 'javascript','value' => 'modules/ZSELEX/javascript/shopsetting/shopsetting.js'), $this);?>

<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<?php echo smarty_function_pageaddvar(array('name' => 'stylesheet','value' => "themes/".($this->_tpl_vars['current_theme'])."/style/minisiteimages.css"), $this);?>

<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<?php echo smarty_function_timepickerplugin(array(), $this);?>

<?php echo smarty_function_jcrop(array(), $this);?>


<input type="hidden" id="servicelimit" value="<?php echo $this->_tpl_vars['servicelimit']; ?>
" />
<input type="hidden" id="quantity" value="<?php echo $this->_tpl_vars['quantity']; ?>
" />
<input type="hidden" id="uploadpath" value="<?php echo $this->_tpl_vars['uploadpath']; ?>
" />
<input type="hidden" id="shop_id" name="shop_id" value="<?php echo $_REQUEST['shop_id']; ?>
" />

<div class="z-admin-content-pagetitle">
    <?php echo smarty_function_icon(array('type' => 'view','size' => 'small'), $this);?>

    <h3><?php echo smarty_function_gt(array('text' => 'Shop Settings'), $this);?>
</h3>
</div>
<?php $this->_tag_stack[] = array('securityutil_checkpermission_block', array('component' => 'ZSELEX::','instance' => '::','level' => 'ACCESS_ADD')); $_block_repeat=true;smarty_block_securityutil_checkpermission_block($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>

<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_securityutil_checkpermission_block($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<div id="backshield" class="backshield" style="height: 4000px;display:none"></div>
<div id="editImage" class="basket_content" style="display:none"></div>    
<div id="cropImage" class="basket_content" style="display:none"></div> 

<br>
<div id="deleteShop" class="basket_content" style="display:none">
    
</div>  
 <div class="z-buttons z-formbuttons">
         <button onClick="deleteShopRequest()" id="product_delete"  type="button"  name="action" value="deleteimage" title="<?php echo smarty_function_gt(array('text' => 'Delete Shop'), $this);?>
">
             <?php echo smarty_function_img(array('src' => '14_layer_deletelayer.png','modname' => 'core','set' => 'icons/extrasmall','alt' => ((is_array($_tmp='Delete Shop')) ? $this->_run_mod_handler('gt', true, $_tmp, $this->_tpl_vars['zikula_view']) : smarty_modifier_gt($_tmp, $this->_tpl_vars['zikula_view'])),'title' => ((is_array($_tmp='Delete Shop')) ? $this->_run_mod_handler('gt', true, $_tmp, $this->_tpl_vars['zikula_view']) : smarty_modifier_gt($_tmp, $this->_tpl_vars['zikula_view']))), $this);?>

             <?php echo smarty_function_gt(array('text' => 'Delete Shop'), $this);?>

         </button>
 </div>
<form class="z-form" id="zselex_form" name="zselex_form" action="<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'admin','func' => 'shopsettings','shop_id' => $_REQUEST['shop_id']), $this);?>
" method="post">
    <input type="hidden" name="csrftoken" value="<?php require_once(SMARTY_CORE_DIR . 'core.run_insert_handler.php');
echo smarty_core_run_insert_handler(array('args' => array('name' => 'csrftoken')), $this); ?>
" />
    <div class="z-panels" id="panel">
        <a name="aTop"></a>
        <fieldset>
            <legend id="settingheaders" title="<?php echo smarty_function_gt(array('text' => 'Click here to see more fields and options'), $this);?>
"><?php echo smarty_function_gt(array('text' => 'Shop Information'), $this);?>
</legend> 
            <div id="settingheadersdiv" style="display:none">
                <div class="z-buttons z-formbuttons">
                    <a href="<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'avery','func' => 'printAvery','shop_id' => $_REQUEST['shop_id']), $this);?>
" title="<?php echo smarty_function_gt(array('text' => 'Cancel'), $this);?>
" target="_blank"> <?php echo smarty_function_gt(array('text' => 'Print Label Sheet'), $this);?>
</a>
                    <?php echo smarty_function_button(array('src' => "button_ok.png",'set' => "icons/extrasmall",'alt' => ((is_array($_tmp='Save')) ? $this->_run_mod_handler('gt', true, $_tmp, $this->_tpl_vars['zikula_view']) : smarty_modifier_gt($_tmp, $this->_tpl_vars['zikula_view'])),'title' => ((is_array($_tmp='Save')) ? $this->_run_mod_handler('gt', true, $_tmp, $this->_tpl_vars['zikula_view']) : smarty_modifier_gt($_tmp, $this->_tpl_vars['zikula_view'])),'text' => ((is_array($_tmp='Save')) ? $this->_run_mod_handler('gt', true, $_tmp, $this->_tpl_vars['zikula_view']) : smarty_modifier_gt($_tmp, $this->_tpl_vars['zikula_view'])),'name' => ((is_array($_tmp='action')) ? $this->_run_mod_handler('gt', true, $_tmp, $this->_tpl_vars['zikula_view']) : smarty_modifier_gt($_tmp, $this->_tpl_vars['zikula_view'])),'value' => ((is_array($_tmp='savedefaults')) ? $this->_run_mod_handler('gt', true, $_tmp, $this->_tpl_vars['zikula_view']) : smarty_modifier_gt($_tmp, $this->_tpl_vars['zikula_view']))), $this);?>

                    <a href="<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'user','func' => 'site','shop_id' => $_REQUEST['shop_id']), $this);?>
" title="<?php echo smarty_function_gt(array('text' => 'Cancel'), $this);?>
"><?php echo smarty_function_img(array('modname' => 'core','src' => "button_cancel.png",'set' => "icons/extrasmall",'alt' => ((is_array($_tmp='Cancel')) ? $this->_run_mod_handler('gt', true, $_tmp, $this->_tpl_vars['zikula_view']) : smarty_modifier_gt($_tmp, $this->_tpl_vars['zikula_view'])),'title' => ((is_array($_tmp='Cancel')) ? $this->_run_mod_handler('gt', true, $_tmp, $this->_tpl_vars['zikula_view']) : smarty_modifier_gt($_tmp, $this->_tpl_vars['zikula_view']))), $this);?>
 <?php echo smarty_function_gt(array('text' => 'Cancel'), $this);?>
</a>
                </div>

                <fieldset>
                    <div class="z-formrow">
                        <label for="shop_name"><?php echo smarty_function_gt(array('text' => 'Shop Name'), $this);?>
</label>
                        <input type="text"  name='shop_name' id='shop_name' value="<?php echo ((is_array($_tmp=$this->_tpl_vars['shop_info']['shop_name'])) ? $this->_run_mod_handler('cleantext', true, $_tmp) : smarty_modifier_cleantext($_tmp)); ?>
" >
                    </div>
                    <div class="z-formrow">
                        <label for="urltitle"><?php echo smarty_function_gt(array('text' => 'Shop URL'), $this);?>
</label>
                        <input disabled type="text"  name='urltitle' id='urltitle' value="<?php echo $this->_tpl_vars['shop_info']['urltitle']; ?>
" >
                        <span style="padding-left:164px;cursor:pointer;color:blue" id="unlock" onclick="unlockTitle();"><?php echo smarty_function_gt(array('text' => 'unlock'), $this);?>
</span>
                    </div>

                </fieldset>

                <a name="aInformation"></a>
                <fieldset>
                    <div class="z-formrow">
                        <label for="shop_info"><?php echo smarty_function_gt(array('text' => 'Frontpage Information'), $this);?>
</label>
                        <textarea  name='shop_info' id='shop_info' cols="80" rows="10"><?php echo ((is_array($_tmp=$this->_tpl_vars['shop_info']['shop_info'])) ? $this->_run_mod_handler('cleantext', true, $_tmp) : smarty_modifier_cleantext($_tmp)); ?>
</textarea>
                    </div>
                      <div class="z-formrow">
                        <label for="link_to_homepage"><?php echo smarty_function_gt(array('text' => 'Link to my home page'), $this);?>
</label>
                        <input type='text'  name='link_to_homepage' id='link_to_homepage' value='<?php echo $this->_tpl_vars['shop_info']['link_to_homepage']; ?>
' />
                    <span style="padding-left:154px" align="justify">
                        <i style="color:grey"> 
                            <?php echo smarty_function_gt(array('text' => "* Just leave this field empty if you don't have a homepage."), $this);?>

                            <br>
                            <?php echo smarty_function_gt(array('text' => "** Insert HTTP:// in front of your link if it is external."), $this);?>

                            
                        </i>
                    </span>
                      </div>

                </fieldset>

                <a name="aAddress"></a>
                <fieldset>
                    <div class="z-formrow">
                        <label for="opening_hours"><?php echo smarty_function_gt(array('text' => 'Address'), $this);?>
</label>
                        <textarea  name='address' id='address' cols="80" rows="3"><?php echo ((is_array($_tmp=$this->_tpl_vars['shop_info']['address'])) ? $this->_run_mod_handler('cleantext', true, $_tmp) : smarty_modifier_cleantext($_tmp)); ?>
</textarea>
                    </div>

                    <div class="z-formrow">
                        <label for="elemtTele"><?php echo smarty_function_gt(array('text' => 'Telephone'), $this);?>
</label>
                        <input type='text'  name='telephone' id='telephone' value='<?php echo $this->_tpl_vars['shop_info']['telephone']; ?>
' />
                    </div>
                    <div class="z-formrow">
                        <label for="elemtTele"><?php echo smarty_function_gt(array('text' => 'VAT#'), $this);?>
</label>
                        <input type='text'  name='vat_number' id='telephone' value='<?php echo $this->_tpl_vars['shop_info']['vat_number']; ?>
' />
                    </div>

                    <div class="z-formrow">
                        <label for="elemtFax"><?php echo smarty_function_gt(array('text' => 'Fax'), $this);?>
</label>
                        <input type='text'  name='fax' id='elemtFax' value='<?php echo $this->_tpl_vars['shop_info']['fax']; ?>
'   />
                    </div>

                    <div class="z-formrow">
                        <label for="elemtEmail"><?php echo smarty_function_gt(array('text' => 'Email'), $this);?>
</label>
                        <input type='text'  name='email' id='elemtEmail' value='<?php echo $this->_tpl_vars['shop_info']['email']; ?>
'   />
                    </div>

                </fieldset>

                <a name="aOpeninghours"></a>
                <fieldset>
                    <div class="z-formrow">
                        <label for="opening_hours"><?php echo smarty_function_gt(array('text' => 'Opening Hours'), $this);?>
</label>
                                                <table align="center">
                            <tr>
                                <td></td>
                                <td><?php echo smarty_function_gt(array('text' => 'Open'), $this);?>
</td>
                                <td><?php echo smarty_function_gt(array('text' => 'Close'), $this);?>
</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td><?php echo smarty_function_gt(array('text' => 'Monday'), $this);?>
</td>
                                <td><input class="timepicker" type="text" id="mon_open" name='opening_hours[mon][open]' value="<?php if ($this->_tpl_vars['shop_info']['opening_hour_array']['mon']['open'] != ''): ?><?php echo $this->_tpl_vars['shop_info']['opening_hour_array']['mon']['open']; ?>
<?php else: ?>8:00<?php endif; ?>"></td>
                                <td><input class="timepicker" type="text" id="mon_close"  name='opening_hours[mon][close]' value="<?php if ($this->_tpl_vars['shop_info']['opening_hour_array']['mon']['close'] != ''): ?><?php echo $this->_tpl_vars['shop_info']['opening_hour_array']['mon']['close']; ?>
<?php else: ?>16:00<?php endif; ?>"></td>
                                <td><input type="checkbox" name='opening_hours[mon][closed]' value="1" <?php if ($this->_tpl_vars['shop_info']['opening_hour_array']['mon']['closed']): ?>checked<?php endif; ?>>&nbsp;<?php echo smarty_function_gt(array('text' => 'closed'), $this);?>
</td>
                            </tr>
                            <tr>
                                <td><?php echo smarty_function_gt(array('text' => 'Tuesday'), $this);?>
<br><span style="cursor:pointer;color:blue;" onclick="copyTime(document.getElementById('mon_open').value, document.getElementById('mon_close').value, 'tue')"><font size="1"><?php echo smarty_function_gt(array('text' => 'as above'), $this);?>
</font></span></td>
                                <td><input class="timepicker" type="text" id="tue_open" name='opening_hours[tue][open]' value="<?php if ($this->_tpl_vars['shop_info']['opening_hour_array']['tue']['open']): ?><?php echo $this->_tpl_vars['shop_info']['opening_hour_array']['tue']['open']; ?>
<?php else: ?>8:00<?php endif; ?>"></td>
                                <td><input class="timepicker" type="text" id="tue_close" name='opening_hours[tue][close]' value="<?php if ($this->_tpl_vars['shop_info']['opening_hour_array']['tue']['close'] != ''): ?><?php echo $this->_tpl_vars['shop_info']['opening_hour_array']['tue']['close']; ?>
<?php else: ?>16:00<?php endif; ?>"></td>
                                <td><input type="checkbox" value="1" name='opening_hours[tue][closed]' <?php if ($this->_tpl_vars['shop_info']['opening_hour_array']['tue']['closed']): ?>checked<?php endif; ?>>&nbsp;<?php echo smarty_function_gt(array('text' => 'closed'), $this);?>
</td>
                            </tr>
                            <tr>
                                <td><?php echo smarty_function_gt(array('text' => 'Wednesday'), $this);?>
<br><span style="cursor:pointer;color:blue;" onclick="copyTime(document.getElementById('tue_open').value, document.getElementById('tue_close').value, 'wed')"><font size="1"><?php echo smarty_function_gt(array('text' => 'as above'), $this);?>
</font></span></td>
                                <td><input class="timepicker" type="text" id="wed_open" name='opening_hours[wed][open]' value="<?php if ($this->_tpl_vars['shop_info']['opening_hour_array']['wed']['open'] != ''): ?><?php echo $this->_tpl_vars['shop_info']['opening_hour_array']['wed']['open']; ?>
<?php else: ?>8:00<?php endif; ?>"></td>
                                <td><input class="timepicker" type="text" id="wed_close" name='opening_hours[wed][close]' value="<?php if ($this->_tpl_vars['shop_info']['opening_hour_array']['wed']['close'] != ''): ?><?php echo $this->_tpl_vars['shop_info']['opening_hour_array']['wed']['close']; ?>
<?php else: ?>16:00<?php endif; ?>"></td>
                                <td><input type="checkbox" value="1" name='opening_hours[wed][closed]' <?php if ($this->_tpl_vars['shop_info']['opening_hour_array']['wed']['closed']): ?>checked<?php endif; ?>>&nbsp;<?php echo smarty_function_gt(array('text' => 'closed'), $this);?>
</td>
                            </tr>
                            <tr>
                                <td><?php echo smarty_function_gt(array('text' => 'Thursday'), $this);?>
<br><span style="cursor:pointer;color:blue;" onclick="copyTime(document.getElementById('wed_open').value, document.getElementById('wed_close').value, 'thu')"><font size="1"><?php echo smarty_function_gt(array('text' => 'as above'), $this);?>
</font></span></td>
                                <td><input class="timepicker" type="text" id="thu_open" name='opening_hours[thu][open]' value="<?php if ($this->_tpl_vars['shop_info']['opening_hour_array']['thu']['open'] != ''): ?><?php echo $this->_tpl_vars['shop_info']['opening_hour_array']['thu']['open']; ?>
<?php else: ?>8:00<?php endif; ?>"></td>
                                <td><input class="timepicker" type="text" id="thu_close"  name='opening_hours[thu][close]' value="<?php if ($this->_tpl_vars['shop_info']['opening_hour_array']['thu']['close'] != ''): ?><?php echo $this->_tpl_vars['shop_info']['opening_hour_array']['thu']['close']; ?>
<?php else: ?>16:00<?php endif; ?>"></td>
                                <td><input type="checkbox" value="1" name='opening_hours[thu][closed]' <?php if ($this->_tpl_vars['shop_info']['opening_hour_array']['thu']['closed']): ?>checked<?php endif; ?>>&nbsp;<?php echo smarty_function_gt(array('text' => 'closed'), $this);?>
</td>
                            </tr>
                            <tr>
                                <td><?php echo smarty_function_gt(array('text' => 'Friday'), $this);?>
<br><span style="cursor:pointer;color:blue;" onclick="copyTime(document.getElementById('thu_open').value, document.getElementById('thu_close').value, 'fri')"><font size="1"><?php echo smarty_function_gt(array('text' => 'as above'), $this);?>
</font></span></td>
                                <td><input class="timepicker" type="text" id="fri_open"  name='opening_hours[fri][open]' value="<?php if ($this->_tpl_vars['shop_info']['opening_hour_array']['fri']['open']): ?><?php echo $this->_tpl_vars['shop_info']['opening_hour_array']['fri']['open']; ?>
<?php else: ?>8:00<?php endif; ?>"></td>
                                <td><input class="timepicker" type="text" id="fri_close"  name='opening_hours[fri][close]' value="<?php if ($this->_tpl_vars['shop_info']['opening_hour_array']['fri']['close'] != ''): ?><?php echo $this->_tpl_vars['shop_info']['opening_hour_array']['fri']['close']; ?>
<?php else: ?>16:00<?php endif; ?>"></td>
                                <td><input type="checkbox" value="1" name='opening_hours[fri][closed]' <?php if ($this->_tpl_vars['shop_info']['opening_hour_array']['fri']['closed']): ?>checked<?php endif; ?>>&nbsp;<?php echo smarty_function_gt(array('text' => 'closed'), $this);?>
</td>
                            </tr>
                            <tr>
                                <td><?php echo smarty_function_gt(array('text' => 'Saturday'), $this);?>
<br><span style="cursor:pointer;color:blue;" onclick="copyTime(document.getElementById('fri_open').value, document.getElementById('fri_close').value, 'sat')"><font size="1"><?php echo smarty_function_gt(array('text' => 'as above'), $this);?>
</font></span></td>
                                <td><input class="timepicker" type="text" id="sat_open" name='opening_hours[sat][open]' value="<?php if ($this->_tpl_vars['shop_info']['opening_hour_array']['sat']['open']): ?><?php echo $this->_tpl_vars['shop_info']['opening_hour_array']['sat']['open']; ?>
<?php else: ?>8:00<?php endif; ?>"></td>
                                <td><input class="timepicker" type="text" id="sat_close"  name='opening_hours[sat][close]' value="<?php if ($this->_tpl_vars['shop_info']['opening_hour_array']['sat']['close'] != ''): ?><?php echo $this->_tpl_vars['shop_info']['opening_hour_array']['sat']['close']; ?>
<?php else: ?>16:00<?php endif; ?>"></td>
                                <td><input type="checkbox" value="1" name='opening_hours[sat][closed]' <?php if ($this->_tpl_vars['shop_info']['opening_hour_array']['sat']['closed']): ?>checked<?php endif; ?>>&nbsp;<?php echo smarty_function_gt(array('text' => 'closed'), $this);?>
</td>
                            </tr>
                            <tr>
                                <td><?php echo smarty_function_gt(array('text' => 'Sunday'), $this);?>
<br><span style="cursor:pointer;color:blue;" onclick="copyTime(document.getElementById('sat_open').value, document.getElementById('sat_close').value, 'sun')"><font size="1"><?php echo smarty_function_gt(array('text' => 'as above'), $this);?>
</font></span></td>
                                <td><input class="timepicker" type="text" id="sun_open" name='opening_hours[sun][open]' value="<?php if ($this->_tpl_vars['shop_info']['opening_hour_array']['sun']['open'] != ''): ?><?php echo $this->_tpl_vars['shop_info']['opening_hour_array']['sun']['open']; ?>
<?php else: ?>8:00<?php endif; ?>"></td>
                                <td><input class="timepicker" type="text" id="sun_close" name='opening_hours[sun][close]' value="<?php if ($this->_tpl_vars['shop_info']['opening_hour_array']['sun']['close'] != ''): ?><?php echo $this->_tpl_vars['shop_info']['opening_hour_array']['sun']['close']; ?>
<?php else: ?>16:00<?php endif; ?>"></td>
                                <td><input type="checkbox" value="1" name='opening_hours[sun][closed]' <?php if ($this->_tpl_vars['shop_info']['opening_hour_array']['sun']['closed']): ?>checked<?php endif; ?>>&nbsp;<?php echo smarty_function_gt(array('text' => 'closed'), $this);?>
</td>
                            </tr>
                            <tr>
                                <td><?php echo smarty_function_gt(array('text' => 'Comment'), $this);?>
</td>
                                <td colspan="3"><textarea style="width: 250px; height: 86px;"  name='opening_hours[comment]' id='comment' cols="90" rows="3"><?php echo ((is_array($_tmp=$this->_tpl_vars['shop_info']['opening_hour_array']['comment'])) ? $this->_run_mod_handler('cleantext', true, $_tmp) : smarty_modifier_cleantext($_tmp)); ?>
</textarea></td>

                            </tr>
                        </table>
                    </div>

                </fieldset>

                <!--            </div>
                        </fieldset>                     
                
                
                        <fieldset>
                            <legend id="settingheaders"><?php echo smarty_function_gt(array('text' => 'Advance Settings'), $this);?>
</legend> 
                            <div style="display:none">
                -->
                <div class="z-formrow">
                    <label for="mainshopyes"><?php echo smarty_function_gt(array('text' => 'Set as Main Shop'), $this);?>
</label>
                    <div>
                        <input type="radio" onclick='radioCheck()' value="1" id="mainshopyes" name="mainshop" <?php if ($this->_tpl_vars['shop_info']['main'] == 1): ?> checked="checked"<?php endif; ?>/>
                               <input type="button"  onclick="check();" value="<?php echo smarty_function_gt(array('text' => 'Set'), $this);?>
">
                        <input type="button" onclick="uncheck();" value="<?php echo smarty_function_gt(array('text' => 'Unset'), $this);?>
">
                    </div>
                </div>
                    
                     <div class="z-formrow">
                    <label for="adv_sel_prod"><?php echo smarty_function_gt(array('text' => 'Advertise only selected products'), $this);?>
</label>
                    <div>
                        <input type="checkbox"  value="1" id="adv_sel_prod" name="adv_sel_prod" <?php if ($this->_tpl_vars['shop_info']['advertise_sel_prods'] == 1): ?> checked="checked"<?php endif; ?>/>
                             
                    </div>
                </div>

                <!--fieldset>
                    <legend><?php echo smarty_function_gt(array('text' => 'Set Shop Default Image'), $this);?>
</legend>
                    <div class="z-formrow">

                        <label for="default_img_frm1"><?php echo smarty_function_gt(array('text' => 'From Minisite Image'), $this);?>
</label>
                        <div>
                            <input <?php if (! $this->_tpl_vars['image_perm']): ?> disabled <?php endif; ?> type="radio" value="fromshop" id="default_img_frm1" name="default_img_frm" <?php if ($this->_tpl_vars['shop_info']['default_img_frm'] == 'fromshop'): ?> checked="checked"<?php endif; ?>/>
                        </div>
                        <label for="default_img_frm2"><?php echo smarty_function_gt(array('text' => 'From Gallery Image'), $this);?>
</label>
                        <div>
                            <input <?php if (! $this->_tpl_vars['gallery_perm']): ?> disabled <?php endif; ?> type="radio" value="fromgallery" id="default_img_frm2" name="default_img_frm" <?php if ($this->_tpl_vars['shop_info']['default_img_frm'] == 'fromgallery'): ?> checked="checked"<?php endif; ?>/>
                        </div>

                    </div>
                </fieldset-->


                <!--div class="z-formrow">
                    <label for="article"><?php echo smarty_function_gt(array('text' => 'Select Article'), $this);?>
</label>
                    <select <?php if (! $this->_tpl_vars['article_perm']): ?> disabled <?php endif; ?> name='formElements[article]' id='article' class="icon-menu" >
                        <option style="padding-left:0px;" value=""><?php echo smarty_function_gt(array('text' => 'Select Article'), $this);?>
</option>
                        <?php $_from = $this->_tpl_vars['articles']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['article']):
?>
                        <option  value="<?php echo $this->_tpl_vars['article']['sid']; ?>
" <?php if ($this->_tpl_vars['item']['news_article_id'] == $this->_tpl_vars['article']['sid']): ?> selected='selected' <?php endif; ?>> <?php echo $this->_tpl_vars['article']['title']; ?>
 </option>
                        <?php endforeach; endif; unset($_from); ?>
                    </select>
                </div-->


                <div class="z-buttons z-formbuttons">
                    <?php echo smarty_function_button(array('src' => "button_ok.png",'set' => "icons/extrasmall",'alt' => ((is_array($_tmp='Save')) ? $this->_run_mod_handler('gt', true, $_tmp, $this->_tpl_vars['zikula_view']) : smarty_modifier_gt($_tmp, $this->_tpl_vars['zikula_view'])),'title' => ((is_array($_tmp='Save')) ? $this->_run_mod_handler('gt', true, $_tmp, $this->_tpl_vars['zikula_view']) : smarty_modifier_gt($_tmp, $this->_tpl_vars['zikula_view'])),'text' => ((is_array($_tmp='Save')) ? $this->_run_mod_handler('gt', true, $_tmp, $this->_tpl_vars['zikula_view']) : smarty_modifier_gt($_tmp, $this->_tpl_vars['zikula_view'])),'name' => ((is_array($_tmp='action')) ? $this->_run_mod_handler('gt', true, $_tmp, $this->_tpl_vars['zikula_view']) : smarty_modifier_gt($_tmp, $this->_tpl_vars['zikula_view'])),'value' => ((is_array($_tmp='savedefaults')) ? $this->_run_mod_handler('gt', true, $_tmp, $this->_tpl_vars['zikula_view']) : smarty_modifier_gt($_tmp, $this->_tpl_vars['zikula_view']))), $this);?>

                    <a href="<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'user','func' => 'site','shop_id' => $_REQUEST['shop_id']), $this);?>
" title="<?php echo smarty_function_gt(array('text' => 'Cancel'), $this);?>
"><?php echo smarty_function_img(array('modname' => 'core','src' => "button_cancel.png",'set' => "icons/extrasmall",'alt' => ((is_array($_tmp='Cancel')) ? $this->_run_mod_handler('gt', true, $_tmp, $this->_tpl_vars['zikula_view']) : smarty_modifier_gt($_tmp, $this->_tpl_vars['zikula_view'])),'title' => ((is_array($_tmp='Cancel')) ? $this->_run_mod_handler('gt', true, $_tmp, $this->_tpl_vars['zikula_view']) : smarty_modifier_gt($_tmp, $this->_tpl_vars['zikula_view']))), $this);?>
 <?php echo smarty_function_gt(array('text' => 'Cancel'), $this);?>
</a>
                </div>

            </div>

        </fieldset>   

<?php $this->_tag_stack[] = array('securityutil_checkpermission_block', array('component' => 'ZSELEX::','instance' => '::','level' => 'ACCESS_ADD')); $_block_repeat=true;smarty_block_securityutil_checkpermission_block($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
        <fieldset>
            <input type="hidden" name="formElement[shop_id]" value="<?php echo $_REQUEST['shop_id']; ?>
" />
            <legend id="settingheaders" title="<?php echo smarty_function_gt(array('text' => 'Click here to configure payment gateway'), $this);?>
"><?php echo smarty_function_gt(array('text' => 'Payment Gateways'), $this);?>
</legend> 
            <div id="settingheadersdiv" style="display:none">
                <div class="z-buttons z-formbuttons">
                    <?php echo smarty_function_button(array('src' => "button_ok.png",'set' => "icons/extrasmall",'alt' => ((is_array($_tmp='Save')) ? $this->_run_mod_handler('gt', true, $_tmp, $this->_tpl_vars['zikula_view']) : smarty_modifier_gt($_tmp, $this->_tpl_vars['zikula_view'])),'title' => ((is_array($_tmp='Save')) ? $this->_run_mod_handler('gt', true, $_tmp, $this->_tpl_vars['zikula_view']) : smarty_modifier_gt($_tmp, $this->_tpl_vars['zikula_view'])),'text' => ((is_array($_tmp='Save')) ? $this->_run_mod_handler('gt', true, $_tmp, $this->_tpl_vars['zikula_view']) : smarty_modifier_gt($_tmp, $this->_tpl_vars['zikula_view'])),'name' => ((is_array($_tmp='action')) ? $this->_run_mod_handler('gt', true, $_tmp, $this->_tpl_vars['zikula_view']) : smarty_modifier_gt($_tmp, $this->_tpl_vars['zikula_view'])),'value' => ((is_array($_tmp='savepayments')) ? $this->_run_mod_handler('gt', true, $_tmp, $this->_tpl_vars['zikula_view']) : smarty_modifier_gt($_tmp, $this->_tpl_vars['zikula_view']))), $this);?>

                    <a href="<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'user','func' => 'site','shop_id' => $_REQUEST['shop_id']), $this);?>
" title="<?php echo smarty_function_gt(array('text' => 'Cancel'), $this);?>
"><?php echo smarty_function_img(array('modname' => 'core','src' => "button_cancel.png",'set' => "icons/extrasmall",'alt' => ((is_array($_tmp='Cancel')) ? $this->_run_mod_handler('gt', true, $_tmp, $this->_tpl_vars['zikula_view']) : smarty_modifier_gt($_tmp, $this->_tpl_vars['zikula_view'])),'title' => ((is_array($_tmp='Cancel')) ? $this->_run_mod_handler('gt', true, $_tmp, $this->_tpl_vars['zikula_view']) : smarty_modifier_gt($_tmp, $this->_tpl_vars['zikula_view']))), $this);?>
 <?php echo smarty_function_gt(array('text' => 'Cancel'), $this);?>
</a>
                </div>
                <?php if ($this->_tpl_vars['payment_perm']): ?><!--payment config--->


                <fieldset>
                    
                    <legend  title="<?php echo smarty_function_gt(array('text' => 'Cards Accepted'), $this);?>
"><?php echo smarty_function_gt(array('text' => 'Cards/Payments Accepted'), $this);?>
</legend>
                     <div id="settingheadersdiv">
                    <table>
                        <tr>
                            <td>
                                  <input <?php if ($this->_tpl_vars['CardsAccepted']['paypal'] == 'PayPal|paypal.png'): ?>checked<?php endif; ?> id="paypl" type="checkbox" name="CardsAccepted[paypal]" value="PayPal|paypal.png">
                        <label for="paypl"><img class="paycard" src="modules/ZSELEX/images/CreditCards/paypal.png"><?php echo smarty_function_gt(array('text' => 'PayPal'), $this);?>
</label>
                            </td>
                             <td>
                                   <input <?php if ($this->_tpl_vars['CardsAccepted']['VisaDankort'] == 'Dankort/Visa-Dankort|dankort.png'): ?>checked<?php endif; ?> id="Vsa-Dankrt"  type="checkbox" name="CardsAccepted[VisaDankort]" value="Dankort/Visa-Dankort|dankort.png">
                        <label for="Vsa-Dankrt"><img class="paycard" class="paycard" src="modules/ZSELEX/images/CreditCards/dankort.png"><?php echo smarty_function_gt(array('text' => "Dankort/Visa-Dankort"), $this);?>
</label>
                            </td>
                            <td>
                                  <input <?php if ($this->_tpl_vars['CardsAccepted']['Maestro3D'] == 'Maestro (3D)|3d-maestro.png'): ?>checked<?php endif; ?> id="Maestro3D" type="checkbox" name="CardsAccepted[Maestro3D]" value="Maestro (3D)|3d-maestro.png">
                        <label for="Maestro3D"><img class="paycard" src="modules/ZSELEX/images/CreditCards/3d-maestro.png"><?php echo smarty_function_gt(array('text' => "Maestro (3D)"), $this);?>
</label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                 <input  <?php if ($this->_tpl_vars['CardsAccepted']['Mastercard3D'] == 'Mastercard (3D)|3d-mastercard.png'): ?>checked<?php endif; ?> id="Mastercard3D" type="checkbox" name="CardsAccepted[Mastercard3D]" value="Mastercard (3D)|3d-mastercard.png">
                        <label for="Mastercard3D"><img class="paycard" src="modules/ZSELEX/images/CreditCards/3d-mastercard.png"><?php echo smarty_function_gt(array('text' => "Mastercard (3D)"), $this);?>
</label>
                            </td>
                            <td>
                                 <input  <?php if ($this->_tpl_vars['CardsAccepted']['MastercardDebet'] == 'Mastercard-Debet|3d-mastercard-debet-dk.png'): ?>checked<?php endif; ?> id="MastercardDebet" type="checkbox" name="CardsAccepted[MastercardDebet]" value="Mastercard-Debet|3d-mastercard-debet-dk.png">
                        <label for="MastercardDebet"><img class="paycard" src="modules/ZSELEX/images/CreditCards/3d-mastercard-debet-dk.png"><?php echo smarty_function_gt(array('text' => "Mastercard-Debet"), $this);?>
</label>
                            </td>
                            <td>
                                
                       <input <?php if ($this->_tpl_vars['CardsAccepted']['Visa3D'] == 'Visa (3D)|3d-visa.png'): ?>checked<?php endif; ?> id="Visa3D" type="checkbox" name="CardsAccepted[Visa3D]" value="Visa (3D)|3d-visa.png">
                       <label for="Visa3D"><img class="paycard" src="modules/ZSELEX/images/CreditCards/3d-visa.png"><?php echo smarty_function_gt(array('text' => "Visa (3D)"), $this);?>
</label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                 <input <?php if ($this->_tpl_vars['CardsAccepted']['VisaElectron3D'] == 'Visa-Electron (3D)|3d-visa-electron.png'): ?>checked<?php endif; ?> id="Visa-Electron3D" type="checkbox" name="CardsAccepted[VisaElectron3D]" value="Visa-Electron (3D)|3d-visa-electron.png">
                        <label for="Visa-Electron3D"><img class="paycard" src="modules/ZSELEX/images/CreditCards/3d-visa-electron.png"><?php echo smarty_function_gt(array('text' => "Visa-Electron (3D)"), $this);?>
</label>
                            </td>
                            <td>
                                   <input <?php if ($this->_tpl_vars['CardsAccepted']['JCB3D'] == 'JCB (3D)|3d-jcb.png'): ?>checked<?php endif; ?>  id="JCB3D" type="checkbox" name="CardsAccepted[JCB3D]" value="JCB (3D)|3d-jcb.png">
                        <label for="JCB3D"><img class="paycard" src="modules/ZSELEX/images/CreditCards/3d-jcb.png"><?php echo smarty_function_gt(array('text' => "JCB (3D)"), $this);?>
</label>
                            </td>
                            <td>
                                <input <?php if ($this->_tpl_vars['CardsAccepted']['LICMASTERCARD'] == 'LIC Mastercard|lic.png'): ?>checked<?php endif; ?> id="LICMASTERCARD" type="checkbox" name="CardsAccepted[LICMASTERCARD]" value="LIC Mastercard|lic.png">
                        <label for="LICMASTERCARD"><img class="paycard" src="modules/ZSELEX/images/CreditCards/lic.png"><?php echo smarty_function_gt(array('text' => 'LIC Mastercard'), $this);?>
</label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                 <input <?php if ($this->_tpl_vars['CardsAccepted']['paii'] == 'Paii|paii.png'): ?>checked<?php endif; ?> id="paii" type="checkbox" name="CardsAccepted[paii]" value="Paii|paii.png">
                        <label for="paii"><img class="paycard" src="modules/ZSELEX/images/CreditCards/paii.png"><?php echo smarty_function_gt(array('text' => 'Paii'), $this);?>
</label>
                            </td>
                            <td>
                                  <input <?php if ($this->_tpl_vars['CardsAccepted']['edankort'] == 'eDankort|edankort.png'): ?>checked<?php endif; ?> id="edankort" type="checkbox" name="CardsAccepted[edankort]" value="eDankort|edankort.png">
                       <label for="edankort"><img class="paycard" src="modules/ZSELEX/images/CreditCards/edankort.png"><?php echo smarty_function_gt(array('text' => 'eDankort'), $this);?>
</label>
                            </td>
                            <td>
                                 <input <?php if ($this->_tpl_vars['CardsAccepted']['mastercard'] == 'Mastercard|mastercard.png'): ?>checked<?php endif; ?>  id="mastercard" type="checkbox" name="CardsAccepted[mastercard]" value="Mastercard|mastercard.png">
                        <label for="mastercard"><img class="paycard" src="modules/ZSELEX/images/CreditCards/mastercard.png"><?php echo smarty_function_gt(array('text' => 'Mastercard'), $this);?>
</label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                  <input <?php if ($this->_tpl_vars['CardsAccepted']['mastercarddebet'] == 'Mastercard-Debet|mastercard-debet-dk.png'): ?>checked<?php endif; ?> id="mastercard-debet" type="checkbox" name="CardsAccepted[mastercarddebet]" value="Mastercard-Debet|mastercard-debet-dk.png">
                         <label for="mastercard-debet"><img class="paycard" src="modules/ZSELEX/images/CreditCards/mastercard-debet-dk.png"><?php echo smarty_function_gt(array('text' => "Mastercard-Debet"), $this);?>
</label>
                            </td>
                             <td>
                                  <input <?php if ($this->_tpl_vars['CardsAccepted']['visa'] == 'Visa|visa.png'): ?>checked<?php endif; ?>  id="visa" type="checkbox" name="CardsAccepted[visa]" value="Visa|visa.png">
                        <label for="visa"><img class="paycard" src="modules/ZSELEX/images/CreditCards/visa.png"><?php echo smarty_function_gt(array('text' => 'Visa'), $this);?>
</label>
                            </td>
                             <td>
                                  <input <?php if ($this->_tpl_vars['CardsAccepted']['visaelectron'] == 'Visa-Electron|visa-electron.png'): ?>checked<?php endif; ?> id="visa-electron" type="checkbox" name="CardsAccepted[visaelectron]" value="Visa-Electron|visa-electron.png">
                        <label for="visa-electron"><img class="paycard" src="modules/ZSELEX/images/CreditCards/visa-electron.png"><?php echo smarty_function_gt(array('text' => "Visa-Electron"), $this);?>
</label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input <?php if ($this->_tpl_vars['CardsAccepted']['JCB'] == 'JCB|jcb.png'): ?>checked<?php endif; ?> id="jcb" type="checkbox" name="CardsAccepted[JCB]" value="JCB|jcb.png">
                       <label for="jcb"><img class="paycard" src="modules/ZSELEX/images/CreditCards/jcb.png"><?php echo smarty_function_gt(array('text' => 'JCB'), $this);?>
</label>
                            </td>
                             <td>
                                 <input <?php if ($this->_tpl_vars['CardsAccepted']['americanexpress'] == 'American Express|american-express.png'): ?>checked<?php endif; ?> id="americanexpress" type="checkbox" name="CardsAccepted[americanexpress]" value="American Express|american-express.png">
                        <label for="americanexpress"><img class="paycard" src="modules/ZSELEX/images/CreditCards/american-express.png"><?php echo smarty_function_gt(array('text' => 'American Express'), $this);?>
</label>
                            </td>
                            
                        </tr>
                    </table>
                         </div>
                </fieldset>




                <?php if ($this->_tpl_vars['modvars']['ZPayment']['Netaxept_enabled_general'] == true): ?>
                <fieldset>
                    <legend id="settingheaders" title="<?php echo smarty_function_gt(array('text' => 'Click here to configure Netaxept'), $this);?>
"><?php echo smarty_function_gt(array('text' => 'Netaxept settings'), $this);?>
</legend>
            <div id="settingheadersdiv" style="display:none">
                    <div class="z-formrow">
                        <label for="Netaxept_enabled"><?php echo smarty_function_gt(array('text' => 'Enable'), $this);?>
</label>
                        <input type="checkbox" value="1" id="Netaxept_enabled" name="formElement[Netaxept_enabled]"<?php if ($this->_tpl_vars['netaxept']['enabled'] == true): ?> checked="checked"<?php endif; ?>/>
                    </div>
                    <div class="z-formrow">
                        <label for="Netaxept_testmode"><?php echo smarty_function_gt(array('text' => 'Test Mode'), $this);?>
</label>
                        <input type="checkbox" value="1" id="Netaxept_testmode" name="formElement[Netaxept_testmode]"<?php if ($this->_tpl_vars['netaxept']['test_mode'] == true): ?> checked="checked"<?php endif; ?>/>
                    </div>
                    <div class="z-formrow">
                        <label for="Netaxept_merchant_id"><?php echo smarty_function_gt(array('text' => 'Merchant ID'), $this);?>
</label>
                        <input type="text" value="<?php echo $this->_tpl_vars['netaxept']['merchant_id']; ?>
" id="Netaxept_merchant_id" name="formElement[Netaxept_merchant_id]"/>
                    </div>
                    <div class="z-formrow">
                        <label for="Netaxept_token"><?php echo smarty_function_gt(array('text' => 'Token'), $this);?>
</label>
                        <input type="text" value="<?php echo $this->_tpl_vars['netaxept']['token']; ?>
" id="Netaxept_token" name="formElement[Netaxept_token]"/>
                    </div>
                    <div class="z-formrow">
                        <label for="Netaxept_testmerchant_id"><?php echo smarty_function_gt(array('text' => 'Test Merchant ID'), $this);?>
</label>
                        <input type="text" value="<?php echo $this->_tpl_vars['netaxept']['test_merchant_id']; ?>
" id="Netaxept_testmerchant_id" name="formElement[Netaxept_testmerchant_id]"/>
                    </div>
                    <div class="z-formrow">
                        <label for="Netaxept_testtoken"><?php echo smarty_function_gt(array('text' => 'Test Token'), $this);?>
</label>
                        <input type="text" value="<?php echo $this->_tpl_vars['netaxept']['test_token']; ?>
" id="Netaxept_testtoken" name="formElement[Netaxept_testtoken]"/>
                    </div>
                    <div class="z-formrow">
                        <label for="Netaxept_returl"><?php echo smarty_function_gt(array('text' => 'Return Url'), $this);?>
</label>
                        <input type="text" value="<?php echo $this->_tpl_vars['netaxept']['return_url']; ?>
" id="Netaxept_returl" name="formElement[Netaxept_returl]"/>
                     <span style="padding-left:154px"  >
                        <i style="color:grey"> 
                            <?php echo smarty_function_gt(array('text' => 'Please enter the URL we should return to when returning from payment gateway. If you leave it blank we will return to'), $this);?>
 <?php echo $this->_tpl_vars['baseurl']; ?>
<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'user','func' => 'shop','shop_id' => $this->_tpl_vars['shop_id']), $this);?>
.<?php echo smarty_function_gt(array('text' => 'If you point a domain name like http://mydomainname.com to your CityPilot shop you should enter that url here.'), $this);?>

                        </i>
                    </span>
                    </div>
			</div>
                </fieldset>
                <?php endif; ?>
                <?php if ($this->_tpl_vars['modvars']['ZPayment']['Paypal_enabled_general'] == true): ?>
                <fieldset>
                    <legend id="settingheaders" title="<?php echo smarty_function_gt(array('text' => 'Click here to configure Paypal'), $this);?>
"><?php echo smarty_function_gt(array('text' => 'Paypal settings'), $this);?>
</legend>
            <div id="settingheadersdiv" style="display:none">
                    <div class="z-formrow">
                        <label for="Paypal_disabled"><?php echo smarty_function_gt(array('text' => 'Enable'), $this);?>
</label>
                        <input type="checkbox" value="1" id="Netaxept_testmode" name="formElement[Paypal_enabled]"<?php if ($this->_tpl_vars['paypal']['enabled'] == true): ?> checked="checked"<?php endif; ?>/>
                    </div>
                    <div class="z-formrow">
                        <label for="Paypal_testmode"><?php echo smarty_function_gt(array('text' => 'Test Mode'), $this);?>
</label>
                        <input type="checkbox" value="1" id="Paypal_testmode" name="formElement[Paypal_testmode]"<?php if ($this->_tpl_vars['paypal']['test_mode'] == true): ?> checked="checked"<?php endif; ?>/>
                    </div>
                    <div class="z-formrow">
                        <label for="Paypal_business_email"><?php echo smarty_function_gt(array('text' => 'Business Email'), $this);?>
</label>
                        <input type="text" value="<?php echo $this->_tpl_vars['paypal']['business_email']; ?>
" id="Paypal_business_email" name="formElement[Paypal_business_email]"/>
                    </div>
			</div>
                </fieldset>
                <?php endif; ?>
                <?php if ($this->_tpl_vars['modvars']['ZPayment']['QuickPay_enabled_general'] == true): ?>
                <fieldset>
                    <legend id="settingheaders" title="<?php echo smarty_function_gt(array('text' => 'Click here to configure QuickPay'), $this);?>
"><?php echo smarty_function_gt(array('text' => 'QuickPay settings'), $this);?>
</legend>
            <div id="settingheadersdiv" style="display:none">
                    <div class="z-formrow">
                        <label for="QuickPay_enabled"><?php echo smarty_function_gt(array('text' => 'Enable'), $this);?>
</label>
                        <input type="checkbox" value="1" id="QuickPay_enabled" name="formElement[QuickPay_enabled]"<?php if ($this->_tpl_vars['quickpay']['enabled'] == true): ?> checked="checked"<?php endif; ?>/>
                    </div>
                                        <div class="z-formrow">
                        <label for="Quickpay_Merchant_ID"><?php echo smarty_function_gt(array('text' => 'Merchant ID'), $this);?>
</label>
                        <input type="text" value="<?php echo $this->_tpl_vars['quickpay']['merchant_id']; ?>
" id="Quickpay_Merchant_ID" name="formElement[quickpay_merchant_id]"/>
                    </div>
                      <div class="z-formrow">
                        <label for="Quickpay_Agreement_ID"><?php echo smarty_function_gt(array('text' => 'Agreement ID'), $this);?>
</label>
                        <input type="text" value="<?php echo $this->_tpl_vars['quickpay']['agreement_id']; ?>
" id="Quickpay_Agreement_ID" name="formElement[quickpay_agreement_id]"/>
                    </div>
                    <div class="z-formrow">
                        <label for="Quickpay_Api_Key"><?php echo smarty_function_gt(array('text' => 'Api Key'), $this);?>
</label>
                        <input type="text" value="<?php echo $this->_tpl_vars['quickpay']['api_key']; ?>
" id="Quickpay_Api_Key" name="formElement[quickpay_api_key]"/>
                    </div>
                                         <div class="z-formrow">
                        <label for="Quickpay_Api_Key"><?php echo smarty_function_gt(array('text' => 'Return Url'), $this);?>
</label>
                        <input type="text" value="<?php echo $this->_tpl_vars['quickpay']['return_url']; ?>
" id="Quickpay_Api_Key" name="formElement[quickpay_return_url]"/>
                          <span style="padding-left:154px"  >
                        <i style="color:grey"> 
                            <?php echo smarty_function_gt(array('text' => 'Please enter the URL we should return to when returning from payment gateway. If you leave it blank we will return to'), $this);?>
 <?php echo $this->_tpl_vars['baseurl']; ?>
<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'user','func' => 'shop','shop_id' => $this->_tpl_vars['shop_id']), $this);?>
.<?php echo smarty_function_gt(array('text' => 'If you point a domain name like http://mydomainname.com to your CityPilot shop you should enter that url here.'), $this);?>

                        </i>
                    </span>
                    </div>
			</div>
                </fieldset>
                <?php endif; ?>
                 <?php if ($this->_tpl_vars['modvars']['ZPayment']['Epay_enabled_general'] == true): ?>
                <fieldset>
                    <legend id="settingheaders" title="<?php echo smarty_function_gt(array('text' => 'Click here to configure ePay'), $this);?>
"><?php echo smarty_function_gt(array('text' => 'ePay settings'), $this);?>
</legend>
            <div id="settingheadersdiv" style="display:none">
                    <div class="z-formrow">
                        <label for="Epay_enabled"><?php echo smarty_function_gt(array('text' => 'Enable'), $this);?>
</label>
                        <input type="checkbox" value="1" id="QuickPay_enabled" name="formElement[Epay_enabled]"<?php if ($this->_tpl_vars['epay']['enabled'] == true): ?> checked="checked"<?php endif; ?>/>
                    </div>
                    <div class="z-formrow">
                        <label for="Epay_testmode"><?php echo smarty_function_gt(array('text' => 'Test Mode'), $this);?>
</label>
                        <input type="checkbox" value="1" id="QuickPay_testmode" name="formElement[Epay_testmode]"<?php if ($this->_tpl_vars['epay']['test_mode'] == true): ?> checked="checked"<?php endif; ?>/>
                    </div>
                    <div class="z-formrow">
                        <label for="Epay_test_merchant_number"><?php echo smarty_function_gt(array('text' => 'ePay Test Merchant Number'), $this);?>
</label>
                         <input type="text" value="<?php echo $this->_tpl_vars['epay']['test_merchant_number']; ?>
" id="test_merchant_number" name="formElement[Epay_test_merchant_number]"/>
                    </div>
                     <div class="z-formrow">
                        <label for="Epay_merchant_number"><?php echo smarty_function_gt(array('text' => 'ePay Merchant Number'), $this);?>
</label>
                        <input type="text" value="<?php echo $this->_tpl_vars['epay']['merchant_number']; ?>
" id="QuickPay_ID" name="formElement[Epay_merchant_number]"/>
                    </div>
                    <div class="z-formrow">
                        <label for="Epay_md5"><?php echo smarty_function_gt(array('text' => 'MD5 Hash'), $this);?>
</label>
                        <input type="text" value="<?php echo $this->_tpl_vars['epay']['md5_hash']; ?>
" id="Epay_md5" name="formElement[Epay_md5]"/>
                    </div>
                   
			</div>
                </fieldset>
                <?php endif; ?>
                <?php if ($this->_tpl_vars['modvars']['ZPayment']['Directpay_enabled_general'] == true): ?>
                <fieldset>
                    <legend id="settingheaders" title="<?php echo smarty_function_gt(array('text' => 'Click here to configure Direct Payment'), $this);?>
"><?php echo smarty_function_gt(array('text' => 'Directpay settings'), $this);?>
</legend>
            <div id="settingheadersdiv" style="display:none">
                    <div class="z-formrow">
                        <label for="Directpay_enabled"><?php echo smarty_function_gt(array('text' => 'Enable'), $this);?>
</label>
                        <input type="checkbox" value="1" id="Directpay_enabled" name="formElement[Directpay_enabled]"<?php if ($this->_tpl_vars['directpay']['enabled'] == true): ?> checked="checked"<?php endif; ?>/>
                    </div>


                    <div class="z-formrow">
                        <label for="Directpay_info"><?php echo smarty_function_gt(array('text' => 'Info'), $this);?>
</label>
                        <textarea name="formElement[Directpay_info]"><?php echo $this->_tpl_vars['directpay']['info']; ?>
</textarea>
                        <span style="padding-left:154px" align="justify">
                            <i style="color:grey"> 
                                <?php echo smarty_function_gt(array('text' => 'Note: Text written in this field will be presented to your customer as a selectable payment method. Here you can write something like: â€œPlease make bank transfer to our account xxxx-xxxxxxxx.We will ship your items when we register your payment.â€�'), $this);?>
 
                            </i>
                        </span>

                    </div>
			</div>
                </fieldset>
                <?php endif; ?> 
                
                
                 <fieldset>
                    <legend id="settingheaders" title="<?php echo smarty_function_gt(array('text' => 'Click here to configure Freight settings'), $this);?>
"><?php echo smarty_function_gt(array('text' => 'Freight settings'), $this);?>
</legend>
            <div id="settingheadersdiv" style="display:none">
                    <div class="z-formrow">
                        <label for="Freight_enabled"><?php echo smarty_function_gt(array('text' => 'Enable'), $this);?>
</label>
                        <input type="checkbox" value="1" id="Freight_enabled" name="formElement[freight_enabled]"<?php if ($this->_tpl_vars['freight']['enabled'] == true): ?> checked="checked"<?php endif; ?>/>
                    </div>

                    <div class="z-formrow">
                        <label for="Directpay_info"><?php echo smarty_function_gt(array('text' => 'Standard Freight Price'), $this);?>
</label>
                        <input type="text" name="formElement[std_freight_price]" value="<?php echo $this->_tpl_vars['freight']['std_freight_price']; ?>
">
                     </div>
                          
                    <div class="z-formrow">
                        <label for="Directpay_info"><?php echo smarty_function_gt(array('text' => '0-Freight amount:'), $this);?>
</label>
                        <input type="text" name="formElement[zero_freight_price]" value="<?php echo $this->_tpl_vars['freight']['zero_freight_price']; ?>
">
                     </div>
	   </div>
                </fieldset>

                <?php endif; ?>   
                <fieldset>
                     <div class="z-formrow">
                        <label for="delivery_time"><?php echo smarty_function_gt(array('text' => 'Delivery Time:'), $this);?>
</label>
                        <input type="text" name="formElement[delivery_time]" value="<?php echo $this->_tpl_vars['shop_info']['delivery_time']; ?>
">
                          <span style="padding-left:154px" align="justify">
                        <i style="color:grey"> 
                            <?php echo smarty_function_gt(array('text' => 'Note: If this field is not filled out standard delivery time is 3-5 business days.'), $this);?>
 
                        </i>
                    </span>
                     </div>
                <div class="z-formrow">
                     <label for="no_payment"><?php echo smarty_function_gt(array('text' => 'No payment'), $this);?>
</label>
                    <input type="checkbox" value="1" id="no_payment" name="formElement[no_payment]"<?php if ($this->_tpl_vars['shop_details']['no_payment'] == true): ?> checked="checked"<?php endif; ?>/>
                           <span style="padding-left:154px" align="justify">
                        <i style="color:grey"> 
                            <?php echo smarty_function_gt(array('text' => 'Note: When this option is selected, your visitors will not be able to buy from your shop. It gives you the option to promote your products, however with no online purchase option.'), $this);?>
 
                        </i>
                    </span>
                </div>
                        </fieldset>


                <div class="z-buttons z-formbuttons">
                    <?php echo smarty_function_button(array('src' => "button_ok.png",'set' => "icons/extrasmall",'alt' => ((is_array($_tmp='Save')) ? $this->_run_mod_handler('gt', true, $_tmp, $this->_tpl_vars['zikula_view']) : smarty_modifier_gt($_tmp, $this->_tpl_vars['zikula_view'])),'title' => ((is_array($_tmp='Save')) ? $this->_run_mod_handler('gt', true, $_tmp, $this->_tpl_vars['zikula_view']) : smarty_modifier_gt($_tmp, $this->_tpl_vars['zikula_view'])),'text' => ((is_array($_tmp='Save')) ? $this->_run_mod_handler('gt', true, $_tmp, $this->_tpl_vars['zikula_view']) : smarty_modifier_gt($_tmp, $this->_tpl_vars['zikula_view'])),'name' => ((is_array($_tmp='action')) ? $this->_run_mod_handler('gt', true, $_tmp, $this->_tpl_vars['zikula_view']) : smarty_modifier_gt($_tmp, $this->_tpl_vars['zikula_view'])),'value' => ((is_array($_tmp='savepayments')) ? $this->_run_mod_handler('gt', true, $_tmp, $this->_tpl_vars['zikula_view']) : smarty_modifier_gt($_tmp, $this->_tpl_vars['zikula_view']))), $this);?>

                    <a href="<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'user','func' => 'site','shop_id' => $_REQUEST['shop_id']), $this);?>
" title="<?php echo smarty_function_gt(array('text' => 'Cancel'), $this);?>
"><?php echo smarty_function_img(array('modname' => 'core','src' => "button_cancel.png",'set' => "icons/extrasmall",'alt' => ((is_array($_tmp='Cancel')) ? $this->_run_mod_handler('gt', true, $_tmp, $this->_tpl_vars['zikula_view']) : smarty_modifier_gt($_tmp, $this->_tpl_vars['zikula_view'])),'title' => ((is_array($_tmp='Cancel')) ? $this->_run_mod_handler('gt', true, $_tmp, $this->_tpl_vars['zikula_view']) : smarty_modifier_gt($_tmp, $this->_tpl_vars['zikula_view']))), $this);?>
 <?php echo smarty_function_gt(array('text' => 'Cancel'), $this);?>
</a>
                </div>

            </div>

        </fieldset>   
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_securityutil_checkpermission_block($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

        <fieldset>
            <input type="hidden" name="formElement[shop_id]" value="<?php echo $_REQUEST['shop_id']; ?>
" />
            <legend id="settingheaders" title="<?php echo smarty_function_gt(array('text' => 'Configure Terms and condition text'), $this);?>
"><?php echo smarty_function_gt(array('text' => 'Terms and conditions'), $this);?>
</legend> 
            <div id="settingheadersdiv" style="display:none">

                <fieldset>
                    <legend><?php echo smarty_function_gt(array('text' => 'Info'), $this);?>
</legend>
                    <?php $this->assign('termsConditionInfo', ((is_array($_tmp=$this->_tpl_vars['shop_info']['terms_conditions'])) ? $this->_run_mod_handler('unserialize', true, $_tmp) : unserialize($_tmp))); ?> 
                    <?php $_from = $this->_tpl_vars['languages']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['language']):
?>
                    <div class="z-formrow">
                        <label for="terms_conditions"><?php echo smarty_function_gt(array('text' => 'Terms of trade'), $this);?>
(<b><?php echo $this->_tpl_vars['language']; ?>
</b>)</label>
                        <textarea id="terms_conditions" name="formElement[terms_conditions][<?php echo $this->_tpl_vars['language']; ?>
][termsoftrade]"><?php echo ((is_array($_tmp=$this->_tpl_vars['termsConditionInfo'][$this->_tpl_vars['language']]['termsoftrade'])) ? $this->_run_mod_handler('cleantext', true, $_tmp) : smarty_modifier_cleantext($_tmp)); ?>
</textarea>
                    </div>
                    <?php endforeach; endif; unset($_from); ?>
                    <?php $_from = $this->_tpl_vars['languages']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['language']):
?>
                    <div class="z-formrow">
                        <label for="terms_conditions"><?php echo smarty_function_gt(array('text' => 'RMA'), $this);?>
(<b><?php echo $this->_tpl_vars['language']; ?>
</b>)</label>
                        <textarea id="terms_conditions" name="formElement[terms_conditions][<?php echo $this->_tpl_vars['language']; ?>
][rma]"><?php echo ((is_array($_tmp=$this->_tpl_vars['termsConditionInfo'][$this->_tpl_vars['language']]['rma'])) ? $this->_run_mod_handler('cleantext', true, $_tmp) : smarty_modifier_cleantext($_tmp)); ?>
</textarea>
                    </div>
                    <?php endforeach; endif; unset($_from); ?>
                    <?php $_from = $this->_tpl_vars['languages']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['language']):
?>
                    <div class="z-formrow">
                        <label for="terms_conditions"><?php echo smarty_function_gt(array('text' => 'Delivery prices'), $this);?>
(<b><?php echo $this->_tpl_vars['language']; ?>
</b>)</label>
                        <textarea id="terms_conditions" name="formElement[terms_conditions][<?php echo $this->_tpl_vars['language']; ?>
][deliveryprices]"><?php echo ((is_array($_tmp=$this->_tpl_vars['termsConditionInfo'][$this->_tpl_vars['language']]['deliveryprices'])) ? $this->_run_mod_handler('cleantext', true, $_tmp) : smarty_modifier_cleantext($_tmp)); ?>
</textarea>
                    </div>
                    <?php endforeach; endif; unset($_from); ?>
                    <?php $_from = $this->_tpl_vars['languages']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['language']):
?>
                    <div class="z-formrow">
                        <label for="terms_conditions"><?php echo smarty_function_gt(array('text' => 'Delivery time'), $this);?>
(<b><?php echo $this->_tpl_vars['language']; ?>
</b>)</label>
                        <textarea id="terms_conditions" name="formElement[terms_conditions][<?php echo $this->_tpl_vars['language']; ?>
][deliverytime]"><?php echo ((is_array($_tmp=$this->_tpl_vars['termsConditionInfo'][$this->_tpl_vars['language']]['deliverytime'])) ? $this->_run_mod_handler('cleantext', true, $_tmp) : smarty_modifier_cleantext($_tmp)); ?>
</textarea>
                    </div>
                    <?php endforeach; endif; unset($_from); ?>

                    <?php $_from = $this->_tpl_vars['languages']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['language']):
?>
                    <div class="z-formrow">
                        <label for="terms_conditions"><?php echo smarty_function_gt(array('text' => 'Privacy'), $this);?>
(<b><?php echo $this->_tpl_vars['language']; ?>
</b>)</label>
                        <textarea id="terms_conditions" name="formElement[terms_conditions][<?php echo $this->_tpl_vars['language']; ?>
][privacy]"><?php echo ((is_array($_tmp=$this->_tpl_vars['termsConditionInfo'][$this->_tpl_vars['language']]['privacy'])) ? $this->_run_mod_handler('cleantext', true, $_tmp) : smarty_modifier_cleantext($_tmp)); ?>
</textarea>
                    </div>
                    <?php endforeach; endif; unset($_from); ?>
                    <?php $_from = $this->_tpl_vars['languages']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['language']):
?>
                    <div class="z-formrow">
                        <label for="terms_conditions"><?php echo smarty_function_gt(array('text' => 'Secure payment'), $this);?>
(<b><?php echo $this->_tpl_vars['language']; ?>
</b>)</label>
                        <textarea id="terms_conditions" name="formElement[terms_conditions][<?php echo $this->_tpl_vars['language']; ?>
][securepayment]"><?php echo ((is_array($_tmp=$this->_tpl_vars['termsConditionInfo'][$this->_tpl_vars['language']]['securepayment'])) ? $this->_run_mod_handler('cleantext', true, $_tmp) : smarty_modifier_cleantext($_tmp)); ?>
</textarea>
                    </div>
                    <?php endforeach; endif; unset($_from); ?>

                </fieldset>


                <div class="z-buttons z-formbuttons">
                    <?php echo smarty_function_button(array('src' => "button_ok.png",'set' => "icons/extrasmall",'alt' => ((is_array($_tmp='Save')) ? $this->_run_mod_handler('gt', true, $_tmp, $this->_tpl_vars['zikula_view']) : smarty_modifier_gt($_tmp, $this->_tpl_vars['zikula_view'])),'title' => ((is_array($_tmp='Save')) ? $this->_run_mod_handler('gt', true, $_tmp, $this->_tpl_vars['zikula_view']) : smarty_modifier_gt($_tmp, $this->_tpl_vars['zikula_view'])),'text' => ((is_array($_tmp='Save')) ? $this->_run_mod_handler('gt', true, $_tmp, $this->_tpl_vars['zikula_view']) : smarty_modifier_gt($_tmp, $this->_tpl_vars['zikula_view'])),'name' => ((is_array($_tmp='action')) ? $this->_run_mod_handler('gt', true, $_tmp, $this->_tpl_vars['zikula_view']) : smarty_modifier_gt($_tmp, $this->_tpl_vars['zikula_view'])),'value' => ((is_array($_tmp='termsconditions')) ? $this->_run_mod_handler('gt', true, $_tmp, $this->_tpl_vars['zikula_view']) : smarty_modifier_gt($_tmp, $this->_tpl_vars['zikula_view']))), $this);?>

                    <a href="<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'user','func' => 'site','shop_id' => $_REQUEST['shop_id']), $this);?>
" title="<?php echo smarty_function_gt(array('text' => 'Cancel'), $this);?>
"><?php echo smarty_function_img(array('modname' => 'core','src' => "button_cancel.png",'set' => "icons/extrasmall",'alt' => ((is_array($_tmp='Cancel')) ? $this->_run_mod_handler('gt', true, $_tmp, $this->_tpl_vars['zikula_view']) : smarty_modifier_gt($_tmp, $this->_tpl_vars['zikula_view'])),'title' => ((is_array($_tmp='Cancel')) ? $this->_run_mod_handler('gt', true, $_tmp, $this->_tpl_vars['zikula_view']) : smarty_modifier_gt($_tmp, $this->_tpl_vars['zikula_view']))), $this);?>
 <?php echo smarty_function_gt(array('text' => 'Cancel'), $this);?>
</a>
                </div>

            </div>

        </fieldset> 
                
             <fieldset>
            <legend id="settingheaders" title="<?php echo smarty_function_gt(array('text' => 'Statistics'), $this);?>
"><?php echo smarty_function_gt(array('text' => 'Statistics'), $this);?>
</legend> 
            <div id="settingheadersdiv" style="display:none">
                  <div class="z-formrow">
                            <label for="purchase_setting"><?php echo smarty_function_gt(array('text' => 'Collect purchase statistics'), $this);?>
</label>
                            <div><input type="checkbox" value="1" id="purchase_stat" <?php if ($this->_tpl_vars['shop_info']['purchase_collect_stat'] == 1): ?>checked<?php endif; ?>/> </div>
                 </div>
                  <div class="z-formrow">
                            <label for="email_purcahse"><?php echo smarty_function_gt(array('text' => 'Email all purchase tries'), $this);?>
</label>
                            <div><input type="checkbox" value="1" id="email_purchase" <?php if ($this->_tpl_vars['shop_info']['email_purchase_tries'] == 1): ?>checked<?php endif; ?>/> </div>
                 </div>
                  <div class="z-buttons z-formbuttons">
       
       <button onClick="return saveStatistics();" id="stat_save"  type="button"  name="action" value="save" title="<?php echo smarty_function_gt(array('text' => 'Save'), $this);?>
">
             <?php echo smarty_function_img(array('src' => 'button_ok.png','modname' => 'core','set' => 'icons/extrasmall','alt' => ((is_array($_tmp='Save')) ? $this->_run_mod_handler('gt', true, $_tmp, $this->_tpl_vars['zikula_view']) : smarty_modifier_gt($_tmp, $this->_tpl_vars['zikula_view'])),'title' => ((is_array($_tmp='Save')) ? $this->_run_mod_handler('gt', true, $_tmp, $this->_tpl_vars['zikula_view']) : smarty_modifier_gt($_tmp, $this->_tpl_vars['zikula_view']))), $this);?>

             <?php echo smarty_function_gt(array('text' => 'Save'), $this);?>

       </button>
        <button style='display:none' id="stat_save_msg"  type="button"  name="action" value="save" title="<?php echo smarty_function_gt(array('text' => 'Save'), $this);?>
">
                <?php echo smarty_function_gt(array('text' => 'Saving...'), $this);?>

       </button>
       </div>
              
              </div>
               
      </fieldset>

        <div id="show_images_ajax">   
                    </div>

        <a name="aImages"></a>
        <table class="options">
            <tbody>
                <tr>
                    <td>
                        <div id="drop_images"  class="SettingDiv">
                                                        <div id="images_display" style="padding-left:40px">
                                <?php echo smarty_function_modfunc(array('modname' => 'ZSELEX','type' => 'admin','func' => 'loadMiniSiteImages','shop_id' => $_REQUEST['shop_id']), $this);?>

                            </div>  
                        </div>
                        <div class="SettingBottomOrange">
                            <div class="OrageSettingTextL left"><?php echo smarty_function_gt(array('text' => 'Edit This Image'), $this);?>
</div>

                            <div style="padding-right:124px;padding-top:21px" class="OrageSettingTextR right"><?php echo smarty_function_gt(array('text' => 'Drag and Drop Image'), $this);?>
</div>

                        </div>
                        <div id="minisite_images" style="width:500px" class="TopAddFile"></div>

                    </td>

                </tr>
            </tbody>
        </table>
        <a name="aBanner"></a>
          <fieldset>
            <legend id="settingheaders" title="<?php echo smarty_function_gt(array('text' => 'Banner Settings'), $this);?>
"><?php echo smarty_function_gt(array('text' => 'Banner Settings'), $this);?>
</legend> 
            <div id="settingheadersdiv" style="display:none">
                  <div class="z-formrow">
                            <label for="nc"><?php echo smarty_function_gt(array('text' => 'No Change'), $this);?>
</label>
                            <div><input onClick="bannerSetting(this.value)" type="radio" value="0" id="banner_setting" name="formElement[banner_setting]" <?php if ($this->_tpl_vars['image_mode']['image_mode'] == 0): ?>checked<?php endif; ?>/> <?php echo smarty_function_gt(array('text' => "uploaded image is resized to 320 pixel in height and placed on 2048 x 320 pixel canvas."), $this);?>
</div>
                 </div>
                  <div class="z-formrow">
                            <label for="strech"><?php echo smarty_function_gt(array('text' => 'Stretch'), $this);?>
</label>
                            <div><input onClick="bannerSetting(this.value)" type="radio" value="1" id="banner_setting" name="formElement[banner_setting]" <?php if ($this->_tpl_vars['image_mode']['image_mode'] == 1): ?>checked<?php endif; ?>/> <?php echo smarty_function_gt(array('text' => "uploaded image is resized to 2048 pixel width and the center 320 pixel in height is shown."), $this);?>
</div>
                 </div>
                                         <span style="padding-left:154px" align="justify">
                        <i style="color:grey"> 
                            <?php echo smarty_function_gt(array('text' => 'Note: These settings affect uploading of banner.'), $this);?>
 
                        </i>
                    </span>
              </div>
                 <input type="hidden" id="image_mode"  value="<?php echo $this->_tpl_vars['image_mode']['image_mode']; ?>
">
      </fieldset>
             
      
               <?php if ($this->_tpl_vars['banner_perm']): ?>
       <div align="right"><input type="button" value="<?php echo smarty_function_gt(array('text' => 'Crop banner'), $this);?>
" class="ProductPageBtn" title="<?php echo smarty_function_gt(array('text' => 'Crop the uploaded Topbanner image.'), $this);?>
" onClick="cropImage()" style="cursor:pointer;"></div>
        <?php endif; ?>
        <table class="options">
            <tbody>
                <tr>
                    <td>
                        <div id="drop_banner" class="SettingDiv" <?php if ($this->_tpl_vars['announcement_perm']): ?> onClick="editAnnouncement()" style="cursor:pointer;" <?php endif; ?>>
                                                          <div id="load_banner" style="padding-left:40px;text-align:center">
                                <div <?php if ($this->_tpl_vars['announcement_perm']): ?> onClick="editAnnouncement()"  <?php endif; ?>>
                                    <?php $this->assign('banner_img', ((is_array($_tmp=$this->_tpl_vars['minisite_banner']['banner_image'])) ? $this->_run_mod_handler('replace', true, $_tmp, ' ', '%20') : smarty_modifier_replace($_tmp, ' ', '%20'))); ?>
                                    <?php $this->assign('banner_image', "zselexdata/".($this->_tpl_vars['shop_id'])."/banner/resized/".($this->_tpl_vars['banner_img'])); ?>
                                    <?php if (is_file ( $this->_tpl_vars['banner_image'] )): ?>
                                    <?php echo smarty_function_imageproportional(array('image' => $this->_tpl_vars['banner_img'],'path' => ($this->_tpl_vars['baseurl'])."zselexdata/".($this->_tpl_vars['shop_id'])."/banner/resized",'height' => '120','width' => '500'), $this);?>

                                    <img  <?php echo $this->_tpl_vars['imagedimensions']; ?>
 style="cursor:pointer" width="500" height="120" src="zselexdata/<?php echo $this->_tpl_vars['shop_id']; ?>
/banner/resized/<?php echo ((is_array($_tmp=$this->_tpl_vars['minisite_banner']['banner_image'])) ? $this->_run_mod_handler('safetext', true, $_tmp) : smarty_modifier_safetext($_tmp)); ?>
">
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="SettingBottomOrange">
                            <div class="OrageSettingTextL left"><?php echo smarty_function_gt(array('text' => 'Edit This Banner'), $this);?>
</div>

                            <div style="padding-right:124px;padding-top:21px" class="OrageSettingTextR right"><?php echo smarty_function_gt(array('text' => 'Drag and Drop Banner Image'), $this);?>
</div>

                        </div>
                        <div id="minisite_banner" style="width:500px" class="TopAddFile"></div>

                    </td>

                </tr>
            </tbody>
        </table>

        <a name="aEmployees"></a>
        <table class="options">
            <tbody>
                <tr>
                    <td>
                        <div id="drop_employee" class="SettingDiv">
                                                        <div id="employee_display" style="padding-left:40px">
                                <?php echo smarty_function_modfunc(array('modname' => 'ZSELEX','type' => 'admin','func' => 'loadEmployees','shop_id' => $_REQUEST['shop_id']), $this);?>

                            </div>  
                        </div>
                        <div class="SettingBottomOrange">
                            <div class="OrageSettingTextL left"><?php echo smarty_function_gt(array('text' => 'Edit This Employee'), $this);?>
</div>

                            <div style="padding-right:124px;padding-top:21px" class="OrageSettingTextR right"><?php echo smarty_function_gt(array('text' => 'Drag and Drop Employee Image'), $this);?>
</div>

                        </div>
                        <div id="employee_images" style="width:500px" class="TopAddFile"></div>

                    </td>

                </tr>
            </tbody>
        </table>

    </div>
    <div class="z-buttons z-formbuttons">
        <a href="<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'user','func' => 'site','shop_id' => $_REQUEST['shop_id']), $this);?>
" title="<?php echo smarty_function_gt(array('text' => 'Back'), $this);?>
"><?php echo smarty_function_img(array('modname' => 'ZSELEX','src' => "icon_cp_backtoshoplist.png",'alt' => ((is_array($_tmp='Back')) ? $this->_run_mod_handler('gt', true, $_tmp, $this->_tpl_vars['zikula_view']) : smarty_modifier_gt($_tmp, $this->_tpl_vars['zikula_view'])),'title' => ((is_array($_tmp='Back')) ? $this->_run_mod_handler('gt', true, $_tmp, $this->_tpl_vars['zikula_view']) : smarty_modifier_gt($_tmp, $this->_tpl_vars['zikula_view']))), $this);?>
 <?php echo smarty_function_gt(array('text' => 'Back'), $this);?>
</a>
    </div>
</form>



<script type="text/javascript"><?php echo '
'; ?>
<?php if ($this->_tpl_vars['image_perm']): ?><?php echo '
            jQuery(\'#minisite_images\').ajaxupload({
    url:document.location.pnbaseURL + "index.php?module=ZSELEX&type=Dnd&func=upload_images",
            allowExt:[\'jpg\', \'JPG\', \'png\', \'PNG\', \'gif\', \'GIF\'],
            data:"shop_id='; ?>
<?php echo $_REQUEST['shop_id']; ?>
<?php echo '&file_check_folder=fullsize",
            editFilename:true,
            maxFiles:'; ?>
<?php echo $this->_tpl_vars['imagelimit']; ?>
<?php echo ',
            dropArea:\'#drop_images\',
            dropColor: \'red\',
            autoStart: true,
            remotePath:document.getElementById(\'uploadpath\').value + "minisiteimages",
            //form:\'#zselex_bulkaction_product_form\',
            removeOnSuccess:true,
            success:function(files)
    {
    getImages();
           // jQuery(\'li[title="\' + files + \'"]\').remove();
    },
            finish:function(files, filesObj){
    // window.location.href=\'\';
         deleteExtraImages();
    },
            error:function(txt, obj){
    alert(Zikula.__(\'Cannot upload : \' + txt, \'module_zselex_js\'));
            //alert(\'An error occour \'+ txt);
    }

    });
            '; ?>
<?php endif; ?><?php echo '
            	  '; ?>
<?php if ($this->_tpl_vars['banner_perm']): ?><?php echo '
        jQuery(\'#minisite_banner\').ajaxupload({
            url:document.location.pnbaseURL + "index.php?module=ZSELEX&type=Dnd&func=upload_banner",
            allowExt:[\'jpg\', \'JPG\', \'png\', \'PNG\', \'gif\', \'GIF\'],
            data:"shop_id='; ?>
<?php echo $_REQUEST['shop_id']; ?>
<?php echo '",
            editFilename:true,
            maxFiles:'; ?>
<?php echo $this->_tpl_vars['banner_limit']; ?>
<?php echo ',
            dropArea:\'#drop_banner\',
            dropColor: \'red\',
            autoStart: true,
            remotePath:document.getElementById(\'uploadpath\').value + "banner",
            //form:\'#zselex_bulkaction_product_form\',
            removeOnSuccess:true,
            success:function(files)
            {

              getBanner();
            //jQuery(\'li[title="\' + files + \'"]\').remove();
             },
            finish:function(files, filesObj){
    // window.location.href=\'\';
       // if(jQuery(\'#image_mode\').val()==2){
            //alert(\'Crop\');
            //cropImage();
      //  }
            
    },
            error:function(txt, obj){
             alert(Zikula.__(\'Cannot upload : \' + txt, \'module_zselex_js\'));
        }

    });
        '; ?>
<?php endif; ?><?php echo '
        '; ?>
<?php if ($this->_tpl_vars['emp_perm']): ?><?php echo '
            jQuery(\'#employee_images\').ajaxupload({
    url:document.location.pnbaseURL + "index.php?module=ZSELEX&type=Dnd&func=upload_employees",
            allowExt:[\'jpg\', \'JPG\', \'png\', \'PNG\', \'gif\', \'GIF\'],
            data:"shop_id='; ?>
<?php echo $_REQUEST['shop_id']; ?>
<?php echo '&file_check_folder=fullsize",
            editFilename:true,
            maxFiles:'; ?>
<?php echo $this->_tpl_vars['employee_limit']; ?>
<?php echo ',
            dropArea:\'#drop_employee\',
            dropColor: \'red\',
            autoStart: true,
            remotePath:document.getElementById(\'uploadpath\').value + "employees",
            //form:\'#zselex_bulkaction_product_form\',
            removeOnSuccess:true,
            success:function(files)
    {

    getEmployees();
            //jQuery(\'li[title="\' + files + \'"]\').remove();
    },
            finish:function(files, filesObj){
            // window.location.href=\'\';
           //alert(\'uploads completed!\');
            deleteExtraEmployees();
    },
            error:function(txt, obj){
    alert(Zikula.__(\'Cannot upload : \' + txt, \'module_zselex_js\'));
    }

    });
            '; ?>
<?php endif; ?><?php echo '
            // Put anchors into below array which should open panel #0 (the first panel)
            // ~~() converts a boolean value to an integer
            var anchors = [\'#aInformation\', \'#aAddress\', \'#aOpeninghours\'];
            //var anchors = [\'#aInformation\',\'#aAddress\'];
            var panel = new Zikula.UI.Panels(\'panel\', {
    headerSelector: \'#settingheaders\',
            headerClassName: \'z-panel-indicator\'
            // active: [~~(anchors.indexOf(window.location.hash) <= -1)]

    });
            function tts(){
            var panel = new Zikula.UI.Panels(\'panelpop\', {
            headerSelector: \'#popupheader\',
                    headerClassName: \'z-panel-indicator\',
                    active: [0]

            });
            }
'; ?>
</script>


<?php echo smarty_function_adminfooter(array(), $this);?>



<script src="modules/ZSELEX/javascript/jquerycalendar/jquery-ui.js"><?php echo ''; ?>
</script>
<script><?php echo '
    jQuery(function() {
    jQuery("#startdate").datepicker({ dateFormat: "yy-mm-dd", firstDay: \'1\'});
            jQuery("#enddate").datepicker({ dateFormat: "yy-mm-dd", firstDay: \'1\'});
            jQuery(".timepicker").timepicker({ ampm: false, seconds: false });
    });
'; ?>
</script>