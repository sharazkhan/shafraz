<div class="container-fluid" style="margin-top: 20px;">
    <div class="row">
        <div class="col-lg-12">
            <h4>{$title}</h4>
{assign var="termsConditionInfo" value=$modvars.ZSELEX.termsConditionInfo|unserialize}
{if $shop_id > 0}
{assign var="shop_termsInfo" value=$shop_details.terms_conditions|unserialize}
{/if}
{if $termsConditionInfo.$thislang.$key neq ''}
{$termsConditionInfo.$thislang.$key|nl2br}
{/if}
{if $termsConditionInfo.$thislang.$key neq '' AND $shop_id > 0 AND $shop_termsInfo.$thislang.$key neq ''}
    <br><br><hr>
{/if} 
 {if $shop_id > 0 AND $shop_termsInfo.$thislang.$key neq ''} 
   {if $termsConditionInfo.$thislang.$key neq ''}<br>{/if}
     
   <b>{$shop_name}</b>
      <p> 
     {assign var="shop_termsInfo" value=$shop_details.terms_conditions|unserialize}
     {$shop_termsInfo.$thislang.$key|nl2br} 
     </p>
    
{/if}  
        </div>
    </div>
</div>