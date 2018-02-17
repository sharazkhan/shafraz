<?php

/**
 * ZSELEX.
 *
 * @copyright 
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @package ShopProducts
 * @author  <>.
 * @link http://modulestudio.de/
 * @link http://zikula.org
 * @version Generated by ModuleStudio 0.5.4 (http://modulestudio.de) at Tue Feb 07 21:56:43 IST 2012.
 */
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use DoctrineExtensions\StandardFields\Mapping\Annotation as ZK;

/**
 * Entity class that defines the entity structure and behaviours.
 *
 * This is the base entity class for shop entities.
 *
 *
 * @ORM\Entity(repositoryClass="ZSELEX_Entity_Repository_ShopRepository")
 * @ORM\Table(name="zselex_shop_affiliation")
 */
class ZSELEX_Entity_ShopAffiliation extends Zikula_EntityAccess {
	
	/**
	 * type_id field (record type_id)
	 *
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	public $aff_id = 0;
	
	/**
	 * @ORM\Column(length=255)
	 * 
	 * @var string $aff_name.
	 */
	public $aff_name = '';
	
	/**
	 * @ORM\Column(length=255)
	 * 
	 * @var string $aff_image.
	 */
	public $aff_image = '';
	
	/**
	 * @ORM\Column(type="integer")
	 * 
	 * @var int $sort_order.
	 */
	public $sort_order = 0;
	
	/**
	 * @ORM\Column(type="datetime")
	 * @Gedmo\Timestampable(on="create")
	 * 
	 * @var datetime $cr_date.
	 */
	public $cr_date;
	
	/**
	 * @ORM\Column(type="integer")
	 * @ZK\StandardFields(type="userid", on="create")
	 * 
	 * @var integer $cr_uid.
	 */
	public $cr_uid;
	
	/**
	 * @ORM\Column(type="datetime")
	 * @Gedmo\Timestampable(on="create")
	 * 
	 * @var datetime $lu_date.
	 */
	public $lu_date;
	
	/**
	 * @ORM\Column(type="integer")
	 * @ZK\StandardFields(type="userid", on="update")
	 * 
	 * @var integer $lu_uid.
	 */
	public $lu_uid;
	
	/**
	 * Constructor.
	 * Will not be called by Doctrine and can therefore be used
	 * for own implementation purposes. It is also possible to add
	 * arbitrary arguments as with every other class method.
	 */
	
	/**
	 * @ORM\OneToMany(targetEntity="ZSELEX_Entity_Shop" ,cascade={"all"}, mappedBy="aff_id")
	 */
	public $affiliate_shops;
	public function __construct() {
		
		// $this->advertise_price = new ArrayCollection();
		$this->affiliate_shops = new ArrayCollection ();
	}
	
	/**
	 * Get id.
	 *
	 * @return integer
	 */
	public function getAff_id() {
		return $this->aff_id;
	}
	
	/**
	 * Get shop_name.
	 *
	 * @return string
	 */
	public function getAff_name() {
		return $this->aff_name;
	}
	public function setAff_name($aff_name) {
		$this->aff_name = $aff_name;
	}
	public function getAff_image() {
		return $this->aff_image;
	}
	public function setAff_image($aff_image) {
		$this->aff_image = $aff_image;
	}
	public function getAffiliate_shops() {
		return $this->affiliate_shops;
	}
	public function setAffiliate_shops($affiliate_shops) {
		$this->affiliate_shops = $affiliate_shops;
	}
	
	/**
	 * Get created user id.
	 *
	 * @return integer[]
	 */
	public function getCr_date() {
		return $this->cr_date;
	}
	
	/**
	 * Set created user id.
	 *
	 * @param integer[] $createdUserId.        	
	 *
	 * @return void
	 */
	public function setCr_date($cr_date) {
		$this->cr_date = $cr_date;
	}
	
	/**
	 * Get updated user id.
	 *
	 * @return integer[]
	 */
	public function getCr_uid() {
		return $this->cr_uid;
	}
	
	/**
	 * Set updated user id.
	 *
	 * @param integer[] $updatedUserId.        	
	 *
	 * @return void
	 */
	public function setCr_uid($cr_uid) {
		$this->cr_uid = $cr_uid;
	}
	
	/**
	 * Get created date.
	 *
	 * @return datetime[]
	 */
	public function getLu_date() {
		return $this->lu_date;
	}
	
	/**
	 * Set created date.
	 *
	 * @param datetime[] $createdDate.        	
	 *
	 * @return void
	 */
	public function setLu_date($lu_date) {
		$this->lu_date = $lu_date;
	}
	
	/**
	 * Get updated date.
	 *
	 * @return datetime[]
	 */
	public function getLu_uid() {
		return $this->lu_uid;
	}
	
	/**
	 * Get updated date.
	 *
	 * @return datetime[]
	 */
	public function setLu_uid($lu_uid) {
		$this->lu_uid = $lu_uid;
	}
	
