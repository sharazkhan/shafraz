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
class ZPayment_Api_QuickPay extends Zikula_AbstractApi
{

    public function initialize()
    {
        
    }

    function QuickPayCallback()
    {
        // $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_COMMENT), LogUtil::getErrorMsgPermission());
        mail('sharaz.khan@r2international.com', 'testing', $message = 'works');
        try {
            $order_id       = $_REQUEST['ordernumber'];
            $state          = $_REQUEST['state'];
            $qpstatmsg      = $_REQUEST['qpstatmsg'];
            $transaction_id = $_REQUEST['transaction'];
            $shop_id        = $_REQUEST['CUSTOM_shop_id'];
            $user_id        = UserUtil::getVar('uid');
            if ($state || $state == 1 || $state == '1') {
                $payment_status   = "Success";
                $user_id          = UserUtil::getVar('uid');
                $get_products     = ModUtil::apiFunc('ZSELEX', 'user', 'get',
                        $getargs          = array(
                        'table' => 'zselex_cart',
                        'where' => "user_id=$user_id",
                        //'fields' => array('id', 'quantity', 'availed')
                ));
                $content          = $get_products['cart_content'];
                $cart_unserialize = unserialize($content);
                unset($_SESSION['user_cart'][$shop_id]);
                unset($cart_unserialize[$shop_id]);
                $this->update_cart($cart_unserialize);
                unset($_SESSION['cart_menu']);
                $updateOrder      = array(
                    'table' => 'zselex_order',
                    'IdValue' => $orderno,
                    'fields' => array('status' => $payment_status),
                    'idName' => 'id',
                    'where' => "order_id='".$order_id."'",
                );

                $updateOrderId   = ModUtil::apiFunc('ZSELEX', 'user',
                        'updateObject', $updateOrder);
                $update_quickpay = $this->entityManager->getRepository('ZPayment_Entity_QuickPay')->updateQuickPayPayment($status
                    = $payment_status, $order_id, $transaction_id,
                    $info            = $qpstatmsg);


                // return $this->view->fetch('user/thankyou.tpl');
            } else {
                $payment_status = "Failed";
                $updateOrder    = array(
                    'table' => 'zselex_order',
                    'IdValue' => $orderno,
                    'fields' => array('status' => $payment_status),
                    'idName' => 'id',
                    'where' => "order_id='".$order_id."'",
                );

                $updateOrderId   = ModUtil::apiFunc('ZSELEX', 'user',
                        'updateObject', $updateOrder);
                $update_quickpay = $this->entityManager->getRepository('ZPayment_Entity_QuickPay')->updateQuickPayPayment($status
                    = $payment_status, $order_id, $transaction_id,
                    $info            = $qpstatmsg);
                // return $this->view->fetch('user/pperror.tpl');
            }
            mail('sharaz.khan@r2international.com', 'testing',
                $message = 'works1');
            return true;
        } catch (Exception $e) {

            $dt = date('Y-m-d H:i:s', time());
            error_log("[$dt] QuickPay Error : ".$e->getMessage()."\n", 3,
                "modules/ZSELEX/errorlog/error.log");
        }
        //return $this->redirect(ModUtil::url('ZSELEX', 'user', 'paymentStatus', array('order_id' => $order_id)));
    }

