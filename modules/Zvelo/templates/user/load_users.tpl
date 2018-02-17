<style>
.autocomplete-suggestions { border: 1px solid #999; background: #FFF; cursor: default; overflow: auto; -webkit-box-shadow: 1px 4px 3px rgba(50, 50, 50, 0.64); -moz-box-shadow: 1px 4px 3px rgba(50, 50, 50, 0.64); box-shadow: 1px 4px 3px rgba(50, 50, 50, 0.64); }
.autocomplete-suggestion { padding: 2px 5px; white-space: nowrap; overflow: hidden; }
.autocomplete-suggestions {z-index: 99999}
.autocomplete-no-suggestion { padding: 2px 5px;}
.autocomplete-selected { background: #F0F0F0; }
.autocomplete-suggestions strong { font-weight: normal; color: #3399FF; }

.AutoInput input { font-size: 28px; padding: 10px; border: 1px solid #CCC; display: block; margin: 20px 0; }
</style>
<script>
        jQuery(function() {
             // alert('hii');
            
             jQuery('#autocomplete-ajax').autocomplete({
             // serviceUrl: '/autosuggest/service/url',
             serviceUrl: "index.php?module=zvelo&type=user&func=getUsers",
             // lookup: countriesArray,
             lookupFilter: function(suggestion, originalQuery, queryLowerCase) {
             //alert(originalQuery);
             var re = new RegExp('\\b' + jQuery.Autocomplete.utils.escapeRegExChars(queryLowerCase), 'gi');
             return re.test(suggestion.value);
             },
             onSelect: function(suggestion) {
            //  alert(suggestion);
            // jQuery('#selction-ajax').html('You selecteds: ' + suggestion.value + ', ' + suggestion.data);
             jQuery('#customer').val(suggestion.data);
             },
             onHint: function(hint) {
             jQuery('#autocomplete-ajax-x').val(hint);
             },
             onInvalidateSelection: function() {
            // jQuery('#selction-ajax').html('You selected: none');
             }
             });
             
             
              jQuery( ".z-window-close" ).click(function() {
                    //alert( "Handler for .click() called." );
                   // jQuery(".autocomplete-suggestion").css('display' , 'none');
                  // jQuery(".autocomplete-suggestion").remove(".autocomplete-suggestion");
                    });
        
        });
       
    </script>
<form class="z-form" action="{modurl modname="Zvelo" type="user" func="loadUsers"}" method="post" enctype="application/x-www-form-urlencoded">
     <input type="hidden" name="customer" id="customer">
    <fieldset>
         
               <div class="z-formrow">
                    <label for="level">{gt text='Select User'}</label>
                   {* <select  name='customer' id='customer' >
                        <option value=''>{gt text='Select User...'}</option>
                            {foreach from=$customers  item='customer'}
                            <option value="{$customer.customer_id}" >{$customer.first_name|upper}&nbsp;{$customer.last_name|upper}</option>
                            {/foreach}
                    </select>*}
                    
            <div style="position: relative; height: 30px;" class="AutoInput">
                <input type="text" name="country" id="autocomplete-ajax" style="position: absolute; z-index: 2; background: transparent;"/>
                <input type="text" name="country" id="autocomplete-ajax-x" disabled="disabled" style="color: #CCC; position: absolute; background: transparent; z-index: 1;"/>
            </div>
            <div id="selction-ajax"></div>
            </div>
                    
        <div class="z-buttons z-formbuttons">
            <button id="zselex_button_submit"  class="z-btgreen" type="submit" name="action" value="1" title="{gt text='Save this region'}">
                {img src='button_ok.png' modname='core' set='icons/extrasmall' __alt='Load' __title='Load'}
                {gt text='Load'}
            </button>
            {*<a id="zselex_button_cancel" href="#" onClick="return closePopup();"  class="z-btred">{img modname='core' src='button_cancel.png' set='icons/extrasmall' __alt='Cancel' __title='Cancel'} {gt text='Cancel'}</a>*}
        </div>
         </fieldset>



</form>