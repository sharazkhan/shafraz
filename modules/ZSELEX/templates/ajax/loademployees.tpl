  <ul class="ImagePrivew">
            {foreach item='item' key=index from=$employees}
            <li onClick="editEmployee({$item.emp_id})" style="background: url({$baseurl}zselexdata/{$shop_id}/employees/thumb/{$item.emp_image|replace:' ':'%20'}) no-repeat center center;cursor:pointer" >
                <a  href="#" style="width:98px; height:98px; display: block; margin-top: 105px;">

                </a>
            </li>
            {/foreach}    
  </ul>