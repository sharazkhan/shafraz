<?php

/**
 * Zselex_country - a content-tagging module for the Zikukla Application Framework
 * 
 * @license MIT
 *
 * Please see the NOTICE file distributed with this source code for further
 * information regarding copyright and licensing.
 */
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo; // Add behaviours
use DoctrineExtensions\StandardFields\Mapping\Annotation as ZK;

/**
 * Tags entity class.
 *
 * Annotations define the entity mappings to database.
 *
 * @ORM\Entity(repositoryClass="ZSELEX_Entity_Repository_CountryRepository")
 * @ORM\Table(name="zselex_country")
 */
class ZSELEX_Entity_Country extends Zikula_EntityAccess {
	
	/**
	 * id field
	 *
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	public $country_id;
	
	/**
	 * module field (hooked module name)
	 *
	 * @ORM\Column(length=50)
	 */
	private $country_name;
	
	/**
	 * Country description
	 *
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $description;
	
	/**
	 * Event All Day or not
	 *
	 * @ORM\Column(type="boolean")
	 */
	private $status;
	
	/**
	 * @ORM\OneToMany(targetEntity="ZSELEX_Entity_City" ,cascade={"persist"}, mappedBy="country")
	 */
	public $city_countries;
	
	/**
	 * @ORM\OneToMany(targetEntity="ZSELEX_Entity_Region" , mappedBy="country")
	 */
	public $region_countries;
	
	/**
	 * @ORM\OneToMany(targetEntity="ZSELEX_Entity_Area" ,cascade={"all"}, mappedBy="country")
	 */
	public $area_countries;
	
	/**
	 * @ORM\OneToMany(targetEntity="ZSELEX_Entity_Shop" ,cascade={"all"}, mappedBy="country")
	 */
	public $shop_countries;
	
	/**
	 * @ORM\OneToMany(targetEntity="ZSELEX_Entity_Advertise" ,cascade={"all"}, mappedBy="country")
	 */
	public $country_ads;
	
	/**
	 * @ORM\Column(type="datetime")
	 * @Gedmo\Timestampable(on="create")
	 * 
	 * @var datetime $cr_date.
	 */
	protected $cr_date;
	
	/**
	 * @ORM\Column(type="integer")
	 * @ZK\StandardFields(type="userid", on="create")
	 * 
	 * @var integer $cr_uid.
	 */
	protected $cr_uid;
	
	/**
	 * @ORM\Column(type="datetime")
	 * @Gedmo\Timestampable(on="create")
	 * 
	 * @var datetime $lu_date.
	 */
	protected $lu_date;
	
	/**
	 * @ORM\Column(type="integer")
	 * @ZK\StandardFields(type="userid", on="update")
	 * 
	 * @var integer $lu_uid.
	 */
	protected $lu_uid;
	
	/**
	 * Constructor
	 */
	public function __construct() {
		$this->city_countries = new ArrayCollection ();
		$this->region_countries = new ArrayCollection ();
		$this->area_countries = new ArrayCollection ();
		$this->shop_countries = new ArrayCollection ();
		$this->country_ads = new ArrayCollection ();
	}
	
	/**
	 * get the record ID
	 * 
	 * @return integer
	 */
	public function getCountry_id() {
		return $this->country_id;
	}
	
	/**
	 * set the Country ID
	 * 
	 * @param string $module        	
	 */
	public function setCountry_id($country_id) {
		$this->country_id = $country_id;
	}
	public function __toString() {
		return $this->getCountry_id ();
	}
	
	/**
	 * get the record ID
	 * 
	 * @return integer
	 */
	public function getCountry_name() {
		return $this->country_name;
	}
	
	/**
	 * set the Country Name
	 * 
	 * @param string $module        	
	 */
	public function setCountry_name($country_name) {
		$this->country_name = $country_name;
	}
	
	/**
	 * get the record ID
	 * 
	 * @return integer
	 */
	public function getDescription() {
		return $this->description;
	}
	
	/**
	 * set the Country Desc
	 * 
	 * @param string $module        	
	 */
	public function setDescription($description) {
		$this->description = $description;
	}
	
	/**
	 * get the record ID
	 * 
	 * @return integer
	 */
	public function getStatus() {
		return $this->status;
	}
	
	/**
	 * set the Country Status
	 * 
	 * @param string $module        	
	 */
	public function setStatus($status) {
		$this->status = $status;
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
	public function getCity_countries() {
		return $this->city_countries;
	}
	public function setCity_countries($city_countries) {
		$this->city_countries = $city_countries;
	}
	public function getRegion_countries() {
		return $this->region_countries;
	}
	public function setRegion_countries($region_countries) {
		$this->region_countries = $region_countries;
	}
	public function getShop_countries() {
		return $this->shop_countries;
	}
	public function setShop_countries($shop_countries) {
		$this->shop_countries = $shop_countries;
	}
}

