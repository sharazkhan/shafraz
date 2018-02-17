<?php

/**
 * Copyright ACTA-IT 2015 - ZTEXT
 *
 *
 * @license GNU/LGPLv3 (or at your option, any later version).
 */

/**
 * Class to control Admin interface
 */
class ZTEXT_Api_Admin extends Zikula_AbstractApi {

    /**
     * Get available admin panel links
     *
     * @return array array of admin links
     */
    public function getlinks() {
        // Define an empty array to hold the list of admin links
        $links = array();

        // Check the users permissions to each avaiable action within the admin panel
        // and populate the links array if the user has permission
        if (SecurityUtil::checkPermission('ZTEXT::', '::', ACCESS_ADMIN)) {
            $links[] = array(
                'url' => ModUtil::url('ZTEXT', 'admin', 'modifyconfig'),
                'text' => $this->__('Settings'),
                'class' => 'z-icon-es-config');
        }
        if (SecurityUtil::checkPermission('ZTEXT::', '::', ACCESS_ADMIN)) {
            $links[] = array(
                'url' => ModUtil::url('ZTEXT', 'admin', 'info'),
                'text' => $this->__('Module Information'),
                'class' => 'z-icon-es-info');
        }

        // Return the links array back to the calling function
        return $links;
    }

    public function createPage($args) {
        $page = $this->entityManager->getRepository('ZTEXT_Entity_Page')
                ->createPageDnd($args);
        return $page;
    }

    /*
     * Generate PDF thumb image
     * 
     * @uses ImageMagick
     * @param string $args['destination']
     * 
     * @return string thumb image name
     */

    public function generatePagePdfImage($args) {

        // echo "<pre>";  print_r($args); echo "</pre>"; exit;

        try {
            $destination = $args['destination'];
            $pdfDirectory = $destination;
            // $thumbDirectory = "zselexdata/shoppdf/thumb/";
            //$destination1 = substr($pdfDirectory, 0, -4);
            $thumbDirectory = $destination . '/thumb/';
            $mediumDirectory = $destination . '/medium/';

            $modvariable = ModUtil::getVar('ZSELEX');

            $medWidth = !empty($modvariable['medimagewidth']) ? $modvariable['medimagewidth'] : 400;
            $medHeight = !empty($modvariable['medimageheight']) ? $modvariable['medimageheight'] : 400;


            $thumbWidth = !empty($modvariable['thumbimagewidth']) ? $modvariable['thumbimagewidth'] : 150;
            $thumbHeight = !empty($modvariable['thumbimageheight']) ? $modvariable['thumbimageheight'] : 150;

            if (!file_exists($thumbDirectory) && !empty($thumbDirectory)) {
                mkdir($thumbDirectory, 0775, true);
                chmod($thumbDirectory, 0775);
            }

            if (!file_exists($mediumDirectory) && !empty($mediumDirectory)) {
                mkdir($mediumDirectory, 0775, true);
                chmod($mediumDirectory, 0775);
            }
            if (!file_exists($destination . '/tmp/')) {
                mkdir($destination . '/tmp/', 0775, true);
                chmod($destination . '/tmp/', 0775);
            }
            //print_r($file);  exit;
            // echo $file['newName']; exit;
            // $name = $file['name'];
            $name = $args['filename'];
            //exit;
            //Check file extension

            $allowedExtensions = array(
                'pdf'
            );
            $ex = end(explode(".", $name));
            if (!in_array($ex, $allowedExtensions)) {
                return LogUtil::registerError($this->__f('Error! Invalid file type: %1$s', $ex));
            }
            //Check file size
            if ($size >= 16000000) {
                return LogUtil::registerError($this->__('Error! Your file is too big. The limit is 14 MB.'));
            }
            $newNme = $args['filename'];

            //echo $newNme; exit;

            $thumb = basename($newNme, ".pdf");
            //$thumb = preg_replace("/[^A-Za-z0-9_-]/", "", $thumb) . ".pdf";
            $thumb = preg_replace("/[^A-Za-z0-9_-]/", "", $thumb);
            //echo $thumb; exit;
            //$code = self::doUploadFile($file, $destination);
            //the path to the PDF file
            $pdfWithPath = $pdfDirectory . '/' . $newNme;
            //echo $pdfWithPath;  exit;
            //add the desired extension to the thumbnail
            //$time = time();
            $thumb = $thumb . ".jpg";

            //echo $thumb; exit;
            //echo $thumbDirectory.$thumb; exit;

            $finalPath = $thumbDirectory . $thumb;
            $finalPath2 = $mediumDirectory . $thumb;
            //exec("convert \"{$pdfWithPath}[0]\" -colorspace RGB -geometry 120 $finalPath");
            //   if ($_SERVER['SERVER_NAME'] == 'localhost') { // only for localhost
            // exec("convert -define jpeg:size=60x60 \"{$pdfWithPath}[0]\" -colorspace RGB -thumbnail 100x150 -gravity center -crop 100x150+0+0 +repage $finalPath");
            //exec("convert -define jpeg:size=60x60 \"{$pdfWithPath}[0]\" -colorspace RGB -thumbnail 500x500 -gravity center -crop 500x500+0+0 +repage $finalPath2");
            //return basename($newNme, '.pdf') . '.jpg';
            exec("convert -density 200  \"{$pdfWithPath}[0]\" -append -resize 150x150 -background white -flatten $finalPath");
            exec("convert -density 200  \"{$pdfWithPath}[0]\" -append -resize 400x400 -background white -flatten $finalPath2");
            //exec("convert -density 300 \"{$pdfWithPath}[0]\"  -resize 400x400 -normalize -units PixelsPerInch -quality 100 -background white -flatten $finalPath2");
        } catch (Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";
        }
        return basename($newNme, '.pdf') . '.jpg';
    }

