
{**********Override Mail Template**************}

{mailcss}


{strip}
{if $reginfo.pendingApproval}
    {gt text='New registration pending approval' assign='heading'}
    {gt text='New registration pending approval: %s' tag1=$reginfo.uname assign='subject'}
{elseif $reginfo.pendingVerification}
    {gt text='New registration pending e-mail verification' assign='heading'}
    {gt text='New registration pending verification: %s' tag1=$reginfo.uname assign='subject'}
{else}
    {gt text='New user activated' assign='heading'}
    {gt text='New user activated: %s' tag1=$reginfo.uname assign='subject'}
{/if}
{assign var='sitelink' value='<a href="%1$s">%2$s</a>'|sprintf:$siteurl:$sitename}
{/strip}


<table {$headerTable}>
    <tr>
        <td {$header1stTd}>
            
              <a href="http://citypilot.dk/index.php"><img  src="{$themepath}/images/Logo.png" class="logo" /></a>
        </td>
           <td {$header2ndTd}>
             <h3 {$headerH3}>
                 
                    {$subject}
                </h3>

            </td>
    </tr>
</table>

<table {$contentTable}>
    <tr>
        <td {$contentTd} >
            <!-- Contents Starts ---->
<p>&nbsp;</p>
          <h3>{$heading}</h3>
          <!--- additional text --->          
{if $modvars.ZSELEX.enabled_mail_text}
<p>{$modvars.ZSELEX.additional_mail_text|nl2br}</p>
{/if}
 <!------------------------->
          <p>{gt text='A new user account has been activated on %1$s.' tag1=$sitelink}
{if $adminCreated}{gt text='It was created by an administrator or sub-administrator logged in as \'%1$s\'.' tag1=$adminUname}
{/if}{gt text='The account details are as follows:'}</p>
    
<p>{gt text='User name: \'%s\'.' tag1=$reginfo.uname}</p>
<p>&nbsp;</p>
<!-- Contents Ends ---->
       
   </td>
    </tr>
</table>

<table  {$footerTable}>
    <tr>
        <td {$footerTd}>
                <img src="{$themepath}/images/FooterLogo.png"  {$footerLogo}/> <img src="{$themepath}/images/bg.png"  />
        </td>
     </tr>
     
</table>