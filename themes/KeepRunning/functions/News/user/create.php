<?php

    function create($args) {
        //echo "Works Fine!!!";  exit;
        // Get parameters from whatever input we need
        $story = FormUtil::getPassedValue('story', isset($args['story']) ? $args['story'] : null, 'POST');
        $files = News_ImageUtil::reArrayFiles(FormUtil::getPassedValue('news_files', null, 'FILES'));

        //echo "<pre>";  print_r($story);  echo "</pre>"; exit;
        // Create the item array for processing
        $item = array(
            'title' => $story['title'],
            'urltitle' => isset($story['urltitle']) ? $story['urltitle'] : '',
            '__CATEGORIES__' => isset($story['__CATEGORIES__']) ? $story['__CATEGORIES__'] : null,
            '__ATTRIBUTES__' => isset($story['attributes']) ? News_Util::reformatAttributes($story['attributes']) : null,
            'language' => isset($story['language']) ? $story['language'] : '',
            'hometext' => isset($story['hometext']) ? $story['hometext'] : '',
            'hometextcontenttype' => $story['hometextcontenttype'],
            'bodytext' => isset($story['bodytext']) ? $story['bodytext'] : '',
            'bodytextcontenttype' => $story['bodytextcontenttype'],
            'notes' => $story['notes'],
            'displayonindex' => isset($story['displayonindex']) ? $story['displayonindex'] : 0,
            'allowcomments' => isset($story['allowcomments']) ? $story['allowcomments'] : 0,
            'from' => isset($story['from']) ? $story['from'] : null,
            'tonolimit' => isset($story['tonolimit']) ? $story['tonolimit'] : null,
            'to' => isset($story['to']) ? $story['to'] : null,
            'unlimited' => isset($story['unlimited']) && $story['unlimited'] ? true : false,
            'weight' => isset($story['weight']) ? $story['weight'] : 0,
            'action' => isset($story['action']) ? $story['action'] : self::ACTION_PREVIEW,
            'sid' => isset($story['sid']) ? $story['sid'] : null,
            'tempfiles' => isset($story['tempfiles']) ? $story['tempfiles'] : null,
            'del_pictures' => isset($story['del_pictures']) ? $story['del_pictures'] : null,
        );


        //  echo "<pre>";  print_r($item);  echo "</pre>"; exit;
        // convert user times to server times (TZ compensation) refs #181
        //  can't do the below because values are YYYY-MM-DD HH:MM:SS and DateUtil value is in seconds.
        // $item['from'] = $item['from'] + DateUtil::getTimezoneUserDiff();
        // $item['to'] = $item['to'] + DateUtil::getTimezoneUserDiff();
        // Disable the non accessible fields for non editors
        if (!SecurityUtil::checkPermission('News::', '::', ACCESS_ADD)) {
            $item['notes'] = '';
            $item['displayonindex'] = 1;
            $item['allowcomments'] = 1;
            $item['from'] = null;
            $item['tonolimit'] = true;
            $item['to'] = null;
            $item['unlimited'] = true;
            $item['weight'] = 0;
            if ($item['action'] > self::ACTION_SUBMIT) {
                $item['action'] = self::ACTION_PREVIEW;
            }
        }

        // Validate the input
        $validationerror = News_Util::validateArticle($item);
        // check hooked modules for validation
        $sid = isset($item['sid']) ? $item['sid'] : null;
        $hookvalidators = News_Controller_User::notifyHooks(new Zikula_ValidationHook('news.ui_hooks.articles.validate_edit', new Zikula_Hook_ValidationProviders()))->getValidators();
        if ($hookvalidators->hasErrors()) {
            $validationerror .= News_Controller_User::__('Error! Hooked content does not validate.') . "\n";
        }

        // get all module vars
        // $modvars = News_Controller_User::getVars();

        $modvars = ModUtil::getVar('News');


        //echo "<pre>"; print_r($modvars);   echo "</pre>"; exit;


        if (isset($files) && $modvars['picupload_enabled']) {
            list($files, $item) = News_ImageUtil::validateImages($files, $item);
        } else {
            $item['pictures'] = 0;
        }

        // story was previewed with uploaded pics
        if (isset($item['tempfiles'])) {
            $tempfiles = unserialize($item['tempfiles']);
            // delete files if requested
            if (isset($item['del_pictures'])) {
                foreach ($tempfiles as $key => $file) {
                    if (in_array($file['name'], $item['del_pictures'])) {
                        unset($tempfiles[$key]);
                        News_ImageUtil::removePreviewImages(array($file));
                    }
                }
            }
            $files = array_merge($files, $tempfiles);
            $item['pictures'] += count($tempfiles);
        }

        // if the user has selected to preview the article we then route them back
        // to the new function with the arguments passed here
        if ($item['action'] == self::ACTION_PREVIEW || $validationerror !== false) {
            // log the error found if any
            if ($validationerror !== false) {
                LogUtil::registerError(nl2br($validationerror));
            }
            if ($item['pictures'] > 0) {
                $tempfiles = News_ImageUtil::tempStore($files);
                $item['tempfiles'] = serialize($tempfiles);
            }
            // back to the referer form
            SessionUtil::setVar('newsitem', $item);
            News_Controller_User::redirect(ModUtil::url('News', 'user', 'newitem'));
        } else {
            // As we're not previewing the item let's remove it from the session
            SessionUtil::delVar('newsitem');
        }

        // Confirm authorization code.
        News_Controller_User::checkCsrfToken();

        if (!isset($item['sid']) || empty($item['sid'])) {
            // Create the news story
            $sid = ModUtil::apiFunc('News', 'user', 'create', $item);
            if ($sid != false) {
                // Success
                LogUtil::registerStatus(News_Controller_User::__('Done! Created new article.'));
                // Let any hooks know that we have created a new item
                News_Controller_User::notifyHooks(new Zikula_ProcessHook('news.ui_hooks.articles.process_edit', $sid, new Zikula_ModUrl('News', 'User', 'display', ZLanguage::getLanguageCode(), array('sid' => $sid))));
                News_Controller_User::notify($item); // send notification email
            } else {
                // fail! story not created
                throw new Zikula_Exception_Fatal(News_Controller_User::__('Story not created for unknown reason (Api failure).'));
                return false;
            }
        } else {
            // update the draft
            $result = ModUtil::apiFunc('News', 'admin', 'update', $item);
            if ($result) {
                LogUtil::registerStatus(News_Controller_User::__('Story Updated.'));
            } else {
                // fail! story not updated
                throw new Zikula_Exception_Fatal(News_Controller_User::__('Story not updated for unknown reason (Api failure).'));
                return false;
            }
        }

        // clear article and view caches
        News_Controller_User::clearArticleCaches($item, $this);

        if (isset($files) && $modvars['picupload_enabled']) {
            $resized = News_ImageUtil::resizeImages($sid, $files); // resize and move the uploaded pics
            if (isset($item['tempfiles'])) {
                News_ImageUtil::removePreviewImages($tempfiles); // remove any preview images
            }
            LogUtil::registerStatus(News_Controller_User::_fn('%1$s out of %2$s picture was uploaded and resized.', '%1$s out of %2$s pictures were uploaded and resized.', $item['pictures'], array($resized, $item['pictures'])));
            if (($item['action'] >= self::ACTION_SAVEDRAFT) && ($resized <> $item['pictures'])) {
                LogUtil::registerStatus(News_Controller_User::_fn('Article now has draft status, since the picture was not uploaded.', 'Article now has draft status, since not all pictures were uploaded.', $item['pictures'], array($resized, $item['pictures'])));
            }
        }

        // release pagelock
        if (ModUtil::available('PageLock')) {
            ModUtil::apiFunc('PageLock', 'user', 'releaseLock', array('lockName' => "Newsnews{$item['sid']}"));
        }

        if ($item['action'] == self::ACTION_SAVEDRAFT_RETURN) {
            SessionUtil::setVar('newsitem', $item);
            News_Controller_User::redirect(ModUtil::url('News', 'user', 'newitem'));
        }
        News_Controller_User::redirect(ModUtil::url('News', 'user', 'view'));
    }

?>