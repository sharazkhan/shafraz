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
 * Class to control User interface (Netaxept)//
 */
class ZPayment_Api_Netaxept extends Zikula_AbstractApi
{
    /**
     * Get available user links
     *
     * @return array array of admin links
     */
    public $merchantId;
    public $token;
    public $test_mode;
    public $wsdl;
    public $terminal;

    public function initialize()
    {
        //echo "come here"; exit;

        $modvars         = ModUtil::getVar('ZPayment');
        $this->test_mode = $modvars['Netaxept_testmode'];
        if ($this->test_mode) {
            $this->merchantId = $modvars['Netaxept_test_merchant_id'];
            $this->token      = $modvars['Netaxept_test_token'];
        } else {
            $this->merchantId = $modvars['Netaxept_merchant_id'];
            $this->token      = $modvars['Netaxept_token'];
        }


        if ($this->test_mode) {
            $this->wsdl     = "https://epayment-test.bbs.no/Netaxept.svc?wsdl";
            $this->terminal = "https://epayment-test.bbs.no/terminal/default.aspx";
        } else {
            $this->wsdl     = "https://epayment.bbs.no/Netaxept.svc?wsdl";
            $this->terminal = "https://epayment.bbs.no/terminal/default.aspx";
        }
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


        $order_type       = $args['order_type'];
        $zselex_order_id  = $args['order_id'];
        $cart_shop_id     = $args['shop_id'];
        $shop_id          = $cart_shop_id;
        $shop_info        = $this->entityManager->getRepository('ZSELEX_Entity_Shop')
            ->get(array(
            'entity' => 'ZSELEX_Entity_Shop',
            'fields' => array('a.shop_name'),
            'where' => array('a.shop_id' => $shop_id)
        ));
        //echo "<pre>";  print_r($shop_info);  echo "</pre>";   exit;
        $shop_name        = $shop_info['shop_name'];
        $netaxept_info    = $this->entityManager->getRepository('ZPayment_Entity_Netaxept')->getNetaxept(array(
            'shop_id' => $shop_id));
        // echo "<pre>";  print_r($netaxept_info);  echo "</pre>";   exit;
        $shop_merchant_id = $netaxept_info['merchant_id'];
        $shop_token       = $netaxept_info['token'];
        $test_mode        = $netaxept_info['test_mode'];
        if ($test_mode) {
            $shop_merchant_id = $netaxept_info['test_merchant_id'];
            $shop_token       = $netaxept_info['test_token'];
            $wsdl             = "https://epayment-test.bbs.no/Netaxept.svc?wsdl";
            $terminal         = "https://epayment-test.bbs.no/terminal/default.aspx";
        } else {
            $wsdl     = "https://epayment.bbs.no/Netaxept.svc?wsdl";
            //$terminal = "https://epayment.bbs.no/terminal/default.aspx";
            $terminal = "https://epayment.bbs.no/Terminal/default.aspx";
        }

        //echo "merchantID : " . $shop_merchant_id . '<br>';
        //echo "token : " . $shop_token . '<br>';
        //echo "wsdl : " . $wsdl . '<br>';
        //exit;
        //$redirect_url = pnGetBaseURL() . ModUtil::url('ZSELEX', 'user', 'netsReturn', array('orderId' => $zselex_order_id, 'cart_shop_id' => $cart_shop_id));
        // $redirect_url = "http://z13x.acta-it.dk/minishop/newishop";
        $redirect_url = $netaxept_info['return_url'];
        if (empty($netaxept_info['return_url'])) {
            $redirect_url = pnGetBaseURL().ModUtil::url('ZSELEX', 'user',
                    'shop', array('shop_id' => $shop_id));
        }

        //  $redirect_url = $redirect_url.'?gateway=nets';
        $query = parse_url($redirect_url, PHP_URL_QUERY);

// Returns a string if the URL has parameters or NULL if not
        if ($query) {
            $redirect_url .= '&gateway=nets';
        } else {
            $redirect_url .= '?gateway=nets';
        }
        // echo $redirect_url;  exit;
        ####  PARAMETERS IN ORDER  ####
        // $amount = (int) $args['grand_total']; // The amount described as the lowest monetary unit, example: 100,00 NOK is noted as "10000", 9.99 USD is noted as "999".
        // $amount = (float) $args['grand_total'];
        //$amount = floatval($args['grand_total']);
        // $amount = (int) ($args['grand_total_all'] * 100);
        $amount = (int) ($args['final_price'] * 100);
        //echo "Amount :" . $amount;exit;


        $thislang                = ZLanguage::getLanguageCode();
        $currencyCode            = "DKK";  //The currency code, following ISO 4217. Typical examples include "DKK" and "USD".
        $force3DSecure           = null;   // Optional parameter
        $orderNumber             = md5(uniqid('', true));
        $UpdateStoredPaymentInfo = null;

####  PARAMETERS IN ENVIRONMENT  ####
        $Language           = null; // Optional parameter
        $OS                 = null; // Optional parameter
        $WebServicePlatform = 'PHP5'; // Required (for Web Services)
####  PARAMETERS IN TERMINAL  ####
        $autoAuth           = null; // Optional parameter
        $paymentMethodList  = null; // Optional parameter
        // $language = "en_GB"; // Optional parameter
        if ($thislang == 'en') {
            $language = "en_GB";
        } elseif ($thislang == 'da') {
            $language = "da_DK"; //
        }
        //$orderDescription = "Order Description"; // Optional parameter
        $orderDescription    = $this->__("Your order")." #$zselex_order_id ".$this->__("from")." ".$shop_name;
        //echo $orderDescription; exit;
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

        $customerAddress1                  = "";    // Optional parameter (required if DnBNorDirectPayment)
        $customerAddress2                  = "";                         // Optional parameter
        $customerCompanyName               = null;                       // Optional parameter
        $customerCompanyRegistrationNumber = null;                       // Optional parameter
        $customerCountry                   = "NO";                       // Optional parameter
        $customerFirstName                 = "";                   // Optional parameter (required if DnBNorDirectPayment)
        $customerLastName                  = "";                 // Optional parameter (required if DnBNorDirectPayment)
        $customerNumber                    = UserUtil::getVar('uid');                  // Optional parameter
        $customerEmail                     = "";   // Optional parameter
        $customerPhoneNumber               = "";               // Optional parameter
        $customerPostcode                  = "";                     // Optional parameter (required if DnBNorDirectPayment)
        $customerSocialSecurityNumber      = "";              // Optional parameter
        $customerTown                      = "";                     // Optional parameter (required if DnBNorDirectPayment)



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
            "token" => $shop_token,
            "merchantId" => $shop_merchant_id,
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

            $terminal_parameters = "?merchantId=".$shop_merchant_id."&transactionId=".$RegisterResult->TransactionId;
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
            $ownerInfo = ModUtil::apiFunc('ZSELEX', 'admin', 'getOwnerInfo',
                    $args      = array(
                    'shop_id' => $shop_id
            ));
            $emails[]  = 'kim@acta-it.dk';
            $emails[]  = 'sharazkhanz@gmail.com';
            $emails[]  = $ownerInfo['email'];
            $message   = $this->__("Registration Failed for")." ".$this->__("Shop ID")." : ".$shop_id.'<br>';
            $message .= $this->__("Server")." :".$_SERVER['SERVER_NAME'].'<br>';
            $message .= $this->__("Date")." :".date('Y-m-d h:i:s a', time()).'<br>';
            $message .= $this->__("Module").': ZPayment<br>';

            foreach ($emails as $email) {
                $mailer_args = array(
                    'toaddress' => $email,
                    'fromname' => 'ZSELEX',
                    'subject' => 'Netaxept Error',
                    'body' => $message,
                    'html' => true
                );

                $sent = ModUtil::apiFunc('Mailer', 'user', 'sendMessage',
                        $mailer_args);
            }

            $returnArray = array('error' => 1);
            return $returnArray;

            // return $fault;
        }
        $returnArray = array(
            'result' => $RegisterResult,
            'merchantId' => $shop_merchant_id,
            'transactionId' => $transactionId,
            //'netsOrderNumber' => $transactionId,
            'terminal_url' => $terminal_url
        );
        return $returnArray;
        // exit;
####  END   REGISTER CALL  ####
    }

    function query_call($args)
    {
        // include(DIR_FS_CATALOG . DIR_WS_MODULES . 'payment/netaxept/phpBBS/Parameters.php');
        $transactionId = $args['transaction_id'];
        $shop_id       = $args['shop_id'];
        $type          = $args['type'];

        if ($type != 'service') {
            $netaxept_info    = $this->entityManager->getRepository('ZPayment_Entity_Netaxept')->getNetaxept(array(
                'shop_id' => $shop_id));
            $shop_merchant_id = $netaxept_info['merchant_id'];
            $shop_token       = $netaxept_info['token'];
            $test_mode        = $netaxept_info['test_mode'];
            if ($test_mode) {
                $shop_merchant_id = $netaxept_info['test_merchant_id'];
                $shop_token       = $netaxept_info['test_token'];
            }
        } else {
            $shop_merchant_id = $this->merchantId;
            $shop_token       = $this->token;
            $test_mode        = $this->test_mode;
        }
        if ($test_mode) {
            $wsdl     = "https://epayment-test.bbs.no/Netaxept.svc?wsdl";
            $terminal = "https://epayment-test.bbs.no/terminal/default.aspx";
        } else {
            $wsdl     = "https://epayment.bbs.no/Netaxept.svc?wsdl";
            //$terminal = "https://epayment.bbs.no/terminal/default.aspx";
            $terminal = "https://epayment.bbs.no/Terminal/default.aspx";
        }

        require_once ('modules/ZPayment/lib/Netaxept/ClassQueryRequest.php');
        $returnArray = array();

        ####  QUERY OBJECT  ####
        $QueryRequest = new QueryRequest(
            $transactionId
        );

####  ARRAY WITH QUERY PARAMETERS  ####
        $InputParametersOfQuery = array
            (
            "token" => $shop_token,
            "merchantId" => $shop_merchant_id,
            "request" => $QueryRequest
        );

        ####  START QUERY CALL  ####
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

            $OutputParametersOfQuery = $client->__call('Query',
                array("parameters" => $InputParametersOfQuery));
            $QueryResult             = $OutputParametersOfQuery->QueryResult;

            /*  echo "<h3><font color='gray'>Output parameters:</font></h3>";
              echo "<pre>";
              //  print_r((array) $OutputParametersOfQuery);
              print_r((array) $QueryResult);
              echo "</pre>";

              echo "<h3><font color='green'>Query call successfully done.</font></h3>";
              echo "<h3><a href='index.php'>Test Webshops</a><h3>"; */
            $returnArray = array('error' => 0, 'result' => $QueryResult);
        } // End try
        catch (SoapFault $fault) {
            ## Do some error handling in here...
            /*  echo "<h3><a href='index.php'>Test Webshops</a><h3>";

              echo "<br/><font color='red'>EXCEPTION!";
              echo "<br/><br/><h3><font color='red'>Query call failed</font></h3>";
              echo "<pre>";   print_r($fault);   echo "</pre>"; */
            //$responseCode = $fault['Error']->ResponseCode;
            $faults      = (array) $fault;
            //  echo "<pre>";   print_r($faults);   echo "</pre>"; exit;
            $returnArray = array('error' => 1, 'result' => $faults);
        } // End catch
