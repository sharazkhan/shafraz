<?php

/**
 * FConnect
 */
class Google_Controller_User extends Zikula_AbstractController {

    /**
     * The default entry point.
     */
    public function test() {
        //echo "comes here";  exit;
        // echo $this->name; exit;
        $settings = ModUtil::getVar($this->name);

        //echo "<pre>";   print_r($settings); echo "</pre>"; exit;
        //Parameter extraction and error checking
        if (!isset($settings['clientid']) || !isset($settings['secretkey'])) {
            throw new Zikula_Exception_Fatal($this->__('Google login is not supported'));
        }

        $google_id = ModUtil::apiFunc($this->name, 'user', 'getmyfb_id');

        // echo $google_id; exit;
        //$fb_data = ModUtil::apiFunc($this->name, 'user', 'getmyfb_userdata');

        if (empty($google_id)) {
            //echo "comes here"; exit;
            $loginUrl = ModUtil::apiFunc($this->name, 'user', 'getmyloginurl');

            $this->redirect($loginUrl);
        }

        // echo "helloooooooooooo"; exit;
        /// we should have facebook id now lets do what we want
        // is user logged in? or not		
        $uid = UserUtil::getVar('uid');
        // uid = 1 is the anonymous user
        if ($uid < 2) {

            if (ModUtil::apiFunc($this->name, 'user', 'logmein', $google_id)) {
                // echo "comes here"; exit;
                $this->redirect(ModUtil::url('Users', 'user', 'main'));
            } else if (ModUtil::apiFunc($this->name, 'user', 'registerme')) {

                if (ModUtil::apiFunc($this->name, 'user', 'logmein', $google_id)) {

                    $this->redirect(ModUtil::url('Users', 'user', 'main'));
                }
            }
        } else {
            //user is logged in but not connected just connect	
            //ModUtil::apiFunc($this->name, 'user', 'connectme');
        }

        // Assign all the module vars
        return $this->view->assign('google_id', $fb_id)
                        ->fetch('fconnect_user_main.tpl');
    }

