
{pageaddvar name='javascript' value='system/Blocks/javascript/checkconnection.js'}

<div class="z-formrow">
	<label for="amount">{gt text="No: of products to display"}</label>
	<input id="amount" type="text"   value="{$vars.amount}" name="amount" />
</div>

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
	<label for="orderby">{gt text="Order by"}</label>
	 <select name='orderby' id='orderby'>
         <option value='random' {if $vars.orderby eq 'random'} selected="selected" {/if}>{gt text='Random'}</option>
         <option value='new' {if $vars.orderby eq 'new'} selected="selected" {/if}>{gt text='New'}</option>
          </select>
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