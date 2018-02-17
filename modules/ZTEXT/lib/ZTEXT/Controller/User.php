<?php
/**
 * ZTEXT
 * User controller class
 *
 */

/**
 * Class to control User interface
 */
class ZTEXT_Controller_User extends Zikula_AbstractController
{

    /**
     * Display pages
     * 
     * @param array $args
     * int - shop_id , int - itemsperpage , int - startnum , string - order , int - sdir
     * @return array of pages
     */
    function pages($args)
    {
        // echo 'comes here..'; exit;
        $shop_id = FormUtil::getPassedValue('shop_id', null, 'REQUEST');

        if (!$shop_id) {
            //  echo "find us"; exit;
            return LogUtil::registerError($this->__('Error! Site not found.'),
                    404);
        }

        $user_id = UserUtil::getVar('uid');
        $perm    = ModUtil::apiFunc('ZSELEX', 'admin', 'shopPermission',
                array('shop_id' => $shop_id, 'user_id' => $user_id));
        System::queryStringSetVar('perm', $perm);
        $this->view->assign('perm', $perm);

        $serviceExist = ModUtil::apiFunc('ZSELEX', 'admin', 'serviceExistBlock',
                $args         = array(
                'shop_id' => $shop_id,
                'type' => 'pages'
        ));


        $repo          = $this->entityManager->getRepository('ZTEXT_Entity_Page');
        $itemsperpage  = FormUtil::getPassedValue('itemsperpage',
                isset($args['itemsperpage']) ? $args['itemsperpage'] : 1,
                'GETPOST');
        $startnum      = FormUtil::getPassedValue('startnum',
                isset($args['startnum']) ? $args['startnum'] : null, 'GETPOST');
        $order         = FormUtil::getPassedValue('order',
                isset($args['order']) ? $args['order'] : 'text_id', 'GETPOST');
        $original_sdir = FormUtil::getPassedValue('sdir',
                isset($args['sdir']) ? $args['sdir'] : 0, 'GETPOST');
        $sdir          = $original_sdir ? 0 : 1;
        $this->view->assign('itemsperpage', $itemsperpage);
        if ($sdir == 0) {
            $sort['class'][$order] = 'z-order-desc';
            $orderdir              = 'DESC';
        }
        if ($sdir == 1) {
            $sort['class'][$order] = 'z-order-asc';
            $orderdir              = 'ASC';
        }
        $page_args = array(
            'shop_id' => $shop_id,
            'startlimit' => $startnum,
            'itemsperpage' => $itemsperpage,
            /* 'order' => $order,
              'orderdir' => $orderdir,
              'status' => $status,
              'searchtext' => $searchtext,
             */
        );



        // echo "<pre>"; print_r($page_args);  echo "</pre>";
        $getPages    = array();
        $total_count = 0;
        if ($serviceExist) {
            $getPages    = $repo->getPagesForSite($page_args);
            //echo "<pre>"; print_r($getPages);  echo "</pre>"; exit;
            $total_count = $repo->getPagesForSiteCount($page_args);
        }



        $pages = $getPages;
        // echo "<pre>"; print_r($pages);  echo "</pre>"; exit;
        $this->view->assign('pages', $pages);
        $this->view->assign('total_count', $total_count);


        /*
          $url = pnGetBaseURL().ModUtil::url('ztext', 'user', 'pages',
          array(
          'shop_id' => $shop_id
          ));
          // echo $url;
          $this->view->assign('url', $url);

          $shopImage = ModUtil::apiFunc('ZSELEX', 'shop', 'getShopProfileImage',
          array('shop_id' => $shop_id));
          $this->view->assign('shopImage', $shopImage);

          $shopArgs = array('entity' => 'ZSELEX_Entity_Shop', 'where' => array('a.shop_id' => $shop_id),
          'fields' => array('a.shop_name', 'a.shop_info'));
          $shopInfo = $this->entityManager->getRepository('ZSELEX_Entity_Shop')->get($shopArgs);
          $this->view->assign('shopInfo', $shopInfo);
         */
        return $this->view->fetch('user/pages.tpl');
    }

