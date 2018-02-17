<?php
/*
 * 2015
 *
 * Class for shop related ajax functionalities
 */

class ZSELEX_Controller_Shopajax extends Zikula_Controller_AbstractAjax
{
    /*
     * Save banner settings
     *
     * @params GET request
     * @return Json
     */

    function bannerSetting()
    {
        // echo 1; exit;
        // AjaxUtil::output(array('count'=>2));
        $shop_id = FormUtil::getPassedValue('shop_id', 0, 'REQUEST');
        $value   = FormUtil::getPassedValue('value', 0, 'REQUEST');

        $repo = $this->entityManager->getRepository('ZSELEX_Entity_BannerSetting');

        $count = $repo->getCount(null, 'ZSELEX_Entity_BannerSetting',
            'ban_set_id', array(
            'a.shop' => $shop_id
        ));

        // echo $count; exit;
        // AjaxUtil::output(array('count'=>$count));

        if ($count) {
            $item   = array(
                'image_mode' => $value
            );
            // echo "<pre>"; print_r($item); echo "</pre>"; exit;
            $result = $repo->updateEntity(null, 'ZSELEX_Entity_BannerSetting',
                $item, array(
                'a.shop' => $shop_id
            ));
        } else {
            $ban_sett = new ZSELEX_Entity_BannerSetting ();
            $shop     = $this->entityManager->find('ZSELEX_Entity_Shop',
                $shop_id);
            $ban_sett->setShop($shop);
            $ban_sett->setImage_mode($value);

            $this->entityManager->persist($ban_sett);
            $this->entityManager->flush();
            $result = $ban_sett->getBan_set_id();
        }
        if ($result) {
            $output ['success'] = 1;
        } else {
            $output ['success'] = 0;
        }
        // error_log("\r\nHello Errors\r\n", 3, "modules/ZSELEX/errors/errors4.log");
        // asdf7896
        // ZSELEX_Util::logError('errors27.log', 'pdf', 'Testing Error Logs');
        AjaxUtil::output($output);
    }
    /*
     * Crop the image
     *
     * Pop up screen for croping a image
     *
     * @params GET request
     * @return Json
     */

    function cropImage()
    {
        $shop_id = FormUtil::getPassedValue('shop_id', 0, 'REQUEST');
        $view    = Zikula_View::getInstance($this->name);
        $view->setCaching(false);
        $repo    = $this->entityManager->getRepository('ZSELEX_Entity_Banner');

        $getBanner = $repo->get(array(
            'entity' => 'ZSELEX_Entity_Banner',
            'where' => array(
                'a.shop' => $shop_id
            )
        ));
        // echo "<pre>"; print_r($getBanner); echo "</pre>"; exit;
        $data      = '';
        $view->assign('banner', $getBanner);

        $bannerImage       = pnGetBaseURL()."zselexdata/$shop_id/banner/resized/".str_replace(" ",
                "%20", $getBanner ['banner_image']);
        $view->assign('bannerImage', $bannerImage);
        // echo $getBanner['banner_image']; exit;
        $view->assign('file_name', $getBanner ['banner_image']);
        $output_tpl        = $view->fetch('ajax/crop_image.tpl');
        $data .= new Zikula_Response_Ajax_Plain($output_tpl);
        $output ["data"]   = $data;
        $output ["height"] = $getBanner ['height'];
        $output ["width"]  = $getBanner ['width'];
        AjaxUtil::output($output);
    }
    /*
     * Save the croped image abd save it to the destination folder
     * Gets the croped image coordinates
     *
     * @param GET
     * @return Json
     */

