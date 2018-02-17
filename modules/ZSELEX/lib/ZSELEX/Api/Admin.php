<?php

class ZSELEX_Api_Admin extends ZSELEX_Api_Base_Admin
{

    /**
     * Get all service identifiers
     * 
     * @param type $args
     * @return array
     */
    public function getServiceIdentifiers($args)
    {
        $extrasql = $args ['sql'];
        $sql      = "SELECT id,name,identifier,description,status
                     FROM zselex_service_identifiers WHERE id!=''"." ".$extrasql;

        $res    = DBUtil::executeSQL($sql, $args ['start'] - 1,
                $args ['itemsperpage']);
        $result = DBUtil::marshallObjects($res);

        $rescount = DBUtil::executeSQL($sql);
        $count    = $rescount->rowCount();

        $returnarray = array(
            'items' => $result,
            'count' => $count
        );

        return $returnarray;
    }

    public function getServiceBundles($args)
    {
        $extrasql = $args ['sql'];
        $sql      = "SELECT bundle_id,bundle_name,bundle_description,bundle_type,bundle_price,calculated_price,sort_order
                FROM zselex_service_bundles WHERE bundle_id!=''"." ".$extrasql;

        $res    = DBUtil::executeSQL($sql, $args ['start'] - 1,
                $args ['itemsperpage']);
        $result = DBUtil::marshallObjects($res);

        $rescount = DBUtil::executeSQL($sql);
        $count    = $rescount->rowCount();

        $returnarray = array(
            'items' => $result,
            'count' => $count
        );

        return $returnarray;
    }

    public function searchArray($array, $type)
    {
        foreach ($array as $item) {
            if ($item ['type'] == $type) return true;
        }
        return false;
    }

    public function canBuyStatusBundle($args)
    { // api for checking the buy staus based on dependency for bundle services
        // error_reporting(0);
        // exit;
        $repo     = $this->entityManager->getRepository('ZSELEX_Entity_Bundle');
        $bundleId = $args ['bundleId'];
        $shop_id  = $args ['shop_id'];
        $shoptype = ModUtil::apiFunc('ZSELEX', 'admin', 'shopType',
                $typeargs = array(
                'shop_id' => $shop_id
        ));
        $shoptype = $shoptype ['shoptype'];

        $result = $repo->getAll(array(
            'entity' => 'ZSELEX_Entity_BundleItem',
            'fields' => array(
                'b.plugin_id',
                'a.servicetype',
                'a.service_name',
                'b.service_depended',
                'b.depended_services',
                'b.shop_depended'
            ),
            'joins' => array(
                'LEFT JOIN a.plugin b'
            ),
            'where' => array(
                'a.bundle' => $bundleId,
                ' b.status' => 1
            )
        ));
        // echo "<pre>"; print_r($result); echo "</pre>";

        $cantbuy         = 0;
        $shopdepended    = 0;
        $finalservices   = array();
        $depend_services = array();
        $typeonly        = array();
        $b               = 0;
        foreach ($result as $key => $val) { // loop through bundle item services
            // $typeonly= array();
            $servicetype1 = $val ['servicetype'];
            // echo $servicetype1 . '<br>';
            $typeonly []  = $servicetype1;
            // echo "<pre>"; print_r($typeonly); echo "</pre>";
            // echo $servicetype . "-". $val['shop_depended'] .'<br>';
            if ($val ['service_depended'] == 1) { // check for depended services
                // echo $val['depended_services'] . '<br><br><br>';
                // echo $servicetype . "-". $val['shop_depended'] .'<br>';
                $depend_services = unserialize($val ['depended_services']); // convert to array
                $depend_services = (array) $depend_services;
                // echo "<pre>"; print_r($depend_services); echo "</pre>";
                // echo "<pre>"; print_r($newArr); echo "</pre>";

                foreach ($depend_services as $key1 => $val1) { // loop through depended services of each bundle item services
                    // echo $val1['type'] . '<br>';
                    // echo $key1 . '<br>';
                    $serviceType = $val1 ['type'];

                    $countItemInBundle = $repo->getCount(null,
                        'ZSELEX_Entity_BundleItem', 'id',
                        array(
                        'a.bundle' => $bundleId,
                        'a.servicetype' => $serviceType
                    ));
                    // echo $countItemInBundle . "<br>";
                    if ($countItemInBundle == 0) {

                        $serviceargs = array(
                            'shop_id' => $shop_id,
                            'service_type' => $serviceType
                        );
                        // echo "<pre>"; print_r($serviceargs); echo "</pre>";
                        $servicePerm = ModUtil::apiFunc('ZSELEX', 'admin',
                                'serviceExpiryCheck', $serviceargs);
                        $count       = $servicePerm;
                        // echo $count . '<br>';
                        if ($count < 1) {
                            $cantbuy ++;
                        }
                    }
                    if ($val ['shop_depended'] == 1) { // check for shop depended for depended services
                        $shop_depend_count = $repo->getCount2(array(
                            'entity' => 'ZSELEX_Entity_Plugin',
                            'field' => 'plugin_id',
                            'where' => "a.type=:servicetype AND (a.depended_shoptypes LIKE :shoptype OR a.depended_shoptypes LIKE '')",
                            'setParams' => array(
                                'servicetype' => $serviceType,
                                'shoptype' => "%".DataUtil::formatForStore($shoptype)."%"
                            )
                        ));

                        // echo $shop_depend_count . "<br>";
                        // echo $serviceType ."-". $ishopcount . '<br>';
                        if ($shop_depend_count < 1) {
                            $cantbuy ++; // shop based
                            $shopdepended ++;
                        }
                    }

                    if ($countItemInBundle == 0) {
                        $depend_services [$key1] ['canbuystatus'] = ($count > 0)
                                ? '<font color="green"><b>'.$this->__('bought').'</b></font>'
                                : '<b>'.$this->__('not bought').'</b>';

                        if (!$this->searchArray($finalservices, $val1 ['type'])) {
                            $finalservices [] = array(
                                'plugin_id' => $val1 ['plugin_id'],
                                'type' => $val1 ['type'],
                                'name' => $val1 ['name'],
                                'canbuystatus' => $depend_services [$key1] ['canbuystatus']
                            );
                            // unset($finalservices);
                        }
                    }

                    // echo $depend_services[$key]['canbuystatus'] . '<br>';
                    $b ++;
                }

                // echo "<br>";
            }
        }
        // echo "<pre>"; print_r($typeonly); echo "</pre>";
        if ($shopdepended > 0) {
            $msg = $this->__("Not applicable for this shop");
        } else {
            $msg = $this->__("Depended services has not been bought");
        }

        // echo "<pre>"; print_r($allservices); echo "</pre>";
        $returnArray = array(
            'depended_services' => $finalservices,
            'cantbuy' => $cantbuy,
            'msg' => $msg
        );
        // echo "<pre>"; print_r($returnArray); echo "</pre>";
        return $returnArray;
    }

