<?php
function smarty_function_eventimage($args, &$smarty) {
	
	// echo "hello";
	$amount = $args ['amount'];
	
	$shop_id = $args ['shop_id'];
	$id = $args ['id'];
	$from = $args ['from'];
	$shoptype = $args ['shoptype'];
	
	$imagepath = '';
	$path = '';
	$image = '';
	$image_exist = 1;
	$zencart = '';
	// echo $shop_id; exit;
	
	if ($from == 'product') {
		if ($shoptype == 'iSHOP') {
			$sql = "SELECT prd_image FROM zselex_products WHERE product_id=$id";
			$query = DBUtil::executeSQL ( $sql );
			$res = $query->fetch ();
			$shopId = $shop_id;
			
			$image = $res ['prd_image'];
			$imagepath = pnGetBaseURL () . "zselexdata/$shop_id/products/thumb/$image";
			if (! empty ( $image ) || file_exists ( $imagepath )) {
				$imagepath = pnGetBaseURL () . "zselexdata/$shop_id/products/thumb/$image";
				$path = pnGetBaseURL () . "zselexdata/$shop_id/products/thumb";
			} else {
				// $imagepath = "<img src=" . pnGetBaseURL() . "images/imageBlank.png>";
				$imagepath = pnGetBaseURL () . "images/imageBlank.png";
				$image_exist = 0;
			}
		} elseif ($shoptype == 'zSHOP') {
			$zenargs = array (
					'table' => 'zselex_zenshop',
					'fields' => array (
							"*" 
					),
					'where' => array (
							"shop_id=$shop_id" 
					) 
			);
			$zencart = ModUtil::apiFunc ( 'ZSELEX', 'user', 'selectRow', $zenargs );
			// $this->view->assign('zencart', $zencart);
			$zenproduct = ModUtil::apiFunc ( 'ZSELEX', 'admin', 'getZenCartProduct', array (
					'shop' => $zencart,
					'shop_id' => $shop_id,
					'product_id' => $id 
			) );
			$image = $zenproduct ['products_image'];
			$imagepath = "http://" . $zencart ['domain'] . "/images/" . $zenproduct ['products_image'];
			$path = "http://" . $zencart ['domain'] . "/images";
			// echo "<pre>"; print_r($zencart); echo "</pre>";
			// echo "<pre>"; print_r($zenproduct); echo "</pre>"; exit;
		}
	} elseif ($from == 'article') {
		$modvars = ModUtil::getVar ( 'News' );
		$picupload_uploaddir = $modvars ['picupload_uploaddir'];
		$sid = $id;
		$imagepath = pnGetBaseURL () . $picupload_uploaddir . "/pic_sid" . $sid . "-0-thumb.jpg";
		$image = "pic_sid" . $sid . "-0-thumb.jpg";
		$path = pnGetBaseURL () . $picupload_uploaddir;
		if (file_exists ( $imagepath )) {
			$imagepath = pnGetBaseURL () . $picupload_uploaddir . "/pic_sid" . $sid . "-0-thumb.jpg";
		} else {
			// $imagepath = "<img src=" . pnGetBaseURL() . "images/imageBlank.png>";
			$imagepath = pnGetBaseURL () . "images/imageBlank.png";
			$image_exist = 0;
		}
	}
	// echo $imagepath; exit;
	$smarty->assign ( "eventfullpath", $imagepath );
	$smarty->assign ( "path", $path );
	$smarty->assign ( "eventimage", $image );
	$smarty->assign ( "zencart", $zencart );
	$smarty->assign ( "image_exist", $image_exist );
	// return $imagepath;
}