    function saveImage()
    {

        // error_reporting(0);
        // echo "<pre>"; print_r($_REQUEST); echo "</pre>"; exit;
        $shop_id     = FormUtil::getPassedValue('shop_id', 0, 'REQUEST');
        $filename    = FormUtil::getPassedValue('file_name', null, 'REQUEST');
        $imageMode   = FormUtil::getPassedValue('image_mode', null, 'REQUEST');
        $x           = FormUtil::getPassedValue('x', null, 'REQUEST');
        $y           = FormUtil::getPassedValue('y', null, 'REQUEST');
        $w           = FormUtil::getPassedValue('w', null, 'REQUEST');
        $h           = FormUtil::getPassedValue('h', null, 'REQUEST');
        // $bannerImage = pnGetBaseURL() . "zselexdata/$shop_id/banner/resized/" . str_replace(" ", "%20", $filename);
        $bannerImage = "zselexdata/$shop_id/banner/resized/".str_replace(" ",
                "%20", $filename);
        // $savePath = pnGetBaseURL() . "zselexdata/$shop_id/banner/resized/";

        $extension = strrchr($filename, '.');

        $fileInfo = pathinfo($filename);
        $fileExt  = $fileInfo ['extension'];
        $fileBase = $fileInfo ['filename'];

        // $newFileName = $fileBase . '.jpg';
        $newFileName = time().'_croped.jpg';
        $filename    = $newFileName;
        $savePath    = "zselexdata/$shop_id/banner/resized/".str_replace(" ",
                "%20", $filename);

        if ($extension == '.jpg') {
            // list($width, $height) = getimagesize($bannerImage);
            // $targ_w = $targ_h = 150;
            $targ_w       = $w;
            $targ_h       = $h;
            $jpeg_quality = 95;

            // $src = 'demo_files/pool.jpg';
            $src   = $bannerImage;
            $img_r = imagecreatefromjpeg($src);
            $dst_r = ImageCreateTrueColor($targ_w, $targ_h);

            imagecopyresampled($dst_r, $img_r, 0, 0, $x, $y, $targ_w, $targ_h,
                $w, $h);

            // header('Content-type: image/jpeg');
            imagejpeg($dst_r, $savePath, $jpeg_quality);
            unlink($bannerImage);
        } elseif ($extension == '.png') {

            $targ_w       = $w;
            $targ_h       = $h;
            $jpeg_quality = 95;

            // $src = 'demo_files/pool.jpg';
            $src   = $bannerImage;
            $img_r = imagecreatefrompng($src);
            $dst_r = ImageCreateTrueColor($targ_w, $targ_h);

            imagecopyresampled($dst_r, $img_r, 0, 0, $x, $y, $targ_w, $targ_h,
                $w, $h);

            // header('Content-type: image/png');
            // imagepng($dst_r, $savePath, $jpeg_quality);
            imagejpeg($dst_r, $savePath, $jpeg_quality);
            imagedestroy($dst_r);
            unlink($bannerImage);
        } elseif ($extension == '.gif') {
            $targ_w       = $w;
            $targ_h       = $h;
            $jpeg_quality = 95;

            // $src = 'demo_files/pool.jpg';
            $src   = $bannerImage;
            $img_r = @imagecreatefromgif($src);
            $dst_r = ImageCreateTrueColor($targ_w, $targ_h);

            @imagecopyresampled($dst_r, $img_r, 0, 0, $x, $y, $targ_w, $targ_h,
                    $w, $h);

            // header('Content-type: image/jpeg');
            // imagegif($dst_r, $savePath, $jpeg_quality);
            @imagejpeg($dst_r, $savePath, $jpeg_quality);
            @imagedestroy($dst_r);
            @unlink($bannerImage);
        }

        $destination = "zselexdata/$shop_id/banner";
        if ($imageMode == 0) {
            $resizeFunc = 'bannerResize';
        } elseif ($imageMode == 1) {
            $resizeFunc = 'bannerStretch';
        }
        $resizeBanner = ModUtil::apiFunc('ZSELEX', 'admin', $resizeFunc,
                array(
                'filename' => $filename,
                'destination' => $destination.'/',
                'crop' => 1
        ));

        $repo     = $this->entityManager->getRepository('ZSELEX_Entity_Banner');
        $newImage = pnGetBaseURL()."zselexdata/$shop_id/banner/resized/".str_replace(" ",
                "%20", $filename);
        list ( $width, $height ) = getimagesize($newImage);
        $item     = array(
            'banner_image' => $filename,
            'height' => $height,
            'width' => $width
        );
        $update   = $repo->updateEntity(null, 'ZSELEX_Entity_Banner', $item,
            array(
            'a.shop' => $shop_id
        ));
        $output   = array(
            'success' => 1
        );
        AjaxUtil::output($output);
    }

    function saveStatistics()
    {
        // echo 1; exit;
        // AjaxUtil::output(array('count'=>2));
        $shop_id       = FormUtil::getPassedValue('shop_id', 0, 'REQUEST');
        $purchaseStat  = FormUtil::getPassedValue('purchaseStat', 0, 'REQUEST');
        $emailPurchase = FormUtil::getPassedValue('emailPurchase', 0, 'REQUEST');

        $repo = $this->entityManager->getRepository('ZSELEX_Entity_Shop');

        $item   = array(
            'purchase_collect_stat' => $purchaseStat,
            'email_purchase_tries' => $emailPurchase
        );
        // echo "<pre>"; print_r($item); echo "</pre>"; exit;
        $result = $repo->updateEntity(null, 'ZSELEX_Entity_Shop', $item,
            array(
            'a.shop_id' => $shop_id
        ));

        if ($result) {
            $output ['success'] = 1;
        } else {
            $output ['success'] = 0;
        }
        // error_log("\r\nHello Errors\r\n", 3, "modules/ZSELEX/errors/errors4.log");
        // asdf7896
        // ZSELEX_Util::logError('errors27.log', 'pdf', 'Testing Error Logs');
        AjaxUtil::output($output);
    }

