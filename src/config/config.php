<?php
return array(
    /*
    |--------------------------------------------------------------------------
    | Cache
    |--------------------------------------------------------------------------
    |
    | The package only caches the most necessary data in a keyless array to 
    | minimalize the cache usage.
    |
    | Uses Laravel's built-in Cache system if set to true.
    |
    | Default: True
    */
    'cache' => true,

    /*
    |--------------------------------------------------------------------------
    | Cache Interval
    |--------------------------------------------------------------------------
    |
    | Caching for X day, in case if you don't want to overfill your memory with 
    | old results.
    |
    | You can increase the interval but Google releasing a new Chrome every time 
    | when someone blinks so.
    |
    | Default: 7
    */
    'cache_interval' => 7,

    /*
    |--------------------------------------------------------------------------
    | Cache Prefix
    |--------------------------------------------------------------------------
    |
    | Prefix used for the result cache keys.
    |
    | Default: hbd_
    */
    'cache_prefix'  => 'hbd_',

    /*
    |--------------------------------------------------------------------------
    | Browscap Cache
    |--------------------------------------------------------------------------
    |
    | The "garetjax/phpbrowscap" package's using a file cache for the browsecap.ini 
    | and it's cached format.
    |
    | If set to NULL it will use the package's cache directory or you can set your own path.
    |
    | Default: null
    */
    'browscap_cache' => null,
);
