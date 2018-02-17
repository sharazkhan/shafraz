 
                    {* {if !$expired}{gt text='<b>Update This Event</b> : Drag and Drop new Event image or document to this box or click anywhere to edit Event information'}{/if}*}
                    <table id="zselex_admintable" class="z-datatable" style="width:600px;table-layout:fixed; margin: 0px;">
                        <thead>
                            <tr>
                                <th>{gt text='Title'}</th>
                                <th>{gt text='Body Text'}</th>
                                <th>{gt text='Created Date'}</th>
                                <th>{gt text='Status'}</th>
                                <th>{gt text='Image'}</th>
                            </tr>
                        </thead>  
                        <tbody id="tbody{$item.text_id}">
                            <tr class="{cycle values='z-odd,z-even'}">
                                <td width="1px">{$item.headertext|safetext|wordwrap:14:"\n":true}</td>
                                <td width="1px">{shorttext text=$item.bodytext|strip_tags|nl2br|wordwrap:14:"\n":true len=50}</td>
                                <td>{$item.cr_date}</td>
                                <td>{$aStatus[$item.active]|safetext}</td>
                                <td>
                                    {if $item.extension eq 'pdf'}
                                        {assign var="imageExist" value="zselexdata/`$shop_id`/ztext/pdf/thumb/`$item.image`"}
                                         {if is_file($imageExist)}
                                         <img style="max-height:93px;max-width:93px" src="{$baseurl}zselexdata/{$shop_id}/ztext/pdf/thumb/{$item.image}">
                                        {/if}
                                     {else}    
                                        {assign var="imageExist" value="zselexdata/`$shop_id`/ztext/thumb/`$item.image`"}
                                        {if is_file($imageExist)}
                                        <img style="max-height:93px;max-width:93px" src="{$baseurl}zselexdata/{$shop_id}/ztext/thumb/{$item.image}">
                                       {/if}
                                   {/if}
                                </td>
                                
                            </tr>
                        </tbody>
                    </table>
               