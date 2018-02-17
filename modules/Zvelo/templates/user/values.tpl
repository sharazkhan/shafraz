<form name="myform" id="myform" class="z-form" action="{modurl modname="Zvelo" type="user" func="values"}" method="post">
        <input type="hidden" id="mtype" name="formElement[type]" value="">
        <input type="hidden" id="murl" name="formElement[murl]" value="">

<div class="thumb_nails_sec">
                	<div class="config_cont">
                    	<div class="final_conf_img"><img src="{$themepath}/images/BikeMesureImage.png"></div>
                        <div class="final_conf_value">
                        	<p>
                            	Value 1<br />
                                <input type="text" name="formElement[value1]" value="{$values.value1}" class="orange_box w75" />
                            </p>
                            
                            <p>
                            	Value 2<br />
                                <input type="text" name="formElement[value2]" value="{$values.value2}" class="orange_box w75" />
                            </p>
                            
                            <p>
                            	Valu 3<br />
                                <input type="text" name="formElement[value3]" value="{$values.value3}" class="orange_box w75" />
                            </p>
                            
                            <p>
                            	Value 4<br />
                                <input type="text" name="formElement[value4]" value="{$values.value4}" class="orange_box w75" />
                            </p>
                        </div>
                    
                    </div>
                	
                </div>
                
                <div class="privew_sec">
                 {bicycledetail}
                </div><!-- -->
                </form>