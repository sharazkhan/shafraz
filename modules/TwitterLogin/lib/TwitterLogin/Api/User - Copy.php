<?php

/**
 * TwitterLogin
 */

/**
 * Administrative API functions.
 */
class TwitterLogin_Api_User extends Zikula_AbstractApi {

    public $google;
    public $oauth2;

    protected function initialize() {


        $settings = ModUtil::getVar($this->name);

        // echo "<pre>";   prinr_r($settings); exit;
//        $this->google = new Facebook(array(
//                    'appId' => $settings['appid'],
//                    'secret' => $settings['secretkey']
//                ));
    }

    /**
     * Log in url .
     */
    public function getmyloginurl() {

        return $authUrl = $this->google->createAuthUrl();

        //$redirecturi = ModUtil::url($this->name, 'user', 'main', $args = array(), $ssl = null, $fragment = null, $fqurl = true, $forcelongurl = false, $forcelang = false);
        //return $this->google->getLoginUrl($params = array('scope' => 'email,read_stream', 'redirect_uri' => $redirecturi));
    }

    /**
     * FBID .
     */
    public function getmyfb_id() {
        //  echo "comes heree";  exit;

        if ($this->google->getAccessToken()) {
            // echo "comes here";  exit;
            $user = $this->oauth2->userinfo->get();
            // echo "<pre>";  print_r($user);  exit;

            $google_id = $user['id'];

            $_SESSION['token'] = $this->google->getAccessToken();
        } else {
            // echo "no id";  exit;
            $google_id = null;
        }
        //$google_id = $this->google->getUser();
        //$google_id = $googleuser_id;
        /*
          if ($google_id) {
          try {
          $user_profile = $this->google->api('/me');
          } catch (FacebookApiException $e) {
          $google_id = null;
          }
          }
         * 
         */

        return $google_id;
    }

    /**
     * User Data .
     */
    public function getmyfb_userdata() {
        return $this->facebook->api('/me');
    }

    /**
     * Login .
     */
    public function logmein($twitter_id) {

        $authenticationInfo = array(
            'twitter_id' => $twitter_id,
            'pass' => false,
        );
        $authenticationMethod = array(
            'modname' => 'TwitterLogin',
            'method' => 'TwitterLogin'
        );

        return UserUtil::loginUsing($authenticationMethod, $authenticationInfo, $rememberme, null, false);
    }

    /**
     * find connected user
     */
    public function get_myuid_bymyfb_id($fb_id) {

        $connection = $this->entityManager->getRepository('TwitterLogin_Entity_Connections')
                ->findOneBy(array('twitter_id' => $fb_id));
        if (!$connection) {
            $con = false;
        } else {
            $con = $connection->toArray();
        }

        return $con['user_id'];
    }

    /**
     * Setup connection private?
     */
    public function connectme($twitter_id, $user_id, $email) {

        // echo "come here"; exit;
        // echo $google_id;
        // echo "<br>";
        // echo $user_id; exit;
        //   $emailExist = $this->checkEmailExist($twitter_id);

        $connection = new TwitterLogin_Entity_Connections();
        $connection->settwitter_id($twitter_id);
        if (is_numeric($user_id)) {
            $connection->setuser_id($user_id);
        } else {
            $connection->setuser_id(UserUtil::getVar('uid'));
        }
        $connection->setemail($email);
        $status = '0';
        $connection->setstatus($status);

        $this->entityManager->persist($connection);
        $this->entityManager->flush();

        return true;
    }

    public function connectme_temperorily($twitter_id, $email) {

        //echo "come here"; exit;
        // echo $google_id;
        // echo "<br>";
        // echo $user_id; exit;
        //   $emailExist = $this->checkEmailExist($twitter_id);

        $connection = new TwitterLogin_Entity_Connections();
        $connection->settwitter_id($twitter_id);
        $connection->setuser_id('0');
        $connection->setemail($email);
        $status = '0';
        $connection->setstatus($status);

        $this->entityManager->persist($connection);
        $this->entityManager->flush();

        return true;
    }

    function checkEmailExist1($id) {

        $sql = "SELECT email from twitter_login WHERE twitter_id=$id";
        $query = DBUtil::executeSQL($sql);
        $result = $query->fetch();
        $email = $result['email'];
        return $email;
    }

