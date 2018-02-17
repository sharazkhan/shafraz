<?php

include 'lib/bootstrap.php';
$core->init(Zikula_Core::STAGE_ALL | Zikula_Core::STAGE_AJAX & ~Zikula_Core::STAGE_DECODEURLS);
$keyword = $_REQUEST['data'];

$countryId = $_REQUEST['countryId'];
$regionId = $_REQUEST['regionId'];

$cityId = $_REQUEST['cityId'];


if (!empty($keyword)) {
    // $sql = "select shopId,shopname from zselex_shop where shopname like '" . $keyword . "%' order by shopname";


    if ($cityId > 0) {

        $sql = "SELECT shopId,shopname FROM zselex_shop a , zselex_parent b 
                WHERE a.shopId=b.childId AND b.childType='SHOP' 
                AND b.parentId='" . $cityId . "' AND b.parentType='CITY' 
                AND a.shopname like '" . $keyword . "%' order by a.shopname";
    } elseif ($cityId < 0 && $regionId > 0) {
        $sql = "SELECT shopId,shopname FROM zselex_shop WHERE shopID IN(SELECT childId FROM zselex_parent WHERE childType='SHOP'
                AND parentType='CITY' AND parentID IN(SELECT childId FROM zselex_parent WHERE childType='CITY' AND parentType='REGION' 
                AND parentId=$regionId))  AND shopname like '" . $keyword . "%'
                OR
                shopID IN(SELECT childId FROM zselex_parent WHERE childType='SHOP' AND parentType='REGION' AND parentId=$regionId)
                AND shopname like '" . $keyword . "%' order by shopname
            ";
    } elseif ($cityId < 0 && $regionId < 0 && $countryId > 0) {

        $sql = "SELECT shopId,shopname FROM zselex_shop WHERE shopID IN(SELECT childId FROM zselex_parent WHERE childType='SHOP' 
        AND parentType='CITY' AND parentId IN(SELECT childId FROM zselex_parent WHERE childType='CITY' 
        AND parentType='COUNTRY' AND parentId=$countryId))
        AND shopname like '" . $keyword . "%' 
        OR 
        shopID IN(SELECT childId FROM zselex_parent WHERE childType='SHOP' 
        AND parentType='CITY' AND parentId IN(SELECT childId FROM zselex_parent WHERE childType='CITY' 
        AND parentType='REGION' AND parentId IN(SELECT
        childId FROM zselex_parent WHERE childType='REGION' AND parentType='COUNTRY' AND parentId=$countryId)))
          AND shopname like '" . $keyword . "%'
        OR
        shopID IN(SELECT childId FROM zselex_parent WHERE childType='SHOP' AND parentType='COUNTRY' AND parentId=$countryId) 
                  AND shopname like '" . $keyword . "%' order by shopname";
    } else {

        $sql = "select shopId,shopname from zselex_shop where shopname like '" . $keyword . "%' order by shopname";
    }


//$sql = "select name from ".$db_table."";



    $statement = Doctrine_Manager::getInstance()->connection();
    $results = $statement->execute($sql);
    $values = $results->fetchAll();


    if (count($values) > 0) {
        echo '<ul class="list">';


        foreach ($values as $row) {

            $t = $row['shopname'];
            $tg = explode(',', $t);


            $str = strtolower($row['shopname']);
            $start = strpos($str, $keyword);
            $end = similar_text($str, $keyword);
            $last = substr($str, $end, strlen($str));
            $first = substr($str, $start, $end);

            $final = '<span class="bold">' . ucwords($first) . '</span>' . $last;

            echo '<li><a href=\'javascript:void(0);\' id="' . $row['shopId'] . '">' . $final . '</a></li>';
        }
        echo "</ul>";
    } else {
        echo 0;
    }
} else {


    //$sql = "select shopId,shopname from zselex_shop order by shopname";


    if ($cityId > 0) {

        $sql = "SELECT shopId,shopname FROM zselex_shop a , zselex_parent b 
                WHERE a.shopId=b.childId AND b.childType='SHOP' 
                AND b.parentId='" . $cityId . "' AND b.parentType='CITY' 
                 order by a.shopname";
    } elseif ($cityId < 0 && $regionId > 0) {
        $sql = "SELECT shopId,shopname FROM zselex_shop WHERE shopID IN(SELECT childId FROM zselex_parent WHERE childType='SHOP'
                AND parentType='CITY' AND parentID IN(SELECT childId FROM zselex_parent WHERE childType='CITY' AND parentType='REGION' 
                AND parentId=$regionId)) 
                OR
                shopID IN(SELECT childId FROM zselex_parent WHERE childType='SHOP' AND parentType='REGION' AND parentId=$regionId)
               order by shopname
            ";
    } elseif ($cityId < 0 && $regionId < 0 && $countryId > 0) {

        $sql = "SELECT shopId,shopname FROM zselex_shop WHERE shopID IN(SELECT childId FROM zselex_parent WHERE childType='SHOP' 
        AND parentType='CITY' AND parentId IN(SELECT childId FROM zselex_parent WHERE childType='CITY' 
        AND parentType='COUNTRY' AND parentId=$countryId))
       
        OR 
        shopID IN(SELECT childId FROM zselex_parent WHERE childType='SHOP' 
        AND parentType='CITY' AND parentId IN(SELECT childId FROM zselex_parent WHERE childType='CITY' 
        AND parentType='REGION' AND parentId IN(SELECT
        childId FROM zselex_parent WHERE childType='REGION' AND parentType='COUNTRY' AND parentId=$countryId)))
         
        OR
        shopID IN(SELECT childId FROM zselex_parent WHERE childType='SHOP' AND parentType='COUNTRY' AND parentId=$countryId) 
                   order by shopname";
    } else {

        $sql = "select shopId,shopname from zselex_shop  order by shopname";
    }



//$sql = "select name from ".$db_table."";



    $statement = Doctrine_Manager::getInstance()->connection();
    $results = $statement->execute($sql);
    $values = $results->fetchAll();

  if (count($values) > 0) {

    echo '<ul class="list">';


    foreach ($values as $row) {

        $t = $row['shopname'];


        echo '<li><a href=\'javascript:void(0);\' id="' . $row['shopId'] . '">' . $t . '</a></li>';
    }
    echo "</ul>";
  }
  else{
      
        echo 0;
  }
}
?>