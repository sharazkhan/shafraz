<?PHP

// NOTE: You cannot display the data in a browser, since the resultpage is called in the background
// Collect return values and store them in a file, database, send them by email etc.
// EXAMPLE: Send an e-mail with data
// Set keys we wish to read from $_POST array
//fields that is recieved from the callback
//see http://doc.quickpay.dk/paymentwindow/technicalspecification.html#index2h3 for more information 
$fields = array('msgtype', 'ordernumber', 'amount', 'currency', 'time', 'state', 'qpstat', 'qpstatmsg', 'chstat', 'chstatmsg', 'merchant', 'merchantemail', 'transaction', 'cardtype', 'cardnumber', 'cardexpire', 'acquirer', 'splitpayment', 'fraudprobability', 'fraudremarks', 'fraudreport', 'fee', 'md5check');

//variable to collect values for the md5 check
$cstr = '';

foreach ($fields as $key) {
    if (isset($_POST[$key])) {
        $message .= "$key: " . $_POST[$key] . "\r\n";
        if ('md5check' != $key) {
            $cstr .= $_POST[$key];
        }
    }
}
mail('sharaz.khan@r2international.com', 'resultpage', $message);
$md5secret = 'a2aa3b62eacbe78baec92162ca99d93171b0115295fe87c65cebe91d3d5a1685';

if ($_POST['md5check'] != md5($cstr . $md5secret)) {
    //md5 check failed - request cannot be from quickpay
    error_log('Invalid request received on quickpay callback');
    error_log(print_r($_POST, true));
    header("HTTP/1.0 400 Bad request");
    die();
}

// Send an email with the data posted to your resultpage
//mail('sharaz.khan@r2international.com', 'resultpage', $message);
?>
