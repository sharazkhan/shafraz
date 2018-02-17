<?php

/**
 * ZAPP
 * User controller class
 *
 */

/**
 * Class to control User interface
 */
class ZAPP_Controller_User extends Zikula_AbstractController {

    public function locate() {
        //echo System::getBaseUrl(); exit;

        $baseurl = System::getBaseUrl();
        // echo "hellooo"; exit;
        //setcookie("user", "", time()-3600);
        setcookie("country_cookie", "", -1, '/');
        setcookie("cityname_cookie", "", -1, '/');
        setcookie("city_cookie", "", -1, '/');
        setcookie("regionname_cookie", "", -1, '/');
        setcookie("region_cookie", "", -1, '/');
        setcookie("areaname_cookie", "", -1, '/');
        setcookie("area_cookie", "", -1, '/');
        setcookie("branch_cookie", "", -1, '/');
        setcookie("affiliate_cookie", "", -1, '/');

        $_SESSION['country_cookie'] = 0;
        $_SESSION['city_cookie'] = 0;
        $_SESSION['region_cookie'] = 0;
        $_SESSION['area_cookie'] = 0;
        $_SESSION['branch_cookie'] = 0;
        $_SESSION['affiliate_cookie'] = 0;


        // echo "<pre>";   print_r($_REQUEST);   echo "</pre>"; exit;
        // exit;

        $Country = $_REQUEST['country'];
        $Region = $_REQUEST['region'];
        $City = $_REQUEST['city'];
        $Street = $_REQUEST['street'];
        if (isset($_REQUEST['adress'])) {
            $Adress = $_REQUEST['adress'];
        } elseif (isset($_REQUEST['address'])) {
            $Adress = $_REQUEST['address'];
        }
        $Level = $_REQUEST['level'];
        $countryRepo = $this->entityManager->getRepository('ZSELEX_Entity_Country');
        $Level = 'shop'; //
        if (!empty($Country)) {


            $countryobj = $countryRepo->getAppCountry(array('country' => $Country));


            // echo "<pre>";   print_r($countryobj);   echo "</pre>"; exit;
            $country_id = $countryobj['country_id'];
            $country_name = $countryobj['country_name'];
            if ($country_id > 0) {
                // echo "country here"; exit;
                setcookie("country_cookie", $country_id, time() + 604800, '/');
                $_SESSION['country_cookie'] = $country_id;
            }
        }

        if ($Level == 'city' || $Level == 'street' || $Level == 'shop') {
            if (!empty($Region)) {
                $this->getAppRegion($Region, $type = 'name');
            }
        }

        if ($Level == 'city' || $Level == 'street' || $Level == 'shop') {
            if (!empty($City)) {
                $this->getAppCity($City, $type = 'name');
            }
        }

        if ($Level == 'street' || $Level == 'shop') {
            if (!empty($Adress)) {
                if (strpos($Adress, '|') !== false) {
                    // echo 'true';
                    $Adresses = explode('|', $Adress);
                    // echo "<pre>";    print_r($Regions);  echo "</pre>";  exit;
                    $AdressLike = '';
                    $addr_join = '';
                    $addr_coma_join = '';
                    foreach ($Adresses as $addr) {
                        //echo $val . '<br>';
                        if (!empty($addr)) {
                            $addr_join .= $addr . " ";
                            $addr_coma_join .= $addr . ",";
                            // $AdressLike .= "OR address LIKE '%" . DataUtil::formatForStore($addr) . "%' ";
                            if (!empty($addr)) {
                                $AdressLike .= "OR MATCH(a.address) AGAINST('" . DataUtil::formatForStore($addr) . "') ";
                            }
                        }
                    }
                    $addr_join = substr($addr_join, 0, -1);
                    $addr_coma_join = substr($addr_coma_join, 0, -1);
                    $AdressLike = substr($AdressLike, 2);

                    //echo $RegionLike;
                } else {
                    // $AdressLike = "OR address LIKE '%" . DataUtil::formatForStore($Adress) . "%' ";
                    if (!empty($Adress)) {
                        $AdressLike .= " MATCH(a.address) AGAINST('" . DataUtil::formatForStore($Adress) . "') ";
                    }
                }
                // $AdressLike .= "OR address LIKE '%" . DataUtil::formatForStore($addr_join) . "%' ";
                if (!empty($addr_join)) {
                    $AdressLike .= " OR MATCH(a.address) AGAINST('" . DataUtil::formatForStore($addr_join) . "') ";
                }
// $AdressLike .= "OR address LIKE '%" . DataUtil::formatForStore($addr_coma_join) . "%' ";
                if (!empty($addr_coma_join)) {
                    $AdressLike .= " OR MATCH(a.address) AGAINST('" . DataUtil::formatForStore($addr_coma_join) . "') ";
                }
                // echo $addr_join;
                //  $AdressLike = substr($AdressLike, 2);
                $AdressLike = " " . $AdressLike . " ";
                // echo $AdressLike;  exit;

                /*
                  $addrobj = ModUtil::apiFunc('ZSELEX', 'user', 'get', $args = array(
                  'table' => 'zselex_shop',
                  'where' => $AdressLike,
                  'fields' => array('shop_id', 'city_id', 'region_id', 'country_id', 'area_id')
                  ));
                 */

                $addrobj = $countryRepo->getAppShopAddress(array('addressQry' => $AdressLike));
                $city_id = $addrobj[0]['city_id'];
                if ($city_id > 0) {
                    $this->getAppCity($city_id, $type = 'id');
                }
                $region_id = $addrobj[0]['region_id'];
                if ($region_id > 0) {
                    $this->getAppRegion($region_id, $type = 'id');
                }
                $area_id = $addrobj[0]['area_id'];
                if ($area_id > 0) {
                    $this->getAppArea($area_id);
                }

                //setcookie("areaname_cookie", $area_name, time() + 604800, '/');
                // setcookie("area_cookie", $area_id, time() + 604800, '/');
            }
        }
        // $this->view->clear_cache();
        // LogUtil::registerStatus($this->__('We didnt find any matches!.'));
        $url = $baseurl;
        $aff_ids = $_REQUEST['aff_id'];
        $branch_id = $_REQUEST['branch_id'];
        $page = $_REQUEST['page'];
        $lock = $_REQUEST['lock'];
        if ($page == 'shoplist') {
            $url .= "shoplists";
        }

        //$this->redirect(ModUtil::url())
        // echo "<pre>"; print_r($aff_ids);  echo "</pre>";  exit;;
        $aff_id_url = '';
        if (!empty($aff_ids) || !empty($branch_id)) {
            $url .= '?';
        }
        if (!empty($aff_ids)) {
            $url_params[] = "aff_id=" . $aff_ids;
        }
        //echo $aff_id_url; exit;
        //echo $baseurl; exit;


        if ($lock > 0) {
            $url_params[] = "lock=1";
        }

        if ($branch_id > 0) {
            $url_params[] = "branch_id=$branch_id";
        }
        $params = '';
        if (!empty($url_params)) {
            $params = implode('&', $url_params);
            $url .= $params;
        }
        // echo $params; exit;
        // echo "<pre>"; print_r($url_params);  echo "</pre>";  exit;
        // echo $url;  exit;

        return $this->redirect($url . $aff_id_url);
    }

