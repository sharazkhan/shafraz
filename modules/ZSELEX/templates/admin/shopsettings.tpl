
{pageaddvar name='javascript' value='jquery'}
{*{pageaddvar name='javascript' value='modules/ZSELEX/javascript/dndfiles/jquery.js'}
{pageaddvar name='javascript' value='modules/ZSELEX/javascript/dndfiles/ajaxupload.js'}*}

<!------------------ DND PLUGIN --------------------------------------->
{*{pageaddvar name='javascript' value='modules/ZSELEX/javascript/DND/jquery.js'}*}
{pageaddvar name='javascript' value='modules/ZSELEX/javascript/DND/ajaxupload-min.js'}
{pageaddvar name="stylesheet" value="modules/ZSELEX/style/DND/classicTheme/style.css"}
<!---------------------------------------------------------------------->


{pageaddvar name="stylesheet" value="themes/$current_theme/style/shopsetting.css"}

{*
{pageaddvar name='javascript' value='modules/ZSELEX/javascript/jquery.jscroll.min.js'}
{pageaddvar name='javascript' value='modules/ZSELEX/javascript/jquery.jscroll.js'}
*}
{jscroll file_name='modules/ZSELEX/javascript/scroll.js'}

<script src="modules/ZSELEX/javascript/jquerycalendar/jquery-ui.js"></script>
{pageaddvar name='javascript' value='modules/ZSELEX/javascript/shopsetting/shopsetting.js'}
{*<link href="modules/ZSELEX/style/dndcss/classicTheme/style.css" rel="stylesheet" type="text/css" />*}
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
{pageaddvar name="stylesheet" value="themes/$current_theme/style/minisiteimages.css"}
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
{timepickerplugin}
{jcrop}

<input type="hidden" id="servicelimit" value="{$servicelimit}" />
<input type="hidden" id="quantity" value="{$quantity}" />
<input type="hidden" id="uploadpath" value="{$uploadpath}" />
<input type="hidden" id="shop_id" name="shop_id" value="{$smarty.request.shop_id}" />

<div class="z-admin-content-pagetitle">
    {icon type="view" size="small"}
    <h3>{gt text='Shop Settings'}</h3>
</div>
{securityutil_checkpermission_block component='ZSELEX::' instance='::' level=ACCESS_ADD}

{/securityutil_checkpermission_block}

<div id="backshield" class="backshield" style="height: 4000px;display:none"></div>
<div id="editImage" class="basket_content" style="display:none"></div>    
<div id="cropImage" class="basket_content" style="display:none"></div> 

<br>
<div id="deleteShop" class="basket_content" style="display:none">
    
</div>  
 <div class="z-buttons z-formbuttons">
         <button onClick="deleteShopRequest()" id="product_delete"  type="button"  name="action" value="deleteimage" title="{gt text='Delete Shop'}">
             {img src='14_layer_deletelayer.png' modname='core' set='icons/extrasmall' __alt='Delete Shop' __title='Delete Shop'}
             {gt text='Delete Shop'}
         </button>
 </div>
