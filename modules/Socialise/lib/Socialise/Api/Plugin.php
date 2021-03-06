<?php

/**
 * Copyright socialise Team 2011
 *
 * This work is contributed to the Zikula Foundation under one or more
 * Contributor Agreements and licensed to You under the following license:
 *
 * @license GNU/LGPLv3 (or at your option, any later version).
 * @package socialise
 * @link http://code.zikula.org/socialise
 *
 * Please see the NOTICE file distributed with this source code for further
 * information regarding copyright and licensing.
 */

/**
 * Plugin api class.
 */
class Socialise_Api_Plugin extends Zikula_AbstractApi {

    /**
     * Instance of Zikula_View.
     *
     * @var Zikula_View
     */
    protected $view;

    /**
     * Initialize.
     *
     * @return void
     */
    protected function initialize() {
        $this->setView();
    }

    /**
     * Set view property.
     *
     * @param Zikula_View $view Default null means new Render instance for this module name.
     *
     * @return Zikula_AbstractController
     */
    protected function setView(Zikula_View $view = null) {
        if (is_null($view)) {
            $view = Zikula_View::getInstance($this->getName());
        }

        $this->view = $view;
        return $this;
    }

    /**
     * Twitter plugin
     *
     * @param array $args Parameters from the plugin (title, url, count, via, related).
     *
     * @return string
     */
    public function twitter($args) {
        // http://twitter.com/about/resources/tweetbutton#type-fields
        $args = array(
            'title' => (isset($args['title']) && $args['title']) ? $args['title'] : '',
            'url' => (isset($args['url']) && $args['url']) ? $args['url'] : '',
            'count' => (isset($args['count']) && in_array($args['count'], array('none', 'vertical', 'horizontal'))) ? $args['count'] : 'none',
            'via' => (isset($args['via'])) ? $args['via'] : true,
            'related' => (isset($args['related']) && $args['related']) ? $args['related'] : ''
        );

        // process via if is not fixed
        if ($args['via'] && !is_string($args['via'])) {
            $keys = ModUtil::apiFunc('Socialise', 'user', 'getKeys', array('service' => 'Twitter'));
            $args['via'] = (isset($keys['siteaccount']) && $keys['siteaccount']) ? $keys['siteaccount'] : false;
        }

        // process lang
        $lang = substr(ZLanguage::getLanguageCode(), 0, 2);
        $langs = array('de', 'en', 'es', 'fr', 'it', 'ja', 'ko');
        $args['lang'] = in_array($lang, $langs) ? $lang : 'en';

        // build the plugin output
        return $this->view->assign('plugin', $args)
                        ->fetch('plugin/twitter.tpl');
    }

    /**
     * Google+ plugin
     *
     * @param array $args Parameters from the plugin (title, url, count, via, related).
     *
     * @return string Output.
     */
    public function googleplus($args) {
        //echo "<pre>"; print_r($args);
        // http://twitter.com/about/resources/tweetbutton#type-fields
        $args = array(
            'title' => (isset($args['title']) && $args['title']) ? $args['title'] : '',
            'description' => (isset($args['description']) && $args['description']) ? $args['description'] : '',
            'size' => (isset($args['size']) && $args['size']) ? $args['size'] : '',
            'width' => (isset($args['width']) && $args['width']) ? $args['width'] : '',
            'annotation' => (isset($args['annotation']) && $args['annotation']) ? $args['annotation'] : ''
        );

        // process lang
        $lang = substr(ZLanguage::getLanguageCode(), 0, 2);
        $langs = array('de', 'en', 'es', 'fr', 'it', 'ja', 'ko');
        $args['lang'] = in_array($lang, $langs) ? $lang : 'en';

        PageUtil::addVar('header', '<meta itemprop="name" content="' . $args['title'] . '">');
        PageUtil::addVar('header', '<meta itemprop="description" content="' . $args['description'] . '">');

        // build the plugin output
        return $this->view->assign('plugin', $args)
                        ->fetch('plugin/googleplus.tpl');
    }

