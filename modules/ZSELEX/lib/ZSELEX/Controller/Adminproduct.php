<?php
class ZSELEX_Controller_Adminproduct extends Zikula_AbstractController {
	protected function postInitialize() {
		// Disable caching by default.
		$this->view->setCaching ( Zikula_View::CACHE_DISABLED );
	}
	public function productOption($args) {
		return $this->view->fetch ( 'admin/ishopproducts/viewproduct_option.tpl' );
	}
}

?>