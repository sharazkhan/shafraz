<?php

function smarty_function_product_option_exist($args, &$smarty)
{

    $em         = ServiceUtil::getService('doctrine.entitymanager');
    $repo       = $em->getRepository('ZSELEX_Entity_Product');
    // echo "smarty_function_product_option_exist";
    $product_id = $args ['product_id'];
    // echo "ProductID: " . $product_id;
    /* $count      = DBUtil::selectObjectCount($table      = "zselex_product_to_options",
      $where      = "product_id=$product_id", $column     = "product_id",
      $distinct, $categoryFilter, $subquery); */

    $count = $repo->getCount(null, 'ZSELEX_Entity_ProductToOption',
        'product_to_options_id',
        array(
        'a.product' => $product_id,
    ));
    // echo "count : " . $count;

    $optionQty = 0;
    if ($count) {
        /* $optionQty      = DBUtil::selectObjectSum($table          = "zselex_product_to_options_values",
          $column         = "qty",
          $where          = 'product_id="'.$product_id.'"',
          $categoryFilter = null); */

        $optionQty = getQtySum($product_id);
        //echo "qty : ".$optionQty;
    }

    $smarty->assign("optionExist", $count);
    $smarty->assign("optionQty", $optionQty);
}

function getQtySum($product_id)
{
    $em      = ServiceUtil::getService('doctrine.entitymanager');
    $dql     = "SELECT SUM(e.qty) AS balance FROM ZSELEX_Entity_ProductToOptionValue e ".
        "WHERE e.product = ?1";
    $balance = $em->createQuery($dql)
        ->setParameter(1, $product_id)
        ->getSingleScalarResult();

    return $balance;
}
