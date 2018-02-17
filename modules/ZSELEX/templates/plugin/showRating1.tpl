 {pageaddvar name="stylesheet" value="themes/CityPilot/style/rating.css"}
     <input type='hidden' id='shop_id' value={$shop_id}>
           <div class="r">
                    <div class="rating">
                      {section name=starcount loop=$rating|floor}
                       {assign var=i value=$smarty.section.starcount.iteration|intval}
                        <div class="star" id={$i}></div>
                      {/section}
                    </div>
                    <div class="transparent">
                        <div class="star" id="1"></div>
                        <div class="star" id="2"></div>
                        <div class="star" id="3"></div>
                        <div class="star" id="4"></div>
                        <div class="star" id="5"></div>
                        <div class="Chat"><span>{$ratingCount}</span></div>
                       {*<div class="votes"> ({$dec_rating}/5, {$ratingCount} {$v})</div>*}
                    </div>
                   
                </div>
              {if $isLoggedIn}
                  {if $userRating > 0}
               <br>
                <div class="userRated">
                    {gt text='You rated this as'} <b>{$userRating}</b>
                </div>
                {/if}
                {/if}
            
         