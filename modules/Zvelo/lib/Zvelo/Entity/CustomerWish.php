<?php

/**
 * Zvelo
 */
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use DoctrineExtensions\StandardFields\Mapping\Annotation as ZK;

//use Gedmo\Mapping\Annotation as Gedmo; // Add behaviors

/**
 *
 * Annotations define the entity mappings to database.
 *
 * @ORM\Entity(repositoryClass="Zvelo_Entity_Repository_CustomerWishRepo")
 * @ORM\Table(name="zvelo_customer_wish")
 */
class Zvelo_Entity_CustomerWish extends Zikula_EntityAccess {

    /**
     * id field (wish id)
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $wish_id;

    /**
     * @ORM\ManyToOne(targetEntity="Zvelo_Entity_Customer", cascade={"all"}, inversedBy="customer_wishes")
     * @ORM\JoinColumn(name="customer_id", referencedColumnName="customer_id" ,  onDelete="CASCADE")
     */
    private $customer;

    /**
     * @ORM\ManyToOne(targetEntity="Zvelo_Entity_Bicycle", cascade={"all"}, inversedBy="bicycle_customers_wishes")
     * @ORM\JoinColumn(name="bicycle_id", referencedColumnName="bicycle_id")
     */
    private $bicycle;

    /**
     * @ORM\Column(type="integer" , nullable=true)
     */
    private $seatposition;

    /**
     * @ORM\Column(length=255 , nullable=true)
     */
    private $usages;

    /**
     * @ORM\Column(length=255 , nullable=true)
     */
    private $ageclass;

    /**
     * @ORM\Column(length=255 , nullable=true)
     */
    private $kmmonthly;

    /**
     * @ORM\Column(length=255 , nullable=true)
     */
    private $framematerial;

    /**
     * @ORM\Column(length=255 , nullable=true)
     */
    private $frametype;

    /**
     * @ORM\Column(length=255 , nullable=true)
     */
    private $suspension;

    /**
     * @ORM\Column(length=255 , nullable=true)
     */
    private $gears;

    /**
     * @ORM\Column(length=255 , nullable=true)
     */
    private $brakes;

    /**
     * @ORM\Column(length=255 , nullable=true)
     */
    private $accessories;

    /**
     * @ORM\Column(type="text" , nullable=true)
     */
    private $comments;

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="create")
     * @var datetime $cr_date.
     */
    private $cr_date;

    /**
     * @ORM\Column(type="integer")
     * @ZK\StandardFields(type="userid", on="create")
     * @var integer $cr_uid.
     */
    private $cr_uid;

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="create")
     * @var datetime $lu_date.
     */
    private $lu_date;

    /**
     * @ORM\Column(type="integer")
     * @ZK\StandardFields(type="userid", on="update")
     * @var integer $lu_uid.
     */
    private $lu_uid;

    public function __construct() {
        // $this->tags = new ArrayCollection();
        // the fourth arg is forceLang and if left to default (true) then the url is malformed - core bug as of 1.3.0
    }

    /**
     * get the client ID
     * @return integer
     */
    public function getWish_id() {
        return $this->wish_id;
    }

    /**
     * get the gender
     * @return string $gender 
     */
    public function getCustomer() {
        return $this->customer;
    }

    /**
     * set the gender
     * @param string $gender 
     */
    public function setCustomer(Zvelo_Entity_Customer $customer) {
        $this->customer = $customer;
    }

    /**
     * get the first name
     * @return string first_name
     */
    public function getBicycle() {
        return $this->bicycle;
    }

    /**
     * set the module name
     * @param string $module 
     */
    public function setBicycle(Zvelo_Entity_Bicycle $bicycle) {
        $this->bicycle = $bicycle;
    }

    /**
     * get the Hook Area ID
     * @return integer
     */
    public function getSeatposition() {
        return $this->seatposition;
    }

    /**
     * Set the Hook Area ID
     * @param integer $areaId 
     */
    public function setSeatposition($seatposition) {
        $this->seatposition = $seatposition;
    }

    /**
     * Get the hooked object ID
     * @return integer
     */
    public function getUsages() {
        return $this->usages;
    }

    /**
     * Set the hooked object ID
     * @param integer $objectId 
     */
    public function setUsages($usages) {
        $this->usages = $usages;
    }

    /**
     * @deprecated since Tag version 1.0.2
     * @return string 
     */
    public function getAgeclass() {
        return $this->ageclass;
    }

    /**
     * @deprecated since Tag version 1.0.2
     * @param string $url
     */
    public function setAgeclass($ageclass) {
        $this->ageclass = $ageclass;
    }

    /**
     * get the hooked object UrlObject
     * @return Zikula_ModUrl
     */
    public function getZipcode() {
        return $this->zipcode;
    }

    /**
     * set the hooked object UrlObject
     * @param Zikula_ModUrl $urlObject 
     */
    public function setZipcode($zipcode) {
        $this->zipcode = $zipcode;
    }

    /**
     * get the hooked object UrlObject
     * @return Zikula_ModUrl
     */
    public function getKmmonthly() {
        return $this->kmmonthly;
    }

    /**
     * set the hooked object UrlObject
     * @param Zikula_ModUrl $urlObject 
     */
    public function setKmmonthly($kmmonthly) {
        $this->kmmonthly = $kmmonthly;
    }

    /**
     * get the hooked object UrlObject
     * @return Zikula_ModUrl
     */
    public function getFramematerial() {
        return $this->framematerial;
    }

    /**
     * set the hooked object UrlObject
     * @param Zikula_ModUrl $urlObject 
     */
    public function setFramematerial($framematerial) {
        $this->framematerial = $framematerial;
    }

    /**
     * get the hooked object UrlObject
     * @return Zikula_ModUrl
     */
    public function getFrametype() {
        return $this->frametype;
    }

    /**
     * set the hooked object UrlObject
     * @param Zikula_ModUrl $urlObject 
     */
    public function setFrametype($frametype) {
        $this->frametype = $frametype;
    }

    /**
     * get the hooked object UrlObject
     * @return Zikula_ModUrl
     */
    public function getSuspension() {
        return $this->suspension;
    }

    /**
     * set the hooked object UrlObject
     * @param Zikula_ModUrl $urlObject 
     */
    public function setSuspension($suspension) {
        $this->suspension = $suspension;
    }

    /**
     * get the hooked object UrlObject
     * @return Zikula_ModUrl
     */
    public function getGears() {
        return $this->gears;
    }

    /**
     * set the hooked object UrlObject
     * @param Zikula_ModUrl $urlObject 
     */
    public function setGears($gears) {
        $this->gears = $gears;
    }

    /**
     * get the hooked object UrlObject
     * @return Zikula_ModUrl
     */
    public function getBrakes() {
        return $this->brakes;
    }

    /**
     * set the hooked object UrlObject
     * @param Zikula_ModUrl $urlObject 
     */
    public function setBrakes($brakes) {
        $this->brakes = $brakes;
    }

    /**
     * get the hooked object UrlObject
     * @return Zikula_ModUrl
     */
    public function getAccessories() {
        return $this->accessories;
    }

    /**
     * set the hooked object UrlObject
     * @param Zikula_ModUrl $urlObject 
     */
    public function setAccessories($accessories) {
        $this->accessories = $accessories;
    }

    /**
     * get the hooked object UrlObject
     * @return Zikula_ModUrl
     */
    public function getComments() {
        return $this->comments;
    }

    /**
     * set the hooked object UrlObject
     * @param Zikula_ModUrl $urlObject 
     */
    public function setComments($comments) {
        $this->comments = $comments;
    }

}
