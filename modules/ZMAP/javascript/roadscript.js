
function validateRoad(){

	var roadname =  document.getElementById('roadname').value;
	var start =  document.getElementById('startgps').value;
	var end =  document.getElementById('endgps').value;
   
	if(roadname==''){
		alert(Zikula.__('Please enter a road name'));
		return false;
	} else if(start==''){
		alert(Zikula.__('Please enter a start point'));
		return false;
	} else if(end==''){
		alert(Zikula.__('Please enter a end point'));
		return false;
	}

	return true;
}



function getRoad(rid){

	//alert(rid); exit();
  
	var n=rid.split("+"); 
	var splitroad = n[0];
     
	var r1= n[1];
	// alert(r1); exit();
    
	var r2 = r1.split("("); 
	document.getElementById('rmve').value = r2[0]; 
    
	// alert(r2[0]); exit();
	var pars = "module=ZMAP&type=ajax&func=getRoad&rid="+splitroad;
	var myAjax = new Ajax.Request(
		"ajax.php",
		{
			method: 'get',
			parameters: pars,
			onComplete:getRoadResponses
		});

}


function getRoadResponses (req)    
{
    
	//alert('ajax works'); exit();
	if (req.status != 200){
		pnshowajaxerror(req.responseText);
		return;
	}
	var json = pndejsonize(req.responseText);
	// alert(json.name);
	// alert(json.gps);
	// alert(req.responseText.length);   exit();
	//alert(req.responseText); exit();
	document.getElementById('ridedit').value = json.rid;
	document.getElementById('roadname').value = json.name;
	document.getElementById('roaddescription').value = json.desc;
	document.getElementById('startgps').value = json.start;
	document.getElementById('endgps').value = json.end;
	document.getElementById('startdescription').value = json.startdesc;
	document.getElementById('enddescription').value = json.enddesc;
}


function setVal(val){
}


function loadroad(rid){
	//alert(rid);
	document.forms["loadroads"].submit();
}