    public function canBuyStatus($args)
    { // api for checking the buy staus based on dependency for normal services
        // error_reporting(0);
        // echo "hellooo";
        // $depend_services = array();
        // echo "<pre>"; print_r($args); echo "</pre>";
        $repo = $this->entityManager->getRepository('ZSELEX_Entity_Plugin');
        $type = $args ['type'];

        $depend_services  = unserialize($args ['depended_services']); // convert to array
        $service_depended = $args ['service_depended'];
        if (!empty($depend_services)) {
            $depend_services = unserialize($args ['depended_services']);
        } else {
            $depend_services = array();
        }

        $shop_depended = $args ['shop_depended'];
        $shop_id       = $args ['shop_id'];
        $shoptype      = ModUtil::apiFunc('ZSELEX', 'admin', 'shopType',
                $typeargs      = array(
                'shop_id' => $shop_id
        ));
        $shoptype      = $shoptype ['shoptype'];
        $cantbuy       = 0;
        $shopdepended  = 0;
        if ($shop_depended) {

            $shop_depend_count1 = $this->entityManager->getRepository('ZSELEX_Entity_Plugin')->shopDependedCount(array(
                'type' => $type,
                'shoptype' => $shoptype
            ));
            // echo "count :" . $shop_depend_count1;
            // echo $serviceType ."-". $ishopcount . '<br>'; exit;
            if ($shop_depend_count1 < 1) {
                $cantbuy ++; // shop based
                $shopdepended ++;
            }
        }
        // echo "count :" .count($depend_services);
        // echo "<pre>"; print_r($depend_services); echo "</pre>";
        if ($service_depended) {
            foreach ($depend_services as $key => $val) { // checking if depended services are purchased or not
                // echo $val['type'] . '<br>';
                $serviceType = $val ['type'];
                // if (!empty($serviceType)) {

                $serviceargs = array(
                    'shop_id' => $shop_id,
                    'service_type' => $serviceType
                );
                $servicePerm = ModUtil::apiFunc('ZSELEX', 'admin',
                        'serviceExpiryCheck', $serviceargs);
                // echo $count . '<br>';
                $count       = $servicePerm;
                if ($count < 1) {
                    $cantbuy ++; // not purchased
                }

                $is_shop_depended = $repo->get(array(
                    'entity' => 'ZSELEX_Entity_Plugin',
                    'fields' => array(
                        'a.shop_depended'
                    ),
                    'where' => array(
                        'a.type' => $serviceType
                    )
                ));

                // echo "<pre>"; print_r($is_shop_depended); echo "</pre>";
                // echo $serviceType ."-". $ishopcount . '<br>';

                if ($is_shop_depended ['shop_depended'] == 1) {

                    $shop_depend_count = $this->entityManager->getRepository('ZSELEX_Entity_Plugin')->shopDependedCount(array(
                        'type' => $serviceType,
                        'shoptype' => $shoptype
                    ));
                    // echo "count :" . $shop_depend_count;
                    // echo $serviceType ."-". $ishopcount . '<br>';
                    if ($shop_depend_count < 1) {
                        $cantbuy ++; // shop based
                        $shopdepended ++;
                    }
                }

                $depend_services [$key] ['canbuystatus'] = ($count == 1) ? '<font color="green"><b>'.$this->__('bought').'</b></font>'
                        : '<b>'.$this->__('not bought').'</b>';
                // echo $depend_services[$key]['canbuystatus'] . '<br>';
                // }
            }
        } else {
            $depend_services = array();
        }

        if ($shopdepended > 0) {
            $msg = $this->__("Not applicable for this shop");
        } else {
            $msg = $this->__("Depended services has not been bought");
        }

        // echo "<pre>"; print_r($depend_services); echo "</pre>";
        // echo $cantbuy . '<br>';
        $returnArray = array(
            'depended_services' => $depend_services,
            'cantbuy' => $cantbuy,
            'msg' => $msg
        );
        return $returnArray;
    }

    public function serviceExpiryCheck($args)
    {
        $shop_id      = $args ['shop_id'];
        $service_type = $args ['service_type'];

        $service_exist = $this->entityManager->getRepository('ZSELEX_Entity_ServiceShop')->getCount(array(
            'type' => $service_type,
            'shop_id' => $shop_id
        ));
        // echo $service_exist . '<br>';
        if (!$service_exist) {
            return 0; // not purchased
        }
        $today = date("Y-m-d");

        $service  = $this->entityManager->getRepository('ZSELEX_Entity_ServiceShop')->getService(array(
            'type' => $service_type,
            'shop_id' => $shop_id
        ));
        // echo "<pre>"; print_r($service); echo "</pre>";
        // echo "<pre>"; print_r($services); echo "</pre>";
        // $dateDiff = $this->dateDiff($service['timer_date'], $today);
        // $dateDiff = $service['timer_days'] - $dateDiff;
        $dateDiff = $service ['days'];
        if ($dateDiff < 0) { // expired!
            return 0;
        }
        return 1;
    }

    public function canBuyStatus1($args)
    { // api for checking the buy staus based on dependency for normal services
        // error_reporting(0);
        // echo "hellooo";
        // $depend_services = array();
        $type = $args ['type'];

        $depend_services  = unserialize($args ['depended_services']); // convert to array
        $service_depended = $args ['service_depended'];
        if (!empty($depend_services)) {
            $depend_services = unserialize($args ['depended_services']);
        } else {
            $depend_services = array();
        }

        $shop_depended = $args ['shop_depended'];
        $shop_id       = $args ['shop_id'];
        $shoptype      = ModUtil::apiFunc('ZSELEX', 'admin', 'shopType',
                $typeargs      = array(
                'shop_id' => $shop_id
        ));
        $shoptype      = $shoptype ['shoptype'];
        $cantbuy       = 0;
        $shopdepended  = 0;
        if ($shop_depended) {
            $shop_depend_count1 = ModUtil::apiFunc('ZSELEX', 'admin',
                    'getCount',
                    $args               = array(
                    'table' => 'zselex_plugin',
                    "where" => "type='".$type."' AND (depended_shoptypes LIKE '%".$shoptype."%' OR depended_shoptypes LIKE '')"
            ));
            // echo $serviceType ."-". $ishopcount . '<br>'; exit;
            if ($shop_depend_count1 < 1) {
                $cantbuy ++; // shop based
                $shopdepended ++;
            }
        }
        // echo "count :" .count($depend_services);
        // echo "<pre>"; print_r($depend_services); echo "</pre>";
        if ($service_depended) {
            foreach ($depend_services as $key => $val) { // checking if depended services are purchased or not
                // echo $val['type'] . '<br>';
                $serviceType = $val ['type'];
                // if (!empty($serviceType)) {
                $count       = ModUtil::apiFunc('ZSELEX', 'admin', 'getCount',
                        $args        = array(
                        'table' => 'zselex_serviceshop',
                        "where" => "shop_id=$shop_id AND type='".$serviceType."'"
                ));
                // echo $count . '<br>';
                if ($count < 1) {
                    $cantbuy ++; // not purchased
                }
                $is_shop_depended = ModUtil::apiFunc('ZSELEX', 'user',
                        'selectRow',
                        array(
                        'table' => 'zselex_plugin',
                        'where' => array(
                            "type='".$serviceType."'"
                        )
                ));
                // echo $serviceType ."-". $ishopcount . '<br>';

                if ($is_shop_depended ['shop_depended'] == 1) {
                    $shop_depend_count = ModUtil::apiFunc('ZSELEX', 'admin',
                            'getCount',
                            $args              = array(
                            'table' => 'zselex_plugin',
                            "where" => "type='".$serviceType."' AND (depended_shoptypes LIKE '%".$shoptype."%' OR depended_shoptypes LIKE '')"
                    ));
                    // echo $serviceType ."-". $ishopcount . '<br>';
                    if ($shop_depend_count < 1) {
                        $cantbuy ++; // shop based
                        $shopdepended ++;
                    }
                }

                $depend_services [$key] ['canbuystatus'] = ($count == 1) ? '<font color="green"><b>'.$this->__('bought').'</b></font>'
                        : '<b>'.$this->__('not bought').'</b>';
                // echo $depend_services[$key]['canbuystatus'] . '<br>';
                // }
            }
        } else {
            $depend_services = array();
        }

        if ($shopdepended > 0) {
            $msg = $this->__("Not applicable for this shop");
        } else {
            $msg = $this->__("Depended services has not been bought");
        }

        // echo "<pre>"; print_r($depend_services); echo "</pre>";
        // echo $cantbuy . '<br>';
        $returnArray = array(
            'depended_services' => $depend_services,
            'cantbuy' => $cantbuy,
            'msg' => $msg
        );
        return $returnArray;
    }

