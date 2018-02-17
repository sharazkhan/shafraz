<?php

function smarty_function_cardsaccepted($params, &$smarty)
{
    // return;
    $items   = '';
    $shop_id = $params ['shop_id'];
    $footer  = $params ['footer'];
    // echo "shop_id :" .$shop_id;
    if (!empty($shop_id)) {
        // return $items;

        $em    = ServiceUtil::getService('doctrine.entitymanager');
        $cards = $em->getRepository('ZPayment_Entity_QuickPay')->get_Cards_Accepted(array(
            'shop_id' => $shop_id
            ));
        $cards = unserialize($cards ['cards']);
        // echo "<pre>"; print_r($cards); echo "</pre>";
    } else {
        $modvars = ModUtil::getVar('ZPayment');
        $cards   = unserialize($modvars ['CardsAccepted']);
    }
    $i = 0;
    if (!empty($cards) && count($cards) > 0) {
        if ($footer) {
            $items .= "<ul class='Payments' style='margin-top:12px'>";
        }
        foreach ($cards as $key => $val) {

            $item     = $val;
            $item     = explode('|', $item);
            $card_img = $item ['1'];
            $extra    = '';
            // echo $item['0'] . '<br>';
            $title    = $smarty->__($item ['0']).$extra;
            // echo $title . '<br>';
            // if ($item['0'] != 'Mastercard-Debet') {
            // unset($cards[$key]);
            // $items .= "<span><img style='vertical-align:middle' src=modules/ZSELEX/images/CreditCards/" . $card_img . "> " . $item['0'] . "</span>";
            if ($footer) {
                $items .= "<li style='float:left;padding:3px' class='tooltips' title='".$title."'><img  style='vertical-align:middle' src=modules/ZSELEX/images/CreditCards/".$card_img."></li>";
            } else {
                $items .= "<span class='tooltips' title='".$title."'><img  style='vertical-align:middle' src=modules/ZSELEX/images/CreditCards/".$card_img."> </span>";
            }
            $i ++;
            // }
        }
        if ($footer) {
            $items .= "</ul>";
        }
    }

    echo $items;

    echo "<script type='text/javascript'>
                        Zikula.UI.Tooltips($$('.tooltips'));
     </script>";
}
