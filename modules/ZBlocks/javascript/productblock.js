jQuery.noConflict();
//var defwindowajax = new Zikula.UI.Window($('miniShopProducts'), {resizable: true});
jQuery(function () {
    //alert('helloo');
    var selcat = Zikula.__('select category', 'module_zblocks_js');
    var selmanf = Zikula.__('select manufacturer', 'module_zblocks_js');
    jQuery(".mcategory").dropdownchecklist({emptyText: selcat, maxDropHeight: 150, width: 150});
    jQuery(".mmanuf").dropdownchecklist({emptyText: selmanf, maxDropHeight: 150, width: 150});
}); 