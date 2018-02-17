<?php
class ZSELEX_Api_DndCallBack extends ZSELEX_Api_Admin {
	public function product_success($destination, $file_name, $file_orig_name, $shop_id = 0, $product_cat_id, $manufacturer_id) {
		try {
			$view = Zikula_View::getInstance ( 'ZSELEX' );
			// $this->uploadImages3($file_name, "zselexdata/keeprunning/products/");
			// echo "Testing"; exit;
			$category_id = $_REQUEST ['category_id'];
			$resizeImages = ModUtil::apiFunc ( 'ZSELEX', 'upload', 'full_medium_thumb', $args = array (
					'filename' => $file_name,
					'destination' => $destination 
			) );
			$path_parts = pathinfo ( $file_name );
			// $image_name = $path_parts['filename'];
			$image_name = $file_orig_name;
			$user_id = UserUtil::getVar ( 'uid' );
			
			$urltitle = strtolower ( $image_name );
			// $urltitle = str_replace(" ", '-', $urltitle);
			$urltitle = ZSELEX_Util::cleanTitle ( $urltitle );
			$args_url = array (
					'table' => 'zselex_products',
					'title' => $urltitle,
					'field' => 'urltitle' 
			);
			$final_urltitle = ZSELEX_Controller_Admin::increment_url ( $args_url );
			
			$item = array (
					'shop_id' => $shop_id,
					'user_id' => $user_id,
					'product_name' => $image_name,
					'urltitle' => $final_urltitle,
					'prd_image' => $file_name,
					'original_price' => 0,
					'prd_price' => 0,
					'prd_status' => 1 
			);
			if ($product_cat_id > 0) {
				// error_log($product_cat_id, 3, "/var/www/test/logs.log");
				$item ['category_id'] = $product_cat_id;
			}
			if ($manufacturer_id > 0) {
				$item ['manufacturer_id'] = $manufacturer_id;
			}
			
			/*
			 * $args = array(
			 * 'table' => 'zselex_products',
			 * 'element' => $item,
			 * 'Id' => 'product_id'
			 * );
			 * $result = ModUtil::apiFunc('ZSELEX', 'admin', 'createElement', $args);
			 */
			
			$result = ModUtil::apiFunc ( 'ZSELEX', 'product', 'createProduct', $item );
			// $result = $this->entityManager->getRepository('ZSELEX_Entity_Product')->createProduct($item);
			// error_log('TESTINGGGGGG\n', 3, "/var/www/test/logs.log");
			// $result = true;
			// error_log($result . ' , ' . $product_cat_id, 3, "/var/www/test/logs.log");
			
			if ($product_cat_id > 0) {
				$categories = array ();
				$categories [] = $product_cat_id;
				$saveCategories = ModUtil::apiFunc ( 'ZSELEX', 'product', 'saveProductCategories', array (
						'product_id' => $result,
						'categories' => $categories 
				) );
			}
			
			if ($result > 0) {
				
				$serviceupdatearg = array (
						'user_id' => $user_id,
						'type' => 'addproducts',
						'shop_id' => $shop_id 
				);
				$serviceavailed = ModUtil::apiFunc ( 'ZSELEX', 'admin', 'updateServiceUsed', $serviceupdatearg );
			}
			return true;
		} catch ( \Exception $e ) {
			
			error_log ( $e->getMessage (), 3, "/var/www/test/logs.log" );
			echo $e->getMessage ();
			exit ();
		}
	}
	public function paymentsEnabled($shop_id) {
		$em = ServiceUtil::getService ( 'doctrine.entitymanager' );
		$netaxept = $em->getRepository ( 'ZPayment_Entity_Netaxept' )->getNetaxept ( array (
				'shop_id' => $shop_id 
		) );
		$paypal = $em->getRepository ( 'ZPayment_Entity_PaypalSetting' )->getPaypal ( array (
				'shop_id' => $shop_id 
		) );
		$directpay = $em->getRepository ( 'ZPayment_Entity_DirectpaySetting' )->getDirectpay ( array (
				'shop_id' => $shop_id 
		) );
		$nopayment = $em->getRepository ( 'ZSELEX_Entity_ShopSetting' )->getNopayment ( array (
				'shop_id' => $shop_id 
		) );
		
		$enabled = $netaxept ['enabled'] + $paypal ['enabled'] + $directpay ['enabled'] + $nopayment ['no_payment'];
		return $enabled;
		// return true;
	}
	public function image_success($destination, $file_name, $file_orig_name, $shop_id = 0) {
		$resizeImages = ModUtil::apiFunc ( 'ZSELEX', 'upload', 'full_medium_thumb', $args = array (
				'filename' => $file_name,
				'destination' => $destination 
		) );
		$user_id = UserUtil::getVar ( 'uid' );
		// $image_name = $file_orig_name;
		
		$item = array (
				'name' => $file_name,
				'shop_id' => $shop_id,
				'user_id' => $user_id,
				// 'display' => 1,
				'dispname' => $file_name,
				'status' => 1 
		);
		/*
		 * $args = array(
		 * 'table' => 'zselex_files',
		 * 'element' => $item,
		 * 'Id' => 'pdf_id'
		 * );
		 * $result = ModUtil::apiFunc('ZSELEX', 'admin', 'createElement', $args);
		 */
		$result = ModUtil::apiFunc ( 'ZSELEX', 'shopsetting', 'createImageDnd', $item );
		if ($result) {
			$serviceupdatearg = array (
					'user_id' => $user_id,
					'type' => 'minisiteimages',
					'shop_id' => $shop_id 
			);
			$serviceavailed = ModUtil::apiFunc ( 'ZSELEX', 'admin', 'updateServiceUsed', $serviceupdatearg );
		}
		return true;
	}
	public function employee_success($destination, $file_name, $file_orig_name, $shop_id = 0) {
		$resizeImages = ModUtil::apiFunc ( 'ZSELEX', 'upload', 'full_medium_thumb', $args = array (
				'filename' => $file_name,
				'destination' => $destination 
		) );
		$user_id = UserUtil::getVar ( 'uid' );
		$path_parts = pathinfo ( $file_name );
		// $image_name = $path_parts['filename'];
		$image_name = $file_orig_name;
		
		$item = array (
				'shop_id' => $shop_id,
				'name' => $image_name,
				'emp_image' => $file_name,
				'status' => 1 
		);
		
		$args = array (
				'table' => 'zselex_shop_employees',
				'element' => $item,
				'Id' => 'emp_id' 
		);
		// $result = ModUtil::apiFunc('ZSELEX', 'admin', 'createElement', $args);
		$result = ModUtil::apiFunc ( 'ZSELEX', 'shopsetting', 'createEmployeeDnd', $item );
		if ($result) {
			$serviceupdatearg = array (
					'user_id' => $user_id,
					'type' => 'employees',
					'shop_id' => $shop_id 
			);
			$serviceavailed = ModUtil::apiFunc ( 'ZSELEX', 'admin', 'updateServiceUsed', $serviceupdatearg );
		}
		// ModUtil::apiFunc('ZSELEX', 'service', 'deleteExtraEmployeeServices', array('shop_id' => $shop_id));
		return true;
	}
	public function banner_success($destination, $file_name, $file_orig_name, $shop_id = 0) {
		
		// $resizeImages = ModUtil::apiFunc('ZSELEX', 'upload', 'full_medium_thumb', $args = array('filename' => $file_name, 'destination' => $destination));
		$user_id = UserUtil::getVar ( 'uid' );
		$ownerName = ModUtil::apiFunc ( 'ZSELEX', 'admin', 'getOwner', $args = array (
				'shop_id' => $shop_id 
		) );
		$path_parts = pathinfo ( $file_name );
		// $image_name = $path_parts['filename'];
		$image_name = $file_orig_name;
		
		$args_del_extra_banner = array (
				'ownername' => $ownerName,
				'shop_id' => $shop_id 
		);
		ZSELEX_Controller_Ajax::deleteExtraBanner ( $args_del_extra_banner );
		
		$upload_banner = ZSELEX_Controller_Ajax::uploadBanner ( $file_name, $destination, $shop_id ); // resize banner
		
		list ( $width, $height, $type, $attr ) = @getimagesize ( pnGetBaseURL () . "zselexdata/" . $shop_id . "/banner/resized/" . str_replace ( " ", "%20", $file_name ) );
		
		if ($upload_banner) {
			$item = array (
					'shop_id' => $shop_id,
					'banner_image' => $file_name,
					'height' => $height,
					'width' => $width 
			);
			
			$args = array (
					'table' => 'zselex_shop_banner',
					'element' => $item,
					'Id' => 'shop_banner_id' 
			);
			// $result = ModUtil::apiFunc('ZSELEX', 'admin', 'createElement', $args);
			$result = ModUtil::apiFunc ( 'ZSELEX', 'shopsetting', 'createBannerDnd', $item );
			if ($result) {
				$serviceupdatearg = array (
						'type' => 'minisitebanner',
						'shop_id' => $shop_id 
				);
				
				$serviceavailed = ModUtil::apiFunc ( 'ZSELEX', 'admin', 'updateServiceUsed', $serviceupdatearg );
				// $args_del_extra_banner = array('ownername' => $ownerName, 'shop_id' => $shop_id);
				// ZSELEX_Controller_Ajax::deleteExtraBanner($args_del_extra_banner);
			}
		}
		return true;
	}
	public function event_success_new($destination, $file_name, $file_orig_name, $shop_id = 0) {
		$user_id = UserUtil::getVar ( 'uid' );
		$path_parts = pathinfo ( $file_name );
		// $image_name = $path_parts['filename'];
		$image_name = $file_orig_name;
		
		$ex = end ( explode ( ".", $file_name ) );
		
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
		
		if (in_array ( $ex, $allowedExtensions )) {
			$resizeImages = ModUtil::apiFunc ( 'ZSELEX', 'upload', 'uploadEventImage', $args = array (
					'filename' => $file_name,
					'destination' => $destination 
			) );
		}
		
		$height = '';
		$width = '';
		
		if (in_array ( $ex, $allowedExtensions )) {
			$showfrom = 'image';
			/*
			 * $ownerName = ModUtil::apiFunc('ZSELEX', 'admin', 'getOwner', $args = array(
			 * 'shop_id' => $shop_id
			 * ));
			 */
			list ( $width, $height, $type, $attr ) = @getimagesize ( pnGetBaseURL () . "zselexdata/" . $shop_id . "/events/fullsize/" . str_replace ( " ", "%20", $file_name ) );
			$image_spec = 'image';
			$image_spec_serial = serialize ( array (
					'height' => $height,
					'width' => $width 
			) );
		} elseif (! in_array ( $ex, $allowedExtensions )) {
			/*
			 * $ownerName = ModUtil::apiFunc('ZSELEX', 'admin', 'getOwner', $args = array(
			 * 'shop_id' => $shop_id
			 * ));
			 */
			$showfrom = 'doc';
			$pdf_destination = 'zselexdata/' . $shop_id . '/events/docs';
			$generateThumb = ModUtil::apiFunc ( 'ZSELEX', 'upload', 'generateEventPdfImage', array (
					'filename' => $file_name,
					'destination' => $pdf_destination 
			) );
		}
		
		$urltitle = strtolower ( $image_name );
		$urltitle = ZSELEX_Util::cleanTitle ( $urltitle );
		
		$args_url = array (
				'table' => 'zselex_shop_events',
				'title' => $urltitle,
				'field' => 'event_urltitle' 
		);
		$final_urltitle = ZSELEX_Controller_Admin::increment_url ( $args_url );
		
		$item = array (
				'shop_id' => $shop_id,
				'shop_event_name' => $image_name,
				'event_urltitle' => $final_urltitle,
				'event_image' => in_array ( $ex, $allowedExtensions ) ? $file_name : '',
				'event_image' => in_array ( $ex, $allowedExtensions ) ? $file_name : '',
				'image_height' => in_array ( $ex, $allowedExtensions ) ? $height : '',
				'image_width' => in_array ( $ex, $allowedExtensions ) ? $width : '',
				'event_doc' => ! in_array ( $ex, $allowedExtensions ) ? $file_name : '',
				'showfrom' => $showfrom,
				'status' => 1 
		);
		
		$create_args = array (
				'table' => 'zselex_shop_events',
				'element' => $item,
				'Id' => 'shop_event_id' 
		);
		// Create the event
		// $result = ModUtil::apiFunc('ZSELEX', 'admin', 'createElement', $create_args);
		$result = ModUtil::apiFunc ( 'ZSELEX', 'shopsetting', 'createEventDnd', $item );
		
		if ($result) {
			$ownerName = ModUtil::apiFunc ( 'ZSELEX', 'admin', 'getOwner', $args = array (
					'shop_id' => $shop_id 
			) );
			
			$serviceupdatearg = array (
					'user_id' => $user_id,
					'type' => 'eventservice',
					'shop_id' => $shop_id 
			);
			$serviceavailed = ModUtil::apiFunc ( 'ZSELEX', 'admin', 'updateServiceUsed', $serviceupdatearg );
			
			// $args_del_extra_employee = array('ownername' => $ownerName, 'shop_id' => $shop_id);
			// ZSELEX_Controller_Admin::deleteExtraEmployeeServices($args_del_extra_employee);
		}
		return true;
	}
	public function event_success_edit($destination, $file_name, $file_orig_name, $shop_id = 0) {
		$em = ServiceUtil::getService ( 'doctrine.entitymanager' );
		$repo = $em->getRepository ( 'ZSELEX_Entity_Event' );
		$user_id = UserUtil::getVar ( 'uid' );
		$path_parts = pathinfo ( $file_name );
		// $image_name = $path_parts['filename'];
		$image_name = $file_orig_name;
		$event_id = $_REQUEST ['event_id'];
		
		$ex = end ( explode ( ".", $file_name ) );
		
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
		
		if (in_array ( $ex, $allowedExtensions )) {
			$resizeImages = ModUtil::apiFunc ( 'ZSELEX', 'upload', 'uploadEventImage', $args = array (
					'filename' => $file_name,
					'destination' => $destination 
			) );
		}
		
		if (in_array ( $ex, $allowedExtensions )) {
			$showfrom = 'image';
			/*
			 * $ownerName = ModUtil::apiFunc('ZSELEX', 'admin', 'getOwner', $args = array(
			 * 'shop_id' => $shop_id
			 * ));
			 */
			list ( $width, $height, $type, $attr ) = @getimagesize ( pnGetBaseURL () . "zselexdata/" . $shop_id . "/events/fullsize/" . str_replace ( " ", "%20", $file_name ) );
		} elseif (! in_array ( $ex, $allowedExtensions )) {
			$showfrom = 'doc';
			/*
			 * $ownerName = ModUtil::apiFunc('ZSELEX', 'admin', 'getOwner', $args = array(
			 * 'shop_id' => $shop_id
			 * ));
			 */
			$pdf_destination = 'zselexdata/' . $shop_id . '/events/docs';
			// error_log($pdf_destination, 3, "/var/www/test/errors.log");
			$generateThumb = ModUtil::apiFunc ( 'ZSELEX', 'upload', 'generateEventPdfImage', array (
					'filename' => $file_name,
					'destination' => $pdf_destination 
			) );
		}
		
		$event_item = ModUtil::apiFunc ( 'ZSELEX', 'user', 'get', $getargs = array (
				'table' => 'zselex_shop_events',
				'where' => "shop_event_id=$event_id" 
		) );
		
		// $urltitle = strtolower($image_name);
		// $urltitle = ZSELEX_Util::cleanTitle($urltitle);
		// $args_url = array('table' => 'zselex_shop_events', 'title' => $urltitle, 'field' => 'event_urltitle');
		// $final_urltitle = ZSELEX_Controller_Admin::increment_url($args_url);
		
		$item = array (
				'shop_event_id' => $event_id,
				'shop' => $shop_id,
				// 'shop_event_name' => $image_name,
				// 'event_urltitle' => $final_urltitle,
				'event_image' => in_array ( $ex, $allowedExtensions ) ? $file_name : '',
				'image_height' => in_array ( $ex, $allowedExtensions ) ? $height : '',
				'image_width' => in_array ( $ex, $allowedExtensions ) ? $width : '',
				'event_doc' => ! in_array ( $ex, $allowedExtensions ) ? $file_name : '',
				'showfrom' => $showfrom,
				'status' => 1 
		);
		
		$updateargs = array (
				'table' => 'zselex_shop_events',
				'IdValue' => $event_id,
				'IdName' => 'shop_event_id',
				'element' => $item 
		);
		
		// $result = ModUtil::apiFunc('ZSELEX', 'admin', 'updateElement', $updateargs);
		$result = ModUtil::apiFunc ( 'ZSELEX', 'shopsetting', 'updateEventDnd', array (
				'event_id' => $event_id,
				'items' => $item 
		) );
		
		if ($result) {
			/*
			 * $ownerName = ModUtil::apiFunc('ZSELEX', 'admin', 'getOwner', $args = array(
			 * 'shop_id' => $shop_id
			 * ));
			 */
			$deleteEventTmp = $repo->deleteEntity ( null, 'ZSELEX_Entity_EventTemp', array (
					'a.event' => $event_id 
			) );
			if ($deleteEventTmp) {
				$dateRange = ZSELEX_Util::createDateRangeArray ( $event_item ['shop_event_startdate'], $event_item ['shop_event_enddate'] );
				$setEventTemp = $repo->updateEventTemp ( array (
						'shop_id' => $shop_id,
						'event_id' => $event_id,
						'dates' => $dateRange 
				) );
			}
			
			if ($file_name != $event_item ['event_image']) {
				if (is_file ( 'zselexdata/' . $shop_id . '/events/fullsize/' . $event_item ['event_image'] )) {
					unlink ( 'zselexdata/' . $shop_id . '/events/fullsize/' . $event_item ['event_image'] );
				}
				if (is_file ( 'zselexdata/' . $shop_id . '/events/medium/' . $event_item ['event_image'] )) {
					unlink ( 'zselexdata/' . $shop_id . '/events/medium/' . $event_item ['event_image'] );
				}
				if (is_file ( 'zselexdata/' . $shop_id . '/events/thumb/' . $event_item ['event_image'] )) {
					unlink ( 'zselexdata/' . $shop_id . '/events/thumb/' . $event_item ['event_image'] );
				}
			}
			if ($file_name != $event_item ['event_doc']) {
				if (is_file ( 'zselexdata/' . $shop_id . '/events/docs/' . $event_item ['event_doc'] )) {
					unlink ( 'zselexdata/' . $shop_id . '/events/docs/' . $event_item ['event_doc'] );
					$file_parts = pathinfo ( $event_item ['event_doc'] );
					if ($file_parts ['extension'] == 'pdf') {
						unlink ( 'zselexdata/' . $shop_id . '/events/docs/thumb/' . $file_parts ['filename'] . '.jpg' );
						unlink ( 'zselexdata/' . $shop_id . '/events/docs/medium/' . $file_parts ['filename'] . '.jpg' );
					}
				}
			}
			
			// $args_del_extra_employee = array('ownername' => $ownerName, 'shop_id' => $shop_id);
			// ZSELEX_Controller_Admin::deleteExtraEmployeeServices($args_del_extra_employee);
		}
		return true;
	}
}

?>