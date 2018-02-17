<?php

class ZSELEX_Controller_Shop extends Zikula_AbstractController
{
    public $message = '';
    public $server  = '';
    public $subject = '';

    public function postInitialize()
    {
        $this->server = $_SERVER ['SERVER_NAME'];
    }

    public function myshops()
    {
        echo "Function Comes Here";
        exit;
        return $this->view->fetch('user/viewtest.tpl');
    }

    /**
     * Reactivate Demo
     *
     * @params string shop_ids
     * @params int days
     * @return void
     */
    public function reactivateDemo($args)
    {
        //  echo "reactivateDemo"; exit;
        // echo "<pre>"; print_r($_REQUEST);  echo "</pre>";  exit;
        $shopIds     = FormUtil::getPassedValue('shop_ids', null, 'POST');
        $shopIdArray = explode(',', $shopIds);
        $shopIdsJson = json_encode($shopIdArray);
        // echo $shopIdsJson; exit;
        $demoPeriod  = FormUtil::getPassedValue('demo_period', null, 'POST');
        $path        = $_SERVER ['DOCUMENT_ROOT'].'/scripts/reactivate_demo.php';
        if ($_SERVER ['SERVER_NAME'] == 'localhost') {
            $path = $_SERVER ['DOCUMENT_ROOT'].'/zselex/scripts/reactivate_demo.php';
        }

        $baseUrl = pnGetBaseURL();
        if (!$demoPeriod) {
            $demoPeriod = 0;
        }

        // exec("/usr/bin/php -c php.ini ".$path." > /dev/null &");
        //  $cmd = "/usr/bin/php -c php.ini ".$path." ".$shopIdsJson." ".$demoPeriod." ".$baseUrl;
        $cmd = "php ".$path." ".$shopIdsJson." ".$demoPeriod." ".$baseUrl;
        //echo $cmd;  exit;

        ZSELEX_Util::execInBackground($cmd);

        LogUtil::registerStatus($this->__('Reactivation script has started at background'));
        $this->redirect(ModUtil::url('ZSELEX', 'admin', 'viewshop'));
    }

    /**
     * Reactivate Demo BG Call
     *
     * @params json shop_ids
     * @params int days
     * @return void
     */
    public function reactivateDemoBg($args)
    {
        set_time_limit(0);

        $this->message .= $this->__('Reactivate Demo is completed at the background.').'<br><br>';
        $this->message .= $this->__('Server').' : '.$this->server.'<br>';
        $this->message .= $this->__('Start Date').' : '.date('Y-m-d h:i:s a',
                time()).'<br>';
        $total = 0;
        try {
            // echo "reactivateDemoBg"; exit;
            // echo "<pre>"; print_r($_REQUEST);  echo "</pre>";  exit;
            $shopIdsJson = FormUtil::getPassedValue('shop_ids', null, 'REQUEST');
            $demoPeriod  = FormUtil::getPassedValue('days', null, 'REQUEST');
            if (!$demoPeriod) {
                $demoPeriod = $this->__('Standard');
            }

            // echo $shopIdsJson; exit;
            $shopIdArray = array();
            if (!empty($shopIdsJson)) {
                // $shopIdArray = explode(',', $shopIds);
                $shopIdArray = json_decode($shopIdsJson, true);
                foreach ($shopIdArray as $sid) {
                    $count = $this->entityManager->getRepository('ZSELEX_Entity_Bundle')->reactivateDemoFromShopListing(array(
                        'shop_id' => $sid,
                        'days' => $demoPeriod
                    ));
                    //   $total += $count;
                    if ($count) {
                        $total ++;
                    }
                }
            }
            $this->message .= $this->__('End Date').' : '.date('Y-m-d h:i:s a',
                    time()).'<br>';
            $this->message .= $this->__('Total Shops Selected').' : '.count($shopIdArray).'<br>';
            $this->message .= $this->__('Total Shops Executed').' : '.$total.'<br>';
            $this->message .= $this->__('Demo Period').' : '.$demoPeriod.'<br>';
            $this->subject = $this->__('Reactivate Demo Script');
            $this->sendEmail();
        } catch (Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";
            die();
        }

        // echo "<pre>"; print_r($shopIdArray);  echo "</pre>";  exit;
        die("End Of Script");
    }

