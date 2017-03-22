<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class OAuthController extends Controller
{
    public function oauth()
    {
        $query = http_build_query([
            'client_id' => '3',
            'redirect_uri'=>'http://laravelapp.dev/callback',
            'response_type'=>'code',
            'scope'=>'',
        ]);
        return redirect('http://lar.dev/oauth/authorize?'.$query);
    }
    public function callback(Request $request)
    {
        $http = new \GuzzleHttp\Client();
        $response = $http->post('http://lar.dev/oauth/token', [
            'form_params'=> [
                'grant_type' => 'authorization_code',
                'client_id'  => '3',
                'client_secret'=>'pZNyowjRQHEJwSnA1FmziqAmvVoelIM1VBu6b6Eb',
                'redirect_uri' => 'http://laravelapp.dev/callback',
                'code'=>$request->get('code'),
            ]

//            'form_params'=> [
//                'grant_type' => 'password',
//                'client_id'  => '5',
//                'client_secret'=>'i2H11gR6iDwciwomzIvfJ4BcXLSkV0cdPXA7vjIW',
//                'username' => 'overhook@qq.com',
//                'password'=>'aa111111',
//                'scope'=>'',
//            ]
        ]);
        $accessToken = Arr::get(json_decode((string) $response->getBody(), true), 'access_token');

        return $this->getUserByToken($accessToken);
    }

    public function getUserByToken($accessToken)
    {
        $http = new \GuzzleHttp\Client();
        $headers = ['Authorization'=>'Bearer ' . $accessToken, 'Accept'=>'application/json'];
        $request = new \GuzzleHttp\Psr7\Request('GET', 'http://lar.dev/api/user', $headers);

        $response = $http->send($request);
        return json_decode((string) $response->getBody(), true);
    }
}