    public function main() {
        $this->view->setCaching(false);
        // unset($_SESSION['token']); exit;
        // echo "Google here"; exit;
        // echo "<pre>"; print_r($_SESSION); echo "<pre>"; exit;
        //EventUtil::registerPersistentModuleHandler('Google', 'user.account.delete', array('Google_Listener_User', 'delete')); exit;
        require_once 'modules/Google/lib/vendor/Google/Google_Client.php';
        require_once 'modules/Google/lib/vendor/Google/Google_Oauth2Service.php';

        //  echo $this->name; exit;

        $settings = ModUtil::getVar($this->name);
        $client_id = $settings['clientid'];
        $client_secret = $settings['secretkey'];
        $redirect_uri = $settings['redirecturi'];
        // echo "<pre>";   print_r($settings); exit;

        $client = new Google_Client();
        $client->setApplicationName("Google UserInfo Zikula");

        $client->setClientId($client_id);
        $client->setClientSecret($client_secret);
        $client->setRedirectUri($redirect_uri);
        $client->setApprovalPrompt('auto');
        $client->setAccessType('online');

        $oauth2 = new Google_Oauth2Service($client);


        if (isset($_GET['code'])) {
            $client->authenticate($_GET['code']);
            $_SESSION['token'] = $client->getAccessToken();
        }

        if (isset($_REQUEST['error'])) {
            $this->redirect(ModUtil::url('Users', 'user', 'main'));
        }

        if (isset($_SESSION['token'])) {
            $client->setAccessToken($_SESSION['token']);
        }

        if ($client->getAccessToken()) {
            $user = $oauth2->userinfo->get();
            // echo "<pre>";   print_r($user); exit;
            // These fields are currently filtered through the PHP sanitize filters.
            // See http://www.php.net/manual/en/filter.filters.sanitize.php
            // $email = filter_var($user['email'], FILTER_SANITIZE_EMAIL);
            // $img = filter_var($user['picture'], FILTER_VALIDATE_URL);
            // $img = !empty($img) ? $img : '';
            //$personMarkup = "$email<div><img src='$img?sz=50'></div>";
            // $personMarkup = "$email<div></div>";
            // The access token may have been updated lazily.
            $google_id = $user['id'];
            //echo $google_id; exit;
            $_SESSION['token'] = $client->getAccessToken();

            $uid = UserUtil::getVar('uid');

            // uid = 1 is the anonymous user
            if ($uid < 2) {
                //echo "come here"; exit;
                //echo $uid; exit;

                if ($userDetails = ModUtil::apiFunc($this->name, 'user', 'logmein', $google_id)) {
                    //echo "login"; exit;
                    //  echo "<pre>";   print_r($userDetails); echo "</pre>"; exit;
                    $userId = $userDetails['uid'];
                    $returnUrl = $this->setRedirectUrl($userDetails['uid']);
                    // $this->redirect(ModUtil::url('Users', 'user', 'main'));
                    $this->redirect(ModUtil::url($returnUrl));
                    //echo "<pre>";   print_r($user); exit;
                } else if (ModUtil::apiFunc($this->name, 'user', 'registerme', $user)) {
                    //  echo "register"; exit;
                    if ($userDetails = ModUtil::apiFunc($this->name, 'user', 'logmein', $google_id)) {
                        $userId = $userDetails['uid'];
                        $returnUrl = $this->setRedirectUrl($userDetails['uid']);
                        $this->redirect(ModUtil::url($returnUrl));
                    }
                }
            } else {
                //echo "come here"; exit;
                //user is logged in but not connected just connect	
                ModUtil::apiFunc($this->name, 'user', 'connectme');
            }
        } else {
            //echo "comes here"; exit;
            unset($_SESSION['token']);
            $authUrl = $client->createAuthUrl();
            $this->redirect($authUrl);
        }
    }

    function setRedirectUrl($uid) {
        ModUtil::apiFunc('ZSELEX', 'cart', 'updateFromGuestCart');
        $returnUrl = ModUtil::url('Users', 'user', 'main');
        if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN) && SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADD)) {
            $item = ModUtil::apiFunc('ZSELEX', 'admin', 'getMainShop', array('user_id' => $uid));
            //exit;
            $_SESSION['mainshop'] = $item['shop_id'];
        } elseif (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADD) && SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_EDIT)) {
            $item = ModUtil::apiFunc('ZSELEX', 'admin', 'getMainShop', array('user_id' => $uid));

            //echo "<pre>"; print_r($item);   echo "</pre>"; exit;
            $_SESSION['mainshop'] = $item['shop_id'];
        }
        $shop_id = $item['shop_id'];

        $groupsql = "SELECT gid FROM group_membership WHERE uid=$uid && gid <> 1"; // Except group User
        $gquery = DBUtil::executeSQL($groupsql);
        $gresult = $gquery->fetch();
        $gid = $gresult[gid];
        if ($gid == 2) {
            $returnUrl = ModUtil::url('ZSELEX', 'admin', 'viewshop');
//die("SUPERADMIN! uid=".$userId.", gid=".$gid);
        } else {
            $modvars = ModUtil::getVar('ZSELEX');
//print_r($modvars);
//die("uid=".$userId.", gid=".$gid);
            if (($gid == $modvars['shopOwnerGroup']) || ($gid == $modvars['shopAdminGroup'])) {
                $returnUrl = ModUtil::url('ZSELEX', 'admin', 'shopsummary', array('shop_id' => $shop_id));
//die("OWNER or ADMIN! uid=".$userId.", gid=".$gid);
            } else {
                $returnUrl = ModUtil::url('Users', 'user', 'main');
//die("NORMAL USER! uid=".$userId.", gid=".$gid);
            }
        }

        return $returnUrl;
    }

}
