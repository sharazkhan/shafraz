<?php

/**
 * Internal callback class used to check permissions to each ZSELEX item
 * @author Jorn Wildt
 */
class ZSELEX_ResultChecker {
	protected $enablecategorization;
	protected $enablecategorybasedpermissions;
	function __construct($enablecategorization, $enablecategorybasedpermissions) {
		$this->enablecategorization = $enablecategorization;
		$this->enablecategorybasedpermissions = $enablecategorybasedpermissions;
	}
	// This method is called by DBUtil::selectObjectArrayFilter() for each and every search result.
	// A return value of true means "keep result" - false means "discard".
	function checkResult(&$item) {
		$ok = (SecurityUtil::checkPermission ( 'ZSELEX::', "$item[cr_uid]::$item[type_id]", ACCESS_OVERVIEW ));
		if ($this->enablecategorization && $this->enablecategorybasedpermissions) {
			ObjectUtil::expandObjectWithCategories ( $item, 'zselex_type', 'type_id' );
			$ok = $ok && CategoryUtil::hasCategoryAccess ( $item ['__CATEGORIES__'], 'ZSELEX' );
		}
		return $ok;
	}
}