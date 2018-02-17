<?php
	if ($_SERVER['HTTP_X_REQUESTED_WITH']!=NULL) {
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
		$to = "ken@citypilot.dk";
		$subject = "Feedback fra " . $_POST['user'] . " på CityPilot BETA";
		$message = $_POST['user'] .'<br />'. $_POST['email'] .'<br />'. $_POST['url'] .'<br />'. nl2br($_POST['feedback']);
	 
		if(mail($to,$subject,$message,$headers)) {
			echo "Message Sent";
		} else {
			echo "Message Not Sent";
		}
	}
?>