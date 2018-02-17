<?php
/**
 * Copyright ACTA-IT 2014
 *
 * ZSELEX
 * Demonstration of Zikula Module
 *
 * @license GNU/LGPLv3 (or at your option, any later version).
 */

/**
 * Class to control User interface
 */
class ZSELEX_Api_Mail extends Zikula_AbstractApi
{

    function sendOrderConfirmation($args)
    {
        $render     = Zikula_View::getInstance($this->name, false);
        $renderArgs = $args ['renderArgs'];
        // echo "<pre>"; print_r($renderArgs); echo "</pre>"; exit;
        $render->assign($renderArgs);

        $subject = $args ['subject'];
        $render->assign('subject', $subject);

        $message     = $render->fetch('mail/order_confirmation_mail.tpl'); // mail template
        // echo "Message : <br> " . $message; exit;
        $mailer_args = array(
            'toaddress' => $args ['toaddress'],
            'fromname' => $args ['fromname'],
            'fromaddress' => $args ['fromaddress'],
            'subject' => $subject,
            'body' => $message,
            'html' => true
        );
        // echo "<pre>"; print_r($mailer_args); echo "</pre>"; exit;
        $sent        = ModUtil::apiFunc('Mailer', 'user', 'sendMessage',
                $mailer_args);

        if (!$sent) {
            return false;
        }
        return true;
    }

    function sendOrderConfirmationToOwner($args)
    {
        $render     = Zikula_View::getInstance($this->name, false);
        $renderArgs = $args ['renderArgs'];
        // echo "<pre>"; print_r($renderArgs); echo "</pre>"; exit;
        $render->assign($renderArgs);

        $subject = $args ['subject'];
        $render->assign('subject', $subject);

        $message     = $render->fetch('mail/order_confirmation_mail_owner.tpl'); // mail template
        // echo "Message : <br> " . $message; exit;
        $mailer_args = array(
            'toaddress' => $args ['toaddress'],
            'fromname' => $args ['fromname'],
            'subject' => $subject,
            'body' => $message,
            'html' => true
        );
        // echo "<pre>"; print_r($mailer_args); echo "</pre>"; exit;
        $sent        = ModUtil::apiFunc('Mailer', 'user', 'sendMessage',
                $mailer_args);

        if (!$sent) {
            return false;
        }
        return true;
    }
}
// end class def