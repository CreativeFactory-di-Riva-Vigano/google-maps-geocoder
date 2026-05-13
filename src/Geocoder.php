<?php

namespace CreativeFactoryRV\GoogleMapsGeocoder;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\HttpFactory;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\InvalidArgumentException;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;

class Geocoder {
    /** @var string */
    private string $language;

    /** @var string */
    private string $apiKey;

    private ?CacheItemPoolInterface $cacheAdapter;
    /** @var ClientInterface */
    private ClientInterface $httpClient;

    /** @var RequestFactoryInterface */
    private RequestFactoryInterface $requestFactory;

    /**
     * Constructor method to initialize the geocoding class with necessary dependencies.
     *
     * @param string $apiKey The API key for accessing the Google Maps Platform.
     * @param string $language The preferred language for the API responses (default is 'en').
     * @param CacheItemPoolInterface|null $cacheAdapter Optional PSR-6 cache adapter for caching responses.
     * @param ClientInterface|null $httpClient Optional PSR-18 HTTP client for making API requests. If not provided, a Guzzle client will be used if available.
     * @param RequestFactoryInterface|null $requestFactory Optional PSR-17 request factory for creating HTTP requests. If not provided, a default factory will be used if available.
     *
     * @return void
     *
     * @throws Exception If no suitable PSR-18 HTTP client or PSR-17 request factory is provided or available.
     */
    public function __construct(string $apiKey, string $language = 'en', ?CacheItemPoolInterface $cacheAdapter = null, ?ClientInterface $httpClient = null, ?RequestFactoryInterface $requestFactory = null) {
        $this->apiKey = $apiKey;
        $this->language = $language;
        $this->cacheAdapter = $cacheAdapter;

        // Gestione client/factory di default (Guzzle)
        if ( $httpClient === null ) {
            if ( class_exists('\Http\Adapter\Guzzle7\Client') ) {
                $httpClient = new Client();
            }
            elseif ( class_exists('\GuzzleHttp\Client') ) {
                // Fallback usando Guzzle direttamente (wrapping manuale)
                $httpClient = new Client();
            }
            else {
                throw new Exception("No PSR-18 HTTP Client provided and no compatible HTTP client is installed.");
            }
        }
        $this->httpClient = $httpClient;

        if ( $requestFactory === null ) {
            if ( class_exists('\GuzzleHttp\Psr7\HttpFactory') ) {
                $requestFactory = new HttpFactory();
            }
            else {
                throw new Exception("No PSR-17 Request Factory provided and Guzzle (guzzlehttp/psr7) is not installed.");
            }
        }
        $this->requestFactory = $requestFactory;
    }

    /**
     * @param string $address The address we want to be geocoded.
     * @return Location Location object istantiated with the information retrieved from Google Maps Platform.
     * @throws InvalidArgumentException
     * @throws ClientExceptionInterface
     */
    public function query(string $address): Location {
        $doCaching = false;
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
            catch (Exception $e) {
                $doCaching = false;
            }
        }

        $url = 'https://maps.google.com/maps/api/geocode/json?address=' . urlencode($address) . '&key=' . $this->apiKey . '&language=' . $this->language;

        // Crea la richiesta PSR-7
        $request = $this->requestFactory->createRequest('GET', $url);

        // Invia la richiesta usando il client PSR-18
        $response = $this->httpClient->sendRequest($request);

        // Verifica il codice di stato HTTP
        if ( $response->getStatusCode() !== 200 ) {
            throw new Exception('HTTP request failed with status code: ' . $response->getStatusCode(), $response->getStatusCode());
        }

        // Ottieni il corpo della risposta
        $data = $response->getBody()
            ->getContents()
        ;

        // Decodifica la risposta JSON per verificare la presenza di errori
        $decodedData = json_decode($data, true);

        if ( json_last_error() !== JSON_ERROR_NONE ) {
            throw new Exception('Invalid JSON response: ' . json_last_error_msg());
        }

        if ( $decodedData['status'] != 'OK' ) {
            if ( isset($decodedData['error_message']) && !empty($decodedData['error_message']) ) {
                $errorMessage = is_string($decodedData['error_message']) ? $decodedData['error_message'] : json_encode($decodedData['error_message']);
            }
            throw new Exception('API error: ' . $errorMessage ?: $decodedData['status']);
        }

        $location = new Location($data);

        if ( $doCaching && ($this->cacheAdapter != null) ) {
            $item->set($location);
            $this->cacheAdapter->save($item);
        }

        return $location;
    }
}