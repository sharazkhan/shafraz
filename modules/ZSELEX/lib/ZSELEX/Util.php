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
 * External Util class for example
 */
class ZSELEX_Util
{

    public static function externalfunction()
    {
        return true;
    }

    // public static function validateType($type) {
    //
	// //echo "checkingggg"; exit;
    // $dom = ZLanguage::getModuleDomain('ZSELEX');
    // $validationerror = false;
    // if ($type['action'] != ZSELEX_Controller_Admin::ACTION_SUBMIT && empty($type['type_name'])) {
    // $validationerror .= __f('Error! You did not enter a %s.', __('type_name', $dom), $dom) . "\n";
    // }
    // return $validationerror;
    // }
    //
	public static function validateType($type)
    {
        $dom             = ZLanguage::getModuleDomain('ZSELEX');
        $validationerror = false;
        if (empty($type ['type_name'])) {
            $validationerror .= __f('Error! You did not enter a %s.',
                    __('Type name', $dom), $dom)."\n";
        }
        if (empty($type ['description'])) {
            $validationerror .= __f('Error! You did not enter a %s.',
                    __('Type description', $dom), $dom)."\n";
        }
        return $validationerror;
    }

    public static function validate($validation_rules)
    {
        $dom             = ZLanguage::getModuleDomain('ZSELEX');
        $validationerror = false;

        foreach ($validation_rules as $names => $values) {
            $name  = $validation_rules [$names];
            $value = $validation_rules [$names] ['value'];
            $label = $validation_rules [$names] ['label'];
            // check required values
            if (isset($validation_rules [$names] ['required']) && $validation_rules [$names] ['required']
                && empty($value)) {
                $validationerror .= __f('Error! You did not enter a %s.',
                        __($label, $dom), $dom)."\n";
            }

            if ($validation_rules [$names] ['required'] && isset($validation_rules [$names] ['email'])
                && $validation_rules [$names] ['email'] && !filter_var($value,
                    FILTER_VALIDATE_EMAIL) && !empty($value)) {
                $validationerror .= __f("Error! Please enter a valid Email")."\n";
            }

            if ($validation_rules [$names] ['required'] && isset($validation_rules [$names] ['exist'])
                && $validation_rules [$names] ['exist'] && !empty($value)) {
                $field_name  = $validation_rules [$names] ['field_name'];
                $field_value = $validation_rules [$names] ['field_value'];
                $sql         = "";
                if ($validation_rules [$names] ['edit'] == true) {
                    $edit_id = $validation_rules [$names] ['edit_id'];
                    $idName  = $validation_rules [$names] ['idName'];
                    $sql     = " AND $idName!='".$edit_id."'";
                }

                $fieldExist = ModUtil::apiFunc('ZSELEX', 'admin', 'getCount',
                        $args       = array(
                        'table' => $validation_rules [$names] ['table'],
                        'where' => "$field_name = '".$value."'".$sql
                ));
                if ($fieldExist > 0) {
                    $validationerror .= __f("Error! $label already exists")."\n";
                }
            }

            if ($validation_rules [$names] ['required'] && isset($validation_rules [$names] ['int'])
                && $validation_rules [$names] ['int'] && !is_numeric($value) && !empty($value)) {
                $validationerror .= __f("Error! $label should be numeric")."\n";
            }
        }
        return $validationerror;
    }

    public static function validateItems($type)
    {
        $dom             = ZLanguage::getModuleDomain('ZSELEX');
        $validationerror = false;
        foreach ($type as $key => $val) {
            $split = explode("|", $key);
            $msg   = $split [1];
            if (empty($val)) {
                $validationerror .= __f('Error! You did not enter a %s.',
                        __($msg, $dom), $dom)."\n";
            }
        }

        return $validationerror;
    }

