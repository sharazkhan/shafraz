<a href="{modurl modname="ZSELEX" type="admin" func="serviceCart"}"><b>{gt text='Cart'}</b></a>
&nbsp;>>&nbsp;
{if $smarty.session.payment_option}
<a href="{modurl modname="ZSELEX" type="admin" func="paymentOption"}"><b>{gt text='Payment Options'}</b></a>
{else}
<a href="#"><b>{gt text='Payment Options'}</b></a>
{/if}
&nbsp;>>&nbsp;
<a href="#"><b>{gt text='Order Confirmation'}</b></a>