<?php

/**
 * Zvelo
 */
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use DoctrineExtensions\StandardFields\Mapping\Annotation as ZK;
use Doctrine\DBAL\LockMode;
use Doctrine\ORM\OptimisticLockException;

//use Gedmo\Mapping\Annotation as Gedmo; // Add behaviors

/**
 *
 * Annotations define the entity mappings to database.
 *
 * @ORM\Entity(repositoryClass="Zvelo_Entity_Repository_CustomerRepo")
 * @ORM\Table(name="zvelo_customer")
 */
class Zvelo_Entity_Customer extends Zikula_EntityAccess {

    /**
     * id field (Customer id)
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $customer_id;

    /**
     * @ORM\Column(length=255 , nullable=true)
     */
    private $gender;

    /**
     * @ORM\Column(length="255" , nullable=true)
     */
    private $first_name;

    /**
     * @ORM\Column(length="255" , nullable=true)
     */
    private $last_name;

    /**
     * @ORM\Column(type="text" , nullable=true)
     */
    private $address;

    /**
     * @ORM\Column(type="text" , nullable=true)
     */
    private $address2;

    /**
     * @ORM\Column(length="255" , nullable=true)
     */
    private $zipcode;

    /**
     * @ORM\Column(length="255" , nullable=true)
     */
    private $city;

    /**
     * @ORM\Column(length="255" , nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(length="255" , nullable=true)
     */
    private $email;

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

    /**
     * @ORM\OneToMany(targetEntity="Zvelo_Entity_CustomerWish" ,  mappedBy="customer")
     */
    public $customer_wishes;

    /**
     * @ORM\OneToMany(targetEntity="Zvelo_Entity_CustomerMeasurement" , mappedBy="customer")
     */
    public $customer_measurements;

    public function __construct() {
        $this->customer_wishes = new ArrayCollection();
        $this->customer_measurements = new ArrayCollection();
        // the fourth arg is forceLang and if left to default (true) then the url is malformed - core bug as of 1.3.0
    }

    /**
     * get the client ID
     * @return integer
     */
    public function getCustomer_id() {
        return $this->customer_id;
    }

    /**
     * get the gender
     * @return string $gender 
     */
    public function getGender() {
        return $this->gender;
    }

    /**
     * set the gender
     * @param string $gender 
     */
    public function setGender($gender) {
        $this->gender = $gender;
    }

    /**
     * get the first name
     * @return string first_name
     */
    public function getFirst_name() {
        return $this->first_name;
    }

    /**
     * set the module name
     * @param string $module 
     */
    public function setFirst_name($first_name) {
        $this->first_name = $first_name;
    }

    /**
     * get the Hook Area ID
     * @return integer
     */
    public function getLast_name() {
        return $this->last_name;
    }

    /**
     * Set the Hook Area ID
     * @param integer $areaId 
     */
    public function setLast_name($last_name) {
        $this->last_name = $last_name;
    }

    /**
     * Get the hooked object ID
     * @return integer
     */
    public function getAddress() {
        return $this->address;
    }

    /**
     * Set the hooked object ID
     * @param integer $objectId 
     */
    public function setAddress($address) {
        $this->address = $address;
    }

    /**
     * @deprecated since Tag version 1.0.2
     * @return string 
     */
    public function getAddress2() {
        return $this->address2;
    }

    /**
     * @deprecated since Tag version 1.0.2
     * @param string $url
     */
    public function setAddress2($address2) {
        $this->address2 = $address2;
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
    public function getCity() {
        return $this->city;
    }

    /**
     * set the hooked object UrlObject
     * @param Zikula_ModUrl $urlObject 
     */
    public function setCity($city) {
        $this->city = $city;
    }

    /**
     * get the hooked object UrlObject
     * @return Zikula_ModUrl
     */
    public function getPhone() {
        return $this->phone;
    }

    /**
     * set the hooked object UrlObject
     * @param Zikula_ModUrl $urlObject 
     */
    public function setPhone($phone) {
        $this->phone = $phone;
    }

    /**
     * get the hooked object UrlObject
     * @return Zikula_ModUrl
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * set the hooked object UrlObject
     * @param Zikula_ModUrl $urlObject 
     */
    public function setEmail($email) {
        $this->email = $email;
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

    /**
     * get the hooked object UrlObject
     * @return Zikula_ModUrl
     */
    public function getCustomer_wishes() {
        return $this->customer_wishes;
    }

    /**
     * set the hooked object UrlObject
     * @param Zikula_ModUrl $urlObject 
     */
    public function setCustomer_wishes($customer_wishes) {
        $this->customer_wishes = $customer_wishes;
    }

}
