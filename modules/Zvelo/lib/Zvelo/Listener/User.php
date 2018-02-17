<?php

/**
 * Event handler implementation class for user-related events.
 */
class Zvelo_Listener_User {

    public static function getTheme(Zikula_Event $event) {

        if ($_REQUEST['module'] == 'Zvelo' || $_REQUEST['module'] == 'zvelo') {
            $event->setData('ZveloTheme');
            $event->stop();
        }
    }

}

?>