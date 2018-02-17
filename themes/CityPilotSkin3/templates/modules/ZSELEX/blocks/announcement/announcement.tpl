   
  {assign var='variablename' value="\n"|explode:$text}
  <div class="CircleContainer">
            <div class="CircleBanner">
                <div class="BannerSloppedText">
                 <h3>{$variablename[0]}</h3>
                 <h6>{$variablename[1]}</h6>
                 <p>{$variablename[2]}</p>
                </div>
            </div>
      </div>
                
