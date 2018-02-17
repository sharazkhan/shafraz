var editing = false;
var draftsaved = false;

/**
 * create the onload function to enable the respective functions
 *
 */
Event.observe(window, 'load', zselex_init_check);

function zselex_init_check() 
{
    if ($('zselex_loadzselex')) {
        Element.hide('zselex_loadzselex');
    }
    // if Javascript is on remove regular links
    if ($('zselex_editlinks') && $('zselex_editlinks_ajax')) {
        Element.remove('zselex_editlinks');
    }
    if ($('zselex_editlinks_ajax')) {
        Element.removeClassName($('zselex_editlinks_ajax'), 'hidelink'); 
    }
    if ($('zselex_button_savedraft_nonajax')) {
        Element.remove('zselex_button_savedraft_nonajax');
    }
    if ($('zselex_button_savedraft_ajax')) {
        Element.removeClassName($('zselex_button_savedraft_ajax'), 'hidelink'); 
    }
    if ($('zselex_status_info')) {
        zselex_title_init();
    }
    if ($('zselex_meta_collapse')) {
        zselex_meta_init();
    }
    if ($('zselex_notes_collapse')) {
        zselex_notes_init();
    }
    if ($('zselex_expiration_details')) {
        zselex_expiration_init();
    }
    if ($('zselex_publication_collapse')) {
        zselex_publication_init();
    }
    if ($('zselex_attributes_collapse')) {
        zselex_attributes_init();
    }
    if ($('zselex_button_text_draft')) {
        zselex_isdraft();
    }
    // activate the title field for a new article
    if ($('zselex_title') && $F('zselex_title') == '') {
        $('zselex_title').focus();
    }
}


/**
 * Start the editing/updating process by calling the appropriate Ajax function
 *
 *@params sid    the story id;
 *@params page   the page id;
 *@return none;
 *@author Frank Schummertz
 */
function editzselex(sid, page)
{
    if(editing==false) {
        Element.show('zselex_loadzselex');
        var pars = {
            sid: sid,
            page: page
        }
        new Zikula.Ajax.Request(
            "ajax.php?module=Zselex&func=modify",
            {
                parameters: pars,
                onComplete: editzselex_init
            });
    }
}

/**
 * This functions gets called when the Ajax request initiated in editzselex() returns. 
 * It hides the zselex story and shows the modify html as defined in zselex_ajax_modify.htm
 *
 *@params req   response from Ajax request;
 *@return none;
 *@author Frank Schummertz
 */
function editzselex_init(req) 
{
    Element.hide('zselex_loadzselex');
    if(!req.isSuccess()) {
        Zikula.showajaxerror(req.getMessage());
        return;
    }
    var data = req.getData();
    editing = true;
    // Fill the zselex_modify div with rendered template zselex_ajax_modify.htm
    Element.update('zselex_modify', data.result);
    Element.hide('zselex_savezselex');
    Element.hide('zselex_articlecontent');
    sizecheckinit();
    zselex_init_check();
    
    // Manual start of the Xinha editor
    if (typeof Xinha != "undefined" && typeof xinha_editorsarray != "undefined") {
        editors = Xinha.makeEditors(xinha_editorsarray, xinha_config, xinha_plugins);
        Xinha.startEditors(editors);
    }

    // enable attribute UI
    var list_zselexattributes = null;
    list_zselexattributes = new Zikula.itemlist('zselexattributes', {headerpresent: true, firstidiszero: true});
    $('appendattribute').observe('click',function(event){
        list_zselexattributes.appenditem();
        event.stop();
    });

    // add warning to page about file ops
    $('label_for_zselex_files_element').insert({
        before: "<p class='z-warningmsg'>"+Zikula.__("Picture/file operations not supported in 'Quick edit' mode. You must click the 'Save full edit' button to complete these operations.")+"</div>"
    });

    return;
}

/**
 * Cancel the edit process: Remove the modify html and re-enable the original story
 *
 *@params none;
 *@return none;
 *@author Frank Schummertz
 */
function editzselex_cancel()
{
    Element.update('zselex_modify', '&nbsp;');
    Element.show('zselex_articlecontent');
    editing = false;
    return;
}

/**
 * Send the story information via Ajax request to the server for storing in the database
 *
 *@params none;
 *@return none;
 *@author Frank Schummertz
 */
function editzselex_save(action)
{
    if (editing == true) {
        editing = false;
        Element.show('zselex_savezselex');

        // A manual onsubmit for xinha to update the textarea data again.
        if (typeof Xinha != "undefined") {
            $('zselex_ajax_modifyform').onsubmit();
        }
        var pars = $('zselex_ajax_modifyform').serialize(true);
        pars.action = action;
        new Zikula.Ajax.Request(
            'ajax.php?module=Zselex&func=update',
            {
                parameters: pars,
                onComplete: editzselex_saveresponse
            });
    }
    return;
}

