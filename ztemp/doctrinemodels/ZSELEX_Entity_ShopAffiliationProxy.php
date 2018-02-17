<?php

namespace DoctrineProxy;

/**
 * THIS CLASS WAS GENERATED BY THE DOCTRINE ORM. DO NOT EDIT THIS FILE.
 */
class ZSELEX_Entity_ShopAffiliationProxy extends \ZSELEX_Entity_ShopAffiliation implements \Doctrine\ORM\Proxy\Proxy
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
    
    
    public function getAff_id()
    {
        $this->__load();
        return parent::getAff_id();
    }

    public function getAff_name()
    {
        $this->__load();
        return parent::getAff_name();
    }

    public function setAff_name($aff_name)
    {
        $this->__load();
        return parent::setAff_name($aff_name);
    }

    public function getAff_image()
    {
        $this->__load();
        return parent::getAff_image();
    }

    public function setAff_image($aff_image)
    {
        $this->__load();
        return parent::setAff_image($aff_image);
    }

    public function getAffiliate_shops()
    {
        $this->__load();
        return parent::getAffiliate_shops();
    }

    public function setAffiliate_shops($affiliate_shops)
    {
        $this->__load();
        return parent::setAffiliate_shops($affiliate_shops);
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
        return array('__isInitialized__', 'aff_id', 'aff_name', 'aff_image', 'sort_order', 'cr_date', 'cr_uid', 'lu_date', 'lu_uid', 'affiliate_shops');
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