<?php

class ZTEXT_Api_DndCallBack extends ZTEXT_Api_Admin {

    public function page_success1($destination, $file_name, $params) {
        //echo "helloo"; exit;
        try {

            $em = ServiceUtil::getService('doctrine.entitymanager');
            $repo = $em->getRepository('ZTEXT_Entity_Page');
            // $view = Zikula_View::getInstance('ZTEXT');
            //$this->uploadImages3($file_name, "zselexdata/keeprunning/products/");
            // echo "Testing"; exit;
            // $category_id = $_REQUEST['category_id'];
            $resizeImages = ModUtil::apiFunc('ZSELEX', 'upload', 'full_medium_thumb', $args = array('filename' => $file_name, 'destination' => $destination));

            $data = unserialize($params);
            $text_id = $data['text_id'];
            $shop_id = $data['shop_id'];
            $purpose = $data['purpose'];
            $file_orig_name = $data['file_orig_name'];
            $path_parts = pathinfo($file_name);
            $header_text = $path_parts['filename'];
            if ($purpose == 'edit') {
                $item = array(
                    //'headertext'=>$header_text,
                    'image' => $file_name,
                );

                $result = $repo->updateEntity(null, 'ZTEXT_Entity_Page', $item, array('a.text_id' => $text_id));
            } else {
                $create_args = array('shop_id' => $shop_id, 'image' => $file_name, 'headertext' => '');
                // $result = $repo->createPageDnd($create_args);
                $result = ModUtil::apiFunc('ZTEXT', 'admin', 'createPage', $create_args);
                // echo "<script>alert($result)</script>";
                if ($result) {
                    $serviceupdatearg = array(
                        'user_id' => UserUtil::getVar('uid'),
                        'type' => 'pages',
                        'shop_id' => $shop_id
                    );
                    $serviceavailed = ModUtil::apiFunc('ZSELEX', 'admin', 'updateServiceUsed', $serviceupdatearg);
                }
            }
        } catch (\Exception $e) {

            // error_log($e->getMessage(), 3, "/var/www/test/logs.log");
            echo $e->getMessage();
            exit;
        }
    }

    public function page_success($destination, $file_name, $params) {
        //echo "helloo"; exit;
        try {

            $em = ServiceUtil::getService('doctrine.entitymanager');
            $repo = $em->getRepository('ZTEXT_Entity_Page');
            $path_parts = pathinfo($file_name);
            $data = unserialize($params);
            $text_id = $data['text_id'];
            $shop_id = $data['shop_id'];
            $purpose = $data['purpose'];
            $file_orig = pathinfo($data['file_orig_name']);
            $file_orig_name = $file_orig['filename'];
            // $view = Zikula_View::getInstance('ZTEXT');
            //$this->uploadImages3($file_name, "zselexdata/keeprunning/products/");
            // echo "Testing"; exit;
            // $category_id = $_REQUEST['category_id'];
            $pdf_image = '';
            if ($path_parts['extension'] == 'pdf') {
                //die();
                $pdf_destination = "zselexdata/$shop_id/ztext/pdf";
                //$pdf_image = $this->generatePagePdfImage(array('filename' => $file_name, 'destination' => $pdf_destination));
                $pdf_image = ModUtil::apiFunc('ZTEXT', 'admin', 'generatePagePdfImage', array('filename' => $file_name, 'destination' => $pdf_destination));
            } else {
                $resizeImages = ModUtil::apiFunc('ZSELEX', 'upload', 'full_medium_thumb', $args = array('filename' => $file_name, 'destination' => $destination));
            }

            //$path_parts = pathinfo($file_name);
            $header_text = $path_parts['filename'];
            if ($purpose == 'edit') {

                $item = array(
                    //'headertext'=>$header_text,
                    'image' => ($path_parts['extension'] == 'pdf') ? $pdf_image : $file_name,
                    // 'image' => $file_name,
                    'extension' => $path_parts['extension'],
                    'doc' => ($path_parts['extension'] == 'pdf') ? $file_name : '',
                ); //

                $result = $repo->updateEntity(null, 'ZTEXT_Entity_Page', $item, array('a.text_id' => $text_id));
            } else {
                $create_args = array('shop_id' => $shop_id, 'image' => $file_name, 'extension' => $path_parts['extension'], 'pdf_image' => $pdf_image, 'headertext' => $file_orig_name);
                // $result = $repo->createPageDnd($create_args);
                $result = ModUtil::apiFunc('ZTEXT', 'admin', 'createPage', $create_args);
                // echo "<script>alert($result)</script>";
                if ($result) {
                    $serviceupdatearg = array(
                        'user_id' => UserUtil::getVar('uid'),
                        'type' => 'pages',
                        'shop_id' => $shop_id
                    );
                    $serviceavailed = ModUtil::apiFunc('ZSELEX', 'admin', 'updateServiceUsed', $serviceupdatearg);
                }
            }
        } catch (\Exception $e) {

            // error_log($e->getMessage(), 3, "/var/www/test/logs.log");
            echo $e->getMessage();
            exit;
        }
    }

}

?>