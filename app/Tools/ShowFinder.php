<?php
declare(strict_types=1);


namespace App\Tools;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use http\Message;

/**
 * Class ShowFinder
 * @package App\Tools
 */
class ShowFinder
{
    // constant used so that the base api url can easily nbe changed in the future
    const BASEURL = "http://api.tvmaze.com/search/shows";

    /**
     * @var Client
     */
    protected $client;

    /**
     * ShowFinder constructor.
     * @param $client
     */
    public function __construct( $client)
    {
        // This allows for easily switching API clients
        $this->client = $client;
    }

    /**
     * @param string $title
     * @return array
     * fetch shows for the TVMaze API
     */
    public function findShow(string $title): array
    {
        if (!is_string($title)){
            $title =  strval($title);
        }
        $url = self::BASEURL . "?q={$title}";

      //  dd($this->client->get($url)->get);

        try {
            $request = $this->client->get($url);


            if ($request->getStatusCode() == 200) {
                $response = json_decode($request->getBody()->getContents());
                if (empty($response)){
                    return [
                        "success" => true,
                        "total_matches" => count($response),
                        "matches" => 'No matches for '.$title,
                        "total_suggestions" => count($response),
                        "suggestions" => 'No suggestions for '.$title,
                    ];
                }
                return $this->processSuccessResponse($response, $title);
            } else {

                return [
                    "success" => false,
                    "code" => $request->getStatusCode(),
                    "message" => $request->getReasonPhrase()
                ];
            }
        } catch (ClientException $e) {

            return [
                "success" => false,
                "code" => 400,
                "message" => $e->getMessage()
            ];
        }
    }

    /**
     * @param $response
     * @param $title
     * @return array
     *
     */
    private function processSuccessResponse($response, $title) : array
    {
        $matches = [];
        $suggestions = [];
        list($show, $matches) = $this->fetchShows($response, $title, $matches);


        if (empty($matches)){
            foreach ($response as $show) {
                $suggestions[] = $show->show->name;
            }
        }

        return [
            "success" => true,
            "total_matches" => count($matches),
            "matches" => $matches,
            "total_suggestions" => count($suggestions),
            "suggestions" => $suggestions
        ];
    }


    /**
     * @param $shows
     * @param string $title
     * @param array $matches
     * @return array
     */
    private function fetchShows($shows, string $title, array $matches): array
    {
        foreach ($shows as $show) {
            $showName = $show->show->name;
            $searchParam = $title;

            if (strcmp(strtolower($showName), strtolower($searchParam)) === 0) {
                $matches[] = $show;
            }
        }

        return [$show, $matches];
    }

}
