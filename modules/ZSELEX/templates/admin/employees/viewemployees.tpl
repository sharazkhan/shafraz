{pageaddvar name='javascript' value='jquery'}
{pageaddvar name='javascript' value='zikula.ui'}
<script type="text/javascript" src="modules/ZSELEX/javascript/dndfiles/jquery.js"></script>
<script type="text/javascript" src="modules/ZSELEX/javascript/dndfiles/ajaxupload.js"></script>

<link href="modules/ZSELEX/style/dndcss/classicTheme/style.css" rel="stylesheet" type="text/css" />


 {shopheader}
 {securityutil_checkpermission_block component='ZSELEX::' instance='::' level=ACCESS_ADD}
{if $serviceerror < 1 AND !$servicedisable}
<div class="z-admin-content-pagetitle">
    <a href="{modurl modname="ZSELEX" type='admin' func='createemployee' shop_id=$smarty.request.shop_id}" class="z-iconlink z-icon-es-add">{gt text='Create Employee'}</a>
</div>
  {/if}
 {/securityutil_checkpermission_block}

<input type="hidden" id="servicelimit" value="{$servicelimit}" />
<input type="hidden" id="quantity" value="{$quantity}" />
<input type="hidden" id="uploadpath" value="{$uploadpath}" />
<input type="hidden" id="shop_id" name="shop_id" value="{$smarty.request.shop_id}" />
 {securityutil_checkpermission_block component='ZSELEX::' instance='::' level=ACCESS_ADD}
{if $serviceerror < 1 AND !$servicedisable}
 <table class="options">
    <tbody>
        <tr>
            <td>
                <div id="drophere" style="width:580px;height:200px;border: 1px solid black;">{gt text='Drag and Drop files here'}</div>
                <div id="employee_images" style="width:500px"></div>

            </td>

        </tr>
    </tbody>
</table>
{/if}
 {/securityutil_checkpermission_block}
                

<div class="z-admin-content-pagetitle">
    {icon type="view" size="small"}
    <h3>{gt text='Employee list'}</h3>
</div>

<form class="z-form" id="bulk" action="{modurl modname='ZSELEX' type='admin' func='viewemployees' shop_id=$smarty.request.shop_id}" method="post" enctype="application/x-www-form-urlencoded">
    <fieldset id="zselex_multicategory_filter"{if $filter_active} class='filteractive'{/if}>
        {if $filter_active}{gt text='active' assign=filteractive}{elseif $filter_active neq ''}{gt text='inactive' assign=filteractive}{else $filter_active eq ''}{gt text='All' assign=filteractive}{/if}
        <legend>{gt text='Filter %1$s, %2$s Employee listed (%3$s active)' plural='Filter %1$s, %2$s Employees listed (%3$s active)' count=$total_count tag1=$filteractive tag2=$total_count tag3=$active_plugins}</legend>
        <input type="hidden" name="startnum" value="{$startnum}" />
        <input type="hidden" name="order" value="{$order}" />
        <input type="hidden" name="sdir" value="{$sdir}" />
        <label for="searchtext">{gt text='Employee Name'}</label>
        <input type="text" name="searchtext" value="{$searchtext}" />
        <label for="status">{gt text='Status'}</label>
        {html_options name='status' id='status' options=$itemstatus selected=$status}
        &nbsp;
        &nbsp;&nbsp;
        <span class="z-nowrap z-buttons">
            <input class='z-bt-filter' name="submit" type="submit" value="{gt text='Filter'}" />
            <a href="{modurl modname="ZSELEX" type='admin' func='viewemployees' shop_id=$smarty.request.shop_id}" title="{gt text="Clear"}">{img modname=core src="button_cancel.png" set="icons/extrasmall" __alt="Clear" __title="Clear"} {gt text="Clear"}</a>
        </span>
    </fieldset>
