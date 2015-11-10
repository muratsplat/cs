<?php

return [

    /**
     * This option can be useful to change connection url 
     * to access ClearSettle API
     * 
     * Options:
     *  - live
     *  - test  
     * 
     */
    'default' => env('API_DEFAULT', 'test'),    
    
    /**
     * We have two urls to access ClearSettle API
     * 
     * In Production it can selected one of all.
     */
    'apis' => [
        
        'live'  => [
                'base_uri'   => 'https://livereportingapi.clearsettle.com/api/v3/',
                'verify'    => true, // enable ssl
                'timeout'   => 10,
            ],
        
        'test'  => [
                'base_uri'   => 'https://testreportingapi.clearsettle.com/api/v3/',
                'verify'    => true,
                'timeout'   => 10,
            
            ],
    ], 
    

];