	/**
	 * Post-Process the data after the entity has been constructed by the entity manager.
	 * The event happens after the entity has been loaded from database or after a refresh call.
	 *
	 * Restrictions:
	 * - no access to entity manager or unit of work apis
	 * - no access to associations (not initialised yet)
	 *
	 * @see ShopProducts_Entity_Shop::postLoadCallback()
	 * @return boolean true if completed successfully else false.
	 */
	protected function performPostLoadCallback() {
		// echo 'loaded a record ...';
		
		/*
		 * $currentType = FormUtil::getPassedValue('type', 'user', 'GETPOST', FILTER_SANITIZE_STRING);
		 * $currentFunc = FormUtil::getPassedValue('func', 'main', 'GETPOST', FILTER_SANITIZE_STRING);
		 *
		 * $this['id'] = (int) ((isset($this['id']) && !empty($this['id'])) ? DataUtil::formatForDisplay($this['id']) : 0);
		 * if ($currentFunc != 'edit') {
		 * $this['shop_name'] = ((isset($this['shop_name']) && !empty($this['shop_name'])) ? DataUtil::formatForDisplayHTML($this['shop_name']) : '');
		 * }
		 * if ($currentFunc != 'edit') {
		 * $this['address'] = ((isset($this['address']) && !empty($this['address'])) ? DataUtil::formatForDisplayHTML($this['address']) : '');
		 * }
		 * $this->prepareItemActions();
		 */
		return true;
	}
	
	/**
	 * Pre-Process the data prior to an insert operation.
	 * The event happens before the entity managers persist operation is executed for this entity.
	 *
	 * Restrictions:
	 * - no access to entity manager or unit of work apis
	 * - no identifiers available if using an identity generator like sequences
	 * - Doctrine won't recognize changes on relations which are done here
	 * if this method is called by cascade persist
	 * - no creation of other entities allowed
	 *
	 * @see ShopProducts_Entity_Shop::prePersistCallback()
	 * @return boolean true if completed successfully else false.
	 */
	protected function performPrePersistCallback() {
		// echo 'inserting a record ...';
		// $this->validate();
		return true;
	}
	
	/**
	 * Post-Process the data after an insert operation.
	 * The event happens after the entity has been made persistant.
	 * Will be called after the database insert operations.
	 * The generated primary key values are available.
	 *
	 * Restrictions:
	 * - no access to entity manager or unit of work apis
	 *
	 * @see ShopProducts_Entity_Shop::postPersistCallback()
	 * @return boolean true if completed successfully else false.
	 */
	protected function performPostPersistCallback() {
		// echo 'inserted a record ...';
		return true;
	}
	
	/**
	 * Pre-Process the data prior a delete operation.
	 * The event happens before the entity managers remove operation is executed for this entity.
	 *
	 * Restrictions:
	 * - no access to entity manager or unit of work apis
	 * - will not be called for a DQL DELETE statement
	 *
	 * @see ShopProducts_Entity_Shop::preRemoveCallback()
	 * @return boolean true if completed successfully else false.
	 */
	protected function performPreRemoveCallback() {
		/*
		 * // delete workflow for this entity
		 * $result = Zikula_Workflow_Util::deleteWorkflow($this);
		 * if ($result === false) {
		 * $dom = ZLanguage::getModuleDomain('ShopProducts');
		 * return LogUtil::registerError(__('Error! Could not remove stored workflow.', $dom));
		 * }
		 */
		return true;
	}
	
	/**
	 * Post-Process the data after a delete.
	 * The event happens after the entity has been deleted.
	 * Will be called after the database delete operations.
	 *
	 * Restrictions:
	 * - no access to entity manager or unit of work apis
	 * - will not be called for a DQL DELETE statement
	 *
	 * @see ShopProducts_Entity_Shop::postRemoveCallback()
	 * @return boolean true if completed successfully else false.
	 */
	protected function performPostRemoveCallback() {
		// echo 'deleted a record ...';
		return true;
	}
	
	/**
	 * Pre-Process the data prior to an update operation.
	 * The event happens before the database update operations for the entity data.
	 *
	 * Restrictions:
	 * - no access to entity manager or unit of work apis
	 * - will not be called for a DQL UPDATE statement
	 * - changes on associations are not allowed and won't be recognized by flush
	 * - changes on properties won't be recognized by flush as well
	 * - no creation of other entities allowed
	 *
	 * @see ShopProducts_Entity_Shop::preUpdateCallback()
	 * @return boolean true if completed successfully else false.
	 */
	protected function performPreUpdateCallback() {
		// echo 'updating a record ...';
		// $this->validate();
		return true;
	}
	
	/**
	 * Post-Process the data after an update operation.
	 * The event happens after the database update operations for the entity data.
	 *
	 * Restrictions:
	 * - no access to entity manager or unit of work apis
	 * - will not be called for a DQL UPDATE statement
	 *
	 * @see ShopProducts_Entity_Shop::postUpdateCallback()
	 * @return boolean true if completed successfully else false.
	 */
	protected function performPostUpdateCallback() {
		// echo 'updated a record ...';
		return true;
	}
	
	/**
	 * Pre-Process the data prior to a save operation.
	 * This combines the PrePersist and PreUpdate events.
	 * For more information see corresponding callback handlers.
	 *
	 * @see ShopProducts_Entity_Shop::preSaveCallback()
	 * @return boolean true if completed successfully else false.
	 */
	protected function performPreSaveCallback() {
		// echo 'saving a record ...';
		// $this->validate();
		return true;
	}
	
	/**
	 * Post-Process the data after a save operation.
	 * This combines the PostPersist and PostUpdate events.
	 * For more information see corresponding callback handlers.
	 *
	 * @see ShopProducts_Entity_Shop::postSaveCallback()
	 * @return boolean true if completed successfully else false.
	 */
	protected function performPostSaveCallback() {
		// echo 'saved a record ...';
		return true;
	}
}
