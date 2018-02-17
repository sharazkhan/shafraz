 <div class="admin_right_top">
        <h4 class="dine"> {gt text="Your Services"} </h4>
        <table class="DineTable">
            <tr>
                <th>{gt text="Service"}:</th><th>{gt text="Expires"}:</th>
            </tr>
             {foreach item='service' key=index from=$services}
                 {if $service.remind eq 1}
                    <tr class="Orange">
                      <td>
                          {$service.service_name} {if $service.qty_based}({$service.quantity}){/if}
                      </td>
                      <td>
                          <span class="Exclaim"></span>{$service.DIFF}
                      </td>
                    </tr>
                    {else}
                     <tr>
                        <td>
                            {$service.service_name} {if $service.qty_based}({$service.quantity}){/if}
                        </td>
                        <td>{$service.DIFF}
                        </td>
                    </tr> 
                  {/if}   
             {foreachelse}
                 
                   <tr>
                       <td colspan="2" align="left">{gt text="No Services"}</td>
                    </tr> 
                 
              {/foreach}   
     </table>
    </div>