    public function locate1() {
        //echo System::getBaseUrl(); exit;

        $baseurl = System::getBaseUrl();
        // echo "hellooo"; exit;
        //setcookie("user", "", time()-3600);
        setcookie("country_cookie", "", -1, '/');
        setcookie("cityname_cookie", "", -1, '/');
        setcookie("city_cookie", "", -1, '/');
        setcookie("regionname_cookie", "", -1, '/');
        setcookie("region_cookie", "", -1, '/');
        setcookie("areaname_cookie", "", -1, '/');
        setcookie("area_cookie", "", -1, '/');
        setcookie("branch_cookie", "", -1, '/');
        setcookie("affiliate_cookie", "", -1, '/');

        $_SESSION['country_cookie'] = 0;
        $_SESSION['city_cookie'] = 0;
        $_SESSION['region_cookie'] = 0;
        $_SESSION['area_cookie'] = 0;
        $_SESSION['branch_cookie'] = 0;
        $_SESSION['affiliate_cookie'] = 0;


        // echo "<pre>";   print_r($_REQUEST);   echo "</pre>"; exit;
        // exit;

        $Country = $_REQUEST['country'];
        $Region = $_REQUEST['region'];
        $City = $_REQUEST['city'];
        $Street = $_REQUEST['street'];
        if (isset($_REQUEST['adress'])) {
            $Adress = $_REQUEST['adress'];
        } elseif (isset($_REQUEST['address'])) {
            $Adress = $_REQUEST['address'];
        }
        $Level = $_REQUEST['level'];
        $countryRepo = $this->entityManager->getRepository('ZSELEX_Entity_Country');
        $Level = 'shop'; //
        if (!empty($Country)) {


            $countryobj = $countryRepo->getAppCountry(array('country' => $Country));


            // echo "<pre>";   print_r($countryobj);   echo "</pre>"; exit;
            $country_id = $countryobj['country_id'];
            $country_name = $countryobj['country_name'];
            if ($country_id > 0) {
                // echo "country here"; exit;
                setcookie("country_cookie", $country_id, time() + 604800, '/');
                $_SESSION['country_cookie'] = $country_id;
            }
        }

        if ($Level == 'city' || $Level == 'street' || $Level == 'shop') {
            if (!empty($Region)) {
                $this->getAppRegion($Region, $type = 'name');
            }
        }

        if ($Level == 'city' || $Level == 'street' || $Level == 'shop') {
            if (!empty($City)) {
                $this->getAppCity($City, $type = 'name');
            }
        }

        if ($Level == 'street' || $Level == 'shop') {
            if (!empty($Adress)) {
                if (strpos($Adress, '|') !== false) {
                    // echo 'true';
                    $Adresses = explode('|', $Adress);
                    // echo "<pre>";    print_r($Regions);  echo "</pre>";  exit;
                    $AdressLike = '';
                    $addr_join = '';
                    $addr_coma_join = '';
                    foreach ($Adresses as $addr) {
                        //echo $val . '<br>';
                        if (!empty($addr)) {
                            $addr_join .= $addr . " ";
                            $addr_coma_join .= $addr . ",";
                            // $AdressLike .= "OR address LIKE '%" . DataUtil::formatForStore($addr) . "%' ";
                            if (!empty($addr)) {
                                $AdressLike .= "OR MATCH(a.address) AGAINST('" . DataUtil::formatForStore($addr) . "') ";
                            }
                        }
                    }
                    $addr_join = substr($addr_join, 0, -1);
                    $addr_coma_join = substr($addr_coma_join, 0, -1);
                    $AdressLike = substr($AdressLike, 2);

                    //echo $RegionLike;
                } else {
                    // $AdressLike = "OR address LIKE '%" . DataUtil::formatForStore($Adress) . "%' ";
                    if (!empty($Adress)) {
                        $AdressLike .= " MATCH(a.address) AGAINST('" . DataUtil::formatForStore($Adress) . "') ";
                    }
                }
                // $AdressLike .= "OR address LIKE '%" . DataUtil::formatForStore($addr_join) . "%' ";
                if (!empty($addr_join)) {
                    $AdressLike .= " OR MATCH(a.address) AGAINST('" . DataUtil::formatForStore($addr_join) . "') ";
                }
// $AdressLike .= "OR address LIKE '%" . DataUtil::formatForStore($addr_coma_join) . "%' ";
                if (!empty($addr_coma_join)) {
                    $AdressLike .= " OR MATCH(a.address) AGAINST('" . DataUtil::formatForStore($addr_coma_join) . "') ";
                }
                // echo $addr_join;
                //  $AdressLike = substr($AdressLike, 2);
                $AdressLike = " " . $AdressLike . " ";
                // echo $AdressLike;  exit;

                /*
                  $addrobj = ModUtil::apiFunc('ZSELEX', 'user', 'get', $args = array(
                  'table' => 'zselex_shop',
                  'where' => $AdressLike,
                  'fields' => array('shop_id', 'city_id', 'region_id', 'country_id', 'area_id')
                  ));
                 */

                $addrobj = $countryRepo->getAppShopAddress(array('addressQry' => $AdressLike));
                $city_id = $addrobj[0]['city_id'];
                if ($city_id > 0) {
                    $this->getAppCity($city_id, $type = 'id');
                }
                $region_id = $addrobj[0]['region_id'];
                if ($region_id > 0) {
                    $this->getAppRegion($region_id, $type = 'id');
                }
                $area_id = $addrobj[0]['area_id'];
                if ($area_id > 0) {
                    $this->getAppArea($area_id);
                }

                //setcookie("areaname_cookie", $area_name, time() + 604800, '/');
                // setcookie("area_cookie", $area_id, time() + 604800, '/');
            }
        }
        // $this->view->clear_cache();
        // LogUtil::registerStatus($this->__('We didnt find any matches!.'));
        $url = $baseurl;
        $aff_ids = $_REQUEST['aff_id'];
        $branch_id = $_REQUEST['branch_id'];
        $page = $_REQUEST['page'];
        if ($page == 'shoplist') {
            $url .= "shoplists";
        }

        //$this->redirect(ModUtil::url())
        // echo "<pre>"; print_r($aff_ids);  echo "</pre>";  exit;;
        $aff_id_url = '';
        if (!empty($aff_ids) || !empty($branch_id)) {
            $url .= '?';
        }
        if (!empty($aff_ids)) {
            // $aff_id_url = '?';
            //$url .= '?';
            foreach ($aff_ids as $val) {
                //$aff_id_url .= "aff_id[]=" . $val . "&";
                $url_params[] = "aff_id[]=" . $val;
            }
            //$aff_id_url = substr($aff_id_url, 0, -1);
            // $url .= $aff_id_url;
        }
        //echo $aff_id_url; exit;
        //echo $baseurl; exit;


        if ($branch_id > 0) {
            //$url .= "&branch_id=$branch_id";
            $url_params[] = "branch_id=$branch_id";
        }
        $params = '';
        if (!empty($url_params)) {
            $params = implode('&', $url_params);
            $url .= $params;
        }
        // echo $params; exit;
        // echo "<pre>"; print_r($url_params);  echo "</pre>";  exit;
        // echo $url; exit;
        return $this->redirect($url . $aff_id_url);
    }

