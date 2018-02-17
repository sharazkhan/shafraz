{fileversion}
{pageaddvar name="stylesheet" value="themes/CityPilot/style/checkout.css$ver"} 
{pageaddvar name="stylesheet" value="modules/ZSELEX/javascript/validation/style.css$ver"} 
{pageaddvar name='javascript' value='http://ajax.googleapis.com/ajax/libs/prototype/1.6.0.3/prototype.js'}
{pageaddvar name='javascript' value='http://ajax.googleapis.com/ajax/libs/scriptaculous/1.8.2/effects.js'}
{pageaddvar name='javascript' value="modules/ZSELEX/javascript/validation/fabtabulous.js$ver"}
{pageaddvar name='javascript' value="modules/ZSELEX/javascript/validation/validation.js$ver"}

 {include file="user/cartmenu.tpl"}<br><br>
   <form id="delivery" name="delivery" method="post" action="{modurl modname="ZSELEX" type="user" func="delivery" shop_id=$smarty.request.shop_id}">           
<div class="checkout_form">
                    <h2>{gt text='Enter Customer Information'}</h2>
                    <div class="chekout_sec left">
                        <div class="height_sink">
                            <table class="checkout_table">
                                <tr>
                                    <td><label>{gt text='First Name'}</label></td>
                                    <td><input class="required" title="{gt text='Please enter your first name'}" type="text" name="fname" value="{$info.first_name|cleantext}" /></td>    
                                </tr>
                                <tr>
                                    <td><label>{gt text='Last Name'}</label></td>
                                    <td><input class="required" title="{gt text='Please enter your last name'}" type="text" name="lname" value="{$info.last_name|cleantext}"/></td> 
                                </tr>
                                <tr>
                                    <td><label>{gt text='Address'}:</label></td>
                                    <td><input class="required" title="{gt text='Please enter your address'}" type="text" name="address" value="{$info.address|cleantext}"/></td> 
                                </tr>
                                <tr>
                                    <td><label>{gt text='ZIP code. city'}: </label></td>
                                    <td><input class="required" title="{gt text='Please enter your zip code'}" type="text" name="zip" id="country_code" value="{$info.zip}"/> &nbsp;&nbsp;<input class="required" title="{gt text='Please enter your city'}" type="text" name="city" id="telephone" value="{$info.city|cleantext}"/></td> 
                                </tr>
                            </table>
                        </div>
                        <!--button type="button" class="gray_button">
                            <span class="Left_Arrow"></span>
                            {gt text='Continue Shopping'}
                            </span>
                         </button-->
                <a href="{modurl modname="ZSELEX" type="user" func="shop" shop_id=$shop_id}"><button type="button" class="gray_button"><span class="Left_Arrow"></span>{gt text='Continue Shopping'}</button></a>

                    </div>
                    <div class="chekout_sec right">
                        <div class="height_sink">
                            <table class="checkout_table">
                                <tr>
                                    <td><label>{gt text='Phone'}:</label></td>
                                    <td><input  class="required" title="{gt text='Please enter a valid phone number'}" type="text" name="phone" value="{$info.mobile}"/></td>    
                                </tr>
                                <tr>
                                    <td><label>{gt text='Email address'}:</label></td>
                                    <td><input class="required validate-email" title="{gt text='Please enter a valid email'}"  type="text" name="email" value="{$info.email}"/></td> 
                                </tr>
                                <tr>
                                    <td><label>{gt text='Subscribe To Newsletter'}:</label></td>
                                    <td><input type="checkbox" name="subscribe" value="1"/></td> 
                                </tr>
                            </table>
                        </div>
                    
                        <button onClick="submitCheckout()" type="button" class="Orange_button right">
                            <span>{gt text='Go to delivery'}</span><span class="Right_Arrow"></span>
                        </button>
                        
                    </div>
               </div>
                        <input type="hidden" name="country" value="{$info.country}"/>
                        <input type="hidden" name="state" value="{$info.state}"/>
                         </form>
                         
                         <script type="text/javascript">
			 // var valid2 = new Validation('delivery', {useTitles:true});
                          var valid2 = new Validation('delivery', {useTitles:true,onSubmit:false});
                          
                          
                          function submitCheckout(){
                            var result = valid2.validate();
                                if(result){
                                    jQuery('#delivery').submit();
                                }
                           
                          }
		         </script>
                       
                       