<?php
/**
 * ZSELEX.
 */

/**
 * Event handler implementation class for user login events.
 */
class ZSELEX_Listener_UserLogin
{

    /**
     * Listener for the `module.users.ui.login.succeeded` event.
     *
     * Occurs right after a successful attempt to log in, and just prior to redirecting the user to the desired page.
     * All handlers are notified.
     *
     * The event subject contains the user's user record (from `UserUtil::getVars($event['uid'])`).
     * The arguments of the event are as follows:
     * `'authentication_module'` an array containing the authenticating module name (`'modname'`) and method (`'method'`)
     * used to log the user in.
     * `'redirecturl'` will contain the value of the 'returnurl' parameter, if one was supplied, or an empty
     * string. This can be modified to change where the user is redirected following the login.
     *
     * __The `'redirecturl'` argument__ controls where the user will be directed at the end of the log-in process.
     * Initially, it will be the value of the returnurl parameter provided to the log-in process, or blank if none was provided.
     *
     * The action following login depends on whether WCAG compliant log-in is enabled in the Users module or not. If it is enabled,
     * then the user is redirected to the returnurl immediately. If not, then the user is first displayed a log-in landing page,
     * and then meta refresh is used to redirect the user to the returnurl.
     *
     * If a `'redirecturl'` is specified by any entity intercepting and processing the `module.users.ui.login.succeeded` event, then
     * the URL provided replaces the one provided by the returnurl parameter to the login process. If it is set to an empty
     * string, then the user is redirected to the site's home page. An event handler should carefully consider whether
     * changing the `'redirecturl'` argument is appropriate. First, the user may be expecting to return to the page where
     * he was when he initiated the log-in process. Being redirected to a different page might be disorienting to the user.
     * Second, all event handlers are being notified of this event. This is not a `notify()` event. An event handler
     * that was notified prior to the current handler may already have changed the `'redirecturl'`.
     *
     * Finally, this event only fires in the event of a "normal" UI-oriented log-in attempt. A module attempting to log in
     * programmatically by directly calling the core functions will not see this event fired.
     */
    public static function succeeded(Zikula_Event $event)
    {
        // HERE IS YOUR CODE
        // echo "<pre>"; print_r($event); echo "</pre>"; exit;
        // echo $_SESSION['returnPAGE']; exit;
        $loguser   = UserUtil::getVar('uid');
        $returnUrl = ModUtil::url('Users', 'user', 'main');
        // echo $loguser; exit;
        // echo "hello world!!!!"; exit;
        // print_r($item); exit;

        $item = ModUtil::apiFunc('ZSELEX', 'admin', 'getMainShop',
                array(
                'user_id' => $loguser
        ));
        // echo "<pre>"; print_r($item); echo "</pre>"; exit;

        $shop_id    = $item ['shop_id'];
        $shopName   = $item ['shop_name'];
        $linkToShop = $item ['quantity'];

        SessionUtil::setVar('shop_id', $shop_id);

        $_SESSION ['mainshop'] = $shop_id;

        /*
         * if ($_SESSION['checkoutsession'] == '1') {
         * $returnUrl = ModUtil::url('ZSELEX', 'user', 'cart'); // whatever, but use ModUtil::url() !!!
         * } elseif (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN) && !empty($shopName)) {
         * $returnUrl = ModUtil::url('ZSELEX', 'user', 'site', array('id' => $shop_id));
         * }
         */

        $returnUrl = ModUtil::url('Users', 'user', 'main');

        $groupsql = "SELECT gid FROM group_membership WHERE uid=$loguser && gid <> 1"; // Except group User
        $gquery   = DBUtil::executeSQL($groupsql);
        $gresult  = $gquery->fetch();
        $gid      = $gresult [gid];
        // die("uid=".$userId.", gid=".$gid);
        if ($gid == 2) {
            $returnUrl = ModUtil::url('ZSELEX', 'admin', 'viewshop');
            // die("SUPERADMIN! uid=".$userId.", gid=".$gid);
        } else {
            $modvars = ModUtil::getVar('ZSELEX');
            // print_r($modvars);
            // die("uid=".$userId.", gid=".$gid);
            if ($shop_id) {
                if (($gid == $modvars ['shopOwnerGroup']) || ($gid == $modvars ['shopAdminGroup'])) {
                    $returnUrl = ModUtil::url('ZSELEX', 'admin', 'shopsummary',
                            array(
                            'shop_id' => $shop_id
                    ));
                    // die("OWNER or ADMIN! uid=".$userId.", gid=".$gid);
                } else {
                    $returnUrl = ModUtil::url('Users', 'user', 'main');
                    // die("NORMAL USER! uid=".$userId.", gid=".$gid);
                }
            }
        }
        // /////////////////////////////////////////////////////////////////////
        // ZSELEX_Controller_User::updateFromGuestCart();
        // echo "comes here..."; exit;
        ModUtil::apiFunc('ZSELEX', 'cart', 'updateFromGuestCart');

        // /////////////////////////////////////////////////////////////////////////

        if ($_SESSION ['checkoutsession'] == '1') {
            $returnUrl = ModUtil::url('ZSELEX', 'user', 'cart'); // redirect to cart!
        }

        if (!empty($returnUrl)) {
            // set own redirect url
            $event->setArg('redirecturl', $returnUrl);
        }
    }