/**
 * This functions gets called then the Ajax request in editzselex_save() returns.
 * It removes the update html and the article html as well. The new article content
 * (the pnRendered zselex_user_articlecontent.htm) gets returned as part of the JSON result.
 * Depending on the action performed it *might* initiate a page reload! This is necessary
 * when the story has been deleted or set to pending state which means the sid in the url
 * is no longer valid.
 *
 *@params req   response from Ajax request;
 *@return none;
 *@author Frank Schummertz
 */
function editzselex_saveresponse(req)
{
    Element.hide('zselex_savezselex');
    editing = false;

    if(!req.isSuccess()) {
        Zikula.showajaxerror(req.getMessage());
        return;
    }
    var data = req.getData();

    Element.update('zselex_modify', '&nbsp;');
    Element.update('zselex_articlecontent', data.result);
    if ($('zselex_editlinks_ajax')) {
        Element.hide('zselex_loadzselex');
        Element.remove('zselex_editlinks');
        Element.removeClassName($('zselex_editlinks_ajax'), 'hidelink'); 
    } 
    Element.show('zselex_articlecontent');
    switch(data.action) {
        case 'update':
            // reload if necessary (e.g. urltitle change)
            if (data.reloadurl != '') {
                location.replace(data.reloadurl);
            }
            break;
        case 'delete':
        case 'pending':
            // redirect to the zselex index
            location.replace(data.reloadurl);
            break;
        default:
    }

    return;
}


/* Taken from Dizkus edittopicsubject TOBEDONE !
function permalinkedit(topicid)
{
    if(subjectstatus == false) {
        subjectstatus = true;
        var pars = "module=Dizkus&func=edittopicsubject&topic=" + topicid;
        Ajax.Responders.register(dzk_globalhandlers);
        var myAjax = new Ajax.Request(
            document.location.pnbaseURL+"ajax.php",
            {
                method: 'post',
                parameters: pars,
                onComplete: permalinkeditinit
            });
    }
}

function permalinkeditinit(originalRequest)
{
    // show error if necessary
    if( originalRequest.status != 200 ) {
        dzk_showajaxerror(originalRequest.responseText);
        subjectstatus = false;
        return;
    }

    var result = dejsonize(originalRequest.responseText);

    var topicsubjectID = 'topicsubject_' + result.topic_id;

    Element.hide(topicsubjectID);
    updateAuthid(result.authid);

    new Insertion.After($(topicsubjectID), result.data);
}

function permalinkeditcancel(topicid)
{
    var topicsubjectID = 'topicsubject_' + topicid;

    Element.remove(topicsubjectID + '_editor');
    Element.show(topicsubjectID);
    subjectstatus = false;
}

function permalinkeditsave(topicid)
{
    var topicsubjectID = 'topicsubject_' + topicid;
    var editID = topicsubjectID + '_edit';
    var authID = topicsubjectID + '_authid';
    if($F(editID) == '') {
        // no text
        return;
    }

    var pars = "module=Dizkus&func=updatetopicsubject" +
               "&topic=" + topicid +
               "&subject=" + encodeURIComponent($F(editID)) +
               "&authid=" + $F(authID);
    Ajax.Responders.register(dzk_globalhandlers);
    var myAjax = new Ajax.Request(
                    document.location.pnbaseURL+"ajax.php",
                    {
                        method: 'post',
                        parameters: pars,
                        onComplete: permalinkeditsave_response
                    }
                    );

}

function permalinkeditsave_response(originalRequest)
{
    // show error if necessary
    if( originalRequest.status != 200 ) {
        dzk_showajaxerror(originalRequest.responseText);
        subjectstatus = false;
        return;
    }

    var result = dejsonize(originalRequest.responseText);
    var topicsubjectID = 'topicsubject_' + result.topic_id;

    Element.remove(topicsubjectID + '_editor');
    updateAuthid(result.authid);

    Element.update(topicsubjectID + '_content', result.topic_title);
    Element.show(topicsubjectID);

    subjectstatus = false;
}
*/


/**
 * Start the saving draft process by calling the appropriate Ajax function
 *
 *@return none;
 *@author Erik Spaan
 */
