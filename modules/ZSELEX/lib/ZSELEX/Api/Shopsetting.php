<?php
class ZSELEX_Api_Shopsetting extends ZSELEX_Api_Admin {
	public function createEventDnd($args) {
		$event = $this->entityManager->getRepository ( 'ZSELEX_Entity_Event' )->createEventDnd ( $args );
		return true;
	}
	public function updateEventDnd($args) {
		$event = $this->entityManager->getRepository ( 'ZSELEX_Entity_Event' )->updateEventDnd ( $args );
		return true;
	}
	public function createImageDnd($args) {
		$image = $this->entityManager->getRepository ( 'ZSELEX_Entity_MinisiteImage' )->createImageDnd ( $args );
		return true;
	}
	public function createEmployeeDnd($args) {
		$image = $this->entityManager->getRepository ( 'ZSELEX_Entity_Employee' )->createEmployeeDnd ( $args );
		return true;
	}
	public function createBannerDnd($args) {
		$image = $this->entityManager->getRepository ( 'ZSELEX_Entity_Employee' )->createBannerDnd ( $args );
		return true;
	}
	public function getBannerImageMode($args) {
		$shop_id = $args ['shop_id'];
		$banArray = array (
				'entity' => 'ZSELEX_Entity_BannerSetting',
				'where' => array (
						'a.shop' => $shop_id 
				) 
		);
		$image_mode = $this->entityManager->getRepository ( 'ZSELEX_Entity_BannerSetting' )->get ( $banArray );
		return $image_mode;
	}
}

?>