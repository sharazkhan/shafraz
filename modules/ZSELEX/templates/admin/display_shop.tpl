{adminheader}
<div class="z-admin-content-pagetitle">
    <h3>{gt text='Display Shop'}</h3>
</div>
    <div>
<table cellpadding="10" cellspacing="10">
        <tr>
            <td >{gt text='Name'} : </td>
            <td >{$item.shop_name|safetext}</td>
        </tr>
        <tr>
            <td >{gt text='Description'} : </td>
            <td >{$item.description|safetext}</td>
        </tr>
        <tr>
            <td >{gt text='Status'} : </td>
            <td>{$item.status}</td>
        </tr>
</table>
    <div class="z-buttons z-formbuttons">
         {assign var='options' value=$item.options}
        {section name='options' loop=$options}
        <a href="{$options[options].url|safetext}">{img modname='core' set='icons/extrasmall' src=$options[options].image title=$options[options].title alt=$options[options].title class='tooltips'}</a>
        {/section}
    </div>

    </div>
</form>
{adminfooter}