    /**
     * Listener for the `module.users.ui.login.failed` event.
     *
     * Occurs right after an unsuccessful attempt to log in. All handlers are notified.
     *
     * The event subject contains the user's user record (from `UserUtil::getVars($event['uid'])`) if it has been found, otherwise null.
     * The arguments of the event are as follows:
     * `'authentication_module'` an array containing the authenticating module name (`'modname'`) and method (`'method'`)
     * used to log the user in.
     * `'authentication_info'` an array containing the authentication information entered by the user (contents will vary by method).
     * `'redirecturl'` will initially contain an empty string. This can be modified to change where the user is redirected following the failed login.
     *
     * __The `'redirecturl'` argument__ controls where the user will be directed following a failed log-in attempt.
     * Initially, it will be an empty string, indicating that the user should continue with the log-in process and be presented
     * with the log-in form.
     *
     * If a `'redirecturl'` is specified by any entity intercepting and processing the `module.users.ui.login.failed` event, then
     * the user will be redirected to the URL provided, instead of being presented with the log-in form.
     *
     * Finally, this event only fires in the event of a "normal" UI-oriented log-in attempt. A module attempting to log in
     * programmatically by directly calling `UserUtil::loginUsing()` will not see this event fired. Instead, the
     * `Users_Controller_User#login()` function can be called with the appropriate parameters, if the event is desired.
     */
    public static function failed(Zikula_Event $event)
    {

        // echo "Failed"; exit;
    }

    public static function logout(Zikula_Event $event)
    {
        // echo "Log out"; exit;
        // ZSELEX_Controller_User::clearCart(); // clear the cart from session and cookies
        // $facebook = ModUtil::apiFunc('FConnect', 'Facebook', 'facebook');
        // $facebook->destroySession();
        // require_once 'modules/FConnect/lib/vendor/Facebook/base_facebook.php';
        // require_once 'modules/FConnect/lib/vendor/Facebook/facebook.php';
        $settings                                               = ModUtil::getVar('FConnect');
        /*
         * $facebook = new Facebook(array(
         * 'appId' => $settings['appid'],
         * 'secret' => $settings['secretkey']
         * ));
         */
        // $facebookss = ModUtil::apiFunc('FConnect', 'Facebook', 'facebook');
        $_SESSION ["fb_(".$settings ['appid'].")_access_token"] = '';
        unset($_SESSION ["fb_".$settings ['appid']."_user_id"]);

        // echo "<pre>"; print_r($settings); echo "</pre>"; exit;
        // $settings['appid']
        // $settings['secretkey']
        setcookie('fbs_'.$settings ['appid'], '', time() - 100, '/',
            $_SERVER ['REQUEST_URI']);
        session_destroy();
        // $returnUrl = ModUtil::url('Users', 'user', 'main');
        $returnUrl = ModUtil::url('ZSELEX', 'user', 'myRedirect');
        throw new Zikula_Exception_Redirect($returnUrl, $type);
    }
}