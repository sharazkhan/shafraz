<?php
// ini_set('display_errors', 'off');
// error_reporting(0);
/**
 * Copyright 2015
 *
 * ZSELEX
 * Demonstration of Zikula Module
 *
 * @license GNU/LGPLv3 (or at your option, any later version).
 */

/**
 * Class to control User interface
 */
class ZSELEX_Api_Base_User extends Zikula_AbstractApi
{

    /**
     * Get available user links
     *
     * @return array array of admin links
     */
    public function getlinks()
    {
        // Define an empty array to hold the list of admin links
        $links = array();

        // Check the users permissions to each avaiable action within the admin panel
        // and populate the links array if the user has permission
        if (SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
            $links [] = array(
                'url' => ModUtil::url('ZSELEX', 'admin', 'modifyconfig'),
                'text' => $this->__('Admin'),
                'class' => 'z-icon-es-config'
            );
        }

        $sublinks = array(
            array(
                'url' => ModUtil::url('ZSELEX', 'user', 'standard'),
                'text' => $this->__('Standard caching')
            ),
            array(
                'url' => ModUtil::url('ZSELEX', 'user', 'nevercached'),
                'text' => $this->__('Never cached')
            ),
            array(
                'url' => ModUtil::url('ZSELEX', 'user', 'partialcache'),
                'text' => $this->__('Partial cache')
            ),
            array(
                'url' => ModUtil::url('ZSELEX', 'user', 'uniquepages'),
                'text' => $this->__('Multiple page caching')
            ),
            array(
                'url' => ModUtil::url('ZSELEX', 'user', 'checkiscached'),
                'text' => $this->__('is_cached demo')
            ),
            array(
                'url' => ModUtil::url('ZSELEX', 'user', 'cacheinfo'),
                'text' => $this->__('Caching explained')
            )
        );

        $links [] = array(
            'url' => ModUtil::url('ZSELEX', 'user', 'cacheinfo'),
            'text' => $this->__('Cache demo'),
            'class' => 'z-icon-es-view',
            'links' => $sublinks
        );

        // Return the links array back to the calling function
        return $links;
    }

    public function permission()
    {
        if (!SecurityUtil::checkPermission('News::', '::', ACCESS_ADMIN)) {
            $perm = 1;
        } else {
            $perm = 0;
        }
        return $perm;
    }

    public function getOwnerShops()
    {
        $user = UserUtil::getVar('uid');

        $sql    = "SELECT * FROM zselex_shop WHERE user_id='".$user."' AND shoptype_id='2'";
        $query  = DBUtil::executeSQL($sql);
        $result = $query->fetchAll();
        return $result;
    }

    /**
     * Chnage theme
     * 
     * @param array $args
     * @return string
     */
    public function changeTheme($args)
    {
        $product_id   = $args ['product_id'];
        $productTitle = $args ['producttitle'];

        if (!empty($product_id)) {
            $shopDesign = "SELECT s.theme FROM zselex_shop s , zselex_products p WHERE s.shop_id=p.shop_id AND p.product_id='".$product_id."'";
        } else {
            $shopDesign = "SELECT s.theme FROM zselex_shop s , zselex_products p WHERE s.shop_id=p.shop_id AND p.urltitle='".$productTitle."'";
        }
        $result    = DBUtil::executeSQL($shopDesign);
        $resitems  = $result->fetch();
        $shopTheme = $resitems ['theme'];

        return $shopTheme;
    }

    public function checkArticlePermission($args)
    {
        $sql      = "SELECT quantity FROM zselex_serviceshop WHERE user_id = '".$args ['user']."' AND type='createarticles'";
        $query    = DBUtil::executeSQL($sql);
        $result   = $query->fetch();
        $quantity = $result ['quantity'];
        return $quantity;
    }

    public function updateArticleService($args)
    {
        $user_id = $args ['user_id'];
        $shop_id = $args ['shop_id'];
        $type    = $args ['type'];
        $sql     = "UPDATE zselex_serviceshop SET availed=availed+1
                WHERE user_id='".$user_id."' AND shop_id='".$shop_id."' AND type='".$type."'";
        // echo $sql; exit;
        $query   = DBUtil::executeSQL($sql);
    }

    public function updateServiceUsed($args)
    {
        $user_id = $args ['user_id'];
        $shop_id = $args ['shop_id'];
        $type    = $args ['type'];
        $sql     = "UPDATE zselex_serviceshop SET availed=availed+1
                WHERE user_id='".$user_id."' AND shop_id='".$shop_id."' AND type='".$type."'";
        // echo $sql; exit;
        $query   = DBUtil::executeSQL($sql);
    }

    public function getZenCartProducts($args)
    {

        // echo "<pre>"; print_r($args); echo "</pre>";
        $sValues = array();
        try {
            $limit = '';
            if ($args ['limit'] != '') {
                $limit = $args ['limit'];
            } else {
                // $limit = '2';
            }

            $orderBy = '';
            if ($args ['orderby'] != '') {
                $orderBy = " ORDER BY ".$args ['orderby'];
            }

            $dnName  = (!empty($args ['shop'] ['dbname']) ? $args ['shop'] ['dbname']
                        : 'nodb');
            $dnUser  = (!empty($args ['shop'] ['username']) ? $args ['shop'] ['username']
                        : 'root');
            $dbPswrd = (!empty($args ['shop'] ['password']) ? $args ['shop'] ['password']
                        : '');
            $dbHost  = (!empty($args ['shop'] ['host']) ? $args ['shop'] ['host']
                        : 'localhost');

            $dsn = "mysql:dbname='".$dnName."';host='".$dbHost."'";
            // echo $dsn; exit;

            $dsn         = "mysql:dbname=$dnName;host=$dbHost";
            $user        = $dnUser;
            $password    = $dbPswrd;
            $tableprefix = (!empty($args ['shop'] ['table_prefix']) ? $args ['shop'] ['table_prefix']
                        : '');

            $prdwhere   = "b.products_name!='' AND a.products_image!='' AND a.manufacturers_id!=''";
            $prdwhere   = "a.products_status=1";
            $prdquery   = "SELECT a.products_id , a.products_image , a.products_price , LEFT(b.products_name, 20) AS products_name  , LEFT(b.products_description, 20) AS products_description,
                         mn.manufacturers_name
                         FROM  ".$tableprefix."products a 
                         LEFT JOIN ".$tableprefix."products_description b ON b.products_id=a.products_id
                         LEFT JOIN ".$tableprefix."manufacturers mn ON mn.manufacturers_id=a.manufacturers_id
                         WHERE ".$prdwhere." group by a.products_id ".$orderBy." ".$limit;
            // echo $prdquery; exit;
            $dbh        = new PDO($dsn, $user, $password);
            $statement1 = Doctrine_Manager::getInstance()->connection($dbh);
            $results    = $statement1->execute($prdquery);
            $sValues    = $results->fetchAll();

            // echo $config['domain'];
            // $imagearr = array('imageval'=>'lower');
            $list = array();

            // return $sValues;
            // echo "<pre>"; print_r($sValues); echo "</pre>";
            $statement1 = Doctrine_Manager::getInstance()->closeConnection($statement1);
        } catch (PDOException $e) {
            // $statement1 = Doctrine_Manager::getInstance()->closeConnection($statement1);
            // echo 'Caught exception: ', $e->getMessage(), "\n";
        } catch (Exception $e) {
            // $statement1 = Doctrine_Manager::getInstance()->closeConnection($statement1);
            // echo "General Error: " . $e->getMessage();
        }

        // echo "count :" . count($e); exit;

        return $sValues;
    }

    public function getIshopProducts()
    {
        $iprdctQry = "SELECT p.* , LEFT(p.prd_description, 20) AS prd_description , s.theme AS shopTheme FROM zselex_products p , zselex_shop s WHERE s.shoptype_id='2' AND p.shop_id='".$shopsId."' AND p.shop_id=s.shop_id LIMIT 0 , $limit";
        $statement = Doctrine_Manager::getInstance()->connection();
        $results   = $statement->execute($iprdctQry);
        $iproducts = $results->fetchAll();

        for ($i = 0; $i < count($iproducts); $i ++) {
            $iproducts [$i] ['adId']           = $config ['advertise_id'];
            $iproducts [$i] ['products_name']  = $iproducts [$i] ['product_name'];
            $iproducts [$i] ['products_id']    = $iproducts [$i] ['product_id'];
            $iproducts [$i] ['products_image'] = $iproducts [$i] ['prd_image'];
            $iproducts [$i] ['PRICE']          = $iproducts [$i] ['prd_price'];
            $iproducts [$i] ['SHOPTYPE']       = $shopType;
            $iproducts [$i] ['SHOPID']         = $shopsId;
            $iproducts [$i] ['THEME']          = $iproducts [$i] ['shopTheme'];

            if ($iproducts [$i] ['products_image'] != '') {
                list ( $width, $height, $type, $attr ) = getimagesize(pnGetBaseURL().'modules/zselex/images/products/'.str_replace(" ",
                        "%20", $iproducts [$i] ['products_image']));
                $AW = $width;
                $AH = $height;
                $H  = '';
                $W  = '';
                if ($AH < 210 && $AW < 170) {
                    
                }
                if ($AH > 210 && $AW < 170) {
                    $H                    = 210;
                    $W                    = $AW * ((210 * 100) / $AH) / 100;
                    $iproducts [$i] ['H'] = round($H);
                    $iproducts [$i] ['W'] = round($W);
                }
                if ($AH < 210 && $AW > 170) {
                    $W                    = 170;
                    $H                    = $AH * ((170 * 100) / $AW) / 100;
                    $iproducts [$i] ['H'] = round($H);
                    $iproducts [$i] ['W'] = round($W);
                }
                if ($AH > 210 && $AW > 170) {
                    $H    = 210;
                    $W    = $AW * ((210 * 100) / $AH) / 100;
                    $WTmp = $W;
                    if ($W > 170) {
                        $W = 170;
                        $H = $H * ((170 * 100) / $WTmp) / 100;
                    }
                    $iproducts [$i] ['H'] = round($H);
                    $iproducts [$i] ['W'] = round($W);
                }
            }
        }
        $allValues [] = $iproducts;
    }

    public function getIshopDOTDProduct($args)
    {

        // echo "<pre>"; print_r($args); echo "</pre>";
        $shop_id   = $args ['shop_id'];
        $column    = $args ['column_name'];
        $value     = $args ['columnValue'];
        $ownerName = ModUtil::apiFunc('ZSELEX', 'admin', 'getOwner',
                $args      = array(
                'shop_id' => $shop_id
        ));
        // $iprdctQry = "SELECT p.* , LEFT(p.prd_description, 20) AS prd_description , s.theme AS shopTheme FROM
        // zselex_products p , zselex_shop s WHERE s.shoptype_id='2' AND p.shop_id='" . $shop_id . "' AND p.$column='" . $value . "' AND p.shop_id=s.shop_id";

        $iprdctQry = "SELECT p.* , LEFT(p.prd_description, 20) AS prd_description , s.theme AS shopTheme FROM
                      zselex_products p , zselex_shop s 
                      WHERE  p.shop_id='".$shop_id."'  AND p.$column='".$value."' AND p.shop_id=s.shop_id";

        // echo $iprdctQry;

        $results   = DBUtil::executeSQL($iprdctQry);
        $iproducts = $results->fetch();
        $count     = $results->rowCount();

        if (!empty($count)) {
            $iproducts ['products_name']  = $iproducts ['product_name'];
            $iproducts ['products_id']    = $iproducts ['product_id'];
            $iproducts ['products_image'] = $iproducts ['prd_image'];
            $iproducts ['PRICE']          = $iproducts ['prd_price'];
            $iproducts ['SHOPTYPE']       = $shopType;
            $iproducts ['SHOPID']         = $shop_id;
            $iproducts ['THEME']          = $iproducts ['shopTheme'];

            if ($iproducts ['products_image'] != '') {
                list ( $width, $height, $type, $attr ) = getimagesize(pnGetBaseURL()."zselexdata/".$ownerName."/products/".str_replace(" ",
                        "%20", $iproducts ['products_image']));
                $AW = $width;
                $AH = $height;
                $H  = '';
                $W  = '';
                if ($AH < 210 && $AW < 170) {
                    
                }
                if ($AH > 210 && $AW < 170) {
                    $H               = 210;
                    $W               = $AW * ((210 * 100) / $AH) / 100;
                    $iproducts ['H'] = round($H);
                    $iproducts ['W'] = round($W);
                }
                if ($AH < 210 && $AW > 170) {
                    $W               = 170;
                    $H               = $AH * ((170 * 100) / $AW) / 100;
                    $iproducts ['H'] = round($H);
                    $iproducts ['W'] = round($W);
                }
                if ($AH > 210 && $AW > 170) {
                    $H    = 210;
                    $W    = $AW * ((210 * 100) / $AH) / 100;
                    $WTmp = $W;
                    if ($W > 170) {
                        $W = 170;
                        $H = $H * ((170 * 100) / $WTmp) / 100;
                    }
                    $iproducts ['H'] = round($H);
                    $iproducts ['W'] = round($W);
                }
            }
            // echo "<pre>"; print_r($iproducts); echo "</pre>"; exit;
            return $iproducts;
        } else {
            return 0;
        }
    }

    public function getZenCartDOTDProducts($args)
    {

        // echo "<pre>"; print_r($args); echo "</pre>"; exit;
        $shop_id = $args ['shop_id'];
        $column  = $args ['column_name'];
        $value   = $args ['columnValue'];
        try {
            $limit = '';
            if ($args ['limit'] != '') {
                $limit = $args ['limit'];
            } else {
                // $limit = '2';
            }

            $orderBy = '';
            if ($args ['orderby'] != '') {
                $orderBy = " ORDER BY ".$args ['orderby'];
            }

            $dnName  = (!empty($args ['shop'] ['dbname']) ? $args ['shop'] ['dbname']
                        : 'nodb');
            $dnUser  = (!empty($args ['shop'] ['username']) ? $args ['shop'] ['username']
                        : 'root');
            $dbPswrd = (!empty($args ['shop'] ['password']) ? $args ['shop'] ['password']
                        : '');
            $dbHost  = (!empty($args ['shop'] ['host']) ? $args ['shop'] ['host']
                        : 'localhost');

            $dsn = "mysql:dbname='".$dnName."';host='".$dbHost."'";
            // echo $dsn; exit;

            $dsn         = "mysql:dbname=$dnName;host=$dbHost";
            $user        = $dnUser;
            $password    = $dbPswrd;
            $tableprefix = (!empty($args ['shop'] ['table_prefix']) ? $args ['shop'] ['table_prefix']
                        : '');

            $prdwhere = "b.products_name!='' AND a.products_image!='' AND a.manufacturers_id!=''";
            $prdwhere = "a.products_status=1";
            $prdquery = "SELECT a.products_id , a.products_image , a.products_price , LEFT(b.products_name, 20) AS products_name  , LEFT(b.products_description, 20) AS products_description,
                         mn.manufacturers_name
                         FROM  ".$tableprefix."products a 
                         LEFT JOIN ".$tableprefix."products_description b ON b.products_id=a.products_id
                         LEFT JOIN ".$tableprefix."manufacturers mn ON mn.manufacturers_id=a.manufacturers_id
                         WHERE ".$prdwhere." AND a.$column='".$value."'";

            // echo $prdquery; exit;
            $dbh        = new PDO($dsn, $user, $password);
            $statement1 = Doctrine_Manager::getInstance()->connection($dbh);
            $results    = $statement1->execute($prdquery);
            $sValues    = $results->fetch();

            // echo $config['domain'];
            // $imagearr = array('imageval'=>'lower');
            $list = array();

            $sValues ['domain'] = $args ['shop'] ['domain'];

            $priceexplode = explode('.', $sValues ['products_price']);

            if (strlen($priceexplode [0]) >= 4) { // converting price to DK
                $p1 = substr_replace($priceexplode [0], ".", 1, 0);
                $p2 = substr_replace($priceexplode [1], ",", 2);
                $p2 = substr($p2, 0, - 1);

                $sValues ['PRICE'] = $p1.','.$p2;
            } else {

                // echo $priceexplode[1] . '<br>';
                $newstring = substr_replace($priceexplode [1], '', '2');

                // echo $newstring . '<br>';
                // echo $priceexplode[0] . ',' . $newstring . '<br>';

                $sValues ['PRICE'] = $priceexplode [0].','.$newstring;
            }

            // echo $sValues[$i]['PRICE'] . '<br>';

            if ($sValues ['products_image'] != '') { // resize image
                list ( $width, $height, $type, $attr ) = getimagesize('http://'.$args ['shop'] ['domain'].'/images/'.str_replace(" ",
                        "%20", $sValues ['products_image']));
                $AW = $width;
                $AH = $height;

                $H = '';
                $W = '';

                if ($AH < 210 && $AW < 170) {
                    
                }

                if ($AH > 210 && $AW < 170) {

                    $H = 210;
                    $W = $AW * ((210 * 100) / $AH) / 100;

                    $sValues ['H'] = round($H);
                    $sValues ['W'] = round($W);
                }

                if ($AH < 210 && $AW > 170) {

                    $W             = 170;
                    $H             = $AH * ((170 * 100) / $AW) / 100;
                    $sValues ['H'] = round($H);
                    $sValues ['W'] = round($W);
                }

                if ($AH > 210 && $AW > 170) {

                    $H = 210;
                    $W = $AW * ((210 * 100) / $AH) / 100;

                    $WTmp = $W;
                    if ($W > 170) {
                        $W = 170;
                        $H = $H * ((170 * 100) / $WTmp) / 100;
                    }

                    $sValues ['H'] = round($H);
                    $sValues ['W'] = round($W);
                }
            }

            // return $sValues;
            // echo "<pre>"; print_r($sValues); echo "</pre>"; exit;
            $statement1 = Doctrine_Manager::getInstance()->closeConnection($statement1);
        } catch (PDOException $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";
            // die;
        }

        return $sValues;
    }
    /**
     * News API Override
     * Article Creation
     * 
     * @return void
     */
    const STATUS_PUBLISHED = 0;
    const STATUS_REJECTED  = 1;
    const STATUS_PENDING   = 2;
    const STATUS_ARCHIVED  = 3;
    const STATUS_DRAFT     = 4;
    const STATUS_SCHEDULED = 5;