    function checkEmailExist($id) {

        $sql = "SELECT email from twitter_login WHERE twitter_id=$id";
        $query = DBUtil::executeSQL($sql);
        $result = $query->fetch();
        $status = $result['status'];
        $email = $result['email'];

        if (!empty($email) && $status > 0) {  // everything ok , proceed
            $return = 1;
        } elseif (!empty($email) && $status < 1) { // email exist but not verified by user.
            $return = 2;
        } else {
            $return = 0;  // email doesnt exist;
        }
        return $return;
    }

    /**
     * Register me return user id .
     */
    public function registerme($user) {

        // $google_id = $this->google->getUser();
        //we need reg info so lets get it form facebook
        //email first
        //$user_data = $this->getmyfb_userdata();
        $user_data = (array) $user;
        // echo "User Info : <br>";
        //echo "<pre>";  print_r($user_data); exit;



        if ($user_data) {
            // echo "exist"; exit;
            $twitter_id = $user_data['id'];
            // echo $twitter_id; exit;
            $email = $user_data['email'];
            $checkEmail = $this->checkEmailExist($twitter_id);

            if ($checkEmail == 0) { // email doesnt exist;
                $this->connectme_temperorily($twitter_id, $email);
                return 0;
                //return $this->view->fetch('emailconfirm.tpl');
            } elseif ($checkEmail == 2) { // email exist not verified;
                return 2;
            }


            $twitter_name = $user_data['screen_name'];
            // echo "email here"; exit;
            //echo $email; exit;
            //check if email is registered
            //we should check first for the email settings in users module
            //if ($this->getVar(Users_Constant::MODVAR_REQUIRE_UNIQUE_EMAIL, false)) {
            // echo "comesddd"; exit;
            //$email = "no_email_$twitter_id@testing.com";
            $emailUsageCount = UserUtil::getEmailUsageCount($email);
            //  echo $emailUsageCount; exit;
            if ($emailUsageCount) {
                //get uid of user actually using this email
                // should be only for email strict mode
                // what if there is more accounts with same email? create another one?
                $user_to_connect = ModUtil::apiFunc('Users', 'admin', 'findUsers', array('email' => $email));
                //there can be only one :)
                $user_to_connect_id = $user_to_connect[0]['uid'];

                //echo $google_id;
                // echo "<br>";
                // echo $user_to_connect_id; exit;

                $this->connectme($twitter_id, $user_to_connect_id, $email);

                return true;
            }

            //  echo "comes hereeee";
            //exit;
            //no email used
            //process email			
            //generate uname
            $basename = $this->getunnamefromemail($email);
            $uname = $this->generateuname($basename);

            //   $uname = $this->generateuname($twitter_name);
            // valid uname and email proceed to 
            $reginfo = array(
                'uname' => $uname,
                'pass' => 'NO_USERS_AUTHENTICATION',
                'passreminder' => 'Account created with Twitter',
                'email' => $email,
            );

            $registeredObj = ModUtil::apiFunc('Users', 'registration', 'registerNewUser', array(
                        'reginfo' => $reginfo,
                        'usernotification' => false,
                        'adminnotification' => false
                    ));



            $verified = ModUtil::apiFunc('Users', 'registration', 'verify', array('reginfo' => $registeredObj));

            // echo "<pre>"; print_r($verified);  echo "</pre>"; exit;

            $this->connectme($twitter_id, $verified['uid'], $email);

            return true;
        }
        //echo "not exist"; exit;
        //Error no user data
        return false;
    }

    /**
     * Get part?
     */
    public function getunnamefromemail($email) {

        $email = explode('@', $email);
        $basename = $email[0];

        return $this->validatebasename($basename);
    }

    /**
     *  fix basename lenght illegal characters etc..
     */
    public function validatebasename($basename) {
        return $basename;
    }

    /**
     * uname need to be unique
     */
    public function generateuname($basename) {

        $umaneUsageCount = UserUtil::getUnameUsageCount($basename);
        if ($umaneUsageCount) {
            $basename = $basename . 'x';
            $basename = $this->generateuname($basename);
        }

        return $basename;
    }

}