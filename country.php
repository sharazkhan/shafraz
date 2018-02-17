<?php

include 'lib/bootstrap.php';
$core->init(Zikula_Core::STAGE_ALL | Zikula_Core::STAGE_AJAX & ~Zikula_Core::STAGE_DECODEURLS);
$keyword = $_REQUEST['data'];

if (!empty($keyword)) {
    $sql = "select countryId,countryName from zselex_country where countryName like '" . $keyword . "%' order by countryName";

//$sql = "select name from ".$db_table."";



    $statement = Doctrine_Manager::getInstance()->connection();
    $results = $statement->execute($sql);
    $values = $results->fetchAll();


    if (count($values) > 0) {
        echo '<ul class="list">';


        foreach ($values as $row) {

            $t = $row['countryName'];
            $tg = explode(',', $t);


            $str = strtolower($row['countryName']);
            $start = strpos($str, $keyword);
            $end = similar_text($str, $keyword);
            $last = substr($str, $end, strlen($str));
            $first = substr($str, $start, $end);

            $final = '<span class="bold">' . ucwords($first) . '</span>' . $last;

            echo '<li><a href=\'javascript:void(0);\' id="' . $row['countryId'] . '">' . $final . '</a></li>';
        }
        echo "</ul>";
    } else {
        echo 0;
    }
} else {


    $sql = "select countryId,countryName from zselex_country order by countryName";

//$sql = "select name from ".$db_table."";



    $statement = Doctrine_Manager::getInstance()->connection();
    $results = $statement->execute($sql);
    $values = $results->fetchAll();



    echo '<ul class="list">';


    foreach ($values as $row) {

        $t = $row['countryName'];

        echo '<li><a href=\'javascript:void(0);\' id="' . $row['countryId'] . '">' . $t . '</a></li>';
    }
    echo "</ul>";
}
?>