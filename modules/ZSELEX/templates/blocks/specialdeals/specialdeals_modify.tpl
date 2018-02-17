<fieldset>
<legend id="eventheaders">{gt text='High Price Ads'}</legend> 
<div class="z-formrow">
	<label for="highad_amount">{gt text="No: of shops to display"}</label>
	<input id="highad_amount" type="text"   value="{$vars.highad_amount}" name="highad_amount" />
</div>


{*<div class="z-formrow">
    <label for="sd_adtype">{gt text="Ad Type"}</label>
    <select name='sd_adtype' id='sd_adtype'>
    <option value='0'>-select type-</option>
    {foreach from=$adtypes item='item'}
    <option value="{$item.adprice_id}"  {if $vars.sd_adtype eq $item.adprice_id} selected="selected" {/if}>{$item.name}</option>
    {/foreach}
    </select>
</div>*}


{*<div class="z-formrow">
	<label for="sd_orderby">{gt text="Order by"}</label>
	<select name='sd_orderby' id='sd_orderby'>
         <option value='random' {if $vars.sd_orderby eq 'random'} selected="selected" {/if}>{gt text='Random'}</option>
         <option value='new' {if $vars.sd_orderby eq 'new'} selected="selected" {/if}>{gt text='New'}</option>
        </select>
</div>*}
</fieldset>


<fieldset>
<legend id="eventheaders">{gt text='Medium Price Ads'}</legend> 
<div class="z-formrow">
	<label for="midad_amount">{gt text="No: of shops to display"}</label>
	<input id="midad_amount" type="text"   value="{$vars.midad_amount}" name="midad_amount" />
</div>

</fieldset>


<fieldset>
<legend id="eventheaders">{gt text='Low Price Ads'}</legend> 
<div class="z-formrow">
	<label for="lowad_amount">{gt text="No: of shops to display"}</label>
	<input id="lowad_amount" type="text"   value="{$vars.lowad_amount}" name="lowad_amount" />
</div>

</fieldset>


<fieldset>
<legend id="eventheaders">{gt text='Events'}</legend> 
<div class="z-formrow">
	<label for="event_amount">{gt text="No: of shops to display"}</label>
	<input id="event_amount" type="text"   value="{$vars.event_amount}" name="event_amount" />
</div>

</fieldset>


<fieldset>
<legend id="eventheaders">{gt text='Articles'}</legend> 
<div class="z-formrow">
	<label for="article_amount">{gt text="No: of shops to display"}</label>
	<input id="article_amount" type="text"   value="{$vars.article_amount}" name="article_amount" />
</div>

</fieldset>