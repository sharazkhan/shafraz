<?php /* Smarty version 2.6.28, created on 2017-09-30 14:24:15
         compiled from home.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'fileversion', 'home.tpl', 2, false),array('function', 'pageaddvar', 'home.tpl', 3, false),array('function', 'blockposition', 'home.tpl', 8, false),array('function', 'searchbreadcrum', 'home.tpl', 12, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "includes/head.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php echo smarty_function_fileversion(array(), $this);?>

<?php echo smarty_function_pageaddvar(array('name' => 'stylesheet','value' => "themes/CityPilot/style/CityBody.css".($this->_tpl_vars['ver'])), $this);?>


<body id="CityPilotBody" >
         <div id="CityPilotHeader">
    <?php echo smarty_function_blockposition(array('name' => 'citypilotheader'), $this);?>

    </div>
         <div class="Containter">
           <?php echo smarty_function_searchbreadcrum(array(), $this);?>

                 <div class="BannerBlock">
            <div class="inner">
                
                                  <?php echo smarty_function_blockposition(array('name' => 'exclusive_events'), $this);?>

                
            </div>
         </div>

        <div class="BodyBlock">
            <div class="inner Contents">
                <div class="ContentLeft left left_home">
                                                         <?php echo smarty_function_blockposition(array('name' => 'specialdeals'), $this);?>

                   <?php echo smarty_function_blockposition(array('name' => "center-home"), $this);?>

                 </div>
                <div class="ContentRight left right_home">
                    
                       <?php echo smarty_function_blockposition(array('name' => 'product-ad'), $this);?>

                       <?php echo smarty_function_blockposition(array('name' => 'upcomming-events'), $this);?>

                       <?php echo smarty_function_blockposition(array('name' => "right-home"), $this);?>

                      
                                          
                </div>
            </div>
        </div>

        <div class="ImageBlock">
            <div class="inner">
                <div class="ImageSection">
                    <!---- full width  ----->
                </div>
                 <?php echo smarty_function_blockposition(array('name' => 'newshops'), $this);?>

            </div>
        </div>

       
    </div>
         <div id="CityPilotFotter">
               <?php echo smarty_function_blockposition(array('name' => 'citypilotfooter'), $this);?>

    </div>
    
     </body>
</html>