    /**
     * Get all bundles to list in service page
     *
     * @return array
     */
    public function getBundles()
    {
        $Repo = $this->entityManager->getRepository('ZSELEX_Entity_Bundle');

        $result = $Repo->getAll(array(
            'entity' => 'ZSELEX_Entity_Bundle',
            'where' => array(
                'a.status' => 1
            ),
            'fields' => array(
                'a.bundle_id',
                'a.bundle_name',
                'a.type',
                'a.bundle_price',
                'a.calculated_price',
                'a.bundle_description',
                'a.content',
                'a.bundle_type',
                'a.demo',
                'a.demoperiod',
                'a.sort_order',
                'a.status',
                'a.is_free'
            ),
            'orderby' => 'a.sort_order ASC'
            )
            // 'orderby' => 'CASE WHEN (a.sort_order = 0) THEN 999999 ELSE a.sort_order END'
        );
        return $result;
    }

    public function ervicePurchased()
    {
        $sql    = "SELECT bundle_id , bundle_name , type , bundle_price , calculated_price , bundle_description , content , bundle_type , demo , demoperiod , sort_order , status
                FROM zselex_service_bundles 
                WHERE status=1 ORDER BY IF(sort_order = 0, 999999999, sort_order) ASC";
        $query  = DBUtil::executeSQL($sql);
        $result = $query->fetchAll();
        return $result;
    }

    public function updateMinisite($args)
    {
        $MinisiteUpdateRepo = $this->entityManager->getRepository('ZSELEX_Entity_MinisiteUpdate');
        $shop_id            = $args ['shop_id'];
        $is_new             = $args ['is_new'];
        if ($is_new) {
            $update_val = 0;
        } else {
            $update_val = 1;
        }

        $ownerInfo = ModUtil::apiFunc('ZSELEX', 'admin', 'getOwnerInfo',
                $args      = array(
                'shop_id' => $shop_id
        ));

        // echo "<pre>"; print_r($ownerInfo); echo "</pre>"; exit;

        $owner_id = $ownerInfo ['uid'];

        $count       = $MinisiteUpdateRepo->getCount(array(
            'entity' => 'ZSELEX_Entity_MinisiteUpdate',
            'field' => 'id',
            'where' => array(
                'a.shop_id' => $shop_id
            )
        ));
        // echo $count; exit;
        $currentdate = date("Y-m-d");

        $item = array(
            'shop_id' => $shop_id,
            'owner_id' => $owner_id,
            'update_date' => $currentdate,
            'is_updated_recent' => $update_val
        );

        if ($count < 1) {
            // insert

            $result = $MinisiteUpdateRepo->createMinisiteUpdate($item);
        } else {
            // update

            $update = $MinisiteUpdateRepo->updateEntity(null,
                'ZSELEX_Entity_MinisiteUpdate', $item,
                array(
                'a.shop_id' => $shop_id
            ));
        }
        if ($result) {
            return true;
        }
    }

    public function checkFilename($args)
    {
        $uploadPath    = $args ['upload_path'];
        $fileName      = $args ['file_name'];
        $maxsize_regex = preg_match("/^(?'size'[\\d]+)(?'rang'[a-z]{0,1})$/i",
            $maxFileSize, $match);
        $maxSize       = 4 * 1024 * 1024; // default 4 M
        if ($maxsize_regex && is_numeric($match ['size'])) {
            switch (strtoupper($match ['rang'])) { // 1024 or 1000??
                case 'K' :
                    $maxSize = $match [1] * 1024;
                    break;
                case 'M' :
                    $maxSize = $match [1] * 1024 * 1024;
                    break;
                case 'G' :
                    $maxSize = $match [1] * 1024 * 1024 * 1024;
                    break;
                case 'T' :
                    $maxSize = $match [1] * 1024 * 1024 * 1024 * 1024;
                    break;
                default :
                    $maxSize = $match [1]; // default 4 M
            }
        }

        // -----------------End max file size check
        // comment if not using windows web server
        $windowsReserved = array(
            'CON',
            'PRN',
            'AUX',
            'NUL',
            'COM1',
            'COM2',
            'COM3',
            'COM4',
            'COM5',
            'COM6',
            'COM7',
            'COM8',
            'COM9',
            'LPT1',
            'LPT2',
            'LPT3',
            'LPT4',
            'LPT5',
            'LPT6',
            'LPT7',
            'LPT8',
            'LPT9'
        );
        $badWinChars     = array_merge(array_map('chr', range(0, 31)),
            array(
            "<",
            ">",
            ":",
            '"',
            "/",
            "\\",
            "|",
            "?",
            "*"
        ));

        $fileName = str_replace($badWinChars, '', $fileName);
        $fileInfo = pathinfo($fileName);
        $fileExt  = $fileInfo ['extension'];
        $fileBase = $fileInfo ['filename'];

        $fullPath = $uploadPath.'/'.$fileName;
        // echo $fullPath; exit;
        $c        = 0;
        while (file_exists($fullPath)) {
            $c ++;
            $fileName = $fileBase."$c.".$fileExt;
            $fullPath = $uploadPath.$fileName;
        }
        // echo $fullPath; die;
        return $fileName;

        // return array('filename' => $fileName, 'fullpath' => $fullPath);
    }
    /*
     * resize banner image
     *
     * @param $args['filename']
     * @param $args['destination']
     * @return image
     */

