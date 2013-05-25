<?
header('Content-Type: text/html; charset=utf-8');

require_once('SolrPhpClient/Apache/Solr/Service.php');
// example xml document to upload

function test_solr(){
 $limit = 10;
 $query = isset($_REQUEST['q']) ? $_REQUEST['q'] : false;
 $results = false;
  if (get_magic_quotes_gpc() == 1)
  {
    $query = stripslashes($query);
  }

 $solr = new Apache_Solr_Service('174.129.138.69', '8983', '/solr');
 try{
   $results = $solr->search($query, 0, $limit);
 }
 catch (Exception $e){
        die("<html><head><title>SEARCH EXCEPTION</title><body><pre>{$e->__toString()}</pre></body></html>");
 }
}

?>
<html>
  <head>
    <title>PHP Solr Client Example</title>
  </head>
  <body>
    <form  accept-charset="utf-8" method="get">
      <label for="q">Search:</label>
      <input id="q" name="q" type="text" value="<?php echo htmlspecialchars($query, ENT_QUOTES, 'utf-8'); ?>"/>
      <input type="submit"/>
    </form>

<?php

// display results
if ($results)
{
  $total = (int) $results->response->numFound;
  $start = min(1, $total);
  $end = min($limit, $total);
?>
    <div>Results <?php echo $start; ?> - <?php echo $end;?> of <?php echo $total; ?>:</div>
    <ol>
<?php
  // iterate result documents
  foreach ($results->response->docs as $doc)
  {
?>
      <li>
        <table style="border: 1px solid black; text-align: left">
<?php
    // iterate document fields / values
    foreach ($doc as $field => $value)
    {
?>
          <tr>
            <th><?php echo htmlspecialchars($field, ENT_NOQUOTES, 'utf-8'); ?></th>
            <td><?php echo htmlspecialchars($value, ENT_NOQUOTES, 'utf-8'); ?></td>
          </tr>
<?php
    }
?>
        </table>
      </li>
<?php
  }
?>
    </ol>
<?php
}
?>
  </body>
</html>

<?
$example_doc = <<<EOD
<add>
<doc>
  <field name="id">SOLR1000</field>
  <field name="name">Solr, the Enterprise Search Server</field>
  <field name="manu">Apache Software Foundation</field>
  <field name="cat">software</field>
  <field name="cat">search</field>
  <field name="features">Advanced Full-Text Search Capabilities using Lucene</field>
  <field name="features">Optimized for High Volume Web Traffic</field>
  <field name="features">Standards Based Open Interfaces - XML and HTTP</field>
  <field name="features">Comprehensive HTML Administration Interfaces</field>
  <field name="features">Scalability - Efficient Replication to other Solr Search Servers</field>
  <field name="features">Flexible and Adaptable with XML configuration and Schema</field>
  <field name="features">Good unicode support: h&#xE9;llo (hello with an accent over the e)</field>
  <field name="price">0</field>
  <field name="popularity">10</field>
  <field name="inStock">true</field>
  <field name="incubationdate_dt">2006-01-17T00:00:00.000Z</field>
</doc>
</add>
EOD;


function post_sample_doc() {
  return true;
}

echo $example_doc;
?>