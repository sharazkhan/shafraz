<form name="myform" id="myform" class="z-form" action="{modurl modname="Zvelo" type="user" func="clientinfo"}" method="post">
        <input type="hidden" id="mtype" name="formElement[type]" value="">
        <input type="hidden" id="murl" name="formElement[murl]" value="">

<div class="address_sec">
    <div class="addr asec1">
        <div class="text_sep1">{gt text='Clientno'}<br /><input readonly value="{$customerInfo.customer_id}" type="text" class="grey_box w59" />
        </div>
        <div class="text_sep2">{gt text='Created'}<br /><input readonly value="{$customerInfo.cr_date}" type="text" class="grey_box w104" />

        </div>

    </div>

    <div class="addr asec2">{gt text='First name'}<br />
        <input type="text" autocomplete="off" name="formElement[first_name]" value="{$customerInfo.first_name}" class="orange_box full_width1" />
    </div>
    <div class="addr asec2">{gt text='Last name'}<br />
        <input type="text" autocomplete="off" name="formElement[last_name]" value="{$customerInfo.last_name}" class="orange_box full_width1" />
    </div>

    <div class="addr asec2 ht58">{gt text='Address'}<br />
        <input type="text" autocomplete="off"  name="formElement[address]" value="{$customerInfo.address}" class="orange_box full_width1" /><br />
        <input type="text" autocomplete="off"  name="formElement[address2]" value="{$customerInfo.address2}" class="orange_box full_width1 slight_top" /><br />
    </div>
    <div class="addr asec1">
        <div class="text_sep1">{gt text='ZIP'}<br /><input autocomplete="off" name="formElement[zip]" value="{$customerInfo.zipcode}" type="text" class="orange_box w59" /> </div>
        <div class="text_sep2">{gt text='City'}<br /><input autocomplete="off" name="formElement[city]" value="{$customerInfo.city}" type="text" class="orange_box w104" /></div>                        
    </div>
    <div class="addr asec2">{gt text='Phone'}<br />
        <input type="text" autocomplete="off" name="formElement[phone]" value="{$customerInfo.phone}" class="orange_box full_width1" />
    </div>
    <div class="addr asec2">{gt text='Email'}<br />
        <input type="text" autocomplete="off" name="formElement[email]" value="{$customerInfo.email}" class="orange_box full_width1" />
    </div>
    <div class="addr asec2 ht100">{gt text='Comments'}<br />
        <textarea name='formElement[comment]' class="orange_box full_width1 ht90" rows="5" >{$customerInfo.comments}</textarea>

    </div>

</div>

<div class="address_image_sec">

    <div class="tophr_sec">
        <hr class="tophr"/>                    	
    </div>
    <div class="tophr_sec2">
        <hr class="tophr2"/>

    </div>
    <div class="tophr_sec3 right">                     	
        <div>
            <div class="top_box"></div>
            <div class="chest_row">  <hr class="tophr3"/> </div>                            
        </div>
    </div>

    <div class="tophr_sec4"> 
        <div class="bot_box"></div>              	
    </div>

    <div class="tophr_sec5"> 
        <div class="margin40"><hr class="tophr3"/></div>
        <div><hr class="tophr3"/></div>
    </div>

    <div class="tophr_sec3 right">                     	
        <div>
            <div class="top_box"></div>

        </div>
    </div>

    <div class="tophr_sec4"> 
        <div class="bot_box"></div>              	
    </div>

    <div class="tophr_sec5"> 
        <div class="margin30"><hr class="tophr3"/></div>

    </div>

</div>
<div class="address_label_sec">

    <p class="LabelMap l_top1">{gt text='Gender'}</p>
    <p class="LabelMap l_top2">{gt text='Functional height'}</p>
    <p class="LabelMap l_top3">{gt text='Shoulder height'}</p>
    <p class="LabelMap l_top4">{gt text='Shoulder width'}/2</p>
    <p class="LabelMap l_top5">{gt text='Arm length'}</p>
    <p class="LabelMap l_top6">{gt text='Pelvic bone height'}</p>
    <p class="LabelMap l_top7">{gt text='Fist height'}</p>
    <p class="LabelMap l_top8">{gt text='Leg length'}</p>
    <p class="LabelMap l_top9">{gt text='Weight'}</p>


</div>
<div class="address_textbox_sec">
    <p class="LabelMap l_top1_tx"><input readonly value="{$customerInfo.gender}" type="text" class="grey_box w61" />{gt text='cm'}</p>
    <p class="LabelMap l_top2_tx"><input readonly value="{$msrmrnt.functionalheight}" type="text" class="grey_box w61" />{gt text='cm'}</p>
    <p class="LabelMap l_top3_tx"><input readonly value="{$msrmrnt.shoulderheight}" type="text" class="grey_box w61" />{gt text='cm'}</p>
    <p class="LabelMap l_top4_tx"><input readonly value="{$msrmrnt.shoulderwidth}" type="text" class="grey_box w61" />{gt text='cm'}</p>
    <p class="LabelMap l_top5_tx"><input readonly value="" type="text" class="grey_box w61" />{gt text='cm'}</p>
    <p class="LabelMap l_top6_tx"><input readonly value="{$msrmrnt.pelvicboneheight}" type="text" class="grey_box w61" />{gt text='cm'}</p>
    <p class="LabelMap l_top7_tx"><input readonly value="{$msrmrnt.fistheight}" type="text" class="grey_box w61" />{gt text='cm'}</p>
    <p class="LabelMap l_top8_tx"><input readonly value="" type="text" class="grey_box w61" />{gt text='cm'}</p>
    <p class="LabelMap l_top9_tx"><input readonly value="{$msrmrnt.weight}" type="text" class="grey_box w61" />{gt text='cm'}</p>


</div>
    </form>