    public function bannerResize($args)
    {
        // echo "<pre>"; print_r($args); echo "</pre>"; exit;
        // The file
        // $filename = 'FLAIR-banner.jpg';
        // $filename = 'PROFILOPTIK-banner.jpg';
        $filename = $args ['filename'];
        if ($args ['crop']) {
            $filename = $args ['destination'].'resized/'.$filename;
        } else {
            $filename = $args ['destination'].$filename;
        }
        // $filename = 'Banner.png';
        // $filename = '14ZGIFS1-articleLarge-v4.gif';
        $savePath = $args ['destination'].'resized/';

        $extension = strrchr($filename, '.');
        $extension = strtolower($extension);
        // echo $extension; exit;

        if ($extension == '.jpg') {
            $basename = 'modules/ZSELEX/images/2048x320.jpg';

            list ( $width, $height ) = getimagesize($filename);
            $new_height = 320;
            $new_width  = $width * (320 / $height);

            // Resample
            $image_resampled = imagecreatetruecolor($new_width, $new_height);
            $image_base      = imagecreatefromjpeg($filename);

            // (dst, src, dst_X, dst_Y, src_X, src_Y, dst_W, dst_H, src_W, src_H)
            imagecopyresampled($image_resampled, $image_base, 0, 0, 0, 0,
                $new_width, $new_height, $width, $height);

            if ($new_width >= 2048) {
                $image_out = $image_resampled;
            } else {
                // if just as it is then this:
                $image_out = imagecreatefromjpeg($basename);
                imagecopyresized($image_out, $image_resampled,
                    (2048 - $new_width) / 2, 0, 0, 0, $new_width, 320,
                    $new_width, 320);
                // if resize to max then this:
                // $image_out = $image_base;
                // if transform with streatch effect then this:
                // This code fills left and right side of image with one pixel in full height from each side of the image to fill the whitespace... KIMENEMARK
                // $image_out = imagecreatefromjpeg($basename);
                // imagecopyresized($image_out, $image_resampled, 0, 0, 0, 0, (2048 - $new_width) / 2, 320, 1, 320);
                // imagecopyresized($image_out, $image_resampled, (2048 - $new_width) / 2, 0, 0, 0, $new_width, 320, $new_width, 320);
                // imagecopyresized($image_out, $image_resampled, (2048 - $new_width) / 2 + $new_width - 1, 0, $new_width - 1, 0, (2048 - $new_width) / 2, 320, 1, 320);
            }

            // Output
            // imagejpeg($image_out, null, 100);

            return imagejpeg($image_out, $savePath.$args ['filename'], 95); // 100 = 100%
        } elseif ($extension == '.png') {
            $basename = 'modules/ZSELEX/images/2048x320.png';

            list ( $width, $height ) = getimagesize($filename);
            $new_height = 320;
            $new_width  = $width * (320 / $height);

            // Resample
            $image_resampled = imagecreatetruecolor($new_width, $new_height);
            $image_base      = @imagecreatefrompng($filename);

            // (dst, src, dst_X, dst_Y, src_X, src_Y, dst_W, dst_H, src_W, src_H)
            imagecopyresampled($image_resampled, $image_base, 0, 0, 0, 0,
                $new_width, $new_height, $width, $height);

            if ($new_width >= 2048) {
                // echo "comes here1"; exit;
                $image_out = $image_resampled;
            } else {
                // echo "comes here"; exit;
                $image_out = @imagecreatefrompng($basename);
                // imagecopyresized($image_out, $image_resampled, 0, 0, 0, 0, (2048 - $new_width) / 2, 320, 1, 320);
                imagecopyresized($image_out, $image_resampled,
                    (2048 - $new_width) / 2, 0, 0, 0, $new_width, 320,
                    $new_width, 320);
                // imagecopyresized($image_out, $image_resampled, (2048 - $new_width) / 2 + $new_width - 1, 0, $new_width - 1, 0, (2048 - $new_width) / 2, 320, 1, 320);
            }

            // Output
            // imagejpeg($image_out, null, 100);
            $scaleQuality = round((100 / 100) * 9);

            // *** Invert quality setting as 0 is best, not 9
            $invertScaleQuality = 9 - $scaleQuality;

            return imagepng($image_out, $savePath.$args ['filename'],
                $invertScaleQuality);
        } elseif ($extension == '.gif') {
            $basename = 'modules/ZSELEX/images/2048x320.gif';

            list ( $width, $height ) = getimagesize($filename);
            $new_height = 320;
            $new_width  = $width * (320 / $height);

            // Resample
            $image_resampled = imagecreatetruecolor($new_width, $new_height);
            $image_base      = @imagecreatefromgif($filename);

            // (dst, src, dst_X, dst_Y, src_X, src_Y, dst_W, dst_H, src_W, src_H)
            imagecopyresampled($image_resampled, $image_base, 0, 0, 0, 0,
                $new_width, $new_height, $width, $height);

            if ($new_width >= 2048) {
                // echo "comes here1"; exit;
                $image_out = $image_resampled;
            } else {
                // echo "comes here"; exit;
                $image_out = @imagecreatefromgif($basename);
                // imagecopyresized($image_out, $image_resampled, 0, 0, 0, 0, (2048 - $new_width) / 2, 320, 1, 320);
                imagecopyresized($image_out, $image_resampled,
                    (2048 - $new_width) / 2, 0, 0, 0, $new_width, 320,
                    $new_width, 320);
                // imagecopyresized($image_out, $image_resampled, (2048 - $new_width) / 2 + $new_width - 1, 0, $new_width - 1, 0, (2048 - $new_width) / 2, 320, 1, 320);
            }

            // echo $savePath; exit;
            return imagegif($image_out, $savePath.$args ['filename']);
        }
        // return true;
    }
    /*
     * resize banner image
     *
     * @param $args['filename']
     * @param $args['destination']
     * @return image
     */

    public function bannerStretch($args)
    {
        // echo "<pre>"; print_r($args); echo "</pre>"; exit;
        // The file
        // $filename = 'FLAIR-banner.jpg';
        // $filename = 'PROFILOPTIK-banner.jpg';
        $filename = $args ['filename'];
        // $filename = $args['destination'] . $filename;
        if ($args ['crop']) {
            $filename = $args ['destination'].'resized/'.$filename;
        } else {
            $filename = $args ['destination'].$filename;
        }
        // $filename = 'Banner.png';
        // $filename = '14ZGIFS1-articleLarge-v4.gif';
        $savePath = $args ['destination'].'resized/';

        $extension = strrchr($filename, '.');
        $extension = strtolower($extension);
        // echo $extension; exit;

        if ($extension == '.jpg') {
            $basename = 'modules/ZSELEX/images/2048x320.jpg';

            list ( $width, $height ) = getimagesize($filename);
            $new_height = 320;
            $new_width  = $width * (320 / $height);

            // Resample
            $image_resampled = imagecreatetruecolor($new_width, $new_height);
            $image_base      = imagecreatefromjpeg($filename);

            // (dst, src, dst_X, dst_Y, src_X, src_Y, dst_W, dst_H, src_W, src_H)
            imagecopyresampled($image_resampled, $image_base, 0, 0, 0, 0,
                $new_width, $new_height, $width, $height);

            $image_out = $image_resampled;

            return imagejpeg($image_out, $savePath.$args ['filename'], 95); // 100 = 100%
        } elseif ($extension == '.png') {
            $basename = 'modules/ZSELEX/images/2048x320.png';

            list ( $width, $height ) = getimagesize($filename);
            $new_height = 320;
            $new_width  = $width * (320 / $height);

            // Resample
            $image_resampled = imagecreatetruecolor($new_width, $new_height);
            $image_base      = @imagecreatefrompng($filename);

            // (dst, src, dst_X, dst_Y, src_X, src_Y, dst_W, dst_H, src_W, src_H)
            imagecopyresampled($image_resampled, $image_base, 0, 0, 0, 0,
                $new_width, $new_height, $width, $height);

            $image_out = $image_resampled;

            // Output
            // imagejpeg($image_out, null, 100);
            $scaleQuality = round((100 / 100) * 9);

            // *** Invert quality setting as 0 is best, not 9
            $invertScaleQuality = 9 - $scaleQuality;

            return imagepng($image_out, $savePath.$args ['filename'],
                $invertScaleQuality);
        } elseif ($extension == '.gif') {
            $basename = 'modules/ZSELEX/images/2048x320.gif';

            list ( $width, $height ) = getimagesize($filename);
            $new_height = 320;
            $new_width  = $width * (320 / $height);

            // Resample
            $image_resampled = imagecreatetruecolor($new_width, $new_height);
            $image_base      = @imagecreatefromgif($filename);

            // (dst, src, dst_X, dst_Y, src_X, src_Y, dst_W, dst_H, src_W, src_H)
            imagecopyresampled($image_resampled, $image_base, 0, 0, 0, 0,
                $new_width, $new_height, $width, $height);

            $image_out = $image_resampled;

            // echo $savePath; exit;
            return imagegif($image_out, $savePath.$args ['filename']);
        }
        // return true;
    }