    /**
     * Facebook like button plugin
     *
     * @param array $args Parameters from the plugin (tpl, url, layout, rel, width, height, action, colorscheme, font, faces, addmetatags, metatitle, metatype, metaimage).
     *
     * @return string Output.
     */
    public function fblike($args) {
        //echo "like";
        // http://developers.facebook.com/docs/reference/plugins/like/
        // validation of Facebook ID
        $keys = ModUtil::apiFunc('Socialise', 'user', 'getKeys', array('service' => 'Facebook'));
        //echo "<pre>";  print_r($keys);
        // echo "<pre>";  print_r($args);


        if (!array_filter($keys)) {
            return '';
        }

        $args = array(
            'tpl' => (isset($args['tpl']) && $args['tpl']) ? DataUtil::formatForOS($args['tpl']) : '',
            'description' => $args['description'],
            'url' => (isset($args['url']) && $args['url']) ? $args['url'] : ModUtil::apiFunc('socialise', 'user', 'getCurrentUrl'),
            'action' => (isset($args['action']) && $args['action']) ? strtolower($args['action']) : '',
            'layout' => (isset($args['layout'])) ? $args['layout'] : '',
            'faces' => (isset($args['faces'])) ? (bool) $args['faces'] : false,
            'font' => (isset($args['font'])) ? $args['font'] : '',
            'ref' => (isset($args['ref'])) ? $args['ref'] : '',
            'width' => (isset($args['width']) && $args['width']) ? (int) $args['width'] : 55,
            'height' => (isset($args['height']) && $args['height']) ? (int) $args['height'] : 20,
            'colorscheme' => (isset($args['colorscheme'])) ? strtolower($args['colorscheme']) : '',
            'addmetatags' => (isset($args['addmetatags'])) ? (bool) $args['addmetatags'] : false,
            'metatitle' => (isset($args['metatitle']) && $args['metatitle']) ? $args['metatitle'] : PageUtil::getVar('title'),
            'metatype' => (isset($args['metatype']) && $args['metatype']) ? $args['metatype'] : 'article',
            'metaimage' => (isset($args['metaimage']) && $args['metaimage']) ? $args['metaimage'] : ''
        );

        // echo "<pre>"; print_r($args);  echo "</pre>";
        // parameters validations
        if (!in_array($args['action'], array('like', 'recommend'))) {
            $args['action'] = 'like';
        }
        // avoid a browser bug with XFBML and action="recommend"
        if ($args['action'] == 'recommend' && $args['tpl'] == 'xfbml') {
            $args['tpl'] = '';
        }
        switch ($args['layout']) {
            case 'horizontal':
            case 'button_count':
                $args['layout'] = 'button_count';
                // adjust the size
                $args['width'] = $args['width'] >= 100 ? $args['width'] : 100;
                $args['height'] = $args['height'] >= 20 ? $args['height'] : 20;
                break;
            case 'vertical':
            case 'box_count':
                $args['layout'] = 'box_count';
                // adjust the size
                $args['width'] = $args['width'] >= 80 ? $args['width'] : 80;
                $args['height'] = $args['height'] >= 65 ? $args['height'] : 65;
                break;
            default:
                $args['layout'] = 'standard';
                // adjust the size
                if ($args['width'] < 85) {
                    $args['width'] = ($args['action'] == 'like') ? 450 : 105;
                }
                if ($args['faces']) {
                    $args['height'] = $args['height'] >= 80 ? $args['height'] : 80;
                }
                $args['height'] = $args['height'] >= 25 ? $args['height'] : 25;
        }
        $args['faces'] = $args['faces'] ? 'true' : 'false';

        $args['font'] = str_replace(' ', '+', $args['font']);
        $fonts = array('arial', 'lucida+grande', 'segoe+ui', 'tahoma', 'trebuchet+ms', 'verdana');
        if (!in_array($args['font'], $fonts)) {
            $args['font'] = '';
        }

        if (!in_array($args['colorscheme'], array('light', 'dark'))) {
            $args['colorscheme'] = 'light';
        }

        // add the meta tags
        foreach (array_filter($keys) as $prop => $content) {
            if (in_array($prop, array('app_id', 'admins'))) {
                //PageUtil::addVar('header', "<!--\n" . '<meta property="fb:' . $prop . '" content="' . $content . '" />' . "\n-->");
                PageUtil::addVar('header', '<meta property="fb:' . $prop . '" content="' . $content . '" />');
            }
        }


        if ($args['addmetatags']) {
            //echo "meta";


            $metadata = array(
                'title' => $args['metatitle'],
                'type' => $args['metatype'],
                'url' => $args['url'],
                'description' => $args['description'],
                'site_name' => ModUtil::getVar('ZConfig', 'sitename')
            );

            //  echo "<pre>";  print_r($metadata);
            if ($args['metaimage']) {
                $metadata['image'] = $args['metaimage'];
            }

            foreach ($metadata as $prop => $content) {
                // PageUtil::addVar('header', "<!--\n" . '<meta property="og:' . $prop . '" content="' . $content . '" />' . "\n-->");
                PageUtil::addVar('header', '<meta property="og:' . $prop . '" content="' . $content . '" />');
            }
        }

        // build the plugin output
        $this->view->assign('plugin', $args);

        //echo "comes here...";

        if ($args['tpl'] && $this->view->template_exists("plugin/fblike_{$args['tpl']}.tpl")) {
            return $this->view->fetch("plugin/fblike_{$args['tpl']}.tpl");
        }

        return $this->view->fetch('plugin/fblike.tpl');
    }