    /**
     * Send email notification to user , owner and super admin,
     * about the failure
     *
     * @param type $args
     * @return void
     */
    function sendErrorNotification($args)
    {

        $errorMsg = '';


        $requestArray = $args['request_array'];
        $urlValues    = $args['url_values'];

        $orderId = $requestArray ['order_id'];
        $shopId  = $requestArray['variables']['CUSTOM_shop_id'];
        // $userEmail  = UserUtil::getVar('email');


        $getQuickPayInfo = $this->entityManager->getRepository('ZPayment_Entity_QuickPay')->get(array(
            'entity' => 'ZPayment_Entity_QuickPay',
            'fields' => array(
                'a.callback'
            ),
            'where' => array(
                'a.order_id' => $orderId
            )
        ));


        $success = 0;
        if ($requestArray['operations'][0]['qp_status_code'] == '20000') {
            $payment_status = "Success";
            $subject        = $this->__("Order Success");
            $success        = 1;
            if (!$getQuickPayInfo['callback']) {
                ModUtil::apiFunc('ZSELEX', 'cart', 'updateProductOptions',
                    array(
                    'order_id' => $orderId
                ));
            }
        } else {
            $payment_status = "Failed";
            $subject        = $this->__("Order Failed");
            $success        = 0;
        }

        //if (!$success) {
        $adminMailArgs = array('order_id' => $orderId, 'shop_id' => $shopId,
            'gateway' => 'quickpay', 'request_array' => $requestArray, 'success' => $success);
        $this->sendOrderMailToAdmin($adminMailArgs);
        // }


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
    }
    /*
      function sendErrorNotification($args)
      {

      $errorMsg = '';


      $requestArray = $args['request_array'];
      $urlValues    = $args['url_values'];

      $shopId     = $requestArray['variables']['CUSTOM_shop_id'];
      // $userEmail  = UserUtil::getVar('email');
      $ownerInfo  = ModUtil::apiFunc('ZSELEX', 'admin', 'getOwnerInfo',
      array(
      'shop_id' => $shopId
      ));
      $ownerEmail = $ownerInfo ['email'];

      if ($requestArray['operations'][0]['qp_status_code'] == '20000') {
      $payment_status = "Success";
      $subject        = $this->__("Order Success");
      } else {
      $payment_status = "Failed";
      $subject        = $this->__("Order Failed");
      }


      $orderInfoArgs = array(
      'fields' => array(
      'a.id',
      'a.status',
      'a.user_id',
      'b.shop_id',
      'a.email',
      'a.totalprice',
      'a.order_id',
      'a.vat',
      'a.shipping',
      'a.self_pickup'
      ),
      'where' => array(
      'a.order_id' => $requestArray ['order_id']
      )
      );
      $orderInfo     = $this->entityManager->getRepository('ZSELEX_Entity_Order')->getOrderInfo($orderInfoArgs);

      $currArgs  = array(
      'amount' => $orderInfo['totalprice'],
      'currency_symbol' => '',
      'decimal_point' => ',',
      'thousands_sep' => '.',
      'precision' => '2'
      );
      $cartTotal = ModUtil::apiFunc('ZSELEX', 'user', 'number2currency',
      $currArgs);


      $vatArgs = array(
      'amount' => $orderInfo['vat'],
      'currency_symbol' => '',
      'decimal_point' => ',',
      'thousands_sep' => '.',
      'precision' => '2'
      );
      $vat     = ModUtil::apiFunc('ZSELEX', 'user', 'number2currency',
      $vatArgs);

      if ($orderInfo['self_pickup']) {
      $orderInfo['shipping'] = 0;
      }

      $shpArgs  = array(
      'amount' => $orderInfo['shipping'],
      'currency_symbol' => '',
      'decimal_point' => ',',
      'thousands_sep' => '.',
      'precision' => '2'
      );
      $shipping = ModUtil::apiFunc('ZSELEX', 'user', 'number2currency',
      $shpArgs);


      $errorMsg .= $this->__("Order ID")." :".$requestArray ['order_id'].'<br>';
      $errorMsg .= $this->__("Total")." :".$cartTotal.' DKK<br>';
      $errorMsg .= $this->__("Vat")." :".$vat.' DKK<br>';
      $errorMsg .= $this->__("Shipping")." :".$shipping.' DKK<br>';
      $errorMsg .= $this->__("Site")." :".$_SERVER ['SERVER_NAME'].'<br>';
      $errorMsg .= $this->__("Date")." :".date('Y-m-d h:i:s a', time()).'<br>';
      $errorMsg .= $this->__("Shop Name")." : ".$urlValues['shopName'].'<br>';
      $errorMsg .= $this->__("Gateway").': Quickpay<br>';
      $errorMsg .= $this->__("Status").': '.$payment_status.'<br>';
      $errorMsg .= $this->__("Error Message").' : '.$requestArray['operations'][0]['qp_status_msg'].'<br>';
      $errorMsg .= $this->__("Error Code").' : '.$requestArray['operations'][0]['qp_status_code'].'<br>';
      // $errorMsg .= $this->__("User Name").' : '.UserUtil::getVar('uname').'<br>';
      $errorMsg .= $this->__("User Email").' : '.$orderInfo['email'].'<br><br>';
      $errorMsg .= '<br>';
      $errorMsg .= $this->appendProductDetails($requestArray ['order_id']);
      $errorMsg .= '<br>';

      $errorMsg .= $this->__("Full Dump").' : <pre>'.json_encode($requestArray).'</pre><br>';

      $emails[] = "sharazkhanz@gmail.com";
      if ($payment_status == 'Failed') {
      $emails[] = $ownerEmail;
      }
      $emails[] = "ken@citypilot.dk";


      foreach ($emails as $email) {
      $maileArgs = array(
      'toaddress' => $email,
      'fromname' => 'ZSELEX',
      'subject' => $this->__($subject),
      'body' => $errorMsg,
      'html' => true
      );

      $sent = ModUtil::apiFunc('Mailer', 'user', 'sendMessage', $maileArgs);
      }
      }
     */

