
<div class="z-formrow">
	<label for="amount">{gt text="No: of products to display"}</label>
	<input id="amount" type="text"   value="{$vars.amount}" name="amount" />
</div>

<div class="z-formrow">
	<label for="orderby">{gt text="Order by"}</label>
	 <select name='orderby' id='orderby'>
         <option value='random' {if $vars.orderby eq 'random'} selected="selected" {/if}>{gt text='Random'}</option>
         <option value='new' {if $vars.orderby eq 'new'} selected="selected" {/if}>{gt text='New'}</option>
          </select>
</div>
          
           <div class="z-formrow">
        <label for="displayinfo">{gt text="Display Information Message"}</label>
                <select name='displayinfo'>
                 <option value='no' {if $vars.displayinfo eq 'no'} selected="selected" {/if}>{gt text='No'}</option>
                 <option value='yes' {if $vars.displayinfo eq 'yes'} selected="selected" {/if}>{gt text='Yes'}</option>
                </select>
        </div>


  {foreach item='language' from=$languages}
        
      <div align="center"><b><font>{$language}</font></b></div>
        <div class="z-formrow" >
             <label for="infotitle">{gt text="Information title"}(<b>{$language}</b>)</label>
             <input type="text" id="{$language}" name="blockinfo[{$language}][infotitle]" value="{$vars.blockinfo.$language.infotitle}" />
        </div>    


        <div class="z-formrow">
        <label for="infomessage">{gt text="Information Message"}(<b>{$language}</b>)</label>
                 <textarea id="{$language}" name="blockinfo[{$language}][infomessage]">{$vars.blockinfo.$language.infomessage}</textarea>
      </div>
      
  {/foreach}

