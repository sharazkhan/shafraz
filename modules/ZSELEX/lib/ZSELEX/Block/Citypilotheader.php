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
class ZSELEX_Block_Citypilotheader extends Zikula_Controller_AbstractBlock
{
    protected $shopfunctions = ['site', 'shop', 'pages', 'page', 'findus', 'productview',
        'viewevent'];

    /**
     * initialise block
     */
    public function init()
    {
        SecurityUtil::registerPermissionSchema('ZSELEX:citypilotheader:',
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
            'text_type_long' => $this->__('Citypilot Header'),
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
        if (!SecurityUtil::checkPermission('ZSELEX:citypilotheader:',
                "$blockinfo[title]::", ACCESS_OVERVIEW)) {
            return;
        }
        if (!ModUtil::available('ZSELEX')) {
            return;
        }
        $vars = BlockUtil::varsFromContent($blockinfo ['content']);

        // echo $shopconfig['dbname'];
        $this->view->assign('vars', $vars);

        $shopId   = $_GET['shop_id'];
        $currFunc = $_GET['func'];

        // echo "shopID :" . $shopId;

        $current_theme = System::getVar('Default_Theme');
        $this->view->assign('current_theme', $current_theme);


        $blockinfo ['content'] = $this->view->fetch('blocks/citypilottheme/header.tpl');
        /*
          if ($shopId) {
          $blockinfo ['content'] = $this->view->fetch('blocks/citypilottheme/shop_header.tpl');
          }
         */
        if (in_array($currFunc, $this->shopfunctions)) {
            $blockinfo ['content'] = $this->view->fetch('blocks/citypilottheme/shop_header.tpl');
        }

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