<?php

/**
 * Copyright socialise Team 2011
 *
 * This work is contributed to the Zikula Foundation under one or more
 * Contributor Agreements and licensed to You under the following license:
 *
 * @license GNU/LGPLv3 (or at your option, any later version).
 * @package socialise
 * @link http://code.zikula.org/socialise
 *
 * Please see the NOTICE file distributed with this source code for further
 * information regarding copyright and licensing.
 */

/**
 * Plugin api class.
 */
class Zvelo_Api_Plugin extends Zikula_AbstractApi {

    /**
     * Instance of Zikula_View.
     *
     * @var Zikula_View
     */
    protected $view;

    /**
     * Initialize.
     *
     * @return void
     */
    protected function initialize() {
        $this->setView();
    }

    /**
     * Set view property.
     *
     * @param Zikula_View $view Default null means new Render instance for this module name.
     *
     * @return Zikula_AbstractController
     */
    protected function setView(Zikula_View $view = null) {
        if (is_null($view)) {
            $view = Zikula_View::getInstance($this->getName());
        }

        $this->view = $view;
        return $this;
    }

    public function leftBlock($args) {

        //$this->view->assign("encode", $encode);
        //$current_theme = System::getVar('Default_Theme');
        $customerInfo = array();
        $wish = array();
        if (!empty($_SESSION['current_customer_id'])) {
            $customerInfo = $this->entityManager->getRepository('Zvelo_Entity_Customer')->getCustmerInfo(array('customer_id' => $_SESSION['current_customer_id']));
            $wish = $this->entityManager->getRepository('Zvelo_Entity_CustomerWish')->getWish(array('customer_id' => $_SESSION['current_customer_id']));
            $bicycleBlock = $this->entityManager->getRepository('Zvelo_Entity_Bicycle')->getBicycleDetailByCustomerId(array('customer_id' => $_SESSION['current_customer_id']));
        }
        // echo "<pre>";   print_r($bicycleBlock);   echo "</pre>"; 
        $this->view->assign('customerInfo', $customerInfo);
        $this->view->assign('wish', $wish);
        $this->view->assign('bicycleBlock', $bicycleBlock);
        return $this->view->fetch('plugin/leftblock.tpl');
    }

    public function rightBlock($args) {

        $customer_id = $_SESSION['current_customer_id'];
        $disable = '';
        $bt_class = "z-btgreen";
        if (!$customer_id) {
            $disable = "disabled";
            $bt_class = "z-btgrey";
        }
        $this->view->assign('customer_id', $customer_id);
        $this->view->assign('disable', $disable);
        $this->view->assign('bt_class', $bt_class);
        return $this->view->fetch('plugin/rightblock.tpl');
    }

    function bicycledetail($args) {
        $customer_id = $_SESSION['current_customer_id'];
        $bicycle = $this->entityManager->getRepository('Zvelo_Entity_Bicycle')->getBicycleDetailByCustomerId(array('customer_id' => $customer_id));
        $this->view->assign('bicycle', $bicycle);
        return $this->view->fetch('ajax/bicycledetail.tpl');
    }

}
