<?php
declare(strict_types=1);


namespace Tests\Feature;


use GuzzleHttp\Client;
use Illuminate\Support\Facades\Route;
use PHPUnit\Framework\SkippedTest;
use Tests\TestCase;

/**
 * Class ApiTest
 * @package Tests\Feature
 */
class ApiTest extends TestCase
{
    /**
     * Verify if server is up
     */
    public function testApiRoute()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    /**
     * Server should respond with some error message if query parameter is poorly formatted
     */
    public function testBadRequest()
    {
        $response = $this->get('/api?q=%%Ae**m%');
        $response->assertStatus(400);
    }

    /**
     * Hint user If query parameter is incomplete
     * test should return 400 Bad request
     */
    public function testValidQueryParameter()
    {
        $response = $this->get('/api?q');

        $response->assertStatus(400);
        self::assertSame($response->original['success'], false);
        self::assertSame($response->original['errors']['message'],
            'No tv show requested. Please make your url looks like this : http://localhost:8000/api?q=[show name]'
        );

    }

    /**
     * test against typo tolerance from TVMaze API
     * eg deadwood should  return Deadwood
     */
    public function testSearchReturnsExactMatch()
    {
        $names =  [
            'Deadwood',
            'Prison Break',
            'Outlander'
        ];

        foreach ($names as $name){

            $response = $this->get('/api?q='.$name);

            self::assertSame($response->original['success'], true);

            self::assertSame($response->original['data']['matches']['total'],
                1
            );
            self::assertSame($response->original['data']['suggestions']['total'],
                0
            );
            self::assertSame($response->original['data']['matches']['records'][0]->show->name, $name);
        }
    }

    /**
     * Tests if non matching search parameter returns suggestions
     * eg prison should return adn aray of simmilar show names like   [Prison Break , ...]
     */
    public function testSearchNonExactMatchReturnSuggestion()
    {
        $names =  [
            'Deadwoods',
            'Breking dad',
            'pison Brek'
        ];

        foreach ($names as $name){

            $response = $this->get('/api?q='.$name);
            self::assertSame($response->original['success'], true);

            self::assertSame($response->original['data']['matches']['total'],
                0
            );

            self::assertLessThanOrEqual($response->original['data']['suggestions']['total'] , 1);
        }
    }

    /**
     * test against case sensitive  query parameter (tv shoe name) from TVMaze API
     * eg deadwood should return Deadwood or deadwood
     */
    public function testSearchNonCaseSensitive()
    {
        $names =  [
            'DeadWood',
            'prison Break',
        ];

        foreach ($names as $name){

            $response = $this->get('/api?q='.$name);

            self::assertSame($response->original['success'], true);

            self::assertSame($response->original['data']['matches']['total'],
                1
            );
            self::assertSame(strtolower($response->original['data']['matches']['records'][0]->show->name),
                strtolower($name));
        }
    }

    /**
     * Test if user exeeds api calls per minute. The number of api requests per minute can be
     * adjusted in the api route by changeing throttle parameters; currently set to 10,1
     * So if user makes more than 10 requests in 1 minute thes should recieve an error
     */
    public function testApiRequestLimits()
    {
        $names =  [
            'Deadwood',
            'Prison Break',
            'Outlander',
            'Admin',
            'Thrump',
            'Corona',
            'Queen',
            'Breaking Bad',
            'Suits',
            'freud',
            'tatort',
            'president',
            'game of thrones'
        ];

        for ($i = 0; $i < sizeof($names); $i++) {
          $this->get('http://127.0.0.1:8000/api?q=' . $names[$i]);
        }

        $response = $this->get('http://127.0.0.1:8000/api?q=');
        self::assertSame($response->original['errors']['message'],
            'Too many requests. Please wait a minute'
        );
    }
}
