<span></span>
{*
{modulelinks modname='ZMAP' type='user'}

{img src="ZMAP.jpg"}<br />
<hr>
{if $modvars.ZMAP.showAdminZMAP}
{gt text='Admin says ZMAP'}
{else}
{gt text='Admin did not say ZMAP'}
{/if}
<hr>
{zmap}
<hr>
{if $external_function}
{gt text='External function executes true.'}
{/if}
*}