    public function bannerResize_latest_not_using_now($args)
    {
        // echo "<pre>"; print_r($args); echo "</pre>"; exit;
        // The file
        // $filename = 'FLAIR-banner.jpg';
        // $filename = 'PROFILOPTIK-banner.jpg';
        $filename = $args ['filename'];
        $filename = $args ['destination'].$filename;
        // $filename = 'Banner.png';
        // $filename = '14ZGIFS1-articleLarge-v4.gif';
        $savePath = $args ['destination'].'resized/';

        $extension = strrchr($filename, '.');
        $extension = strtolower($extension);
        // echo $extension; exit;

        if ($extension == '.jpg') {
            $basename = 'modules/ZSELEX/images/2048x320.jpg';

            list ( $width, $height ) = getimagesize($filename);
            $new_height = 320;
            $new_width  = $width * (320 / $height);

            // Resample
            $image_resampled = imagecreatetruecolor($new_width, $new_height);
            $image_base      = imagecreatefromjpeg($filename);

            // (dst, src, dst_X, dst_Y, src_X, src_Y, dst_W, dst_H, src_W, src_H)
            imagecopyresampled($image_resampled, $image_base, 0, 0, 0, 0,
                $new_width, $new_height, $width, $height);
            /*
             * if ($new_width >= 2048) {
             * $image_out = $image_resampled;
             * } else {
             * // if just as it is then this:
             * $image_out = imagecreatefromjpeg($basename);
             * imagecopyresized($image_out, $image_resampled, (2048 - $new_width) / 2, 0, 0, 0, $new_width, 320, $new_width, 320);
             * // if resize to max then this:
             * // $image_out = $image_base;
             * // if transform with streatch effect then this:
             * // This code fills left and right side of image with one pixel in full height from each side of the image to fill the whitespace... KIMENEMARK
             * // $image_out = imagecreatefromjpeg($basename);
             * // imagecopyresized($image_out, $image_resampled, 0, 0, 0, 0, (2048 - $new_width) / 2, 320, 1, 320);
             * // imagecopyresized($image_out, $image_resampled, (2048 - $new_width) / 2, 0, 0, 0, $new_width, 320, $new_width, 320);
             * // imagecopyresized($image_out, $image_resampled, (2048 - $new_width) / 2 + $new_width - 1, 0, $new_width - 1, 0, (2048 - $new_width) / 2, 320, 1, 320);
             * }
             */
            $image_out = $image_resampled;

            // Output
            // imagejpeg($image_out, null, 100);

            return imagejpeg($image_out, $savePath.$args ['filename'], 95); // 100 = 100%
        } elseif ($extension == '.png') {
            $basename = 'modules/ZSELEX/images/2048x320.png';

            list ( $width, $height ) = getimagesize($filename);
            $new_height = 320;
            $new_width  = $width * (320 / $height);

            // Resample
            $image_resampled = imagecreatetruecolor($new_width, $new_height);
            $image_base      = @imagecreatefrompng($filename);

            // (dst, src, dst_X, dst_Y, src_X, src_Y, dst_W, dst_H, src_W, src_H)
            imagecopyresampled($image_resampled, $image_base, 0, 0, 0, 0,
                $new_width, $new_height, $width, $height);
            /*
             * if ($new_width >= 2048) {
             * // echo "comes here1"; exit;
             * $image_out = $image_resampled;
             * } else {
             * // echo "comes here"; exit;
             * $image_out = @imagecreatefrompng($basename);
             * imagecopyresized($image_out, $image_resampled, 0, 0, 0, 0, (2048 - $new_width) / 2, 320, 1, 320);
             * imagecopyresized($image_out, $image_resampled, (2048 - $new_width) / 2, 0, 0, 0, $new_width, 320, $new_width, 320);
             * imagecopyresized($image_out, $image_resampled, (2048 - $new_width) / 2 + $new_width - 1, 0, $new_width - 1, 0, (2048 - $new_width) / 2, 320, 1, 320);
             * }
             */
            $image_out = $image_resampled;

            // Output
            // imagejpeg($image_out, null, 100);
            $scaleQuality = round((100 / 100) * 9);

            // *** Invert quality setting as 0 is best, not 9
            $invertScaleQuality = 9 - $scaleQuality;

            return imagepng($image_out, $savePath.$args ['filename'],
                $invertScaleQuality);
        } elseif ($extension == '.gif') {
            $basename = 'modules/ZSELEX/images/2048x320.gif';

            list ( $width, $height ) = getimagesize($filename);
            $new_height = 320;
            $new_width  = $width * (320 / $height);

            // Resample
            $image_resampled = imagecreatetruecolor($new_width, $new_height);
            $image_base      = @imagecreatefromgif($filename);

            // (dst, src, dst_X, dst_Y, src_X, src_Y, dst_W, dst_H, src_W, src_H)
            imagecopyresampled($image_resampled, $image_base, 0, 0, 0, 0,
                $new_width, $new_height, $width, $height);
            /*
             * if ($new_width >= 2048) {
             * // echo "comes here1"; exit;
             * $image_out = $image_resampled;
             * } else {
             * // echo "comes here"; exit;
             * $image_out = @imagecreatefromgif($basename);
             * imagecopyresized($image_out, $image_resampled, 0, 0, 0, 0, (2048 - $new_width) / 2, 320, 1, 320);
             * imagecopyresized($image_out, $image_resampled, (2048 - $new_width) / 2, 0, 0, 0, $new_width, 320, $new_width, 320);
             * imagecopyresized($image_out, $image_resampled, (2048 - $new_width) / 2 + $new_width - 1, 0, $new_width - 1, 0, (2048 - $new_width) / 2, 320, 1, 320);
             * }
             */
            $image_out = $image_resampled;

            // echo $savePath; exit;
            return imagegif($image_out, $savePath.$args ['filename']);
        }
        // return true;
    }

