<?php /* Smarty version 2.6.28, created on 2017-10-02 16:16:42
         compiled from includes/header.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'lang', 'includes/header.tpl', 2, false),array('function', 'langdirection', 'includes/header.tpl', 2, false),array('function', 'charset', 'includes/header.tpl', 4, false),array('function', 'pagegetvar', 'includes/header.tpl', 5, false),array('function', 'pageaddvar', 'includes/header.tpl', 9, false),array('function', 'blockposition', 'includes/header.tpl', 19, false),array('block', 'browserhack', 'includes/header.tpl', 12, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo smarty_function_lang(array(), $this);?>
" dir="<?php echo smarty_function_langdirection(array(), $this);?>
">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=<?php echo smarty_function_charset(array(), $this);?>
" />
        <title><?php echo smarty_function_pagegetvar(array('name' => 'title'), $this);?>
</title>
        <meta name="description" content="<?php echo $this->_tpl_vars['metatags']['description']; ?>
" />
        <meta name="keywords" content="<?php echo $this->_tpl_vars['metatags']['keywords']; ?>
" />
        <meta http-equiv="X-UA-Compatible" content="chrome=1" />
        <?php echo smarty_function_pageaddvar(array('name' => 'stylesheet','value' => ($this->_tpl_vars['stylepath'])."/fluid960gs/reset.css"), $this);?>

        <?php echo smarty_function_pageaddvar(array('name' => 'stylesheet','value' => ($this->_tpl_vars['stylepath'])."/fluid960gs/".($this->_tpl_vars['layout']).".css"), $this);?>

        <?php echo smarty_function_pageaddvar(array('name' => 'stylesheet','value' => ($this->_tpl_vars['stylepath'])."/style.css"), $this);?>

        <?php $this->_tag_stack[] = array('browserhack', array('condition' => 'if IE 6','assign' => 'ieconditional')); $_block_repeat=true;smarty_block_browserhack($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?><link rel="stylesheet" type="text/css" href="<?php echo $this->_tpl_vars['stylepath']; ?>
/fluid960gs/ie6.css" media="screen" /><?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_browserhack($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
        <?php echo smarty_function_pageaddvar(array('name' => 'header','value' => $this->_tpl_vars['ieconditional']), $this);?>

        <?php $this->_tag_stack[] = array('browserhack', array('condition' => 'if IE 7','assign' => 'ieconditional')); $_block_repeat=true;smarty_block_browserhack($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?><link rel="stylesheet" type="text/css" href="<?php echo $this->_tpl_vars['stylepath']; ?>
/fluid960gs/ie.css" media="screen" /><?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_browserhack($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
        <?php echo smarty_function_pageaddvar(array('name' => 'header','value' => $this->_tpl_vars['ieconditional']), $this);?>

    </head>
    <body>
    <span id="CityPilotHeader">
    <?php echo smarty_function_blockposition(array('name' => 'citypilotheader'), $this);?>

    </span>
        <div id="theme_page_container" class="container_16">
                            
             
                