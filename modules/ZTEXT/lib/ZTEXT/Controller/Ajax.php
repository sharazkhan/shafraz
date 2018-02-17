<?php

class ZTEXT_Controller_Ajax extends ZSELEX_Controller_Base_Ajax {

    function pagePopUp($args) {
        $view = Zikula_View::getInstance($this->name);
        $shop_id = FormUtil::getPassedValue('shop_id', null, 'REQUEST');
        $text_id = FormUtil::getPassedValue('text_id', null, 'REQUEST');
        $div_id = FormUtil::getPassedValue('div_id', null, 'REQUEST');
        $repo = $this->entityManager->getRepository('ZTEXT_Entity_Page');
        $data = '';
        $output = array();

        $page = $repo->get(array('entity' => 'ZTEXT_Entity_Page', 'where' => array('a.text_id' => $text_id)));
        // echo "<pre>"; print_r($page);  echo "</pre>"; exit;
        $view->assign('shop_id', $shop_id);
        $view->assign('item', $page);
        $view->assign('div_id', $div_id);

        $output_page = $view->fetch('admin/page_popup.tpl');
        $data .= new Zikula_Response_Ajax_Plain($output_page);
        $output["data"] = $data;
        $output["event_id"] = $event_id;
        AjaxUtil::output($output);
    }

    function savePage($args) {
        // echo "<pre>"; print_r($_REQUEST);  echo "</pre>"; exit;
        //$this->checkCsrfToken();
        $repo = $this->entityManager->getRepository('ZTEXT_Entity_Page');
        $text_id = FormUtil::getPassedValue('text_id', null, 'REQUEST');
        $shop_id = FormUtil::getPassedValue('shop_id', null, 'REQUEST');
        $headertext = FormUtil::getPassedValue('headertext', null, 'REQUEST');
        // $shopId = FormUtil::getPassedValue('shopId', null, 'REQUEST');
        $bodytext = FormUtil::getPassedValue('bodytext', null, 'POST');
        // echo $bodytext; exit;
        $status = FormUtil::getPassedValue('status', null, 'REQUEST');
        $displayonfront = FormUtil::getPassedValue('displayonfront', null, 'REQUEST');
        $sort_order = FormUtil::getPassedValue('sort_order', null, 'REQUEST');
        $link = FormUtil::getPassedValue('link', null, 'REQUEST');
        $div_id = FormUtil::getPassedValue('div_id', null, 'REQUEST');
        $urltitle = strtolower($headertext);
        $urltitle = ZSELEX_Util::cleanTitle($urltitle);


        //$bodytext = @preg_replace('/(<[^>]+) style=".*?"/i', '$1', $bodytext);
        if ($text_id) {
            $urlCount = $repo->getCount2(
                    array('entity' => 'ZTEXT_Entity_Page',
                        'field' => 'text_id',
                        'where' => "a.urltitle=:urltitle AND a.text_id!=" . $text_id,
                        'setParams' => array('urltitle' => $urltitle)
            ));

            if ($urlCount > 0) {
                $url_args = array(
                    'table' => 'ztext_pages',
                    'title' => $urltitle,
                    'field' => 'urltitle'
                );
                $final_urltitle = ZSELEX_Util::increment_url($url_args);
            } else {
                $final_urltitle = $urltitle;
            }
            $item = array(
                'headertext' => $headertext,
                'urltitle' => $final_urltitle,
                'bodytext' => $bodytext,
                'active' => $status,
                'displayonfront' => $displayonfront,
                'link' => $link,
                'sort_order' => isset($sort_order) ? $sort_order : 0
            );

            $result = $repo->updateEntity(null, 'ZTEXT_Entity_Page', $item, array('a.text_id' => $text_id));
        } else {
            $url_args = array(
                'table' => 'ztext_pages',
                'title' => $urltitle,
                'field' => 'urltitle'
            );
            $final_urltitle = ZSELEX_Util::increment_url($url_args);

            $page = new ZTEXT_Entity_Page();
            $shop = $this->entityManager->find('ZSELEX_Entity_Shop', $shop_id);
            $page->setShop($shop);
            $page->setHeadertext($headertext);
            $page->setUrltitle($final_urltitle);
            $page->setBodytext($bodytext);
            $page->setActive($status);
            $page->setDisplayonfront($displayonfront);
            $page->setLink($link);
            $page->setSort_order($sort_order);
            $this->entityManager->persist($page);
            $this->entityManager->flush();
            $result = $page->getText_id();
            if ($result) {
                $serviceupdatearg = array(
                    'user_id' => UserUtil::getVar('uid'),
                    'type' => 'pages',
                    'shop_id' => $shop_id
                );
                $serviceavailed = ModUtil::apiFunc('ZSELEX', 'admin', 'updateServiceUsed', $serviceupdatearg);
            }
        }
        $output = array();
        if ($result) {
            if (!$text_id) {
                $output['create'] = 1;
            } else {
                $output['create'] = 0;
            }
            $output['success'] = 1;
            $output['text_id'] = $result;
            $output['div_id'] = $div_id;
        }
        AjaxUtil::output($output);
    }

