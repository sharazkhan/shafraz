<?php
/**
 * Copyright ACTA-IT 2014 - ZPayment
 *
 * ZPayment
 * Payment Gateway Module
 *
 * @license GNU/LGPLv3 (or at your option, any later version).
 */

/**
 * Class to control User interface
 */
class ZPayment_Controller_User extends Zikula_AbstractController
{

    /**
     * main
     *
     * main view function for end user
     * @access public
     */
    public function main()
    {
        $this->redirect(ModUtil::url('ZPayment', 'user', 'view'));
    }

    /**
     * view items
     * This is a standard function to provide an overview of all of the items
     * available from the module.
     */
    public function view()
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZPayment::',
                '::', ACCESS_OVERVIEW), LogUtil::getErrorMsgPermission());

        $this->view->assign('external_function',
            ZPayment_Util::externalfunction());

        return $this->view->fetch('user/view.tpl');
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

    public function registerTransaction($args)
    {
        // echo "<pre>";  print_r($args);  echo "</pre>";   exit;
        //echo "comes here nets"; exit;
        // require_once 'modules/ZPayment/lib/ZPayment/Parameters.php';
        $returnArray = array();
        $args        = $_SESSION['checkoutinfo'];
        // echo "<pre>";  print_r($args);  echo "</pre>";   exit;
        require_once ('modules/ZPayment/lib/Netaxept/ClassRegisterRequest.php');
        require_once ('modules/ZPayment/lib/Netaxept/ClassCustomer.php');
        require_once ('modules/ZPayment/lib/Netaxept/ClassTerminal.php');
        require_once ('modules/ZPayment/lib/Netaxept/ClassItem.php');
        require_once ('modules/ZPayment/lib/Netaxept/ClassArrayOfItem.php');
        require_once ('modules/ZPayment/lib/Netaxept/ClassOrder.php');
        require_once ('modules/ZPayment/lib/Netaxept/ClassEnvironment.php');



        $modvars         = ModUtil::getVar('ZPayment');
        // echo "<pre>";  print_r($modvars);  echo "</pre>"; exit;
        $test_mode       = $modvars['Netaxept_testmode'];
        $merchantId      = $modvars['Netaxept_merchant_id'];
        $token           = $modvars['Netaxept_token'];
        $zselex_order_id = $args['order_id'];
        $cart_shop_id    = $args['shop_id'];
        $redirect_url    = pnGetBaseURL().ModUtil::url('ZSELEX', 'user',
                'netsReturn',
                array('orderId' => $zselex_order_id, 'cart_shop_id' => $cart_shop_id));

        if ($test_mode) {
            $wsdl     = "https://epayment-test.bbs.no/Netaxept.svc?wsdl";
            $terminal = "https://epayment-test.bbs.no/terminal/default.aspx";
        } else {
            $wsdl     = "https://epayment.bbs.no/Netaxept.svc?wsdl";
            $terminal = "https://epayment.bbs.no/terminal/default.aspx";
        }


        ####  PARAMETERS IN ORDER  ####
        $amount = (int) $args['grand_total']; // The amount described as the lowest monetary unit, example: 100,00 NOK is noted as "10000", 9.99 USD is noted as "999".
        // echo "Amount :" . $amount;exit;


        $currencyCode            = "DKK";  //The currency code, following ISO 4217. Typical examples include "DKK" and "USD".
        $force3DSecure           = null;   // Optional parameter
        $orderNumber             = md5(uniqid('', true));
        $UpdateStoredPaymentInfo = null;

####  PARAMETERS IN ENVIRONMENT  ####
        $Language            = null; // Optional parameter
        $OS                  = null; // Optional parameter
        $WebServicePlatform  = 'PHP5'; // Required (for Web Services)
####  PARAMETERS IN TERMINAL  ####
        $autoAuth            = null; // Optional parameter
        $paymentMethodList   = null; // Optional parameter
        $language            = "en_GB"; // Optional parameter
        $orderDescription    = "Order Description"; // Optional parameter
        $redirectOnError     = null; // Optional parameter
####  PARAMETERS IN REGISTER REQUEST  ####
        $AvtaleGiro          = null; // Optional parameter
        $CardInfo            = null; // Optional parameter
        $description         = "description of Order Registration"; // Optional parameter
        $DnBNorDirectPayment = null; // Optional parameter
        $Environment         = null; // Optional parameter for REST
        $MicroPayment        = null; // Optional parameter
        $serviceType         = null; // Optional parameter
        $Recurring           = null; // Optional parameter
        $transactionId       = null; // Optional parameter
        $transactionReconRef = null; // Optional parameter
####  PARAMETERS IN CUSTOMER  ####

        $customerAddress1                  = $args['address'];    // Optional parameter (required if DnBNorDirectPayment)
        $customerAddress2                  = "";                         // Optional parameter
        $customerCompanyName               = null;                       // Optional parameter
        $customerCompanyRegistrationNumber = null;                       // Optional parameter
        $customerCountry                   = "NO";                       // Optional parameter
        $customerFirstName                 = $args['fname'];                   // Optional parameter (required if DnBNorDirectPayment)
        $customerLastName                  = $args['lname'];                 // Optional parameter (required if DnBNorDirectPayment)
        $customerNumber                    = UserUtil::getVar('uid');                  // Optional parameter
        $customerEmail                     = $args['email'];   // Optional parameter
        $customerPhoneNumber               = $args['phone'];               // Optional parameter
        $customerPostcode                  = $args['zip'];                     // Optional parameter (required if DnBNorDirectPayment)
        $customerSocialSecurityNumber      = "18106500157";              // Optional parameter
        $customerTown                      = $args['city'];                     // Optional parameter (required if DnBNorDirectPayment)



        $ArrayOfItem = null; // no goods for Klana ==> normal transaction
####  ENVIRONMENT OBJECT  ####
        $Environment = new Environment(
            $Language, $OS, $WebServicePlatform
        );

####  TERMINAL OBJECT  ####
        $Terminal = new Terminal(
            $autoAuth, $paymentMethodList, $language, $orderDescription,
            $redirectOnError, $redirect_url
        );

####  ORDER OBJECT  ####
        /* $Order = new Order(
          $amount,
          $currencyCode,
          $force3DSecure,
          $ArrayOfItem,
          $orderNumber,
          $UpdateStoredPaymentInfo
          ); */

        $Order                          = new stdClass();
        $Order->Amount                  = $amount;
        $Order->CurrencyCode            = $currencyCode;
        $Order->Force3DSecure           = '';
        $Order->Goods                   = '';
        // $Order->OrderNumber = $orderNumber;
        $Order->OrderNumber             = $zselex_order_id;
        $Order->UpdateStoredPaymentInfo = '';

####  CUSTOMER OBJECT  ####
        $Customer = new Customer(
            $customerAddress1, $customerAddress2, $customerCompanyName,
            $customerCompanyRegistrationNumber, $customerCountry,
            $customerNumber, $customerEmail, $customerFirstName,
            $customerLastName, $customerPhoneNumber, $customerPostcode,
            $customerSocialSecurityNumber, $customerTown
        );

####  START REGISTER REQUEST  ####
        $RegisterRequest = new RegisterRequest(
            $AvtaleGiro, $CardInfo, $Customer, $description,
            $DnBNorDirectPayment, $Environment, $MicroPayment, $Order,
            $Recurring, $serviceType, $Terminal, $transactionId,
            $transactionReconRef
        );



####  ARRAY WITH REGISTER PARAMETERS  ####
        $InputParametersOfRegister = array
            (
            "token" => $token,
            "merchantId" => $merchantId,
            "request" => $RegisterRequest
        );

        ####  START REGISTER CALL  ####
        try {
            if (strpos($_SERVER["HTTP_HOST"], 'uapp') > 0) {
                // Creating new client having proxy
                $client = new SoapClient($wsdl,
                    array('proxy_host' => "isa4", 'proxy_port' => 8080, 'trace' => true,
                    'exceptions' => true));
            } else {
                // Creating new client without proxy
                $client = new SoapClient($wsdl,
                    array('trace' => true, 'exceptions' => true));
            }

            $OutputParametersOfRegister = $client->__call('Register',
                array("parameters" => $InputParametersOfRegister));

            // RegisterResult
            $RegisterResult = $OutputParametersOfRegister->RegisterResult;

            $terminal_parameters = "?merchantId=".$merchantId."&transactionId=".$RegisterResult->TransactionId;
            $process_parameters  = "?transactionId=".$RegisterResult->TransactionId;
            $transactionId       = $RegisterResult->TransactionId;
            $terminal_url        = $terminal.$terminal_parameters;

            // echo "<h3><font color='gray'>Output parameters:</font></h3>";
            //  echo "TransactionId= " . $RegisterResult->TransactionId . "<br/>";
            // $returnArray = array();
        } catch (SoapFault $fault) {
            // Printing errors from the communication
            //  echo "<h3><a href='index.php'>Test Webshops</a><h3>";
            // echo "<h3><font color='red'>EXCEPTION IN REGISTER CALL:</font></h3>";
            //echo "<pre>";  print_r($fault);   echo "</pre>";  exit;

            $returnArray = array('error' => 1);
            return $returnArray;

            // return $fault;
        }
        $returnArray = array(
            'result' => $RegisterResult,
            'merchantId' => $merchantId,
            'transactionId' => $transactionId,
            //'netsOrderNumber' => $transactionId,
            'terminal_url' => $terminal_url
        );
        return $returnArray;
        // exit;
####  END   REGISTER CALL  ####
    }

    public function netsReturn()
    {
        // echo "<pre>"; print_r($_REQUEST);  echo "</pre>"; exit;
        $responseCode  = FormUtil::getPassedValue('responseCode', null,
                'REQUEST');
        $transactionId = FormUtil::getPassedValue('transactionId', null,
                'REQUEST');
        $orderId       = FormUtil::getPassedValue('orderId', null, 'REQUEST');
        $cart_shop_id  = FormUtil::getPassedValue('cart_shop_id', null,
                'REQUEST');
        //echo "nets return url"; exit;
        // $pntables = pnDBGetTables();
        // $column = $pntables['zselex_order_column'];
        $where         = "WHERE order_id='".$orderId."'";
        $nets_where    = "WHERE zselex_order_id='".$orderId."' AND nets_transaction_id='".$transactionId."'";

        //echo $where;  exit;

        if ($responseCode == 'OK') {
            //echo $where;  exit;
            $userUpdateObj = array(
                'status' => 'success',
            );
            DBUtil::updateObject($userUpdateObj, 'zselex_order', $where);

            $userNets = array(
                'status' => 'success',
            );
            DBUtil::updateObject($userNets, 'zpayment_ZPayment', $nets_where);


            //  DBUTil::updateObject($obj, 'zselex_shop', $where);
            $user_id          = UserUtil::getVar('uid');
            $get_products     = ModUtil::apiFunc('ZSELEX', 'user', 'get',
                    $getargs          = array(
                    'table' => 'zselex_cart',
                    'where' => "user_id=$user_id",
                    //'fields' => array('id', 'quantity', 'availed')
            ));
            $content          = $get_products['cart_content'];
            $cart_unserialize = unserialize($content);
            unset($_SESSION['user_cart'][$cart_shop_id]);
            unset($cart_unserialize[$cart_shop_id]);
            $this->update_cart($cart_unserialize);
            unset($_SESSION['cart_menu']);
            // return $this->view->fetch('user/thankyou.tpl');
        } elseif ($responseCode == 'Cancel') {

            $userUpdateObj = array(
                'status' => 'canceled',
            );
            DBUtil::updateObject($userUpdateObj, 'zselex_order', $where);
            $userNets      = array(
                'status' => 'cancelled',
            );
            DBUtil::updateObject($userNets, 'zpayment_ZPayment', $nets_where);
            // return $this->view->fetch('user/ppcancelled.tpl');
        }
        //Payment failed
        else {
            // echo "Failed...."; exit;

            $userUpdateObj = array(
                'status' => 'failed',
            );
            DBUtil::updateObject($userUpdateObj, 'zselex_order', $where);
            $userNets      = array(
                'status' => 'failed',
            );
            DBUtil::updateObject($userNets, 'zpayment_ZPayment', $nets_where);
            //return $this->view->fetch('user/pperror.tpl');
            //Delete order information
            //Redirect to failed page
        }
        return $this->redirect(ModUtil::url('ZSELEX', 'user', 'paymentStatus',
                    array('order_id' => $orderId)));
    }

    function test2()
    {
        //echo "test2";  exit;
        $orderId         = 'ZS3872646';
        $getQuickPayInfo = $this->entityManager->getRepository('ZPayment_Entity_QuickPay')->get(array(
            'entity' => 'ZPayment_Entity_QuickPay',
            'fields' => array(
                'a.callback'
            ),
            'where' => array(
                'a.order_id' => $orderId
            )
        ));

        //  echo "<pre>"; print_r($getQuickPayInfo); echo "</pre>";  exit;

        $updCallbackArgs = array(
            'entity' => 'ZPayment_Entity_QuickPay',
            'fields' => array(
                'callback' => $getQuickPayInfo['callback'] + 1
            ),
            'where' => array(
                'a.order_id' => $orderId
            )
        );

        $updateCallBack = $this->entityManager->getRepository('ZPayment_Entity_QuickPay')->updateEntity($updCallbackArgs);

        // echo "End of script";  exit;
    }
}
// end class def