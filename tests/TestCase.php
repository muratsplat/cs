<?php

class TestCase extends Illuminate\Foundation\Testing\TestCase
{
    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost';

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        return $app;
    }
    
    /**
     * To call migrations
     * 
     * @return void
     */
    public function callMigration()
    {
        if ( $this->isSqliteOnMemory() ) {
             
            \Artisan::call('migrate');    
            
            return;

        }        
        
        $this->assertTrue(false,'The migrations is not called!' );            
    }
    
    /**
     * Determine if Db uses sqlite in memory..
     * 
     * @return bool
     */
    public function isSqliteOnMemory()
    {
        return \DB::getDefaultConnection() === 'sqlite_memory';
    }
}