    function getPage($args) {
        $data = '';
        $view = Zikula_View::getInstance($this->name);
        $repo = $this->entityManager->getRepository('ZTEXT_Entity_Page');
        $text_id = FormUtil::getPassedValue('text_id', null, 'REQUEST');
        $shop_id = FormUtil::getPassedValue('shop_id', null, 'REQUEST');
        $create = FormUtil::getPassedValue('create', null, 'REQUEST');

        $page = $repo->get(array('entity' => 'ZTEXT_Entity_Page', 'where' => array('a.text_id' => $text_id)));
        $view->assign('item', $page);
        $view->assign('shop_id', $shop_id);
        $aStatus = array(
            'InActive',
            'Active'
        );
        $view->assign('aStatus', $aStatus);
        if ($create == 'yes') {
            // echo "exitsssss"; exit;
            $output_page = $view->fetch('admin/full_content.tpl');
        } else {
            $output_page = $view->fetch('admin/single_content.tpl');
        }
        // $output_page = $view->fetch('admin/single_content.tpl');
        $data .= new Zikula_Response_Ajax_Plain($output_page);
        $output["data"] = $data;
        AjaxUtil::output($output);
    }

    function deletePage() {
        $repo = $this->entityManager->getRepository('ZTEXT_Entity_Page');
        $text_id = FormUtil::getPassedValue('text_id', null, 'REQUEST');
        $shop_id = FormUtil::getPassedValue('shop_id', null, 'REQUEST');
        $delete_image = FormUtil::getPassedValue('delete_image', null, 'REQUEST');
        $page = $repo->get(array('entity' => 'ZTEXT_Entity_Page', 'where' => array('a.text_id' => $text_id)));
        //echo "<pre>"; print_r($page); echo "</pre>"; exit;
        if (!$delete_image) {
            $delete = $repo->deleteEntity(null, 'ZTEXT_Entity_Page', array('a.text_id' => $text_id));
        } else {
            $delete = true;
        }
        if ($delete) {

            if (!$delete_image) {
                $args = array(
                    'shop_id' => $shop_id,
                    'servicetype' => 'pages',
                );
                $deleteservice = ModUtil::apiFunc('ZSELEX', 'admin', 'deleteService', $args);
            }

            if ($page['extension'] == 'pdf') {
                if (is_file('zselexdata/' . $shop_id . '/ztext/pdf/' . $page['doc'])) {
                    unlink('zselexdata/' . $shop_id . '/ztext/pdf/' . $page['doc']);
                }
                if (is_file('zselexdata/' . $shop_id . '/ztext/pdf/medium/' . $page['image'])) {
                    unlink('zselexdata/' . $shop_id . '/ztext/pdf/medium/' . $page['image']);
                }
                if (is_file('zselexdata/' . $shop_id . '/ztext/pdf/thumb/' . $page['image'])) {
                    unlink('zselexdata/' . $shop_id . '/ztext/pdf/thumb/' . $page['image']);
                }
            } else {
                if (is_file('zselexdata/' . $shop_id . '/ztext/fullsize/' . $page['image'])) {
                    unlink('zselexdata/' . $shop_id . '/ztext/fullsize/' . $page['image']);
                }
                if (is_file('zselexdata/' . $shop_id . '/ztext/medium/' . $page['image'])) {
                    unlink('zselexdata/' . $shop_id . '/ztext/medium/' . $page['image']);
                }
                if (is_file('zselexdata/' . $shop_id . '/ztext/thumb/' . $page['image'])) {
                    unlink('zselexdata/' . $shop_id . '/ztext/thumb/' . $page['image']);
                }
            }
            AjaxUtil::output(array('success' => 1));
        }
    }

    function getTextIdByImage() {
        $image = FormUtil::getPassedValue('image', null, 'REQUEST');
        $file_part = pathinfo($image);
        if ($file_part['extension'] == 'pdf') {
            $where = array('a.doc' => $image);
        } else {
            $where = array('a.image' => $image);
        }
        $repo = $this->entityManager->getRepository('ZTEXT_Entity_Page');
        $page = $repo->get(array('entity' => 'ZTEXT_Entity_Page',
            'fields' => array('a.text_id'), 'where' => $where));
        AjaxUtil::output(array('text_id' => $page['text_id']));
    }