    function getAppRegion($Region, $type) {
        $repo = $this->entityManager->getRepository('ZSELEX_Entity_Country');
        if ($type == 'name') {
            if (strpos($Region, '|') !== false) {
                // echo 'true';
                $Regions = explode('|', $Region);
                // echo "<pre>";    print_r($Regions);  echo "</pre>";  exit;
                $RegionLike = '';
                foreach ($Regions as $reg_name) {
                    //echo $val . '<br>';
                    //$RegionLike .= "OR region_name LIKE '%" . DataUtil::formatForStore($reg_name) . "%' ";
                    $RegionLike .= "OR MATCH(a.region_name) AGAINST('" . DataUtil::formatForStore($reg_name) . "') ";
                }
                $RegionLike = substr($RegionLike, 2);

                //echo $RegionLike;
            } else {
                // $RegionLike = "region_name LIKE '%" . DataUtil::formatForStore($Region) . "%' ";
                $RegionLike = "MATCH(a.region_name) AGAINST('" . DataUtil::formatForStore($Region) . "') ";
            }

            $RegionLike = " " . $RegionLike . " ";
        } elseif ($type == 'id') {
            $RegionLike = "a.region_id=$Region";
        }
        // echo $RegionLike;  exit;

        /*
          $regionobj = ModUtil::apiFunc('ZSELEX', 'user', 'get', $args = array(
          'table' => 'zselex_region',
          // 'where' => "region_name LIKE '%" . DataUtil::formatForStore($Region) . "%' ",
          'where' => $RegionLike,
          'fields' => array('region_id', 'region_name')
          ));
         */

        $regionobj = $repo->getAppRegion(array('regionQry' => $RegionLike));


        // echo "<pre>";    print_r($regionobj);  echo "</pre>"; 
        if ($regionobj == true) {
            $region_id = $regionobj['region_id'];
            $region_name = $regionobj['region_name'];
            if ($region_id > 0) {
                setcookie("regionname_cookie", $region_name, time() + 604800, '/');
                setcookie("region_cookie", $region_id, time() + 604800, '/');
                $_SESSION['region_cookie'] = $region_id;
            }
        }
        return true;
    }

