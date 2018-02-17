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
class ZMAP_Block_Roads extends Zikula_Controller_AbstractBlock {

    /**
     * initialise block
     */
    public function init() {
        SecurityUtil::registerPermissionSchema('ZMAP:roads:', 'Block title::');
    }

    /**
     * get information on block
     */
    public function info() {
        return array(
            'text_type' => 'ZMAP',
            'module' => 'ZMAP',
            'text_type_long' => $this->__('Roads Block'),
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
        if (!SecurityUtil::checkPermission('ZMAP:roads:', "$blockinfo[title]::", ACCESS_OVERVIEW)) {
            return;
        }
        if (!ModUtil::available('ZMAP')) {
            return;
        }
        $vars = BlockUtil::varsFromContent($blockinfo['content']);
        // $baseurl = pnGetBaseURL();




        $userId = UserUtil::getVar('uid');
        $projectId = $_REQUEST['projectId'];
        $groupsql = "SELECT gid FROM group_membership WHERE uid=$userId  AND gid!=1";
        $gquery = DBUtil::executeSQL($groupsql);
        $gresult = $gquery->fetch();
        $gid = $gresult[gid];


        $apsql = "SELECT * FROM zmap_roadmap WHERE cid=$gid";
        $apquery = DBUtil::executeSQL($apsql);
        $allProjectRoads = $apquery->fetchAll();


        // echo "<pre>";  print_r($allProjectRoads);  echo "</pre>";

        foreach ($allProjectRoads as $key => $value) {

            if (in_array($projectId, $value)) {
                $roadsinproject[$key] = $value;
            }
        }

        // echo "<pre>";  print_r($roadsinproject);  echo "</pre>";
        //  $allProjectRoads = DBUtil::selectObjectArray('zmap_roadmap');

        $projectId = $_REQUEST['projectId'];
        if (!empty($projectId)) {
            //$projectroads = DBUtil::selectObjectArray('zmap_roadmap', $projectId, 'pid');

            $pntable = pnDBGetTables();
            $roadcolumn = $pntable['zmap_roadmap_column'];
            $where = "WHERE $roadcolumn[pid] = '" . pnVarPrepForStore($projectId) . "'";
            $orderBy = "";
            $projectroads = DBUtil::selectObjectArray('zmap_roadmap', $where, $orderBy);
        }

        $this->view->assign('allProjectRoads', $allProjectRoads);
        // $this->view->assign('projectroads', $roadsinproject);
        $this->view->assign('projectroads', $projectroads);

        //  echo "<pre>"; print_r($projectroads);   echo "</pre>";
        //echo $_SERVER['SCRIPT_FILENAME'];
        $formaction = $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

        $uri = $_SERVER['REQUEST_URI'];
        $this->view->assign('uri', $uri);
        $this->view->assign('formaction', $formaction);
        $this->view->assign('vars', $vars);

        $blockinfo['content'] = $this->view->fetch('blocks/roads/roads.tpl');

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

        return $this->view->fetch('blocks/roads/roads_modify.tpl');
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
        $this->view->clear_cache('blocks/roads/roads.tpl');

        return $blockinfo;
    }

}

// end class def