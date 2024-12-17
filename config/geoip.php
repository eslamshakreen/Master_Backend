<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Logging Configuration
    |--------------------------------------------------------------------------
    |
    | Configure the log settings for when a location is not found
    | for the provided IP.
    |
    */

    'log_failures' => true,

    /*
    |--------------------------------------------------------------------------
    | Include Currency in Results
    |--------------------------------------------------------------------------
    |
    | Enable this to attempt determining the user's currency
    | by matching their ISO code to a preset list of currencies.
    |
    */

    'include_currency' => true,

    /*
    |--------------------------------------------------------------------------
    | Default Service
    |--------------------------------------------------------------------------
    |
    | Specify the default storage driver to be used
    | by the framework using the services listed below.
    |
    */

    'service' => 'maxmind_database',

    /*
    |--------------------------------------------------------------------------
    | Storage Specific Configuration
    |--------------------------------------------------------------------------
    |
    | Configure as many storage drivers as you wish.
    |
    */

    'services' => [

        'maxmind_database' => [
            'class' => \Torann\GeoIP\Services\MaxMindDatabase::class,
            'database_path' => storage_path('app/geoip.mmdb'),
            'update_url' => sprintf('https://download.maxmind.com/app/geoip_download?edition_id=GeoLite2-City&license_key=%s&suffix=tar.gz', env('MAXMIND_LICENSE_KEY')),
            'locales' => ['en'],
        ],

        'maxmind_api' => [
            'class' => \Torann\GeoIP\Services\MaxMindWebService::class,
            'user_id' => env('MAXMIND_USER_ID'),
            'license_key' => env('MAXMIND_LICENSE_KEY'),
            'locales' => ['en'],
        ],

        'ipgeolocation' => [
            'class' => \Torann\GeoIP\Services\IPGeoLocation::class,
            'secure' => true,
            'key' => env('IPGEOLOCATION_KEY'),
            'continent_path' => storage_path('app/continents.json'),
            'lang' => 'en',
        ],

        'ipdata' => [
            'class' => \Torann\GeoIP\Services\IPData::class,
            'key' => env('IPDATA_API_KEY'),
            'secure' => true,
        ],

        'ipfinder' => [
            'class' => \Torann\GeoIP\Services\IPFinder::class,
            'key' => env('IPFINDER_API_KEY'),
            'secure' => true,
            'locales' => ['en'],
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Default Cache Driver
    |--------------------------------------------------------------------------
    |
    | Specify the type of caching to be used by the package.
    |
    | Options:
    |
    |  all  - All locations are cached
    |  some - Cache only the requesting user
    |  none - Disable caching
    |
    */

    'cache' => 'all',

    /*
    |--------------------------------------------------------------------------
    | Cache Tags
    |--------------------------------------------------------------------------
    |
    | Cache tags are not supported when using the file or database cache
    | drivers in Laravel. This is done so that only locations can be cleared.
    |
    */

    'cache_tags' => ['torann-geoip-location'],

    /*
    |--------------------------------------------------------------------------
    | Cache Expiration
    |--------------------------------------------------------------------------
    |
    | Define how long cached locations are valid.
    |
    */

    'cache_expires' => 30,

    /*
    |--------------------------------------------------------------------------
    | Default Location
    |--------------------------------------------------------------------------
    |
    | Return when a location is not found.
    |
    */

    'default_location' => [
        'ip' => '127.0.0.0',
        'iso_code' => 'US',
        'country' => 'United States',
        'city' => 'New Haven',
        'state' => 'CT',
        'state_name' => 'Connecticut',
        'postal_code' => '06510',
        'lat' => 41.31,
        'lon' => -72.92,
        'timezone' => 'America/New_York',
        'continent' => 'NA',
        'default' => true,
        'currency' => 'USD',
    ],

];
