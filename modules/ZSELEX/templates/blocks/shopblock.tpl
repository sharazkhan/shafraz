 
<input type='hidden' id='origLimit' name='origLimit' value={$amount}>
<input type='hidden' id='startval' name='startval' value='0'>
<input type='hidden' id='endval' name='endval' value={$amount}>


<dt>
<div id="blockshop"  style="width:400px;  height:auto; display:table-cell; margin-bottom:5px;">
 {*
    <div>

        {if $allCount > $amount}
        <span  onClick=paginateNext() style='cursor:pointer; float:right; color:#605d59; font-weight:bold'>{gt text="Next"} &nbsp;<img src='{$baseurl}images/RtArrow.jpg' style='vertical-align:top; margin-top:1px'></span>
        {/if}
        {if $allCount gt 0}
        <span style='cursor:pointer; float:left;  margin-left:120px;  color:#605d59; '>{gt text="Total Shops :"} {$allCount}</span>
        {/if}
    </div>
    <br>
    {foreach from=$zshop item='item'}
      

    <div style='border:solid 1px #CCC; padding-left:15px; padding-top:15px; padding-bottom:5px'> 
         {if $item.default_img_frm eq 'fromgallery'}
          {if $item.image_name neq ''}
           <div>
         <a href='{modurl modname='ZSELEX' type='user' func='site'  id=$item.SID}' target='_blank'> <img src="{$baseurl}zselexdata/{$item.uname}/minisitegallery/thumb/{$item.image_name}"></a>
           </div>
           {/if} 
        
         {/if}  
         
           {if $item.default_img_frm eq 'fromshop'}
        
        {if $item.name neq ''}
        <div>
      <a href='{modurl modname='ZSELEX' type='user' func='site'  id=$item.SID}' target='_blank'> <img src="{$baseurl}zselexdata/{$item.uname}/minisiteimages/thumb/{$item.name}"></a>
        </div>
        {/if}
             {/if}  
       
        <div>
            <b>Shop Name</b>:
            <a href='{modurl modname='ZSELEX' type='user' func='site'  id=$item.SID}' target='_blank'>{$item.shop_name}</a>    
       
        </div>

        <div><b>{gt text="Address"}</b>: {$item.address}</div>

        <div><b>{gt text="Telephone"}</b>: {$item.telephone}</div>

        <div><b>{gt text="Fax"}</b>: {$item.fax}</div>

        <div><b>{gt text="Email"}</b>: {$item.email} </div>
        
         <div align="right">

            {if $admin neq 1}
            {if $item.quantity gt 0 AND $item.minishop_configured gt 0}
            <a  style="color:green" href='{modurl modname='ZSELEX' type='user' func='shop' shop_id=$item.SID}' target='_blank'> {gt text="Go To Shop"} </a>
            {else}
            
            {/if}

            {else}
                {if $item.minishop_configured gt 0}
            <a  style="color:green" href='{modurl modname='ZSELEX' type='user' func='shop'  shop_id=$item.SID}' target='_blank'> {gt text="Go To Shop"}</a>
                {/if}
            {/if}
            
             {seturl shop_id=$item.SID}
             
        </div>
            
  
    </div>

    {/foreach}
    
     <div>

        {if $allCount > $amount}
        <span  onClick=paginateNext() style='cursor:pointer; float:right; color:#605d59; font-weight:bold'>{gt text="Next"} &nbsp;<img src='{$baseurl}images/RtArrow.jpg' style='vertical-align:top; margin-top:1px'></span>
        {/if}
    </div>
    *}
</div>
    </dt>



 {*
             <div>{fblike url=$url layout='horizontal'}</div>
             <div>{fblike url=$url layout='standard' width='350' height='25' action='recommend' font='tahoma'}</div>
             <div>{twitter url=$url title=$item.shop_name}</div>
             <div>{googleplus title=$item.shop_name description=$item.address}</div>
             <div>{sharethis id=$item.SID url=$url title=$item.shop_name}</div>
             *}
             