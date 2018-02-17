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
 * @ORM\Entity(repositoryClass="Zvelo_Entity_Repository_CustomerErgonomicValueRepo")
 * @ORM\Table(name="zvelo_customer_ergonomic_value")
 */
class Zvelo_Entity_CustomerErgonomicValue extends Zikula_EntityAccess {

    /**
     * id field (Customer id)
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $ergonomic_value_id;

    /**
     * @ORM\ManyToOne(targetEntity="Zvelo_Entity_Customer", cascade={"all"}, inversedBy="customer_ergonomic_values")
     * @ORM\JoinColumn(name="customer_id", referencedColumnName="customer_id" ,  onDelete="CASCADE")
     */
    private $customer;

    /**
     * @ORM\ManyToOne(targetEntity="Zvelo_Entity_Bicycle", cascade={"all"}, inversedBy="bicycle_customer_ergonomic_values")
     * @ORM\JoinColumn(name="bicycle_id", referencedColumnName="bicycle_id")
     */
    private $bicycle;

    /**
     * @ORM\Column(length="255")
     */
    private $value1;

    /**
     * @ORM\Column(length="255")
     */
    private $value2;

    /**
     * @ORM\Column(length="255")
     */
    private $value3;

    /**
     * @ORM\Column(length="255")
     */
    private $value4;

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

        // the fourth arg is forceLang and if left to default (true) then the url is malformed - core bug as of 1.3.0
    }

    /**
     * get the client ID
     * @return integer
     */
    public function getErgonomic_value_id() {
        return $this->ergonomic_value_id;
    }

    /**
     * get the customer
     * @return int $customer 
     */
    public function getCustomer() {
        return $this->customer;
    }

    /**
     * set the customer
     * @param int $customer 
     */
    public function setCustomer(Zvelo_Entity_Customer $customer) {
        $this->customer = $customer;
    }

    public function getBicycle() {
        return $this->bicycle;
    }

    public function setBicycle(Zvelo_Entity_Bicycle $bicycle) {
        $this->bicycle = $bicycle;
    }

    public function getValue1() {
        return $this->value1;
    }

    public function setValue1($value1) {
        $this->value1 = $value1;
    }

    public function getValue2() {
        return $this->value2;
    }

    public function setValue2($value2) {
        $this->value2 = $value2;
    }

    public function getValue3() {
        return $this->value3;
    }

    public function setValue3($value3) {
        $this->value3 = $value3;
    }

    public function getValue4() {
        return $this->value4;
    }

    public function setValue4($value4) {
        $this->value4 = $value4;
    }

}
