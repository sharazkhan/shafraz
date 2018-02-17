<?php
/**
 * Copyright ACTA-IT 2013 - ZMAP
 *
 * ZMAP
 * Demonstration of Zikula Module
 *
 * @license GNU/LGPLv3 (or at your option, any later version).
 */

/**
 * Class to control Version information
 */
class ZMAP_Version extends Zikula_AbstractVersion
{
    public function getMetaData()
    {
        $meta = array();
        $meta['displayname']    = $this->__('ZMAP');
        $meta['url']            = $this->__(/*!used in URL - nospaces, no special chars, lcase*/'zmap');
        $meta['description']    = $this->__('ZMAP module for map project!');
        $meta['version']        = '1.0.0';

        $meta['securityschema'] = array(
            'ZMAP::'      => '::');
        $meta['core_min']       = '1.3.0'; // requires minimum 1.3.0 or later
        //$meta['core_max'] = '1.3.0'; // doesn't work with versions later than x.x.x

        return $meta;
    }
} // end class def