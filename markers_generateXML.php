<?php
require_once("inc/config.php");
require(ROOT_PATH . "inc/database.php");

// Start XML file, create parent node
$dom = new DOMDocument("1.0");
$node = $dom->createElement("markers");
$parnode = $dom->appendChild($node);

// query database for marker locations
try {
  $markers_query = $db->prepare('SELECT address, name, lat, lng, type, html, website FROM markers');
 	$markers_query->execute();
} catch (Exception $e) {
  echo "Database connection error. Please try again later.";
	exit;
}
$markers = $markers_query->fetchAll(PDO::FETCH_ASSOC);

header("Content-type: text/xml");

//Iterate through the rows, adding XML nodes for each
foreach ($markers as $row) {
  $node = $dom->createElement("marker");
  $newnode = $parnode->appendChild($node);
  $newnode->setAttribute("name", $row['name']);
  $newnode->setAttribute("address", $row['address']);
  $newnode->setAttribute("lat", $row['lat']);
  $newnode->setAttribute("lng", $row['lng']);
  $newnode->setAttribute("type", $row['type']);
  $newnode->setAttribute("html", $row['html']);
  $newnode->setAttribute("website", $row['website']);
}

echo $dom->saveXML();

?>