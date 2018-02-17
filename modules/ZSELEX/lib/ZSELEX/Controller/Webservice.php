<?php

class ZSELEX_Controller_Webservice extends ZSELEX_Controller_Base_User
{

    /**
     * Get Events for joomls
     *
     * @param int $limit
     * @param int $start
     * @return JSON response
     */
    function getEvents()
    {
        // echo "getEvents"; exit;
        $limit       = $_REQUEST['limit'];
        $start       = $_REQUEST['start'];
        // echo $limit; exit;
        $events      = $this->entityManager->getRepository('ZSELEX_Entity_Event')->getAllEventsApi(array(
            'limit' => $limit, 'start' => $start
        ));
        $eventsCount = $this->entityManager->getRepository('ZSELEX_Entity_Event')->getAllEventsApiCount();
        // $events = array(['id' => 1, 'name' => 'test'], ['id' => 2, 'name' => 'test2']);
        $output      = array('events' => $events, 'total_count' => $eventsCount);
        $toJson      = json_encode($output);
        die($toJson);
        exit;
    }
}
?>