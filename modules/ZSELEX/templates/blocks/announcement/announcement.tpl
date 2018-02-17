{fileversion}
{pageaddvar name="stylesheet" value="themes/$current_theme/style/announcement.css$ver"}
{assign var='variablename' value="\n"|explode:$text}


 <div class="SlopedText">
                 <div class="InnerText">
                     {*
                 {if $variablename[0]|trim neq ''}     
                  {$variablename[0]}<br>
                  {/if}
                  {if $variablename[1]|trim neq ''}  
                 {$variablename[1]}<br>
                  {/if}
                {if $variablename[2]|trim neq ''}  
                 {$variablename[2]}<br>
                  {/if}
                {if $variablename[3]|trim neq ''}  
                 {$variablename[3]}
                    {/if}
                    *}
                    {foreach from=$variablename item='text'}
                          {if $text|trim neq ''} 
                         {$text}<br>
                          {/if}
                    {/foreach}
                  </div>
 </div>
