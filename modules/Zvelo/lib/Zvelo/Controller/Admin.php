<?php

/**
 * Zvelo
 */

/**
 * This is the Admin controller class providing navigation and interaction functionality.
 */
class Zvelo_Controller_Admin extends Zikula_AbstractController {

    /**
     * This method is the default function.
     *
     * Called whenever the module's Admin area is called without defining arguments.
     *
     * @param array $args Array.
     *
     * @return redirect
     */
    public function main($args) {
        $this->redirect(ModUtil::url('Zvelo', 'admin', 'view', $args));
    }

    /**
     * This method provides a generic item list overview.
     *
     * @param array $args Array.
     *
     * @return string|boolean Output.
     */
    public function view($args) {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Tag::', '::', ACCESS_EDIT), LogUtil::getErrorMsgPermission());



        return $this->view->assign('tags', $tags)
                        ->fetch('admin/view.tpl');
    }

    public function upgrade() {
        $upgrade = DoctrineHelper::updateSchema($this->entityManager, array(
                    'Zvelo_Entity_Customer',
                    'Zvelo_Entity_CustomerWish',
                    'Zvelo_Entity_Bicycle',
                    'Zvelo_Entity_CustomerErgonomicValue',
                    'Zvelo_Entity_CustomerMeasurement'
        ));

        /* $create = DoctrineHelper::createSchema($this->entityManager, array(
          'Zvelo_Entity_CustomerWish'
          )); */
        LogUtil::registerStatus($this->__('Upgraded Successfully'));
        return $this->redirect(ModUtil::url('Zvelo', 'admin', 'view'));
    }

    public function updateBicycleInfo() {
        $bicyleArr = array(
            array('name' => 'City Bike', 'nos' => '1', 'iconname' => 'City', 'imagename' => 'cityIMG.png', 'imagename2' => 'BikeImage.png', 'description' => 'test description1'),
            array('name' => 'Trekking Bike', 'nos' => '2', 'iconname' => 'Trekking', 'imagename' => 'trekkingIMG.png', 'imagename2' => 'BikeImage.png', 'description' => 'test description2'),
            array('name' => 'Cross Bike', 'nos' => '3', 'iconname' => 'Cross', 'imagename' => 'crossIMG.png', 'imagename2' => 'BikeImage.png', 'description' => 'test description3'),
            array('name' => 'Fitness Bike', 'nos' => '4', 'iconname' => 'Fitness', 'imagename' => 'Fitness.png', 'imagename2' => 'BikeImage.png', 'description' => 'test description4'),
            array('name' => 'MTB Cross Bike', 'nos' => '5', 'iconname' => 'MTB Cross', 'imagename' => 'MTBcrosscountryIMG.png', 'imagename2' => 'BikeImage.png', 'description' => 'test description5'),
            array('name' => 'MTB DH/FR Bike', 'nos' => '6', 'iconname' => 'MTB DH/FR', 'imagename' => 'MTBdhfrIMG.png', 'imagename2' => 'BikeImage.png', 'description' => 'test description6'),
        );
        foreach ($bicyleArr as $value) {
            $bicycleEntity = new Zvelo_Entity_Bicycle();
            $bicycleEntity->setName($value['name']);
            $bicycleEntity->setNos($value['nos']);
            $bicycleEntity->setIconname($value['iconname']);
            $bicycleEntity->setImagename($value['imagename']);
            $bicycleEntity->setImagename2($value['imagename2']);
            $bicycleEntity->setDescription($value['description']);
            $this->entityManager->persist($bicycleEntity);
            $bicycleId = $bicycleEntity->getBicycle_id();
        }
        $this->entityManager->flush();
        LogUtil::registerStatus($this->__('Upgraded Successfully'));
        return $this->redirect(ModUtil::url('Zvelo', 'admin', 'view'));
    }

    /**
     * modify the module settings
     */
    public function modifyconfig($args) {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Tag::', '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());