    public static function validateDotd($args, $itemValidate)
    {
        $validationerror = false;
        $validationerror = ZSELEX_Util::validateItems($itemValidate);

        $dom = ZLanguage::getModuleDomain('ZSELEX');

        $column     = $args ['checkColum'];
        $value      = $args ['checkValue'];
        $product_id = $args ['product_id'];
        $sql        = "SELECT COUNT(*) as count FROM zselex_dotd WHERE $column='".$value."'";
        $query      = DBUtil::executeSQL($sql);
        $result     = $query->fetch();

        $count = $result ['count'];

        $date = date("Y-m-d");

        if ($count > 0) {
            $validationerror .= __f('DOTD: Date already exist', __($msg, $dom),
                    $dom)."\n";
        }
        if (!empty($value) && ($value < $date)) {
            $validationerror .= __f('DOTD: Date cannot be older than current date',
                    __($msg, $dom), $dom)."\n";
        }

        if (empty($product_id)) {
            $validationerror .= __f('Please choose a product', __($msg, $dom),
                    $dom)."\n";
        }

        return $validationerror;
    }

    public static function validateShopEvent($item)
    {
        $dom             = ZLanguage::getModuleDomain('ZSELEX');
        $validationerror = false;
        $date            = date("Y-m-d");
        // echo "<pre>"; print_r($item); echo "</pre>"; exit;
        if (empty($item ['shop_event_name'])) {
            $validationerror .= __f('Error! You did not enter a %s.',
                    __('Event name', $dom), $dom)."\n";
        }
        if (empty($item ['shop_event_description'])) {
            $validationerror .= __f('Error! You did not enter a %s.',
                    __('Event description', $dom), $dom)."\n";
        }
        if (empty($item ['shop_event_startdate'])) {
            $validationerror .= __f('Error! You did not enter a %s.',
                    __('Event start date', $dom), $dom)."\n";
        } elseif ($item ['shop_event_startdate'] < $date) {
            if ($item ['type'] != 'modify') {
                $validationerror .= __f('Event: Start date cannot be lesser than current date',
                        __($msg, $dom), $dom)."\n";
            }
        }

        if (empty($item ['shop_event_enddate'])) {
            $validationerror .= __f('Error! You did not enter a %s.',
                    __('Event end date', $dom), $dom)."\n";
        } elseif ($item ['shop_event_enddate'] < $date) {
            if ($item ['type'] != 'modify') {
                $validationerror .= __f('Event: End date cannot be less than current date',
                        __($msg, $dom), $dom)."\n";
            }
        } elseif ($item ['shop_event_enddate'] < $item ['shop_event_startdate']) {
            $validationerror .= __f('Event: End date cannot be less then start date',
                    __($msg, $dom), $dom)."\n";
        }
        if ($item ['upload_check'] == 1) {
            if (empty($item ['showfrom'])) {
                $validationerror .= __f('Error! You did choose which to display (Article , image or document) %s.',
                        __($msg, $dom), $dom)."\n";
            }
        }
        return $validationerror;
    }

    public static function validateDotdModify($args, $itemValidate)
    {
        $validationerror = false;
        $validationerror = ZSELEX_Util::validateItems($itemValidate);

        $dom = ZLanguage::getModuleDomain('ZSELEX');

        $column       = $args ['checkColum'];
        $value        = $args ['checkValue'];
        $product_id   = $args ['product_id'];
        $selectedDate = $args ['selectedDate'];

        $sql    = "SELECT COUNT(*) as count FROM zselex_dotd WHERE $column='".$value."' AND dotd_date!='".$selectedDate."'";
        // echo $sql; exit;
        $query  = DBUtil::executeSQL($sql);
        $result = $query->fetch();

        $count = $result ['count'];

        $date = date("Y-m-d");

        if ($count > 0) {
            $validationerror .= __f('DOTD: Date already exist', __($msg, $dom),
                    $dom)."\n";
        }
        if (!empty($value) && ($value < $date) && ($selectedDate != $value)) {
            $validationerror .= __f('DOTD: Date cannot be older than current date',
                    __($msg, $dom), $dom)."\n";
        }

        if (empty($product_id)) {
            $validationerror .= __f('Please choose a product', __($msg, $dom),
                    $dom)."\n";
        }

        return $validationerror;
    }