    /**
     * Confirmation box prior to downgrading bundle
     *
     * @param int bundle_id
     * @param int shop_id
     * @return ajax response
     */
    function downgradeConfirm()
    {
        $output ['error'] = 1;
        $view             = Zikula_View::getInstance($this->name);
        $bundle_id        = FormUtil::getPassedValue('bundle_id', 0, 'REQUEST');
        $shop_id          = FormUtil::getPassedValue('shop_id', 0, 'REQUEST');
        $repo             = $this->entityManager->getRepository('ZSELEX_Entity_Shop');

        // $getExistingBundle = ModUtil::apiFunc('ZSELEX', 'admin', 'getExistingBundle', $getargs = array('shop_id' => $shop_id));

        $getExistingBundle = $repo->get(array(
            'entity' => 'ZSELEX_Entity_ServiceBundle',
            'where' => array(
                'a.shop' => $shop_id,
                'a.bundle_type' => 'main'
            ),
            'joins' => array(
                'JOIN a.bundle b'
            ),
            'fields' => array(
                'a.service_status',
                'a.timer_date',
                'a.timer_days',
                'b.bundle_name'
            )
        ));
        // echo "<pre>"; print_r($getExistingBundle); echo "</pre>"; exit;

        $getBundle = $repo->get(array(
            'entity' => 'ZSELEX_Entity_Bundle',
            'where' => array(
                'a.bundle_id' => $bundle_id
            ),
            'fields' => array(
                'a.bundle_name'
            )
        ));

        $data       = '';
        $view->assign('current_bundle_name', $getExistingBundle ['bundle_name']);
        $view->assign('new_bundle_name', $getBundle ['bundle_name']);
        $output_tpl = $view->fetch('ajax/downgrade_bundle_confirm.tpl');
        $data .= new Zikula_Response_Ajax_Plain($output_tpl);

        // $output['current_bundle_name'] = $getExistingBundle['bundle_name'];
        // $output['new_bundle_name'] = $getBundle['bundle_name'];
        $output ['error'] = 0;
        $output ['data']  = $data;

        AjaxUtil::output($output);
    }

