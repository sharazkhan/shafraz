<?php

/**
 * Tag - a content-tagging module for the Zikukla Application Framework
 * 
 * @license MIT
 *
 * Please see the NOTICE file distributed with this source code for further
 * information regarding copyright and licensing.
 */
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM;
use Doctrine\ORM\Query\ResultSetMapping;

/**
 * Repository class for DQL calls
 */
class ZSELEX_Entity_Repository_ShopOwnerRepo extends ZSELEX_Entity_Repository_General
{

    public function getOwnerInfo($args)
    {
        // return;
        // echo "<pre>"; print_r($args); echo "</pre>"; exit;
        try {
            $shop_id = $args ['shop_id'];
            // echo "<pre>"; print_r($setParams); echo "</pre>"; exit;

            $rsm = new ORM\Query\ResultSetMapping ();

            $rsm->addEntityResult('ZSELEX_Entity_ShopOwner', 'u');
            // $rsm->addFieldResult('u', 'shop', 'shop');
            $rsm->addFieldResult('u', 'user_id', 'user_id');
            // $rsm->addMetaResult('u', 'shop_id', 'shop_id');
            $rsm->addMetaResult('u', 'uid', 'uid');
            $rsm->addMetaResult('u', 'uname', 'uname');
            $rsm->addMetaResult('u', 'email', 'email');

            /*
             * $dql = "SELECT a.uid,a.uname,a.email
             * FROM zselex_shop_owners u
             * INNER JOIN users a ON a.uid=u.user_id
             * INNER JOIN zselex_shop b ON b.shop_id=u.shop_id
             * WHERE a.uid=u.user_id AND u.shop_id=b.shop_id AND b.shop_id=$shop_id";
             */

            /*
             * $dql = "SELECT u.user_id , a.uid , a.uname , a.email
             * FROM zselex_shop_owners u
             * INNER JOIN zselex_shop b ON b.shop_id=u.shop_id
             * INNER JOIN users a ON a.uid=u.user_id
             * WHERE u.shop_id=b.shop_id AND b.shop_id=$shop_id AND (u.co_owner=0 OR u.main=1)";
             */
            $dql   = "SELECT  u.user_id , a.uid , a.uname , a.email
                FROM zselex_shop_owners u
               
                INNER JOIN users a ON a.uid=u.user_id
                WHERE u.shop_id=$shop_id AND (u.co_owner=0 OR u.main=1)";
            // echo $dql;
            $query = $this->_em->createNativeQuery($dql, $rsm);

            // $query->setParameters('shop_id', $shop_id);
            // echo $query->getSQL() . '<br>';
            $result = $query->getOneOrNullResult(2);
            // $result = $query->getResult(2);
            // echo "<pre>"; print_r($result); echo "</pre>"; exit;
        } catch (\Exception $e) {
            // echo 'Message: ' . $e->getMessage();
            // exit;
        }

        return $result;
    }

    public function getOwner($args)
    {
        $shop_id = $args ['shop_id'];

        try {

            // echo "<pre>"; print_r($setParams); echo "</pre>"; exit;

            $rsm = new ORM\Query\ResultSetMapping ();

            $rsm->addEntityResult('ZSELEX_Entity_ShopOwner', 'u');
            // $rsm->addFieldResult('u', 'shop', 'shop');
            $rsm->addFieldResult('u', 'user_id', 'user_id');
            // $rsm->addMetaResult('u', 'shop_id', 'shop_id');
            $rsm->addMetaResult('u', 'uid', 'uid');
            $rsm->addMetaResult('u', 'uname', 'uname');
            $rsm->addMetaResult('u', 'email', 'email');

            $dql   = "SELECT  u.user_id , a.uname
                FROM zselex_shop_owners u
                INNER JOIN zselex_shop b ON b.shop_id=u.shop_id
                INNER JOIN users a ON a.uid=u.user_id
                WHERE u.shop_id=b.shop_id AND b.shop_id=$shop_id";
            $query = $this->_em->createNativeQuery($dql, $rsm);

            $result = $query->getOneOrNullResult(2);

            // echo "<pre>"; print_r($result); echo "</pre>";
        } catch (\Exception $e) {
            // echo 'Message: ' . $e->getMessage();
            // exit;
        }

        return $result ['uname'];
    }

    public function getPermission($args)
    {
        $shop_id = $args ['shop_id'];
        $user_id = $args ['user_id'];

        $dql = "SELECT COUNT(a.id) FROM ZSELEX_Entity_ShopOwner a
          INNER JOIN a.shop b
          WHERE a.shop=b.shop_id AND a.shop=$shop_id AND b.shop_id=$shop_id AND a.user_id=$user_id";

        /*
         * $dql = "SELECT COUNT(s.id) FROM ZSELEX_Entity_ShopOwner s
         * WHERE s.shop=$shop_id AND s.user_id=$user_id";
         */

        // echo $dql . '<br>';
        $query = $this->_em->createQuery($dql);
        // $query->setParameter('shop_id', $shop_id);
        $count = $query->getSingleScalarResult();
        return $count;
    }

