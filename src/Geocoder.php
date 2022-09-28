<?php

namespace CreativeFactoryRV\GoogleMapsGeocoder;

class Geocoder {
    /** @var string */
    private $language;

    /** @var string */
    private $apiKey;

    public function __construct($apiKey, $language = 'en') {
        $this->apiKey = $apiKey;
        $this->language = $language;
    }

    /**
     * @param $address The address we want to be geocoded
     * @return Location Location object istantiated with the information retrieved from Google Maps Platform
     * @throws \Exception If something goes wrong, it throws an exception with information about the problem
     */
    public function query($address): Location {
        $url = 'https://maps.google.com/maps/api/geocode/json?address=' . urlencode($address) . '&key=' . $this->apiKey . '&language=' . $this->language;

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        $data = curl_exec($curl);
        curl_close($curl);

        return new Location($data);
    }
}