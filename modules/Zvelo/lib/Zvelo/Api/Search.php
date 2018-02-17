<?php

/**
 * Tag - a content-tagging module for the Zikukla Application Framework
 * 
 * @license MIT
 *
 * Please see the NOTICE file distributed with this source code for further
 * information regarding copyright and licensing.
 */
class Zvelo_Api_Search extends Zikula_AbstractApi {

    /**
     * Search plugin info
     */
    public function info() {
        return array('title' => 'Zvelo',
            'functions' => array('tag' => 'search'));
    }

}