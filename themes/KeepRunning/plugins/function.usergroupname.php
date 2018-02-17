<?php
/**
 * Copyright Zikula Foundation 2009 - Zikula Application Framework
 *
 * This work is contributed to the Zikula Foundation under one or more
 * Contributor Agreements and licensed to You under the following license:
 *
 * @license GNU/LGPLv3 (or at your option, any later version).
 * @package Zikula_View
 * @subpackage Template_Plugins
 *
 * Please see the NOTICE file distributed with this source code for further
 * information regarding copyright and licensing.
 */

/**
 * Zikula_View function to display the welcome message
 *
 * Example
 * {usergroupname}
 *
 * @param array       $params All attributes passed to this function from the template.
 * @param Zikula_View $view   Reference to the Zikula_View object.
 *
 * @see    function.usergroupname.php::smarty_function_usergroupname()
 *
 * @return string users groupname.
 */
function smarty_function_usergroupname($params, Zikula_View $view)
{
    if (UserUtil::isLoggedIn()) {
        $userId = UserUtil::getVar('uid');
        $groupsql = "SELECT gid FROM group_membership WHERE uid=$userId && gid <> 1"; // Except group User
        $gquery = DBUtil::executeSQL($groupsql);
        $gresult = $gquery->fetch();
        $gid = $gresult[gid];
        $groupsql = "SELECT name FROM groups WHERE gid=$gid";
        $gquery = DBUtil::executeSQL($groupsql);
        $gresult = $gquery->fetch();
        $groupname = $gresult[name];
    } else {
        $groupname = __('Unregistered');
    }

    return "gid=".$gid .", name=". $groupname;
}
