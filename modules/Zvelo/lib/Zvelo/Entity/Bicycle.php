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
 * @ORM\Entity(repositoryClass="Zvelo_Entity_Repository_BicycleRepo")
 * @ORM\Table(name="zvelo_bicycle")
 */
class Zvelo_Entity_Bicycle extends Zikula_EntityAccess {

    /**
     * id field (Customer id)
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $bicycle_id;

    /**
     * @ORM\Column(length="255")
     */
    private $name;

    /**
     * @ORM\Column(length="255")
     */
    private $nos;

    /**
     * @ORM\Column(length="255")
     */
    private $iconname;

    /**
     * @ORM\Column(length="255")
     */
    private $imagename;

    /**
     * @ORM\Column(length="255")
     */
    private $imagename2;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

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
     * @ORM\OneToMany(targetEntity="Zvelo_Entity_CustomerWish" , mappedBy="bicycle")
     */
    public $bicycle_customers_wishes;

    /**
     * @ORM\OneToMany(targetEntity="Zvelo_Entity_CustomerErgonomicValue" , mappedBy="bicycle")
     */
    public $bicycle_customer_ergonomic_values;

    public function __construct() {

        // the fourth arg is forceLang and if left to default (true) then the url is malformed - core bug as of 1.3.0
    }

    /**
     * get the client ID
     * @return integer
     */
    public function getBicycle_id() {
        return $this->bicycle_id;
    }

    /**
     * get the customer
     * @return int $customer 
     */
    public function getName() {
        return $this->name;
    }

    /**
     * set the customer
     * @param int $customer 
     */
    public function setName($name) {
        $this->name = $name;
    }

    public function getNos() {
        return $this->nos;
    }

    public function setNos($nos) {
        $this->nos = $nos;
    }

    public function getIconname() {
        return $this->iconname;
    }

    public function setIconname($iconname) {
        $this->iconname = $iconname;
    }

    public function getImagename() {
        return $this->imagename;
    }

    public function setImagename2($imagename2) {
        $this->imagename2 = $imagename2;
    }

    public function getImagename2() {
        return $this->imagename2;
    }

    public function setImagename($imagename) {
        $this->imagename = $imagename;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function getBicycle_customers_wishes() {
        return $this->bicycle_customers_wishes;
    }

    public function setBicycle_customers_wishes($bicycle_customers_wishes) {
        $this->bicycle_customers_wishes = $bicycle_customers_wishes;
    }

}
