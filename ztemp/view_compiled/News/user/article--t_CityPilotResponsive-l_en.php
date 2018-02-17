<?php /* Smarty version 2.6.28, created on 2018-02-17 19:33:04
         compiled from user/article.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'ajaxheader', 'user/article.tpl', 1, false),array('function', 'pageaddvar', 'user/article.tpl', 5, false),array('function', 'setmetatag', 'user/article.tpl', 10, false),array('function', 'gt', 'user/article.tpl', 24, false),array('function', 'modurl', 'user/article.tpl', 30, false),array('function', 'notifydisplayhooks', 'user/article.tpl', 37, false),array('modifier', 'notifyfilters', 'user/article.tpl', 10, false),array('modifier', 'strip_tags', 'user/article.tpl', 10, false),array('modifier', 'trim', 'user/article.tpl', 10, false),array('modifier', 'truncate', 'user/article.tpl', 10, false),array('modifier', 'safehtml', 'user/article.tpl', 30, false),array('modifier', 'dateformat', 'user/article.tpl', 30, false),array('block', 'nocache', 'user/article.tpl', 13, false),array('insert', 'getstatusmsg', 'user/article.tpl', 14, false),)), $this); ?>
<?php $this->_cache_serials['ztemp/view_compiled/News/user/article--t_CityPilotResponsive-l_en.inc'] = 'c8d8bad864aaf96d02128a8bac660394'; ?><?php echo smarty_function_ajaxheader(array('ui' => true,'imageviewer' => 'true'), $this);?>

<?php if ($this->_tpl_vars['modvars']['News']['enableajaxedit']): ?>
    <?php if ($this->_tpl_vars['modvars']['News']['picupload_enabled']): ?>
    <?php echo smarty_function_pageaddvar(array('name' => 'javascript','value' => 'modules/News/javascript/multifile.js'), $this);?>

    <?php endif; ?>
<?php endif; ?>

<?php if ($this->_tpl_vars['modvars']['News']['enabledescriptionvar']): ?>
<?php echo smarty_function_setmetatag(array('name' => 'description','value' => ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['info']['hometext'])) ? $this->_run_mod_handler('notifyfilters', true, $_tmp, 'news.hook.articlesfilter.ui.filter') : smarty_modifier_notifyfilters($_tmp, 'news.hook.articlesfilter.ui.filter')))) ? $this->_run_mod_handler('strip_tags', true, $_tmp) : smarty_modifier_strip_tags($_tmp)))) ? $this->_run_mod_handler('trim', true, $_tmp) : trim($_tmp)))) ? $this->_run_mod_handler('truncate', true, $_tmp, $this->_tpl_vars['modvars']['News']['descriptionvarchars']) : smarty_modifier_truncate($_tmp, $this->_tpl_vars['modvars']['News']['descriptionvarchars']))), $this);?>

<?php endif; ?>

<?php if ($this->caching && !$this->_cache_including): echo '{nocache:c8d8bad864aaf96d02128a8bac660394#0}'; endif;$this->_tag_stack[] = array('nocache', array()); $_block_repeat=true;Zikula_View_Resource::block_nocache($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'user/menu.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo Zikula_View_Resource::block_nocache($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); if ($this->caching && !$this->_cache_including): echo '{/nocache:c8d8bad864aaf96d02128a8bac660394#0}'; endif;?>
<?php require_once(SMARTY_CORE_DIR . 'core.run_insert_handler.php');
echo smarty_core_run_insert_handler(array('args' => array('name' => 'getstatusmsg')), $this); ?>


<div id="news_articlecontent">
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'user/articlecontent.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</div>

<div id="news_modify">&nbsp;</div>

<?php if ($this->_tpl_vars['modvars']['News']['enablemorearticlesincat'] && $this->_tpl_vars['morearticlesincat'] > 0): ?>
<div id="news_morearticlesincat">
<h4><?php echo smarty_function_gt(array('text' => 'More articles in category '), $this);?>

<?php $_from = $this->_tpl_vars['preformat']['categorynames']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['categorynames'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['categorynames']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['categoryname']):
        $this->_foreach['categorynames']['iteration']++;
?>
<?php echo $this->_tpl_vars['categoryname']; ?>
<?php if (($this->_foreach['categorynames']['iteration'] == $this->_foreach['categorynames']['total']) != true): ?>&nbsp;&amp;&nbsp;<?php endif; ?>
<?php endforeach; endif; unset($_from); ?></h4>
<ul>
    <?php $_from = $this->_tpl_vars['morearticlesincat']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['morearticle']):
?>
    <li><a href="<?php echo smarty_function_modurl(array('modname' => 'News','type' => 'user','func' => 'display','sid' => $this->_tpl_vars['morearticle']['sid']), $this);?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['morearticle']['title'])) ? $this->_run_mod_handler('safehtml', true, $_tmp) : smarty_modifier_safehtml($_tmp)); ?>
</a> (<?php echo smarty_function_gt(array('text' => 'by %1$s on %2$s','tag1' => $this->_tpl_vars['morearticle']['contributor'],'tag2' => ((is_array($_tmp=$this->_tpl_vars['morearticle']['from'])) ? $this->_run_mod_handler('dateformat', true, $_tmp, 'datebrief') : smarty_modifier_dateformat($_tmp, 'datebrief'))), $this);?>
)</li>
    <?php endforeach; endif; unset($_from); ?>
</ul>
</div>
<?php endif; ?>

<?php echo smarty_function_notifydisplayhooks(array('eventname' => 'news.ui_hooks.articles.display_view','id' => $this->_tpl_vars['info']['sid'],'assign' => 'hooks'), $this);?>

<?php $_from = $this->_tpl_vars['hooks']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['provider_area'] => $this->_tpl_vars['hook']):
?>
<?php if (! ( ( $this->_tpl_vars['provider_area'] == 'provider.ezcomments.ui_hooks.comments' ) && ( $this->_tpl_vars['info']['allowcomments'] == 0 ) )): ?>
<?php echo $this->_tpl_vars['hook']; ?>

<?php endif; ?>
<?php endforeach; endif; unset($_from); ?>