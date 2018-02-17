

<span style="display:none">{gt text='DOTD'}</span>


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
