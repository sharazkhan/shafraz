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
class ZSELEX_Block_Advertisedisplay extends Zikula_Controller_AbstractBlock
{
    public $amount;

    /**
     * initialise block
     */
    public function init()
    {
        SecurityUtil::registerPermissionSchema('ZSELEX:advertisedisplayblock:',
            'Block title::');
    }

    /**
     * get information on block
     */
    public function info()
    {
        return array(
            'text_type' => 'selection',
            'module' => 'ZSELEX',
            'text_type_long' => $this->__('Advertisedisplay Block'),
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
        if (!SecurityUtil::checkPermission('ZSELEX:helloblock:',
                "$blockinfo[title]::", ACCESS_OVERVIEW)) {
            return;
        }
        if (!ModUtil::available('ZSELEX')) {
            return;
        }
        $vars = BlockUtil::varsFromContent($blockinfo ['content']);

        // echo "<pre>"; print_r($vars); echo "</pre>";
        // $products = ModUtil::apiFunc('ZSELEX', 'admin', 'getAdRandomProducts', $vars);
        // echo "<pre>"; print_r($products); echo "</pre>";
        // echo count($products);
        // $test = array_rand($products , 2);
        // echo "<pre>"; print_r($products); echo "</pre>";

        $aItem = array();
        if (count($products) > 0) {
            foreach ($products as $product) {
                foreach ($product as $item) {
                    $aItem [] = $item;
                }
            }
            $prodval = $this->array_random_assoc($aItem, $num     = 1);
        }

        // echo "<pre>"; print_r($prodval); echo "</pre>";
        // echo $shopconfig['dbname'];
        $this->view->assign('vars', $vars);
        $this->view->assign('products', $prodval);
        $this->view->assign('shopconfig', $shopconfig);

        $blockinfo ['content'] = $this->view->fetch('blocks/advertise.tpl');

        return BlockUtil::themeBlock($blockinfo);
    }

    function array_random_assoc($arr, $num = 2)
    {
        $keys = array_keys($arr);
        shuffle($keys);

        $r = array();
        for ($i = 0; $i <= $num; $i ++) {
            $r [$keys [$i]] = $arr [$keys [$i]];
        }
        return $r;
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

        $shops = ModUtil::apiFunc('ZSELEX', 'admin', 'getShop', $items);

        $adtypes = ModUtil::apiFunc('ZSELEX', 'admin', 'getAdTypes', $items);
        // echo "<pre>"; print_r($adtypes); echo "</pre>";

        $this->view->assign('vars', $vars);
        $this->view->assign('adtypes', $adtypes);

        return $this->view->fetch('blocks/advertise_modify.tpl');
    }

    /**
     * update block settings
     */
    public function update($blockinfo)
    {
        $vars = BlockUtil::varsFromContent($blockinfo ['content']);

        // alter the corresponding variable
        $vars ['showAdminZSELEXinBlock'] = FormUtil::getPassedValue('showAdminZSELEXinBlock',
                '', 'POST');

        $vars ['amount']  = FormUtil::getPassedValue('amount', '', 'POST');
        $vars ['adtype']  = FormUtil::getPassedValue('adtype', '', 'POST');
        $vars ['orderby'] = FormUtil::getPassedValue('orderby', '', 'POST');

        // write back the new contents
        $blockinfo ['content'] = BlockUtil::varsToContent($vars);

        // clear the block cache
        $this->view->clear_cache('blocks/advertise_modify.tpl');

        return $blockinfo;
    }
}
// end class def