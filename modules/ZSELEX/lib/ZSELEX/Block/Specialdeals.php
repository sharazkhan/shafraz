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
class ZSELEX_Block_Specialdeals extends Zikula_Controller_AbstractBlock
{
    public $amount;

    /**
     * initialise block
     */
    public function init()
    {
        SecurityUtil::registerPermissionSchema('ZSELEX:specialdeals :',
            'Block title::');
    }

    /**
     * get information on block
     */
    public function info()
    {
        return array(
            'text_type' => 'selection',
            'module' => 'ZSELEX',
            'text_type_long' => $this->__('Specialdeals Block'),
            'allow_multiple' => true,
            'form_content' => false,
            'form_refresh' => false,
            'show_preview' => true,
            'admin_tableless' => true
        );
    }

    /**
     * display block
     *
     * @return html
     */
    public function display($blockinfo)
    {

        // return;
        if (!SecurityUtil::checkPermission('ZSELEX:specialdeals:',
                "$blockinfo[title]::", ACCESS_OVERVIEW)) {
            return;
        }
        if (!ModUtil::available('ZSELEX')) {
            return;
        }
        $loguser = UserUtil::getVar('uid');
        $loguser = !empty($loguser) ? $loguser : 0;
        $user_id = $loguser;
        $vars    = BlockUtil::varsFromContent($blockinfo ['content']);

        // echo "<pre>"; print_r($vars); echo "</pre>";

        $admin = SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN);
        $edit  = SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_EDIT);

        $thislang = ZLanguage::getLanguageCode();

        // $highAd = $this->getSpecialBlockProductAd($args);
        // echo "<pre>"; print_r($highAd); echo "</pre>";
        // $aff_id = $_REQUEST['aff_id'];
        // echo "<pre>"; print_r($aff_id); echo "</pre>";
        // echo $aff_id;
        $modvariable   = $this->getVars();
        $country_id    = $modvariable ['default_country_id'];
        $region_id     = $_COOKIE ['region_cookie'];
        $city_id       = $_COOKIE ['city_cookie'];
        $area_id       = $_COOKIE ['area_cookie'];
        $shop_id       = $_COOKIE ['shop_cookie'];
        $branch_id     = $_COOKIE ['branch_cookie'];
        $branch_id_url = FormUtil::getPassedValue("branch_id");
        if ($branch_id_url > 0) {
            $branch_id = $branch_id_url;
        }
        // echo "BranchId :" . $branch_id;
        $aff_id     = $_COOKIE ['affiliate_cookie'];
        $aff_id_url = FormUtil::getPassedValue("aff_id");
        if ($aff_id_url) {
            $aff_id = $aff_id_url;
        }
        $category_id  = $_COOKIE ['category_cookie'];
        $search       = stripslashes(htmlspecialchars($_COOKIE ['search_cookie']));
        $highad_limit = $vars ['highad_amount'];
        $midad_limit  = $vars ['midad_amount'];
        $lowad_limit  = $vars ['lowad_amount'];
        $event_limit  = $vars ['event_amount'];

        $highad_args = array(
            'ad_type' => 'high',
            'country_id' => $country_id,
            'region_id' => $region_id,
            'city_id' => $city_id,
            'area_id' => $area_id,
            'shop_id' => $shop_id,
            'category_id' => $category_id,
            'branch_id' => $branch_id,
            'aff_id' => $aff_id,
            'search' => $search,
            'limit' => $highad_limit
        );

        // echo "<pre>"; print_r($highad_args); echo "</pre>";

        $highad = ModUtil::apiFunc('ZSELEX', 'user', 'getSpecialDealAd',
                $highad_args);

        // echo "<pre>"; print_r($highad); echo "</pre>";

        $midad_args = array(
            'ad_type' => 'mid',
            'country_id' => $country_id,
            'region_id' => $region_id,
            'city_id' => $city_id,
            'area_id' => $area_id,
            'shop_id' => $shop_id,
            'category_id' => $category_id,
            'branch_id' => $branch_id,
            'aff_id' => $aff_id,
            'search' => $search,
            'limit' => $midad_limit
        );

        $midad = ModUtil::apiFunc('ZSELEX', 'user', 'getSpecialDealAd',
                $midad_args);

        // echo "<pre>"; print_r($midad); echo "</pre>";

        $lowad_args = array(
            'ad_type' => 'low',
            'country_id' => $country_id,
            'region_id' => $region_id,
            'city_id' => $city_id,
            'area_id' => $area_id,
            'shop_id' => $shop_id,
            'category_id' => $category_id,
            'branch_id' => $branch_id,
            'aff_id' => $aff_id,
            'search' => $search,
            'limit' => $lowad_limit
        );

        $lowad      = ModUtil::apiFunc('ZSELEX', 'user', 'getSpecialDealAd',
                $lowad_args);
        // echo "<pre>"; print_r($lowad); echo "</pre>";
        $lowadCount = count($lowad);

        $event_args = array(
            'country_id' => $country_id,
            'region_id' => $region_id,
            'city_id' => $city_id,
            'area_id' => $area_id,
            'shop_id' => $shop_id,
            'category_id' => $category_id,
            'branch_id' => $branch_id,
            'aff_id' => $aff_id,
            'search' => $search,
            'limit' => $event_limit
        );

        // echo "<pre>"; print_r($event_args); echo "</pre>";

        $events     = ModUtil::apiFunc('ZSELEX', 'user',
                'getSpecialBlockEvents', $event_args);
        $eventcount = count($events);
        // echo "<pre>"; print_r($highad); echo "</pre>";
        // echo "<pre>"; print_r($midad); echo "</pre>";
        // echo "<pre>"; print_r($events); echo "</pre>";
        $midadCount = count($midad);
        $lowadCount = count($lowad);
        $this->view->assign('admin', $admin);
        $this->view->assign('bid', $blockinfo ['bid']);
        $this->view->assign('highad', $highad);
        $this->view->assign('midad', $midad);
        $this->view->assign('lowad', $lowad);
        $this->view->assign('midadCount', $midadCount);
        $this->view->assign('lowad', $lowad);
        $this->view->assign('lowadCount', $lowadCount);
        $this->view->assign('events', $events);
        $this->view->assign('eventcount', $eventcount);

        $this->view->assign('vars', $vars);
        $blockinfo ['content'] = $this->view->fetch('blocks/specialdeals/specialdeals.tpl');

        return BlockUtil::themeBlock($blockinfo);
    }

    public function getSpecialBlockProductAd($args)
    { // For Special Deal Products High Advertisement Block
        $shop_id     = $_REQUEST ["shop_id"];
        $modvariable = $this->getVars();
        $country_id  = $modvariable ['default_country_id'];
        // $country_id = '61';
        $region_id   = $_REQUEST ["region_id"];
        $city_id     = $_REQUEST ["city_id"];
        $area_id     = $_REQUEST ["area_id"];
        $category_id = $_REQUEST ["category_id"];
        $branch_id   = $_REQUEST ["branch_id"];
        $adtype      = $_REQUEST ["adtype"];
        $amount      = $_REQUEST ["amount"];
        $search      = $_REQUEST ["hsearch"];
        $search      = ($search == $this->__('search for...') || $search == $this->__('search'))
                ? '' : $search;
        // $themepath = $this->current_theme;

        $append = '';

        // echo "<pre>"; print_r($_SESSION['shoparray']); echo "</pre>"; exit;
        // AjaxUtil::output($amount); exit;
        $counts = '';
        if (($shop_id > 0 || !empty($shop_id)) || ($region_id > 0 || !empty($region_id))
            || ($city_id > 0 || !empty($city_id)) || ($country_id > 0 || !empty($country_id))
            || ($area_id > 0 || !empty($area_id)) || (!empty($search)) || ($category_id
            > 0 || !empty($category_id)) || ($branch_id > 0 || !empty($branch_id))) {
            $catquery = '';
            $catshop  = '';

            if ($category_id != '' && $country_id <= 0 && $region_id <= 0 && $city_id
                <= 0 && $area_id <= 0 && $shop_id <= 0) { // Only category is selected
                $catshop = " AND a.cat_id=$category_id";
            }

            $branchquery = '';
            $branchshop  = '';

            if ($branch_id != '' && $country_id <= 0 && $region_id <= 0 && $city_id
                <= 0 && $area_id <= 0 && $shop_id <= 0) { // Only branch is selected
                $branchshop = " AND a.branch_id=$branch_id";
            }

            $searchquery = '';

            $searchquerymain = '';
            if (!empty($search) && $country_id <= 0 && $region_id <= 0 && $city_id
                <= 0 && $area_id <= 0 && $shop_id <= 0) { // Only search is typed
                $searchquerymain = " AND b.keywords LIKE '%".DataUtil::formatForStore($search)."%'";
            }

            if (!empty($category_id)) {
                $append .= " AND a.cat_id=$category_id";
            }

            if (!empty($branch_id)) {
                $append .= " AND a.branch_id=$branch_id";
            }

            if (!empty($search)) { // for search with others
                $append .= " AND b.keywords LIKE '%".DataUtil::formatForStore($search)."%'";
            }

            $adquery = '';
            // if ($adtype != '') {
            $append .= " AND b.adprice_id='1'";
            // }

            $items = array(
                'id' => $shop_id
            );
            // $shops = ModUtil::apiFunc('ZSELEX', 'admin', 'getShop', $items);
            $where = '';

            if (($country_id > 0) or ( $country_id > 0 && $region_id < 0 && $city_id
                < 0 && $shop_id < 0)) { // COUNTRY
                $where = " AND b.country_id=$country_id AND b.level='COUNTRY' AND a.shop_id!=''  $append";
            }

            if (($region_id > 0) or ( $country_id > 0 && $region_id > 0 && $city_id
                < 0 && $shop_id < 0)) { // REGION
                $where = " AND b.region_id=$region_id AND b.level='REGION' AND a.shop_id!=''  $append";
            }

            if (($city_id > 0) or ( $region_id > 0 && $city_id > 0 && $country_id
                > 0 && $shop_id < 0)) { // CITY
                $where = " AND b.city_id=$city_id AND b.level='CITY' AND a.shop_id!=''  $append";
            }

            if (($area_id > 0) or ( $region_id > 0 && $area_id > 0 && $city_id > 0
                && $country_id > 0 && $shop_id < 0)) { // AREA
                $where = " AND b.area_id=$area_id AND b.level='AREA' AND a.shop_id!=''  $append";
            }

            if (($shop_id > 0) or ( $shop_id > 0 && $region_id > 0 && $city_id > 0
                && $country_id > 0)) { // SHOP
                $where = " AND b.shop_id=$shop_id AND b.level='SHOP' AND a.shop_id!=''  $append";
            }

            if (($shop_id < 0 || empty($shop_id)) && ($region_id < 0 || empty($region_id))
                && ($city_id < 0 || empty($city_id)) && ($country_id < 0 || empty($country_id))
                && ($area_id < 0 || empty($area_id))) {
                // $where = " WHERE a.shop_id!='' $adquery $catquery $branchquery $searchquery";
            }

            $sql = "SELECT a.* , b.* , m.* FROM zselex_shop a , zselex_advertise b , zselex_minishop m
                    WHERE b.status='1'
                    AND a.shop_id=b.shop_id
                    $where $catshop $branchshop $searchquerymain
                    AND a.shop_id=m.shop_id
                    AND b.maxviews > b.totalviews AND  b.maxclicks > b.totalclicks AND b.startdate<=CURDATE() AND b.enddate>=CURDATE() 
                    AND b.advertise_type='productAd' ORDER BY RAND() LIMIT 0 , 2";

            $statement = Doctrine_Manager::getInstance()->connection();
            $results   = $statement->execute($sql);
            $configs   = $results->fetchAll();
            // echo "<pre>"; print_r($configs); echo "</pre>";
            $counts    = $results->rowCount();
        } else {

            $counts = 0;
            return array();
        }

        $allValues = array();
        $imageShow = '';
        $limit     = '';
        if ($counts > 0) {
            // $limit = 2;
            if (!empty($amount)) {
                // $limit = $amount + 1;
                $limit = "2";
            } else {
                $limit = "2";
            }
            $total_products = array();
            foreach ($configs as $config) {
                // $shopType = $config['shoptype_id'];
                $shopType = $config ['shoptype'];
                $shopsId  = $config ['shop_id'];
                $shopName = $config ['shop_name'];

                if ($shopType == 'zSHOP') { // ZEN-CART
                    $zShop   = DBUtil::selectObjectByID('zselex_zenshop',
                            $shopsId, 'shop_id');
                    $dnName  = (!empty($zShop ['dbname']) ? $zShop ['dbname'] : '');
                    $dnUser  = (!empty($zShop ['username']) ? $zShop ['username']
                                : 'root');
                    $dbPswrd = (!empty($zShop ['password']) ? $zShop ['password']
                                : '');
                    $dbHost  = (!empty($zShop ['hostname']) ? $zShop ['hostname']
                                : 'localhost');

                    // $dsn = "mysql:dbname='" . $dnName . "';host='" . $dbHost . "'";
                    // echo $dsn; exit;

                    $dsn         = "mysql:dbname=$dnName;host=$dbHost";
                    $user        = $dnUser;
                    $password    = $dbPswrd;
                    $tableprefix = (!empty($zShop ['table_prefix']) ? $zShop ['table_prefix']
                                : '');

                    $prdquery = "SELECT a.products_id , a.products_image , a.products_price , LEFT(b.products_name, 20) AS products_name  , LEFT(b.products_description, 20) AS products_description,
                                    mn.manufacturers_name
                                    FROM  ".$tableprefix."products a 
                                    LEFT JOIN ".$tableprefix."products_description b ON b.products_id=a.products_id
                                    LEFT JOIN ".$tableprefix."manufacturers mn ON mn.manufacturers_id=a.manufacturers_id
                                    WHERE b.products_name!='' AND a.products_image!='' AND a.manufacturers_id!=''
                                    group by a.products_id 
                                    ORDER BY RAND()  LIMIT  0,1";

                    $dbh                    = new PDO($dsn, $user, $password);
                    $statement1             = Doctrine_Manager::getInstance()->connection($dbh);
                    $results                = $statement1->execute($prdquery);
                    $sValues                = $results->fetch();
                    // echo "<pre>"; print_r($sValues); echo "</pre>";
                    // $imagearr = array('imageval'=>'lower');
                    $list                   = array();
                    // for ($i = 0; $i < count($sValues); $i++) {
                    $sValues ['domainname'] = $zShop ['domain'];
                    $sValues ['adId']       = $config ['advertise_id'];
                    $sValues ['maxviews']   = $config ['maxviews'];
                    $sValues ['totalviews'] = $config ['totalviews'];
                    $sValues ['maxclicks']  = $config ['totalclicks'];
                    $sValues ['SHOPTYPE']   = $shopType;
                    $sValues ['shopName']   = $shopName;

                    $priceexplode = explode('.', $sValues ['products_price']);
                    if (strlen($priceexplode [0]) >= 4) { // more than 1000
                        $p1 = substr_replace($priceexplode [0], ".", 1, 0);
                        $p2 = substr_replace($priceexplode [1], ",", 2);
                        $p2 = substr($p2, 0, - 1);

                        $sValues ['PRICE'] = $p1.','.$p2;
                    } else {
                        $newstring         = substr_replace($priceexplode [1],
                            '', '2');
                        $sValues ['PRICE'] = $priceexplode [0].','.$newstring;
                    }

                    // echo $sValues[$i]['PRICE'] . '<br>';

                    if ($sValues ['products_image'] != '') {
                        // list($width, $height, $type, $attr) = @getimagesize('http://' . $zShop['domain'] . '/images/' . str_replace(" ", "%20", $sValues['products_image']));
                        $imagepath     = 'http://'.$zShop ['domain'].'/images/'.str_replace(" ",
                                "%20", $sValues ['products_image']);
                        // echo $imagepath; exit;
                        $img_args      = array(
                            'imagepath' => $imagepath,
                            'setheight' => '210',
                            'setwidth' => '170'
                        );
                        $new_resize    = ModUtil::apiFunc('ZSELEX', 'admin',
                                'imageProportional', $args          = $img_args);
                        $sValues ['H'] = $new_resize ['new_height'];
                        $sValues ['W'] = $new_resize ['new_width'];
                    }
                    // }
                    // $allValues[] = $sValues;
                    $total_products [] = $sValues;
                    // return $sValues;
                    // echo "<pre>"; print_r($sValues); echo "</pre>"; exit;
                    $statement1        = Doctrine_Manager::getInstance()->closeConnection($statement1);
                }  // /
                else if ($shopType == 'iSHOP') { // INTERNAL-SHOP
                    $iprdctQry = "SELECT p.* ,   LEFT(p.product_name, 20) product_name , LEFT(p.prd_description, 20) AS prd_description , s.theme AS shopTheme , u.uname
                        FROM zselex_products p , zselex_shop s 
                        LEFT JOIN zselex_shop_owners ow ON ow.shop_id=s.shop_id
                        LEFT JOIN users u ON u.uid = ow.user_id
                        LEFT JOIN zselex_serviceshop sv ON sv.shop_id = s.shop_id AND sv.type='paybutton'
                        WHERE p.prd_status='1' AND p.shop_id='".$shopsId."' AND p.shop_id=s.shop_id ORDER BY RAND()  LIMIT 0 , 1";
                    // echo $iprdctQry; exit;
                    $statement = Doctrine_Manager::getInstance()->connection();
                    $results   = $statement->execute($iprdctQry);
                    $iproducts = $results->fetch();

                    // $output["data"] = $iprdctQry;
                    // AjaxUtil::output($output);
                    // for ($i = 0; $i < count($iproducts); $i++) {
                    $iproducts ['adId']           = $config ['advertise_id'];
                    $iproducts ['products_name']  = $iproducts ['product_name'];
                    $iproducts ['products_id']    = $iproducts ['product_id'];
                    $iproducts ['products_image'] = $iproducts ['prd_image'];
                    $iproducts ['PRICE']          = $iproducts ['prd_price'];
                    $iproducts ['SHOPTYPE']       = $shopType;
                    $iproducts ['SHOPID']         = $shopsId;
                    $iproducts ['shopName']       = $shopName;
                    $iproducts ['THEME']          = $iproducts ['shopTheme'];
                    // echo $sValues[$i]['shopName']; exit;
                    if (!empty($iproducts ['products_image'])) {

                        $imagepath       = pnGetBaseURL().'zselexdata/'.$iproducts ['uname'].'/products/medium/'.str_replace(" ",
                                "%20", $iproducts ['products_image']);
                        // echo $imagepath; exit;
                        $img_args        = array(
                            'imagepath' => $imagepath,
                            'setheight' => '210',
                            'setwidth' => '170'
                        );
                        $new_resize      = ModUtil::apiFunc('ZSELEX', 'admin',
                                'imageProportional', $args            = $img_args);
                        $iproducts ['H'] = $new_resize ['new_height'];
                        $iproducts ['W'] = $new_resize ['new_width'];
                    }

                    $total_products [] = $iproducts;
                }
            } // /////
            // echo "<pre>"; print_r($total_products); echo "</pre>";

            $allValues = array_merge($allValues, $total_products);
            // echo "<pre>"; print_r($allValues); echo "</pre>";
            $aItem     = array();
            $aItem     = $allValues;

            $number = '';
            if (count($aItem) <= $amount) {
                $number = count($aItem);
            } else {
                $number = $amount;
            }
            // $prodval = array();
            $prodval      = array_filter($aItem);
            $productcount = count($prodval);
        }

        return $prodval;
    }

    /**
     * modify block settings .
     * .
     */
    public function modify($blockinfo)
    {
        $vars      = BlockUtil::varsFromContent($blockinfo ['content']);
        $languages = ZLanguage::getInstalledLanguages();
        $adtypes   = ModUtil::apiFunc('ZSELEX', 'admin', 'getAdTypes', $items);
        // echo "<pre>"; print_r($adtypes); echo "</pre>";
        $this->view->assign('languages', $languages);
        $this->view->assign('adtypes', $adtypes);
        $this->view->assign('vars', $vars);

        return $this->view->fetch('blocks/specialdeals/specialdeals_modify.tpl');
    }

    /**
     * update block settings
     */
    public function update($blockinfo)
    {
        $vars = BlockUtil::varsFromContent($blockinfo ['content']);

        // echo "<pre>"; print_r($_POST); echo "</pre>"; exit;
        $vars ['highad_amount']  = FormUtil::getPassedValue('highad_amount', '',
                'POST');
        $vars ['midad_amount']   = FormUtil::getPassedValue('midad_amount', '',
                'POST');
        $vars ['lowad_amount']   = FormUtil::getPassedValue('lowad_amount', '',
                'POST');
        $vars ['event_amount']   = FormUtil::getPassedValue('event_amount', '',
                'POST');
        $vars ['article_amount'] = FormUtil::getPassedValue('article_amount',
                '', 'POST');
        // write back the new contents
        $blockinfo ['content']   = BlockUtil::varsToContent($vars);

        // clear the block cache
        $this->view->clear_cache('blocks/specialdeals/specialdeals_modify.tpl');

        return $blockinfo;
    }
}
// end class def