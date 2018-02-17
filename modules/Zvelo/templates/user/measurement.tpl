<form name="myform" id="myform"  action="{modurl modname="Zvelo" type="user" func="measurement"}" method="post">
     <input type="hidden" id="mtype" name="formElement[type]" value="">
     <input type="hidden" id="murl" name="formElement[murl]" value="">
   
                <div class="text_box">
                	<div class="text_box_inner">
                    	<p class="top">
                        <select name="formElement[gender]" class="orange_box w71" style="height:23px">
                            <option value=''>{gt text='Select'}</option>
                            <option value='male' {if $msrmrnt.gender eq 'male'}selected{/if}>{gt text='Male'}</option>
                            <option value='female' {if $msrmrnt.gender eq 'female'}selected{/if}>{gt text='Female'}</option>
                        </select>
                        </p>
                        <p class="top1"><input type="text" autocomplete="off" name="formElement[height]" value="{$msrmrnt.functionalheight}" class="orange_box w71" />{gt text='cm'}</p>
                        <p class="top2"><input type="text" autocomplete="off" name="formElement[shoulderheight]" value="{$msrmrnt.shoulderheight}"  class="orange_box w71" />{gt text='cm'}</p>
                        <p class="top3"><input type="text" autocomplete="off" name="formElement[shoulderwidth]" value="{$msrmrnt.shoulderwidth}" class="orange_box w71" />{gt text='cm'}</p>
                        <p class="top4"><input type="text" autocomplete="off" name="formElement[pelvicboneheight]" value="{$msrmrnt.pelvicboneheight}" class="orange_box w71" />{gt text='cm'}</p>
                        <p class="top5"><input type="text" autocomplete="off" name="formElement[fistheight]" value="{$msrmrnt.fistheight}"  class="orange_box w71" />{gt text='cm'}</p>
                        <p class="top6"><input type="text" autocomplete="off" name="formElement[weight]" value="{$msrmrnt.weight}" class="orange_box w71" />{gt text='cm'}</p>
                    </div>
                </div>
                <div class="white_line_sec">
                	<hr class="line1" />
                    <hr class="line2" />
                    <hr class="line3" />
                  <div class="sholder_line">
                  <hr class="line4" />
                  </div>
                  <hr class="line5" />
                  <hr class="line6" />
                  <hr class="line7" />
                </div>
                <div class="label_section">
                	<p class="right_label1">{gt text='Gender'}</p>
                	<p class="right_label2">{gt text='Functional height'}</p>
               		<p class="right_label3">{gt text='Shoulder height'}</p>
                	<p class="right_label4">{gt text='Shoulder width'}/2</p>
                	<p class="right_label5">{gt text='Pelvic bone height'}</p>
                	<p class="right_label6">{gt text='Fist height'}</p>
                	<p class="right_label7">{gt text='Weight'}</p>
                </div>
        
        
         </form>