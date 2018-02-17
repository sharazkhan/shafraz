<?php
/**
 * Copyright  2013
 *
 * ZSELEX
 * Demonstration of Zikula Module
 *
 * @license GNU/LGPLv3 (or at your option, any later version).
 */

/**
 * Class to control Block display and interface
 */
class ZSELEX_Block_Citypilotfooter extends Zikula_Controller_AbstractBlock
{

    /**
     * initialise block
     */
    public function init()
    {
        SecurityUtil::registerPermissionSchema('ZSELEX:citypilotfooter:',
            'Block title::');
    }

    /**
     * get information on block
     */
    public function info()
    {
        return array(
            'text_type' => 'hello',
            'module' => 'ZSELEX',
            'text_type_long' => $this->__('Citypilot Footer'),
            'allow_multiple' => true,
            'form_content' => false,
            'form_refresh' => false,
            'show_preview' => true,
            'admin_tableless' => true
        );
    }

    /**
     * display block
     */
    public function display($blockinfo)
    {
        if (!SecurityUtil::checkPermission('ZSELEX:citypilotfooter:',
                "$blockinfo[title]::", ACCESS_OVERVIEW)) {
            return;
        }
        if (!ModUtil::available('ZSELEX')) {
            return;
        }
        $vars = BlockUtil::varsFromContent($blockinfo ['content']);

        // echo $shopconfig['dbname'];
        $this->view->assign('vars', $vars);

        $current_theme = System::getVar('Default_Theme');
        $this->view->assign('current_theme', $current_theme);

        $thislang = ZLanguage::getLanguageCode();

        // echo "<pre>"; print_r($plugins); echo "</pre>";

        $this->view->assign('thislang', $thislang);

        $blockinfo ['content'] = $this->view->fetch('blocks/citypilottheme/footer.tpl');

        return BlockUtil::themeBlock($blockinfo);
    }

    /**
     * modify block settings .
     * .
     */
    public function modify($blockinfo)
    {
        $vars = BlockUtil::varsFromContent($blockinfo ['content']);

        $this->view->assign('vars', $vars);
        return $this->view->fetch('blocks/citypilottheme/citypilotheader_modify.tpl');
    }

    /**
     * update block settings
     */
    public function update($blockinfo)
    {
        $vars = BlockUtil::varsFromContent($blockinfo ['content']);

        // alter the corresponding variable

        $blockinfo ['content'] = BlockUtil::varsToContent($vars);

        // clear the block cache
        $this->view->clear_cache('blocks/citypilotheader_modify.tpl');

        return $blockinfo;
    }
}
// end class def