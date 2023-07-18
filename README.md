# Google Maps Geocoder
A super simple geocoding class that use Google Maps Platform to do the magic.  
You only need to obtain a Google Maps Platform API (https://developers.google.com/maps).  
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

### Get a JSON
Return the Google Maps Platform API response as it is (JSON).
```php
$geocoder = new Geocoder('XXXXXXXXX-My-Google-Maps-Platform-API-Key-XXXXXXXXX', 'en');
$location = $geocoder->query('Duomo, Milano, Italy');
$myJSONString = $location->getRaw();
```

### Get an array
Return the Google Maps Platform API response as an associative array.
```php
$geocoder = new Geocoder('XXXXXXXXX-My-Google-Maps-Platform-API-Key-XXXXXXXXX', 'en');
$location = $geocoder->query('Duomo, Milano, Italy');
$myArray = $location->toArray();
```

### Get latitude and longitude
Returns an array with the desired values.
```php
$geocoder = new Geocoder('XXXXXXXXX-My-Google-Maps-Platform-API-Key-XXXXXXXXX', 'en');
$location = $geocoder->query('Duomo, Milano, Italy');
$myArray = $location->getCoordinates();
```

### Country
Returns the country value.
```php
$geocoder = new Geocoder('XXXXXXXXX-My-Google-Maps-Platform-API-Key-XXXXXXXXX', 'en');
$location = $geocoder->query('Duomo, Milano, Italy');
echo $location->getCountry();
```

### Locality (city)
Returns the city or locality value.
```php
$geocoder = new Geocoder('XXXXXXXXX-My-Google-Maps-Platform-API-Key-XXXXXXXXX', 'en');
$location = $geocoder->query('Duomo, Milano, Italy');
echo $location->getLocality();
```

### Postal code
Returns the postal code or zipcode value.
```php
$geocoder = new Geocoder('XXXXXXXXX-My-Google-Maps-Platform-API-Key-XXXXXXXXX', 'en');
$location = $geocoder->query('Duomo, Milano, Italy');
echo $location->getPostalCode();
```

### Route (street name)
Returns the street name (called "route" by Google).
```php
$geocoder = new Geocoder('XXXXXXXXX-My-Google-Maps-Platform-API-Key-XXXXXXXXX', 'en');
$location = $geocoder->query('Duomo, Milano, Italy');
echo $location->getRoute();
```

### Street number
Returns the street number value.
```php
$geocoder = new Geocoder('XXXXXXXXX-My-Google-Maps-Platform-API-Key-XXXXXXXXX', 'en');
$location = $geocoder->query('Duomo, Milano, Italy');
echo $location->getStreetNumber();
```

### Administrative levels 1
Returns the administrative levels 1 value.
```php
$geocoder = new Geocoder('XXXXXXXXX-My-Google-Maps-Platform-API-Key-XXXXXXXXX', 'en');
$location = $geocoder->query('Duomo, Milano, Italy');
echo $location->getAdministrativeAreaLevel1();
```

### Administrative levels 2
Returns the administrative levels 2 value.
```php
$geocoder = new Geocoder('XXXXXXXXX-My-Google-Maps-Platform-API-Key-XXXXXXXXX', 'en');
$location = $geocoder->query('Duomo, Milano, Italy');
echo $location->getAdministrativeAreaLevel2();
```

### Administrative levels 3
Returns the administrative levels 3 value.
```php
$geocoder = new Geocoder('XXXXXXXXX-My-Google-Maps-Platform-API-Key-XXXXXXXXX', 'en');
$location = $geocoder->query('Duomo, Milano, Italy');
echo $location->getAdministrativeAreaLevel3();
```

### Formatted address
Returns a string representing the address (called "formatted_address" by Google).
```php
$geocoder = new Geocoder('XXXXXXXXX-My-Google-Maps-Platform-API-Key-XXXXXXXXX', 'en');
$location = $geocoder->query('Duomo, Milano, Italy');
echo $location->getFormattedAddress();
```

### Format as you like
Returns a formatted address according to $format.
- %A1    Administrative Area Level 1<br>
- %a1    Administrative Area Level 1 (short version)<br>
- %A2    Administrative Area Level 2<br>
- %a2    Administrative Area Level 2 (short version)<br>
- %A3    Administrative Area Level 3<br>
- %a3    Administrative Area Level 3 (short version)<br>
- %C Country
- %L Locality
- %P Postal code
- %R Route
- %N Street number
```php
$geocoder = new Geocoder('XXXXXXXXX-My-Google-Maps-Platform-API-Key-XXXXXXXXX', 'en');
$location = $geocoder->query('Duomo, Milano, Italy');
$format = '%R %N, %C';
echo $location->format($format);
```

## License
MIT
