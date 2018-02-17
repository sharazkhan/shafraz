<?php
/**
 * Copyright ACTA-IT 2014 - ZTEXT
 *
 * ZTEXT
 * Payment Gateway Module
 *
 * @license GNU/LGPLv3 (or at your option, any later version).
 */

/**
 * Class to control User interface
 */
class ZTEXT_Api_User extends Zikula_AbstractApi
{

    /**
     * Get available user links
     *
     * @return array array of admin links
     */
    public function getlinks()
    {////
        // Define an empty array to hold the list of admin links
        $links = array();

        // Return the links array back to the calling function
        return $links;
    }

    public function decodeurl($args)
    {
        if (!isset($args['vars'])) {
            return LogUtil::registerArgsError();
        }

        //echo "<pre>"; print_r($args);   echo "</pre>"; exit;
        $funcs = array(
            'page',
            'pages',
        );
        if (in_array($args['vars'][2], $funcs)) {
            if (empty($args['vars'][2])) {
                System::queryStringSetVar('func', 'view');
                $nextvar = 3;
            } /* elseif ($args['vars'][2] == 'page') {
              System::queryStringSetVar('func', 'view');
              $nextvar = 3;
              } */ elseif (!in_array($args['vars'][2], $funcs)) {
                System::queryStringSetVar('func', 'display');
                $nextvar = 2;
            } else {
                //echo "this works";
                System::queryStringSetVar('func', $args['vars'][2]);
                $nextvar = 3;
            }
            System::queryStringSetVar('type', 'user');
            $func = FormUtil::getPassedValue('func', 'view', 'GET');

            // for now let the core handle the view function
            if (($func == 'view' || $func == 'main') && isset($args['vars'][$nextvar])) {
                System::queryStringSetVar('page', (int) $args['vars'][$nextvar]);
            }

            if ($func == 'pages') {
                $repo       = $this->entityManager->getRepository('ZSELEX_Entity_Shop');
                //echo "comes here";exit;
                // echo "<pre>"; print_r($args);   echo "</pre>"; exit;
                $shop_title = $args['vars'][$nextvar];
                //echo $shop_title; exit;
                System::queryStringSetVar('shop_title', $shop_title);
                $item       = $repo->get(array(
                    'entity' => 'ZSELEX_Entity_Shop',
                    'fields' => array('a.shop_id', 'a.shop_name', 'b.city_name',
                        'a.status'),
                    'joins' => array('LEFT JOIN a.city b'),
                    'where' => array('a.urltitle' => DataUtil::formatForStore($args['vars'][$nextvar])),
                ));

                if (!$item) {
                    return;
                } elseif (!$item['status']) {
                    // echo "comes here";  exit;
                    if (!SecurityUtil::checkPermission('ZSELEX::', '::',
                            ACCESS_ADMIN)) {
                        return;
                    }
                }

                System::queryStringSetVar('shop_id', $item['shop_id']); //set this for theme changing
                System::queryStringSetVar('shopName', $item['shop_name']);
                System::queryStringSetVar('shop_name', $item['shop_name']);
                System::queryStringSetVar('city_name', $item['city_name']);
                System::queryStringSetVar('shoptitle', $args['vars'][$nextvar]);
                System::queryStringSetVar('startnum',
                    $args['vars'][$nextvar + 2]);
            } elseif ($func == 'page') {
                $repo       = $this->entityManager->getRepository('ZSELEX_Entity_Shop');
                //echo "comes here";exit;
                // echo "<pre>"; print_r($args);   echo "</pre>"; exit;
                $shop_title = $args['vars'][$nextvar];
                $page_title = $args['vars'][$nextvar + 1];
                //echo $shop_title; exit;
                System::queryStringSetVar('shop_title', $shop_title);
                $item       = $repo->get(array(
                    'entity' => 'ZSELEX_Entity_Shop',
                    'fields' => array('a.shop_id', 'a.shop_name', 'b.city_name',
                        'a.status'),
                    'joins' => array('LEFT JOIN a.city b'),
                    'where' => array('a.urltitle' => DataUtil::formatForStore($args['vars'][$nextvar])),
                ));

                if (!$item) {
                    return;
                } elseif (!$item['status']) {
                    // echo "comes here";  exit;
                    if (!SecurityUtil::checkPermission('ZSELEX::', '::',
                            ACCESS_ADMIN)) {
                        return;
                    }
                }

                $text_id   = $args['args']['text_id'];
                $page_item = $repo->get(array('entity' => 'ZTEXT_Entity_Page',
                    'where' => array('a.urltitle' => $page_title),
                    'fields' => array('a.text_id')
                ));
                System::queryStringSetVar('shop_id', $item['shop_id']); //set this for theme changing
                System::queryStringSetVar('shopName', $item['shop_name']);
                System::queryStringSetVar('shop_name', $item['shop_name']);
                System::queryStringSetVar('city_name', $item['city_name']);
                System::queryStringSetVar('shoptitle', $args['vars'][$nextvar]);
                System::queryStringSetVar('text_id', $page_item['text_id']);
            }
        }
    }

