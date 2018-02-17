<?php

use Doctrine\DBAL\LockMode;
use Doctrine\ORM\OptimisticLockException;

/**
 * Zvelo
 */

/**
 * This is the User controller class providing navigation and interaction functionality.
 */
class Zvelo_Controller_User extends Zikula_AbstractController {

    public function postInitialize() {
        // echo "first here"; exit;
        // PageUtil::addVar('stylesheet', 'modules/Zvelo/style/styles.css');
        PageUtil::addVar('javascript', 'modules/Zvelo/javascript/zvelo.js');
        PageUtil::addVar('javascript', 'modules/Zvelo/javascript/scripts/jquery-1.8.2.min.js');
        PageUtil::addVar('javascript', 'modules/Zvelo/javascript/scripts/jquery.mockjax.js');
        PageUtil::addVar('javascript', 'modules/Zvelo/javascript/scripts/jquery.autocomplete.js');
        if ($_POST) {
            $formElement = FormUtil::getPassedValue('formElement', isset($args['formElement']) ? $args['formElement'] : null, 'POST');
            //echo "<pre>";   print_r($formElement);     echo "</pre>";    exit;
            if ($formElement['type'] == 'reload') {
                unset($_SESSION['current_customer_id']);
                //return $this->redirect();
                return $this->redirect($this->redirect(ModUtil::url('Zvelo', 'user')));
            }
        }
    }

    /**
     * This method is the default function.
     *
     * @param array $args Array.
     *
     * @return redirect
     */
    public function main($args) {
        //echo "main"; exit;
        //$this->redirect(ModUtil::url('Zvelo', 'user', 'measurement', $args));
        return $this->measurement($args);
    }

    /**
     * This method provides a generic item list overview.
     *
     * @param array $args Array.
     *
     * @return string|boolean Output.
     */
    public function view($args) {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Zvelo::', '::', ACCESS_OVERVIEW), LogUtil::getErrorMsgPermission());


        return $this->view->assign('tags', $tagsByPopularity)
                        ->fetch('user/view.tpl');
    }

    function moduleVersion() {
        $modname = 'Zvelo';
        $modid = ModUtil::getIdFromName($modname);
        $modinfo = ModUtil::getInfo($modid);
        //echo "<pre>"; print_r($modinfo);  echo "</pre>";
        return $modinfo['version'];
    }

    public function deleteUser($args) {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Zvelo::', '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());

