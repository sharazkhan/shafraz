<?php

namespace DoctrineProxy;

/**
 * THIS CLASS WAS GENERATED BY THE DOCTRINE ORM. DO NOT EDIT THIS FILE.
 */
class ZSELEX_Entity_ShopProxy extends \ZSELEX_Entity_Shop implements \Doctrine\ORM\Proxy\Proxy
{
    private $_entityPersister;
    private $_identifier;
    public $__isInitialized__ = false;
    public function __construct($entityPersister, $identifier)
    {
        $this->_entityPersister = $entityPersister;
        $this->_identifier = $identifier;
    }
    /** @private */
    public function __load()
    {
        if (!$this->__isInitialized__ && $this->_entityPersister) {
            $this->__isInitialized__ = true;

            if (method_exists($this, "__wakeup")) {
                // call this after __isInitialized__to avoid infinite recursion
                // but before loading to emulate what ClassMetadata::newInstance()
                // provides.
                $this->__wakeup();
            }

            if ($this->_entityPersister->load($this->_identifier, $this) === null) {
                throw new \Doctrine\ORM\EntityNotFoundException();
            }
            unset($this->_entityPersister, $this->_identifier);
        }
    }
    
    
    public function addCategory(\ZSELEX_Entity_Category $category = NULL)
    {
        $this->__load();
        return parent::addCategory($category);
    }

    public function removeCategory(\ZSELEX_Entity_Category $category)
    {
        $this->__load();
        return parent::removeCategory($category);
    }

    public function addBranch(\ZSELEX_Entity_Branch $branch = NULL)
    {
        $this->__load();
        return parent::addBranch($branch);
    }

    public function removeBranch(\ZSELEX_Entity_Branch $branch)
    {
        $this->__load();
        return parent::removeBranch($branch);
    }

    public function __toString()
    {
        $this->__load();
        return parent::__toString();
    }

    public function deleteCategory($shop_id)
    {
        $this->__load();
        return parent::deleteCategory($shop_id);
    }

    public function removeChild(\ZSELEX_Entity_Shop $shop)
    {
        $this->__load();
        return parent::removeChild($shop);
    }

    public function getShop_id()
    {
        $this->__load();
        return parent::getShop_id();
    }

    public function getTitle()
    {
        $this->__load();
        return parent::getTitle();
    }

    public function setTitle($title)
    {
        $this->__load();
        return parent::setTitle($title);
    }

    public function getUrltitle()
    {
        $this->__load();
        return parent::getUrltitle();
    }

    public function setUrltitle($urltitle)
    {
        $this->__load();
        return parent::setUrltitle($urltitle);
    }

    public function getUser_id()
    {
        $this->__load();
        return parent::getUser_id();
    }

    public function setUser_id($user_id)
    {
        $this->__load();
        return parent::setUser_id($user_id);
    }

    public function getCountry()
    {
        $this->__load();
        return parent::getCountry();
    }

    public function setCountry(\ZSELEX_Entity_Country $country)
    {
        $this->__load();
        return parent::setCountry($country);
    }

    public function getRegion()
    {
        $this->__load();
        return parent::getRegion();
    }

    public function setRegion(\ZSELEX_Entity_Region $region)
    {
        $this->__load();
        return parent::setRegion($region);
    }

    public function getCity()
    {
        $this->__load();
        return parent::getCity();
    }

    public function setCity(\ZSELEX_Entity_City $city)
    {
        $this->__load();
        return parent::setCity($city);
    }

    public function getArea()
    {
        $this->__load();
        return parent::getArea();
    }

    public function setArea(\ZSELEX_Entity_Area $area)
    {
        $this->__load();
        return parent::setArea($area);
    }

    public function getCategory()
    {
        $this->__load();
        return parent::getCategory();
    }

    public function setCategory(\ZSELEX_Entity_Category $category)
    {
        $this->__load();
        return parent::setCategory($category);
    }

    public function getBranch()
    {
        $this->__load();
        return parent::getBranch();
    }

    public function setBranch(\ZSELEX_Entity_Branch $branch)
    {
        $this->__load();
        return parent::setBranch($branch);
    }

    public function getTheme()
    {
        $this->__load();
        return parent::getTheme();
    }

    public function setTheme($theme)
    {
        $this->__load();
        return parent::setTheme($theme);
    }

    public function getShop_name()
    {
        $this->__load();
        return parent::getShop_name();
    }

    public function setShop_name($shop_name)
    {
        $this->__load();
        return parent::setShop_name($shop_name);
    }

    public function getDescription()
    {
        $this->__load();
        return parent::getDescription();
    }

    public function setDescription($description)
    {
        $this->__load();
        return parent::setDescription($description);
    }

    public function getShop_info()
    {
        $this->__load();
        return parent::getShop_info();
    }

    public function setShop_info($shop_info)
    {
        $this->__load();
        return parent::setShop_info($shop_info);
    }

