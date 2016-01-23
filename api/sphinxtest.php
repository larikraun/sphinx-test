<html>
<body>
<?php
mysql_connect("localhost", "root", "yinka92");
mysql_select_db("test");
require("sphinxapi.php");
require_once('FSphinx/FSphinxClient.php');
require_once('FSphinx/Facet.php');
require_once('FSphinx/MultiFieldQuery.php');
$q = $_GET["q"];

$s = new  FSphinx\FSphinxClient();

$s->setServer("127.0.0.1", 9312); // NOT "localhost" under Windows 7!
$s->setMatchMode(SPH_MATCH_EXTENDED);
$s->SetLimits(0, 25);
$s->setFilter("tag_attr", array(7), false);
$s->setGroupBy("tag_attr", SPH_GROUPBY_ATTR);
$s->attachQueryParser(new FSphinx\MultiFieldQuery());

$tagFacet = new FSphinx\Facet("tag");
$s->attachFacet($tagFacet);

$yearFacet = new FSphinx\Facet(("year"));
$s->attachFacet($yearFacet);
$result = $s->Query($q, "test1"); ?>
<pre>
<code>
    <?php echo $tagFacet; ?>
    <?php echo $yearFacet; ?>
</code>
</pre>
<?php

if ($result['total'] > 0) {
    echo 'Total: ' . $result['total'] . "<br>\n";
    echo 'Total Found: ' . $result['total_found'] . "<br>\n";
    echo '<table>';
    echo '<tr><td>No.</td><td>ID</td><td>Group ID</td><td>Group ID 2</td><td>Date Added</td><td>Title</td><td>Content</td></tr>';
    foreach ($result['matches'] as $id => $otherStuff) {
        $row = mysql_fetch_array(mysql_query("select * from documents where id = $id"));
        extract($row);
        ++$no;
        echo "<tr><td>$no</td><td>$id</td><td>$group_id</td><td>$group_id2</td><td>$date_added</td><td>$title</td><td>$content</td></tr>";
    }
    echo '</table>';
} else {

    print $s->GetLastError();
    echo ' No results found';
}


?>
<pre>
<code>
    <?php echo json_encode($result, JSON_PRETTY_PRINT); ?>

</code>
</pre>
</body>
</html>