    /**
     * Downgrades a Bundle
     *
     * @return Response
     */
    function downgradeBundle()
    {
        error_reporting(0);
        $output ['error'] = 1;
        $bundle_id        = FormUtil::getPassedValue('bundle_id', 0, 'REQUEST');
        $shop_id          = FormUtil::getPassedValue('shop_id', 0, 'REQUEST');
        $repo             = $this->entityManager->getRepository('ZSELEX_Entity_Shop');

        $getExistingBundle = $repo->get(array(
            'entity' => 'ZSELEX_Entity_ServiceBundle',
            'where' => array(
                'a.shop' => $shop_id,
                'a.bundle_type' => 'main'
            ),
            'fields' => array(
                'a.service_status',
                'a.timer_date',
                'a.timer_days'
            )
        ));
        // echo "<pre>"; print_r($getExistingBundle); echo "</pre>"; exit;

        $getExistingAdditionalBundles = $repo->getAll(array(
            'entity' => 'ZSELEX_Entity_ServiceBundle',
            'where' => array(
                'a.shop' => $shop_id,
                'a.bundle_type' => 'additional'
            ),
            'joins' => array('JOIN a.bundle b'),
            'fields' => array(
                'b.bundle_id',
            )
        ));

        // echo "<pre>"; print_r($getExistingAdditionalBundles); echo "</pre>"; exit;
        if ($getExistingAdditionalBundles) {
            foreach ($getExistingAdditionalBundles as $additionalBundle) {
                $a_bundleId = $additionalBundle['bundle_id'];

                $cantBuy = ModUtil::apiFunc('ZSELEX', 'service',
                        'canBuyStatusAdditionalBundle',
                        array(
                        'shop_id' => $shop_id,
                        'bundle_id' => $a_bundleId,
                        'd_bundle_id' => $bundle_id
                ));

                if ($cantBuy) {
                    $deleteAdditionalBundle = $repo->deleteEntity(null,
                        'ZSELEX_Entity_ServiceBundle',
                        array(
                        'a.shop' => $shop_id, 'a.bundle' => $a_bundleId
                    ));
                }
            }
        }

        $getBundle = $repo->get(array(
            'entity' => 'ZSELEX_Entity_Bundle',
            'where' => array(
                'a.bundle_id' => $bundle_id
            )
            )
            // 'fields' => array('a.service_status', 'a.timer_date', 'a.timer_days')
        );

        // echo "<pre>"; print_r($getBundle); echo "</pre>"; exit;
        $isFree = $getBundle['is_free'];

        $service_status = $getExistingBundle ['service_status'];
        $timer_date     = $getExistingBundle ['timer_date'];
        $timer_days     = $getExistingBundle ['timer_days'];

        if ($isFree) {
            $service_status = 3;
        }

        $user_id   = UserUtil::getVar('uid');
        $ownerInfo = ModUtil::apiFunc('ZSELEX', 'admin', 'getOwnerInfo',
                array(
                'shop_id' => $shop_id
        ));

        $owner_id = $ownerInfo ['user_id'];

        $update_item = array(
            'bundle' => $bundle_id,
            'bundle_name' => $getBundle ['bundle_name']
        );
        $update      = $repo->updateEntity(null, 'ZSELEX_Entity_ServiceBundle',
            $update_item,
            array(
            'a.shop' => $shop_id,
            'a.bundle_type' => 'main'
        ));

        if ($update) {
            // $demoCount = $repo->getCount(null, 'ZSELEX_Entity_ServiceDemo', 'demo_id', array('a.shop' => $shop_id, 'a.bundle' => $bundle_id));
            // $demoCount = $repo->getCount(null, 'ZSELEX_Entity_ServiceDemo', 'demo_id', array('a.shop' => $shop_id , 'a.bundle' => $bundle_id));
            $demoCount = $repo->getCount(null, 'ZSELEX_Entity_ServiceDemo',
                'demo_id',
                array(
                'a.shop' => $shop_id,
                'a.bundle_type' => 'main'
            ));
            if (!$demoCount) {
                $servcDemo = new ZSELEX_Entity_ServiceDemo ();
                $shopObj   = $this->entityManager->find('ZSELEX_Entity_Shop',
                    $shop_id);
                $servcDemo->setShop($shopObj);
                // $servcDemo->setPlugin_id($serviceId);
                // $servcDemo->setType($serviceType);
                $servcDemo->setUser_id($user_id);
                $servcDemo->setOwner_id($owner_id);
                $servcDemo->setQuantity(1);
                $servcDemo->setQty_based(1);
                $servcDemo->setIs_bundle(1);
                $bundleObj = $this->entityManager->find('ZSELEX_Entity_Bundle',
                    $bundle_id);
                $servcDemo->setBundle($bundleObj);
                $servcDemo->setTop_bundle(1);
                $servcDemo->setBundle_type('main');
                $servcDemo->setStatus(1);
                $servcDemo->setStart_date(date_create($timer_date));
                $servcDemo->setTimer_days($timer_days);
                $this->entityManager->persist($servcDemo);
                $this->entityManager->flush();
                $result    = $servcDemo->getDemo_id();
            } else {
                $updateDemoItem = array(
                    'bundle' => $bundle_id
                );
                $updateDemo     = $repo->updateEntity(null,
                    'ZSELEX_Entity_ServiceDemo', $updateDemoItem,
                    array(
                    'a.shop' => $shop_id,
                    'a.bundle_type' => 'main'
                ));
            }
            $deleteServices = $repo->deleteEntity(null,
                'ZSELEX_Entity_ServiceShop',
                array(
                'a.shop' => $shop_id
            ));

            $values                    = array(
                'bundle' => 1,
                'service_status' => $service_status,
                'user_id' => $user_id,
                'owner_id' => $owner_id,
                'shop_id' => $shop_id,
                'timer_date' => $timer_date
            );
            $bundleitems               = $repo->getAll(array(
                'entity' => 'ZSELEX_Entity_BundleItem',
                'fields' => array(
                    'a.id',
                    'b.bundle_id',
                    'a.service_name',
                    'a.servicetype',
                    'c.plugin_id',
                    'a.price',
                    'a.qty',
                    'a.qty_based'
                ),
                'joins' => array(
                    'JOIN a.bundle b',
                    'LEFT JOIN a.plugin c'
                ),
                'where' => array(
                    'a.bundle' => $bundle_id
                ),
                'groupby' => 'a.id'
            ));
            $values ['bundleitems']    = $bundleitems;
            $values ['timer_days']     = $timer_days;
            $values ['bundle_type']    = 'main';
            $values ['bundle_id']      = $bundle_id;
            $values ['shop_id']        = $shop_id;
            $values ['service_status'] = $service_status;

            $approvebundlesitems = ZSELEX_Controller_Base_Admin::insertBundleItems($values);

            $output ['error'] = 0;
        }
        AjaxUtil::output($output);
    }