</form>
<form class="z-form" id="zselex_bulkaction_employee_form" name="zselex_bulkaction_employee_form" action="" method="post">
     <div style="overflow:auto;">
        <input type="hidden" name="csrftoken" value="{insert name='csrftoken'}" />
       <table id="zselex_admintable" class="z-datatable">
            <thead>
                <tr>
                   <!--   <th></th> -->
                   {securityutil_checkpermission_block component='ZSELEX::' instance='::' level=ACCESS_ADD}
                   {if !$expired AND !$servicedisable}
                    <th>{gt text='Actions'}</th>
                   {/if}
                    {/securityutil_checkpermission_block}   
                    <th><a class='{$sort.class.emp_id}' href='{$sort.url.emp_id|safetext}'>{gt text='Emp ID'}</a></th>
                    <th><a class='{$sort.class.name}' href='{$sort.url.name|safetext}'>{gt text='Name'}</a></th>
                    <th>{gt text='Picture'}</th>
                    <th>{gt text='Shop'}</th>
                    <th>{gt text='Phone'}</th>
                    <th>{gt text='Cell'}</th>
                    <th>{gt text='Email'}</th>
                    <th>{gt text='Job'}</th>
                    <th>{gt text='Status'}</th>
                    <th>{gt text='Created User'}</th>
                    <th><a class='{$sort.class.cr_date}' href='{$sort.url.cr_date|safetext}'>{gt text='Created'}</a></th>
                    <th>{gt text='Updated User'}</th>
                    <th><a class='{$sort.class.lu_date}' href='{$sort.url.lu_date|safetext}'>{gt text='Updated'}</a></th>
                   
                </tr>
            </thead>
            <tbody>
                {foreach from=$employees item='employee'}
                <tr class="{cycle values='z-odd,z-even'}">
                   {securityutil_checkpermission_block component='ZSELEX::' instance='::' level=ACCESS_ADD}
                    {if !$expired AND !$servicedisable}
                    <td>
                        {assign var='options' value=$employee.options}
                        {section name='options' loop=$options}
                        <a href="{$options[options].url|safetext}">{img modname='core' set='icons/extrasmall' src=$options[options].image title=$options[options].title alt=$options[options].title class='tooltips'}</a>
                        {/section}
                    </td>
                     {/if}
                     {/securityutil_checkpermission_block}
                    <td>{$employee.emp_id|safetext}</td>
                    <td>{$employee.name|safetext}</td>
                    <td><img src="{$baseurl}zselexdata/{$ownername}/employees/thumb/{$employee.emp_image}"></td> 
                    <td>{$employee.shop_name|safetext}</td>
                    <td>{$employee.phone|safetext}</td>
                    <td>{$employee.cell|safetext}</td>
                    <td>{$employee.email|safetext}</td>
                    <td>{$employee.job|safetext}</td>
                    <td>{$aStatus[$employee.status]|safetext}</td>
                    <td>{$employee.createduser|safetext}</td>
                    <td>{$employee.cr_date|safetext}</td>
                    <td>{$employee.updateduser|safetext}</td>
                    <td>{$employee.lu_date|safetext}</td>
                   
                </tr>
                {foreachelse}
                <tr class="z-datatableempty"><td colspan="14">{gt text='No Employees present for this shop.'}</td></tr>
                {/foreach}
            </tbody>
        </table>
       
    </div>
</form>

{pager rowcount=$total_count limit=$itemsperpage posvar='startnum' maxpages=10}

{adminfooter}


<script type="text/javascript">
    jQuery('#employee_images').ajaxupload({
        url:document.location.pnbaseURL+"index.php?module=ZSELEX&type=ajax&func=upload_employee_images",
        // allowExt:['jpg','JPG'],
        remotePath: document.getElementById('uploadpath').value ,
        dropArea:'#drophere' ,
        editFilename:true,
        maxFiles:{{$servicelimit}},
        form:'#zselex_bulkaction_employee_form'
    });
</script>


<script type="text/javascript">
// <![CDATA[
    Zikula.UI.Tooltips($$('.tooltips'));
// ]]>
</script>