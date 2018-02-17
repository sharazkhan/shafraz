<?php
class ZSELEX_Controller_Dnd extends Zikula_Controller_AbstractAjax {
	public $uploader;
	public $FINISH_FUNCTION;
	public function initialize() {
		require_once ('modules/ZSELEX/lib/vendor/DND/upload.php');
		$DENY_EXT = array (
				'php',
				'php3',
				'php4',
				'php5',
				'phtml',
				'exe',
				'pl',
				'cgi',
				'html',
				'htm',
				'js',
				'asp',
				'aspx',
				'bat',
				'sh',
				'cmd' 
		);
		$this->uploader = new RealAjaxUploader ( $DENY_EXT ); // create uploader object
	}
	public function upload_products($args) {
		$fileName = FormUtil::getPassedValue ( 'ax-file-name', null, 'REQUEST' );
		$fileSize = FormUtil::getPassedValue ( 'ax-file-size', null, 'REQUEST' );
		
		$shop_id = $_REQUEST ['shop_id'];
		$category_id = FormUtil::getPassedValue ( 'category_id', null, 'REQUEST' );
		
		$uploadPath = FormUtil::getPassedValue ( 'ax-file-path', null, 'REQUEST' );
		if (! file_exists ( $uploadPath ) && ! empty ( $uploadPath )) {
			mkdir ( $uploadPath, 0775, true );
			chmod ( $uploadPath, 0775 );
		}
		
		$image_upload = $uploadPath;
		if (! file_exists ( $image_upload ) && ! empty ( $image_upload )) {
			mkdir ( $image_upload, 0775, true );
			chmod ( $image_upload, 0775 );
		}
		
		$fullsize = $image_upload . "/fullsize/";
		if (! file_exists ( $fullsize ) && ! empty ( $fullsize )) {
			mkdir ( $fullsize, 0775, true );
			chmod ( $fullsize, 0775 );
		}
		
		$medium = $image_upload . "/medium/";
		if (! file_exists ( $medium ) && ! empty ( $medium )) {
			mkdir ( $medium, 0775, true );
			chmod ( $medium, 0775 );
		}
		
		$thumb = $image_upload . "/thumb/";
		if (! file_exists ( $thumb ) && ! empty ( $thumb )) {
			mkdir ( $thumb, 0775, true );
			chmod ( $thumb, 0775 );
		}
		
		// register a callback function on file complete
		$this->FINISH_FUNCTION = "product_success";
		// $UPLOAD_PATH = "zselex";
		if (isset ( $this->FINISH_FUNCTION ) && $this->FINISH_FUNCTION)
			$this->uploader->onFinish ( $this->FINISH_FUNCTION ); // set name of external function to be called on finish upload
		
		if (isset ( $_REQUEST ['ax-check-file'] )) {
			$this->uploader->header ();
			echo $this->uploader->_checkFileExists () ? 'yes' : 'no';
		} elseif (isset ( $_REQUEST ['ax-delete-file'] ) && $ALLOW_DELETE) {
			$this->uploader->header ();
			echo $this->uploader->deleteFile ();
		} else {
			// $this->uploader->uploadFile();
			$this->uploader->uploadFile ( $shop_id, $fileSize, 'diskQuoataCheck' );
		}
	}
	public function upload_images() {
		$fileName = FormUtil::getPassedValue ( 'ax-file-name', null, 'REQUEST' );
		$fileSize = FormUtil::getPassedValue ( 'ax-file-size', null, 'REQUEST' );
		
		$shop_id = $_REQUEST ['shop_id'];
		// $shop_id = '';
		if (ZSELEX_Controller_Admin::shopPermission ( $shop_id ) < 1) {
			$this->uploader->customError ( 1, $this->__ ( 'Cannot upload image!' ) );
		}
		// $this->uploader->customError(1, 'Sample Error!');
		$uploadPath = FormUtil::getPassedValue ( 'ax-file-path', null, 'REQUEST' );
		if (! file_exists ( $uploadPath ) && ! empty ( $uploadPath )) {
			mkdir ( $uploadPath, 0775, true );
			chmod ( $uploadPath, 0775 );
		}
		
		$image_upload = $uploadPath;
		if (! file_exists ( $image_upload ) && ! empty ( $image_upload )) {
			mkdir ( $image_upload, 0775, true );
			chmod ( $image_upload, 0775 );
		}
		
		$fullsize = $image_upload . "/fullsize/";
		if (! file_exists ( $fullsize ) && ! empty ( $fullsize )) {
			mkdir ( $fullsize, 0775, true );
			chmod ( $fullsize, 0775 );
		}
		
		$medium = $image_upload . "/medium/";
		if (! file_exists ( $medium ) && ! empty ( $medium )) {
			mkdir ( $medium, 0775, true );
			chmod ( $medium, 0775 );
		}
		
		$thumb = $image_upload . "/thumb/";
		if (! file_exists ( $thumb ) && ! empty ( $thumb )) {
			mkdir ( $thumb, 0775, true );
			chmod ( $thumb, 0775 );
		}
		
		// register a callback function on file complete
		$this->FINISH_FUNCTION = "image_success";
		// $UPLOAD_PATH = "zselex";
		if (isset ( $this->FINISH_FUNCTION ) && $this->FINISH_FUNCTION)
			$this->uploader->onFinish ( $this->FINISH_FUNCTION ); // set name of external function to be called on finish upload
		
		if (isset ( $_REQUEST ['ax-check-file'] )) {
			$this->uploader->header ();
			echo $this->uploader->_checkFileExists () ? 'yes' : 'no';
		} elseif (isset ( $_REQUEST ['ax-delete-file'] ) && $ALLOW_DELETE) {
			$this->uploader->header ();
			echo $this->uploader->deleteFile ();
		} else {
			// $this->uploader->uploadFile();
			$this->uploader->uploadFile ( $shop_id, $fileSize, 'diskQuoataCheck' );
		}
	}
	public function upload_employees() {
		$fileName = FormUtil::getPassedValue ( 'ax-file-name', null, 'REQUEST' );
		$fileSize = FormUtil::getPassedValue ( 'ax-file-size', null, 'REQUEST' );
		
		$shop_id = $_REQUEST ['shop_id'];
		$uploadPath = FormUtil::getPassedValue ( 'ax-file-path', null, 'REQUEST' );
		if (! file_exists ( $uploadPath ) && ! empty ( $uploadPath )) {
			mkdir ( $uploadPath, 0775, true );
			chmod ( $uploadPath, 0775 );
		}
		
		$image_upload = $uploadPath;
		if (! file_exists ( $image_upload ) && ! empty ( $image_upload )) {
			mkdir ( $image_upload, 0775, true );
			chmod ( $image_upload, 0775 );
		}
		
		$fullsize = $image_upload . "/fullsize/";
		if (! file_exists ( $fullsize ) && ! empty ( $fullsize )) {
			mkdir ( $fullsize, 0775, true );
			chmod ( $fullsize, 0775 );
		}
		
		$medium = $image_upload . "/medium/";
		if (! file_exists ( $medium ) && ! empty ( $medium )) {
			mkdir ( $medium, 0775, true );
			chmod ( $medium, 0775 );
		}
		
		$thumb = $image_upload . "/thumb/";
		if (! file_exists ( $thumb ) && ! empty ( $thumb )) {
			mkdir ( $thumb, 0775, true );
			chmod ( $thumb, 0775 );
		}
		
		// register a callback function on file complete
		$this->FINISH_FUNCTION = "employee_success";
		// $UPLOAD_PATH = "zselex";
		if (isset ( $this->FINISH_FUNCTION ) && $this->FINISH_FUNCTION)
			$this->uploader->onFinish ( $this->FINISH_FUNCTION ); // set name of external function to be called on finish upload
		
		if (isset ( $_REQUEST ['ax-check-file'] )) {
			$this->uploader->header ();
			echo $this->uploader->_checkFileExists () ? 'yes' : 'no';
		} elseif (isset ( $_REQUEST ['ax-delete-file'] ) && $ALLOW_DELETE) {
			$this->uploader->header ();
			echo $this->uploader->deleteFile ();
		} else {
			// $this->uploader->uploadFile();
			$this->uploader->uploadFile ( $shop_id, $fileSize, 'diskQuoataCheck' );
		}
	}
	public function upload_banner() {
		$fileName = FormUtil::getPassedValue ( 'ax-file-name', null, 'REQUEST' );
		$fileSize = FormUtil::getPassedValue ( 'ax-file-size', null, 'REQUEST' );
		
		$shop_id = $_REQUEST ['shop_id'];
		$uploadPath = FormUtil::getPassedValue ( 'ax-file-path', null, 'REQUEST' );
		if (! file_exists ( $uploadPath ) && ! empty ( $uploadPath )) {
			mkdir ( $uploadPath, 0775, true );
			chmod ( $uploadPath, 0775 );
		}
		
		$image_upload = $uploadPath;
		if (! file_exists ( $image_upload ) && ! empty ( $image_upload )) {
			mkdir ( $image_upload, 0775, true );
			chmod ( $image_upload, 0775 );
		}
		
		$medium = $image_upload . "/resized/";
		if (! file_exists ( $medium ) && ! empty ( $medium )) {
			mkdir ( $medium, 0775, true );
			chmod ( $medium, 0775 );
		}
		
		// register a callback function on file complete
		$this->FINISH_FUNCTION = "banner_success";
		// $UPLOAD_PATH = "zselex";
		if (isset ( $this->FINISH_FUNCTION ) && $this->FINISH_FUNCTION)
			$this->uploader->onFinish ( $this->FINISH_FUNCTION ); // set name of external function to be called on finish upload
		
		if (isset ( $_REQUEST ['ax-check-file'] )) {
			$this->uploader->header ();
			echo $this->uploader->_checkFileExists () ? 'yes' : 'no';
		} elseif (isset ( $_REQUEST ['ax-delete-file'] ) && $ALLOW_DELETE) {
			$this->uploader->header ();
			echo $this->uploader->deleteFile ();
		} else {
			// $this->uploader->uploadFile();
			$this->uploader->uploadFile ( $shop_id, $fileSize, 'diskQuoataCheck' );
		}
	}
	public function upload_event() {
		
		// $this->uploader->message(-1, 'Diskquota exceeded');die();
		$fileName = FormUtil::getPassedValue ( 'ax-file-name', null, 'REQUEST' );
		$fileSize = FormUtil::getPassedValue ( 'ax-file-size', null, 'REQUEST' );
		$pupose = FormUtil::getPassedValue ( 'purpose', null, 'REQUEST' );
		$shop_id = $_REQUEST ['shop_id'];
		$ex = end ( explode ( ".", $fileName ) );
		$allowedExtensions = array (
				'png',
				'jpg',
				'gif',
				'jpeg',
				'JPEG',
				'JPG',
				'PNG',
				'GIF' 
		);
		$uploadPath = FormUtil::getPassedValue ( 'ax-file-path', null, 'REQUEST' );
		if (! file_exists ( $uploadPath ) && ! empty ( $uploadPath )) {
			mkdir ( $uploadPath, 0775, true );
			chmod ( $uploadPath, 0775 );
		}
		
		$image_upload = $uploadPath;
		if (! file_exists ( $image_upload ) && ! empty ( $image_upload )) {
			mkdir ( $image_upload, 0775, true );
			chmod ( $image_upload, 0775 );
		}
		
		$docs = $image_upload . "/docs/";
		if (! file_exists ( $docs ) && ! empty ( $docs )) {
			mkdir ( $docs, 0775, true );
			chmod ( $docs, 0775 );
		}
		
		$fullsize = $image_upload . "/fullsize/";
		if (! file_exists ( $fullsize ) && ! empty ( $fullsize )) {
			mkdir ( $fullsize, 0775, true );
			chmod ( $fullsize, 0775 );
		}
		
		$medium = $image_upload . "/medium/";
		if (! file_exists ( $medium ) && ! empty ( $medium )) {
			mkdir ( $medium, 0775, true );
			chmod ( $medium, 0775 );
		}
		
		$thumb = $image_upload . "/thumb/";
		if (! file_exists ( $thumb ) && ! empty ( $thumb )) {
			mkdir ( $thumb, 0775, true );
			chmod ( $thumb, 0775 );
		}
		
		// register a callback function on file complete
		if ($pupose == 'create') {
			$this->FINISH_FUNCTION = "event_success_new";
		} elseif ($pupose == 'edit') {
			$this->FINISH_FUNCTION = "event_success_edit";
		}
		
		// $UPLOAD_PATH = "zselexdata/keeprunning/events/docs";
		// $uploader->setUploadPath($UPLOAD_PATH);
		if (! in_array ( $ex, $allowedExtensions )) {
			$ownerName = ModUtil::apiFunc ( 'ZSELEX', 'admin', 'getOwner', $args = array (
					'shop_id' => $shop_id 
			) );
			$UPLOAD_PATH = "zselexdata/$shop_id/events/docs/";
			$this->uploader->setUploadPath ( $UPLOAD_PATH );
		}
		// $this->uploader->message(-1, 'File move error');
		// if (isset($UPLOAD_PATH) && $UPLOAD_PATH)
		// $this->uploader->setMaxFileSize($max_file_size = '0M');customError
		// $this->uploader->customError(1, 'custom error');
		if (isset ( $this->FINISH_FUNCTION ) && $this->FINISH_FUNCTION)
			$this->uploader->onFinish ( $this->FINISH_FUNCTION ); // set name of external function to be called on finish upload
		
		if (isset ( $_REQUEST ['ax-check-file'] )) {
			$this->uploader->header ();
			echo $this->uploader->_checkFileExists () ? 'yes' : 'no';
		} elseif (isset ( $_REQUEST ['ax-delete-file'] ) && $ALLOW_DELETE) {
			$this->uploader->header ();
			echo $this->uploader->deleteFile ();
		} else {
			// $this->uploader->uploadFile();
			if ($pupose == 'create') {
				$this->uploader->uploadFile ( $shop_id, $fileSize, 'diskQuoataCheck' );
			} else {
				$this->uploader->uploadFile ();
			}
		}
	}
	function deleteExtraEmployees() {
		$shop_id = $_REQUEST ['shop_id'];
		ModUtil::apiFunc ( 'ZSELEX', 'service', 'deleteExtraEmployeeServices', array (
				'shop_id' => $shop_id 
		) );
		return true;
	}
	function deleteExtraImages() {
		$shop_id = $_REQUEST ['shop_id'];
		ModUtil::apiFunc ( 'ZSELEX', 'service', 'deleteExtraImageService', array (
				'shop_id' => $shop_id 
		) );
		return true;
	}
	function deleteExtraEvents() {
		$shop_id = $_REQUEST ['shop_id'];
		ModUtil::apiFunc ( 'ZSELEX', 'service', 'deleteExtraEventService', array (
				'shop_id' => $shop_id 
		) );
		return true;
	}
	function deleteExtraProducts() {
		$shop_id = $_REQUEST ['shop_id'];
		$Run = ModUtil::apiFunc ( 'ZSELEX', 'service', 'deleteExtraProductService', array (
				'shop_id' => $shop_id 
		) );
		shell_exec ( "/usr/bin/php" . " " . $Run );
		return true;
	}
}

?>