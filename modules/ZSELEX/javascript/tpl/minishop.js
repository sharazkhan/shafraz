
//var defwindowajax = new Zikula.UI.Window($('miniShopProducts'), {resizable: true});
//jQuery(function () {
jQuery(document).ready(function () {
    //alert('hiii');
    var selcat = Zikula.__('select category', 'module_zselex_js');
    var selmanf = Zikula.__('select manufacturer', 'module_zselex_js');
    jQuery(".mcategory").dropdownchecklist({emptyText: selcat, maxDropHeight: 150, width: 150});
    jQuery(".mmanuf").dropdownchecklist({emptyText: selmanf, maxDropHeight: 150, width: 150});
    jQuery("img.lazy").lazyload();
});
