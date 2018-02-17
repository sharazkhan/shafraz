<?php
/**
 * Copyright  2014
 *
 * ZSELEX
 * Demonstration of Zikula Module
 *
 * @license GNU/LGPLv3 (or at your option, any later version).
 */

/**
 * Class to control Version information
 */
class ZSELEX_Version extends Zikula_AbstractVersion
{ // Kerala

    public function getMetaData()
    {
        $meta                 = array();
        $domain               = ZLanguage::getModuleDomain($meta ['name']);
        $meta ['displayname'] = $this->__('ZSELEX');
        $meta ['url']         = $this->__(/* !used in URL - nospaces, no special chars, lcase */'zselex');
        $meta ['description'] = $this->__('ZSELEX Module!');
        $meta ['version']     = '1.5.43'; // version

        $meta ['securityschema'] = array(
            'ZSELEX::' => '::'
        );
        $meta ['core_min']       = '1.3.0'; // requires minimum 1.3.0 or later
        // $meta['core_max'] = '1.3.0'; // doesn't work with versions later than x.x.x

        return $meta;
    }

    protected function setupHookBundles()
    {
        $bundle = new Zikula_HookManager_SubscriberBundle($this->name,
            'subscriber.news.ui_hooks.articles', 'ui_hooks',
            $this->__('News Articles Hooks'));
        $bundle->addEvent('display_view', 'news.ui_hooks.articles.display_view');
        $bundle->addEvent('form_edit', 'news.ui_hooks.articles.form_edit');
        $bundle->addEvent('form_delete', 'news.ui_hooks.articles.form_delete');
        $bundle->addEvent('validate_edit',
            'news.ui_hooks.articles.validate_edit');
        $bundle->addEvent('validate_delete',
            'news.ui_hooks.articles.validate_delete');
        $bundle->addEvent('process_edit', 'news.ui_hooks.articles.process_edit');
        $bundle->addEvent('process_delete',
            'news.ui_hooks.articles.process_delete');
        $this->registerHookSubscriberBundle($bundle);

        $bundle = new Zikula_HookManager_SubscriberBundle($this->name,
            'subscriber.news.filter_hooks.articles', 'filter_hooks',
            $this->__('News Display Hooks'));
        $bundle->addEvent('filter', 'news.filter_hooks.articles.filter');
        $this->registerHookSubscriberBundle($bundle);
    }
}
// end class def