        return $this->view->fetch('admin/modifyconfig.tpl');
    }

    /**
     * @desc sets module variables as requested by admin
     * @return      status/error ->back to modify config page
     */
    public function updateconfig() {
        $this->checkCsrfToken();

        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Tag::', '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());

        $modvars = array(
            'poptagsoneditform' => $this->request->getPost()->get('poptagsoneditform', 10),
        );

        // set the new variables
        $this->setVars($modvars);

        // clear the cache
        $this->view->clear_cache();

        LogUtil::registerStatus($this->__('Done! Updated the Tag configuration.'));
        return $this->redirect(ModUtil::url('Tag', 'admin', 'view', array()));
    }

    /**
     * Migrate existing tags in crpTag to Tag
     * Migrates both Tags and Objects with relation
     * Does not confirm existence of tagged object
     */
    public function migrateCrpTag() {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Tag::', '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());
        if ($this->getVar('crpTagMigrateComplete')) {
            LogUtil::registerStatus($this->__('CrpTag has been already been migrated. You can only run the migration once.'));
            $this->redirect(ModUtil::url('Tag', 'admin', 'view'));
        }

        $objCount = 0;
        $tagCount = 0;

        // use 'brute force' sql to obtain all tags
        $conn = $this->entityManager->getConnection();
        $prefix = $this->serviceManager['prefix'];
        // get all available tags
        $sql = "SELECT DISTINCT name from {$prefix}_crptag";
        $tags = $conn->fetchAll($sql);
        foreach ($tags as $tag) {
            $word = $tag['name'];
            $tagObject = $this->entityManager->getRepository('Tag_Entity_Tag')->findOneBy(array('tag' => $word));
            if (!isset($tagObject)) {
                $tagObject = new Tag_Entity_Tag();
                $tagObject->setTag($word);
                $this->entityManager->persist($tagObject);
                $tagCount++;
            }
        }
        $this->entityManager->flush();

        // more 'brute force' sql to obtain object values
        $sql = "SELECT DISTINCT id_module, module from {$prefix}_crptag_archive";
        $objects = $conn->fetchAll($sql);
        foreach ($objects as $object) {
            // search for existing object - it SHOULDN'T exist!
            $hookObject = $this->entityManager
                    ->getRepository('Tag_Entity_Object')
                    ->findOneBy(array(
                'module' => $object['module'],
                'objectId' => $object['id_module']));
            if (isset($hookObject)) {
                $this->entityManager->remove($hookObject);
            }
            // get the most likely areaID
            // Doctrine 1.2 method because Hook Tables support only this
            $area = Doctrine_Core::getTable('Zikula_Doctrine_Model_HookArea')->createQuery()
                    ->where("owner = ?", $object['module'])
                    ->andWhere("areatype = ?", 's')
                    ->andWhere("category = ?", 'ui_hooks')
                    ->execute()
                    ->toArray();
            $areaId = $area[0]['id'];
            // no way to adequately determine URL, so insert generic module link
            $objUrl = ModUtil::url($object['module'], 'user', 'main');
            $hookObject = new Tag_Entity_Object($object['module'], $object['id_module'], $areaId, $objUrl);

            // even more 'brute force' sql to obtain related tag values
            $sql = "SELECT t.name FROM {$prefix}_crptag_archive a LEFT JOIN {$prefix}_crptag t" .
                    " ON a.id_tag = t.id WHERE a.id_module=$object[id_module]";
            $tags = $conn->fetchAll($sql);

            foreach ($tags as $tag) {
                $word = $tag['name'];
                $tagObject = $this->entityManager->getRepository('Tag_Entity_Tag')->findOneBy(array('tag' => $word));
                // all tags should exist - but just in case
                if (!isset($tagObject)) {
                    $tagObject = new Tag_Entity_Tag();
                    $tagObject->setTag($word);
                    $this->entityManager->persist($tagObject);
                }
                $hookObject->assignToTags($tagObject);
            }
            $this->entityManager->persist($hookObject);
            $objCount++;
        }
        $this->entityManager->flush();
        LogUtil::registerStatus($this->__f('CrpTag has been migrated. %1$s objects and %2$s tags completed.', array($objCount, $tagCount)));
        $this->setVar('crpTagMigrateComplete', true);
        $this->redirect(ModUtil::url('Tag', 'admin', 'view'));
    }

}

