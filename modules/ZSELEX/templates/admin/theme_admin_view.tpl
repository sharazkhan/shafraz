{ajaxheader ui=true}
{pageaddvarblock}
<script type="text/javascript">
    document.observe("dom:loaded", function() {
        Zikula.UI.Tooltips($$('.tooltips'));
    });
</script>
{/pageaddvarblock}

{gt text="Extension database" assign=extdbtitle}
{assign value="<strong><a href=\"http://community.zikula.org/module-Extensions.htm\">`$extdbtitle`</a></strong>" var=extdblink}

{shopheader}
<div class="z-admin-content-pagetitle">
    {icon type="view" size="small"}
    <h3>{gt text="Themes list"}</h3>
</div>

<div id="themes-alphafilter" style="padding:0 0 1em;"><strong>[{pagerabcshoptheme posvar="startlet" forwardvars='' shop_id=$smarty.request.shop_id}]</strong></div>
<form class="z-form" id="country_filter" action="{modurl modname='ZSELEX' type='admin' func='configureshoptheme' shop_id=$smarty.request.shop_id startlet=$startlet}" method="post" enctype="application/x-www-form-urlencoded">
    <fieldset id="zselex_multicategory_filter"{if $filter_active} class='filteractive'{/if}>
        {if $filter_active}{gt text='active' assign=filteractive}{elseif $filter_active neq ''}{gt text='inactive' assign=filteractive}{else $filter_active eq ''}{gt text='All' assign=filteractive}{/if}
       
        <label for="searchtext">{gt text='No Of Pages'}</label>
        <select name="no_of_pages">
            <option value=''>{gt text='Select'}</option>
           
            <option value='5' {if $no_of_pages eq '5'}selected=selected{/if}>5</option>
            <option value='10' {if $no_of_pages eq '10'}selected=selected{/if}>10</option>
            <option value='15' {if $no_of_pages eq '15'}selected=selected{/if}>15</option>
            <option value='25' {if $no_of_pages eq '25'}selected=selected{/if}>25</option>
            <option value='50' {if $no_of_pages eq '50'}selected=selected{/if}>50</option>
         
        </select>
       
        &nbsp;
        &nbsp;&nbsp;
        <span class="z-nowrap z-buttons">
            <input class='z-bt-filter' name="" type="submit" value="{gt text='Filter'}" />
            <a href="{modurl modname="ZSELEX" type='admin' func='configureshoptheme' shop_id=$smarty.request.shop_id}" title="{gt text="Reset"}">{img modname=core src="button_cancel.png" set="icons/extrasmall" __alt="Reset" __title="Reset"} {gt text="Reset"}</a>
        </span>
    </fieldset>
</form>
<table class="z-datatable">
    <thead>
        <tr>
             {if !$expired AND !$servicedisable}
            <th class="z-right">{gt text="Actions"}</th>
             {/if}
            <th>{gt text="Name"}</th>
            <th>{gt text="Description"}</th>
        </tr>
    </thead>
    <tbody>
        {foreach from=$themes item=theme}
        {homepage assign='homepageurl'}
        {if $modvars.ZConfig.shorturls eq 1 && $modvars.ZConfig.shorturlsstripentrypoint neq 1}
        {assign var='themeurl' value="`$homepageurl`/`$theme.name`"}
        {elseif $modvars.ZConfig.shorturls eq 1 && $modvars.ZConfig.shorturlsstripentrypoint eq 1}
        {assign var='themeurl' value="`$homepageurl``$theme.name`"}
        {else}
        {if $homepageurl|strstr:"?"}
        {assign var='themeurl' value="`$homepageurl`&theme=`$theme.name`"}
        {else}
        {assign var='themeurl' value="`$homepageurl`?theme=`$theme.name`"}
        {/if}
        {/if}
        <tr class="{cycle values="z-odd,z-even}{if $theme.name|strtolower eq $currenttheme|strtolower} z-defaulttablerow{/if}">
            {if !$expired AND !$servicedisable}
            <td class="z-right z-nowrap">
                {gt text='Preview: %s' tag1=$theme.displayname assign=strPreviewTheme}
                {gt text='Edit: %s' tag1=$theme.displayname assign=strEditTheme}
                {gt text='Delete: %s' tag1=$theme.displayname assign=strDeleteTheme}
                {gt text='Set as default: %s' tag1=$theme.displayname assign=strSetDefaultTheme}
                {gt text='Credits: %s' tag1=$theme.displayname assign=strCreditsTheme}
            
              
                <a href="{modurl modname="ZSELEX" type="user" func="site" id=$smarty.request.shop_id theme=$theme.displayname|safetext}" title="{$theme.displayname|safetext}">{icon type="preview" size="extrasmall" __alt="Preview" title=$strPreviewTheme class="tooltips"}</a>
                 {if $theme.name neq $currenttheme and $theme.user and $theme.state neq 2}
                <a href="{modurl modname="ZSELEX" type="admin" func="setasdefaultshoptheme" shop_id=$smarty.request.shop_id themename=$theme.name}">{icon type="ok" size="extrasmall" __alt="Set as default" title=$strSetDefaultTheme class="tooltips"}</a>
                {/if}
                <a href="{modurl modname="Theme" type="admin" func="credits" themename=$theme.name}">{icon type="info" size="extrasmall" __alt="Credits" title=$strCreditsTheme class="tooltips"}</a>
           
                </td>
                {/if}

            <td>
                <a href="{modurl modname="ZSELEX" type="user" func="site" id=$smarty.request.shop_id theme=$theme.displayname|safetext}" title="{$theme.displayname|safetext}">
                  
                    <span title="#title_{$theme.name}" class="tooltips marktooltip">{$theme.displayname|safetext}</span>
                   
                </a>
                {if $theme.name|strtolower eq $currenttheme|strtolower}<span title="{gt text="Default theme"}" class="tooltips z-form-mandatory-flag">*</span>{/if}
                <div id="title_{$theme.name}" class="theme_preview z-center" style="display: none;">
                    <h4>{$theme.displayname}</h4>
                    {if $themeinfo.system neq 1}
                    <p>{previewimage name=$theme.name}</p>
                    {/if}
                </div>
            </td>
            <td>
              
                {$theme.description|default:$theme.displayname}
            </td>
               
        </tr>
        {foreachelse}
        <tr class="z-datatableempty"><td colspan="3">{gt text="No items found."}</td></tr>
        {/foreach}
    </tbody>
</table>

<em><span class="z-form-mandatory-flag">*</span> = {gt text="Default theme"}</em>
{pager rowcount=$pager.numitems limit=$pager.itemsperpage posvar='startnum' maxpages=10}
{adminfooter}