    /**
     * Update Bundles BG Call
     *
     * @params json shopIds
     * @return void
     */
    public function updateShopBundlesBg($args)
    {
        set_time_limit(0);
        $this->message .= $this->__('Update Bundles script is completed at the background.').'<br><br>';
        $this->message .= $this->__('Server').' : '.$this->server.'<br>';
        $this->message .= $this->__('Start Date').' : '.date('Y-m-d h:i:s a',
                time()).'<br>';
        $shopJson = FormUtil::getPassedValue('shopIds', null, 'REQUEST');
        $total    = 0;
        if (!empty($shopJson)) {
            $shopArray = json_decode($shopJson, true);
            $shops     = implode(',', $shopArray);
            //  echo $shops; exit;
            $repo      = $this->entityManager->getRepository('ZSELEX_Entity_Bundle');
            $update    = $repo->updateShopBundlesWithLatest(array(
                'shops' => $shops
            ));
            $total += $update;
        }
        $this->message .= $this->__('End Date').' : '.date('Y-m-d h:i:s a',
                time()).'<br>';
        $this->subject = $this->__('Update Bundles Script');
        $this->message .= $this->__('Total Shops Selected').' : '.count($shopArray).'<br>';
        $this->message .= $this->__('Total Shops Executed').' : '.$total.'<br>';
        $this->sendEmail();
        die("End Of Script");
    }

