<?php /* Smarty version 2.6.28, created on 2017-09-30 14:23:40
         compiled from master.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'fileversion', 'master.tpl', 2, false),array('function', 'pageaddvar', 'master.tpl', 9, false),array('function', 'blockposition', 'master.tpl', 14, false),array('function', 'searchbreadcrum', 'master.tpl', 20, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "includes/head.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php echo smarty_function_fileversion(array(), $this);?>

<script><?php echo '
 document.observe("dom:loaded", function(){
  //alert(\'The DOM is loaded!\');
 // displayBlocks();
});
'; ?>
</script>
<?php echo smarty_function_pageaddvar(array('name' => 'stylesheet','value' => "themes/CityPilot/style/CityBody.css".($this->_tpl_vars['ver'])), $this);?>


<body id="body" >
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
                <div class="ContentLeft ContentLeftMaster left">
                      <?php echo $this->_tpl_vars['maincontent']; ?>

                      
                </div>
                <div class="ContentRight right">
                                       <?php echo smarty_function_blockposition(array('name' => 'upcomming-events'), $this);?>

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
</html>