{**********Override Mail Template**************}

{mailcss}

<table {$headerTable}>
    <tr>
        <td {$header1stTd}>
            
              <a href="http://citypilot.dk/index.php"><img  src="{$themepath}/images/Logo.png" class="logo" /></a>
        </td>
        <td {$header2ndTd}>
             <h3 {$headerH3}>
                    {gt text='\'%1$s\' account information' tag1=$sitename assign='subject'}
                    {$subject}
             </h3>
        </td>
    </tr>
</table>

<table {$contentTable}>
    <tr>
        <td {$contentTd} >
<p>&nbsp;</p>
           <p>{gt text='Hello!'}</p>
 <!--- additional text --->          
{if $modvars.ZSELEX.enabled_mail_text}
<p>{$modvars.ZSELEX.additional_mail_text|nl2br}</p>
{/if}
 <!------------------------->

{if $adminRequested}
<p>{gt text='The administrator at %s requested that you receive your user name via e-mail.' tag1=$sitename}</p>
{else}
<p>{gt text='Someone with the IP address %1$s has just requested the account information at %2$s associated with this e-mail address.' tag1=$hostname tag2=$sitename}</p>
{/if}

<p>{gt text='The user name for your account is: %1$s' tag1=$uname}</p>

{if !empty($authentication_methods)}
<p>{gt text='You may use the following information to %1$slog into your account%2$s:' tag1='<a href="'|cat:$url|cat:'">' tag2='</a>'}</p>
{foreach from=$authentication_methods item='authentication_method' name='authentication_methods'}
    <p>{$authentication_method.short_description}: {$authentication_method.uname}{if !empty($authentication_method.link)} ({$authentication_method.link}){/if}</p>
{/foreach}

<p>{gt text='(If you cannot click on the log-in link, you can copy this URL and paste it into your browser: %s )' tag1=$url}</p>
{else}
<p>{gt text='You do not have any active methods available to log into our site. Please contact a site administrator.'}</p>
{/if}

<p>{if !$adminRequested}{gt text='If the request was not made by you then you don\'t need to take any action.'} {/if}{gt text='You are the only recipient of this message, and your user name has not been sent to any other e-mail address.'}{if !$adminRequested} {gt text='You can just delete this message.'}{/if}</p>


<p>&nbsp;</p>
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