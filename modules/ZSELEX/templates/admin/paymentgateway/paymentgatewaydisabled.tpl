
{shopheader}
{pageaddvar name='javascript' value='jquery'}
{pageaddvar name='javascript' value='zikula.ui'}
{pageaddvar name='javascript' value='modules/ZSELEX/javascript/basket.js'}


<style>

    .basket_content {
        background-color: white;
        border: 16px solid black;
        left: 25%;
        min-height: 100px;
        overflow: auto;
        position: fixed;
        top: 30%;
        width: 750px;
        z-index: 10002;
    }
    .backshield {
        background-color: #333333;
        height: 200%;
        left: 0;
        opacity: 0.8;
        position: absolute;
        top: 0;
        width: 100%;
        z-index: 1000;
    }

</style>


<div class="z-admin-content-pagetitle">
   
    <h3>{gt text='Payments'}</h3>
 
</div>
    
    
<div class="z-admin-content-pagetitle">
     <h4><font color="red">{gt text='This Service Is Tempororily Disabled'}</font></h4>
</div>
  <!--  
<div id="admCart" align="center" onClick='displayBasket({$smarty.request.shop_id})' style="cursor:pointer">
   
    <b>cart({$count})</b>
 
</div> 
  -->
<form class="z-form" action="{modurl modname="ZSELEX" type="admin" func="paymentgateway"}" method="post" enctype="multipart/form-data">
<div>
    <div class="z-formrow">
                <label for="plugin"></label>
                <table width="30%"  class="z-datatable">
                     <thead>
                        <tr>
                            <td><b>{gt text='Payment Type'}</b></td>
                           
                            <td><b>{gt text='Edit'}</b></td>
                            
                        </tr>
                    </thead>
                    <tbody>
                    
                    <tr class="{cycle values='z-odd,z-even'}">
                           <td>
                             {gt text='PayPal'}
                           </td>
                            <td>
                          {if $admin < 1} 
                             {gt text='Cannot Edit'}
                             {else}
                                <a href="{modurl modname="ZSELEX" type="admin" func="paypalConfig" shop_id=$smarty.request.shop_id}">{gt text='Edit'}</a>
                           {/if}
                             </td>
                    </tr>
              
                  
                    </tbody>
                </table>
            </div>
                </div>
  
</form>
    
    
<div id="light" class="basket_content" style="display:none"></div>
<div id="backshield" class="backshield" style="height: 2157px;display:none" onClick='closeWindow();'></div>
                
                
                
<script type="text/javascript">
    // var defaultTooltip = new Zikula.UI.Tooltip($('toolmsg'));

    Zikula.UI.Tooltips($$('.toolmsg'));
</script>
 
 
 


{adminfooter}