    /**
     * Facebook comment box plugin
     *
     * @param array $args Parameters from the plugin (tpl, url, layout, rel, width, height, action, colorscheme, font, faces, addmetatags, metatitle, metatype, metaimage).
     *
     * @return string Output.
     */
    public function fbcomment($args) {
        //echo "like";
        // http://developers.facebook.com/docs/reference/plugins/like/
        // validation of Facebook ID
        $keys = ModUtil::apiFunc('Socialise', 'user', 'getKeys', array('service' => 'Facebook'));
        //echo "<pre>";  print_r($keys);
        //echo "<pre>";  print_r($args);

        $shop_id = $args['shop_id'];
        $title = $args['title'];
        $description = $args['description'];

        if (!array_filter($keys)) {
            return '';
        }

        $args = array(
            'tpl' => (isset($args['tpl']) && $args['tpl']) ? DataUtil::formatForOS($args['tpl']) : '',
            'url' => (isset($args['url']) && $args['url']) ? $args['url'] : ModUtil::apiFunc('socialise', 'user', 'getCurrentUrl'),
            'action' => (isset($args['action']) && $args['action']) ? strtolower($args['action']) : '',
            'layout' => (isset($args['layout'])) ? $args['layout'] : '',
            'faces' => (isset($args['faces'])) ? (bool) $args['faces'] : false,
            'font' => (isset($args['font'])) ? $args['font'] : '',
            'ref' => (isset($args['ref'])) ? $args['ref'] : '',
            'width' => (isset($args['width']) && $args['width']) ? (int) $args['width'] : 55,
            'height' => (isset($args['height']) && $args['height']) ? (int) $args['height'] : 20,
            'colorscheme' => (isset($args['colorscheme'])) ? strtolower($args['colorscheme']) : '',
            'addmetatags' => (isset($args['addmetatags'])) ? (bool) $args['addmetatags'] : false,
            'metatitle' => (isset($args['metatitle']) && $args['metatitle']) ? $args['metatitle'] : PageUtil::getVar('title'),
            'metatype' => (isset($args['metatype']) && $args['metatype']) ? $args['metatype'] : 'article',
            'metaimage' => (isset($args['metaimage']) && $args['metaimage']) ? $args['metaimage'] : ''
        );



        //  PageUtil::addVar('header', '<meta name="title" content="' . $title . '">');
        //  PageUtil::addVar('header', '<meta name="Description" content="' . $description . '">');
        // add the meta tags
        foreach (array_filter($keys) as $prop => $content) {
            if (in_array($prop, array('app_id', 'admins'))) {
                PageUtil::addVar('header', '<meta property="fb:' . $prop . '" content="' . $content . '" />');
            }
        }



        $metadata = array(
            'title' => $title,
            // 'type' => $args['metatype'],
            //'url' => $args['url'],
            'description' => $description,
            'site_name' => $title
        );
        if ($args['metaimage']) {
            $metadata['image'] = $args['metaimage'];
        }

        foreach ($metadata as $prop => $content) {
            // echo $prop;
            PageUtil::addVar('header', '<meta name="' . $prop . '" content="' . $content . '" />');
        }

        $url = ModUtil::url('ZSELEX', 'user', 'shop', array('shop_id' => $shop_id));

        // build the plugin output
        $this->view->assign('url', $url);
        $this->view->assign('plugin', $args);



        return $this->view->fetch('plugin/fbcomment.tpl');
    }

