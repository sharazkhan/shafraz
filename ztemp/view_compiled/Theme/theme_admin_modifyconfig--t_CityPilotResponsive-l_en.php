<?php /* Smarty version 2.6.28, created on 2017-10-10 23:45:12
         compiled from theme_admin_modifyconfig.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'adminheader', 'theme_admin_modifyconfig.tpl', 1, false),array('function', 'ajaxheader', 'theme_admin_modifyconfig.tpl', 2, false),array('function', 'icon', 'theme_admin_modifyconfig.tpl', 5, false),array('function', 'gt', 'theme_admin_modifyconfig.tpl', 6, false),array('function', 'modurl', 'theme_admin_modifyconfig.tpl', 9, false),array('function', 'button', 'theme_admin_modifyconfig.tpl', 165, false),array('function', 'img', 'theme_admin_modifyconfig.tpl', 166, false),array('function', 'adminfooter', 'theme_admin_modifyconfig.tpl', 170, false),array('insert', 'csrftoken', 'theme_admin_modifyconfig.tpl', 11, false),array('modifier', 'safetext', 'theme_admin_modifyconfig.tpl', 16, false),array('modifier', 'gt', 'theme_admin_modifyconfig.tpl', 165, false),)), $this); ?>
<?php echo smarty_function_adminheader(array(), $this);?>

<?php echo smarty_function_ajaxheader(array('modname' => 'Theme','filename' => "theme_admin_modifyconfig.js",'noscriptaculous' => true,'effects' => true), $this);?>


<div class="z-admin-content-pagetitle">
    <?php echo smarty_function_icon(array('type' => 'config','size' => 'small'), $this);?>

    <h3><?php echo smarty_function_gt(array('text' => 'Settings'), $this);?>
</h3>
</div>

<form class="z-form" action="<?php echo smarty_function_modurl(array('modname' => 'Theme','type' => 'admin','func' => 'updateconfig'), $this);?>
" method="post" enctype="application/x-www-form-urlencoded">
    <div>
        <input type="hidden" name="csrftoken" value="<?php require_once(SMARTY_CORE_DIR . 'core.run_insert_handler.php');
echo smarty_core_run_insert_handler(array('args' => array('name' => 'csrftoken')), $this); ?>
" />
        <fieldset>
            <legend><?php echo smarty_function_gt(array('text' => 'General settings'), $this);?>
</legend>
            <div class="z-formrow">
                <label for="themeswitcher_itemsperpage"><?php echo smarty_function_gt(array('text' => 'Items per page'), $this);?>
</label>
                <input id="themeswitcher_itemsperpage" type="text" name="itemsperpage" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['itemsperpage'])) ? $this->_run_mod_handler('safetext', true, $_tmp) : smarty_modifier_safetext($_tmp)); ?>
" size="4" maxlength="4" tabindex="2" />
            </div>
            <div class="z-formrow">
                <label for="theme_change"><?php echo smarty_function_gt(array('text' => 'Allow users to change themes'), $this);?>
</label>
                <input id="theme_change" name="theme_change" type="checkbox" value="1" <?php if ($this->_tpl_vars['theme_change']): ?>checked="checked"<?php endif; ?> />
            </div>
            <div class="z-formrow">
                <label for="enable_mobile_theme"><?php echo smarty_function_gt(array('text' => 'Enable mobile theme'), $this);?>
</label>
                <input id="enable_mobile_theme" name="enable_mobile_theme" type="checkbox" value="1" <?php if ($this->_tpl_vars['enable_mobile_theme']): ?>checked="checked"<?php endif; ?> />
            </div>
        </fieldset>
        <fieldset>
            <legend><?php echo smarty_function_gt(array('text' => 'Compilation'), $this);?>
</legend>
            <div class="z-formrow">
                <label for="theme_compile_check"><?php echo smarty_function_gt(array('text' => 'Check for updated version of theme templates'), $this);?>
</label>
                <input id="theme_compile_check" name="compile_check" type="checkbox" value="1" <?php if ($this->_tpl_vars['compile_check'] == 1): ?>checked="checked"<?php endif; ?> />
            </div>
            <div class="z-formrow">
                <label for="theme_force_compile"><?php echo smarty_function_gt(array('text' => "Force re-compilation of theme templates"), $this);?>
</label>
                <input id="theme_force_compile" name="force_compile" type="checkbox" value="1" <?php if ($this->_tpl_vars['force_compile'] == 1): ?>checked="checked"<?php endif; ?> />
                <a class="z-indented" href="<?php echo smarty_function_modurl(array('modname' => 'Theme','type' => 'admin','func' => 'clear_compiled','csrftoken' => $this->_tpl_vars['csrftoken']), $this);?>
"><?php echo smarty_function_gt(array('text' => 'Delete compiled theme templates'), $this);?>
</a>
            </div>
            <div class="z-formrow">
                <label for="render_compile_dir"><?php echo smarty_function_gt(array('text' => 'Compiled render templates directory'), $this);?>
</label>
                <span id="render_compile_dir"><em><?php echo $this->_reg_objects['render'][0]->compile_dir;?>
</em></span>
            </div>
            <div class="z-formrow">
                <label for="render_compile_check"><?php echo smarty_function_gt(array('text' => 'Check for updated version of render templates'), $this);?>
</label>
                <input id="render_compile_check" type="checkbox" name="render_compile_check" value="1"<?php if ($this->_tpl_vars['render_compile_check']): ?>checked="checked"<?php endif; ?> />
            </div>
            <div class="z-formrow">
                <label for="render_force_compile"><?php echo smarty_function_gt(array('text' => "Force re-compilation of render templates"), $this);?>
</label>
                <input id="render_force_compile" type="checkbox" name="render_force_compile" value="1"<?php if ($this->_tpl_vars['render_force_compile']): ?>checked="checked"<?php endif; ?> />
                <a class="z-indented" href="<?php echo smarty_function_modurl(array('modname' => 'Theme','type' => 'admin','func' => 'render_clear_compiled','csrftoken' => $this->_tpl_vars['csrftoken']), $this);?>
"><?php echo smarty_function_gt(array('text' => 'Delete compiled render templates'), $this);?>
</a>
            </div>
        </fieldset>
        <fieldset>
            <legend><?php echo smarty_function_gt(array('text' => 'Caching'), $this);?>
</legend>
            <div class="z-formrow">
                <label for="enablecache"><?php echo smarty_function_gt(array('text' => 'Enable theme caching'), $this);?>
</label>
                <input id="enablecache" name="enablecache" type="checkbox" value="1" <?php if ($this->_tpl_vars['enablecache'] == 1): ?>checked="checked"<?php endif; ?> />
                <a class="z-indented" href="<?php echo smarty_function_modurl(array('modname' => 'Theme','type' => 'admin','func' => 'clear_cache','csrftoken' => $this->_tpl_vars['csrftoken']), $this);?>
"><?php echo smarty_function_gt(array('text' => 'Delete cached theme pages'), $this);?>
</a>
            </div>
            <div id="theme_caching">
                <div class="z-formrow">
                    <label for="cache_lifetime"><?php echo smarty_function_gt(array('text' => 'Length of time to keep cached theme pages'), $this);?>
</label>
                    <p class="z-formnote z-informationmsg"><?php echo smarty_function_gt(array('text' => "Notice: A cache lifetime of 0 will set the cache to continually regenerate; this is equivalent to no caching."), $this);?>
<br /><?php echo smarty_function_gt(array('text' => "A cache lifetime of -1 will set the cache output to never expire."), $this);?>
</p>
                    <label for="cache_lifetime"><?php echo smarty_function_gt(array('text' => 'For homepage'), $this);?>
</label>
                    <span>
                        <input type="text" name="cache_lifetime" id="cache_lifetime" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['cache_lifetime'])) ? $this->_run_mod_handler('safetext', true, $_tmp) : smarty_modifier_safetext($_tmp)); ?>
" size="6" tabindex="2" />
                        <?php echo smarty_function_gt(array('text' => 'seconds'), $this);?>

                        <a class="z-indented" href="<?php echo smarty_function_modurl(array('modname' => 'Theme','type' => 'admin','func' => 'clear_cache','cacheid' => 'homepage','csrftoken' => $this->_tpl_vars['csrftoken']), $this);?>
"><?php echo smarty_function_gt(array('text' => 'Delete cached pages'), $this);?>
</a>
                    </span>
                </div>
                <div class="z-formrow">
                    <label for="cache_lifetime_mods"><?php echo smarty_function_gt(array('text' => 'For modules'), $this);?>
</label>
                    <span>
                        <input type="text" name="cache_lifetime_mods" id="cache_lifetime_mods" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['cache_lifetime_mods'])) ? $this->_run_mod_handler('safetext', true, $_tmp) : smarty_modifier_safetext($_tmp)); ?>
" size="6" tabindex="2" />
                        <?php echo smarty_function_gt(array('text' => 'seconds'), $this);?>

                    </span>

                </div>
                <div class="z-formrow">
                    <label for="theme_nocache"><?php echo smarty_function_gt(array('text' => 'Modules to exclude from theme caching'), $this);?>
</label>
                    <div id="theme_nocache">
                        <?php $_from = $this->_tpl_vars['mods']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['modname'] => $this->_tpl_vars['moddisplayname']):
?>
                        <div class="z-formlist">
                            <input id="theme_nocache_<?php echo ((is_array($_tmp=$this->_tpl_vars['modname'])) ? $this->_run_mod_handler('safetext', true, $_tmp) : smarty_modifier_safetext($_tmp)); ?>
" type="checkbox" name="modulesnocache[]" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['modname'])) ? $this->_run_mod_handler('safetext', true, $_tmp) : smarty_modifier_safetext($_tmp)); ?>
"<?php if (isset ( $this->_tpl_vars['modulesnocache'][$this->_tpl_vars['modname']] )): ?> checked="checked"<?php endif; ?> />
                            <label for="theme_nocache_<?php echo ((is_array($_tmp=$this->_tpl_vars['modname'])) ? $this->_run_mod_handler('safetext', true, $_tmp) : smarty_modifier_safetext($_tmp)); ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['moddisplayname'])) ? $this->_run_mod_handler('safetext', true, $_tmp) : smarty_modifier_safetext($_tmp)); ?>
</label>
                            <a class="z-indented" href="<?php echo smarty_function_modurl(array('modname' => 'Theme','type' => 'admin','func' => 'clear_cache','cacheid' => $this->_tpl_vars['modname'],'csrftoken' => $this->_tpl_vars['csrftoken']), $this);?>
"><?php echo smarty_function_gt(array('text' => 'Delete cached pages'), $this);?>
</a>
                        </div>
                        <?php endforeach; endif; unset($_from); ?>
                    </div>
                </div>
            </div>
            <div class="z-formrow">
                <label for="render_cache_dir"><?php echo smarty_function_gt(array('text' => 'Cached templates directory'), $this);?>
</label>
                <span id="render_cache_dir"><em><?php echo $this->_reg_objects['render'][0]->cache_dir;?>
</em></span>
            </div>
            <div class="z-formrow">
                <label for="render_cache"><?php echo smarty_function_gt(array('text' => 'Enable render caching'), $this);?>
</label>
                <input id="render_cache" type="checkbox" name="render_cache" value="1"<?php if ($this->_tpl_vars['render_cache']): ?>checked="checked"<?php endif; ?> />
                <a class="z-indented" href="<?php echo smarty_function_modurl(array('modname' => 'Theme','type' => 'admin','func' => 'render_clear_cache','csrftoken' => $this->_tpl_vars['csrftoken']), $this);?>
"><?php echo smarty_function_gt(array('text' => 'Delete cached render pages'), $this);?>
</a>
            </div>
            <div id="render_lifetime_container">
                <div class="z-formrow">
                    <label for="render_lifetime"><?php echo smarty_function_gt(array('text' => 'Length of time to keep cached render pages'), $this);?>
</label>
                    <span>
                        <input id="render_lifetime" type="text" name="render_lifetime" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['render_lifetime'])) ? $this->_run_mod_handler('safetext', true, $_tmp) : smarty_modifier_safetext($_tmp)); ?>
" size="6" />
                        <?php echo smarty_function_gt(array('text' => 'seconds'), $this);?>

                    </span>
                    <p class="z-formnote z-informationmsg"><?php echo smarty_function_gt(array('text' => "Notice: A cache lifetime of 0 will set the cache to continually regenerate; this is equivalent to no caching."), $this);?>
<br /><?php echo smarty_function_gt(array('text' => "Notice: A cache lifetime of -1 will set the cache output to never expire."), $this);?>
</p>
                </div>
            </div>
        </fieldset>
        <fieldset>
            <legend><?php echo smarty_function_gt(array('text' => "CSS/JS optimisation"), $this);?>
</legend>
            <p class="z-formnote z-informationmsg"><?php echo smarty_function_gt(array('text' => "Notice: Combining and compressing JavaScript (JS) and CSS can considerably speed-up the performances of your site."), $this);?>
</p>
            <div class="z-formrow">
                <label for="cssjscombine"><?php echo smarty_function_gt(array('text' => "Enable CSS/JS combination"), $this);?>
</label>
                <input id="cssjscombine" name="cssjscombine" type="checkbox" value="1" <?php if ($this->_tpl_vars['cssjscombine'] == 1): ?>checked="checked"<?php endif; ?> />
                <a class="z-indented" href="<?php echo smarty_function_modurl(array('modname' => 'Theme','type' => 'admin','func' => 'clear_cssjscombinecache','csrftoken' => $this->_tpl_vars['csrftoken']), $this);?>
"><?php echo smarty_function_gt(array('text' => 'Delete combination cache'), $this);?>
</a>
            </div>
            <div id="theme_cssjscombine">
                <div class="z-formrow">
                    <label for="cssjscompress"><?php echo smarty_function_gt(array('text' => 'Use GZ compression'), $this);?>
</label>
                    <input id="cssjscompress" name="cssjscompress" type="checkbox" value="1" <?php if ($this->_tpl_vars['cssjscompress'] == 1): ?>checked="checked"<?php endif; ?> />
                </div>
                <div class="z-formrow">
                    <label for="cssjsminify"><?php echo smarty_function_gt(array('text' => 'Minify CSS'), $this);?>
</label>
                    <input id="cssjsminify" name="cssjsminify" type="checkbox" value="1" <?php if ($this->_tpl_vars['cssjsminify'] == 1): ?>checked="checked"<?php endif; ?> />
                    <div id="theme_cssjsminify">
                        <p class="z-warningmsg z-formnote"><?php echo smarty_function_gt(array('text' => "The 'Minify CSS' option may require more PHP memory. If errors occur, you should increase the 'memory_limit' setting in your PHP installation's 'php.ini' configuration file. Alternatively, you should add the following entry to the '.htaccess' file in your site's web root (without the quotation marks): 'php_value memory_limit 64M'. 64M is just a suggested value. You should experiment to find the lowest value that resolves the problem."), $this);?>
</p>
                    </div>
                </div>
                <div class="z-formrow">
                    <label for="cssjscombine_lifetime"><?php echo smarty_function_gt(array('text' => 'Length of time to keep combination cache'), $this);?>
</label>
                    <span>
                        <input type="text" name="cssjscombine_lifetime" id="cssjscombine_lifetime" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['cssjscombine_lifetime'])) ? $this->_run_mod_handler('safetext', true, $_tmp) : smarty_modifier_safetext($_tmp)); ?>
" size="6" />
                        <?php echo smarty_function_gt(array('text' => 'seconds'), $this);?>

                    </span>
                </div>
            </div>
        </fieldset>
        <fieldset>
            <legend><?php echo smarty_function_gt(array('text' => 'Themes configurations'), $this);?>
</legend>
            <p class="z-formnote z-informationmsg"><?php echo smarty_function_gt(array('text' => "Notice: When edit the configuration of a Theme, the Theme Engine creates copies of its configuration files inside the Temporary folder when it cannot write on them directly. If you changed your mind and want to have your configuration inside your theme, make its .ini files writable and clear the temporary copies with the following link."), $this);?>
</p>
            <div class="z-formrow">
                <a class="z-formnote" href="<?php echo smarty_function_modurl(array('modname' => 'Theme','type' => 'admin','func' => 'clear_config','csrftoken' => $this->_tpl_vars['csrftoken']), $this);?>
"><?php echo smarty_function_gt(array('text' => 'Delete theme configurations'), $this);?>
</a>
            </div>
        </fieldset>
        <fieldset>
            <legend><?php echo smarty_function_gt(array('text' => 'Filters'), $this);?>
</legend>
            <p class="z-formnote z-informationmsg"><?php echo smarty_function_gt(array('text' => "Notice: The 'trimwhitespace' output filter trims leading white space and blank lines from the template source code after it is interpreted, which cleans-up the code and saves bandwidth."), $this);?>
</p>
            <div class="z-formrow">
                <label for="trimwhitespace"><?php echo smarty_function_gt(array('text' => "Use 'trimwhitespace' output filter"), $this);?>
</label>
                <input id="trimwhitespace" name="trimwhitespace" type="checkbox" value="1" <?php if ($this->_tpl_vars['trimwhitespace'] == 1): ?>checked="checked"<?php endif; ?> />
            </div>
        </fieldset>
        <fieldset>
            <legend><?php echo smarty_function_gt(array('text' => 'Debug settings'), $this);?>
</legend>
            <div class="z-formrow">
                <label for="render_expose_template"><?php echo smarty_function_gt(array('text' => 'Embed template information within comments inside the source code of pages'), $this);?>
</label>
                <input id="render_expose_template" type="checkbox" name="render_expose_template" value="1"<?php if ($this->_tpl_vars['render_expose_template']): ?>checked="checked"<?php endif; ?> />
                <p class="z-warningmsg z-formnote"><?php echo smarty_function_gt(array('text' => "Warning! When auxiliary themes like RSS are used, enabling this option can corrupt the page output until you disable it again (for instance, with RSS, the feed will be broken)."), $this);?>
</p>
            </div>
        </fieldset>

        <div class="z-buttons z-formbuttons">
            <?php echo smarty_function_button(array('src' => "button_ok.png",'set' => "icons/extrasmall",'alt' => ((is_array($_tmp='Save')) ? $this->_run_mod_handler('gt', true, $_tmp, $this->_tpl_vars['zikula_view']) : smarty_modifier_gt($_tmp, $this->_tpl_vars['zikula_view'])),'title' => ((is_array($_tmp='Save')) ? $this->_run_mod_handler('gt', true, $_tmp, $this->_tpl_vars['zikula_view']) : smarty_modifier_gt($_tmp, $this->_tpl_vars['zikula_view'])),'text' => ((is_array($_tmp='Save')) ? $this->_run_mod_handler('gt', true, $_tmp, $this->_tpl_vars['zikula_view']) : smarty_modifier_gt($_tmp, $this->_tpl_vars['zikula_view']))), $this);?>

            <a href="<?php echo smarty_function_modurl(array('modname' => 'Theme','type' => 'admin','func' => 'view'), $this);?>
" title="<?php echo smarty_function_gt(array('text' => 'Cancel'), $this);?>
"><?php echo smarty_function_img(array('modname' => 'core','src' => "button_cancel.png",'set' => "icons/extrasmall",'alt' => ((is_array($_tmp='Cancel')) ? $this->_run_mod_handler('gt', true, $_tmp, $this->_tpl_vars['zikula_view']) : smarty_modifier_gt($_tmp, $this->_tpl_vars['zikula_view'])),'title' => ((is_array($_tmp='Cancel')) ? $this->_run_mod_handler('gt', true, $_tmp, $this->_tpl_vars['zikula_view']) : smarty_modifier_gt($_tmp, $this->_tpl_vars['zikula_view']))), $this);?>
 <?php echo smarty_function_gt(array('text' => 'Cancel'), $this);?>
</a>
        </div>
    </div>
</form>
<?php echo smarty_function_adminfooter(array(), $this);?>