    function downgradeBundle1()
    {
        error_reporting(0);
        $output ['error'] = 1;
        $bundle_id        = FormUtil::getPassedValue('bundle_id', 0, 'REQUEST');
        $shop_id          = FormUtil::getPassedValue('shop_id', 0, 'REQUEST');
        $repo             = $this->entityManager->getRepository('ZSELEX_Entity_Shop');

        $getExistingBundle = $repo->get(array(
            'entity' => 'ZSELEX_Entity_ServiceBundle',
            'where' => array(
                'a.shop' => $shop_id,
                'a.bundle_type' => 'main'
            ),
            'fields' => array(
                'a.service_status',
                'a.timer_date',
                'a.timer_days'
            )
        ));

        // echo "<pre>"; print_r($getExistingBundle); echo "</pre>"; exit;

        $getBundle = $repo->get(array(
            'entity' => 'ZSELEX_Entity_Bundle',
            'where' => array(
                'a.bundle_id' => $bundle_id
            )
            )
            // 'fields' => array('a.service_status', 'a.timer_date', 'a.timer_days')
        );

        // echo "<pre>"; print_r($getExistingBundle); echo "</pre>"; exit;

        $service_status = $getExistingBundle ['service_status'];
        $timer_date     = $getExistingBundle ['timer_date'];
        $timer_days     = $getExistingBundle ['timer_days'];

        $user_id   = UserUtil::getVar('uid');
        $ownerInfo = ModUtil::apiFunc('ZSELEX', 'admin', 'getOwnerInfo',
                array(
                'shop_id' => $shop_id
        ));

        $owner_id = $ownerInfo ['user_id'];

        $update_item = array(
            'bundle' => $bundle_id,
            'bundle_name' => $getBundle ['bundle_name']
        );
        $update      = $repo->updateEntity(null, 'ZSELEX_Entity_ServiceBundle',
            $update_item,
            array(
            'a.shop' => $shop_id,
            'a.bundle_type' => 'main'
        ));

        if ($update) {
            // $demoCount = $repo->getCount(null, 'ZSELEX_Entity_ServiceDemo', 'demo_id', array('a.shop' => $shop_id, 'a.bundle' => $bundle_id));
            // $demoCount = $repo->getCount(null, 'ZSELEX_Entity_ServiceDemo', 'demo_id', array('a.shop' => $shop_id , 'a.bundle' => $bundle_id));
            $demoCount = $repo->getCount(null, 'ZSELEX_Entity_ServiceDemo',
                'demo_id',
                array(
                'a.shop' => $shop_id,
                'a.bundle_type' => 'main'
            ));
            if (!$demoCount) {
                $servcDemo = new ZSELEX_Entity_ServiceDemo ();
                $shopObj   = $this->entityManager->find('ZSELEX_Entity_Shop',
                    $shop_id);
                $servcDemo->setShop($shopObj);
                // $servcDemo->setPlugin_id($serviceId);
                // $servcDemo->setType($serviceType);
                $servcDemo->setUser_id($user_id);
                $servcDemo->setOwner_id($owner_id);
                $servcDemo->setQuantity(1);
                $servcDemo->setQty_based(1);
                $servcDemo->setIs_bundle(1);
                $bundleObj = $this->entityManager->find('ZSELEX_Entity_Bundle',
                    $bundle_id);
                $servcDemo->setBundle($bundleObj);
                $servcDemo->setTop_bundle(1);
                $servcDemo->setBundle_type('main');
                $servcDemo->setStatus(1);
                $servcDemo->setStart_date(date_create($timer_date));
                $servcDemo->setTimer_days($timer_days);
                $this->entityManager->persist($servcDemo);
                $this->entityManager->flush();
                $result    = $servcDemo->getDemo_id();
            } else {
                $updateDemoItem = array(
                    'bundle' => $bundle_id
                );
                $updateDemo     = $repo->updateEntity(null,
                    'ZSELEX_Entity_ServiceDemo', $updateDemoItem,
                    array(
                    'a.shop' => $shop_id,
                    'a.bundle_type' => 'main'
                ));
            }
            $deleteServices = $repo->deleteEntity(null,
                'ZSELEX_Entity_ServiceShop',
                array(
                'a.shop' => $shop_id
            ));

            $values                    = array(
                'bundle' => 1,
                'service_status' => $service_status,
                'user_id' => $user_id,
                'owner_id' => $owner_id,
                'shop_id' => $shop_id,
                'timer_date' => $timer_date
            );
            $bundleitems               = $repo->getAll(array(
                'entity' => 'ZSELEX_Entity_BundleItem',
                'fields' => array(
                    'a.id',
                    'b.bundle_id',
                    'a.service_name',
                    'a.servicetype',
                    'c.plugin_id',
                    'a.price',
                    'a.qty',
                    'a.qty_based'
                ),
                'joins' => array(
                    'JOIN a.bundle b',
                    'LEFT JOIN a.plugin c'
                ),
                'where' => array(
                    'a.bundle' => $bundle_id
                ),
                'groupby' => 'a.id'
            ));
            $values ['bundleitems']    = $bundleitems;
            $values ['timer_days']     = $timer_days;
            $values ['bundle_type']    = 'main';
            $values ['bundle_id']      = $bundle_id;
            $values ['shop_id']        = $shop_id;
            $values ['service_status'] = $service_status;

            $approvebundlesitems = ZSELEX_Controller_Base_Admin::insertBundleItems($values);

            $output ['error'] = 0;
        }
        AjaxUtil::output($output);
    }

