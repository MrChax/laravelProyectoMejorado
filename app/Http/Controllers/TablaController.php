<?php

namespace App\Http\Controllers;

use Request;
use GuzzleHttp\Client;

class TablaController extends Controller
{
    function index(){
        try{
            $client = new Client(['verify' => false]);
            $request = $client->get('https://api.github.com/users');
            $response = json_decode($request->getBody()->getContents());
            return view('usersTable', ['users' => $response]);
        }catch(RequestExeption $e){
            return null;            
        }
    }

    function usersDetails(){
        $username = Request::input('username'); //recibo algo que me manda un ajax      
        try{
            $client = new Client(['verify' => false]);
            $request = $client->get('https://api.github.com/users/'.$username); //el . concatena un string con otro
            $response = json_decode($request->getBody()->getContents());
            return json_encode($response);
        }catch(RequestExeption $e){
            return null;            
        }
    }

}