    /**
     * Sexybutton plugin.
     *
     * @param array $args Parameters from the plugin (title, url).
     *
     * @return string Output.
     */
    public function sexybookmarks($args) {
        $args = array(
            'title' => (isset($args['title']) && $args['title']) ? $args['title'] : '',
            'url' => (isset($args['url']) && $args['url']) ? $args['url'] : ModUtil::apiFunc('socialise', 'user', 'getCurrentUrl')
        );

        $services = ModUtil::apiFunc('Socialise', 'user', 'getServices');
        foreach ($services as $key => $value) {
            $services[$key]['url'] = str_replace('{url}', $args['url'], $value['url']);
            $services[$key]['url'] = str_replace('{title}', $args['title'], $services[$key]['url']);
        }

        $sexybookmarks = $this->getVar('sexybookmarks');
        foreach ($sexybookmarks as $key => $value) {
            $sexybookmarks[$key] = array(
                'name' => $value,
                'url' => $services[$value]['url'],
                'title' => $services[$value]['title']
            );
        }

        // build the plugin output
        return $this->view->assign('plugin', array('linewidth' => count($sexybookmarks) * 64, 'sexybookmarks' => $sexybookmarks))
                        ->fetch('plugin/sexybookmarks.tpl');
    }

    /**
     * ShareThis plugin.
     *
     * @param ∂array $args Parameters from the plugin (id, title, url, text).
     *
     * @return string
     */
    public function sharethis($args) {
        $args = array(
            'id' => (isset($args['id']) && $args['id']) ? $args['id'] : '',
            'title' => (isset($args['title']) && $args['title']) ? $args['title'] : '',
            'url' => (isset($args['url']) && $args['url']) ? $args['url'] : ModUtil::apiFunc('socialise', 'user', 'getCurrentUrl'),
            'text' => (isset($args['text']) && $args['text']) ? $args['text'] : $this->__('Share!')
        );

        if (!$args['id']) {
            return '';
        }

        $output = '';

        // stuff to do once and once only....
        static $onceonly = false;
        if (!$onceonly) {
            $output = $this->view->fetch('plugin/sharethis_include.tpl');
            $onceonly = true;
        }

        // build the plugin output
        $this->view->assign('plugin', $args);

        // stuff to do for each item
        return $output . $this->view->fetch('plugin/sharethis.tpl');
    }

    /**
     * socialshareprivacy plugin.
     *
     * @return string
     */
    public function socialshareprivacy() {
        return $this->view->fetch('plugin/socialshareprivacy.tpl');
    }

}