    public function create($args)
    {

        // $shop_id = $_REQUEST['shop_id'];
        // echo $shop_id; exit;
        // echo "create zselex api"; exit;
        // Argument check
        if (!isset($args ['title']) || empty($args ['title']) || !isset($args ['hometext'])
            || !isset($args ['hometextcontenttype']) || !isset($args ['bodytext'])
            || !isset($args ['bodytextcontenttype']) || !isset($args ['notes'])) {
            return LogUtil::registerArgsError();
        }

        // evaluates the input action
        $args ['action'] = isset($args ['action']) ? $args ['action'] : null;
        switch ($args ['action']) {
            case News_Controller_User::ACTION_SUBMIT : // submitted => pending
                $args ['published_status'] = self::STATUS_PENDING;
                break;
            case News_Controller_User::ACTION_PUBLISH :
            case News_Controller_User::ACTION_REJECT :
            case News_Controller_User::ACTION_SAVEPENDING :
            case News_Controller_User::ACTION_ARCHIVE :
                $args ['published_status'] = $args ['action'] - 2;
                break;
            case News_Controller_User::ACTION_SAVEDRAFT :
            case News_Controller_User::ACTION_SAVEDRAFT_RETURN :
                $args ['published_status'] = self::STATUS_DRAFT;
                break;
        }

        // Security check
        // if (!SecurityUtil::checkPermission('News::', '::', ACCESS_COMMENT)) {
        // return LogUtil::registerPermissionError();
        // } elseif (SecurityUtil::checkPermission('News::', '::', ACCESS_ADD)) {
        // if (!isset($args['published_status'])) {
        // $args['published_status'] = self::STATUS_PUBLISHED;
        // }
        // } else {
        // $args['published_status'] = self::STATUS_PENDING;
        // }

        $args ['published_status'] = 0;

        // calculate the format type
        $args ['format_type'] = ($args ['bodytextcontenttype'] % 4) * 4 + $args ['hometextcontenttype']
            % 4;

        // define the lowercase permalink, using the title as slug, if not present
        if (!isset($args ['urltitle']) || empty($args ['urltitle'])) {
            $args ['urltitle'] = strtolower(DataUtil::formatPermalink($args ['title']));
        }

        // check the publishing date options
        if ((!isset($args ['from']) && !isset($args ['to'])) || (isset($args ['unlimited'])
            && !empty($args ['unlimited']))) {
            $args ['from'] = null;
            $args ['to']   = null;
        } elseif (isset($args ['from']) && (isset($args ['tonolimit']) && !empty($args ['tonolimit']))) {
            $args ['from'] = DateUtil::formatDatetime($args ['from']);
            $args ['to']   = null;
        } else {
            $args ['from'] = DateUtil::formatDatetime($args ['from']);
            $args ['to']   = DateUtil::formatDatetime($args ['to']);
        }

        // Work out name of story submitter and approver
        $args ['approver'] = 0;
        if (!UserUtil::isLoggedIn() && empty($args ['contributor'])) {
            $args ['contributor'] = System::getVar('anonymous');
        } else {
            $args ['contributor'] = UserUtil::getVar('uname');
            if ($args ['published_status'] == self::STATUS_PUBLISHED) {
                $args ['approver'] = UserUtil::getVar('uid');
            }
        }

        $args ['counter']  = 0;
        $args ['comments'] = 0;

        if (!($obj = DBUtil::insertObject($args, 'news', 'sid'))) {
            return LogUtil::registerError($this->__('Error! Could not create new article.'));
        }

        // update the from field to the same cr_date if it's null
        if (is_null($args ['from'])) {
            $obj = array(
                'sid' => $obj ['sid'],
                'from' => $obj ['cr_date']
            );
            if (!DBUtil::updateObject($obj, 'news', '', 'sid')) {
                LogUtil::registerError($this->__('Error! Unable to save your changes.'));
            }
        }

        // Return the id of the newly created item to the calling process
        return $args ['sid'];
    }

    public function insertShopNews($args)
    {
        $sql = "INSERT INTO zselex_shop_news(shop_id , news_id , cr_uid)VALUES('".$args ['shop_id']."','".$args ['news_id']."','".$args ['cr_uid']."')";
        DBUtil::executeSQL($sql);
    }

    public function getShop_Shoppage($args)
    {

        // echo "<pre>"; print_r($args); echo "</pre>";
        $name = FormUtil::getPassedValue('shopName', null, 'REQUEST');

        // echo "shop name: " . $name;
        // echo "<pre>"; print_r($args); echo "</pre>";
        $item = '';

        if (!empty($args ['shop_id'])) {
            $item = DBUtil::selectObjectByID('zselex_shop', $args ['shop_id'],
                    'shop_id');
        } else {

            $tables     = DBUtil::getTables();
            $col        = $tables ['zselex_shop_column'];
            $where      = "{$col['urltitle']} = '".DataUtil::formatForStore($args ['shoptitle'])."'";
            $item       = DBUtil::selectObject('zselex_shop', $where, null,
                    $permFilter = '', null, $args ['SQLcache']);
        }

        // $sql = "SELECT a.*, a.shop_id SID , b.name FROM zselex_shop a LEFT JOIN zselex_files b ON a.shop_id=b.shop_id AND b.defaultImg=1 WHERE a.shop_id='" . $args['shop_id'] . "'";
        // $query = DBUtil::executeSQL($sql);
        // $result = $query->fetch();

        return $item;
    }

    /**
     * News API Override
     * Fetch Articles
     *
     * @return void
     */
    public function getall1($args)
    {
        // echo "Comes here";
        // echo "<pre>"; print_r($args); echo "</pre>";
        // Optional arguments.
        $shop_id = $_REQUEST ['shop_id'];

        // echo $shop_id;

        if (!isset($args ['status']) || (empty($args ['status']) && $args ['status']
            !== 0)) {
            $args ['status'] = null;
        }
        if (!isset($args ['startnum']) || empty($args ['startnum'])) {
            $args ['startnum'] = 1;
        }
        if (!isset($args ['numitems']) || empty($args ['numitems'])) {
            $args ['numitems'] = - 1;
        }
        if (!isset($args ['ignoreml']) || !is_bool($args ['ignoreml'])) {
            $args ['ignoreml'] = false;
        }
        if (!isset($args ['language'])) {
            $args ['language'] = '';
        }
        if (!isset($args ['filterbydate'])) {
            $args ['filterbydate'] = true;
        }

        if ((!empty($args ['status']) && !is_numeric($args ['status'])) || !is_numeric($args ['startnum'])
            || !is_numeric($args ['numitems'])) {
            return LogUtil::registerArgsError();
        }

        // create a empty result set
        $items = array();

        // Security check
        if (!SecurityUtil::checkPermission('News::', '::', ACCESS_OVERVIEW)) {
            // return $items;
        }

        // echo "<pre>"; print_r($args); echo "</pre>";

        $where = $this->generateWhere1($args);

        // $loguser = UserUtil::getVar('uid');
        // $wher = "AND cr_uid='" . $loguser . "'";
        // $wher = "AND sid IN(select news_id from zselex_shop_news where shop_id='" . $shop_id . "')";
        // $where = $where . " " . $wher;
        // echo $where;

        $tables = DBUtil::getTables();

        // echo "<pre>"; print_r($tables); echo "</pre>";

        $news_column = $tables ['news_column'];
        $orderby     = '';
        // Handle the sort order, if nothing requested use admin setting
        if (!isset($args ['order'])) {
            $args ['order'] = News_Api_User::getVar('storyorder');
            switch ($args ['order']) {
                case 0 :
                    $order = 'sid';
                    break;
                case 2 :
                    $order = 'weight';
                    break;
                case 1 :
                default :
                    $order = 'from';
            }
        } elseif (isset($news_column [$args ['order']])) {
            $order = $args ['order'];
        }

        // if ordering is used also set the order direction, ascending/descending
        if (!empty($order)) {
            if (isset($args ['orderdir']) && in_array(strtoupper($args ['orderdir']),
                    array(
                    'ASC',
                    'DESC'
                ))) {
                $orderby = $news_column [$order].' '.strtoupper($args ['orderdir']);
            } else {
                $orderby = $news_column [$order].' DESC';
            }
        } elseif ($args ['order'] == 'random') {
            $orderby = 'RAND()';
        }

        // if sorted by weight add second ordering "from", since weight is not unique
        if ($order == 'weight') {
            $orderby .= ', '.$news_column ['from'].' DESC';
        }

        $permChecker = new News_ResultChecker(News_Api_User::getVar('enablecategorization'),
            News_Api_User::getVar('enablecategorybasedpermissions'));
        $objArray    = DBUtil::selectObjectArrayFilter('news', $where, $orderby,
                $args ['startnum'] - 1, $args ['numitems'], '', $permChecker,
                $this->getCatFilter($args));

        // echo "<pre>"; print_r($objArray); echo "</pre>";
        // Check for an error with the database code, and if so set an appropriate
        // error message and return
        if ($objArray === false) {
            return LogUtil::registerError(News_Api_User::__('Error! Could not load any articles.'));
        }

        // need to do this here as the category expansion code can't know the
        // root category which we need to build the relative path component
        if (News_Api_User::getVar('enablecategorization') && $objArray && isset($args ['catregistry'])
            && $args ['catregistry']) {
            ObjectUtil::postProcessExpandedObjectArrayCategories($objArray,
                $args ['catregistry']);
        }

        // Return the items
        return $objArray;
    }

    public function getall_old($args)
    {
        // echo "hello world";
        // Optional arguments.
        if (!isset($args ['status']) || (empty($args ['status']) && $args ['status']
            !== 0)) {
            $args ['status'] = null;
        }
        if (!isset($args ['startnum']) || empty($args ['startnum'])) {
            $args ['startnum'] = 1;
        }
        if (!isset($args ['numitems']) || empty($args ['numitems'])) {
            $args ['numitems'] = - 1;
        }
        if (!isset($args ['ignoreml']) || !is_bool($args ['ignoreml'])) {
            $args ['ignoreml'] = false;
        }
        if (!isset($args ['language'])) {
            $args ['language'] = '';
        }
        if (!isset($args ['filterbydate'])) {
            $args ['filterbydate'] = true;
        }

        if ((!empty($args ['status']) && !is_numeric($args ['status'])) || !is_numeric($args ['startnum'])
            || !is_numeric($args ['numitems'])) {
            return LogUtil::registerArgsError();
        }

        // create a empty result set
        $items = array();

        // Security check
        if (!SecurityUtil::checkPermission('News::', '::', ACCESS_OVERVIEW)) {
            return $items;
        }

        $loguser = UserUtil::getVar('uid');

        $where = $this->generateWhere($args);

        // echo $where;
        $tables      = DBUtil::getTables();
        $news_column = $tables ['news_column'];
        $orderby     = '';
        // Handle the sort order, if nothing requested use admin setting
        if (!isset($args ['order'])) {
            $args ['order'] = $this->getVar('storyorder');
            switch ($args ['order']) {
                case 0 :
                    $order = 'sid';
                    break;
                case 2 :
                    $order = 'weight';
                    break;
                case 1 :
                default :
                    $order = 'from';
            }
        } elseif (isset($news_column [$args ['order']])) {
            $order = $args ['order'];
        }

        // if ordering is used also set the order direction, ascending/descending
        if (!empty($order)) {
            if (isset($args ['orderdir']) && in_array(strtoupper($args ['orderdir']),
                    array(
                    'ASC',
                    'DESC'
                ))) {
                $orderby = $news_column [$order].' '.strtoupper($args ['orderdir']);
            } else {
                $orderby = $news_column [$order].' DESC';
            }
        } elseif ($args ['order'] == 'random') {
            $orderby = 'RAND()';
        }

        // if sorted by weight add second ordering "from", since weight is not unique
        if ($order == 'weight') {
            $orderby .= ', '.$news_column ['from'].' DESC';
        }
        // echo $args['numitems'];

        $permChecker = new News_ResultChecker($this->getVar('enablecategorization'),
            $this->getVar('enablecategorybasedpermissions'));
        $objArray    = DBUtil::selectObjectArrayFilter('news', $where, $orderby,
                $args ['startnum'] - 1, $args ['numitems'], '', $permChecker,
                $this->getCatFilter($args));

        // Check for an error with the database code, and if so set an appropriate
        // error message and return
        if ($objArray === false) {
            return LogUtil::registerError($this->__('Error! Could not load any articles.'));
        }

        // need to do this here as the category expansion code can't know the
        // root category which we need to build the relative path component
        if ($this->getVar('enablecategorization') && $objArray && isset($args ['catregistry'])
            && $args ['catregistry']) {
            ObjectUtil::postProcessExpandedObjectArrayCategories($objArray,
                $args ['catregistry']);
        }

        // Return the items
        return $objArray;
    }

    protected function generateWhere($args)
    {
        $tables      = DBUtil::getTables();
        $news_column = $tables ['news_column'];
        $queryargs   = array();

        if (System::getVar('multilingual') == 1 && !$args ['ignoreml'] && empty($args ['language'])) {
            $queryargs [] = "({$news_column['language']} = '".DataUtil::formatForStore(ZLanguage::getLanguageCode())."' OR {$news_column['language']} = '')";
        } elseif (!empty($args ['language'])) {
            $queryargs [] = "{$news_column['language']} = '".DataUtil::formatForStore($args ['language'])."'";
        }

        if (isset($args ['status'])) {
            $queryargs [] = "{$news_column['published_status']} = '".DataUtil::formatForStore($args ['status'])."'";
        }

        if (isset($args ['displayonindex'])) {
            $queryargs [] = "{$news_column['displayonindex']} = '".DataUtil::formatForStore($args ['displayonindex'])."'";
        }

        // Check for specific date interval
        if (isset($args ['from']) || isset($args ['to'])) {
            // Both defined
            if (isset($args ['from']) && isset($args ['to'])) {
                $from         = DataUtil::formatForStore($args ['from']);
                $to           = DataUtil::formatForStore($args ['to']);
                $queryargs [] = "({$news_column['from']} >= '$from' AND {$news_column['from']} < '$to')";
                // Only 'from' is defined
            } elseif (isset($args ['from'])) {
                $date         = DataUtil::formatForStore($args ['from']);
                $queryargs [] = "({$news_column['from']} >= '$date' AND ({$news_column['to']} IS NULL OR {$news_column['to']} >= '$date'))";
                // Only 'to' is defined
            } elseif (isset($args ['to'])) {
                $date         = DataUtil::formatForStore($args ['to']);
                $queryargs [] = "({$news_column['from']} < '$date')";
            }
            // or can filter with the current date
        } elseif ((isset($args ['filterbydate'])) && ($args ['filterbydate'])) {
            $date         = DateUtil::getDatetime();
            $queryargs [] = "('$date' >= {$news_column['from']} AND ({$news_column['to']} IS NULL OR '$date' <= {$news_column['to']}))";
        }

        if (isset($args ['tdate'])) {
            $queryargs [] = "{$news_column['from']} LIKE '%{$args['tdate']}%'";
        }

        if (isset($args ['query']) && is_array($args ['query'])) {
            // query array with rows like {'field', 'op', 'value'}
            $allowedoperators = array(
                '>',
                '>=',
                '=',
                '<',
                '<=',
                'LIKE',
                '!=',
                '<>'
            );
            foreach ($args ['query'] as $row) {
                if (is_array($row) && count($row) == 3) {
                    // validate fields and operators
                    list ( $field, $op, $value ) = $row;
                    if (isset($news_column [$field]) && in_array($op,
                            $allowedoperators)) {
                        $queryargs [] = "$news_column[$field] $op '".DataUtil::formatForStore($value)."'";
                    }
                }
            }
        }

        // check for a specific author
        if (isset($args ['uid']) && is_numeric($args ['uid'])) {
            $queryargs [] = "{$news_column['cr_uid']} = '".DataUtil::formatForStore($args ['uid'])."'";
        }
        $shop_id = !empty($_REQUEST ['shop_idnewItem']) ? $_REQUEST ['shop_idnewItem']
                : $_REQUEST ['shop_id'];

        // $wher = "AND sid IN(select news_id from zselex_shop_news where shop_id='" . $shop_id . "')";

        if ($_REQUEST ['func'] == 'site' || ($_REQUEST ['module'] == 'ZSELEX' || $_REQUEST ['func']
            == 'newitem')) { // get shop articles in shop page
            // echo "shop articles";
            array_push($queryargs,
                "sid IN(SELECT news_id FROM zselex_shop_news WHERE shop_id='".$shop_id."')");
        } else { // get articles by avoiding shop articles for home page
            // echo "comes here";
            // echo "user articles";
            // array_push($queryargs, "cr_uid='" . UserUtil::getVar('uid') . "'");
            array_push($queryargs,
                "sid NOT IN(SELECT news_id FROM  zselex_shop_news)");
        }

        // echo "<pre>"; print_r($queryargs); echo "</pre>";

        $where = '';
        if (count($queryargs) > 0) {
            $where = implode(' AND ', $queryargs);
        }

        return $where;
    }

    public function getArticlePreformat($args)
    {
        $info  = $args ['info'];
        $links = $args ['links'];

        // Preformat the text according the article format type
        $hometext = $info ['hometype'] ? $info ['hometext'] : nl2br($info ['hometext']);
        $bodytext = $info ['bodytype'] ? $info ['bodytext'] : nl2br($info ['bodytext']);

        // Only bother with readmore if there is more to read
        $bytesmore     = strlen($info ['bodytext']);
        $readmore      = '';
        $bytesmorelink = '';
        if ($bytesmore > 0) {
            if (SecurityUtil::checkPermission('News::',
                    "{$info['cr_uid']}::{$info['sid']}", ACCESS_READ)) {
                $title    = $this->__('Read more...');
                $readmore = '<a title="'.$title.'" href="'.$links ['fullarticle'].'">'.$title.'</a>';
            }
            $bytesmorelink = $this->__f('%s bytes more', $bytesmore);
        }

        // Allowed to read full article?
        if (SecurityUtil::checkPermission('News::',
                "{$info['cr_uid']}::{$info['sid']}", ACCESS_READ)) {
            $title     = '<a href="'.$links ['fullarticle'].'" title="'.$info ['title'].'">'.$info ['title'].'</a>';
            $print     = '<a class="news_printlink" href="'.$links ['print'].'">'.$this->__('Print').' <img src="images/icons/extrasmall/printer.png" height="16" width="16" alt="[P]" title="'.$this->__('Printer-friendly page').'" /></a>';
            $printicon = '<a class="news_printlink" href="'.$links ['print'].'"><img src="images/icons/extrasmall/printer.png" height="16" width="16" alt="[P]" title="'.$this->__('Printer-friendly page').'" /></a>';
        } else {
            $title     = $info ['title'];
            $print     = '';
            $printicon = '';
        }

        $comment     = '';
        $commentlink = '';
        if (ModUtil::available('EZComments') && $info ['allowcomments'] == 1) {
            // Work out how to say 'comment(s)(?)' correctly
            $comment = ($info ['commentcount'] == 0) ? $this->__('Comments?') : $this->_fn('%s comment',
                    '%s comments', $info ['commentcount'],
                    $info ['commentcount']);

            // Allowed to comment?
            if (SecurityUtil::checkPermission('News::',
                    "{$info['cr_uid']}::{$info['sid']}", ACCESS_COMMENT)) {
                $commentlink = '<a title="'.$this->__f('%1$s about %2$s',
                        array(
                        $info ['commentcount'],
                        $info ['title']
                    )).'" href="'.$links ['comment'].'">'.$comment.'</a>';
            } else if (SecurityUtil::checkPermission('News::',
                    "{$info['cr_uid']}::{$info['sid']}", ACCESS_READ)) {
                $commentlink = $comment;
            }
        }

        // Notes, if there are any
        $notes = (isset($info ['notes']) && !empty($info ['notes'])) ? $this->__f('Footnote: %s',
                $info ['notes']) : '';

        // Build the categories preformated content
        $categories     = array();
        $category_names = array();
        if (!empty($links ['categories']) && is_array($links ['categories']) && $this->getVar('enablecategorization')) {
            $lang       = ZLanguage::getLanguageCode();
            $properties = array_keys($links ['categories']);
            foreach ($properties as $prop) {
                $catname                = isset($info ['categories'] [$prop] ['display_name'] [$lang])
                        ? $info ['categories'] [$prop] ['display_name'] [$lang] : $info ['categories'] [$prop] ['name'];
                $categories [$prop]     = '<a href="'.$links ['categories'] [$prop].'">'.$catname.'</a>';
                $category_names [$prop] = $catname;
            }
        }

        // Set up the array itself
        $preformat = array(
            'bodytext' => $bodytext,
            'bytesmore' => $bytesmorelink,
            'category' => '<a href="'.$links ['category'].'" title="'.$info ['cattitle'].'">'.$info ['cattitle'].'</a>',
            'categories' => $categories,
            'category_names' => $category_names,
            'comment' => $comment,
            'commentlink' => $commentlink,
            'hometext' => $hometext,
            'notes' => $notes,
            'print' => $print,
            'printicon' => $printicon,
            'readmore' => $readmore,
            'title' => $title
        );

        if (!empty($info ['topicimage'])) {
            $catimagepath              = $this->getVar('catimagepath');
            $preformat ['searchtopic'] = '<a href="'.DataUtil::formatForDisplay($links ['searchtopic']).'"><img src="'.$catimagepath.$info ['topicimage'].'" title="'.$info ['topictext'].'" alt="'.$info ['topictext'].'" /></a>';
        } else {
            $preformat ['searchtopic'] = '';
        }

        if (isset($info ['cat'])) {
            $preformat ['catandtitle'] = $preformat ['category'].': '.$preformat ['title'];
        } else {
            $preformat ['catandtitle'] = $preformat ['title'];
        }

        // echo "<pre>"; print_r($preformat); echo "</pre>";

        return $preformat;
    }

