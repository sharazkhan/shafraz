<?php

/**
 * Class to control Version information
 */
class ZAPP_Version extends Zikula_AbstractVersion {

    /**
     * Retrieve version and other metadata for the ZAPP module.
     *
     * @return array Metadata for the ZAPP module, as specified by Zikula core.
     */
    public function getMetaData() {
        $meta = array();
        $meta['displayname'] = $this->__('ZAPP');
        $meta['url'] = $this->__(/* !used in URL - nospaces, no special chars, lcase */'app');
        $meta['description'] = $this->__('ZAPP module!');
        $meta['version'] = '0.0.1';

        $meta['securityschema'] = array(
            'ZAPP::' => '::');
        $meta['core_min'] = '1.3.0'; // requires minimum 1.3.0 or later
        //$meta['core_max'] = '1.3.0'; // doesn't work with versions later than x.x.x

        return $meta;
    }

}

// end class def