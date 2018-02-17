<?php
/**
 * Copyright ACTA-IT 2014 - ZTEXT
 *
 * ZTEXT
 * Payment Gateway Module
 *
 * @license GNU/LGPLv3 (or at your option, any later version).
 */

/**
 * Class to control Admin interface
 */
class ZTEXT_Controller_Admin extends Zikula_AbstractController
{

    /**
     * the main administration function
     * This function is the default function, and is called whenever the
     * module is initiated without defining arguments.
     */
    public function main()
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZTEXT::',
                '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());
        //$this->redirect(ModUtil::url('ZTEXT', 'admin', 'payments'));
        return $this->view->fetch('admin/info.tpl');
        //return $this->view->fetch('admin/create_page.tpl');
    }

    /**
     * @desc set caching to false for all admin functions
     * @return      null
     */
    public function postInitialize()
    {
        $this->view->setCaching(false);
    }

    /**
     * @desc present administrator options to change module configuration
     * @return      config template
     */
    public function modifyconfig()
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZTEXT::',
                '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());
        $modvars       = ModUtil::getVar('ZTEXT');
        // echo "<pre>"; print_r($modvars);  echo "</pre>";
        $CardsAccepted = unserialize($modvars['CardsAccepted']);
        //echo "<pre>"; print_r($CardsAccepted);  echo "</pre>";
        $this->view->assign('CardsAccepted', $CardsAccepted);
        return $this->view->fetch('admin/modifyconfig.tpl');
    }

    /**
     * @desc sets module variables as requested by admin
     * @return      status/error ->back to modify config page
     */
    public function updateconfig()
    {
        $this->checkCsrfToken();

        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZTEXT::',
                '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());

        // echo "<pre>"; print_r($_POST);  echo "</pre>"; exit;
        $modvars                              = array();
        //$modvars['showAdminZTEXT'] = FormUtil::getPassedValue('showAdminZTEXT', 0);
        $modvars['Netaxept_enabled_general']  = FormUtil::getPassedValue('Netaxept_enabled_general',
                0);
        $modvars['Paypal_enabled_general']    = FormUtil::getPassedValue('Paypal_enabled_general',
                0);
        $modvars['Directpay_enabled_general'] = FormUtil::getPassedValue('Directpay_enabled_general',
                0);
        $modvars['QuickPay_enabled_general']  = FormUtil::getPassedValue('QuickPay_enabled_general',
                0);
        $modvars['Epay_enabled_general']      = FormUtil::getPassedValue('Epay_enabled_general',
                0);

        $CardsAccepted            = FormUtil::getPassedValue('CardsAccepted',
                null, 'REQUEST');
        //echo "<pre>"; print_r($CreditCarsAccepted);  echo "</pre>"; exit;
        $modvars['CardsAccepted'] = serialize($CardsAccepted);

        // set the new variables
        $this->setVars($modvars);

        // clear the cache
        $this->view->clear_cache();

        LogUtil::registerStatus($this->__('Done! Updated the ZTEXT configuration.'));
        return $this->modifyconfig();
    }

    /**
     * @desc present administrator information
     * @return      template
     */
    public function info()
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZTEXT::',
                '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());

        return $this->view->fetch('admin/info.tpl');
    }

    public function _createPage()
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZTEXT::',
                '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());
        //$this->redirect(ModUtil::url('ZTEXT', 'admin', 'payments'));
        $shop_id = FormUtil::getPassedValue('shop_id', null, 'REQUEST');
        $this->view->assign('shop_id', $shop_id);

        if (!ZSELEX_Util::shopPermission($shop_id)) {
            return LogUtil::registerPermissionError();
        }

        if ($_POST) {
            $formElements = FormUtil::getPassedValue('formElements', null,
                    'REQUEST');
            $formElements = ZSELEX_Util::purifyHtml($formElements);
            $shop         = $this->entityManager->find('ZSELEX_Entity_Shop',
                $formElements['shop_id']);
            echo "<pre>";
            print_r($formElements);
            echo "</pre>";
            exit;
            extract($formElements);
            // echo $headertext; exit;
            $url_args     = array(
                'table' => 'ztext_pages',
                'title' => $headertext,
                'field' => 'urltitle'
            );
            $utltitle     = ZSELEX_Util::increment_url($url_args);
            $page         = new ZTEXT_Entity_Page();
            $page->setShop($shop);
            $page->setHeadertext($headertext);
            $page->setUrltitle($utltitle);
            $bodytext     = @preg_replace('/(<[^>]+) style=".*?"/i', '$1',
                    $bodytext);
            $page->setBodytext($bodytext);
            $page->setActive($active);
            $page->setDisplayonfront($displayonfront);
            $page->setLink($link);
            $page->setSort_order($sort_order);
            $this->entityManager->persist($page);
            $this->entityManager->flush();
            $result       = $page->getText_id();
            if ($result) {
                $this->redirect(ModUtil::url('ZTEXT', 'admin', 'viewPages',
                        array('shop_id' => $shop_id)));
            }
        }

        return $this->view->fetch('admin/create_page.tpl');
    }

    function _viewPages()
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZTEXT::',
                '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());
        $shop_id = FormUtil::getPassedValue('shop_id', null, 'REQUEST');
        $this->view->assign('shop_id', $shop_id);
        $repo    = $this->entityManager->getRepository('ZTEXT_Entity_Page');

        //echo "<pre>"; print_r($pages); echo "</pre>"; exit;

        $sort   = array();
        $fields = array(
            'text_id',
            'headertext'
        );

        $itemsperpage  = FormUtil::getPassedValue('itemsperpage',
                isset($args['itemsperpage']) ? $args['itemsperpage'] : 20,
                'GETPOST');
        $startnum      = FormUtil::getPassedValue('startnum',
                isset($args['startnum']) ? $args['startnum'] : null, 'GETPOST');
        $status        = FormUtil::getPassedValue('status',
                isset($args['status']) ? $args['status'] : null, 'GETPOST');
        //echo "status :". $status; 
        $order         = FormUtil::getPassedValue('order',
                isset($args['order']) ? $args['order'] : 'text_id', 'GETPOST');
        $original_sdir = FormUtil::getPassedValue('sdir',
                isset($args['sdir']) ? $args['sdir'] : 0, 'GETPOST');
        $searchtext    = FormUtil::getPassedValue('searchtext',
                isset($args['searchtext']) ? $args['searchtext'] : null,
                'GETPOST');
        $this->view->assign('searchtext', $searchtext);
        $this->view->assign('startnum', $startnum);
        $this->view->assign('itemsperpage', $itemsperpage);
        $this->view->assign('order', $order);
        $this->view->assign('sdir', $original_sdir);
        $aStatus       = array(
            'InActive',
            'Active'
        );
        $this->view->assign('aStatus', $aStatus);
        $sdir          = $original_sdir ? 0 : 1; //if true change to false, if false change to true
        // change class for selected 'orderby' field to asc/desc
        if ($sdir == 0) {
            $sort['class'][$order] = 'z-order-desc';
            $orderdir              = 'DESC';
        }
        if ($sdir == 1) {
            $sort['class'][$order] = 'z-order-asc';
            $orderdir              = 'ASC';
        }
        // complete initialization of sort array, adding urls
        foreach ($fields as $field) {
            $sort['url'][$field] = ModUtil::url('ZTEXT', 'admin', 'viewPages',
                    array(
                    'shop_id' => $shop_id,
                    'status' => $status,
                    'filtercats_serialized' => serialize($filtercats),
                    'order' => $field,
                    'sdir' => $sdir
            ));
        }
        //  echo "<pre>"; print_r($sort);  echo "</pre>";
        $this->view->assign('sort', $sort);

        $this->view->assign('filter_active', $status);

        $page_args = array(
            'shop_id' => $shop_id,
            'startlimit' => $startnum,
            'offset' => $itemsperpage,
            'order' => $order,
            'orderdir' => $orderdir,
            'status' => $status,
            'searchtext' => $searchtext,
        );



        // echo "<pre>"; print_r($page_args);  echo "</pre>";
        $getPages    = $repo->getPages($page_args);
        $pages       = $getPages['result'];
        $total_count = $getPages['count'];
        $itemstatus  = array(
            '' => $this->__('All'),
            ZSELEX_Controller_Admin::STATUS_ACTIVE => $this->__('Active'),
            ZSELEX_Controller_Admin::STATUS_INACTIVE => $this->__('InActive')
        );


        $this->view->assign('itemstatus', $itemstatus);
        $this->view->assign('pages', $pages);

        $this->view->assign('total_count', $total_count);
        return $this->view->fetch('admin/view_pages.tpl');
    }

    function pages()
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZTEXT::',
                '::', ACCESS_EDIT), LogUtil::getErrorMsgPermission());
        $shop_id = FormUtil::getPassedValue('shop_id', null, 'REQUEST');
        $this->view->assign('shop_id', $shop_id);
        if (!(int) ($shop_id)) {
            return LogUtil::registerError($this->__f('Error! The ShopID in URL is invalid (%1$s). Please contact the administrator of this site and inform about this error!',
                        (int) ($shop_id)));
        }

        if (!ZSELEX_Util::shopPermission($shop_id)) {
            return LogUtil::registerPermissionError();
        }

        $repo = $this->entityManager->getRepository('ZTEXT_Entity_Page');

        //echo "<pre>"; print_r($pages); echo "</pre>"; exit;

        $sort   = array();
        $fields = array(
            'text_id',
            'headertext'
        );

        $itemsperpage  = FormUtil::getPassedValue('itemsperpage',
                isset($args['itemsperpage']) ? $args['itemsperpage'] : 20,
                'GETPOST');
        $startnum      = FormUtil::getPassedValue('startnum',
                isset($args['startnum']) ? $args['startnum'] : null, 'GETPOST');
        $status        = FormUtil::getPassedValue('status',
                isset($args['status']) ? $args['status'] : null, 'GETPOST');
        //echo "status :". $status; 
        $order         = FormUtil::getPassedValue('order',
                isset($args['order']) ? $args['order'] : 'text_id', 'GETPOST');
        $original_sdir = FormUtil::getPassedValue('sdir',
                isset($args['sdir']) ? $args['sdir'] : 0, 'GETPOST');
        $searchtext    = FormUtil::getPassedValue('searchtext',
                isset($args['searchtext']) ? $args['searchtext'] : null,
                'GETPOST');
        $this->view->assign('searchtext', $searchtext);
        $this->view->assign('startnum', $startnum);
        $this->view->assign('itemsperpage', $itemsperpage);
        $this->view->assign('order', $order);
        $this->view->assign('sdir', $original_sdir);
        $aStatus       = array(
            'InActive',
            'Active'
        );
        $this->view->assign('aStatus', $aStatus);
        $sdir          = $original_sdir ? 0 : 1; //if true change to false, if false change to true
        // change class for selected 'orderby' field to asc/desc
        if ($sdir == 0) {
            $sort['class'][$order] = 'z-order-desc';
            $orderdir              = 'DESC';
        }
        if ($sdir == 1) {
            $sort['class'][$order] = 'z-order-asc';
            $orderdir              = 'ASC';
        }
        // complete initialization of sort array, adding urls
        foreach ($fields as $field) {
            $sort['url'][$field] = ModUtil::url('ZTEXT', 'admin', 'viewPages',
                    array(
                    'shop_id' => $shop_id,
                    'status' => $status,
                    'filtercats_serialized' => serialize($filtercats),
                    'order' => $field,
                    'sdir' => $sdir
            ));
        }
        //  echo "<pre>"; print_r($sort);  echo "</pre>";
        $this->view->assign('sort', $sort);

        $this->view->assign('filter_active', $status);

        $serviceargs       = array(
            'shop_id' => $shop_id,
            'type' => 'pages',
            'disablecheck' => true
        );
        $servicePermission = ModUtil::apiFunc('ZSELEX', 'admin',
                'servicePermission', $serviceargs);
        //echo "<pre>"; print_r($servicePermission);  echo "</pre>";
        $this->view->assign('service', $servicePermission);
        if ($servicePermission['perm'] < 1) {
            $message = $servicePermission['message'];
            LogUtil::registerError(nl2br($message));
        }

        $pages = array();
        if ($servicePermission['perm']) {
            $totalPages = $repo->getCount(null, 'ZTEXT_Entity_Page', 'text_id',
                array(
                'a.shop' => $shop_id
            ));

            $pageLimit  = $servicePermission ['quantity'] - $totalPages;
            $pages_left = $pageLimit;
            if ($pageLimit < 1) {

                $message = ($servicePermission['service_status'] == 2) ? $this->__("Your service limit is over for this service")
                        : $this->__("Your service limit is over for this demo service");
                
                LogUtil::registerError(nl2br($message));
            }

            $page_args = array(
                'shop_id' => $shop_id,
                'startlimit' => $startnum,
                //'offset' => $itemsperpage,
                'order' => $order,
                'orderdir' => $orderdir,
                'status' => $status,
                'searchtext' => $searchtext,
            );
            if ($pageLimit < $totalPages) {
                $page_args['offset'] = $servicePermission ['quantity'];
            }



            // echo "<pre>"; print_r($page_args);  echo "</pre>";
            $getPages    = $repo->getPages($page_args);
            $pages       = $getPages['result'];
            $total_count = $getPages['count'];
        }
        $itemstatus = array(
            '' => $this->__('All'),
            ZSELEX_Controller_Admin::STATUS_ACTIVE => $this->__('Active'),
            ZSELEX_Controller_Admin::STATUS_INACTIVE => $this->__('InActive')
        );


        $this->view->assign('itemstatus', $itemstatus);
        $this->view->assign('pages', $pages);
        $this->view->assign('shop_id', $shop_id);

        $this->view->assign('total_count', $total_count);

        /*
          $page_service_args = array(
          'entity' => 'ZSELEX_Entity_ServiceShop',
          'fields' => array('a.quantity', 'a.availed'),
          'where' => 'a.shop=:shop_id AND a.type=:type',
          'setParams' => array('shop_id' => $shop_id, 'type' => 'pages'),
          //'orderby' => 'a.shop_event_id ASC'
          );
          $page_service      = $this->entityManager->getRepository('ZSELEX_Entity_ServiceShop')->fetch($page_service_args);
          $pages_left        = $page_service['quantity'] - $page_service['availed'];
         */


        $this->view->assign('pages_left', $pages_left);
        /*
          $serviceargs = array(
          'shop_id' => $shop_id,
          'type' => 'pages',
          'disablecheck' => true
          );
          $servicePermission = ModUtil::apiFunc('ZSELEX', 'admin', 'servicePermission', $serviceargs);
          //echo "<pre>"; print_r($servicePermission);  echo "</pre>";
          $this->view->assign('service', $servicePermission);
          if ($servicePermission['perm'] < 1) {
          $message = $servicePermission['message'];
          LogUtil::registerError(nl2br($message));
          } */
        $page_setting = $repo->get(array('entity' => 'ZTEXT_Entity_PageSetting',
            'fields' => array('a.disable_page_index', 'a.disable_frontend_image'),
            'where' => array('a.shop' => $shop_id)));
        $this->view->assign('page_setting', $page_setting);
        $this->view->assign('upload_path', "zselexdata/$shop_id/ztext");

        $diskquota_service = ModUtil::apiFunc('ZSELEX', 'admin',
                'checkDiskquota',
                array(
                'shop_id' => $shop_id
        ));
        // echo "<pre>"; print_r($diskquota_service);  echo "</pre>"; exit;
        $this->view->assign('diskquota_service', $diskquota_service);
        $image_args        = array(
            'shop_id' => $shop_id,
            'type' => 'minisiteimages',
            'disablecheck' => true
        );
        $image_service     = ModUtil::apiFunc('ZSELEX', 'admin',
                'servicePermission', $image_args);
        //$this->view->assign('image_service', $image_service);
        $page_limit        = 1;
        if ($diskquota_service['error'] || $image_service['expired']) {
            $page_limit = 0;
        }
        $this->view->assign('page_limit', $page_limit);
        $this->view->assign('time', time());
        return $this->view->fetch('admin/page_dnd.tpl');
    }

    function _editPage($args)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());

        $shop_id = FormUtil::getPassedValue('shop_id', null, 'REQUEST');
        $text_id = FormUtil::getPassedValue('text_id',
                isset($args['text_id']) ? $args['text_id'] : null, 'GETPOST');
        $repo    = $this->entityManager->getRepository('ZTEXT_Entity_Page');
        if ($_POST) {
            $formElements = FormUtil::getPassedValue('formElements',
                    isset($args['formElements']) ? $args['formElements'] : null,
                    'POST');
            //  echo "<pre>"; print_r($formElements);  echo "</pre>"; exit;
            //$InsertId = $formElements['text_id'];
            $tags         = array('<font>');
            $bodytext     = $formElements['bodytext'];
            //$bodytext = $this->strip_html_tags($bodytext);
            // echo $bodytext;exit;


            $url_args = array(
                'table' => 'ztext_pages',
                'title' => $formElements['headertext'],
                'field' => 'urltitle'
            );
            $utltitle = ZSELEX_Util::increment_url($url_args);

            $bodytext = @preg_replace('/(<[^>]+) style=".*?"/i', '$1',
                    $formElements['bodytext']);
            $item     = array(
                'headertext' => $formElements['headertext'],
                'urltitle' => $utltitle,
                'bodytext' => $bodytext,
                'active' => $formElements['active'],
                'displayonfront' => isset($formElements['displayonfront']) ? $formElements['displayonfront']
                        : '',
                'link' => isset($formElements['link']) ? $formElements['link'] : 0,
                'sort_order' => isset($formElements['sort_order']) ? $formElements['sort_order']
                        : 0
            );

            $result = $repo->updateEntity(null, 'ZTEXT_Entity_Page', $item,
                array('a.text_id' => $text_id));
            if ($result) {
                LogUtil::registerStatus($this->__('Done! Page has been updated successfully.'));
                $this->redirect(ModUtil::url('ZTEXT', 'admin', 'viewPages',
                        array('shop_id' => $shop_id)));
            }
        }
        //echo "modifycity";
        // $item = ModUtil::apiFunc('ZSELEX', 'admin', 'getElement', $args);
        $page = $repo->get(array('entity' => 'ZTEXT_Entity_Page', 'where' => array(
                'a.text_id' => $text_id)));

        $this->view->assign('item', $page);
        $this->view->assign('shop_id', $shop_id);
        return $this->view->fetch('admin/create_page.tpl');
    }

    function _deletePage($args)
    {
        $text_id = FormUtil::getPassedValue('text_id',
                isset($args['text_id']) ? $args['text_id'] : null, 'REQUEST');
        $shop_id = FormUtil::getPassedValue('shop_id',
                isset($args['shop_id']) ? $args['shop_id'] : null, 'REQUEST');
        $entity  = 'ZTEXT_Entity_Page';

        //$user_id = UserUtil::getVar('uid');
        // Validate the essential parameters
        if (empty($text_id)) {
            return LogUtil::registerArgsError();
        }

        $repo         = $this->entityManager->getRepository($entity);
        $confirmation = FormUtil::getPassedValue('confirmation', null, 'POST');
        if (empty($confirmation)) {
            // Add ZSELEX type ID
            $this->view->assign('IdValue', $text_id);
            $this->view->assign('confirm_title',
                $this->__f('Delete %s item', $this->__('Page')));
            $this->view->assign('confirm_msg',
                $this->__f('Do you want to delete this %s item',
                    $this->__('Page')));
            $this->view->assign('shop_id', $shop_id);
            $this->view->assign('IdName', 'text_id'); // edit id param name
            $this->view->assign('submitFunc', 'deletePage');
            $this->view->assign('cancelFunc', 'viewPages');
            $emptyvar = $this->__('Confirmation prompt'); // just to get the translation out with poedit!!!
            // Return the output that has been generated by this function
            return $this->view->fetch('admin/deletecommon.tpl');
        }


        $delete = $repo->deleteEntity(null, $entity,
            array('a.text_id' => $text_id));
        //   if (ModUtil::apiFunc('ZSELEX', 'admin', 'deleteItem', $args)) {
        if ($delete) {
            // Success
            LogUtil::registerStatus($this->__('Done! Page has been deleted successfully.'));

            // Let any hooks know that we have deleted an item
            $this->notifyHooks(new Zikula_ProcessHook('type.ui_hooks.types.process_delete',
                $Id));
        }

        return $this->redirect(ModUtil::url('ZTEXT', 'admin', 'viewPages',
                    array('shop_id' => $shop_id)));
    }

    function strip_html_tags($text)
    {
        // PHP's strip_tags() function will remove tags, but it
        // doesn't remove scripts, styles, and other unwanted
        // invisible text between tags.  Also, as a prelude to
        // tokenizing the text, we need to insure that when
        // block-level tags (such as <p> or <div>) are removed,
        // neighboring words aren't joined.
        $text = preg_replace(
            array(
            // Remove invisible content
            '@<head[^>]*?>.*?</head>@siu',
            '@<style[^>]*?>.*?</style>@siu',
            '@<script[^>]*?.*?</script>@siu',
            '@<object[^>]*?.*?</object>@siu',
            '@<embed[^>]*?.*?</embed>@siu',
            '@<applet[^>]*?.*?</applet>@siu',
            '@<noframes[^>]*?.*?</noframes>@siu',
            '@<noscript[^>]*?.*?</noscript>@siu',
            '@<noembed[^>]*?.*?</noembed>@siu',
            // Add line breaks before & after blocks
            '@<((br)|(hr))@iu',
            '@</?((address)|(blockquote)|(center)|(del))@iu',
            '@</?((div)|(h[1-9])|(ins)|(isindex)|(p)|(pre))@iu',
            '@</?((dir)|(dl)|(dt)|(dd)|(li)|(menu)|(ol)|(ul))@iu',
            '@</?((table)|(th)|(td)|(caption))@iu',
            '@</?((form)|(button)|(fieldset)|(legend)|(input))@iu',
            '@</?((label)|(select)|(optgroup)|(option)|(textarea))@iu',
            '@</?((frameset)|(frame)|(iframe))@iu',
            ),
            array(
            ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ',
            "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0",
            "\n\$0", "\n\$0",
            ), $text);

        // Remove all remaining tags and comments and return.
        //return strip_tags($text);
        return $text;
    }
}
// end class def