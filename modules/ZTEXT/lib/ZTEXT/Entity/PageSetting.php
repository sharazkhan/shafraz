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
 * @ORM\Entity(repositoryClass="ZTEXT_Entity_Repository_PageRepository")
 * @ORM\Table(name="ztext_settings")
 */
class ZTEXT_Entity_PageSetting extends Zikula_EntityAccess {

    /**
     * id field
     *
     * @ORM\Id
     * @ORM\Column(type="bigint")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * shop_id
     * 
     * @ORM\ManyToOne(targetEntity="ZSELEX_Entity_Shop")
     * @ORM\JoinColumn(name="shop_id", referencedColumnName="shop_id")
     */
    public $shop;

    /**
     * Active
     * 
     * @ORM\Column(type="boolean" , nullable=true) 
     */
    private $disable_page_index = 0;

    /**
     * Active
     * 
     * @ORM\Column(type="boolean" , nullable=true) 
     */
    private $disable_frontend_image = 0;

    /**
     * Constructor 
     */
    public function __construct() {

        //$this->shop_branches = new ArrayCollection();
    }

    /**
     * get the record ID
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * get the branch_name
     * @return string
     */
    public function getShop() {
        return $this->shop;
    }

    /**
     * set the shop_id
     * @param string $branch_name
     */
    public function setShop(ZSELEX_Entity_Shop $shop) {
        $this->shop = $shop;
    }

    /**
     * get the record ID
     * @return integer
     */
    public function getDisable_page_index() {
        return $this->disable_page_index;
    }

    /**
     * set the Country Desc
     * @param string $module 
     */
    public function setDisable_page_index($disable_page_index) {
        $this->disable_page_index = $disable_page_index;
    }

    public function getDisable_frontend_image() {
        return $this->disable_frontend_image;
    }

    public function setDisable_frontend_image($disable_frontend_image) {
        $this->disable_frontend_image = $disable_frontend_image;
    }

}