    /**
     * Send mail on order initiation
     * 
     * @param array $args
     * @return void
     */
    function sendInitialNotification($args)
    {

        $errorMsg = '';

        $shopId     = $args['shop_id'];
        $orderId    = $args['order_id'];
        // $userEmail  = UserUtil::getVar('email');
        $ownerInfo  = ModUtil::apiFunc('ZSELEX', 'admin', 'getOwnerInfo',
                array(
                'shop_id' => $shopId
        ));
        $ownerEmail = $ownerInfo ['email'];

        $paymentStatus = "Initiated";


        $getArgs  = array(
            'entity' => 'ZSELEX_Entity_Shop',
            'fields' => array(
                'a.shop_name'
            ),
            'where' => array(
                'a.shop_id' => $shopId
            )
        );
        $getShop  = $this->entityManager->getRepository('ZSELEX_Entity_Order')->get($getArgs);
        $shopName = $getShop ['shop_name'];


        $currArgs  = array(
            'amount' => $args['totalprice'],
            'currency_symbol' => '',
            'decimal_point' => ',',
            'thousands_sep' => '.',
            'precision' => '2'
        );
        $cartTotal = ModUtil::apiFunc('ZSELEX', 'user', 'number2currency',
                $currArgs);


        $vatArgs = array(
            'amount' => $args['vat'],
            'currency_symbol' => '',
            'decimal_point' => ',',
            'thousands_sep' => '.',
            'precision' => '2'
        );
        $vat     = ModUtil::apiFunc('ZSELEX', 'user', 'number2currency',
                $vatArgs);

        if ($args['self_pickup']) {
            $args['shipping'] = 0;
        }

        $shpArgs  = array(
            'amount' => $args['shipping'],
            'currency_symbol' => '',
            'decimal_point' => ',',
            'thousands_sep' => '.',
            'precision' => '2'
        );
        $shipping = ModUtil::apiFunc('ZSELEX', 'user', 'number2currency',
                $shpArgs);

        $errorMsg .= $this->__("Order ID")." :".$orderId.'<br>';
        $errorMsg .= $this->__("Total")." :".$cartTotal.' DKK<br>';
        $errorMsg .= $this->__("Vat")." :".$vat.' DKK<br>';
        $errorMsg .= $this->__("Shipping")." :".$shipping.' DKK<br>';
        $errorMsg .= $this->__("Site")." :".$_SERVER ['SERVER_NAME'].'<br>';
        $errorMsg .= $this->__("Date")." :".date('Y-m-d h:i:s a', time()).'<br>';
        $errorMsg .= $this->__("Shop Name")." : ".$shopName.'<br>';
        $errorMsg .= $this->__("Owner Name")." : ".$ownerInfo ['uname'].'<br>';
        $errorMsg .= $this->__("Owner Email")." : ".$ownerEmail.'<br>';
        $errorMsg .= $this->__("Gateway").': Quickpay<br>';
        $errorMsg .= $this->__("Status").': '.$paymentStatus.'<br>';



        //$errorMsg .= $this->__("User Name").' : '.UserUtil::getVar('uname').'<br>';
        $errorMsg .= $this->__("Customer Email").' : '.$args['email'].'<br>';

        $errorMsg .= '<br>';
        $errorMsg .= $this->appendProductDetails($orderId);

        $emails[] = "sharazkhanz@gmail.com";

        $emails[] = "ken@citypilot.dk";


        foreach ($emails as $email) {
            $maileArgs = array(
                'toaddress' => $email,
                'fromname' => 'ZSELEX',
                'subject' => $this->__('Order Initiated'),
                'body' => $errorMsg,
                'html' => true
            );

            $sent = ModUtil::apiFunc('Mailer', 'user', 'sendMessage', $maileArgs);
        }
    }