    public function generatePagePdfImage_old($args) {

        // echo "<pre>";  print_r($args); echo "</pre>"; exit;

        try {
            $destination = $args['destination'];
            $pdfDirectory = $destination;
            // $thumbDirectory = "zselexdata/shoppdf/thumb/";
            //$destination1 = substr($pdfDirectory, 0, -4);
            $thumbDirectory = $destination . '/thumb/';
            $mediumDirectory = $destination . '/medium/';

            $modvariable = ModUtil::getVar('ZSELEX');

            $medWidth = !empty($modvariable['medimagewidth']) ? $modvariable['medimagewidth'] : 400;
            $medHeight = !empty($modvariable['medimageheight']) ? $modvariable['medimageheight'] : 400;


            $thumbWidth = !empty($modvariable['thumbimagewidth']) ? $modvariable['thumbimagewidth'] : 150;
            $thumbHeight = !empty($modvariable['thumbimageheight']) ? $modvariable['thumbimageheight'] : 150;

            if (!file_exists($thumbDirectory) && !empty($thumbDirectory)) {
                mkdir($thumbDirectory, 0775, true);
                chmod($thumbDirectory, 0775);
            }

            if (!file_exists($mediumDirectory) && !empty($mediumDirectory)) {
                mkdir($mediumDirectory, 0775, true);
                chmod($mediumDirectory, 0775);
            }
            if (!file_exists($destination . '/tmp/')) {
                mkdir($destination . '/tmp/', 0775, true);
                chmod($destination . '/tmp/', 0775);
            }
            //print_r($file);  exit;
            // echo $file['newName']; exit;
            // $name = $file['name'];
            $name = $args['filename'];
            //exit;
            //Check file extension

            $allowedExtensions = array(
                'pdf'
            );
            $ex = end(explode(".", $name));
            if (!in_array($ex, $allowedExtensions)) {
                return LogUtil::registerError($this->__f('Error! Invalid file type: %1$s', $ex));
            }
            //Check file size
            if ($size >= 16000000) {
                return LogUtil::registerError($this->__('Error! Your file is too big. The limit is 14 MB.'));
            }
            $newNme = $args['filename'];

            //echo $newNme; exit;

            $thumb = basename($newNme, ".pdf");
            //$thumb = preg_replace("/[^A-Za-z0-9_-]/", "", $thumb) . ".pdf";
            $thumb = preg_replace("/[^A-Za-z0-9_-]/", "", $thumb);
            //echo $thumb; exit;
            //$code = self::doUploadFile($file, $destination);
            //the path to the PDF file
            $pdfWithPath = $pdfDirectory . '/' . $newNme;
            //echo $pdfWithPath;  exit;
            //add the desired extension to the thumbnail
            //$time = time();
            $thumb = $thumb . ".jpg";

            //echo $thumb; exit;
            //echo $thumbDirectory.$thumb; exit;

            $finalPath = $thumbDirectory . $thumb;
            $finalPath2 = $mediumDirectory . $thumb;
            //exec("convert \"{$pdfWithPath}[0]\" -colorspace RGB -geometry 120 $finalPath");
            //   if ($_SERVER['SERVER_NAME'] == 'localhost') { // only for localhost
            exec("convert -define jpeg:size=60x60 \"{$pdfWithPath}[0]\" -colorspace RGB -thumbnail 100x150 -gravity center -crop 100x150+0+0 +repage $finalPath");
            exec("convert -define jpeg:size=60x60 \"{$pdfWithPath}[0]\" -colorspace RGB -thumbnail 500x500 -gravity center -crop 500x500+0+0 +repage $finalPath2");
            return basename($newNme, '.pdf') . '.jpg';
            //  }
            // KIMENEMARK BEGIN
            $pdfpage = 1;
            // $basepath = $_SERVER['DOCUMENT_ROOT'] . '/' . $destination . '/';
            $basepath = $destination . '/';
            //echo $basepath; exit;
            $pdf_name = $basepath . $newNme;
            $jpgname = $basepath . 'thumb/' . basename($newNme, '.pdf') . '.jpg';
            $gsjpgname = $basepath . 'tmp/' . basename($newNme, '.pdf') . '.jpg';

            $jpgname2 = $basepath . 'medium/' . basename($newNme, '.pdf') . '.jpg';
            $gsjpgname2 = $basepath . 'tmp/' . basename($newNme, '.pdf') . '.jpg';

            // $gscommand = '/usr/bin/gs -sDEVICE=jpeg -sCompression=lzw -r300x300  -dNOPAUSE -dFirstPage=' . $pdfpage . ' -dLastPage=' . $pdfpage . ' -sOutputFile="' . $gsjpgname . '" ' . $pdf_name;
            //$command = '/usr/bin/convert -define jpeg:size=60x60 ' . $gsjpgname . ' -colorspace RGB -thumbnail 100x150 -gravity center -crop 100x150+0+0 ' . $jpgname;
            $gscommand = '/usr/bin/gs -sDEVICE=jpeg -sCompression=lzw -r300x300  -dNOPAUSE -dFirstPage=' . $pdfpage . ' -dLastPage=' . $pdfpage . ' -sOutputFile="' . $gsjpgname . '" ' . $pdf_name;
            $command = '/usr/bin/convert -define jpeg:size=60x60 ' . $gsjpgname . ' -colorspace RGB -thumbnail ' . $thumbWidth . 'x' . $thumbHeight . ' -gravity center -crop 100x150+0+0 ' . $jpgname;
            exec($gscommand);
            exec($command);
            unlink($gsjpgname);


            $gscommand2 = '/usr/bin/gs -sDEVICE=jpeg -sCompression=lzw -r300x300  -dNOPAUSE -dFirstPage=' . $pdfpage . ' -dLastPage=' . $pdfpage . ' -sOutputFile="' . $gsjpgname2 . '" ' . $pdf_name;
            $command2 = '/usr/bin/convert -define jpeg:size=600x600 ' . $gsjpgname2 . ' -colorspace RGB -thumbnail ' . $medWidth . 'x' . $medHeight . ' -gravity center -crop 600x600+0+0 ' . $jpgname2;
            exec($gscommand2);
            exec($command2);
            unlink($gsjpgname2);

            //echo "$basePath\n\n";
            //echo "$pdfDirectory\n\n";
            //echo "$gsjpgname\n\n";
            //echo "$gscommand\n\n";
            //echo "$command\n\n";
            //exit;
            // exec($gscommand);
            // exec($command);
            // unlink($gsjpgname);
            // KIMENEMARK END
        } catch (Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";
        }
        return basename($newNme, '.pdf') . '.jpg';
    }

}

// end class def