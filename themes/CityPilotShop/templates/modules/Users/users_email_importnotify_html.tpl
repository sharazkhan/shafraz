

{**********Override Mail Template**************}
{mailcss}

<table {$headerTable}>
    <tr>
        <td {$header1stTd}>
            
              <a href="http://citypilot.dk/index.php"><img  src="{$themepath}/images/Logo.png" class="logo" /></a>
        </td>
    
        <td {$header2ndTd}>
             <h3 {$headerH3}>
                    {gt text='Welcome to %1$s!' tag1=$sitename assign='subject'}
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
           <h3>{gt text='Welcome to %1$s (%2$s)!' tag1=$sitename tag2=$siteurl}</h3>
           
           <!--- additional text --->          
{if $modvars.ZSELEX.enabled_mail_text}
<p>{$modvars.ZSELEX.additional_mail_text|nl2br}</p>
{/if}
 <!------------------------->

<p>{gt text='Hello! This e-mail address (\'%1$s\') has been used to register an account on the \'%2$s\' site.' tag1=$email tag2=$sitename}</p>
<p>{gt text="Your account details are as follows:"}</p>
<p>{gt text="User name: %s" tag1=$uname}</p>
<p>{gt text="Password: %s" tag1=$pass}</p>
<!-- Contents Ends ---->


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