    /**
     * append product details to the mail
     * 
     * @param type $orderId
     */
    function appendProductDetails($orderId)
    {

        $repo       = $this->entityManager->getRepository('ZSELEX_Entity_Product');
        $errorMsg   = '';
        $orderItems = ModUtil::apiFunc('ZSELEX', 'user', 'getOrderDetails',
                array(
                'order_id' => $orderId
        ));

        foreach ($orderItems as $item) {

            $errorMsg .= '<br>';
            $errorMsg .= $this->__("Product ID").': '.$item['product_id'].'<br>';
            $errorMsg .= $this->__("Product Name").': '.$item['product_name'].'<br>';
            $productOptions = unserialize($item ['product_options']);

            if (!empty($productOptions)) {

                foreach ($productOptions as $val) {
                    $getName = $repo->get(array(
                        'entity' => 'ZSELEX_Entity_ProductToOption',
                        'fields' => array(
                            'b.option_name',
                            'b.option_type'
                        ),
                        'where' => array(
                            "a.product_to_options_id" => $val['prdToOptionID']
                        ),
                        'joins' => array(
                            'LEFT JOIN a.option b'
                        )
                    ));

                    $getValue = $repo->get(array(
                        'entity' => 'ZSELEX_Entity_ProductToOptionValue',
                        'fields' => array(
                            'b.option_value',
                            'a.price',
                            'a.parent_option_value_id',
                            'a.price_prefix',
                            'a.qty'
                        ),
                        'where' => array(
                            "a.product_to_options_value_id" => $val ['valueID']
                        ),
                        'joins' => array(
                            'LEFT JOIN a.option_value_id b'
                        )
                        )
                        // 'groupby' => 'b.option_value_id'
                    );
                    // echo "<pre>"; print_r($getValue); echo "</pre>";
                    $errorMsg .= $getName['option_name'].' : '.$getValue['option_value'].'<br>';
                    $errorMsg .= 'Quantity Left'.' : '.$getValue['qty'];
                }
            } else {

                $errorMsg .= $this->__("Quantity Left").': '.$item['prd_quantity'].'<br>';
            }
            $errorMsg .= '<br>';
        }

        return $errorMsg;
    }