####  END   QUERY CALL  ####

        return $returnArray;
    }

    function auth_call($args)
    {
        // include(DIR_FS_CATALOG . DIR_WS_MODULES . 'payment/netaxept/phpBBS/Parameters.php');
        $transactionId = $args['transaction_id'];
        $shop_id       = $args['shop_id'];
        $type          = $args['type'];

        /* $netaxept_info = $this->entityManager->getRepository('ZPayment_Entity_Netaxept')->getNetaxept(array('shop_id' => $shop_id));
          $shop_merchant_id = $netaxept_info['merchant_id'];
          $shop_token = $netaxept_info['token'];
          $test_mode = $netaxept_info['test_mode']; */
        // $netaxept_info = $this->entityManager->getRepository('ZPayment_Entity_Netaxept')->getNetaxept(array('shop_id' => $shop_id));
        if ($type != 'service') {
            $netaxept_info    = $this->entityManager->getRepository('ZPayment_Entity_Netaxept')->getNetaxept(array(
                'shop_id' => $shop_id));
            $shop_merchant_id = $netaxept_info['merchant_id'];
            $shop_token       = $netaxept_info['token'];
            $test_mode        = $netaxept_info['test_mode'];
            if ($test_mode) {
                $shop_merchant_id = $netaxept_info['test_merchant_id'];
                $shop_token       = $netaxept_info['test_token'];
            }
        } else {
            $shop_merchant_id = $this->merchantId;
            $shop_token       = $this->token;
            $test_mode        = $this->test_mode;
        }
        if ($test_mode) {
            $wsdl     = "https://epayment-test.bbs.no/Netaxept.svc?wsdl";
            $terminal = "https://epayment-test.bbs.no/terminal/default.aspx";
            //$shop_merchant_id = $netaxept_info['test_merchant_id'];
            //$shop_token = $netaxept_info['test_token'];
        } else {
            $wsdl     = "https://epayment.bbs.no/Netaxept.svc?wsdl";
            //$terminal = "https://epayment.bbs.no/terminal/default.aspx";
            $terminal = "https://epayment.bbs.no/Terminal/default.aspx"; // live url here!
        }

        require_once ('modules/ZPayment/lib/Netaxept/ClassProcessRequest.php');

        $returnArray = array();

        ####  PROCESS OBJECT  ####
        $description         = "description of AUTH operation";
        $operation           = "AUTH";
        $transactionAmount   = "";
        $transactionReconRef = "";

        ####  PROCESS OBJECT  ####
        $ProcessRequest = new ProcessRequest(
            $description, $operation, $transactionAmount, $transactionId,
            $transactionReconRef
        );

        ####  ARRAY WITH PROCESS PARAMETERS  ####
        $InputParametersOfProcess = array
            (
            "token" => $shop_token,
            "merchantId" => $shop_merchant_id,
            "request" => $ProcessRequest
        );

        ####  START PROCESS CALL  ####
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

            $OutputParametersOfProcess = $client->__call('Process',
                array("parameters" => $InputParametersOfProcess));
            $ProcessResult             = $OutputParametersOfProcess->ProcessResult;

            $returnArray = array('error' => 0, 'result' => $ProcessResult);
        } // End try
        catch (SoapFault $fault) {
            ## Do some error handling in here...
            //$responseCode = $fault['Error']->ResponseCode;
            $faults      = (array) $fault;
            $returnArray = array('error' => 1, 'result' => $faults);
        } // End catch
        ####  END   PROCESS CALL  ####

        return $returnArray;
    }

    public function registerServiceTransaction($args)
    {
        // echo "<pre>";  print_r($args);  echo "</pre>";   exit;
        //echo "comes here nets"; exit;
        // require_once 'modules/ZPayment/lib/ZPayment/Parameters.php';
        $returnArray = array();
        $orderInfo   = $args['order_info'];
        // echo "<pre>";  print_r($args);  echo "</pre>";   exit;
        require_once ('modules/ZPayment/lib/Netaxept/ClassRegisterRequest.php');
        require_once ('modules/ZPayment/lib/Netaxept/ClassCustomer.php');
        require_once ('modules/ZPayment/lib/Netaxept/ClassTerminal.php');
        require_once ('modules/ZPayment/lib/Netaxept/ClassItem.php');
        require_once ('modules/ZPayment/lib/Netaxept/ClassArrayOfItem.php');
        require_once ('modules/ZPayment/lib/Netaxept/ClassOrder.php');
        require_once ('modules/ZPayment/lib/Netaxept/ClassEnvironment.php');



        $service_order_id = $orderInfo['order_id'];

        $redirect_url = pnGetBaseURL().ModUtil::url('ZSELEX', 'admin',
                'netaxeptReturn', array('orderId' => $service_order_id));



        ####  PARAMETERS IN ORDER  ####
        // $amount = (int) $args['grand_total']; // The amount described as the lowest monetary unit, example: 100,00 NOK is noted as "10000", 9.99 USD is noted as "999".
        // $amount = (float) $args['grand_total'];
        //$amount = floatval($args['grand_total']);
        $amount = (int) ($orderInfo['grand_total'] * 100);
        //echo "Amount :" . $amount;exit;



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

        $customerAddress1                  = "";    // Optional parameter (required if DnBNorDirectPayment)
        $customerAddress2                  = "";                         // Optional parameter
        $customerCompanyName               = null;                       // Optional parameter
        $customerCompanyRegistrationNumber = null;                       // Optional parameter
        $customerCountry                   = "NO";                       // Optional parameter
        $customerFirstName                 = "";                   // Optional parameter (required if DnBNorDirectPayment)
        $customerLastName                  = "";                 // Optional parameter (required if DnBNorDirectPayment)
        $customerNumber                    = UserUtil::getVar('uid');                  // Optional parameter
        $customerEmail                     = "";   // Optional parameter
        $customerPhoneNumber               = "";               // Optional parameter
        $customerPostcode                  = "";                     // Optional parameter (required if DnBNorDirectPayment)
        $customerSocialSecurityNumber      = "18106500157";              // Optional parameter
        $customerTown                      = "";                     // Optional parameter (required if DnBNorDirectPayment)



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
        $Order->OrderNumber             = $service_order_id;
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
            "token" => $this->token,
            "merchantId" => $this->merchantId,
            "request" => $RegisterRequest
        );

        ####  START REGISTER CALL  ####
        try {
            if (strpos($_SERVER["HTTP_HOST"], 'uapp') > 0) {
                // Creating new client having proxy
                $client = new SoapClient($this->wsdl,
                    array('proxy_host' => "isa4", 'proxy_port' => 8080, 'trace' => true,
                    'exceptions' => true));
            } else {
                // Creating new client without proxy
                $client = new SoapClient($this->wsdl,
                    array('trace' => true, 'exceptions' => true));
            }

            $OutputParametersOfRegister = $client->__call('Register',
                array("parameters" => $InputParametersOfRegister));

            // RegisterResult
            $RegisterResult = $OutputParametersOfRegister->RegisterResult;

            $terminal_parameters = "?merchantId=".$this->merchantId."&transactionId=".$RegisterResult->TransactionId;
            $process_parameters  = "?transactionId=".$RegisterResult->TransactionId;
            $transactionId       = $RegisterResult->TransactionId;
            $terminal_url        = $this->terminal.$terminal_parameters;

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
            'merchantId' => $this->merchantId,
            'transactionId' => $transactionId,
            //'netsOrderNumber' => $transactionId,
            'terminal_url' => $terminal_url
        );
        return $returnArray;
        // exit;
####  END   REGISTER CALL  ####
    }

    function getShopId($args)
    {
        $transId   = $args['transaction_id'];
        $statement = Doctrine_Manager::getInstance()->connection();
        $sql       = "SELECT a.shop_id , a.order_id , a.completed
                FROM zselex_order a , zpayment_netaxept b
                WHERE b.nets_transaction_id='".$transId."' AND a.order_id=b.zselex_order_id";
        $query     = $statement->execute($sql);
        $result    = $query->fetch();
        return $result;
    }
}
// end class def