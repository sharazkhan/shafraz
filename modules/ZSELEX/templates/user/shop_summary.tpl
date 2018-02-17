<div class="Admin_Left left">
    {*{pager rowcount=$count limit=2 posvar='startnum'}*}
    <h2 class="Backend_h2">{gt text='Welcome Back'}!</h2>
    <p>{gt text='Welcome to the administration part.Choose one of the following to continue'}...</p>
    <div class="SettingImgMain">
        <div class="Section">
            <a href="{modurl modname="ZSELEX" type="admin" func="quickbuy" shop_id=$shop_id}">
               <div class="Settings_Image_Section">
                    <div class="Setting_section">
                        <div class="Setting_section_image">
                            <img src="themes/CityPilot/images/HomeOrange.png" />
                        </div>
                        <div class="Setting_section_Orange">
                            <p>{gt text='Purchase'} <span class="ArrowShade"></span></p>
                        </div>
                    </div>
                </div></a>
            <p class="SectionP">{gt text='Buy more services to enhance the experience for your users thus increasing your turnover'}. </p>
        </div>
        <div class="Section Middle">
            <div class="Settings_Image_Section">
                <a href="{modurl modname="ZSELEX" type="admin" func="editservices" shop_id=$shop_id}">
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
        </div>
        <div class="Section">
            <a href="{modurl modname="ZSELEX" type="admin" func="shopinnerview" shop_id=$shop_id}">
               <div class="Settings_Image_Section">
                    <div class="Setting_section">
                        <div class="Setting_section_image">
                            <img src="themes/CityPilot/images/Spaner.png" />
                        </div>
                        <div class="Setting_section_Orange">
                            <p>{gt text='Advanced'} <span class="ArrowShade"></span></p>
                        </div>
                    </div>
                </div>
            </a>
            <p class="SectionP">{gt text='Go to the Advanced View of the backend'} </p>
        </div>
    </div>
</div>


<div class="Admin_Right left">
    <div class="admin_right_top">
        <h4 class="Graph"> {$shop_info.shop_name} , {$shop_info.city_name} </h4>
        <p class="admin_right_p">
            {gt text='I have had'}:<br />
                    <span class="orange_text">84</span> {gt text='new visitors lately'} <span class="orange_text">1250</span> {gt text='visitors in total'} <br /><br />
            <span class="orange_text">3</span> {gt text="rated your page"} <span class="orange_text">16</span> {gt text="has laid a comment"}<br /><br />
           {gt text="I've sold"}:<br /><span class="orange_text">19</span> {gt text="Products in"} July
            <span class="orange_text">201</span>  {gt text="products in total"}
        </p>
    </div>

    {blockposition name='backend-right' assign=backendblock}
    {if !empty($backendblock)}
    <div class="admin_right_top">
	    {$backendblock}
    </div>
    {/if}

    <div class="admin_right_top">
        <h4 class="mail">{gt text="Message from City Pilot"}</h4>
        <p class="admin_right_p">
            Vi har opdateres shopsystemet med 4 nyeulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede<br />
            <a href="#">Læs mere her>></a>
        </p>
    </div>
</div>