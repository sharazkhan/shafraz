{pageaddvar name='javascript' value='jquery'}
{pageaddvar name='javascript' value='zikula.ui'}
{pageaddvar name='javascript' value='modules/ZSELEX/javascript/product_ad.js'}
<link href="modules/ZSELEX/style/combo/sexy-combo.css" rel="stylesheet" type="text/css" />
<link href="modules/ZSELEX/style/combo/sexy/sexy.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="modules/ZSELEX/javascript/combo/jquery.sexy-combo.js"></script>

<script type="text/javascript">
    jQuery(function () {
    // alert('hiii'); exit();
    jQuery("#city-combo").ZselexCombo({
    emptyText: "Choose a city..."
    //autoFill: true
    //triggerSelected: true
});
}); 
</script>
<style>
    .MapLabel .sexy{display:inline-block;}
</style>
<div class="z-admin-content-pagetitle">
    {icon type="view" size="small"}
    <h3>{gt text='Manage Ads'}</h3>
</div>
 
<form name="product_ad" action="" method="post" onsubmit="return validateProductAd()">
    <div class="ProductAdvertSec">
        <div>
            <h2 class="Ads">{gt text='Product Advertise'}</h2>
            <div class="AdSec">
                <div class="AdSecTop">
                    <h3>1</h3>
                    <p>
                        {gt text='Please select below on what search level to show Product AD. Choose REGION on map and perhaps City in dropdown.'}<br />
                           <span>{gt text='No selection'} = {gt text='Denmark'}.</span>
                    </p>
                </div>
                <div class="MapSVG">
                    <svg
                        xmlns:dc="http://purl.org/dc/elements/1.1/"
                        xmlns:cc="http://creativecommons.org/ns#"
                        xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
                        xmlns:svg="http://www.w3.org/2000/svg"
                        xmlns="http://www.w3.org/2000/svg"
                        xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd"
                        xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape"
                        width="285"
                        height="339"
                        id="svg2"
                        version="1.1"
                        inkscape:version="0.48.4 r9939"
                        sodipodi:docname="SmallMap.svg" style="cursor:pointer">
                        <defs
                            id="defs4" />
                        <sodipodi:namedview
                            id="base"
                            pagecolor="#ffffff"
                            bordercolor="#666666"
                            borderopacity="1.0"
                            inkscape:pageopacity="0.0"
                            inkscape:pageshadow="2"
                            inkscape:zoom="1"
                            inkscape:cx="147.50031"
                            inkscape:cy="166.18109"
                            inkscape:document-units="px"
                            inkscape:current-layer="layer1"
                            showgrid="false"
                            inkscape:window-width="1366"
                            inkscape:window-height="706"
                            inkscape:window-x="-8"
                            inkscape:window-y="-8"
                            inkscape:window-maximized="1" />
                        <metadata
                            id="metadata7">
                            <rdf:RDF>
                                <cc:Work
                                    rdf:about="">
                                    <dc:format>image/svg+xml</dc:format>
                                    <dc:type
                                        rdf:resource="http://purl.org/dc/dcmitype/StillImage" />
                                </cc:Work>
                            </rdf:RDF>
                        </metadata>
                        <g
                            inkscape:label="Layer 1"
                            inkscape:groupmode="layer"
                            id="layer1"
                            transform="translate(0,-713.36217)">
                            <g
                                id="g3022"
                                transform="matrix(0.59267243,0,0,0.59353155,3.1637323,718.06343)">
                                <g
                                    id="g3135"
                                    style="fill-rule:evenodd"
                                    transform="matrix(1.2473118,0,0,1.2473118,1.4110197,6.880312)">
                                    <polygon
                                        style="fill:#727d84;fill-opacity:1"
                                        id="first1"
                                        points="22,125 54,81 116,75 159,22 178,20 206,0 213,6 194,30 202,42 206,77 179,109 182,132 145,144 95,120 55,140 "
                                        class="fil0 first" onclick="selectedLevel(this.id , 'first');regionSelect('{$modvars.ZSELEX.da_region1}','NORDJYLLAND');"  onmouseover="Orange(this.id,2)" onmouseout="Gray(this.id,2)"  />
                                    <polygon
                                        style="fill:#727f84;fill-opacity:1"
                                        id="first2"
                                        points="229,70 237,77 257,65 256,60 "
                                        class="fil1 first" onclick="selectedLevel(this.id , 'first');regionSelect('{$modvars.ZSELEX.da_region1}','NORDJYLLAND');"  onmouseover="Orange(this.id,2)" onmouseout="Gray(this.id,2)"  />
                                    <text
                                        xml:space="preserve"
                                        style="font-size:32.06896591px;font-style:normal;font-weight:normal;line-height:125%;letter-spacing:0px;word-spacing:0px;fill:#000000;fill-opacity:1;stroke:none;font-family:Sans"
                                        x="69.109512"
                                        y="102.51038"
                                        id="first2"
                                        class="first"
                                        sodipodi:linespacing="125%" onclick="selectedLevel(this.id , 'first');regionSelect('{$modvars.ZSELEX.da_region1}','NORDJYLLAND');"  onmouseover="Orange(this.id,2)" onmouseout="Gray(this.id,2)" ><tspan
                                            sodipodi:role="line"
                                            id="tspan3038"
                                            x="69.109512"
                                            y="102.51038"
                                            style="font-size:12.82758617px;fill:#ffffff">NORDJYLLAND</tspan></text>
                                </g>
                                <g
                                    id="g3141"
                                    style="fill-rule:evenodd"
                                    transform="matrix(1.2473118,0,0,1.2473118,1.4110197,6.880312)">
                                    <path
                                        sodipodi:nodetypes="cccccscccccccccccc"
                                        style="fill:#727f84;fill-opacity:1"
                                        inkscape:connector-curvature="0"
                                        id="second1"
                                        d="m 20.698111,126.30189 34.13837,16 L 95,122.06918 l 49.93082,24.5346 38.30188,-12.7673 c 0,0 1.7673,13.16352 6.7673,22.16352 5,9 6,12 6,12 l 32,1 8,11 -19,42 -31,-8 -13,46 -11,11 L 110.69811,238.7673 7.3018885,259.16352 8,167 19,129 z"
                                        class="fil0 second" onclick="selectedLevel(this.id , 'second');regionSelect('{$modvars.ZSELEX.da_region2}','MIDTJYLLAND');" onmouseover="Orange(this.id,2)" onmouseout="Gray(this.id,2)" />
                                    <polygon
                                        style="fill:#727f84;fill-opacity:1"
                                        id="second2"
                                        points="200,243 198,271 205,271 213,255 "
                                        class="fil0 second" onclick="selectedLevel(this.id , 'second');regionSelect('{$modvars.ZSELEX.da_region2}','MIDTJYLLAND');" onmouseover="Orange(this.id,2)" onmouseout="Gray(this.id,2)"/>
                                    <text
                                        xml:space="preserve"
                                        style="font-size:32.06896591px;font-style:normal;font-weight:normal;line-height:125%;letter-spacing:0px;word-spacing:0px;fill:#000000;fill-opacity:1;stroke:none;font-family:Sans"
                                        x="48.047558"
                                        y="190.37817"
                                        id="second2"
                                        class="second"
                                        sodipodi:linespacing="125%" onclick="selectedLevel(this.id , 'second');regionSelect('{$modvars.ZSELEX.da_region2}','MIDTJYLLAND');" onmouseover="Orange(this.id,2)" onmouseout="Gray(this.id,2)"><tspan
                                            sodipodi:role="line"
                                            id="tspan3042"
                                            x="48.047558"
                                            y="190.37817"
                                            style="font-size:12.82758617px;fill:#ffffff">MIDTJYLLAND</tspan></text>
                                </g>
                                <g
                                    id="g3147"
                                    style="fill-rule:evenodd"
                                    transform="matrix(1.2473118,0,0,1.2473118,1.4110197,6.880312)">
                                    <polygon
                                        style="fill:#727f84;fill-opacity:1"
                                        id="third1"
                                        points="133,346 111,372 124,366 151,387 134,397 128,391 91,401 41,383 47,338 0,295 7,261 111,241 160,272 121,317 "
                                        class="fil0 third" onclick="selectedLevel(this.id , 'third');regionSelect('{$modvars.ZSELEX.da_region3}','SYDJYLLAND');" onmouseover="Orange(this.id,1)" onmouseout="Gray(this.id,1)"/>
                                    <text
                                        xml:space="preserve"
                                        style="font-size:32.06896591px;font-style:normal;font-weight:normal;line-height:125%;letter-spacing:0px;word-spacing:0px;fill:#000000;fill-opacity:1;stroke:none;font-family:Sans"
                                        x="30.815725"
                                        y="289.02203"
                                        id="third1"
                                        class="third"
                                        sodipodi:linespacing="125%" onclick="selectedLevel(this.id , 'third');regionSelect('{$modvars.ZSELEX.da_region3}','SYDJYLLAND');" onmouseover="Orange(this.id,1)" onmouseout="Gray(this.id,1)"><tspan
                                            sodipodi:role="line"
                                            id="tspan3046"
                                            x="30.815725"
                                            y="289.02203"
                                            style="font-size:12.82758617px;fill:#ffffff">SYDJYLLAND</tspan></text>
                                </g>
                                <g
                                    id="g3152"
                                    style="fill-rule:evenodd"
                                    transform="matrix(1.2473118,0,0,1.2473118,1.4110197,6.880312)">
                                    <polygon
                                        style="fill:#727f84;fill-opacity:1"
                                        id="four1"
                                        points="135,309 146,346 185,378 216,369 224,332 194,293 "
                                        class="fil0 four" onclick="selectedLevel(this.id , 'four');regionSelect('{$modvars.ZSELEX.da_region4}','FYN');" onmouseover="Orange(this.id,3)" onmouseout="Gray(this.id,3)"/>
                                    <polygon
                                        style="fill:#727f84;fill-opacity:1"
                                        id="four2"
                                        points="229,360 204,397 212,413 230,372 "
                                        class="fil0 four" onclick="selectedLevel(this.id , 'four');regionSelect('{$modvars.ZSELEX.da_region4}','FYN');" onmouseover="Orange(this.id,3)" onmouseout="Gray(this.id,3)"/>
                                    <polygon
                                        style="fill:#727f84;fill-opacity:1"
                                        id="four3"
                                        points="176,387 190,407 199,400 "
                                        class="fil0 four" onclick="selectedLevel(this.id , 'four');regionSelect('{$modvars.ZSELEX.da_region4}','FYN');" onmouseover="Orange(this.id,3)" onmouseout="Gray(this.id,3)"/>
                                    <text
                                        sodipodi:linespacing="125%"
                                        id="four1"
                                        class="four"
                                        y="335.09503"
                                        x="162.45288"
                                        style="font-size:32.06896591px;font-style:normal;font-weight:normal;line-height:125%;letter-spacing:0px;word-spacing:0px;fill:#000000;fill-opacity:1;stroke:none;font-family:Sans"
                                        xml:space="preserve" onclick="selectedLevel(this.id , 'four');regionSelect('{$modvars.ZSELEX.da_region4}','FYN');" onmouseover="Orange(this.id,3)" onmouseout="Gray(this.id,3)"><tspan
                                            style="font-size:12.82758617px;fill:#ffffff"
                                            y="335.09503"
                                            x="162.45288"
                                            id="tspan3050"
                                            sodipodi:role="line">FYN</tspan></text>
                                </g>
                                <g
                                    id="g3159"
                                    style="fill-rule:evenodd"
                                    transform="matrix(1.2473118,0,0,1.2473118,1.4110197,6.880312)">
                                    <polygon
                                        style="fill:#727f84;fill-opacity:1"
                                        id="five1"
                                        points="310,284 303,245 287,248 259,241 278,256 254,277 230,278 253,322 255,352 280,352 306,381 290,393 253,381 236,408 271,430 305,426 315,439 344,390 344,375 330,379 329,358 358,337 343,321 344,313 "
                                        class="fil0 five" onclick="selectedLevel(this.id , 'five');regionSelect('{$modvars.ZSELEX.da_region5}','SJÆLLAND');" onmouseover="Orange(this.id,1)" onmouseout="Gray(this.id,1)"/>
                                    <text
                                        xml:space="preserve"
                                        style="font-size:32.06896591px;font-style:normal;font-weight:normal;line-height:125%;letter-spacing:0px;word-spacing:0px;fill:#000000;fill-opacity:1;stroke:none;font-family:Sans"
                                        x="263.1553"
                                        y="323.90588"
                                        id="five1"
                                        class="five"
                                        sodipodi:linespacing="125%" onclick="selectedLevel(this.id , 'five');regionSelect('{$modvars.ZSELEX.da_region5}','SJÆLLAND');" onmouseover="Orange(this.id,1)" onmouseout="Gray(this.id,1)"><tspan
                                            sodipodi:role="line"
                                            id="tspan3054"
                                            x="263.1553"
                                            y="323.90588"
                                            style="font-size:12.82758617px;fill:#ffffff">SJÆLLAND</tspan></text>
                                </g>
                                <path
                                    sodipodi:nodetypes="cccccccscccc"
                                    style="fill:#727f84;fill-opacity:1;fill-rule:evenodd"
                                    inkscape:connector-curvature="0"
                                    id="six1"
                                    d="m 382.21767,315.78216 7.77412,44.29135 40.49449,34.05397 1.24731,-20.5375 11.22581,-2.49463 12.47312,9.9785 9.97849,-9.9785 c 0,0 -8.73118,-11.2258 -9.97849,-33.67742 -1.24731,-22.45161 0,-38.66666 0,-38.66666 l -38.66667,-14.96774 -35.25126,28.33417 z"
                                    class="fil0 six" onclick="selectedLevel(this.id , 'six');regionSelect('{$modvars.ZSELEX.da_region6}','HOVEDSTADSOMR&Aring;DET');" onmouseover="Orange(this.id,3)" onmouseout="Gray(this.id,3)"/>
                                <text
                                    id="six2"
                                    class="six"
                                    style="font-size:13.80000019px;font-style:normal;font-weight:normal;text-align:start;text-anchor:start;fill:#000000;fill-rule:evenodd;font-family:Calibri"
                                    y="317.77484"
                                    x="403.09995"
                                    xml:space="preserve" onclick="selectedLevel(this.id , 'six');regionSelect('{$modvars.ZSELEX.da_region6}','HOVEDSTADSOMR&Aring;DET');" onmouseover="Orange(this.id,3)" onmouseout="Gray(this.id,3)"><tspan
                                        style="font-size:10px;fill:#ffffff"
                                        id="tspan3148">HOVED -</tspan></text>
                                <text
                                    id="six3"
                                    class="six"
                                    style="font-size:13.80000019px;font-style:normal;font-weight:normal;text-align:start;text-anchor:start;fill:#000000;fill-rule:evenodd;font-family:Calibri"
                                    y="329.14011"
                                    x="403.09995"
                                    xml:space="preserve" onclick="selectedLevel(this.id , 'six');regionSelect('{$modvars.ZSELEX.da_region6}','HOVEDSTADSOMR&Aring;DET');" onmouseover="Orange(this.id,3)" onmouseout="Gray(this.id,3)"><tspan
                                        style="font-size:10px;fill:#ffffff"
                                        id="tspan3150">STADS -</tspan></text>
                                <text
                                    id="six4"
                                    class="six"
                                    style="font-size:10px;font-style:normal;font-weight:normal;text-align:start;text-anchor:start;fill:#000000;fill-rule:evenodd;font-family:Calibri"
                                    y="340.50537"
                                    x="397.09995"
                                    xml:space="preserve" onclick="selectedLevel(this.id , 'six');regionSelect('{$modvars.ZSELEX.da_region6}','HOVEDSTADSOMR&Aring;DET');" onmouseover="Orange(this.id,3)" onmouseout="Gray(this.id,3)"><tspan
                                        style="font-size:10px;fill:#ffffff"
                                        id="tspan3146">OMRÅDET</tspan></text>
                                <path
                                    inkscape:connector-curvature="0"
                                    id="seven1"
                                    class="seven"
                                    d="m 381.12711,68.292091 6.42857,38.928569 44.46429,6.25 -0.17858,-30.892855 z"
                                    style="fill:#727d84;fill-opacity:1;fill-rule:evenodd;stroke:#000000;stroke-width:0;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none" onclick="selectedLevel(this.id , 'seven');regionSelect('{$modvars.ZSELEX.da_region7}','BORNHOLM');" onmouseover="Orange(this.id,2)" onmouseout="Gray(this.id,2)"/>
                                <path
                                    inkscape:connector-curvature="0"
                                    id="seven2"
                                    class="seven"
                                    d="m 362.73425,56.327805 2.5,-0.178571 0,69.642856 86.78572,0 0,2.32143 -89.28572,0 z"
                                    style="fill:#727d84;fill-opacity:1;fill-rule:evenodd;stroke:#000000;stroke-width:0;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none" onclick="selectedLevel(this.id , 'seven');regionSelect('{$modvars.ZSELEX.da_region7}','BORNHOLM');" onmouseover="Orange(this.id,2)" onmouseout="Gray(this.id,2)"/>

                                <text
                                    xml:space="preserve"
                                    x="392.09995"
                                    y="90.50537"
                                    style="font-size:10px;font-style:normal;font-weight:normal;text-align:start;text-anchor:start;fill:#000000;fill-rule:evenodd;font-family:Calibri"
                                    id="seven1"  class="seven" onclick="selectedLevel(this.id , 'seven');regionSelect('{$modvars.ZSELEX.da_region7}','BORNHOLM');"  onmouseover="Orange(this.id,3)" onmouseout="Gray(this.id,3)"><tspan
                                       
                                        style="font-size:10px;fill:#ffffff" >BORN</tspan></text>

                                <text
                                    xml:space="preserve"
                                    x="392.09995"
                                    y="100.50537"
                                    style="font-size:10px;font-style:normal;font-weight:normal;text-align:start;text-anchor:start;fill:#000000;fill-rule:evenodd;font-family:Calibri"
                                    id="seven1"  class="seven" onclick="selectedLevel(this.id , 'seven');regionSelect('{$modvars.ZSELEX.da_region7}','BORNHOLM');"  onmouseover="Orange(this.id,3)" onmouseout="Gray(this.id,3)"><tspan
                                      
                                        style="font-size:10px;fill:#ffffff">HOLM</tspan></text>
                            </g>
                        </g>
                    </svg>
                </div>
                <div class="MapLabel">
                    <div class="LabelRow">
                        <label>{gt text='City for AD'}</label>
                        <span id="city-div">
                        <select id="city-combo" name="city_id">
                            <option value="">{gt text='search city'}</option>
                            {foreach from=$cities  item='city'}
                                <option value="{$city.city_id}">{$city.city_name}</option>
                            {/foreach}
                        </select>
                        </span>
                    </div>
                    <div class="LabelRow"><label>{gt text='AD Name'}</label> <input type="text" value="" id="ad_name" name="ad_name" /></div>
                </div>
            </div>
            <div class="AdSec AdLeft">
                <div class="AdSecTop">
                    <h3>2</h3>
                    <p>
                        {gt text='Please select where to show your Product AD on below frontpage schematic.'}<br />
                        {gt text='You can choose A, B or C.'}
                    </p>
                </div>

                <div class="SplOffer">
                    <h5>{gt text='Special offers right now'}</h5>

                    <ul id="high_ul" class="A_Sec" style="cursor:pointer" onClick="selectLevel('A');">
                        <li id="high_1">{gt text='A'}</li>
                        <li id="high_2">{gt text='A'}</li>               
                    </ul>

                    <ul id="mid_ul" class="B_Sec" style="cursor:pointer" onClick="selectLevel('B');">
                        <li>{gt text='B'}</li>
                        <li>{gt text='B'}</li> 
                        <li>{gt text='B'}</li>
                        <li>{gt text='B'}</li>               
                    </ul>
                    <ul class="B_Sec Events">
                        <li>{gt text='Event'}</li>
                        <li>{gt text='Event'}</li> 
                        <li>{gt text='Event'}</li>
                        <li>{gt text='Event'}</li>               
                    </ul>
                    <ul id="low_ul" class="B_Sec" style="cursor:pointer" onClick="selectLevel('C');">
                        <li>{gt text='C'}</li>
                        <li>{gt text='C'}</li> 
                        <li>{gt text='C'}</li>
                        <li>{gt text='C'}</li>               
                    </ul>
                </div>
            </div>
        </div>
        <div class="Ads_Submit_Sec">
            <div class="Ads_Submit_Sec_Left">
                3
            </div>
            <div class="Ads_Submit_Sec_Right">
                <div class="Ads_Submit_Sec_Right_Top">
                    <div class="SubmitTopPara1">
                        {gt text='Finish Product AD creation <br> by clicking CREATE button.'}<br />
                       
                    </div>
                    <div class="SubmitTopPara2">
                      {*  <span> AD's left :</span>&lt; {$servicelimit} &gt;<br />
                        <span>This will cost :</span>&lt; no &gt; *}
                        <span> {gt text="Rest"} :</span><span>{$servicelimit} {gt text="points"}</span><br />
                        <span>{gt text="Uses"}  :</span><span id="adCost">0 {gt text="points"}</span>
                    </div>
                </div>
                <input type="hidden" name="csrftoken" value="{insert name="csrftoken"}" />
                <input type="hidden" id="map_level" name="map_level" value="">
                <input type="hidden" id="region" name="region" value="">
                <input type="hidden" id="region_id" name="region_id" value="0">
                <input type="hidden" id="region_name" name="region_name" value="">
                <input type="hidden" id="city_name" name="city_name" value="">
                <input type="hidden" id="ad_level" name="ad_level" value="">
                <input type="hidden" id="selection_level" name="selection_level" value="">
                <input type="hidden" id="service_limit" name="service_limit" value="{$servicelimit}">
                <input type="hidden" id="reset_ad" name="reset_ad" value="">
                            <div class="Ads_Submit_Sec_Right_Bot">
                                 {if $servicecount > 0 AND !$servicedisable}
                                <button  id="submit_ad" name="submit_ad" value="1" class="OrangeBtn">{gt text='Create Product AD'}<img src="{$themepath}/images/ArrowShadow.png"></button>
                                <button onclick="return resetProductAd()" name="reset_ad" value="1" class="GreyBtn">{gt text="Reset Selection"}</button>
                                  {/if}
                           </div>
                            </div>
                            </div>
                            </div>
         </form> 
                              
                                
             <br>                   
   <form class="z-form" id="zselex_bulkaction_form" action="{modurl modname='ZSELEX' type='admin' func='processbulkaction'}" method="post">
    <div style="overflow:auto;">
       
        <table id="zselex_admintable" class="z-datatable">
            <thead>
                <tr>
                  <!--    <th></th> -->
                    {if !$expired AND !$servicedisable}
                    <th>{gt text='Actions'}</th>
                    {/if}
                  
                    <th>{gt text='Advertise Name'}</th>
                    <th>{gt text='Where'}</th>
                    <th>{gt text='Points'}</th>
                    <th>{gt text='Country'}</th>
                    <th>{gt text='Region'}</th>
                    <th>{gt text='City'}</th>
                       
                </tr>
            </thead>
            <tbody>
                {foreach from=$product_ads item='advertiseitem'}
                <tr class="{cycle values='z-odd,z-even'}">
                   <!--   <td><input type="checkbox" name="zselex_selected_advertises[]" value="{$advertiseitem.advertise_id}" class="zselex_checkbox" /></td> -->
                    {if !$expired AND !$servicedisable}
                    <td>

