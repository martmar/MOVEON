<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Algorithm\Dice;
use GuzzleHttp\Client;

class BattleController extends Controller
{
    protected $battleAlgorithm = null;

    protected function setBattleAlgorithm()
    {
        $this->battleAlgorithm = new Dice();
    }

    public function index(Request $request)
    {
        return response()->json(['method' => 'index']);
    }

    public function get($id)
    {
        return response()->json(['method' => 'get', 'id' => $id]);
    }

    public function create(Request $request)
    {
        return response()->json(['method' => 'create']);
    }

    public function update(Request $request, $id)
    {
        return response()->json(['method' => 'update', 'id' => $id]);
    }

    public function delete($id)
    {
        return response()->json(['method' => 'delete', 'id' => $id]);
    }

    public function duel(Request $request)
    {
        $this->setBattleAlgorithm();

        $duelResult = $this->battleAlgorithm->fight();

        $client = new Client(['verify' => false]);

        $player1Data= $client->get('http://microservice_user_nginx/api/v1/user/' . $request->input('userA'));
        $player2Data= $client->get('http://microservice_user_nginx/api/v1/user/' . $request->input('userB'));

        return response()->json(
            [
                'player1' => json_decode($player1Data->getBody()),
                'player2' => json_decode($player2Data->getBody()),
                'duelResults' => $duelResult
            ]
        );
    }
}
