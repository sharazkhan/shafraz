
    {*{pager rowcount=$count limit=2 posvar='startnum'}*}
    <h2 class="Backend_h2">{gt text='Welcome'} {$fullusername}</h2>
    <p>{gt text='Welcome to the administration part.Choose one of the following to continue'}...</p>
    <div class="SettingImgMain NewSettingImgMain">
        <div class="Section  Margin36">
            <a href="{modurl modname="ZSELEX" type="user" func="site" shop_id=$shop_id}">
               <div class="Settings_Image_Section">
                    <div class="Setting_section">
                        <div class="Setting_section_image">
                            <img src="themes/{$current_theme}/images/smallhome.png" />
                        </div>
                        <div class="Setting_section_Orange">
                            <p>{gt text='My Site'} <span class="ArrowShade"></span></p>
                             <p class="SectionP">{gt text='Click here to edit your site. You can update your products / events and more.'}. </p>
                        </div>
                    </div>
                </div></a>
           
        </div>
        
        {*<div class="Section Middle">
            <div class="Settings_Image_Section">
                <a href="{modurl modname="ZSELEX" type="admin" func="editservices" shop_id=$smarty.request.shop_id}">
                    <div class="Setting_section">
                        <div class="Setting_section_image">
                            <img src="themes/CityPilot/images/EditeOrange.png" />
                        </div>
                        <div class="Setting_section_Orange">
                            <p>{gt text='Edit'} <span class="ArrowShade"></span></p>
                        </div>
                    </div>
                </a>
            </div>
            <p class="SectionP">{gt text='Change the options in your services'}. </p>
        </div>*}
        <div class="Section">
            <a href="{modurl modname="ZSELEX" type="admin" func="shopservices" shop_id=$shop_id}">
               <div class="Settings_Image_Section">
                    <div class="Setting_section">
                        <div class="Setting_section_image">
                            <img src="themes/{$current_theme}/images/Spaner.png" />
                        </div>
                        <div class="Setting_section_Orange">
                            <p>{gt text='My Services'} <span class="ArrowShade"></span></p>
                             <p class="SectionP">{gt text='Here is an overview of the services you purchased. Do you need additional services, you can buy them here.'} </p>
                        </div>
                    </div>
                </div>
            </a>
           
        </div>
    </div>
        
         {blockposition name='shopsummary-menu' assign=shopsummarymenu}
           {if !empty($shopsummarymenu)}
        <div class="MyPageBottom">{gt text='Settings to the My Side'}</div>
         <div class="GrayCapsule">
        {$shopsummarymenu}
         </div>
         {/if}
         {if $smarty.request.func eq 'shopsummary'}
	   <div class="owner-content">
       	            {blockposition name='owner-content'} <!-- new position added  -->
  				
    	    </div>
        {/if}



