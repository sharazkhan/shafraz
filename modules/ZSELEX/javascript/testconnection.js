


function checkConnection(domain , host , dbname , username , password , tableprefix){
    //alert("domain: " + domain + " " + "host: " + host + " " + "db: " + dbname + " " + username + " " + password + " " + tableprefix); exit();
    //alert(hasDate);
    //alert('heloo'); // exit();
    document.getElementById('errordiv').style.display = 'block';
    document.getElementById('message').innerHTML = "Checking Connection...";
   
    
    var pars = "module=ZSELEX&type=ajax&func=checkConnection&domain=" + domain + "&host=" + host + "&dbname=" + dbname + "&username=" + username + "&password=" + password + "&tableprefix=" + tableprefix;
    var myAjax = new Ajax.Request(
        "ajax.php",
        {
            method: 'get',
            parameters: pars,
            async: true,
            onComplete:myFunctioncheckConnectionResponses
        });
   
}

function myFunctioncheckConnectionResponses (req)    
{
    var exist;
    var error;
    if (req.status != 200) 
    {
        
        pnshowajaxerror(req.responseText);
        return;
    }
    //alert('hellooooo'); exit();
    
  
    var json = pndejsonize(req.responseText);
   
    error = json.error;
    exist = json.success;
    //alert("myObject is " + error.toSource()); exit();
    //alert(error);  exit();
    
    // alert(exist);  exit();
   
   
  
    var msg;
    if(exist > 0) {
        msg = "<font color='green'><b>Connection Sucessfull!!!</b></font>";
    //document.getElementById("dotdSpan").style.display= 'none';
    }
    else if(error!=''){
        msg = "<font color='red'>Error Ocurred:</font>&nbsp;"  + "<b>" + error + "</b>";
    //document.getElementById("dotdSpan").style.display= 'block';
    }
    
    document.getElementById('errordiv').style.display = 'block';
    document.getElementById('message').innerHTML = msg;
//alert(req.responseText);  exit();
// $("#blockshopscontent").html(req.responseText);
  
//document.getElementById('blockadcontent').innerHTML = req.responseText;
    
}

