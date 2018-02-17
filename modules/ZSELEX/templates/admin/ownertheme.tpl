
{adminheader}

{pageaddvar name='javascript' value='jquery'}
<link href="modules/ZSELEX/style/combo/sexy-combo.css" rel="stylesheet" type="text/css" />
<link href="modules/ZSELEX/style/combo/sexy/sexy.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="modules/ZSELEX/javascript/combo/jquery.sexy-combo.js"></script>

       <style>
        #ajax-container input[type="text"],#ajax-container textarea {
         padding: 0.09em;
         }

          #ajax-container ul {
              margin:0px;
          }
        </style>
         
        

<div class="z-admin-content-pagetitle">
    {icon type="view" size="small"}
    <h3>{gt text='Set Theme'}</h3>
</div>

<form class="z-form" id="zselex_bulkaction_form" action="{modurl modname="ZSELEX" type="admin" func="ownertheme"}" method="post">
    <div style="overflow:auto;">
        <input type="hidden" name="csrftoken" value="{insert name='csrftoken'}" />
       <table id="zselex_admintable" class="z-datatable">
            <thead>
                <tr>
                     <th>{gt text='Theme'}</th>
                     <th>{gt text='Owners'}</th>
                   
                </tr>
            </thead>
            <tbody>
                {foreach from=$themes item='theme' key='k'}
                <script type="text/javascript" >
                    //jQuery.noConflict();
                    jQuery(function () {

                        //alert('hii');
                        jQuery("#theme-combo{/literal}{$k}{literal}").ZselexCombo({
                        emptyText: Zikula.__("Select Theme...")
                        //autoFill: true
                        //triggerSelected: true
                        });

                    }); 

                </script>
                    
                <tr class="{cycle values='z-odd,z-even'}">
                 
                    <td>
                        {$theme.name|safetext}
                     </td>
                    <td>
                        {*
                       <select id="country-combo" id="{$theme.id}" name="shopsowner[{$theme.id}+{$theme.name}]" size="1">
                        <option value=''>{gt text='Select'}</option>
                        {foreach from=$shopowners  item='owner'}
                        <option value="{$owner.user_id}+{$owner.uname}" >{$owner.uname|upper}</option>
                        {/foreach}
                        *}
                    <div id="ajax-container" >
                        <select id="theme-combo{$k}" name="shopsowner[{$theme.id}+{$theme.name}]" size="1">
                            <option value=''>{gt text='Select'}</option>
                            {foreach from=$shopowners  item='owner'}
                            <option value="{$owner.user_id}+{$owner.uname}" >{$owner.uname|upper}</option>
                            {/foreach}
                        </select>
                    </div>
                    </td>
                 
                    
                </tr>
                {foreachelse}
                <tr class="z-datatableempty"><td colspan="10">{gt text='No Admins.'}</td></tr>
                {/foreach}
               
                
                <tr>
                    <td colspan="3">
                 <div class="z-buttons z-formbuttons">
                     {if !empty($themes)}
        {button src="button_ok.png" set="icons/extrasmall" __alt="Save" __title="Save" __text="Save" __name="typetable[action]" __value="save"}
                      {/if}
        <a href="{modurl modname="ZSELEX" type="admin" func='viewownertheme'}" title="{gt text="Cancel"}">{img modname=core src="button_cancel.png" set="icons/extrasmall" __alt="Cancel" __title="Cancel"} {gt text="Cancel"}</a>
                 </div>
                </td>
                
                </tr>
              
            </tbody>
        </table>
      
    </div>
</form>


{adminfooter}
