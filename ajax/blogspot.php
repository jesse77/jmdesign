<?php
// Set name of XML file
$file = "http://jesseandacamera.blogspot.ca/atom.xml";
// Load specified XML file or report failure
$xml = simplexml_load_file($file) or die ("Unable to load XML file!");
// Load blog entries
$xml =  $xml ->entry;
// Run loop for the number of available entries
foreach( $xml as $row )
{
// Load the entry publish time
$dtime = date("D jS M, Y", strtotime(strtok($row->published, 'T')));
// Load the link of each blog entry
$titlelink = $row->link[4][href];
// Load the text for Comment and comment counts
$comments = $row->link[1][href];
$comm = $row->link[1][title][0];
/* Display the contents (use your own imaginations here =).) */
  echo "{$row->title}<br />";
// Display publish time
  echo "Published on: $dtime <br />";
// Display blog entry content
  echo "{$row->content}<br />";
// Display number of comments
  echo "$comm <br /><br />";
}
?>