    function pagesLeft() {
        $shop_id = FormUtil::getPassedValue('shop_id', null, 'REQUEST');
        //$repo = $this->entityManager->getRepository('ZTEXT_Entity_Page');
        $view = Zikula_View::getInstance($this->name);
        $page_service_args = array(
            'entity' => 'ZSELEX_Entity_ServiceShop',
            'fields' => array('a.quantity', 'a.availed'),
            'where' => 'a.shop=:shop_id AND a.type=:type',
            'setParams' => array('shop_id' => $shop_id, 'type' => 'pages'),
                //'orderby' => 'a.shop_event_id ASC'
        );
        $page_service = $this->entityManager->getRepository('ZSELEX_Entity_ServiceShop')->fetch($page_service_args);
        $pages_left = $page_service['quantity'] - $page_service['availed'];
        $view->assign('pages_left', $pages_left);
        // $count = count($pages_left);
        $diskquota_service = ModUtil::apiFunc('ZSELEX', 'admin', 'checkDiskquota', array(
                    'shop_id' => $shop_id
        ));
        // echo "<pre>"; print_r($diskquota_service);  echo "</pre>"; exit;
        $view->assign('diskquota_service', $diskquota_service);
        $image_args = array(
            'shop_id' => $shop_id,
            'type' => 'minisiteimages',
            'disablecheck' => true
        );
        $image_service = ModUtil::apiFunc('ZSELEX', 'admin', 'servicePermission', $image_args);
        $page_limit = 1;
        if ($diskquota_service['error'] || $image_service['expired']) {
            $page_limit = 0;
        }
        $view->assign('page_limit', $page_limit);
        $data = '';
        $output_page = $view->fetch('admin/pages_left.tpl');
        $data .= new Zikula_Response_Ajax_Plain($output_page);
        $output["data"] = $data;
        $output["count"] = $pages_left;
        AjaxUtil::output($output);
    }

    function disablePageIndex() {
        //echo "<pre>"; print_r($_REQUEST); echo "</pre>"; exit;
        $shop_id = FormUtil::getPassedValue('shop_id', 0, 'REQUEST');
        $value = FormUtil::getPassedValue('value', 0, 'REQUEST');
        $image_disabled = FormUtil::getPassedValue('image_disabled', 0, 'REQUEST');
        $repo = $this->entityManager->getRepository('ZTEXT_Entity_Page');

        $count = $repo->getCount2(
                array('entity' => 'ZTEXT_Entity_PageSetting',
                    'field' => 'id',
                    'where' => "a.shop=:shop",
                    'setParams' => array('shop' => $shop_id)
        ));

        if ($count) {
            $item = array(
                'disable_page_index' => $value,
                'disable_frontend_image' => $image_disabled,
            );
            //  echo "<pre>"; print_r($item); echo "</pre>"; exit;
            $result = $repo->updateEntity(null, 'ZTEXT_Entity_PageSetting', $item, array('a.shop' => $shop_id));
        } else {
            $page = new ZTEXT_Entity_PageSetting();
            $shop = $this->entityManager->find('ZSELEX_Entity_Shop', $shop_id);
            $page->setShop($shop);
            $page->setDisable_page_index($value);
            $page->setDisable_frontend_image($image_disabled);
            $this->entityManager->persist($page);
            $this->entityManager->flush();
            $result = $page->getId();
        }
        if ($result) {
            $output['success'] = 1;
        } else {
            $output['success'] = 0;
        }
        AjaxUtil::output($output);
    }

    public function deleteExtraPageService($args) {
        // error_reporting('E_ALL');
        $repo = $this->entityManager->getRepository('ZTEXT_Entity_Page');
        try {

            //  $arra = array();
            $shop_id = $_REQUEST['shop_id'];

            $servicecheck = $repo->get(
                    array(
                        'entity' => 'ZSELEX_Entity_ServiceShop',
                        'fields' => array('a.availed', 'a.quantity'),
                        'where' => array('a.shop' => $shop_id, 'a.type' => 'pages')
            ));

            //echo "<pre>"; print_r($servicecheck);  echo "</pre>"; exit;
            if ($servicecheck['availed'] > $servicecheck['quantity']) {
                $service_used_extra = $servicecheck['availed'] - $servicecheck['quantity'];
                $original_used_extra = $servicecheck['availed'] - $service_used_extra;
                //echo $original_used_extra;

                $service_extra = $repo->getAll(
                        array(
                            'entity' => 'ZTEXT_Entity_Page',
                            //'fields' => array('a.shop_event_id', 'a.event_doc', 'a.event_image'),
                            'where' => array('a.shop' => $shop_id),
                            'startlimit' => 0,
                            'offset' => $service_used_extra,
                            'orderby' => 'a.text_id DESC'
                ));

                // echo "<pre>"; print_r($service_extra);  echo "</pre>"; exit;

                foreach ($service_extra as $extra_item) {
                    @unlink('zselexdata/' . $shop_id . '/ztext/fullsize/' . $extra_item['image']);
                    @unlink('zselexdata/' . $shop_id . '/ztext/medium/' . $extra_item['image']);
                    @unlink('zselexdata/' . $shop_id . '/ztext/thumb/' . $extra_item['image']);

                    $repo->deleteEntity(null, 'ZTEXT_Entity_Page', array('a.text_id' => $extra_item['text_id']));

                    //echo $extra_item['pdf_name'] . '<br>';
                }

                $update_service = $repo->updateEntity(null, 'ZSELEX_Entity_ServiceShop', array('availed' => $original_used_extra), array('a.shop' => $shop_id, 'a.type' => 'pages')
                );
            }
            return true;
        } catch (\Exception $e) {
            // echo 'Message: ' . $e->getMessage() . '<br>';
            // echo $query->getSQL();
            //exit;
            //error_log("Error : " . $e->getMessage(), 3, "/var/www/zselex/modules/ZSELEX/errors.log");
        }
    }

}
