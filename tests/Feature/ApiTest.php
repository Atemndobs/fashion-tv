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
     * @group incomplete
     */
    public function testBadRequest()
    {
        $respose = $this->get('?q=%%Ae**m%');
        $respose->assertStatus(400);
    }

    /**
     * Hint user If query parameter is incomplete
     * @group incomplete
     */
    public function testValidQueryParameter()
    {
        $respose = $this->get('?q');

        $respose->assertSee('');
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
}
