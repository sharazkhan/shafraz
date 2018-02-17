<?php

/**
 * Zvelo
 */

/**
 * Access to actions initiated through AJAX for the Tag module.
 */
class Zvelo_Controller_Ajax extends Zikula_Controller_AbstractAjax {

    public function getBicycleDetail() {
        //die();
        $view = Zikula_View::getInstance($this->name);
        //echo "sharaz"; exit;
        $id = $_REQUEST['bicycle_id'];
        // echo "ID : " . $id; exit;
        $bicycle = $this->entityManager->getRepository('Zvelo_Entity_Bicycle')->getBicycleDetail(array('bicycle_id' => $id));
        // echo "<pre>";   print_r($bicycle);   echo "</pre>";  exit;
        $view->assign('bicycle', $bicycle);
        $data = '';
        $output_tpl = $view->fetch('ajax/bicycledetail.tpl');
        $data .= new Zikula_Response_Ajax_Plain($output_tpl);
        $outputArr['data'] = $data;
        AjaxUtil::output($outputArr);
        //return $data;
    }

    function getUsers() {
        $view = Zikula_View::getInstance($this->name);
        $value = $_REQUEST['query'];
        $users = $this->entityManager->getRepository('Zvelo_Entity_Customer')->searchUsers($value);

        $data = json_encode($users);

        $final_str = '{"query":"Unit","suggestions":' . $data . '}';
        Zvelo_Util::ajaxOutput($final_str);
        // return $final_str;
        // echo "<pre>";   print_r($users);   echo "</pre>"; 
        // $view->assign('users', $users);
        //  $output = $view->fetch('ajax/users.tpl');
        //return new Zikula_Response_Ajax_Plain($output);
    }

}
