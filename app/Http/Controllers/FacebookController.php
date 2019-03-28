<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Facebook\Facebook;
use Facebook\Exceptions\FacebookResponseException;
use Illuminate\Support\Arr;

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
            $request = $this->fb_api->get('/me?fields=accounts', Auth::user()->token);
            $response = json_decode($request->getBody(), true);
            $pages = $response['accounts']['data'];

            return view('facebook_pages', compact('pages'));
        } catch (Facebook\Exceptions\FacebookResponseException $e) {
            echo 'Facebook SDK error: ' . $e->getMessage();
        }
    }

    public function postFacebookPage(Request $request)
    {
        $payload = array();
        $action = "";

        if ($request['scheduling']) {
            $scheduling = strtotime($request['scheduling']);
            $payload['scheduled_publish_time'] = strtotime("+3 hour", $scheduling);
            $payload['published'] = 'false';
        }

        if ($request->file('image')) {
            $image = $request->file('image');
            $name = time() . '.' . $image->getClientOriginalName();
            $folder = storage_path('images');
            $image->move($folder, $name);
            $absolute_path = $folder . '/' . $name;
            $payload['source'] = $this->fb_api->fileToUpload($absolute_path);
            $payload['message'] = $request['message'];
            $action = '/photos';
        }

        if ($request['message'] && !($request['image'])) {
            $payload['message'] = $request['message'];
            $action = '/feed';
        }
        // var_dump($payload);exit;
        if ($payload) {
            $page_id = $request['page_id'];
            $access_token = $request['page_token'];;

            $response = $this->fb_api->post(
                '/' . $page_id . $action,
                $payload,
                $access_token
            );

            if ($response->getHttpStatusCode() == 200) {
                return response()->json([
                    'response' => 'Postagem realizada'
                ], 200);
            }
        }

        return response()->json([
            'response' => 'Postagem não realizada'
        ], 406);
    }
}
