 
{if $parent_product_options.0.option_type eq 'dropdown'}
    <div class="form-group">
   <select linked="1" {if !$enable}disabled{/if} parent="1" mytype="dropdown" id="test-{$parent_product_options.0.parent_option_value_id}" class='options_select parents' name="{$parent_product_options.0.option_name}" onChange="changePrice('{$parent_product_options.0.product_id}','{$parent_product_options.0.option_id}',this.value ,0,1,'');">
   <option value=''>{gt text='select'}</option>
    {foreach from=$parent_product_options  item='pitem'}              
               <option value="{$pitem.parent_option_value_id}" valueid="{$pitem.parent_option_value_id}">{$pitem.option_value}</option>
    {/foreach}  
   </select>
   </div>

{elseif $parent_product_options.0.option_type eq 'radio'}
        <div class="form-group">
     {foreach from=$parent_product_options  item='pitem'}              
        {* <input linked="1" {if !$enable}disabled{/if} parent="1" mytype="radio" valueid="{$pitem.parent_option_value_id}" id="test-{$pitem.parent_option_value_id}" name="product_options[{$pitem.option_name}][]"  class='options parents'  value="{$pitem.parent_option_value_id}" type="radio" onClick="changePrice('{$pitem.product_id}','{$parent_product_options.0.option_id}',this.value ,0,1,'');">{$pitem.option_value}  *}      
       <input linked="1" {if !$enable}disabled{/if} parent="1" mytype="radio" valueid="{$pitem.parent_option_value_id}" id="test-{$parent_product_options.0.parent_option_value_id}" name="product_options[{$pitem.option_name}][]"  class='options_select parents'  value="{$pitem.parent_option_value_id}" type="radio" onClick="changePrice('{$pitem.product_id}','{$parent_product_options.0.option_id}',this.value ,0,1,'');">{$pitem.option_value}        
     {/foreach}  
        </div>
{/if}