


<div class="z-formrow">
	<label for="amount">{gt text="No: of shops to display"}</label>
	<input id="amount" type="text"   value="{$vars.amount}" name="amount" />
</div>

<div class="z-formrow">
	<label for="orderby">{gt text="Order by"}</label>
	 <select name='orderby' id='orderby'>
         <option value='random' {if $vars.orderby eq 'random'} selected="selected" {/if}>Random</option>
         <option value='new' {if $vars.orderby eq 'new'} selected="selected" {/if}>New</option>
          </select>
</div>