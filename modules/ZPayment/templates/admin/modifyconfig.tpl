{adminheader}
<div class="z-admin-content-pagetitle">
    {icon type="config" size="small"}
    <h3>{gt text='Module settings'}</h3>
</div>

<form class="z-form" action="{modurl modname="ZPayment" type="admin" func="updateconfig"}" method="post" enctype="application/x-www-form-urlencoded">
    <div>
    <input type="hidden" name="csrftoken" value="{insert name="csrftoken"}" />
    <fieldset>
        <legend>{gt text='General settings'}</legend>
       {* <div class="z-formrow">
			<label for="showAdminZPayment">{gt text='Display Admin Hello World'}</label>
			<input type="checkbox" value="1" id="showAdminZPayment" name="showAdminZPayment"{if $modvars.ZPayment.showAdminZPayment eq true} checked="checked"{/if}/>
        </div> *}
        <div class="z-formrow">
			<label for="Netaxept_enabled_general">{gt text='Enable Netaxept in general'}</label>
			<input type="checkbox" value="1" id="Netaxept_enabled_general" name="Netaxept_enabled_general"{if $modvars.ZPayment.Netaxept_enabled_general eq true} checked="checked"{/if}/>
        </div>
        <div class="z-formrow">
			<label for="Paypal_enabled_general">{gt text='Enable Paypal in general'}</label>
			<input type="checkbox" value="1" id="Paypal_enabled_general" name="Paypal_enabled_general"{if $modvars.ZPayment.Paypal_enabled_general eq true} checked="checked"{/if}/>
       </div>
       <div class="z-formrow">
			<label for="QuickPay_enabled_general">{gt text='Enable QuickPay in general'}</label>
			<input type="checkbox" value="1" id="QuickPay_enabled_general" name="QuickPay_enabled_general"{if $modvars.ZPayment.QuickPay_enabled_general eq true} checked="checked"{/if}/>
       </div>
       <div class="z-formrow">
			<label for="Epay_enabled_general">{gt text='Enable Epay in general'}</label>
			<input type="checkbox" value="1" id="Epay_enabled_general" name="Epay_enabled_general"{if $modvars.ZPayment.Epay_enabled_general eq true} checked="checked"{/if}/>
       </div>
       <div class="z-formrow">
			<label for="Directpay_enabled_general">{gt text='Enable Direct pay in general'}</label>
			<input type="checkbox" value="1" id="Directpay_enabled_general" name="Directpay_enabled_general"{if $modvars.ZPayment.Directpay_enabled_general eq true} checked="checked"{/if}/>
       </div>
    </fieldset>
       
     <fieldset>
                    <legend>{gt text='Cards/Payments Accepted'}</legend>
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
                                 <input {if $CardsAccepted.MastercardDebet eq 'Mastercard-Debet|3d-mastercard-debet-dk.png'}checked{/if} id="MastercardDebet" type="checkbox" name="CardsAccepted[MastercardDebet]" value="Mastercard-Debet|3d-mastercard-debet-dk.png">
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
                                <input {if $CardsAccepted.jcb eq 'JCB|jcb.png'}checked{/if} id="jcb" type="checkbox" name="CardsAccepted[jcb]" value="JCB|jcb.png">
                       <label for="jcb"><img class="paycard" src="modules/ZSELEX/images/CreditCards/jcb.png">{gt text="JCB"}</label>
                            </td>
                             <td>
                                 <input {if $CardsAccepted.americanexpress eq 'American Express|american-express.png'}checked{/if} id="americanexpress" type="checkbox" name="CardsAccepted[americanexpress]" value="American Express|american-express.png">
                        <label for="americanexpress"><img class="paycard" src="modules/ZSELEX/images/CreditCards/american-express.png">{gt text="American Express"}</label>
                            </td>
                            
                        </tr>
                    </table>

                </fieldset>
   
    <div class="z-buttons z-formbuttons">
        {button src="button_ok.png" set="icons/extrasmall" __alt="Save" __title="Save" __text="Save"}
        <a href="{modurl modname="ZPayment" type="admin" func='payments'}" title="{gt text="Cancel"}">{img modname=core src="button_cancel.png" set="icons/extrasmall" __alt="Cancel" __title="Cancel"} {gt text="Cancel"}</a>
    </div>
    </div>
</form>
{adminfooter}