    public function getOwnerLike($args)
    {
        $ownerName = $args ['owner'];

        try {
            // echo "<pre>"; print_r($setParams); echo "</pre>"; exit;
            $rsm = new ORM\Query\ResultSetMapping ();
            $rsm->addEntityResult('ZSELEX_Entity_ShopOwner', 'u');
            $rsm->addFieldResult('u', 'user_id', 'user_id');
            // $rsm->addMetaResult('u', 'uname', 'uname');

            $dql    = "SELECT u.user_id
                FROM zselex_shop_owners u
                INNER JOIN users a ON a.uid=u.user_id
                WHERE a.uname LIKE '%".DataUtil::formatForStore($ownerName)."%' 
                GROUP BY a.uid";
            // echo $dql;
            $query  = $this->_em->createNativeQuery($dql, $rsm);
            $result = $query->getArrayResult();
            // echo "<pre>"; print_r($result); echo "</pre>";

            return $result;
            // echo "<pre>"; print_r($result); echo "</pre>";
        } catch (\Exception $e) {
            // echo 'Message: ' . $e->getMessage();
            // exit;
        }
    }

    public function getOwnerThemes($args)
    {
        $user_id = $args ['user_id'];
        $shop_id = $args ['shop_id'];

        try {

            $rsm = new ORM\Query\ResultSetMapping ();
            $rsm->addEntityResult('ZSELEX_Entity_ShopOwnerTheme', 'u');

            $rsm->addFieldResult('u', 'id', 'id');
            $rsm->addMetaResult('u', 'name', 'name');
            $rsm->addMetaResult('u', 'displayname', 'displayname');
            $rsm->addMetaResult('u', 'description', 'description');

            if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN) && SecurityUtil::checkPermission('ZSELEX::',
                    '::', ACCESS_ADD)) {

                $dql = "SELECT u.id , t.name , t.displayname , t.description
               FROM themes t , zselex_shop_owners_theme u 
               WHERE u.user_id='".$user_id."' AND u.theme_id=t.id";
            }