    function removeConfirm()
    {
        $output ['error'] = 1;
        $view             = Zikula_View::getInstance($this->name);
        $bundle_id        = FormUtil::getPassedValue('bundle_id', 0, 'REQUEST');
        $shop_id          = FormUtil::getPassedValue('shop_id', 0, 'REQUEST');

        $output_tpl = $view->fetch('ajax/remove_bundle_confirm.tpl');
        $data .= new Zikula_Response_Ajax_Plain($output_tpl);

        // $output['current_bundle_name'] = $getExistingBundle['bundle_name'];
        // $output['new_bundle_name'] = $getBundle['bundle_name'];
        $output ['error'] = 0;
        $output ['data']  = $data;

        AjaxUtil::output($output);
    }
    /*
     * Removes shop bundles and services
     *
     * @param int bundle_id
     * @param int shop_id
     * @return Response
     */

    function removeBundle()
    {
        $output ['error'] = 1;
        $bundle_id        = FormUtil::getPassedValue('bundle_id', 0, 'REQUEST');
        $shop_id          = FormUtil::getPassedValue('shop_id', 0, 'REQUEST');
        $repo             = $this->entityManager->getRepository('ZSELEX_Entity_Shop');

        $deleteServices = $repo->deleteEntity(null, 'ZSELEX_Entity_ServiceShop',
            array(
            'a.shop' => $shop_id
        ));

        $deleteBundle = $repo->deleteEntity(null, 'ZSELEX_Entity_ServiceBundle',
            array(
            'a.shop' => $shop_id
        ));

        /*
          $deleteBundle     = $repo->deleteEntity(null,
          'ZSELEX_Entity_ServiceBundle',
          array(
          'a.shop' => $shop_id, 'a.bundle' => $bundle_id
          ));
         */
        // $deleteDemo = $repo->deleteEntity(null, 'ZSELEX_Entity_ServiceDemo', array('a.shop' => $shop_id, 'a.bundle' => $bundle_id));
        /*
         * $updateDemoItem = array('timer_days' => 0);
         * $updateDemo = $repo->updateEntity(null, 'ZSELEX_Entity_ServiceDemo', $updateDemoItem, array('a.shop' => $shop_id, 'a.bundle' => $bundle_id));
         *
         * $updateBundleItem = array('timer_days' => 0);
         * $updateBundle = $repo->updateEntity(null, 'ZSELEX_Entity_ServiceBundle', $updateBundleItem, array('a.shop' => $shop_id, 'a.bundle' => $bundle_id));
         */
        $output ['error'] = 0;
        AjaxUtil::output($output);
    }

    function downgradeAdditionalConfirm()
    {
        $output ['error'] = 1;
        $view             = Zikula_View::getInstance($this->name);
        $bundle_id        = FormUtil::getPassedValue('bundle_id', 0, 'REQUEST');
        $shop_id          = FormUtil::getPassedValue('shop_id', 0, 'REQUEST');
        $repo             = $this->entityManager->getRepository('ZSELEX_Entity_Shop');

        // echo "BunleID :" . $bundle_id; exit;
        // $getExistingBundle = ModUtil::apiFunc('ZSELEX', 'admin', 'getExistingBundle', $getargs = array('shop_id' => $shop_id));

        $getExistingBundle = $repo->get(array(
            'entity' => 'ZSELEX_Entity_ServiceBundle',
            'where' => array(
                'a.shop' => $shop_id,
                'a.bundle' => $bundle_id
            ),
            'joins' => array(
                'JOIN a.bundle b'
            ),
            'fields' => array(
                'a.service_status',
                'a.timer_date',
                'a.timer_days',
                'b.bundle_name'
            )
        ));
        //echo "<pre>"; print_r($getExistingBundle); echo "</pre>"; exit;

        $getBundle = $repo->get(array(
            'entity' => 'ZSELEX_Entity_Bundle',
            'where' => array(
                'a.bundle_id' => $bundle_id
            ),
            'fields' => array(
                'a.bundle_name'
            )
        ));

        $data       = '';
        $view->assign('current_bundle_name', $getExistingBundle ['bundle_name']);
        $view->assign('new_bundle_name', $getBundle ['bundle_name']);
        $output_tpl = $view->fetch('ajax/downgrade_adt_bundle_confirm.tpl');
        $data .= new Zikula_Response_Ajax_Plain($output_tpl);

        // $output['current_bundle_name'] = $getExistingBundle['bundle_name'];
        // $output['new_bundle_name'] = $getBundle['bundle_name'];
        $output ['error'] = 0;
        $output ['data']  = $data;

        AjaxUtil::output($output);
    }
    /*
     * Downgrades Additional Bundle
     *
     * @return Response
     */