    public function getAddress()
    {
        $this->__load();
        return parent::getAddress();
    }

    public function setAddress($address)
    {
        $this->__load();
        return parent::setAddress($address);
    }

    public function getTelephone()
    {
        $this->__load();
        return parent::getTelephone();
    }

    public function setTelephone($telephone)
    {
        $this->__load();
        return parent::setTelephone($telephone);
    }

    public function getFax()
    {
        $this->__load();
        return parent::getFax();
    }

    public function setFax($fax)
    {
        $this->__load();
        return parent::setFax($fax);
    }

    public function getEmail()
    {
        $this->__load();
        return parent::getEmail();
    }

    public function setEmail($email)
    {
        $this->__load();
        return parent::setEmail($email);
    }

    public function getOpening_hours()
    {
        $this->__load();
        return parent::getOpening_hours();
    }

    public function setOpening_hours($opening_hours)
    {
        $this->__load();
        return parent::setOpening_hours($opening_hours);
    }

    public function getPictures()
    {
        $this->__load();
        return parent::getPictures();
    }

    public function setPictures($pictures)
    {
        $this->__load();
        return parent::setPictures($pictures);
    }

    public function getDefault_img_frm()
    {
        $this->__load();
        return parent::getDefault_img_frm();
    }

    public function setDefault_img_frm($default_img_frm)
    {
        $this->__load();
        return parent::setDefault_img_frm($default_img_frm);
    }

    public function getMain()
    {
        $this->__load();
        return parent::getMain();
    }

    public function setMain($main)
    {
        $this->__load();
        return parent::setMain($main);
    }

    public function getMeta_tag()
    {
        $this->__load();
        return parent::getMeta_tag();
    }

    public function setMeta_tag($meta_tag)
    {
        $this->__load();
        return parent::setMeta_tag($meta_tag);
    }

    public function getMeta_description()
    {
        $this->__load();
        return parent::getMeta_description();
    }

    public function setMeta_description($meta_description)
    {
        $this->__load();
        return parent::setMeta_description($meta_description);
    }

    public function getLink_to_homepage()
    {
        $this->__load();
        return parent::getLink_to_homepage();
    }

    public function setLink_to_homepage($link_to_homepage)
    {
        $this->__load();
        return parent::setLink_to_homepage($link_to_homepage);
    }

    public function getLink_to_mailinglist()
    {
        $this->__load();
        return parent::getLink_to_mailinglist();
    }

    public function setLink_to_mailinglist($link_to_mailinglist)
    {
        $this->__load();
        return parent::setLink_to_mailinglist($link_to_mailinglist);
    }

    public function setTerms_conditions($terms_conditions)
    {
        $this->__load();
        return parent::setTerms_conditions($terms_conditions);
    }

    public function getTerms_conditions()
    {
        $this->__load();
        return parent::getTerms_conditions();
    }

    public function getShoptype_id()
    {
        $this->__load();
        return parent::getShoptype_id();
    }

    public function setShoptype_id($shoptype_id)
    {
        $this->__load();
        return parent::setShoptype_id($shoptype_id);
    }

    public function getEnable_checkoutinfo()
    {
        $this->__load();
        return parent::getEnable_checkoutinfo();
    }

    public function setCheckout_info($checkout_info)
    {
        $this->__load();
        return parent::setCheckout_info($checkout_info);
    }

    public function getAff_id()
    {
        $this->__load();
        return parent::getAff_id();
    }

    public function setAff_id(\ZSELEX_Entity_ShopAffiliation $aff_id)
    {
        $this->__load();
        return parent::setAff_id($aff_id);
    }

    public function setVat_number($vat_number)
    {
        $this->__load();
        return parent::setVat_number($vat_number);
    }

    public function getVat_number()
    {
        $this->__load();
        return parent::getVat_number();
    }

    public function getDelivery_time()
    {
        $this->__load();
        return parent::getDelivery_time();
    }

    public function setDelivery_time($delivery_time)
    {
        $this->__load();
        return parent::setDelivery_time($delivery_time);
    }

    public function getShop_to_category()
    {
        $this->__load();
        return parent::getShop_to_category();
    }

    public function getStatus()
    {
        $this->__load();
        return parent::getStatus();
    }

    public function setStatus($status)
    {
        $this->__load();
        return parent::setStatus($status);
    }

    public function getCr_date()
    {
        $this->__load();
        return parent::getCr_date();
    }

    public function setCr_date($cr_date)
    {
        $this->__load();
        return parent::setCr_date($cr_date);
    }

    public function getCr_uid()
    {
        $this->__load();
        return parent::getCr_uid();
    }

    public function setCr_uid($cr_uid)
    {
        $this->__load();
        return parent::setCr_uid($cr_uid);
    }

    public function getLu_date()
    {
        $this->__load();
        return parent::getLu_date();
    }

    public function setLu_date($lu_date)
    {
        $this->__load();
        return parent::setLu_date($lu_date);
    }