function savedraft()
{
    // Re-fill the original textareas if Scribite Xinha is used, manual onsubmit needed
    if (typeof Xinha != "undefined") {
        $('zselex_user_newform').onsubmit();
    }
    var pars = $('zselex_user_newform').serialize(true);

    $('zselex_status_info').show();
    $('zselex_saving_draft').show();
    $('zselex_status_text').update(Zikula.__('Saving quick draft...','module_Zselex'));
    $('zselex_button_text_draft').update(Zikula.__('Saving quick draft...','module_Zselex'));
    new Zikula.Ajax.Request(
        'ajax.php?module=zselex&func=savedraft',
        {
            parameters: pars, 
            onComplete: savedraft_update
        });
}

function savedraft_update(req)
{
    $('zselex_saving_draft').hide();
    if(!req.isSuccess()) {
        $('zselex_button_text_draft').update(Zikula.__('Save quick draft','module_Zselex'));
        $('zselex_status_text').update(Zikula.__('Save quick draft failed','module_Zselex'));
        Zikula.showajaxerror(req.getMessage());
        return;
    }
    var data = req.getData();

    draftsaved = true;
    $('zselex_button_text_draft').update(Zikula.__('Update quick draft','module_Zselex'));
    $('zselex_status_text').update(data.result);
    $('zselex_urltitle').value = data.slug;
    $('zselex_sid').value = data.sid;

    // add warning to page about file ops
    showfilesaddedwarning();

    return;
}

function showfilesaddedwarning()
{
    var filecount=$$('.negative').size();
    if (filecount>0) {
        $('zselex_button_fulldraft').addClassName('z-btgreen');
        $('zselex_picture_warning').show();
        $('zselex_picture_warning_text').update(Zikula.__("Picture/file operations not supported in 'Quick draft' mode. You must click the 'Save full draft' button to complete these operations.",'module_Zselex'));
    }
}

function zselex_isdraft()
{
    var sid = $F('zselex_sid');
    if (sid != '') {
        draftsaved = true;
        $('zselex_button_text_draft').update(Zikula.__('Update quick draft','module_Zselex'));
    }
    return;
}


/**
 * Admin panel functions
 * Functions to enable watching for changes in  the optional divs and show and hide these divs 
 * with the switchdisplaystate funtion of javascript/ajax/pnajax.js. This function uses BlindDown and BlindUp
 * when scriptaculous Effects is loaded and otherwise show and hide of prototype.
 */
function zselex_title_init()
{
//    Event.observe('zselex_title', 'change', savedraft);
//    $('zselex_urltitle_details').hide();
    $('zselex_status_info').hide();
    $('zselex_picture_warning').hide();
  
    // not the correct location but for reference later on:
    //new PeriodicalExecuter(savedraft, 30);
}

function zselex_expiration_init()
{
    if ($('zselex_tonolimit').checked == true) 
        $('zselex_expiration_date').hide();
    if ($('zselex_unlimited').checked == true) {
        $('zselex_expiration_details').hide();
    } else if ($('zselex_button_text_publish')) {
        $('zselex_button_text_publish').update(Zikula.__('Schedule','module_Zselex'));
    }
    $('zselex_unlimited').observe('click', zselex_unlimited_onchange);
    $('zselex_tonolimit').observe('click', zselex_tonolimit_onchange);
}

function zselex_unlimited_onchange()
{
    switchdisplaystate('zselex_expiration_details');
    if ($('zselex_button_text_publish') && $('zselex_expiration_details').style.display != "none") {
        $('zselex_button_text_publish').update(Zikula.__('Publish','module_Zselex'));
    } else {
        $('zselex_button_text_publish').update(Zikula.__('Schedule','module_Zselex'));
    }
}

function zselex_tonolimit_onchange()
{
    switchdisplaystate('zselex_expiration_date');
}


function zselex_publication_init()
{
    $('zselex_publication_collapse').observe('click', zselex_publication_click);
    $('zselex_publication_collapse').addClassName('z-toggle-link');
    // show the publication details when a variable is not set to default
    if ($('zselex_unlimited').checked == true && $('zselex_displayonindex').checked == true && $('zselex_allowcomments').checked == true) {
        $('zselex_publication_collapse').removeClassName('z-toggle-link-open');
        $('zselex_publication_showhide').update(Zikula.__('Show','module_Zselex'));
        $('zselex_publication_details').hide();
    } else {
        $('zselex_publication_collapse').addClassName('z-toggle-link-open');
        $('zselex_publication_showhide').update(Zikula.__('Hide','module_Zselex'));
    }
    if ($('zselex_button_text_publish') && $('zselex_unlimited').checked == false) {
        $('zselex_button_text_publish').update(Zikula.__('Schedule','module_Zselex'));
    }
}

