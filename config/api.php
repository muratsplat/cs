<?php

return [

    /**
     * This option can be useful to change connection url 
     * to access Bumin API
     * 
     * Options:
     *  https://livereportingapi.clearsettle.com/api/v3
     *  https://testreportingapi.clearsettle.com/api/v3
     * 
     */
    'default' => env('API_DEFAULT', 'test'),
    
    
    /**
     * We have two urls to access Bumin API
     * 
     * In Production it can selected one of all.
     */
    'urls' => [
        
        'live'  => 'https://livereportingapi.clearsettle.com/api/v3',
        
        'test'  => 'https://testreportingapi.clearsettle.com/api/v3',
    ], 
    

];
