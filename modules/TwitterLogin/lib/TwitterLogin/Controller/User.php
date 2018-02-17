<?php

/**
 * Twitterlogin
 */
class TwitterLogin_Controller_User extends Zikula_AbstractController {

    /**
     * The default entry point.
     */
    public function main() {

        require_once 'modules/TwitterLogin/lib/vendor/TwitterLogin/twitteroauth.php';


        $settings = ModUtil::getVar($this->name);
        $consumer_key = $settings['consumerkey'];
        $consumer_secret = $settings['consumersecret'];
        $redirect_uri = $settings['redirecturi'];
        //echo "<pre>";   print_r($settings); exit;
        $twitteroauth = new TwitterOAuth($consumer_key, $consumer_secret);
// Requesting authentication tokens, the parameter is the URL we will be redirected to
        $request_token = $twitteroauth->getRequestToken($redirect_uri);

// Saving them into the session

        $_SESSION['oauth_token'] = $request_token['oauth_token'];
        $_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];


        if ($twitteroauth->http_code == 200) {
            // Let's generate the URL and redirect
            $url = $twitteroauth->getAuthorizeURL($request_token['oauth_token']);
            // header('Location: ' . $url);
            $this->redirect($url);
        } else {
            // It's a bad idea to kill the script, but we've got to know when there's an error.
            die('Something wrong happened.');
        }
    }

    function checkEmailExist1($id) {

        $sql = "SELECT email from twitter_login WHERE twitter_id=$id";
        $query = DBUtil::executeSQL($sql);
        $result = $query->fetch();
        $email = $result['email'];

        if (!empty($email)) {

            $return = 1;
        } else {
            $return = 0;
        }
        return $return;
    }

    function checkEmailExist($id) {

        $sql = "SELECT email , status from twitter_login WHERE twitter_id=$id";
        $query = DBUtil::executeSQL($sql);
        $result = $query->fetch();
        $status = $result['status'];
        $email = $result['email'];

        //  echo "<pre>";  print_r($result); exit;

        if (!empty($email) && $status > 0) {  // everything ok , proceed
            $return = 1;
        } elseif (!empty($email) && $status < 1) { // email exist but not verified by user.
            $return = 2;
        } else {
            $return = 0;
        }
        return $return;
    }

    public function twitterResponse() {
        require_once 'modules/TwitterLogin/lib/vendor/TwitterLogin/twitteroauth.php';
        $settings = ModUtil::getVar($this->name);
        $consumer_key = $settings['consumerkey'];
        $consumer_secret = $settings['consumersecret'];
        //echo '<pre>';   print_r($settings);   exit;

        if (!empty($_GET['oauth_verifier']) && !empty($_SESSION['oauth_token']) && !empty($_SESSION['oauth_token_secret'])) {
            //echo "comes here111";  exit;
            // We've got everything we need
            $twitteroauth = new TwitterOAuth($consumer_key, $consumer_secret, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
            //echo "comes here111";  exit;
// Let's request the access token
            $access_token = $twitteroauth->getAccessToken($_GET['oauth_verifier']);
// Save it in a session var
            $_SESSION['access_token'] = $access_token;
// Let's get the user's info
            $user_info = $twitteroauth->get('account/verify_credentials');
// Print user's info
            // echo "comes here";  exit;
            // echo '<pre>';   print_r($user_info);   exit;

            $_SESSION['twitter_userinfo'] = (array) $user_info;
            $checkEmail = $this->checkEmailExist($user_info->id);

            if ($checkEmail == 0) {
                $this->redirect(ModUtil::url('TwitterLogin', 'user', 'afterConfirmingEmail'));
            } elseif ($checkEmail == 2) {
                $this->redirect(ModUtil::url('TwitterLogin', 'user', 'verifyEmail'));
            }


            if (isset($user_info->error)) {
                //Something's wrong, go back to square 1  
                //header('Location: login-twitter.php');
                $this->redirect(ModUtil::url('TwitterLogin', 'user', 'main'));
            } else {   // zikula integration starts here
                $uid = UserUtil::getVar('uid');

                $twitter_id = $user_info->id;
                $username = $user_info->name;

                if ($uid < 2) {
                    //echo "come here"; exit;
                    if ($userDetails = ModUtil::apiFunc($this->name, 'user', 'logmein', $twitter_id)) {
                        //echo "login"; exit;
                        $returnUrl = $this->setRedirectUrl($userDetails['uid']);
                        $this->redirect(ModUtil::url($returnUrl));
                        // $this->redirect(ModUtil::url('Users', 'user', 'main'));
                        //echo "<pre>";   print_r($user); exit;
                    } else if (ModUtil::apiFunc($this->name, 'user', 'registerme', $user_info)) {
                        //  echo "register"; exit;
                        if ($userDetails = ModUtil::apiFunc($this->name, 'user', 'logmein', $twitter_id)) {
                            $returnUrl = $this->setRedirectUrl($userDetails['uid']);
                            $this->redirect(ModUtil::url($returnUrl));
                            //$this->redirect(ModUtil::url('Users', 'user', 'main'));
                        }
                    }
                } else {
                    //echo "come here"; exit;
                    //user is logged in but not connected just connect	
                    ModUtil::apiFunc($this->name, 'user', 'connectme');
                }

                /*
                  $user = new User();
                  $userdata = $user->checkUser($uid, 'twitter', $username);
                  if (!empty($userdata)) {
                  session_start();
                  $_SESSION['id'] = $userdata['id'];
                  $_SESSION['oauth_id'] = $uid;
                  $_SESSION['username'] = $userdata['username'];
                  $_SESSION['oauth_provider'] = $userdata['oauth_provider'];
                  header("Location: home.php");
                  }
                 * 
                 */
            }
        } else {
            //echo "comes here2";  exit;
            // Something's missing, go back to square 1
            //header('Location: login-twitter.php');
            $this->redirect(ModUtil::url('Users', 'user', 'main'));
            //$this->redirect(ModUtil::url('TwitterLogin', 'user', 'main'));
        }
    }

    public function afterConfirmingEmail($args) {
        if (empty($_SESSION['twitter_userinfo'])) {
            LogUtil::registerError($this->__f('No authentication has been done'));
            $this->redirect(ModUtil::url('Users', 'user', 'main'));
        }
        $sess_item = SessionUtil::getVar('twitteritem');
        $this->view->assign('item', $sess_item);
        $this->view->assign('confirm_title', $this->__('Email Authentication'));
        $this->view->assign('confirm_msg', $this->__('Please enter your email to complete the twitter authentication'));

        if ($_POST) {
            $email = $_POST['email'];
            $user_info = $_SESSION['twitter_userinfo'];
            $user_info['email'] = $email;
            $twitter_id = $user_info['id'];
            $uid = UserUtil::getVar('uid');

            $item = array(
                'twitteremail' => $email,
            );

            $validationerror = TwitterLogin_Util::validateEmail($email);

            if ($validationerror !== false) {
                // log the error found if any
                if ($validationerror !== false) {
                    LogUtil::registerError(nl2br($validationerror));
                }
                SessionUtil::setVar('twitteritem', $item);
                $this->redirect(ModUtil::url('TwitterLogin', 'user', 'afterConfirmingEmail'));
                //return $this->view->fetch('emailconfirm.tpl');
            } else {
                // As we're not previewing the item let's remove it from the session
                SessionUtil::delVar('twitteritem');
                unset($_SESSION['twitter_userinfo']);
            }

            if (ModUtil::apiFunc($this->name, 'user', 'register_temperorily', $user_info)) {
                //LogUtil::registerStatus($this->__('Done! ZSELEX configuration has been updated successfully.'));
                $this->redirect(ModUtil::url('TwitterLogin', 'user', 'verifyEmail'));
            } else {
                LogUtil::registerError($this->__f('Something went wrong with your registartion.Please try again'));
                $this->redirect(ModUtil::url('Users', 'user', 'main'));
            }


            if ($uid < 2) {
                //echo "come here"; exit;
                if (ModUtil::apiFunc($this->name, 'user', 'logmein', $twitter_id)) {
                    //echo "login"; exit;
                    $this->redirect(ModUtil::url('Users', 'user', 'main'));
                    //echo "<pre>";   print_r($user); exit;
                } else if (ModUtil::apiFunc($this->name, 'user', 'registerme', $user_info)) {
                    //  echo "register"; exit;
                    if (ModUtil::apiFunc($this->name, 'user', 'logmein', $twitter_id)) {
                        $this->redirect(ModUtil::url('Users', 'user', 'main'));
                    }
                }
            } else {
                // echo "come here"; exit;
                //user is logged in but not connected just connect	
                ModUtil::apiFunc($this->name, 'user', 'connectme');
            }
        }

        return $this->view->fetch('emailconfirm.tpl');

        //  echo "<pre>";  print_r($user_info);  echo "</pre>"; exit;
    }

    public function verifyEmail() {
        return $this->view->fetch('verifyemail.tpl');
    }

    public function doVerifyEmail($id) {
        $id = $_REQUEST['id'];
        $chk = "SELECT COUNT(*) as count FROM twitter_login WHERE twitter_id='" . $id . "' AND status='1'";
        $query = DBUtil::executeSQL($chk);
        $result = $query->fetch();
        $count = $result['count'];
        if ($count > 0) {
            LogUtil::registerStatus($this->__('This account has been already verified.'));
            $this->redirect(ModUtil::url('Users', 'user', 'main'));
        }

        $sql = "UPDATE twitter_login set status='1' WHERE twitter_id='" . $id . "'";
        //   echo $sql; exit;
        $query = DBUtil::executeSQL($sql);

        //  if ($query) {
        $sql = "SELECT email FROM twitter_login WHERE twitter_id='" . $id . "'";
        $query = DBUtil::executeSQL($sql);
        $result = $query->fetch();
        $email = $result['email'];
        $user_info = array('id' => $id, 'email' => $email);

        $del = "DELETE FROM twitter_login WHERE twitter_id='" . $id . "'";
        $querydel = DBUtil::executeSQL($del);

        if (ModUtil::apiFunc($this->name, 'user', 'registerme', $user_info)) {
            // echo "register"; exit;
            LogUtil::registerStatus($this->__('Done! You have successfully registered yourself with twitter account.please login with you credentials again.'));
            // if (ModUtil::apiFunc($this->name, 'user', 'logmein', $id)) {
            // echo "login"; exit;
            // $this->redirect(ModUtil::url('Users', 'user', 'main'));
            // }
            $this->redirect(ModUtil::url('Users', 'user', 'main'));
        }
        //  }
        LogUtil::registerError($this->__f('Something went wrong with your verification link.'));
        $this->redirect(ModUtil::url('Users', 'user', 'main'));
    }

    function setRedirectUrl($uid) {
        // ZSELEX_Controller_User::updateFromGuestCart();
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
