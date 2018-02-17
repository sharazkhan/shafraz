<?php

/**
 * FConnect
 */

/**
 * Administrative API functions.
 */
class Google_Api_User extends Zikula_AbstractApi {

    public $google;
    public $oauth2;

    protected function initialize() {
        error_reporting(E_ALL);
        //echo 'comes here'; exit;
        //require_once 'modules/FConnect/lib/vendor/Facebook/facebook.php';
        //require_once 'config.php';
        require_once 'modules/Google/lib/vendor/Google/Google_Client.php';
        require_once 'modules/Google/lib/vendor/Google/Google_Oauth2Service.php';

        $settings = ModUtil::getVar($this->name);

        //echo "<pre>";   prinr_r($settings); exit;
//        $this->google = new Facebook(array(
//                    'appId' => $settings['appid'],
//                    'secret' => $settings['secretkey']
//                ));

        $this->google = new Google_Client();


        $this->google->setApplicationName("Google UserInfo PHP Starter Application");

        $this->google->setClientId($settings['clientid']);
        $this->google->setClientSecret($settings['secretkey']);
        $this->google->setRedirectUri($settings['redirecturi']);
        $this->google->setApprovalPrompt("auto");
        $this->google->setAccessType("online");

        $this->oauth2 = new Google_Oauth2Service($this->google);
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
            //echo "<pre>";  print_r($user);  exit;


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
    public function logmein($google_id) {
        //echo $google_id; exit;
        // echo "logmein"; exit;
        $authenticationInfo = array(
            'google_id' => $google_id,
            'pass' => false,
        );
        $authenticationMethod = array(
            'modname' => 'Google',
            'method' => 'Google'
        );

        $uid = $this->get_myuid_bymyfb_id($google_id);

        if ($uid > 0) {
            $ZikulaUserExist = $this->getZikulaUserCount($uid);
            //echo $ZikulaUserExist; exit;
            if (!$ZikulaUserExist) {
                $this->deleteGMAILuser($uid);
                $uid = null;
            }
        }

        return UserUtil::loginUsing($authenticationMethod, $authenticationInfo, $rememberme, null, false);
    }

    public function deleteGMAILuser($uid) {
        // echo "<pre>"; print_r($args);  echo "</pre>"; exit;
        $query = $this->entityManager->createQuery('delete from Google_Entity_Connections g where g.user_id=:user_id');
        $query->setParameter('user_id', $uid);
        $numDeleted = $query->execute();
        return $numDeleted;
    }

    function getZikulaUserCount($uid) {
        $sql = "SELECT COUNT(*) as count FROM users WHERE uid=$uid";
        //echo $sql; exit;
        $query = DBUtil::executeSQL($sql);
        $result = $query->fetch();
        $count = $result['count'];
        return $count;
    }

    /**
     * find connected user
     */
    public function get_myuid_bymyfb_id($fb_id) {

        $connection = $this->entityManager->getRepository('Google_Entity_Connections')
                ->findOneBy(array('google_id' => $fb_id));
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
    public function connectme($google_id, $user_id) {

        // echo "come here"; exit;
        // echo $google_id;
        // echo "<br>";
        // echo $user_id; exit;

        $connection = new Google_Entity_Connections();
        $connection->setgoogle_id($google_id);
        if (is_numeric($user_id)) {
            $connection->setuser_id($user_id);
        } else {
            $connection->setuser_id(UserUtil::getVar('uid'));
        }

        $this->entityManager->persist($connection);
        $this->entityManager->flush();

        return true;
    }

    /**
     * Register me return user id .
     */
    public function registerme($user) {

        // $google_id = $this->google->getUser();
        //we need reg info so lets get it form facebook
        //email first
        //$user_data = $this->getmyfb_userdata();
        $user_data = $user;
        // echo "<pre>";  print_r($user_data); exit;

        if ($user_data) {
            // echo "exist"; exit;
            $google_id = $user_data['id'];
            //echo $google_id; exit;
            $email = $user_data['email'];

            //echo $email; exit;
            //check if email is registered
            //we should check first for the email settings in users module
            //if ($this->getVar(Users_Constant::MODVAR_REQUIRE_UNIQUE_EMAIL, false)) {

            $emailUsageCount = UserUtil::getEmailUsageCount($email);
            //echo $emailUsageCount; exit;
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

                $this->connectme($google_id, $user_to_connect_id);

                return true;
            }

            //no email used
            //process email			
            //generate uname
            $basename = $this->getunnamefromemail($email);
            $uname = $this->generateuname($basename);

            // valid uname and email proceed to 
            $reginfo = array(
                'uname' => $uname,
                'pass' => 'NO_USERS_AUTHENTICATION',
                'passreminder' => 'Account created with Google',
                'email' => $email,
            );

            $registeredObj = ModUtil::apiFunc('Users', 'registration', 'registerNewUser', array(
                        'reginfo' => $reginfo,
                        'usernotification' => false,
                        'adminnotification' => false
            ));

            $verified = ModUtil::apiFunc('Users', 'registration', 'verify', array('reginfo' => $registeredObj));

            $this->connectme($google_id, $verified['uid']);

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
