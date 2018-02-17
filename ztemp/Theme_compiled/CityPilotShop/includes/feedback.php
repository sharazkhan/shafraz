<?php /* Smarty version 2.6.28, created on 2017-10-02 15:09:27
         compiled from includes/feedback.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'gt', 'includes/feedback.tpl', 150, false),array('function', 'userloggedinname', 'includes/feedback.tpl', 153, false),array('function', 'userloggedinemail', 'includes/feedback.tpl', 154, false),)), $this); ?>
<style type="text/CSS"><?php echo '
.feedback {
	position:fixed;
	top: 140px; /*24px*/
	left: 0;  z-index:999;
}
.feedback a {
	display:block;
	height:70px;
	width:58px;
	text-align:center;
	background: #3f7682;
	padding:5px;
	float:left;
	cursor:pointer;
	
	/*Font*/
	color:#FFF;
	font-weight:bold;
	font-size:18px;
}

.feedback .fbckform{
	clear:both;
	height:220px;
	width:450px;
	background: #3f7682;
	padding:15px;
	display: none;
	margin: 0 0 0 -450px;
}
.feedback .fbckform textarea{
	height:70px;
	width:400px;
	padding:5px;
}
.feedback .status{
	font-size:16px;
}
.feedback .fbckform #pageURL {
	width:408px;
}
/*.feedback .fbckform H3 {
	color: #FFF;
        font-size:17px;
}*/

.feedback .fbckform p {
	color: #FFF;
        font-weight:bold;
        font-size:17px;
}
.feedback .fbckform span {
	color: #FFF;
}
'; ?>
</style>

<script src="<?php echo $this->_tpl_vars['themepath']; ?>
/javascript/jquery.min.js" type="text/javascript"><?php echo ''; ?>
</script>


<script type="text/javascript"><?php echo '
    //$.noConflict();
$myjq = jQuery.noConflict(true);
$myjq(function ($fbck) {

  feedback_button = {

    onReady: function () {      
      this.feedback_button_click();
      this.send_feedback();
    },
    
    feedback_button_click: function(){
		var feed = $myjq(\'.fbckform\');
		feed.css("display", "block").data("showing", false).hide();
    	$myjq("#feedback_button").click(function(){
			var t = $myjq(\'.fbckform\');
			if (t.data("showing") == true) {
				// hide it
				t.animate({
					marginLeft: "-480px",	// fbckform.width + fbck.padding * 2
					height: "0"
				}).data("showing", false)
					.children().fadeOut();
			} else {
				// show it
				t.animate({
					marginLeft: "0",
					height: "250px"	 // fbckform.height + fbck.padding * 2
				}).data("showing", true)
					.children().fadeIn();
				var currentURL = $myjq(location).attr(\'href\');
				$myjq(\'#pageURL\').val(currentURL);
			}
    	});
    },
    
    send_feedback: function(){
    	$myjq(\'#submit_fbckform\').click(function(){
    		if($myjq(\'#feedback_text\').val() != ""){
    			
    			$myjq(\'.status\').text("");
    			
    			$myjq.ajax({  
    				type: "POST",  
      			  	url: "/process_email.php",  
      			  	data: "user="+encodeURIComponent($myjq(\'#fullusername\').val())+"&email="+encodeURIComponent($myjq(\'#useremail\').val())+"&url="+encodeURIComponent($myjq(\'#pageURL\').val())+"&feedback="+encodeURIComponent($myjq(\'#feedback_text\').val()),
	      			success: function(result,status) { 
	      				//email sent successfully displays a success message
	      				if(result == \'Message Sent\'){
	      					$myjq(\'.status\').text(Zikula.__("Feedback Sent"));
							$myjq(\'.fbckform\').animate({
								marginLeft: "-480px",	 // fbckform.width + fbck.padding * 2
								height: "0"
							}).data("showing", false)
								.children().fadeOut();							
	      				} else {
	      					$myjq(\'.status\').text(Zikula.__("Feedback Failed to Send"));
	      				}
	      			},
	      			error: function(result,status){
	      				$myjq(\'.status\').text(Zikula.__("Feedback Failed to Send"));
	      			}  
      			});
    		} else {
				$myjq(\'.status\').text(Zikula.__("Please enter feedback!"));
			}

    	});
    },
    
    
  };

  $fbck().ready(function () {
	  feedback_button.onReady();
  });

});	
'; ?>
</script>



<?php if ($this->_tpl_vars['loggedin'] == true): ?>
<div id="feedback" class="feedback">
	<a id="feedback_button"><img src="<?php echo $this->_tpl_vars['themepath']; ?>
/images/feedback.png" /></a>
	
	<div id="fbckform" class="fbckform">
	<p><?php echo smarty_function_gt(array('text' => 'Please Send Us Your Feedback'), $this);?>
</p>
    	<span><?php echo smarty_function_gt(array('text' => 'Make sure to be placed on the page you want to give us info about!'), $this);?>
</span><br /><br />
		<span class="status"></span>
		<span><input type="text" value="<?php echo smarty_function_userloggedinname(array(), $this);?>
" id="fullusername" disabled="disabled" />
		<input type="text" value="<?php echo smarty_function_userloggedinemail(array(), $this);?>
" id="useremail" disabled="disabled" /></span>
		<input type="text" value="" id="pageURL" disabled="disabled" />
		<textarea id="feedback_text"></textarea>
		<input type="button" value="Send" id="submit_fbckform" />
	</div>
</div>
<?php endif; ?>
