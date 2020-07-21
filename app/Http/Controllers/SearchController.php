<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Tools\ShowFinder;
use GuzzleHttp\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class SearchController
 * @package App\Http\Controllers
 */
class SearchController extends Controller
{

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function searchShow(Request $request)
    {
        $title = request('q');
        if (empty(request('q'))){
            return response()->json([
                "success" => false,
                "errors" => [
                    "message" => "No tv show requested. Please make your url looks like this : http://localhost:8000/api?q=[show name]"
                ]
            ], 400);
        }

        $client = new Client();
        $finder = new ShowFinder($client);

        $response = $finder->findShow($title);

        // if request failed
        if(!$response['success']){
            return response()->json([
                "success"=> false,
                "errors" => [
                    "message" => $response['message']
                ]
            ], $response['code']);
        }

        // if request was successful
        return response()->json([
            "success"=> true,
            "data" => [
                'matches' => [
                    'total' => $response['total_matches'],
                    'records' => $response['matches']
                ],
                'suggestions' => [
                    'total' => $response['total_suggestions'],
                    'records' => $response['suggestions']
                ]
            ]
        ], 200);
    }
}
