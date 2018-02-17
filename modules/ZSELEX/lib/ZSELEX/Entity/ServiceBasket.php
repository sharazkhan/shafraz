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
 * @ORM\Entity(repositoryClass="ZSELEX_Entity_Repository_ServiceShopRepo")
 * @ORM\Table(name="zselex_basket")
 */
class ZSELEX_Entity_ServiceBasket extends Zikula_EntityAccess {
	
	/**
	 * type_id field (record type_id)
	 *
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	public $basket_id = 0;
	
	/**
	 * @ORM\ManyToOne(targetEntity="ZSELEX_Entity_Plugin")
	 * @ORM\JoinColumn(name="plugin_id", referencedColumnName="plugin_id")
	 */
	public $plugin;
	
	/**
	 * @ORM\Column(length=255)
	 */
	public $type = '';
	
	/**
	 * @ORM\ManyToOne(targetEntity="ZSELEX_Entity_Shop", inversedBy="service_shops")
	 * @ORM\JoinColumn(name="shop_id", referencedColumnName="shop_id")
	 */
	public $shop;
	
	/**
	 * @ORM\Column(type="integer")
	 */
	public $user_id;
	
	/**
	 * @ORM\Column(type="integer")
	 */
	public $owner_id;
	
	/**
	 * @ORM\Column(type="integer")
	 */
	public $quantity = 0;
	
	/**
	 * @ORM\Column(type="boolean")
	 */
	public $qty_based = 0;
	
	/**
	 * @ORM\Column(type="boolean")
	 */
	public $is_bundle = 0;
	
	/**
	 * @ORM\ManyToOne(targetEntity="ZSELEX_Entity_Bundle")
	 * @ORM\JoinColumn(name="bundle_id", referencedColumnName="bundle_id")
	 */
	public $bundle = 0;
	
	/**
	 * @ORM\Column(type="boolean")
	 */
	public $top_bundle = 0;
	
	/**
	 * @ORM\Column(length=255)
	 */
	public $bundle_type = '';
	
	/**
	 * field (original_price)
	 *
	 * @ORM\Column(type="decimal" , precision=15 , scale=4 , nullable=true)
	 */
	private $original_price = 0.0000;
	
	/**
	 * field (prd_price)
	 *
	 * @ORM\Column(type="decimal" , precision=15 , scale=4 , nullable=true)
	 */
	private $price = 0.0000;
	
	/**
	 * field (prd_price)
	 *
	 * @ORM\Column(type="decimal" , precision=15 , scale=4 , nullable=true)
	 */
	private $subtotal = 0.0000;
	
	/**
	 * @ORM\Column(type="boolean")
	 */
	public $status = 0;
	
	/**
	 * @ORM\Column(type="smallint")
	 */
	public $service_status = 0;
	
	/**
	 * @ORM\Column(type="integer")
	 */
	public $timer_days;
	
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
	 * @Gedmo\Timestampable(on="update")
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
	 * xddfgsdsgsdfgsfdgsfdg
	 * Will not be called by Doctrine and can therefore be used
	 * for own implementation purposes. It is also possible to add
	 * arbitrary arguments as with every other class method.
	 */
	public function __construct() {
		/*
		 * $this->id = 1;
		 * $this->_idFields = array('id');
		 * $this->initValidator();
		 * $this->_hasUniqueSlug = false;
		 * $this->products = new ArrayCollection();
		 *
		 */
		// $this->shop_areas = new ArrayCollection();
	}
	public function geBasket_id() {
		return $this->basket_id;
	}
	public function getPlugin() {
		return $this->plugin;
	}
	public function setPlugin(ZSELEX_Entity_Plugin $plugin) {
		$this->plugin = $plugin;
	}
	public function getType() {
		return $this->type;
	}
	public function setType($type) {
		$this->type = $type;
	}
	public function getShop() {
		return $this->shop;
	}
	public function setShop(ZSELEX_Entity_Shop $shop) {
		$this->shop = $shop;
	}
	
	/**
	 * Get user id.
	 */
	public function getUser_id() {
		return $this->user_id;
	}
	
	/**
	 */
	
	public function setUser_id($user_id) {
		$this->user_id = $user_id;
	}
	
	/**
	 * Get owner_id.
	 *
	 * @return integer
	 */
	public function getOwner_id() {
		return $this->owner_id;
	}
	
	/**
	 * Set owner_id.
	 *
	 * @param
	 *        	integer owner_id.
	 *        	
	 */
	public function setOwner_id($owner_id) {
		$this->owner_id = $owner_id;
	}
	
	/**
	 * Get quantity.
	 * 
	 * @return integer
	 */
	public function getQuantity() {
		return $this->quantity;
	}
	public function setQuantity($quantity) {
		$this->quantity = $quantity;
	}
	public function getQty_based() {
		return $this->qty_based;
	}
	public function setQty_based($qty_based) {
		$this->qty_based = $qty_based;
	}
	public function getIs_bundle() {
		return $this->is_bundle;
	}
	public function setIs_bundle($is_bundle) {
		$this->is_bundle = $is_bundle;
	}
	public function getBundle() {
		return $this->bundle;
	}
	public function setBundle(ZSELEX_Entity_Bundle $bundle) {
		$this->bundle = $bundle;
	}
	public function getTop_bundle() {
		return $this->top_bundle;
	}
	public function setTop_bundle($top_bundle) {
		$this->top_bundle = $top_bundle;
	}
	public function getBundle_type() {
		return $this->bundle_type;
	}
	public function setBundle_type($bundle_type) {
		$this->bundle_type = $bundle_type;
	}
	public function getOriginal_price() {
		return $this->original_price;
	}
	public function setOriginal_price($original_price) {
		$this->original_price = $original_price;
	}
	public function getPrice() {
		return $this->price;
	}
	public function setPrice($price) {
		$this->price = $price;
	}
	public function getSubtotal() {
		return $this->subtotal;
	}
	public function setSubtotal($subtotal) {
		$this->subtotal = $subtotal;
	}
	
	/**
	 * Get status.
	 * 
	 * @return integer
	 */
	public function getStatus() {
		return $this->status;
	}
	
	/**
	 * Set quantity.
	 */
	public function setStatus($status) {
		$this->status = $status;
	}
	
	/**
	 * Get service_status.
	 * 
	 * @return integer
	 */
	public function getService_status() {
		return $this->service_status;
	}
	
	/**
	 * Set service_status.
	 */
	public function setService_status($service_status) {
		$this->service_status = $service_status;
	}
	
	/**
	 * Set timer_date.
	 */
	public function setTimer_days($timer_days) {
		$this->timer_days = $timer_days;
	}
	
	/**
	 * Get timer_date.
	 * 
	 * @return string
	 */
	public function getTimer_days() {
		return $this->timer_days;
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