    public function getErrors($postData, $rules)
    {
        $errors = array();

        // validate each existing input
        foreach ($postData as $name => $value) {

            // if rule not found, skip loop iteration
            if (!isset($rules [$name])) {
                continue;
            }

            // convert special characters to HTML entities
            $fieldName = htmlspecialchars($name);

            $rule = $rules [$name];

            // check required values
            if (isset($rule ['required']) && $rule ['required'] && !$value) {

                $validationerror .= __f('Field '.$fieldName.' is required.',
                        __($msg, $dom), $dom)."\n";
            }

            // check field's minimum length
            if (isset($rule ['minlength']) && strlen($value) < $rule ['minlength']) {
                $errors [] = $fieldName.' should be at least '.$rule ['minlength'].' characters length.';
            }

            // verify email address
            if (isset($rule ['email']) && $rule ['email'] && !filter_var($value,
                    FILTER_VALIDATE_EMAIL)) {
                $errors [] = $fieldName.' must be valid email address.';
            }

            $rules [$name] ['found'] = true;
        }

        // check for missing inputs
        foreach ($rules as $name => $values) {
            if (!isset($values ['found']) && isset($values ['required']) && $values ['required']) {
                $errors [] = 'Field '.htmlspecialchars($name).' is required.';
            }
        }

        return $errors;
    }

    public static function validateItems1($type)
    {
        $dom             = ZLanguage::getModuleDomain('ZSELEX');
        $validationerror = false;

        foreach ($type as $key => $val) {

            $pos = strrpos($key, "|");
            if ($pos == true) {

                $split = explode("|", $key);
                $msg   = $split [1];

                if (empty($val)) {
                    $validationerror .= __f('Error! You did not enter a %s.',
                            __($msg, $dom), $dom)."\n";
                }
            }
        }

        return $validationerror;
    }

    public static function validatePayPal($type)
    {
        $dom             = ZLanguage::getModuleDomain('ZSELEX');
        $validationerror = false;
        if (empty($type ['ppemail'])) {
            $validationerror .= __f('Error! You did not enter a %s.',
                    __('PayPal Email', $dom), $dom)."\n";
        }

        if (filter_var($type ['ppemail'], FILTER_VALIDATE_EMAIL)) {
            $valid = 1; // valid email
        } else {
            $valid = 0; // not valid
        }

        if ($valid < 1) {
            $validationerror .= __f('Error! Invalid %s.',
                    __('PayPal Email', $dom), $dom)."\n";
        }
        // if (empty($type['description'])) {
        // $validationerror .= __f('Error! You did not enter a %s.', __('Type description', $dom), $dom) . "\n";
        // }
        return $validationerror;
    }

    public static function validatePayPalOwner($type)
    {
        $dom             = ZLanguage::getModuleDomain('ZSELEX');
        $validationerror = false;
        if (empty($type ['ppemail'])) {
            $validationerror .= __f('Error! You did not enter a %s.',
                    __('PayPal Email', $dom), $dom)."\n";
        }

        if (filter_var($type ['ppemail'], FILTER_VALIDATE_EMAIL)) {
            $valid = 1; // valid email
        } else {
            $valid = 0; // not valid
        }

        if ($valid < 1) {
            $validationerror .= __f('Error! Invalid %s.',
                    __('PayPal Email', $dom), $dom)."\n";
        }
        // if (empty($type['description'])) {
        // $validationerror .= __f('Error! You did not enter a %s.', __('Type description', $dom), $dom) . "\n";
        // }
        return $validationerror;
    }

    public static function validateArticle($story)
    {
        $dom             = ZLanguage::getModuleDomain('News');
        // Validate the input
        $validationerror = false;
        if ($story ['action'] != News_Controller_User::ACTION_PREVIEW && empty($story ['title'])) {
            $validationerror .= __f('Error! You did not enter a %s.',
                    __('title', $dom), $dom)."\n";
        }
        // both text fields can't be empty
        if ($story ['action'] != News_Controller_User::ACTION_PREVIEW && empty($story ['hometext'])
            && empty($story ['bodytext'])) {
            $validationerror .= __f('Error! You did not enter the minimum necessary %s.',
                    __('article content', $dom), $dom)."\n";
        }

        return $validationerror;
    }

    public static function validateService($story)
    {
        $dom             = ZLanguage::getModuleDomain('News');
        // Validate the input
        $validationerror = false;
        if ($story ['action'] != News_Controller_User::ACTION_PREVIEW && empty($story ['title'])) {
            $validationerror .= __f('Error! You did not enter a %s.',
                    __('title', $dom), $dom)."\n";
        }
        // both text fields can't be empty
        if ($story ['action'] != News_Controller_User::ACTION_PREVIEW && empty($story ['hometext'])
            && empty($story ['bodytext'])) {
            $validationerror .= __f('Error! You did not enter the minimum necessary %s.',
                    __('article content', $dom), $dom)."\n";
        }

        return $validationerror;
    }

