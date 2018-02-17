<?php

function smarty_function_displayoptions($args, &$smarty)
{
    // $dom = ZLanguage::getModuleDomain('ZSELEX');
    $em        = ServiceUtil::getService('doctrine.entitymanager');
    $repo      = $em->getRepository('ZSELEX_Entity_ProductOption');
    $optionArr = array();
    // $options = json_decode($args['options'], true);
    $options   = unserialize($args ['options']);
    // echo $args['key'];
    // echo "<pre>"; print_r($options); echo "</pre>";

    if (empty($options)) {
        return '';
    }
    foreach ($options as $key => $val) {
        $optionArr [$val ['prdToOptionID']] [] = array(
            'valueID' => $val ['valueID']
        );
    }
    // echo "<pre>"; print_r($optionArr); echo "</pre>";

    $optionString = '';
    $optionString .= '<div style="font-size:12px; float:none; display: block; border:none; height:auto">';
    foreach ($optionArr as $key1 => $val1) {
        // echo $key1 .'<br>';
        // echo count($val1);
        /*
         * $get_name = ModUtil::apiFunc('ZSELEX', 'user', 'selectJoinRow', $args = array(
         * 'table' => 'zselex_product_to_options a',
         * 'fields' => array(
         * 'b.option_name,b.option_type'
         * ),
         * 'where' => array(
         * "a.product_to_options_id=$key1"
         * ),
         * 'joins' => array(
         * "LEFT JOIN zselex_product_options b ON b.option_id=a.option_id"
         * )
         * ));
         */

        $get_name = $repo->get(array(
            'entity' => 'ZSELEX_Entity_ProductToOption',
            'fields' => array(
                'b.option_name',
                'b.option_type'
            ),
            'where' => array(
                "a.product_to_options_id" => $key1
            ),
            'joins' => array(
                'LEFT JOIN a.option b'
            )
            ));
        // echo "<pre>"; print_r($get_name); echo "</pre>";
        if ($get_name ['option_type'] == 'checkbox') {
            if (count($val1) > 1) {
                $space = "<br>";
            } else {
                // $space = '';
                $space = "<br>";
            }
        } else {
            // $space = '';
            $space = "<br>";
        }
        if ($get_name == true) {
            $optionString .= "<b>".$get_name ['option_name'].': </b>'.$space;
        }
        // echo "<pre>"; print_r($val1); echo "</pre>";
        foreach ($val1 as $key2 => $val2) {

            // echo $val2[valueID] . '<br>';

            /*
             * $get_values = ModUtil::apiFunc('ZSELEX', 'user', 'selectJoinArray', array(
             * 'table' => 'zselex_product_to_options_values a',
             * 'fields' => array(
             * 'b.option_value,a.price,a.parent_option_value_id,a.price_prefix'
             * ),
             * 'where' => array(
             * "a.product_to_options_value_id=$val2[valueID]"
             * ),
             * 'joins' => array(
             * "LEFT JOIN zselex_product_options_values b ON b.option_value_id=a.option_value_id"
             * )
             * ));
             */

            // echo count($get_values);

            $get_values = $repo->getAll(array(
                'entity' => 'ZSELEX_Entity_ProductToOptionValue',
                'fields' => array(
                    'b.option_value',
                    'a.price',
                    'a.parent_option_value_id',
                    'a.price_prefix'
                ),
                'where' => array(
                    "a.product_to_options_value_id" => $val2 ['valueID']
                ),
                'joins' => array(
                    'LEFT JOIN a.option_value_id b'
                )
                )
                // 'groupby' => 'b.option_value_id'
            );

            // echo "<pre>"; print_r($get_values); echo "</pre>";
            if (!empty($get_values)) {
                $parentVal = '';
                foreach ($get_values as $key3 => $val3) {
                    // echo count($val3);
                    if ($val3 ['parent_option_value_id'] > 0) {
                        /*
                         * $getParentVal = ModUtil::apiFunc('ZSELEX', 'user', 'selectJoinRow', $args = array(
                         * 'table' => 'zselex_product_options_values a',
                         * 'fields' => array(
                         * 'a.option_value,b.option_name'
                         * ),
                         * 'where' => array(
                         * "a.option_value_id=$val3[parent_option_value_id]"
                         * ),
                         * 'joins' => array(
                         * "LEFT JOIN zselex_product_options b ON b.option_id=a.option_id"
                         * ),
                         * // 'orderby' => 'a.sort_order ASC'
                         * ));
                         */

                        $getParentVal = $repo->get(array(
                            'entity' => 'ZSELEX_Entity_ProductOptionValue',
                            'fields' => array(
                                'a.option_value',
                                'b.option_name'
                            ),
                            'where' => array(
                                "a.option_value_id" => $val3 ['parent_option_value_id']
                            ),
                            'joins' => array(
                                'LEFT JOIN a.option b'
                            )
                            )
                            // 'groupby' => 'b.option_value_id'
                        );
                        // $parentVal = "&nbsp;<i>" . $getParentVal['option_name'] . "</i>&nbsp(" . $getParentVal['option_value'] . ")<br>";
                        $parentVal    = "<b>".$getParentVal ['option_name']."</b> : <i>".$getParentVal ['option_value']."</i><br>";
                    }

                    $pref = $val3 ['price_prefix'];
                    /*
                     * if ($val3['price'] > 0) {
                     * $price = "&nbsp;(+" . ZSELEX_Util::convert_price($val3['price']) . " dkk)";
                     * } else {
                     * // $price = '';
                     * $price = "&nbsp;(" . ZSELEX_Util::convert_price($val3['price']) . " dkk)";
                     * }
                     */

                    if ($pref == '+') {
                        $price = "&nbsp;(+".ZSELEX_Util::convert_price($val3 ['price'])." dkk)";
                    } else {

                        $price = "&nbsp;(".ZSELEX_Util::convert_price($val3 ['price'])." dkk)";
                    }

                    // $optionString .= "&nbsp;<i>" . $val3['option_value'] . "</i>" . "$price<br>$parentVal";
                    $optionString .= "&nbsp;<i>".$val3 ['option_value']."</i>"."<br>$parentVal";
                }
            }
        }
        // $optionString .= '<p>';
    }
    $optionString .= '</div>';
    // echo "<pre>"; print_r($finallArr); echo "</pre>";
    // $smarty->assign("optionString", $optionString);

    return $optionString;
}