    public function uploadEventImage($args)
    {
        $filename    = $args ['filename'];
        $destination = $args ['destination'];

        $allowedExtensions = array(
            'png',
            'jpg',
            'gif',
            'jpeg'
        );
        $ex                = end(explode(".", $name));
        if (!in_array($ex, $allowedExtensions)) {
            // return LogUtil::registerError($this->__f('Error! Invalid file type: %1$s', $ex));
        }
        $modvariable = $this->getVars();

        $fullWidth  = !empty($modvariable ['fullimagewidth']) ? $modvariable ['fullimagewidth']
                : 1024;
        $fullHeight = !empty($modvariable ['fullimageheight']) ? $modvariable ['fullimageheight']
                : 768;

        $medWidth  = !empty($modvariable ['medimagewidth']) ? $modvariable ['medimagewidth']
                : 800;
        $medHeight = !empty($modvariable ['medimageheight']) ? $modvariable ['medimageheight']
                : 500;

        $thumbWidth  = !empty($modvariable ['thumbimagewidth']) ? $modvariable ['thumbimagewidth']
                : 298;
        $thumbHeight = !empty($modvariable ['thumbimageheight']) ? $modvariable ['thumbimageheight']
                : 133;

        require_once ('modules/ZSELEX/lib/vendor/ImageResize.php');
        $resizeObj = new ImageResize($destination.$filename);
        $resizeObj->resizeImage($fullWidth, $fullHeight);
        $resizeObj->saveImage($destination.'fullsize/'.$filename, 100);

        $resizeObj->resizeImage($medWidth, $medHeight);
        $resizeObj->saveImage($destination.'medium/'.$filename, 100);

        $resizeObj->resizeImage($thumbWidth, $thumbHeight);
        $resizeObj->saveImage($destination.'thumb/'.$filename, 100);

        unlink($destination.$filename);

        return true;
    }

    public function mainBundleExist($args)
    {
        $shop_id = $args ['shop_id'];
        /*
         * $sql = "SELECT COUNT(*) as count FROM zselex_serviceshop a
         * LEFT JOIN zselex_service_bundles b ON a.bundle_id=b.bundle_id
         * WHERE b.bundle_type='main' AND a.shop_id=$shop_id";
         * $query = DBUtil::executeSQL($sql);
         * $result = $query->fetch();
         * $count = $result['count'];
         * return $count;
         */

        $repo  = $this->entityManager->getRepository('ZSELEX_Entity_ServiceShop');
        $count = $repo->mainBundleExist($args);

        // echo "<pre>"; print_r($result); echo "</pre>"; exit;

        return $count;
    }

    public function buttonShowCheck($args)
    {

        // echo "<pre>"; print_r($args); echo "</pre>";
        $repo       = $this->entityManager->getRepository('ZSELEX_Entity_Bundle');
        $shop_id    = $args ['shop_id'];
        $bundle_id  = $args ['bundle_id'];
        $sort_order = $args ['sort_order'];

        /*
         * $sql = "SELECT bundle_id , type FROM zselex_service_bundles
         * WHERE sort_order < $sort_order";
         * //echo $sql . '<br>';
         * $query = DBUtil::executeSQL($sql);
         * $result = $query->fetchAll();
         */
        // echo "<pre>"; print_r($result); echo "</pre>";
        $setArgs = array(
            'entity' => 'ZSELEX_Entity_Bundle',
            'fields' => array(
                'a.bundle_id',
                'a.type'
            ),
            'where' => 'a.sort_order < :sortorder',
            'setParams' => array(
                'sortorder' => $sort_order
            )
            )
        // 'orderby' => 'a.shop_event_id DESC'
        ;

        $result = $repo->fetchAll($setArgs);

        // echo "<pre>"; print_r($result); echo "</pre>";
        $c = 0;
        foreach ($result as $key => $val) {
            /*
             * $button_show_status = ModUtil::apiFunc('ZSELEX', 'admin', 'serviceExpiryCheck', $args = array(
             * 'shop_id' => $shop_id,
             * "service_type" => $val['type']
             * ));
             */

            /* To check if its already bought and to show higher bundles from there */
            /*
             * $button_show_status = ModUtil::apiFunc('ZSELEX', 'admin', 'getCount', $args = array(
             * 'table' => 'zselex_serviceshop_bundles',
             * 'where' => "shop_id=$shop_id AND bundle_id='" . $val['bundle_id'] . "' AND bundle_type='main'"));
             */
            $button_show_status = $repo->getCount(null,
                'ZSELEX_Entity_ServiceBundle', 'service_bundle_id',
                array(
                'a.shop' => $shop_id,
                'a.bundle' => $val ['bundle_id'],
                'a.bundle_type' => 'main'
            ));
            // echo $button_show_status . '<br>';
            if ($button_show_status) {
                $c ++;
            }
            // $c++;
        }

        return $c;
    }

    public function buttonShowCheckDemo($args)
    {

        // echo "<pre>"; print_r($args); echo "</pre>";
        $repo       = $this->entityManager->getRepository('ZSELEX_Entity_Bundle');
        $shop_id    = $args ['shop_id'];
        $bundle_id  = $args ['bundle_id'];
        $sort_order = $args ['sort_order'];

        $setArgs = array(
            'entity' => 'ZSELEX_Entity_Bundle',
            'fields' => array(
                'a.bundle_id',
                'a.type'
            ),
            'where' => 'a.sort_order < :sortorder',
            'setParams' => array(
                'sortorder' => $sort_order
            )
            )
        // 'orderby' => 'a.shop_event_id DESC'
        ;

        $result = $repo->fetchAll($setArgs);

        // echo "<pre>"; print_r($result); echo "</pre>";
        $c = 0;
        foreach ($result as $key => $val) {

            $button_show_status = $repo->getCount(null,
                'ZSELEX_Entity_ServiceDemo', 'demo_id',
                array(
                'a.shop' => $shop_id,
                'a.bundle' => $val ['bundle_id'],
                'a.bundle_type' => 'main'
            ));
            // echo $button_show_status . '<br>';
            if ($button_show_status) {
                $c ++;
            }
            // $c++;
        }

        return $c;
    }

    public function bundleExpiryCheck($args)
    {
        // echo "<pre>"; print_r($args); echo "</pre>";
        $repo      = $this->entityManager->getRepository('ZSELEX_Entity_Bundle');
        $shop_id   = $args ['shop_id'];
        $bundle_id = $args ['bundle_id'];
        $return    = array();
        /*
         * $service_exist = ModUtil::apiFunc('ZSELEX', 'admin', 'getCount', $args = array(
         * 'table' => 'zselex_service_demo',
         * "where" => "shop_id=$shop_id AND bundle_id='" . $bundle_id . "'"
         * ));
         */

        $service_exist = $repo->getCount(null, 'ZSELEX_Entity_ServiceDemo',
            'demo_id',
            array(
            'a.shop' => $shop_id,
            'a.bundle' => $bundle_id
        ));
        // echo $service_exist . '<br>';
        if (!$service_exist) {
            return array(
                'running' => 0
            ); // not purchased
        }
        $today       = date("Y-m-d");
        $joinInfo [] = array(
            'join_table' => 'zselex_service_bundles',
            'join_field' => array(
                'bundle_id',
                'bundle_type'
            ),
            'object_field_name' => array(
                'bundle_id',
                'bundle_type'
            ),
            'compare_field_table' => 'bundle_id', // main table
            'compare_field_join' => 'bundle_id'
        );
        /*
         * $service = ModUtil::apiFunc('ZSELEX', 'user', 'get', $getargs = array(
         * 'table' => 'zselex_service_demo',
         * // 'joinInfo' => $joinInfo,
         * 'where' => "shop_id=$shop_id AND bundle_id='" . $bundle_id . "'"));
         */

        $service = $repo->get(array(
            'entity' => 'ZSELEX_Entity_ServiceDemo',
            'fields' => array(
                'a.demo_id',
                'b.shop_id',
                'a.plugin_id',
                'a.type',
                'a.user_id',
                'a.owner_id',
                'a.quantity',
                'a.availed',
                'a.qty_based',
                'c.bundle_id',
                'a.top_bundle',
                'a.bundle_type',
                'a.status',
                'a.start_date',
                'a.timer_days'
            ),
            'joins' => array(
                'JOIN a.shop b',
                'JOIN a.bundle c'
            ),
            'where' => array(
                'a.shop' => $shop_id,
                'a.bundle' => $bundle_id
            )
        ));
        // echo "<pre>"; print_r($service);echo "</pre>";

        $dateDiff = $this->dateDiff($service ['start_date'], $today);
        $dateDiff = $service ['timer_days'] - $dateDiff;
        if ($dateDiff <= 0) { // expired!
            return array(
                'running' => 0,
                'expired' => 1,
                'bundle_status' => 1,
                'bundle_type' => $service ['bundle_type'],
                'time_left' => $dateDiff
            );
        }
        return array(
            'running' => 1,
            'expired' => 0,
            'bundle_status' => $service ['service_status'],
            'bundle_type' => $service ['bundle_type'],
            'time_left' => $dateDiff
        );
    }