<form class="z-form" id="zselex_form" name="zselex_form" action="{modurl modname='ZSELEX' type='admin' func='shopsettings' shop_id=$smarty.request.shop_id}" method="post">
    <input type="hidden" name="csrftoken" value="{insert name="csrftoken"}" />
    <div class="z-panels" id="panel">
        <a name="aTop"></a>
        <fieldset>
            <legend id="settingheaders" title="{gt text='Click here to see more fields and options'}">{gt text='Shop Information'}</legend> 
            <div id="settingheadersdiv" style="display:none">
                <div class="z-buttons z-formbuttons">
                    <a href="{modurl modname="ZSELEX" type="avery" func="printAvery" shop_id=$smarty.request.shop_id}" title="{gt text="Cancel"}" target="_blank"> {gt text="Print Label Sheet"}</a>
                    {button src="button_ok.png" set="icons/extrasmall" __alt="Save" __title="Save" __text="Save" __name="action" __value="savedefaults"}
                    <a href="{modurl modname="ZSELEX" type="user" func="site" shop_id=$smarty.request.shop_id}" title="{gt text="Cancel"}">{img modname=core src="button_cancel.png" set="icons/extrasmall" __alt="Cancel" __title="Cancel"} {gt text="Cancel"}</a>
                </div>

                <fieldset>
                    <div class="z-formrow">
                        <label for="shop_name">{gt text='Shop Name'}</label>
                        <input type="text"  name='shop_name' id='shop_name' value="{$shop_info.shop_name|cleantext}" >
                    </div>
                    <div class="z-formrow">
                        <label for="urltitle">{gt text='Shop URL'}</label>
                        <input disabled type="text"  name='urltitle' id='urltitle' value="{$shop_info.urltitle}" >
                        <span style="padding-left:164px;cursor:pointer;color:blue" id="unlock" onclick="unlockTitle();">{gt text='unlock'}</span>
                    </div>

                </fieldset>

                <a name="aInformation"></a>
                <fieldset>
                    <div class="z-formrow">
                        <label for="shop_info">{gt text='Frontpage Information'}</label>
                        <textarea  name='shop_info' id='shop_info' cols="80" rows="10">{$shop_info.shop_info|cleantext}</textarea>
                    </div>
                      <div class="z-formrow">
                        <label for="link_to_homepage">{gt text='Link to my home page'}</label>
                        <input type='text'  name='link_to_homepage' id='link_to_homepage' value='{$shop_info.link_to_homepage}' />
                    <span style="padding-left:154px" align="justify">
                        <i style="color:grey"> 
                            {gt text="* Just leave this field empty if you don't have a homepage."}
                            <br>
                            {gt text="** Insert HTTP:// in front of your link if it is external."}
                            
                        </i>
                    </span>
                      </div>

                </fieldset>

                <a name="aAddress"></a>
                <fieldset>
                    <div class="z-formrow">
                        <label for="opening_hours">{gt text='Address'}</label>
                        <textarea  name='address' id='address' cols="80" rows="3">{$shop_info.address|cleantext}</textarea>
                    </div>

                    <div class="z-formrow">
                        <label for="elemtTele">{gt text='Telephone'}</label>
                        <input type='text'  name='telephone' id='telephone' value='{$shop_info.telephone}' />
                    </div>
                    <div class="z-formrow">
                        <label for="elemtTele">{gt text='VAT#'}</label>
                        <input type='text'  name='vat_number' id='telephone' value='{$shop_info.vat_number}' />
                    </div>

                    <div class="z-formrow">
                        <label for="elemtFax">{gt text='Fax'}</label>
                        <input type='text'  name='fax' id='elemtFax' value='{$shop_info.fax}'   />
                    </div>

                    <div class="z-formrow">
                        <label for="elemtEmail">{gt text='Email'}</label>
                        <input type='text'  name='email' id='elemtEmail' value='{$shop_info.email}'   />
                    </div>

                </fieldset>

                <a name="aOpeninghours"></a>
                <fieldset>
                    <div class="z-formrow">
                        <label for="opening_hours">{gt text='Opening Hours'}</label>
                        {*<textarea  name='opening_hours' id='opening_hours' cols="80" rows="10">{$shop_info.opening_hours}</textarea>*}
                        <table align="center">
                            <tr>
                                <td></td>
                                <td>{gt text='Open'}</td>
                                <td>{gt text='Close'}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>{gt text='Monday'}</td>
                                <td><input class="timepicker" type="text" id="mon_open" name='opening_hours[mon][open]' value="{if $shop_info.opening_hour_array.mon.open neq ''}{$shop_info.opening_hour_array.mon.open}{else}8:00{/if}"></td>
                                <td><input class="timepicker" type="text" id="mon_close"  name='opening_hours[mon][close]' value="{if $shop_info.opening_hour_array.mon.close neq ''}{$shop_info.opening_hour_array.mon.close}{else}16:00{/if}"></td>
                                <td><input type="checkbox" name='opening_hours[mon][closed]' value="1" {if $shop_info.opening_hour_array.mon.closed}checked{/if}>&nbsp;{gt text='closed'}</td>
                            </tr>
                            <tr>
                                <td>{gt text='Tuesday'}<br><span style="cursor:pointer;color:blue;" onclick="copyTime(document.getElementById('mon_open').value, document.getElementById('mon_close').value, 'tue')"><font size="1">{gt text='as above'}</font></span></td>
                                <td><input class="timepicker" type="text" id="tue_open" name='opening_hours[tue][open]' value="{if $shop_info.opening_hour_array.tue.open}{$shop_info.opening_hour_array.tue.open}{else}8:00{/if}"></td>
                                <td><input class="timepicker" type="text" id="tue_close" name='opening_hours[tue][close]' value="{if $shop_info.opening_hour_array.tue.close neq ''}{$shop_info.opening_hour_array.tue.close}{else}16:00{/if}"></td>
                                <td><input type="checkbox" value="1" name='opening_hours[tue][closed]' {if $shop_info.opening_hour_array.tue.closed}checked{/if}>&nbsp;{gt text='closed'}</td>
                            </tr>
                            <tr>
                                <td>{gt text='Wednesday'}<br><span style="cursor:pointer;color:blue;" onclick="copyTime(document.getElementById('tue_open').value, document.getElementById('tue_close').value, 'wed')"><font size="1">{gt text='as above'}</font></span></td>
                                <td><input class="timepicker" type="text" id="wed_open" name='opening_hours[wed][open]' value="{if $shop_info.opening_hour_array.wed.open neq ''}{$shop_info.opening_hour_array.wed.open}{else}8:00{/if}"></td>
                                <td><input class="timepicker" type="text" id="wed_close" name='opening_hours[wed][close]' value="{if $shop_info.opening_hour_array.wed.close neq ''}{$shop_info.opening_hour_array.wed.close}{else}16:00{/if}"></td>
                                <td><input type="checkbox" value="1" name='opening_hours[wed][closed]' {if $shop_info.opening_hour_array.wed.closed}checked{/if}>&nbsp;{gt text='closed'}</td>
                            </tr>
                            <tr>
                                <td>{gt text='Thursday'}<br><span style="cursor:pointer;color:blue;" onclick="copyTime(document.getElementById('wed_open').value, document.getElementById('wed_close').value, 'thu')"><font size="1">{gt text='as above'}</font></span></td>
                                <td><input class="timepicker" type="text" id="thu_open" name='opening_hours[thu][open]' value="{if $shop_info.opening_hour_array.thu.open neq ''}{$shop_info.opening_hour_array.thu.open}{else}8:00{/if}"></td>
                                <td><input class="timepicker" type="text" id="thu_close"  name='opening_hours[thu][close]' value="{if $shop_info.opening_hour_array.thu.close neq ''}{$shop_info.opening_hour_array.thu.close}{else}16:00{/if}"></td>
                                <td><input type="checkbox" value="1" name='opening_hours[thu][closed]' {if $shop_info.opening_hour_array.thu.closed}checked{/if}>&nbsp;{gt text='closed'}</td>
                            </tr>
                            <tr>
                                <td>{gt text='Friday'}<br><span style="cursor:pointer;color:blue;" onclick="copyTime(document.getElementById('thu_open').value, document.getElementById('thu_close').value, 'fri')"><font size="1">{gt text='as above'}</font></span></td>
                                <td><input class="timepicker" type="text" id="fri_open"  name='opening_hours[fri][open]' value="{if $shop_info.opening_hour_array.fri.open}{$shop_info.opening_hour_array.fri.open}{else}8:00{/if}"></td>
                                <td><input class="timepicker" type="text" id="fri_close"  name='opening_hours[fri][close]' value="{if $shop_info.opening_hour_array.fri.close neq ''}{$shop_info.opening_hour_array.fri.close}{else}16:00{/if}"></td>
                                <td><input type="checkbox" value="1" name='opening_hours[fri][closed]' {if $shop_info.opening_hour_array.fri.closed}checked{/if}>&nbsp;{gt text='closed'}</td>
                            </tr>
                            <tr>
                                <td>{gt text='Saturday'}<br><span style="cursor:pointer;color:blue;" onclick="copyTime(document.getElementById('fri_open').value, document.getElementById('fri_close').value, 'sat')"><font size="1">{gt text='as above'}</font></span></td>
                                <td><input class="timepicker" type="text" id="sat_open" name='opening_hours[sat][open]' value="{if $shop_info.opening_hour_array.sat.open}{$shop_info.opening_hour_array.sat.open}{else}8:00{/if}"></td>
                                <td><input class="timepicker" type="text" id="sat_close"  name='opening_hours[sat][close]' value="{if $shop_info.opening_hour_array.sat.close neq ''}{$shop_info.opening_hour_array.sat.close}{else}16:00{/if}"></td>
                                <td><input type="checkbox" value="1" name='opening_hours[sat][closed]' {if $shop_info.opening_hour_array.sat.closed}checked{/if}>&nbsp;{gt text='closed'}</td>
                            </tr>
                            <tr>
                                <td>{gt text='Sunday'}<br><span style="cursor:pointer;color:blue;" onclick="copyTime(document.getElementById('sat_open').value, document.getElementById('sat_close').value, 'sun')"><font size="1">{gt text='as above'}</font></span></td>
                                <td><input class="timepicker" type="text" id="sun_open" name='opening_hours[sun][open]' value="{if $shop_info.opening_hour_array.sun.open neq ''}{$shop_info.opening_hour_array.sun.open}{else}8:00{/if}"></td>
                                <td><input class="timepicker" type="text" id="sun_close" name='opening_hours[sun][close]' value="{if $shop_info.opening_hour_array.sun.close neq ''}{$shop_info.opening_hour_array.sun.close}{else}16:00{/if}"></td>
                                <td><input type="checkbox" value="1" name='opening_hours[sun][closed]' {if $shop_info.opening_hour_array.sun.closed}checked{/if}>&nbsp;{gt text='closed'}</td>
                            </tr>
                            <tr>
                                <td>{gt text='Comment'}</td>
                                <td colspan="3"><textarea style="width: 250px; height: 86px;"  name='opening_hours[comment]' id='comment' cols="90" rows="3">{$shop_info.opening_hour_array.comment|cleantext}</textarea></td>

                            </tr>
                        </table>
                    </div>

                </fieldset>

                <!--            </div>
                        </fieldset>                     
                
                
                        <fieldset>
                            <legend id="settingheaders">{gt text='Advance Settings'}</legend> 
                            <div style="display:none">
                -->
                <div class="z-formrow">
                    <label for="mainshopyes">{gt text='Set as Main Shop'}</label>
                    <div>
                        <input type="radio" onclick='radioCheck()' value="1" id="mainshopyes" name="mainshop" {if $shop_info.main eq 1} checked="checked"{/if}/>
                               <input type="button"  onclick="check();" value="{gt text='Set'}">
                        <input type="button" onclick="uncheck();" value="{gt text='Unset'}">
                    </div>
                </div>
                    
                     <div class="z-formrow">
                    <label for="adv_sel_prod">{gt text='Advertise only selected products'}</label>
                    <div>
                        <input type="checkbox"  value="1" id="adv_sel_prod" name="adv_sel_prod" {if $shop_info.advertise_sel_prods eq 1} checked="checked"{/if}/>
                             
                    </div>
                </div>

                <!--fieldset>
                    <legend>{gt text='Set Shop Default Image'}</legend>
                    <div class="z-formrow">

                        <label for="default_img_frm1">{gt text='From Minisite Image'}</label>
                        <div>
                            <input {if !$image_perm} disabled {/if} type="radio" value="fromshop" id="default_img_frm1" name="default_img_frm" {if $shop_info.default_img_frm eq 'fromshop'} checked="checked"{/if}/>
                        </div>
                        <label for="default_img_frm2">{gt text='From Gallery Image'}</label>
                        <div>
                            <input {if !$gallery_perm} disabled {/if} type="radio" value="fromgallery" id="default_img_frm2" name="default_img_frm" {if $shop_info.default_img_frm eq 'fromgallery'} checked="checked"{/if}/>
                        </div>

                    </div>
                </fieldset-->


                <!--div class="z-formrow">
                    <label for="article">{gt text='Select Article'}</label>
                    <select {if !$article_perm} disabled {/if} name='formElements[article]' id='article' class="icon-menu" >
                        <option style="padding-left:0px;" value="">{gt text='Select Article'}</option>
                        {foreach item='article' from=$articles}
                        <option  value="{$article.sid}" {if $item.news_article_id eq $article.sid} selected='selected' {/if}> {$article.title} </option>
                        {/foreach}
                    </select>
                </div-->


                <div class="z-buttons z-formbuttons">
                    {button src="button_ok.png" set="icons/extrasmall" __alt="Save" __title="Save" __text="Save" __name="action" __value="savedefaults"}
                    <a href="{modurl modname="ZSELEX" type="user" func="site" shop_id=$smarty.request.shop_id}" title="{gt text="Cancel"}">{img modname=core src="button_cancel.png" set="icons/extrasmall" __alt="Cancel" __title="Cancel"} {gt text="Cancel"}</a>
                </div>

            </div>

        </fieldset>   

