


<div class="z-formrow">
	<label for="amount">{gt text="No: of products to display"}</label>
	<input id="amount" type="text"   value="{$vars.amount}" name="amount" />
</div>

{*
<div class="z-formrow">
	<label for="adtype">{gt text="Ad Type"}</label>
	 <select name='adtype' id='adtype'>
         <option value='High' {if $vars.adtype eq 'High'} selected="selected" {/if}>High</option>
         <option value='Low' {if $vars.adtype eq 'Low'} selected="selected" {/if}>Low</option>
          </select>
</div>
*}

<div class="z-formrow">
    <label for="adtype">{gt text="Ad Type"}</label>
    <select name='adtype' id='adtype'>
    <option value=''>-select type-</option>
    {foreach from=$adtypes item='item'}
                    <option value="{$item.adprice_id}"  {if $vars.adtype eq $item.adprice_id} selected="selected" {/if}> {$item.name} </option>
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