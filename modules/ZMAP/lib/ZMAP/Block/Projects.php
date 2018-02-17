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
class ZMAP_Block_Projects extends Zikula_Controller_AbstractBlock {

    /**
     * initialise block
     */
    public function init() {
        SecurityUtil::registerPermissionSchema('ZMAP:projects:', 'Block title::');
    }

    /**
     * get information on block
     */
    public function info() {
        return array(
            'text_type' => 'ZMAP',
            'module' => 'ZMAP',
            'text_type_long' => $this->__('Projects Block'),
            'allow_multiple' => true,
            'form_content' => false,
            'form_refresh' => false,
            'show_preview' => true,
            'admin_tableless' => true);
    }

    /**
     * display block
     */
    public function display($blockinfo) {
        if (!SecurityUtil::checkPermission('ZMAP:projects:', "$blockinfo[title]::", ACCESS_OVERVIEW)) {
            return;
        }
        if (!ModUtil::available('ZMAP')) {
            return;
        }
        $vars = BlockUtil::varsFromContent($blockinfo['content']);
        $userId = UserUtil::getVar('uid');
        $groupsql = "SELECT gid FROM group_membership WHERE uid=$userId  AND gid!=1";
        $gquery = DBUtil::executeSQL($groupsql);
        $gresult = $gquery->fetch();
        $gid = $gresult[gid];
        //$projects = ModUtil::apiFunc('ZSELEX', 'admin', 'getElements', $args = array('table' => 'zmap_projects', 'where' => "cid=$gid", 'orderBy' => 'pid ASC', 'useJoins' => ''));
		$projects = ModUtil::apiFunc('ZMAP', 'user', 'getElements', $args = array('table' => 'zmap_projects', 'where' => "cid=$gid", 'orderBy' => 'pid ASC', 'useJoins' => ''));

        $formaction = $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

        $projectId = $_REQUEST['projectId'];
       

        if (!empty($projectId)) {
            $projectitem = DBUtil::selectObjectByID('zmap_projects', $projectId, 'pid');
        }

        // echo "<pre>"; print_r($projectobj);  echo "</pre>";
		
        $this->view->assign('projectitem', $projectitem);
        $this->view->assign('formaction', $formaction);
        $this->view->assign('projects', $projects);
        $this->view->assign('vars', $vars);

        $blockinfo['content'] = $this->view->fetch('blocks/projects/projects.tpl');

        return BlockUtil::themeBlock($blockinfo);
    }

    /**
     * modify block settings ..
     */
    public function modify($blockinfo) {
        $vars = BlockUtil::varsFromContent($blockinfo['content']);
        if (empty($vars['showAdminZMAPinBlock'])) {
            $vars['showAdminZMAPinBlock'] = 0;
        }

        $this->view->assign('vars', $vars);

        return $this->view->fetch('blocks/projects/projects_modify.tpl');
    }

    /**
     * update block settings
     */
    public function update($blockinfo) {
        $vars = BlockUtil::varsFromContent($blockinfo['content']);

        // alter the corresponding variable
        $vars['showAdminZMAPinBlock'] = FormUtil::getPassedValue('showAdminZMAPinBlock', '', 'POST');

        // write back the new contents
        $blockinfo['content'] = BlockUtil::varsToContent($vars);

        // clear the block cache
        $this->view->clear_cache('blocks/projects/projects.tpl');

        return $blockinfo;
    }

}

// end class def