    /**
     * update category , branch , affiliate , status
     *
     * @return void
     */
    function updateShopSelection()
    {

        set_time_limit(0);

        $this->message .= $this->__('Update Shops script is completed at the background.').'<br><br>';
        $this->message .= $this->__('Server').' : '.$this->server.'<br>';
        $this->message .= $this->__('Start Date').' : '.date('Y-m-d h:i:s a',
                time()).'<br>';


        try {
            $shopRepo    = $this->entityManager->getRepository('ZSELEX_Entity_Shop');
            $shopJson    = FormUtil::getPassedValue('shopJson', null, 'REQUEST');
            $chg_cat     = FormUtil::getPassedValue('chg_cat', null, 'REQUEST');
            $chg_aff     = FormUtil::getPassedValue('chg_aff', null, 'REQUEST');
            $chg_stat    = FormUtil::getPassedValue('chg_stat', null, 'REQUEST');
            $chg_del     = FormUtil::getPassedValue('chg_del', null, 'REQUEST');
            $rdemo       = FormUtil::getPassedValue('chg_demo', null, 'REQUEST');
            $chg_brnch   = FormUtil::getPassedValue('chg_brnch', null, 'REQUEST');
            $select_type = FormUtil::getPassedValue('select_type', null,
                    'REQUEST');


            // echo $shopJson; PHP_EOL;
            // echo "branch :". $chg_brnch; PHP_EOL;


            if ($chg_cat) {
                //echo "cat" . PHP_EOL; exit;
                $cats []     = $chg_cat;
                $objs        = array(
                    'cat_id' => $chg_cat
                );
                $typ         = 'Category';
                $category_id = $chg_cat;
                $catObj      = $this->entityManager->getRepository('ZSELEX_Entity_Category')->find($category_id);

                // echo "<pre>"; print_r($catObj);  echo "</pre>";  exit;
                // echo "cat : ". $catObj->category_name . PHP_EOL; exit;
                // echo "cat : ". $catObj['category_name'] . PHP_EOL; exit;
                $catName = $catObj['category_name'];
            } elseif ($chg_aff) {
                $objs          = array(
                    'aff_id' => $chg_aff
                );
                $typ           = 'Affiliate';
                $this->subject = $this->__('Update Affiliate');
                $affObj        = $this->entityManager->getRepository('ZSELEX_Entity_ShopAffiliation')->find($chg_aff);
                $affiliateName = $affObj['aff_name'];
            } elseif (isset($chg_stat)) {
                if ($chg_stat == 'active') $status        = 1;
                else $status        = 0;
                $objs          = array(
                    'status' => $status
                );
                $typ           = 'Status';
                $this->subject = $this->__('Update Status');
            } elseif ($chg_brnch) {
                $branches [] = $chg_brnch;
                $objs        = array(
                    'branch' => $chg_brnch
                );
                $typ         = 'Branch';
                $branch_id   = $chg_brnch;
                $branchObj   = $this->entityManager->getRepository('ZSELEX_Entity_Branch')->find($branch_id);
                $branchName  = $branchObj['branch_name'];
            }

            // $branches [] = $chg_brnch;
            //echo "<pre>"; print_r($objs); echo "</pre>"; exit;

            $shopArray  = json_decode($shopJson, true);
            $totalShops = count($shopArray);

            //echo "<pre>"; print_r($shopArray); echo "</pre>"; exit;
            foreach ($shopArray as $sid) {
                if (isset($chg_cat) && !empty($chg_cat)) {
                    if ($select_type == 'rm_cat') {
                        $this->subject = $this->__('Remove Category');
                        $description   = $this->__('Description').' : '.$totalShops.' shops removed from category: '.$catName.'<br>';
                        $shopObj       = $this->entityManager->getRepository('ZSELEX_Entity_Shop')->find($sid);
                        $shopObj->removeCategory($catObj);
                        $this->entityManager->persist($shopObj);
                    } else {
                        $this->subject  = $this->__('Update Category');
                        $saveCategories = ModUtil::apiFunc('ZSELEX', 'admin',
                                'saveShopCategories',
                                array(
                                'shop_id' => $sid,
                                'categories' => $cats
                        ));
                        $description    = $this->__('Description').' : '.$totalShops.' shops updated to category: '.$catName.'<br>';
                    }
                    $update = $saveCategories;
                } elseif (isset($chg_brnch) && !empty($chg_brnch)) {
                    if ($select_type == 'rm_brnch') {
                        $this->subject = $this->__('Remove Branch');
                        // echo "rm_brnch"; exit;
                        $shopObj       = $this->entityManager->getRepository('ZSELEX_Entity_Shop')->find($sid);
                        $shopObj->removeBranch($branchObj);
                        $this->entityManager->persist($shopObj);
                        $update        = true;
                        $description   = $this->__('Description').' : '.$totalShops.' shops removed from branch: '.$branchName.'<br>';
                    } else {
                        $this->subject = $this->__('Update Branch');
                        $saveBranches  = ModUtil::apiFunc('ZSELEX', 'admin',
                                'saveShopBranches',
                                array(
                                'shop_id' => $sid,
                                'branches' => $branches
                        ));
                        $update        = $saveBranches;
                        $description   = $this->__('Description').' : '.$totalShops.' shops updated to branch: '.$branchName.'<br>';
                    }
                } else {
                    $upd_args = array(
                        'entity' => 'ZSELEX_Entity_Shop',
                        'fields' => $objs,
                        'where' => array(
                            'a.shop_id' => $sid
                        )
                    );
                    //echo "<pre>"; print_r($upd_args); echo "</pre>" . PHP_EOL; exit;
                    $update   = $shopRepo->updateEntity($upd_args);

                    if ($select_type == 'stat') {
                        $description = $this->__('Description').' : '.$totalShops.' shops updated to status: '.$chg_stat.'<br>';
                    } else {
                        $description = $this->__('Description').' : '.$totalShops.' shops updated to affiliate: '.$affiliateName.'<br>';
                    }
                }
            }
            if ($select_type == 'rm_brnch' || $select_type == 'rm_cat') {
                $this->entityManager->flush();
            }
        } catch (Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n".PHP_EOL;
            die();
        }

        $this->message .= $this->__('End Date').' : '.date('Y-m-d h:i:s a',
                time()).'<br>';

        $this->message .= $this->__('Total Shops Selected').' : '.$totalShops.'<br>';
        $this->message .= $this->__('Total Shops Executed').' : '.$totalShops.'<br>';
        $this->message .= $description;
        $this->sendEmail();


        die("End Of Script".PHP_EOL);
    }

