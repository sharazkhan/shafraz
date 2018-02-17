<?php

class ZSELEX_Api_Shop extends ZSELEX_Api_Admin
{

    /**
     * Get shop details
     * 
     * @param type $args
     * @return array
     */
    function getShopDetail($args)
    {

        // echo "<pre>"; print_r($args); echo "</pre>"; exit;
        $shopId   = $args ['shop_id'];
        $title    = $args ['title'];
        $shopArgs = array(
            'entity' => 'ZSELEX_Entity_Shop',
            'joins' => array('LEFT JOIN a.city b'),
            'fields' => array(
                'a.shop_id',
                'a.shop_name',
                'a.status',
                'b.city_name'
            )
        );

        if (isset($shop_id) && !empty($shop_id)) {
            // $where = " a.shop_id=:shop_id ";
            $shopArgs ['where'] ['a.shop_id'] = $shopId;
        } else {
            // $where = " a.urltitle=:urltitle ";
            $shopArgs ['where'] ['a.urltitle'] = $title;
        }
        $getShop = $this->entityManager->getRepository('ZSELEX_Entity_Shop')->get($shopArgs);
        // echo "<pre>"; print_r($getShop); echo "</pre>"; exit;
        if (!$getShop) {
            // echo "not found"; exit;
            $oldUrlArr = array(
                'entity' => 'ZSELEX_Entity_Url',
                'where' => array(
                    'a.type' => 'shop',
                    'a.url' => $title
                )
            );
            $getOldUrl = $this->entityManager->getRepository('ZSELEX_Entity_Shop')->get($oldUrlArr);
            //echo "<pre>"; print_r($getOldUrl); echo "</pre>"; exit;
            if ($getOldUrl) {
                $url = pnGetBaseURL().ModUtil::url('ZSELEX', 'user', 'shop',
                        array(
                        'shop_id' => $getOldUrl ['type_id']
                ));
                // echo $url; exit;
                // $this->redirect($url);
                System::redirect($url);
                die();
            }
        } elseif (!$getShop['status']) {
            //echo "comes here";  exit;
            if (!SecurityUtil::checkPermission('ZSELEX::cart', '::',
                    ACCESS_ADMIN)) {
                return array();
            }
        }
        // echo "Comes here..."; exit;
        return $getShop;
    }

    public function getShopProfileImage($args)
    {
        //$ownerName  = $this->ownername;
        $shop_id    = $args ['shop_id'];
        // $source     = $args ['from'];
        $image_path = '';
        $get        = ModUtil::apiFunc('ZSELEX', 'user', 'get',
                $getargs    = array(
                'table' => 'zselex_files',
                'fields' => array(
                    'file_id',
                    'name'
                ),
                'where' => "shop_id=$shop_id AND defaultImg=1"
        ));

        $image_path = pnGetBaseURL()."zselexdata/$shop_id/minisiteimages/fullsize/".$get ['name'];
        return $image_path;
    }
}
?>