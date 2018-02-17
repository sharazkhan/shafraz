{pageaddvar name='javascript' value='jquery'}

      <div class="z-formrow">
        <label for="displayinfo">{gt text="Display Information Message"}</label>
                <select name='displayinfo'>
                 <option value='no' {if $vars.displayinfo eq 'no'} selected="selected" {/if}>No</option>
                 <option value='yes' {if $vars.displayinfo eq 'yes'} selected="selected" {/if}>Yes</option>
                </select>
        </div>
                
        <div class="z-formrow" >
             <label for="height">{gt text="Height"}</label>
             <input type="text" id="height" name="map_height" value="{$vars.map_height}" />
        </div>    
          <div class="z-formrow" >
             <label for="width">{gt text="Width"}</label>
             <input type="text" id="width" name="map_width" value="{$vars.map_width}" />
        </div> 
        
        <div class="z-formrow" >
             <label for="width">{gt text="Zoom Level"}</label>
             <input type="text" id="width" name="map_zoom" value="{$vars.map_zoom}" />
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
