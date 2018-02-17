<?php /* Smarty version 2.6.28, created on 2017-10-02 20:36:23
         compiled from master.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'nocache', 'master.tpl', 1, false),array('function', 'charset', 'master.tpl', 3, false),array('function', 'getcurrenturl', 'master.tpl', 7, false),array('function', 'pagegetvar', 'master.tpl', 8, false),array('function', 'id', 'master.tpl', 10, false),array('function', 'updated', 'master.tpl', 11, false),)), $this); ?>
<?php $this->_cache_serials['ztemp/Theme_compiled/Atom2/master.inc'] = '7894889ecd45e3b96c3f870830a7e536'; ?><?php if ($this->caching && !$this->_cache_including): echo '{nocache:7894889ecd45e3b96c3f870830a7e536#0}'; endif;$this->_tag_stack[] = array('nocache', array()); $_block_repeat=true;Zikula_View_Resource::block_nocache($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?><?php header("Content-type: application/atom+xml"); ?><?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo Zikula_View_Resource::block_nocache($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); if ($this->caching && !$this->_cache_including): echo '{/nocache:7894889ecd45e3b96c3f870830a7e536#0}'; endif;?>
<?php echo '<?xml'; ?>
 version="1.0" encoding="<?php echo smarty_function_charset(array(), $this);?>
"<?php echo '?>'; ?>

<feed xmlns="http://www.w3.org/2005/Atom">
    <link rel="alternate" type="text/html" href="<?php echo $this->_tpl_vars['baseurl']; ?>
" />
    <link rel="self" type="application/atom+xml" href="<?php echo smarty_function_getcurrenturl(array(), $this);?>
" />
    <title><?php echo smarty_function_pagegetvar(array('name' => 'title'), $this);?>
</title>
    <subtitle><?php echo $this->_tpl_vars['modvars']['ZConfig']['slogan']; ?>
</subtitle>
    <id><?php echo smarty_function_id(array(), $this);?>
</id>
    <updated><?php echo smarty_function_updated(array(), $this);?>
</updated>
    <author>
        <name><?php echo $this->_tpl_vars['modvars']['ZConfig']['adminmail']; ?>
</name>
    </author>
    <generator><?php echo $this->_tpl_vars['modvars']['ZConfig']['Version_ID']; ?>
</generator>
    <rights>Copyright <?php echo $this->_tpl_vars['modvars']['ZConfig']['sitename']; ?>
</rights>
    <?php echo $this->_tpl_vars['maincontent']; ?>

</feed>