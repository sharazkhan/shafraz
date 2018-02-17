
{pageaddvar name="stylesheet" value="modules/ZSELEX/javascript/validation/style.css$ver"} 
{pageaddvar name='javascript' value='http://ajax.googleapis.com/ajax/libs/prototype/1.6.0.3/prototype.js'}
{pageaddvar name='javascript' value='http://ajax.googleapis.com/ajax/libs/scriptaculous/1.8.2/effects.js'}
{pageaddvar name='javascript' value="modules/ZSELEX/javascript/validation/fabtabulous.js$ver"}
{pageaddvar name='javascript' value="modules/ZSELEX/javascript/validation/validation.js$ver"}
<div class="row">
    <div class="col-md-12">
        {include file="user/cartmenu.tpl"}
        <h2>{gt text='Enter Customer Information'}</h2>

        <!-- Checkout -->
        <div class="checkout-wrap clearfix">
            <form id="delivery" name="delivery" method="post" action="{modurl modname="ZSELEX" type="user" func="delivery" shop_id=$smarty.request.shop_id}" class="form-horizontal clearfix">
                  <div class="col-sm-6">

                    <div class="form-group">
                        <label for="" class="col-sm-4 control-label">{gt text='First Name'}: </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control required" title="{gt text='Please enter your first name'}" name="fname" value="{$info.first_name|cleantext}" id="" placeholder="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-sm-4 control-label">{gt text='Last Name'}: </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control required" title="{gt text='Please enter your last name'}" name="lname" value="{$info.last_name|cleantext}" id="" placeholder="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-sm-4 control-label">{gt text='Address'}: </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control required" title="{gt text='Please enter your address'}" name="address" value="{$info.address|cleantext}" id="" placeholder="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-sm-4 col-xs-12 control-label">{gt text='ZIP code. city'}: </label>
                        <div class="col-sm-3 col-xs-4">
                            <input type="text" title="{gt text='Please enter your zip code'}" class="form-control required" name="zip" value="{$info.zip}" id="" placeholder="">
                        </div>
                        <div class="col-sm-5 col-xs-8">
                            <input type="text" class="form-control required"  title="{gt text='Please enter your city'}" name="city" value="{$info.city|cleantext}" id="" placeholder="">
                        </div>
                    </div>

                </div>
                <div class="col-sm-6">

                    <div class="form-group">
                        <label for="" class="col-sm-4 control-label">{gt text='Phone'}: </label>
                        <div class="col-sm-8">
                            <input type="tel" title="{gt text='Please enter a valid phone number'}" class="form-control required" name="phone" value="{$info.mobile}" id="" placeholder="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-sm-4 control-label">{gt text='Email address'}: </label>
                        <div class="col-sm-8">
                            <input type="email" class="form-control required validate-email" title="{gt text='Please enter a valid email'}" name="email" value="{$info.email}" id="" placeholder="">
                        </div>
                    </div>
                    <div class="form-group checkbox-wrap">
                        <label for="" class="col-sm-4 control-label">{gt text='Subscribe To Newsletter'}: </label>
                        <div class="col-sm-8">
                            <div class="checkbox-click">
                                <input type="checkbox" id="check2" class="btn" name="subscribe" value="1" checked="checked">
                                <label for="check2"></label>
                            </div>
                        </div>
                    </div>
                    {*<div class="form-group checkbox-wrap">
                        <label for="" class="col-sm-4 control-label">Create Account: </label>
                        <div class="col-sm-8">
                            <div class="checkbox-click">
                                <input type="checkbox" id="checkbox1" class="btn" value="">
                                <label for="checkbox1"></label>
                            </div>
                            <div id="reg-username">
                                <label class="control-label">User Name :</label> 
                                <input type="text" placeholder="Username" value="user1256" class="form-control reg-username">
                            </div>
                        </div>
                    </div>*}
                    <input type="hidden" name="country" value="{$info.country}"/>
                    <input type="hidden" name="state" value="{$info.state}"/>

                </div>
            </form>
        </div>
        <div class="checkout-btn-wrap clearfix">
            <a href="#" class="btn btn-warning cs-btn btn-info"><i class="fa fa-angle-left"></i> {gt text='Continue Shopping'}</a>
            <a href="#" onClick="return submitCheckout()" class="btn btn-primary checkout-btn">{gt text='Go to delivery'} <i class="fa fa-arrow-right"></i></a>
        </div>
        <!-- Checkout -->
    </div>
</div>

<script type="text/javascript">
    // var valid2 = new Validation('delivery', {useTitles:true});
    var valid2 = new Validation('delivery', {useTitles: true, onSubmit: false});


    function submitCheckout() {
        //alert('submitCheckout'); exit();
       var result = valid2.validate();
      // var result = true;
        if (result) {
            jQuery('#delivery').submit();
            return false;
        }

    }
</script>