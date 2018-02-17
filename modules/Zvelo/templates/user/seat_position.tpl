<script>

function selectSeatPos(value){
//alert(val);
jQuery('#seatposition').val(value);
jQuery(".selected").removeClass("active");
jQuery(".selected").removeClass("selected");
jQuery("#"+value+"li").addClass( "selected" );
jQuery(".selected").addClass( "active" );
//jQuery("#edit" + this.id).removeClass("edit");
}

</script>
  <form name="myform" id="myform" class="z-form" action="{modurl modname="Zvelo" type="user" func="seatposition"}" method="post">
        <input type="hidden" id="mtype" name="formElement[type]" value="">
        <input type="hidden" id="murl" name="formElement[murl]" value="">
        <input type="hidden" id="seatposition" name="formElement[seatposition]" value="{$seatposition.seatposition}">
<div class="thumb_nails_sec">
                	
                    	<div class="riding_positon_container">
                        	<ul class="rider">
                            	<li class="first {if $seatposition.seatposition eq '1'}selected active{/if}" id="1li">
                                <div class="rid_pos_img" id="1" style="cursor:pointer" onClick="selectSeatPos(this.id)"><img src="{$themepath}/images/Pos1.png"></div>
                                <div class="orange_head">1</div>
                                </li>
                                <li class="{if $seatposition.seatposition eq '2'}selected active{/if}" {*class="active"*} id="2li">
                                <div  class="rid_pos_img" id="2" style="cursor:pointer" onClick="selectSeatPos(this.id)"><img src="{$themepath}/images/Pos2.png"></div>
                                  <div class="orange_head">2</div>
                                </li>
                                <li class="{if $seatposition.seatposition eq '3'}selected active{/if}" id="3li">
                                <div  class="rid_pos_img" id="3" style="cursor:pointer" onClick="selectSeatPos(this.id)"><img src="{$themepath}/images/Pos3.png"></div>
                                <div class="orange_head">3</div>
                                </li>
                            </ul>
                        	
                            
                            
                        </div>
                	
                </div>
                
                <div class="privew_sec">
                	 {bicycledetail}
                    
                	
                </div>
                
            </form>	