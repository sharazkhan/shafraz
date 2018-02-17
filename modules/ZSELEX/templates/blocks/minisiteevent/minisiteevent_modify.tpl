{pageaddvar name='javascript' value='jquery'}


      <div class="z-formrow">
        <label for="displayinfo">{gt text="Display Information Message"}</label>
                <select name='displayinfo'>
                 <option value='no' {if $vars.displayinfo eq 'no'} selected="selected" {/if}>No</option>
                 <option value='yes' {if $vars.displayinfo eq 'yes'} selected="selected" {/if}>Yes</option>
                </select>
        </div>


  {foreach item='language' from=$languages}
        
      <div align="center"><b><font>{$language}</font></b></div>
        <div class="z-formrow" >
             <label for="infotitle">{gt text="Information title"}(<b>{$language}</b>)</label>
             <input type="text" id="{$language}" name="blockinfo[{$language}][infotitle]" value="{if $exist}{$vars.blockinfo.$language.infotitle}{/if}" />
        </div>    


        <div class="z-formrow">
        <label for="infomessage">{gt text="Information Message"}(<b>{$language}</b>)</label>
                 <textarea id="{$language}" name="blockinfo[{$language}][infomessage]">{if $exist}{$vars.blockinfo.$language.infomessage}{/if}</textarea>
       </div>
      
  {/foreach}
