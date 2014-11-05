<?php
require_once 'google-api-php-client/autoload.php'; // or wherever autoload.php is located
$client		= new Google_Client();
$client->setApplicationName("JMDesgin_Gallery");
$client->setDeveloperKey("");
$service	= new Google_Service_Books($client);
$optParams	= array('filter' => 'free-ebooks');
$results	= $service->volumes->listVolumes('Henry David Thoreau', $optParams);
echo '<pre>';
print_r($results);
echo '</pre>';
foreach ($results as (object) $item) {
    echo $item['volumeInfo'][''], "<br /> \n";
}