function zselex_publication_click()
{
    if ($('zselex_publication_details').style.display != "none") {
        Element.removeClassName.delay(0.9, $('zselex_publication_collapse'), 'z-toggle-link-open');
        $('zselex_publication_showhide').update(Zikula.__('Show','module_Zselex'));
    } else {
        $('zselex_publication_collapse').addClassName('z-toggle-link-open');
        $('zselex_publication_showhide').update(Zikula.__('Hide','module_Zselex'));
    }
    switchdisplaystate('zselex_publication_details');
}


function zselex_attributes_init()
{
    $('zselex_attributes_collapse').observe('click', zselex_attributes_click);
    $('zselex_attributes_collapse').addClassName('z-toggle-link');
    // show attributes if they already exist
    if ($F('attributecount') > 0) {
        $('zselex_attributes_collapse').addClassName('z-toggle-link-open');
        $('zselex_attributes_showhide').update(Zikula.__('Hide','module_Zselex'));
    } else {
        $('zselex_attributes_collapse').removeClassName('z-toggle-link-open');
        $('zselex_attributes_showhide').update(Zikula.__('Show','module_Zselex'));
        $('zselex_attributes_details').hide();
    }
}

function zselex_attributes_click()
{
    if ($('zselex_attributes_details').style.display != "none") {
        Element.removeClassName.delay(0.9, $('zselex_attributes_collapse'), 'z-toggle-link-open');
        $('zselex_attributes_showhide').update(Zikula.__('Show','module_Zselex'));
    } else {
        $('zselex_attributes_collapse').addClassName('z-toggle-link-open');
        $('zselex_attributes_showhide').update(Zikula.__('Hide','module_Zselex'));
    }
    switchdisplaystate('zselex_attributes_details');
}

function zselex_notes_init()
{
    $('zselex_notes_collapse').observe('click', zselex_notes_click);
    $('zselex_notes_collapse').addClassName('z-toggle-link');
    if ($('zselex_notes_details').style.display != "none") {
        $('zselex_notes_collapse').removeClassName('z-toggle-link-open');
        $('zselex_notes_showhide').update(Zikula.__('Show','module_Zselex'));
        $('zselex_notes_details').hide();
    }
}

function zselex_notes_click()
{
    if ($('zselex_notes_details').style.display != "none") {
        Element.removeClassName.delay(0.9, $('zselex_notes_collapse'), 'z-toggle-link-open');
        $('zselex_notes_showhide').update(Zikula.__('Show','module_Zselex'));
    } else {
        $('zselex_notes_collapse').addClassName('z-toggle-link-open');
        $('zselex_notes_showhide').update(Zikula.__('Hide','module_Zselex'));
    }
    switchdisplaystate('zselex_notes_details');
}


function zselex_meta_init()
{
    $('zselex_meta_collapse').observe('click', zselex_meta_click);
    $('zselex_meta_collapse').addClassName('z-toggle-link');
    if ($('zselex_meta_details').style.display != "none") {
        $('zselex_meta_collapse').removeClassName('z-toggle-link-open');
        $('zselex_meta_details').hide();
    }
}

function zselex_meta_click()
{
    if ($('zselex_meta_details').style.display != "none") {
        Element.removeClassName.delay(0.9, $('zselex_meta_collapse'), 'z-toggle-link-open');
    } else {
        $('zselex_meta_collapse').addClassName('z-toggle-link-open');
    }
    switchdisplaystate('zselex_meta_details');
}

function executeuserselectform(data)
{
    if(data) {
        var pars = {
            uid: data.userselector,
            sid: $F('zselex_sid'),
            dest: data.destination
        }
        new Zikula.Ajax.Request(
            "ajax.php?module=Zselex&func=updateauthor",
            {
                parameters: pars,
                onComplete: executeuserselectform_response
            });
    }
}

function executeuserselectform_response(req)
{
    if (!req.isSuccess()) {
        showinfo();
        Zikula.showajaxerror(req.getMessage());
        return;
    }
    var data = req.getData();
    if (data) {
        $('zselex_cr_uid_edit').hide();
        $('zselex_cr_uid').setValue(data.uid);
        $('zselex_contributor').update(data.cont); // not a form element
        $('zselex_cr_uid_edit').insert({after: ' ' + Zikula.__('Author updated')}).insert({after: new Element('img', {src: 'images/icons/extrasmall/button_ok.png'})});
        if (data.dest == 'list') {
            $('zselex_user_modifyform').insert({bottom: new Element('input', {type: 'hidden', name: 'story[action]', value: '2'})});
            $('zselex_user_modifyform').submit();
        }
    }
}