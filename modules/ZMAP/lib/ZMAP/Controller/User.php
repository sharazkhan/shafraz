<?php
/**
 * Copyright R2International 2013 - ZMAP
 *
 * ZMAP
 *
 * @license GNU/LGPLv3 (or at your option, any later version).
 */

/**
 * Class to control User interface
 */
class ZMAP_Controller_User extends Zikula_AbstractController
{

    function postInitialize()
    {
        header("Cache-Control: max-age=300, must-revalidate");
    }

    /**
     * main
     *
     * main view function for end user
     * @access public
     */
    public function main()
    {
        $this->redirect(ModUtil::url('ZMAP', 'user', 'view'));
    }

    /**
     * view items
     * This is a standard function to provide an overview of all of the items
     * available from the module.
     */
    public function view()
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZMAP::',
                '::', ACCESS_OVERVIEW), LogUtil::getErrorMsgPermission());

        $this->view->assign('external_function', ZMAP_Util::externalfunction());

        return $this->view->fetch('user/view.tpl');
    }

    public function map($args)
    {

        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZMAP::',
                '::', ACCESS_COMMENT), LogUtil::getErrorMsgPermission());


        return $this->view->fetch('user/zmap.tpl');
    }

    public function deleteProject($args)
    {    // DELETE THE PROJECT
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZMAP::',
                '::', ACCESS_COMMENT), LogUtil::getErrorMsgPermission());
        $projectId = $_REQUEST['projectId'];

        $deleteproject = "DELETE FROM zmap_projects WHERE pid=$projectId";
        DBUtil::executeSQL($deleteproject);

        $deleteroad = "DELETE FROM zmap_roadmap WHERE pid=$projectId";
        DBUtil::executeSQL($deleteroad);
        LogUtil::registerStatus($this->__("<b>Project '%s' has been deleted!.</b>",
                $_POST['aprojectname']));
        $this->redirect(ModUtil::url('ZMAP', 'user', 'map'));
    }

    public function loadroad($args)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZMAP::',
                '::', ACCESS_COMMENT), LogUtil::getErrorMsgPermission());
        // echo "<pre>";   print_r($_POST);  echo "</pre>";  exit;

        $rid    = $_REQUEST['rid'];
        $uri    = $_REQUEST['uri'];
        $sql    = "SELECT * FROM  zmap_roadmap WHERE rid=$rid";
        // $query = mysql_query($sql);
        $query  = DBUtil::executeSQL($sql);
        $result = $query->fetch();


        $random_digit = rand(0000, 9999);


        $_SESSION['currentmap'][] = array(
            'rid' => $random_digit,
            'color' => 'yellow',
            'name' => $result['name'],
            'description' => $result['description'],
            'start' => $result['start'],
            'startdescription' => $result['startdescription'],
            'end' => $result['end'],
            'enddescription' => $result['enddescription'],
            'start_lattitude' => $result['start_lattitude'],
            'start_longitude' => $result['start_longitude'],
            'end_lattitude' => $result['end_lattitude'],
            'end_longitude' => $result['end_longitude'],
            'waypoints' => $result['waypoints'],
        );
        $this->redirect($uri);
    }

    public function roads($args)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZMAP::',
                '::', ACCESS_COMMENT), LogUtil::getErrorMsgPermission());


        if ($_POST && isset($_POST['saveroad']) && $_POST['saveroad'] == 'Save') {  // Save road
            // echo "come here again"; exit;
            //$this->saveRoad($_POST);
            //echo "<pre>";  print_r($_POST);  echo "</pre>"; exit;
            $projectId = $_REQUEST['projectId'];

            $rid       = $_REQUEST['ridedit'];
            $name      = $_REQUEST['roadname'];
            $desc      = $_REQUEST['roaddescription'];
            $start     = urlencode($_REQUEST['start']);
            $end       = urlencode($_REQUEST['end']);
            $startdesc = addslashes($_REQUEST['startdescription']);
            $enddesc   = addslashes($_REQUEST['enddescription']);
            $width     = $_REQUEST['width'];
            $city      = '';
            $state     = '';
            $country   = '';

            /*
              $geocodeStart = file_get_contents('http://maps.google.com/maps/api/geocode/json?address=' . $start . ',+' . $city . ',+' . $state . ',+' . $country . '&sensor=false');
              $outputStart = json_decode($geocodeStart); //Store values in variable

              $geocodeEnd = file_get_contents('http://maps.google.com/maps/api/geocode/json?address=' . $end . ',+' . $city . ',+' . $state . ',+' . $country . '&sensor=false');
              $outputEnd = json_decode($geocodeEnd); //Store values in variable

              $latStart = $outputStart->results[0]->geometry->location->lat;
              $longStart = $outputStart->results[0]->geometry->location->lng;

              $latEnd = $outputEnd->results[0]->geometry->location->lat;
              $longEnd = $outputEnd->results[0]->geometry->location->lng;
             * 
             */


            $item = array(
                'rid' => $rid,
                'name' => $name,
                'description' => $desc,
                'start' => $start,
                'startdescription' => $startdesc,
                'end' => $end,
                'enddescription' => $enddesc,
                //  'start_lattitude' => $latStart,
                //  'start_longitude' => $longStart,
                //  'end_lattitude' => $latEnd,
                //  'end_longitude' => $longEnd,
                // 'waypoints' => $_SESSION['waypoints']['rid'][$rid],
            );

            //echo "<pre>";  print_r($item);  echo "</pre>"; exit;

            $updateargs = array(
                'table' => 'zmap_roadmap',
                'IdValue' => $rid,
                'IdName' => 'rid',
                'element' => $item
            );

            $updateroad = ModUtil::apiFunc('ZMAP', 'user', 'updateElement',
                    $updateargs);
            $this->redirect(ModUtil::url('ZMAP', 'user', 'map',
                    array('projectId' => $projectId)));
        } elseif ($_POST && isset($_POST['saveroadas']) && $_POST['saveroadas'] == 'Set Road') { // save road as
            //echo "<pre>";  print_r($_POST);  echo "</pre>";  exit;
            $start        = urlencode($_REQUEST['start']);
            $end          = urlencode($_REQUEST['end']);
            $startaddress = urlencode($_REQUEST['startdescription']);
            $endaddress   = urlencode($_REQUEST['enddescription']);
            $width        = $_REQUEST['width'];
            $city         = '';
            $state        = '';
            $country      = '';

            $uri = $_REQUEST['uri'];

            $geocodeStart = file_get_contents('http://maps.google.com/maps/api/geocode/json?address='.$start.',+'.$city.',+'.$state.',+'.$country.'&sensor=false');
            $outputStart  = json_decode($geocodeStart); //Store values in variable

            $geocodeEnd = file_get_contents('http://maps.google.com/maps/api/geocode/json?address='.$end.',+'.$city.',+'.$state.',+'.$country.'&sensor=false');
            $outputEnd  = json_decode($geocodeEnd); //Store values in variable

            $latStart  = $outputStart->results[0]->geometry->location->lat;
            $longStart = $outputStart->results[0]->geometry->location->lng;

            $latEnd  = $outputEnd->results[0]->geometry->location->lat;
            $longEnd = $outputEnd->results[0]->geometry->location->lng;


            $random_digit             = rand(0000, 9999);
            $_SESSION['currentmap'][] = array(
                //  'rid' => '-2',
                'rid' => $random_digit,
                'color' => 'yellow',
                // 'waypoints' => array(),
                'name' => $_POST['roadname'],
                'description' => $_POST['roaddescription'],
                'start' => $_POST['start'],
                'startdescription' => $_POST['startdescription'],
                'end' => $_POST['end'],
                'enddescription' => $_POST['enddescription'],
                'start_lattitude' => $latStart,
                'start_longitude' => $longStart,
                'end_lattitude' => $latEnd,
                'end_longitude' => $longEnd,
            );
            $this->redirect($uri);
        } elseif ($_POST && isset($_POST['deleteroad']) && $_POST['deleteroad'] == 'Delete') { // delete road
            $rid        = $_REQUEST['ridedit'];
            $pid        = $_REQUEST['projectId'];
            $deleteroad = "DELETE FROM zmap_roadmap WHERE rid=$rid";
            DBUtil::executeSQL($deleteroad);
            LogUtil::registerStatus($this->__f("<b>Road '%s' has been deleted!.</b>",
                    $name));
            $this->redirect(ModUtil::url('ZMAP', 'user', 'map',
                    array('projectId' => $pid)));
        } elseif ($_POST && isset($_POST['removesetroads']) && $_POST['removesetroads']
            == 'Remove Set Roads') {  // remove set road
            $pid = $_REQUEST['projectId'];
            unset($_SESSION['currentmap']);
            $this->redirect(ModUtil::url('ZMAP', 'user', 'map',
                    array('projectId' => $pid)));
        }
    }

    public function deleteRoad($args)
    {   // DELETE ROAD
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZMAP::',
                '::', ACCESS_COMMENT), LogUtil::getErrorMsgPermission());
        $rid        = $_REQUEST['ridedit'];
        $pid        = $_REQUEST['projectId'];
        $deleteroad = "DELETE FROM zmap_roadmap WHERE rid=$rid";
        DBUtil::executeSQL($deleteroad);
        LogUtil::registerStatus($this->__('<b>Road has been deleted!.</b>'));
        $this->redirect(ModUtil::url('ZMAP', 'user', 'map',
                array('projectId' => $pid)));
    }

    public function saveloadedproject1()
    {

        $projectId = FormUtil::getPassedValue("projectId");
        $userId    = UserUtil::getVar('uid');

        $groupsql = "SELECT gid FROM group_membership WHERE uid=$userId";
        // $query = mysql_query($sql);
        $gquery   = DBUtil::executeSQL($groupsql);
        $gresult  = $gquery->fetch();

        $gid = $gresult[gid];

        $deleteroads = "DELETE FROM zmap_roadmap WHERE pid=$projectId";
        DBUtil::executeSQL($deleteroads);
        foreach ($_SESSION['finalSession'] as $key => $value) {
            $itemroadss       = array(
                'rid' => $value['rid'],
                'name' => $value['name'],
                'description' => $value['description'],
                'start' => $value['start'],
                'startdescription' => $value['startdescription'],
                'end' => $value['end'],
                'enddescription' => $value['enddescription'],
                // 'start_lattitude' => $value['start_lattitude'],
                // 'start_longitude' => $value['start_longitude'],
                //  'end_lattitude' => $value['end_lattitude'],
                // 'end_longitude' => $value['end_longitude'],
                'start_lattitude' => $_SESSION['startendpoints']['startLat']['rid'][$value['rid']],
                'start_longitude' => $_SESSION['startendpoints']['startLng']['rid'][$value['rid']],
                'end_lattitude' => $_SESSION['startendpoints']['endLat']['rid'][$value['rid']],
                'end_longitude' => $_SESSION['startendpoints']['endLng']['rid'][$value['rid']],
                'waypoints' => $_SESSION['waypoints']['rid'][$value['rid']],
                'cid' => $gid,
                'pid' => $projectId,
            );
            $saveprojectroads = ModUtil::apiFunc('ZMAP', 'user',
                    'createElement',
                    array('table' => 'zmap_roadmap', 'element' => $itemroadss, 'Id' => 'rid'));
        }
        unset($_SESSION['currentmap']);
        $this->redirect(ModUtil::url('ZMAP', 'user', 'map',
                array('projectId' => $projectId)));
    }

    public function saveloadedproject()
    {  // SAVE THE PROJECT
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZMAP::',
                '::', ACCESS_COMMENT), LogUtil::getErrorMsgPermission());


        // echo "<pre>"; print_r($_POST);   echo "</pre>"; exit;
        // $sql1 = "SELECT * FROM  zmap_roadmap_temp";
        // $query = mysql_query($sql);
        //  $q1= DBUtil::executeSQL($sql1);
        // $res= $q1->fetchAll();
        //  echo "<pre>"; print_r($res);   echo "</pre>"; exit;


        $projectId = FormUtil::getPassedValue("projectId");
        $userId    = UserUtil::getVar('uid');
        $groupsql  = "SELECT gid FROM group_membership WHERE uid=$userId AND gid!=1";
        // $query = mysql_query($sql);
        $gquery    = DBUtil::executeSQL($groupsql);
        $gresult   = $gquery->fetch();
        $gid       = $gresult[gid];


        $itemproject = array(
            'pid' => $projectId,
            'name' => $_POST['aprojectname'],
            'description' => $_POST['aprojectdescription']
        );

        $updateargs = array(
            'table' => 'zmap_projects',
            'IdValue' => $projectId,
            'IdName' => 'pid',
            'element' => $itemproject
        );

        $updateproject = ModUtil::apiFunc('ZMAP', 'user', 'updateElement',
                $updateargs);


        //foreach ($finalresult as $key => $value) {

        if (!empty($_SESSION['currentmap'])) {
            foreach ($_SESSION['currentmap'] as $key => $value) {

                $itemroads        = array(
                    'name' => $value['name'],
                    'description' => $value['description'],
                    'start' => $value['start'],
                    'startdescription' => $value['startdescription'],
                    'end' => $value['end'],
                    'enddescription' => $value['enddescription'],
                    'start_lattitude' => $value['start_lattitude'],
                    'start_longitude' => $value['start_longitude'],
                    'end_lattitude' => $value['end_lattitude'],
                    'end_longitude' => $value['end_longitude'],
                    'waypoints' => $value['waypoints'],
                    'cid' => $gid,
                    'pid' => $projectId,
                );
                //echo "<pre>"; print_r($itemroads);   echo "</pre>"; exit;
                $saveprojectroads = ModUtil::apiFunc('ZMAP', 'user',
                        'createElement',
                        array('table' => 'zmap_roadmap', 'element' => $itemroads,
                        'Id' => 'rid'));
            }
        }
        LogUtil::registerStatus($this->__f('<b>Project %s has been saved!</b>',
                $_POST['aprojectname']));
        // exit;
        unset($_SESSION['currentmap']);


        $this->redirect(ModUtil::url('ZMAP', 'user', 'map',
                array('projectId' => $projectId)));
    }

    public function saveNewProject()
    {  // CREATE NEW PROJECT
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZMAP::',
                '::', ACCESS_COMMENT), LogUtil::getErrorMsgPermission());
        //echo "hellooooo";
        // echo "<pre>"; print_r($_SESSION['currentmap']);   echo "</pre>"; exit;
        //echo "<pre>"; print_r($_POST);   echo "</pre>"; 
        $projectId = FormUtil::getPassedValue("projectId");
        $userId    = UserUtil::getVar('uid');

        $groupsql = "SELECT gid FROM group_membership WHERE uid=$userId AND gid!=1";
        // $query = mysql_query($sql);
        $gquery   = DBUtil::executeSQL($groupsql);
        $gresult  = $gquery->fetch();

        $gid = $gresult[gid];


        $sql    = "select * from zmap_roadmap where pid='".$projectId."'";
        $query  = DBUtil::executeSQL($sql);
        $result = $query->fetchAll();


        //  echo $sql; exit;
        //  echo "<pre>"; print_r($result);   echo "</pre>";  exit;



        $name = $_POST['hprojectname'];
        $desc = $_POST['hprojectdescription'];
        //echo "<font color=green><b>Done! Project has been created successfully</b></font>";


        $item           = array(
            'cid' => $gid,
            'name' => $name,
            'description' => $desc,
        );
        $savenewproject = ModUtil::apiFunc('ZMAP', 'user', 'createElement',
                array('table' => 'zmap_projects',
                'element' => $item,
                'Id' => 'pid'));

        $newProjId = $savenewproject['pid'];

        // echo "new projectid :" . $newProjId;
        // foreach ($_SESSION['currentmap'] as $key => $value) {
        // foreach ($_SESSION['finalSession'] as $key => $value) {
        foreach ($result as $key => $value) {

            $itemroads    = array(
                'name' => $value['name'],
                'description' => $value['description'],
                'start' => $value['start'],
                'startdescription' => $value['startdescription'],
                'end' => $value['end'],
                'enddescription' => $value['enddescription'],
                'start_lattitude' => $value['start_lattitude'],
                'start_longitude' => $value['start_longitude'],
                'end_lattitude' => $value['end_lattitude'],
                'end_longitude' => $value['end_longitude'],
                'waypoints' => $value['waypoints'],
                'cid' => $gid,
                'pid' => $newProjId,
            );
            $savenewroads = ModUtil::apiFunc('ZMAP', 'user', 'createElement',
                    array('table' => 'zmap_roadmap',
                    'element' => $itemroads,
                    'Id' => 'rid'));
        }
        if (!empty($_SESSION['currentmap'])) {
            foreach ($_SESSION['currentmap'] as $key => $value) {

                $itemroads1       = array(
                    'name' => $value['name'],
                    'description' => $value['description'],
                    'start' => $value['start'],
                    'startdescription' => $value['startdescription'],
                    'end' => $value['end'],
                    'enddescription' => $value['enddescription'],
                    'start_lattitude' => $value['start_lattitude'],
                    'start_longitude' => $value['start_longitude'],
                    'end_lattitude' => $value['end_lattitude'],
                    'end_longitude' => $value['end_longitude'],
                    'waypoints' => $value['waypoints'],
                    'cid' => $gid,
                    'pid' => $newProjId,
                );
                //echo "<pre>"; print_r($itemroads);   echo "</pre>"; exit;
                $saveprojectroads = ModUtil::apiFunc('ZMAP', 'user',
                        'createElement',
                        array('table' => 'zmap_roadmap', 'element' => $itemroads1,
                        'Id' => 'rid'));
            }
        }


        unset($_SESSION['currentmap']);
        LogUtil::registerStatus($this->__f('<b>Project %s has been created!</b>',
                $name));
        $this->redirect(ModUtil::url('ZMAP', 'user', 'map',
                array('projectId' => $newProjId)));
    }

    /**
     * This is a page to provide an textual overview of caching concepts
     * @return string 
     */
    public function cacheinfo()
    {
        // template needs to know where the directories are
        $this->view->assign('compiledir', $this->view->getCompileDir());
        $this->view->assign('cachedir', $this->view->getCacheDir());

        return $this->view->fetch('user/cachedemo/info.tpl');
    }

    /**
     * This is a standard page that returns a template view
     * It DOES respect the settings in Theme->settings->render caching
     * (on/off and lifetime)
     * @return string 
     */
    public function standard()
    {
        $this->view->assign('time', microtime(true));
        return $this->view->fetch('user/cachedemo/standard.tpl');
    }

    /**
     * This is a page that should never return cached information. It does not 
     * respect cache settings (on/off) in Theme. The page should always return 
     * new information regardless of all cache settings.
     * @return string
     */
    public function nevercached()
    {
        // force caching off
        $this->view->setCaching(Zikula_View::CACHE_DISABLED);

        $this->view->assign('time', microtime(true));
        return $this->view->fetch('user/cachedemo/nevercached.tpl');
    }

    /**
     * This is a page that should  return partially cached information. It does
     * not respect cache settings(on/off or lifetime) in Theme. The page should
     * always return some information that is always cached and some information
     * that is never cached. (controlled in template by {nocache} block)
     * @return string
     */
    public function partialcache()
    {
        // force caching on
        $this->view->setCaching(Zikula_View::CACHE_ENABLED);

        //force local cache lifetime
        $localcachelifetime = 31;
        $this->view->setCacheLifetime($localcachelifetime);

        $this->view->assign('time', microtime(true));
        $this->view->assign('localcachelifetime', $localcachelifetime);
        return $this->view->fetch('user/cachedemo/partialcache.tpl');
    }

    /**
     * When one template is used to render multiple pages or versions of content
     * it becomes necessary to 'salt' the cacheId with additional information
     * in order that each unique page of content has a unique cache
     * 
     * This page will return unique cached information per page id. In this
     * example the only unique information on the page is the page number.
     * 
     * It does not respect cache settings (on/off or lifetime) in Theme.
     * 
     * Additionally, this page demonstrates the varying methods to clear cached
     * templates using clear_cache().
     * @return string
     */
    public function uniquepages()
    {
        $submit = (int) $this->request->getPost()->get('submit', 0);
        $page   = (int) $this->request->getPost()->get('page', 1);
        // enfore min/max values for $page
        if ($page < 1) {
            $page = 1;
        }
        if ($page > 9) {
            $page = 9;
        }

        $template = 'user/cachedemo/uniquepages.tpl';

        // force caching on
        $this->view->setCaching(Zikula_View::CACHE_ENABLED);

        //force local cache lifetime
        $localcachelifetime = 120;
        $this->view->setCacheLifetime($localcachelifetime);

        // setting the cacheid forces each page version of the template to unique
        $this->view->setCacheId($page);

        switch ($submit) {
            case -100: // clear this page template cache
                $this->view->clear_cache($template, $this->view->getCacheId());
                LogUtil::registerStatus($this->__f("Just this version of '%s' cleared from cache.",
                        $template));
                break;
            case -200: // clear all page uses of this template cache
                $this->view->clear_cache($template);
                LogUtil::registerStatus($this->__f("All versions of '%s' cleared from cache.",
                        $template));
                break;
            // NOTE: calling $this->view->clear_cache(); (with no arguments) clears all cached templates for *this* module.
        }

        $this->view->assign('cacheid', $this->view->getCacheId());
        $this->view->assign('submit', $submit);

        $this->view->assign('page', $page);
        $this->view->assign('time', microtime(true));
        $this->view->assign('localcachelifetime', $localcachelifetime);
        return $this->view->fetch($template);
    }

    /**
     * This is a page to demonstrate the value of checking ->is_cached() when
     * returning a cached template. A manufactured delay (sleep) is used to
     * simulate doing something very resource intensive that might take place
     * in a real module.
     * It does not respect cache settings (on/off or lifetime) in Theme.
     * @return string 
     */
    public function checkiscached()
    {
        $template = 'user/cachedemo/checkiscached.tpl';

        // force caching on
        $this->view->setCaching(Zikula_View::CACHE_ENABLED);

        //force local cache lifetime
        $localcachelifetime = 31;
        $this->view->setCacheLifetime($localcachelifetime);

        // check to see if the tempalte is cached, if not, get required data
        if (!$this->view->is_cached($template)) {
            // manufactured wait to demo DB fetch or something resource intensive
            sleep(5);

            $this->view->assign('time', microtime(true));
            $this->view->assign('localcachelifetime', $localcachelifetime);
        }
        return $this->view->fetch($template);
    }
}
// end class def