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
    <h3>{gt text='Mail Text'}</h3>
</div>

<form class="z-form" action="" method="post" enctype="application/x-www-form-urlencoded">
      <div>
        <input type="hidden" name="csrftoken" value="{insert name="csrftoken"}" />
               <fieldset>
            <legend>{gt text='Mail Text'}</legend>
          
            <div class="z-formrow">
                <label for="additional_mail_text">{gt text='Addditional Mail Text'}</label>
                <textarea id="additional_mail_text" rows="5" name="additional_mail_text">{$modvars.ZSELEX.additional_mail_text}</textarea>
            </div>
            <div class="z-formrow">
                <label for="enabled_mail_text">{gt text='Enable'}</label>
                <input id="enabled_mail_text" type="checkbox" name="enabled_mail_text" value="1" {if $modvars.ZSELEX.enabled_mail_text}checked{/if}>
            </div>
        </fieldset>
        
        </fieldset>  

        <div class="z-buttons z-formbuttons">
            {button src="button_ok.png" set="icons/extrasmall" __alt="Save" __title="Save" __text="Save"}
            <a href="{modurl modname="ZSELEX" type="admin" func='modifyconfig'}" title="{gt text="Cancel"}">{img modname=core src="button_cancel.png" set="icons/extrasmall" __alt="Cancel" __title="Cancel"} {gt text="Cancel"}</a>
        </div>
    </div>
</form>
{adminfooter}
