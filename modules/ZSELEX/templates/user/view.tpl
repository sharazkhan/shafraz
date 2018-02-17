<h1>{gt text='ZSELEX'}</h1>
{modulelinks modname='ZSELEX' type='user'}

{img src="ZSELEX.jpg"}<br />
<hr>
{if $modvars.ZSELEX.showAdminZSELEX}
{gt text='Admin says Hello World'}
{else}
{gt text='Admin did not say Hello World'}
{/if}
<hr>
{zselex}
<hr>
{if $external_function}
{gt text='External function executes true.'}
{/if}