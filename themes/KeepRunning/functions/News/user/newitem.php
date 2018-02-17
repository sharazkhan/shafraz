<?php


    function newitem($params , Zikula_View &$smarty) {
       //echo "new item";  exit;
       // $this->throwForbiddenUnless(SecurityUtil::checkPermission('News::', '::', ACCESS_COMMENT), LogUtil::getErrorMsgPermission());

        // Any item set for preview will be stored in a session var
        // Once the new article is posted we'll clear the session var.
        $item = array();
        $sess_item = SessionUtil::getVar('newsitem');

        // get the type parameter so we can decide what template to use
        $type = FormUtil::getPassedValue('type', 'user', 'REQUEST');

        // Set the default values for the form. If not previewing an item prior
        // to submission these values will be null but do need to be set
        $item['sid'] = isset($sess_item['sid']) ? $sess_item['sid'] : '';
        $item['__CATEGORIES__'] = isset($sess_item['__CATEGORIES__']) ? $sess_item['__CATEGORIES__'] : array();
        $item['__ATTRIBUTES__'] = isset($sess_item['__ATTRIBUTES__']) ? $sess_item['__ATTRIBUTES__'] : array();
        $item['title'] = isset($sess_item['title']) ? $sess_item['title'] : '';
        $item['urltitle'] = isset($sess_item['urltitle']) ? $sess_item['urltitle'] : '';
        $item['hometext'] = isset($sess_item['hometext']) ? $sess_item['hometext'] : '';
        $item['hometextcontenttype'] = isset($sess_item['hometextcontenttype']) ? $sess_item['hometextcontenttype'] : '';
        $item['bodytext'] = isset($sess_item['bodytext']) ? $sess_item['bodytext'] : '';
        $item['bodytextcontenttype'] = isset($sess_item['bodytextcontenttype']) ? $sess_item['bodytextcontenttype'] : '';
        $item['notes'] = isset($sess_item['notes']) ? $sess_item['notes'] : '';
        $item['displayonindex'] = isset($sess_item['displayonindex']) ? $sess_item['displayonindex'] : 1;
        $item['language'] = isset($sess_item['language']) ? $sess_item['language'] : '';
        $item['allowcomments'] = isset($sess_item['allowcomments']) ? $sess_item['allowcomments'] : 1;
        $item['from'] = isset($sess_item['from']) ? $sess_item['from'] : DateUtil::getDatetime(null, '%Y-%m-%d %H:%M');
        $item['to'] = isset($sess_item['to']) ? $sess_item['to'] : DateUtil::getDatetime(null, '%Y-%m-%d %H:%M');
        $item['tonolimit'] = isset($sess_item['tonolimit']) ? $sess_item['tonolimit'] : 1;
        $item['unlimited'] = isset($sess_item['unlimited']) ? $sess_item['unlimited'] : 1;
        $item['weight'] = isset($sess_item['weight']) ? $sess_item['weight'] : 0;
        $item['pictures'] = isset($sess_item['pictures']) ? $sess_item['pictures'] : 0;
        $item['tempfiles'] = isset($sess_item['tempfiles']) ? $sess_item['tempfiles'] : null;
        $item['temp_pictures'] = isset($sess_item['tempfiles']) ? unserialize($sess_item['tempfiles']) : null;

        $preview = '';
        if (isset($sess_item['action']) && $sess_item['action'] == self::ACTION_PREVIEW) {
            $preview = $this->preview(array('title' => $item['title'],
                        'hometext' => $item['hometext'],
                        'hometextcontenttype' => $item['hometextcontenttype'],
                        'bodytext' => $item['bodytext'],
                        'bodytextcontenttype' => $item['bodytextcontenttype'],
                        'notes' => $item['notes'],
                        'sid' => $item['sid'],
                        'pictures' => $item['pictures'],
                        'temp_pictures' => $item['temp_pictures']));
        }

        // Get the module vars
     //$modvars = $this->getVars();
        
             $modvars = ModUtil::getVar('News');

        if ($modvars['enablecategorization']) {
            $catregistry = CategoryRegistryUtil::getRegisteredModuleCategories('News', 'news');
            $smarty->assign('catregistry', $catregistry);

            // add article attribute if morearticles is enabled and general setting is zero
            if ($modvars['enablemorearticlesincat'] && $modvars['morearticlesincat'] == 0) {
                $item['__ATTRIBUTES__']['morearticlesincat'] = 0;
            }
        }

        // Assign the default languagecode
         $smarty->assign('lang', ZLanguage::getLanguageCode());

        // Assign the item to the template
         $smarty->assign('item', $item);

        // Assign the content format
        $formattedcontent = ModUtil::apiFunc('News', 'user', 'isformatted', array('func' => 'new'));
        $smarty->view->assign('formattedcontent', $formattedcontent);

        $smarty->assign('accessadd', 0);
        if (SecurityUtil::checkPermission('News::', '::', ACCESS_ADD)) {
            $smarty->assign('accessadd', 1);
            $smarty->assign('accesspicupload', 1);
            $smarty->assign('accesspubdetails', 1);
        } else {
            $smarty->assign('accesspicupload', SecurityUtil::checkPermission('News:pictureupload:', '::', ACCESS_ADD));
            $smarty->assign('accesspubdetails', SecurityUtil::checkPermission('News:publicationdetails:', '::', ACCESS_ADD));
        }

        $smarty->assign('preview', $preview);

        // Return the output that has been generated by this function
        return $smarty->fetch('user/create.tpl');
       
    }


?>