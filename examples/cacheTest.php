<?php

use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use CreativeFactoryRV\GoogleMapsGeocoder\Geocoder;

include(__DIR__ . '/../vendor/autoload.php');

$apiKey = 'XXXXXXXXX-My-Google-Maps-Platform-API-Key-XXXXXXXXX';
$language = 'en';

// Istantiate the PSR-6 cache adapter (ex. Symfony FilesystemAdapter)
$cacheAdapter = new FilesystemAdapter('', 0, 'your-cache-directory');

$geocoder = new Geocoder($apiKey, $language, $cacheAdapter);

try {
    $location = $geocoder->query('CreativeFactory, Casatenovo');

    $latLng = $location->getCoordinates();
    echo 'Lat: ' . $latLng['lat'] . ' / Lng: ' . $latLng['lng'] . '<br>';
    echo $location->getCountry() . '<br>';
    echo $location->getLocality() . '<br>';
    echo $location->getPostalCode() . '<br>';
    echo $location->getRoute() . '<br>';
    echo $location->getStreetNumber() . '<br>';
    echo $location->getAdministrativeAreaLevel1() . '<br>';
    echo $location->getAdministrativeAreaLevel2() . '<br>';
    echo $location->getAdministrativeAreaLevel3() . '<br>';
    echo $location->getFormattedAddress() . '<br>';
    echo $location->format('%R %N<br>%P %L (%a2)<br>%C');
}
catch (Exception $e) {
    echo $e->getMessage();
}