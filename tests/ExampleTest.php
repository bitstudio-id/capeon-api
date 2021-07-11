<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

// use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{
    // use RefreshDatabase;
    
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        // $this->withoutExceptionHandling();
        $this->get('/');

        $this->assertEquals(
            $this->app->version(), $this->response->getContent()
        );
    }
}
 