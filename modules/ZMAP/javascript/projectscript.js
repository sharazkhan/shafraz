
function onProjectChange(projid){
//alert(projid);

//	var burl = "{/literal}{$baseurl}{literal}";

//alert(burl);

	if(projid!=''){
		window.location.href="/zmap/map/projectId/"+projid;
	} else{
		window.location.href="/zmap/map";     
	}
}
    
    
    
function validateSaveProject111(){
     // alert('hiii');
	if(confirm(Zikula.__('Do you want to save this project?'))==true){
		var projectId = "{/literal}{$smarty.request.projectId}{literal}";
		if(projectId==''){
			alert(Zikula.__('You have not selected a project to save'));
			return false;
		} else{
		//return true;
	  		document.getElementById('savemsg').innerHTML = '<h2>'+Zikula.__('Please Wait...... Saving.....')+'</h2>';
	  		window.setTimeout("doSaveProject();", 5000);
		}
	} else {
		return false;
	}

}
        
function doSaveProject(){
	// alert('hiii');
	document.forms["saveproject"].submit();
	//return true; 
}
                
                
                
function validateSaveProject(){

	var projectId = "{/literal}{$smarty.request.projectId}{literal}";
//alert("projectId="+projectId);
    if(projectId==''){
		alert(Zikula.__('You have not selected a project to save'));
    	return false;
	} else {
		return true;     
	}
}               
     
function validateSaveNewProject(){
	var projectName =  document.getElementById('projectname').value;	
	var projectNameDb =  document.getElementById('projectnameDB').value;

	if(projectName == projectNameDb){
		alert(Zikula.__('Please enter a new project name'));
		return false;
	} else {
		return true;   
	}
}   
        

function copyProjectWords(val){
	// alert(id);
	// document.getElementById(id).value = val;

	document.getElementById('hprojectname').value = val;
	document.getElementById('aprojectname').value = val;
}
                
function copyProjectText(val){
// alert(id+' = '+val);
// document.getElementById(id).value = val;

	document.getElementById('hprojectdescription').value = val;
	document.getElementById('aprojectdescription').value = val;
}
