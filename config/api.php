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
                'baseUrl'   => 'https://livereportingapi.clearsettle.com/api/v3',
                'verify'    => true, // enable ssl
                'timeout'   => 3,
            ],
        
        'test'  => [
                'baseUrl'   => 'https://testreportingapi.clearsettle.com/api/v3',
                'verify'    => true,
                'timeout'   => 2,
            
            ],
    ], 
    

];
