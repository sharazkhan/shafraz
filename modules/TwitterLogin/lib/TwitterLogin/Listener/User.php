<?php

/**
 * Event handler implementation class for user-related events.
 */
class TwitterLogin_Listener_User {

    public function create(Zikula_Event $event) {
        
    }

    public function delete(Zikula_Event $event) {
        $userRecord = $event->getSubject();
        $user_id = $userRecord['uid'];

        $sql = "DELETE FROM twitter_login WHERE user_id='" . $user_id . "'";
        $query = DBUtil::executeSQL($sql);
    }

}

?>