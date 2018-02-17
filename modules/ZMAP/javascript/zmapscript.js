function zmapconfirm(msg) {
    if(confirm(msg)==true)
		return true;
	else
		return false;
}

function zmapPconfirm(func){
	var proj = document.getElementById('projectname').value;
	switch (func) {
		case 'deleteProject': var msg = Zikula.__f('Do you want to delete this project (%s) ?',proj,'module_ZMAP');
		break;
		case 'saveLoadedProject': var msg = Zikula.__f('Do you want to save changes to this project (%s) ?',proj,'module_ZMAP');
		break;
		case 'saveNewProject': var msg = Zikula.__f('Do you want to create new project (%s) ?',proj,'module_ZMAP');
		break;
		case 'deleteRoad': var msg = Zikula.__f('Do you want to delete this road (%s) ?',proj,'module_ZMAP');
		break;
		default: var msg = Zikula.__f('Unknown function call (%1$s) on project (%2$s)',[func,proj],'module_ZMAP');
	}
//	var msga = new Array();
//	msga["deleteProject"] = Zikula.__f('Do you want to delete this project (%s) ?',proj,'module_ZMAP');
//	msga["saveLoadedProject"] = Zikula.__f('Do you want to save this project (%s) ?',proj,'module_ZMAP');
//	msga["saveNewProject"] = Zikula.__f('Do you want to create new project (%s) ?',proj,'module_ZMAP');
//	var msg = msga[func];
	return zmapconfirm(msg);
}
        

function zmapRconfirm(func){
	var road = document.getElementById('roadname').value;
	switch (func) {
		case 'deleteRoad': var msg = Zikula.__f('Do you want to delete this road (%s) ?',road,'module_ZMAP');
		break;
		default: var msg = Zikula.__f('Unknown function call (%1$s) on road (%2$s)',[func,road],'module_ZMAP');
	}

	return zmapconfirm(msg);
}
        

function getGps(ID){
    
  //alert(ID);  exit();
   
    //alert("city: "+cityId); exit();
    var pars = "module=ZMAP&type=ajax&func=getGps&id="+ID;
    var myAjax = new Ajax.Request(
        "ajax.php",
        {
            method: 'get',
            parameters: pars,
            onComplete:getGpsResponses
        });
   
}

function getGpsResponses (req)    
{
    
   // alert('ajax works'); exit();
    if (req.status != 200) 
    {
        
        pnshowajaxerror(req.responseText);
        return;
    }
     var json = pndejsonize(req.responseText);
      // alert(json.id);
     // alert(json.gps);
    // alert(req.responseText.length);   exit();
     //alert(req.responseText); exit();
   document.getElementById(json.id).value = json.gps;
    
}

