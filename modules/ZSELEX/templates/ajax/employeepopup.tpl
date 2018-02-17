
<div class="z-admin-content-pagetitle">
      <h3>{gt text='Edit Employee'}</h3>
</div>
      <form class="z-form" id="employeedelete_form" name="employee_form" action="{modurl modname='ZSELEX' type='admin' func='saveEmployee' shop_id=$smarty.request.shop_id}" method="post">
            <input type="hidden" name="csrftoken" value="{insert name="csrftoken"}" />
            <input type="hidden" name="formElements[emp_id]" id="image_id" value="{$employee.emp_id}">
            <input type="hidden" name="formElements[shop_id]" id="shop_id" value="{$smarty.request.shop_id}">
            <input type="hidden" name="action" value="deleteemployee">
      </form>
<form class="z-form" id="employee_form" name="employee_form" action="{modurl modname='ZSELEX' type='admin' func='saveEmployee' shop_id=$smarty.request.shop_id}" method="post">
       <div align="center">
            <input type="hidden" name="csrftoken" value="{insert name="csrftoken"}" />
            <input type="hidden" name="formElements[emp_id]" id="image_id" value="{$employee.emp_id}">
            <input type="hidden" name="formElements[shop_id]" id="shop_id" value="{$smarty.request.shop_id}">
            <div class="z-formrow">
                <label for="shop_info">{gt text='Name'}:</label>
                <input type="text" name="formElements[name]" value="{$employee.name}">
            </div>

            <div class="z-formrow">
                <label for="shop_info">{gt text='Phone'}:</label>
                <input type="text" name="formElements[phone]" value="{$employee.phone}">
            </div>
            <div class="z-formrow">
                <label for="shop_info">{gt text='Cell'}:</label>
                <input type="text" name="formElements[cell]" value="{$employee.cell}">
            </div>
            <div class="z-formrow">
                <label for="shop_info">{gt text='Email'}:</label>
                <input type="text" name="formElements[email]" value="{$employee.email}">
            </div>
             <div class="z-formrow">
                <label for="shop_info">{gt text='Job'}:</label>
                <textarea name="formElements[job]">{$employee.job}</textarea>
            </div>
             <div class="z-formrow">
                <label for="sort_order">{gt text='Sort Order'}:</label>
                <input type="text" name="formElements[sort_order]" value="{$employee.sort_order}">
            </div>
       <div class="z-buttons z-formbuttons">
            {button src="button_ok.png" set="icons/extrasmall" __alt="Save" __title="Save" __text="Save" __name="action" __value="saveimageemployee"}
            <a href="javascript:closeWindow()"  title="{gt text="Cancel"}">{img modname=core src="button_cancel.png" set="icons/extrasmall" __alt="Cancel" __title="Cancel"} {gt text="Cancel"}</a>
           
       <button onClick="return deleteEmployee();" id="employee_delete"  type="button"  name="action" value="deleteemployee" title="{gt text='Delete Employee'}">
             {img src='14_layer_deletelayer.png' modname='core' set='icons/extrasmall' __alt='Delete Employee' __title='Delete Employee'}
             {gt text='Delete Employee'}
         </button>
       </div>

      </div>
    </form>