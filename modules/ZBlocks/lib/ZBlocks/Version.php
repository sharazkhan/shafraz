<?php

/**
 * Copyright ACTA-IT 2015 - ZBlocks
 *
 * ZBlocks
 * Block module
 *
 * @license GNU/LGPLv3 (or at your option, any later version).
 */

/**
 * Class to control Version information
 */
class ZBlocks_Version extends Zikula_AbstractVersion {

    public function getMetaData() {
        $meta = array();
        $meta['displayname'] = $this->__('ZBlocks');
        $meta['url'] = $this->__(/* !used in URL - nospaces, no special chars, lcase */'ZBlocks');
        $meta['description'] = $this->__('ZBlocks block module!');
        $meta['version'] = '0.0.1';

        $meta['securityschema'] = array(
            'ZBlocks::' => '::');
        $meta['core_min'] = '1.3.0'; // requires minimum 1.3.0 or later
        //$meta['core_max'] = '1.3.0'; // doesn't work with versions later than x.x.x

        return $meta;
    }

}

// end class def