<?php

include 'lib/bootstrap.php';
$core->init(Zikula_Core::STAGE_ALL | Zikula_Core::STAGE_AJAX & ~Zikula_Core::STAGE_DECODEURLS);
$keyword = $_REQUEST['data'];

$countryId = $_REQUEST['countryId'];

if (!empty($keyword)) {

    if (empty($countryId)) {
        $sql = "select regionId,regionName from zselex_region where regionName like '" . $keyword . "%' order by regionName";
    } else {
        $sql = "SELECT regionId,regionName FROM zselex_region a , zselex_parent b 
                WHERE a.regionId=b.childId AND b.childType='REGION' AND  b.parentType='COUNTRY' 
                AND b.parentId=$countryId AND a.regionName like '" . $keyword . "%' order by a.regionName";
    }

//$sql = "select name from ".$db_table."";



    $statement = Doctrine_Manager::getInstance()->connection();
    $results = $statement->execute($sql);
    $values = $results->fetchAll();


    if (count($values) > 0) {
        echo '<ul class="list">';


        foreach ($values as $row) {

            $t = $row['regionName'];
            $tg = explode(',', $t);


            $str = strtolower($row['regionName']);
            $start = strpos($str, $keyword);
            $end = similar_text($str, $keyword);
            $last = substr($str, $end, strlen($str));
            $first = substr($str, $start, $end);

            $final = '<span class="bold">' . ucwords($first) . '</span>' . $last;

            echo '<li><a href=\'javascript:void(0);\' id="' . $row['regionId'] . '">' . $final . '</a></li>';
        }
        echo "</ul>";
    } else {
        echo 0;
    }
} else {


    $sql = "select regionId,regionName from zselex_region order by regionName";



    if (empty($countryId)) {
        $sql = "select regionId,regionName from zselex_region order by regionName";
    } else {
        $sql = "SELECT regionId,regionName FROM zselex_region a , zselex_parent b 
                WHERE a.regionId=b.childId AND b.childType='REGION' AND  b.parentType='COUNTRY' 
                AND b.parentId=$countryId order by a.regionName";
    }
//$sql = "select name from ".$db_table."";



    $statement = Doctrine_Manager::getInstance()->connection();
    $results = $statement->execute($sql);
    $values = $results->fetchAll();



    echo '<ul class="list">';


    foreach ($values as $row) {

        $t = $row['regionName'];




        echo '<li><a href=\'javascript:void(0);\' id="' . $row['regionId'] . '">' . $t . '</a></li>';
    }
    echo "</ul>";
}
?>