


<div class="z-formrow">
	<label for="amount">{gt text="No: of shops to display"}</label>
	<input id="amount" type="text"   value="{$vars.amount}" name="amount" />
</div>

{*
<div class="z-formrow">
    <label for="adtype">{gt text="Ad Type"}</label>
    <select name='adtype' id='adtype'>
    <option value=''>-select type-</option>
    {foreach from=$adtypes item='item'}
                    <option value="{$item.adprice_id}"  {if $vars.adtype eq $item.adprice_id} selected="selected" {/if}> {$item.name} </option>
                       {/foreach}
    </select>
</div>
*}

<div class="z-formrow">
    <label for="shop_id">{gt text="ISHOP"}</label>
    <select name='shop_id' id='shop_id'>
    <option value=''>-select type-</option>
    {foreach from=$ishops item='item'}
     <option value="{$item.shop_id}"  {if $vars.shop_id eq $item.shop_id} selected="selected" {/if}> {$item.shop_name} </option>
    {/foreach}
    </select>
</div>

<div class="z-formrow">
	<label for="orderby">{gt text="Order by"}</label>
	 <select name='orderby' id='orderby'>
         <option value='random' {if $vars.orderby eq 'random'} selected="selected" {/if}>{gt text='Random'}</option>
         <option value='new' {if $vars.orderby eq 'new'} selected="selected" {/if}>{gt text='New'}</option>
          </select>
</div>