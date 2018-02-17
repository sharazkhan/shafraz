<?php /* Smarty version 2.6.28, created on 2018-02-17 19:33:04
         compiled from user/articlecontent.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'safehtml', 'user/articlecontent.tpl', 13, false),array('modifier', 'dateformat', 'user/articlecontent.tpl', 22, false),array('modifier', 'notifyfilters', 'user/articlecontent.tpl', 35, false),array('block', 'nocache', 'user/articlecontent.tpl', 15, false),array('function', 'articleadminlinks', 'user/articlecontent.tpl', 16, false),array('function', 'gt', 'user/articlecontent.tpl', 22, false),array('function', 'modurl', 'user/articlecontent.tpl', 43, false),array('function', 'pager', 'user/articlecontent.tpl', 64, false),)), $this); ?>
<?php $this->_cache_serials['ztemp/view_compiled/News/user/articlecontent--t_CityPilotResponsive-l_en.inc'] = 'a9ff8e3bf85b725d0abf1d11a8b2c048'; ?><script type="text/javascript"><?php echo '
    // <![CDATA[
    var bytesused = Zikula.__f(\'%s characters out of 4,294,967,295\',\'#{chars}\',\'module_News\');
    // ]]>
'; ?>
</script>

<!--span class="news_category">
<?php $_from = $this->_tpl_vars['preformat']['categories']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['categorylinks'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['categorylinks']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['categorylink']):
        $this->_foreach['categorylinks']['iteration']++;
?>
<?php echo $this->_tpl_vars['categorylink']; ?>

<?php if (($this->_foreach['categorylinks']['iteration'] == $this->_foreach['categorylinks']['total']) != true): ?><span class="text_separator"> | </span><?php endif; ?>
<?php endforeach; endif; unset($_from); ?>
</span-->
<h3 class="news_title"><?php echo ((is_array($_tmp=$this->_tpl_vars['info']['title'])) ? $this->_run_mod_handler('safehtml', true, $_tmp) : smarty_modifier_safehtml($_tmp)); ?>
</h3>

<?php if ($this->caching && !$this->_cache_including): echo '{nocache:a9ff8e3bf85b725d0abf1d11a8b2c048#0}'; endif;$this->_tag_stack[] = array('nocache', array()); $_block_repeat=true;Zikula_View_Resource::block_nocache($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
<div id="news_editlinks"><?php echo smarty_function_articleadminlinks(array('sid' => $this->_tpl_vars['info']['sid']), $this);?>
</div>
<?php if ($this->_tpl_vars['modvars']['News']['enableajaxedit']): ?>
<div id="news_editlinks_ajax" class="hidelink"><?php echo smarty_function_articleadminlinks(array('sid' => $this->_tpl_vars['info']['sid'],'page' => $this->_tpl_vars['page'],'type' => 'ajax'), $this);?>
</div>
<?php endif; ?>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo Zikula_View_Resource::block_nocache($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); if ($this->caching && !$this->_cache_including): echo '{/nocache:a9ff8e3bf85b725d0abf1d11a8b2c048#0}'; endif;?>

<!--p class="news_meta z-sub"><?php echo smarty_function_gt(array('text' => 'Contributed'), $this);?>
 <?php echo smarty_function_gt(array('text' => 'by %1$s on %2$s','tag1' => $this->_tpl_vars['info']['contributor'],'tag2' => ((is_array($_tmp=$this->_tpl_vars['info']['from'])) ? $this->_run_mod_handler('dateformat', true, $_tmp, 'datetimebrief') : smarty_modifier_dateformat($_tmp, 'datetimebrief'))), $this);?>
</p-->

<?php if ($this->_tpl_vars['links']['searchtopic'] != '' && $this->_tpl_vars['info']['topicimage'] != ''): ?>
<p id="news_topic" class="news_meta"><a href="<?php echo $this->_tpl_vars['links']['searchtopic']; ?>
"><img src="<?php echo $this->_tpl_vars['modvars']['News']['catimagepath']; ?>
<?php echo $this->_tpl_vars['info']['topicimage']; ?>
" alt="<?php echo $this->_tpl_vars['info']['topicname']; ?>
" title="<?php echo $this->_tpl_vars['info']['topicname']; ?>
" /></a></p>
<?php endif; ?>

<div id="news_body" class="news_body">
    <?php if ($this->_tpl_vars['modvars']['News']['picupload_enabled'] && $this->_tpl_vars['info']['pictures'] > 0): ?>
    <div class="news_photo news_thumbs" style="float:<?php echo $this->_tpl_vars['modvars']['News']['picupload_article_float']; ?>
">
        <a href="<?php echo $this->_tpl_vars['modvars']['News']['picupload_uploaddir']; ?>
/pic_sid<?php echo $this->_tpl_vars['info']['sid']; ?>
-0-norm.jpg" rel="imageviewer[sid<?php echo $this->_tpl_vars['info']['sid']; ?>
]"><img src="<?php echo $this->_tpl_vars['modvars']['News']['picupload_uploaddir']; ?>
/pic_sid<?php echo $this->_tpl_vars['info']['sid']; ?>
-0-thumb2.jpg" alt="<?php echo smarty_function_gt(array('text' => 'Picture %1$s for %2$s','tag1' => '0','tag2' => ((is_array($_tmp=$this->_tpl_vars['info']['title'])) ? $this->_run_mod_handler('safehtml', true, $_tmp) : smarty_modifier_safehtml($_tmp))), $this);?>
" /></a>
    </div>
    <?php endif; ?>
    <div class="news_hometext">
        <?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['preformat']['hometext'])) ? $this->_run_mod_handler('notifyfilters', true, $_tmp, 'news.hook.articlesfilter.ui.filter') : smarty_modifier_notifyfilters($_tmp, 'news.hook.articlesfilter.ui.filter')))) ? $this->_run_mod_handler('safehtml', true, $_tmp) : smarty_modifier_safehtml($_tmp)); ?>

    </div>
    <?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['preformat']['bodytext'])) ? $this->_run_mod_handler('notifyfilters', true, $_tmp, 'news.hook.articlesfilter.ui.filter') : smarty_modifier_notifyfilters($_tmp, 'news.hook.articlesfilter.ui.filter')))) ? $this->_run_mod_handler('safehtml', true, $_tmp) : smarty_modifier_safehtml($_tmp)); ?>


    <p class="news_footer">
        <?php echo $this->_tpl_vars['preformat']['print']; ?>

        <?php if ($this->_tpl_vars['modvars']['News']['pdflink']): ?>
        <span class="text_separator">|</span>
        <a title="PDF" href="<?php echo smarty_function_modurl(array('modname' => 'News','type' => 'user','func' => 'displaypdf','sid' => $this->_tpl_vars['info']['sid']), $this);?>
" target="_blank">PDF <img src="modules/News/images/pdf.gif" width="16" height="16" alt="PDF" /></a>
        <?php endif; ?>
    </p>
    
    <?php if ($this->_tpl_vars['modvars']['News']['picupload_enabled'] && $this->_tpl_vars['info']['pictures'] > 1): ?>
    <div class="news_pictures"><div><strong><?php echo smarty_function_gt(array('text' => 'Picture gallery'), $this);?>
</strong></div>
        <?php unset($this->_sections['counter']);
$this->_sections['counter']['name'] = 'counter';
$this->_sections['counter']['start'] = (int)1;
$this->_sections['counter']['loop'] = is_array($_loop=$this->_tpl_vars['info']['pictures']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['counter']['step'] = ((int)1) == 0 ? 1 : (int)1;
$this->_sections['counter']['show'] = true;
$this->_sections['counter']['max'] = $this->_sections['counter']['loop'];
if ($this->_sections['counter']['start'] < 0)
    $this->_sections['counter']['start'] = max($this->_sections['counter']['step'] > 0 ? 0 : -1, $this->_sections['counter']['loop'] + $this->_sections['counter']['start']);
else
    $this->_sections['counter']['start'] = min($this->_sections['counter']['start'], $this->_sections['counter']['step'] > 0 ? $this->_sections['counter']['loop'] : $this->_sections['counter']['loop']-1);
if ($this->_sections['counter']['show']) {
    $this->_sections['counter']['total'] = min(ceil(($this->_sections['counter']['step'] > 0 ? $this->_sections['counter']['loop'] - $this->_sections['counter']['start'] : $this->_sections['counter']['start']+1)/abs($this->_sections['counter']['step'])), $this->_sections['counter']['max']);
    if ($this->_sections['counter']['total'] == 0)
        $this->_sections['counter']['show'] = false;
} else
    $this->_sections['counter']['total'] = 0;
if ($this->_sections['counter']['show']):

            for ($this->_sections['counter']['index'] = $this->_sections['counter']['start'], $this->_sections['counter']['iteration'] = 1;
                 $this->_sections['counter']['iteration'] <= $this->_sections['counter']['total'];
                 $this->_sections['counter']['index'] += $this->_sections['counter']['step'], $this->_sections['counter']['iteration']++):
$this->_sections['counter']['rownum'] = $this->_sections['counter']['iteration'];
$this->_sections['counter']['index_prev'] = $this->_sections['counter']['index'] - $this->_sections['counter']['step'];
$this->_sections['counter']['index_next'] = $this->_sections['counter']['index'] + $this->_sections['counter']['step'];
$this->_sections['counter']['first']      = ($this->_sections['counter']['iteration'] == 1);
$this->_sections['counter']['last']       = ($this->_sections['counter']['iteration'] == $this->_sections['counter']['total']);
?>
            <div class="news_photoslide news_thumbsslide">
                <a href="<?php echo $this->_tpl_vars['modvars']['News']['picupload_uploaddir']; ?>
/pic_sid<?php echo $this->_tpl_vars['info']['sid']; ?>
-<?php echo $this->_sections['counter']['index']; ?>
-norm.jpg" rel="imageviewer[sid<?php echo $this->_tpl_vars['info']['sid']; ?>
]"><span></span>
                <img src="<?php echo $this->_tpl_vars['modvars']['News']['picupload_uploaddir']; ?>
/pic_sid<?php echo $this->_tpl_vars['info']['sid']; ?>
-<?php echo $this->_sections['counter']['index']; ?>
-thumb.jpg" alt="<?php echo smarty_function_gt(array('text' => 'Picture %1$s for %2$s','tag1' => $this->_sections['counter']['index'],'tag2' => $this->_tpl_vars['info']['title']), $this);?>
" /></a>
            </div>
        <?php endfor; endif; ?>
    </div>
    <?php endif; ?>
</div>

<?php if ($this->_tpl_vars['preformat']['notes'] != ''): ?>
<span id="news_notes" class="news_meta"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['preformat']['notes'])) ? $this->_run_mod_handler('notifyfilters', true, $_tmp, 'news.hook.articlesfilter.ui.filter') : smarty_modifier_notifyfilters($_tmp, 'news.hook.articlesfilter.ui.filter')))) ? $this->_run_mod_handler('safehtml', true, $_tmp) : smarty_modifier_safehtml($_tmp)); ?>
</span>
<?php endif; ?>

<?php echo smarty_function_pager(array('rowcount' => $this->_tpl_vars['pager']['numitems'],'limit' => $this->_tpl_vars['pager']['itemsperpage'],'posvar' => 'page'), $this);?>
