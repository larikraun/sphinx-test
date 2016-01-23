<html>
<body>
<?php
/**
 * Created by PhpStorm.
 * User: Omolara Adejuwon
 * Date: 23/01/2016
 * Time: 01:42
 */
//$conn = mysqli_connect("localhost", "root", "yinka92", "test", "9306");
//Sphinx PDO
$ln_sph = new PDO('mysql:host=127.0.0.1;port=9306', 'root', 'yinka92');
//mysqli_select_db($conn, "test");
$where = ' title = ' . 'test' . ' ';
$finalResponse = array();
//$selection = ' AND '
$search_query = 'this';
$indexes = 'test1';
//$str_query = "SELECT *  FROM $indexes";// AND $where ";
$stmt = $ln_sph->prepare("SELECT * FROM $indexes WHERE MATCH(:match)");
$stmt->bindValue(':match', $search_query, PDO::PARAM_STR);
$stmt->execute();
$response = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<pre>
<code>
    <?php
    $finalResponse['matches'] = $response;
    // echo json_encode($response, JSON_PRETTY_PRINT);
    $meta = $ln_sph->query("SHOW META")->fetchAll(PDO::FETCH_ASSOC);
    foreach ($meta as $m) {
        $meta_map[$m['Variable_name']] = $m['Value'];
    }
    $total_found = $meta_map['total_found'];
    $total = $meta_map['total'];
    $finalResponse['total_found'] = $total_found;
    $finalResponse['total'] = $total;
    //echo $total . ' ' . $total_found;
    $attr = 'year_attr';
    $sql = array();
    $rows = array();
    $sql = "SELECT *,GROUPBY() as selected,COUNT(*) as counts,WEIGHT() as weight FROM $indexes WHERE MATCH(:match) GROUP BY $attr ORDER BY counts DESC";// WHERE MATCH(:match) $where  GROUP BY $attr ORDER BY cnt DESC LIMIT 0,10";


    $stmt = $ln_sph->prepare($sql);
    $stmt->bindValue(':match', $search_query, PDO::PARAM_STR);
    $stmt->execute();
    $rows[$attr] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $finalResponse['facets'][$attr] = $rows[$attr];
    //echo json_encode($rows, JSON_PRETTY_PRINT);


    // string attrs are not yet supported in multi-query optimization, so we run them separate
    $stmt = $ln_sph->prepare("SELECT *,COUNT(*) as cnt FROM $indexes WHERE MATCH(:match) GROUP BY year_attr ORDER BY cnt DESC  LIMIT 0,10");
    $stmt->bindValue(':match', $search_query, PDO::PARAM_STR);
    $stmt->execute();
    $property = $stmt->fetchAll();
    $facets = array();
    foreach ($property as $p) {
        $facets['year_attr'][] = array(
            'value' => $p['year_attr'],
            'count' => $p['cnt']
        );
    }
    //$finalResponse['bl'] = $facets;
    //echo json_encode($facets, JSON_PRETTY_PRINT);
    foreach ($rows as $k => $v) {
        foreach ($v as $x) {
            $facets[$k][] = array(
                'value' => $x['selected'],
                'count' => $x['cnt']
            );
        }
    }
    echo json_encode($finalResponse, JSON_PRETTY_PRINT);
    ?>

</code>
</pre>
</body>
</html>