    function getAppCity($City, $type) {
        //echo $City; exit;
        $repo = $this->entityManager->getRepository('ZSELEX_Entity_Country');
        if ($type == 'name') {
            // echo "name"; exit;
            if (strpos($City, '|') !== false) {
                // echo 'true';
                $Cities = explode('|', $City);
                // echo "<pre>";    print_r($Regions);  echo "</pre>";  exit;
                $CityLike = '';
                foreach ($Cities as $cit_name) {
                    //echo $val . '<br>';
                    // $CityLike .= "OR city_name LIKE '%" . DataUtil::formatForStore($cit_name) . "%' ";
                    $CityLike .= "OR MATCH(a.city_name) AGAINST('" . DataUtil::formatForStore($cit_name) . "') ";
                }
                $CityLike = substr($CityLike, 2);

                //echo $RegionLike;
            } else {
                // $CityLike = "city_name LIKE '%" . DataUtil::formatForStore($City) . "%' ";
                $CityLike = "MATCH(a.city_name) AGAINST('" . DataUtil::formatForStore($City) . "') ";
            }
            //$CityLike = substr($CityLike, 2);
            $CityLike = " " . $CityLike . " ";
        } elseif ($type == 'id') {
            //echo "id"; exit;
            $CityLike = "a.city_id=$City";
        }
        // echo $CityLike; exit;
        /*
          $cityobj = ModUtil::apiFunc('ZSELEX', 'user', 'get', $args = array(
          'table' => 'zselex_city',
          'where' => $CityLike,
          'fields' => array('city_id', 'city_name')
          ));
         */
        $cityobj = $repo->getAppCity(array('cityQry' => $CityLike));
        //echo "<pre>";   print_r($cityobj);  echo "</pre>";  exit;



        if ($cityobj == true) {
            $city_id = $cityobj['city_id'];
            $city_name = $cityobj['city_name'];
            if ($city_id > 0) {
                setcookie("cityname_cookie", $city_name, time() + 604800, '/');
                setcookie("city_cookie", $city_id, time() + 604800, '/');
                $_SESSION['city_cookie'] = $city_id;
                if ($_SESSION['region_cookie'] < 1) {
                    setcookie("region_cookie", $cityobj['region_id'], time() + 604800, '/');
                    setcookie("regionname_cookie", $cityobj['region_name'], time() + 604800, '/');
                }
            }
        }
        return true;
    }

