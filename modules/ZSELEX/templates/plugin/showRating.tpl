  
{pageaddvar name="stylesheet" value="themes/CityPilotResponsive/style/rating.css"}
{pageaddvar name='javascript' value="$themepath/javascript/rate.js"}
           <input type='hidden' id='shop_id' value={$shop_id}>
           <div class="r">
               <div class="rating-wrap">
                    <div class="rating-star">
                      {section name=starcount loop=$dec_rating}
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
                       
                    </div>
               </div>
                        <div class="review-count">{$ratingCount}</div>
                   
                </div>
                   
               
                        {*
              {if $isLoggedIn}
                  {if $userRating > 0}
               <br>
                <div class="userRated">
                    {gt text='You rated this as'} <b>{$userRating}</b>
                </div>
                {/if}
                {/if}
                *}
            
         