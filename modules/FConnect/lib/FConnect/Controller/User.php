<?php

/**
 * FConnect
 */
class FConnect_Controller_User extends Zikula_AbstractController
{

    /**
     * The default entry point.
     */
    public function main()
    {
        $this->view->setCaching(false);
        $settings = ModUtil::getVar($this->name);

        //Parameter extraction and error checking
        if (!isset($settings['appid']) || !isset($settings['secretkey'])) {
            throw new Zikula_Exception_Fatal($this->__('Facebook login is not supported'));
        }

        //  echo "<pre>"; print_r($settings);  echo "</pre>"; exit;
        $fb_id = ModUtil::apiFunc($this->name, 'FacebookUser', 'getId');



        if (!$fb_id) {
            $loginUrl = ModUtil::apiFunc($this->name, 'Facebook', 'logInUrl');
            $this->redirect($loginUrl);
        }


        /// we should have facebook id now lets do what we want
        // is user logged in? or not		
        $uid = UserUtil::getVar('uid');
        // uid = 1 is the anonymous user
        if ($uid < 2) {


            if ($userDetails = ModUtil::apiFunc($this->name, 'user', 'login',
                    $fb_id)) {
                // echo "comes here"; exit;

                $returnUrl = $this->setRedirectUrl($userDetails['uid']);
                $this->redirect(ModUtil::url($returnUrl));
            } else if (ModUtil::apiFunc($this->name, 'user', 'register')) {


                if ($userDetails = ModUtil::apiFunc($this->name, 'user',
                        'login', $fb_id)) {
                    $returnUrl = $this->setRedirectUrl($userDetails['uid']);
                    $this->redirect(ModUtil::url($returnUrl));
                    //$this->redirect(ModUtil::url('Users', 'user', 'main'));
                }
            }
        } else {
            //user is logged in but not connected just connect	
            //ModUtil::apiFunc($this->name, 'user', 'connectme');
        }


        //exit;
        // Assign all the module vars
        return $this->view->assign('fb_id', $fb_id)
                ->fetch('fconnect_user_main.tpl');
    }

    /**
     * The default entry point.
     */
    public function updateavatar()
    {
        ModUtil::apiFunc($this->name, 'user', 'avatar');
        return System::redirect(ModUtil::url('Avatar', 'user', 'main'));
    }

    function setRedirectUrl($uid)
    {
        // ZSELEX_Controller_User::updateFromGuestCart();
        ModUtil::apiFunc('ZSELEX', 'cart', 'updateFromGuestCart');
        $returnUrl = ModUtil::url('Users', 'user', 'main');
        if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN) && SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADD)) {
            $item                 = ModUtil::apiFunc('ZSELEX', 'user',
                    'selectRow',
                    array('table' => 'zselex_shop a , zselex_shop_owners b',
                    'fields' => array('a.shop_id', 'a.shop_name'),
                    'where' => array("b.user_id=$uid", "a.shop_id=b.shop_id"),
                    'orderby' => "CASE WHEN b.main = '1'
                                      THEN b.main END DESC ,
                                      CASE WHEN b.main = '0' 
                                      THEN a.shop_id  END ASC"
            ));
            //exit;
            $_SESSION['mainshop'] = $item['shop_id'];
        } elseif (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADD) && SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_EDIT)) {

            $item = ModUtil::apiFunc('ZSELEX', 'user', 'selectJoinRow',
                    array('table' => 'zselex_shop_admins a',
                    'fields' => array('a.shop_id as shop_id', 'b.shop_name'),
                    'joins' => array('LEFT JOIN zselex_shop b ON b.shop_id=a.shop_id'),
                    'where' => array("a.user_id=$uid"),
                    'orderby' => "CASE WHEN b.main = '1'
                                      THEN b.main END DESC ,
                                      CASE WHEN b.main = '0' 
                                      THEN b.shop_id  END ASC"));



            //echo "<pre>"; print_r($item);   echo "</pre>"; exit;
            $_SESSION['mainshop'] = $item['shop_id'];
        }
        $shop_id = $item['shop_id'];

        $groupsql = "SELECT gid FROM group_membership WHERE uid=$uid && gid <> 1"; // Except group User
        $gquery   = DBUtil::executeSQL($groupsql);
        $gresult  = $gquery->fetch();
        $gid      = $gresult[gid];
        if ($gid == 2) {
            $returnUrl = ModUtil::url('ZSELEX', 'admin', 'viewshop');
//die("SUPERADMIN! uid=".$userId.", gid=".$gid);
        } else {
            $modvars = ModUtil::getVar('ZSELEX');
//print_r($modvars);
//die("uid=".$userId.", gid=".$gid);
            if (($gid == $modvars['shopOwnerGroup']) || ($gid == $modvars['shopAdminGroup'])) {
                $returnUrl = ModUtil::url('ZSELEX', 'admin', 'shopsummary',
                        array('shop_id' => $shop_id));
//die("OWNER or ADMIN! uid=".$userId.", gid=".$gid);
            } else {
                $returnUrl = ModUtil::url('Users', 'user', 'main');
//die("NORMAL USER! uid=".$userId.", gid=".$gid);
            }
        }

        return $returnUrl;
    }
}