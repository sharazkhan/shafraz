<?php
class ZSELEX_Api_Product extends ZSELEX_Api_Admin {
	public function copyProduct($args) {
		$productRepo = $this->entityManager->getRepository ( 'ZSELEX_Entity_Product' );
		
		$new_product_id = $args ['new_product_id'];
		$shop_id = $args ['shop_id'];
		$curr_product_id = $args ['curr_product_id'];
		
		$product = $this->entityManager->getRepository ( 'ZSELEX_Entity_Product' )->getProduct ( array (
				'product_id' => $new_product_id 
		) );
		// echo "<pre>"; print_r($product); echo "</pre>"; exit;
		
		$urltitle = strtolower ( $product ['product_name'] );
		$urltitle = ZSELEX_Util::cleanTitle ( $urltitle );
		
		/*
		 * $urlCount = ModUtil::apiFunc('ZSELEX', 'admin', 'getCount', $args = array(
		 * 'table' => 'zselex_products',
		 * 'where' => "urltitle='" . $urltitle . "' AND product_id!=$curr_product_id"
		 * ));
		 */
		
		$urlCount = $productRepo->getCount2 ( array (
				'entity' => 'ZSELEX_Entity_Product',
				'field' => 'product_id',
				'where' => "a.urltitle=:urltitle AND a.product_id!=:curr_product_id",
				'setParams' => array (
						'urltitle' => $urltitle,
						'curr_product_id' => $curr_product_id 
				) 
		) );
		
		if ($urlCount > 0) {
			$args_url = array (
					'table' => 'zselex_products',
					'title' => $urltitle,
					'field' => 'urltitle' 
			);
			$final_urltitle = ZSELEX_Controller_Admin::increment_url ( $args_url );
		} else {
			$final_urltitle = $urltitle;
		}
		$item = array (
				'product_id' => $curr_product_id,
				'shop' => $product ['shop_id'],
				'product_name' => $product ['product_name'],
				'urltitle' => $final_urltitle,
				'prd_description' => $product ['prd_description'],
				'manufacturer' => $product ['manufacturer_id'],
				'keywords' => $product ['keywords'],
				'original_price' => $product ['original_price'],
				'prd_price' => $product ['prd_price'],
				'discount' => $product ['discount'],
				'prd_quantity' => $product ['prd_quantity'],
				'prd_quantity' => $product ['prd_quantity'] 
		);
		// echo "<pre>"; print_r($item); echo "</pre>"; exit;
		/*
		 * $updateargs = array(
		 * 'table' => 'zselex_products',
		 * 'IdValue' => $curr_product_id,
		 * 'IdName' => 'product_id',
		 * 'element' => $item
		 * );
		 *
		 * $result = ModUtil::apiFunc('ZSELEX', 'admin', 'updateElement', $updateargs);
		 */
		
		$result = $productRepo->updateEntity ( null, 'ZSELEX_Entity_Product', $item, array (
				'a.product_id' => $curr_product_id 
		) );
		
		// DBUtil::deleteWhere('zselex_product_to_category', $where = "product_id=$curr_product_id");
		/*
		 * $getCategories = ModUtil::apiFunc('ZSELEX', 'user', 'getAll', array(
		 * 'table' => 'zselex_product_to_category',
		 * 'where' => "product_id=$new_product_id"
		 * ));
		 */
		
		$productRepo->deleteProductCategories ( $curr_product_id );
		
		$getCategories = $productRepo->getAll ( array (
				'entity' => 'ZSELEX_Entity_Product',
				'where' => array (
						'a.product_id' => $new_product_id 
				),
				'joins' => array (
						'JOIN a.product_to_category b' 
				),
				'fields' => array (
						'b.prd_cat_id' 
				) 
		) );
		
		/*
		 * foreach ($getCategories as $key => $val) {
		 * $sql = "INSERT INTO zselex_product_to_category (product_id , prd_cat_id) VALUES('" . $curr_product_id . "' , '" . $val['category_id'] . "')";
		 * DBUtil::executeSQL($sql);
		 * }
		 */
		$categories = array ();
		foreach ( $getCategories as $key => $val ) {
			$categories [] = $val ['prd_cat_id'];
		}
		
		$productRepo->addProductCategories ( $curr_product_id, $categories );
		
		// DBUtil::deleteWhere('zselex_product_to_options', $where = "product_id=$curr_product_id");
		$productRepo->deleteEntity ( null, 'ZSELEX_Entity_ProductToOption', array (
				'a.product' => $curr_product_id 
		) );
		// DBUtil::deleteWhere('zselex_product_to_options_values', $where = "product_id=$curr_product_id");
		$productRepo->deleteEntity ( null, 'ZSELEX_Entity_ProductToOptionValue', array (
				'a.product' => $curr_product_id 
		) );
		
		/*
		 * $getProductToOptions = ModUtil::apiFunc('ZSELEX', 'user', 'getAll', array(
		 * 'table' => 'zselex_product_to_options',
		 * 'where' => "product_id=$new_product_id"
		 * ));
		 */
		
		$getProductToOptions = $productRepo->getAll ( array (
				'entity' => 'ZSELEX_Entity_ProductToOption',
				'where' => array (
						'a.product' => $new_product_id 
				),
				'fields' => array (
						'a.product_to_options_id',
						'b.option_id',
						'a.parent_option_id' 
				),
				'joins' => array (
						'JOIN a.option b' 
				) 
		) );
		
		// echo "<pre>"; print_r($getProductToOptions); echo "</pre><br>"; exit;
		foreach ( $getProductToOptions as $key => $val ) {
			/*
			 * $obj = array(
			 * 'product_id' => $curr_product_id,
			 * 'option_id' => $val['option_id'],
			 * 'parent_option_id' => $val['parent_option_id']
			 * );
			 *
			 * // echo "<pre>"; print_r($obj2); echo "</pre><br>";
			 * $result = DBUtil::insertObject($obj, 'zselex_product_to_options');
			 * $last_product_to_options_id = DBUtil::getInsertID('zselex_product_to_options', 'product_to_options_id');
			 */
			
			// echo $parent_option_id; exit;
			$productObj = $this->entityManager->find ( 'ZSELEX_Entity_Product', $curr_product_id );
			$optionObj = $this->entityManager->find ( 'ZSELEX_Entity_ProductOption', $val ['option_id'] );
			
			$prodToOpt = new ZSELEX_Entity_ProductToOption ();
			$prodToOpt->setProduct ( $productObj );
			$prodToOpt->setOption ( $optionObj );
			$prodToOpt->setParent_option_id ( $val ['parent_option_id'] );
			$this->entityManager->persist ( $prodToOpt );
			$this->entityManager->flush ();
			$last_product_to_options_id = $prodToOpt->getProduct_to_options_id ();
			
			/*
			 * $getProductToOptionsValues = ModUtil::apiFunc('ZSELEX', 'user', 'getAll', array(
			 * 'table' => 'zselex_product_to_options_values',
			 * 'where' => "product_to_options_id=$val[product_to_options_id]"
			 * ));
			 */
			
			$getProductToOptionsValues = $productRepo->getAll ( array (
					'entity' => 'ZSELEX_Entity_ProductToOptionValue',
					'where' => array (
							'a.product_to_option' => $val ['product_to_options_id'] 
					),
					'fields' => array (
							'b.option_id',
							'a.parent_option_id',
							'c.option_value_id',
							'a.parent_option_value_id',
							'a.qty',
							'a.price' 
					),
					'joins' => array (
							'JOIN a.option b',
							'JOIN a.option_value_id c' 
					) 
			) );
			foreach ( $getProductToOptionsValues as $key1 => $val1 ) {
				/*
				 * $obj2 = array(
				 * 'product_to_options_id' => $last_product_to_options_id,
				 * 'product_id' => $curr_product_id,
				 * 'option_id' => $val1['option_id'],
				 * 'parent_option_id' => $val1['parent_option_id'],
				 * 'option_value_id' => $val1['option_value_id'],
				 * 'parent_option_value_id' => $val1['parent_option_value_id'],
				 * // 'option_value' => $val1['option_value'],
				 * 'qty' => $val1['qty'],
				 * 'price' => $val1['price']
				 * );
				 * $result2 = DBUtil::insertObject($obj2, 'zselex_product_to_options_values');
				 */
				$product_to_optionObj = $this->entityManager->find ( 'ZSELEX_Entity_ProductToOption', $last_product_to_options_id );
				$productObj = $this->entityManager->find ( 'ZSELEX_Entity_Product', $curr_product_id );
				$optionObj = $this->entityManager->find ( 'ZSELEX_Entity_ProductOption', $val1 ['option_id'] );
				$optionToValueId = $this->entityManager->find ( 'ZSELEX_Entity_ProductOptionValue', $val1 ['option_value_id'] );
				
				$prodToOptVal = new ZSELEX_Entity_ProductToOptionValue ();
				$prodToOptVal->setProduct_to_option ( $product_to_optionObj );
				$prodToOptVal->setProduct ( $productObj );
				$prodToOptVal->setOption ( $optionObj );
				$prodToOptVal->setParent_option_id ( $val1 ['parent_option_id'] );
				// $prodToOptVal->setOption_value_id($val1['option_value_id']);
				$prodToOptVal->setOption_value_id ( $optionToValueId );
				$prodToOptVal->setParent_option_value_id ( $val1 ['parent_option_value_id'] );
				$prodToOptVal->setPrice ( $val1 ['price'] );
				$prodToOptVal->setQty ( $val1 ['qty'] );
				$this->entityManager->persist ( $prodToOptVal );
				$this->entityManager->flush ();
			}
		}
		
		return true;
	}
	public function saveProductCategories($args) {
		// echo "<pre>"; print_r($args); echo "</pre>"; exit;
		$productRepo = $this->entityManager->getRepository ( 'ZSELEX_Entity_Product' );
		$categories = $args ['categories'];
		$product_id = $args ['product_id'];
		if (empty ( $product_id )) {
			return false;
		}
		
		$deleteCategories = $productRepo->deleteProductCategories ( $product_id );
		$addCategories = $productRepo->addProductCategories ( $product_id, $categories );
		// echo "<pre>"; print_r($categories); echo "</pre>"; exit;
		
		/*
		 * if (!empty($categories)) {
		 * $del_cats = implode(',', $categories);
		 * $d = " AND category_id NOT IN($del_cats)";
		 * }
		 *
		 * //echo $del_cats; exit;
		 * $delsql = "DELETE FROM zselex_product_to_category WHERE product_id=$product_id" . $d;
		 * $delete = DBUtil::executeSQL($delsql, '', '', false);
		 * if (!empty($categories)) {
		 * foreach ($categories as $key => $val) {
		 * // echo $val; exit;
		 * $count = ModUtil::apiFunc('ZSELEX', 'admin', 'getCount', array(
		 * 'table' => 'zselex_product_to_category',
		 * "where" => "product_id=$product_id AND category_id=$val"
		 * ));
		 *
		 * if (!$count) {
		 * $sql = "INSERT INTO zselex_product_to_category(product_id,category_id)VALUES('$product_id','$val')";
		 * $result = DBUtil::executeSQL($sql, '', '', false);
		 * }
		 * }
		 * }
		 */
		
		return true;
	}
	function price_filter($array, $array2) {
		// echo "<pre>"; print_r($array); echo "</pre>";
		// echo "<pre>"; print_r($array2); echo "</pre>";
		// exit;
		$i = 0;
		foreach ( $array as $key => $val ) {
			
			if ($key != $array2 [$i]) {
				unset ( $array [$key] );
			}
			
			$i ++;
		}
		return $array;
	}
	public function saveProductOptions1($args) {
		// echo "<pre>"; print_r($args); echo "</pre>"; exit;
		// try {
		$options = $args ['options'];
		$product_id = $args ['product_id'];
		// echo "<pre>"; print_r($options); echo "</pre>"; exit;
		
		if (empty ( $options )) {
			DBUtil::deleteWhere ( 'zselex_product_to_options', $where = "product_id=$product_id" );
			DBUtil::deleteWhere ( 'zselex_product_to_options_values', $where = "product_id=$product_id" );
			return true;
		}
		foreach ( $options as $key => $val ) {
			
			$option_id = $val ['option_id'];
			$option_values = $val ['val']; // array
			$option_price = $val ['price']; // array
			$option_qty = $val ['qty']; // array
			                           // $option_sort_order = $val['sort_order']; //array
			$type = $val ['type'];
			$oldId = $val ['oldId'];
			$sel_options [] = $option_id;
			
			if ($type == 'new') {
				if (! empty ( $option_values )) {
					$obj = array (
							"product_id" => $product_id,
							"option_id" => $option_id 
					);
					$insert_product_to_option = DBUtil::insertObject ( $obj, 'zselex_product_to_options' );
					$product_to_options_id = DBUtil::getInsertID ( 'zselex_product_to_options', 'product_to_options_id' );
					foreach ( $option_values as $k1 => $v1 ) {
						$obj2 = array (
								"product_to_options_id" => $product_to_options_id,
								"product_id" => $product_id,
								"option_id" => $option_id,
								"option_value_id" => $v1,
								"price" => $val ['price'] [$v1],
								"qty" => $val ['qty'] [$v1] 
						)
						// "sort_order" => $val['sort_order'][$v1],
						;
						$insert_product_to_option_value = DBUtil::insertObject ( $obj2, 'zselex_product_to_options_values' );
					}
				}
			} elseif ($type == 'old') {
				
				if (empty ( $option_values )) {
					DBUtil::deleteWhere ( 'zselex_product_to_options', $where = "product_to_options_id=$oldId" );
					DBUtil::deleteWhere ( 'zselex_product_to_options_values', $where = "product_to_options_id=$oldId" );
				}
				foreach ( $option_values as $k1 => $v1 ) {
					$sel_vals [] = $v1;
					$val_count = ModUtil::apiFunc ( 'ZSELEX', 'admin', 'getCount', array (
							'table' => 'zselex_product_to_options_values',
							"where" => "product_to_options_id=$oldId AND option_value_id='" . $v1 . "'" 
					) );
					// exit;
					// echo "$value :" .$val_count;
					if ($val_count < 1) {
						$sql = "INSERT INTO zselex_product_to_options_values(product_to_options_id,product_id,option_id,option_value_id,price,qty)VALUES('$oldId','$product_id','$option_id','$v1','" . $val ['price'] [$v1] . "','" . $val ['qty'] [$v1] . "')";
						// echo $sql . '<br>';
						$insert = DBUtil::executeSQL ( $sql, '', '', false );
					} else {
						// echo "here..";
						$upd_sql = "UPDATE zselex_product_to_options_values SET option_value_id='" . $v1 . "' , price='" . $val ['price'] [$v1] . "' , qty='" . $val ['qty'] [$v1] . "' 
                                 WHERE product_to_options_id=$oldId AND option_value_id='" . $v1 . "'";
						// echo $upd_sql . '<br>';
						$upd_result = DBUtil::executeSQL ( $upd_sql, '', '', false );
					}
				}
				
				if (! empty ( $sel_vals )) {
					$del_vals = "'" . implode ( "','", $sel_vals ) . "'";
					DBUtil::deleteWhere ( 'zselex_product_to_options_values', $where = "product_to_options_id=$oldId AND option_value_id NOT IN($del_vals)" );
				}
			}
		}
		
		if (! empty ( $sel_options )) {
			$del_options = "'" . implode ( "','", $sel_options ) . "'";
			
			$getOptionIdToDel = ModUtil::apiFunc ( 'ZSELEX', 'user', 'getAll', $args = array (
					'table' => 'zselex_product_to_options',
					'where' => "option_id NOT IN($del_options) AND product_id=$product_id",
					'fields' => array (
							'product_to_options_id',
							'option_id' 
					) 
			) );
			// echo "<pre>"; print_r($getOptionIdToDel); echo "</pre>"; exit;
			if ($getOptionIdToDel) {
				foreach ( $getOptionIdToDel as $k => $v ) {
					// echo $v . '</br>';
					DBUtil::deleteWhere ( 'zselex_product_to_options', $where = "product_id=$product_id AND option_id=$v[option_id]" );
					DBUtil::deleteWhere ( 'zselex_product_to_options_values', $where = "product_id=$product_id AND product_to_options_id=$v[product_to_options_id]" );
				}
			}
		}
		
		// } catch (Exception $e) {
		// echo "MyError : " . $e->getTraceAsString(); exit;
		// }
		// exit;
		return true;
	}
	public function saveProductOptions($args) {
		// echo "<pre>"; print_r($args); echo "</pre>"; exit;
		// try {
		$options = $args ['options'];
		$product_id = $args ['product_id'];
		// echo "<pre>"; print_r($options); echo "</pre>"; exit;
		
		if (empty ( $options )) {
			// DBUtil::deleteWhere('zselex_product_to_options', $where = "product_id=$product_id");
			$setParams = array (
					'product_id' => $product_id 
			);
			$this->entityManager->getRepository ( 'ZSELEX_Entity_ProductToOption' )->deleteProductToOption ( array (
					'where' => "a.product=:product_id",
					'setParams' => $setParams 
			) );
			// DBUtil::deleteWhere('zselex_product_to_options_values', $where = "product_id=$product_id");
			$this->entityManager->getRepository ( 'ZSELEX_Entity_ProductToOption' )->deleteProductToOptionValue ( array (
					'where' => "a.product=:product_id",
					'setParams' => $setParams 
			) );
			return true;
		}
		
		// DBUtil::deleteWhere('zselex_product_to_options', $where = "product_id=$product_id");
		// DBUtil::deleteWhere('zselex_product_to_options_values', $where = "product_id=$product_id");
		foreach ( $options as $key => $val ) {
			$linked = $val ['linked'];
			// if ($linked) {
			$type = $val ['type'];
			$option_id = $val ['option_id'];
			$sel_options [] = $option_id;
			$old_product_to_option_id = $val ['oldId'];
			$parent_option_id = $val ['parent_option_id'];
			$values = $val ['val'];
			$hvalues = $val ['hval'];
			// $parentval = $val['parentval'];
			$prices = $val ['price'];
			$qtys = $val ['qty'];
			
			$PrdToOptCount = $this->entityManager->getRepository ( 'ZSELEX_Entity_ProductToOption' )->getProductToOptionCount ( array (
					'product_id' => $product_id,
					'option_id' => $option_id 
			) );
			// echo $PrdToOptCount; exit;
			if (! $PrdToOptCount) {
				$item = array (
						'product_id' => $product_id,
						'option_id' => $option_id,
						'parent_option_id' => $parent_option_id 
				);
				
				$create = $this->entityManager->getRepository ( 'ZSELEX_Entity_ProductToOption' )->createProductToOption ( $item );
				// $product_to_options_last_id = DBUtil::getInsertID('zselex_product_to_options', 'product_to_options_id');
				$product_to_options_last_id = $create;
			} else {
				$product_to_options_last_id = $old_product_to_option_id;
			}
			// return;
			// echo "<pre>"; print_r(array_keys($prices)); echo "</pre>";
			// $j = 0;
			// $ids = array_keys($prices);
			// foreach ($prices as $pk => $pv) {
			if ($linked) {
				foreach ( $hvalues as $valId ) {
					if (! empty ( $valId )) {
						// $id = $ids[$j];
						// echo $id . '<br>';
						// echo $id . '<br>';
						// foreach ($pv as $pk1 => $price) {
						// echo "<pre>"; print_r($prices[$valId]); echo "</pre>"; exit;
						foreach ( $qtys [$valId] as $pk1 => $qty ) {
							$where = " a.product=$product_id AND a.option=$option_id AND a.parent_option_id=$parent_option_id AND a.option_value_id=$valId AND a.parent_option_value_id=$pk1";
							
							$linkedCount = $this->entityManager->getRepository ( 'ZSELEX_Entity_ProductToOption' )->getLinkedCount ( array (
									'where' => $where 
							) );
							
							// echo $linkedCount; exit;
							
							if (! $linkedCount) {
								if ($qty > 0) {
									$item1 = array (
											'product_to_options_id' => $product_to_options_last_id,
											'product_id' => $product_id,
											'option_id' => $option_id,
											'parent_option_id' => $parent_option_id,
											// 'option_value_id' => $id,
											'option_value_id' => $valId,
											'parent_option_value_id' => $pk1,
											'price_prefix' => substr ( $prices [$valId] [$pk1], 0, 1 ),
											'price' => $prices [$valId] [$pk1],
											'qty' => $qty 
									);
									
									// echo "<pre>"; print_r($item1); echo "</pre>";
									
									$create_values = $this->entityManager->getRepository ( 'ZSELEX_Entity_ProductToOption' )->createProductToOptionValue ( $item1 );
									// }
								}
							} else {
								if ($qty > 0) {
									
									$upd_result = $this->entityManager->getRepository ( 'ZSELEX_Entity_ProductToOption' )->updateProductToOptionValue ( array (
											'price' => $prices [$valId] [$pk1],
											'qty' => $qty,
											'where' => $where 
									) );
								} else {
									// DBUtil::deleteWhere("zselex_product_to_options_values", "$where");
									$this->entityManager->getRepository ( 'ZSELEX_Entity_ProductToOption' )->deleteProductToOptionValueWhere ( array (
											'where' => $where 
									) );
								}
							}
						}
						$j ++;
					}
				}
				
				$count = $this->entityManager->getRepository ( 'ZSELEX_Entity_ProductToOption' )->getProductToOptionValueCount ( array (
						'product_to_options_id' => $product_to_options_last_id 
				) );
				// echo $count; exit;
				if (! $count) {
					// DBUtil::deleteWhere('zselex_product_to_options', "product_to_options_id=$product_to_options_last_id");
					$this->entityManager->getRepository ( 'ZSELEX_Entity_ProductToOption' )->deleteProductToOption2 ( array (
							'product_to_options_id' => $product_to_options_last_id 
					) );
				}
			} else { // not linked
				foreach ( $hvalues as $valId ) {
					$where2 = " a.product=$product_id AND a.option=$option_id AND a.option_value_id=$valId";
					
					$notLinkedCount = $this->entityManager->getRepository ( 'ZSELEX_Entity_ProductToOption' )->getLinkedCount ( array (
							'where' => $where2 
					) );
					
					// echo $notLinkedCount; exit;
					
					if (! $notLinkedCount) {
						if ($qtys [$valId] > 0) {
							$item2 = array (
									'product_to_options_id' => $product_to_options_last_id,
									'product_id' => $product_id,
									'option_id' => $option_id,
									'parent_option_id' => 0,
									'option_value_id' => $valId,
									'parent_option_value_id' => 0,
									'price_prefix' => substr ( $prices [$valId], 0, 1 ),
									'price' => $prices [$valId],
									'qty' => $qtys [$valId] 
							);
							
							// echo "<pre>"; print_r($item1); echo "</pre>";
							
							$create_values2 = $this->entityManager->getRepository ( 'ZSELEX_Entity_ProductToOption' )->createProductToOptionValue ( $item2 );
						}
					} else {
						
						$upd_result2 = $this->entityManager->getRepository ( 'ZSELEX_Entity_ProductToOption' )->updateProductToOptionValue ( array (
								'price' => $prices [$valId],
								'qty' => $qtys [$valId],
								'where' => $where2 
						) );
					}
				}
				
				$count = $this->entityManager->getRepository ( 'ZSELEX_Entity_ProductToOption' )->getProductToOptionValueCount ( array (
						'product_to_options_id' => $product_to_options_last_id 
				) );
				if (! $count) {
					// DBUtil::deleteWhere('zselex_product_to_options', "product_to_options_id=$product_to_options_last_id");
					$this->entityManager->getRepository ( 'ZSELEX_Entity_ProductToOption' )->deleteProductToOption2 ( array (
							'product_to_options_id' => $product_to_options_last_id 
					) );
				}
			}
		}
		
		if (! empty ( $sel_options )) {
			$del_options = "'" . implode ( "','", $sel_options ) . "'";
			
			$getOptionIdToDel = ModUtil::apiFunc ( 'ZSELEX', 'user', 'getAll', $args = array (
					'table' => 'zselex_product_to_options',
					'where' => "option_id NOT IN($del_options) AND product_id=$product_id",
					'fields' => array (
							'product_to_options_id',
							'option_id' 
					) 
			) );
			// echo "<pre>"; print_r($getOptionIdToDel); echo "</pre>"; exit;
			if ($getOptionIdToDel) {
				foreach ( $getOptionIdToDel as $k => $v ) {
					// echo $v . '</br>';
					// DBUtil::deleteWhere('zselex_product_to_options', $where = "product_id=$product_id AND option_id=$v[option_id]");
					// DBUtil::deleteWhere('zselex_product_to_options_values', $where = "product_id=$product_id AND product_to_options_id=$v[product_to_options_id]");
					$setParams2 = array (
							'product_id' => $product_id,
							'option_id' => $v ['option_id'] 
					);
					$this->entityManager->getRepository ( 'ZSELEX_Entity_ProductToOption' )->deleteProductToOption ( array (
							'where' => "a.product=:product_id AND a.option=:option_id",
							'setParams' => $setParams2 
					) );
					
					$setParams3 = array (
							'product_id' => $product_id,
							'product_to_options_id' => $v ['product_to_options_id'] 
					);
					$this->entityManager->getRepository ( 'ZSELEX_Entity_ProductToOption' )->deleteProductToOptionValue ( array (
							'where' => "a.product=:product_id AND a.product_to_option=:product_to_options_id",
							'setParams' => $setParams3 
					) );
				}
			}
		}
		// exit;
		return true;
	}
	function getConfiguredProductOptions1($ags) {
		$product_id = $ags ['product_id'];
		
		$array = array ();
		$sql = "SELECT a.product_to_options_id,
                     b.option_id,b.option_name,b.option_type
                    FROM zselex_product_to_options a 
                    INNER JOIN zselex_product_options b ON b.option_id=a.option_id
                    WHERE a.product_id=$product_id ORDER BY product_to_options_id ASC";
		$query = DBUtil::executeSQL ( $sql, '', '', false );
		$result = $query->fetchAll ();
		// $array[]
		// echo "<pre>"; print_r($result); echo "</pre>"; exit;
		
		foreach ( $result as $key => $val ) {
			
			$result [$key] ['main_option_values'] = ModUtil::apiFunc ( 'ZSELEX', 'user', 'getAll', array (
					'table' => 'zselex_product_options_values',
					'where' => "option_id=$val[option_id]",
					'fields' => array (
							'option_value_id',
							'option_value',
							'sort_order' 
					),
					'orderby' => "sort_order ASC" 
			) );
			
			$product_to_options_id = $val ['product_to_options_id'];
			$sql = "SELECT a.product_to_options_value_id , a.option_value_id , a.price , a.qty  , b.option_value
                    FROM zselex_product_to_options_values a
                    LEFT JOIN zselex_product_options_values b ON a.option_value_id=b.option_value_id
                    WHERE a.product_to_options_id=$product_to_options_id 
                    ORDER BY b.sort_order ASC";
			$query = DBUtil::executeSQL ( $sql, '', '', false );
			$result [$key] ['values'] = $query->fetchAll ();
		}
		// echo "<pre>"; print_r($result); echo "</pre>"; exit;
		return $result;
	}
	function getConfiguredProductOptions($ags) {
		// echo "<pre>"; print_r($ags); echo "</pre>";
		$product_id = $ags ['product_id'];
		$type = $ags ['type'];
		
		$array = array ();
		// return $array;
		/*
		 * $sql = "SELECT a.product_to_options_id,a.parent_option_id,
		 * b.option_id,b.option_name,b.option_type
		 * FROM zselex_product_to_options a
		 * INNER JOIN zselex_product_options b ON b.option_id=a.option_id
		 * WHERE a.product_id=$product_id ORDER BY b.sort_order ASC";
		 * // echo $sql;
		 * $query = DBUtil::executeSQL($sql, '', '', false);
		 * $result = $query->fetchAll();
		 */
		
		$result = $this->entityManager->getRepository ( 'ZSELEX_Entity_ProductToOption' )->getProductToOptions ( array (
				'product_id' => $product_id 
		) );
		// $array[]
		// echo "<pre>"; print_r($result); echo "</pre>"; exit;
		
		foreach ( $result as $key => $val ) {
			
			/*
			 * $result[$key]['main_option_values'] = ModUtil::apiFunc('ZSELEX', 'user', 'getAll', array(
			 * 'table' => 'zselex_product_options_values',
			 * 'where' => "option_id=$val[option_id]",
			 * 'fields' => array('option_value_id', 'option_value', 'sort_order'),
			 * 'orderby' => "sort_order ASC"
			 * ));
			 */
			
			$result [$key] ['main_option_values'] = $this->entityManager->getRepository ( 'ZSELEX_Entity_ProductToOption' )->getProductOptionValues ( array (
					'option_id' => $val ['option_id'] 
			) );
			
			// echo "<pre>"; print_r($result); echo "</pre>"; exit;
			$product_to_options_id = $val ['product_to_options_id'];
			$append = '';
			$qtySql = '';
			
			if ($type == 'front') {
				$qtySql = " AND a.qty > 0 ";
			}
			if ($type == 'front') {
				$append = " GROUP BY a.option_value_id ";
			}
			/*
			 * $sql = "SELECT a.product_to_options_value_id , a.option_value_id , a.parent_option_value_id ,a.price , a.qty ,a.parent_option_id,b.option_value
			 * FROM zselex_product_to_options_values a
			 * LEFT JOIN zselex_product_options_values b ON a.option_value_id=b.option_value_id
			 * WHERE a.product_to_options_id=$product_to_options_id $qtySql
			 * $append
			 * ORDER BY b.sort_order ASC";
			 * // GROUP BY a.option_value_id
			 * // echo $sql;
			 * $query = DBUtil::executeSQL($sql, '', '', false);
			 * $result[$key]['values'] = $query->fetchAll();
			 */
			
			$result [$key] ['values'] = $this->entityManager->getRepository ( 'ZSELEX_Entity_ProductToOption' )->getProductToOptionValues ( array (
					'product_to_options_id' => $product_to_options_id,
					'append' => $append,
					'qtySql' => $qtySql 
			) );
		}
		
		// echo "<pre>"; print_r($result); echo "</pre>"; exit;
		
		return $result;
	}
	public function saveOptionValues($args) {
		// echo "<pre>"; print_r($args); echo "</pre>"; exit;
		$repo = $this->entityManager->getRepository ( 'ZSELEX_Entity_ProductOption' );
		$option_values = array ();
		$option_val = array ();
		$option_id = $args ['elemId'];
		$shop_id = $args ['shop_id'];
		$option_values = $args ['optionValues'];
		$option_val = array_filter ( $option_values ['val'] );
		
		// echo "<pre>"; print_r($option_val); echo "</pre>"; exit;
		if (empty ( $option_id )) {
			return false;
		}
		
		/*
		 * $get = ModUtil::apiFunc('ZSELEX', 'user', 'get', array('table' => 'zselex_product_options',
		 * 'where' => "option_id=$option_id",
		 * 'fields' => array('option_id', 'option_type', 'parent_option_id')
		 * )
		 * );
		 */
		
		$get = $repo->get ( array (
				'entity' => 'ZSELEX_Entity_ProductOption',
				'fields' => array (
						'a.option_id',
						'a.option_type',
						'a.parent_option_id' 
				),
				'where' => array (
						'a.option_id' => $option_id 
				) 
		) );
		// echo "<pre>"; print_r($get); echo "</pre>"; exit;
		if ($get ['parent_option_id'] > 0 && $args ['type'] == 'checkbox') {
			LogUtil::registerError ( $this->__ ( 'This option is linked.you cannot change its type to "checkbox"' ) );
			return false;
		}
		
		// echo "<pre>"; print_r($option_values); echo "</pre>"; exit;
		// / echo "<pre>"; print_r($option_values['val']); echo "</pre>"; exit;
		
		if (empty ( $option_val )) {
			/*
			 * $delsql = "DELETE FROM zselex_product_options_values WHERE option_id=$option_id";
			 * // echo $delsql; exit;
			 * $delete = DBUtil::executeSQL($delsql, '', '', false);
			 */
			
			// echo "empty values"; exit;
			
			$delete = $repo->deleteEntity ( null, 'ZSELEX_Entity_ProductOptionValue', array (
					'a.option' => $option_id 
			) );
		}
		
		// exit;
		/*
		 * $sql_upd = "UPDATE zselex_product_options SET option_name='" . $args['name'] . "' , option_type='" . $args['type'] . "'
		 * WHERE option_id=$option_id";
		 * // echo $sql_upd . '<br>'; exit;
		 * $result_upd = DBUtil::executeSQL($sql_upd, '', '', false);
		 */
		
		$upd_fields = array (
				'option_name' => $args ['name'],
				'option_type' => $args ['type'] 
		);
		$result_upd = $repo->updateEntity ( null, 'ZSELEX_Entity_ProductOption', $upd_fields, array (
				'a.option_id' => $option_id 
		) );
		
		foreach ( $option_val as $key => $val ) {
			// echo $key . '<br>';
			// echo $option_values['ID'][$key] . '<br>';
			
			$sel_val_keys [] = $option_values ['ID'] [$key];
			/*
			 * $count = ModUtil::apiFunc('ZSELEX', 'admin', 'getCount', array(
			 * 'table' => 'zselex_product_options_values',
			 * "where" => "option_id=$option_id AND option_value_id ='" . $option_values['ID'][$key] . "'"
			 * ));
			 */
			
			$count = $repo->getCount ( null, 'ZSELEX_Entity_ProductOptionValue', 'option_value_id', array (
					'a.option' => $option_id,
					'a.option_value_id' => $option_values ['ID'] [$key] 
			) );
			
			// if ($count < 1) {
			if ($option_values ['ID'] [$key] < 1) {
				/*
				 * $sql_insert = "INSERT INTO zselex_product_options_values(option_id,shop_id,option_value,sort_order)VALUES('$option_id','$shop_id','" . $val . "' , '" . $option_values['sort_order'][$key] . "')";
				 * $result_insert = DBUtil::executeSQL($sql_insert, '', '', false);
				 * $sel_val_keys[] = DBUtil::getInsertID('zselex_product_options_values', 'option_value_id');
				 */
				$prodOptVal = new ZSELEX_Entity_ProductOptionValue ();
				$optionObj = $this->entityManager->find ( 'ZSELEX_Entity_ProductOption', $option_id );
				$prodOptVal->setOption ( $optionObj );
				$shopObj = $this->entityManager->find ( 'ZSELEX_Entity_Shop', $shop_id );
				$prodOptVal->setShop ( $shopObj );
				$prodOptVal->setOption_value ( $val );
				$prodOptVal->setSort_order ( $option_values ['sort_order'] [$key] );
				$this->entityManager->persist ( $prodOptVal );
				$this->entityManager->flush ();
				$sel_val_keys [] = $prodOptVal->getOption_value_id ();
			} else {
				/*
				 * $sql_upd = "UPDATE zselex_product_options_values SET option_id='" . $option_id . "' , shop_id='" . $shop_id . "' , option_value='" . $val . "' , sort_order='" . $option_values['sort_order'][$key] . "'
				 * WHERE option_id=$option_id AND option_value_id='" . $option_values['ID'][$key] . "'";
				 * //echo $sql_upd . '<br>';
				 * $result_upd = DBUtil::executeSQL($sql_upd, '', '', false);
				 */
				
				$upd_fields = array (
						'option' => $option_id,
						'shop' => $shop_id,
						'option_value' => $val,
						'sort_order' => $option_values ['sort_order'] [$key] 
				);
				$result_upd = $repo->updateEntity ( null, 'ZSELEX_Entity_ProductOptionValue', $upd_fields, array (
						'a.option' => $option_id,
						'a.option_value_id' => $option_values ['ID'] [$key] 
				) );
			}
		}
		// exit;
		// echo "<pre>"; print_r($sel_val_keys); echo "</pre>"; exit;
		
		$sel_val_keys = array_filter ( $sel_val_keys );
		$countValues = count ( $sel_val_keys );
		// echo "count : " . $countValues; exit;
		// if (!empty($sel_val_keys)) {
		if ($countValues > 0) {
			$del_options_vals = "'" . implode ( "','", $sel_val_keys ) . "'";
			// echo $del_options_vals; exit;
			/*
			 * $delsql = "DELETE FROM zselex_product_options_values WHERE option_id=$option_id
			 * AND option_value_id NOT IN($del_options_vals)";
			 * $delete = DBUtil::executeSQL($delsql, '', '', false);
			 */
			// if ($del_options_vals == '0') {
			$getOptionIdToDel = ModUtil::apiFunc ( 'ZSELEX', 'user', 'getAll', $args = array (
					'table' => 'zselex_product_options_values',
					'where' => "option_value_id NOT IN($del_options_vals) AND option_id=$option_id",
					'fields' => array (
							'option_value_id',
							'option_id' 
					) 
			) );
			// echo "<pre>"; print_r($getOptionIdToDel); echo "</pre>"; exit;
			if ($getOptionIdToDel) {
				foreach ( $getOptionIdToDel as $k => $v ) {
					// echo $v['option_value_id'] . '</br>';
					DBUtil::deleteWhere ( 'zselex_product_options_values', $where = "option_id=$option_id AND option_value_id=$v[option_value_id]" );
					// DBUtil::deleteWhere('zselex_product_to_options_values', $where = "product_id=$product_id AND product_to_options_id=$v[product_to_options_id]");
				}
			}
			// }
			// exit;
		}
		return true;
	}
	public function saveOptionValues1($args) {
		// echo "<pre>"; print_r($args); echo "</pre>"; exit;
		$option_id = $args ['elemId'];
		$shop_id = $args ['shop_id'];
		$option_values = $args ['optionValues'];
		if (empty ( $option_id )) {
			return false;
		}
		
		// echo "<pre>"; print_r($option_values); echo "</pre>"; exit;
		
		if (empty ( $option_values )) {
			$delsql = "DELETE FROM zselex_product_options_values WHERE option_id=$option_id";
			// echo $delsql; exit;
			$delete = DBUtil::executeSQL ( $delsql, '', '', false );
		}
		
		$sql_upd = "UPDATE zselex_product_options SET option_name='" . $args ['name'] . "' , option_type='" . $args ['type'] . "'
                    WHERE option_id=$option_id";
		// echo $sql_upd . '<br>'; exit;
		$result_upd = DBUtil::executeSQL ( $sql_upd, '', '', false );
		
		foreach ( $option_values as $key => $val ) {
			// echo $key . '<br>';
			$sel_val_keys [] = $key;
			$count = ModUtil::apiFunc ( 'ZSELEX', 'admin', 'getCount', array (
					'table' => 'zselex_product_options_values',
					"where" => "option_id=$option_id AND option_value_id ='" . $key . "'" 
			) );
			
			if ($count < 1) {
				$sql_insert = "INSERT INTO zselex_product_options_values(option_id,shop_id,option_value)VALUES('$option_id','$shop_id','" . $val . "')";
				$result_insert = DBUtil::executeSQL ( $sql_insert, '', '', false );
			} else {
				$sql_upd = "UPDATE zselex_product_options_values SET option_id='" . $option_id . "' , shop_id='" . $shop_id . "' , option_value='" . $val . "' 
                    WHERE option_id=$option_id AND option_value_id=$key";
				// echo $sql_upd . '<br>';
				$result_upd = DBUtil::executeSQL ( $sql_upd, '', '', false );
			}
		}
		// exit;
		
		if (! empty ( $sel_val_keys )) {
			$del_options_vals = "'" . implode ( "','", $sel_val_keys ) . "'";
			// echo $del_options_vals; exit;
			/*
			 * $delsql = "DELETE FROM zselex_product_options_values WHERE option_id=$option_id
			 * AND option_value_id NOT IN($del_options_vals)";
			 * $delete = DBUtil::executeSQL($delsql, '', '', false);
			 */
			if ($del_options_vals == '0') {
				$getOptionIdToDel = ModUtil::apiFunc ( 'ZSELEX', 'user', 'getAll', $args = array (
						'table' => 'zselex_product_options_values',
						'where' => "option_value_id NOT IN($del_options_vals) AND option_id=$option_id",
						'fields' => array (
								'option_value_id',
								'option_id' 
						) 
				) );
				// echo "<pre>"; print_r($getOptionIdToDel); echo "</pre>"; exit;
				if ($getOptionIdToDel) {
					foreach ( $getOptionIdToDel as $k => $v ) {
						// echo $v['option_value_id'] . '</br>';
						DBUtil::deleteWhere ( 'zselex_product_options_values', $where = "option_id=$option_id AND option_value_id=$v[option_value_id]" );
						// DBUtil::deleteWhere('zselex_product_to_options_values', $where = "product_id=$product_id AND product_to_options_id=$v[product_to_options_id]");
					}
				}
			}
			// exit;
		}
		return true;
	}
	public function setParentOptions($args) {
		// echo "<pre>"; print_r($args); echo "</pre>"; exit;
		$repo = $this->entityManager->getRepository ( 'ZSELEX_Entity_ProductOption' );
		$shop_id = $args ['shop_id'];
		// if (empty($args)) {
		if (empty ( $args [0] ) && empty ( $args [1] )) {
			// DBUtil::executeSQL("UPDATE zselex_product_options SET parent_option_id=0", '', '', false);
			$fields = array (
					'parent_option_id' => 0 
			);
			$repo->updateEntity ( null, 'ZSELEX_Entity_ProductOption', $fields, array (
					'a.shop' => $shop_id 
			) );
			return true;
		}
		$parent1 = $args [0];
		$parent2 = $args [1];
		
		/*
		 * $getType = ModUtil::apiFunc('ZSELEX', 'user', 'get', array('table' => 'zselex_product_options',
		 * 'where' => "option_id=$parent1",
		 * 'fields' => array('option_id', 'option_type')
		 * )
		 * );
		 */
		
		$getType = $repo->get ( array (
				'entity' => 'ZSELEX_Entity_ProductOption',
				'fields' => array (
						'a.option_id',
						'a.option_type' 
				),
				'where' => array (
						'a.option_id' => $parent1 
				) 
		) );
		
		// echo "<pre>"; print_r($getType); echo "</pre>"; exit;
		
		if ($getType ['option_type'] == 'checkbox') {
			LogUtil::registerError ( $this->__ ( "You cannot link a 'checkbox' option" ) );
			return;
		}
		
		/*
		 * $getType = ModUtil::apiFunc('ZSELEX', 'user', 'get', array('table' => 'zselex_product_options',
		 * 'where' => "option_id=$parent2",
		 * 'fields' => array('option_id', 'option_type')
		 * )
		 * );
		 */
		
		$getType = $repo->get ( array (
				'entity' => 'ZSELEX_Entity_ProductOption',
				'fields' => array (
						'a.option_id',
						'a.option_type' 
				),
				'where' => array (
						'a.option_id' => $parent2 
				) 
		) );
		if ($getType ['option_type'] == 'checkbox') {
			LogUtil::registerError ( $this->__ ( "You cannot link a 'checkbox' option" ) );
			return;
		}
		// DBUtil::executeSQL("UPDATE zselex_product_options SET parent_option_id=0", '', '', false);
		
		$repo->updateEntity ( null, 'ZSELEX_Entity_ProductOption', $fields = array (
				'parent_option_id' => 0 
		), array (
				'a.shop' => $shop_id 
		) );
		
		/*
		 * $sql_upd1 = "UPDATE zselex_product_options SET parent_option_id='" . $parent1 . "'
		 * WHERE option_id=$parent2";
		 * $result_upd1 = DBUtil::executeSQL($sql_upd1, '', '', false);
		 */
		
		$repo->updateEntity ( null, 'ZSELEX_Entity_ProductOption', $fields = array (
				'parent_option_id' => $parent1 
		), array (
				'a.option_id' => $parent2,
				'a.shop' => $shop_id 
		) );
		
		/*
		 * $sql_upd2 = "UPDATE zselex_product_options SET parent_option_id='" . $parent2 . "'
		 * WHERE option_id=$parent1";
		 * $result_upd2 = DBUtil::executeSQL($sql_upd2, '', '', false);
		 */
		
		$repo->updateEntity ( null, 'ZSELEX_Entity_ProductOption', $fields = array (
				'parent_option_id' => $parent2 
		), array (
				'a.option_id' => $parent1,
				'a.shop' => $shop_id 
		) );
		
		return true;
	}
	public function createProduct($item) {
		$result = $this->entityManager->getRepository ( 'ZSELEX_Entity_Product' )->createProduct ( $item );
		return $result;
	}
	
	/**
	 * Get quantity discounts of a product
	 *
	 * @param array $args
	 *        	int productId
	 * @return array of quantity discounts
	 */
	public function getQtyDiscounts($args) {
		$product_id = $args ['product_id'];
		$repo = $this->entityManager->getRepository ( 'ZSELEX_Entity_Product' );
		$result = $repo->getAll ( array (
				'entity' => 'ZSELEX_Entity_QuantityDiscount',
				'fields' => array (
						'a.quantity',
						'a.discount',
						'a.start_date',
						'a.end_date' 
				),
				'where' => array (
						'a.product' => $product_id 
				),
				'orderby' => 'a.discount_id ASC' 
		) );
		return $result;
	}
}

?>