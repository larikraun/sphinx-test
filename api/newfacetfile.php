<html>
<body>
<?php
/**
 * Created by PhpStorm.
 * User: Omolara Adejuwon
 * Date: 23/01/2016
 * Time: 01:42
 */
$conn = mysqli_connect("localhost", "root", "yinka92", "test", "9306");
//mysqli_select_db($conn, "test");
$where = ' group_id = ' . 1 . ' ';
//$selection = ' AND '
$indexes = 'test1';
$str_query = "SELECT *  FROM $indexes";// WHERE MATCH('document') AND $where ";

$result = mysqli_query($conn, $str_query);
$response = array();
while ($row = mysqli_fetch_assoc($result)) {
    $response[] = $row;
} ?>
<pre>
<code>
    <?php
    echo json_encode($response,JSON_PRETTY_PRINT);

    //WHERE MATCH('document') $where  " . "FACET group_id ORDER BY COUNT(*) DESC  ";?>

</code>
</pre>
</body>
</html>