    public function assignToGroup()
    {
        error_reporting(0);
        set_time_limit(0);

        $this->message .= $this->__('Group assigning script is completed at the background.').'<br><br>';
        $this->message .= $this->__('Server').' : '.$this->server.'<br>';
        $this->message .= $this->__('Start Date').' : '.date('Y-m-d h:i:s a',
                time()).'<br>';


        $shopRepo = $this->entityManager->getRepository('ZSELEX_Entity_Shop');
        $shopJson = FormUtil::getPassedValue('shopJson', null, 'REQUEST');
        $group    = FormUtil::getPassedValue('chg_group', null, 'REQUEST');

        $shopArray  = json_decode($shopJson, true);
        $totalShops = count($shopArray);

        //echo "<pre>"; print_r($shopArray); echo "</pre>"; exit;

        $total = 0;
        foreach ($shopArray as $sid) {

            $shopId    = $sid;
            $ownerInfo = ModUtil::apiFunc('ZSELEX', 'admin', 'getOwnerInfo',
                    $args      = array(
                    'shop_id' => $shopId
            ));

            $ownerId = $ownerInfo['uid'];
            if ($ownerId) {
                $update = $this->updateToGroup($group, $ownerId);
                if ($update) {
                    $total ++;
                }
            }
        }


        $this->message .= $this->__('End Date').' : '.date('Y-m-d h:i:s a',
                time()).'<br>';

        $this->message .= $this->__('Total Shops Selected').' : '.$totalShops.'<br>';
        $this->message .= $this->__('Total Shops Executed').' : '.$total.'<br>';
        $this->message .= $description;
        $this->subject = $this->__('Group assigning Script');
        $this->sendEmail();

        die("End Of Script".PHP_EOL);
    }

    /**
     * Update to a group
     * 
     * @param int $gid
     * @param int $uid
     * @return boolean
     */
    public function updateToGroup($gid, $uid)
    {
        $statement = Doctrine_Manager::getInstance()->connection();
        $sql       = "SELECT count(*) as count"
            ." FROM group_membership "
            ."WHERE gid = '$gid' AND uid='$uid'";
        $query     = $statement->execute($sql);
        $result    = $query->fetch();
        if (!$result['count'] || $result['count'] < 1) {
            $insertSql = "INSERT INTO group_membership(gid , uid) VALUES('$gid' , '$uid')";
            $statement->execute($insertSql);
            return true;
        }

        return false;
    }

    /**
     * Send the script status through Email
     *
     * @return void
     */
    public function sendEmail()
    {
        $emails [] = 'sharazkhanz@gmail.com';
        $emails [] = 'kim@acta-it.dk';
        foreach ($emails as $email) {
            $mailer_args = array(
                'toaddress' => $email,
                'fromname' => 'ZSELEX',
                'subject' => $this->subject,
                'body' => $this->message,
                'html' => true
            );

            $sent = @ModUtil::apiFunc('Mailer', 'user', 'sendMessage',
                    $mailer_args);
        }
    }

    /**
     * Update shop keywords
     * Triggered at BG
     *
     * @return boolean
     */
    public function updateKeywords()
    {
        $shopRepo      = $this->entityManager->getRepository('ZSELEX_Entity_Shop');
        $updateKeyword = $shopRepo->insertKeyword();
        die();
    }

    public function updateEventTemp($args)
    {

      //  echo "<pre>";  print_r($_POST); echo "</pre>"; exit;
        $shopRepo = $this->entityManager->getRepository('ZSELEX_Entity_Event');

        $shop_id  = $_POST['shop_id'];
        $event_id = $_POST['event_id'];
        $start    = $_POST['from'];
        $end      = $_POST['end'];


        $dateRange = ZSELEX_Util::createDateRangeArray($start, $end);
        $shopRepo->updateEventTemp(
            array(
                'shop_id' => $shop_id,
                'event_id' => $event_id,
                'dates' => $dateRange
        ));

        die();
    }
}
?>