    public static function validateCheckOut($item)
    {
        $dom             = ZLanguage::getModuleDomain('ZSELEX');
        $validationerror = false;
        if (empty($type ['ppemail'])) {
            $validationerror .= __f('Error! You did not enter a %s.',
                    __('PayPal Email', $dom), $dom)."\n";
        }

        if (filter_var($type ['ppemail'], FILTER_VALIDATE_EMAIL)) {
            $valid = 1; // valid email
        } else {
            $valid = 0; // not valid
        }

        if ($valid < 1) {
            $validationerror .= __f('Error! Invalid %s.',
                    __('PayPal Email', $dom), $dom)."\n";
        }
        // if (empty($type['description'])) {
        // $validationerror .= __f('Error! You did not enter a %s.', __('Type description', $dom), $dom) . "\n";
        // }
        return $validationerror;
    }

    public static function addStandardFieldsToTableDefinition(&$columns,
                                                              $col_prefix = '')
    {
        // ensure correct handling of prefix with and without underscore
        if ($col_prefix) {
            $plen = strlen($col_prefix);
            if ($col_prefix [$plen - 1] != '_') {
                $col_prefix .= '_';
            }
        }

        // add standard fields
        // $columns['obj_status'] = $col_prefix . 'obj_status';
        $columns ['cr_date'] = $col_prefix.'cr_date';
        $columns ['cr_uid']  = $col_prefix.'cr_uid';
        $columns ['lu_date'] = $col_prefix.'lu_date';
        $columns ['lu_uid']  = $col_prefix.'lu_uid';

        return;
    }

    public static function addStandardFieldsToTableDataDefinition(&$columns)
    {
        // $columns['obj_status'] = "C(1) NOTNULL DEFAULT 'A'";
        $columns ['cr_date'] = "T NOTNULL DEFAULT '1970-01-01 00:00:00'";
        $columns ['cr_uid']  = "I NOTNULL DEFAULT '0'";
        $columns ['lu_date'] = "T NOTNULL DEFAULT '1970-01-01 00:00:00'";
        $columns ['lu_uid']  = "I NOTNULL DEFAULT '0'";

        return;
    }

    public static function ajaxOutput($data, $code = '200 OK')
    {
        // exit;
        if (!System::isLegacyMode()) {
            $response = new Zikula_Response_Ajax($data);
            echo $response;
            System::shutDown();
        }
        // Below for reference - to be deleted.
        // check if an error message is set
        $msgs = LogUtil::getErrorMessagesText('<br />');

        if ($msgs != false && !empty($msgs)) {
            self::error($msgs);
        }

        echo $data;
        System::shutdown();
    }

    public static function validateServiceIdentifier($item)
    {
        $validationerror = false;
        $dom             = ZLanguage::getModuleDomain('ZSELEX');

        // echo "<pre>"; print_r($item); exit;

        if (empty($item ['name'])) {
            $validationerror .= __f('Error! You did not enter a %s.',
                    __('name', $dom), $dom)."\n";
        }

        if (empty($item ['identifier'])) {
            $validationerror .= __f('Error! You did not enter a %s.',
                    __('Identifier', $dom), $dom)."\n";
        }

        $and = '';
        if (!empty($item ['selected'])) {
            $and = "AND identifier!='".$item ['selected']."'";
        }

        $sql    = "SELECT COUNT(*) as count FROM zselex_service_identifiers WHERE identifier='".$item ['identifier']."'"." ".$and;
        $query  = DBUtil::executeSQL($sql);
        $result = $query->fetch();
        $count  = $result ['count'];

        $date = date("Y-m-d");

        if ($count > 0) {
            $validationerror .= __f('Error: %s already exist',
                    __('Identifier', $dom), $dom)."\n";
        }

        return $validationerror;
    }

