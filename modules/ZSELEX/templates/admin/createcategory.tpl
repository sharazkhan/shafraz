{pageaddvar name='javascript' value='jquery'}

<link href="modules/ZSELEX/style/combo/sexy-combo.css" rel="stylesheet" type="text/css" />
<link href="modules/ZSELEX/style/combo/sexy/sexy.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="modules/ZSELEX/javascript/combo/jquery.sexy-combo.js"></script>

<input type='hidden' id='temps' name='temps' value=''>
{adminheader}


<script type="text/javascript" >
 jQuery(function () {
 
  jQuery("#category-combo").ZselexCombo({
                  emptyText: "Choose a category..."
                  //autoFill: true
                  //triggerSelected: true
                });
 
   }); 
</script>
 <style>
        
        #ajax-container input[type="text"],#ajax-container textarea {
         padding: 0.09em;
         }

          #ajax-container ul {
              margin:0px;
          }
          .AutoFIll textarea{ padding:0px;}

.AutoFIll input[type="text"],.AutoFIll textarea {
    padding: 0.09em;
}
.AutoFIll label {
    color: #333333;
    display: block;
    float: left;
    font-weight: normal;
    padding: 0.3em 1% 0.3em 0;
    text-align: right;
    width: 100%;
}

 
div.sexy {    margin: 0 0 0 0}

        </style>
    <div class="z-admin-content-pagetitle">
     {if $item.category_id neq ''}
        <h3>{gt text='Update Category'}</h3>
        {else}
    	<h3>{gt text='Create Category'}</h3>
        {/if}

    </div>
    
    <form class="z-form" action="{modurl modname="ZSELEX" type="admin" func="submitcategory"}" method="post" enctype="application/x-www-form-urlencoded">
        <div>
            <input type="hidden" name="csrftoken" value="{insert name="csrftoken"}" />
            <input type="hidden" name='formElements[childType]' value="CATEGORY" />
            <input type="hidden" name='formElements[elemId]' value="{$item.category_id}" />
            
            <input type="hidden" name='formElements[parentcategory]' id="parentcategory" value="{$item.parentcategory_id}" />
            <fieldset>
            <div class="z-formrow">
                <label for="elemtName">{gt text='Name'}</label>
                <input type='text'  name='formElements[elemtName]' id='elemtName' value='{$item.category_name}'   />
            </div>
            
             <div class="z-formrow">
                <label for="elemtDesc">{gt text='Description'}</label>
                <textarea  name='formElements[elemtDesc]' id='elemtDesc' >{$item.description}</textarea>
            </div>
           {*
            <div class="z-formrow">
                <label for="parentCategory">{gt text='Category'}</label>
               <div>
                    <input name="formElements[parentcategory_list]" type="text" id="parentcategory_list" value="{$item.parentcategory}" size="30" maxlength="1000" onfocus="autoSuggestCategory(this.id, 'listWrap2', 'searchList2', 'parentcategory_list', event);"  onkeyup="autoSuggestCategory(this.id, 'listWrap2', 'searchList2', 'parentcategory_list', event);" onkeydown="keyBoardNav(event, this.id);" onblur="outlist('listWrap2')" /> 
                </div>
                <div class="listWrap" id="listWrap2" >
                    <ul class="searchList" id="searchList2">
                    </ul>
                </div>
            </div>
            *}
                
            <div class="AutoFIll" style="height:45px; margin-top: 8px ">
                <div style="width:28%; float: left;"><label for="category-combo">{gt text='Categories'}</label></div>
                <div id="ajax-container" style="float:left; padding-left: 10px">
                    <select id="category-combo" name="formElements[category-combo]" size="1">
                    <option value=''>search</option>
                        {foreach from=$categories  item='category'}
                        <option value="{$category.category_id}"  {if $item.parent_cat_id eq $category.category_id} selected='selected' {/if}>{$category.category_name|upper}</option>
                        {/foreach}
                    </select>
                </div>
                
            </div>
           
            <div class="z-formrow">
                <label for="typestatus">{gt text='Status'}</label>
                <select id="typestatus" name="formElements[status]" />
                     <option value="1" {if $item.status eq '1'} selected='selected' {/if}>Active</option>
                    <option value="0" {if $item.status eq '0'} selected='selected' {/if}>InActive</option>
                </select>
            </div>
            <div class="z-buttons z-formbuttons">
             <button id="zselex_button_submit"  class="z-btgreen" type="submit" onclick="return validate_category();" name="action" value="1" title="{gt text='Save this region'}">
            {img src='button_ok.png' modname='core' set='icons/extrasmall' __alt='Save' __title='Save this Region' }
             {gt text='Save'}
            </button>
            <a id="zselex_button_cancel" href="{modurl modname="ZSELEX" type="admin" func='viewcategory'}" class="z-btred">{img modname='core' src='button_cancel.png' set='icons/extrasmall' __alt='Cancel' __title='Cancel'} {gt text='Cancel'}</a>
            </div>
        </div>
	</form>
    <div>

</div>

<script type="text/javascript">
	var options = {
		script:document.location.pnbaseURL+"/index.php?module=ZSELEX&type=ajax&func=admin_category_autocomplete&",
		varname:"input",
		json:true,
		callback: function (obj) { 
		document.getElementById('parentcategory').value = obj.id;
		 }
	};
	var as_json = new AutoSuggest('parentcategory_list', options);
	
	
</script>
{adminfooter}

