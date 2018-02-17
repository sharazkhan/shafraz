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
 * @ORM\Entity(repositoryClass="Zvelo_Entity_Repository_CustomerMeasurementRepo")
 * @ORM\Table(name="zvelo_customer_measurement")
 */
class Zvelo_Entity_CustomerMeasurement extends Zikula_EntityAccess {

    /**
     * id field (Customer id)
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $measurement_id;

    /**
     * @ORM\ManyToOne(targetEntity="Zvelo_Entity_Customer",  inversedBy="customer_measurements")
     * @ORM\JoinColumn(name="customer_id", referencedColumnName="customer_id" , onDelete="CASCADE")
     */
    private $customer;

    /**
     * @ORM\Column(length="255")
     */
    private $functionalheight;

    /**
     * @ORM\Column(length="255")
     */
    private $shoulderheight;

    /**
     * @ORM\Column(length="255")
     */
    private $shoulderwidth;

    /**
     * @ORM\Column(length="255")
     */
    private $fistheight;

    /**
     * @ORM\Column(length="255")
     */
    private $pelvicboneheight;

    /**
     * @ORM\Column(length="255")
     */
    private $weight;

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

        // the fourth arg is forceLang and if left to default (true) then the url is malformed - core bug as of 1.3.0
    }

    /**
     * get the client ID
     * @return integer
     */
    public function getMeasurement_id() {
        return $this->measurement_id;
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

    public function getFunctionalheight() {
        return $this->functionalheight;
    }

    public function setFunctionalheight($functionalheight) {
        $this->functionalheight = $functionalheight;
    }

    public function getShoulderheight() {
        return $this->shoulderheight;
    }

    public function setShoulderheight($shoulderheight) {
        $this->shoulderheight = $shoulderheight;
    }

    public function getShoulderwidth() {
        return $this->shoulderwidth;
    }

    public function setShoulderwidth($shoulderwidth) {
        $this->shoulderwidth = $shoulderwidth;
    }

    public function getFistheight() {
        return $this->fistheight;
    }

    public function setFistheight($fistheight) {
        $this->fistheight = $fistheight;
    }

    public function getPelvicboneheight() {
        return $this->pelvicboneheight;
    }

    public function setPelvicboneheight($pelvicboneheight) {
        $this->pelvicboneheight = $pelvicboneheight;
    }

    public function getWeight() {
        return $this->weight;
    }

    public function setWeight($weight) {
        $this->weight = $weight;
    }

    public function getComments() {
        return $this->comments;
    }

    public function setComments($comments) {
        $this->comments = $comments;
    }

}
