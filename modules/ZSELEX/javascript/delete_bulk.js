/**
 * create the onload function to enable the respective functions
 *
 */
Event.observe(window, 'load', news_admin_init_check);
var formID;
function news_admin_init_check() 
{
    formID = jQuery('#formID').val();
    if ($('news_select_all') && $('news_deselect_all')) {
       
        news_admin_selectall_init(); 
    }
    if ($('news_bulkaction_select')) {
        news_admin_bulkaction_init();
    }
}

Zikula.toggleInputs=function(a,c){
    var d=c==null?function(e){
        return !e
    }:function(e){
        return c
    },b=$(a)?$(a).select("input[type=checkbox]"):$$(a);
    if(b){
        b.each(function(f){
            f.checked=d(f.checked)
        })
    }
};

// Initialize and process the (de)select all functions
function news_admin_selectall_init()
{
    
    // alert(formID);
    $('news_select_all').observe('click', function(e){
        Zikula.toggleInputs(formID, "checkbox");
    //checkedAll();
    // e.stop();
    });
    $('news_deselect_all').observe('click', function(e){
        Zikula.toggleInputs(formID, false);
        e.stop();
    });
}

// Initialize and process bulkactions on selected articles
function news_admin_bulkaction_init()
{
    $('news_bulkaction_select').observe('change', function(event){
        var values=$$('input:checked[type=checkbox][name=news_selected_articles\[\]]').pluck('value');
        values.sort(function(a,b){
            return a - b
        });
        var valuescount=values.length;
        var action=$F('news_bulkaction_select');
        var actionmap=new Array(6);
        actionmap[0]=null;
        actionmap[1]=Zikula.__('delete','module_zselex_js');
        actionmap[2]=Zikula.__('reactivate demo','module_zselex_js');
        actionmap[3]=Zikula.__('publish','module_zselex_js');
        actionmap[4]=Zikula.__('reject','module_zselex_js');
        actionmap[5]=Zikula.__('change categories for','module_zselex_js');
        var actionword=actionmap[action];
        if (actionword=='delete') {
            var deletemessage='<br /><br /><strong class="z-warningmsg">'+Zikula.__('You cannot undo this operation!','module_zselex_js')+'</strong>';
        } else {
            var deletemessage='';
        }
        if ((action>0) && (valuescount>0)) {
            var options = {
                modal:true,
                draggable:false
            };
            executeform = function(data){
                if(data) {
                    $('news_bulkaction_categorydata').setValue(Object.toJSON(data));
                    $(formID).submit();
                } else {
                    // action cancelled
                    $('news_bulkaction_select').selectedIndex=0;
                }
            }
            if (action!=5) {
                // standard bulk actions
                var conf=Zikula.UI.Confirm(
                    Zikula._fn('Are you sure you want to %s the following items',
                        'Are you sure you want to %s the selected items',
                        valuescount,
                        ['<strong>'+actionword+'</strong>'],
                        'module_News')+'? '/*+values+deletemessage*/,
                    Zikula.__('Confirm Bulk Action','module_zselex_js'),
                    executeform,
                    options
                    );
            } else {
                // change categories
                var formdialog = new Zikula.UI.FormDialog(
                    $('news_changeCategoriesForm'),
                    executeform,
                    options
                    );
                formdialog.open();
            }
        } else {
            // no articles selected
            $('news_bulkaction_select').selectedIndex=0;
            Zikula.UI.Alert(
                Zikula.__f('Please select at least one service to %s.',actionword,'module_zselex_js'),
                Zikula.__('Bulk action error','module_zselex_js')
                );
        }
    });
}