{securityutil_checkpermission_block component='ZSELEX::' instance='::' level=ACCESS_ADD}
        <fieldset>
            <input type="hidden" name="formElement[shop_id]" value="{$smarty.request.shop_id}" />
            <legend id="settingheaders" title="{gt text='Click here to configure payment gateway'}">{gt text='Payment Gateways'}</legend> 
            <div id="settingheadersdiv" style="display:none">
                <div class="z-buttons z-formbuttons">
                    {button src="button_ok.png" set="icons/extrasmall" __alt="Save" __title="Save" __text="Save" __name="action" __value="savepayments"}
                    <a href="{modurl modname="ZSELEX" type="user" func="site" shop_id=$smarty.request.shop_id}" title="{gt text="Cancel"}">{img modname=core src="button_cancel.png" set="icons/extrasmall" __alt="Cancel" __title="Cancel"} {gt text="Cancel"}</a>
                </div>
                {if $payment_perm}<!--payment config--->


                <fieldset>
                    
                    <legend  title="{gt text='Cards Accepted'}">{gt text='Cards/Payments Accepted'}</legend>
                     <div id="settingheadersdiv">
                    <table>
                        <tr>
                            <td>
                                  <input {if $CardsAccepted.paypal eq 'PayPal|paypal.png'}checked{/if} id="paypl" type="checkbox" name="CardsAccepted[paypal]" value="PayPal|paypal.png">
                        <label for="paypl"><img class="paycard" src="modules/ZSELEX/images/CreditCards/paypal.png">{gt text="PayPal"}</label>
                            </td>
                             <td>
                                   <input {if $CardsAccepted.VisaDankort eq 'Dankort/Visa-Dankort|dankort.png'}checked{/if} id="Vsa-Dankrt"  type="checkbox" name="CardsAccepted[VisaDankort]" value="Dankort/Visa-Dankort|dankort.png">
                        <label for="Vsa-Dankrt"><img class="paycard" class="paycard" src="modules/ZSELEX/images/CreditCards/dankort.png">{gt text="Dankort/Visa-Dankort"}</label>
                            </td>
                            <td>
                                  <input {if $CardsAccepted.Maestro3D eq 'Maestro (3D)|3d-maestro.png'}checked{/if} id="Maestro3D" type="checkbox" name="CardsAccepted[Maestro3D]" value="Maestro (3D)|3d-maestro.png">
                        <label for="Maestro3D"><img class="paycard" src="modules/ZSELEX/images/CreditCards/3d-maestro.png">{gt text="Maestro (3D)"}</label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                 <input  {if $CardsAccepted.Mastercard3D eq 'Mastercard (3D)|3d-mastercard.png'}checked{/if} id="Mastercard3D" type="checkbox" name="CardsAccepted[Mastercard3D]" value="Mastercard (3D)|3d-mastercard.png">
                        <label for="Mastercard3D"><img class="paycard" src="modules/ZSELEX/images/CreditCards/3d-mastercard.png">{gt text="Mastercard (3D)"}</label>
                            </td>
                            <td>
                                 <input  {if $CardsAccepted.MastercardDebet eq 'Mastercard-Debet|3d-mastercard-debet-dk.png'}checked{/if} id="MastercardDebet" type="checkbox" name="CardsAccepted[MastercardDebet]" value="Mastercard-Debet|3d-mastercard-debet-dk.png">
                        <label for="MastercardDebet"><img class="paycard" src="modules/ZSELEX/images/CreditCards/3d-mastercard-debet-dk.png">{gt text="Mastercard-Debet"}</label>
                            </td>
                            <td>
                                
                       <input {if $CardsAccepted.Visa3D eq 'Visa (3D)|3d-visa.png'}checked{/if} id="Visa3D" type="checkbox" name="CardsAccepted[Visa3D]" value="Visa (3D)|3d-visa.png">
                       <label for="Visa3D"><img class="paycard" src="modules/ZSELEX/images/CreditCards/3d-visa.png">{gt text="Visa (3D)"}</label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                 <input {if $CardsAccepted.VisaElectron3D eq 'Visa-Electron (3D)|3d-visa-electron.png'}checked{/if} id="Visa-Electron3D" type="checkbox" name="CardsAccepted[VisaElectron3D]" value="Visa-Electron (3D)|3d-visa-electron.png">
                        <label for="Visa-Electron3D"><img class="paycard" src="modules/ZSELEX/images/CreditCards/3d-visa-electron.png">{gt text="Visa-Electron (3D)"}</label>
                            </td>
                            <td>
                                   <input {if $CardsAccepted.JCB3D eq 'JCB (3D)|3d-jcb.png'}checked{/if}  id="JCB3D" type="checkbox" name="CardsAccepted[JCB3D]" value="JCB (3D)|3d-jcb.png">
                        <label for="JCB3D"><img class="paycard" src="modules/ZSELEX/images/CreditCards/3d-jcb.png">{gt text="JCB (3D)"}</label>
                            </td>
                            <td>
                                <input {if $CardsAccepted.LICMASTERCARD eq 'LIC Mastercard|lic.png'}checked{/if} id="LICMASTERCARD" type="checkbox" name="CardsAccepted[LICMASTERCARD]" value="LIC Mastercard|lic.png">
                        <label for="LICMASTERCARD"><img class="paycard" src="modules/ZSELEX/images/CreditCards/lic.png">{gt text="LIC Mastercard"}</label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                 <input {if $CardsAccepted.paii eq 'Paii|paii.png'}checked{/if} id="paii" type="checkbox" name="CardsAccepted[paii]" value="Paii|paii.png">
                        <label for="paii"><img class="paycard" src="modules/ZSELEX/images/CreditCards/paii.png">{gt text="Paii"}</label>
                            </td>
                            <td>
                                  <input {if $CardsAccepted.edankort eq 'eDankort|edankort.png'}checked{/if} id="edankort" type="checkbox" name="CardsAccepted[edankort]" value="eDankort|edankort.png">
                       <label for="edankort"><img class="paycard" src="modules/ZSELEX/images/CreditCards/edankort.png">{gt text="eDankort"}</label>
                            </td>
                            <td>
                                 <input {if $CardsAccepted.mastercard eq 'Mastercard|mastercard.png'}checked{/if}  id="mastercard" type="checkbox" name="CardsAccepted[mastercard]" value="Mastercard|mastercard.png">
                        <label for="mastercard"><img class="paycard" src="modules/ZSELEX/images/CreditCards/mastercard.png">{gt text="Mastercard"}</label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                  <input {if $CardsAccepted.mastercarddebet eq 'Mastercard-Debet|mastercard-debet-dk.png'}checked{/if} id="mastercard-debet" type="checkbox" name="CardsAccepted[mastercarddebet]" value="Mastercard-Debet|mastercard-debet-dk.png">
                         <label for="mastercard-debet"><img class="paycard" src="modules/ZSELEX/images/CreditCards/mastercard-debet-dk.png">{gt text="Mastercard-Debet"}</label>
                            </td>
                             <td>
                                  <input {if $CardsAccepted.visa eq 'Visa|visa.png'}checked{/if}  id="visa" type="checkbox" name="CardsAccepted[visa]" value="Visa|visa.png">
                        <label for="visa"><img class="paycard" src="modules/ZSELEX/images/CreditCards/visa.png">{gt text="Visa"}</label>
                            </td>
                             <td>
                                  <input {if $CardsAccepted.visaelectron eq 'Visa-Electron|visa-electron.png'}checked{/if} id="visa-electron" type="checkbox" name="CardsAccepted[visaelectron]" value="Visa-Electron|visa-electron.png">
                        <label for="visa-electron"><img class="paycard" src="modules/ZSELEX/images/CreditCards/visa-electron.png">{gt text="Visa-Electron"}</label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input {if $CardsAccepted.JCB eq 'JCB|jcb.png'}checked{/if} id="jcb" type="checkbox" name="CardsAccepted[JCB]" value="JCB|jcb.png">
                       <label for="jcb"><img class="paycard" src="modules/ZSELEX/images/CreditCards/jcb.png">{gt text="JCB"}</label>
                            </td>
                             <td>
                                 <input {if $CardsAccepted.americanexpress eq 'American Express|american-express.png'}checked{/if} id="americanexpress" type="checkbox" name="CardsAccepted[americanexpress]" value="American Express|american-express.png">
                        <label for="americanexpress"><img class="paycard" src="modules/ZSELEX/images/CreditCards/american-express.png">{gt text="American Express"}</label>
                            </td>
                            
                        </tr>
                    </table>
                         </div>
                </fieldset>




                {if $modvars.ZPayment.Netaxept_enabled_general eq true}
                <fieldset>
                    <legend id="settingheaders" title="{gt text='Click here to configure Netaxept'}">{gt text='Netaxept settings'}</legend>
            <div id="settingheadersdiv" style="display:none">
                    <div class="z-formrow">
                        <label for="Netaxept_enabled">{gt text='Enable'}</label>
                        <input type="checkbox" value="1" id="Netaxept_enabled" name="formElement[Netaxept_enabled]"{if $netaxept.enabled eq true} checked="checked"{/if}/>
                    </div>
                    <div class="z-formrow">
                        <label for="Netaxept_testmode">{gt text='Test Mode'}</label>
                        <input type="checkbox" value="1" id="Netaxept_testmode" name="formElement[Netaxept_testmode]"{if $netaxept.test_mode eq true} checked="checked"{/if}/>
                    </div>
                    <div class="z-formrow">
                        <label for="Netaxept_merchant_id">{gt text='Merchant ID'}</label>
                        <input type="text" value="{$netaxept.merchant_id}" id="Netaxept_merchant_id" name="formElement[Netaxept_merchant_id]"/>
                    </div>
                    <div class="z-formrow">
                        <label for="Netaxept_token">{gt text='Token'}</label>
                        <input type="text" value="{$netaxept.token}" id="Netaxept_token" name="formElement[Netaxept_token]"/>
                    </div>
                    <div class="z-formrow">
                        <label for="Netaxept_testmerchant_id">{gt text='Test Merchant ID'}</label>
                        <input type="text" value="{$netaxept.test_merchant_id}" id="Netaxept_testmerchant_id" name="formElement[Netaxept_testmerchant_id]"/>
                    </div>
                    <div class="z-formrow">
                        <label for="Netaxept_testtoken">{gt text='Test Token'}</label>
                        <input type="text" value="{$netaxept.test_token}" id="Netaxept_testtoken" name="formElement[Netaxept_testtoken]"/>
                    </div>
                    <div class="z-formrow">
                        <label for="Netaxept_returl">{gt text='Return Url'}</label>
                        <input type="text" value="{$netaxept.return_url}" id="Netaxept_returl" name="formElement[Netaxept_returl]"/>
                     <span style="padding-left:154px"  >
                        <i style="color:grey"> 
                            {gt text='Please enter the URL we should return to when returning from payment gateway. If you leave it blank we will return to'} {$baseurl}{modurl modname="ZSELEX" type="user" func='shop' shop_id=$shop_id}.{gt text='If you point a domain name like http://mydomainname.com to your CityPilot shop you should enter that url here.'}
                        </i>
                    </span>
                    </div>
			</div>
                </fieldset>
                {/if}
                {if $modvars.ZPayment.Paypal_enabled_general eq true}
                <fieldset>
                    <legend id="settingheaders" title="{gt text='Click here to configure Paypal'}">{gt text='Paypal settings'}</legend>
            <div id="settingheadersdiv" style="display:none">
                    <div class="z-formrow">
                        <label for="Paypal_disabled">{gt text='Enable'}</label>
                        <input type="checkbox" value="1" id="Netaxept_testmode" name="formElement[Paypal_enabled]"{if $paypal.enabled eq true} checked="checked"{/if}/>
                    </div>
                    <div class="z-formrow">
                        <label for="Paypal_testmode">{gt text='Test Mode'}</label>
                        <input type="checkbox" value="1" id="Paypal_testmode" name="formElement[Paypal_testmode]"{if $paypal.test_mode eq true} checked="checked"{/if}/>
                    </div>
                    <div class="z-formrow">
                        <label for="Paypal_business_email">{gt text='Business Email'}</label>
                        <input type="text" value="{$paypal.business_email}" id="Paypal_business_email" name="formElement[Paypal_business_email]"/>
                    </div>
			</div>
                </fieldset>
                {/if}
                {if $modvars.ZPayment.QuickPay_enabled_general eq true}
                <fieldset>
                    <legend id="settingheaders" title="{gt text='Click here to configure QuickPay'}">{gt text='QuickPay settings'}</legend>
            <div id="settingheadersdiv" style="display:none">
                    <div class="z-formrow">
                        <label for="QuickPay_enabled">{gt text='Enable'}</label>
                        <input type="checkbox" value="1" id="QuickPay_enabled" name="formElement[QuickPay_enabled]"{if $quickpay.enabled eq true} checked="checked"{/if}/>
                    </div>
                    {*<div class="z-formrow">
                        <label for="QuickPay_testmode">{gt text='Test Mode'}</label>
                        <input type="checkbox" value="1" id="QuickPay_testmode" name="formElement[QuickPay_testmode]"{if $quickpay.test_mode eq true} checked="checked"{/if}/>
                    </div>*}
                    <div class="z-formrow">
                        <label for="Quickpay_Merchant_ID">{gt text='Merchant ID'}</label>
                        <input type="text" value="{$quickpay.merchant_id}" id="Quickpay_Merchant_ID" name="formElement[quickpay_merchant_id]"/>
                    </div>
                      <div class="z-formrow">
                        <label for="Quickpay_Agreement_ID">{gt text='Agreement ID'}</label>
                        <input type="text" value="{$quickpay.agreement_id}" id="Quickpay_Agreement_ID" name="formElement[quickpay_agreement_id]"/>
                    </div>
                    <div class="z-formrow">
                        <label for="Quickpay_Api_Key">{gt text='Api Key'}</label>
                        <input type="text" value="{$quickpay.api_key}" id="Quickpay_Api_Key" name="formElement[quickpay_api_key]"/>
                    </div>
                   {* <div class="z-formrow">
                        <label for="QuickPay_paytype">{gt text='Payment Type'}</label>
                        <div>
                            <input type="radio" id="QuickPay_paytype" name="formElement[QuickPay_paytype]" value="auto" {if $quickpay.pay_type eq 'auto'}checked{/if}>
                                   {gt text='Automatic Card Selection'}
                                   <input type="radio" id="QuickPay_paytype" name="formElement[QuickPay_paytype]" value="individual" {if $quickpay.pay_type eq 'individual'}checked{/if}>
                                   {gt text='Select Individual Payment'}
                        </div>
                    </div>*}
                      <div class="z-formrow">
                        <label for="Quickpay_Api_Key">{gt text='Return Url'}</label>
                        <input type="text" value="{$quickpay.return_url}" id="Quickpay_Api_Key" name="formElement[quickpay_return_url]"/>
                          <span style="padding-left:154px"  >
                        <i style="color:grey"> 
                            {gt text='Please enter the URL we should return to when returning from payment gateway. If you leave it blank we will return to'} {$baseurl}{modurl modname="ZSELEX" type="user" func='shop' shop_id=$shop_id}.{gt text='If you point a domain name like http://mydomainname.com to your CityPilot shop you should enter that url here.'}
                        </i>
                    </span>
                    </div>
			</div>
                </fieldset>
                {/if}
                 {if $modvars.ZPayment.Epay_enabled_general eq true}
                <fieldset>
                    <legend id="settingheaders" title="{gt text='Click here to configure ePay'}">{gt text='ePay settings'}</legend>
            <div id="settingheadersdiv" style="display:none">
                    <div class="z-formrow">
                        <label for="Epay_enabled">{gt text='Enable'}</label>
                        <input type="checkbox" value="1" id="QuickPay_enabled" name="formElement[Epay_enabled]"{if $epay.enabled eq true} checked="checked"{/if}/>
                    </div>
                    <div class="z-formrow">
                        <label for="Epay_testmode">{gt text='Test Mode'}</label>
                        <input type="checkbox" value="1" id="QuickPay_testmode" name="formElement[Epay_testmode]"{if $epay.test_mode eq true} checked="checked"{/if}/>
                    </div>
                    <div class="z-formrow">
                        <label for="Epay_test_merchant_number">{gt text='ePay Test Merchant Number'}</label>
                         <input type="text" value="{$epay.test_merchant_number}" id="test_merchant_number" name="formElement[Epay_test_merchant_number]"/>
                    </div>
                     <div class="z-formrow">
                        <label for="Epay_merchant_number">{gt text='ePay Merchant Number'}</label>
                        <input type="text" value="{$epay.merchant_number}" id="QuickPay_ID" name="formElement[Epay_merchant_number]"/>
                    </div>
                    <div class="z-formrow">
                        <label for="Epay_md5">{gt text='MD5 Hash'}</label>
                        <input type="text" value="{$epay.md5_hash}" id="Epay_md5" name="formElement[Epay_md5]"/>
                    </div>
                   
			</div>
                </fieldset>
                {/if}
                {if $modvars.ZPayment.Directpay_enabled_general eq true}
                <fieldset>
                    <legend id="settingheaders" title="{gt text='Click here to configure Direct Payment'}">{gt text='Directpay settings'}</legend>
            <div id="settingheadersdiv" style="display:none">
                    <div class="z-formrow">
                        <label for="Directpay_enabled">{gt text='Enable'}</label>
                        <input type="checkbox" value="1" id="Directpay_enabled" name="formElement[Directpay_enabled]"{if $directpay.enabled eq true} checked="checked"{/if}/>
                    </div>


                    <div class="z-formrow">
                        <label for="Directpay_info">{gt text='Info'}</label>
                        <textarea name="formElement[Directpay_info]">{$directpay.info}</textarea>
                        <span style="padding-left:154px" align="justify">
                            <i style="color:grey"> 
                                {gt text='Note: Text written in this field will be presented to your customer as a selectable payment method. Here you can write something like: â€œPlease make bank transfer to our account xxxx-xxxxxxxx.We will ship your items when we register your payment.â€�'} 
                            </i>
                        </span>

                    </div>
			</div>
                </fieldset>
                {/if} 
                
                
                 <fieldset>
                    <legend id="settingheaders" title="{gt text='Click here to configure Freight settings'}">{gt text='Freight settings'}</legend>
            <div id="settingheadersdiv" style="display:none">
                    <div class="z-formrow">
                        <label for="Freight_enabled">{gt text='Enable'}</label>
                        <input type="checkbox" value="1" id="Freight_enabled" name="formElement[freight_enabled]"{if $freight.enabled eq true} checked="checked"{/if}/>
                    </div>

                    <div class="z-formrow">
                        <label for="Directpay_info">{gt text='Standard Freight Price'}</label>
                        <input type="text" name="formElement[std_freight_price]" value="{$freight.std_freight_price}">
                     </div>
                          
                    <div class="z-formrow">
                        <label for="Directpay_info">{gt text='0-Freight amount:'}</label>
                        <input type="text" name="formElement[zero_freight_price]" value="{$freight.zero_freight_price}">
                     </div>
	   </div>
                </fieldset>

                {/if}   
                <fieldset>
                     <div class="z-formrow">
                        <label for="delivery_time">{gt text='Delivery Time:'}</label>
                        <input type="text" name="formElement[delivery_time]" value="{$shop_info.delivery_time}">
                          <span style="padding-left:154px" align="justify">
                        <i style="color:grey"> 
                            {gt text='Note: If this field is not filled out standard delivery time is 3-5 business days.'} 
                        </i>
                    </span>
                     </div>
                <div class="z-formrow">
                     <label for="no_payment">{gt text='No payment'}</label>
                    <input type="checkbox" value="1" id="no_payment" name="formElement[no_payment]"{if $shop_details.no_payment eq true} checked="checked"{/if}/>
                           <span style="padding-left:154px" align="justify">
                        <i style="color:grey"> 
                            {gt text='Note: When this option is selected, your visitors will not be able to buy from your shop. It gives you the option to promote your products, however with no online purchase option.'} 
                        </i>
                    </span>
                </div>
                        </fieldset>


                <div class="z-buttons z-formbuttons">
                    {button src="button_ok.png" set="icons/extrasmall" __alt="Save" __title="Save" __text="Save" __name="action" __value="savepayments"}
                    <a href="{modurl modname="ZSELEX" type="user" func="site" shop_id=$smarty.request.shop_id}" title="{gt text="Cancel"}">{img modname=core src="button_cancel.png" set="icons/extrasmall" __alt="Cancel" __title="Cancel"} {gt text="Cancel"}</a>
                </div>

            </div>

        </fieldset>   
{/securityutil_checkpermission_block}

        <fieldset>
            <input type="hidden" name="formElement[shop_id]" value="{$smarty.request.shop_id}" />
            <legend id="settingheaders" title="{gt text='Configure Terms and condition text'}">{gt text='Terms and conditions'}</legend> 
            <div id="settingheadersdiv" style="display:none">

                <fieldset>
                    <legend>{gt text='Info'}</legend>
                    {assign var="termsConditionInfo" value=$shop_info.terms_conditions|unserialize} 
                    {foreach item='language' from=$languages}
                    <div class="z-formrow">
                        <label for="terms_conditions">{gt text='Terms of trade'}(<b>{$language}</b>)</label>
                        <textarea id="terms_conditions" name="formElement[terms_conditions][{$language}][termsoftrade]">{$termsConditionInfo.$language.termsoftrade|cleantext}</textarea>
                    </div>
                    {/foreach}
                    {foreach item='language' from=$languages}
                    <div class="z-formrow">
                        <label for="terms_conditions">{gt text='RMA'}(<b>{$language}</b>)</label>
                        <textarea id="terms_conditions" name="formElement[terms_conditions][{$language}][rma]">{$termsConditionInfo.$language.rma|cleantext}</textarea>
                    </div>
                    {/foreach}
                    {foreach item='language' from=$languages}
                    <div class="z-formrow">
                        <label for="terms_conditions">{gt text='Delivery prices'}(<b>{$language}</b>)</label>
                        <textarea id="terms_conditions" name="formElement[terms_conditions][{$language}][deliveryprices]">{$termsConditionInfo.$language.deliveryprices|cleantext}</textarea>
                    </div>
                    {/foreach}
                    {foreach item='language' from=$languages}
                    <div class="z-formrow">
                        <label for="terms_conditions">{gt text='Delivery time'}(<b>{$language}</b>)</label>
                        <textarea id="terms_conditions" name="formElement[terms_conditions][{$language}][deliverytime]">{$termsConditionInfo.$language.deliverytime|cleantext}</textarea>
                    </div>
                    {/foreach}

                    {foreach item='language' from=$languages}
                    <div class="z-formrow">
                        <label for="terms_conditions">{gt text='Privacy'}(<b>{$language}</b>)</label>
                        <textarea id="terms_conditions" name="formElement[terms_conditions][{$language}][privacy]">{$termsConditionInfo.$language.privacy|cleantext}</textarea>
                    </div>
                    {/foreach}
                    {foreach item='language' from=$languages}
                    <div class="z-formrow">
                        <label for="terms_conditions">{gt text='Secure payment'}(<b>{$language}</b>)</label>
                        <textarea id="terms_conditions" name="formElement[terms_conditions][{$language}][securepayment]">{$termsConditionInfo.$language.securepayment|cleantext}</textarea>
                    </div>
                    {/foreach}

                </fieldset>


                <div class="z-buttons z-formbuttons">
                    {button src="button_ok.png" set="icons/extrasmall" __alt="Save" __title="Save" __text="Save" __name="action" __value="termsconditions"}
                    <a href="{modurl modname="ZSELEX" type="user" func="site" shop_id=$smarty.request.shop_id}" title="{gt text="Cancel"}">{img modname=core src="button_cancel.png" set="icons/extrasmall" __alt="Cancel" __title="Cancel"} {gt text="Cancel"}</a>
                </div>

            </div>

        </fieldset> 
                
             <fieldset>
            <legend id="settingheaders" title="{gt text='Statistics'}">{gt text='Statistics'}</legend> 
            <div id="settingheadersdiv" style="display:none">
                  <div class="z-formrow">
                            <label for="purchase_setting">{gt text='Collect purchase statistics'}</label>
                            <div><input type="checkbox" value="1" id="purchase_stat" {if $shop_info.purchase_collect_stat eq 1}checked{/if}/> </div>
                 </div>
                  <div class="z-formrow">
                            <label for="email_purcahse">{gt text='Email all purchase tries'}</label>
                            <div><input type="checkbox" value="1" id="email_purchase" {if $shop_info.email_purchase_tries eq 1}checked{/if}/> </div>
                 </div>
                  <div class="z-buttons z-formbuttons">
       
       <button onClick="return saveStatistics();" id="stat_save"  type="button"  name="action" value="save" title="{gt text='Save'}">
             {img src='button_ok.png' modname='core' set='icons/extrasmall' __alt='Save' __title='Save'}
             {gt text='Save'}
       </button>
        <button style='display:none' id="stat_save_msg"  type="button"  name="action" value="save" title="{gt text='Save'}">
                {gt text='Saving...'}
       </button>
       </div>
              
              </div>
               
      </fieldset>

        <div id="show_images_ajax">   
            {* {modfunc modname=ZSELEX type=admin func=loadMiniSiteImages shop_id=$smarty.request.shop_id}*}
        </div>

        <a name="aImages"></a>
        <table class="options">
            <tbody>
                <tr>
                    <td>
                        <div id="drop_images"  class="SettingDiv">
                            {*{if $image_perm}{gt text='Drag and Drop images here'}{/if}*}
                            <div id="images_display" style="padding-left:40px">
                                {modfunc modname=ZSELEX type=admin func=loadMiniSiteImages shop_id=$smarty.request.shop_id}
                            </div>  
                        </div>
                        <div class="SettingBottomOrange">
                            <div class="OrageSettingTextL left">{gt text='Edit This Image'}</div>

                            <div style="padding-right:124px;padding-top:21px" class="OrageSettingTextR right">{gt text='Drag and Drop Image'}</div>

                        </div>
                        <div id="minisite_images" style="width:500px" class="TopAddFile"></div>

                    </td>

                </tr>
            </tbody>
        </table>
        <a name="aBanner"></a>
          <fieldset>
            <legend id="settingheaders" title="{gt text='Banner Settings'}">{gt text='Banner Settings'}</legend> 
            <div id="settingheadersdiv" style="display:none">
                  <div class="z-formrow">
                            <label for="nc">{gt text='No Change'}</label>
                            <div><input onClick="bannerSetting(this.value)" type="radio" value="0" id="banner_setting" name="formElement[banner_setting]" {if $image_mode.image_mode eq 0}checked{/if}/> {gt text="uploaded image is resized to 320 pixel in height and placed on 2048 x 320 pixel canvas."}</div>
                 </div>
                  <div class="z-formrow">
                            <label for="strech">{gt text='Stretch'}</label>
                            <div><input onClick="bannerSetting(this.value)" type="radio" value="1" id="banner_setting" name="formElement[banner_setting]" {if $image_mode.image_mode eq 1}checked{/if}/> {gt text="uploaded image is resized to 2048 pixel width and the center 320 pixel in height is shown."}</div>
                 </div>
               {* <div class="z-formrow">
                            <label for="crop">{gt text='Crop'}</label>
                            <div><input onClick="bannerSetting(this.value)" type="radio" value="2" id="banner_setting" name="formElement[banner_setting]" {if $image_mode.image_mode eq 2}checked{/if}/></div>
                 </div>*}
                          <span style="padding-left:154px" align="justify">
                        <i style="color:grey"> 
                            {gt text='Note: These settings affect uploading of banner.'} 
                        </i>
                    </span>
              </div>
                 <input type="hidden" id="image_mode"  value="{$image_mode.image_mode}">
      </fieldset>
             
      
       {*<div id="crop_label" align="right" onClick="cropImage()" style="cursor:pointer;color:blue;{if $image_mode.image_mode eq 2}display:block{/if}">Crop</div>*}
        {if $banner_perm}
       <div align="right"><input type="button" value="{gt text='Crop banner'}" class="ProductPageBtn" title="{gt text='Crop the uploaded Topbanner image.'}" onClick="cropImage()" style="cursor:pointer;"></div>
        {/if}
        <table class="options">
            <tbody>
                <tr>
                    <td>
                        <div id="drop_banner" class="SettingDiv" {if $announcement_perm} onClick="editAnnouncement()" style="cursor:pointer;" {/if}>
                             {* {if $banner_perm}{gt text='Drag and Drop banner here'}{/if}*}
                             <div id="load_banner" style="padding-left:40px;text-align:center">
                                <div {if $announcement_perm} onClick="editAnnouncement()"  {/if}>
                                    {assign var="banner_img" value=$minisite_banner.banner_image|replace:' ':'%20'}
                                    {assign var="banner_image" value="zselexdata/`$shop_id`/banner/resized/`$banner_img`"}
                                    {if is_file($banner_image)}
                                    {imageproportional image=$banner_img path="`$baseurl`zselexdata/`$shop_id`/banner/resized" height="120" width="500"}
                                    <img  {$imagedimensions} style="cursor:pointer" width="500" height="120" src="zselexdata/{$shop_id}/banner/resized/{$minisite_banner.banner_image|safetext}">
                                    {/if}
                                </div>
                            </div>
                        </div>
                        <div class="SettingBottomOrange">
                            <div class="OrageSettingTextL left">{gt text='Edit This Banner'}</div>

                            <div style="padding-right:124px;padding-top:21px" class="OrageSettingTextR right">{gt text='Drag and Drop Banner Image'}</div>

                        </div>
                        <div id="minisite_banner" style="width:500px" class="TopAddFile"></div>

                    </td>

                </tr>
            </tbody>
        </table>

        <a name="aEmployees"></a>
        <table class="options">
            <tbody>
                <tr>
                    <td>
                        <div id="drop_employee" class="SettingDiv">
                            {* {if $emp_perm}{gt text='Drag and Drop employee here'}{/if} *}
                            <div id="employee_display" style="padding-left:40px">
                                {modfunc modname=ZSELEX type=admin func=loadEmployees shop_id=$smarty.request.shop_id}
                            </div>  
                        </div>
                        <div class="SettingBottomOrange">
                            <div class="OrageSettingTextL left">{gt text='Edit This Employee'}</div>

                            <div style="padding-right:124px;padding-top:21px" class="OrageSettingTextR right">{gt text='Drag and Drop Employee Image'}</div>

                        </div>
                        <div id="employee_images" style="width:500px" class="TopAddFile"></div>

                    </td>

                </tr>
            </tbody>
        </table>

    </div>
    <div class="z-buttons z-formbuttons">
        <a href="{modurl modname="ZSELEX" type="user" func="site" shop_id=$smarty.request.shop_id}" title="{gt text="Back"}">{img modname='ZSELEX' src="icon_cp_backtoshoplist.png" __alt="Back" __title="Back"} {gt text="Back"}</a>
    </div>
