<?php /* Smarty version 2.6.28, created on 2017-10-29 15:29:49
         compiled from mail/order_confirmation_mail.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'mailcss', 'mail/order_confirmation_mail.tpl', 1, false),array('function', 'pageaddvar', 'mail/order_confirmation_mail.tpl', 3, false),array('function', 'gt', 'mail/order_confirmation_mail.tpl', 31, false),array('function', 'modurl', 'mail/order_confirmation_mail.tpl', 86, false),array('function', 'displayoptions', 'mail/order_confirmation_mail.tpl', 103, false),array('function', 'displayprice', 'mail/order_confirmation_mail.tpl', 115, false),array('insert', 'getstatusmsg', 'mail/order_confirmation_mail.tpl', 4, false),array('modifier', 'nl2br', 'mail/order_confirmation_mail.tpl', 56, false),array('modifier', 'replace', 'mail/order_confirmation_mail.tpl', 87, false),)), $this); ?>
<?php echo smarty_function_mailcss(array(), $this);?>


<?php echo smarty_function_pageaddvar(array('name' => 'stylesheet','value' => "themes/CityPilot/style/checkout.css"), $this);?>
 
     <?php require_once(SMARTY_CORE_DIR . 'core.run_insert_handler.php');
echo smarty_core_run_insert_handler(array('args' => array('name' => 'getstatusmsg')), $this); ?>

        <style type="text/css"><?php echo '
            .Hd{ height:31px; background: #f2f2f2; border:1px #f2f2f2 solid; font-family:Arial, Helvetica, sans-serif; font-weight:bold; font-size:12px; }
            .Hd div{height:21px;  float:left; margin-top:7px; margin-left:3px}
            .TblRow div{height:80px;  float:left; font-family:Arial, Helvetica, sans-serif; font-size:12px; padding-top:7px; padding-left:6px; border: solid 1px #f2f2f2; border-left:none; border-top:none}
			.CartTable td{ padding:3px 10px; vertical-align:center}
        '; ?>
</style>
        
        
 <table <?php echo $this->_tpl_vars['headerTable']; ?>
>
    <tr>
        <td  <?php echo $this->_tpl_vars['header1stTd']; ?>
>
            
              <a href="http://citypilot.dk/index.php"><img style="margin-top: 24px;" src="<?php echo $this->_tpl_vars['themepath']; ?>
/images/Logo.png" class="logo" /></a>
        </td>
    
        <td <?php echo $this->_tpl_vars['header2ndTd']; ?>
>
             <h3 <?php echo $this->_tpl_vars['headerH3']; ?>
>
                    <?php echo $this->_tpl_vars['subject']; ?>

             </h3>
                
        </td>
    </tr>
</table>
  

  <div class="checkout_form" style="margin-top: 20px !important;margin-bottom: 20px !important;padding-left: 50px !important;font-size:14px !important;color:#666666 !important;">
        <h2><?php echo smarty_function_gt(array('text' => 'Order Summary'), $this);?>
</h2>
        
<h3><?php echo smarty_function_gt(array('text' => 'Your Order Id'), $this);?>
 : <?php echo $this->_tpl_vars['order_id']; ?>
</h3>
<?php echo smarty_function_gt(array('text' => 'Congratulations on your order'), $this);?>

<h4><?php echo smarty_function_gt(array('text' => 'Payment Method'), $this);?>
 : <?php echo $this->_tpl_vars['payment_method']; ?>
</h4>
<?php if ($this->_tpl_vars['payment_mode']['test_mode']): ?>
<h4><?php echo smarty_function_gt(array('text' => 'Payment Mode'), $this);?>
 : <?php echo smarty_function_gt(array('text' => 'Test'), $this);?>
 </h4>
<?php endif; ?>
<?php if ($this->_tpl_vars['cardtype'] != ''): ?>
<h4><?php echo smarty_function_gt(array('text' => 'Card Type'), $this);?>
 : <?php echo $this->_tpl_vars['cardtype']; ?>
</h4> 
<?php endif; ?>
<h3><u><?php echo smarty_function_gt(array('text' => 'Your Details'), $this);?>
</u> :</h3> 
<div><b><?php echo smarty_function_gt(array('text' => 'Customer Name'), $this);?>
 : </b> &nbsp; <?php echo $this->_tpl_vars['order_info']['first_name']; ?>
&nbsp;<?php echo $this->_tpl_vars['order_info']['last_name']; ?>
</div>
<div><b><?php echo smarty_function_gt(array('text' => 'Email'), $this);?>
 : </b> &nbsp; <?php echo $this->_tpl_vars['order_info']['email']; ?>
</div>
<div><b><?php echo smarty_function_gt(array('text' => 'Delivery Address'), $this);?>
 : </b> &nbsp; <?php echo $this->_tpl_vars['order_info']['address']; ?>
</div>
<div><b><?php echo smarty_function_gt(array('text' => 'ZIP code. city'), $this);?>
 : </b> &nbsp; <?php echo $this->_tpl_vars['order_info']['zip']; ?>
 <?php echo $this->_tpl_vars['order_info']['city']; ?>
</div>

<div><b><?php echo smarty_function_gt(array('text' => 'Phone Number'), $this);?>
 : </b> &nbsp; <?php echo $this->_tpl_vars['order_info']['phone']; ?>
</div>
<?php if ($this->_tpl_vars['payment_method'] == 'directpay'): ?>
    <br><br>
    <?php echo $this->_tpl_vars['checkout_info']['info']; ?>

 <?php endif; ?>   
      <br><br>
 <div><b><?php echo smarty_function_gt(array('text' => 'Shop'), $this);?>
: &nbsp; <?php echo $this->_tpl_vars['shop_info']['shop_name']; ?>
</b> </div>
 <div><?php echo ((is_array($_tmp=$this->_tpl_vars['shop_info']['address'])) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</div>
 <div><?php echo smarty_function_gt(array('text' => 'Phone'), $this);?>
: &nbsp; <?php echo $this->_tpl_vars['shop_info']['telephone']; ?>
</div>
 <div><?php echo smarty_function_gt(array('text' => 'Email'), $this);?>
: &nbsp; <?php echo $this->_tpl_vars['shop_info']['email']; ?>
</div>
    <div style="width:100%; margin:auto;">
                <table style="border-collapse:collapse; width:100%; border:solid 1px #f2f2f2;" class="CartTable">
                 <form name='updatecart<?php echo $this->_tpl_vars['k']; ?>
' action='index.php?module=zselex&type=user&func=updatecart&shop_id=<?php echo $this->_tpl_vars['k']; ?>
' method='post'>
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                                    <tr class="Hd">
                    <td><?php echo smarty_function_gt(array('text' => 'Item'), $this);?>
</td>
                    <td><?php echo smarty_function_gt(array('text' => 'Description'), $this);?>
</td>
                    <td><?php echo smarty_function_gt(array('text' => 'Price'), $this);?>
</td>
                    <td><?php echo smarty_function_gt(array('text' => 'Quantity'), $this);?>
</td>
                    <td><?php echo smarty_function_gt(array('text' => 'Subtotal'), $this);?>
</td>
                    <td>&nbsp;</td>
                </tr>
                <!--table content Row -->
               <?php $_from = $this->_tpl_vars['items']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k1'] => $this->_tpl_vars['v1']):
?>  
                   
                <tr class="TblRow">
                    <td>
                    	<center>
            <a href="<?php echo $this->_tpl_vars['baseurl']; ?>
<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'user','func' => 'productview','id' => $this->_tpl_vars['v1']['product_id']), $this);?>
">
          <?php $this->assign('image1', ((is_array($_tmp=$this->_tpl_vars['v1']['prd_image'])) ? $this->_run_mod_handler('replace', true, $_tmp, ' ', '%20') : smarty_modifier_replace($_tmp, ' ', '%20'))); ?>
          <?php $this->assign('image', "zselexdata/".($this->_tpl_vars['shop_id'])."/products/thumb/".($this->_tpl_vars['image1'])); ?>
  <?php if (is_file ( $this->_tpl_vars['image'] )): ?>
   <img src="<?php echo $this->_tpl_vars['baseurl']; ?>
zselexdata/<?php echo $this->_tpl_vars['shop_id']; ?>
/products/thumb/<?php echo $this->_tpl_vars['v1']['prd_image']; ?>
" width="70" />

<br>
<?php endif; ?>
<p><b><?php echo $this->_tpl_vars['v1']['product_name']; ?>
</b></p>
</a>
                        </center>
                      
               </td>
                    <td>
                        <?php echo $this->_tpl_vars['v1']['prd_description']; ?>

                         <?php if ($this->_tpl_vars['v1']['product_options'] != '' || $this->_tpl_vars['v1']['product_options'] != '[]'): ?>
                             <br>
                          <?php echo smarty_function_displayoptions(array('options' => $this->_tpl_vars['v1']['product_options']), $this);?>

                        <?php endif; ?>
                         <?php if ($this->_tpl_vars['v1']['prd_answer'] != ''): ?>
                            <div style="float:none; display: block; border:none; height: auto">
                                <b><?php echo $this->_tpl_vars['v1']['prd_question']; ?>
:</b><br>
                            <?php echo $this->_tpl_vars['v1']['prd_answer']; ?>

                            </div>
                         <?php endif; ?>
                          <?php if ($this->_tpl_vars['v1']['no_vat']): ?>
                               <p>*</p>
                          <?php endif; ?>
                    </td>
                    <td>DKK <?php echo smarty_function_displayprice(array('amount' => $this->_tpl_vars['v1']['price']), $this);?>
</td>
                   
                    <td><?php echo $this->_tpl_vars['v1']['quantity']; ?>
</td>
                    <td>DKK <?php echo smarty_function_displayprice(array('amount' => $this->_tpl_vars['v1']['total']), $this);?>
</td>
                   
                </tr>
                  
                  <?php endforeach; endif; unset($_from); ?>
                    
               </form>
                    <tr class="Hd">
                    <td style="width:515px; text-align:right; font-weight:bold" colspan="4">
                        <?php echo smarty_function_gt(array('text' => 'Shipping'), $this);?>
:<br /><?php if ($this->_tpl_vars['vat'] > 0): ?><?php echo smarty_function_gt(array('text' => 'Vat'), $this);?>
:<br /><?php endif; ?><?php echo smarty_function_gt(array('text' => 'Grand Total'), $this);?>
:
                    </td>
                    <td style="width:125px;color:red; font-weight:bold" colspan="2">
                       <?php if ($this->_tpl_vars['shipping'] < 1): ?><?php echo smarty_function_gt(array('text' => 'Free'), $this);?>
<?php else: ?>DKK <?php echo smarty_function_displayprice(array('amount' => $this->_tpl_vars['shipping']), $this);?>
<?php endif; ?><br /><?php if ($this->_tpl_vars['vat'] > 0): ?>DKK <?php echo smarty_function_displayprice(array('amount' => $this->_tpl_vars['vat']), $this);?>
<br /><?php endif; ?>DKK <?php echo smarty_function_displayprice(array('amount' => $this->_tpl_vars['grand_total_all']), $this);?>

                    </td>
                    </tr>
              
</table>
                
             
        </div>
                    <?php if ($this->_tpl_vars['hasNoVatProduct']): ?>
                    <div>* <?php echo smarty_function_gt(array('text' => 'VAT is not applicable for this product'), $this);?>
</div>  
                    <?php endif; ?>

   </div>

 
   <style><?php echo '
   .HalfSec{ width:60%;}
   '; ?>
</style>
   
   <table  <?php echo $this->_tpl_vars['footerTable']; ?>
>
    <tr>
        <td <?php echo $this->_tpl_vars['footerTd']; ?>
>
                <img src="<?php echo $this->_tpl_vars['themepath']; ?>
/images/FooterLogo.png"  <?php echo $this->_tpl_vars['footerLogo']; ?>
/> <img src="<?php echo $this->_tpl_vars['themepath']; ?>
/images/bg.png"  />
        </td>
     </tr>
     
</table>