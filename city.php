<?php

include 'lib/bootstrap.php';
$core->init(Zikula_Core::STAGE_ALL | Zikula_Core::STAGE_AJAX & ~Zikula_Core::STAGE_DECODEURLS);
$keyword = $_REQUEST['data'];
$countryId = $_REQUEST['countryId'];
$regionId = $_REQUEST['regionId'];

if (!empty($keyword)) {


    //$sql = "select cityId,cityname from zselex_city where cityname like '" . $keyword . "%' order by cityname";


    if (($regionId > 0) OR ($regionId > 0 && $countryId > 0)) {
        $sql = "SELECT cityId,cityname FROM zselex_city a , zselex_parent b 
                WHERE a.cityId=b.childId AND b.childType='CITY' AND  b.parentType='REGION' 
                AND b.parentId=$regionId AND a.cityname like '" . $keyword . "%' order by a.cityname";
    } elseif ($regionId < 0 && $countryId > 0) {


        $sql = "SELECT cityId,cityname FROM zselex_city WHERE cityId IN(SELECT childId FROM zselex_parent WHERE childType='CITY' AND parentType='REGION' 
                 AND parentId IN(SELECT childId FROM zselex_parent WHERE childType='REGION' AND parentType='COUNTRY' AND parentId=$countryId))
                 AND cityname like '" . $keyword . "%' 
                OR
                   cityId IN(SELECT childId FROM zselex_parent WHERE childType='CITY' AND parentType='COUNTRY' AND parentId=$countryId)
                   AND cityname like '" . $keyword . "%' order by cityname
                ";
    } else {

        $sql = "SELECT cityId,cityname FROM zselex_city where cityname like '" . $keyword . "%' order by cityname";
    }

//$sql = "select name from ".$db_table."";



    $statement = Doctrine_Manager::getInstance()->connection();
    $results = $statement->execute($sql);
    $values = $results->fetchAll();


    if (count($values) > 0) {
        echo '<ul class="list">';


        foreach ($values as $row) {

            $t = $row['cityname'];
            $tg = explode(',', $t);


            $str = strtolower($row['cityname']);
            $start = strpos($str, $keyword);
            $end = similar_text($str, $keyword);
            $last = substr($str, $end, strlen($str));
            $first = substr($str, $start, $end);

            $final = '<span class="bold">' . ucwords($first) . '</span>' . $last;

            echo '<li><a href=\'javascript:void(0);\' id="' . $row['cityId'] . '">' . $final . '</a></li>';
        }
        echo "</ul>";
    } else {
        echo 0;
    }
} else {


    $sql = "select cityId,cityname from zselex_city order by cityname";



    if (($regionId > 0) OR ($regionId > 0 && $countryId > 0)) {
        $sql = "SELECT cityId,cityname FROM zselex_city a , zselex_parent b 
                WHERE a.cityId=b.childId AND b.childType='CITY' AND  b.parentType='REGION' 
                AND b.parentId=$regionId  order by a.cityname";
    } elseif ($regionId < 0 && $countryId > 0) {


        $sql = "SELECT cityId,cityname FROM zselex_city WHERE cityId IN(SELECT childId FROM zselex_parent WHERE childType='CITY' AND parentType='REGION' 
                 AND parentId IN(SELECT childId FROM zselex_parent WHERE childType='REGION' AND parentType='COUNTRY' AND parentId=$countryId))
                 
                OR
                   cityId IN(SELECT childId FROM zselex_parent WHERE childType='CITY' AND parentType='COUNTRY' AND parentId=$countryId)
                    order by cityname
                ";
    } else {

        $sql = "SELECT cityId,cityname FROM zselex_city  order by cityname";
    }


//$sql = "select name from ".$db_table."";



    $statement = Doctrine_Manager::getInstance()->connection();
    $results = $statement->execute($sql);
    $values = $results->fetchAll();


    if (count($values) > 0) {
        echo '<ul class="list">';


        foreach ($values as $row) {

            $t = $row['cityname'];




            echo '<li><a href=\'javascript:void(0);\' id="' . $row['cityId'] . '">' . $t . '</a></li>';
        }
        echo "</ul>";
    } else {
        echo 0;
    }
}
?>