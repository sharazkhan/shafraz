<?php

/**
 * Class to control Admin interface
 */
class ZTEXT_Controller_Dnd extends Zikula_AbstractController { // edited

    public $uploader;
    public $FINISH_FUNCTION;

    /**
     * @desc set caching to false for all admin functions
     * @return      null
     */
    public function postInitialize() {
        $this->view->setCaching(false);
        require_once ('modules/ZTEXT/lib/vendor/dnd/upload.php');
        $DENY_EXT = array('php', 'php3', 'php4', 'php5', 'phtml', 'exe', 'pl', 'cgi', 'html', 'htm', 'js', 'asp', 'aspx', 'bat', 'sh', 'cmd');
        $this->uploader = new RealAjaxUploader($DENY_EXT);  //create uploader object
    }

    public function uploadPageImage() {
        $uploadPath = FormUtil::getPassedValue('ax-file-path', null, 'REQUEST');
        $fileName = FormUtil::getPassedValue('ax-file-name', null, 'REQUEST');
        $shop_id = FormUtil::getPassedValue('shop_id', null, 'REQUEST');
        $text_id = FormUtil::getPassedValue('text_id', null, 'REQUEST');
        $purpose = FormUtil::getPassedValue('purpose', null, 'REQUEST');
        $file_info = pathinfo($fileName);


        $repo = ServiceUtil::getService('doctrine.entitymanager')->getRepository('ZTEXT_Entity_Page');

        if (!file_exists($uploadPath) && !empty($uploadPath)) {
            mkdir($uploadPath, 0775, true);
            chmod($uploadPath, 0775);
        }

        $image_upload = $uploadPath;
        if (!file_exists($image_upload) && !empty($image_upload)) {
            mkdir($image_upload, 0775, true);
            chmod($image_upload, 0775);
        }

        $fullsize = $image_upload . "/fullsize/";
        if (!file_exists($fullsize) && !empty($fullsize)) {
            mkdir($fullsize, 0775, true);
            chmod($fullsize, 0775);
        }


        $medium = $image_upload . "/medium/";
        if (!file_exists($medium) && !empty($medium)) {
            mkdir($medium, 0775, true);
            chmod($medium, 0775);
        }



        $thumb = $image_upload . "/thumb/";
        if (!file_exists($thumb) && !empty($thumb)) {
            mkdir($thumb, 0775, true);
            chmod($thumb, 0775);
        }


        if ($purpose == 'edit') {
            $page = $repo->get(array(
                'entity' => 'ZTEXT_Entity_Page',
                'fields' => array('a.image', 'a.doc'),
                'where' => array('a.text_id' => $text_id)));
            if (is_file('zselexdata/' . $shop_id . '/ztext/fullsize/' . $page['image'])) {
                unlink('zselexdata/' . $shop_id . '/ztext/fullsize/' . $page['image']);
            }
            if (is_file('zselexdata/' . $shop_id . '/ztext/medium/' . $page['image'])) {
                unlink('zselexdata/' . $shop_id . '/ztext/medium/' . $page['image']);
            }
            if (is_file('zselexdata/' . $shop_id . '/ztext/thumb/' . $page['image'])) {
                unlink('zselexdata/' . $shop_id . '/ztext/thumb/' . $page['image']);
            }

            if (is_file('zselexdata/' . $shop_id . '/ztext/pdf/' . $page['doc'])) {
                @unlink('zselexdata/' . $shop_id . '/ztext/pdf/' . $page['doc']);
                $pdf_parts = @pathinfo($page['doc']);
                 @unlink('zselexdata/' . $shop_id . '/ztext/pdf/medium/' . $pdf_parts['filename'] . '.jpg');
                 @unlink('zselexdata/' . $shop_id . '/ztext/pdf/thumb/' . $pdf_parts['filename'] . '.jpg');
            }
        }


        /* $uploader = new RealAjaxUploader($DENY_EXT);  //create uploader object
          if (isset($MAX_FILES_SIZE) && $MAX_FILES_SIZE)
          $uploader->setMaxFileSize($MAX_FILES_SIZE);
          if (isset($ALLOW_EXTENSIONS) && $ALLOW_EXTENSIONS)
          $uploader->setAllowExt($ALLOW_EXTENSIONS);
          if (isset($UPLOAD_PATH) && $UPLOAD_PATH)
          $uploader->setUploadPath($UPLOAD_PATH); */

        if ($file_info['extension'] == 'pdf') {

            $UPLOAD_PATH = "zselexdata/$shop_id/ztext/pdf/";
            if (!file_exists($UPLOAD_PATH) && !empty($UPLOAD_PATH)) {
                mkdir($UPLOAD_PATH, 0775, true);
                chmod($UPLOAD_PATH, 0775);
            }
            $this->uploader->setUploadPath($UPLOAD_PATH);
        }


//register a callback function on file complete
        $FINISH_FUNCTION = 'page_success';
        if (isset($FINISH_FUNCTION) && $FINISH_FUNCTION)
            $this->uploader->onFinish($FINISH_FUNCTION); //set name of external function to be called on finish upload





            
//check request, this check if file already exits only, depends from javascript part requests
        if (isset($_REQUEST['ax-check-file'])) {
            $this->uploader->header();
            echo $this->uploader->_checkFileExists() ? 'yes' : 'no';
        } elseif (isset($_REQUEST['ax-delete-file']) && $ALLOW_DELETE) {
            $this->uploader->header();
            echo $this->uploader->deleteFile();
        } else {
            $this->uploader->uploadFile();
        }
    }

}

// end class def