    function downgradeAdditionalBundle()
    {
        error_reporting(0);
        $output ['error'] = 1;
        $bundle_id        = FormUtil::getPassedValue('bundle_id', 0, 'REQUEST');
        $shop_id          = FormUtil::getPassedValue('shop_id', 0, 'REQUEST');
        $repo             = $this->entityManager->getRepository('ZSELEX_Entity_Shop');

        $getExistingBundle = $repo->get(array(
            'entity' => 'ZSELEX_Entity_ServiceBundle',
            'where' => array(
                'a.shop' => $shop_id,
                'a.bundle' => $bundle_id
            ),
            'fields' => array(
                'a.service_status',
                'a.timer_date',
                'a.timer_days',
                'a.quantity'
            )
        ));
        //echo "<pre>"; print_r($getExistingBundle); echo "</pre>"; exit;

        $service_status = $getExistingBundle ['service_status'];
        $timer_date     = $getExistingBundle ['timer_date'];
        $timer_days     = $getExistingBundle ['timer_days'];
        $quantity       = $getExistingBundle ['quantity'];


        $getBundle = $repo->get(array(
            'entity' => 'ZSELEX_Entity_Bundle',
            'where' => array(
                'a.bundle_id' => $bundle_id
            )
            )
            // 'fields' => array('a.service_status', 'a.timer_date', 'a.timer_days')
        );

        //   echo "<pre>"; print_r($getBundle); echo "</pre>"; exit;


        $getBundleItems = $repo->getAll(array(
            'entity' => 'ZSELEX_Entity_BundleItem',
            'where' => array(
                'a.bundle' => $bundle_id
            )
            )
            // 'fields' => array('a.service_status', 'a.timer_date', 'a.timer_days')
        );

        //echo "<pre>"; print_r($getBundleItems); echo "</pre>"; exit;


        $user_id   = UserUtil::getVar('uid');
        $ownerInfo = ModUtil::apiFunc('ZSELEX', 'admin', 'getOwnerInfo',
                array(
                'shop_id' => $shop_id
        ));

        $owner_id = $ownerInfo ['user_id'];

        if ($quantity > 1) {
            $update_item = array(
                'quantity' => $quantity - 1,
            );
            $result      = $repo->updateEntity(null,
                'ZSELEX_Entity_ServiceBundle', $update_item,
                array(
                'a.shop' => $shop_id,
                'a.bundle' => $bundle_id
            ));
        } else {
            $result = $repo->deleteEntity(null, 'ZSELEX_Entity_ServiceBundle',
                array(
                'a.shop' => $shop_id,
                'a.bundle' => $bundle_id
            ));
        }

        $currentQty = $quantity - 1;

        if ($result) {
            $output ['error'] = 0;
        }

        foreach ($getBundleItems as $key => $val) {

            $getItem = $repo->get(array(
                'entity' => 'ZSELEX_Entity_ServiceShop',
                'where' => array(
                    'a.shop' => $shop_id,
                    'a.type' => $val['servicetype']
                ),
                'fields' => array(
                    'a.quantity'
                )
            ));

            $newQty = $getItem['quantity'] - $val['qty'];
            if ($newQty > 0) {
                $update_item2 = array(
                    'quantity' => $newQty,
                );
                $result2      = $repo->updateEntity(null,
                    'ZSELEX_Entity_ServiceShop', $update_item2,
                    array(
                    'a.shop' => $shop_id,
                    'a.type' => $val['servicetype']
                ));
            } else {
                $deleteService = $repo->deleteEntity(null,
                    'ZSELEX_Entity_ServiceShop',
                    array(
                    'a.shop' => $shop_id,
                    'a.type' => $val['servicetype']
                ));
            }
        }

        AjaxUtil::output($output);
    }

