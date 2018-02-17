{ajaxheader ui=true}
{ajaxheader imageviewer="true"}
{pageaddvarblock}
<script type="text/javascript">
    document.observe("dom:loaded", function() {
        Zikula.UI.Tooltips($$('.tooltips'));
    });
</script>
{/pageaddvarblock}
{adminheader}
<div class="z-admin-content-pagetitle">
    {icon type="config" size="small"}
    <h3>{gt text='Payment settings'}</h3>
</div>

<form class="z-form" action="{modurl modname="ZSELEX" type="admin" func="paymentgatewaysettings"}" method="post" enctype="application/x-www-form-urlencoded">
    <div>
    <input type="hidden" name="csrftoken" value="{insert name="csrftoken"}" />
   
       <fieldset>
        <legend>{gt text='Paypal Settings'}</legend>
        <div class="z-formrow">
			<label for="paypalzselexemail">{gt text='Paypal Email'}</label>
			<input type="text" value="{$modvars.ZSELEX.paypalzselexemail}" id="paypalzselexemail" name="paypalzselexemail"/>
        </div>
      </fieldset>
        
    
    <div class="z-buttons z-formbuttons">
        {button src="button_ok.png" set="icons/extrasmall" __alt="Save" __title="Save" __text="Save"}
        <a href="{modurl modname="ZSELEX" type="admin" func='viewshop'}" title="{gt text="Cancel"}">{img modname=core src="button_cancel.png" set="icons/extrasmall" __alt="Cancel" __title="Cancel"} {gt text="Cancel"}</a>
    </div>
    </div>
</form>
{adminfooter}


  