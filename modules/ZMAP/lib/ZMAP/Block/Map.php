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
class ZMAP_Block_Map extends Zikula_Controller_AbstractBlock {

    /**
     * initialise block
     */
    public function init() {
        SecurityUtil::registerPermissionSchema('ZMAP:map:', 'Block title::');
    }

    /**
     * get information on block
     */
    public function info() {
        return array(
            'text_type' => 'ZMAP',
            'module' => 'ZMAP',
            'text_type_long' => $this->__('Display ZMAP'),
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
        if (!SecurityUtil::checkPermission('ZMAP:map:', "$blockinfo[title]::", ACCESS_OVERVIEW)) {
            return;
        }
        if (!ModUtil::available('ZMAP')) {
            return;
        }

        $userId = UserUtil::getVar('uid');

       // if ($_POST && isset($_POST['saveprj']) && $_POST['saveprj'] == 'Save') { // loaded project
            //echo "hellooooo";
            // echo "<pre>"; print_r($_SESSION['currentmap']);   echo "</pre>";
           // echo "<font color=green><b>Done! Project has been saved successfully</b></font>";
       // }

       // if ($_POST && isset($_POST['saveasvalue']) && $_POST['saveasvalue'] == 'saveas') { // save new project
            //echo "hellooooo";
            //echo "<pre>"; print_r($_SESSION['currentmap']);   echo "</pre>";
            //echo "<font color=green><b>Done! Project has been created successfully</b></font>";
       // }



        $test = $_SESSION['waypoints'][0];

        $test1 = explode(',(', $test);

        //echo "<pre>"; print_r($test1);   echo "</pre>";

        $string = '';

        foreach ($test1 as $val) {
            // echo $val . '<br>';

            $order = array("(", ")");
            $lnglt = str_replace($order, " ", $val);
            $string .= "{location:new google.maps.LatLng($lnglt),stopover:false},";

            // echo $lnglt . '<br>';
        }
        $string = substr($string, 0, -1);
        //echo $string;


        $vars = BlockUtil::varsFromContent($blockinfo['content']);

        $this->view->assign('vars', $vars);

        $items = array("1" => "test1", "2" => "test2", "3" => "test3");


        // echo "<pre>";  print_r($items);  echo "</pre>";
        $count = count($items);
        $this->view->assign('count', $count);
        $this->view->assign('items', $items);


        $blockinfo['content'] = $this->view->fetch('blocks/map/map.tpl');

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

        return $this->view->fetch('blocks/map/map_modify.tpl');
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
        $this->view->clear_cache('blocks/map/map.tpl');

        return $blockinfo;
    }

}

// end class def