    public function bundlePaidExpiryCheck($args)
    {
        // echo "<pre>"; print_r($args); echo "</pre>";
        $repo          = $this->entityManager->getRepository('ZSELEX_Entity_Bundle');
        $shop_id       = $args ['shop_id'];
        $bundle_id     = $args ['bundle_id'];
        $return        = array();
        /*
         * $service_exist = ModUtil::apiFunc('ZSELEX', 'admin', 'getCount', $args = array(
         * 'table' => 'zselex_serviceshop_bundles',
         * "where" => "shop_id=$shop_id AND bundle_type='main'"
         * ));
         */
        $service_exist = $repo->getCount(null, 'ZSELEX_Entity_ServiceBundle',
            'service_bundle_id',
            array(
            'a.shop' => $shop_id,
            'a.bundle_type' => 'main'
        ));
        // echo $service_exist . '<br>'; exit;
        if (!$service_exist) {
            return array(
                'running' => 0
            ); // not purchased
        }
        $today = date("Y-m-d");

        $service = $repo->get(array(
            'entity' => 'ZSELEX_Entity_ServiceBundle',
            'fields' => array(
                'a.timer_date',
                'a.timer_days',
                'a.bundle_type',
                'a.service_status'
            ),
            'where' => array(
                'a.bundle_type' => 'main',
                'a.shop' => $shop_id
            )
        ));
        // echo "<pre>"; print_r($service);echo "</pre>"; exit;

        $dateDiff = $this->dateDiff($service ['timer_date'], $today);
        $dateDiff = $service ['timer_days'] - $dateDiff;
        // echo $dateDiff; exit;
        if ($dateDiff <= 0) { // expired!
            return array(
                'running' => 0,
                'expired' => 1,
                'bundle_status' => 1,
                'bundle_type' => $service ['bundle_type'],
                'time_left' => $dateDiff
            );
        }
        return array(
            'running' => 1,
            'expired' => 0,
            'bundle_status' => $service ['service_status'],
            'bundle_type' => $service ['bundle_type'],
            'time_left' => $dateDiff
        );
    }

    public function getExistingBundle($args)
    {
        $repo = $this->entityManager->getRepository('ZSELEX_Entity_Bundle');

        $result = $repo->getAll(array(
            'entity' => 'ZSELEX_Entity_ServiceDemo',
            'fields' => array(
                'a.demo_id',
                'b.shop_id',
                'a.plugin_id',
                'a.type',
                'a.user_id',
                'a.owner_id',
                'a.quantity',
                'a.availed',
                'a.qty_based',
                'c.bundle_id',
                'a.top_bundle',
                'a.bundle_type',
                'a.status',
                'a.start_date',
                'a.timer_days'
            ),
            'where' => array(
                'a.top_bundle' => 1,
                'a.shop' => $args ['shop_id'],
                'a.bundle_type' => 'main'
            ),
            'joins' => array(
                'JOIN a.shop b',
                'JOIN a.bundle c'
            ),
            'groupby' => 'a.demo_id',
            'orderby' => 'a.demo_id ASC'
        ));

        $result = $result [0];

        // $result=$repo->getExistingBundle($args);
        // echo "<pre>"; print_r($result);echo "</pre>";
        return $result;
    }

    public function getExistingBundle1($args)
    {
        $sql    = "SELECT a.* , b.bundle_type FROM zselex_serviceshop a
             LEFT JOIN zselex_service_bundles b ON a.bundle_id=b.bundle_id
             AND a.top_bundle=1 AND a.shop_id=$args[shop_id] AND b.bundle_type='main'";
        $query  = DBUtil::executeSQL($sql);
        $result = $query->fetch();
        return $result;
    }

    public function updateAdUsed($args)
    {
        // $user_id = $args['user_id'];
        $adRepo  = $this->entityManager->getRepository('ZSELEX_Entity_Advertise');
        $shop_id = $args ['shop_id'];
        $used    = $args ['used'];
        $type    = $args ['type'];

        /*
         * $sql = "UPDATE zselex_serviceshop SET availed=availed+$used
         * WHERE shop_id='" . $shop_id . "' AND type='createad'";
         * //echo $sql; exit;
         * $query = DBUtil::executeSQL($sql);
         */

        $item      = $adRepo->get(array(
            'entity' => 'ZSELEX_Entity_ServiceShop',
            'fields' => array(
                'a.availed'
            ),
            'where' => array(
                'a.shop' => $shop_id,
                'a.type' => 'createad'
            )
        ));
        $availed   = $item ['availed'];
        $availeadd = $availed + $used;

        $query = $adRepo->updateEntity(array(
            'entity' => 'ZSELEX_Entity_ServiceShop',
            'fields' => array(
                'availed' => $availeadd
            ),
            'where' => array(
                'a.shop' => $shop_id,
                'a.type' => $type
            )
        ));
        return true;
    }

    public function deleteAdService($args)
    {
        $adRepo  = $this->entityManager->getRepository('ZSELEX_Entity_Advertise');
        $shop_id = $args ['shop_id'];
        // $serviceType = $args['servicetype'];
        $cost    = $args ['cost'];

        /*
         * $availed = $this->getSingleItem($args = array(
         * 'table' => 'zselex_serviceshop',
         * 'itemname' => 'availed',
         * 'where' => "shop_id=$shop_id AND type='createad'"
         * ));
         */
        $item    = $adRepo->get(array(
            'entity' => 'ZSELEX_Entity_ServiceShop',
            'fields' => array(
                'a.availed'
            ),
            'where' => array(
                'a.shop' => $shop_id,
                'a.type' => 'createad'
            )
        ));
        $availed = $item ['availed'];
        // echo "cost :" . $cost . "avialed:" .$availed; exit;
        if ($availed > 0) {
            if ($availed > 0) {
                $availedless = $availed - $cost;
            } else {
                $availedless = $availed;
            }

            /*
             * $sql = "UPDATE zselex_serviceshop SET availed=$availedless WHERE shop_id='" . $shop_id . "'
             * AND type='createad'";
             * // echo $sql; exit;
             * $query = DBUtil::executeSQL($sql);
             */

            $query = $adRepo->updateEntity(array(
                'entity' => 'ZSELEX_Entity_ServiceShop',
                'fields' => array(
                    'availed' => $availedless
                ),
                'where' => array(
                    'a.shop' => $shop_id,
                    'a.type' => 'createad'
                )
            ));

            return $query;
        }
    }

