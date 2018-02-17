    <img src="{$bannerImage}" id="cropbox">
     <div align="center">
    <form class="z-form" id="employee_form" name="employee_form" action="#" method="post">
       
    <input type="hidden" id="x" name="x" />
    <input type="hidden" id="y" name="y" />
    <input type="hidden" id="w" name="w" />
    <input type="hidden" id="h" name="h" />
    <input type="hidden" id="file_name" value="{$file_name}" />
      <div class="z-buttons z-formbuttons">
        <a href="javascript:closeWindow()"  title="{gt text="Cancel"}">{img modname=core src="button_cancel.png" set="icons/extrasmall" __alt="Cancel" __title="Cancel"} {gt text="Cancel"}</a>
       <button onClick="return saveImage();" id="crop_save"  type="button"  name="action" value="deleteemployee" title="{gt text='Save Image'}">
             {img src='button_ok.png' modname='core' set='icons/extrasmall' __alt='Save Image' __title='Save Image'}
             {gt text='Save'}
       </button>
       </div>
   
    </form>
        </div>