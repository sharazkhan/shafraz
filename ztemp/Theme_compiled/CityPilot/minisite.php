<?php /* Smarty version 2.6.28, created on 2017-09-30 14:24:36
         compiled from minisite.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'fileversion', 'minisite.tpl', 2, false),array('function', 'pageaddvar', 'minisite.tpl', 4, false),array('function', 'blockposition', 'minisite.tpl', 10, false),array('function', 'searchbreadcrum', 'minisite.tpl', 13, false),array('function', 'usergroupid', 'minisite.tpl', 44, false),array('function', 'modurl', 'minisite.tpl', 46, false),array('function', 'gt', 'minisite.tpl', 46, false),array('insert', 'getstatusmsg', 'minisite.tpl', 30, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "includes/head.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php echo smarty_function_fileversion(array(), $this);?>

<!--MINISITE PAGE-->
<?php echo smarty_function_pageaddvar(array('name' => 'stylesheet','value' => "themes/CityPilot/style/CityBody.css".($this->_tpl_vars['ver'])), $this);?>

<body>

    <div class="Containter">
                <div id="CityPilotHeader">
            <?php echo smarty_function_blockposition(array('name' => 'citypilotheader'), $this);?>

        </div>
                <?php echo smarty_function_searchbreadcrum(array(), $this);?>

        <div class="BannerBlock">
          
            <div class="Banner">
                                <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "includes/shopname.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                <?php echo smarty_function_blockposition(array('name' => "minisite-banner"), $this);?>

                <?php echo smarty_function_blockposition(array('name' => 'minisite-announcement'), $this);?>

            </div>
         
        </div>
        <div class="BodyBlock">
            <div class="inner Contents">
                 <div style="width:630px"><?php require_once(SMARTY_CORE_DIR . 'core.run_insert_handler.php');
echo smarty_core_run_insert_handler(array('args' => array('name' => 'getstatusmsg')), $this); ?>
</div>
                <div class="ContentLeft left"><!----  Center   ---->
                    <?php if (! empty ( $_REQUEST['shop_id'] )): ?>
                       <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "includes/shopheader.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                     <?php endif; ?>  
                     
                    <?php echo $this->_tpl_vars['maincontent']; ?>

                    <?php echo smarty_function_blockposition(array('name' => 'ministe-images'), $this);?>

                    <?php echo smarty_function_blockposition(array('name' => 'minisite-left'), $this);?>


                </div>
                <div class="ContentRight left"> <!----  Right   ---->
                    <div class="sub_right_inner">
                        
                       <!-- <?php echo smarty_function_usergroupid(array(), $this);?>

                        <?php if (( $this->_tpl_vars['ZXusergroupid'] == $this->_tpl_vars['modvars']['ZSELEX']['shopOwnerGroup'] ) || ( $this->_tpl_vars['ZXusergroupid'] == $this->_tpl_vars['modvars']['ZSELEX']['shopAdminGroup'] ) || ( $this->_tpl_vars['ZXusergroupid'] == 2 )): ?>
                        <a href="<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'admin','func' => 'shopsettings','shop_id' => $_REQUEST['shop_id']), $this);?>
"><img src="modules/ZSELEX/images/F2B_EDIT_button.png" width="220px" title="<?php echo smarty_function_gt(array('text' => 'Edit Shopsettings'), $this);?>
" /></a>
                        <br /><br />
                                                <?php endif; ?>-->
                        <?php echo smarty_function_blockposition(array('name' => 'shopaddress'), $this);?>

                        <div class="sociallinks">
                        <?php echo smarty_function_blockposition(array('name' => 'sociallinks'), $this);?>

                        </div>
                        <div class="ministeEvent">
                            <?php echo smarty_function_blockposition(array('name' => 'minisite-event'), $this);?>

                            <!--  <?php if (( $this->_tpl_vars['ZXusergroupid'] == $this->_tpl_vars['modvars']['ZSELEX']['shopOwnerGroup'] ) || ( $this->_tpl_vars['ZXusergroupid'] == $this->_tpl_vars['modvars']['ZSELEX']['shopAdminGroup'] ) || ( $this->_tpl_vars['ZXusergroupid'] == 2 )): ?>
                            <a href="<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'admin','func' => 'events','shop_id' => $_REQUEST['shop_id']), $this);?>
"><img src="modules/ZSELEX/images/F2B_EDIT_button.png" width="220px" title="<?php echo smarty_function_gt(array('text' => 'Edit Events'), $this);?>
" /></a>
                            <br />
                                                        <?php endif; ?>-->
                        </div>
                        <?php echo smarty_function_blockposition(array('name' => 'minisite-right'), $this);?>

                    </div>        
                </div>
                <div style="clear:both"></div>
            </div>
        </div>

        <div class="ImageBlock">
            <div class="inner">
                <?php echo smarty_function_blockposition(array('name' => 'ZSELEX-minisite-products'), $this);?>


                <!-- Comments -->
                <?php if ($_REQUEST['shop_id'] == '38'): ?>
                <h4>Kommentarer</h4> 
                <div class="Discussion">
                    <div class="DiscSection">
                        <div class="DiscSectionLeft">
                            <h6>    Anne Pedersen - 19. Juni kl. 10.35</h6>
                            <p>Maecenas tempus, tellus eget condimentum rhoncus, sem quam semper libero, sit amet adipiscing sem neque sed ipsum.</p>
                        </div>
                        <div class="DiscSectionRight">
                            <img src="<?php echo $this->_tpl_vars['themepath']; ?>
/images/DownArow.png" class="ShiftRight" />
                            <div class="Comment">
                                <h6>Trine Andersen - 19. Juni kl. 14.05</h6>
                                <p> Maecenas tempus, tellus eget condimentum rhoncus, sem.</p>
                            </div>
                        </div>
                    </div>
                    <div class="DiscSection">
                        <div class="DiscSectionLeft">
                            <h6>    Anne Pedersen - 19. Juni kl. 10.35</h6>
                            <p>Maecenas tempus, tellus eget condimentum rhoncus, sem quam semper libero, sit amet adipiscing sem neque sed ipsum.</p>
                        </div>
                        <div class="DiscSectionRight">
                            <img src="<?php echo $this->_tpl_vars['themepath']; ?>
/images/RightArow.png" class="ShiftRight" />
                            <div class="Comment">
                                <h6>Trine Andersen - 19. Juni kl. 14.05</h6>
                            </div>

                        </div>
                    </div>
                    <div class="DiscSection">
                        <div class="DiscSectionLeft">
                            <h6>    Anne Pedersen - 19. Juni kl. 10.35</h6>
                            <p>Maecenas tempus, tellus eget condimentum rhoncus, sem quam semper libero, sit amet adipiscing sem neque sed ipsum.</p>

                        </div>
                    </div>

                </div>
                <div class="FormDiv">

                    <input type="text" value="Dit navn" />

                    <textarea rows="10" cols="50">Vi vil gerne høre hvad du har at sige..
                    </textarea>

                    <input type="button" value="Send" />

                </div>
                <?php endif; ?>
                <!-- -->

            </div>
        </div>
    </div>
        <div id="CityPilotFotter">
        <?php echo smarty_function_blockposition(array('name' => 'citypilotfooter'), $this);?>

    </div>
    </body>
</html>