    /**
     * Display a page
     * 
     * @param array $args
     * int - shop_id , int - text_id
     * 
     * @return mixed
     */
    function page($args)
    {
        // echo 'comes here..'; exit;
        $shop_id = FormUtil::getPassedValue('shop_id', null, 'REQUEST');

        if (!$shop_id) {
            //  echo "find us"; exit;
            return LogUtil::registerError($this->__('Error! Site not found.'),
                    404);
        }

        $text_id = FormUtil::getPassedValue('text_id', null, 'REQUEST');
        $repo    = $this->entityManager->getRepository('ZTEXT_Entity_Page');
        $user_id = UserUtil::getVar('uid');

        $serviceExist = ModUtil::apiFunc('ZSELEX', 'admin', 'serviceExistBlock',
                $args         = array(
                'shop_id' => $shop_id,
                'type' => 'pages'
        ));


        $page       = array();
        $page_arags = array(
            'entity' => 'ZTEXT_Entity_Page',
            // 'fields' => array('a.text_id', 'a.headertext'),
            'where' => 'a.shop=:shop_id AND a.text_id=:text_id',
            'setParams' => array('shop_id' => $shop_id, 'text_id' => $text_id),
            // 'orderby' => 'a.shop_event_id DESC'
        );

        if ($serviceExist) {
            $page = $repo->fetch($page_arags);
        }
        // echo "<pre>"; print_r($page);  echo "</pre>"; exit;
        if ($page == false) {
            //  echo "comes here"; exit;
            return LogUtil::registerError($this->__('Error! page not found.'),
                    404);
        }

        //  echo "<pre>";  print_r($page);   echo "</pre>";


        $this->view->assign('page', $page);
        $perm = ModUtil::apiFunc('ZSELEX', 'admin', 'shopPermission',
                array('shop_id' => $shop_id, 'user_id' => $user_id));
        System::queryStringSetVar('perm', $perm);
        $this->view->assign('perm', $perm);


        $url = pnGetBaseURL().ModUtil::url('ztext', 'user', 'page',
                array(
                'shop_id' => $shop_id,
                'text_id' => $text_id
        ));
        // echo $url;
        $this->view->assign('url', $url);

        /*
          $shopImage = ModUtil::apiFunc('ZSELEX', 'shop', 'getShopProfileImage',
          array('shop_id' => $shop_id));
          $this->view->assign('shopImage', $shopImage);
          $shopArgs  = array('entity' => 'ZSELEX_Entity_Shop', 'where' => array('a.shop_id' => $shop_id),
          'fields' => array('a.shop_name', 'a.shop_info'));
          $shopInfo  = $this->entityManager->getRepository('ZSELEX_Entity_Shop')->get($shopArgs);
         */
        // $this->view->assign('shopInfo', $shopInfo);

        $imagePath = pnGetBaseURL()."zselexdata/$shop_id/ztext/medium/".$page['image'];
        $this->view->assign('imagePath', $imagePath);

        return $this->view->fetch('user/page.tpl');
    }

    /**
     * View pdf file
     * 
     * @param array $args
     * @return html
     */
    function pdfView($args)
    {
        // echo "<pre>";   print_r($_REQUEST);     echo "</pre>"; 
        $text_id = $_REQUEST['id'];
        // $shop_id = $_REQUEST['shop_id'];
        $repo    = $this->entityManager->getRepository('ZTEXT_Entity_Page');
        //$pdf = urldecode($pdf);
        // echo $pdf; exit;
        $data    = $repo->get(
            array(
                'entity' => 'ZTEXT_Entity_Page',
                'fields' => array('a.doc', 'b.shop_id'),
                'joins' => array('JOIN a.shop b'),
                'where' => array('a.text_id' => $text_id),
            // 'exit'=>true
        ));
        //echo "<pre>";   print_r($data);     echo "</pre>"; exit;

        $shop_id = $data['shop_id'];
        // $path_parts = pathinfo($pdf);
        // $extension = $path_parts['extension'];
        //echo "<pre>";   print_r($path_parts);     echo "</pre>"; exit;
        //$file = "zselexdata/$ownerName/events/docs/" . $pdf;
        $file    = "zselexdata/$shop_id/ztext/pdf/".$data['doc'];
        if (!file_exists($file)) {
            return LogUtil::registerError($this->__("file not found!"));
        }

        $this->view->assign('file', $file);
        return $this->view->fetch('user/pdfview.tpl');
    }
}
// end class def