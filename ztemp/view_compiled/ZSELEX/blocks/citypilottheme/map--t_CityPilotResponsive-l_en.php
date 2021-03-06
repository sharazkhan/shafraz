<?php /* Smarty version 2.6.28, created on 2017-11-26 08:10:31
         compiled from blocks/citypilottheme/map.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'gt', 'blocks/citypilottheme/map.tpl', 15, false),)), $this); ?>

<style type="text/css"><?php echo '
    body, html{padding:0px; margin:0px}
    body{ /*background:url(MAPFrame.png) no-repeat*/}
    .fil1 {fill:#727f84}
    .fil2 {fill:#727f84}
    .fil0 {fill:#727F84}
    .fil3 {fill:#889397}
    .fil4 {fill:#A6AEB1}
    .fil5 {fill:#AEB5B7}


'; ?>
</style>
    <!-- MENU BUTTON -->
<button class="wsite-nav-button"><?php echo smarty_function_gt(array('text' => 'Menu'), $this);?>
</button>
<input type="hidden" id="mapLoaded" value="0">
<div class="MapDiv right">
    <div class="MapDivTxt left">
                <?php echo smarty_function_gt(array('text' => 'Select City'), $this);?>

    </div>
    <div class="MapDivImg left Pointer" id="MapDivImg">
        <img id="mapImg" src="themes/<?php echo $this->_tpl_vars['current_theme']; ?>
/images/MapThumb.png" />
        <div id="mapContent">
        <div class="MapBlock" id="MapBlock">

            <div class="SVGMap left">

                <svg
                    xmlns:dc="http://purl.org/dc/elements/1.1/"
                    xmlns:cc="http://creativecommons.org/ns#"
                    xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
                    xmlns:svg="http://www.w3.org/2000/svg"
                    xmlns="http://www.w3.org/2000/svg"
                    xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd"
                    xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape"
                    id="svg3175"
                    version="1.1"
                    inkscape:version="0.48.4 r9939"
                    width="466"
                    height="555"
                    sodipodi:docname="New.png">
                    <metadata
                        id="metadata3181">
                        <rdf:RDF>
                            <cc:Work
                                rdf:about="">
                                <dc:format>image/svg+xml</dc:format>
                                <dc:type
                                    rdf:resource="http://purl.org/dc/dcmitype/StillImage" />
                                <dc:title></dc:title>
                            </cc:Work>
                        </rdf:RDF>
                    </metadata>
                    <defs
                        id="defs3179" />
                    <sodipodi:namedview
                        pagecolor="#ffffff"
                        bordercolor="#666666"
                        borderopacity="1"
                        objecttolerance="10"
                        gridtolerance="10"
                        guidetolerance="10"
                        inkscape:pageopacity="0"
                        inkscape:pageshadow="2"
                        inkscape:window-width="1366"
                        inkscape:window-height="706"
                        id="namedview3177"
                        showgrid="false"
                        inkscape:zoom="1.7009009"
                        inkscape:cx="296.79225"
                        inkscape:cy="465.63559"
                        inkscape:window-x="-8"
                        inkscape:window-y="-8"
                        inkscape:window-maximized="1"
                        inkscape:current-layer="svg3175" />
                    <g
                        transform="matrix(1.2473118,0,0,1.2473118,1.4110197,6.880312)"
                        style="fill-rule:evenodd"
                        id="g3135">
                        <polygon
                            class="fil0 reg<?php echo $this->_tpl_vars['modvars']['ZSELEX']['da_region1']; ?>
 fillAll"
                            points="206,77 179,109 182,132 145,144 95,120 55,140 22,125 54,81 116,75 159,22 178,20 206,0 213,6 194,30 202,42 "
                            id="first1"
                            style="fill:#727d84;fill-opacity:1" onclick="regionSelect('<?php echo $this->_tpl_vars['modvars']['ZSELEX']['da_region1']; ?>
','NORDJYLLAND');"  onmouseover="Orange(this.id,2,'<?php echo $this->_tpl_vars['modvars']['ZSELEX']['da_region1']; ?>
')" onmouseout="Gray(this.id,2,'<?php echo $this->_tpl_vars['modvars']['ZSELEX']['da_region1']; ?>
')" />
                        <polygon
                            class="fil1 reg<?php echo $this->_tpl_vars['modvars']['ZSELEX']['da_region1']; ?>
 fillAll"
                            points="229,70 237,77 257,65 256,60 "
                            id="first2"
                            style="fill:#727f84;fill-opacity:1" onclick="regionSelect('<?php echo $this->_tpl_vars['modvars']['ZSELEX']['da_region1']; ?>
','NORDJYLLAND');" onmouseover="Orange(this.id,2,'<?php echo $this->_tpl_vars['modvars']['ZSELEX']['da_region1']; ?>
')" onmouseout="Gray(this.id,2,'<?php echo $this->_tpl_vars['modvars']['ZSELEX']['da_region1']; ?>
')"/>
                        <text
                            sodipodi:linespacing="125%"
                            id="first2"
                            class="reg<?php echo $this->_tpl_vars['modvars']['ZSELEX']['da_region1']; ?>
 fillAll"
                            y="102.51038"
                            x="69.109512"
                            style="font-size:32.06896591px;font-style:normal;font-weight:normal;line-height:125%;letter-spacing:0px;word-spacing:0px;fill:#000000;fill-opacity:1;stroke:none;font-family:Sans"
                            xml:space="preserve" onclick="regionSelect('<?php echo $this->_tpl_vars['modvars']['ZSELEX']['da_region1']; ?>
','NORDJYLLAND');" onmouseover="Orange(this.id,2,'<?php echo $this->_tpl_vars['modvars']['ZSELEX']['da_region1']; ?>
')" onmouseout="Gray(this.id,2,'<?php echo $this->_tpl_vars['modvars']['ZSELEX']['da_region1']; ?>
')"><tspan
                                style="font-size:12.82758617px;fill:#ffffff"
                                y="102.51038"
                                x="69.109512"
                                id="tspan3038"
                                sodipodi:role="line">NORDJYLLAND</tspan></text>
                    </g>
                    <g
                        transform="matrix(1.2473118,0,0,1.2473118,1.4110197,6.880312)"
                        style="fill-rule:evenodd"
                        id="g3141">
                        <path
                            class="fil0 reg<?php echo $this->_tpl_vars['modvars']['ZSELEX']['da_region2']; ?>
 fillAll"
                            d="m 20.698111,126.30189 34.13837,16 L 95,122.06918 l 49.93082,24.5346 38.30188,-12.7673 c 0,0 1.7673,13.16352 6.7673,22.16352 5,9 6,12 6,12 l 32,1 8,11 -19,42 -31,-8 -13,46 -11,11 L 110.69811,238.7673 7.3018885,259.16352 8,167 19,129 z"
                            id="second1"
                            inkscape:connector-curvature="0"
                            style="fill:#727f84;fill-opacity:1"
                            sodipodi:nodetypes="cccccscccccccccccc" onclick="regionSelect('<?php echo $this->_tpl_vars['modvars']['ZSELEX']['da_region2']; ?>
','MIDTJYLLAND');" onmouseover="Orange(this.id,2,'<?php echo $this->_tpl_vars['modvars']['ZSELEX']['da_region2']; ?>
')" onmouseout="Gray(this.id,2,'<?php echo $this->_tpl_vars['modvars']['ZSELEX']['da_region2']; ?>
')" />
                        <polygon
                            class="fil0 reg<?php echo $this->_tpl_vars['modvars']['ZSELEX']['da_region2']; ?>
 fillAll"
                            points="200,243 198,271 205,271 213,255 "
                            id="second2"
                            style="fill:#727f84;fill-opacity:1" onclick="regionSelect('<?php echo $this->_tpl_vars['modvars']['ZSELEX']['da_region2']; ?>
','MIDTJYLLAND');" onmouseover="Orange(this.id,2,'<?php echo $this->_tpl_vars['modvars']['ZSELEX']['da_region2']; ?>
')" onmouseout="Gray(this.id,2,'<?php echo $this->_tpl_vars['modvars']['ZSELEX']['da_region2']; ?>
')"/>
                        <text
                            sodipodi:linespacing="125%"
                            id="second2"
                            class="reg<?php echo $this->_tpl_vars['modvars']['ZSELEX']['da_region2']; ?>
 fillAll"
                            y="190.37817"
                            x="48.047558"
                            style="font-size:32.06896591px;font-style:normal;font-weight:normal;line-height:125%;letter-spacing:0px;word-spacing:0px;fill:#000000;fill-opacity:1;stroke:none;font-family:Sans"
                            xml:space="preserve" onclick="regionSelect('<?php echo $this->_tpl_vars['modvars']['ZSELEX']['da_region2']; ?>
','MIDTJYLLAND');"  onmouseover="Orange(this.id,2,'<?php echo $this->_tpl_vars['modvars']['ZSELEX']['da_region2']; ?>
')" onmouseout="Gray(this.id,2,'<?php echo $this->_tpl_vars['modvars']['ZSELEX']['da_region2']; ?>
')"><tspan
                                style="font-size:12.82758617px;fill:#ffffff"
                                y="190.37817"
                                x="48.047558"
                                id="tspan3042"
                                sodipodi:role="line">MIDTJYLLAND</tspan></text>
                    </g>
                    <g
                        transform="matrix(1.2473118,0,0,1.2473118,1.4110197,6.880312)"
                        style="fill-rule:evenodd"
                        id="g3147">
                        <polygon
                            class="fil0 reg<?php echo $this->_tpl_vars['modvars']['ZSELEX']['da_region3']; ?>
 fillAll"
                            points="91,401 41,383 47,338 0,295 7,261 111,241 160,272 121,317 133,346 111,372 124,366 151,387 134,397 128,391 "
                            id="third1"
                            style="fill:#727f84;fill-opacity:1" onclick="regionSelect('<?php echo $this->_tpl_vars['modvars']['ZSELEX']['da_region3']; ?>
','SYDJYLLAND');" onmouseover="Orange(this.id,1,'<?php echo $this->_tpl_vars['modvars']['ZSELEX']['da_region3']; ?>
')" onmouseout="Gray(this.id,1,'<?php echo $this->_tpl_vars['modvars']['ZSELEX']['da_region3']; ?>
')"/>
                        <text
                            sodipodi:linespacing="125%"
                            id="third1"
                            class="reg<?php echo $this->_tpl_vars['modvars']['ZSELEX']['da_region3']; ?>
 fillAll"
                            y="289.02203"
                            x="30.815725"
                            style="font-size:32.06896591px;font-style:normal;font-weight:normal;line-height:125%;letter-spacing:0px;word-spacing:0px;fill:#000000;fill-opacity:1;stroke:none;font-family:Sans"
                            xml:space="preserve" onclick="regionSelect('<?php echo $this->_tpl_vars['modvars']['ZSELEX']['da_region3']; ?>
','SYDJYLLAND');" onmouseover="Orange(this.id,1,'<?php echo $this->_tpl_vars['modvars']['ZSELEX']['da_region3']; ?>
')" onmouseout="Gray(this.id,1,'<?php echo $this->_tpl_vars['modvars']['ZSELEX']['da_region3']; ?>
')"><tspan
                                style="font-size:12.82758617px;fill:#ffffff"
                                y="289.02203"
                                x="30.815725"
                                id="tspan3046"
                                sodipodi:role="line">SYDJYLLAND</tspan></text>
                    </g>
                    <g
                        transform="matrix(1.2473118,0,0,1.2473118,1.4110197,6.880312)"
                        style="fill-rule:evenodd"
                        id="g3152">
                        <polygon
                            class="fil0 reg<?php echo $this->_tpl_vars['modvars']['ZSELEX']['da_region4']; ?>
 fillAll"
                            points="135,309 146,346 185,378 216,369 224,332 194,293 "
                            id="four1"
                            style="fill:#727f84;fill-opacity:1" onclick="regionSelect('<?php echo $this->_tpl_vars['modvars']['ZSELEX']['da_region4']; ?>
','FYN');" onmouseover="Orange(this.id,3,'<?php echo $this->_tpl_vars['modvars']['ZSELEX']['da_region4']; ?>
')" onmouseout="Gray(this.id,3,'<?php echo $this->_tpl_vars['modvars']['ZSELEX']['da_region4']; ?>
')"/>
                        <polygon
                            class="fil0 reg<?php echo $this->_tpl_vars['modvars']['ZSELEX']['da_region4']; ?>
 fillAll"
                            points="229,360 204,397 212,413 230,372 "
                            id="four2"
                            style="fill:#727f84;fill-opacity:1" onclick="regionSelect('<?php echo $this->_tpl_vars['modvars']['ZSELEX']['da_region4']; ?>
','FYN');" onmouseover="Orange(this.id,3,'<?php echo $this->_tpl_vars['modvars']['ZSELEX']['da_region4']; ?>
')" onmouseout="Gray(this.id,3,'<?php echo $this->_tpl_vars['modvars']['ZSELEX']['da_region4']; ?>
')"/>
                        <polygon
                            class="fil0 reg<?php echo $this->_tpl_vars['modvars']['ZSELEX']['da_region4']; ?>
 fillAll"
                            points="176,387 190,407 199,400 "
                            id="four3"
                            style="fill:#727f84;fill-opacity:1" onclick="regionSelect('<?php echo $this->_tpl_vars['modvars']['ZSELEX']['da_region4']; ?>
','FYN');" onmouseover="Orange(this.id,3,'<?php echo $this->_tpl_vars['modvars']['ZSELEX']['da_region4']; ?>
')" onmouseout="Gray(this.id,3,'<?php echo $this->_tpl_vars['modvars']['ZSELEX']['da_region4']; ?>
')"/>
                        <text
                            xml:space="preserve"
                            style="font-size:32.06896591px;font-style:normal;font-weight:normal;line-height:125%;letter-spacing:0px;word-spacing:0px;fill:#000000;fill-opacity:1;stroke:none;font-family:Sans"
                            x="162.45288"
                            y="335.09503"
                            id="four1"
                            class="reg<?php echo $this->_tpl_vars['modvars']['ZSELEX']['da_region4']; ?>
 fillAll"
                            sodipodi:linespacing="125%" onclick="regionSelect('<?php echo $this->_tpl_vars['modvars']['ZSELEX']['da_region4']; ?>
','FYN');" onmouseover="Orange(this.id,3,'<?php echo $this->_tpl_vars['modvars']['ZSELEX']['da_region4']; ?>
')" onmouseout="Gray(this.id,3,'<?php echo $this->_tpl_vars['modvars']['ZSELEX']['da_region4']; ?>
')"><tspan
                                sodipodi:role="line"
                                id="tspan3050"
                                x="162.45288"
                                y="335.09503"
                                style="font-size:12.82758617px;fill:#ffffff">FYN</tspan></text>
                    </g>
                    <g
                        transform="matrix(1.2473118,0,0,1.2473118,1.4110197,6.880312)"
                        style="fill-rule:evenodd"
                        id="g3159">
                        <polygon
                            class="fil0 reg<?php echo $this->_tpl_vars['modvars']['ZSELEX']['da_region5']; ?>
 fillAll"
                            points="253,381 236,408 271,430 305,426 315,439 344,390 344,375 330,379 329,358 358,337 343,321 344,313 310,284 303,245 287,248 259,241 278,256 254,277 230,278 253,322 255,352 280,352 306,381 290,393 "
                            id="five1"
                            style="fill:#727f84;fill-opacity:1"  onclick="regionSelect('<?php echo $this->_tpl_vars['modvars']['ZSELEX']['da_region5']; ?>
','SJ&AElig;LLAND');" onmouseover="Orange(this.id,3,'<?php echo $this->_tpl_vars['modvars']['ZSELEX']['da_region5']; ?>
')" onmouseout="Gray(this.id,3,'<?php echo $this->_tpl_vars['modvars']['ZSELEX']['da_region5']; ?>
')"/>
                        <text
                            sodipodi:linespacing="125%"
                            id="five1"
                            class="reg<?php echo $this->_tpl_vars['modvars']['ZSELEX']['da_region5']; ?>
 fillAll"
                            y="323.90588"
                            x="263.1553"
                            style="font-size:32.06896591px;font-style:normal;font-weight:normal;line-height:125%;letter-spacing:0px;word-spacing:0px;fill:#000000;fill-opacity:1;stroke:none;font-family:Sans"
                            xml:space="preserve"  onclick="regionSelect('<?php echo $this->_tpl_vars['modvars']['ZSELEX']['da_region5']; ?>
','SJ&AElig;LLAND');" onmouseover="Orange(this.id,3,'<?php echo $this->_tpl_vars['modvars']['ZSELEX']['da_region5']; ?>
')" onmouseout="Gray(this.id,3,'<?php echo $this->_tpl_vars['modvars']['ZSELEX']['da_region5']; ?>
')"><tspan
                                style="font-size:12.82758617px;fill:#ffffff"
                                y="323.90588"
                                x="263.1553"
                                id="tspan3054"
                                sodipodi:role="line">SJÆLLAND</tspan></text>
                    </g>
                    <path
                        class="fil0 reg<?php echo $this->_tpl_vars['modvars']['ZSELEX']['da_region6']; ?>
 fillAll"
                        d="m 382.21767,315.78216 7.77412,44.29135 40.49449,34.05397 1.24731,-20.5375 11.22581,-2.49463 12.47312,9.9785 9.97849,-9.9785 c 0,0 -8.73118,-11.2258 -9.97849,-33.67742 -1.24731,-22.45161 0,-38.66666 0,-38.66666 l -38.66667,-14.96774 -35.25126,28.33417 z"
                        id="six1"
                        inkscape:connector-curvature="0"
                        style="fill:#727f84;fill-opacity:1;fill-rule:evenodd"
                        sodipodi:nodetypes="cccccccscccc" onclick="regionSelect('<?php echo $this->_tpl_vars['modvars']['ZSELEX']['da_region6']; ?>
','HOVEDSTADSOMR&Aring;DET');" onmouseover="Orange(this.id,3,'<?php echo $this->_tpl_vars['modvars']['ZSELEX']['da_region6']; ?>
')" onmouseout="Gray(this.id,3,'<?php echo $this->_tpl_vars['modvars']['ZSELEX']['da_region6']; ?>
')"/>
                    <text
                        xml:space="preserve"
                        class="reg<?php echo $this->_tpl_vars['modvars']['ZSELEX']['da_region6']; ?>
 fillAll"
                        x="403.09995"
                        y="317.77484"
                        style="font-size:13.80000019px;font-style:normal;font-weight:normal;text-align:start;text-anchor:start;fill:#000000;fill-rule:evenodd;font-family:Calibri"
                        id="six2" onclick="regionSelect('<?php echo $this->_tpl_vars['modvars']['ZSELEX']['da_region6']; ?>
','HOVEDSTADSOMR&Aring;DET');" onmouseover="Orange(this.id,3,'<?php echo $this->_tpl_vars['modvars']['ZSELEX']['da_region6']; ?>
')" onmouseout="Gray(this.id,3,'<?php echo $this->_tpl_vars['modvars']['ZSELEX']['da_region6']; ?>
')"><tspan
                            id="tspan3148"
                            style="font-size:10px;fill:#ffffff" >HOVED -</tspan></text>
                    <text
                        xml:space="preserve"
                        class="reg<?php echo $this->_tpl_vars['modvars']['ZSELEX']['da_region6']; ?>
 fillAll"
                        x="403.09995"
                        y="329.14011"
                        style="font-size:13.80000019px;font-style:normal;font-weight:normal;text-align:start;text-anchor:start;fill:#000000;fill-rule:evenodd;font-family:Calibri"
                        id="six3" onclick="regionSelect('<?php echo $this->_tpl_vars['modvars']['ZSELEX']['da_region6']; ?>
','HOVEDSTADSOMR&Aring;DET');" onmouseover="Orange(this.id,3,'<?php echo $this->_tpl_vars['modvars']['ZSELEX']['da_region6']; ?>
')" onmouseout="Gray(this.id,3,'<?php echo $this->_tpl_vars['modvars']['ZSELEX']['da_region6']; ?>
')"><tspan
                            id="tspan3150"
                            style="font-size:10px;fill:#ffffff">STADS -</tspan></text>
                    <text
                        xml:space="preserve"
                        class="reg<?php echo $this->_tpl_vars['modvars']['ZSELEX']['da_region6']; ?>
 fillAll"
                        x="397.09995"
                        y="340.50537"
                        style="font-size:10px;font-style:normal;font-weight:normal;text-align:start;text-anchor:start;fill:#000000;fill-rule:evenodd;font-family:Calibri"
                        id="six4" onclick="regionSelect('<?php echo $this->_tpl_vars['modvars']['ZSELEX']['da_region6']; ?>
','HOVEDSTADSOMR&Aring;DET');"  onmouseover="Orange(this.id,3,'<?php echo $this->_tpl_vars['modvars']['ZSELEX']['da_region6']; ?>
')" onmouseout="Gray(this.id,3,'<?php echo $this->_tpl_vars['modvars']['ZSELEX']['da_region6']; ?>
')"><tspan
                            id="tspan3146"
                            style="font-size:10px;fill:#ffffff">OMRÅDET</tspan></text>
                    <path
                        style="fill:#727d84;fill-opacity:1;fill-rule:evenodd;stroke:#000000;stroke-width:0;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"
                        d="m 381.12711,68.292091 6.42857,38.928569 44.46429,6.25 -0.17858,-30.892855 z"
                        id="seven1"
                        class="reg<?php echo $this->_tpl_vars['modvars']['ZSELEX']['da_region7']; ?>
 fillAll"
                        inkscape:connector-curvature="0" onclick="regionSelect('<?php echo $this->_tpl_vars['modvars']['ZSELEX']['da_region7']; ?>
','BORNHOLM');" onmouseover="Orange(this.id,2,'<?php echo $this->_tpl_vars['modvars']['ZSELEX']['da_region7']; ?>
')" onmouseout="Gray(this.id,2,'<?php echo $this->_tpl_vars['modvars']['ZSELEX']['da_region7']; ?>
')"/>
                    <path
                        style="fill:#727d84;fill-opacity:1;fill-rule:evenodd;stroke:#000000;stroke-width:0;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"
                        d="m 362.73425,56.327805 2.5,-0.178571 0,69.642856 86.78572,0 0,2.32143 -89.28572,0 z"
                        id="seven2"
                        class="reg<?php echo $this->_tpl_vars['modvars']['ZSELEX']['da_region7']; ?>
 fillAll"
                        inkscape:connector-curvature="0" onclick="regionSelect('<?php echo $this->_tpl_vars['modvars']['ZSELEX']['da_region7']; ?>
','BORNHOLM');" onmouseover="Orange(this.id,2,'<?php echo $this->_tpl_vars['modvars']['ZSELEX']['da_region7']; ?>
')" onmouseout="Gray(this.id,2,'<?php echo $this->_tpl_vars['modvars']['ZSELEX']['da_region7']; ?>
')"/>

                    <text
                        xml:space="preserve"
                        x="392.09995"
                        y="90.50537"
                        class="reg<?php echo $this->_tpl_vars['modvars']['ZSELEX']['da_region7']; ?>
 fillAll"
                        style="font-size:10px;font-style:normal;font-weight:normal;text-align:start;text-anchor:start;fill:#000000;fill-rule:evenodd;font-family:Calibri"
                        id="seven1"  onmouseover="Orange(this.id,3,'<?php echo $this->_tpl_vars['modvars']['ZSELEX']['da_region7']; ?>
')" onclick="regionSelect('<?php echo $this->_tpl_vars['modvars']['ZSELEX']['da_region7']; ?>
','BORNHOLM');" onmouseout="Gray(this.id,3,'<?php echo $this->_tpl_vars['modvars']['ZSELEX']['da_region7']; ?>
')"><tspan
                            id="tspan3146"
                            style="font-size:10px;fill:#ffffff">BORN</tspan></text>

                    <text
                        xml:space="preserve"
                        x="392.09995"
                        y="100.50537"
                        class="reg<?php echo $this->_tpl_vars['modvars']['ZSELEX']['da_region7']; ?>
 fillAll"
                        style="font-size:10px;font-style:normal;font-weight:normal;text-align:start;text-anchor:start;fill:#000000;fill-rule:evenodd;font-family:Calibri"
                        id="seven1" onclick="regionSelect('<?php echo $this->_tpl_vars['modvars']['ZSELEX']['da_region7']; ?>
','BORNHOLM');"  onmouseover="Orange(this.id,3,'<?php echo $this->_tpl_vars['modvars']['ZSELEX']['da_region7']; ?>
')" onmouseout="Gray(this.id,3,'<?php echo $this->_tpl_vars['modvars']['ZSELEX']['da_region7']; ?>
')"><tspan
                            id="tspan3146"
                            style="font-size:10px;fill:#ffffff">HOLM</tspan></text>
                </svg>

            </div>
            <div class="SVGDescription left">

                <div class="Places">
                    <h2 id="setRegion"></h2>
                    <div id="regionCities">
                    </div>
                </div>
            </div>
        </div>
                            </div>
    </div>
</div>