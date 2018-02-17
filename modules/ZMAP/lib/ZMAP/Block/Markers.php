<?php
/**
 * Copyright ACTA-IT 2013 - ZMAP
 *
 * ZMAP
 * Demonstration of Zikula Module
 *
 * @license GNU/LGPLv3 (or at your option, any later version).
 */

/**
 * Class to control Block display and interface
 */
class ZMAP_Block_Markers extends Zikula_Controller_AbstractBlock
{
    /**
     * initialise block
     */
    public function init()
    {
        SecurityUtil::registerPermissionSchema('ZMAP:markers:', 'Block title::');
    }
    
    /**
     * get information on block
     */
    public function info()
    {
        return array(
            'text_type'        => 'ZMAP',
            'module'           => 'ZMAP',
            'text_type_long'   => $this->__('Marker Block'),
            'allow_multiple'   => true,
            'form_content'     => false,
            'form_refresh'     => false,
            'show_preview'     => true,
            'admin_tableless'  => true);
    }
    
    /**
     * display block
     */
    public function display($blockinfo)
    {
        if (!SecurityUtil::checkPermission('ZMAP:map:', "$blockinfo[title]::", ACCESS_OVERVIEW)) {
            return;
        }
        if (!ModUtil::available('ZMAP')) {
            return;
        }
        $vars = BlockUtil::varsFromContent($blockinfo['content']);
    
        $this->view->assign('vars', $vars);
    
        $blockinfo['content'] = $this->view->fetch('blocks/markers/markers.tpl');
    
        return BlockUtil::themeBlock($blockinfo);
    }
    
    /**
     * modify block settings ..
     */
    public function modify($blockinfo)
    {
        $vars = BlockUtil::varsFromContent($blockinfo['content']);
        if (empty($vars['showAdminZMAPinBlock'])) {
            $vars['showAdminZMAPinBlock'] = 0;
        }

        $this->view->assign('vars', $vars);
    
        return $this->view->fetch('blocks/markers/markers_modify.tpl');
    }
    
    /**
     * update block settings
     */
    public function update($blockinfo)
    {
        $vars = BlockUtil::varsFromContent($blockinfo['content']);
    
        // alter the corresponding variable
        $vars['showAdminZMAPinBlock'] = FormUtil::getPassedValue('showAdminZMAPinBlock', '', 'POST');
    
        // write back the new contents
        $blockinfo['content'] = BlockUtil::varsToContent($vars);
    
        // clear the block cache
        $this->view->clear_cache('blocks/markers/markers.tpl');
    
        return $blockinfo;
    }
} // end class def