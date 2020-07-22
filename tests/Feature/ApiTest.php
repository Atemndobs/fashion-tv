<?php
declare(strict_types=1);


namespace Tests\Feature;


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
        $respose = $this->get('/');
        $respose->assertStatus(200);
    }

    /**
     * Server should respond with some error message if query parameter is poorly formatted
     */
    public function testBadRequest()
    {
        $respose = $this->get('/api?q=%%Ae**m%');
        $respose->assertStatus(400);
    }

    /**
     * Hint user If query parameter is incomplete
     */
    public function testValidQueryParameter()
    {
        $respose = $this->get('/api?q');

        $respose->assertStatus(400);
        self::assertSame($respose->original['success'], false);
        self::assertSame($respose->original['errors']['message'],
            'No tv show requested. Please make your url looks like this : http://localhost:8000/api?q=[show name]'
        );

    }

    /**
     * test against typo tolerance from TVMaze API
     * eg deadwood should  return Deadwood
     * @group incomplete
     */
    public function testSearchReturnsExactMatch()
    {
        $names =  [
            'Deadwood',
            'Prison Break',
            'Outlander'
        ];

        foreach ($names as $name){

            $respose = $this->get('?q='.$name);
            $respose->assertSee($respose->original['Matching results for '.$name][0]->show->name);

            self::assertSame($respose->original['Matching results for '.$name][0]->show->name, $name);
        }
    }

    /**
     * Tests if non matching search parameter returns suggestions
     * eg prison should return adn aray of simmilar show names like   [Prison Break , ...]
     * @group incomplete
     */
    public function testSearchNonExactMatchReturnSuggestion()
    {
        $names =  [
            'Deadwoods',
            'Prison Breakers',
            'Trump'
        ];

        foreach ($names as $name){

            $respose = $this->get('?q='.$name);
            $respose->assertSee($respose->original['Matching results for '.$name][0]);

            $jsonResponse = [
                "Sorry we could not find any show named ".$name,
                "Did you mean:",
            ];

            self::assertSame($respose->original['Matching results for '.$name][0], $jsonResponse[0]);
            self::assertSame($respose->original['Matching results for '.$name][1], $jsonResponse[1]);
        }
    }

    /**
     * test against case sensitive  query parameter (tv shoe name) from TVMaze API
     * eg deadwood should return Deadwood or deadwood
     * @group incomplete
     */
    public function testSearchNonCaseSensitive()
    {
        $names =  [
            'deadwood',
            'prison Break',
            'Outlander',
            'qUEen'
        ];

        foreach ($names as $name){

            $respose = $this->get('?q='.$name);
            $respose->assertSee($respose->original['Matching results for '.$name][0]->show->name);

            self::assertSame(strtolower($respose->original['Matching results for '.$name][0]->show->name),
                strtolower($name));
        }
    }

    /**
     * Test if user exeeds api calls per minute. The number of api requests per minute can be
     * adjusted in the api route by changeing throttle parameters; currently set to 10,1
     * So if user makes more than 10 requests in 1 minute thes should recieve an error
     * @group incomplete
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

        foreach ($names as $name){

            $respose = $this->get('?q='.$name);
        }
        $respose->assertStatus(429);
        $respose->assertSee('Too many requests. Please wait a minute');
    }
}
