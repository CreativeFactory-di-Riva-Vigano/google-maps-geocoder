<?php

namespace CreativeFactoryRV\GoogleMapsGeocoder;

class Location {
    const NAME_LONG = 'long_name';
    const NAME_SHORT = 'short_name';
    private $dataJson;
    private $dataDecoded;

    /**
     * @param $response Raw response as returned by Google Masp Platform API.
     * @throws \Exception
     */
    public function __construct($response) {
        $this->dataJson = $response;
        $this->dataDecoded = json_decode($response, true);
        if ( json_last_error() !== JSON_ERROR_NONE ) {
            throw new \Exception('Invalid data');
        }
        if ( $this->dataDecoded['status'] != 'OK' ) {
            throw new \Exception($this->dataDecoded['status']);
        }
    }

    /**
     * Return the Google Maps Platform API response as it is (JSON).
     * @return string A JSON encoded string.
     */
    public function getRaw(): string {
        return $this->dataJson;
    }

    /**
     * Returns values as an associative array.
     * @return mixed
     */
    public function toArray(): array {
        return $this->dataDecoded;
    }

    /**
     * Returns an array with the desired values.
     * @return array
     */
    public function getCoordinates() {
        $lat = $this->dataDecoded['results'][0]['geometry']['location']['lat'];
        $lng = $this->dataDecoded['results'][0]['geometry']['location']['lng'];
        return [
            0 => $lat,
            1 => $lng,
            'lat' => $lat,
            'lng' => $lng
        ];
    }

    /**
     * Returns the country value.
     * @return string|null
     */
    public function getCountry() {
        return $this->getAddressComponent('country', self::NAME_LONG);
    }

    /**
     * Returns the city or locality value.
     * @return string|null
     */
    public function getLocality() {
        return $this->getAddressComponent('locality', self::NAME_LONG);
    }

    /**
     * Returns the postal code or zipcode value.
     * @return string|null
     */
    public function getPostalCode() {
        return $this->getAddressComponent('postal_code', self::NAME_LONG);
    }

    /**
     * Returns the street name (called "route" by Google).
     * @return string|null
     */
    public function getRoute() {
        return $this->getAddressComponent('route', self::NAME_LONG);
    }

    /**
     * Returns the street number value.
     * @return string|null
     */
    public function getStreetNumber() {
        return $this->getAddressComponent('street_number', self::NAME_LONG);
    }

    /**
     * Returns the administrative levels 1 value.
     * @return string|null
     */
    public function getAdministrativeAreaLevel1() {
        return $this->getAddressComponent('administrative_area_level_1', self::NAME_LONG);
    }

    /**
     * Returns the administrative levels 2 value.
     * @return string|null
     */
    public function getAdministrativeAreaLevel2() {
        return $this->getAddressComponent('administrative_area_level_2', self::NAME_LONG);
    }

    /**
     * Returns the administrative levels 3 value.
     * @return string|null
     */
    public function getAdministrativeAreaLevel3() {
        return $this->getAddressComponent('administrative_area_level_3', self::NAME_LONG);
    }

    /**
     * Returns a string representing the address (called "formatted_address" by Google).
     * @return string|null
     */
    public function getFormattedAddress() {
        return $this->dataDecoded['results'][0]['formatted_address'];
    }

    /**
     * @param $format
     * - %A1    Administrative Area Level 1<br>
     * - %a1    Administrative Area Level 1 (short version)<br>
     * - %A2    Administrative Area Level 2<br>
     * - %a2    Administrative Area Level 2 (short version)<br>
     * - %A3    Administrative Area Level 3<br>
     * - %a3    Administrative Area Level 3 (short version)<br>
     * - %C Country
     * - %L Locality
     * - %P Postal code
     * - %R Route
     * - %N Street number
     * @return string Returns a formatted string.
     */
    public function format($format): string {
        $result = $format;
        $result = mb_ereg_replace('%A1', $this->getAdministrativeAreaLevel1(), $result);
        $result = mb_ereg_replace('%a1', $this->getAddressComponent('administrative_area_level_1', self::NAME_SHORT), $result);
        $result = mb_ereg_replace('%A2', $this->getAdministrativeAreaLevel2(), $result);
        $result = mb_ereg_replace('%a2', $this->getAddressComponent('administrative_area_level_2', self::NAME_SHORT), $result);
        $result = mb_ereg_replace('%A3', $this->getAdministrativeAreaLevel3(), $result);
        $result = mb_ereg_replace('%a3', $this->getAddressComponent('administrative_area_level_3', self::NAME_SHORT), $result);
        $result = mb_ereg_replace('%C', $this->getCountry(), $result);
        $result = mb_ereg_replace('%L', $this->getLocality(), $result);
        $result = mb_ereg_replace('%P', $this->getPostalCode(), $result);
        $result = mb_ereg_replace('%R', $this->getRoute(), $result);
        $result = mb_ereg_replace('%N', $this->getStreetNumber(), $result);
        return $result;
    }

    /**
     * @param string $type Address component type
     * @param string $valueType Allowed value are self::NAME_LONG and self::NAME_SHORT
     * @return mixed|void
     */
    private function getAddressComponent(string $type, string $valueType) {
        foreach ( $this->dataDecoded['results'][0]['address_components'] as $v ) {
            if ( $v['types'][0] == $type ) {
                return $v[$valueType];
            }
        }
    }
}