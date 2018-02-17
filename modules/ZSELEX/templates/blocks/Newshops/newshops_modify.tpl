

<div class="z-formrow">
	<label for="shop">{gt text="Select Shop"}</label>
	
        <select name='shop' id='shop'>
                  <option value='0'>select</option>
                  {foreach item='item' from=$zshops}
                  <option value="{$item.shop_id}" {if $item.shop_id eq $vars.shop} selected="selected"{/if}> {$item.shop_name} </option>
                   {/foreach}
        </select>
</div>

<div class="z-formrow">
	<label for="amount">{gt text="No: of products to display"}</label>
	<input id="amount" type="text"   value="{$vars.amount}" name="amount" />
</div>

<div class="z-formrow">
	<label for="orderby">{gt text="Order by"}</label>
	 <select name='orderby' id='orderby'>
         <option value='random' {if $vars.orderby eq 'random'} selected="selected" {/if}>Random</option>
         <option value='new' {if $vars.orderby eq 'new'} selected="selected" {/if}>New</option>
         </select>
</div>