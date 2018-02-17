<?php

/**
 * Class to control Version information
 */
class ZTEXT_Version extends Zikula_AbstractVersion {

    /**
     * Retrieve version and other metadata for the ZTEXT module.
     *
     * @return array Metadata for the ZTEXT module, as specified by Zikula core.
     */
    public function getMetaData() {
        $meta = array();
        $meta['displayname'] = $this->__('ZTEXT');
        $meta['url'] = $this->__(/* !used in URL - nospaces, no special chars, lcase */'ztext');
        $meta['description'] = $this->__('ZTEXT block module!');
        $meta['version'] = '0.0.5';

        $meta['securityschema'] = array(
            'ZTEXT::' => '::');
        $meta['core_min'] = '1.3.0'; // requires minimum 1.3.0 or later
        //$meta['core_max'] = '1.3.0'; // doesn't work with versions later than x.x.x

        return $meta;
    }

}

// end class def