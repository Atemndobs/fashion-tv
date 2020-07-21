<?php
declare(strict_types=1);


namespace Tests\Feature;


use Tests\TestCase;

/**
 * Class ApiTest
 * @package Tests\Feature
 */
class ApiTest extends TestCase
{
    public function testApiRoute()
    {
        $respose = $this->get('/');
        $respose->assertStatus(200);
    }
}