    public static function validateProductAd($type)
    {
        $em              = ServiceUtil::getService('doctrine.entitymanager');
        $repo            = $em->getRepository('ZSELEX_Entity_Bundle');
        $dom             = ZLanguage::getModuleDomain('ZSELEX');
        $validationerror = false;
        if (empty($type ['ad_level'])) {
            $validationerror .= __f('Error! You did not choose a %s.',
                    __('Ad Level', $dom), $dom)."\n";
        } else {

            /*
             * $get = ModUtil::apiFunc('ZSELEX', 'user', 'get', $getargs =
             * array('table' => 'zselex_advertise_price',
             * 'where' => "identifier='" . $type['ad_level'] . "'"));
             */

            $get   = $repo->get(array(
                'entity' => 'ZSELEX_Entity_AdvertisePrice',
                'fields' => array(
                    'a.price'
                ),
                'where' => array(
                    'a.identifier' => $type ['ad_level']
                )
            ));
            // echo "<pre>"; print_r($get); echo "</pre>"; exit;
            $price = $get ['price'];
            /*
             * $service = ModUtil::apiFunc('ZSELEX', 'user', 'get', $getargs =
             * array('table' => 'zselex_serviceshop',
             * 'where' => "type='createad' AND shop_id='" . $type['shop_id'] . "'"));
             */

            $service           = $repo->get(array(
                'entity' => 'ZSELEX_Entity_ServiceShop',
                'where' => array(
                    'a.type' => 'createad',
                    'a.shop' => $type ['shop_id']
                )
            ));
            // echo "<pre>"; print_r($service); echo "</pre>"; exit;
            $remaining_service = $service ['quantity'] - $service ['availed'];
            if ($remaining_service < $price) {
                $validationerror .= __f('Error! You dont have enough Ads for creating Ad in chosen level')."\n";
            }
        }
        if (empty($type ['description'])) {
            // $validationerror .= __f('Error! You did not enter a %s.', __('Type description', $dom), $dom) . "\n";
        }
        return $validationerror;
    }

    function cleanTitle($str, $replace = array(), $delimiter = '-')
    {
        setlocale(LC_ALL, 'en_US.UTF8');
        if (!empty($replace)) {
            $str = str_replace((array) $replace, ' ', $str);
        }

        $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
        $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
        $clean = strtolower(trim($clean, '-'));
        $clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);

