# Google Maps Geocoder
A super simple geocoding class that use Google Maps Platform to do the magic.\
You only need to obtain a Google Maps Platform API (https://developers.google.com/maps).\
Optionally, the class can leverage a PRS-6 cache implementation in order to query the Maps Platform only when necessary.


## Install
```bash
composer require creativefactoryrv/google-maps-geocoder
```

## How to use
```php
$geocoder = new Geocoder('XXXXXXXXX-My-Google-Maps-Platform-API-Key-XXXXXXXXX', 'en');
$location = $geocoder->query('Duomo, Milano, Italy');
$latLng = $location->getCoordinates();
echo 'Lat: ' . $latLng['lat'] . ' / Lng: ' . $latLng['lng'];
echo 'ZIP code: ' . $location->getPostalCode();
```

## License
MIT