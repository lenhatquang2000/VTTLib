<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     * Test the new online database detail endpoint.
     */
    public function test_online_database_detail_endpoint(): void
    {
        $response = $this->get('/tai-nguyen/co-so-du-lieu-chi-tiet?CSDLId=1&CSDLName=SpringerLink');

        $response->assertStatus(200);
        $response->assertSee('SpringerLink');
        $response->assertSee('#1');
    }
}
