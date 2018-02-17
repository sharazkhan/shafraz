<?php

/**
 * Copyright ACTA-IT 2014 - ZPayment
 *
 * ZPayment
 * Payment Gateway Module
 *
 * @license GNU/LGPLv3 (or at your option, any later version).
 */

/**
 * Class to control Version information
 */
class ZPayment_Version extends Zikula_AbstractVersion {

    public function getMetaData() {
        $meta = array();
        $meta['displayname'] = $this->__('ZPayment');
        $meta['url'] = $this->__(/* !used in URL - nospaces, no special chars, lcase */'ZPayment');
        $meta['description'] = $this->__('ZPayment payment module!');
        $meta['version'] = '1.0.2';

        $meta['securityschema'] = array(
            'ZPayment::' => '::');
        $meta['core_min'] = '1.3.0'; // requires minimum 1.3.0 or later
        //$meta['core_max'] = '1.3.0'; // doesn't work with versions later than x.x.x

        return $meta;
    }

}

// end class def