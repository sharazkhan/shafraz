
<form name="myform" id="myform" class="z-form" action="{modurl modname="Zvelo" type="user" func="wishes"}" method="post">
        <input type="hidden" id="mtype" name="formElement[type]" value="">
        <input type="hidden" id="murl" name="formElement[murl]" value="">
<div class="thumb_nails_sec options">
                	<div class="config_cont">
                    	<ul class="bike_config">
                        	
                            <li>
                            <p>{gt text='Usage'}</p><!-- checkbox -->
                            <div class="check_box">
                            	
                                <div class="input_checkbox">
                                 <input type="checkbox" name="formElement[usage][]"  {foreach from=$wish.usages|unserialize item='item'}{if $item eq 'Commuting'}checked{/if}{/foreach} value="Commuting"  >Commuting</div>
                                 
                                 <div class="input_checkbox"><input type="checkbox" name="formElement[usage][]" value="Recreation" {foreach from=$wish.usages|unserialize item='item'}{if $item eq 'Recreation'}checked{/if}{/foreach}>
                                 Recreation</div>
                                 <div class="input_checkbox"><input type="checkbox" name="formElement[usage][]" value="Fitness" {foreach from=$wish.usages|unserialize item='item'}{if $item eq 'Fitness'}checked{/if}{/foreach}>
                                Fitness</div>
                                 <div class="input_checkbox"> <input type="checkbox" name="formElement[usage][]" value="Sport" {foreach from=$wish.usages|unserialize item='item'}{if $item eq 'Sport'}checked{/if}{/foreach}>
                               Sport</div>
                                
                               
                            </div>
                            </li>
                            
                            <li><p>{gt text='Age class'}</p><!-- radio -->
                            <div class="check_box" style="line-height:12px">
                            	{*<div class="orange_bg variation_bg">*}
                                	 <div class="input_checkbox">    
                                     <input type="radio" name="formElement[ageclass]" value="< 18" {if $wish.ageclass eq '< 18'}checked{/if}>
                                           < 18
                                         </div> 
                                            <div class="input_checkbox">  
                                     <input type="radio" name="formElement[ageclass]" value="18 - 30" {if $wish.ageclass eq '18 - 30'}checked{/if}>
                                           18 - 30
                                            </div>
                                            <div class="input_checkbox">
                                     <input type="radio" name="formElement[ageclass]" value="31 - 45" {if $wish.ageclass eq '31 - 45'}checked{/if}>
                                           31 - 45
                                            </div>
                                             <div class="input_checkbox">
                                     <input type="radio" name="formElement[ageclass]" value="46 - 55" {if $wish.ageclass eq '46 - 55'}checked{/if}>
                                           46 - 55
                                             </div>
                                              <div class="input_checkbox">
                                     <input type="radio" name="formElement[ageclass]" value="> 55" {if $wish.ageclass eq '> 55'}checked{/if}>
                                             > 55
                                              </div>
                                     {* </div> *}
                                {*<div class="orange_bg_text variation_tx radio">
                                    < 18<br />
                                    18 - 30<br />
                                    31 - 45<br />
                                    46 - 55<br />
                                    > 55
                                	
                                </div>*}
                            </div>
                            </li>
                         
                            <li class="last"> <!-- radio -->
                               <p>{gt text='KM / month'}</p>
                             <div class="check_box">
                            	
                                     <div class="input_checkbox">
                                	 <input type="radio" name="formElement[kmmonthly]" value="< 100" {if $wish.kmmonthly eq '< 100'}checked{/if}>
                                      < 100
                                     </div>  
                                        <div class="input_checkbox"> 
                                         <input type="radio" name="formElement[kmmonthly]" value="< 100 - 200" {if $wish.kmmonthly eq '< 100 - 200'}checked{/if}>
                                        100 - 200
                                        </div>
                                          <div class="input_checkbox">
                                         <input type="radio" name="formElement[kmmonthly]" value="< 201 - 500" {if $wish.kmmonthly eq '< 201 - 500'}checked{/if}>
                                        201 - 500
                                          </div>
                                         <div class="input_checkbox">
                                         <input type="radio" name="formElement[kmmonthly]" value="> 500" {if $wish.kmmonthly eq '> 500'}checked{/if}>
                                          > 500
                                         </div>
                               
                            </div>
                            </li>
                            <li>
                            <p>{gt text='Frame material'}</p> <!-- checkbox -->
                             <div class="check_box">
                              <div class="input_checkbox">
                                <input type="checkbox" name="formElement[framematerial][]" value="Steel" {foreach from=$wish.framematerial|unserialize item='item'}{if $item eq 'Steel'}checked{/if}{/foreach}>
                              Steel
                              </div> 
                               <div class="input_checkbox">
                                <input type="checkbox" name="formElement[framematerial][]" value="Alloy" {foreach from=$wish.framematerial|unserialize item='item'}{if $item eq 'Alloy'}checked{/if}{/foreach}>
                               Alloy
                               </div>
                               <div class="input_checkbox">
                                <input type="checkbox" name="formElement[framematerial][]" value="Composite" {foreach from=$wish.framematerial|unserialize item='item'}{if $item eq 'Composite'}checked{/if}{/foreach}>
                               Composite
                               </div>
                                <div class="input_checkbox">
                                <input type="checkbox" name="formElement[framematerial][]" value="Other"  {foreach from=$wish.framematerial|unserialize item='item'}{if $item eq 'Other'}checked{/if}{/foreach}>
                               Other
                                </div>
                               
                            </div>
                            </li>
                            <li>
                             <p>{gt text='Frame type'}</p> <!-- checkbox -->
                             <div class="check_box">
                            	<div class="input_checkbox">
                                <input type="checkbox" name="formElement[frametype][]" value="Diamond" {foreach from=$wish.frametype|unserialize item='item'}{if $item eq 'Diamond'}checked{/if}{/foreach}>
                               Diamond
                                </div>
                                <div class="input_checkbox">
                                <input type="checkbox" name="formElement[frametype][]" value="Women" {foreach from=$wish.frametype|unserialize item='item'}{if $item eq 'Women'}checked{/if}{/foreach}>
                               Women
                                </div>
                               <div class="input_checkbox">
                                <input type="checkbox" name="formElement[frametype][]" value="Unisex" {foreach from=$wish.frametype|unserialize item='item'}{if $item eq 'Unisex'}checked{/if}{/foreach}>
                              Unisex
                               </div>
                              <div class="input_checkbox">
                                <input type="checkbox" name="formElement[frametype][]" value="Y" {foreach from=$wish.frametype|unserialize item='item'}{if $item eq 'Y'}checked{/if}{/foreach}>	
                               Y
                              </div>
                               
                            </div>
                            </li>
                            <li class="last"> <!-- checkbox -->
                            	<p>{gt text='Suspension'}</p>
                             <div class="check_box">
                            	<div class="input_checkbox">
                                    <input type="checkbox" name="formElement[suspension][]" value="No" {foreach from=$wish.suspension|unserialize item='item'}{if $item eq 'No'}checked{/if}{/foreach}>
                                   No
                                </div>
                                   <div class="input_checkbox">
                                    <input type="checkbox" name="formElement[suspension][]" value="Front" {foreach from=$wish.suspension|unserialize item='item'}{if $item eq 'Front'}checked{/if}{/foreach}>
                                   Front
                                   </div>
                                    <div class="input_checkbox">
                                    <input type="checkbox" name="formElement[suspension][]" value="Frame" {foreach from=$wish.suspension|unserialize item='item'}{if $item eq 'Frame'}checked{/if}{/foreach}>
                                  Frame
                                    </div>
                                    <div class="input_checkbox">
                                    <input type="checkbox" name="formElement[suspension][]" value="Seatpos" {foreach from=$wish.suspension|unserialize item='item'}{if $item eq 'Seatpos'}checked{/if}{/foreach}>
                                   Seat pos
                                    </div>
                               
                            </div>
                            
                            </li>
                            <li class="bottom"> <!-- checkbox -->
                            	<p>{gt text='Gears'}</p>
                             <div class="check_box">
                            	  <div class="input_checkbox">
                                    <input type="checkbox" name="formElement[gears][]" value="No" {foreach from=$wish.gears|unserialize item='item'}{if $item eq 'No'}checked{/if}{/foreach}>
                                   No
                                  </div>
                                   <div class="input_checkbox">
                                    <input type="checkbox" name="formElement[gears][]" value="Derailleur" {foreach from=$wish.gears|unserialize item='item'}{if $item eq 'Derailleur'}checked{/if}{/foreach}>
                                 Derailleur
                                    </div>
                                    <div class="input_checkbox">
                                    <input type="checkbox" name="formElement[gears][]" value="Internalhub" {foreach from=$wish.gears|unserialize item='item'}{if $item eq 'Internalhub'}checked{/if}{/foreach}>
                                  Internal hub
                                    </div>
                                    <div class="input_checkbox">
                                    <input type="checkbox" name="formElement[gears][]" value="Other" {foreach from=$wish.gears|unserialize item='item'}{if $item eq 'Other'}checked{/if}{/foreach}>
                                  Other
                                    </div>
                               
                            </div>
                            
                            </li>
                            <li class="bottom"> <!-- checkbox -->
                            		<p>{gt text='Brakes'}</p>
                             <div class="check_box">
                            	<div class="input_checkbox">
                                    <input type="checkbox" name="formElement[brakes][]" value="V-brake" {foreach from=$wish.brakes|unserialize item='item'}{if $item eq 'V-brake'}checked{/if}{/foreach}>
                                 V-brake
                                </div> 
                                  <div class="input_checkbox">
                                    <input type="checkbox" name="formElement[brakes][]" value="Discbrake" {foreach from=$wish.brakes|unserialize item='item'}{if $item eq 'Discbrake'}checked{/if}{/foreach}>
                                  Disc brake
                                  </div>
                                     <div class="input_checkbox">
                                    <input type="checkbox" name="formElement[brakes][]" value="Hydraulic" {foreach from=$wish.brakes|unserialize item='item'}{if $item eq 'Hydraulic'}checked{/if}{/foreach}>
                                  Hydraulic
                                     </div>
                                   <div class="input_checkbox">
                                    <input type="checkbox" name="formElement[brakes][]" value="Other" {foreach from=$wish.brakes|unserialize item='item'}{if $item eq 'Other'}checked{/if}{/foreach}>
                                 Other
                                   </div>
                               
                            </div>
                            </li>
                            <li class="last bottom"> <!-- checkbox -->
                            <p>{gt text='Accessories'}</p>
                             <div class="check_box">
                            	  <div class="input_checkbox">
                                    <input type="checkbox" name="formElement[accessories][]" value="Clothing" {foreach from=$wish.accessories|unserialize item='item'}{if $item eq 'Clothing'}checked{/if}{/foreach}>
                                    Clothing
                                  </div> 
                                     <div class="input_checkbox">
                                    <input type="checkbox" name="formElement[accessories][]" value="Helmet" {foreach from=$wish.accessories|unserialize item='item'}{if $item eq 'Helmet'}checked{/if}{/foreach}>
                                    Helmet
                                     </div>
                                      <div class="input_checkbox">
                                    <input type="checkbox" name="formElement[accessories][]" value="Pump" {foreach from=$wish.accessories|unserialize item='item'}{if $item eq 'Pump'}checked{/if}{/foreach}>
                                    Pump
                                      </div>
                                       <div class="input_checkbox">
                                    <input type="checkbox" name="formElement[accessories][]" value="Shoes" {foreach from=$wish.accessories|unserialize item='item'}{if $item eq 'Shoes'}checked{/if}{/foreach}>
                                    Shoes
                                       </div>
                              
                            </div>
                            </li>
                        </ul>
                    </div>
                	
                </div>
                
                 <div class="privew_sec">
                	 {bicycledetail}
                 </div><!-- -->
                
            </form>	