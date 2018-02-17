

{**********Override Mail Template**************}
{mailcss}
<table {$headerTable}>
    <tr>
        <td {$header1stTd}>
            
              <a href="http://citypilot.dk/index.php"><img  src="{$themepath}/images/Logo.png" class="logo" /></a>
        </td>
          <td {$header2ndTd}>
             <h3 {$headerH3}>
                    {gt text='Your recent request at %1$s.' tag1=$sitename tag2=$reginfo.uname assign='subject'}
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
           <h3>{gt text='A message from %1$s...' tag1=$sitename}</h3>
           
           <!--- additional text --->          
{if $modvars.ZSELEX.enabled_mail_text}
<p>{$modvars.ZSELEX.additional_mail_text|nl2br}</p>
{/if}
 <!------------------------->

<p>{gt text='Recently, this e-mail address (\'%1$s\') was used to request an account on \'%2$s\' (%3$s).' tag1=$reginfo.email tag2=$sitename tag3=$siteurl}
{gt text="The information that was registered is as follows:"}</p>

<p>{gt text="User name"}: {$reginfo.uname}<br />

<p>{gt text="Thank you for your application for a new account. At this time we are unable to approve your application."}</p>

{if !empty($reason)}<p>{$reason}</p>{/if}
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
