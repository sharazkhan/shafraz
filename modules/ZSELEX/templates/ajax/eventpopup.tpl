<form id="eventdelete_popup_form" class="z-form" action="{modurl modname="ZSELEX" type="admin" func='saveEvents' shop_id=$smarty.request.shop_id}" method="post" enctype="multipart/form-data">
 <input type="hidden" name='formElements[shop_id]' value="{$smarty.request.shop_id}" />
 <input type="hidden" name='formElements[elemId]' value="{$smarty.request.event_id}" />
 <input type="hidden" id="src" name="src" value="{if isset($smarty.request.src)}{$smarty.request.src}{else}'normal'{/if}" />
 <input type="hidden" id="deleteevent"  name="action" value='deleteevent'>
</form>
<div style="width:98%;padding-left: 4px">
    <form id="event_popup_form" class="z-form" action="{modurl modname="ZSELEX" type="admin" func='saveEvents' shop_id=$smarty.request.shop_id}" method="post" enctype="multipart/form-data">
        <div class="z-panels" id="panel">
            <input type="hidden" id="sample"  value="0" />
            <input type="hidden" name="csrftoken" value="{insert name="csrftoken"}" />
            <input type="hidden" name='formElements[shop_id]' value="{$smarty.request.shop_id}" />
            <input type="hidden" name='formElements[elemId]' value="{$smarty.request.event_id}" />
            <input type="hidden" name='formElements[selectedimage]' value="{$item.event_image}" />
            <input type="hidden" name='formElements[selecteddoc]' value="{$item.event_doc}" />
            <input type="hidden" name='src' value="{$smarty.request.src}" />

            <fieldset>
                <legend id="eventheaders">{gt text='Event'}</legend> 
                <div>
                    <div class="z-formrow">
                        <label for="name">{gt text='Event Name'}</label>
                        <input class="required" title="{gt text='Event name required'}" onBlur="setInto(this.value)" type="text" id="name" name="formElements[eventname]" value="{$item.shop_event_name|cleantext}" />
                    </div>
                    <div class="z-formrow">
                        <label for="startdate">{gt text='Start Date'}</label>
                        <input class="required" title="{gt text='Start date required'}" {if $event_id eq 'new'}  onchange="setActivationDate(this.value)" onfocus="setActivationDate(this.value)" onkeyup="setActivationDate(this.value)" onblur="setActivationDate(this.value)" {/if} autocomplete='off' type='text'  name='formElements[startdate]' id='startdate' class='startdate' value='{$item.shop_event_startdate}' />
                    </div>
                    <div class="z-formrow">
                        <label for="starttime">{gt text='Start Hour'}</label>
                        <input type='text'  name='formElements[starthour]' id='starthour' class='starthour' value='{$item.shop_event_starthour}' />
                    </div>

                    <div class="z-formrow">
                        <label for="enddate">{gt text='End Date'}</label>
                        <input class="required" title="{gt text='End date required'}" autocomplete='off' type='text'  name='formElements[enddate]' id='enddate'  class='enddate' value='{$item.shop_event_enddate}' />
                    </div>
                    <div class="z-formrow">
                        <label for="endtime">{gt text='End Hour'}</label>
                        <input type='text'  name='formElements[endhour]' id='endhour' class='endhour' value='{$item.shop_event_endhour}' />

                    </div>
                      <div class="z-formrow">
                        <label for="activation_date">{gt text='Activation Date'}</label>
                        <input autocomplete='off' type='text'  name='formElements[activation_date]' id='activation_date' class='activation_date' value='{$item.activation_date}' />
                      </div>  
                      
                     
                      <div class="z-formrow">
                        <label for="exclusive">{gt text='Exclusive'}</label>
                         <input onclick="checkImageSizes('{$item.image_height}' , '{$item.image_width}')"  {if !$exclusive_event.perm} disabled  {/if} type="checkbox" name="formElements[exclusive]" id="exclusive"  {if $item.exclusive} checked {/if} value="1">
                         <div>{if $item.exclusive && $item.image_height < '300' && $item.image_width < '900'}{gt text='Uploaded image cannot be displayed.Please upload a image with height=300 and width=900 in minimum'}{/if}</div>
                      </div> 
                     

                </div>
            </fieldset>


            <fieldset>
                <legend id="eventheaders">{gt text='Event Information'}</legend> 
                <div style="display:none">
                    <div class="z-formrow">
                        <label for="description">{gt text='Event short intro'}</label>
                        <textarea id="shortdescription" name="formElements[eventshortinto]" cols="40" rows="5" />{$item.shop_event_shortdescription|cleantext}</textarea>
                    </div>
                    <div class="z-formrow">
                        <label for="description">{gt text='Event Description'}</label>
                        <textarea id="description" name="formElements[eventdetail]" cols="40" rows="5" />{$item.shop_event_description|cleantext}</textarea>
                    </div>

                    <div class="z-formrow">
                        <label for="keywords">{gt text='Event Keywords'}</label>
                        <textarea id="keywords" name="formElements[keywords]" cols="40" rows="5" />{$item.shop_event_keywords|cleantext}</textarea>
                    </div>

                    <div class="z-formrow">
                        <label for="enddate">{gt text='Event Price'}</label>
                        <!--<input type='text'  name='formElements[price]' id='price'  class='price' value='{displayprice amount=$item.price}' />-->
                        <input type='text'  name='formElements[price]' id='price'  class='price' value='{$item.price}' />
                    </div>
                     <fieldset>
                <legend>{gt text='Event Link'}</legend> 
                    <div class="z-formrow">
                        <label for="event_link">{gt text='Link'}</label>
                        <input class="validate-url" title='{gt text='Please enter a valid URL'}' type='text'  name='formElements[event_link]' id='event_link'   value='{$item.event_link}' />
                    </div>
                    <div class="z-formrow">
                        <label for="open_new">{gt text='Open URL in new window'}</label>
                        <input type='checkbox'  name='formElements[open_new]' id='open_new' value='1' {if $item.open_new}checked{/if} />
                    </div>
                   
                     <div class="z-formrow">
                        <label for="call_link_directly2">{gt text='Open Link'}</label>
                        <select name='formElements[call_link_directly]'>
                            <option value='0'  {if $item.call_link_directly eq 0}selected{/if}>{gt text='From Event link button'}</option>
                            <option value='1' {if $item.call_link_directly eq 1}selected{/if}>{gt text='Directly when Event clicked'}</option>
                            <option value='2' {if $item.call_link_directly eq 2}selected{/if}>{gt text='From Event when image is clicked'}</option>
                        </select>
                     </div>
                    
                     </fieldset>
                </div>
            </fieldset>

            <fieldset>
                <legend id="eventheaders">{gt text='Event Contact'}</legend> 
                <div style="display:none">
                     <div class="z-formrow">
                        <label for="email">{gt text='Contact Name'}</label>
                        <input type='text'  name='formElements[contact_name]' id='contact_name'  class='contact_name' value='{$item.contact_name}' />
                    </div>
                    <div class="z-formrow">
                        <label for="email">{gt text='Email'}</label>
                        <input type='text'  name='formElements[email]' id='email'  class='email' value='{$item.email}' />
                    </div>

                    <div class="z-formrow">
                        <label for="phone">{gt text='Phone'}</label>
                        <input type='text'  name='formElements[phone]' id='phone'  class='phone' value='{$item.phone}' />
                    </div>

                    <div class="z-formrow">

                        <label for="address">{gt text='Venue'}</label>
                        <textarea id="venue" name="formElements[venue]" cols="70" rows="10" />{$item.shop_event_venue}</textarea>
                    </div>
                </div>
            </fieldset>



            <fieldset>
                <legend id="eventheaders">{gt text='Event Files'}</legend> 
                <div style="display:none">
                    <div  class="z-formrow">
                        <label for="article">{gt text='Show from'}</label>
                        <div>
                            <input {if $article_service.expired} disabled {/if}  type="radio"  name="formElements[showfrom]"{if $item.showfrom eq 'article'} checked="checked" {/if} value="article" onclick='showmsg(this.value)'> {gt text='Article'}
                            <input {if $product_service.expired} disabled {/if} type="radio" name="formElements[showfrom]"{if $item.showfrom eq 'product'} checked="checked" {/if} value="product" onclick='showmsg(this.value)'> {gt text='Product'}
                            <input {if $image_service.expired} disabled {/if} type="radio" name="formElements[showfrom]" value="image" {if $item.showfrom eq 'image'} checked="checked" {/if} onclick='showmsg(this.value)'>  {gt text='Image'} 
                            <input {if $image_service.expired} disabled {/if} type="radio" name="formElements[showfrom]" value="doc"  {if $item.showfrom eq 'doc'} checked="checked" {/if} onclick='showmsg(this.value)'>  {gt text='Doc'}
                        </div>
                    </div>



                    <div id="adprices"  class="z-formrow">
                        <label for="article">{gt text='Select Product'}</label>
                        <select {if $product_service.expired} disabled {/if} name='formElements[product]' id='article' class="icon-menu" {$disable_addproduct}>
                            <option style="padding-left:0px;" value="">{gt text='Select Product'}</option>
                            {foreach item='product' from=$products}
                                <option  value="{$product.product_id}" {if $item.product_id eq $product.product_id} selected='selected' {/if}> {$product.product_name} </option>
                            {/foreach}
                        </select>
                    </div>
                    <div id="adprices"  class="z-formrow">
                        <label for="article">{gt text='Select Article'}</label>
                        <select {if $article_service.expired} disabled {/if} name='formElements[article]' id='article' class="icon-menu" {$disable_article}>
                            <option style="padding-left:0px;" value="">{gt text='Select Article'}</option>
                            {foreach item='article' from=$articles}
                                <option  value="{$article.sid}" {if $item.news_article_id eq $article.sid} selected='selected' {/if}> {$article.title} </option>
                            {/foreach}
                        </select>


                    </div>

                    <div  class="z-formrow">
                        <label for=""></label>
                        <div id="sarticle" style="display:none">
                            <font color="green"> <b>   {gt text='Your selected article will be displayed in the Event'}</b> </font>
                        </div>
                        <div id="simage" style="display:none">
                            <font color="green"> <b>  {gt text='Your uploaded Image will be displayed in the Event'}</b> </font>
                        </div>
                        <div id="sdoc" style="display:none">
                            <font color="green"> <b>{gt text='Your uploaded Document will be displayed in the Event'}</b> </font>
                        </div>
                        <div id="sprod" style="display:none">
                            <font color="green"> <b>{gt text='Your selected product will be displayed in the Event'}</b> </font>
                        </div>
                    </div>
                </div>
            </fieldset>

            <fieldset>
                <div class="z-formrow">
                    <label for="status">{gt text='Event Status'}</label>
                    <select id="status" name="formElements[status]" />
                    <option value="1" {if $item.status eq '1'} selected='selected' {/if}>{gt text='Active'}</option>
                    <option value="0" {if $item.status eq '0'} selected='selected' {/if}>{gt text='InActive'}</option>
                    </select>
                </div>
            </fieldset>

            <div class="z-buttons z-formbuttons">
                <button id="zselex_button_submit" onClick="validateEvent()"  class="z-btgreen" type="button"  name="action" value="saveevents" title="{gt text='Save Event'}">
                    {img src='button_ok.png' modname='core' set='icons/extrasmall' __alt='Save' __title='Save Event'}
                    {gt text='Save'}
                </button>
                <input type="hidden" id="event_action" name="action" value="">
                  {if $smarty.request.src eq 'view'} 
                  <a href="{modurl modname="ZSELEX" type="user" func="viewevent" shop_id=$smarty.request.shop_id eventId=$smarty.request.event_id}"  title="{gt text="Cancel"}">{img modname=core src="button_cancel.png" set="icons/extrasmall" __alt="Cancel" __title="Cancel"} {gt text="Cancel"}</a>
                      {else}
                <a href="javascript:closeWindow()"  title="{gt text="Cancel"}">{img modname=core src="button_cancel.png" set="icons/extrasmall" __alt="Cancel" __title="Cancel"} {gt text="Cancel"}</a>
                {/if}
                {if $event_id neq 'new'}
                {*{button src="14_layer_deletelayer.png" set="icons/extrasmall" __alt="Delete Event" __title="Delete Event" __text="Delete Event" __name="action" __value="deleteevent"}*}
                 <button onClick="return deleteEvent();" id=event_delete"  type="button"  name="action" value="deleteevent" title="{gt text='Delete Event'}">
             {img src='14_layer_deletelayer.png' modname='core' set='icons/extrasmall' __alt='Delete Event' __title='Delete Event'}
             {gt text='Delete Event'}
               </button>
                {/if}
            </div>
        </div>
    </form>
</div>
            
            <style>
            .validation-advice{
               margin-left:130px;
                }
            </style>
