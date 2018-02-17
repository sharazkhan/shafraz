<?php

/**
 * Zikula Application Framework
 *
 * @copyright (c) 2001, Zikula Development Team
 * @link http://www.zikula.org
 * @version $Id: function.articleadminlinks.php 75 2009-02-24 04:51:52Z mateo $
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package Zikula_3rdParty_Modules
 * @subpackage News
 */

/**
 * Smarty function to display edit and delete links for a news article
 *
 * Example
 * <!--[articleadminlinks sid='1' start='[' and=']' seperator='|' class='z-sub']-->
 *
 * @author       Mark West
 * @since        20/10/03
 * @see          function.articleadminlinks.php::smarty_function_articleadminlinks()
 * @param        array       $params      All attributes passed to this function from the template
 * @param        object      &$smarty     Reference to the Smarty object
 * @param        integer     $sid         article id
 * @param        string      $start       start string
 * @param        string      $end         end string
 * @param        string      $seperator   link seperator
 * @param        string      $class       CSS class
 * @return       string      the results of the module function
 */
function smarty_function_articleadminlinks($params, &$smarty) {
    
    echo "HELLOO WORLD!!!";

    //echo "<pre>"; print_r($params);   echo "</pre>";

    $dom = ZLanguage::getModuleDomain('News');

    // get the info template var
    $info = $smarty->get_template_vars('info');

    if (!isset($params['sid'])) {
        $params['sid'] = $info['sid'];
    }
    if (!isset($params['page'])) {
        $params['page'] = $smarty->get_template_vars('page');
    }

    // set some defaults
    if (!isset($params['start'])) {
        $params['start'] = '[';
    }
    if (!isset($params['end'])) {
        $params['end'] = ']';
    }
    if (!isset($params['seperator'])) {
        $params['seperator'] = '|';
    }
    if (!isset($params['class'])) {
        $params['class'] = 'z-sub';
    }
    if (isset($params['type']) && $params['type'] <> 'ajax') {
        $params['type'] = '';
    }

    /*
      // Check for the current user to enable users to edit their own articles
      if (UserUtil::isLoggedIn()) {
      $uid = UserUtil::getVar('uid');
      } else {
      $uid = 0;
      }
     */
    $articlelinks = '';
    if (SecurityUtil::checkPermission('News::', "$info[cr_uid]:$info[cattitle]:$info[sid]", ACCESS_READ) /* || ($uid != 0 && $info['cr_uid'] == $uid) */) {
        // load our ajax files into the header
        if (isset($params['type']) && $params['type'] == 'ajax') {
            // load our ajax files into the header
            require_once $smarty->_get_plugin_filepath('function', 'ajaxheader');
            smarty_function_ajaxheader(array('modname' => 'News', 'filename' => 'news.js'), $smarty);
            smarty_function_ajaxheader(array('modname' => 'News', 'filename' => 'sizecheck.js'), $smarty);
            smarty_function_ajaxheader(array('modname' => 'News', 'filename' => 'prototype-base-extensions.js'), $smarty);
            smarty_function_ajaxheader(array('modname' => 'News', 'filename' => 'prototype-date-extensions.js'), $smarty);
            smarty_function_ajaxheader(array('modname' => 'News', 'filename' => 'datepicker.js'), $smarty);
            smarty_function_ajaxheader(array('modname' => 'News', 'filename' => 'datepicker-locale.js'), $smarty);
            if (ModUtil::getVar('News', 'enableattribution')) {
                PageUtil::addVar('javascript', 'javascript/helpers/Zikula.itemlist.js');
            }
            PageUtil::addVar('stylesheet', 'modules/News/style/datepicker.css');
            $articlelinks .= '<img id="news_loadnews" src="' . System::getBaseUrl() . 'images/ajax/circle-ball-dark-antialiased.gif" alt="" /><span class="' . $params['class'] . '"> ' . $params['start'] . ' <a onclick="editnews(' . $params['sid'] . ',' . $params['page'] . ')" href="javascript:void(0);">' . __('Quick edit', $dom) . '</a> ' . $params['end'] . "</span>\n";
        } else {
            $info[cr_uid];
            $loguser = UserUtil::getVar('uid');
            if (SecurityUtil::checkPermission('News::', '::', ACCESS_ADMIN)) {
                $articlelinks .= '<span class="' . $params['class'] . '"> ' . $params['start'] . ' <a href="' . DataUtil::formatForDisplayHTML(ModUtil::url('News', 'admin', 'modify', array('sid' => $params['sid']))) . '">' . __('Edit', $dom) . '</a>';
            }


            if (!SecurityUtil::checkPermission('News::', '::', ACCESS_ADMIN)) {
                if ($loguser == $info[cr_uid]) {
                    $articlelinks .= '<span class="' . $params['class'] . '"> ' . $params['start'] . ' <a href="' . DataUtil::formatForDisplayHTML(ModUtil::url('News', 'admin', 'modify', array('sid' => $params['sid']))) . '">' . __('Edit', $dom) . '</a>';
                }
            }


            //if (SecurityUtil::checkPermission('News::', "$info[cr_uid]:$info[cattitle]:$info[sid]", ACCESS_DELETE)) {

                if (SecurityUtil::checkPermission('News::', '::', ACCESS_ADMIN)) {
                    $articlelinks .= ' ' . $params['seperator'] . ' <a href="' . DataUtil::formatForDisplay(ModUtil::url('News', 'admin', 'delete', array('sid' => $params['sid']))) . '">' . __('Delete', $dom) . '</a>';
                }


                if (!SecurityUtil::checkPermission('News::', '::', ACCESS_ADMIN)) {
                    if ($loguser == $info[cr_uid]) {
                        $articlelinks .= ' ' . $params['seperator'] . ' <a href="' . DataUtil::formatForDisplay(ModUtil::url('News', 'admin', 'delete', array('sid' => $params['sid']))) . '">' . __('Delete', $dom) . '</a>';
                    }
                }
           // }
            if (SecurityUtil::checkPermission('News::', '::', ACCESS_ADMIN)) {
                $articlelinks .= ' ' . $params['end'] . "</span>\n";
            }
            if (!SecurityUtil::checkPermission('News::', '::', ACCESS_ADMIN)) {
                if ($loguser == $info[cr_uid]) {
                    $articlelinks .= ' ' . $params['end'] . "</span>\n";
                }
            }
        }
    }

    return $articlelinks;
}