            if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADD) && SecurityUtil::checkPermission('ZSELEX::',
                    '::', ACCESS_EDIT)) {
                $dql = "SELECT u.id , t.name , t.displayname , t.description
               FROM themes t , zselex_shop_owners_theme u
               WHERE u.theme_id=t.id AND u.user_id IN(SELECT user_id FROM zselex_shop_admins WHERE shop_id=$shop_id AND owner_id=$user_id)";
            } elseif (SecurityUtil::checkPermission('ZSELEX::', '::',
                    ACCESS_ADMIN)) {

                $dql = "SELECT u.id , t.name , t.displayname , t.description
               FROM themes t , zselex_shop_owners_theme u
               WHERE u.theme_id=t.id AND u.user_id IN(SELECT user_id FROM zselex_shop_owners WHERE shop_id=$shop_id)";
            }

            // echo $dql; exit;

            $query  = $this->_em->createNativeQuery($dql, $rsm);
            $result = $query->getResult(2);

            // echo "<pre>"; print_r($result); echo "</pre>";
        } catch (\Exception $e) {
            // echo 'Message: ' . $e->getMessage();
            // exit;
        }

        return $result;
    }

    public function getShopOwners($args)
    {
        $shop_id = $args ['shop_id'];

        try {
            // echo "<pre>"; print_r($setParams); echo "</pre>"; exit;
            $rsm = new ORM\Query\ResultSetMapping ();
            $rsm->addEntityResult('ZSELEX_Entity_ShopOwner', 'u');
            $rsm->addFieldResult('u', 'user_id', 'user_id');
            $rsm->addMetaResult('u', 'uname', 'uname');
            $rsm->addMetaResult('u', 'user_regdate', 'user_regdate');
            $rsm->addMetaResult('u', 'lastlogin', 'lastlogin');
            $rsm->addMetaResult('u', 'passreminder', 'passreminder');
            $rsm->addMetaResult('u', 'pass', 'pass');
            $rsm->addMetaResult('u', 'approved_date', 'approved_date');
            $rsm->addMetaResult('u', 'approved_by', 'approved_by');
            $rsm->addMetaResult('u', 'theme', 'theme');
            $rsm->addMetaResult('u', 'ublockon', 'ublockon');
            $rsm->addMetaResult('u', 'ublock', 'ublock');
            $rsm->addMetaResult('u', 'tz', 'tz');
            $rsm->addMetaResult('u', 'locale', 'locale');
            $rsm->addMetaResult('u', 'uid', 'uid');
            $rsm->addMetaResult('u', 'activated', 'activated');

            $dql    = "SELECT u.user_id  , a.uid  , a.uname , a.user_regdate , a.lastlogin , a.passreminder ,
                a.pass , a.approved_date , a.approved_by , a.theme , a.ublockon , a.ublock , a.tz , a.locale , a.activated
                FROM zselex_shop_owners u
                INNER JOIN users a ON a.uid=u.user_id
                WHERE u.shop_id = $shop_id
                GROUP BY a.uid";
           // echo $dql; exit;
            $query  = $this->_em->createNativeQuery($dql, $rsm);
            $result = $query->getArrayResult();
            // echo "<pre>"; print_r($result); echo "</pre>";

            return $result;
            // echo "<pre>"; print_r($result); echo "</pre>";
        } catch (\Exception $e) {
            // echo 'Message: ' . $e->getMessage();
            // exit;
        }
    }

    public function getExistingOwners($args)
    {
        $shop_id = $args ['shop_id'];

        $ownerGroup = $args ['group_id'];
        $append     = $args ['append'];
        $start      = $args ['startnum'];
        $limit      = $args ['itemsperpage'];

        try {
            $statement = Doctrine_Manager::getInstance()->connection();

            $dql    = "SELECT a.gid  , b.uid , b.uname , d.shop_id , c.user_id
                FROM group_membership a
                INNER JOIN users b ON a.uid=b.uid
                LEFT JOIN zselex_shop_owners c ON c.user_id=b.uid
                LEFT JOIN zselex_shop d ON d.shop_id=c.shop_id
                WHERE a.gid = $ownerGroup  ".$append."
                GROUP BY b.uid
                LIMIT $start , $limit";
            // echo $dql;
            $query  = $statement->execute($dql);
            $result = $query->fetchAll();
            return $result;
            // echo "<pre>"; print_r($result); echo "</pre>";
        } catch (\Exception $e) {
            // echo 'Message: ' . $e->getMessage();
            // exit;
        }
    }

    public function getExistingOwnersCount($args)
    {
        $shop_id    = $args ['shop_id'];
        $ownerGroup = $args ['group_id'];
        $append     = $args ['append'];

        try {
            $statement = Doctrine_Manager::getInstance()->connection();

            $dql = "SELECT a.gid  , b.uid , b.uname , d.shop_id , c.user_id
                FROM group_membership a
                INNER JOIN users b ON a.uid=b.uid
                LEFT JOIN zselex_shop_owners c ON c.user_id=b.uid
                LEFT JOIN zselex_shop d ON d.shop_id=c.shop_id
                WHERE a.gid = $ownerGroup ".$append."
                GROUP BY b.uid";

            // echo $dql;
            $query  = $statement->execute($dql);
            $result = $query->rowCount();
            return $result;
            // echo "<pre>"; print_r($result); echo "</pre>";
        } catch (\Exception $e) {
            // echo 'Message: ' . $e->getMessage();
            // exit;
        }
    }

    public function insertAdminToOwnerGroup($args)
    {
        $ownerGroup = $args ['group_id'];
        $user_id    = $args ['user_id'];

        try {
            $statement = Doctrine_Manager::getInstance()->connection();
            $dql       = "INSERT INTO group_membership (gid , uid) VALUES($ownerGroup , $user_id)";
            // echo $dql;
            $query     = $statement->execute($dql);
            return $query;
            // echo "<pre>"; print_r($result); echo "</pre>";
        } catch (\Exception $e) {
            // echo 'Message: ' . $e->getMessage();
            // exit;
        }
    }

    public function getOwners($args)
    {
        // return;
        // echo "<pre>"; print_r($args); echo "</pre>"; exit;
        try {
            $shop_id = $args ['shop_id'];
            // echo "<pre>"; print_r($setParams); echo "</pre>"; exit;

            $rsm = new ORM\Query\ResultSetMapping ();

            $rsm->addEntityResult('ZSELEX_Entity_ShopOwner', 'u');
            // $rsm->addFieldResult('u', 'shop', 'shop');
            $rsm->addFieldResult('u', 'user_id', 'user_id');
            // $rsm->addMetaResult('u', 'shop_id', 'shop_id');
            $rsm->addMetaResult('u', 'uid', 'uid');
            $rsm->addMetaResult('u', 'uname', 'uname');
            $rsm->addMetaResult('u', 'email', 'email');

            /*
             * $dql = "SELECT a.uid,a.uname,a.email
             * FROM zselex_shop_owners u
             * INNER JOIN users a ON a.uid=u.user_id
             * INNER JOIN zselex_shop b ON b.shop_id=u.shop_id
             * WHERE a.uid=u.user_id AND u.shop_id=b.shop_id AND b.shop_id=$shop_id";
             */

            $dql   = "SELECT  u.user_id , a.uid , a.uname , a.email
                FROM zselex_shop_owners u
                INNER JOIN zselex_shop b ON b.shop_id=u.shop_id
                INNER JOIN users a ON a.uid=u.user_id
                WHERE u.shop_id=b.shop_id AND b.shop_id=$shop_id";
            // echo $dql;
            $query = $this->_em->createNativeQuery($dql, $rsm);

            // $query->setParameters('shop_id', $shop_id);
            // echo $query->getSQL() . '<br>';
            $result = $query->getArrayResult();
            // $result = $query->getResult(2);
            // echo "<pre>"; print_r($result); echo "</pre>"; exit;
        } catch (\Exception $e) {
            // echo 'Message: ' . $e->getMessage();
            // exit;
        }

        return $result;
    }
}