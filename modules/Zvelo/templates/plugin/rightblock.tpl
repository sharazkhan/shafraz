

<script>
    function submitForm(value, url){
    //alert(value);
    //  alert(url); exit();

    jQuery('#mtype').val(value);
            jQuery('#murl').val(url);
            $('myform').submit();
            return false;
    }


    function confirmDel(){
    if (confirm('Delete User?') == true){
    return true
    }
    else{
    return false;
    }
    }
    
    
    function navRedirect(url){
    window.location.href=url;
    }
    
    
    function closePopup(){
     //alert('test');
    jQuery('#Zikula_UI_Window_8').remove();
    return false;
    }


</script>
<div style="float:left;width: 76px;">
<div class="z-buttons z-formbuttons">
    {if $smarty.request.func neq 'values'}
    <a class="tooltips" href="#" class="z-btgreen button_link" onClick="return submitForm('next');"  title="{gt text="Next"}">
       {*{gt text="Next"}*}
       {img modname=core src="1rightarrow.png" set="icons/extrasmall" __alt="Next" __title="Next"} 
    </a>
{/if}
{if $smarty.request.func neq ''}
<br><br>
<a class="tooltips" href="{homepage}" class="z-btgreen button_link" onClick="return submitForm('prev', '{$baseurl}{modurl modname="Zvelo" type="user" func="main"}');"  title="{gt text="Previous"}">
   {img modname=core src="1leftarrow.png" set="icons/extrasmall" __alt="Previous" __title="Previous"} 
   {*{gt text="Prev"}*}
</a>
{/if}
<br><br>
<a class="tooltips" href="#" class="z-btgreen button_link" onClick="return submitForm('save');"  title="{gt text="Save"}">
   {img modname=core src="button_ok.png" set="icons/extrasmall" __alt="Save" __title="Save"} 
   {*{gt text="Save"}*}
</a>
<br><br>
<a id="defwindowajax" class="tooltips" href="{modurl modname="Zvelo" type="user" func="loadUsers"}" class="z-btgreen button_link"   title="{gt text="Load Users"}">
   {img modname=core src="switchuser.png" set="icons/extrasmall" __alt="Load Users" __title="Load Users"} 

</a>
<br><br>
<a class="tooltips" href="#" class="z-btgreen button_link" onClick="return  $('reload').submit();"  title="{gt text="Reload"}">
   {img modname=core src="reload.png" set="icons/extrasmall" __alt="Reload" __title="Reload"} 

</a>
{if $customer_id > 0}
<br><br>
<a class="tooltips" href="{modurl modname="Zvelo" type="user" func="deleteUser" customer_id=$customer_id}" class="z-btgreen button_link" onClick="return confirmDel();"  title="{gt text="Delete User"}">
   {img modname=core src="delete_user.png" set="icons/extrasmall" __alt="Delete User" __title="Delete User"} 

</a>
{/if}


</div>
</div>

<div style="float:left">
    <div class="z-buttons z-formbuttons">
     
    <button {$disable} onClick="navRedirect('{$baseurl}{modurl modname="Zvelo" type="user" func="main"}')"  type='button' name="story[action]" value='2' class="{$bt_class}" title="{gt text='Home'}">
        {gt text='1'}
    </button>
      
       <br><br>
    <button {$disable}  onClick="navRedirect('{$baseurl}{modurl modname="Zvelo" type="user" func="clientinfo"}')"  type='button' name="story[action]" value='2'  class="{$bt_class}" title="{gt text='customer Information'}">
        {gt text='2'}
    </button>
    
      <br><br>
    <button {$disable} onClick="navRedirect('{$baseurl}{modurl modname="Zvelo" type="user" func="bicycle"}')"  type='button' name="story[action]" value='2'  class="{$bt_class}" title="{gt text='Bicycle'}">
        {gt text='3'}
    </button>
      <br><br>
    <button {$disable} onClick="navRedirect('{$baseurl}{modurl modname="Zvelo" type="user" func="seatposition"}')"   type='button' name="story[action]" value='2'  class="{$bt_class}" title="{gt text='Seat Position'}">
        {gt text='4'}
    </button>
      <br><br>
    <button {$disable} onClick="navRedirect('{$baseurl}{modurl modname="Zvelo" type="user" func="wishes"}')"   type='button' name="story[action]" value='2'  class="{$bt_class}" title="{gt text='Customer Wish'}">
        {gt text='5'}
    </button>
      <br><br>
    <button {$disable} onClick="navRedirect('{$baseurl}{modurl modname="Zvelo" type="user" func="values"}')" type='button' name="story[action]" value='2'  class="{$bt_class}" title="{gt text='Ergonomic Value'}">
        {gt text='6'}
    </button>
     
       
    </div>
</div>

<form name="reload" id="reload"  action="{modurl modname="Zvelo" type="user"}" method="post">
<input type="hidden" id="mtype" name="formElement[type]" value="reload">
</form>

<script type="text/javascript">
           Zikula.UI.Tooltips($$('.tooltips'));
           var defwindowajax = new Zikula.UI.Window($('defwindowajax'), {resizable: true , draggable: false});
</script>