    /**
     * Delete Shop request form
     *
     * @return ajax response
     */
    function deleteShopRequest()
    {

        $shopId = FormUtil::getPassedValue('shop_id', 0, 'REQUEST');
        $repo   = $this->entityManager->getRepository('ZSELEX_Entity_Shop');
        $view   = Zikula_View::getInstance($this->name);

        $perm = ModUtil::apiFunc('ZSELEX', 'admin', 'shopPermission',
                array(
                'shop_id' => $shopId,
                'user_id' => $userId
        ));

        // echo "perm :" . $perm; exit;
        if (!$perm) {
            $output['perm_error'] = 1;
            AjaxUtil::output($output);
        }

        $getArgs          = array(
            'entity' => 'ZSELEX_Entity_Shop',
            'fields' => array('a.status'),
            'where' => array('a.shop_id' => $shopId)
        );
        $getStatus        = $repo->get($getArgs);
        //echo "<pre>"; print_r($getStatus); echo "</pre>"; exit;
        $data             = '';
        $view->assign('shopId', $shopId);
        $output_tpl       = $view->fetch('ajax/deleteShop.tpl');
        $data .= new Zikula_Response_Ajax_Plain($output_tpl);
        $output['status'] = $getStatus['status'];
        $output['data']   = $data;
        AjaxUtil::output($output);
    }

    /**
     * Delete Shop confirm request form
     *
     * @return ajax response
     */
    function deleteShopConfirm()
    {

        error_reporting(0);
        $shopId     = FormUtil::getPassedValue('shop_id', 0, 'REQUEST');
        $deleteDesc = FormUtil::getPassedValue('delete_desc', null, 'REQUEST');
        $repo       = $this->entityManager->getRepository('ZSELEX_Entity_Shop');
        $view       = Zikula_View::getInstance($this->name);


        $loguser = UserUtil::getVar('uid');
        $loguser = !empty($loguser) ? $loguser : 0;
        $userId  = $loguser;
        $perm    = ModUtil::apiFunc('ZSELEX', 'admin', 'shopPermission',
                array(
                'shop_id' => $shopId,
                'user_id' => $userId
        ));

        // echo "perm :" . $perm; exit;
        if (!$perm) {
            $output['perm_error'] = 1;
            AjaxUtil::output($output);
        }

        $getArgs   = array(
            'entity' => 'ZSELEX_Entity_Shop',
            'fields' => array('a.shop_name'),
            'where' => array('a.shop_id' => $shopId)
        );
        $getShop   = $repo->get($getArgs);
        /*
          $ownerName = $this->entityManager->getRepository('ZSELEX_Entity_ShopOwner')->getOwner(array(
          'shop_id' => $shopId));
         */
        $ownerInfo = ModUtil::apiFunc('ZSELEX', 'admin', 'getOwnerInfo',
                $args      = array(
                'shop_id' => $shopId
        ));
        // echo "<pre>"; print_r($ownerInfo); echo "</pre>"; exit;

        $success      = 0;
        $item         = array(
            'status' => 0
        );
        $updateStatus = $repo->updateEntity(null, 'ZSELEX_Entity_Shop', $item,
            array(
            'a.shop_id' => $shopId
        ));
        //echo "<pre>"; print_r($getStatus); echo "</pre>"; exit;
        $message      = '';
        if ($updateStatus) {
            $message .= $this->__('Shop ID').' : '.$shopId.'<br>';
            $message .= $this->__('Shop Name').' : '.$getShop['shop_name'].'<br>';
            $message .= $this->__('Owner Name').' : '.$ownerInfo['uname'].'<br>';
            $message .= $this->__('Owner Email').' : '.$ownerInfo['email'].'<br>';
            $message .= $this->__('Server').' : '.$_SERVER ['SERVER_NAME'].'<br>';
            $message .= $this->__('Request Date').' : '.date('Y-m-d h:i:s a',
                    time()).'<br>';
            $message .= $this->__('Reason').' : '.$deleteDesc.'<br>';

            $success   = 1;
            $emails [] = 'sharazkhanz@gmail.com';
            $emails [] = 'kim@acta-it.dk';
            foreach ($emails as $email) {
                $mailer_args = array(
                    'toaddress' => $email,
                    'fromname' => 'ZSELEX',
                    'subject' => $this->__("Shop Delete Request"),
                    'body' => $message,
                    'html' => true
                );

                $sent = @ModUtil::apiFunc('Mailer', 'user', 'sendMessage',
                        $mailer_args);
            }
        }

        $output['success'] = $success;
        AjaxUtil::output($output);
    }
}
?>