    public function getLu_uid()
    {
        $this->__load();
        return parent::getLu_uid();
    }

    public function setLu_uid($lu_uid)
    {
        $this->__load();
        return parent::setLu_uid($lu_uid);
    }

    public function getShop_products()
    {
        $this->__load();
        return parent::getShop_products();
    }

    public function setShop_products($shop_products)
    {
        $this->__load();
        return parent::setShop_products($shop_products);
    }

    public function getShop_prod_categories()
    {
        $this->__load();
        return parent::getShop_prod_categories();
    }

    public function setShop_prod_categories($shop_prod_categories)
    {
        $this->__load();
        return parent::setShop_prod_categories($shop_prod_categories);
    }

    public function getShop_settings()
    {
        $this->__load();
        return parent::getShop_settings();
    }

    public function setShop_settings($shop_settings)
    {
        $this->__load();
        return parent::setShop_settings($shop_settings);
    }

    public function getShop_events()
    {
        $this->__load();
        return parent::getShop_events();
    }

    public function setShop_events($shop_events)
    {
        $this->__load();
        return parent::setShop_events($shop_events);
    }

    public function addShop(\ZSELEX_Entity_ShopSetting $shop_setting)
    {
        $this->__load();
        return parent::addShop($shop_setting);
    }

    public function removeShop(\ZSELEX_Entity_ShopSetting $shop_setting)
    {
        $this->__load();
        return parent::removeShop($shop_setting);
    }

    public function getPurchase_collect_stat()
    {
        $this->__load();
        return parent::getPurchase_collect_stat();
    }

    public function setPurchase_collect_stat($purchase_collect_stat)
    {
        $this->__load();
        return parent::setPurchase_collect_stat($purchase_collect_stat);
    }

    public function getEmail_purchase_tries()
    {
        $this->__load();
        return parent::getEmail_purchase_tries();
    }

    public function setEmail_purchase_tries($email_purchase_tries)
    {
        $this->__load();
        return parent::setEmail_purchase_tries($email_purchase_tries);
    }

    public function getAdvertise_sel_prods()
    {
        $this->__load();
        return parent::getAdvertise_sel_prods();
    }

    public function setAdvertise_sel_prods($advertiseSelProds)
    {
        $this->__load();
        return parent::setAdvertise_sel_prods($advertiseSelProds);
    }

    public function getReflection()
    {
        $this->__load();
        return parent::getReflection();
    }

    public function offsetExists($key)
    {
        $this->__load();
        return parent::offsetExists($key);
    }

    public function offsetGet($key)
    {
        $this->__load();
        return parent::offsetGet($key);
    }

    public function offsetSet($key, $value)
    {
        $this->__load();
        return parent::offsetSet($key, $value);
    }

    public function offsetUnset($key)
    {
        $this->__load();
        return parent::offsetUnset($key);
    }

    public function toArray()
    {
        $this->__load();
        return parent::toArray();
    }

    public function merge(array $array)
    {
        $this->__load();
        return parent::merge($array);
    }


    public function __sleep()
    {
        return array('__isInitialized__', 'shop_id', 'shop_name', 'title', 'urltitle', 'user_id', 'country', 'region', 'city', 'area', 'branch', 'description', 'theme', 'shop_info', 'address', 'telephone', 'fax', 'email', 'opening_hours', 'pictures', 'default_img_frm', 'main', 'meta_tag', 'meta_description', 'aff_id', 'vat_number', 'advertise_sel_prods', 'delivery_time', 'shoptype_id', 'link_to_homepage', 'link_to_mailinglist', 'terms_conditions', 'purchase_collect_stat', 'email_purchase_tries', 'status', 'cr_date', 'cr_uid', 'lu_date', 'lu_uid', 'shop_products', 'shop_prod_categories', 'service_shops', 'shop_service_bundles', 'shop_service_demo_bundles', 'shop_events', 'minishops', 'shop_ads', 'shop_owners', 'shop_admins', 'shop_ratings', 'shop_keywords', 'shop_images', 'shop_gallery', 'shop_zencarts', 'shop_options', 'shop_option_values', 'shop_banners', 'shop_announcements', 'shop_pdfs', 'shop_employees', 'shop_manufacturers', 'shop_to_category', 'shop_to_branch', 'shop_pages');
    }

    public function __clone()
    {
        if (!$this->__isInitialized__ && $this->_entityPersister) {
            $this->__isInitialized__ = true;
            $class = $this->_entityPersister->getClassMetadata();
            $original = $this->_entityPersister->load($this->_identifier);
            if ($original === null) {
                throw new \Doctrine\ORM\EntityNotFoundException();
            }
            foreach ($class->reflFields AS $field => $reflProperty) {
                $reflProperty->setValue($this, $reflProperty->getValue($original));
            }
            unset($this->_entityPersister, $this->_identifier);
        }
        
    }
}