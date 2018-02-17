
{pageaddvar name='stylesheet' value="$themepath/style/breadcrum.css?v=1.1"}
<section class="search-wrap">
    <div class="container">
        <div class="form-inline clearfix">
            <div class="mobi-controls">
                <div class="region-select inline-select" id='region-div'>
                    <select id="region-combo" class="chosen-select-search form-control">
                        <option value="">{gt text="search region"}</option>
                        {foreach from=$regions  item='region'}
                        <option value="{$region.region_id}" {if $smarty.cookies.region_cookie eq $region.region_id} selected="selected" {/if}>{$region.region_name}</option>
                        {/foreach}
                    </select>
                </div>
                <div class="city-select inline-select" id="city-div">
                    <select id="city-combo1" class="chosen-select-search form-control">
                        <option value="">{gt text="search city"}</option>
                        {foreach from=$cities  item='city'}
                        <option value="{$city.city_id}" {if $smarty.cookies.city_cookie eq $city.city_id} selected="selected" {/if}>{$city.city_name}</option>
                        {/foreach}
                    </select>
                </div>
                <div class="category-select inline-select" id="cat-div">
                    <select id="cat-combo" class="chosen-select-search form-control">
                        <option value="">{gt text="search category"}</option>
                        {foreach from=$category  item='cat'}
                        <option value="{$cat.category_id}" {if $smarty.cookies.category_cookie eq $cat.category_id} selected="selected" {/if}>{$cat.category_name}</option>
                        {/foreach}

                    </select>
                </div>
                {*<input id="searchfield" type="text" value="{if $search_cookie neq ''}{$search_cookie|cleantext}{else}{gt text='search for...'}{/if}" class="form-control"  onkeyup="searchvalue(this.value)" onblur="if (this.value == '') this.value = '{gt text='search for ...'}';" onfocus="if (this.value == '{gt text='search for ...'}') this.value = '';">*}
            <input id="searchfield" type="text" value="{if $search_cookie neq ''}{$search_cookie|cleantext}{/if}" placeholder='{$search_place_holder}'  class="form-control"  onkeyup="searchvalue(this.value)" >
            </div>

            <button class="btn btn-primary search-btn" onclick="document.forms['shopform'].submit();">{gt text="Show shops"} <i class="fa fa-angle-double-right"></i></button>
            <span class="btn btn-default reset-btn" onClick='resets();'><i class="fa fa-times"></i>{gt text='reset'}</span>
            <a href="{modurl modname='ZSELEX' type='user' func='cart'}" class="desk-cart">
                <span class="btn btn-default shopping-bag"><span class="carts_total" id="carts_total">{$cartCount}<span class="dkk"> DKK</span></span> <i class="fa fa-shopping-bag"></i></span>
            </a>
        </div>
             <!-- Row -->
             <div id="BreadCrumHead">
                <ul class="search-breadcrumb BrudcomeTree">
                  {* <li><a href="#">Fredericia</a><i class="fa fa-times remove-icon"></i></li> 
                   <li><a href="#">Autoudstyr</a><i class="fa fa-times remove-icon"></i></li> 
                   <li><a href="#">Fredericia</a><i class="fa fa-times remove-icon"></i></li> 
                   <li><a href="#">Auto</a><i class="fa fa-times remove-icon"></i></li> *}
                </ul>
             </div>
                <!-- End -->
    </div>
</section>

<form id="shopform" name="shopform" action="{modurl modname='ZSELEX' type='user' func='shoplists'}" method='post'>
    <input type='hidden' id='default_country_id' name='default_country_id' value={$country_id}> 
    <input type='hidden' id='default_country_name' name='default_country_name' value={$country_name|cleantext}>   
    <input type='hidden' id='hcountry' name='country_id' value={$country_id}>
    <input type='hidden' id='hregion' name='region_id' value="{$region_cookie|cleantext}">
    <input type='hidden' id='hcity' name='city_id' value="{$city_cookie|cleantext}">
    <input type='hidden' id='hshop' name='shop_id' value="{$shop_cookie|cleantext}">
    <input type='hidden' id='harea' name='area_id' value="{$area_cookie|cleantext}">
    <input type='hidden' id='hcategory' name='category_id' value="{$category_cookie|cleantext}">
    <input type='hidden' id='hbranch' name='branch_id'  value="{$branch_cookie|cleantext}">
    <input type='hidden' id='hbranch_name' name='hbranch_name'  value="{$branchNameCookie|cleantext}">
    <input type='hidden' id='hsearch' name='hsearch' value="{if $search_cookie neq ''}{$search_cookie|cleantext}{/if}">
    {*<input type="hidden" id="aff_id" name="aff_id" value='{$smarty.request.aff_id|@json_encode}'>*}
    <input type="hidden" id="aff_id" name="aff_id" value='{$affiliate_cookie}'>
    <input type="hidden" id="aff_name" name="aff_name" value='{$affNameCookie}'>
</form>

<input type='hidden' id='level'  value=''>
<input type='hidden' id='type'  value=''>
<input type='hidden' id='name'  value=''>

<input type='hidden' id='hcountryname' name='hcountry' value={$country_name|cleantext}>
<input type='hidden' id='hregionname' name='hregion' value=''>
<input type='hidden' id='hcity_name' name='hcity' value=''>
<input type='hidden' id='hshop_name' name='hshop' value=''>
<input type='hidden' id='hareaname' name='harea' value=''>
<input type='hidden' id='hcatname' value={$categoryName_cookie|cleantext}>
<input type="hidden" id="current_theme" value="{$current_theme}">
<input type="hidden" id="curr_lang" value="{$thislang}">
{*<input type="hidden" id="aff_id" name="aff_id[]" value='{$smarty.request.aff_id|@json_encode}'>*}


<input type='hidden' id='offset' name='offset' value='0'>
<input type='hidden' id='pageload' name='pageload' value=1>
{*
<input type='text' id='startval' name='startval' value='0'>
<input type='text' id='endval' name='endval' value='5'>
*}
<input type='hidden' id='curr_func' name='curr_func' value="{$smarty.request.func}">

<input type='hidden' id='shop_url' name='shop_url' value="{if $smarty.request.shop_id}{$baseurl}{modurl modname='ZSELEX' type='user' func='shop' shop_id=$smarty.request.shop_id}{/if}">
<input type='hidden' id='site_url' name='site_url' value="{if $smarty.request.shop_id}{$baseurl}{modurl modname='ZSELEX' type='user' func='site' shop_id=$smarty.request.shop_id}{/if}">
<input type='hidden' id='pages_url' name='pages_url' value="{if $smarty.request.shop_id}{$baseurl}{modurl modname='ZTEXT' type='user' func='pages' shop_id=$smarty.request.shop_id}{/if}">

<script>
    jQuery(document).ready(function(){
    resetAutocomplete();
    });
</script>
