
{if $perm}
<a href="{modurl modname="ZSELEX" type="admin" func="shopsettings" shop_id=$smarty.request.shop_id}#aAddress" class="edit-link"><i class="fa fa-pencil-square" aria-hidden="true"></i>{gt text='Edit Content'}</a>
{/if}

{assign var="time" value=$shopinfo.opening_hours|unserialize}
<div class="shop-address col-md-12 col-sm-6 col-md-push-0 col-sm-push-6 col-xs-6 col-xs-push-6">
    {if $time.mon.open neq '' || $time.tue.open neq '' || $time.wed.open neq '' || $time.thu.open neq '' || $time.fri.open neq '' || $time.sat.open neq '' || $time.sun.open neq '' || $time.comment neq ''}
    <p>
        <b>{gt text='Opening Hours'}</b>
    </p>
    <table width="100%" class="opening-time">
        <thead>
            <tr>
                <th width="20%"></th>
                <th width="10%"></th>
                <th width="5%"></th>
                <th width="60%"></th>
            </tr>
        </thead>
        <tbody>
            {if !$time.mon.closed}
            {if $time.mon.open neq '' || $time.mon.close neq ''}
            <tr>
                <td>{gt text='Monday'}</td>
                {if $time.mon.closed}
                <td>{gt text='Closed'}</td>
                {else}  
                <td>{$time.mon.open}</td>
                <td>-</td>
                <td>{$time.mon.close}</td>
                <td width="50%"></td>
                {/if}
            </tr>
            {/if}
            {/if}

            {if !$time.tue.closed}
            {if $time.tue.open neq '' || $time.tue.close neq ''}
            <tr>
                <td>{gt text='Tuesday'}</td>
                {if $time.tue.closed}
                <td>{gt text='Closed'}</td>
                {else} 
                <td>{$time.tue.open}</td>
                <td>-</td>
                <td>{$time.tue.close}</td>
                <td width="50%"></td>
                {/if}
            </tr>
            {/if}
            {/if}
            {if !$time.wed.closed}
            {if $time.wed.open neq '' || $time.wed.close neq ''}
            <tr>
                <td>{gt text='Wednesday'}</td>
                {if $time.wed.closed}
                <td>{gt text='Closed'}</td>
                {else} 
                <td>{$time.wed.open}</td>
                <td>-</td>
                <td>{$time.wed.close}</td>
                <td width="50%"></td>
                {/if}
            </tr>
            {/if}
            {/if}
            {if !$time.thu.closed}
            {if $time.thu.open neq '' || $time.thu.close neq ''}
            <tr>
                <td>{gt text='Thursday'}</td>
                {if $time.thu.closed}
                <td>{gt text='Closed'}</td>
                {else} 
                <td>{$time.thu.open}</td>
                <td>-</td>
                <td>{$time.thu.close}</td>
                <td width="50%"></td>
                {/if}
            </tr>
            {/if}
            {/if}
            {if !$time.fri.closed}
            {if $time.fri.open neq '' || $time.fri.close neq ''}
            <tr>
                <td>{gt text='Friday'}</td>
                {if $time.fri.closed}
                <td>{gt text='Closed'}</td>
                {else} 
                <td>{$time.fri.open}</td>
                <td>-</td>
                <td>{$time.fri.close}</td>
                <td width="50%"></td>
                {/if}
            </tr>
            {/if}
            {/if}
            {if !$time.sat.closed}
            {if $time.sat.open neq '' || $time.sat.close neq ''}
            <tr>
                <td>{gt text='Saturday'}</td>
                {if $time.sat.closed}
                <td>{gt text='Closed'}</td>
                {else}   
                <td>{$time.sat.open}</td>
                <td>-</td>
                <td>{$time.sat.close}</td>
                <td width="50%"></td>
                {/if}
            </tr>
            {/if}
            {/if}
            {if !$time.sun.closed}
            {if $time.sun.open neq '' || $time.sun.close neq ''}
            <tr>
                <td>{gt text='Sunday'}</td>
                {if $time.sun.closed}
                <td>{gt text='Closed'}</td>
                {else}   
                <td>{$time.sun.open}</td>
                <td>-</td>
                <td>{$time.sun.close}</td>
                <td width="50%"></td>
                {/if}
            </tr>
            {/if}
            {/if}
            {if $time.comment neq ''}
                <tr>
                   <td colspan="5">
                       {*{$time.comment|nl2br|wordwrap:35:"<br/>":true}*}
                       <br>{$time.comment|nl2br}
                   </td>
               </tr>
            {/if}
        </tbody>
    </table>
    {/if}
    <p>
        <b>{gt text='Contact Us'}</b>
    </p>
    <p>
        {if $shopinfo.address neq ''}
    <div>{$shopinfo.address|nl2br}</div>
    {/if}
    {if $shopinfo.email neq ''}
    <div>{gt text='Email'} : {$shopinfo.email}</div>
    {/if}
    {if !empty($shopinfo.fax)}
    <div>{gt text='Fax'}: {$shopinfo.fax}</div>
    {/if}
    {if $shopinfo.telephone neq ''}
    <div>{gt text='Tel'} : {$shopinfo.telephone}</div>
    {/if}
    {if $shopinfo.vat_number neq ''}
    <div>{gt text='VAT#'} : {$shopinfo.vat_number|wordwrap:30:"<br/>":true}</div>
    {/if}
</p>
</div>