    function sendOrderMailToAdmin($args)
    {

        $orderId      = $args['order_id'];
        $shopId       = $args['shop_id'];
        $status       = $args['status'];
        $gateway      = $args['gateway'];
        $requestArray = $args['request_array'];
        $success      = $args['success'];

        $ownerInfo  = ModUtil::apiFunc('ZSELEX', 'admin', 'getOwnerInfo',
                array(
                'shop_id' => $shopId
        ));
        $ownerEmail = $ownerInfo ['email'];

        if ($success) {
            $payment_status = "Success";
            $subject        = $this->__("Order Success");
        } else {
            $payment_status = "Failed";
            $subject        = $this->__("Order Failed");
        }



        $orderInfoArgs = array(
            'fields' => array(
                'a.id',
                'a.status',
                'a.user_id',
                'b.shop_id',
                'a.email',
                'a.totalprice',
                'a.order_id',
                'a.vat',
                'a.shipping',
                'a.self_pickup',
                'b.shop_name'
            ),
            'where' => array(
                'a.order_id' => $orderId
            )
        );
        $orderInfo     = $this->entityManager->getRepository('ZSELEX_Entity_Order')->getOrderInfo($orderInfoArgs);

        $currArgs  = array(
            'amount' => $orderInfo['totalprice'],
            'currency_symbol' => '',
            'decimal_point' => ',',
            'thousands_sep' => '.',
            'precision' => '2'
        );
        $cartTotal = ModUtil::apiFunc('ZSELEX', 'user', 'number2currency',
                $currArgs);


        $vatArgs = array(
            'amount' => $orderInfo['vat'],
            'currency_symbol' => '',
            'decimal_point' => ',',
            'thousands_sep' => '.',
            'precision' => '2'
        );
        $vat     = ModUtil::apiFunc('ZSELEX', 'user', 'number2currency',
                $vatArgs);

        if ($orderInfo['self_pickup']) {
            $orderInfo['shipping'] = 0;
        }

        $shpArgs  = array(
            'amount' => $orderInfo['shipping'],
            'currency_symbol' => '',
            'decimal_point' => ',',
            'thousands_sep' => '.',
            'precision' => '2'
        );
        $shipping = ModUtil::apiFunc('ZSELEX', 'user', 'number2currency',
                $shpArgs);


        $errorMsg .= $this->__("Order ID")." :".$orderId.'<br>';
        $errorMsg .= $this->__("Total")." :".$cartTotal.' DKK<br>';
        $errorMsg .= $this->__("Vat")." :".$vat.' DKK<br>';
        $errorMsg .= $this->__("Shipping")." :".$shipping.' DKK<br>';
        $errorMsg .= $this->__("Site")." :".$_SERVER ['SERVER_NAME'].'<br>';
        $errorMsg .= $this->__("Date")." :".date('Y-m-d h:i:s a', time()).'<br>';
        $errorMsg .= $this->__("Shop Name")." : ".$orderInfo['shop_name'].'<br>';
        $errorMsg .= $this->__("Gateway").': Quickpay<br>';
        $errorMsg .= $this->__("Status").': '.$payment_status.'<br>';

        if ($gateway == 'quickpay') {
            $errorMsg .= $this->__("Error Message").' : '.$requestArray['operations'][0]['qp_status_msg'].'<br>';
            $errorMsg .= $this->__("Error Code").' : '.$requestArray['operations'][0]['qp_status_code'].'<br>';
            $errorMsg .= $this->__("User Email").' : '.$orderInfo['email'].'<br><br>';
            $errorMsg .= '<br>';
        }

        $errorMsg .= $this->appendProductDetails($orderId);
        $errorMsg .= '<br>';

        $errorMsg .= $this->__("Full Dump").' : <pre>'.json_encode($requestArray).'</pre><br>';

        $emails[] = "sharazkhanz@gmail.com";
        if ($payment_status == 'Failed') {
            $emails[] = $ownerEmail;
        }
        $emails[] = "ken@citypilot.dk";


        foreach ($emails as $email) {
            $maileArgs = array(
                'toaddress' => $email,
                'fromname' => 'ZSELEX',
                'subject' => $this->__($subject),
                'body' => $errorMsg,
                'html' => true
            );

            $sent = ModUtil::apiFunc('Mailer', 'user', 'sendMessage', $maileArgs);
        }
    }
}
// end class def