    function getAppArea($Area) {

        $repo = $this->entityManager->getRepository('ZSELEX_Entity_Country');
        $AreaLike = "a.area_id=$Area";
        //echo $AreaLike; exit;
        /*
          $areaobj = ModUtil::apiFunc('ZSELEX', 'user', 'get', $args = array(
          'table' => 'zselex_area',
          'where' => $AreaLike,
          'fields' => array('area_id', 'area_name')
          ));
         */
        $areaobj = $repo->getAppArea(array('areaQry' => $AreaLike));
        // echo "<pre>";  print_r($areaobj);  echo "</pre>";  exit;

        $area_id = $areaobj['area_id'];
        $area_name = $areaobj['area_name'];
        if ($area_id > 0) {
            setcookie("areaname_cookie", $area_name, time() + 604800, '/');
            setcookie("area_cookie", $area_id, time() + 604800, '/');
            $_SESSION['area_cookie'] = $area_id;
            if ($_SESSION['region_cookie'] < 1) {
                setcookie("region_cookie", $areaobj['region_id'], time() + 604800, '/');
                setcookie("regionname_cookie", $areaobj['region_name'], time() + 604800, '/');
            }
            if ($_SESSION['city_cookie'] < 1) {
                setcookie("city_cookie", $areaobj['city_id'], time() + 604800, '/');
                setcookie("cityname_cookie", $areaobj['city_name'], time() + 604800, '/');
            }
        }
        return true;
    }

}

// end class def