


                <h3>{gt text='Profile'}</h3>
                <h4 class="orange_h4">{gt text='Client data'}</h4>
                <ul class="white_list">
                    <li>{$customerInfo.first_name}&nbsp;{$customerInfo.last_name}</li>
                    <li>{$customerInfo.address}</li>
                    <li>{$customerInfo.address2}</li>
                    <li>{$customerInfo.zipcode}&nbsp;{$customerInfo.city}</li>
                    <li>{$customerInfo.phone}</li>
                </ul>
                 <h4 class="orange_h4">{gt text='Bicycle category'}</h4>
                <ul class="white_list">
                    <li>{$bicycleBlock.name}</li>
                    <li>{$bicycleBlock.nos}</li>                   
                </ul>
                 <h4 class="orange_h4">{gt text='Client wishes'}</h4>
                <ul class="white_list">
                     <li>
                        <b>{gt text='Seat Position'} :</b><br>
                         {if $wish.seatposition}
                          &nbsp;{$wish.seatposition}
                          {/if}
                    </li>
                    <li>
                        <b>{gt text='Usage'} :</b><br>
                         {if $wish.usages|unserialize}
                        {foreach from=$wish.usages|unserialize item='item'}
                            &nbsp;{$item}<br>
                        {/foreach}
                          {/if}
                    </li>
                    <li>
                         <b>{gt text='Ageclass'} :</b><br>
                           {if $wish.ageclass}
                              &nbsp;{$wish.ageclass}
                           {/if}
                    </li>
                    <li>
                         <b>{gt text='KM / month'} :</b><br>
                          {if $wish.kmmonthly}
                              &nbsp;{$wish.kmmonthly}
                           {/if}
                    </li>
                    <li>
                         <b>{gt text='Frame material'} :</b><br>
                            {if $wish.framematerial|unserialize}
                        {foreach from=$wish.framematerial|unserialize item='item'}
                             &nbsp;{$item}<br>
                        {/foreach}
                            {/if}
                    </li>
                    <li>
                          <b>{gt text='Frame type'} :</b><br>
                           {if $wish.frametype|unserialize}
                        {foreach from=$wish.frametype|unserialize item='item'}
                            &nbsp;{$item}<br>
                        {/foreach}
                            {/if}
                    </li>
                    <li>
                         <b>{gt text='Suspension'} :</b><br>
                        {if $wish.suspension|unserialize}
                        {foreach from=$wish.suspension|unserialize item='item'}
                            &nbsp;{$item}<br>
                        {/foreach}
                        {/if}
                    </li>
                    <li>
                          <b>{gt text='Gear'} :</b><br>
                          {if $wish.gear|unserialize}
                        {foreach from=$wish.gear|unserialize item='item'}
                            &nbsp;{$item}<br>
                        {/foreach}
                           {/if}
                    </li>
                    <li>
                          <b>{gt text='Brakes'} :</b><br>
                           {if $wish.brakes|unserialize}
                        {foreach from=$wish.brakes|unserialize item='item'}
                            &nbsp;{$item}<br>
                        {/foreach}
                          {/if}
                    </li>
                    <li>
                         <b>{gt text='Accessories'} :</b><br>
                           {if $wish.accessories|unserialize}
                        {foreach from=$wish.accessories|unserialize item='item'}
                            &nbsp;{$item}<br>
                        {/foreach}
                        {/if}
                    </li>                
                </ul>
               
                 <h4 class="orange_h4">{gt text='Comments'}</h4>
                  <ul class="white_list">
                      <li style="padding-right: 15px;">{$customerInfo.comments}</li>
                  </ul>
                