    public function encodeurl($args)
    { // encode url
        //exit;
        //echo "encodeurl comes here"; exit;
        // check we have the required input
        if (!isset($args['modname']) || !isset($args['func']) || !isset($args['args'])) {
            return LogUtil::registerArgsError();
        }
        if (!isset($args['type'])) {
            $args['type'] = 'user';
        }
        if (empty($args['func'])) {
            $args['func'] = 'view';
        }

        //echo $args['func'] ;
        //echo $args['args']['id'];
        //echo "<pre>"; print_r($args['args']);   echo "</pre>";
        // create an empty string ready for population
        $vars = '';

        // for the display function use the defined permalink structure

        $allowedFunctions = array(
            'page',
            'pages',
        );



        if (in_array($args['func'], $allowedFunctions)) {
            $repo = $this->entityManager->getRepository('ZSELEX_Entity_Shop');

            if ($args['func'] == 'pages') {
                // echo "<pre>"; print_r($args['args']);  echo "</pre>";
                $shop_id = $args['args']['shop_id'];
                $item    = $repo->get(array('entity' => 'ZSELEX_Entity_Shop',
                    'where' => array('a.shop_id' => $shop_id),
                    'fields' => array('a.urltitle')
                ));
                //echo "<pre>"; print_r($item); echo "</pre>"; exit;
                if (empty($item['urltitle'])) {
                    return LogUtil::registerError($this->__('Error! Type not found.'));
                }


                $shoptitle = $item['urltitle'];
                $vars      = "{$shoptitle}";

                if (!empty($args['args']['startnum'])) {
                    $vars .= "/startnum/".$args['args']['startnum'];
                }
            } elseif ($args['func'] == 'page') {
                $shop_id   = $args['args']['shop_id'];
                $item      = $repo->get(array('entity' => 'ZSELEX_Entity_Shop',
                    'where' => array('a.shop_id' => $shop_id),
                    'fields' => array('a.urltitle')
                ));
                $text_id   = $args['args']['text_id'];
                $page_item = $repo->get(array('entity' => 'ZTEXT_Entity_Page',
                    'where' => array('a.text_id' => $text_id),
                    'fields' => array('a.urltitle')
                ));
                //echo "<pre>"; print_r($item); echo "</pre>"; exit;
                if (empty($page_item['urltitle'])) {
                    return LogUtil::registerError($this->__('Error! page not found.'));
                }


                $shoptitle = $item['urltitle'];
                $vars      = "{$shoptitle}";

                $page_title = $page_item['urltitle'];
                $vars .= "/{$page_title}";
            }
            //$vars .= "/test";

            if (empty($vars)) {
                //echo $args['modname'] . '/' . $args['func'] . '/';
                return $args['modname'].'/'.$args['func'];
            } else {

                return $args['modname'].'/'.$args['func'].'/'.$vars;
            }
        }
    }
}
// end class def