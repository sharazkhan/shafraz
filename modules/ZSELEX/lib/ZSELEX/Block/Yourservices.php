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
class ZSELEX_Block_Yourservices extends Zikula_Controller_AbstractBlock
{

    /**
     * initialise block
     *
     * @return void
     */
    public function init()
    {
        SecurityUtil::registerPermissionSchema('ZSELEX:Yourservices:',
            'Block title::');
    }

    /**
     * get information on block
     *
     * @return array
     */
    public function info()
    {
        return array(
            'text_type' => 'hello',
            'module' => 'ZSELEX',
            'text_type_long' => $this->__('Your Services Block'),
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
        if (!SecurityUtil::checkPermission('ZSELEX:Yourservices:',
                "$blockinfo[title]::", ACCESS_EDIT)) {
            return;
        }
        if (!ModUtil::available('ZSELEX')) {
            return;
        }
        // return;
        $repo    = $this->entityManager->getRepository('ZSELEX_Entity_Bundle');
        $shop_id = $_REQUEST ['shop_id'];

        $valid = ZSELEX_Util::shopPermission($shop_id);
        if (!$valid) {
            return;
        }
        $vars = BlockUtil::varsFromContent($blockinfo ['content']);



        $services = $repo->getAll(array(
            'entity' => 'ZSELEX_Entity_ServiceShop',
            'joins' => array(
                'LEFT JOIN a.plugin b',
                'JOIN a.bundle c'
            ),
            'fields' => array(
                'a.quantity',
                'b.plugin_name as service_name',
                'c.bundle_id',
                'a.timer_date',
                'a.top_bundle',
                'a.timer_days',
                'a.qty_based',
                'c.is_free'
            ),
            'where' => array(
                'a.shop' => $shop_id
            )
        ));

        $modvariable = $this->getVars();
        if (!empty($modvariable ['serviceexpiryday'])) {
            $expiry_reminder_time = $modvariable ['serviceexpiryday'];
        } else {
            $expiry_reminder_time = 10;
        }
        // echo $expiry_reminder_time;
        $today   = date("Y-m-d");
        $daysort = array();
        foreach ($services as $key => $val) {
            if ($val ['top_bundle'] == 1) {


                $services [$key] ['bundleitems'] = $repo->getAll(array(
                    'entity' => 'ZSELEX_Entity_BundleItem',
                    'joins' => array(
                        'JOIN a.bundle b'
                    ),
                    'where' => array(
                        'a.bundle' => $val ['bundle_id']
                    ),
                    'orderby' => 'bundle_id'
                ));
            }

            // echo $val['timer_days'] ;
            // $dateDiff = $this->dateDiff($val['timer_date'], $today);
            if ($val['is_free']) {
                $services [$key] ['DIFF'] = $this->__("NA");
            } else {
                $dateDiff = $this->dateDiff($val ['timer_date'], $today);
                $dateDiff = $val ['timer_days'] - $dateDiff;
                // echo $dateDiff . '<br>';
                if ($dateDiff < 0) {
                    $services [$key] ['DIFF'] = $this->__("Expired");
                }
                if ($dateDiff == 0) {
                    $services [$key] ['DIFF'] = $this->__("Today");
                } elseif ($dateDiff == 1) {
                    $services [$key] ['DIFF'] = $dateDiff." ".$this->__("Day");
                } elseif ($dateDiff > 1) {
                    $services [$key] ['DIFF'] = $dateDiff." ".$this->__("Days");
                }
                if ($expiry_reminder_time > $dateDiff) {
                    $services [$key] ['remind'] = 1;
                } else {
                    $services [$key] ['remind'] = 0;
                }
            }
            $daysort [$key] = $dateDiff;
        }
        array_multisort($daysort, SORT_ASC, $services);
        // echo "<pre>"; print_r($vars); echo "</pre>";
        //echo "<pre>"; print_r($services); echo "</pre>";
        // echo $shopconfig['dbname'];
        $this->view->assign('vars', $vars);
        $this->view->assign('services', $services);

        $blockinfo ['content'] = $this->view->fetch('blocks/yourservices/yourservices.tpl');

        return BlockUtil::themeBlock($blockinfo);
    }

    public function dateDiff($start, $end)
    { // returns number of days between two dates
        $start_ts = strtotime($start);

        $end_ts = strtotime($end);

        $diff = $end_ts - $start_ts;

        return round($diff / 86400);
    }

    /**
     * modify block settings .
     * .
     */
    public function modify($blockinfo)
    {
        $vars = BlockUtil::varsFromContent($blockinfo ['content']);
        if (empty($vars ['showAdminZSELEXinBlock'])) {
            $vars ['showAdminZSELEXinBlock'] = 0;
        }

        // $shops = ModUtil::apiFunc('ZSELEX', 'admin', 'getShop', $items);

        $this->view->assign('vars', $vars);
        $this->view->assign('zshops', $shops);

        return $this->view->fetch('blocks/yourservices/yourservices_modify.tpl');
    }

    /**
     * update block settings
     */
    public function update($blockinfo)
    {
        $vars = BlockUtil::varsFromContent($blockinfo ['content']);

        // alter the corresponding variable
        // $vars['showAdminZSELEXinBlock'] = FormUtil::getPassedValue('showAdminZSELEXinBlock', '', 'POST');
        // write back the new contents
        $blockinfo ['content'] = BlockUtil::varsToContent($vars);

        // clear the block cache
        $this->view->clear_cache('blocks/yourservices/yourservices.tpl');

        return $blockinfo;
    }
}
// end class def