</form>



<script type="text/javascript">
{{if $image_perm}}
            jQuery('#minisite_images').ajaxupload({
    url:document.location.pnbaseURL + "index.php?module=ZSELEX&type=Dnd&func=upload_images",
            allowExt:['jpg', 'JPG', 'png', 'PNG', 'gif', 'GIF'],
            data:"shop_id={{$smarty.request.shop_id}}&file_check_folder=fullsize",
            editFilename:true,
            maxFiles:{{$imagelimit}},
            dropArea:'#drop_images',
            dropColor: 'red',
            autoStart: true,
            remotePath:document.getElementById('uploadpath').value + "minisiteimages",
            //form:'#zselex_bulkaction_product_form',
            removeOnSuccess:true,
            success:function(files)
    {
    getImages();
           // jQuery('li[title="' + files + '"]').remove();
    },
            finish:function(files, filesObj){
    // window.location.href='';
         deleteExtraImages();
    },
            error:function(txt, obj){
    alert(Zikula.__('Cannot upload : ' + txt, 'module_zselex_js'));
            //alert('An error occour '+ txt);
    }

    });
            {{/if}}
            	  {{if $banner_perm}}
        jQuery('#minisite_banner').ajaxupload({
            url:document.location.pnbaseURL + "index.php?module=ZSELEX&type=Dnd&func=upload_banner",
            allowExt:['jpg', 'JPG', 'png', 'PNG', 'gif', 'GIF'],
            data:"shop_id={{$smarty.request.shop_id}}",
            editFilename:true,
            maxFiles:{{$banner_limit}},
            dropArea:'#drop_banner',
            dropColor: 'red',
            autoStart: true,
            remotePath:document.getElementById('uploadpath').value + "banner",
            //form:'#zselex_bulkaction_product_form',
            removeOnSuccess:true,
            success:function(files)
            {

              getBanner();
            //jQuery('li[title="' + files + '"]').remove();
             },
            finish:function(files, filesObj){
    // window.location.href='';
       // if(jQuery('#image_mode').val()==2){
            //alert('Crop');
            //cropImage();
      //  }
            
    },
            error:function(txt, obj){
             alert(Zikula.__('Cannot upload : ' + txt, 'module_zselex_js'));
        }

    });
        {{/if}}
        {{if $emp_perm}}
            jQuery('#employee_images').ajaxupload({
    url:document.location.pnbaseURL + "index.php?module=ZSELEX&type=Dnd&func=upload_employees",
            allowExt:['jpg', 'JPG', 'png', 'PNG', 'gif', 'GIF'],
            data:"shop_id={{$smarty.request.shop_id}}&file_check_folder=fullsize",
            editFilename:true,
            maxFiles:{{$employee_limit}},
            dropArea:'#drop_employee',
            dropColor: 'red',
            autoStart: true,
            remotePath:document.getElementById('uploadpath').value + "employees",
            //form:'#zselex_bulkaction_product_form',
            removeOnSuccess:true,
            success:function(files)
    {

    getEmployees();
            //jQuery('li[title="' + files + '"]').remove();
    },
            finish:function(files, filesObj){
            // window.location.href='';
           //alert('uploads completed!');
            deleteExtraEmployees();
    },
            error:function(txt, obj){
    alert(Zikula.__('Cannot upload : ' + txt, 'module_zselex_js'));
    }

    });
            {{/if}}
            // Put anchors into below array which should open panel #0 (the first panel)
            // ~~() converts a boolean value to an integer
            var anchors = ['#aInformation', '#aAddress', '#aOpeninghours'];
            //var anchors = ['#aInformation','#aAddress'];
            var panel = new Zikula.UI.Panels('panel', {
    headerSelector: '#settingheaders',
            headerClassName: 'z-panel-indicator'
            // active: [~~(anchors.indexOf(window.location.hash) <= -1)]

    });
            function tts(){
            var panel = new Zikula.UI.Panels('panelpop', {
            headerSelector: '#popupheader',
                    headerClassName: 'z-panel-indicator',
                    active: [0]

            });
            }
</script>


{adminfooter}


<script src="modules/ZSELEX/javascript/jquerycalendar/jquery-ui.js"></script>
<script>
    jQuery(function() {
    jQuery("#startdate").datepicker({ dateFormat: "yy-mm-dd", firstDay: '1'});
            jQuery("#enddate").datepicker({ dateFormat: "yy-mm-dd", firstDay: '1'});
            jQuery(".timepicker").timepicker({ ampm: false, seconds: false });
    });
</script>