        return $clean;
    }

    function in_assoc_array($value, $key, $array)
    {
        foreach ($array as $v) {
            if ($key != '') {
                if ($v [$key] == $value) {
                    return true;
                }
            } else {
                if (in_array($value, $v ['prd_cat_id'])) {
                    return true;
                }
            }
        }
        return false;
    }

    function MODULE_INFO($modname)
    {
        $modid   = ModUtil::getIdFromName($modname);
        $modinfo = ModUtil::getInfo($modid);
        return $modinfo;
    }

    static public function shopPermission($shop_id)
    {
        // $shop_id = FormUtil::getPassedValue('shop_id', null, 'REQUEST');
        $shop_id = $shop_id;
        if (!(is_numeric($shop_id))) {
            return 0;
        } else {
            $user_id = UserUtil::getVar('uid');
            $perm    = ModUtil::apiFunc('ZSELEX', 'admin', 'shopPermission',
                    array(
                    'shop_id' => $shop_id,
                    'user_id' => $user_id
            ));
            return $perm;
        }
    }

    public function convert_price($price)
    {
        $curr_args       = array(
            'amount' => $price,
            'currency_symbol' => '',
            'decimal_point' => ',',
            'thousands_sep' => '.',
            'precision' => '2'
        );
        $converted_price = ModUtil::apiFunc('ZSELEX', 'user', 'number2currency',
                $curr_args);
        return $converted_price;
    }

    public static function purifyHtml($formElements)
    {
        foreach ($formElements as $key => $val) {
            $formElements [$key] = DataUtil::formatForStore($val);
        }
        return $formElements;
    }

    function disable_magic_quotes()
    {
        if (get_magic_quotes_gpc()) {
            $process = array(
                &$_GET,
                &$_POST,
                &$_COOKIE,
                &$_REQUEST
            );
            while (list ( $key, $val ) = each($process)) {
                foreach ($val as $k => $v) {
                    unset($process [$key] [$k]);
                    if (is_array($v)) {
                        $process [$key] [stripslashes($k)] = $v;
                        $process []                        = &$process [$key] [stripslashes($k)];
                    } else {
                        $process [$key] [stripslashes($k)] = stripslashes($v);
                    }
                }
            }
            unset($process);
        }
    }

    function createDateRangeArray1($start, $end)
    {
        $range = array();

        if (is_string($start) === true) $start = strtotime($start);
        if (is_string($end) === true) $end   = strtotime($end);

        if ($start > $end) return $this->createDateRangeArray($end, $start);

        do {
            $range [] = date('Y-m-d', $start);
            $start    = strtotime("+ 1 day", $start);
        } while ($start <= $end);

        return $range;
    }

    function createDateRangeArray($start, $end)
    {
        $todayDate = date("Y-m-d");
        if ($end > $todayDate) {
            // $start = $todayDate;
        }
        $range = array();

        if (is_string($start) === true) $start = strtotime($start);
        if (is_string($end) === true) $end   = strtotime($end);

        if ($start > $end) return $this->createDateRangeArray($end, $start);

        do {
            $range [] = date('Y-m-d', $start);
            $start    = strtotime("+ 1 day", $start);
        } while ($start <= $end);

        return $range;
    }

    static function getRandLimit($count, $end)
    {
        // $count = 1;
        $rand = mt_rand(0, $count);
        // $end = 2;

        $next = $rand + $end;
        // echo $next . '<br>';
        if ($next > $count) {
            $rand = $rand - $end;
            if ($rand < 0) {
                $rand = 0;
            }
        }

        return array(
            'start' => $rand,
            'end' => $end
        );
    }

    /**
     *
     * @param type $string
     * @return type
     */
    public static function stripLineBreaks($string)
    {
        $output = preg_replace("/\r\n|\r|\n/", '<br/>', $string);
        return $output;
    }

    /**
     * Creates unique urltitle
     *
     * @param array $args
     *        	title - name for title
     *        	table - table name
     *        	field - field name to check
     * @return string urltitle
     */
    static public function increment_url($args)
    {
        $statement = Doctrine_Manager::getInstance()->connection();
        $title     = $args ['title'];
        $title     = self::cleanTitle($title);
        $table     = $args ['table'];
        $field     = $args ['field'];
        $sql       = "SELECT COALESCE( CONCAT( '".$title."', SUBSTRING( MAX( $field ) , CHAR_LENGTH( '".$title."' ) +1 ) *1 +1 ) , '".$title."' ) $field
                    FROM $table
                    WHERE $field REGEXP '$title([0-9]+)?$'";
        $query     = $statement->execute($sql);
        $result    = $query->fetch();
        $urltitle  = $result [$field];
        return $urltitle;
    }

    /**
     * Execute script in background
     *
     * @return void
     */
    static function execInBackground($cmd)
    {
        //echo $cmd; exit;
        if (substr(php_uname(), 0, 7) == "Windows") {
            // echo "Windows"; exit;
            pclose(popen("start /B ".$cmd, "r"));
        } else {
            exec($cmd." > /dev/null &");
        }
    }
    /*
     * Handle error logs
     * Creates a log file in the specified path
     *
     * @param string $file
     * @param string $folder
     * @param string $msf
     * @return true
     */

    function logError($file, $folder, $msg = '')
    {
        $basePath = "modules/ZSELEX/errors/";
        if (!file_exists($basePath) && !empty($basePath)) {
            mkdir($basePath, 0775, true);
            chmod($basePath, 0775);
        }

        if (!empty($folder)) {
            $path = $basePath.$folder.'/';
            if (!file_exists($path) && !empty($path)) {
                mkdir($path, 0775, true);
                chmod($path, 0775);
            }
        } else {
            $path = $basePath;
        }

        $logFile = $path.$file;

        $todayDate = date("Y-m-d");
        $message   = "";
        $message .= $todayDate."\r\n";
        $message .= "============\r\n";
        $message .= "$msg\r\n\r\n\r\n";
        error_log($message, 3, $logFile);

        return true;
    }

    function getDevice()
    {
        $tablet_browser = 0;
        $mobile_browser = 0;

        if (preg_match('/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i',
                strtolower($_SERVER ['HTTP_USER_AGENT']))) {
            $tablet_browser ++;
        }

        if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|iemobile)/i',
                strtolower($_SERVER ['HTTP_USER_AGENT']))) {
            $mobile_browser ++;
        }

        if ((strpos(strtolower($_SERVER ['HTTP_ACCEPT']),
                'application/vnd.wap.xhtml+xml') > 0) or ( (isset($_SERVER ['HTTP_X_WAP_PROFILE']) or isset($_SERVER ['HTTP_PROFILE'])))) {
            $mobile_browser ++;
        }

        $mobile_ua     = strtolower(substr($_SERVER ['HTTP_USER_AGENT'], 0, 4));
        $mobile_agents = array(
            'w3c ',
            'acs-',
            'alav',
            'alca',
            'amoi',
            'audi',
            'avan',
            'benq',
            'bird',
            'blac',
            'blaz',
            'brew',
            'cell',
            'cldc',
            'cmd-',
            'dang',
            'doco',
            'eric',
            'hipt',
            'inno',
            'ipaq',
            'java',
            'jigs',
            'kddi',
            'keji',
            'leno',
            'lg-c',
            'lg-d',
            'lg-g',
            'lge-',
            'maui',
            'maxo',
            'midp',
            'mits',
            'mmef',
            'mobi',
            'mot-',
            'moto',
            'mwbp',
            'nec-',
            'newt',
            'noki',
            'palm',
            'pana',
            'pant',
            'phil',
            'play',
            'port',
            'prox',
            'qwap',
            'sage',
            'sams',
            'sany',
            'sch-',
            'sec-',
            'send',
            'seri',
            'sgh-',
            'shar',
            'sie-',
            'siem',
            'smal',
            'smar',
            'sony',
            'sph-',
            'symb',
            't-mo',
            'teli',
            'tim-',
            'tosh',
            'tsm-',
            'upg1',
            'upsi',
            'vk-v',
            'voda',
            'wap-',
            'wapa',
            'wapi',
            'wapp',
            'wapr',
            'webc',
            'winw',
            'winw',
            'xda ',
            'xda-'
        );

        if (in_array($mobile_ua, $mobile_agents)) {
            $mobile_browser ++;
        }

        if (strpos(strtolower($_SERVER ['HTTP_USER_AGENT']), 'opera mini') > 0) {
            $mobile_browser ++;
            // Check for tablets on opera mini alternative headers
            $stock_ua = strtolower(isset($_SERVER ['HTTP_X_OPERAMINI_PHONE_UA'])
                        ? $_SERVER ['HTTP_X_OPERAMINI_PHONE_UA'] : (isset($_SERVER ['HTTP_DEVICE_STOCK_UA'])
                            ? $_SERVER ['HTTP_DEVICE_STOCK_UA'] : '') );
            if (preg_match('/(tablet|ipad|playbook)|(android(?!.*mobile))/i',
                    $stock_ua)) {
                $tablet_browser ++;
            }
        }

        if ($tablet_browser > 0) {
            // do something for tablet devices
            // print 'is tablet';
            return 'tablet';
        } else if ($mobile_browser > 0) {
            // do something for mobile devices
            // print 'is mobile';
            return 'mobile';
        } else {
            // do something for everything else
            // print 'is desktop';
            return 'desktop';
        }
    }

    /**
     * Create temporary user_id for guest user
     * 
     * @return int
     */
    function getTempUserId()
    {

        $getTempUserId = $_COOKIE['temp_user_id'];
        // echo "From Cookie :" . $getTempUserId . '<br>';
        if (!$getTempUserId) {
            $getTempUserId = $_SESSION['temp_user_id'];
        }


        if (!$getTempUserId) {
            $tempUserId               = uniqid();
            $getTempUserId            = $tempUserId;
            $_SESSION['temp_user_id'] = $tempUserId;
            setcookie("temp_user_id", $tempUserId, time() + 604800, '/');
        }

        return $getTempUserId;
    }

    function dumpValue($value, $servicetype)
    {

        $userId = UserUtil::getVar('uid');
        if ($userId != 124) {
            return true;
        }
        if ($servicetype == 'minisiteimages') {
            echo "<pre>";
            print_r($value);
            echo "</pre>";
        }
    }
}
// end class def