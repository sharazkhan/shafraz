 
{*{pageaddvar name='javascript' value="modules/ZSELEX/javascript/slide/cycle-plugin.js"}*}
{pageaddvar name='javascript' value="$themepath/javascript/exclusive_events.js?v=1.1"}
<section class="slider-wrapper">
        <div class="container">
            <div class="banner-slider">
                <ul class="bxslider clearfix"  id="slideshow">
                    {*<li><img src="http://placehold.it/1170x300" width="1170" height="300"></li>
                    <li><img src="http://placehold.it/1170x300" width="1170" height="300"></li>*}
                    
                </ul>
            </div>
        </div>
    </section>


<input type="hidden" id="old_event_id" value="{$events.old_event_id}">
<input value="{$events.event_count}" type="hidden" id="event_count">
<input type="hidden" id="first_load" value="1">