    protected function generateWhere1($args)
    {

        // echo "News";
        $tables      = DBUtil::getTables();
        $news_column = $tables ['news_column'];
        $queryargs   = array();

        if (System::getVar('multilingual') == 1 && !$args ['ignoreml'] && empty($args ['language'])) {
            $queryargs [] = "({$news_column['language']} = '".DataUtil::formatForStore(ZLanguage::getLanguageCode())."' OR {$news_column['language']} = '')";
        } elseif (!empty($args ['language'])) {
            $queryargs [] = "{$news_column['language']} = '".DataUtil::formatForStore($args ['language'])."'";
        }

        if (isset($args ['status'])) {
            $queryargs [] = "{$news_column['published_status']} = '".DataUtil::formatForStore($args ['status'])."'";
        }

        if (isset($args ['displayonindex'])) {
            $queryargs [] = "{$news_column['displayonindex']} = '".DataUtil::formatForStore($args ['displayonindex'])."'";
        }

        // Check for specific date interval
        if (isset($args ['from']) || isset($args ['to'])) {
            // Both defined
            if (isset($args ['from']) && isset($args ['to'])) {
                $from         = DataUtil::formatForStore($args ['from']);
                $to           = DataUtil::formatForStore($args ['to']);
                $queryargs [] = "({$news_column['from']} >= '$from' AND {$news_column['from']} < '$to')";
                // Only 'from' is defined
            } elseif (isset($args ['from'])) {
                $date         = DataUtil::formatForStore($args ['from']);
                $queryargs [] = "({$news_column['from']} >= '$date' AND ({$news_column['to']} IS NULL OR {$news_column['to']} >= '$date'))";
                // Only 'to' is defined
            } elseif (isset($args ['to'])) {
                $date         = DataUtil::formatForStore($args ['to']);
                $queryargs [] = "({$news_column['from']} < '$date')";
            }
            // or can filter with the current date
        } elseif ((isset($args ['filterbydate'])) && ($args ['filterbydate'])) {
            $date         = DateUtil::getDatetime();
            $queryargs [] = "('$date' >= {$news_column['from']} AND ({$news_column['to']} IS NULL OR '$date' <= {$news_column['to']}))";
        }

        if (isset($args ['tdate'])) {
            $queryargs [] = "{$news_column['from']} LIKE '%{$args['tdate']}%'";
        }

        if (isset($args ['query']) && is_array($args ['query'])) {
            // query array with rows like {'field', 'op', 'value'}
            $allowedoperators = array(
                '>',
                '>=',
                '=',
                '<',
                '<=',
                'LIKE',
                '!=',
                '<>'
            );
            foreach ($args ['query'] as $row) {
                if (is_array($row) && count($row) == 3) {
                    // validate fields and operators
                    list ( $field, $op, $value ) = $row;
                    if (isset($news_column [$field]) && in_array($op,
                            $allowedoperators)) {
                        $queryargs [] = "$news_column[$field] $op '".DataUtil::formatForStore($value)."'";
                    }
                }
            }
        }

        // check for a specific author
        if (isset($args ['uid']) && is_numeric($args ['uid'])) {
            $queryargs [] = "{$news_column['cr_uid']} = '".DataUtil::formatForStore($args ['uid'])."'";
        }

        $where = '';

        if (!SecurityUtil::checkPermission('News::', '::', ACCESS_ADMIN) && $_REQUEST ['func']
            == 'view') { // user shop articles
            // echo "comes here";
            // array_push($queryargs, "cr_uid='" . UserUtil::getVar('uid') . "'");
        }

        if ($_REQUEST ['func'] == 'viewshoparticles') { // user shop articles
            // echo "comes here";
            $shop_id = $_REQUEST ['shop_id'];
            array_push($queryargs,
                "sid IN(SELECT news_id FROM zselex_shop_news WHERE shop_id='".$shop_id."')");
        }

        if (!SecurityUtil::checkPermission('News::', '::', ACCESS_ADMIN) && $_REQUEST ['type']
            == 'admin' && $_REQUEST ['func'] == 'view' || $_REQUEST ['type'] == 'user'
            && $_REQUEST ['func'] == 'view') { // user shop articles
            // echo "Comes Here";
            array_push($queryargs, "cr_uid='".UserUtil::getVar('uid')."'");
        }

        // echo "<pre>"; print_r($queryargs); echo "</pre>";

        if (count($queryargs) > 0) {
            $where = implode(' AND ', $queryargs);
        }

        return $where;
    }

    public function countitems($args)
    {
        // Optional arguments.
        if (!isset($args ['status']) || (empty($args ['status']) && $args ['status']
            !== 0)) {
            $args ['status'] = null;
        }
        if (!isset($args ['ignoreml']) || !is_bool($args ['ignoreml'])) {
            $args ['ignoreml'] = false;
        }
        if (!isset($args ['language'])) {
            $args ['language'] = '';
        }

        $where = $this->generateWhere($args);
        $where = !empty($where) ? ' WHERE '.$where : '';

        return DBUtil::selectObjectCount('news', $where, 'sid', false,
                $this->getCatFilter($args));
    }

    protected function getCatFilter($args)
    {
        $catFilter = array();
        if (isset($args ['category']) && !empty($args ['category'])) {
            if (is_array($args ['category'])) {
                $catFilter = $args ['category'];
            } elseif (isset($args ['property'])) {
                $property              = $args ['property'];
                $catFilter [$property] = $args ['category'];
            }
            $catFilter ['__META__'] = array(
                'module' => 'News'
            );
        } elseif (isset($args ['catfilter'])) {
            $catFilter = $args ['catfilter'];
        }
        return $catFilter;
    }

    public function getSingle($args)
    {

        // echo "comes here"; exit;
        $result = DBUtil::selectObjectByID($args ['table'], $args ['id'],
                $args ['idName']);
        return $result;
    }

    public function getAllData($args)
    {
        $result = DBUtil::selectObjectByID($args ['table'], $args ['id'],
                $args ['idName']);
        return $result;
    }

    public function shopServicesMenu($args)
    {
        $user_id = $args ['user_id'];
        $shop_id = $args ['shop_id'];
        $sqls    = '';
        if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
            // $sqls = " AND a.user_id = '" . $user_id . "'";
            $sqls .= " AND a.availed < a.quantity";
        }

        $sql = "SELECT * FROM  zselex_serviceshop a
                LEFT JOIN  zselex_plugin b ON a.plugin_id=b.plugin_id 
                WHERE a.shop_id='".$args ['shop_id']."' ".$sqls;

