
  <form name="myform" id="myform" class="z-form" action="{modurl modname="Zvelo" type="user" func="bicycle"}" method="post">
        <input type="hidden" id="mtype" name="formElement[type]" value="">
        <input type="hidden" id="murl" name="formElement[murl]" value="">
        <input type="hidden" id="bicycle_id" name="formElement[bicycle_id]" value="{$bicycleSelected.bicycle_id}">

<div class="thumb_nails_sec">
                
                	<div class="thumb_container">
                    	<ul class="privew">
                           {*<li class="first">
                            	<div class="privew_img"><img src="{$themepath}/images/cityIMG.png"></div>
                                <h4>City</h4>
                            </li>
                            <li>
                            	<div class="privew_img"><img src="{$themepath}/images/trekkingIMG.png"></div>
                                <h4>Trekking</h4>
                            </li>
                            <li>
                            	<div class="privew_img"><img src="{$themepath}/images/crossIMG.png"></div>
                                <h4>Cross</h4>
                            </li>
                            <li>
                            	<div class="privew_img"><img src="{$themepath}/images/Fitness.png"></div>
                                <h4>Fitness</h4>
                            </li>
                            
                            <li  class="first">
                            	<div class="privew_img"><img src="{$themepath}/images/MTBcrosscountryIMG.png"></div>
                                <h4>MTB Cross</h4>
                            </li>
                            
                            <li  class="active">
                            	<div class="privew_img"><img src="{$themepath}/images/MTBdhfrIMG.png"></div>
                                <h4>MTB DH/FR</h4>
                            </li>
                            
                            <li>
                            	<div class="privew_img"><img src="{$themepath}/images/roadraceIMG.png"></div>
                                <h4>Road racer</h4>
                            </li>
                            
                            <li>
                            	<div class="active"><img src="{$themepath}/images/triathlonIMG.png"></div>
                                <h4>Triathlon</h4>
                            </li>*}
                            {foreach from=$bicycles item='bicycle' key='key'}
                                <li id="{$bicycle.bicycle_id}" class="{if $key eq 0}first{/if}{if $bicycleSelected.bicycle_id eq $bicycle.bicycle_id} chosen active {/if}" onClick="bicycleDetail(this.id)">
                            	<div class="privew_img"><img src="{$themepath}/images/{$bicycle.imagename}"></div>
                                <h4>{$bicycle.iconname}</h4>
                                </li>
                            {/foreach}
                        </ul>
                    </div>
                </div>
                                
              <div class="privew_sec" id="bicycleDetail">
                    {*<div class="preview_img_sec">
                    	<div class="preview_main"><img src="{$themepath}/images/BikeImage.png" />
                    	</div>
                        <div class="preview_decription">
                        	<h5>&lt;bikename&gt;</h5>
                            <p>&lt;<bikename>bike description with nl2br&gt;</p>
                    	</div>
                    </div>
                    <div class="preview_thum_sec">
                    	<div class="thum_preview_img">
                       		<img src="{$themepath}/images/BikeIcon.png">
                        </div>
                        <p>&lt;bikename&gt;<br />
							&lt;bikeno&gt;
                        </p>
                    </div>*}
                    {bicycledetail}
                	
                </div>
                                   
               </form> 