{shopheader}
{ajaxheader imageviewer="true"}

{pageaddvar name='javascript' value='modules/ZSELEX/javascript/multifile.js'}
{pageaddvar name='javascript' value='modules/ZSELEX/javascript/multifiledocs.js'}
<script src="modules/ZSELEX/javascript/jquerycalendar/jquery-ui.js"></script>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<script>
function setInto(val){
         var shortIntro  = jQuery('#shortdescription').val();
         var eventDescription  = jQuery('#description').val();
         if(shortIntro == ''){
        jQuery('#shortdescription').val(val);
        }
         if(eventDescription == ''){
        jQuery('#description').val(val);
        }
       
    }
    </script>

<div class="z-admin-content-pagetitle">
    {if $smarty.request.eventId eq ''}
    <h3>{gt text='Create an event'}</h3>
    {else}
    <h3>{gt text='Modify event'}</h3>  
    {/if}    
</div>

<form class="z-form" action="{modurl modname="ZSELEX" type="admin" func=$function shop_id=$smarty.request.shop_id}" method="post" enctype="multipart/form-data">
      <div class="z-panels" id="panel">
        <input type="hidden" name="csrftoken" value="{insert name="csrftoken"}" />
        <input type="hidden" name='formElements[shop_id]' value="{$smarty.request.shop_id}" />
        <input type="hidden" name='formElements[elemId]' value="{$smarty.request.eventId}" />
        <input type="hidden" name='formElements[selectedimage]' value="{$item.event_image}" />
        <input type="hidden" name='formElements[selecteddoc]' value="{$item.event_doc}" />


        <fieldset>
            <legend id="eventheaders">{gt text='Event'}</legend> 
            <div>
                <div class="z-formrow">
                    <label for="name">{gt text='Event Name'}</label>
                    <input onBlur="setInto(this.value)" type="text" id="name" name="formElements[eventname]" value="{$item.shop_event_name}" />
                </div>
                <div class="z-formrow">
                    <label for="startdate">{gt text='Start Date'}</label>
                    <input type='text'  name='formElements[startdate]' id='startdate' class='startdate' value='{$item.shop_event_startdate}' />
                </div>
                <div class="z-formrow">
                    <label for="starttime">{gt text='Start Time'}</label>
                    {* <input type='text'  name='formElements[starttime]' id='starttime'  value='{$item.starttime}' /> *}
                    {* {$starttime} *}
                    <div>
                        {gt text='Hour:'} 
                        <select id="hour" name="formElements[starthour]" />
                        <option value="">{gt text='Select'}</option>
                        {section name=foo start=0 loop=25} 
                        <option  {if $item.shop_event_starthour eq $smarty.section.foo.index} selected='selected' {/if}>{$smarty.section.foo.index} </option>
                        {/section} 
                        </select>
                    </div> 
                    <label for=""></label> 
                    <div>
                        {gt text='Minute:'} 
                        <select id="hour" name="formElements[startminute]" />
                        <option value="">{gt text='Select'}</option>
                        {section name=foo start=0 loop=61 step=5} 
                        <option  {if $item.shop_event_startminute eq $smarty.section.foo.index} selected='selected' {/if}>{$smarty.section.foo.index} </option>
                        {/section} 
                        </select>
                    </div>  
                </div>
                <div class="z-formrow">
                    <label for="enddate">{gt text='End Date'}</label>
                    <input type='text'  name='formElements[enddate]' id='enddate'  class='enddate' value='{$item.shop_event_enddate}' />
                </div>
                <div class="z-formrow">
                    <label for="endtime">{gt text='End Time'}</label>
                    {* <input type='text'  name='formElements[endtime]' id='endtime'  value='{$item.endtime}' />*}
                    {* {$endtime} *}
                    <div>
                        {gt text='Hour:'} 
                        <select id="hour" name="formElements[endhour]" />
                        <option value="">{gt text='Select'}</option>
                        {section name=foo start=0 loop=25} 
                        <option {if $item.shop_event_endhour eq $smarty.section.foo.index} selected='selected' {/if}>{$smarty.section.foo.index} </option>
                        {/section} 
                        </select>
                    </div> 
                    <label for=""></label> 
                    <div>
                        {gt text='Minute:'} 
                        <select id="hour" name="formElements[endminute]" />
                        <option value="">{gt text='Select'}</option>
                        {section name=foo start=0 loop=61 step=5} 
                        <option {if $item.shop_event_endminute eq $smarty.section.foo.index} selected='selected' {/if}>{$smarty.section.foo.index} </option>
                        {/section} 
                        </select>
                    </div>  
                </div>
            </div>
        </fieldset>


        <fieldset>
            <legend id="eventheaders">{gt text='Event Information'}</legend> 
            <div style="display:none">
                <div class="z-formrow">
                    <label for="description">{gt text='Event short intro'}</label>
                    <textarea id="shortdescription" name="formElements[eventshortinto]" cols="70" rows="10" />{$item.shop_event_shortdescription}</textarea>
                </div>
                <div class="z-formrow">
                    <label for="description">{gt text='Event Description'}</label>
                    <textarea id="description" name="formElements[eventdetail]" cols="70" rows="10" />{$item.shop_event_description}</textarea>
                </div>

                <div class="z-formrow">
                    <label for="keywords">{gt text='Event Keywords'}</label>
                    <textarea id="keywords" name="formElements[keywords]" cols="70" rows="10" />{$item.shop_event_keywords}</textarea>
                </div>

                <div class="z-formrow">
                    <label for="enddate">{gt text='Event Price'}</label>
                    <input type='text'  name='formElements[price]' id='price'  class='price' value='{$item.price}' />
                </div>
            </div>
        </fieldset>

        <fieldset>
            <legend id="eventheaders">{gt text='Event Contact'}</legend> 
            <div style="display:none">
                <div class="z-formrow">
                    <label for="email">{gt text='Email'}</label>
                    <input type='text'  name='formElements[email]' id='email'  class='email' value='{if $item.email neq ''}{$item.email}{else}{$shop_item.shop_email}{/if}' />
                </div>

                <div class="z-formrow">
                    <label for="phone">{gt text='Phone'}</label>
                    <input type='text'  name='formElements[phone]' id='phone'  class='phone' value='{if $item.phone neq ''}{$item.phone}{else}{$shop_item.shop_phone}{/if}' />
                </div>

                <div class="z-formrow">

                    <label for="address">{gt text='Event Venue'}</label>
                    <textarea id="venue" name="formElements[venue]" cols="70" rows="10" />{if $item.shop_event_venue neq ''}{$item.shop_event_venue}{else}{$shop_item.shop_address}{/if}</textarea>
                </div>
            </div>
        </fieldset>



        <fieldset>
            <legend id="eventheaders">{gt text='Event Files'}</legend> 
            <div style="display:none">
                <div  class="z-formrow">
                    <label for="article">{gt text='Show from'}</label>
                    <div>
                        <input {$disable_article}  type="radio" name="formElements[showfrom]"{if $item.showfrom eq 'article'} checked="checked" {/if} value="article" onclick='showmsg(this.value)'> {gt text='Article'}
                            <input {$disable_addproduct}  type="radio" name="formElements[showfrom]"{if $item.showfrom eq 'product'} checked="checked" {/if} value="product" onclick='showmsg(this.value)'> {gt text='Product'}
                            <input {$disable_image} type="radio" name="formElements[showfrom]" value="image" {if $item.showfrom eq 'image'} checked="checked" {/if} onclick='showmsg(this.value)'>  {gt text='Image'}
                            <input {$disable_image} type="radio" name="formElements[showfrom]" value="doc"  {if $item.showfrom eq 'doc'} checked="checked" {/if} onclick='showmsg(this.value)'>  {gt text='Doc'}
                    </div>
                </div>

                <div  class="z-formrow">
                    <label for="article">{if $item.shop_event_id neq ''}{gt text='Change Image'}{else}{gt text='Upload Image'}{/if}</label>
                    <input id="news_files_element" name="news_files" type="file" {$disable_image}>
                </div>
                {if $item.shop_event_id neq ''}
                {assign var="image" value="zselexdata/`$ownerName`/events/thumb/`$item.event_image`"}
                {if is_file($image)}
                <div class="z-formrow">
                    <label for=""><b> {gt text='Selected Image:'}</b></label>
                    <div>
                        <a id="{$item.shop_event_id}" rel="imageviewer[gallery]" title="{$item.event_image}" href="{$baseurl}zselexdata/{$ownerName}/events/fullsize/{$item.event_image}">
                            <img src="{$baseurl}zselexdata/{$ownerName}/events/thumb/{$item.event_image}"></a>
                    </div>
                </div>
                {/if}
                {/if}     

                <div  class="z-formrow">
                    <label for="article"> {if $item.shop_event_id neq ''}{gt text='Change Document'}{else}{gt text='Upload Document'}{/if}</label>
                    <input id="news_files_element" name="event_docs" type="file" {$disabled_status}>
                </div>
                {if $item.shop_event_id neq ''}
                {assign var="image" value="zselexdata/`$ownerName`/events/thumb/`$item.event_doc`"}
                {if is_file($image)}
                <div class="z-formrow">
                    <label for=""><b> {gt text='Selected Doc:'} </b></label>
                    <div>
                        {$item.event_doc}
                    </div>
                </div>
                {/if}
                {/if}  


                <div id="adprices"  class="z-formrow">
                    <label for="article">{gt text='Select Product'}</label>
                    <select name='formElements[product]' id='article' class="icon-menu" {$disable_addproduct}>
                        <option style="padding-left:0px;" value="">{gt text='Select Product'}</option>
                        {foreach item='product' from=$products}
                        <option  value="{$product.product_id}" {if $item.product_id eq $product.product_id} selected='selected' {/if}> {$product.product_name} </option>
                        {/foreach}
                    </select>
                </div>
                <div id="adprices"  class="z-formrow">
                    <label for="article">{gt text='Select Article'}</label>
                    <select name='formElements[article]' id='article' class="icon-menu" {$disable_article}>
                        <option style="padding-left:0px;" value="">{gt text='Select Article'}</option>
                        {foreach item='article' from=$articles}
                        <option  value="{$article.sid}" {if $item.news_article_id eq $article.sid} selected='selected' {/if}> {$article.title} </option>
                        {/foreach}
                    </select>

                    {* 
                    <label for=""></label>
                    {gt text='OR'} <br>
                    <label for=""></label>
                    {if $item.shop_event_id neq ''}
                    <a href='{modurl modname="ZSELEX" type="user" func="newitemevent" shop_id=$smarty.request.shop_id mid=$smarty.request.eventId}'>{gt text='Create new Article and select'}</a>
                    {else}
                    <a href='{modurl modname="ZSELEX" type="user" func="newitemevent" shop_id=$smarty.request.shop_id}'>{gt text='Create new Article and select'}</a>      
                    {/if}  
                    *}

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



        {*          
        <fieldset>
            <legend>{gt text='Additional Files'}</legend>
            <div class="z-formrow">
                <label for="news_files_element">{gt text='Select a file'}</label>
                <input id="news_files_element" name="news_files" type="file" ><br>
                <span style="padding-left:300px" class="z-sub">{gt text='(You can upload mutiple files here)' tag1=$modvars.News.picupload_maxpictures}</span>
                <div style="padding-left:300px" id="news_files_list"></div>
                <script type="text/javascript">
                    // <![CDATA[
                    var multi_selector = new MultiSelector(document.getElementById('news_files_list'), 30, 0);
                    multi_selector.addElement(document.getElementById('news_files_element'));
                    // ]]>
                </script>
            </div>
        </fieldset> 

        <fieldset>
            <legend>{gt text='Additional Documents'}</legend>
            <div class="z-formrow">
                <label for="event_files_docs">{gt text='Select a doc'}</label>
                <input id="event_files_docs" name="event_docs" type="file" ><br>
                <span style="padding-left:300px" class="z-sub">{gt text='(You can upload mutiple docements here)' tag1=$modvars.News.picupload_maxpictures}</span>
                <div style="padding-left:300px" id="event_files_doclist"></div>
                <script type="text/javascript">
                    // <![CDATA[
                    var multi_selector_docs = new MultiSelectorDocs(document.getElementById('event_files_doclist'), 3, 0);
                    multi_selector_docs.addElement(document.getElementById('event_files_docs'));
                    // ]]>
                </script>
            </div>

        </fieldset>        
        *}

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
            {button src="button_ok.png" set="icons/extrasmall" __alt="Save" __title="Save" __text="Save"}
            <a href="{modurl modname="ZSELEX" type="admin" func='viewshopevent' shop_id=$smarty.request.shop_id}" title="{gt text="Cancel"}">{img modname=core src="button_cancel.png" set="icons/extrasmall" __alt="Cancel" __title="Cancel"} {gt text="Cancel"}</a>
        </div>
    </div>
