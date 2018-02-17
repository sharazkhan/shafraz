<?php

@mysql_connect('localhost', 'c2z13x', 'db7sw920z');
@mysql_select_db('c1z13x_db');
$shopId = $_REQUEST['cid'];

//echo $shopId; 


$sql = "SELECT s.* FROM shopmo_shop AS s WHERE s.id IN(SELECT shopmodule_entity_shop_id FROM
		 shopmo_cityshops WHERE shopmodule_entity_city_id = $shopId) ORDER BY s.id";

//echo $sql; exit;
$query = @mysql_query($sql);
$count = mysql_num_rows($query);
$output = '';
$output .= "<select name=shop>";
if ($count != 0) {

    while ($row = mysql_fetch_array($query)) {

        $output .= "<option value='" . $row['id'] . "'>" . $row['shopname'] . "</option>";
    }
} else {
    $output .= "<option value=''>no shops</option>";
}

$output .= "</select>";

echo $output;
?>