    /**
     * Purify POST values
     *
     * @param array $formElements
     * @return array
     */
    public function purifyHtml($formElements)
    {
        foreach ($formElements as $key => $val) {
            $formElements [$key] = is_string($val) ? trim(DataUtil::formatForStore($val))
                    : DataUtil::formatForStore($val);
        }
        return $formElements;
    }

    public function shopids($args)
    {

        // echo "<pre>"; print_r($args); echo "</pre>"; exit;
        /*
         * $sql = "SELECT a.shop_id
         * FROM zselex_shop a
         * WHERE a.shop_id IS NOT NULL AND a.status=1 " . $args['append'] . " LIMIT 0,$args[limit]";
         * // echo $sql;
         *
         * $query = DBUtil::executeSQL($sql);
         * $result = $query->fetchAll();
         */
        $result  = $this->entityManager->getRepository('ZSELEX_Entity_Shop')->getShopIds($args);
        // echo "<pre>"; print_r($result); echo "</pre>";
        $shopIDs = array();
        foreach ($result as $val) {
            $shop_id       = $val ['shop_id'];
            // echo $shop_id . '<br>';
            $minisiteExist = ModUtil::apiFunc('ZSELEX', 'admin',
                    'serviceExistBlock',
                    array(
                    'shop_id' => $shop_id,
                    'type' => $args ['type']
            ));

            // if (UserUtil::getVar('uid') == 3) {
            // echo $shop_id . " - " . $minisiteExist . '<br>';
            // }
            // echo $shop_id . " - " . $minisiteExist .'<br>';
            if ($minisiteExist) {
                $shopIDs [] = $shop_id;
            }
        }

        // echo "<pre>"; print_r($shopIDs); echo "</pre>";
        // echo "count :" . count($shopIDs);
        $count = count($shopIDs);
        if (!$count) {
            // echo "comes here";exit;
            $validShopIDs = 0;
            return $validShopIDs;
        }
        $validShopIDs = implode(',', $shopIDs);
        return $validShopIDs;
    }

    public function shopidsAds($args)
    {
        $append = $args ['append'];
        $offset = $args ['offset'];

        // echo "<pre>"; print_r($args); echo "</pre>"; exit;

        $shops = array();

        $result = $this->entityManager->getRepository('ZSELEX_Entity_Advertise')->getAdShops($args);
        // echo "<pre>"; print_r($result); echo "</pre>";
        if ($result) {
            // $shops = array_column($result, 'shop_id');
            $shops = $result;
        }
        // echo "<pre>"; print_r($shp); echo "</pre>";

        return $shops;
    }

    public function shopidsAds1($args)
    {
        $append = $args ['append'];
        $offset = $args ['offset'];

        // echo "<pre>"; print_r($args); echo "</pre>"; exit;
        $sql = "SELECT a.shop_name , b.advertise_id , b.adprice_id , b.shop_id , m.configured , m.shoptype
                    FROM zselex_shop a , zselex_advertise b , zselex_minishop m , zselex_advertise_price c
                    WHERE b.status='1'
                    ".$append."
                    AND a.shop_id=b.shop_id
                    AND b.shop_id=m.shop_id
                    AND c.adprice_id=b.adprice_id
                    AND (b.maxviews > b.totalviews OR b.maxviews = -1)
                    AND (b.maxclicks > b.totalclicks OR b.maxclicks = -1)
                    AND (b.startdate<=CURDATE() OR b.startdate =0 OR UNIX_TIMESTAMP(b.startdate)=0 OR b.startdate IS NULL)
                    AND (b.enddate>=CURDATE() OR b.enddate =0 OR UNIX_TIMESTAMP(b.enddate)=0 OR b.enddate IS NULL)
                    AND b.advertise_type='productAd' GROUP BY a.shop_id ORDER BY RAND() LIMIT 0 , $offset";
        // echo $sql; exit;

        $query  = DBUtil::executeSQL($sql);
        $result = $query->fetchAll();
        $shops  = array();
        foreach ($result as $val) {
            $shop_id      = $val ['shop_id'];
            // echo $shop_id . '<br>';
            $serviceExist = ModUtil::apiFunc('ZSELEX', 'admin',
                    'serviceExistBlock',
                    array(
                    'shop_id' => $shop_id,
                    'type' => 'createad'
            ));

            if ($serviceExist) {
                $shops [] = $val;
            }
        }

        return $shops;
    }

    public function saveShopCategories($args)
    {
        // echo "<pre>"; print_r($args); echo "</pre>"; exit;
        $categories = $args ['categories'];
        $shop_id    = $args ['shop_id'];
        if (empty($shop_id)) {
            return false;
        }

        $deleteCategories = $this->entityManager->getRepository('ZSELEX_Entity_Shop')->deleteShopCategories(array(
            'shop_id' => $shop_id
        ));

        $addCategories = $this->entityManager->getRepository('ZSELEX_Entity_Shop')->addShopCategories(array(
            'categories' => $categories,
            'shop_id' => $shop_id
        ));

        return true;
    }

    public function saveShopBranches($args)
    {
        // echo "<pre>"; print_r($args); echo "</pre>"; exit;
        $branches = $args ['branches'];
        $shop_id  = $args ['shop_id'];
        if (empty($shop_id)) {
            return false;
        }

        $deleteBranches = $this->entityManager->getRepository('ZSELEX_Entity_Shop')->deleteShopBranches(array(
            'shop_id' => $shop_id
        ));

        $addBranches = $this->entityManager->getRepository('ZSELEX_Entity_Shop')->addShopBranches(array(
            'branches' => $branches,
            'shop_id' => $shop_id
        ));

        return true;
    }

    function renameFolders($args)
    {
        // echo "exit here"; exit;
        $repo     = $this->entityManager->getRepository('ZSELEX_Entity_Shop');
        $getArgs  = array(
            'entity' => 'ZSELEX_Entity_Shop',
            'fields' => array(
                'a.shop_id'
            )
        );
        $getShops = $repo->getAll($getArgs);
        foreach ($getShops as $val) {
            $owner     = ModUtil::apiFunc('ZSELEX', 'admin', 'getOwnerInfo',
                    array(
                    'shop_id' => $val ['shop_id']
            ));
            // echo "<pre>"; print_r($owner); echo "</pre>";
            $ownerName = $owner ['uname'];
            $oldpath   = $_SERVER ['DOCUMENT_ROOT']."/zselexdata/".$ownerName;
            $newpath   = $_SERVER ['DOCUMENT_ROOT']."/zselexdata/".$val ['shop_id'];
            if ($_SERVER ['SERVER_NAME'] == 'localhost') {
                $oldpath = "zselexdata/".$ownerName;
                $newpath = "zselexdata/".$val ['shop_id'];
            }
            /*
             * if (file_exists($oldpath)) {
             * rename($oldpath, $newpath);
             * }
             */
            exec("cp -r $oldpath $newpath");
        }
        return true;
    }
}
?>