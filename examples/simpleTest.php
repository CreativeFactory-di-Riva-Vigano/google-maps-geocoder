<?php

use CreativeFactoryRV\GoogleMapsGeocoder\Geocoder;

include(__DIR__ . '/../vendor/autoload.php');

$apiKey = 'XXXXXXXXX-My-Google-Maps-Platform-API-Key-XXXXXXXXX';
$language = 'en';

$geocoder = new Geocoder($apiKey, $language);

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