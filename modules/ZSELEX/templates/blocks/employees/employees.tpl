 {if $perm}
<a href="{modurl modname="ZSELEX" type="admin" func="shopsettings" shop_id=$smarty.request.shop_id}#aEmployees" class="edit-link"><i class="fa fa-pencil-square" aria-hidden="true"></i>
    {gt text="Edit Employees"}
</a>
{/if}
<div class="products-wrap clearfix col-sm-12 employees-list">
    <div class="product-head clearfix">
        <h3 class="pull-left">{gt text='Employees'}</h3>
    </div>
    <div class="row">
        {foreach item='item' key=index from=$employees}
        {foreach item='employee'  from=$item}
        <div class="col-sm-6 hover-border">
            <div class="thumbnail clearfix">
                <div class="pro-image">
                    <img src="{$baseurl}zselexdata/{$smarty.request.shop_id}/employees/thumb/{$employee.emp_image}" class="img-responsive" alt="">
                </div>
                <div class="btm-product-name">
                    <h4>{$employee.name}</h4>
                    <p>{$employee.email}</p>
                    {if $employee.phone}<p>{$employee.phone}{/if}
                        {if $employee.phone && $employee.cell},&nbsp;{else}{if $employee.phone}</p>{/if}{if $employee.cell}<p>{/if}{/if}
                        {if $employee.cell}{$employee.cell}</p>{/if}
                    <p>{$employee.job}</p>
                </div>
            </div>
        </div>
        {/foreach}
        {/foreach}


    </div>
</div>