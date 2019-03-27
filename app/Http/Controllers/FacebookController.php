<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Facebook\Facebook;
use Facebook\Exceptions\FacebookResponseException;

class FacebookController extends Controller
{
    private $fb_api;

    public function __construct(Facebook $facebook)
    {
        $this->fb_api = $facebook;
    }

    public function getFacebookPages()
    {
        try {
            $request = $this->fb_api->get('/me?fields=accounts', 'EAAE6gfPUZCFgBABwcfCKYLGPvYhAVnKoqE0IQh5sju5jy3PMBuzir1uURMx4lynhfEg1dgHXec06Yboo23fvIofHGJECXDud2Yh3a1rVOSoma9plUAX7UfI8StZAyWbYu5ZBVDKdblW4KLBZBVkIgg0AsfZCD6BuxRVfZBGzSIxuWO9cCJKeWT');
            $response = json_decode($request->getBody(), true);
            $pages = $response['accounts']['data'];

            return view('home', compact('pages'));
        } catch (Facebook\Exceptions\FacebookResponseException $e) {
            echo 'Facebook SDK error: ' . $e->getMessage();
        }
    }

    public function postFacebookPage(Request $request)
    {
        try {
            // $message = $request['message'];
            // $access_token = $request['access_token'];
            // $page_id = $request['page_id'];
            $message = 'TEST HTTP NEW 1';
            $page_id = '430266707720161';
            $access_token = 'EAAE6gfPUZCFgBAJK3oLA69aAYJ6uP2Kl1opIILezUAZC6uBREuDuIHdC3vIKhSYg2KusAjQdZAlyooqG69FFx32rsBwHYhSXqC9LAkjAcUdYzS7iBliZAbtDeVx7KOw4mgL2eknAPzAasAdTlxwzJHS2bDrzMIvT9hqmUq891mOtzKATKtz5wo5rn2k47ZCwZD';

            $response = $this->fb_api->post(
                '/' . $page_id . '/feed',
                array('message' => $message),
                $access_token
            );

            if ($response->getHttpStatusCode() == 200) {
                return 'Postagem realizada com sucesso';
            }

            return 'Postagem não realizada';
        } catch (Facebook\Exceptions\FacebookResponseException $e) {
            echo 'Facebook SDK error: ' . $e->getMessage();
        }
    }

    public function facebookImagePostPage(Request $request)
    {
        $image = $request->file('source');
        $name = time() . '.' . $image->getClientOriginalName();
        $folder = storage_path('images');
        $image->move($folder, $name);
        $absolute_path = $folder . '/' . $name;

        $message = 'TEST IMAGE';
        $page_id = '430266707720161';
        $access_token = 'EAAE6gfPUZCFgBAJK3oLA69aAYJ6uP2Kl1opIILezUAZC6uBREuDuIHdC3vIKhSYg2KusAjQdZAlyooqG69FFx32rsBwHYhSXqC9LAkjAcUdYzS7iBliZAbtDeVx7KOw4mgL2eknAPzAasAdTlxwzJHS2bDrzMIvT9hqmUq891mOtzKATKtz5wo5rn2k47ZCwZD';

        $response = $this->fb_api->post(
            '/' . $page_id . '/feed',
            array(
                'message' => $message,
                'source' => $this->fb_api->fileToUpload($absolute_path)
            ),
            $access_token
        );

        if ($response->getHttpStatusCode() == 200) {
            return 'Postagem realizada com sucesso';
        }

        return 'Postagem não realizada';
    }
}