</form>



<script>

    function showmsg(val){

        //alert(val);
        if(val=='article') {
            document.getElementById("sarticle").style.display="block";
            document.getElementById("simage").style.display="none";
            document.getElementById("sdoc").style.display="none";
            document.getElementById("sprod").style.display="none";
        }
        else if(val=='image'){
            document.getElementById("sarticle").style.display="none";
            document.getElementById("simage").style.display="block";
            document.getElementById("sdoc").style.display="none";
            document.getElementById("sprod").style.display="none";

        }
        else if(val=='doc'){
            document.getElementById("sarticle").style.display="none";
            document.getElementById("simage").style.display="none";
            document.getElementById("sprod").style.display="none";
            document.getElementById("sdoc").style.display="block";
    
        }
        else if(val=='product'){
            document.getElementById("sarticle").style.display="none";
            document.getElementById("simage").style.display="none";
            document.getElementById("sdoc").style.display="none";
            document.getElementById("sprod").style.display="block";

        }

    }



  
    jQuery(function() {
        jQuery( "#startdate" ).datepicker({ dateFormat: "yy-mm-dd" });
        jQuery( "#enddate" ).datepicker({ dateFormat: "yy-mm-dd" });
  
    });
    
    var panel = new Zikula.UI.Panels('panel', {
        headerSelector: '#eventheaders',
        headerClassName: 'z-panel-indicator',
        active: [0]
      
    });


</script>


</script>
{adminfooter}