<a href="{modurl modname="ZSELEX" type='admin' func='deleteAd' advertise_id=$advertiseitem.advertise_id shop_id=$smarty.request.shop_id}" title="{gt text="Delete"}" onclick='return deleteAd()'>{img modname=core src="14_layer_deletelayer.png" set="icons/extrasmall" __alt="Delete" __title="Delete"} </a>
                    </td>
                    {/if}
               
                <td>{$advertiseitem.name|safetext}</td>
                <td>{$advertiseitem.price_name|safetext}</td>
                <td>{$advertiseitem.price|round}</td>
                <td>{$advertiseitem.country_name|safetext}</td>
                <td>{$advertiseitem.region_name|safetext}</td>
                <td>{$advertiseitem.city_name|safetext}</td>
              </tr>
            {foreachelse}
            <tr class="z-datatableempty"><td colspan="21">{gt text='No advertises currently in database.'}</td></tr>
            {/foreach}
        </tbody>
    </table>
    <!--  <p id='zselex_bulkaction_control'>
        {img modname='core' set='icons/extrasmall' src='2uparrow.png' __alt='doubleuparrow'}<a href="javascript:void(0);" id="zselex_select_all">{gt text="Check all"}</a> / <a href="javascript:void(0);" id="zselex_deselect_all">{gt text="Uncheck all"}</a>
        <select id='zselex_bulkaction_select' name='zselex_bulkaction_select'>
            <option value='0' selected='selected'>{gt text='With selected:'}</option>
            <option value='1'>{gt text='Delete'}</option>
            <option value='2'>{gt text='active'}</option>
        </select>
    </p> -->
</div>
</form>
         <input type="hidden" id="country_id" name="country_id" value="{$country_id}">
         <input type="hidden" id="country_name" name="country_name" value="{$country_name}">    
                  {pager rowcount=$total_count limit=$itemsperpage posvar='startnum' maxpages=10}

    <div class="z-buttons z-formbuttons">
        <a href="{modurl modname="ZSELEX" type="admin" func="shopsummary" shop_id=$smarty.request.shop_id}" title="{gt text="Back"}">{img modname='ZSELEX' src="icon_cp_backtoshoplist.png" __alt="Back" __title="Back"} {gt text="Back"}</a>
    </div>
