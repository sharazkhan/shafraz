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
 * Class to control Admin interface
 */
class ZPayment_Controller_Admin extends Zikula_AbstractController
{

    /**
     * the main administration function
     * This function is the default function, and is called whenever the
     * module is initiated without defining arguments.
     */
    public function main()
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZPayment::',
                '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());
        $this->redirect(ModUtil::url('ZPayment', 'admin', 'payments'));
    }

    /**
     * @desc set caching to false for all admin functions
     * @return      null
     */
    public function postInitialize()
    {
        $this->view->setCaching(false);
    }

    /**
     * @desc present administrator options to change module configuration
     * @return      config template
     */
    public function modifyconfig()
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZPayment::',
                '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());
        $modvars       = ModUtil::getVar('ZPayment');
        // echo "<pre>"; print_r($modvars);  echo "</pre>";
        $CardsAccepted = unserialize($modvars['CardsAccepted']);
        //echo "<pre>"; print_r($CardsAccepted);  echo "</pre>";
        $this->view->assign('CardsAccepted', $CardsAccepted);
        return $this->view->fetch('admin/modifyconfig.tpl');
    }

    /**
     * @desc sets module variables as requested by admin
     * @return      status/error ->back to modify config page
     */
    public function updateconfig()
    {
        $this->checkCsrfToken();

        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZPayment::',
                '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());

        // echo "<pre>"; print_r($_POST);  echo "</pre>"; exit;
        $modvars                              = array();
        //$modvars['showAdminZPayment'] = FormUtil::getPassedValue('showAdminZPayment', 0);
        $modvars['Netaxept_enabled_general']  = FormUtil::getPassedValue('Netaxept_enabled_general',
                0);
        $modvars['Paypal_enabled_general']    = FormUtil::getPassedValue('Paypal_enabled_general',
                0);
        $modvars['Directpay_enabled_general'] = FormUtil::getPassedValue('Directpay_enabled_general',
                0);
        $modvars['QuickPay_enabled_general']  = FormUtil::getPassedValue('QuickPay_enabled_general',
                0);
        $modvars['Epay_enabled_general']      = FormUtil::getPassedValue('Epay_enabled_general',
                0);

        $CardsAccepted            = FormUtil::getPassedValue('CardsAccepted',
                null, 'REQUEST');
        //echo "<pre>"; print_r($CreditCarsAccepted);  echo "</pre>"; exit;
        $modvars['CardsAccepted'] = serialize($CardsAccepted);

        // set the new variables
        $this->setVars($modvars);

        // clear the cache
        $this->view->clear_cache();

        LogUtil::registerStatus($this->__('Done! Updated the ZPayment configuration.'));
        return $this->modifyconfig();
    }

    /**
     * @desc present administrator information
     * @return      template
     */
    public function info()
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZPayment::',
                '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());

        return $this->view->fetch('admin/info.tpl');
    }

    public function payments()
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZPayment::',
                '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());
        $view          = Zikula_View::getInstance('ZPayment');
        $modvars       = ModUtil::getVar('ZPayment');
        // echo "<pre>";  print_r($modvars); echo "</pre>";
        $CardsAccepted = unserialize($modvars['CardsAccepted']);
        //echo "<pre>";  print_r($CardsAccepted); echo "</pre>";
        $payments      = array();
        // if ($modvars['Netaxept_enabled_general']) {
        $payments[]    = array('method' => $this->__("Netaxept"), 'edit_link' => ModUtil::url('ZPayment',
                'admin', 'editNetaxept'));
        // }
        // if ($modvars['Paypal_enabled_general']) {
        $payments[]    = array('method' => $this->__("Paypal"), 'edit_link' => ModUtil::url('ZPayment',
                'admin', 'editPaypal'));
        // }
        $payments[]    = array('method' => $this->__("QuickPay"), 'edit_link' => ModUtil::url('ZPayment',
                'admin', 'editQuickPay'));
        $payments[]    = array('method' => $this->__("ePay"), 'edit_link' => ModUtil::url('ZPayment',
                'admin', 'editEpay'));

        //echo "<pre>";  print_r($payments); echo "</pre>";
        $view->assign('CardsAccepted', $CardsAccepted);
        $view->assign('payments', $payments);

        return $view->fetch('admin/payments.tpl');
    }

    public function payments1($shop_id)
    {
        $view              = Zikula_View::getInstance('ZPayment');
        $modvars           = ModUtil::getVar('ZPayment');
        $serviceargs       = array(
            'shop_id' => $shop_id,
            'type' => 'paybutton',
            'disablecheck' => true
        );
        $servicePermission = ZSELEX_Controller_Admin::servicePermission($serviceargs);
        // echo "<pre>";  print_r($servicePermission); echo "</pre>";
        $perm              = $servicePermission['perm'];
        if ($perm < 1) {
            LogUtil::registerError($this->__($servicePermission['message']));
        }
        $payments = array();
        if ($modvars['Netaxept_enabled_general']) {
            $payments[] = array('method' => $this->__("Netaxept"), 'edit_link' => ModUtil::url('ZPayment',
                    'admin', 'editNetaxept1', array('shop_id' => $shop_id)));
        }
        if ($modvars['Paypal_enabled_general']) {
            $payments[] = array('method' => $this->__("Paypal"), 'edit_link' => ModUtil::url('ZPayment',
                    'admin', 'editPaypal1', array('shop_id' => $shop_id)));
        }
        if ($modvars['Directpay_enabled_general']) {
            $payments[] = array('method' => $this->__("Direct Payment"), 'edit_link' => ModUtil::url('ZPayment',
                    'admin', 'editDirectPay', array('shop_id' => $shop_id)));
        }
        //echo "<pre>";  print_r($payments); echo "</pre>";
        $view->assign('perm', $perm);
        $view->assign('payments', $payments);
        $view->assign('shop_id', $shop_id);

        return $view->fetch('admin/payments1.tpl');
    }

    public function editNetaxept()
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZPayment::',
                '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());
        return $this->view->fetch('admin/edit_netaxept.tpl');
    }

    public function updateNetaxept()
    {
        $this->checkCsrfToken();

        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZPayment::',
                '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());

        $modvars                              = array();
        $modvars['Netaxept_enabled']          = FormUtil::getPassedValue('Netaxept_enabled',
                0);
        $modvars['Netaxept_testmode']         = FormUtil::getPassedValue('Netaxept_testmode',
                0);
        $modvars['Netaxept_test_merchant_id'] = FormUtil::getPassedValue('Netaxept_test_merchant_id',
                null, 'REQUEST');
        $modvars['Netaxept_test_token']       = FormUtil::getPassedValue('Netaxept_test_token',
                null, 'REQUEST');

        $modvars['Netaxept_merchant_id'] = FormUtil::getPassedValue('Netaxept_merchant_id',
                null, 'REQUEST');
        $modvars['Netaxept_token']       = FormUtil::getPassedValue('Netaxept_token',
                null, 'REQUEST');

        // set the new variables
        $this->setVars($modvars);

        // clear the cache
        $this->view->clear_cache();

        LogUtil::registerStatus($this->__('Done! Updated the Netaxept configuration.'));
        //$this->redirect(ModUtil::url('ZPayment', 'admin', 'payments'));
        $this->redirect(ModUtil::url('ZSELEX', 'admin', 'paymentgatewaysettings'));
    }

    public function editPaypal()
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZPayment::',
                '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());
        return $this->view->fetch('admin/edit_paypal.tpl');
    }

    public function updatePaypal()
    {
        $this->checkCsrfToken();

        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZPayment::',
                '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());
        $formElement                      = FormUtil::getPassedValue('formElement',
                null, 'REQUEST');
        $modvars                          = array();
        $modvars['Paypal_enabled']        = $formElement['Paypal_enabled'];
        $modvars['Paypal_testmode']       = $formElement['Paypal_testmode'];
        $modvars['Paypal_business_email'] = $formElement['Paypal_business_email'];
        $modvars['Paypal_pdt']            = $formElement['Paypal_pdt'];


        // set the new variables
        $this->setVars($modvars);

        // clear the cache
        $this->view->clear_cache();

        LogUtil::registerStatus($this->__('Done! Updated the Paypal configuration.'));
        //$this->redirect(ModUtil::url('ZPayment', 'admin', 'payments'));
        $this->redirect(ModUtil::url('ZSELEX', 'admin', 'paymentgatewaysettings'));
    }

    public function editQuickPay()
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZPayment::',
                '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());
        return $this->view->fetch('admin/edit_quickpay.tpl');
    }

    public function updateQuickPay()
    {
        $this->checkCsrfToken();

        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZPayment::',
                '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());
        $formElement                      = FormUtil::getPassedValue('formElement',
                null, 'REQUEST');
        $modvars                          = array();
        $modvars['QuickPay_enabled']      = $formElement['QuickPay_enabled'];
        //  $modvars['QuickPay_testmode'] = $formElement['QuickPay_testmode'];
        //  $modvars['QuickPay_ID'] = $formElement['QuickPay_ID'];
        //  $modvars['QuickPay_md5'] = $formElement['QuickPay_md5'];
        $modvars['QuickPay_Merchant_ID']  = $formElement['QuickPay_Merchant_ID'];
        $modvars['QuickPay_Agreement_ID'] = $formElement['QuickPay_Agreement_ID'];
        $modvars['QuickPay_Api_Key']      = $formElement['QuickPay_Api_Key'];
        // set the new variables
        $this->setVars($modvars);

        // clear the cache
        $this->view->clear_cache();

        LogUtil::registerStatus($this->__('Done! Updated the QuickPay configuration.'));
        //$this->redirect(ModUtil::url('ZPayment', 'admin', 'payments'));
        $this->redirect(ModUtil::url('ZSELEX', 'admin', 'paymentgatewaysettings'));
    }

    public function editEpay()
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZPayment::',
                '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());
        return $this->view->fetch('admin/edit_epay.tpl');
    }

    public function updateEpay()
    {
        $this->checkCsrfToken();

        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZPayment::',
                '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());
        $formElement                          = FormUtil::getPassedValue('formElement',
                null, 'REQUEST');
        $modvars                              = array();
        $modvars['Epay_enabled']              = $formElement['Epay_enabled'];
        $modvars['Epay_testmode']             = $formElement['Epay_testmode'];
        $modvars['Epay_merchant_number']      = $formElement['Epay_merchant_number'];
        $modvars['Epay_test_merchant_number'] = $formElement['Epay_test_merchant_number'];
        $modvars['Epay_md5_hash']             = $formElement['Epay_md5_hash'];
        // set the new variables
        $this->setVars($modvars);

        // clear the cache
        $this->view->clear_cache();

        LogUtil::registerStatus($this->__('Done! Updated the Epay configuration.'));
        //$this->redirect(ModUtil::url('ZPayment', 'admin', 'payments'));
        $this->redirect(ModUtil::url('ZSELEX', 'admin', 'paymentgatewaysettings'));
    }

    public function editPaypal1()
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADD), LogUtil::getErrorMsgPermission());

        $shop_id = FormUtil::getPassedValue('shop_id', null, 'REQUEST');
        if (!(int) ($shop_id)) {
            return LogUtil::registerError($this->__f('Error! The ShopID in URL is invalid (%1$s). Please contact the administrator of this site and inform about this error!',
                        (int) ($shop_id)));
        }
        if (ZSELEX_Controller_Admin::shopPermission($shop_id) < 1) {
            return LogUtil::registerError($this->__('You do not have sufficient permissions'));
        }
        $modvars = ModUtil::getVar('ZPayment');
        if (!$modvars['Paypal_enabled_general']) {
            return LogUtil::registerError($this->__('This payment module is not available'));
        }
        $serviceargs       = array(
            'shop_id' => $shop_id,
            'type' => 'paybutton',
            'disablecheck' => true
        );
        $servicePermission = ZSELEX_Controller_Admin::servicePermission($serviceargs);
        // echo "<pre>";  print_r($servicePermission); echo "</pre>";
        $perm              = $servicePermission['perm'];
        if ($perm < 1) {
            return LogUtil::registerError($this->__($servicePermission['message']));
        }
        $paypal = $this->entityManager->getRepository('ZPayment_Entity_PaypalSetting')->getPaypal(array(
            'shop_id' => $shop_id));

        $this->view->assign('shop_id', $shop_id);
        $this->view->assign('paypal', $paypal);

        return $this->view->fetch('admin/edit_paypal1.tpl');
    }

    public function updatePaypal1()
    {
        $formElement       = FormUtil::getPassedValue('formElement', null,
                'REQUEST');
        //  echo "<pre>"; print_r($formElement); echo "</pre>"; exit;
        $serviceargs       = array(
            'shop_id' => $formElement['shop_id'],
            'type' => 'paybutton',
            'disablecheck' => true
        );
        $servicePermission = ZSELEX_Controller_Admin::servicePermission($serviceargs);
        // echo "<pre>";  print_r($servicePermission); echo "</pre>";
        $perm              = $servicePermission['perm'];
        if ($perm < 1) {
            return LogUtil::registerError($this->__($servicePermission['message']));
        }
        $count = $this->entityManager->getRepository('ZPayment_Entity_Paypal')->paypal_count(array(
            'shop_id' => $formElement['shop_id']));
        // echo "count :" . $count;  exit;

        if ($count < 1) { //INSERT
            $nets_entity = new ZPayment_Entity_PaypalSetting();
            $nets_entity->setShop_id($formElement['shop_id']);
            $nets_entity->setEnabled($formElement['Paypal_enabled']);
            $nets_entity->setTest_mode($formElement['Paypal_testmode']);
            $nets_entity->setBusiness_email($formElement['Paypal_business_email']);

            $this->entityManager->persist($nets_entity);
            $this->entityManager->flush();
            $InsertId = $nets_entity->getId();
            if ($InsertId > 0) {
                LogUtil::registerStatus($this->__('Done! Created Paypal configuration.'));
            }
        } else {
            $update = $this->entityManager->getRepository('ZPayment_Entity_Paypal')->updatePaypalSettings($formElement);
            LogUtil::registerStatus($this->__('Done! Updated the Paypal configuration.'));
        }
        $this->redirect(ModUtil::url('ZSELEX', 'admin', 'paymentgateway',
                array('shop_id' => $formElement['shop_id'])));
    }

    public function editNetaxept1()
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADD), LogUtil::getErrorMsgPermission());
        $shop_id = FormUtil::getPassedValue('shop_id', null, 'REQUEST');
        if (!(int) ($shop_id)) {
            return LogUtil::registerError($this->__f('Error! The ShopID in URL is invalid (%1$s). Please contact the administrator of this site and inform about this error!',
                        (int) ($shop_id)));
        }
        if (ZSELEX_Controller_Admin::shopPermission($shop_id) < 1) {
            return LogUtil::registerError($this->__('You do not have sufficient permissions'));
        }

        $modvars = ModUtil::getVar('ZPayment');
        if (!$modvars['Netaxept_enabled_general']) {
            return LogUtil::registerError($this->__('This payment module is not available'));
        }

        $serviceargs       = array(
            'shop_id' => $shop_id,
            'type' => 'paybutton',
            'disablecheck' => true
        );
        $servicePermission = ZSELEX_Controller_Admin::servicePermission($serviceargs);
        // echo "<pre>";  print_r($servicePermission); echo "</pre>";
        $perm              = $servicePermission['perm'];
        if ($perm < 1) {
            return LogUtil::registerError($this->__($servicePermission['message']));
        }

        $netaxept = $this->entityManager->getRepository('ZPayment_Entity_Netaxept')->getNetaxept(array(
            'shop_id' => $shop_id));
        // echo "<pre>"; print_r($netaxept); echo "</pre>"; 
        // $count = $this->entityManager->getRepository('ZPayment_Entity_Netaxept')->counts(array('shop_id' => $shop_id));
        // echo "count :" . $count;
        $this->view->assign('shop_id', $shop_id);
        $this->view->assign('netaxept', $netaxept);

        return $this->view->fetch('admin/edit_netaxept1.tpl');
    }

    public function updateNetaxept1()
    {
        $formElement = FormUtil::getPassedValue('formElement', null, 'REQUEST');
        //  echo "<pre>"; print_r($formElement); echo "</pre>"; exit;

        $serviceargs       = array(
            'shop_id' => $formElement['shop_id'],
            'type' => 'paybutton',
            'disablecheck' => true
        );
        $servicePermission = ZSELEX_Controller_Admin::servicePermission($serviceargs);
        // echo "<pre>";  print_r($servicePermission); echo "</pre>";
        $perm              = $servicePermission['perm'];
        if ($perm < 1) {
            return LogUtil::registerError($this->__($servicePermission['message']));
        }
        $count = $this->entityManager->getRepository('ZPayment_Entity_Netaxept')->counts(array(
            'shop_id' => $formElement['shop_id']));
        // echo "count :" . $count;  exit;

        if ($count < 1) { //INSERT
            $nets_entity = new ZPayment_Entity_NetaxeptSetting();
            $nets_entity->setShop_id($formElement['shop_id']);
            $nets_entity->setEnabled($formElement['Netaxept_enabled']);
            $nets_entity->setTest_mode($formElement['Netaxept_testmode']);
            $nets_entity->setMerchant_id($formElement['Netaxept_merchant_id']);
            $nets_entity->setToken($formElement['Netaxept_token']);
            $this->entityManager->persist($nets_entity);
            $this->entityManager->flush();
            $InsertId    = $nets_entity->getId();
            if ($InsertId > 0) {
                LogUtil::registerStatus($this->__('Done! Created Netaxept configuration.'));
            }
        } else {
            $update = $this->entityManager->getRepository('ZPayment_Entity_Netaxept')->updateNetaxeptSettings($formElement);
            LogUtil::registerStatus($this->__('Done! Updated Netaxept configuration.'));
        }
        $this->redirect(ModUtil::url('ZSELEX', 'admin', 'paymentgateway',
                array('shop_id' => $formElement['shop_id'])));
    }

    public function editDirectPay()
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADD), LogUtil::getErrorMsgPermission());

        $shop_id = FormUtil::getPassedValue('shop_id', null, 'REQUEST');
        if (!(int) ($shop_id)) {
            return LogUtil::registerError($this->__f('Error! The ShopID in URL is invalid (%1$s). Please contact the administrator of this site and inform about this error!',
                        (int) ($shop_id)));
        }
        if (ZSELEX_Controller_Admin::shopPermission($shop_id) < 1) {
            return LogUtil::registerError($this->__('You do not have sufficient permissions'));
        }

        $modvars = ModUtil::getVar('ZPayment');
        if (!$modvars['Directpay_enabled_general']) {
            return LogUtil::registerError($this->__('This payment module is not available'));
        }

        $serviceargs       = array(
            'shop_id' => $shop_id,
            'type' => 'paybutton',
            'disablecheck' => true
        );
        $servicePermission = ZSELEX_Controller_Admin::servicePermission($serviceargs);
        // echo "<pre>";  print_r($servicePermission); echo "</pre>";
        $perm              = $servicePermission['perm'];
        if ($perm < 1) {
            return LogUtil::registerError($this->__($servicePermission['message']));
        }

        $directpay = $this->entityManager->getRepository('ZPayment_Entity_DirectpaySetting')->getDirectpay(array(
            'shop_id' => $shop_id));
        //echo "<pre>"; print_r($netaxept); echo "</pre>"; 
        // $count = $this->entityManager->getRepository('ZPayment_Entity_Netaxept')->counts(array('shop_id' => $shop_id));
        // echo "count :" . $count;
        $this->view->assign('shop_id', $shop_id);
        $this->view->assign('directpay', $directpay);


        return $this->view->fetch('admin/edit_directpay1.tpl');
    }

    public function updateDirectPay()
    {
        $formElement = FormUtil::getPassedValue('formElement', null, 'REQUEST');
        // echo "<pre>"; print_r($formElement); echo "</pre>"; exit;

        $serviceargs       = array(
            'shop_id' => $formElement['shop_id'],
            'type' => 'paybutton',
            'disablecheck' => true
        );
        $servicePermission = ZSELEX_Controller_Admin::servicePermission($serviceargs);
        // echo "<pre>";  print_r($servicePermission); echo "</pre>";
        $perm              = $servicePermission['perm'];
        if ($perm < 1) {
            return LogUtil::registerError($this->__($servicePermission['message']));
        }
        $count = $this->entityManager->getRepository('ZPayment_Entity_DirectpaySetting')->directpay_count(array(
            'shop_id' => $formElement['shop_id']));
        // echo "count :" . $count;  exit;

        if ($count < 1) { //INSERT
            $directpay_entity = new ZPayment_Entity_DirectpaySetting();
            $directpay_entity->setShop_id($formElement['shop_id']);
            $directpay_entity->setEnabled($formElement['Directpay_enabled']);
            $directpay_entity->setInfo($formElement['Directpay_info']);

            $this->entityManager->persist($directpay_entity);
            $this->entityManager->flush();
            $InsertId = $directpay_entity->getId();
            if ($InsertId > 0) {
                LogUtil::registerStatus($this->__('Done! Created Directpay configuration.'));
            }
        } else {
            $update = $this->entityManager->getRepository('ZPayment_Entity_DirectpaySetting')->updateDirectpaySettings($formElement);
            LogUtil::registerStatus($this->__('Done! Updated Directpay configuration.'));
        }
        $this->redirect(ModUtil::url('ZSELEX', 'admin', 'paymentgateway',
                array('shop_id' => $formElement['shop_id'])));
    }
}
// end class def