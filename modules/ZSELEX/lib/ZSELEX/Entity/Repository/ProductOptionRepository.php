<?php

/**
 * Tag - a content-tagging module for the Zikukla Application Framework
 * 
 * @license MIT
 *
 * Please see the NOTICE file distributed with this source code for further
 * information regarding copyright and licensing.
 */
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM;
use Doctrine\ORM\Query\ResultSetMapping;

/**
 * Repository class for DQL calls
 */
class ZSELEX_Entity_Repository_ProductOptionRepository extends ZSELEX_Entity_Repository_General {
	public function getProductOptionList($args) {
		
		// echo "<pre>"; print_r($args); echo "</pre>";
		// echo "comes here api!!!";
		$shop_id = $args ['shop_id'];
		$order = " a." . $args ['order'];
		$searchtext = $args ['searchtext'];
		$all = $args ['all'];
		// $searchtext = addslashes($searchtext);
		$status = $args ['status'];
		$orderdir = $args ['orderdir'];
		$offset = $args ['offset'];
		$maxResults = $args ['maxResults'];
		$sql = '';
		if (! empty ( $searchtext )) {
			$sql .= " AND a.option_name LIKE :searchtext";
		}
		
		$dql = "SELECT a.option_id , b.shop_id , a.option_name , a.option_value , a.parent_option_id , a.sort_order
            FROM ZSELEX_Entity_ProductOption a
            JOIN a.shop b
            WHERE a.shop = :shop_id 
            $sql
            ORDER BY $order $orderdir";
		// echo $dql;
		$query = $this->_em->createQuery ( $dql );
		$query->setParameter ( 'shop_id', $shop_id );
		
		if (! empty ( $searchtext )) {
			$searchword = "%" . $searchtext . "%";
			$query->setParameter ( 'searchtext', $searchword );
		}
		
		if (! $all) {
			if ($offset > 0) {
				$query->setFirstResult ( $offset );
			}
			$query->setMaxResults ( $maxResults );
		}
		// echo $query->getSQL();
		$result = $query->getResult ();
		// echo "<pre>"; print_r($result); echo "</pre>";
		
		return $result;
	}
	public function getProductOptionCount($args) {
		$shop_id = $args ['shop_id'];
		
		$sql = '';
		
		$dql = "SELECT COUNT(a.option_id) FROM ZSELEX_Entity_ProductOption a WHERE a.shop=:shop_id " . $sql;
		
		$query = $this->_em->createQuery ( $dql );
		$query->setParameter ( 'shop_id', $shop_id );
		
		$count = $query->getSingleScalarResult ();
		return $count;
	}
	public function getProductOptions($args) {
		$shop_id = $args ['shop_id'];
		$dql = "SELECT a.option_id , b.shop_id , a.option_name , a.option_value , a.parent_option_id
            FROM ZSELEX_Entity_ProductOption a
            JOIN a.shop b
            WHERE a.shop = :shop_id AND (a.parent_option_id=0 OR a.parent_option_id is NULL)
            ORDER BY a.option_id ASC";
		
		$query = $this->_em->createQuery ( $dql );
		$query->setParameter ( 'shop_id', $shop_id );
		$result = $query->getResult ();
		return $result;
	}
	public function getParentOptions($args) {
		$shop_id = $args ['shop_id'];
		$newArr = array ();
		$dql = "SELECT a.option_id , b.shop_id , a.option_name ,  a.parent_option_id , a.sort_order
            FROM ZSELEX_Entity_ProductOption a
            JOIN a.shop b
            WHERE a.shop = :shop_id AND a.parent_option_id>0
            ORDER BY a.sort_order ASC";
		
		$query = $this->_em->createQuery ( $dql );
		$query->setParameter ( 'shop_id', $shop_id );
		$result = $query->getResult ();
		// echo "<pre>"; print_r($result); echo "</pre>"; exit;
		if (! empty ( $result )) {
			$newArr [] = array (
					'option_id' => $result [0] ['option_id'],
					'shop_id' => $result [0] ['shop_id'],
					'option_name' => $result [0] ['option_name'] . "+" . $result [1] ['option_name'] 
			);
		}
		
		return $newArr;
	}
	public function getProductOption($args) {
		// $shop_id = $args['shop_id'];
		$option_id = $args ['option_id'];
		
		$dql = "SELECT a.option_id , b.shop_id , a.option_name , a.option_type , a.option_value , a.parent_option_id
                FROM ZSELEX_Entity_ProductOption a
                JOIN a.shop b
                WHERE a.option_id = :option_id";
		$query = $this->_em->createQuery ( $dql );
		
		$query->setParameter ( 'option_id', $option_id );
		$result = $query->getOneOrNullResult ();
		
		return $result;
	}
	public function deleteProductOption($args) {
		// echo "<pre>"; print_r($args); echo "</pre>"; exit;
		$option_id = $args ['option_id'];
		$query = $this->_em->createQuery ( 'DELETE from ZSELEX_Entity_ProductOption m WHERE m.option_id =:option_id' );
		$query->setParameter ( 'option_id', $option_id );
		$numDeleted = $query->execute ();
		return $numDeleted;
	}
	public function deleteProductToOption($args) {
		// echo "<pre>"; print_r($args); echo "</pre>"; exit;
		$where = $args ['where'];
		$setParams = $args ['setParams'];
		$query = $this->_em->createQuery ( 'DELETE from ZSELEX_Entity_ProductToOption a WHERE ' . $where );
		// $query->setParameter('product_id', $product_id);
		$query->setParameters ( $setParams );
		$numDeleted = $query->execute ();
		return $numDeleted;
	}
	public function deleteProductToOptionValue($args) {
		// echo "<pre>"; print_r($args); echo "</pre>"; exit;
		$where = $args ['where'];
		$setParams = $args ['setParams'];
		$query = $this->_em->createQuery ( 'DELETE from ZSELEX_Entity_ProductToOptionValue a WHERE ' . $where );
		$query->setParameters ( $setParams );
		$numDeleted = $query->execute ();
		return $numDeleted;
	}
	public function getProductToOptionCount($args) {
		$product_id = $args ['product_id'];
		$option_id = $args ['option_id'];
		/*
		 * $dql = 'SELECT COUNT(a.product_to_options_id) FROM ZSELEX_Entity_ProductToOption a '
		 * . 'WHERE a.product=:product_id AND a.option=:option_id';
		 * $query = $this->_em->createQuery($dql);
		 * $query->setParameter('product_id', $product_id);
		 * $query->setParameter('option_id', $option_id);
		 * $count = $query->getSingleScalarResult();
		 * return $count;
		 */
		
		$countArgs = array (
				'entity' => 'ZSELEX_Entity_ProductToOption',
				'field' => 'product_to_options_id',
				'where' => array (
						"a.product" => $product_id,
						"a.option" => $option_id 
				) 
		);
		
		$count = $this->getCount ( $countArgs );
		return $count;
	}
	public function createProductToOption($args) {
		// echo "<pre>"; print_r($args); echo "</pre>"; exit;
		$product_id = isset ( $args ['product_id'] ) ? $args ['product_id'] : 0;
		$option_id = isset ( $args ['option_id'] ) ? $args ['option_id'] : 0;
		$parent_option_id = ! empty ( $args ['parent_option_id'] ) ? $args ['parent_option_id'] : 0;
		// echo $parent_option_id; exit;
		$product = $this->_em->find ( 'ZSELEX_Entity_Product', $product_id );
		$option = $this->_em->find ( 'ZSELEX_Entity_ProductOption', $option_id );
		
		$prodToOpt = new ZSELEX_Entity_ProductToOption ();
		$prodToOpt->setProduct ( $product );
		$prodToOpt->setOption ( $option );
		$prodToOpt->setParent_option_id ( $parent_option_id );
		$this->_em->persist ( $prodToOpt );
		$this->_em->flush ();
		
		$InsertId = $prodToOpt->getProduct_to_options_id ();
		$result = $InsertId;
		return $result;
	}
	public function getLinkedCount($args) {
		$where = $args ['where'];
		$dql = 'SELECT COUNT(a.product_to_options_value_id) FROM ZSELEX_Entity_ProductToOptionValue a ' . 'WHERE ' . $where;
		$query = $this->_em->createQuery ( $dql );
		$count = $query->getSingleScalarResult ();
		return $count;
	}
	public function createProductToOptionValue($args) {
		// echo "<pre>"; print_r($args); echo "</pre>"; exit;
		// echo $parent_option_id; exit;
		$product_to_option = $this->_em->find ( 'ZSELEX_Entity_ProductToOption', $args ['product_to_options_id'] );
		$product = $this->_em->find ( 'ZSELEX_Entity_Product', $args ['product_id'] );
		$option = $this->_em->find ( 'ZSELEX_Entity_ProductOption', $args ['option_id'] );
		
		$prodToOptVal = new ZSELEX_Entity_ProductToOptionValue ();
		$prodToOptVal->setProduct_to_option ( $product_to_option );
		$prodToOptVal->setProduct ( $product );
		$prodToOptVal->setOption ( $option );
		$prodToOptVal->setParent_option_id ( $args ['parent_option_id'] );
		$option_value_id = $this->_em->find ( 'ZSELEX_Entity_ProductOptionValue', $args ['option_value_id'] );
		$prodToOptVal->setOption_value_id ( $option_value_id );
		$prodToOptVal->setParent_option_value_id ( $args ['parent_option_value_id'] );
		$prodToOptVal->setPrice_prefix ( $args ['price_prefix'] );
		$prodToOptVal->setPrice ( $args ['price'] );
		$prodToOptVal->setQty ( $args ['qty'] );
		$this->_em->persist ( $prodToOptVal );
		$this->_em->flush ();
		
		$InsertId = $prodToOptVal->getProduct_to_options_value_id ();
		$result = $InsertId;
		return $result;
	}
	public function updateProductToOptionValue($args) {
		$price = $args ['price'];
		$price_prefix = substr ( $price, 0, 1 );
		$qty = $args ['qty'];
		$where = $args ['where'];
		$upd_dql = "UPDATE ZSELEX_Entity_ProductToOptionValue a SET a.price_prefix='" . $price_prefix . "' , a.price='" . $price . "' ,  a.qty='" . $qty . "' 
                    WHERE $where";
		$query = $this->_em->createQuery ( $upd_dql );
		$numUpdated = $query->execute ();
		return $numUpdated;
	}
	public function deleteProductToOptionValueWhere($args) {
		// echo "<pre>"; print_r($args); echo "</pre>"; exit;
		$where = $args ['where'];
		$query = $this->_em->createQuery ( 'DELETE from ZSELEX_Entity_ProductToOptionValue a WHERE ' . $where );
		$numDeleted = $query->execute ();
		return $numDeleted;
	}
	public function getProductToOptionValueCount($args) {
		$product_to_options_id = $args ['product_to_options_id'];
		// echo $product_to_options_id; exit;
		
		$dql = 'SELECT COUNT(a.product_to_options_value_id) FROM ZSELEX_Entity_ProductToOptionValue a ' . 'WHERE a.product_to_option=:product_to_options_id';
		// echo $dql; exit;
		$query = $this->_em->createQuery ( $dql );
		$query->setParameter ( 'product_to_options_id', $product_to_options_id );
		$count = $query->getSingleScalarResult ();
		return $count;
	}
	public function deleteProductToOption2($args) {
		// echo "<pre>"; print_r($args); echo "</pre>"; exit;
		$product_to_options_id = $args ['product_to_options_id'];
		$query = $this->_em->createQuery ( 'DELETE FROM ZSELEX_Entity_ProductToOption a WHERE a.product_to_options_id =:product_to_options_id' );
		$query->setParameter ( 'product_to_options_id', $product_to_options_id );
		$numDeleted = $query->execute ();
		return $numDeleted;
	}
	public function getProductToOptions($args) {
		$product_id = $args ['product_id'];
		// echo $product_id; exit;
		$dql = "SELECT a.product_to_options_id,a.parent_option_id,
                     b.option_id,b.option_name,b.option_type
                    FROM ZSELEX_Entity_ProductToOption a
                    JOIN a.option b
                    WHERE a.product = :product_id            
                    ORDER BY b.sort_order ASC";
		
		$query = $this->_em->createQuery ( $dql );
		$query->setParameter ( 'product_id', $product_id );
		$result = $query->getResult ( 2 );
		return $result;
	}
	public function getProductOptionValues($args) {
		$option_id = $args ['option_id'];
		// echo $product_id; exit;
		$dql = "SELECT a.option_value_id,a.option_value,a.sort_order
                    FROM ZSELEX_Entity_ProductOptionValue a
                    WHERE a.option = :option_id            
                    ORDER BY a.sort_order ASC";
		
		$query = $this->_em->createQuery ( $dql );
		$query->setParameter ( 'option_id', $option_id );
		$result = $query->getResult ( 2 );
		return $result;
	}
	public function getProductToOptionValues($args) {
		$append = $args ['append'];
		$qtySql = $args ['qtySql'];
		$product_to_options_id = $args ['product_to_options_id'];
		// echo $product_id; exit;
		$rsm = new ORM\Query\ResultSetMapping ();
		$rsm->addEntityResult ( 'ZSELEX_Entity_ProductToOptionValue', 'a' );
		$rsm->addFieldResult ( 'a', 'product_to_options_value_id', 'product_to_options_value_id' );
		// $rsm->addFieldResult('a', 'option_value_id', 'option_value_id');
		$rsm->addFieldResult ( 'a', 'parent_option_value_id', 'parent_option_value_id' );
		$rsm->addFieldResult ( 'a', 'price', 'price' );
		$rsm->addFieldResult ( 'a', 'qty', 'qty' );
		$rsm->addFieldResult ( 'a', 'parent_option_id', 'parent_option_id' );
		$rsm->addMetaResult ( 'a', 'option_value', 'option_value' );
		$rsm->addMetaResult ( 'a', 'option_value_id', 'option_value_id' );
		
		$dql = "SELECT  a.product_to_options_value_id , b.option_value_id , a.parent_option_value_id , 
                    a.price , a.qty ,a.parent_option_id,b.option_value
                    FROM zselex_product_to_options_values a
                    LEFT JOIN zselex_product_options_values b ON a.option_value_id=b.option_value_id
                    WHERE a.product_to_options_id=$product_to_options_id $qtySql         
                    $append
                    ORDER BY b.sort_order ASC";
		
		$query = $this->_em->createNativeQuery ( $dql, $rsm );
		$result = $query->getResult ( 2 );
		return $result;
	}
	public function getProductToOptionValuesAjax($args) {
		try {
			$where = $args ['where'];
			$dql = "SELECT a.product_to_options_value_id,b.product_to_options_id,a.price,a.qty
                    FROM ZSELEX_Entity_ProductToOptionValue a
                    LEFT JOIN a.product_to_option b
                    WHERE $where            
                   ";
			
			$query = $this->_em->createQuery ( $dql );
			$result = $query->getOneOrNullResult ( 2 );
			return $result;
		} catch ( \Exception $e ) {
			echo 'Message: ' . $e->getMessage ();
			exit ();
		}
	}
	public function getParentOptionValues($args) {
		try {
			$where = $args ['where'];
			
			$rsm = new ORM\Query\ResultSetMapping ();
			$rsm->addEntityResult ( 'ZSELEX_Entity_ProductToOptionValue', 'a' );
			
			// $rsm->addFieldResult('a', 'option_value_id', 'option_value_id');
			$rsm->addFieldResult ( 'a', 'parent_option_value_id', 'parent_option_value_id' );
			$rsm->addFieldResult ( 'a', 'price', 'price' );
			$rsm->addFieldResult ( 'a', 'qty', 'qty' );
			$rsm->addFieldResult ( 'a', 'parent_option_id', 'parent_option_id' );
			// $rsm->addFieldResult('a', 'option_value_id', 'option_value_id');
			
			$rsm->addMetaResult ( 'a', 'product_id', 'product_id' );
			$rsm->addMetaResult ( 'a', 'option_type', 'option_type' );
			$rsm->addMetaResult ( 'a', 'option_name', 'option_name' );
			$rsm->addMetaResult ( 'a', 'option_value', 'option_value' );
			$rsm->addMetaResult ( 'a', 'option_id', 'option_id' );
			$rsm->addMetaResult ( 'a', 'product_to_options_id', 'product_to_options_id' );
			$rsm->addMetaResult ( 'a', 'option_value_id', 'option_value_id' );
			
			$dql = "SELECT a.product_id,a.price,a.option_id,a.product_to_options_id,a.option_value_id,
                    a.parent_option_value_id,a.qty,b.option_name,b.option_type,c.option_value,
                    a.price , a.qty ,a.parent_option_id
                    FROM zselex_product_to_options_values a
                    LEFT JOIN zselex_product_options b ON b.option_id=a.parent_option_id
                    INNER JOIN zselex_product_options_values c ON c.option_value_id=a.parent_option_value_id
                    WHERE $where  
                    GROUP BY a.parent_option_value_id ASC
                    ORDER BY c.sort_order ASC";
			// echo $dql;
			$query = $this->_em->createNativeQuery ( $dql, $rsm );
			$result = $query->getResult ( 2 );
			return $result;
		} catch ( \Exception $e ) {
			// echo 'Message: ' . $e->getMessage();
			// exit;
		}
	}
	public function getProductToOptionValue($args) {
		$id = $args ['id'];
		$fields = $args ['fields'];
		// echo "<pre>"; print_r($fields); echo "</pre>"; exit;
		try {
			$fields = $this->generateFields ( $fields );
			// echo $fields; exit;
			$dql = "SELECT $fields
                    FROM ZSELEX_Entity_ProductToOptionValue a
                    WHERE a.product_to_options_value_id=:id            
                   ";
			
			$query = $this->_em->createQuery ( $dql );
			$query->setParameter ( 'id', $id );
			$result = $query->getOneOrNullResult ( 2 );
			return $result;
		} catch ( \Exception $e ) {
			// echo 'Message: ' . $e->getMessage();
			// exit;
		}
	}
	public function checkOptionExistForProduct($args) {
		$product_id = $args ['product_id'];
		
		$dql = 'SELECT COUNT(a.product_to_options_id) FROM ZSELEX_Entity_ProductToOption a ' . 'WHERE a.product=:product_id';
		$query = $this->_em->createQuery ( $dql );
		$query->setParameter ( 'product_id', $product_id );
		$count = $query->getSingleScalarResult ();
		return $count;
	}
}