        try {
            $customerId = FormUtil::getPassedValue('customer_id', 0, 'REQUEST');
            //echo $customerId; exit;

            $Delete = $this->entityManager->getRepository('Zvelo_Entity_Customer')->deleteUser(array('customer_id' => $customerId));
            if ($Delete) {
                unset($_SESSION['current_customer_id']);
                LogUtil::registerStatus($this->__('Done! Customer deleted'));
            } else {
                LogUtil::registerError($this->__('Could not delete.Api failed'));
            }
        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage();
            die();
        }
        return $this->redirect($this->redirect(ModUtil::url('Zvelo', 'user')));
    }

    public function measurement($args) {
        //$this->view->assign('nobg', 1);
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Zvelo::', '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());
        // unset($_SESSION['current_customer_id']);
        //echo "hello world!";  exit;
        // $this->moduleInfo();
        $users = $this->entityManager->getRepository('Zvelo_Entity_Customer')->searchUsers('');
        // echo "<pre>";   print_r($users);  echo "</pre>"; 
        if ($_POST) {
            // echo "<pre>"; print_r($_POST);  echo "</pre>"; exit;
            // LogUtil::registerStatus($this->__('Done! Updated the Tag configuration.'));
            // LogUtil::registerError($this->__('Done! Updated the Tag configuration.'));
            $formElement = FormUtil::getPassedValue('formElement', isset($args['formElement']) ? $args['formElement'] : null, 'POST');
            //echo "<pre>";   print_r($formElement);  echo "</pre>";  exit;

            if ($_SESSION['current_customer_id'] < 1) {
                // echo "<pre>";   print_r($formElement);     echo "</pre>";    exit;
                $customerId = $this->entityManager->getRepository('Zvelo_Entity_Customer')->createCustomer($formElement);
                if ($customerId == true) {
                    $_SESSION['current_customer_id'] = $customerId;
                    //$_SESSION['current_customer_id'] = $customerId;
                    LogUtil::registerStatus($this->__('Done! Created record'));
                }

                $msrmrntId = $this->entityManager->getRepository('Zvelo_Entity_CustomerMeasurement')->createCustomerMeasurement($formElement, $customerId);
                if ($formElement['type'] == 'next') {
                    return $this->redirect(ModUtil::url('Zvelo', 'user', 'clientinfo'));
                }
                //$_SESSION['record'][] = $formElement;
            } else {
                // echo "<pre>";   print_r($formElement);     echo "</pre>";    exit;
                $msrmrntId = $this->entityManager->getRepository('Zvelo_Entity_CustomerMeasurement')->updateMeasurement($formElement, $_SESSION['current_customer_id']);

                if ($formElement['type'] == 'next') {
                    return $this->redirect(ModUtil::url('Zvelo', 'user', 'clientinfo'));
                }
            }
            // return $this->redirect(ModUtil::url('Zvelo', 'user', 'clientinfo'));
        }

        if ($_SESSION['current_customer_id']) {
            $msrmrnt = $this->entityManager->getRepository('Zvelo_Entity_CustomerMeasurement')->getMeasurementInfo(array('customer_id' => $_SESSION['current_customer_id']));
            //echo "<pre>";   print_r($msrmrnt);     echo "</pre>";    exit;
        }

        return $this->view->assign('msrmrnt', $msrmrnt)
                        ->assign('nobg', 1)
                        ->fetch('user/measurement.tpl');
    }

    public function clientinfo($args) {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Zvelo::', '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());

        $customer_id = $_SESSION['current_customer_id'];
        if ($_POST) {
            $formElement = FormUtil::getPassedValue('formElement', isset($args['formElement']) ? $args['formElement'] : null, 'POST');
            // echo "<pre>";   print_r($formElement);  echo "</pre>";   exit;


            if ($formElement['type'] == 'prev') {
                $update = $this->entityManager->getRepository('Zvelo_Entity_Customer')->updateCustomerInfo($formElement, $customer_id);
                return $this->redirect($formElement['murl']);
            } elseif ($formElement['type'] == 'next') {
                $update = $this->entityManager->getRepository('Zvelo_Entity_Customer')->updateCustomerInfo($formElement, $customer_id);
                return $this->redirect(ModUtil::url('Zvelo', 'user', 'bicycle'));
            } else {
                $update = $this->entityManager->getRepository('Zvelo_Entity_Customer')->updateCustomerInfo($formElement, $customer_id);
            }
        }
        $customerInfo = $this->entityManager->getRepository('Zvelo_Entity_Customer')->getCustmerInfo(array('customer_id' => $_SESSION['current_customer_id']));
        $msrmrnt = $this->entityManager->getRepository('Zvelo_Entity_CustomerMeasurement')->getMeasurementInfo(array('customer_id' => $_SESSION['current_customer_id']));
        // echo "<pre>"; print_r($customerInfo);  echo "</pre>"; exit;
        return $this->view->assign('customerInfo', $customerInfo)
                        ->assign('msrmrnt', $msrmrnt)
                        ->assign('nobg', 1)
                        ->fetch('user/clientinfo.tpl');
    }

    public function loadUsers($args) {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Zvelo::', '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());
        if ($_POST) {
            $customer_id = FormUtil::getPassedValue('customer', null, 'POST');
            // echo $customer_id; exit;
            $_SESSION['current_customer_id'] = $customer_id;
            // return $this->redirect();
            return $this->redirect($this->redirect(ModUtil::url('Zvelo', 'user')));
        }
        $customerInfo = array();
        $customers = $this->entityManager->getRepository('Zvelo_Entity_Customer')->getAllUsers();
        //echo "<pre>"; print_r($customers);  echo "</pre>"; exit;
        return $this->view->assign('customers', $customers)
                        ->fetch('user/load_users.tpl');
    }

    public function bicycle($args) {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Zvelo::', '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());
        //  echo "hellooo"; exit;
        // echo "<pre>";   print_r($bicyleArr);  echo "</pre>";
        $customer_id = $_SESSION['current_customer_id'];
        if ($_POST) {
            // echo "hellooo"; exit;
            $formElement = FormUtil::getPassedValue('formElement', isset($args['formElement']) ? $args['formElement'] : null, 'POST');
            //echo "<pre>";   print_r($formElement);   echo "</pre>";  exit;
            $count = $this->entityManager->getRepository('Zvelo_Entity_CustomerWish')->count($customer_id);

            if (!$count) {
                $create = $this->entityManager->getRepository('Zvelo_Entity_Bicycle')->createBicycleInWish($formElement, $customer_id);
            } else {
                $update = $this->entityManager->getRepository('Zvelo_Entity_Bicycle')->updateBicycle($formElement, $customer_id);
            }
            if ($formElement['type'] == 'prev') {
                return $this->redirect(ModUtil::url('Zvelo', 'user', 'clientinfo'));
            } elseif ($formElement['type'] == 'next') {
                return $this->redirect(ModUtil::url('Zvelo', 'user', 'seatposition'));
            }
        }

        $bicycleSelected = $this->entityManager->getRepository('Zvelo_Entity_Bicycle')->getBicycleDetailByCustomerId(array('customer_id' => $customer_id));

        $bicycles = $this->entityManager->getRepository('Zvelo_Entity_Bicycle')->getBicycles();
        //echo "<pre>";   print_r($bicycles);  echo "</pre>";
        return $this->view->assign('bicycles', $bicycles)
                        ->assign('bicycleSelected', $bicycleSelected)
                        ->fetch('user/bicycle.tpl');
    }

    public function seatposition($args) {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Zvelo::', '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());
        $customer_id = $_SESSION['current_customer_id'];
        if ($_POST) {
            $formElement = FormUtil::getPassedValue('formElement', isset($args['formElement']) ? $args['formElement'] : null, 'POST');
            //echo "<pre>"; print_r($formElement);  echo "</pre>"; exit;
            $count = $this->entityManager->getRepository('Zvelo_Entity_CustomerWish')->count($customer_id);
            if (!$count) {
                $create = $this->entityManager->getRepository('Zvelo_Entity_CustomerWish')->createSeatPosition($formElement, $customer_id);
            } else {
                $update = $this->entityManager->getRepository('Zvelo_Entity_CustomerWish')->updateSeatPostion($formElement, $customer_id);
            }
            if ($formElement['type'] == 'prev') {
                return $this->redirect(ModUtil::url('Zvelo', 'user', 'bicycle'));
            } elseif ($formElement['type'] == 'next') {
                return $this->redirect(ModUtil::url('Zvelo', 'user', 'wishes'));
            }
        }
        if (!empty($customer_id)) {
            $seatposition = $this->entityManager->getRepository('Zvelo_Entity_CustomerWish')->getSeatPosition(array('customer_id' => $customer_id));

            // echo "<pre>"; print_r($seatposition);  echo "</pre>"; 
        }
        return $this->view->assign('seatposition', $seatposition)
                        ->fetch('user/seat_position.tpl');
    }

    public function wishes($args) {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Zvelo::', '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());
        $customer_id = $_SESSION['current_customer_id'];
        $wish = array();
        if ($_POST) {
            $formElement = FormUtil::getPassedValue('formElement', isset($args['formElement']) ? $args['formElement'] : null, 'POST');
            //echo "<pre>"; print_r($formElement);  echo "</pre>"; exit;
            if ($customer_id > 0) {
                $count = $this->entityManager->getRepository('Zvelo_Entity_CustomerWish')->count($customer_id);
                if (!$count) {
                    $create = $this->entityManager->getRepository('Zvelo_Entity_CustomerWish')->createWish($formElement, $customer_id);
                } else {
                    $update = $this->entityManager->getRepository('Zvelo_Entity_CustomerWish')->updateWish($formElement, $customer_id);
                }
            } else {
                $create = $this->entityManager->getRepository('Zvelo_Entity_CustomerWish')->createWish($formElement, $customer_id);
            }
            if ($formElement['type'] == 'prev') {
                return $this->redirect(ModUtil::url('Zvelo', 'user', 'seatposition'));
            } elseif ($formElement['type'] == 'next') {
                return $this->redirect(ModUtil::url('Zvelo', 'user', 'values'));
            }
        }

        if (!empty($customer_id)) {
            $wish = $this->entityManager->getRepository('Zvelo_Entity_CustomerWish')->getWish(array('customer_id' => $customer_id));
        }
        return $this->view->assign('wish', $wish)
                        ->fetch('user/wishes.tpl');
    }

    public function values($args) {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Zvelo::', '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());
        $customer_id = $_SESSION['current_customer_id'];
        if ($_POST) {

            $formElement = FormUtil::getPassedValue('formElement', isset($args['formElement']) ? $args['formElement'] : null, 'POST');
            // echo "<pre>"; print_r($formElement);  echo "</pre>"; exit;
            if ($customer_id > 0) {
                $count = $this->entityManager->getRepository('Zvelo_Entity_CustomerErgonomicValue')->count($customer_id);
                // echo "count : ". $count; exit;
                if (!$count) {
                    $create = $this->entityManager->getRepository('Zvelo_Entity_CustomerErgonomicValue')->createErgonomicValues($formElement, $customer_id);
                } else {
                    $update = $this->entityManager->getRepository('Zvelo_Entity_CustomerErgonomicValue')->updateErgonomicValues($formElement, $customer_id);
                }
            } else {
                $create = $this->entityManager->getRepository('Zvelo_Entity_CustomerErgonomicValue')->createErgonomicValues($formElement, $customer_id);
            }
            if ($formElement['type'] == 'prev') {
                return $this->redirect(ModUtil::url('Zvelo', 'user', 'wishes'));
            }
        }
        $values = array();
        // echo $customer_id; exit;
        if (!empty($customer_id)) {
            $values = $this->entityManager->getRepository('Zvelo_Entity_CustomerErgonomicValue')->getErgonomicValues(array('customer_id' => $customer_id));
        }
        // echo "<pre>"; print_r($values);  echo "</pre>"; exit;
        return $this->view->assign('values', $values)
                        ->fetch('user/values.tpl');
    }

    function getUsers() {

        $value = $_REQUEST['query'];
        $users = $this->entityManager->getRepository('Zvelo_Entity_Customer')->searchUsers($value);
        $data = json_encode($users);
        $final_str = '{"query":"Unit","suggestions":' . $data . '}';
        echo $final_str;
        exit;
        // Zvelo_Util::ajaxOutput($final_str);
    }

}
