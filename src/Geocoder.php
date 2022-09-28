<?php

namespace CreativeFactoryRV\GoogleMapsGeocoder;

use Psr\Cache\CacheItemPoolInterface;

class Geocoder {
    /** @var string */
    private $language;

    /** @var string */
    private $apiKey;

    private $cacheAdapter;

    /**
     * @param string $apiKey Google Maps Platform API key obtained from Google.
     * @param string $language Results langage.
     * @param CacheItemPoolInterface|null $cacheAdapter PSR-6 cache adapter implementing CacheItemPoolInterface.
     */
    public function __construct(string $apiKey, string $language = 'en', CacheItemPoolInterface $cacheAdapter = null) {
        $this->apiKey = $apiKey;
        $this->language = $language;
        $this->cacheAdapter = $cacheAdapter;
    }

    /**
     * @param string $address The address we want to be geocoded.
     * @return Location Location object istantiated with the information retrieved from Google Maps Platform.
     * @throws \Exception If something goes wrong, it throws an exception with information about the problem.
     */
    public function query(string $address): Location {
        if ( $this->cacheAdapter != null ) {
            $cacheIndex = md5(__CLASS__ . __FUNCTION__ . '|' . serialize(func_get_args()));
            try {
                $item = $this->cacheAdapter->getItem($cacheIndex);
                $isHit = $item->isHit();
                if ( $isHit ) {
                    return $item->get();
                }
                else {
                    $doCaching = true;
                }
            }
            catch (\Exception $e) {
                $doCaching = false;
            }
        }

        $url = 'https://maps.google.com/maps/api/geocode/json?address=' . urlencode($address) . '&key=' . $this->apiKey . '&language=' . $this->language;

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        $data = curl_exec($curl);
        curl_close($curl);

        $location = new Location($data);

        if ( $doCaching && ($this->cacheAdapter != null) ) {
            $item->set($location);
            $this->cacheAdapter->save($item);
        }

        return $location;
    }
}