        $query  = DBUtil::executeSQL($sql);
        $result = $query->fetchAll();
        return $result;
    }

    public function decodeurl($args)
    { // decoding the url - important function
        // exit;
        // check we actually have some vars to work with...
        if (!isset($args ['vars'])) {
            return LogUtil::registerArgsError();
        }
        // return;
        // echo "<pre>"; print_r($args); echo "</pre>";
        // define the available user functions
        $funcs = array(
            'main',
            'newitem',
            'newitemevent',
            'viewshoparticles',
            'create',
            'archives',
            'display',
            'categorylist',
            'displaypdf',
            'cart',
            'site',
            'productview',
            'shopProducts',
            'shop',
            'viewevent',
            'findus',
            'myshops',
            'checkout',
            'delivery',
            'cart'
        );

        if (in_array($args ['vars'] [2], $funcs)) {
            // echo "comes here"; exit;
            // set the correct function name based on our input
            if (empty($args ['vars'] [2])) {
                System::queryStringSetVar('func', 'view');
                $nextvar = 3;
            } elseif ($args ['vars'] [2] == 'page') {
                System::queryStringSetVar('func', 'view');
                $nextvar = 3;
            } elseif (!in_array($args ['vars'] [2], $funcs)) {
                System::queryStringSetVar('func', 'display');
                $nextvar = 2;
            } else {
                // echo "this works";
                System::queryStringSetVar('func', $args ['vars'] [2]);
                $nextvar = 3;
            }
            System::queryStringSetVar('type', 'user');
            if ($args ['vars'] [2] == 'Shop') {
                System::queryStringSetVar('type', 'shop');
            }

            $func = FormUtil::getPassedValue('func', 'view', 'GET');

            //echo $func; exit;
            // for now let the core handle the view function
            if (($func == 'view' || $func == 'main') && isset($args ['vars'] [$nextvar])) {
                System::queryStringSetVar('page',
                    (int) $args ['vars'] [$nextvar]);
            }

            // echo "func : " . $func;
            // add the category info
            if ($func == 'view' && isset($args ['vars'] [$nextvar])) {
                if ($args ['vars'] [$nextvar] == 'page') {
                    System::queryStringSetVar('page',
                        (int) $args ['vars'] [$nextvar + 1]);
                } else {
                    System::queryStringSetVar('prop', $args ['vars'] [$nextvar]);
                    if (isset($args ['vars'] [$nextvar + 1])) {
                        $numargs = count($args ['vars']);
                        if ($args ['vars'] [$numargs - 2] == 'page' && is_numeric($args ['vars'] [$numargs
                                - 1])) {
                            System::queryStringSetVar('cat',
                                (string) implode('/',
                                    array_slice($args ['vars'], $nextvar + 1,
                                        - 2)));
                            System::queryStringSetVar('page',
                                (int) $args ['vars'] [$numargs - 1]);
                        } else {
                            System::queryStringSetVar('cat',
                                (string) implode('/',
                                    array_slice($args ['vars'], $nextvar + 1)));
                            System::queryStringSetVar('page', 1);
                        }
                    }
                }
            } elseif ($func == 'site' && isset($args ['vars'] [$nextvar]) || $func
                == 'checkout' && isset($args ['vars'] [$nextvar]) || $func == 'delivery'
                && isset($args ['vars'] [$nextvar]) || $func == 'cart' && isset($args ['vars'] [$nextvar])) {
                // $repo = $this->entityManager->getRepository('ZSELEX_Entity_Shop');
                // echo "<pre>"; print_r($item); echo "</pre>"; exit;
                System::queryStringSetVar('shop_id', $item ['shop_id']); // set this for theme changing
                System::queryStringSetVar('shopName', $item ['shop_name']);
                System::queryStringSetVar('shop_name', $item ['shop_name']);
                System::queryStringSetVar('city_name', $item ['city_name']);
                System::queryStringSetVar('shoptitle', $args ['vars'] [$nextvar]);
                $user_id = UserUtil::getVar('uid');
                // $perm = ModUtil::apiFunc('ZSELEX', 'admin', 'shopPermission', array('shop_id' => $item['shop_id'], 'user_id' => $user_id));
                // System::queryStringSetVar('perm', $perm);
            } elseif ($func == 'productview' && isset($args ['vars'] [$nextvar])) {
                $repo = $this->entityManager->getRepository('ZSELEX_Entity_Product');

                //echo "<pre>"; print_r($args['vars']); echo "</pre>"; exit;
                // System::queryStringSetVar('productName', $args['vars'][$nextvar]);
                //echo $nextvar; exit;

                System::queryStringSetVar('producttitle',
                    $args ['vars'] [$nextvar + 1]);
                $producttitle = $args ['vars'] [$nextvar + 1];
                //echo $producttitle; exit;
                System::queryStringSetVar('shoptitle',
                    $args ['vars'] [$nextvar]);
                // echo $producttitle; exit;
            } elseif ($func == 'newitem' && isset($args ['vars'] [$nextvar])) {
                // echo $args['vars'][$nextvar];
                // System::queryStringSetVar('shopName', $args['vars'][$nextvar]);
                System::queryStringSetVar('shoptitle', $args ['vars'] [$nextvar]);
                $tables     = DBUtil::getTables();
                $col        = $tables ['zselex_shop_column'];
                $where      = "{$col['urltitle']} = '".DataUtil::formatForStore($args ['vars'] [$nextvar])."'";
                $item       = DBUtil::selectObject('zselex_shop', $where, null,
                        $permFilter = '', null, $args ['SQLcache']);

                System::queryStringSetVar('shop_idnewItem', $item ['shop_id']);
                System::queryStringSetVar('shop_id', $item ['shop_id']);
                System::queryStringSetVar('shopName', $item ['shop_name']);
            } elseif ($func == 'newitemevent' && isset($args ['vars'] [$nextvar])) {
                // echo $args['vars'][$nextvar+1];
                // System::queryStringSetVar('shopName', $args['vars'][$nextvar]);
                System::queryStringSetVar('shoptitle', $args ['vars'] [$nextvar]);

                $tables     = DBUtil::getTables();
                $col        = $tables ['zselex_shop_column'];
                $where      = "{$col['urltitle']} = '".DataUtil::formatForStore($args ['vars'] [$nextvar])."'";
                $item       = DBUtil::selectObject('zselex_shop', $where, null,
                        $permFilter = '', null, $args ['SQLcache']);

                System::queryStringSetVar('shop_id', $item ['shop_id']);
                System::queryStringSetVar('shopName', $item ['shop_name']);
                System::queryStringSetVar('mid', $args ['vars'] [$nextvar + 1]);
            } elseif ($func == 'viewevent' && isset($args ['vars'] [$nextvar])) {
                $repo = $this->entityManager->getRepository('ZSELEX_Entity_Event');
                // echo "<pre>"; print_r($args); echo "</pre><br>";
                // echo $args['vars'][$nextvar];

                System::queryStringSetVar('shoptitle', $args ['vars'] [$nextvar]);
                $event_urltitle = $args ['vars'] [$nextvar + 1];
                // echo $event_urltitle; exit;
                System::queryStringSetVar('event_urltitle', $event_urltitle);

                // echo $item['shop_id']; exit;
            } elseif ($func == 'viewshoparticles' && isset($args ['vars'] [$nextvar])) {
                // echo $args['vars'][$nextvar];
                // System::queryStringSetVar('shopName', $args['vars'][$nextvar]);
                System::queryStringSetVar('shoptitle', $args ['vars'] [$nextvar]);

                $tables     = DBUtil::getTables();
                $col        = $tables ['zselex_shop_column'];
                $where      = "{$col['urltitle']} = '".DataUtil::formatForStore($args ['vars'] [$nextvar])."'";
                $item       = DBUtil::selectObject('zselex_shop', $where, null,
                        $permFilter = '', null, $args ['SQLcache']);

                if (empty($item ['shop_id'])) {
                    return LogUtil::registerError($this->__('Error! Type not found.'));
                }

                System::queryStringSetVar('shop_idnewItem', $item ['shop_id']);
                System::queryStringSetVar('shop_id', $item ['shop_id']);
                System::queryStringSetVar('shopName', $item ['shop_name']);
            } elseif (($func == 'shop' && isset($args ['vars'] [$nextvar]))) {
                // echo "<pre>"; print_r($args); echo "</pre>";
                // echo $args['vars'][$nextvar];
                // System::queryStringSetVar('shopName', $args['vars'][$nextvar]);
                System::queryStringSetVar('shoptitle', $args ['vars'] [$nextvar]);


                // if ($args['vars'][4] == 'prod_category' && $args['vars'][6] != 'prod_mnfr') {
                if ($args ['vars'] [4] == 'prod_categorys' && $args ['vars'] [6]
                    != 'mnfrIds') {
                    // echo "comes here1";
                    // System::queryStringSetVar('prod_category', $args['vars'][5]);
                    System::queryStringSetVar('prod_categorys',
                        $args ['vars'] [5]);
                    System::queryStringSetVar('startnum', $args ['vars'] [7]);
                }    // elseif ($args['vars'][4] == 'prod_mnfr' && $args['vars'][6] != 'prod_category') {
                elseif ($args ['vars'] [4] == 'mnfrIds' && $args ['vars'] [6] != 'prod_categorys') {
                    // echo "comes here1";
                    System::queryStringSetVar('mnfrIds', $args ['vars'] [5]);
                    System::queryStringSetVar('startnum', $args ['vars'] [7]);
                } elseif ($args ['vars'] [4] == 'prod_categorys' && $args ['vars'] [6]
                    == 'mnfrIds') {
                    // echo "comes here2";
                    System::queryStringSetVar('prod_categorys',
                        $args ['vars'] [5]);
                    System::queryStringSetVar('mnfrIds', $args ['vars'] [7]);
                    System::queryStringSetVar('startnum', $args ['vars'] [9]);
                } else {
                    // echo "default";
                    System::queryStringSetVar('startnum', $args ['vars'] [5]);
                }
            }

            // identify the correct parameter to identify the news article
            elseif ($func == 'display' || $func == 'displaypdf') {

                $shoptitle  = $args ['vars'] [3];
                $tables     = DBUtil::getTables();
                $col        = $tables ['zselex_shop_column'];
                $where      = "{$col['urltitle']} = '".DataUtil::formatForStore($shoptitle)."'";
                $item       = DBUtil::selectObject('zselex_shop', $where, null,
                        $permFilter = '', null, $args ['SQLcache']);

                System::queryStringSetVar('shop_id', $item ['shop_id']);
                System::queryStringSetVar('shopName', $item ['shop_name']);

                // check the permalink structure and obtain any missing vars
                $permalinkkeys = array_flip(explode('/',
                        $this->getVar('permalinkformat')));
                // get rid of unused vars
                $args ['vars'] = array_slice($args ['vars'], $nextvar);

                // remove any category path down to the leaf category
                $permalinkkeycount = count($permalinkkeys);
                $varscount         = count($args ['vars']);
                ($args ['vars'] [$varscount - 2] == 'page') ? $pagersize         = 2
                            : $pagersize         = 0;
                if (($permalinkkeycount + $pagersize) != $varscount) {
                    array_splice($args ['vars'], $permalinkkeys ['%category%'],
                        $varscount - $permalinkkeycount);
                }

                // get the story id or title
                foreach ($permalinkkeys as $permalinkvar => $permalinkkey) {
                    System::queryStringSetVar(str_replace('%', '', $permalinkvar),
                        $args ['vars'] [$permalinkkey]);
                }

                if (isset($permalinkkeys ['%articleid%']) && isset($args ['vars'] [$permalinkkeys ['%articleid%']])
                    && is_numeric($args ['vars'] [$permalinkkeys ['%articleid%']])) {
                    System::queryStringSetVar('sid',
                        $args ['vars'] [$permalinkkeys ['%articleid%']]);
                    $nextvar = $permalinkkeys ['%articleid%'] + 1;
                } else {
                    System::queryStringSetVar('title',
                        $args ['vars'] [$permalinkkeys ['%articletitle%']]);
                    $nextvar = $permalinkkeys ['%articletitle%'] + 1;
                }
                if (isset($args ['vars'] [$nextvar]) && $args ['vars'] [$nextvar]
                    == 'page') {
                    System::queryStringSetVar('page',
                        (int) $args ['vars'] [$nextvar + 1]);
                }
            }

            // handle news archives
            elseif ($func == 'archives') {
                if (isset($args ['vars'] [$nextvar])) {
                    System::queryStringSetVar('year', $args ['vars'] [$nextvar]);
                    if (isset($args ['vars'] [$nextvar + 1])) {
                        System::queryStringSetVar('month',
                            $args ['vars'] [$nextvar + 1]);
                    }
                }
            } elseif ($func == 'findus' && isset($args ['vars'] [$nextvar])) {
                // echo "comes here3"; exit;
                // echo "<pre>"; print_r($args['vars']); echo "</pre>"; exit;
                System::queryStringSetVar('shoptitle', $args ['vars'] [3]);

                /*
                  $tables     = DBUtil::getTables();
                  $col        = $tables ['zselex_shop_column'];
                  $where      = "{$col['urltitle']} = '".DataUtil::formatForStore($args ['vars'] [3])."'";
                  $item       = DBUtil::selectObject('zselex_shop', $where, null,
                  $permFilter = '', null, $args ['SQLcache']);

                  if (empty($item ['shop_id']) || !$item) {
                  // echo "comes here"; exit;
                  return LogUtil::registerError($this->__('Error! site not found.'),
                  404);
                  }

                  System::queryStringSetVar('shop_id', $item ['shop_id']);
                  System::queryStringSetVar('shop_name', $item ['shop_name']);
                  return true;
                 */
            }

            return true;
        } // elseif ($args['vars'][3] == 'findus') {
        elseif ($func == 'findus' && isset($args ['vars'] [$nextvar])) {
            echo "comes here";
            exit();

            echo "<pre>";
            print_r($args ['vars']);
            echo "</pre>";
            exit();



            System::queryStringSetVar('shoptitle', $args ['vars'] [2]);

            $tables     = DBUtil::getTables();
            $col        = $tables ['zselex_shop_column'];
            $where      = "{$col['urltitle']} = '".DataUtil::formatForStore($args ['vars'] [2])."'";
            $item       = DBUtil::selectObject('zselex_shop', $where, null,
                    $permFilter = '', null, $args ['SQLcache']);

            if (empty($item ['shop_id']) || !$item) {
                // echo "comes here"; exit;
                return LogUtil::registerError($this->__('Error! site not found.'),
                        404);
            }

            System::queryStringSetVar('shop_id', $item ['shop_id']);
            System::queryStringSetVar('shop_name', $item ['shop_name']);
            return true;
        } elseif ($args ['vars'] [3] == 'productview') {
            // echo "comes here"; exit;
            // echo "<pre>"; print_r($args['vars']); echo "</pre>"; exit;
            $shop       = DBUtil::selectObject('zselex_shop',
                    $where      = "urltitle='".DataUtil::formatForStore($args ['vars'] [2])."'",
                    array(
                    'shop_id'
                    ), $permFilter = '', null, $args ['SQLcache']);
            // echo "<pre>"; print_r($shop); echo "</pre>"; exit;
            if (!$shop) {
                return LogUtil::registerError($this->__('Error! site not found.'),
                        403);
            }
            System::queryStringSetVar('func', $args ['vars'] [3]);
            System::queryStringSetVar('producttitle', $args ['vars'] [4]);
            $producttitle = $args ['vars'] [4];
            $productinfo  = ModUtil::apiFunc('ZSELEX', 'user', 'selectJoinRow',
                    $args         = array(
                    'table' => 'zselex_products a',
                    'fields' => array(
                        'a.shop_id',
                        'b.shop_name',
                        'a.product_id'
                    ),
                    'joins' => array(
                        "LEFT JOIN zselex_shop b ON a.shop_id=b.shop_id"
                    ),
                    'where' => array(
                        "a.urltitle='".$producttitle."'"
                    )
            ));
            // echo "<pre>"; print_r($productinfo); echo "</pre>"; exit;

            System::queryStringSetVar('shop_id', $productinfo ['shop_id']);
            System::queryStringSetVar('shop_name', $productinfo ['shop_name']);
            System::queryStringSetVar('product_id', $productinfo ['product_id']);
            return true;
        }
    }

    public function encodeurl($args)
    { // encode url
        // exit;
        // echo "encodeurl comes here";
        // check we have the required input
        if (!isset($args ['modname']) || !isset($args ['func']) || !isset($args ['args'])) {
            return LogUtil::registerArgsError();
        }
        if (!isset($args ['type'])) {
            $args ['type'] = 'user';
        }
        if (empty($args ['func'])) {
            $args ['func'] = 'view';
        }

        // echo $args['func'] ;
        // echo $args['args']['id'];
        // echo "<pre>"; print_r($args['args']); echo "</pre>";
        // create an empty string ready for population
        $vars = '';

        // for the display function use the defined permalink structure

        $allowedFunctions = array(
            'site',
            'productview',
            'newitem',
            'newitemevent',
            'display',
            'viewshoparticles',
            'otherShops',
            'shop',
            'viewevent',
            'checkout',
            'delivery',
            'findus',
            'cart'
        );

        if (in_array($args ['func'], $allowedFunctions)) {
            if ($args ['func'] == 'site' || $args ['func'] == 'checkout' || $args ['func']
                == 'delivery' || $args ['func'] == 'cart') {
                if (!empty($args ['args'] ['shop_id']) || !empty($args ['args'] ['id'])) {
                    $item      = DBUtil::selectObjectByID('zselex_shop',
                            !empty($args ['args'] ['id']) ? $args ['args'] ['id']
                                    : $args ['args'] ['shop_id'], 'shop_id');
                    $shoptitle = $item ['urltitle'];
                    $vars      = $shoptitle;
                }
            } elseif ($args ['func'] == 'display' || $args ['func'] == 'displaypdf') {

                // echo "news display";
                // echo $args['args']['shop_id'];
                // check for the generic object id parameter
                if (isset($args ['args'] ['objectid'])) {
                    $args ['args'] ['sid'] = $args ['args'] ['objectid'];
                }
                // check the permalink structure and obtain any missing vars
                $permalinkformat = $this->getVar('permalinkformat');

                // echo "<pre>"; print_r($permalinkformat); echo "</pre>";
                // insanity check for permalink format incase of corruption
                if (!isset($permalinkformat) || is_array($permalinkformat) || empty($permalinkformat)) {
                    $this->setVar('permalinkformat',
                        '%year%/%monthnum%/%day%/%articletitle%');
                    $permalinkformat = $this->getVar('permalinkformat');
                }

                if (isset($args ['args'] ['from']) && isset($args ['args'] ['urltitle'])) {

                    $date = getdate(strtotime($args ['args'] ['from']));
                    $in   = array(
                        '%category%',
                        '%articleid%',
                        '%articletitle%',
                        '%year%',
                        '%monthnum%',
                        '%monthname%',
                        '%day%'
                    );
                    $out  = array(
                        (isset($args ['args'] ['__CATEGORIES__'] ['Main'] ['path_relative'])
                                ? $args ['args'] ['__CATEGORIES__'] ['Main'] ['path_relative']
                                : null),
                        $args ['args'] ['sid'],
                        $args ['args'] ['urltitle'],
                        $date ['year'],
                        $date ['mon'],
                        strtolower(substr($date ['month'], 0, 3)),
                        $date ['mday']
                    );
                } else {
                    // get the item (will be cached by DBUtil)
                    $item = ModUtil::apiFunc('News', 'user', 'get',
                            array(
                            'sid' => $args ['args'] ['sid']
                    ));
                    $date = getdate(strtotime($item ['from']));
                    $in   = array(
                        '%category%',
                        '%articleid%',
                        '%articletitle%',
                        '%year%',
                        '%monthnum%',
                        '%monthname%',
                        '%day%'
                    );
                    $out  = array(
                        (isset($item ['__CATEGORIES__'] ['Main'] ['path_relative'])
                                ? $item ['__CATEGORIES__'] ['Main'] ['path_relative']
                                : null),
                        $item ['sid'],
                        $item ['urltitle'],
                        $date ['year'],
                        $date ['mon'],
                        strtolower(substr($date ['month'], 0, 3)),
                        $date ['mday']
                    );
                }
                // replace the vars to form the permalink
                // echo $permalinkformat;

                $item      = DBUtil::selectObjectByID('zselex_shop',
                        $args ['args'] ['shop_id'], 'shop_id');
                $shoptitle = $item ['urltitle'];
                $vars      = $shoptitle.'/';
                $vars .= str_replace($in, $out, $permalinkformat);

                // echo $vars;
                if (isset($args ['args'] ['page']) && $args ['args'] ['page'] != 1) {
                    $vars .= '/page/'.$args ['args'] ['page'];
                }
            }   // for the archives use year/month
            elseif ($args ['func'] == 'archives' && isset($args ['args'] ['year'])
                && isset($args ['args'] ['month'])) {
                $vars = "{$args['args']['year']}/{$args['args']['month']}";
            } elseif ($args ['func'] == 'productview') {

                // echo "<pre>"; print_r($args); echo "</pre>";
                if (!empty($args ['args'] ['id'])) {
                    // echo $args['args']['id'];
                    $item      = DBUtil::selectObjectByID('zselex_products',
                            $args ['args'] ['id'], 'product_id');
                    $prodname  = $item ['product_name'];
                    $prodtitle = $item ['urltitle'];
                    $shop_id   = $item ['shop_id'];
                    $shopitem  = DBUtil::selectObjectByID('zselex_shop',
                            $shop_id, 'shop_id');
                    $shoptitle = $shopitem ['urltitle'];
                    // $vars = "{$prodtitle}";
                    // $vars = "{$prodtitle}/{$shoptitle}";
                    $vars      = "{$shoptitle}/{$prodtitle}";
                    // $product_url = $args['modname'] . '/' . $shoptitle . '/' . $args['func'] . '/' . $vars;
                    // $vars = "newIshop/{$prodtitle}";
                }
            } elseif ($args ['func'] == 'newitem') {
                // echo $args['args']['shop_id'];
                // $item = DBUtil::selectObjectByID('zselex_shop', $args['args']['shop_id'], 'shop_id');
                // $shop_name = $item['shop_name'];
                //
				// $shop_name = 'testshop';
                // $vars = "{$shop_name}";

                $tables     = DBUtil::getTables();
                $col        = $tables ['zselex_shop_column'];
                $where      = "{$col['shop_id']} = '".DataUtil::formatForStore($args ['args'] ['shop_id'])."'";
                $item       = DBUtil::selectObject('zselex_shop', $where, null,
                        $permFilter = '', null, $args ['SQLcache']);
                $shoptitle  = $item ['urltitle'];
                $vars       = "{$shoptitle}";

                // $vars .= "/test";
                // System::queryStringSetVar('shop_idnewItem', $item['shop_id']);
            } elseif ($args ['func'] == 'newitemevent') {
                // echo $args['args']['shop_id'];
                // $item = DBUtil::selectObjectByID('zselex_shop', $args['args']['shop_id'], 'shop_id');
                // $shop_name = $item['shop_name'];
                //
				// $shop_name = 'testshop';
                // $vars = "{$shop_name}";

                $tables     = DBUtil::getTables();
                $col        = $tables ['zselex_shop_column'];
                $where      = "{$col['shop_id']} = '".DataUtil::formatForStore($args ['args'] ['shop_id'])."'";
                $item       = DBUtil::selectObject('zselex_shop', $where, null,
                        $permFilter = '', null, $args ['SQLcache']);
                $shoptitle  = $item ['urltitle'];
                // $vars = "{$shoptitle}";
                if (!empty($args ['args'] ['mid'])) {
                    $vars = "{$shoptitle}"."/".$args ['args'] ['mid'];
                } else {
                    $vars = "{$shoptitle}";
                }

                // $vars .= "/test";
                // System::queryStringSetVar('shop_idnewItem', $item['shop_id']);
            } elseif ($args ['func'] == 'viewevent') {
                $repo = $this->entityManager->getRepository('ZSELEX_Entity_Event');
                /*
                 * $tables = DBUtil::getTables();
                 * $col = $tables['zselex_shop_column'];
                 * $where = "{$col['shop_id']} = '" . DataUtil::formatForStore($args['args']['shop_id']) . "'";
                 * $item = DBUtil::selectObject('zselex_shop', $where, null, $permFilter = '', null, $args['SQLcache']);
                 */
                $item = $repo->get(array(
                    'entity' => 'ZSELEX_Entity_Event',
                    'fields' => array(
                        'a.event_urltitle',
                        'b.urltitle'
                    ),
                    'where' => array(
                        'a.shop_event_id' => $args ['args'] ['eventId']
                    ),
                    'joins' => array(
                        'JOIN a.shop b'
                    )
                ));

                $shoptitle  = $item ['urltitle'];
                $eventtitle = $item ['event_urltitle'];
                // $vars = "{$shoptitle}";
                // $vars = "{$shoptitle}" . "/" . $args['args']['eventId'] . ".htm";
                $vars       = "{$shoptitle}"."/".$eventtitle;

                // $vars .= "/test";
                // System::queryStringSetVar('shop_idnewItem', $item['shop_id']);
            } elseif ($args ['func'] == 'viewshoparticles') {

                $tables     = DBUtil::getTables();
                $col        = $tables ['zselex_shop_column'];
                $where      = "{$col['shop_id']} = '".DataUtil::formatForStore($args ['args'] ['shop_id'])."'";
                $item       = DBUtil::selectObject('zselex_shop', $where, null,
                        $permFilter = '', null, $args ['SQLcache']);

                if (empty($item ['urltitle'])) {
                    return LogUtil::registerError($this->__('Error! Type not found.'));
                }

                $shoptitle = $item ['urltitle'];
                $vars      = "{$shoptitle}";

                // $vars .= "/test";
                // System::queryStringSetVar('shop_idnewItem', $item['shop_id']);
            } elseif ($args ['func'] == 'shop') {

                // echo "<pre>"; print_r($args['args']); echo "</pre>";
                $tables     = DBUtil::getTables();
                $col        = $tables ['zselex_shop_column'];
                $where      = "{$col['shop_id']} = '".DataUtil::formatForStore($args ['args'] ['shop_id'])."'";
                $item       = DBUtil::selectObject('zselex_shop', $where, null,
                        $permFilter = '', null, $args ['SQLcache']);
                // echo "<pre>"; print_r($item); echo "</pre>";
                if (empty($item ['urltitle'])) {
                    return LogUtil::registerError($this->__('Error! Type1 not found.'));
                }

                $shoptitle = $item ['urltitle'];
                $vars      = "{$shoptitle}";
                // $vars .= "/page/{$args['args']['page']}";
                /*
                 * if (!empty($args['args']['prod_category'])) {
                 * $vars .= "/prod_category/" . $args['args']['prod_category'];
                 * }
                 */

                if (!empty($args ['args'] ['prod_categorys'])) {
                    $vars .= "/prod_categorys/".$args ['args'] ['prod_categorys'];
                }
                /*
                 * if (!empty($args['args']['prod_mnfr'])) {
                 * $vars .= "/prod_mnfr/" . $args['args']['prod_mnfr'];
                 * }
                 */
                if (!empty($args ['args'] ['mnfrIds'])) {
                    $vars .= "/mnfrIds/".$args ['args'] ['mnfrIds'];
                }
                if (!empty($args ['args'] ['startnum'])) {
                    $vars .= "/startnum/".$args ['args'] ['startnum'];
                }

                if (!empty($args ['args'] ['search'])) {
                    $vars .= "?search=".$args ['args'] ['search'];
                }

                // $vars .= "/test";
                // System::queryStringSetVar('shop_idnewItem', $item['shop_id']);
            } elseif ($args ['func'] == 'findus') {

                $tables     = DBUtil::getTables();
                $col        = $tables ['zselex_shop_column'];
                $where      = "{$col['shop_id']} = '".DataUtil::formatForStore($args ['args'] ['shop_id'])."'";
                $item       = DBUtil::selectObject('zselex_shop', $where, null,
                        $permFilter = '', null, $args ['SQLcache']);
                // echo "<pre>"; print_r($item); echo "</pre>";
                if (empty($item ['urltitle'])) {
                    return LogUtil::registerError($this->__('Error! Type-myerror not found.'));
                }

                $shoptitle = $item ['urltitle'];
                $vars      = "{$shoptitle}";

                // $return = $args['modname'] . '/' . $vars . '/' . $args['func'];
            }

            // add the category name to the view link
            // if ($args['func'] == 'view' && isset($args['args']['prop'])) {
            // $vars = $args['args']['prop'];
            // $vars .= isset($args['args']['cat']) ? '/' . $args['args']['cat'] : '';
            // }
            // view, main or now function pager
            if (isset($args ['args'] ['page']) && is_numeric($args ['args'] ['page'])
                && ($args ['func'] == '' || $args ['func'] == 'main' || $args ['func']
                == 'view')) {
                if (!empty($vars)) {
                    $vars .= "/page/{$args['args']['page']}";
                } else {
                    $vars = "page/{$args['args']['page']}";
                }
            }

            // construct the custom url part
            if (empty($vars)) {
                // echo $args['modname'] . '/' . $args['func'] . '/';
                return $args ['modname'].'/'.$args ['func'];
            } else {

                /*
                 * if ($args['func'] == 'findus') {
                 * return $args['modname'] . '/' . $vars . '/' . $args['func'];
                 * } elseif ($args['func'] == 'productview') {
                 * return $product_url;
                 * }
                 */
                return $args ['modname'].'/'.$args ['func'].'/'.$vars;
            }
        }
    }

    public function getArticleLinks($info)
    {
        $shorturls = System::getVar('shorturls', false);

        // Allowed to comment?
        if (ModUtil::available('EZComments') && $info ['allowcomments'] == 1) {
            if ($shorturls) {
                $comment = DataUtil::formatForDisplay(ModUtil::url('News',
                            'user', 'display',
                            array(
                            'sid' => $info ['sid'],
                            'from' => $info ['from'],
                            'urltitle' => $info ['urltitle'],
                            '__CATEGORIES__' => $info ['categories']
                            ), null, 'comments'));
            } else {
                $comment = DataUtil::formatForDisplay(ModUtil::url('News',
                            'user', 'display',
                            array(
                            'sid' => $info ['sid']
                            ), null, 'comments'));
            }
        } else {
            $comment = '';
        }

        // Allowed to read full article?
        if (SecurityUtil::checkPermission('News::',
                "{$info['cr_uid']}::{$info['sid']}", ACCESS_READ)) {
            if ($shorturls) {
                $fullarticle = DataUtil::formatForDisplay(ModUtil::url('News',
                            'user', 'display',
                            array(
                            'sid' => $info ['sid'],
                            'from' => $info ['from'],
                            'urltitle' => $info ['urltitle'],
                            '__CATEGORIES__' => $info ['categories']
                )));
            } else {
                $fullarticle = DataUtil::formatForDisplay(ModUtil::url('News',
                            'user', 'display',
                            array(
                            'sid' => $info ['sid']
                )));
            }
        } else {
            $fullarticle = '';
        }

        // Link to topic if there is a topic
        if (!empty($info ['topicpath'])) {
            $topicField = $this->getTopicField();
            // check which variable to use for the topic
            if ($shorturls) {
                $searchtopic = DataUtil::formatForDisplay(ModUtil::url('News',
                            'user', 'view',
                            array(
                            'prop' => $topicField,
                            'cat' => $info ['topicpath']
                )));
            } else {
                $searchtopic = DataUtil::formatForDisplay(ModUtil::url('News',
                            'user', 'view',
                            array(
                            'prop' => $topicField,
                            'cat' => $info ['topicid']
                )));
            }
        } else {
            $searchtopic = '';
        }

        // Link to all the categories
        $categories = array();
        if (!empty($info ['categories']) && is_array($info ['categories']) && $this->getVar('enablecategorization')) {
            // check which variable to use for the category
            if ($shorturls) {
                $field = 'path_relative';
            } else {
                $field = 'id';
            }
            $properties = array_keys($info ['categories']);
            foreach ($properties as $prop) {
                $categories [$prop] = DataUtil::formatForDisplay(ModUtil::url('News',
                            'user', 'view',
                            array(
                            'prop' => $prop,
                            'cat' => $info ['categories'] [$prop] [$field]
                )));
            }
        }

        // Set up the array itself
        if ($shorturls) {
            $links = array(
                'category' => DataUtil::formatForDisplay(ModUtil::url('News',
                        'user', 'view',
                        array(
                        'prop' => 'Main',
                        'cat' => $info ['catvar']
                ))),
                'categories' => $categories,
                'permalink' => DataUtil::formatForDisplayHTML(ModUtil::url('News',
                        'user', 'display',
                        array(
                        'sid' => $info ['sid'],
                        'from' => $info ['from'],
                        'urltitle' => $info ['urltitle'],
                        '__CATEGORIES__' => $info ['categories']
                        ), null, null, true)),
                'comment' => $comment,
                'fullarticle' => $fullarticle,
                'searchtopic' => $searchtopic,
                'print' => DataUtil::formatForDisplay(ModUtil::url('News',
                        'user', 'display',
                        array(
                        'sid' => $info ['sid'],
                        'from' => $info ['from'],
                        'urltitle' => $info ['urltitle'],
                        '__CATEGORIES__' => $info ['categories'],
                        'theme' => 'Printer'
                ))),
                'commentrssfeed' => DataUtil::formatForDisplay(ModUtil::url('EZComments',
                        'user', 'feed',
                        array(
                        'mod' => 'News',
                        'objectid' => $info ['sid']
                ))),
                'commentatomfeed' => DataUtil::formatForDisplay(ModUtil::url('EZComments',
                        'user', 'feed',
                        array(
                        'mod' => 'News',
                        'objectid' => $info ['sid']
                )))
            );
        } else {
            $links = array(
                'category' => DataUtil::formatForDisplay(ModUtil::url('News',
                        'user', 'view',
                        array(
                        'prop' => 'Main',
                        'cat' => $info ['catvar']
                ))),
                'categories' => $categories,
                'permalink' => DataUtil::formatForDisplayHTML(ModUtil::url('News',
                        'user', 'display',
                        array(
                        'sid' => $info ['sid']
                        ), null, null, true)),
                'comment' => $comment,
                'fullarticle' => $fullarticle,
                'searchtopic' => $searchtopic,
                'print' => DataUtil::formatForDisplay(ModUtil::url('News',
                        'user', 'display',
                        array(
                        'sid' => $info ['sid'],
                        'theme' => 'Printer'
                ))),
                'commentrssfeed' => DataUtil::formatForDisplay(ModUtil::url('EZComments',
                        'user', 'feed',
                        array(
                        'mod' => 'News',
                        'objectid' => $info ['sid']
                ))),
                'commentatomfeed' => DataUtil::formatForDisplay(ModUtil::url('EZComments',
                        'user', 'feed',
                        array(
                        'mod' => 'News',
                        'objectid' => $info ['sid']
                )))
            );
        }

        return $links;
    }

    public function selectRow($args)
    {
        // echo "<pre>"; print_r($args); echo "</pre>";
        try {
            $table   = $args ['table'];
            $idName  = $args ['IdName'];
            $idValue = $args ['IdValue'];
            $field   = $args ['fields'];
            $orderby = $args ['orderby'];
            $limit   = '';
            $fields  = '';
            $order   = '';
            if (!empty($field)) {
                foreach ($field as $val) {
                    $fields .= $val.',';
                }
                $fields = substr($fields, 0, - 1);
            } else {
                $fields = '*';
            }
            $where = '';
            if (!empty($args ['where'])) {
                $where = 'WHERE ';
                foreach ($args ['where'] as $key => $val) {
                    $where .= $val.' AND ';
                }
            }
            $where = substr($where, 0, - 4);
            if (!empty($args ['orderby'])) {
                $order = " ORDER BY ".$args ['orderby'];
            }
            if (!empty($args ['limit'])) {
                $limit = " LIMIT ".$args ['limit'];
            }
            $sql    = "SELECT $fields FROM $table $where $order $limit";
            // echo $sql; exit;
            $query  = DBUtil::executeSQL($sql);
            $result = $query->fetch();
            return $result;
        } catch (Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";
            // die;
        }
    }

    public function selectArray($args)
    {
        try {
            $table   = $args ['table'];
            $idName  = $args ['IdName'];
            $idValue = $args ['IdValue'];
            $field   = $args ['fields'];
            $orderby = $args ['orderby'];
            $groupby = $args ['groupby'];
            $fields  = '';
            if (!empty($field)) {
                foreach ($field as $val) {
                    $fields .= $val.',';
                }
                $fields = substr($fields, 0, - 1);
            } else {
                $fields = '*';
            }

            $where = '';
            if (!empty($args ['where'])) {
                $where = 'WHERE ';
                foreach ($args ['where'] as $key => $val) {
                    if (!empty($val)) {
                        $where .= $val.' AND ';
                    }
                }
            }

            $where = substr($where, 0, - 4);

            if (!empty($args ['groupby'])) {
                $groupby = " GROUP BY ".$args ['groupby'];
            }

            if (!empty($args ['orderby'])) {
                $order = " ORDER BY ".$args ['orderby'];
            }
            if (!empty($args ['limit'])) {
                $limit = $args ['limit'];
            }

            $sql    = "SELECT  $fields FROM $table $where $groupby $order $limit";
            // echo $sql;
            $query  = DBUtil::executeSQL($sql);
            $result = $query->fetchAll();

            return $result;
        } catch (Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";
            // die;
        }
    }

    public function selectJoinArray($args)
    {
        $table   = $args ['table'];
        $idName  = $args ['IdName'];
        $idValue = $args ['IdValue'];
        $field   = $args ['fields'];
        $orderby = $args ['orderby'];
        $groupby = $args ['groupby'];
        $joins   = '';

        $fields = '';
        if (!empty($field)) {
            foreach ($field as $val) {
                $fields .= $val.',';
            }
            $fields = substr($fields, 0, - 1);
        } else {
            $fields = '*';
        }

        if (!empty($args ['joins'])) {
            foreach ($args ['joins'] as $key => $val) {
                $joins .= " ".$val;
            }
        }

        $where = '';
        if (!empty($args ['where'])) {
            $where = 'WHERE ';
            foreach ($args ['where'] as $key => $val) {
                $where .= $val.' AND ';
            }
        }

        $where = substr($where, 0, - 4);

        $groupBy = '';
        if (!empty($args ['groupby'])) {
            $groupBy = " GROUP BY ".$args ['groupby'];
        }

        if (!empty($args ['orderby'])) {
            $order = " ORDER BY ".$args ['orderby'];
        }

        if (!empty($args ['limit'])) {
            $limit = " LIMIT ".$args ['limit'];
        }

        $sql = "SELECT $fields FROM $table $joins $where $groupBy $order $limit";
        // echo $sql;

        $query  = DBUtil::executeSQL($sql);
        $result = $query->fetchAll();

        return $result;
    }

    public function selectJoinRow1($args)
    {

        // echo "<pre>"; print_r($args); echo "</pre>";
        $table   = $args ['table'];
        $idName  = $args ['IdName'];
        $idValue = $args ['IdValue'];
        $field   = $args ['fields'];
        $orderby = $args ['orderby'];
        $joins   = '';
        $groupby = '';

        $fields = '';
        if (!empty($field)) {
            foreach ($field as $val) {
                $fields .= $val.',';
            }
            $fields = substr($fields, 0, - 1);
        } else {
            $fields = '*';
        }

        if (!empty($args ['joins'])) {
            foreach ($args ['joins'] as $key => $val) {
                $joins .= " ".$val;
            }
        }

        $where = '';
        if (!empty($args ['where'])) {
            $where = 'WHERE ';
            foreach ($args ['where'] as $key => $val) {
                $where .= $val.' AND ';
            }
        }

        $where = substr($where, 0, - 4);

        if (!empty($args ['orderby'])) {
            $order = " ORDER BY ".$args ['orderby'];
        }
        if (!empty($args ['limit'])) {
            $limit = " LIMIT ".$args ['limit'];
        }

        $sql = "SELECT  $fields FROM $table $joins $where $order $limit";
        // echo $sql;
        // echo $sql; exit;
        // exit;

        $query  = DBUtil::executeSQL($sql);
        $result = $query->fetch();

        return $result;
    }

    public function selectJoinRow($args)
    {

        // echo "<pre>"; print_r($args); echo "</pre>";
        $table   = $args ['table'];
        $idName  = $args ['IdName'];
        $idValue = $args ['IdValue'];
        $field   = $args ['fields'];
        $orderby = $args ['orderby'];
        $joins   = '';
        $groupby = '';

        $fields = '';
        if (!empty($field)) {
            foreach ($field as $val) {
                $fields .= $val.',';
            }
            $fields = substr($fields, 0, - 1);
        } else {
            $fields = '*';
        }

        if (!empty($args ['joins'])) {
            foreach ($args ['joins'] as $key => $val) {
                $joins .= " ".$val;
            }
        }

        $where = '';
        if (!empty($args ['where'])) {
            $where = 'WHERE ';
            foreach ($args ['where'] as $key => $val) {
                $where .= $val.' AND ';
            }
        }

        $where = substr($where, 0, - 4);

        if (!empty($args ['orderby'])) {
            $order = " ORDER BY ".$args ['orderby'];
        }
        if (!empty($args ['limit'])) {
            $limit = " LIMIT ".$args ['limit'];
        }

        $sql = "SELECT  $fields FROM $table $joins $where $order $limit";
        // echo $sql;
        // echo $sql; exit;
        // exit;

        $query  = DBUtil::executeSQL($sql);
        $result = $query->fetch();

        return $result;
    }

    public function deleteItems($args)
    {
        $table = $args ['table'];

        if (!empty($args ['where'])) {
            $where = 'WHERE ';
            foreach ($args ['where'] as $key => $val) {
                $where .= $val.' AND ';
            }
            $where = substr($where, 0, - 4);
        }

        $sql = "DELETE FROM $table $where";

        // echo $sql; exit;
        $query = DBUtil::executeSQL($sql);
    }

    public function getProductCart($args)
    {
        $shop_id = $args ['shop_id'];
        $sql     = "SELECT * , s.shop_id as shop_id
                        FROM zselex_products p , zselex_shop s 
                        LEFT JOIN zselex_shop_owners ow ON ow.shop_id=s.shop_id
                        LEFT JOIN users u ON u.uid = ow.user_id
                        LEFT JOIN zselex_serviceshop sv ON sv.shop_id = s.shop_id AND sv.type='paybutton'
                        WHERE p.shop_id='".$shop_id."' AND p.shop_id=s.shop_id";
        $query   = DBUtil::executeSQL($sql);
        $result  = $query->fetch();
        return $result;
    }

    public function getPayPalEmail($args)
    {
        $shop_id = $args ['shop_id'];
        $sql     = "SELECT paypal_email FROM zselex_paypal pp WHERE shop_id=$shop_id";
        $query   = DBUtil::executeSQL($sql);
        $result  = $query->fetch();
        $ppEmail = $result ['paypal_email'];
        return $ppEmail;
    }

    public function showPurchedOrder($args)
    {
        $order_id = $args ['order_id'];

        $sql = "SELECT * FROM zselex_products p  , zselex_order ordr , zselex_orderitems oi , zselex_shop s , zselex_shop_owners ow ,  users u
                WHERE ordr.order_id=oi.order_id
                AND p.product_id=oi.product_id 
                AND s.shop_id=oi.shop_id
                AND s.shop_id=ow.shop_id
                AND ow.user_id=u.uid
                AND oi.order_id='$order_id'";

        $query  = DBUtil::executeSQL($sql);
        $result = $query->fetchAll();
        return $result;
    }

    public function createArticlesExist($args)
    {
        $shop_id = $args ['shop_id'];
        $user_id = $args ['user_id'];

        if (SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADD)) {
            $sql = "SELECT count(*) as count FROM zselex_serviceshop
            WHERE shop_id='".$shop_id."' AND user_id='".$user_id."' AND type='createarticles' AND quantity > availed";

            $query  = DBUtil::executeSQL($sql);
            $result = $query->fetch();
            $count  = $result ['count'];
            return $count;
        } elseif (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)
            && SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_EDIT)) {

            return 0;
        }
    }

    public function insertObject($args)
    {
        $item         = $args ['item'];
        $table        = $args ['table'];
        $insertResult = DBUtil::insertObject($item, $table);
    }

    public function getEventsPerDay($args)
    {

        // echo "helloooo";
        $eventdate = $args ['date'];
        $todayDate = date("Y-m-d");
        /*
         * $sql = "SELECT *, shop_event_id , shop_id , shop_event_startdate , LEFT(shop_event_name, 20) AS event_name , LEFT(shop_event_description, 20) AS event_description , LEFT(shop_event_shortdescription, 20) AS event_shortdescription , DAYNAME(shop_event_startdate) as dateformated
         * FROM zselex_shop_events
         * WHERE shop_event_startdate='" . $startdate . "' AND shop_event_enddate>=CURDATE()";
         *
         */

        $minmax = "SELECT MIN( shop_event_startdate ) as mindate , MAX( shop_event_enddate ) as maxdate
                   FROM zselex_shop_events WHERE '".$eventdate."' BETWEEN shop_event_startdate AND shop_event_enddate 
                   AND shop_event_startdate <=CURDATE() AND shop_event_enddate >=CURDATE()";
        $q      = DBUtil::executeSQL($minmax);
        $e      = $q->fetch();

        // echo "<pre>"; print_r($e); echo "</pre>";

        $mindate   = $e ['mindate'];
        $maxdate   = $e ['maxdate'];
        $mxdates   = array(
            "0" => $maxdate
        );
        $datearray = array();

        $datearray = $this->createDateRangeArray($mindate, $maxdate);
        $datearray = array_merge($datearray, $mxdates);

        // echo "<pre>"; print_r($datearrayfinal); echo "</pre>";
        $datearrayfinal = array();

        foreach ($datearray as $a => $b) {

            if ($b < $todayDate) continue;
            $datearrayfinal [] = "'$b'";
        }

        $count = count($datearrayfinal);

        if ($count > 0) {
            $dateimplode = implode(',', $datearrayfinal);

            // echo "<pre>"; print_r($datearrayfinal); echo "</pre>";
            // $sql = "SELECT * FROM zselex_shop_events WHERE '" . $eventdate . "' BETWEEN shop_event_startdate AND shop_event_enddate
            // AND shop_event_startdate <=CURDATE() AND shop_event_enddate >=CURDATE()";

            $sql    = "SELECT * FROM zselex_shop_events
                WHERE shop_event_startdate IN($dateimplode) OR shop_event_enddate IN ($dateimplode)";
            // echo $sql;
            $query  = DBUtil::executeSQL($sql);
            $events = $query->fetchAll();

            return $events;
        } else {
            return array();
        }
    }

    function getDays($sStartDate, $sEndDate)
    {
        // Firstly, format the provided dates.
        // This function works best with YYYY-MM-DD
        // but other date formats will work thanks
        // to strtotime().
        $sStartDate = gmdate("Y-m-d", strtotime($sStartDate));
        $sEndDate   = gmdate("Y-m-d", strtotime($sEndDate));

        // Start the variable off with the start date
        $aDays [] = $sStartDate;

        // Set a 'temp' variable, sCurrentDate, with
        // the start date - before beginning the loop
        $sCurrentDate = $sStartDate;

        // While the current date is less than the end date
        while ($sCurrentDate < $sEndDate) {
            // Add a day to the current date
            $sCurrentDate = gmdate("Y-m-d",
                strtotime("+1 day", strtotime($sCurrentDate)));

            // Add this new day to the aDays array
            $aDays [] = $sCurrentDate;
        }

        // Once the loop has finished, return the
        // array of days.
        return $aDays;
    }

    public function upcommingEvents($args)
    {
        $repo      = $this->entityManager->getRepository('ZSELEX_Entity_Event');
        // echo "<pre>"; print_r($args); echo "</pre>";
        $setParams = array();
        try {
            $setParams   = array();
            $shop_id     = $args ["shop_id"];
            $country_id  = $args ["country_id"];
            // $country_id = '456';
            $region_id   = $args ["region_id"];
            $city_id     = $args ["city_id"];
            $area_id     = $args ["area_id"];
            $branch_id   = $args ["branch_id"];
            $category_id = $args ["category_id"];
            $search      = $args ["search"];
            $search      = ($search == $this->__('search for...') || $search == $this->__('search'))
                    ? '' : $search;
            $eventlimit  = $args ["eventlimit"];
            $aff_id      = $args ["aff_id"];
            // echo $eventlimit;
            if (empty($eventlimit)) {
                $eventlimit = 10;
            }
            // $reset = FormUtil::getPassedValue("reset");
            $output = '';
            // echo $reset; exit;
            if (!empty($eventlimit)) {
                $limit      = $eventlimit;
                $limitquery = "LIMIT 0 , $eventlimit";
            } else {
                $limit      = "2";
                $limitquery = "LIMIT 0 , $limit";
            }

            $searchquery = '';
            if (($shop_id > 0 || !empty($shop_id)) || ($region_id > 0 || !empty($region_id))
                || ($city_id > 0 || !empty($city_id)) || ($country_id > 0 || !empty($country_id))
                || ($area_id > 0 || !empty($area_id)) || ($search > 0 || !empty($search))
                || ($category_id > 0 || !empty($category_id)) || ($branch_id > 0
                || !empty($branch_id) || !empty($eventdate))) {

                // $eventdateqry = " AND shop_event_startdate>=CURDATE()";
                $eventdateqry = '';
                $output       = '';
                $items        = array(
                    'id' => $shop_id
                );
                // $shops = ModUtil::apiFunc('ZSELEX', 'admin', 'getShop', $items);
                $where        = '';
                $append       = '';

                if (!empty($country_id)) { // COUNTRY
                    // $append .= " AND a.country_id=$country_id";
                    $append .= " AND b.country_id=$country_id";
                }

                if (!empty($region_id)) { // REGION
                    $append .= " AND b.region_id=$region_id";
                }

                if (!empty($city_id)) { // CITY
                    $append .= " AND b.city_id=$city_id";
                }

                if (!empty($area_id)) { // AREA
                    $append .= " AND b.area_id=$area_id";
                }

                if (!empty($shop_id)) { // SHOP
                    $append .= " AND b.shop_id=$shop_id";
                }
                $join = "";
                if (!empty($category_id)) {
                    $append .= " AND c.category_id=:category ";
                    $join .= " INNER JOIN zselex_shop_to_category c ON c.shop_id=u.shop_id ";
                    $setParams += array(
                        'category' => $category_id
                    );
                }

                if (!empty($branch_id)) {
                    // $append .= " AND b.branch_id=$branch_id";
                    $append .= " AND branch.branch_id=:branch_id ";
                    $join .= " INNER JOIN zselex_shop_to_branch branch ON branch.shop_id=u.shop_id ";
                    $setParams += array(
                        'branch_id' => $branch_id
                    );
                }

                /*
                 * if (!empty($aff_ids)) {
                 * $append .= " AND b.aff_id IN($aff_ids)";
                 * }
                 */

                if (!empty($aff_id)) {
                    $affQuery = self::_affiliateQuery($aff_id, 'b');

                    // $append .= " AND a.aff_id IN (:aff_ids)";
                    // $append .= " AND (" . $affQuery . ")";
                    $append .= " AND (".$affQuery ['query'].")";
                    $setParams += $affQuery ['setParams'];
                }

                // echo "<pre>"; print_r($append); echo "</pre>";

                if (!empty($search)) {

                    // $append .= " AND (a.shop_id IN (SELECT shop_id FROM zselex_keywords WHERE keyword LIKE '%" . DataUtil::formatForStore($search) . "%') OR a.shop_id IN (SELECT shop_id FROM zselex_shop WHERE shop_name LIKE '%" . DataUtil::formatForStore($search) . "%'))";
                    $append .= " AND (b.shop_name LIKE :search OR MATCH (b.shop_name) AGAINST (:search2) OR b.shop_id IN (SELECT shop_id FROM zselex_keywords WHERE keyword LIKE :search OR MATCH (keyword) AGAINST (:search2)))";
                    $setParams += array(
                        'search' => '%'.DataUtil::formatForStore($search).'%',
                        'search2' => DataUtil::formatForStore($search)
                    );
                }

                // geting all shops here becuase to get the min and max dates.
                $result = $repo->getAllEvents(array(
                    'append' => $append,
                    'join' => $join,
                    'setParams' => $setParams,
                    'upcommingEvents' => true
                ));

                // echo "<pre>"; print_r($result); echo "</pre>";

                $eventArr ['events'] = $result;
                $count               = $repo->getAllEventsCount(array(
                    'append' => $append,
                    'join' => $join,
                    'setParams' => $setParams,
                    'upcommingEvents' => true
                ));
                $eventArr ['count']  = $count;
            } else {

                $count = 0;
            }

            if ($count > 0) {
                return $eventArr;
            } else {
                $eventArr = array();
                return $eventArr;
            }

            // echo "<pre>"; print_r($eventArr); echo "</pre>";
            // return $eventArr;
        } catch (Exception $e) {
            // die();
            return array();
        }
    }

    public function upcommingEvents2($args)
    {

        // echo "<pre>"; print_r($args); echo "</pre>";
        try {
            $setParams   = array();
            $shop_id     = $args ["shop_id"];
            $country_id  = $args ["country_id"];
            // $country_id = '456';
            $region_id   = $args ["region_id"];
            $city_id     = $args ["city_id"];
            $area_id     = $args ["area_id"];
            $branch_id   = $args ["branch_id"];
            $category_id = $args ["category_id"];
            $search      = $args ["search"];
            $search      = ($search == $this->__('search for...') || $search == $this->__('search'))
                    ? '' : $search;
            $eventlimit  = $args ["eventlimit"];
            // echo $eventlimit;
            if (empty($eventlimit)) {
                $eventlimit = 10;
            }
            // $reset = FormUtil::getPassedValue("reset");
            $output = '';
            // echo $reset; exit;
            if (!empty($eventlimit)) {
                $limit      = $eventlimit;
                $limitquery = "LIMIT 0 , $eventlimit";
            } else {
                $limit      = "2";
                $limitquery = "LIMIT 0 , $limit";
            }

            $searchquery = '';
            if (($shop_id > 0 || !empty($shop_id)) || ($region_id > 0 || !empty($region_id))
                || ($city_id > 0 || !empty($city_id)) || ($country_id > 0 || !empty($country_id))
                || ($area_id > 0 || !empty($area_id)) || ($search > 0 || !empty($search))
                || ($category_id > 0 || !empty($category_id)) || ($branch_id > 0
                || !empty($branch_id) || !empty($eventdate))) {

                // $eventdateqry = " AND shop_event_startdate>=CURDATE()";
                $eventdateqry = '';
                $output       = '';
                $items        = array(
                    'id' => $shop_id
                );
                // $shops = ModUtil::apiFunc('ZSELEX', 'admin', 'getShop', $items);
                $where        = '';
                $append       = '';

                if (!empty($country_id)) { // COUNTRY
                    // $append .= " AND a.country_id=$country_id";
                    $append .= " AND a.country_id=$country_id";
                }

                if (!empty($region_id)) { // REGION
                    $append .= " AND a.region_id=$region_id";
                }

                if (!empty($city_id)) { // CITY
                    $append .= " AND a.city_id=$city_id";
                }

                if (!empty($area_id)) { // AREA
                    $append .= " AND a.area_id=$area_id";
                }

                if (!empty($shop_id)) { // SHOP
                    $append .= " AND a.shop_id=$shop_id";
                }
                if (!empty($category_id)) {
                    // $append .= " AND a.cat_id=$category_id";
                    $append .= " AND a.shop_id IN(SELECT shop_id FROM zselex_shop_to_category WHERE category_id=$category_id) ";
                }

                if (!empty($branch_id)) {
                    $append .= " AND a.branch_id=$branch_id";
                }

                if (!empty($search)) {

                    // $append .= " AND (a.shop_id IN (SELECT shop_id FROM zselex_keywords WHERE keyword LIKE '%" . DataUtil::formatForStore($search) . "%') OR a.shop_id IN (SELECT shop_id FROM zselex_shop WHERE shop_name LIKE '%" . DataUtil::formatForStore($search) . "%'))";
                    $append .= " AND (a.shop_name LIKE :search OR MATCH (a.shop_name) AGAINST (:search2) OR a.shop_id IN (SELECT shop_id FROM zselex_keywords WHERE keyword LIKE :search OR MATCH (keyword) AGAINST (:search2)))";
                    $setParams += array(
                        'search' => '%'.DataUtil::formatForStore($search).'%',
                        'search2' => DataUtil::formatForStore($search)
                    );
                }

                /*
                 * $sql = "SELECT a.shop_id
                 * FROM zselex_shop a
                 * WHERE a.shop_id IS NOT NULL
                 * AND a.shop_id > 1
                 * AND a.status='1' $append";
                 * // echo $sql;
                 * $query = DBUtil::executeSQL($sql, '', '', false);
                 */

                // geting all shops here becuase to get the min and max dates.
                $event_shops = $this->entityManager->getRepository('ZSELEX_Entity_Event')->getUpcomingEventShops(array(
                    'append' => $append,
                    'limit' => $limit,
                    'setParams' => $setParams,
                    'upcommingEvents' => true
                ));
                // if ($query == false) {
                if ($event_shops == false) {
                    return array();
                }
                // $result = $query->fetchAll();
                $result = $event_shops;

                // echo "<pre>"; print_r($result); echo "</pre>";

                $count = count($result);
            } else {

                $count = 0;
            }
            $shopsql = '';
            if ($count > 0) {
                foreach ($result as $shopid) {
                    $shop_idarray [] = $shopid ['shop_id'];
                }

                $shop_ids = implode(",", $shop_idarray);
                // foreach ($result as $shop) {
                $shopsql  = " AND a.shop IN($shop_ids)";
                $shopsql1 = " AND a.shop_id IN($shop_ids)";

                $shopquery  = $shopsql;
                $shopquery1 = $shopsql1;
                // echo $shopquery;

                /*
                 * $minmax = "SELECT MIN( shop_event_startdate ) as mindate , MAX( shop_event_enddate ) as maxdate
                 * FROM zselex_shop_events
                 * WHERE shop_event_id IS NOT NULL AND UNIX_TIMESTAMP(shop_event_startdate) != 0 AND UNIX_TIMESTAMP(shop_event_enddate) != 0 AND status='1' " . " " . $shopquery . " " . $limitquery;
                 * // echo $minmax;
                 * $q = DBUtil::executeSQL($minmax, '', '', false);
                 * $e = $q->fetch();
                 */

                $e = $this->entityManager->getRepository('ZSELEX_Entity_Event')->getEventDates(array(
                    'shopquery' => $shopquery,
                    'limit' => $limit
                ));

                // echo "<pre>"; print_r($e); echo "</pre>";

                $mindate   = $e ['mindate'];
                $maxdate   = $e ['maxdate'];
                $mxdates   = array(
                    "0" => $maxdate
                );
                $datearray = array();
                // echo "mindate : " . $mindate;
                // echo "maxdate : " . $maxdate;

                $datearray = $this->createDateRangeArray($mindate, $maxdate);
                $datearray = array_merge($datearray, $mxdates);

                $dateCount = count($datearray);

                $todayDate = date("Y-m-d");
                $arrays    = array();

                $i          = 0;
                $j          = 0;
                $countArray = array();
                $eventCount = 0;
                foreach ($datearray as $key => $d) {
                    if ($d < $todayDate) continue;
                    // echo $d . '<br>';
                    if ($i == $limit) {
                        // break;
                    }

                    /*
                     * $sql1 = "SELECT a.shop_event_id , a.shop_id , a.shop_event_name , a.shop_event_shortdescription , a.shop_event_description , a.shop_event_startdate , a.shop_event_starthour , a.shop_event_startminute , a.shop_event_enddate , a.shop_event_endhour , a.shop_event_endminute,a.price,a.email,a.phone,a.shop_event_venue,
                     * b.aff_id , c.aff_image
                     * FROM zselex_shop_events a
                     * LEFT JOIN zselex_shop b ON b.shop_id=a.shop_id
                     * LEFT JOIN zselex_shop_affiliation c ON c.aff_id=b.aff_id
                     * WHERE '" . $d . "' BETWEEN a.shop_event_startdate AND a.shop_event_enddate AND (a.activation_date<=CURDATE() OR UNIX_TIMESTAMP(a.activation_date) = 0) " . " " . $shopquery1;
                     * //echo $sql1 . '<br>'; exit;
                     * // if (UserUtil::getVar('uid') == '122') {
                     * // echo $sql1 . '<br>'; exit;
                     * //}
                     *
                     * $query1 = DBUtil::executeSQL($sql1, '', '', false);
                     * $events1 = $query1->fetchAll();
                     */

                    $events1               = $this->entityManager->getRepository('ZSELEX_Entity_Event')->getEventBetweenDates(array(
                        'd' => $d,
                        'shopsql' => $shopsql
                    ));
                    // echo "<pre>"; print_r($events1); echo "</pre>";
                    // echo "<pre>"; print_r($es); echo "</pre>";
                    // echo $events1['shop_event_name'] . '<br>';
                    $dates                 = strtotime(date("Y-m-d",
                            strtotime($d))." +$j day");
                    $headlinedates         = date('l dS \o\f F Y', $dates);
                    // echo $headlinedates . '<br>';
                    $arrays ['dates'] [$d] = $events1;

                    if (count($events1) > 0) {
                        // $countArray[] = $events1;
                        $eventCount ++;
                    }

                    // echo "<pre>"; print_r($events1); echo "</pre>";
                    // $datearray[$key]['eventsname'] = 'hiii';
                    $i ++;
                    $j ++;
                }

                $eventArr ['events'] = $arrays ['dates'];

                $eventArr ['count'] = $eventCount;
                // $eventcount = count($arrays['dates']);
                // $eventcount = count($countArray);
                $eventcount         = $eventCount;
                // $view->assign('eventcount', $eventcount);
                // $view->assign('limit', $limit);
            } else {
                $eventArr = array();
            }

            return $eventArr;
        } catch (Exception $e) {
            // die();
            return array();
        }
    }

    public function upcommingEvents1($args)
    {

        // echo "<pre>"; print_r($args); echo "</pre>";
        try {
            $setParams   = array();
            $shop_id     = $args ["shop_id"];
            $country_id  = $args ["country_id"];
            // $country_id = '456';
            $region_id   = $args ["region_id"];
            $city_id     = $args ["city_id"];
            $area_id     = $args ["area_id"];
            $branch_id   = $args ["branch_id"];
            $category_id = $args ["category_id"];
            $search      = $args ["search"];
            $search      = ($search == $this->__('search for...') || $search == $this->__('search'))
                    ? '' : $search;
            $eventlimit  = $args ["eventlimit"];
            // $reset = FormUtil::getPassedValue("reset");
            $output      = '';
            // echo $reset; exit;
            if (!empty($eventlimit)) {
                $limit      = $eventlimit;
                $limitquery = "LIMIT 0 , $eventlimit";
            } else {
                $limit      = "2";
                $limitquery = "LIMIT 0 , $limit";
            }

            $searchquery = '';
            if (($shop_id > 0 || !empty($shop_id)) || ($region_id > 0 || !empty($region_id))
                || ($city_id > 0 || !empty($city_id)) || ($country_id > 0 || !empty($country_id))
                || ($area_id > 0 || !empty($area_id)) || ($search > 0 || !empty($search))
                || ($category_id > 0 || !empty($category_id)) || ($branch_id > 0
                || !empty($branch_id) || !empty($eventdate))) {

                // $eventdateqry = " AND shop_event_startdate>=CURDATE()";
                $eventdateqry = '';
                $output       = '';
                $items        = array(
                    'id' => $shop_id
                );
                // $shops = ModUtil::apiFunc('ZSELEX', 'admin', 'getShop', $items);
                $where        = '';
                $append       = '';

                if (!empty($country_id)) { // COUNTRY
                    // $append .= " AND a.country_id=$country_id";
                    $append .= " AND a.country_id=$country_id";
                }

                if (!empty($region_id)) { // REGION
                    $append .= " AND a.region_id=$region_id";
                }

                if (!empty($city_id)) { // CITY
                    $append .= " AND a.city_id=$city_id";
                }

                if (!empty($area_id)) { // AREA
                    $append .= " AND a.area_id=$area_id";
                }

                if (!empty($shop_id)) { // SHOP
                    $append .= " AND a.shop_id=$shop_id";
                }
                if (!empty($category_id)) {
                    // $append .= " AND a.cat_id=$category_id";
                    $append .= " AND a.shop_id IN(SELECT shop_id FROM zselex_shop_to_category WHERE category_id=$category_id) ";
                }

                if (!empty($branch_id)) {
                    $append .= " AND a.branch_id=$branch_id";
                }

                if (!empty($search)) {

                    // $append .= " AND (a.shop_id IN (SELECT shop_id FROM zselex_keywords WHERE keyword LIKE '%" . DataUtil::formatForStore($search) . "%') OR a.shop_id IN (SELECT shop_id FROM zselex_shop WHERE shop_name LIKE '%" . DataUtil::formatForStore($search) . "%'))";
                    $append .= " AND (a.shop_name LIKE :search OR MATCH (a.shop_name) AGAINST (:search2) OR a.shop_id IN (SELECT shop_id FROM zselex_keywords WHERE keyword LIKE :search OR MATCH (keyword) AGAINST (:search2)))";
                    $setParams += array(
                        'search' => '%'.DataUtil::formatForStore($search).'%',
                        'search2' => DataUtil::formatForStore($search)
                    );
                }

                /*
                 * $sql = "SELECT a.shop_id
                 * FROM zselex_shop a
                 * WHERE a.shop_id IS NOT NULL
                 * AND a.shop_id > 1
                 * AND a.status='1' $append";
                 * // echo $sql;
                 * $query = DBUtil::executeSQL($sql, '', '', false);
                 */

                $event_shops = $this->entityManager->getRepository('ZSELEX_Entity_Event')->getUpcomingEventShops(array(
                    'append' => $append,
                    'limit' => $limit,
                    'setParams' => $setParams
                ));
                // if ($query == false) {
                if ($event_shops == false) {
                    return array();
                }
                // $result = $query->fetchAll();
                $result = $event_shops;

                // echo "<pre>"; print_r($result); echo "</pre>";

                $count = count($result);
            } else {

                $count = 0;
            }
            $shopsql = '';
            if ($count > 0) {
                foreach ($result as $shopid) {
                    $shop_idarray [] = $shopid ['shop_id'];
                }

                $shop_ids = implode(",", $shop_idarray);
                // foreach ($result as $shop) {
                $shopsql  = " AND a.shop IN($shop_ids)";
                $shopsql1 = " AND a.shop_id IN($shop_ids)";

                $shopquery  = $shopsql;
                $shopquery1 = $shopsql1;
                // echo $shopquery;

                /*
                 * $minmax = "SELECT MIN( shop_event_startdate ) as mindate , MAX( shop_event_enddate ) as maxdate
                 * FROM zselex_shop_events
                 * WHERE shop_event_id IS NOT NULL AND UNIX_TIMESTAMP(shop_event_startdate) != 0 AND UNIX_TIMESTAMP(shop_event_enddate) != 0 AND status='1' " . " " . $shopquery . " " . $limitquery;
                 * // echo $minmax;
                 * $q = DBUtil::executeSQL($minmax, '', '', false);
                 * $e = $q->fetch();
                 */

                $e = $this->entityManager->getRepository('ZSELEX_Entity_Event')->getEventDates(array(
                    'append' => $append,
                    'shopquery' => $shopquery,
                    'limit' => $limit,
                    'setParams' => $setParams
                ));

                // echo "<pre>"; print_r($e); echo "</pre>";

                $mindate   = $e ['mindate'];
                $maxdate   = $e ['maxdate'];
                $mxdates   = array(
                    "0" => $maxdate
                );
                $datearray = array();
                // echo "mindate : " . $mindate;
                // echo "maxdate : " . $maxdate;

                $datearray = $this->createDateRangeArray($mindate, $maxdate);
                $datearray = array_merge($datearray, $mxdates);

                $dateCount = count($datearray);

                $todayDate = date("Y-m-d");
                $arrays    = array();

                $i          = 0;
                $j          = 0;
                $countArray = array();
                $eventCount = 0;
                foreach ($datearray as $key => $d) {
                    if ($d < $todayDate) continue;
                    // echo $d . '<br>';
                    if ($i == $limit) {
                        // break;
                    }

                    /*
                     * $sql1 = "SELECT a.shop_event_id , a.shop_id , a.shop_event_name , a.shop_event_shortdescription , a.shop_event_description , a.shop_event_startdate , a.shop_event_starthour , a.shop_event_startminute , a.shop_event_enddate , a.shop_event_endhour , a.shop_event_endminute,a.price,a.email,a.phone,a.shop_event_venue,
                     * b.aff_id , c.aff_image
                     * FROM zselex_shop_events a
                     * LEFT JOIN zselex_shop b ON b.shop_id=a.shop_id
                     * LEFT JOIN zselex_shop_affiliation c ON c.aff_id=b.aff_id
                     * WHERE '" . $d . "' BETWEEN a.shop_event_startdate AND a.shop_event_enddate AND (a.activation_date<=CURDATE() OR UNIX_TIMESTAMP(a.activation_date) = 0) " . " " . $shopquery1;
                     * //echo $sql1 . '<br>'; exit;
                     * // if (UserUtil::getVar('uid') == '122') {
                     * // echo $sql1 . '<br>'; exit;
                     * //}
                     *
                     * $query1 = DBUtil::executeSQL($sql1, '', '', false);
                     * $events1 = $query1->fetchAll();
                     */

                    $events1               = $this->entityManager->getRepository('ZSELEX_Entity_Event')->getEventBetweenDates(array(
                        'd' => $d,
                        'shopsql' => $shopsql
                    ));
                    // echo "<pre>"; print_r($events1); echo "</pre>";
                    // echo "<pre>"; print_r($es); echo "</pre>";
                    // echo $events1['shop_event_name'] . '<br>';
                    $dates                 = strtotime(date("Y-m-d",
                            strtotime($d))." +$j day");
                    $headlinedates         = date('l dS \o\f F Y', $dates);
                    // echo $headlinedates . '<br>';
                    $arrays ['dates'] [$d] = $events1;

                    if (count($events1) > 0) {
                        // $countArray[] = $events1;
                        $eventCount ++;
                    }

                    // echo "<pre>"; print_r($events1); echo "</pre>";
                    // $datearray[$key]['eventsname'] = 'hiii';
                    $i ++;
                    $j ++;
                }

                $eventArr ['events'] = $arrays ['dates'];

                $eventArr ['count'] = $eventCount;
                // $eventcount = count($arrays['dates']);
                // $eventcount = count($countArray);
                $eventcount         = $eventCount;
                // $view->assign('eventcount', $eventcount);
                // $view->assign('limit', $limit);
            } else {
                $eventArr = array();
            }

            return $eventArr;
        } catch (Exception $e) {
            // die();
            return array();
        }
    }

    function createDateRangeArray($start, $end)
    {
        $range = array();

        if (is_string($start) === true) $start = strtotime($start);
        if (is_string($end) === true) $end   = strtotime($end);

        if ($start > $end) return $this->createDateRangeArray($end, $start);

        do {
            $range [] = date('Y-m-d', $start);
            $start    = strtotime("+ 1 day", $start);
        } while ($start < $end);

        return $range;
    }

    public function upcommingEventsCount($args)
    {

        // echo "<pre>"; print_r($args); echo "</pre>";
        $sql    = "SELECT count(*) as count
                FROM zselex_shop_events 
                WHERE shop_event_enddate>=CURDATE()";
        // echo $sql;
        $query  = DBUtil::executeSQL($sql);
        $events = $query->fetch();
        $count  = $events ['count'];
        return $count;
    }
    /*
     * Get shops in new shop block in front end
     *
     * @return array - random 3 shops
     */

    public function getNewShops($args)
    {
        // echo "<pre>"; print_r($args); echo "</pre>";
        $setParams   = array();
        $shop_id     = $args ["shop_id"];
        $country_id  = $args ["country_id"];
        $region_id   = $args ["region_id"];
        $city_id     = $args ["city_id"];
        $area_id     = $args ["area_id"];
        $category_id = $args ["category_id"];
        $branch_id   = $args ["branch_id"];
        $aff_id      = $args ["aff_id"];
        $hsearch     = DataUtil::formatForStore($args ["search"]);
        // echo "searchVal1 : " . $hsearch; exit;
        $hsearch     = ($hsearch == $this->__('search for...') || $hsearch == $this->__('search'))
                ? '' : $hsearch;
        $search      = $hsearch;
        // $hsearch = ($hsearch == $this->__('search')) ? '' : $hsearch;
        // echo "searchVals : " . $hsearch; exit;

        $shopfrontorder = $args ["shopfrontorder"];
        $shopfrontlimit = $args ["shopfrontlimit"];
        if ($shopfrontlimit > 0) {
            $limit = $shopfrontlimit;
        } else {
            $limit = 20;
        }

        switch ($shopfrontorder) {
            case "new" :
                $orderby = " ORDER BY a.shop_id DESC ";
                break;
            case "rank" :
                $orderby = " ORDER BY sum(c.rating) DESC ";
                break;
            default :
                $orderby = " ORDER BY a.shop_id DESC ";
        }

        // $affiliate_image = $this->affiliate_image;
        // echo $affiliate_image; exit;

        $append = '';
        $join   = '';
        if (!empty($country_id)) { // COUNTRY
            $append .= " AND a.country_id=$country_id ";
        }

        if (!empty($region_id)) { // REGION
            $append .= " AND a.region_id=$region_id ";
        }

        if (!empty($city_id)) { // CITY
            $append .= " AND a.city_id=$city_id ";
        }

        if (!empty($area_id)) { // AREA
            $append .= " AND a.area_id=$area_id ";
        }

        if (!empty($shop_id)) { // SHOP
            $append .= " AND a.shop_id=$shop_id ";
        }

        if (!empty($category_id)) {
            // $append .= " AND a.cat_id=$category_id";
            // $append .= " AND a.shop_id IN(SELECT shop_id FROM zselex_shop_to_category WHERE category_id=$category_id) ";
            $append .= " AND cat.category_id=:cat_id ";
            $join .= " INNER JOIN zselex_shop_to_category cat ON cat.shop_id=u.shop_id ";
            $setParams += array(
                'cat_id' => DataUtil::formatForStore($category_id)
            );
        }

        if (!empty($branch_id)) {
            // $append .= " AND a.branch_id=$branch_id ";
            $append .= " AND branch.branch_id=:branch_id ";
            $join .= " INNER JOIN zselex_shop_to_branch branch ON branch.shop_id=u.shop_id ";
            $setParams += array(
                'branch_id' => DataUtil::formatForStore($branch_id)
            );
        }

        if (!empty($aff_id)) {
            $affQuery = self::_affiliateQuery($aff_id, 'a');
            // $append .= " AND a.aff_id IN (:aff_ids)";
            // $append .= " AND (" . $affQuery . ")";
            $append .= " AND (".$affQuery ['query'].")";
            $setParams += $affQuery ['setParams'];
        }

        if (!empty($search)) {

            $append .= " AND (a.shop_name LIKE :search OR MATCH (a.shop_name) AGAINST (:search2) OR a.shop_id IN (SELECT shop_id FROM zselex_keywords WHERE keyword LIKE :search OR MATCH (keyword) AGAINST (:search2))) ";
            $setParams += array(
                'search' => '%'.DataUtil::formatForStore($search).'%',
                'search2' => DataUtil::formatForStore($search)
            );
            // $append .= " AND a.shop_name LIKE '%" . DataUtil::formatForStore($search) . "%' OR a.shop_id IN (SELECT s.shop_id FROM ZSELEX_Entity_Keyword k JOIN k.shop s WHERE k.keyword LIKE '%" . DataUtil::formatForStore($search) . "%')";
        }

        // echo $append;

        $result   = array();
        $getShops = $this->entityManager->getRepository('ZSELEX_Entity_Shop')->getNewShops(array(
            'limit' => $limit,
            'append' => $append,
            'setParams' => $setParams,
            'orderby' => $orderby
        ));
        $result   = $getShops;
        // echo "<pre>"; print_r($getShops); echo "</pre>";
        // echo "<pre>"; print_r($result); echo "</pre>";
        if (!empty($result)) {
            shuffle($result);
            $result = array_slice($result, 0, 3);
        }
        // $count = sizeof($result);
        // return $result;
        // echo "<pre>"; print_r($result); echo "</pre>";
        $data          = '';
        $rating        = 0;
        $stars         = '';
        $rating_sec    = '';
        $city          = '';
        $see_ful_store = "";

        $count = sizeof($result);
        if ($count > 0) {
            // $data .= '<div class="image_list newshop">';
            $image = '';

            foreach ($result as $key => $item) {

                $no_image      = 0;
                /*
                 * $minishopExist = ModUtil::apiFunc('ZSELEX', 'admin', 'serviceExistBlock', $args = array('shop_id' => $item[shopid],
                 * 'type' => 'linktoshop'));
                 */
                $minishopExist = $item ['minishop'];
                // if ($minishopExist > 0) {
                if ($minishopExist === 'minishop') {
                    $see_ful_store = "<a href='".ModUtil::url('ZSELEX', 'user',
                            'shop',
                            array(
                            'shop_id' => $item [shopid]
                        ))."'</a>".$this->__('See full store here')."</a>";
                } else {
                    $see_ful_store = "";
                }
                $baseUrl = pnGetBaseURL();
                if ($item [default_img_frm] == "fromgallery") {
                    $imagepath = "zselexdata/$item[shopid]/minisitegallery/medium/$item[image_name]";

                    if (file_exists($imagepath) && $item [image_name] != '') {
                        // $image = "<a href='" . ModUtil::url('ZSELEX', 'user', 'site', array('shop_id' => $item[shopid])) . "'</a><img src='zselexdata/$item[uname]/minisitegallery/medium/$item[image_name]' height='164' width='299'></a>";
                        $image = "<img style='height:134px' src='".$baseUrl."zselexdata/$item[shopid]/minisitegallery/medium/".str_replace(" ",
                                "%20", $item [image_name])."'>";
                        // $imagepath_final = pnGetBaseURL() . "zselexdata/$item[uname]/minisitegallery/medium/" . str_replace(" ", "%20", $item[image_name]);
                    } else {
                        // $image = "<a href='" . ModUtil::url('ZSELEX', 'user', 'site', array('shop_id' => $item[shopid])) . "'</a><img src='zselexdata/nopreview.jpg' width='290' height='160'/></a>";
                        $image           = "<img src='".$baseUrl."zselexdata/nopreview.jpg' >";
                        $no_image        = 1;
                        $imagepath_final = pnGetBaseURL()."zselexdata/nopreview.jpg";
                    }
                } elseif ($item [default_img_frm] == "fromshop") {
                    // exit;
                    $imagepath = "zselexdata/$item[shopid]/minisiteimages/medium/$item[name]";

                    if (file_exists($imagepath) && $item [name] != '') {
                        // $image = "<a href='" . ModUtil::url('ZSELEX', 'user', 'site', array('shop_id' => $item[shopid])) . "'</a><img src='zselexdata/$item[uname]/minisiteimages/medium/$item[name]' ></a>";
                        // $imagepath_final = pnGetBaseURL() . "zselexdata/$item[uname]/minisiteimages/medium/" . str_replace(" ", "%20", $item[name]);
                        // $image = "<img style='height:144px' src='zselexdata/$item[shopid]/minisiteimages/medium/" . str_replace(" ", "%20", $item[name]) . "'>";
                        $image = "<img  src='zselexdata/$item[shopid]/minisiteimages/medium/".str_replace(" ",
                                "%20", $item [name])."'>";
                    } else {
                        // $image = "<a href='" . ModUtil::url('ZSELEX', 'user', 'site', array('shop_id' => $item[shopid])) . "'</a><img src='zselexdata/nopreview.jpg' width='290' height='160'/></a>";
                        $imagepath_final = pnGetBaseURL()."zselexdata/nopreview.jpg";
                        $image           = "<img src='zselexdata/nopreview.jpg' >";
                        $no_image        = 1;
                    }
                }
                $result [$key] ['image']         = $image;
                $result [$key] ['no_image']      = $no_image;
                $result [$key] ['image_final']   = $imagepath_final;
                $result [$key] ['see_ful_store'] = $see_ful_store;
                if (!empty($item [city_name])) {
                    $city = " , <span>$item[city_name]</span>";
                }

                $affiliate                         = $this->entityManager->getRepository('ZSELEX_Entity_Shop')->getAffiliate(array(
                    'aff_id' => $item ['aff_id']
                ));
                // echo "<pre>"; print_r($affiliate); echo "</pre>";
                $result [$key] ['affiliate_image'] = $affiliate ['aff_image'];
            }
            // echo "<pre>"; print_r($result); echo "</pre>";
            $shopArr = $result;

            // $data .= '</div>';
        } else {
            $shopArr = array();
        }

        return $shopArr;
    }
    /*
     * Query builder for affiliate id's from url
     *
     * @access public
     * @return array - aff_id query part , data binding
     */

    static function _affiliateQuery($aff_id, $alias)
    {
        $affQuery   = '';
        $affParams  = array();
        $affIdArray = explode(',', $aff_id);
        if (!empty($affIdArray)) {
            foreach ($affIdArray as $k => $a) {
                if (is_numeric($a)) {
                    $affArray []             = "$alias.aff_id=:aff_id$k";
                    $affParams ['aff_id'.$k] = $a;
                }
            }
        }
        if (!empty($affArray)) {
            $affQuery = implode(' OR ', $affArray);
        }

        // return $affQuery;
        return array(
            'query' => $affQuery,
            'setParams' => $affParams
        );
    }

    function array_random_assoc($arr, $num)
    {
        $keys = array_keys($arr);
        shuffle($keys);

        $r = array();
        $j = 1;
        for ($i = 0; $i <= $num; $i ++) {
            $r [$keys [$i]] = $arr [$keys [$i]];
            if ($j == $num) {
                break;
            }
            $j ++;
        }
        return $r;
    }

    /**
     * Get Ads products for high , medium , low
     *
     * Gets products randomly from shops based on the selection made
     * ZenCart query is used for getting products from zencart shop
     *
     * @param string $args['ad_type']
     * @param int $args['shop_id'] , $args['country_id'] , $args['region_id'] , $args['city_id']
     * $args['area_id'] , $args['category_id']
     * @param string $args['aff_id'] - coma seperated ids
     * @param string $args['search']
     *
     * @return array of products
     */
    function getSpecialDealAd($args)
    {
        // Zikula_View::getInstance('ZSELEX' , false)->setCaching(false);
        // echo pnGetBaseURL() . '<br>';
        // echo "<pre>"; print_r($args); echo "</pre>";
        $ad_type     = $args ['ad_type'];
        $shop_id     = $args ['shop_id'];
        $country_id  = $args ['country_id'];
        $region_id   = $args ['region_id'];
        $city_id     = $args ['city_id'];
        $area_id     = $args ['area_id'];
        $category_id = $args ['category_id'];
        $branch_id   = $args ['branch_id'];
        $aff_id      = $args ['aff_id'];
        $adtype      = $args ['adtype'];
        $amount      = $args ['amount'];
        $limit       = $args ['limit'];
        $search      = $args ['search'];
        $search      = ($search == $this->__('search for...') || $search == $this->__('search'))
                ? '' : $search;
        // $themepath = $this->current_theme;
        // echo "<pre>"; print_r($args); echo "</pre><br>"; exit;
        $img_height  = '';
        $img_width   = '';
        switch ($ad_type) {
            case "high" :
                $offset     = "2";
                $img_height = '145';
                $img_width  = '170';
                break;
            case "mid" :
                $offset     = "4";
                $img_height = '90';
                $img_width  = '128';
                break;
            case "low" :
                $offset     = "4";
                $img_height = '90';
                $img_width  = '128';
                break;
        }

        if (!empty($limit)) {
            $offset = $limit;
        }

        // echo "limit :" . $offset;

        $append = '';

        // echo "<pre>"; print_r($args); echo "</pre>";
        // AjaxUtil::output($amount); exit;
        $counts    = '';
        $setParams = array();
        if (($shop_id > 0 || !empty($shop_id)) || ($region_id > 0 || !empty($region_id))
            || ($city_id > 0 || !empty($city_id)) || ($country_id > 0 || !empty($country_id))
            || ($area_id > 0 || !empty($area_id)) || (!empty($search)) || ($category_id
            > 0 || !empty($category_id)) || ($branch_id > 0 || !empty($branch_id))) {
            $catquery = '';
            $catshop  = '';

            $branchquery = '';
            $branchshop  = '';

            if (!empty($category_id)) {
                // $append .= " AND a.cat_id=$category_id";
                $append .= " AND a.shop_id IN(SELECT shop_id FROM zselex_shop_to_category WHERE category_id=:category) ";
                $setParams += array(
                    'category' => $category_id
                );
            }

            if (!empty($branch_id)) {
                /*
                 * $append .= " AND a.branch_id=:branch";
                 * $setParams += array(
                 * 'branch' => $branch_id,
                 * );
                 */
                $params ['branch_id'] = $branch_id;
            }
            if (!empty($aff_id)) {
                $affQuery = $this->_affiliateAdquery($aff_id);
                $append .= "AND (".$affQuery ['query'].")";
                $setParams += $affQuery ['setParams'];
            }

            // echo "Append :" .$append; exit;
            // echo "<pre>"; print_r($setParams); echo "</pre>"; exit;
            if (!empty($search)) {

                // $append .= " AND (b.shop_id IN (SELECT shop_id FROM zselex_keywords WHERE keyword LIKE '%" . DataUtil::formatForStore($search) . "%') OR b.shop_id IN (SELECT shop_id FROM zselex_shop WHERE shop_name LIKE '%" . DataUtil::formatForStore($search) . "%'))";
                $append .= " AND (a.shop_name LIKE :search OR MATCH (a.shop_name) AGAINST (:search2 IN BOOLEAN MODE) OR u.shop_id IN (SELECT shop_id FROM zselex_keywords WHERE keyword LIKE :search OR MATCH (keyword) AGAINST (:search2 IN BOOLEAN MODE)))";
                $setParams += array(
                    'search' => '%'.DataUtil::formatForStore($search).'%',
                    'search2' => DataUtil::formatForStore($search)
                );
            }

            $adquery = '';
            if ($ad_type == 'high') {
                $append .= " AND b.identifier=:identifier";
                $setParams += array(
                    'identifier' => 'A'
                );
            } elseif ($ad_type == 'mid') {
                $append .= " AND b.identifier=:identifier";
                $setParams += array(
                    'identifier' => 'B'
                );
            } elseif ($ad_type == 'low') {
                $append .= " AND b.identifier=:identifier";
                $setParams += array(
                    'identifier' => 'C'
                );
            }

            $items = array(
                'id' => $shop_id
            );
            // $shops = ModUtil::apiFunc('ZSELEX', 'admin', 'getShop', $items);
            $where = '';

            if (($country_id > 0) or ( $country_id > 0 && $region_id < 0 && $city_id
                < 0 && $shop_id < 0)) { // COUNTRY
                $where = " AND u.country_id=$country_id AND u.level='COUNTRY' ";
                // $setParams += array(
                // 'country' => $country_id,
                // 'level' => 'COUNTRY'
                // );
            }

            if (($region_id > 0) or ( $country_id > 0 && $region_id > 0 && $city_id
                < 0 && $shop_id < 0)) { // REGION
                $where = " AND u.region_id=$region_id AND u.level='REGION' ";
                // $setParams += array(
                // 'region' => $region_id,
                // 'level' => 'REGION'
                // );
            }

            if (($city_id > 0) or ( $region_id > 0 && $city_id > 0 && $country_id
                > 0 && $shop_id < 0)) { // CITY
                $where = " AND u.city_id=$city_id AND u.level='CITY' ";
                // $setParams += array(
                // 'city' => $city_id,
                // 'level' => 'CITY'
                // );
            }

            if (($area_id > 0) or ( $region_id > 0 && $area_id > 0 && $city_id > 0
                && $country_id > 0 && $shop_id < 0)) { // AREA
                $where = " AND u.area_id=$area_id AND u.level='AREA' ";
                // $setParams += array(
                // 'area' => $area_id,
                // 'level' => 'AREA'
                // );
            }

            if (($shop_id > 0) or ( $shop_id > 0 && $region_id > 0 && $city_id > 0
                && $area_id > 0 && $country_id > 0)) { // SHOP
                $where = " AND u.shop_id=$shop_id AND u.level='SHOP' ";
                // $setParams += array(
                // 'shop' => $shop_id,
                // 'level' => 'SHOP'
                // );
            }

            if (($shop_id < 0 || empty($shop_id)) && ($region_id < 0 || empty($region_id))
                && ($city_id < 0 || empty($city_id)) && ($country_id < 0 || empty($country_id))
                && ($area_id < 0 || empty($area_id))) {
                // $where = " WHERE a.shop_id!='' $adquery $catquery $branchquery $searchquery";
            }

            // echo $where;
            $append .= $where;

            // echo "<pre>"; print_r($setParams); echo "</pre>";

            $configs = ModUtil::apiFunc('ZSELEX', 'admin', 'shopidsAds',
                    array(
                    'append' => $append,
                    'setParams' => $setParams,
                    'branch_id' => $branch_id,
                    'type' => 'createad',
                    'offset' => $offset
            ));

            $counts = count($configs);
            // echo "<pre>"; print_r($configs); echo "</pre>";
            // $shop_ids = array_column($configs , 'shop_id');
            // echo "<pre>"; print_r($shop_ids); echo "</pre>";
            // echo $config['dbname'];
        } else {
            // echo "comes here"; exit;
            $counts = 0;
        }

        // echo "<pre>"; print_r($configs); echo "</pre>"; exit;
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
            $appnd_zen      = '';
            $appnd_ishop    = '';
            foreach ($configs as $config) {

                // $shopType = $config['shoptype_id'];
                $shopType    = $config ['shoptype'];
                $shopsId     = $config ['shop_id'];
                $shopName    = $config ['shop_name'];
                $advSelected = $config ['advertise_sel_prods'];

                /* if ($serviceExist) { */
                if ($shopType == 'zSHOP') { // ZEN-CART
                    $appnd_zen = '';

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
                    // group by a.products_id
                    try {
                        $dbh        = new PDO($dsn, $user, $password);
                        $statement1 = Doctrine_Manager::getInstance()->connection($dbh);

                        $prdwhere     = "b.products_name!='' AND a.products_image!='' AND a.manufacturers_id!=''";
                        $prdwhere     = "a.products_status=1";
                        $prdCount     = "SELECT COUNT(*) as count
                                     FROM  ".$tableprefix."products a 
                                       WHERE ".$prdwhere."
                                    ".$appnd_zen."
                                  
                                    ";
                        // echo $prdCount . '<br>';
                        $resultsCount = $statement1->execute($prdCount);
                        $sValuesCount = $resultsCount->fetch();
                        $zAcount      = $sValuesCount ['count'];
                        // echo $zAcount . '<br>';
                        $zrandNum     = mt_rand(0, $zAcount - 1);
                        // echo $zrandNum . '<br>';
                        $prdquery     = "SELECT DISTINCT a.products_id , a.products_image , a.products_price , LEFT(b.products_name, 50) AS products_name  , LEFT(b.products_description, 20) AS products_description,
                                    mn.manufacturers_name
                                    FROM  ".$tableprefix."products a 
                                    LEFT JOIN ".$tableprefix."products_description b ON b.products_id=a.products_id
                                    LEFT JOIN ".$tableprefix."manufacturers mn ON mn.manufacturers_id=a.manufacturers_id
                                    WHERE ".$prdwhere."
                                    ".$appnd_zen."
                                   
                                    LIMIT  $zrandNum , 1";
                        // echo $prdquery . '<br>';
                        $results      = $statement1->execute($prdquery);
                        $zcount       = $results->rowCount();
                        $sValues      = $results->fetch();
                        // echo "<pre>"; print_r($sValues); echo "</pre>";
                        // $imagearr = array('imageval'=>'lower');
                        $list         = array();
                        // for ($i = 0; $i < count($sValues); $i++) {
                        if ($zcount > 0) {
                            $sValues ['domainname'] = $zShop ['domain'];
                            $sValues ['adId']       = $config ['advertise_id'];
                            $sValues ['maxviews']   = $config ['maxviews'];
                            $sValues ['totalviews'] = $config ['totalviews'];
                            $sValues ['maxclicks']  = $config ['totalclicks'];
                            $sValues ['SHOPTYPE']   = $shopType;
                            $sValues ['shopName']   = $shopName;
                            // echo $sValues[$i]['PRICE'] . '<br>';
                            if ($sValues ['products_image'] != '') {
                                // echo "image exists";
                                $imagepath = 'http://'.$zShop ['domain'].'/images/'.str_replace(" ",
                                        "%20", $sValues ['products_image']);
                                // $file_exists = file_exists($imagepath);
                                list ( $width, $height, $type, $attr ) = @getimagesize($imagepath);
                                // echo "width=".$width;
                                if (!empty($width)) {
                                    // echo "width=" . $width;
                                    $img_args                 = array(
                                        'imagepath' => $imagepath,
                                        'setheight' => $img_height,
                                        'setwidth' => $img_width
                                    );
                                    $new_resize               = ModUtil::apiFunc('ZSELEX',
                                            'admin', 'imageProportional',
                                            $args                     = $img_args);
                                    $sValues ['H']            = $new_resize ['new_height'];
                                    $sValues ['W']            = $new_resize ['new_width'];
                                    $sValues ['file_exists1'] = 1;
                                }
                            }
                            // }
                            // $allValues[] = $sValues;
                            $existing_zProducts [] = $sValues [products_id];
                            $total_products []     = $sValues;
                        }
                        // return $sValues;
                        // echo "<pre>"; print_r($sValues); echo "</pre>";
                        $statement1 = Doctrine_Manager::getInstance()->closeConnection($statement1);
                    } catch (PDOException $Exception) {
                        
                    }
                }  // /
                else if ($shopType == 'iSHOP') { // INTERNAL-SHOP
                    $appnd_ishop = '';

                    if (!empty($existing_iProducts)) {
                        $repating_iproducts = implode(',', $existing_iProducts);
                        // $appnd_ishop = " AND p.product_id NOT IN($repating_iproducts) ";
                    }

                    $iproducts = $this->entityManager->getRepository('ZSELEX_Entity_Product')->getAdProducts(array(
                        'shop_id' => $shopsId,
                        'append' => $appnd_ishop,
                        'adv_selected' => $advSelected
                    ));
                    $icount    = count($iproducts);

                    if ($icount > 0) {
                        $iproducts ['adId']           = $config ['advertise_id'];
                        $iproducts ['products_name']  = $iproducts ['product_name'];
                        $iproducts ['products_id']    = $iproducts ['product_id'];
                        $iproducts ['products_image'] = $iproducts ['prd_image'];
                        // $iproducts['PRICE'] = $iproducts['prd_price'];
                        $iproducts ['SHOPTYPE']       = $shopType;
                        $iproducts ['SHOPID']         = $shopsId;
                        $iproducts ['shopName']       = $shopName;
                        $iproducts ['THEME']          = $iproducts ['shopTheme'];
                        // echo $sValues[$i]['shopName']; exit;

                        /*
                         * if (!empty($iproducts['products_image'])) {
                         * $file_exists1 = file_exists('zselexdata/' . $shopsId . '/products/medium/' . str_replace(" ", "%20", $iproducts['products_image']));
                         * //echo "helloo"; exit;
                         * // ZSELEX_Util::ajaxOutput(pnGetBaseURL()); exit;
                         * // list($width, $height, $type, $attr) = @getimagesize(pnGetBaseURL() . 'zselexdata/' . $iproducts['uname'] . '/products/' . str_replace(" ", "%20", $iproducts['products_image']));
                         * if ($file_exists1) {
                         * $imagepath = pnGetBaseURL() . 'zselexdata/' . $shopsId . '/products/medium/' . str_replace(" ", "%20", $iproducts['products_image']);
                         * // echo $imagepath; exit;
                         * $img_args = array(
                         * 'imagepath' => $imagepath,
                         * 'setheight' => $img_height,
                         * 'setwidth' => $img_width
                         * );
                         * $new_resize = ModUtil::apiFunc('ZSELEX', 'admin', 'imageProportional', $args = $img_args);
                         * $iproducts['H'] = $new_resize['new_height'];
                         * $iproducts['W'] = $new_resize['new_width'];
                         * $iproducts['file_exists1'] = 1;
                         * }
                         * }
                         */
                        // }
                        // $allValues[] = $iproducts;
                        // echo "<pre>"; print_r($iproducts); echo "</pre>";
                        $existing_iProducts [] = $iproducts [product_id];
                        $total_products []     = $iproducts;
                    }
                }
                /* } */
                // echo "<pre>"; print_r($allValues); echo "</pre>";
            } // /////
            // echo "<pre>"; print_r($allValues); echo "</pre>"; exit;
            $allValues = array_merge($allValues, $total_products);
            // echo "<pre>"; print_r($allValues); echo "</pre>"; exit;
            $aItem     = array();
            $aItem     = $allValues;

            $number = '';
            if (count($aItem) <= $amount) {
                $number = count($aItem);
            } else {
                $number = $amount;
            }
            // AjaxUtil::output("number :" . $number); exit;

            $prodval = $aItem;
            // $prodval = $this->array_random_assoc($aItem, $num = 2);
            // echo "<pre>"; print_r($prodval); echo "</pre>"; exit;
            $prodval = array_filter($prodval);
            // return $allValues;
            // echo "<pre>"; print_r($prodval); echo "</pre>";

            $output = '';

            $test = '';

            $counter = 0;
            $var     = '';
            $adss    = '';

            $productcount = count($prodval);
            $prdArr       = $prodval;
        } else {
            $prdArr = array();
        }

        return $prdArr;
    }
    /*
     * query builder for affiliates ID's
     *
     * @param int $aff_id
     * @return array of query string and affiliate id binding
     */

    function _affiliateAdquery($aff_id)
    {
        $affQuery   = '';
        $affParams  = array();
        $affIdArray = explode(',', $aff_id);
        // echo "<pre>"; print_r($affIdArray); echo "</pre>"; exit;
        if (!empty($affIdArray)) {
            foreach ($affIdArray as $k => $a) {
                if (is_numeric($a)) {
                    $affArray []             = "a.aff_id=:aff_id$k";
                    $affParams ['aff_id'.$k] = $a;
                }
            }
        }
        if (!empty($affArray)) {
            $affQuery = implode(' OR ', $affArray);
        }

        // echo $affQuery; exit;

        return array(
            'query' => $affQuery,
            'setParams' => $affParams
        );
    }

    public function getSpecialBlockEvents($args)
    {
        // exit;
        // echo $this->thempath;
        // echo "<pre>"; print_r($args); echo "</pre>";
        try {
            $shop_id     = $args ["shop_id"];
            $country_id  = $args ["country_id"];
            $region_id   = $args ["region_id"];
            $city_id     = $args ["city_id"];
            $area_id     = $args ["area_id"];
            $branch_id   = $args ["branch_id"];
            $category_id = $args ["category_id"];
            $search      = $args ["search"];
            $search      = ($search == $this->__('search for...') || $search == $this->__('search'))
                    ? '' : $search;
            $eventlimit  = 4;
            $limit       = $args ["limit"];
            $offset      = "4";

            $aff_id = $args ['aff_id'];

            // echo "<pre>"; print_r($args); echo "</pre>";
            if (!empty($limit)) {
                $offset = $limit;
            }
            // echo $shop_id; exit;
            // echo "OFFSET :" . $offset;
            if (!empty($eventlimit)) {
                $limit      = $eventlimit;
                $limitquery = "LIMIT 0 , $eventlimit";
            } else {
                $limit      = "2";
                $limitquery = "LIMIT 0 , $limit";
            }

            // $limit = 2;
            $joins       = '';
            $searchquery = '';
            $setParams   = array();
            if (($shop_id > 0 || !empty($shop_id)) || ($region_id > 0 || !empty($region_id))
                || ($city_id > 0 || !empty($city_id)) || ($country_id > 0 || !empty($country_id))
                || ($area_id > 0 || !empty($area_id)) || ($search > 0 || !empty($search))
                || ($category_id > 0 || !empty($category_id)) || ($branch_id > 0
                || !empty($branch_id) || !empty($eventdate))) {

                // $eventdateqry = " AND shop_event_startdate>=CURDATE()";
                $eventdateqry = '';
                $output       = '';

                // $shops = ModUtil::apiFunc('ZSELEX', 'admin', 'getShop', $items);
                $where  = '';
                $append = '';

                if (!empty($country_id)) { // COUNTRY
                    $append .= " AND a.country_id=$country_id";
                }

                if (!empty($region_id)) { // REGION
                    $append .= " AND a.region_id=$region_id";
                }

                if (!empty($city_id)) { // CITY
                    $append .= " AND a.city_id=$city_id";
                }

                if (!empty($area_id)) { // AREA
                    $append .= " AND a.area_id=$area_id";
                }

                if (!empty($shop_id)) { // SHOP
                    $append .= " AND a.shop_id=$shop_id";
                    // $setParams += array(
                    // 'shop' => $shop_id
                    // );
                }
                if (!empty($category_id)) {
                    // $append .= " AND a.cat_id=$category_id";
                    // $append .= " AND a.shop_id IN(SELECT shop_id FROM zselex_shop_to_category WHERE category_id=:category) ";

                    $append .= " AND cat.category_id=:category ";
                    $joins .= " INNER JOIN zselex_shop_to_category cat ON cat.shop_id=u.shop_id ";

                    $setParams += array(
                        'category' => DataUtil::formatForStore($category_id)
                    );
                }

                if (!empty($branch_id)) {
                    /*
                     * $append .= " AND a.branch_id=:branch";
                     * $setParams += array(
                     * 'branch' => $branch_id
                     * );
                     */
                    $append .= " AND branch.branch_id=:branch_id ";
                    $joins .= " INNER JOIN zselex_shop_to_branch branch ON branch.shop_id=u.shop_id ";
                    $setParams += array(
                        'branch_id' => $branch_id
                    );
                }

                if (!empty($aff_id)) {
                    $affQuery = self::_affiliateQuery($aff_id, 'a');
                    // $append .= " AND a.aff_id IN (:aff_ids)";
                    $append .= " AND (".$affQuery ['query'].")";
                    $setParams += $affQuery ['setParams'];
                }

                if (!empty($search)) {
                    // $append .= " AND (b.shop_id IN (SELECT shop_id FROM zselex_keywords WHERE keyword LIKE '%" . DataUtil::formatForStore($search) . "%') OR b.shop_id IN (SELECT shop_id FROM zselex_shop WHERE shop_name LIKE '%" . DataUtil::formatForStore($search) . "%'))";
                    $append .= " AND (a.shop_name LIKE :search OR MATCH (a.shop_name) AGAINST (:search2 IN BOOLEAN MODE) OR a.shop_id IN (SELECT shop_id FROM zselex_keywords WHERE keyword LIKE :search  OR MATCH (keyword) AGAINST (:search2 IN BOOLEAN MODE)))";
                    $setParams += array(
                        'search' => '%'.DataUtil::formatForStore($search).'%',
                        'search2' => DataUtil::formatForStore($search)
                    );
                }

                // echo $append; exit;

                $all_shops_events = $this->entityManager->getRepository('ZSELEX_Entity_Event')->getSpecialBlockEvents(array(
                    'offset' => $offset,
                    'append' => $append,
                    'setParams' => $setParams,
                    'joins' => $joins
                ));
                // echo "<pre>"; print_r($alls); echo "</pre>"; exit;
                $count            = count($all_shops_events);
            } else {

                $count = 0;
            }
            $shopsql = '';
            if ($count > 0) {

                $eventArray = $all_shops_events;
            } else {

                $eventArray = array();
            }

            return $eventArray;
        } catch (\Exception $e) {
            echo 'Message: '.$e->getMessage().'<br>';
            // echo $query->getSQL();
            exit();
        }
    }
}
// end class def