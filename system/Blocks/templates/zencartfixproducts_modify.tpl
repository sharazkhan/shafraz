{pageaddvar name='javascript' value='system/Blocks/javascript/checkconnection.js'}

<div class="z-formrow">
	<label for="amount">{gt text="Shop Name"}</label>
	<input id="shop" type="text"   value="{$vars.shop}" name="shop" />
</div>

<div class="z-formrow">
	<label for="domain">{gt text="Domain"}</label>
	<input id="domain" type="text"   value="{$vars.domain}" name="domain" />
</div>



<div class="z-formrow">
	<label for="host">{gt text="Host"}</label>
	<input id="host" type="text"   value="{$vars.host}" name="host" />
</div>


<div class="z-formrow">
	<label for="database">{gt text="Database"}</label>
	<input id="database" type="text"   value="{$vars.database}" name="database" />
</div>


<div class="z-formrow">
	<label for="username">{gt text="User Name"}</label>
	<input id="username" type="text"   value="{$vars.username}" name="username" />
</div>


<div class="z-formrow">
	<label for="password">{gt text="Password"}</label>
	<input id="password" type="text"   value="{$vars.password}" name="password" />
</div>


<div class="z-formrow">
	<label for="tableprefix">{gt text="Table Prefix"}</label>
	<input id="tableprefix" type="text"   value="{$vars.tableprefix}" name="tableprefix" />
</div>


<div class="z-formrow">
	<label for="fixedproducts">{gt text="Product ID(s) (comma seperated)"}</label>
	<input id="fixedproducts" type="text"   value="{$vars.fixedproducts}" name="fixedproducts" />
</div>

<div class="z-buttons z-formbuttons">
	<label for="testconnection"></label>
       {* <span id="testconnection" style="cursor:pointer;color:blue;" onClick='checkConnection(document.getElementById("host").value , document.getElementById("database").value , document.getElementById("username").value , document.getElementById("password").value , document.getElementById("tableprefix").value)'><b>Test Configurations</b></span>*}
        <input type="button" name="Test Configurations" value="Test Configurations" onClick='checkConnection(document.getElementById("domain").value , document.getElementById("host").value , document.getElementById("database").value , document.getElementById("username").value , document.getElementById("password").value , document.getElementById("tableprefix").value)'>
       
</div>
        
<div align="center" id="errordiv" style="display: block"  width="40px"> 
        <label for="message"></label>
        <div id="message"> </div>
</div>
