{pageaddvar name='javascript' value='jquery'}


      <div class="z-formrow">
        <label for="displayinfo">{gt text="Display Information Message"}</label>
                <select name='displayinfo'>
                 <option value='no' {if $vars.displayinfo eq 'no'} selected="selected" {/if}>No</option>
                 <option value='yes' {if $vars.displayinfo eq 'yes'} selected="selected" {/if}>Yes</option>
                </select>
        </div>


 
