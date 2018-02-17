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
class ZSELEX_Block_Employees extends Zikula_Controller_AbstractBlock
{

    /**
     * initialise block
     */
    public function init()
    {
        SecurityUtil::registerPermissionSchema('ZSELEX:Employees:',
            'Block title::');
    }

    /**
     * get information on block
     */
    public function info()
    {
        return array(
            'text_type' => 'Employees',
            'module' => 'ZSELEX',
            'text_type_long' => $this->__('Employees'),
            'allow_multiple' => true,
            'form_content' => false,
            'form_refresh' => false,
            'show_preview' => true,
            'admin_tableless' => true
        );
    }

    /**
     * display block
     */
    public function display($blockinfo)
    {
        if (!SecurityUtil::checkPermission('ZSELEX:Banner:',
                "$blockinfo[title]::", ACCESS_OVERVIEW)) {
            return;
        }
        if (!ModUtil::available('ZSELEX')) {
            return;
        }
        $vars = BlockUtil::varsFromContent($blockinfo ['content']);

        $shop_id = FormUtil::getPassedValue('shop_id',
                isset($args ['shop_id']) ? $args ['shop_id'] : null, 'REQUEST');
        if (empty($shop_id)) {
            return;
        }

        $serviceExist = ModUtil::apiFunc('ZSELEX', 'admin', 'serviceExistBlock',
                $args         = array(
                'shop_id' => $shop_id,
                'type' => 'employees'
        ));

        // echo $serviceExist;
        if ($serviceExist < 1) {
            return;
        }
        /*
         * $getEmployees = ModUtil::apiFunc('ZSELEX', 'user', 'getAll', $args = array(
         * 'table' => 'zselex_shop_employees',
         * 'where' => "shop_id=$shop_id",
         * 'itemsperpage' => 4
         * ));
         */
        $repo = $this->entityManager->getRepository('ZSELEX_Entity_Employee');


        $serviceEmployee = $repo->get(array(
            'entity' => 'ZSELEX_Entity_ServiceShop',
            'where' => array(
                'a.shop' => $shop_id,
                'a.type' => 'employees'
            ),
            'fields' => array(
                'a.id',
                'a.quantity',
                'a.availed'
            )
        ));

        $totalEmployees = $repo->getCount(null, 'ZSELEX_Entity_Employee',
            'emp_id', array(
            'a.shop' => $shop_id
        ));


        $employeeLimit = $serviceEmployee ['quantity'] - $totalEmployees;
        $limit         = 0;
        if ($employeeLimit < $totalEmployees) {
            $limit = $serviceEmployee ['quantity'];
        }

       // echo "limit :".$limit;

        $empArgs = array(
            'entity' => 'ZSELEX_Entity_Employee',
            'where' => array(
                'a.shop' => $shop_id
            ),
            'orderby' => 'a.sort_order ASC',
            //'offset' => 4
        );
        if ($limit > 0) {
            $empArgs ['offset'] = $limit;
        }

        $getEmployees = $repo->getAll($empArgs);
        $ownerName    = ModUtil::apiFunc('ZSELEX', 'admin', 'getOwner',
                $args         = array(
                'shop_id' => $shop_id
        ));

        $loguser = UserUtil::getVar('uid');
        $loguser = !empty($loguser) ? $loguser : 0;
        $user_id = $loguser;
        $perm    = ModUtil::apiFunc('ZSELEX', 'admin', 'shopPermission',
                array(
                'shop_id' => $shop_id,
                'user_id' => $user_id
        ));

        $getEmployeesFinal = array_chunk($getEmployees, 2);
        if (empty($getEmployeesFinal)) {
            return;
        }
        // echo "<pre>"; print_r($getEmployees); echo "</pre>";
        // echo $shopconfig['dbname'];
        $this->view->assign('vars', $vars);
        $this->view->assign('perm', $perm);
        $this->view->assign('ownerName', $ownerName);
        $this->view->assign('employees', $getEmployeesFinal);

        $current_theme = System::getVar('Default_Theme');
        $this->view->assign('current_theme', $current_theme);

        $blockinfo ['content'] = $this->view->fetch('blocks/employees/employees.tpl');

        return BlockUtil::themeBlock($blockinfo);
    }

    /**
     * modify block settings .
     * .
     */
    public function modify($blockinfo)
    {
        $vars = BlockUtil::varsFromContent($blockinfo ['content']);

        // $shops = ModUtil::apiFunc('ZSELEX', 'admin', 'getShop', $items);

        $shops = ModUtil::apiFunc('ZSELEX', 'user', 'selectArray',
                $args  = array(
                'table' => 'zselex_shop s , zselex_minishop m',
                'where' => array(
                    "s.shop_id=m.shop_id",
                    "m.shoptype='zSHOP'"
                )
        ));
        // echo "<pre>"; print_r($shops); echo "</pre>";
        $this->view->assign('vars', $vars);
        $this->view->assign('zshops', $shops);

        return $this->view->fetch('blocks/employees/employees_modify.tpl');
    }

    /**
     * update block settings
     */
    public function update($blockinfo)
    {
        $vars = BlockUtil::varsFromContent($blockinfo ['content']);

        // alter the corresponding variable

        $vars ['shop']    = FormUtil::getPassedValue('shop', '', 'POST');
        $vars ['amount']  = FormUtil::getPassedValue('amount', '', 'POST');
        $vars ['orderby'] = FormUtil::getPassedValue('orderby', '', 'POST');

        // write back the new contents
        $blockinfo ['content'] = BlockUtil::varsToContent($vars);

        // clear the block cache
        $this->view->clear_cache('blocks/employees/employees.tpl');

        return $blockinfo;
    }
}
// end class def