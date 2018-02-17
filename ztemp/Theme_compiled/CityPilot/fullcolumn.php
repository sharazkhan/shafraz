<?php /* Smarty version 2.6.28, created on 2017-09-30 14:23:45
         compiled from fullcolumn.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'fileversion', 'fullcolumn.tpl', 2, false),array('function', 'pageaddvar', 'fullcolumn.tpl', 3, false),array('function', 'blockposition', 'fullcolumn.tpl', 60, false),array('function', 'searchbreadcrum', 'fullcolumn.tpl', 66, false),array('insert', 'getstatusmsg', 'fullcolumn.tpl', 76, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "includes/head.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php echo smarty_function_fileversion(array(), $this);?>

<?php echo smarty_function_pageaddvar(array('name' => 'stylesheet','value' => "themes/CityPilot/style/CityBody.css".($this->_tpl_vars['ver'])), $this);?>
 

<input type="hidden" id="crrntFunc" value="<?php echo $_REQUEST['func']; ?>
">
<script><?php echo '
    /*
    document.observe("dom:loaded", function(){
    //searchBreadcrums();
    if (jQuery(\'div.z-errormsg\').length &&  jQuery(\'#crrntFunc\').val() == \'login\') {
  //alert(\'The DOM is loaded!\');
  
  jQuery(".z-menulinks").addClass("ErrorClass");
  }
    else if (jQuery(\'#crrntFunc\').val() == \'lostpassword\' && !jQuery(\'div.z-errormsg\').length) {
     jQuery(".z-menulinks").addClass("ErrorClass");
    }
    else if(jQuery(\'#crrntFunc\').val() == \'lostpassword\' && jQuery(\'div.z-errormsg\').length){
      jQuery(".z-menulinks").addClass("lostpasswordError");
    }
     
   else if (jQuery(\'#crrntFunc\').val() == \'lostpasswordcode\' && !jQuery(\'div.z-errormsg\').length || jQuery(\'#crrntFunc\').val() == \'lostPasswordCode\' && !jQuery(\'div.z-errormsg\').length ) {
     //alert(\'hellloo\');
     jQuery(".z-menulinks").addClass("lostpasswordcode");
    }
     else if(jQuery(\'#crrntFunc\').val() == \'lostPasswordCode\' && jQuery(\'div.z-errormsg\').length){
      //alert(\'hiii\');
      jQuery(".z-menulinks").addClass("lostPasswordCodeError");
    }
    
    else if (jQuery(\'#crrntFunc\').val() == \'register\') {
      //jQuery(".z-gap").css("padding","0px ,important");
      jQuery(\'.z-gap\').attr(\'style\', \'padding: 0px !important\');
    }
  
  else if (jQuery(\'#crrntFunc\').val() == \'verifyRegistration\' && !jQuery(\'div.z-errormsg\').length) {
      //jQuery(".z-gap").css("padding","0px ,important");
       jQuery(".z-menulinks").addClass("verifyRegistration");
      
    }
    else if (jQuery(\'#crrntFunc\').val() == \'verifyRegistration\' && jQuery(\'div.z-errormsg\').length) {
      //jQuery(".z-gap").css("padding","0px ,important");
       jQuery(".z-menulinks").addClass("verifyRegistrationError");
      
    }
  
});

 */
 
'; ?>
</script>
<?php if ($_REQUEST['func'] == 'verifyRegistration'): ?>
    <style><?php echo '
        .z-menulinks{display:none !important}
    '; ?>
</style>
<?php endif; ?>
<body id="body" >
         <div id="CityPilotHeader">
    <?php echo smarty_function_blockposition(array('name' => 'citypilotheader'), $this);?>

    </div>
         <div class="Containter">
               <?php if ($_REQUEST['module'] != 'Users'): ?>
                    <?php echo smarty_function_searchbreadcrum(array(), $this);?>

                <?php endif; ?>   
         <div class="BannerBlock">
            <div class="inner">
                <!--<img src="<?php echo $this->_tpl_vars['themepath']; ?>
/images/Banner.png" />-->
                
            </div>
        </div>
        <div class="BodyBlock">
            <div class="inner Contents">
                <div style="width:630px"><?php require_once(SMARTY_CORE_DIR . 'core.run_insert_handler.php');
echo smarty_core_run_insert_handler(array('args' => array('name' => 'getstatusmsg')), $this); ?>
</div>
                <div class="Fullwidth">
                      <?php echo $this->_tpl_vars['maincontent']; ?>

                </div>
            </div>
        </div>
        <div class="ImageBlock">
            <div class="inner">
                <div class="ImageSection"></div>
            </div>
        </div>
           </div>
       
            <div id="CityPilotFotter">
               <?php echo smarty_function_blockposition(array('name' => 'citypilotfooter'), $this);?>

    </div>
    
     </body>
</body>
</html>